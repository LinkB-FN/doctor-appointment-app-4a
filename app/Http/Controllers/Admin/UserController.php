<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Services\WhatsAppService;

class UserController extends Controller
{
    /**
     * Mostrar la lista de usuarios
     */
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Mostrar el formulario para crear un nuevo usuario
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Guardar un nuevo usuario en la base de datos
     */
    public function store(Request $request)
    {
        // Validar los datos
        $data = $request->validate([
            'name' => 'required|string|min:3|max:20',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'id_number' => 'required|string|min:5|max:20|regex:/^[A-Za-z0-9\-]+$/|unique:users',
            'phone' => 'required|digits_between:7,15',
            'address' => 'required|string|min:3|max:255',
            'role_id' => 'required|exists:roles,id'
        ]);

        // Hash the password before creating the user
        $data['password'] = Hash::make($data['password']);
        
        $user = User::create($data); 

        $user->roles()->attach($data['role_id']);
         
      
        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Usuario creado correctamente',
            'text'  => 'El usuario ha sido registrado exitosamente'
        ]);

        if ($user->hasRole('Paciente')) {
            $patient = $user->patient()->create([]);
            return redirect()->route('admin.patients.edit', $patient);
        }

        if ($user->hasRole('Doctor')) {
            $doctor = $user->doctor()->create([]);
            return redirect()->route('admin.doctors.edit', $doctor);
        }

        return redirect()->route('admin.users.index')->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Mostrar el formulario para editar un usuario
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Actualizar los datos del usuario
     */
    public function update(Request $request, User $user)
    {
        // Validar los datos
        $data = $request->validate([
            'name' => 'required|string|min:3|max:20',
            'email' => 'required|string|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'id_number' => 'required|string|min:5|max:20|regex:/^[A-Za-z0-9\-]+$/|unique:users,id_number,' . $user->id,
            'phone' => 'required|digits_between:7,15',
            'address' => 'required|string|min:3|max:255',
            'role_id' => 'required|exists:roles,id'
        ]);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        $user->roles()->sync($data['role_id']);

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Usuario actualizado correctamente',
            'text'  => 'Los datos del usuario han sido actualizados exitosamente'
        ]);
        
        return redirect()->route('admin.users.edit', $user->id)->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Enviar mensaje de prueba por WhatsApp (Twilio)
     */
    public function sendTestWhatsApp(User $user, WhatsAppService $whatsApp)
    {
        $phone = preg_replace('/\D+/', '', (string) ($user->phone ?? ''));

        if (empty($phone)) {
            session()->flash('swal', [
                'icon'  => 'error',
                'title' => 'Sin teléfono',
                'text'  => 'El usuario no tiene un número de teléfono válido.'
            ]);

            return redirect()->route('admin.users.index');
        }

        $message = "Mensaje de prueba de WhatsApp para {$user->name}. Si recibes esto, Twilio está funcionando.";

        $sent = $whatsApp->sendMessage($phone, $message);

        if ($sent) {
            session()->flash('swal', [
                'icon'  => 'success',
                'title' => 'Mensaje enviado',
                'text'  => 'Se envió el mensaje de prueba por Twilio correctamente.'
            ]);
        } else {
            session()->flash('swal', [
                'icon'  => 'error',
                'title' => 'Error al enviar',
                'text'  => 'No se pudo enviar el mensaje de prueba por Twilio.'
            ]);
        }

        return redirect()->route('admin.users.index');
    }

    /**
     * Eliminar un usuario
     */
    public function destroy(User $user)
    {
        // Prevent deleting own account
        if ($user->id === auth()->id()) {
            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'No se puede eliminar el usuario',
                'text' => 'No puedes eliminar tu propia cuenta.'
            ]);
            return redirect()->route('admin.users.index');
        }

        $user->delete();


        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Usuario eliminado correctamente',
            'text'  => 'El usuario ha sido eliminado exitosamente'
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Usuario eliminado exitosamente.');
    }
}
