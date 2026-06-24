<?php
declare(strict_types=1);

namespace App\Console;

use App\Console\Commands\GenerateSitemap;
use App\Console\Commands\ManageTasks;
use App\Console\Commands\SpecialFixes;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

/**
 * Class Kernel
 * @package App\Console
 */
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        ManageTasks::class,
        GenerateSitemap::class,
        SpecialFixes::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $date = date('Y-m');

        $schedule->command('task assign-interests')
                 ->everyFiveMinutes()
                 ->appendOutputTo(storage_path('logs'.DS.'tasks'.DS.$date.'.log'));

        $schedule->command('task release-bonuses')
                 ->everyFiveMinutes()
                 ->appendOutputTo(storage_path('logs'.DS.'tasks'.DS.$date.'.log'));

        $schedule->command('task cancel-invoices')
                 ->hourlyAt(2)
                 ->appendOutputTo(storage_path('logs'.DS.'tasks'.DS.$date.'.log'));

        $schedule->command('task send-invoice-reminders')
                 ->hourlyAt(3)
                 ->appendOutputTo(storage_path('logs'.DS.'tasks'.DS.$date.'.log'));

        $schedule->command('sitemap cms.home')
                 ->monthlyOn('1', '08:01')
                 ->appendOutputTo(storage_path('logs'.DS.'tasks'.DS.$date.'.log'));
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        parent::commands();
        require base_path('routes/console.php');
    }
}
