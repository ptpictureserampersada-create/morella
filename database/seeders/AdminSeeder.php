<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $email = 'admin@morela.com';
        $password = 'password123';

        $userExists = DB::table('users')->where('email', $email)->first();

        if (!$userExists) {
            DB::table('users')->insert([
                'name' => 'Administrator',
                'email' => $email,
                'password' => Hash::make($password),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->command->info('=====================================');
            $this->command->info('Admin berhasil dibuat di Database!');
            $this->command->info('Email    : ' . $email);
            $this->command->info('Password : ' . $password);
            $this->command->info('=====================================');
        } else {
            $this->command->warn('Admin dengan email ' . $email . ' sudah ada di database.');
        }
    }
}
