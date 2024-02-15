<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        \App\Models\User::create([
            'name' => 'KIBOKO SRL',
            'email' => 'info@kiboko.it',
            'type' => 'admin',
            'password' => '$2y$10$NQ36WazKgeJE7FfvMzFSauZ7x8sH77fWRDM51JGaowvtRzrk9IYWu',
        ]);

        \App\Models\User::create([
            'name' => 'Matteo Schiona',
            'email' => 'schiona.matteo@gmail.com',
            'type' => 'operator',
            'password' => '$2y$10$NjTAkhk3jO8HuIjI.HJ.xepf3fy.h6ITsWqQoIflKg90mgDDsOXPW',
        ]);
    }
}
