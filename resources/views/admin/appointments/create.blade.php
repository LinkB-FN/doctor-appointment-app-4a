<x-admin-layout title="Nueva Cita | Simify" :breadcrumbs="[
        [
            'name' => 'Dashboard',
            'href' => route('admin.dashboard'),
        ],
        [
            'name' => 'Citas',
            'href' => route('admin.appointments.index'),
        ],
        [
            'name' => 'Nuevo',
        ],
    ]">

    <livewire:admin.appointment-booking />

</x-admin-layout>