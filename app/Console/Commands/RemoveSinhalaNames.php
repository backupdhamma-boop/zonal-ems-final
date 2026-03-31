<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class RemoveSinhalaNames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove:sinhala-names';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Permanently remove all staff users whose names contain Sinhala script characters.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("Scanning for records with Sinhala script names or corrupted question-mark strings...");
        
        $count = 0;
        // The Sinhala Unicode range: \u0D80-\u0DFF
        $sinhalaPattern = '/[\x{0D80}-\x{0DFF}]/u';
        // Match corrupted strings like '??.???.??' or '???'
        $corruptPattern = '/[\?]{2,}/'; 

        // We use chunking to prevent memory issues if the database grows
        User::where('role', '!=', 'admin')
            ->where('email', '!=', 'admin@admin.com')
            ->chunk(100, function ($users) use ($sinhalaPattern, $corruptPattern, &$count) {
                foreach ($users as $user) {
                    $hasSinhala = preg_match($sinhalaPattern, $user->full_name) || preg_match($sinhalaPattern, $user->name);
                    $isCorrupt  = preg_match($corruptPattern, $user->full_name) || preg_match($corruptPattern, $user->name);

                    if ($hasSinhala || $isCorrupt) {
                        $this->warn("Removing User: #{$user->id} - " . ($isCorrupt ? "(Corrupted String)" : $user->full_name));
                        $user->delete();
                        $count++;
                    }
                }
            });

        if ($count > 0) {
            $this->info("Cleanup finished! Total records removed: {$count}");
        } else {
            $this->info("Search complete. No Sinhala or corrupted names were found.");
        }

        return 0;
    }
}
