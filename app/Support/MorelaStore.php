<?php

namespace App\Support;

use App\Models\Booking;
use App\Models\CultureItem;
use App\Models\Destination;
use App\Models\Event;
use App\Models\GalleryItem;
use App\Models\MapMarker;
use App\Models\NewsArticle;
use App\Models\PortalSetting;
use App\Models\TeamMember;
use App\Models\UmkmProduct;
use App\Models\VisitLog;
use Illuminate\Database\Eloquent\Model;

// Penyimpanan data portal berbasis database (MySQL).
// API publik sengaja dipertahankan sama seperti versi file JSON sebelumnya,
// sehingga controller dan view tidak perlu diubah.
class MorelaStore
{
    protected const ENTITY_MODELS = [
        'destinations' => Destination::class,
        'umkm' => UmkmProduct::class,
        'news' => NewsArticle::class,
        'events' => Event::class,
        'culture' => CultureItem::class,
        'gallery' => GalleryItem::class,
        'team' => TeamMember::class,
        'bookings' => Booking::class,
        'mapMarkers' => MapMarker::class,
    ];

    protected const SETTING_KEYS = ['paymentSettings', 'heroSliders', 'contactInfo', 'heroText'];

    protected static ?array $data = null;

    protected static array $columns = [];

    public static function load(): array
    {
        if (self::$data !== null) {
            return self::$data;
        }

        $data = [];

        foreach (self::ENTITY_MODELS as $key => $model) {
            $data[$key] = array_map(
                fn (Model $row) => $row->toArray(),
                $model::query()->orderBy('sort_order')->orderBy('id')->get()->all()
            );
        }

        $defaults = self::defaults();
        $stored = PortalSetting::query()
            ->get()
            ->mapWithKeys(fn (PortalSetting $row) => [$row->key => $row->value])
            ->all();

        foreach (self::SETTING_KEYS as $key) {
            $data[$key] = is_array($stored[$key] ?? null) ? $stored[$key] : ($defaults[$key] ?? []);
        }

        self::$data = $data;

        return $data;
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

    // Mengisi tabel dengan data default (dari morela-seed.json + pengaturan pabrik).
    // Idempotent per tabel kecuali $force = true (mengganti seluruh isi, dipakai reset()).
    public static function seedDefaults(bool $force = false): void
    {
        $defaults = self::defaults();

        foreach (self::ENTITY_MODELS as $key => $model) {
            if ($force) {
                $model::query()->delete();
            } elseif ($model::query()->exists()) {
                continue;
            }

            foreach (array_values($defaults[$key] ?? []) as $index => $item) {
                $attributes = self::filterColumns($model, $item);
                $attributes['id'] = (string) $item['id'];
                $attributes['sort_order'] = $index;
                $model::query()->create($attributes);
            }
        }

        if ($force) {
            VisitLog::query()->delete();
        }

        foreach (self::SETTING_KEYS as $key) {
            if ($force || ! PortalSetting::query()->whereKey($key)->exists()) {
                self::putSetting($key, $defaults[$key] ?? []);
            }
        }

        self::$data = null;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $data = self::load();

        return $data[$key] ?? $default;
    }

    public static function set(string $key, mixed $value): void
    {
        if (isset(self::ENTITY_MODELS[$key])) {
            self::replaceEntity($key, is_array($value) ? $value : []);
        } elseif (in_array($key, self::SETTING_KEYS, true)) {
            self::putSetting($key, $value);
        }

        self::$data = null;
    }

    public static function merge(string $key, array $patch): void
    {
        if (! in_array($key, self::SETTING_KEYS, true)) {
            return;
        }

        $current = self::get($key, []);
        self::putSetting($key, array_merge(is_array($current) ? $current : [], $patch));
        self::$data = null;
    }

    public static function addEntity(string $key, array $item): void
    {
        $model = self::ENTITY_MODELS[$key] ?? null;

        if ($model === null) {
            return;
        }

        $attributes = self::filterColumns($model, $item);
        $attributes['id'] = (string) $item['id'];
        $min = $model::query()->min('sort_order');
        $attributes['sort_order'] = is_null($min) ? 0 : ((int) $min) - 1;

        $model::query()->create($attributes);
        self::$data = null;
    }

    public static function updateEntity(string $key, string $id, array $patch): bool
    {
        $model = self::ENTITY_MODELS[$key] ?? null;

        if ($model === null) {
            return false;
        }

        $row = $model::query()->find($id);

        if ($row === null) {
            return false;
        }

        unset($patch['id']);
        $row->fill(self::filterColumns($model, $patch));
        $row->save();
        self::$data = null;

        return true;
    }

    public static function deleteEntity(string $key, string $id): bool
    {
        $model = self::ENTITY_MODELS[$key] ?? null;

        if ($model === null) {
            return false;
        }

        $deleted = (int) $model::query()->whereKey($id)->delete();
        self::$data = null;

        return $deleted > 0;
    }

    // Mencatat pengunjung unik (per browser) pada bulan berjalan (WIT).
    public static function registerMonthlyVisit(string $visitorId): int
    {
        $month = now('Asia/Jayapura')->format('Y-m');

        VisitLog::query()->insertOrIgnore([
            'month' => $month,
            'visitorId' => $visitorId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return VisitLog::query()->where('month', $month)->count();
    }

    // Rekap jumlah pengunjung unik untuk N bulan terakhir (termasuk bulan berjalan).
    public static function visitMonths(int $count = 3): array
    {
        $months = [];

        for ($i = $count - 1; $i >= 0; $i--) {
            $month = now('Asia/Jayapura')->startOfMonth()->subMonths($i)->format('Y-m');
            $months[] = [
                'month' => $month,
                'count' => VisitLog::query()->where('month', $month)->count(),
            ];
        }

        return $months;
    }

    public static function reset(): void
    {
        self::seedDefaults(true);
    }

    protected static function replaceEntity(string $key, array $items): void
    {
        $model = self::ENTITY_MODELS[$key];
        $model::query()->delete();

        foreach (array_values($items) as $index => $item) {
            $attributes = self::filterColumns($model, $item);
            $attributes['id'] = (string) $item['id'];
            $attributes['sort_order'] = $index;
            $model::query()->create($attributes);
        }
    }

    protected static function putSetting(string $key, mixed $value): void
    {
        PortalSetting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
    }

    // Menyaring atribut agar hanya kolom tabel yang benar-benar ada yang tersimpan
    // (mis. field _token dari form otomatis dibuang).
    protected static function filterColumns(string $model, array $attributes): array
    {
        $instance = new $model;
        $table = $instance->getTable();

        $columns = self::$columns[$table] ??= $instance->getConnection()
            ->getSchemaBuilder()
            ->getColumnListing($table);

        return array_intersect_key($attributes, array_flip($columns));
    }
}
