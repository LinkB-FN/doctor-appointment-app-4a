<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class NotificationsCheck extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'doctor:notifications-check';

    /**
     * The console command description.
     */
    protected $description = 'Checks queue, mail and Twilio WhatsApp configuration for appointment notifications.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('=== Notifications Preflight Check ===');

        $queueConnection = config('queue.default');
        $mailMailer = config('mail.default');

        $this->line("Queue connection: {$queueConnection}");
        $this->line("Mail mailer: {$mailMailer}");

        $this->newLine();
        $this->info('Twilio config:');
        $this->line('TWILIO_SID: ' . ($this->masked(config('services.twilio.sid'))));
        $this->line('TWILIO_TOKEN: ' . ($this->masked(config('services.twilio.token'))));
        $this->line('TWILIO_WHATSAPP_FROM: ' . (config('services.twilio.whatsapp_from') ?: 'MISSING'));

        $this->newLine();
        $jobsTableExists = $this->tableExists('jobs');
        $failedJobsTableExists = $this->tableExists('failed_jobs');

        $this->line('jobs table: ' . ($jobsTableExists ? 'OK' : 'MISSING'));
        $this->line('failed_jobs table: ' . ($failedJobsTableExists ? 'OK' : 'MISSING'));

        if ($jobsTableExists) {
            $pending = DB::table('jobs')->count();
            $this->line("Pending jobs: {$pending}");
        }

        if ($failedJobsTableExists) {
            $failed = DB::table('failed_jobs')->count();
            $this->line("Failed jobs: {$failed}");
        }

        $this->newLine();
        if ($mailMailer === 'log') {
            $this->warn('MAIL_MAILER is "log": emails are NOT delivered, only written to logs.');
        }

        if (empty(config('services.twilio.sid')) || empty(config('services.twilio.token')) || empty(config('services.twilio.whatsapp_from'))) {
            $this->warn('Twilio config is incomplete: WhatsApp sends will fail.');
        }

        if ($queueConnection !== 'sync') {
            $this->warn('Queue is async. Ensure a worker is running: php artisan queue:work');
        }

        $this->info('=== Check complete ===');

        return self::SUCCESS;
    }

    private function tableExists(string $table): bool
    {
        try {
            return DB::getSchemaBuilder()->hasTable($table);
        } catch (\Throwable) {
            return false;
        }
    }

    private function masked(?string $value): string
    {
        if (empty($value)) {
            return 'MISSING';
        }

        $len = strlen($value);

        if ($len <= 6) {
            return str_repeat('*', $len);
        }

        return substr($value, 0, 3) . str_repeat('*', $len - 6) . substr($value, -3);
    }
}
