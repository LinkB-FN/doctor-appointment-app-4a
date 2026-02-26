<x-admin-layout title="Doctores | Simify" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Doctores',
    ],
]">

    <div class="flex justify-end mb-4">
        <x-button href="{{ route('admin.doctors.create') }}" primary>
            <i class="fa-solid fa-plus mr-2"></i>
            Nuevo Doctor
        </x-button>
    </div>

    <x-card>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ID
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Doctor
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Especialidad
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Cédula Profesional
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Biografía
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($doctors as $doctor)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $doctor->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <img src="{{ $doctor->user->profile_photo_url }}"
                                         alt="{{ $doctor->user->name }}"
                                         class="h-9 w-9 rounded-full object-cover object-center mr-3">
                                    <span class="text-sm font-medium text-gray-900">
                                        {{ $doctor->user->name }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $doctor->specialty->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $doctor->medical_license ?: 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                                {{ $doctor->biography ?: 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <x-button href="{{ route('admin.doctors.edit', $doctor) }}" primary xs>
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </x-button>
                                    <form action="{{ route('admin.doctors.destroy', $doctor) }}" method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <x-button type="submit" negative xs>
                                            <i class="fa-solid fa-trash"></i>
                                        </x-button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                No hay doctores registrados
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

</x-admin-layout>
