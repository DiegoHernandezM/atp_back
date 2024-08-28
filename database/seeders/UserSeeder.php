<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert(
            [
                'name' => 'Diego Hernandez',
                'email' => 'admin@admin.com',
                'password' => Hash::make('secret'),
                'type_id' => 1,
                'created_by_admin' => 0,
                'stand_by' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Student Demo',
                'email' => 'student@demo.com',
                'password' => Hash::make('secret'),
                'expires_at' => Carbon::parse('2025-12-12')->format('Y-m-d'),
                'school' => 'Demo School',
                'type_id' => 1,
                'created_by_admin' => 1,
                'stand_by' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        );
    }
}
