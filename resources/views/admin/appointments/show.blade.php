<x-admin-layout title="Detalles de Cita | Simify" :breadcrumbs="[
        [
            'name' => 'Dashboard',
            'href' => route('admin.dashboard'),
        ],
        [
            'name' => 'Citas',
            'href' => route('admin.appointments.index'),
        ],
        [
            'name' => 'Detalles',
        ],
    ]">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Columna Izquierda: Detalles Médicos -->
        <x-card>
            <x-slot name="title">
                <i class="fa-solid fa-stethoscope text-indigo-500 mr-2"></i> Detalles Médicos
            </x-slot>

            <div class="space-y-4">
                <div class="flex items-center space-x-4 border-b pb-4">
                    <img src="{{ $appointment->doctor->user->profile_photo_url }}"
                        alt="{{ $appointment->doctor->user->name }}" class="w-16 h-16 rounded-full object-cover">
                    <div>
                        <h3 class="font-bold text-lg text-gray-800 dark:text-white">Dr.
                            {{ $appointment->doctor->user->name }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $appointment->doctor->specialty->name ?? 'Médico General' }}</p>
                        <p class="text-xs text-gray-400 mt-1">Cédula:
                            {{ $appointment->doctor->medical_license ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="pt-2">
                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Información de la Cita
                    </h4>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                            <span class="block text-xs text-gray-500 dark:text-gray-400">Fecha Programada</span>
                            <span class="font-medium text-gray-900 dark:text-white">
                                <i class="fa-regular fa-calendar mr-1"></i>
                                {{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}
                            </span>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                            <span class="block text-xs text-gray-500 dark:text-gray-400">Horario</span>
                            <span class="font-medium text-gray-900 dark:text-white">
                                <i class="fa-regular fa-clock mr-1"></i>
                                {{ date('H:i', strtotime($appointment->start_time)) }} -
                                {{ date('H:i', strtotime($appointment->end_time)) }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-4">
                        <span class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Estado de Cita</span>
                        @if($appointment->status == 'Programado')
                            <span
                                class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded inline-flex items-center">
                                <i class="fa-solid fa-circle-dot w-3 h-3 mr-2"></i> Programado
                            </span>
                        @elseif($appointment->status == 'Completado')
                            <span
                                class="bg-green-100 text-green-800 text-sm font-medium px-3 py-1 rounded inline-flex items-center">
                                <i class="fa-solid fa-check w-3 h-3 mr-2"></i> Completado
                            </span>
                        @else
                            <span
                                class="bg-red-100 text-red-800 text-sm font-medium px-3 py-1 rounded inline-flex items-center">
                                <i class="fa-solid fa-xmark w-3 h-3 mr-2"></i> Cancelado
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </x-card>

        <!-- Columna Derecha: Paciente y Notas -->
        <div class="space-y-6">
            <x-card>
                <x-slot name="title">
                    <i class="fa-solid fa-user-injured text-teal-500 mr-2"></i> Información del Paciente
                </x-slot>

                <div class="flex items-center space-x-4">
                    <img src="{{ $appointment->patient->user->profile_photo_url }}"
                        alt="{{ $appointment->patient->user->name }}" class="w-12 h-12 rounded-full object-cover">
                    <div>
                        <h3 class="font-bold text-md text-gray-800 dark:text-white">
                            {{ $appointment->patient->user->name }}</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400"><i class="fa-solid fa-envelope mr-1"></i>
                            {{ $appointment->patient->user->email }}</p>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <ul class="text-sm space-y-2 text-gray-600 dark:text-gray-300">
                        <li><span class="font-semibold w-24 inline-block">Teléfono:</span>
                            {{ $appointment->patient->phone ?? 'No registrado' }}</li>
                        <li><span class="font-semibold w-24 inline-block">Nacimiento:</span>
                            {{ $appointment->patient->date_of_birth ? \Carbon\Carbon::parse($appointment->patient->date_of_birth)->format('d/m/Y') : 'No registrado' }}
                        </li>
                        <li><span class="font-semibold w-24 inline-block">Sangre:</span> <span
                                class="text-red-500 font-bold">{{ $appointment->patient->blood_type ?? 'N/A' }}</span>
                        </li>
                    </ul>
                </div>
            </x-card>

            <x-card>
                <x-slot name="title">
                    <i class="fa-solid fa-notes-medical text-yellow-500 mr-2"></i> Motivo / Notas de la Cita
                </x-slot>

                <div
                    class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-lg border border-yellow-100 dark:border-yellow-800">
                    @if($appointment->notes)
                        <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $appointment->notes }}
                        </p>
                    @else
                        <p class="text-sm text-gray-500 italic">No se proporcionaron notas ni motivo para esta cita.</p>
                    @endif
                </div>
            </x-card>

            <div class="flex justify-end space-x-3">
                <x-button href="{{ route('admin.appointments.index') }}" secondary>Volver al Listado</x-button>
                <x-button href="{{ route('admin.appointments.edit', $appointment) }}" primary>Editar Cita</x-button>
            </div>
        </div>

    </div>
</x-admin-layout>