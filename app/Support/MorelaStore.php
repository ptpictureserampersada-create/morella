<?php

namespace App\Support;

class MorelaStore
{
    protected static ?array $data = null;

    public static function load(): array
    {
        if (self::$data !== null) {
            return self::$data;
        }

        $file = self::path();

        if (file_exists($file)) {
            self::$data = json_decode((string) file_get_contents($file), true);
        } else {
            self::$data = self::defaults();
            self::persist();
        }

        return self::$data;
    }

    public static function persist(): void
    {
        file_put_contents(
            self::path(),
            json_encode(self::$data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );
    }

    public static function defaults(): array
    {
        $seed = json_decode((string) file_get_contents(storage_path('app/morela-seed.json')), true);

        return array_merge($seed, [
            'paymentSettings' => [
                'rekeningBCA' => '0987654321 a.n Desa Morela',
                'rekeningMandiri' => '1234567890 a.n Pariwisata Morela',
                'rekeningMaluku' => '1122334455 a.n Kas Desa Morela',
                'qrisUrl' => 'https://upload.wikimedia.org/wikipedia/commons/d/d0/QR_code_for_mobile_English_Wikipedia.svg',
            ],
            'heroSliders' => [
                'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1920&q=85',
                'https://images.unsplash.com/photo-1510414842594-a61c69b5ae57?auto=format&fit=crop&w=1920&q=85',
            ],
            'contactInfo' => [
                'address' => 'Negeri Morela, Kec. Leihitu, Kab. Maluku Tengah, Maluku',
                'phone' => '+62 812-3456-7800 (Sekretariat Negeri)',
            ],
            'heroText' => [
                'badge' => 'Portal Digital Pariwisata Resmi Desa Morela',
                'badgeEn' => 'Official Digital Tourism Portal of Morela Village',
                'title' => 'SELAMAT DATANG DI MORELA',
                'titleEn' => 'WELCOME TO MORELA',
                'tagline' => '“Jelajah Pesona Morela – Alam, Budaya & Masyarakat”',
                'taglineEn' => '“Explore the Charms of Morela – Nature, Culture & Community”',
                'description' => 'Temukan keindahan alam pesisir Leihitu, sakralnya tradisi Pukul Sapu, dan keramahan hangat masyarakat Negeri Morela, Maluku Tengah.',
                'descriptionEn' => 'Discover Leihitu’s coastal azure waters, sacred centuries-old broom whip traditions, and warm Maluku hospitality.',
            ],
            'visitLog' => [],
        ]);
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $data = self::load();

        return $data[$key] ?? $default;
    }

    public static function set(string $key, mixed $value): void
    {
        $data = self::load();
        $data[$key] = $value;
        self::$data = $data;
        self::persist();
    }

    public static function merge(string $key, array $patch): void
    {
        $data = self::load();
        $data[$key] = array_merge(is_array($data[$key] ?? null) ? $data[$key] : [], $patch);
        self::$data = $data;
        self::persist();
    }

    public static function addEntity(string $key, array $item): void
    {
        $data = self::load();
        array_unshift($data[$key], $item);
        self::$data = $data;
        self::persist();
    }

    public static function updateEntity(string $key, string $id, array $patch): bool
    {
        $data = self::load();

        foreach ($data[$key] as $index => $item) {
            if (($item['id'] ?? null) === $id) {
                $data[$key][$index] = array_merge($item, $patch);
                self::$data = $data;
                self::persist();

                return true;
            }
        }

        return false;
    }

    public static function deleteEntity(string $key, string $id): bool
    {
        $data = self::load();
        $before = count($data[$key]);
        $data[$key] = array_values(array_filter($data[$key], fn ($item) => ($item['id'] ?? null) !== $id));

        if (count($data[$key]) === $before) {
            return false;
        }

        self::$data = $data;
        self::persist();

        return true;
    }

    // Mencatat pengunjung unik (per browser) pada bulan berjalan (WIT).
    public static function registerMonthlyVisit(string $visitorId): int
    {
        $data = self::load();
        $month = now('Asia/Jayapura')->format('Y-m');
        $log = is_array($data['visitLog'] ?? null) ? $data['visitLog'] : [];
        $visitors = $log[$month] ?? [];

        if (! in_array($visitorId, $visitors, true)) {
            $visitors[] = $visitorId;
            $log[$month] = $visitors;
            $data['visitLog'] = array_slice($log, -12, null, true);
            self::$data = $data;
            self::persist();
        }

        return count($visitors);
    }

    // Rekap jumlah pengunjung unik untuk N bulan terakhir (termasuk bulan berjalan).
    public static function visitMonths(int $count = 3): array
    {
        $log = self::get('visitLog', []);
        $months = [];

        for ($i = $count - 1; $i >= 0; $i--) {
            $month = now('Asia/Jayapura')->startOfMonth()->subMonths($i)->format('Y-m');
            $months[] = [
                'month' => $month,
                'count' => is_array($log[$month] ?? null) ? count($log[$month]) : 0,
            ];
        }

        return $months;
    }

    public static function reset(): void
    {
        self::$data = self::defaults();
        self::persist();
    }

    protected static function path(): string
    {
        return storage_path('app/morela-data.json');
    }
}
