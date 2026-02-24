<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $doctors = Doctor::with(['user', 'specialty'])->get();
        return view('admin.doctors.index', compact('doctors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $specialties = Specialty::orderBy('name')->get();
        $users = User::whereDoesntHave('doctor')->orderBy('name')->get();
        return view('admin.doctors.create', compact('specialties', 'users'));
    }

    /**
     * Display the specified resource (redirects to edit).
     */
    public function show(Doctor $doctor)
    {
        return redirect()->route('admin.doctors.edit', $doctor);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id'         => 'required|exists:users,id|unique:doctors,user_id',
            'specialty_id'    => 'nullable|exists:specialties,id',
            'medical_license' => 'nullable|string|max:100',
            'biography'       => 'nullable|string|max:1000',
        ]);

        // Normalize empty strings to null to preserve FK integrity
        $data['specialty_id']    = $data['specialty_id']    ?: null;
        $data['medical_license'] = $data['medical_license'] ?: null;
        $data['biography']       = $data['biography']       ?: null;

        Doctor::create($data);

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Doctor creado',
            'text'  => 'El doctor ha sido registrado exitosamente',
        ]);

        return redirect()->route('admin.doctors.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Doctor $doctor)
    {
        $specialties = Specialty::orderBy('name')->get();
        return view('admin.doctors.edit', compact('doctor', 'specialties'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Doctor $doctor)
    {
        $data = $request->validate([
            'specialty_id'    => 'nullable|exists:specialties,id',
            'medical_license' => 'nullable|string|max:100',
            'biography'       => 'nullable|string|max:1000',
        ]);

        // Normalize empty strings to null to preserve FK integrity
        $data['specialty_id']    = $data['specialty_id']    ?: null;
        $data['medical_license'] = $data['medical_license'] ?: null;
        $data['biography']       = $data['biography']       ?: null;

        $doctor->update($data);

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Doctor actualizado',
            'text'  => 'La información del doctor ha sido actualizada exitosamente',
        ]);

        return redirect()->route('admin.doctors.edit', $doctor);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Doctor $doctor)
    {
        $doctor->delete();

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Doctor eliminado',
            'text'  => 'El doctor ha sido eliminado exitosamente',
        ]);

        return redirect()->route('admin.doctors.index');
    }
}
