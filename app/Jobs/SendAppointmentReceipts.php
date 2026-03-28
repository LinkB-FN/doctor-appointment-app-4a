<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

use App\Models\Appointment;
use App\Mail\AppointmentReceiptMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendAppointmentReceipts implements ShouldQueue
{
    use Queueable;

    public $appointment;

    /**
     * Create a new job instance.
     */
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $appointment = $this->appointment->load(['patient.user', 'doctor.user', 'doctor.specialty']);

        $patientEmail = $appointment->patient?->user?->email;
        $doctorEmail = $appointment->doctor?->user?->email;

        $hadError = false;

        if ($patientEmail) {
            try {
                Mail::to($patientEmail)
                    ->send(new AppointmentReceiptMail($appointment, 'patient'));

                Log::info('SendAppointmentReceipts: patient email sent.', [
                    'appointment_id' => $appointment->id,
                    'to' => $patientEmail,
                ]);
            } catch (Throwable $e) {
                $hadError = true;
                Log::error('SendAppointmentReceipts: patient email failed.', [
                    'appointment_id' => $appointment->id,
                    'to' => $patientEmail,
                    'error' => $e->getMessage(),
                ]);
            }
        } else {
            Log::warning('SendAppointmentReceipts: patient email missing.', [
                'appointment_id' => $appointment->id,
                'patient_id' => $appointment->patient?->id,
            ]);
        }

        if ($doctorEmail) {
            try {
                Mail::to($doctorEmail)
                    ->send(new AppointmentReceiptMail($appointment, 'doctor'));

                Log::info('SendAppointmentReceipts: doctor email sent.', [
                    'appointment_id' => $appointment->id,
                    'to' => $doctorEmail,
                ]);
            } catch (Throwable $e) {
                $hadError = true;
                Log::error('SendAppointmentReceipts: doctor email failed.', [
                    'appointment_id' => $appointment->id,
                    'to' => $doctorEmail,
                    'error' => $e->getMessage(),
                ]);
            }
        } else {
            Log::warning('SendAppointmentReceipts: doctor email missing.', [
                'appointment_id' => $appointment->id,
                'doctor_id' => $appointment->doctor?->id,
            ]);
        }

        if ($hadError) {
            throw new \RuntimeException('One or more appointment receipt emails failed to send.');
        }
    }
}
