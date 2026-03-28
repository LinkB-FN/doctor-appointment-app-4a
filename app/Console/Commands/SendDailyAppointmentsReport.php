<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Appointment;
use App\Mail\DailyAppointmentsReportMail;
use App\Mail\DailyAdminReportMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendDailyAppointmentsReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-daily-appointments-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily appointment reports to doctors and admin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();
        
        // Fetch today's appointments
        $appointments = Appointment::with(['patient.user', 'doctor.user'])
            ->whereDate('date', $today)
            ->get();

        if ($appointments->isEmpty()) {
            $this->info('No appointments for today.');
            return;
        }

        // Send to Admin
        $adminEmail = 'luism.berdugo@hotmail.com'; // Hardcoded per user request
        Mail::to($adminEmail)->send(new DailyAdminReportMail($appointments, $today));
        $this->info('Admin report sent to ' . $adminEmail);

        // Group by doctor
        $appointmentsByDoctor = $appointments->groupBy('doctor_id');

        foreach ($appointmentsByDoctor as $doctorId => $doctorAppointments) {
            $doctor = $doctorAppointments->first()->doctor;
            if ($doctor && $doctor->user && $doctor->user->email) {
                Mail::to($doctor->user->email)->send(new DailyAppointmentsReportMail($doctorAppointments, $doctor, $today));
                $this->info('Report sent to Dr. ' . $doctor->user->name);
            }
        }
        
        $this->info('All daily reports sent successfully.');
    }
}
