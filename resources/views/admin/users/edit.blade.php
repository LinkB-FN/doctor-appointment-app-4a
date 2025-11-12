<x-admin-layout title="Usuarios | Simify" :breadcrumbs="[
        [
          'name' => 'Dashboard',
          'href' => route('admin.dashboard')
        ],
        [
          'name' => 'Usuarios',
          'href' => route('admin.users.index')
        ],
        [
          'name' => 'Editar'
        ],
    ]">
 <x-card>
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <x-input label="Nombre" name="name" placeholder="Ingrese el nombre del usuario" value="{{ old('name', $user->name) }}" />
                </div>

                <div class="mb-4">
                    <x-input label="Email" name="email" type="email" placeholder="Ingrese el email del usuario" value="{{ old('email', $user->email) }}" />
                </div>

                <div class="mb-4">
                    <x-input label="Contraseña (dejar en blanco para no cambiar)" name="password" type="password" placeholder="Ingrese la nueva contraseña" />
                </div>

                <div class="mb-4">
                    <x-input label="Confirmar Contraseña" name="password_confirmation" type="password" placeholder="Confirme la nueva contraseña" />
                </div>

                <div class="flex justify-end mt-4">
                    <x-button type="submit" primary>Guardar</x-button>
                </div>
            </form>
        </x-card>


</x-admin-layout>
