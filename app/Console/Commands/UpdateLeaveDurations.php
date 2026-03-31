<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateLeaveDurations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-leave-durations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the duration column for all existing leave records';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $leaves = \App\Models\Leave::all();
        $count = 0;

        foreach ($leaves as $leave) {
            $start = \Carbon\Carbon::parse($leave->start_date);
            $end = \Carbon\Carbon::parse($leave->end_date);
            $duration = $start->diffInDays($end) + 1;

            $leave->update(['duration' => $duration]);
            $count++;
        }

        $this->info("Successfully updated {$count} leave records.");
        return Command::SUCCESS;
    }
}
