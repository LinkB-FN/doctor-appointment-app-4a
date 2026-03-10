<x-admin-layout title="Editar Cita | Simify" :breadcrumbs="[
        [
            'name' => 'Dashboard',
            'href' => route('admin.dashboard'),
        ],
        [
            'name' => 'Citas',
            'href' => route('admin.appointments.index'),
        ],
        [
            'name' => 'Editar',
        ],
    ]">

    <form action="{{ route('admin.appointments.update', $appointment) }}" method="POST">
        @csrf
        @method('PUT')

        <x-card>
            <x-slot name="title">Editar cita médica</x-slot>

            @if ($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    <strong class="font-bold">Hay errores!</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="mb-4">
                    <x-label for="patient_id" value="Paciente *" class="mb-1" />
                    <select id="patient_id" name="patient_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500" required>
                        <option value="" disabled>Seleccione un paciente</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" @selected(old('patient_id', $appointment->patient_id) == $patient->id)>
                                {{ $patient->user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <x-label for="doctor_id" value="Doctor *" class="mb-1" />
                    <select id="doctor_id" name="doctor_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500" required>
                        <option value="" disabled>Seleccione un doctor</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" @selected(old('doctor_id', $appointment->doctor_id) == $doctor->id)>
                                {{ $doctor->user->name }} ({{ $doctor->specialty->name ?? 'Sin especialidad' }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div class="mb-4">
                    <x-label for="date" value="Fecha *" class="mb-1" />
                    <x-input id="date" type="date" name="date" class="w-full"
                        value="{{ old('date', $appointment->date) }}" required />
                </div>

                <div class="mb-4">
                    <x-label for="start_time" value="Hora Inicio *" class="mb-1" />
                    <x-input id="start_time" type="time" name="start_time" class="w-full"
                        value="{{ old('start_time', date('H:i', strtotime($appointment->start_time))) }}" required />
                </div>

                <div class="mb-4">
                    <x-label for="end_time" value="Hora Fin *" class="mb-1" />
                    <x-input id="end_time" type="time" name="end_time" class="w-full"
                        value="{{ old('end_time', date('H:i', strtotime($appointment->end_time))) }}" required />
                </div>
            </div>

            <div class="mb-4">
                <x-label for="status" value="Estado *" class="mb-1" />
                <select id="status" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500" required>
                    <option value="Programado" @selected(old('status', $appointment->status) == 'Programado')>Programado
                    </option>
                    <option value="Completado" @selected(old('status', $appointment->status) == 'Completado')>Completado
                    </option>
                    <option value="Cancelado" @selected(old('status', $appointment->status) == 'Cancelado')>Cancelado
                    </option>
                </select>
            </div>

            <div class="mb-4">
                <x-label for="notes" value="Notas / Motivo de cita" class="mb-1" />
                <x-textarea id="notes" name="notes" class="w-full"
                    rows="3">{{ old('notes', $appointment->notes) }}</x-textarea>
            </div>

            <x-slot name="footer">
                <div class="flex justify-end gap-2">
                    <x-button href="{{ route('admin.appointments.index') }}" secondary>
                        Cancelar
                    </x-button>
                    <x-button type="submit" primary>
                        Actualizar
                    </x-button>
                </div>
            </x-slot>
        </x-card>
    </form>
</x-admin-layout>