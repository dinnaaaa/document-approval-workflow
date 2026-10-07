<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'Admin',
            'Staff',
            'Supervisor',
            'Manager',
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['name' => $role],
                [
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}