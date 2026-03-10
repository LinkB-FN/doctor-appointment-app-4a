<?php

namespace App\Livewire\Admin;

use App\Models\Doctor;
use App\Models\DoctorSchedule;
use Livewire\Component;

class ScheduleManager extends Component
{
    public $doctors;
    public $selectedDoctorId = null;

    // Structure to hold schedules: ['Lunes|08:00', 'Martes|09:15', ...]
    public $schedules = [];

    public $days = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
    public $hours = [];

    public function mount()
    {
        $this->doctors = Doctor::with('user')->get();
        if (request()->has('doctor_id')) {
            $this->selectedDoctorId = request()->doctor_id;
            $this->loadDoctorSchedules();
        }

        // Generate hours from 08:00 to 20:00 every 15 mins
        $start = strtotime('08:00');
        $end = strtotime('20:00');
        while ($start < $end) {
            $this->hours[] = date('H:i', $start);
            $start = strtotime('+15 minutes', $start);
        }
    }

    public function updatedSelectedDoctorId($value)
    {
        $this->loadDoctorSchedules();
    }

    public function loadDoctorSchedules()
    {
        // Reset schedules
        $this->schedules = [];

        if ($this->selectedDoctorId) {
            $dbSchedules = DoctorSchedule::where('doctor_id', $this->selectedDoctorId)->get();
            foreach ($dbSchedules as $schedule) {
                $time = date('H:i', strtotime($schedule->start_time));
                $this->schedules[] = $schedule->day . '|' . $time;
            }
        }
    }

    public function saveSchedules()
    {
        if (!$this->selectedDoctorId) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Por favor, selecciona un doctor.'
            ]);
            return;
        }

        // Delete previous schedules for this doctor
        DoctorSchedule::where('doctor_id', $this->selectedDoctorId)->delete();

        // Save new schedules
        $schedulesToInsert = [];
        foreach ($this->schedules as $slot) {
            if (!$slot)
                continue;

            $parts = explode('|', $slot);
            if (count($parts) === 2) {
                $day = $parts[0];
                $time = $parts[1];
                $endTime = date('H:i:s', strtotime('+15 minutes', strtotime($time)));

                $schedulesToInsert[] = [
                    'doctor_id' => $this->selectedDoctorId,
                    'day' => $day,
                    'start_time' => $time . ':00',
                    'end_time' => $endTime,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if (count($schedulesToInsert) > 0) {
            DoctorSchedule::insert($schedulesToInsert);
        }

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Éxito',
            'text' => 'Horarios guardados correctamente.'
        ]);
    }

    public function render()
    {
        return view('livewire.admin.schedule-manager')->layout('layouts.admin', [
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'route' => route('admin.dashboard')],
                ['name' => 'Horarios']
            ]
        ]);
    }
}
