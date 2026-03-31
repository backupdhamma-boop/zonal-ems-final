<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Imports\UsersImport;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class ImportStaff extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:staff {file=staff_data.xlsx}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import staff members from an Excel/CSV file in the project root';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $fileName = $this->argument('file');
        $filePath = base_path($fileName);

        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            $this->info("Please place your file in the project root (d:\HR System\sms-final\{$fileName})");
            return 1;
        }

        $this->info("Starting Import from: {$fileName}");
        $initialCount = User::count();

        try {
            DB::beginTransaction();
            
            Excel::import(new UsersImport, $filePath);
            
            DB::commit();

            $finalCount = User::count();
            $newUsers = $finalCount - $initialCount;

            $this->info("Import Completed Successfully!");
            $this->info("New Users Added (Created or Updated): {$newUsers}");
            $this->info("Total Users in DB: {$finalCount}");
            
            return 0;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Import Failed: " . $e->getMessage());
            return 1;
        }
    }
}
