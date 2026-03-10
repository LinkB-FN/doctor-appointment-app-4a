<?php

namespace App\Livewire\Admin\Datatables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Appointment;
use Illuminate\Database\Eloquent\Builder;

class AppointmentTable extends DataTableComponent
{
    protected $model = Appointment::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function builder(): Builder
    {
        return Appointment::query()->with(['patient.user', 'doctor.user']);
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Paciente", "patient.user.name")
                ->searchable()
                ->sortable(),
            Column::make("Doctor", "doctor.user.name")
                ->searchable()
                ->sortable(),
            Column::make("Fecha", "date")
                ->sortable(),
            Column::make("Hora", "start_time")
                ->sortable(),
            Column::make("Hora Fin", "end_time")
                ->sortable(),
            Column::make("Estado", "status")
                ->sortable(),
            Column::make("Acciones")
                ->label(
                    fn($row, Column $column) => view('admin.appointments.actions', ['appointment' => $row])
                ),
        ];
    }
}
