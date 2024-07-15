<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::create([
            'role_name' => 'superadmin',
        ]);

        Role::create([
            'role_name' => 'pegawai',
        ]);
        
        User::factory(5)->create();

    }
}
