<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //3 admins
        User::factory()->count(3)->admin()->create();

        //3 managers
        User::factory()->count(3)->manager()->create();

        //5 users
        User::factory()->count(5)->create();
    }
}
