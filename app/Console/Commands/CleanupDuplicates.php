<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CleanupDuplicates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cleanup:duplicates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up duplicate user records based on Email, Name, or Phone Number while keeping the latest record.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("Starting duplicate cleanup process...");
        $totalRemoved = 0;

        // Attributes to check for duplicates
        $attributes = ['email', 'full_name', 'phone_number'];

        foreach ($attributes as $attr) {
            $this->comment("Checking for duplicates in: {$attr}...");
            
            // Find counts of duplicates (ignoring null/empty)
            $duplicates = User::select($attr)
                ->whereNotNull($attr)
                ->where($attr, '!=', '')
                ->groupBy($attr)
                ->havingRaw('COUNT(*) > 1')
                ->pluck($attr);

            if ($duplicates->isEmpty()) {
                $this->line("No duplicates found for {$attr}.");
                continue;
            }

            foreach ($duplicates as $value) {
                // Get all IDs for this duplicate value, ordered by latest created_at
                $ids = User::where($attr, $value)
                    ->orderBy('created_at', 'desc')
                    ->pluck('id')
                    ->toArray();

                // Keep the first one (latest), remove the rest
                $keepId = array_shift($ids);
                $toRemove = $ids;

                if (!empty($toRemove)) {
                    $removedCount = User::whereIn('id', $toRemove)->delete();
                    $totalRemoved += $removedCount;
                    $this->info("Removed {$removedCount} duplicates for {$attr}: {$value}");
                }
            }
        }

        if ($totalRemoved > 0) {
            $this->info("Cleanup finished! Total duplicate records removed: {$totalRemoved}");
        } else {
            $this->info("Cleanup finished. No duplicate records were found.");
        }

        return 0;
    }
}
