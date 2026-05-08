<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    private const DESTINATION = [
        'name' => 'Wisata Batu Kuda',
        'address' => 'Desa Cikadut, Kecamatan Cimenyan, Kabupaten Bandung, Jawa Barat',
        'latitude' => -6.9037,
        'longitude' => 107.7471,
    ];

    public function index(): View
    {
        $user = Auth::user();

        $mapData = [
            'isAuthenticated' => Auth::check(),
            'destination' => self::DESTINATION,
            'origin' => [
                'name' => $user?->name,
                'address' => $user?->Address,
                'latitude' => $user?->latitude,
                'longitude' => $user?->longitude,
            ],
            'routing' => [
                'serviceUrl' => 'https://router.project-osrm.org/route/v1',
                'profile' => 'driving',
            ],
            'referenceRoutes' => [
                'fastestGpxUrl' => asset('routes/batu-kuda-fastest.gpx'),
                'snapRadiusMeters' => 2500,
            ],
        ];

        return view('layout.lokasi', compact('mapData'));
    }
}
