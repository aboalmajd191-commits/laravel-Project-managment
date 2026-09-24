<?php

// database/seeders/AdminSeeder.php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@ngo.ps'],
            [
                'name'      => 'مدير النظام',
                'password'  => Hash::make('Admin@123456'),
                'role'      => 'admin',
                'is_active' => true,
            ]
        );
    }
}
