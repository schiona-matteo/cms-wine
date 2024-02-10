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
        // \App\Models\User::factory(10)->create();

        \App\Models\User::create([
            'name' => 'Matteo Schiona',
            'email' => 'dev@heki.io',
            'password' => '$2y$10$NQ36WazKgeJE7FfvMzFSauZ7x8sH77fWRDM51JGaowvtRzrk9IYWu',
        ]);
    }
}
