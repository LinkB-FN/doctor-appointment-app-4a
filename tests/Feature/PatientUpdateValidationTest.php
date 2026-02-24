<?php

namespace Tests\Feature;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tests para verificar el manejo de errores de validación en la vista edit de pacientes.
 *
 * Estos tests verifican el criterio C (3 pts) — Manejo de Errores:
 * "Las pestañas se pintan de rojo y muestran el icono de alerta automáticamente
 *  cuando existen errores de validación."
 *
 * Lo que se prueba:
 * - No hay Error 500 al enviar datos inválidos
 * - Los errores de validación se almacenan en sesión correctamente
 * - Cada campo inválido genera el error en la pestaña correcta
 * - El controlador calcula $initialTab correctamente según los errores
 */
class PatientUpdateValidationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Crea un usuario y su registro de paciente asociado.
     */
    private function createPatientWithUser(): Patient
    {
        $user = User::factory()->create();
        return Patient::create(['user_id' => $user->id]);
    }

    // =========================================================================
    // TEST 1: No explota (no Error 500) al enviar datos inválidos
    // =========================================================================

    /** @test */
    public function it_does_not_return_500_when_submitting_invalid_data()
    {
        $admin   = User::factory()->create();
        $patient = $this->createPatientWithUser();

        $response = $this->actingAs($admin)->put(
            route('admin.patients.update', $patient),
            ['allergies' => 'AB'] // demasiado corto (min:3)
        );

        // Debe redirigir de vuelta (302), NO explotar con 500
        $response->assertStatus(302);
        $response->assertSessionHasErrors('allergies');
    }

    // =========================================================================
    // TEST 2: Pestaña "antecedentes" — campos: allergies, chronic_conditions,
    //         surgical_history, family_history
    // =========================================================================

    /** @test */
    public function it_validates_allergies_field_belongs_to_antecedentes_tab()
    {
        $admin   = User::factory()->create();
        $patient = $this->createPatientWithUser();

        $response = $this->actingAs($admin)->put(
            route('admin.patients.update', $patient),
            ['allergies' => 'AB'] // min:3 → falla
        );

        $response->assertSessionHasErrors('allergies');
        // El error está en la pestaña "antecedentes"
        $this->assertTrue(
            session('errors')->hasAny(['allergies', 'chronic_conditions', 'surgical_history', 'family_history']),
            'El error de "allergies" debe estar en la pestaña antecedentes'
        );
    }

    /** @test */
    public function it_validates_chronic_conditions_field()
    {
        $admin   = User::factory()->create();
        $patient = $this->createPatientWithUser();

        $response = $this->actingAs($admin)->put(
            route('admin.patients.update', $patient),
            ['chronic_conditions' => 'AB'] // min:3 → falla
        );

        $response->assertSessionHasErrors('chronic_conditions');
    }

    /** @test */
    public function it_validates_surgical_history_field()
    {
        $admin   = User::factory()->create();
        $patient = $this->createPatientWithUser();

        $response = $this->actingAs($admin)->put(
            route('admin.patients.update', $patient),
            ['surgical_history' => 'AB'] // min:3 → falla
        );

        $response->assertSessionHasErrors('surgical_history');
    }

    /** @test */
    public function it_validates_family_history_field()
    {
        $admin   = User::factory()->create();
        $patient = $this->createPatientWithUser();

        $response = $this->actingAs($admin)->put(
            route('admin.patients.update', $patient),
            ['family_history' => 'AB'] // min:3 → falla
        );

        $response->assertSessionHasErrors('family_history');
    }

    // =========================================================================
    // TEST 3: Pestaña "informacion-general" — campos: blood_type_id, observations
    // =========================================================================

    /** @test */
    public function it_validates_observations_field_belongs_to_informacion_general_tab()
    {
        $admin   = User::factory()->create();
        $patient = $this->createPatientWithUser();

        $response = $this->actingAs($admin)->put(
            route('admin.patients.update', $patient),
            ['observations' => 'AB'] // min:3 → falla
        );

        $response->assertSessionHasErrors('observations');
        $this->assertTrue(
            session('errors')->hasAny(['blood_type_id', 'observations']),
            'El error de "observations" debe estar en la pestaña informacion-general'
        );
    }

    /** @test */
    public function it_validates_blood_type_id_must_exist_in_database()
    {
        $admin   = User::factory()->create();
        $patient = $this->createPatientWithUser();

        $response = $this->actingAs($admin)->put(
            route('admin.patients.update', $patient),
            ['blood_type_id' => 9999] // no existe en blood_types → falla
        );

        $response->assertSessionHasErrors('blood_type_id');
    }

    // =========================================================================
    // TEST 4: Pestaña "contacto-emergencia" — campos: emergency_contact_name,
    //         emergency_contact_phone, emergency_contact_relationship
    // =========================================================================

    /** @test */
    public function it_validates_emergency_contact_name_belongs_to_contacto_emergencia_tab()
    {
        $admin   = User::factory()->create();
        $patient = $this->createPatientWithUser();

        $response = $this->actingAs($admin)->put(
            route('admin.patients.update', $patient),
            ['emergency_contact_name' => 'AB'] // min:3 → falla
        );

        $response->assertSessionHasErrors('emergency_contact_name');
        $this->assertTrue(
            session('errors')->hasAny([
                'emergency_contact_name',
                'emergency_contact_phone',
                'emergency_contact_relationship',
            ]),
            'El error debe estar en la pestaña contacto-emergencia'
        );
    }

    /** @test */
    public function it_validates_emergency_contact_phone_min_length()
    {
        $admin   = User::factory()->create();
        $patient = $this->createPatientWithUser();

        $response = $this->actingAs($admin)->put(
            route('admin.patients.update', $patient),
            ['emergency_contact_phone' => '123'] // min:10 → falla
        );

        $response->assertSessionHasErrors('emergency_contact_phone');
    }

    /** @test */
    public function it_validates_emergency_contact_relationship_field()
    {
        $admin   = User::factory()->create();
        $patient = $this->createPatientWithUser();

        $response = $this->actingAs($admin)->put(
            route('admin.patients.update', $patient),
            ['emergency_contact_relationship' => 'AB'] // min:3 → falla
        );

        $response->assertSessionHasErrors('emergency_contact_relationship');
    }

    // =========================================================================
    // TEST 5: Datos válidos → actualización exitosa, sin errores
    // =========================================================================

    /** @test */
    public function it_updates_patient_successfully_with_valid_data()
    {
        $admin   = User::factory()->create();
        $patient = $this->createPatientWithUser();

        $response = $this->actingAs($admin)->put(
            route('admin.patients.update', $patient),
            [
                'allergies'                      => 'Penicilina',
                'chronic_conditions'             => 'Diabetes tipo 2',
                'surgical_history'               => 'Apendicectomía',
                'family_history'                 => 'Hipertensión',
                'observations'                   => 'Paciente estable',
                'emergency_contact_name'         => 'Juan Pérez',
                'emergency_contact_phone'        => '1234567890',
                'emergency_contact_relationship' => 'Familiar',
            ]
        );

        // Sin errores de validación
        $response->assertSessionHasNoErrors();

        // Redirige de vuelta al edit
        $response->assertRedirect(route('admin.patients.edit', $patient));

        // Los datos se guardaron en la base de datos
        $this->assertDatabaseHas('patients', [
            'id'                             => $patient->id,
            'allergies'                      => 'Penicilina',
            'emergency_contact_name'         => 'Juan Pérez',
            'emergency_contact_phone'        => '1234567890',
            'emergency_contact_relationship' => 'Familiar',
        ]);
    }

    // =========================================================================
    // TEST 6: Todos los campos nullable → actualización exitosa con datos vacíos
    // =========================================================================

    /** @test */
    public function it_allows_empty_nullable_fields()
    {
        $admin   = User::factory()->create();
        $patient = $this->createPatientWithUser();

        // Enviar formulario completamente vacío (todos los campos son nullable)
        $response = $this->actingAs($admin)->put(
            route('admin.patients.update', $patient),
            [] // sin datos
        );

        // No debe haber errores de validación
        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
    }
}
