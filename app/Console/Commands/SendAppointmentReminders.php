<?php

namespace App\Console\Commands;

use App\Jobs\SendWhatsAppReminder;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendAppointmentReminders extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'appointments:send-reminders
                            {--date= : Target date (Y-m-d). Defaults to tomorrow.}';

    /**
     * The console command description.
     */
    protected $description = 'Send WhatsApp reminder messages for appointments scheduled for tomorrow.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $targetDate = $this->option('date')
            ? Carbon::parse($this->option('date'))->toDateString()
            : Carbon::tomorrow()->toDateString();

        $this->info("Sending reminders for appointments on: {$targetDate}");

        $appointments = Appointment::with(['patient.user', 'doctor.user', 'doctor.specialty'])
            ->whereDate('date', $targetDate)
            ->where('status', 'Programado')
            ->whereNull('reminder_sent_at')
            ->get();

        if ($appointments->isEmpty()) {
            $this->info('No pending appointments found for that date.');
            return self::SUCCESS;
        }

        $count = 0;
        foreach ($appointments as $appointment) {
            $phone = $appointment->patient?->user?->phone;

            if (empty($phone)) {
                $this->warn("  - Appointment #{$appointment->id}: patient has no phone, skipping.");
                continue;
            }

            SendWhatsAppReminder::dispatch($appointment);
            $count++;

            $patientName = $appointment->patient->user->name ?? 'N/D';
            $this->line("  ✓ Reminder queued for {$patientName} (appointment #{$appointment->id})");
        }

        $this->info("Done. {$count} reminder(s) queued.");

        return self::SUCCESS;
    }
}
