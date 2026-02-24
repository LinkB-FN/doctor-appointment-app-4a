<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Specialty;

class SpecialtySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specialties = [
            'Cardiología',
            'Pediatría',
            'Neurología',
            'Dermatología',
            'Ortopedia',
            'Ginecología',
            'Oftalmología',
            'Psiquiatría',
            'Oncología',
            'Medicina General',
        ];

        foreach ($specialties as $specialty) {
            Specialty::firstOrCreate([
                'name' => $specialty,
            ]);
        }
    }
}
