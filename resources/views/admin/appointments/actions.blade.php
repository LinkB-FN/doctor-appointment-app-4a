<div class="flex items-center space-x-2">
    <x-button href="{{ route('admin.appointments.show', $appointment) }}" teal xs title="Ver Detalles">
        <i class="fa-solid fa-eye"></i>
    </x-button>
    <x-button href="{{ route('admin.appointments.edit', $appointment) }}" primary xs title="Editar Cita">
        <i class="fa-solid fa-pen-to-square"></i>
    </x-button>
    <form action="{{ route('admin.appointments.destroy', $appointment) }}" method="POST" class="delete-form">
        @csrf
        @method('DELETE')
        <x-button type="submit" negative xs>
            <i class="fa-solid fa-trash"></i>
        </x-button>
    </form>
</div>