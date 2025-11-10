<div class="flex items-center space-x-2">
    <x-wire-button href="{{ route('admin.roles.edit', $role) }}" blue xs>
        <i class="fa-solid fa-pen-to-square"></i>
    </x-wire-button>

    <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" x-on:submit.prevent="Swal.fire({title: 'Estas seguro?', text: 'No podras revertir esto!', icon: 'warning', showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33', confirmButtonText: 'Si, eliminar!', cancelButtonText: 'Cancelar'}).then((result) => { if (result.isConfirmed) { $el.submit(); } })">
        @csrf
        @method('DELETE')
        <x-wire-button type="submit" red xs>
            <i class="fa-solid fa-trash"></i>
        </x-wire-button>
    </form>

</div>
