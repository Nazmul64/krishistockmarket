<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AttendanceAbsentDetectionService;

class DetectAbsentEmployeesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:detect-absent {--date= : Target date in YYYY-MM-DD format} {--branch= : Branch ID to process}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically detect and record absent employees for scheduled work days without attendance';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = $this->option('date');
        $branchId = $this->option('branch');

        $this->info("Starting automatic absent calculation for date: " . ($date ?: 'today') . "...");
        $res = AttendanceAbsentDetectionService::detectAndMarkAbsent($date, $branchId);

        $this->table(
            ['Date', 'Evaluated', 'Marked Absent', 'Skipped Holiday', 'Skipped Leave', 'Already Present'],
            [
                [$res['date'], $res['total_evaluated'], $res['marked_absent'], $res['skipped_holiday'], $res['skipped_leave'], $res['already_present']]
            ]
        );

        $this->info("Absent detection completed successfully.");
        return 0;
    }
}
