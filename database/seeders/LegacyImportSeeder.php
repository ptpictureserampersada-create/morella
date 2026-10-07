<?php

namespace Database\Seeders;

use App\Models\VisitLog;
use App\Support\MorelaStore;
use Illuminate\Database\Seeder;

// Impor satu kali dari penyimpanan lama (storage/app/morela-data.json) ke database,
// agar pemesanan, kunjungan, dan konten yang sudah nyata tidak hilang saat pindah ke MySQL.
// Jalankan sekali: php artisan db:seed --class=LegacyImportSeeder
class LegacyImportSeeder extends Seeder
{
    public function run(): void
    {
        $file = storage_path('app/morela-data.json');

        if (! file_exists($file)) {
            $this->command->warn('File morela-data.json tidak ditemukan — impor data lama dilewati.');

            return;
        }

        $legacy = json_decode((string) file_get_contents($file), true) ?? [];

        foreach (array_keys(MorelaStore::defaults()) as $key) {
            if (! in_array($key, ['visitLog'], true) && isset($legacy[$key]) && is_array($legacy[$key])) {
                MorelaStore::set($key, $legacy[$key]);
                $this->command->info($key.': '.count($legacy[$key]).' baris diimpor.');
            }
        }

        $visits = 0;

        foreach ($legacy['visitLog'] ?? [] as $month => $visitors) {
            foreach ((array) $visitors as $visitorId) {
                VisitLog::query()->insertOrIgnore([
                    'month' => $month,
                    'visitorId' => (string) $visitorId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $visits++;
            }
        }

        $this->command->info("visitLog: {$visits} kunjungan diimpor.");
    }
}
