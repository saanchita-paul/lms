<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\User; // Adjust the namespace as per your application
use Illuminate\Support\Facades\DB;
use App\Mail\ActiveUserListWeeklyEmail;

class SendWeeklyActiveUserListEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:active-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send weekly email to active users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Fetching the active user list using the SQL query
        $activeUsers = DB::select("
            SELECT users.id, users.name, users.email, role_user.role_id,
            clinic_user.clinic_id, clinics.clinic_name, clinics.status, clinics.version, users.last_login_date
            FROM users
            RIGHT JOIN role_user ON role_user.user_id = users.id
            RIGHT JOIN clinic_user ON clinic_user.user_id = users.id
            RIGHT JOIN clinics ON clinics.id = clinic_user.clinic_id
            WHERE role_id != 3 AND status = 'Active' group by users.id
            ORDER BY clinic_user.clinic_id ASC
        ");

        // Extract all user IDs
        $userIds = array_column($activeUsers, 'id');

        // Get the latest activity for all users in a single query
        $lastActivities = DB::table('audit_logs')
            ->select('user_id', DB::raw('MAX(created_at) as last_activity'))
            ->whereIn('user_id', $userIds)
            ->groupBy('user_id')
            ->pluck('last_activity', 'user_id')
            ->toArray();

        // Prepare data for the CSV attachment (assuming $activeUsers is an array of objects)
        $csvData = [];
        $index = 1;
        foreach ($activeUsers as $user) {
            $lastActivityDate = $lastActivities[$user->id] ?? null;

            $csvData[] = [
                'Sr. No' => $index,
                'Name' => $user->name,
                'Email' => $user->email,
                'ClinicName' => $user->clinic_name,
                'Version' => $user->version,
                'Status' => $user->status,
                'Last Login Date' => $user->last_login_date,
                'Last Activity Date' => $lastActivityDate
            ];
            $index++;
        }

        // CSV file creation
        $csvFileName = 'active_users_' . date('Ymd') . '.csv';
        $csvFile = fopen(storage_path('app/' . $csvFileName), 'w');
        fputcsv($csvFile, array_keys($csvData[0])); // Write CSV header
        foreach ($csvData as $row) {
            fputcsv($csvFile, $row);
        }
        fclose($csvFile);

        // Sending email to multiple recipients specified in the .env file
        $recipients = explode(',', env('WEEKLY_EMAIL_RECIPIENTS'));
        $subject = 'Weekly Active Users List';
        $attachmentPath = storage_path('app/' . $csvFileName);

        foreach ($recipients as $recipient) {
            Mail::to(trim($recipient))->send(new ActiveUserListWeeklyEmail($subject, $attachmentPath));
        }

        // Clean up - delete the generated CSV file
        unlink($attachmentPath);
    }
}
