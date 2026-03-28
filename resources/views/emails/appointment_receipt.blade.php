<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Comprobante de Cita</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f7fa; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2 style="color: #0284c7; text-align: center;">Cita Médica Confirmada</h2>
        @if($userType === 'patient')
            <p>Hola <strong>{{ $appointment->patient->user->name ?? $appointment->patient->first_name . ' ' . $appointment->patient->last_name }}</strong>,</p>
            <p>Tu cita médica ha sido agendada exitosamente. Adjunto a este correo encontrarás el comprobante en formato PDF con todos los detalles.</p>
        @else
            <p>Hola Dr(a). <strong>{{ $appointment->doctor->user->name }}</strong>,</p>
            <p>Se ha agendado una nueva cita médica. Adjunto a este correo encontrarás el comprobante en formato PDF con los detalles del paciente.</p>
        @endif
        <p><strong>Fecha:</strong> {{ $appointment->date->format('d/m/Y') }}</p>
        <p><strong>Hora:</strong> {{ date('H:i', strtotime($appointment->start_time)) }}</p>
        
        <p style="margin-top: 30px; font-size: 12px; color: #777; text-align: center;">
            Este es un mensaje automático, por favor no respondas a este correo.
        </p>
    </div>
</body>
</html>
