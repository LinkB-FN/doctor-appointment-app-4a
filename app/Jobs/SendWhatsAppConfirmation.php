<?php

namespace App\Jobs;

use App\Models\Appointment;
use App\Services\WhatsAppService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendWhatsAppConfirmation implements ShouldQueue
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
            Log::warning("SendWhatsAppConfirmation: patient #{$patient?->id} has no phone number.");
            return;
        }

        $doctorName  = $appointment->doctor->user->name ?? 'N/D';
        $specialty   = $appointment->doctor->specialty->name ?? 'N/D';
        $date        = \Carbon\Carbon::parse($appointment->date)->translatedFormat('l, d \d\e F \d\e Y');
        $startTime   = \Carbon\Carbon::parse($appointment->start_time)->format('H:i');
        $patientName = $user->name;

        $message = "¡Hola {$patientName}! 🏥\n\n"
            . "Tu cita médica ha sido *confirmada*:\n"
            . "📅 *Fecha:* {$date}\n"
            . "⏰ *Hora:* {$startTime}\n"
            . "👨‍⚕️ *Doctor:* Dr. {$doctorName}\n"
            . "🏥 *Especialidad:* {$specialty}\n\n"
            . "Si necesitas cancelar o reprogramar, por favor contáctanos.\n"
            . "_Clínica Médica_";

        $whatsApp->sendMessage($phone, $message);
    }
}
