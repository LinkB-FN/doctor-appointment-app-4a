<x-admin-layout title="Doctores | Simify" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Doctores',
        'href' => route('admin.doctors.index'),
    ],
    [
        'name' => 'Editar',
    ],
]">

    <form action="{{ route('admin.doctors.update', $doctor) }}" method="POST">
        @csrf
        @method('PUT')

        <x-card class="mb-6">
            <div class="lg:flex lg:justify-between lg:items-center">
                <div class="flex items-center gap-4">
                    <img src="{{ $doctor->user->profile_photo_url }}"
                         alt="{{ $doctor->user->name }}"
                         class="h-20 w-20 rounded-full object-cover object-center">
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $doctor->user->name }}</p>
                        <p class="text-sm text-gray-500 mt-1">
                            <span class="font-semibold text-gray-600">Especialidad:</span>
                            {{ $doctor->specialty->name ?? 'N/A' }}
                        </p>
                        <p class="text-sm text-gray-500 mt-0.5">
                            <span class="font-semibold text-gray-600">Cédula Profesional:</span>
                            {{ $doctor->medical_license ?: 'N/A' }}
                        </p>
                        <p class="text-sm text-gray-500 mt-0.5">
                            <span class="font-semibold text-gray-600">Biografía:</span>
                            {{ $doctor->biography ? \Illuminate\Support\Str::limit($doctor->biography, 60) : 'N/A' }}
                        </p>
                    </div>
                </div>
                <div class="flex space-x-3 mt-6 lg:mt-0">
                    <x-button outline gray href="{{ route('admin.doctors.index') }}" class="whitespace-nowrap">
                        Volver
                    </x-button>
                    <x-button type="submit" class="flex items-center gap-2 whitespace-nowrap">
                        <i class="fa-solid fa-check"></i>
                        <span>Guardar cambios</span>
                    </x-button>
                </div>
            </div>
        </x-card>

        {{-- Info notice about user account --}}
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded-r-lg shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-user-gear text-blue-500 text-xl mt-1"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-bold text-blue-800">Edición de cuenta de usuario</h3>
                        <div class="mt-1 text-sm text-blue-600">
                            <p>La <strong>información de acceso</strong> (nombre, email y contraseña)
                                debe gestionarse desde la cuenta de usuario asociada.</p>
                        </div>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <x-button primary sm href="{{ route('admin.users.edit', $doctor->user) }}" target="_blank">
                        Editar usuario
                        <i class="fa-solid fa-arrow-up-right-from-square ml-1"></i>
                    </x-button>
                </div>
            </div>
        </div>

        {{-- Form fields card --}}
        <x-card>
            <div class="space-y-6">

                {{-- Especialidad --}}
                <div>
                    <x-native-select
                        label="Especialidad"
                        name="specialty_id"
                        :error="$errors->first('specialty_id')"
                    >
                        <option value="">— Selecciona una especialidad —</option>
                        @foreach ($specialties as $specialty)
                            <option value="{{ $specialty->id }}"
                                @selected(old('specialty_id', $doctor->specialty_id) == $specialty->id)>
                                {{ $specialty->name }}
                            </option>
                        @endforeach
                    </x-native-select>
                </div>

                {{-- Cédula Profesional --}}
                <div>
                    <x-input
                        label="Cédula Profesional"
                        name="medical_license"
                        placeholder="Ej. 12345678"
                        value="{{ old('medical_license', $doctor->medical_license) }}"
                        :error="$errors->first('medical_license')"
                    />
                </div>

                {{-- Biografía --}}
                <div>
                    <x-textarea
                        label="Biografía"
                        name="biography"
                        placeholder="Breve descripción profesional del doctor..."
                        :error="$errors->first('biography')"
                    >{{ old('biography', $doctor->biography) }}</x-textarea>
                </div>

            </div>
        </x-card>

    </form>

</x-admin-layout>
