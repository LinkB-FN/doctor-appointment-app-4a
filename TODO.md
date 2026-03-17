# TODO: WhatsApp Notifications para Citas Médicas

## Pasos del Plan

- [x] 1. Instalar paquete `twilio/sdk` via Composer
- [x] 2. Crear `app/Services/WhatsAppService.php` — servicio que encapsula Twilio SDK
- [x] 3. Crear `app/Jobs/SendWhatsAppConfirmation.php` — job para confirmación inmediata
- [x] 4. Crear `app/Jobs/SendWhatsAppReminder.php` — job para recordatorio 1 día antes
- [x] 5. Crear `app/Console/Commands/SendAppointmentReminders.php` — comando Artisan diario
- [x] 6. Crear migración `add_reminder_sent_at_to_appointments_table`
- [x] 7. Actualizar `app/Models/Appointment.php` — agregar `reminder_sent_at` a fillable/casts
- [x] 8. Actualizar `app/Http/Controllers/Admin/AppointmentController.php` — dispatch job en store()
- [x] 9. Actualizar `app/Livewire/Admin/AppointmentBooking.php` — dispatch job en bookAppointment()
- [x] 10. Actualizar `config/services.php` — agregar configuración de Twilio
- [x] 11. Actualizar `bootstrap/app.php` — registrar scheduler diario (08:00 AM)
- [x] 12. Ejecutar migración — DONE ✓

## ⚙️ Configuración Pendiente (Manual)

### 1. Crear cuenta Twilio (GRATIS para pruebas)
1. Ir a https://www.twilio.com/try-twilio
2. Registrarse (no requiere tarjeta de crédito para el sandbox)
3. En el dashboard obtener:
   - **Account SID** (empieza con `AC...`)
   - **Auth Token**
4. Ir a **Messaging → Try it out → Send a WhatsApp message**
5. Anotar el número del sandbox: `+14155238886`
6. Seguir las instrucciones para unirse al sandbox (enviar un mensaje de WhatsApp al número)

### 2. Agregar variables al `.env`
```
TWILIO_SID=ACxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
TWILIO_TOKEN=your_auth_token_here
TWILIO_WHATSAPP_FROM=+14155238886

QUEUE_CONNECTION=database
```

### 3. Iniciar el worker de colas
```bash
php artisan queue:work
```

### 4. Configurar el cron (en producción)
Agregar al crontab del servidor:
```
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

### 5. Probar el comando manualmente
```bash
# Probar recordatorios para una fecha específica
php artisan appointments:send-reminders --date=2026-03-21

# Probar recordatorios para mañana (comportamiento por defecto)
php artisan appointments:send-reminders
```
