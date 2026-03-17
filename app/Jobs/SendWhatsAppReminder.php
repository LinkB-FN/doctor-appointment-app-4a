<?php

namespace App\Jobs;

use App\Models\Appointment;
use App\Services\WhatsAppService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendWhatsAppReminder implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    /**
     * Number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(public Appointment $appointment)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(WhatsAppService $whatsApp): void
    {
        $appointment = $this->appointment->load(['patient.user', 'doctor.user', 'doctor.specialty']);

        $patient = $appointment->patient;
        $user    = $patient?->user;
        $phone   = $user?->phone;

        if (empty($phone)) {
            Log::warning("SendWhatsAppReminder: patient #{$patient?->id} has no phone number.");
            return;
        }

        $doctorName  = $appointment->doctor->user->name ?? 'N/D';
        $specialty   = $appointment->doctor->specialty->name ?? 'N/D';
        $date        = \Carbon\Carbon::parse($appointment->date)->translatedFormat('l, d \d\e F \d\e Y');
        $startTime   = \Carbon\Carbon::parse($appointment->start_time)->format('H:i');
        $patientName = $user->name;

        $message = "¡Hola {$patientName}! 🔔\n\n"
            . "Te recordamos que *mañana* tienes una cita médica:\n"
            . "📅 *Fecha:* {$date}\n"
            . "⏰ *Hora:* {$startTime}\n"
            . "👨‍⚕️ *Doctor:* Dr. {$doctorName}\n"
            . "🏥 *Especialidad:* {$specialty}\n\n"
            . "Por favor, recuerda llegar *10 minutos antes*.\n"
            . "Si necesitas cancelar, contáctanos a la brevedad.\n"
            . "_Clínica Médica_";

        $sent = $whatsApp->sendMessage($phone, $message);

        if ($sent) {
            // Mark reminder as sent to avoid duplicates
            $appointment->update(['reminder_sent_at' => now()]);
        }
    }
}
