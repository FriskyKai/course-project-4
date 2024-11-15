<?php

namespace Database\Seeders;

use App\Models\Role;
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
        $role_admin = Role::where('code', 'admin')->first();
        $role_user = Role::where('code', 'user')->first();

        User::create([
            'username' => 'Admin228',
            'email' => 'admin@mail.ru',
            'password' => 'admin123',
            'api_token' => '1',
            'role_id' => $role_admin->id,
        ]);

        User::create([
            'username' => 'User337',
            'email' => 'user@mail.ru',
            'password' => 'user123',
            'api_token' => '2',
            'role_id' => $role_user->id,
        ]);
    }
}
