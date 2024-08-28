<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('subjects')->truncate();
        $names = [
            'Aerodinámica',
            'Aeronaves y motores',
            'Meteorología',
            'Telecomunicaciones'
        ];
        foreach($names as $subject) {
            \DB::table('subjects')->insert(
                [
                    'name' => $subject
                ]
            );
        }
    }
}
