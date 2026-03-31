<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\Holiday;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Carbon;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Admin sees all, but can filter by user/month/year
        // Regular User: See only own leaves + calculate balances
        $leaves_query = ($user->email === 'admin@admin.com') ? Leave::with('user') : Leave::where('user_id', $user->id);
        
        if ($request->has('user_id') && $user->email === 'admin@admin.com') {
            $leaves_query->where('user_id', $request->user_id);
        }
        if ($request->has('month')) {
            $leaves_query->whereMonth('start_date', $request->month);
        }
        if ($request->has('year')) {
            $leaves_query->whereYear('start_date', $request->year);
        }

        $leaves = (clone $leaves_query)->latest()->paginate(10);

        if ($user->email === 'admin@admin.com') {
            return view('leaves.index', compact('leaves'));
        }

        // Calculate Balances for Current Year (Only for Regular Users)
        $approved_leaves = (clone $leaves_query)->where('status', 'Approved')
                            ->whereYear('start_date', date('Y'))
                            ->get();

        $balances = [
            'Sick Leave' => ['used' => 0, 'limit' => 21],
            'Casual Leave' => ['used' => 0, 'limit' => 24],
            'Total' => ['used' => 0, 'limit' => 45],
        ];

        foreach ($approved_leaves as $leave) {
            if (isset($balances[$leave->leave_type])) {
                $balances[$leave->leave_type]['used'] += (float)$leave->requested_days;
            }
        }

        $balances['Total']['used'] = $balances['Sick Leave']['used'] + $balances['Casual Leave']['used'];

        // Upcoming Birthdays (Next 7 Days)
        $upcomingBirthdays = User::whereNotNull('birthday')
            ->where(function($q) {
                $start = Carbon::today();
                $end   = Carbon::today()->addDays(7);
                
                if ($start->year === $end->year) {
                    $q->whereRaw("DATE_FORMAT(birthday, '%m-%d') BETWEEN ? AND ?", [
                        $start->format('m-d'),
                        $end->format('m-d')
                    ]);
                } else {
                    $q->whereRaw("DATE_FORMAT(birthday, '%m-%d') BETWEEN ? AND '12-31'", [$start->format('m-d')])
                      ->orWhereRaw("DATE_FORMAT(birthday, '%m-%d') BETWEEN '01-01' AND ?", [$end->format('m-d')]);
                }
            })
            ->orderByRaw("DATE_FORMAT(birthday, '%m-%d') ASC")
            ->get();

        return view('leaves.index', compact('leaves', 'balances', 'upcomingBirthdays'));
    }

    public function create()
    {
        $holidayDates = Holiday::pluck('date')->toArray();
        return view('leaves.create', compact('holidayDates'));
    }

    public function edit(Leave $leave)
    {
        // Users can only edit their own leaves if they are still Pending
        if (Auth::id() !== $leave->user_id || $leave->status !== 'Pending') {
            abort(403);
        }

        $holidayDates = Holiday::pluck('date')->toArray();
        return view('leaves.edit', compact('leave', 'holidayDates'));
    }

    private function calculateBusinessDays($startDate, $endDate)
    {
        $start = \Carbon\Carbon::parse($startDate);
        $end = \Carbon\Carbon::parse($endDate);
        if ($start->equalTo($end)) {
            return 0.5;
        }

        $holidays = Holiday::pluck('date')->toArray();
        $businessDays = 0;

        for ($date = $start->copy(); $date->lt($end); $date->addDay()) {
            if (!$date->isWeekend() && !in_array($date->toDateString(), $holidays)) {
                $businessDays++;
            }
        }

        return $businessDays;
    }

    public function update(Request $request, Leave $leave)
    {
        // Users can only update if still Pending
        if (Auth::id() !== $leave->user_id || $leave->status !== 'Pending') {
            abort(403);
        }

        $request->validate([
            'leave_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'requested_days' => 'required|numeric|min:0.5',
            'reason' => 'required|string',
        ]);

        $start_date = \Carbon\Carbon::parse($request->start_date);
        $new_duration = (float)$request->requested_days;

        // Server-side check for business days
        $calculatedDays = $this->calculateBusinessDays($request->start_date, $request->end_date);
        if ($new_duration > $calculatedDays && $new_duration != 0.5) {
             $new_duration = $calculatedDays; 
        }

        // Quota check
        $limits = [
            'Sick Leave' => 21,
            'Casual Leave' => 24,
            'Duty Leave' => null,
            'Annual Leave' => 45,
        ];

        if (array_key_exists($request->leave_type, $limits) && $limits[$request->leave_type] !== null) {
            $used_days = Leave::where('user_id', Auth::id())
                        ->where('status', 'Approved')
                        ->where('id', '!=', $leave->id)
                        ->whereYear('start_date', $start_date->year)
                        ->get();

            if ($request->leave_type === 'Annual Leave' || $request->leave_type === 'Sick Leave' || $request->leave_type === 'Casual Leave') {
                $total_annual_used = $used_days->whereIn('leave_type', ['Sick Leave', 'Casual Leave', 'Annual Leave'])->sum('requested_days');
                
                if ($request->leave_type === 'Sick Leave') {
                    $sick_used = $used_days->where('leave_type', 'Sick Leave')->sum('requested_days');
                    if (($sick_used + $new_duration) > 21) {
                        return back()->withErrors(['leave_type' => "ඔබගේ වාර්ෂික අසනීප නිවාඩු සීමාව (21) ඉක්මවා ඇත."])->withInput();
                    }
                }
                if ($request->leave_type === 'Casual Leave') {
                    $casual_used = $used_days->where('leave_type', 'Casual Leave')->sum('requested_days');
                    if (($casual_used + $new_duration) > 24) {
                        return back()->withErrors(['leave_type' => "ඔබගේ වාර්ෂික අනියම් නිවාඩු සීමාව (24) ඉක්මවා ඇත."])->withInput();
                    }
                }
                if (($total_annual_used + $new_duration) > 45) {
                    return back()->withErrors(['leave_type' => "ඔබගේ සමස්ත වාර්ෂික නිවාඩු සීමාව (45) ඉක්මවා ඇත."])->withInput();
                }
            }
        }

        $leave->update([
            'leave_type' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'duration' => $start_date->diffInDays(\Carbon\Carbon::parse($request->end_date)),
            'requested_days' => $new_duration,
            'reason' => $request->reason,
        ]);

        return redirect()->route('leaves.index')->with('success', 'නිවාඩු අයදුම්පත සාර්ථකව යාවත්කාලීන කරන ලදී!');
    }

    public function store(Request $request)
    {
        $request->validate([
            'leave_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'requested_days' => 'required|numeric|min:0.5',
            'reason' => 'required|string',
        ]);

        $start_date = \Carbon\Carbon::parse($request->start_date);
        $new_duration = (float)$request->requested_days;

        // Server-side check for business days
        $calculatedDays = $this->calculateBusinessDays($request->start_date, $request->end_date);
        if ($new_duration > $calculatedDays && $new_duration != 0.5) {
             $new_duration = $calculatedDays; 
        }

        // Quota check
        $limits = [
            'Sick Leave' => 21,
            'Casual Leave' => 24,
            'Duty Leave' => null,
            'Annual Leave' => 45,
        ];

        if (array_key_exists($request->leave_type, $limits) && $limits[$request->leave_type] !== null) {
            $used_days = Leave::where('user_id', Auth::id())
                        ->where('status', 'Approved')
                        ->whereYear('start_date', $start_date->year)
                        ->get();

            if ($request->leave_type === 'Annual Leave' || $request->leave_type === 'Sick Leave' || $request->leave_type === 'Casual Leave') {
                $total_annual_used = $used_days->whereIn('leave_type', ['Sick Leave', 'Casual Leave', 'Annual Leave'])->sum('requested_days');
                
                if ($request->leave_type === 'Sick Leave') {
                    $sick_used = $used_days->where('leave_type', 'Sick Leave')->sum('requested_days');
                    if (($sick_used + $new_duration) > 21) {
                        return back()->withErrors(['leave_type' => "ඔබගේ වාර්ෂික අසනීප නිවාඩු සීමාව (21) ඉක්මවා ඇත."])->withInput();
                    }
                }
                if ($request->leave_type === 'Casual Leave') {
                    $casual_used = $used_days->where('leave_type', 'Casual Leave')->sum('requested_days');
                    if (($casual_used + $new_duration) > 24) {
                        return back()->withErrors(['leave_type' => "ඔබගේ වාර්ෂික අනියම් නිවාඩු සීමාව (24) ඉක්මවා ඇත."])->withInput();
                    }
                }

                if (($total_annual_used + $new_duration) > 45) {
                    return back()->withErrors(['leave_type' => "ඔබගේ සමස්ත වාර්ෂික නිවාඩු සීමාව (45) ඉක්මවා ඇත."])->withInput();
                }
            }
        }

        Leave::create([
            'user_id' => Auth::id(),
            'leave_type' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'duration' => $start_date->diffInDays(\Carbon\Carbon::parse($request->end_date)),
            'requested_days' => $new_duration,
            'reason' => $request->reason,
            'status' => 'Pending',
        ]);

        return redirect()->route('leaves.index')->with('success', 'නිවාඩු අයදුම්පත සාර්ථකව ඉදිරිපත් කරන ලදී!');
    }

    public function updateStatus(Request $request, Leave $leave)
    {
        if (Auth::user()->email !== 'admin@admin.com') {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:Approved,Rejected',
        ]);

        $leave->update(['status' => $request->status]);

        return redirect()->route('leaves.index')->with('success', 'නිවාඩුව යාවත්කාලීන කරන ලදී.');
    }

    public function cancel(Leave $leave)
    {
        if (Auth::id() !== $leave->user_id) {
            abort(403);
        }

        if (\Carbon\Carbon::parse($leave->start_date)->isPast()) {
            return back()->with('error', 'ආරම්භ වී ඇති නිවාඩුවක් අවලංගු කළ නොහැක.');
        }

        $leave->delete();
        return redirect()->route('leaves.index')->with('success', 'නිවාඩුව අවලංගු කරන ලදී.');
    }

    public function destroy(Leave $leave)
    {
        if (Auth::user()->email !== 'admin@admin.com') {
            abort(403);
        }

        $leave->delete();
        return back()->with('success', 'නිවාඩු වාර්තාව මකා දමන ලදී.');
    }

    public function summary(Request $request)
    {
        if (Auth::user()->email !== 'admin@admin.com') {
            abort(403);
        }

        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));

        $users = \App\Models\User::with(['leaves' => function($query) use ($month, $year) {
            $query->where('status', 'Approved')
                  ->whereMonth('start_date', $month)
                  ->whereYear('start_date', $year);
        }])->where('role', '!=', 'admin')->get();

        $summaryData = $users->map(function($user) {
            $sick = (float)$user->leaves->where('leave_type', 'Sick Leave')->sum('requested_days');
            $casual = (float)$user->leaves->where('leave_type', 'Casual Leave')->sum('requested_days');
            $duty = (float)$user->leaves->where('leave_type', 'Duty Leave')->sum('requested_days');
            $annual = (float)$sick + $casual;

            return [
                'user_id' => $user->id,
                'name' => $user->full_name ?? $user->name,
                'employee_id' => $user->id,
                'sick' => $sick,
                'casual' => $casual,
                'duty' => $duty,
                'annual' => $annual,
            ];
        });

        return view('leaves.summary', compact('summaryData', 'month', 'year'));
    }

    public function exportPdf(Request $request)
    {
        if (Auth::user()->email !== 'admin@admin.com') {
            abort(403);
        }

        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));

        $users = \App\Models\User::with(['leaves' => function($query) use ($month, $year) {
            $query->where('status', 'Approved')
                  ->whereMonth('start_date', $month)
                  ->whereYear('start_date', $year);
        }])->where('role', '!=', 'admin')->get();

        $summaryData = $users->map(function($user) {
            $sick = (float)$user->leaves->where('leave_type', 'Sick Leave')->sum('requested_days');
            $casual = (float)$user->leaves->where('leave_type', 'Casual Leave')->sum('requested_days');
            $duty = (float)$user->leaves->where('leave_type', 'Duty Leave')->sum('requested_days');
            $annual = (float)$sick + $casual;

            return [
                'user_id' => $user->id,
                'name' => $user->full_name ?? $user->name,
                'employee_id' => $user->id,
                'sick' => $sick,
                'casual' => $casual,
                'duty' => $duty,
                'annual' => $annual,
            ];
        });

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('leaves.summary_pdf', compact('summaryData', 'month', 'year'));
        return $pdf->download("leave_summary_{$year}_{$month}.pdf");
    }

    public function exportExcel(Request $request)
    {
        if (Auth::user()->email !== 'admin@admin.com') {
            abort(403);
        }

        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));

        $users = \App\Models\User::with(['leaves' => function($query) use ($month, $year) {
            $query->where('status', 'Approved')
                  ->whereMonth('start_date', $month)
                  ->whereYear('start_date', $year);
        }])->where('role', '!=', 'admin')->get();

        $fileName = "leave_summary_{$year}_{$month}.csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Employee Name', 'Employee ID', 'Sick Leave (Used)', 'Casual Leave (Used)', 'Annual Leave (Used/45)', 'Duty Leave'];

        $callback = function() use($users, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($users as $user) {
                $sick = (float)$user->leaves->where('leave_type', 'Sick Leave')->sum('requested_days');
                $casual = (float)$user->leaves->where('leave_type', 'Casual Leave')->sum('requested_days');
                $duty = (float)$user->leaves->where('leave_type', 'Duty Leave')->sum('requested_days');
                $annual = (float)$sick + $casual;

                fputcsv($file, [
                    $user->full_name ?? $user->name,
                    $user->id,
                    $sick,
                    $casual,
                    "$annual/45",
                    $duty
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Generate official Leave Application PDF for a specific leave request.
     */
    public function downloadApplicationPDF(Leave $leave)
    {
        $user = Auth::user();

        // Security check: Only owner or admin
        if ($user->id !== $leave->user_id && $user->email !== 'admin@admin.com') {
            abort(403, 'Unauthorized access to leave application.');
        }

        $applicant = $leave->user;
        
        // Return to work date calculation
        $return_date = $this->getNextWorkingDay($leave->end_date);

        // Calculate balances for the year of the leave
        $year = date('Y', strtotime($leave->start_date));
        $approved_leaves = Leave::where('user_id', $applicant->id)
                            ->where('status', 'Approved')
                            ->whereYear('start_date', $year)
                            ->get();

        $balances = [
            'Sick Leave' => ['used' => 0],
            'Casual Leave' => ['used' => 0],
            'Annual Leave' => ['used' => 0],
        ];

        foreach ($approved_leaves as $approved) {
            if (isset($balances[$approved->leave_type])) {
                $balances[$approved->leave_type]['used'] += (float)$approved->requested_days;
            }
        }
        $balances['Annual Leave']['used'] = $balances['Sick Leave']['used'] + $balances['Casual Leave']['used'];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('leaves.leave_application_pdf', [
            'leave' => $leave,
            'user' => $applicant,
            'return_date' => $return_date,
            'balances' => $balances
        ]);

        return $pdf->download("Leave_Application_{$applicant->name}_{$leave->start_date}.pdf");
    }

    /**
     * Generate comprehensive Annual Leave Summary PDF for the current user.
     */
    public function downloadMyLeaveSummaryPDF()
    {
        $user = Auth::user();
        $year = date('Y');

        // Admin cannot download their own summary as they don't have leave records in the same way
        if ($user->email === 'admin@admin.com') {
            return back()->with('error', 'Admin summary is managed through the Master Dashboard.');
        }

        // Fetch all leaves for the current user for the current year
        $leaves = Leave::where('user_id', $user->id)
                    ->whereYear('start_date', $year)
                    ->orderBy('start_date', 'asc')
                    ->get();

        // Calculate balances
        $approved_leaves = $leaves->where('status', 'Approved');
        
        $balances = [
            'Sick Leave' => ['used' => 0],
            'Casual Leave' => ['used' => 0],
            'Annual Leave' => ['used' => 0],
        ];

        foreach ($approved_leaves as $approved) {
            if (isset($balances[$approved->leave_type])) {
                $balances[$approved->leave_type]['used'] += (float)$approved->requested_days;
            }
        }
        $balances['Annual Leave']['used'] = $balances['Sick Leave']['used'] + $balances['Casual Leave']['used'];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('leaves.my_leave_summary_pdf', [
            'user' => $user,
            'leaves' => $leaves,
            'balances' => $balances,
            'year' => $year
        ]);

        return $pdf->download("Annual_Leave_Summary_{$user->name}_{$year}.pdf");
    }

    /**
     * Generate a consolidated annual summary for all employees (Zonal Office Report).
     */
    public function downloadZonalAnnualSummaryPDF()
    {
        if (auth()->user()->email !== 'admin@admin.com') {
            abort(403);
        }

        $year = date('Y');
        $users = \App\Models\User::where('role', '!=', 'admin')->get();
        
        $reportData = [];

        foreach ($users as $user) {
            $approved_leaves = Leave::where('user_id', $user->id)
                                ->where('status', 'Approved')
                                ->whereYear('start_date', $year)
                                ->get();

            $sick = (float)$approved_leaves->where('leave_type', 'Sick Leave')->sum('requested_days');
            $casual = (float)$approved_leaves->where('leave_type', 'Casual Leave')->sum('requested_days');
            
            $reportData[] = [
                'name' => $user->full_name ?? $user->name,
                'nic' => $user->nic_number ?? 'N/A',
                'designation' => $user->designation ?? 'N/A',
                'sick' => $sick,
                'casual' => $casual,
                'annual' => $sick + $casual
            ];
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('leaves.zonal_annual_summary_pdf', [
            'reportData' => $reportData,
            'year' => $year
        ])->setPaper('a4', 'landscape');

        return $pdf->download("Zonal_Annual_Summary_{$year}.pdf");
    }

    /**
     * Get the next business day after the given date (skipping weekends and holidays).
     */
    private function getNextWorkingDay($date)
    {
        $date = \Carbon\Carbon::parse($date)->addDay();
        $holidays = \App\Models\Holiday::pluck('date')->toArray();

        while ($date->isWeekend() || in_array($date->toDateString(), $holidays)) {
            $date->addDay();
        }

        return $date->toDateString();
    }
}


