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
            'surname' => '228',
            'name' => 'Admin',
            'username' => 'Admin228',
            'password' => 'admin123',
            'email' => 'admin@mail.ru',
            'phone' => '88005553530',
            'api_token' => '1',
            'role_id' => $role_admin->id,
        ]);

        User::create([
            'surname' => '337',
            'name' => 'User',
            'username' => 'User337',
            'password' => 'user123',
            'email' => 'user@mail.ru',
            'phone' => '88005553531',
            'api_token' => '2',
            'role_id' => $role_user->id,
        ]);
    }
}
