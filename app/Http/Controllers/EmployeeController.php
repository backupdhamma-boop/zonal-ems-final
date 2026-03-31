<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Leave;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use App\Exports\UsersExport;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $admin = auth()->user();

        if ($admin->email === 'admin@admin.com') {
            $query = User::where('role', 'user');

            if ($search = $request->get('search')) {
                $query->where(function($q) use ($search) {
                    $q->where('full_name', 'LIKE', "%{$search}%")
                      ->orWhere('nic_number', 'LIKE', "%{$search}%");
                });
            }

            if ($request->filled('designation')) {
                $query->where('designation', $request->designation);
            }

            if ($request->filled('section')) {
                $query->where('section', $request->section);
            }

            $employees = $query->orderBy('full_name', 'asc')->paginate(40);

            $totalEmployees  = User::where('role', 'user')->count();
            $pendingApprovals = Leave::where('status', 'Pending')->count();

            $today    = Carbon::today();
            $tomorrow = Carbon::tomorrow();

            $onLeaveToday = Leave::where('status', 'Approved')
                                ->whereDate('start_date', '<=', $today)
                                ->whereDate('end_date', '>=', $today)
                                ->with('user')
                                ->get();

            $onLeaveTomorrow = Leave::where('status', 'Approved')
                                ->whereDate('start_date', '<=', $tomorrow)
                                ->whereDate('end_date', '>=', $tomorrow)
                                ->with('user')
                                ->get();

            $designations = User::whereNotNull('designation')->where('designation', '!=', '')->distinct()->pluck('designation');
            $sections     = User::whereNotNull('section')->where('section', '!=', '')->distinct()->pluck('section');

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

            return view('home', compact(
                'employees',
                'totalEmployees',
                'pendingApprovals',
                'onLeaveToday',
                'onLeaveTomorrow',
                'designations',
                'sections',
                'upcomingBirthdays'
            ));
        }

        return redirect()->route('leaves.index');
    }

    public function editSelf()
    {
        $employee = auth()->user();
        return view('update', compact('employee'));
    }

    public function exportExcel()
    {
        return Excel::download(new \App\Exports\EmployeesExport, 'employees.xlsx');
    }

    public function downloadAllPDF()
    {
        $employees = User::where('role', '!=', 'admin')->get();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.employees', compact('employees'));
        return $pdf->download('employees.pdf');
    }

    public function downloadPDF($id)
    {
        $employee = User::findOrFail($id);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.service_record', compact('employee'));
        
        $fileName = 'Service_Record_' . str_replace([' ', '/', '\\'], '_', $employee->full_name ?? $employee->name) . '.pdf';
        return $pdf->download($fileName);
    }

    /**
     * Download the currently authenticated employee's service record as a PDF.
     */
    public function downloadMyPDF()
    {
        $employee = auth()->user();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.service_record', compact('employee'));
        
        $fileName = 'My_Service_Record_' . str_replace([' ', '/', '\\'], '_', $employee->full_name ?? $employee->name) . '.pdf';
        return $pdf->download($fileName);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // SHARED VALIDATION RULES
    // ─────────────────────────────────────────────────────────────────────────
    private function validationRules(bool $isCreate = true, ?int $userId = null): array
    {
        $emailRule = $isCreate
            ? 'nullable|string|email|max:255|unique:users,email'
            : 'nullable|string|email|max:255|unique:users,email,' . $userId;

        return [
            // Core
            'full_name'               => 'nullable|string|max:255',
            'email'                   => $emailRule,
            'password'                => 'nullable|string|min:8',
            'photo'                   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:3072',

            // Personal
            'full_name_with_initials' => 'nullable|string|max:255',
            'nic_number'              => ['nullable', 'string', 'regex:/^([0-9]{9}[vVxX]|[0-9]{12})$/'],
            'race_religion'           => 'nullable|string|max:255',
            'marital_status'          => 'nullable|in:Single,Married,Divorced,Widowed',
            'birthday'                => 'nullable|date|before:today',
            'address'                 => 'nullable|string',
            'permanent_address'       => 'nullable|string',

            // Contact
            'phone_number'            => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s]{7,15}$/'],
            'mobile_no'               => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s]{7,15}$/'],
            'whatsapp_no'             => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s]{7,15}$/'],

            // Professional (admin-only)
            'designation'             => 'nullable|string|max:255',
            'grade'                   => 'nullable|string|max:255',
            'section'                 => 'nullable|string|max:255',
            'workplace'               => 'nullable|string|max:255',
            'wop_no'                  => 'nullable|string|max:100',
            'salary'                  => 'nullable|numeric|min:0',
            'appointment_date'        => 'nullable|date',
            'assumed_duty_date'       => 'nullable|date',

            // Service & Career
            'service_history'         => 'nullable|string',
            'current_office_details'  => 'nullable|string',
            'confirmation_details'    => 'nullable|string',
            'eb_exams'                => 'nullable|string',

            // Qualifications
            'edu_qualifications'      => 'nullable|string',
            'prof_qualifications'     => 'nullable|string',
            'trainings'               => 'nullable|string',
        ];
    }

    private function validationMessages(): array
    {
        return [
            'nic_number.regex'   => 'NIC must be in the format 123456789V or 200012345678.',
            'mobile_no.regex'    => 'Mobile number must be a valid phone number (7-15 digits).',
            'whatsapp_no.regex'  => 'WhatsApp number must be a valid phone number (7-15 digits).',
            'phone_number.regex' => 'Phone number must be a valid phone number (7-15 digits).',
            'birthday.before'    => 'Birthday must be a past date.',
            'marital_status.in'  => 'Marital status must be one of: Single, Married, Divorced, Widowed.',
        ];
    }

    private function buildUserData(Request $request, array $validated, ?User $existing = null): array
    {
        // Safe fallbacks if Admin completely blanks out the "nullable" form
        $email = !empty($validated['email']) ? $validated['email'] : ($existing ? $existing->email : 'emp_' . uniqid() . '@office.com');
        $fullName = !empty($validated['full_name']) ? $validated['full_name'] : ($existing ? $existing->full_name : 'Staff Member');

        $data = [
            'name'                    => $email, // Auto-Username: Maps Email to Username
            'full_name'               => $fullName,
            'full_name_with_initials' => $request->full_name_with_initials,
            'email'                   => $email,
            'designation'             => $validated['designation'] ?? ($existing ? $existing->designation : null),
            'grade'                   => $request->grade,
            'section'                 => $request->section,
            'salary'                  => $validated['salary'] ?? ($existing ? $existing->salary : null),
            'workplace'               => $request->workplace,
            'wop_no'                  => $request->wop_no,
            'nic_number'              => $request->nic_number,
            'race_religion'           => $request->race_religion,
            'marital_status'          => $request->marital_status,
            'birthday'                => $request->birthday ?: null,
            'phone_number'            => $request->phone_number,
            'mobile_no'               => $request->mobile_no,
            'whatsapp_no'             => $request->whatsapp_no,
            'address'                 => $request->address,
            'permanent_address'       => $request->permanent_address,
            'appointment_date'        => $request->appointment_date ?: null,
            'assumed_duty_date'       => $request->assumed_duty_date ?: null,
            'service_history'         => $request->service_history,
            'current_office_details'  => $request->current_office_details,
            'confirmation_details'    => $request->confirmation_details,
            'eb_exams'                => $request->eb_exams,
            'edu_qualifications'      => $request->edu_qualifications,
            'prof_qualifications'     => $request->prof_qualifications,
            'trainings'               => $request->trainings,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('photo')) {
            // Delete old photo if updating
            if ($existing && $existing->profile_photo_path) {
                Storage::disk('public')->delete($existing->profile_photo_path);
            }
            $data['profile_photo_path'] = $request->file('photo')->store('profile-photos', 'public');
        }

        return $data;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // STORE (Create New Employee)
    // ─────────────────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate(
            $this->validationRules(true),
            $this->validationMessages()
        );

        DB::transaction(function () use ($request, $validated) {
            $password = $request->filled('password') ? $request->password : '12345678';
            $data = $this->buildUserData($request, $validated);
            $data['password'] = Hash::make($password);
            $data['role'] = 'user';
            User::create($data);
        });

        return redirect('/')->with('success', 'Employee created successfully!');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // EDIT (Show Edit Form)
    // ─────────────────────────────────────────────────────────────────────────
    public function edit($id)
    {
        $employee = User::findOrFail($id);
        return view('update', compact('employee'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // UPDATE (Save Edit)
    // ─────────────────────────────────────────────────────────────────────────
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $isAdmin = auth()->user()->email === 'admin@admin.com';

        $validated = $request->validate(
            $this->validationRules(false, $user->id),
            $this->validationMessages()
        );

        DB::transaction(function () use ($request, $user, $validated, $isAdmin) {
            $data = $this->buildUserData($request, $validated, $user);

            if (!$isAdmin) {
                // Regular users cannot update these admin-only identity/financial fields
                unset($data['email']);
                unset($data['nic_number']);
                unset($data['salary']);
            }

            $user->update($data);
        });

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully!',
                'photo'   => $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : null
            ]);
        }

        return redirect('/')->with('success', 'Employee profile updated successfully!');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // DESTROY
    // ─────────────────────────────────────────────────────────────────────────
    public function destroy($id)
    {
        if (auth()->user()->email !== 'admin@admin.com') abort(403);
        $user = User::findOrFail($id);
        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }
        $user->delete();
        return redirect('/')->with('success', 'Employee account deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        if (auth()->user()->email !== 'admin@admin.com') abort(403);
        
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:users,id'
        ]);

        $ids = $request->ids;
        // Safety: Do not delete admin accounts during bulk delete
        $deletedCount = User::whereIn('id', $ids)
            ->where('role', '!=', 'admin')
            ->where('email', '!=', 'admin@admin.com')
            ->delete();

        return response()->json([
            'success' => true,
            'message' => "Successfully removed {$deletedCount} staff records."
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // GET DETAILS (AJAX - Admin modal)
    // ─────────────────────────────────────────────────────────────────────────
    public function getDetails(User $user)
    {
        if (auth()->user()->email !== 'admin@admin.com') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $year = date('Y');
        $approved_leaves = Leave::where('user_id', $user->id)
                            ->where('status', 'Approved')
                            ->whereYear('start_date', $year)
                            ->get();

        $balances = [
            'sick'   => (float)$approved_leaves->where('leave_type', 'Sick Leave')->sum('requested_days'),
            'casual' => (float)$approved_leaves->where('leave_type', 'Casual Leave')->sum('requested_days'),
        ];
        $balances['annual'] = $balances['sick'] + $balances['casual'];

        return response()->json([
            'id'                      => $user->id,
            'full_name'               => $user->full_name ?? $user->name,
            'full_name_with_initials' => $user->full_name_with_initials ?? 'N/A',
            'email'                   => $user->email,
            'nic_number'              => $user->nic_number ?? 'N/A',
            'designation'             => $user->designation ?? 'N/A',
            'grade'                   => $user->grade ?? 'N/A',
            'section'                 => $user->section ?? 'N/A',
            'workplace'               => $user->workplace ?? 'N/A',
            'wop_no'                  => $user->wop_no ?? 'N/A',
            'appointment_date'        => $user->appointment_date ? $user->appointment_date->format('Y-m-d') : 'N/A',
            'phone_number'            => $user->phone_number ?? 'N/A',
            'mobile_no'               => $user->mobile_no ?? 'N/A',
            'whatsapp_no'             => $user->whatsapp_no ?? 'N/A',
            'address'                 => $user->address ?? 'N/A',
            'birthday'                => $user->birthday ? $user->birthday->format('Y-m-d') : 'N/A',
            'age'                     => $user->age ?? 'N/A',
            'marital_status'          => $user->marital_status ?? 'N/A',
            'service_history'         => $user->service_history ?? 'N/A',
            'eb_exams'                => $user->eb_exams ?? 'N/A',
            'edu_qualifications'      => $user->edu_qualifications ?? 'N/A',
            'prof_qualifications'     => $user->prof_qualifications ?? 'N/A',
            'photo'                   => $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : null,
            'balances'                => $balances,
            'edit_url'                => route('employee.edit', $user->id),
            'delete_url'              => route('employee.destroy', $user->id),
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // IMPORT / EXPORT / TEMPLATE
    // ─────────────────────────────────────────────────────────────────────────
    public function importUsers(Request $request)
    {
        if (auth()->user()->email !== 'admin@admin.com') abort(403);
        $request->validate(['file' => 'required|mimes:xlsx,xls,csv']);
        
        \Log::info('Staff Import Started: ' . $request->file('file')->getClientOriginalName());

        try {
            DB::beginTransaction();
            Excel::import(new UsersImport, $request->file('file'));
            DB::commit();
            
            \Log::info('Staff Import Completed Successfully. New User Count: ' . User::count());
            return redirect()->back()->with('success', 'Staff imported successfully! Current Total: ' . User::count());
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Staff Import FAILED: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Critical Error: ' . $e->getMessage());
        }
    }

    public function exportUsers()
    {
        if (auth()->user()->email !== 'admin@admin.com') abort(403);
        return Excel::download(new UsersExport, 'staff_data_' . date('Y-m-d') . '.xlsx');
    }

    public function downloadImportTemplate()
    {
        if (auth()->user()->email !== 'admin@admin.com') abort(403);
        $callback = function() {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Name', 'Email', 'Phone']); // Minimal 3-column header
            fputcsv($file, ['John Doe', 'john.doe@ems.com', '0771234567']); // Sample data
            fclose($file);
        };
        return response()->streamDownload($callback, 'staff_import_template_v2.csv', ['Content-Type' => 'text/csv']);
    }
}
