<?php

namespace Database\Seeders;

use App\Models\User;
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
        User::create([
            'role_id' => '999',
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'superadmin@gamil.com',
            'password' => Hash::make('admin@123'),
        ]);
    }
}
