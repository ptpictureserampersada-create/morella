<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $username = 'admin';
        $email = 'admin@morela.com';
        $password = 'password123';

        $admin = User::where('username', $username)->orWhere('email', $email)->first();

        if ($admin) {
            $admin->update([
                'name' => 'Administrator',
                'username' => $username,
                'email' => $email,
                'password' => $password,
            ]);

            $this->command->warn('Admin sudah ada — kredensial disinkronkan ulang.');
        } else {
            User::create([
                'name' => 'Administrator',
                'username' => $username,
                'email' => $email,
                'password' => $password,
            ]);

            $this->command->info('Admin berhasil dibuat di Database!');
        }

        $this->command->info('=====================================');
        $this->command->info('Username : ' . $username);
        $this->command->info('Email    : ' . $email);
        $this->command->info('Password : ' . $password);
        $this->command->info('=====================================');
    }
}
