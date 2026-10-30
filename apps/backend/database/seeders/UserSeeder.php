<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            "email" => "haidarbahzi07@gmail.com",
            "username" => "Admin",
            "fullname" => "Admin",
            "password" => "1234567890",
        ]);
    }
}
