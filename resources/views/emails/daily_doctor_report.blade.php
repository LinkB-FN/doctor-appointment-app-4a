<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tus Citas de Hoy</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f7fa; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 20px; border-radius: 8px;">
        <h2 style="color: #0284c7; border-bottom: 2px solid #e0f2fe; padding-bottom: 10px;">Tus Citas Agendadas</h2>
        <p>Hola Dr(a). <strong>{{ optional($doctor->user)->name }}</strong>,</p>
        <p>Este es tu itinerario de pacientes agendados para hoy: <strong>{{ $date->format('d/m/Y') }}</strong>.</p>
        
        <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <thead>
                <tr style="background-color: #e0f2fe; color: #0369a1;">
                    <th style="padding: 10px; text-align: left; border: 1px solid #bae6fd;">Hora</th>
                    <th style="padding: 10px; text-align: left; border: 1px solid #bae6fd;">Paciente</th>
                    <th style="padding: 10px; text-align: left; border: 1px solid #bae6fd;">Notas</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointments->sortBy('start_time') as $appt)
                <tr>
                    <td style="padding: 10px; border: 1px solid #eee;">{{ date('H:i', strtotime($appt->start_time)) }}</td>
                    <td style="padding: 10px; border: 1px solid #eee;">{{ optional(optional($appt->patient)->user)->name ?? optional($appt->patient)->first_name . ' ' . optional($appt->patient)->last_name }}</td>
                    <td style="padding: 10px; border: 1px solid #eee; font-size: 12px; color: #666;">{{ $appt->notes ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <p style="margin-top: 20px;"><strong>Total de pacientes:</strong> {{ $appointments->count() }}</p>
    </div>
</body>
</html>
