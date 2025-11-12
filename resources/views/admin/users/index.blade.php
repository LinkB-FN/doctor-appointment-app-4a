<x-admin-layout title="Usuarios | Simify" :breadcrumbs="[
        [
          'name' => 'Dashboard', 
          'href' => route('admin.dashboard')
        ],
        [
          'name' => 'Usuarios'
        ],
    ]">
        <x-slot name="action">
            <x-button>
                <i class="fa-solid fa-plus"></i>
                Nuevo
            </x-button>
        </x-slot>

</x-admin-layout>
