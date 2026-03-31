<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SyncRequestedDays extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-requested-days';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Syncs requested_days from duration for existing records';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $leaves = \App\Models\Leave::whereNull('requested_days')->get();
        $count = 0;

        foreach ($leaves as $leave) {
            $leave->update(['requested_days' => $leave->duration]);
            $count++;
        }

        $this->info("Successfully synced requested_days for {$count} records.");
        return Command::SUCCESS;
    }
}
