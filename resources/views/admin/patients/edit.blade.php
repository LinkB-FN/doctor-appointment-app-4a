<x-admin-layout title="Editar Paciente | Simify" :breadcrumbs="[
        [
          'name' => 'Dashboard',
          'href' => route('admin.dashboard')
        ],
        [
          'name' => 'Pacientes',
          'href' => route('admin.patients.index')
        ],
        [
          'name' => 'Editar'
        ],
    ]">
    <x-card>
        <form action="{{ route('admin.patients.update', $patient) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <x-input 
                        label="Alergias" 
                        name="allergies" 
                        placeholder="Ingrese las alergias del paciente" 
                        value="{{ old('allergies', $patient->allergies) }}" />
                </div>

                <div class="mb-4">
                    <x-input 
                        label="Condiciones Crónicas" 
                        name="chronic_conditions" 
                        placeholder="Ingrese las condiciones crónicas" 
                        value="{{ old('chronic_conditions', $patient->chronic_conditions) }}" />
                </div>

                <div class="mb-4">
                    <x-input 
                        label="Historial Quirúrgico" 
                        name="surgical_history" 
                        placeholder="Ingrese el historial quirúrgico" 
                        value="{{ old('surgical_history', $patient->surgical_history) }}" />
                </div>

                <div class="mb-4">
                    <x-input 
                        label="Historial Familiar" 
                        name="family_history" 
                        placeholder="Ingrese el historial familiar" 
                        value="{{ old('family_history', $patient->family_history) }}" />
                </div>

                <div class="mb-4 md:col-span-2">
                    <x-textarea 
                        label="Observaciones" 
                        name="observations" 
                        placeholder="Ingrese observaciones adicionales">{{ old('observations', $patient->observations) }}</x-textarea>
                </div>

                <div class="md:col-span-2">
                    <h3 class="text-lg font-semibold mb-4">Contacto de Emergencia</h3>
                </div>

                <div class="mb-4">
                    <x-input 
                        label="Nombre del Contacto" 
                        name="emergency_contact_name" 
                        placeholder="Nombre completo" 
                        value="{{ old('emergency_contact_name', $patient->emergency_contact_name) }}" />
                </div>

                <div class="mb-4">
                    <x-input 
                        label="Teléfono del Contacto" 
                        name="emergency_contact_phone" 
                        placeholder="Número de teléfono" 
                        value="{{ old('emergency_contact_phone', $patient->emergency_contact_phone) }}" />
                </div>

                <div class="mb-4">
                    <x-input 
                        label="Relación" 
                        name="emergency_contact_relationship" 
                        placeholder="Relación con el paciente" 
                        value="{{ old('emergency_contact_relationship', $patient->emergency_contact_relationship) }}" />
                </div>
            </div>

            <div class="flex justify-end mt-4">
                <x-button type="submit" primary>Guardar Cambios</x-button>
            </div>
        </form>
    </x-card>
</x-admin-layout>
