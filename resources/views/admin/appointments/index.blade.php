<x-admin-layout title="Citas | Simify" :breadcrumbs="[
        [
            'name' => 'Dashboard',
            'href' => route('admin.dashboard'),
        ],
        [
            'name' => 'Citas',
        ],
    ]">

    <div class="flex justify-end mb-4">
        <x-button href="{{ route('admin.appointments.create') }}" primary>
            <i class="fa-solid fa-plus mr-2"></i>
            Nueva Cita
        </x-button>
    </div>

    <x-card>
        <livewire:admin.datatables.appointment-table />
    </x-card>

</x-admin-layout>