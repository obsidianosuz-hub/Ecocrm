<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'Master Admin',
            'email' => 'master@obsidian.uz',
            'password' => \Illuminate\Support\Facades\Hash::make('master123'),
            'role' => 'admin',
            'is_master' => true,
            'approval_status' => 'approved',
        ]);
    }
}
