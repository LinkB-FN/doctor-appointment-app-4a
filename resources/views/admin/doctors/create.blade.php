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
        'name' => 'Nuevo Doctor',
    ],
]">

    <form action="{{ route('admin.doctors.store') }}" method="POST">
        @csrf

        {{-- Header card --}}
        <x-card class="mb-6">
            <div class="lg:flex lg:justify-between lg:items-center">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Registrar Nuevo Doctor</h2>
                    <p class="text-sm text-gray-500 mt-1">Complete la información del doctor a registrar.</p>
                </div>
                <div class="flex space-x-3 mt-4 lg:mt-0">
                    <x-button outline gray href="{{ route('admin.doctors.index') }}" class="whitespace-nowrap">
                        Cancelar
                    </x-button>
                    <x-button type="submit" class="flex items-center gap-2 whitespace-nowrap">
                        <i class="fa-solid fa-check"></i>
                        <span>Guardar Doctor</span>
                    </x-button>
                </div>
            </div>
        </x-card>

        {{-- Form card --}}
        <x-card>
            <div class="space-y-6">

                {{-- Usuario --}}
                <div>
                    <x-native-select
                        label="Usuario"
                        name="user_id"
                        :error="$errors->first('user_id')"
                    >
                        <option value="">— Selecciona un usuario —</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" @selected(old('user_id') == $user->id)>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </x-native-select>
                </div>

                {{-- Especialidad --}}
                <div>
                    <x-native-select
                        label="Especialidad"
                        name="specialty_id"
                        :error="$errors->first('specialty_id')"
                    >
                        <option value="">— Selecciona una especialidad —</option>
                        @foreach ($specialties as $specialty)
                            <option value="{{ $specialty->id }}" @selected(old('specialty_id') == $specialty->id)>
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
                        value="{{ old('medical_license') }}"
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
                    >{{ old('biography') }}</x-textarea>
                </div>

            </div>
        </x-card>

    </form>

</x-admin-layout>
