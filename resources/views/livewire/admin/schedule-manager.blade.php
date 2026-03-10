<div>
    <div class="card bg-white shadow-xl dark:bg-gray-800 rounded-lg p-6">
        
        <div class="flex justify-between items-center mb-6 border-b pb-4 border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">Gestor de horarios</h2>
            <div class="flex items-center space-x-4">
                <div class="w-64">
                    <select wire:model.live="selectedDoctorId" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="">Seleccione un doctor</option>
                        @foreach ($doctors as $doctor)
                            <option value="{{ $doctor->id }}">{{ $doctor->user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button wire:click="saveSchedules" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                    Guardar horario
                </button>
            </div>
        </div>

        @if ($selectedDoctorId)
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">Día/Hora</th>
                            @foreach ($days as $day)
                                <th scope="col" class="px-6 py-3 text-center">{{ $day }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($hours as $hour)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white whitespace-nowrap">
                                    {{ $hour . ':00' }}
                                </td>
                                @foreach ($days as $day)
                                    <td class="px-6 py-4 align-top">
                                        <div class="flex flex-col space-y-2">
                                            @php
                                                $hourStart = strtotime($hour);
                                                $intervals = [
                                                    date('H:i', $hourStart),
                                                    date('H:i', strtotime('+15 minutes', $hourStart)),
                                                    date('H:i', strtotime('+30 minutes', $hourStart)),
                                                    date('H:i', strtotime('+45 minutes', $hourStart)),
                                                ];
                                            @endphp
                                            
                                            @foreach ($intervals as $interval)
                                                <label class="flex items-center space-x-2 cursor-pointer">
                                                    <input type="checkbox" wire:model="schedules" value="{{ $day }}|{{ $interval }}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-300">
                                                        {{ $interval }} - {{ date('H:i', strtotime('+15 minutes', strtotime($interval))) }}
                                                    </span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-10 text-gray-500">
                <i class="fa-solid fa-user-doctor text-4xl mb-3"></i>
                <p>Por favor, seleccione un doctor para gestionar sus horarios.</p>
            </div>
        @endif
    </div>
</div>
