<?php

namespace App\Http\Controllers;

use App\Support\MorelaStore;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ActionController extends Controller
{
    protected const ENTITY_KEYS = [
        'destinasi' => 'destinations',
        'umkm' => 'umkm',
        'berita' => 'news',
        'agenda' => 'events',
        'budaya' => 'culture',
        'galeri' => 'gallery',
        'tim' => 'team',
    ];

    // ---------------------------------------------------------------
    // Tiket (dipanggil via fetch/AJAX dari halaman /tiket)
    // ---------------------------------------------------------------

    public function bookTicket(Request $request): JsonResponse
    {
        $data = $request->validate([
            'destinationId' => 'required|string',
            'destinationName' => 'required|string',
            'destinationLocation' => 'required|string',
            'visitDate' => 'required|string',
            'visitorName' => 'required|string',
            'visitorEmail' => 'required|string',
            'visitorPhone' => 'required|string',
            'visitorCity' => 'required|string',
            'adultCount' => 'required|integer|min:1',
            'childCount' => 'required|integer|min:0',
            'pricePerTicket' => 'required|integer|min:0',
            'cleanlinessFee' => 'required|integer|min:0',
            'totalAmount' => 'required|integer|min:0',
            'paymentMethod' => 'required|string',
        ]);

        $booking = array_merge($data, [
            'id' => 'tkt-'.round(microtime(true) * 1000),
            'bookingCode' => 'MOR-'.now()->format('ymd').'-'.random_int(1000, 9999),
            'paymentStatus' => 'pending',
            'createdAt' => now(config('app.timezone'))->setTimezone('Asia/Jayapura')->format('d/m/Y, H.i').' WIT',
            'qrValidationCode' => '',
        ]);
        $booking['qrValidationCode'] = 'VALID-'.$booking['bookingCode'];
        $booking['paidAt'] = null;

        MorelaStore::addEntity('bookings', $booking);

        return response()->json($booking);
    }

    public function confirmTicketPayment(Request $request): JsonResponse
    {
        $request->validate(['bookingCode' => 'required|string']);

        $code = strtoupper(trim((string) $request->input('bookingCode')));
        $bookings = MorelaStore::get('bookings', []);

        // Seperti SPA asli: simulasi konfirmasi hanya mengubah tampilan lokal,
        // status tersimpan tetap 'pending' sampai admin menekan "Set Lunas".
        foreach ($bookings as $booking) {
            if (strtoupper($booking['bookingCode']) === $code) {
                $nowStr = now()->setTimezone('Asia/Jayapura')->format('d/m/Y, H.i').' WIT';

                return response()->json(array_merge($booking, [
                    'paymentStatus' => 'paid',
                    'paidAt' => $nowStr,
                ]));
            }
        }

        return response()->json(['message' => 'Booking tidak ditemukan'], 404);
    }

    public function checkInTicket(Request $request): JsonResponse
    {
        $request->validate(['code' => 'required|string']);

        $code = strtoupper(trim((string) $request->input('code')));
        $bookings = MorelaStore::get('bookings', []);
        $found = false;

        foreach ($bookings as $index => $booking) {
            if (strtoupper($booking['bookingCode']) === $code) {
                $found = true;
                $nowStr = now()->setTimezone('Asia/Jayapura')->format('d/m/Y, H.i').' WIT';
                $bookings[$index]['paymentStatus'] = 'checked_in';
                $bookings[$index]['checkedInAt'] = $nowStr;

                break;
            }
        }

        if (! $found) {
            return response()->json(['success' => false]);
        }

        MorelaStore::set('bookings', $bookings);

        return response()->json(['success' => true]);
    }

    // ---------------------------------------------------------------
    // Admin: CRUD entitas
    // ---------------------------------------------------------------

    public function createEntity(Request $request, string $entity): RedirectResponse
    {
        $key = self::ENTITY_KEYS[$entity] ?? null;

        if ($key === null) {
            abort(404);
        }

        $now = round(microtime(true) * 1000);
        $item = $request->except('video');

        foreach (['published', 'featured', 'inStock'] as $boolKey) {
            if (array_key_exists($boolKey, $item)) {
                $item[$boolKey] = filter_var($item[$boolKey], FILTER_VALIDATE_BOOLEAN);
            }
        }

        if ($request->hasFile('video')) {
            $file = $request->file('video');

            if (! $file->isValid()) {
                return back()->with('morela_alert', 'Gagal mengunggah video: file tidak dapat diproses. Silakan pilih ulang file video.');
            }

            $sizeKb = $file->getSize() / 1024;
            $extension = strtolower($file->getClientOriginalExtension());
            $allowedExtensions = ['mp4', 'm4v', 'webm', 'ogg', 'ogv', 'mov', 'avi', 'mkv'];

            if (! str_starts_with((string) $file->getMimeType(), 'video/') && ! in_array($extension, $allowedExtensions, true)) {
                return back()->with('morela_alert', 'Gagal mengunggah video: format tidak didukung. Gunakan MP4, WebM, MOV, AVI, atau MKV.');
            }

            if ($sizeKb < 2048 || $sizeKb > 512000) {
                return back()->with('morela_alert', 'Gagal mengunggah video: ukuran file harus antara 2 MB dan 500 MB.');
            }

            $item['videoUrl'] = Storage::url($file->store('videos', 'public'));
        }

        switch ($key) {
            case 'destinations':
                $item['id'] = "dest-{$now}";
                $item['slug'] = $this->slugify((string) ($item['name'] ?? ''));
                $item['coordinates'] = $item['coordinates'] ?? ['lat' => -3.582, 'lng' => 128.084, 'mapX' => 40, 'mapY' => 35];
                $item['galleryImages'] = $item['galleryImages'] ?? [];
                $item['facilities'] = $item['facilities'] ?? ['Area Parkir', 'Gazebo', 'Toilet'];
                break;
            case 'umkm':
                $item['id'] = "umkm-{$now}";
                break;
            case 'news':
                $item['id'] = "news-{$now}";
                $item['slug'] = $this->slugify((string) ($item['title'] ?? ''));
                break;
            case 'events':
                $item['id'] = "evt-{$now}";
                break;
            case 'culture':
                $item['id'] = "cult-{$now}";
                $item['slug'] = $this->slugify((string) ($item['title'] ?? ''));
                $item['tags'] = $item['tags'] ?? [];
                break;
            case 'gallery':
                $item['id'] = "gal-{$now}";
                break;
            case 'team':
                $item['id'] = "team-{$now}";
                break;
        }

        MorelaStore::addEntity($key, $item);

        return back()->with('morela_alert', $this->createAlertMessage($key));
    }

    public function updateEntity(Request $request, string $entity, string $id): RedirectResponse
    {
        $key = self::ENTITY_KEYS[$entity] ?? null;

        if ($key === null) {
            abort(404);
        }

        $patch = $request->all();
        unset($patch['_token']);

        foreach (['published', 'featured', 'inStock'] as $boolKey) {
            if (array_key_exists($boolKey, $patch)) {
                $patch[$boolKey] = filter_var($patch[$boolKey], FILTER_VALIDATE_BOOLEAN);
            }
        }

        MorelaStore::updateEntity($key, $id, $patch);

        return back();
    }

    public function deleteEntity(Request $request, string $entity, string $id): RedirectResponse
    {
        $key = self::ENTITY_KEYS[$entity] ?? null;

        if ($key === null) {
            abort(404);
        }

        MorelaStore::deleteEntity($key, $id);

        return back();
    }

    public function setTicketStatus(Request $request, string $id): RedirectResponse
    {
        $request->validate(['status' => 'required|string|in:paid,pending,checked_in']);

        $status = (string) $request->input('status');
        $bookings = MorelaStore::get('bookings', []);
        $nowStr = now()->setTimezone('Asia/Jayapura')->format('d/m/Y, H.i').' WIT';

        foreach ($bookings as $index => $booking) {
            if ($booking['id'] === $id) {
                $bookings[$index]['paymentStatus'] = $status;
                if ($status === 'paid') {
                    $bookings[$index]['paidAt'] = $nowStr;
                }
                if ($status === 'checked_in') {
                    $bookings[$index]['checkedInAt'] = $nowStr;
                }

                break;
            }
        }

        MorelaStore::set('bookings', $bookings);

        return back();
    }

    // ---------------------------------------------------------------
    // Admin: pengaturan
    // ---------------------------------------------------------------

    public function updatePaymentSettings(Request $request): RedirectResponse
    {
        MorelaStore::merge('paymentSettings', $request->only([
            'rekeningBCA', 'rekeningMandiri', 'rekeningMaluku', 'qrisUrl',
        ]));

        return back();
    }

    public function updateHeroSliders(Request $request): RedirectResponse
    {
        $urls = array_values(array_filter(array_map('trim', explode("\n", (string) $request->input('urls', '')))));
        MorelaStore::set('heroSliders', $urls);

        return back();
    }

    public function updateContactInfo(Request $request): RedirectResponse
    {
        MorelaStore::merge('contactInfo', $request->only(['address', 'phone']));

        return back();
    }

    public function updateHeroText(Request $request): RedirectResponse
    {
        MorelaStore::merge('heroText', $request->only([
            'badge', 'badgeEn', 'title', 'titleEn', 'tagline', 'taglineEn', 'description', 'descriptionEn',
        ]));

        return back();
    }

    public function reset(): RedirectResponse
    {
        MorelaStore::reset();

        return back()->with('morela_alert', 'Seluruh data portal telah dikembalikan ke pengaturan default pabrik.');
    }

    protected function slugify(string $value): string
    {
        $slug = strtolower($value);
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug) ?? '';

        return trim($slug, '-');
    }

    protected function createAlertMessage(string $key): string
    {
        return match ($key) {
            'destinations' => 'Destinasi baru berhasil ditambahkan!',
            'umkm' => 'Produk UMKM berhasil ditambahkan!',
            'news' => 'Artikel warta berhasil dipublikasikan!',
            'events' => 'Agenda kegiatan berhasil ditambahkan!',
            'culture' => 'Naskah budaya berhasil ditambahkan!',
            'gallery' => 'Foto galeri berhasil ditambahkan!',
            'team' => 'Anggota tim berhasil ditambahkan!',
            default => 'Data berhasil disimpan.',
        };
    }
}
