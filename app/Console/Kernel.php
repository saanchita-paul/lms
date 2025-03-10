<?php

namespace App\Console;

use App\Console\Commands\AppointmentReminderCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\CrmStatus;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
        Commands\DailyReports::class,
        Commands\Automation::class,
        Commands\PatientJourneyCampaign::class,
        Commands\CreateMailCampaign::class,
        Commands\NexhealthTokenStore::class,
        Commands\AutomationCampaignNewlead::class,
        Commands\WeeklyRoundMailCampaign::class,
        Commands\AppointmentReminderCommand::class,
        Commands\UpdateCallRailDetails::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        // $schedule->command('reports:daily')->dailyAt('10:00');
        // $schedule->command('automation:daily')->weekdays()->timezone('America/New_York')->at('14:00');
        // $schedule->command('patientjourney:daily')->everyTwoHours()->timezone('America/New_York');
        // $schedule->command('mailcampaign:daily')->weeklyOn(2, '14:00')->timezone('America/New_York');
        // $schedule->command('mailcampaign:weekly')->weeklyOn(4,'14:00')->timezone('America/New_York');
        $schedule->command('callrail:update-details')->everyFiveMinutes();
        $scheduleDay = env('WEEKLY_EMAIL_SCHEDULE_DAY');
        $scheduleTime = env('WEEKLY_EMAIL_SCHEDULE_TIME');
        $scheduleMethod = $scheduleDay . (substr($scheduleDay, -1) !== 's' ? 's' : '');
        $schedule->command('email:active-users')->weekly()->{$scheduleMethod}()->at($scheduleTime);
        $schedule->command('nexhealth-token-store:hour')->hourly();
        $schedule->command('telescope:prune')->daily();
        $schedule->command('customers:appointment-reminder')->everyMinute();
        $statuses = CrmStatus::withTrashed()->pluck('id');

        foreach ($statuses as $statusId) {
       //     $schedule->command('automation:schedule ' . $statusId)->weekdays()->timezone('America/New_York')->at('14:00');
        }

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
