<?php

namespace App\Livewire\Admin;

use App\Jobs\SendWhatsAppConfirmation;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\Patient;
use App\Models\Specialty;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class AppointmentBooking extends Component
{
    // Step 1: Search parameters
    public $date;
    public $time;
    public $specialty_id = '';

    // Step 2: Search Results
    public $availableDoctors = [];
    public $hasSearched = false;

    // Step 3: Booking details
    public $selectedDoctorId = null;
    public $patient_id = '';
    public $notes = '';

    // Data lists
    public $specialties;
    public $patients;
    public $hours = [];

    protected $rules = [
        'patient_id' => 'required',
        'selectedDoctorId' => 'required',
        'date' => 'required|date',
        'time' => 'required',
    ];

    public function mount()
    {
        $this->specialties = Specialty::orderBy('name')->get();
        $this->patients = Patient::with('user')->get();
        $this->date = date('Y-m-d');

        // Generate hours for the select (08:00 to 20:00 every 15 mins)
        $start = strtotime('08:00');
        $end = strtotime('20:00');
        while ($start < $end) {
            $this->hours[] = date('H:i', $start);
            $start = strtotime('+15 minutes', $start);
        }
    }

    public function searchAvailability()
    {
        $this->validate([
            'date' => 'required|date',
            'time' => 'required',
        ]);

        $this->hasSearched = true;
        $this->selectedDoctorId = null;

        // Determine the day of the week in Spanish
        $dayOfWeek = Carbon::parse($this->date)->isoFormat('dddd');
        $dayMap = [
            'lunes' => 'Lunes',
            'martes' => 'Martes',
            'miércoles' => 'Miércoles',
            'jueves' => 'Jueves',
            'viernes' => 'Viernes',
            'sábado' => 'Sábado',
            'domingo' => 'Domingo',
        ];
        $searchDay = $dayMap[strtolower($dayOfWeek)] ?? 'Lunes';

        // Find doctors available at this day and time
        $query = DoctorSchedule::where('day', $searchDay)
            ->where('start_time', $this->time . ':00');

        $availableDoctorIds = $query->pluck('doctor_id')->toArray();

        // Get the doctor models, optionally filtering by specialty
        $doctorsQuery = Doctor::with(['user', 'specialty'])
            ->whereIn('id', $availableDoctorIds);

        if ($this->specialty_id) {
            $doctorsQuery->where('specialty_id', $this->specialty_id);
        }

        $this->availableDoctors = $doctorsQuery->get();
    }

    public function selectDoctor($doctorId)
    {
        $this->selectedDoctorId = $doctorId;
    }

    public function bookAppointment()
    {
        $this->validate();

        $endTime = date('H:i:s', strtotime('+15 minutes', strtotime($this->time)));

        $appointment = Appointment::create([
            'patient_id' => $this->patient_id,
            'doctor_id' => $this->selectedDoctorId,
            'date' => $this->date,
            'start_time' => $this->time . ':00',
            'end_time' => $endTime,
            'status' => 'Programado',
            'notes' => $this->notes,
        ]);

        // Dispatch WhatsApp confirmation (queued, non-blocking)
        $whatsAppDispatch = SendWhatsAppConfirmation::dispatch($appointment)->afterCommit();

        // Dispatch Email and PDF confirmation
        $emailDispatch = \App\Jobs\SendAppointmentReceipts::dispatch($appointment)->afterCommit();

        Log::info('AppointmentBooking: notification jobs dispatched.', [
            'appointment_id' => $appointment->id,
            'whatsapp_job' => $whatsAppDispatch ? get_class($whatsAppDispatch) : null,
            'email_job' => $emailDispatch ? get_class($emailDispatch) : null,
        ]);

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Cita Creada',
            'text' => 'La cita ha sido reservada exitosamente.',
        ]);

        return redirect()->route('admin.appointments.index');
    }

    public function render()
    {
        return view('livewire.admin.appointment-booking');
    }
}
