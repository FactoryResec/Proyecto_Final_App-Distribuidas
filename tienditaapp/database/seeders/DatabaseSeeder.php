<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Cesar Abarca',
            'email' => 'cesar0in.16@gmail.com',
            'password' => Hash::make('rancher15'),
            'rol' => 'administrador', 
        ]);
        
        User::create([
            'name' => 'Luis Godinez',
            'email' => 'LuisGodi@gmail.com',
            'password' => Hash::make('Zumpango123'),
            'rol' => 'empleado', 
        ]);
        
        User::create([
            'name' => 'Amairani',
            'email' => 'Amairani@gmail.com',
            'password' => Hash::make('Amairani123'),
            'rol' => 'empleado', 
        ]);

        User::create([
            'name' => 'Gary',
            'email' => 'Gary@gmail.com',
            'password' => Hash::make('Gary123'),
            'rol' => 'empleado', 
        ]);
    }
}
