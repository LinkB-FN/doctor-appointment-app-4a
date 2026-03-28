<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Comprobante de Cita PDF</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; margin: 0; padding: 0; color: #333; }
        .header { background-color: #0ea5e9; color: white; padding: 30px 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; text-transform: uppercase; letter-spacing: 1px; }
        .content { padding: 40px; }
        .section { margin-bottom: 30px; }
        .section-title { color: #0284c7; font-size: 18px; border-bottom: 2px solid #e0f2fe; padding-bottom: 5px; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 10px; background-color: #e0f2fe; color: #0369a1; width: 30%; }
        td { padding: 10px; border-bottom: 1px solid #e0f2fe; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 12px; color: #7dd3fc; margin-bottom: 20px; }
        .badge { display: inline-block; padding: 5px 10px; background-color: #bae6fd; color: #0c4a6e; border-radius: 4px; font-weight: bold; font-size: 14px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Centro Médico</h1>
        <p style="margin: 5px 0 0 0; font-size: 14px; color: #e0f2fe;">Comprobante Oficial de Cita</p>
    </div>

    <div class="content">
        <div style="text-align: right; margin-bottom: 20px;">
            <strong>Folio:</strong> #{{ str_pad($appointment->id, 5, '0', STR_PAD_LEFT) }}<br>
            <strong>Generado:</strong> {{ now()->format('d/m/Y H:i') }}
        </div>

        <div class="section">
            <h2 class="section-title">Detalles de la Cita</h2>
            <table>
                <tr>
                    <th>Fecha</th>
                    <td><strong>{{ $appointment->date->format('d/m/Y') }}</strong></td>
                </tr>
                <tr>
                    <th>Hora</th>
                    <td><span class="badge">{{ date('H:i', strtotime($appointment->start_time)) }}</span></td>
                </tr>
                <tr>
                    <th>Estado</th>
                    <td>{{ $appointment->status ?? 'Programado' }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <h2 class="section-title">Paciente</h2>
            <table>
                <tr>
                    <th>Nombre</th>
                    <td>{{ $appointment->patient->user->name ?? $appointment->patient->first_name . ' ' . $appointment->patient->last_name }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $appointment->patient->user->email ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <h2 class="section-title">Médico Tratante</h2>
            <table>
                <tr>
                    <th>Nombre</th>
                    <td>Dr(a). {{ $appointment->doctor->user->name }}</td>
                </tr>
                <tr>
                    <th>Especialidad</th>
                    <td>{{ $appointment->doctor->specialty->name ?? 'Medicina General' }}</td>
                </tr>
            </table>
        </div>
        
        @if($appointment->notes)
        <div class="section">
            <h2 class="section-title">Notas Adicionales</h2>
            <p style="background-color: #f8fafc; padding: 15px; border-left: 4px solid #38bdf8; border-radius: 0 4px 4px 0;">
                {{ $appointment->notes }}
            </p>
        </div>
        @endif
    </div>

    <div class="footer">
        Este documento es un comprobante válido de la cita programada.<br>
        Por favor llegar 15 minutos antes de la hora indicada.
    </div>
</body>
</html>
