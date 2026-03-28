<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppointmentReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;
    public $userType;

    /**
     * Create a new message instance.
     */
    public function __construct(Appointment $appointment, string $userType)
    {
        $this->appointment = $appointment;
        $this->userType = $userType; // 'patient' or 'doctor'
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Comprobante de Cita Médica',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.appointment_receipt',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.appointment_receipt', [
            'appointment' => $this->appointment,
            'userType' => $this->userType,
        ]);

        return [
            Attachment::fromData(fn () => $pdf->output(), 'Comprobante_Cita_' . $this->appointment->id . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
