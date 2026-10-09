<?php

namespace App\Http\Controllers;

use App\Support\MorelaStore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        return $this->page('home', 'home');
    }

    public function destinations(): View
    {
        return $this->page('destinations', 'destinations');
    }

    public function tickets(): View
    {
        return $this->page('tickets', 'ticketing');
    }

    public function culture(): View
    {
        return $this->page('culture', 'culture');
    }

    public function umkm(): View
    {
        return $this->page('umkm', 'umkm');
    }

    public function map(): View
    {
        return $this->page('map', 'map');
    }

    // Halaman 'social' memakai tampilan yang sama dengan beranda.
    public function social(): View
    {
        return $this->page('social', 'home');
    }

    public function events(): View
    {
        return $this->page('events', 'events');
    }

    public function news(): View
    {
        return $this->page('news', 'news');
    }

    public function gallery(): View
    {
        return $this->page('gallery', 'gallery');
    }

    public function program(): View
    {
        return $this->page('program', 'program');
    }

    public function admin(): View
    {
        $authenticated = Auth::check();

        // Halaman terkunci tidak boleh membawa data portal (termasuk data pemesanan).
        $data = $authenticated ? null : [
            'destinations' => [],
            'umkm' => [],
            'news' => [],
            'events' => [],
            'culture' => [],
            'gallery' => [],
            'team' => [],
            'pengabdian' => [],
            'bookings' => [],
            'paymentSettings' => [],
            'heroSliders' => [],
            'contactInfo' => [],
            'heroText' => [],
            'programText' => [],
        ];

        return $this->page('admin', 'admin', $data)->with('isAdminAuthenticated', $authenticated);
    }

    protected function page(string $viewId, string $viewName, ?array $data = null): View
    {
        // Statistik kunjungan nyata: setiap browser dihitung sekali per bulan.
        $visitorId = (string) request()->cookie('morela_visitor', '');

        if ($visitorId === '') {
            $visitorId = (string) Str::uuid();
            Cookie::queue('morela_visitor', $visitorId, 60 * 24 * 365 * 5);
        }

        return view($viewName, array_merge($data ?? MorelaStore::load(), [
            'currentView' => $viewId,
            'webVisits' => MorelaStore::registerMonthlyVisit($visitorId),
            'visitChart' => MorelaStore::visitMonths(3),
        ]));
    }
}
