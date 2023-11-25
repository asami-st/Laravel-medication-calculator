<?php

namespace Database\Seeders;

use App\Models\Patient;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PatientSeeder extends Seeder
{
    private $patient;

    public function __construct(Patient $patient)
    {
        $this->patient = $patient;
    }
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->patient->user_id  = 1;
        $this->patient->name     = 'テスト患者';
        $this->patient->save();
    }
}
