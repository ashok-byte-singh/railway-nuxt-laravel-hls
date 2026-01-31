<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Experiment; // ✅ THIS LINE FIXES IT
class ExperimentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $experiments = [
            [
                'title' => 'Ohm’s Law',
                'aim' => 'To verify Ohm’s Law.',
                'objective' => 'Study the relationship between voltage and current.',
                'theory' => 'Ohm’s Law states that current is directly proportional to voltage.',
                'procedure' => 'Connect the circuit, vary voltage, measure current.',
                'video_url' => 'https://www.example.com/videos/ohms-law.mp4',
                'is_active' => true,
            ],
            [
                'title' => 'Verification of Kirchhoff’s Laws',
                'aim' => 'To verify Kirchhoff’s Voltage and Current Laws.',
                'objective' => 'Analyze current and voltage in a closed circuit.',
                'theory' => 'Kirchhoff’s laws are fundamental rules for electrical circuits.',
                'procedure' => 'Construct circuit and verify sum of currents and voltages.',
                'video_url' => null,
                'is_active' => true,
            ],
            [
                'title' => 'PN Junction Diode Characteristics',
                'aim' => 'To study V-I characteristics of a PN junction diode.',
                'objective' => 'Observe forward and reverse bias behavior.',
                'theory' => 'A diode allows current in one direction.',
                'procedure' => 'Apply forward and reverse bias and measure current.',
                'video_url' => null,
                'is_active' => true,
            ],
        ];

        foreach ($experiments as $experiment) {
            Experiment::create($experiment);
        }
    }
}
