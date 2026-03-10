<div>
    <div class="card bg-white shadow-xl dark:bg-gray-800 rounded-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-2">Buscar disponibilidad</h2>
        <p class="text-gray-500 dark:text-gray-400 mb-6 font-medium">Encuentra el horario perfecto para tu cita.</p>

        <!-- Search Form -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end mb-6">
            <div>
                <x-label for="date" value="Fecha" class="mb-1" />
                <x-input wire:model="date" id="date" type="date" class="w-full text-sm py-2" />
                @error('date') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div>
                <x-label for="time" value="Hora" class="mb-1" />
                <select wire:model="time" id="time"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full py-2 px-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    <option value="">Selecciona una hora</option>
                    @foreach($hours as $h)
                        <option value="{{ $h }}">{{ $h }}</option>
                    @endforeach
                </select>
                @error('time') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div>
                <x-label for="specialty_id" value="Especialidad (opcional)" class="mb-1" />
                <select wire:model="specialty_id" id="specialty_id"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full py-2 px-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    <option value="">Selecciona una especialidad...</option>
                    @foreach($specialties as $specialty)
                        <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <button wire:click="searchAvailability"
                    class="w-full text-white bg-indigo-500 hover:bg-indigo-600 focus:ring-4 focus:ring-indigo-300 font-medium rounded-lg text-sm px-5 py-2 dark:bg-indigo-600 dark:hover:bg-indigo-700 focus:outline-none dark:focus:ring-indigo-800 transition-colors">
                    Buscar disponibilidad
                </button>
            </div>
        </div>

        <div wire:loading wire:target="searchAvailability" class="w-full text-center py-4">
            <i class="fa-solid fa-spinner fa-spin text-blue-500 text-2xl"></i>
        </div>

        <!-- Search Results -->
        @if($hasSearched)
            <div wire:loading.remove wire:target="searchAvailability"
                class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-6">

                @if(count($availableDoctors) > 0)
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">Doctores Disponibles: {{ $time }}
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                        @foreach($availableDoctors as $doctor)
                            <div wire:click="selectDoctor({{ $doctor->id }})"
                                class="p-4 border rounded-lg cursor-pointer transition-all duration-200 flex items-center space-x-4
                                             {{ $selectedDoctorId == $doctor->id ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/30 ring-2 ring-indigo-500 ring-opacity-50' : 'border-gray-200 dark:border-gray-700 hover:border-indigo-300 hover:shadow-md dark:bg-gray-800' }}">
                                <img src="{{ $doctor->user->profile_photo_url }}" alt="{{ $doctor->user->name }}"
                                    class="w-12 h-12 rounded-full object-cover">
                                <div>
                                    <h4 class="font-bold text-gray-900 dark:text-white text-sm">{{ $doctor->user->name }}</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $doctor->specialty->name ?? 'Médico General' }}</p>
                                </div>
                                @if($selectedDoctorId == $doctor->id)
                                    <div class="ml-auto text-indigo-500">
                                        <i class="fa-solid fa-circle-check text-xl"></i>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <!-- Finalize Booking Form -->
                    @if($selectedDoctorId)
                        <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-6 border border-gray-200 dark:border-gray-700 mt-6">
                            <h3 class="text-md font-bold text-gray-800 dark:text-white mb-4">Finalizar Reserva</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-label for="patient_id" value="Seleccionar Paciente *" class="mb-1" />
                                    <select wire:model="patient_id" id="patient_id"
                                        class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        <option value="">Buscar paciente...</option>
                                        @foreach($patients as $patient)
                                            <option value="{{ $patient->id }}">{{ $patient->user->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('patient_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                </div>

                                <div class="row-span-2">
                                    <x-label for="notes" value="Notas o Motivo de la Cita" class="mb-1" />
                                    <x-textarea wire:model="notes" id="notes" rows="4"
                                        class="w-full bg-white dark:bg-gray-700"></x-textarea>
                                </div>

                                <div class="flex items-end shadow-sm">
                                    <button wire:click="bookAppointment"
                                        class="w-full text-white bg-green-500 hover:bg-green-600 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800 transition-colors shadow-md">
                                        <i class="fa-solid fa-calendar-check mr-2"></i> Confirmar Cita
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                @else
                    <div class="text-center py-8">
                        <div
                            class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
                            <i class="fa-regular fa-face-frown text-2xl text-gray-500 dark:text-gray-400"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">No hay doctores disponibles</h3>
                        <p class="text-gray-500 dark:text-gray-400 mt-1">Prueba seleccionando otra fecha, hora o especialidad.
                        </p>
                    </div>
                @endif
            </div>
        @endif

    </div>
</div>