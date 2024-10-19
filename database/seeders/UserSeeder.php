<?php

namespace Database\Seeders;

use App\Models\Client;
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
        $client = Client::factory()->create([
            'fname' => "Admin",
            'lname' => "User"
        ]);

        User::factory()->create([
            'email' => 'admin@gmail.com',
            'client_id' => $client->id,
            'isAdmin' => 1,
            'contact_number' => "09123456789",
            'username' => 'Admin'
        ]);
    }
}
