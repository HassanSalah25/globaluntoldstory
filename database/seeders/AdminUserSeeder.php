<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->updateOrCreate(
            ['email' => 'admin@globaluntoldstory.com'],
            [
                'name' => 'Super Admin',
                'password' => 'password',
            ]
        );

        $user->syncRoles(['super-admin']);
    }
}
