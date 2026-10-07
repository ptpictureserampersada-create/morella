<?php

namespace Database\Seeders;

use App\Support\MorelaStore;
use Illuminate\Database\Seeder;

// Mengisi tabel portal dari data default (morela-seed.json + pengaturan pabrik).
// Idempotent: tabel yang sudah berisi data tidak ditimpa.
class MorelaSeeder extends Seeder
{
    public function run(): void
    {
        MorelaStore::seedDefaults();
        $this->command->info('Data portal Morela berhasil di-seed ke database.');
    }
}
