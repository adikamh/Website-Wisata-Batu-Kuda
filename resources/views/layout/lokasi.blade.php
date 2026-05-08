@extends('layout.main')

@section('title', 'Rute Menuju Batu Kuda | Wisata Batu Kuda')
@section('body_class', 'lokasi-route-body')

@push('styles')
    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin=""
    >
    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css"
    >
    @vite('resources/css/lokasi.css')
@endpush

@section('content')
    <script id="batu-kuda-route-data" type="application/json">
        @json($mapData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT)
    </script>

    <section class="route-page" aria-labelledby="routeTitle">
        <header class="route-toolbar">
            <div class="route-toolbar__copy">
                <p class="route-eyebrow">OpenStreetMap + OSRM</p>
                <h1 id="routeTitle">Rute ke Wisata Batu Kuda</h1>
                <p>
                    Dari lokasi akun menuju Desa Cikadut, Cimenyan, dengan pilihan jalur tercepat, normal, dan lambat.
                </p>
                <div class="route-location-meta" aria-label="Ringkasan titik perjalanan">
                    <span>
                        <strong>Dari</strong>
                        <span id="routeOriginLabel">{{ $mapData['origin']['address'] ?: ($mapData['origin']['name'] ?: 'Lokasi akun') }}</span>
                    </span>
                    <span>
                        <strong>Ke</strong>
                        {{ $mapData['destination']['name'] }}
                    </span>
                </div>
            </div>

            <div class="route-toolbar__actions">
                <a class="route-action route-action--ghost" href="{{ route('home') }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M15 18l-6-6 6-6"/>
                    </svg>
                    Beranda
                </a>
                <button
                    class="route-action"
                    type="button"
                    id="routePanelToggle"
                    aria-expanded="true"
                    aria-controls="routeDirectionsPanel"
                >
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/>
                    </svg>
                    Instruksi
                </button>
            </div>
        </header>

        <div class="route-layout">
            <div class="route-map-pane">
                <div id="batuKudaRouteMap" class="route-map" role="application" aria-label="Peta rute menuju Wisata Batu Kuda"></div>

                <button
                    class="route-floating route-floating--locate route-locate-button"
                    type="button"
                    id="routeUseCurrentLocation"
                    title="Gunakan lokasi saya"
                    aria-label="Gunakan lokasi saya"
                >
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="12" cy="12" r="3"/>
                        <circle cx="12" cy="12" r="8"/>
                        <path d="M12 2v3M12 19v3M2 12h3M19 12h3"/>
                    </svg>
                </button>

                <div class="route-floating route-floating--legend" id="routeLegend" aria-label="Legenda jalur">
                    <button type="button" class="route-legend-item" data-route-index="0">
                        <span class="route-line route-line--fast"></span>
                        Tercepat
                    </button>
                    <button type="button" class="route-legend-item" data-route-index="1">
                        <span class="route-line route-line--normal"></span>
                        Normal
                    </button>
                    <button type="button" class="route-legend-item" data-route-index="2">
                        <span class="route-line route-line--slow"></span>
                        Lambat
                    </button>
                </div>

                <div class="route-floating route-floating--status" id="routeStatus" role="status" aria-live="polite">
                    <span class="route-status-dot"></span>
                    <span id="routeStatusText">Menyiapkan peta rute...</span>
                </div>
            </div>

            <aside class="route-directions is-open" id="routeDirectionsPanel" aria-label="Panel instruksi jalan">
                <div class="route-directions__header">
                    <div>
                        <p class="route-eyebrow">Turn by turn</p>
                        <h2>Instruksi Jalan</h2>
                    </div>
                    <button class="route-close" type="button" id="routePanelClose" aria-label="Tutup panel instruksi">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M18 6 6 18M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="route-summary" id="routeSummary">
                    <div>
                        <span>Durasi</span>
                        <strong data-route-duration>-</strong>
                    </div>
                    <div>
                        <span>Jarak</span>
                        <strong data-route-distance>-</strong>
                    </div>
                </div>

                <div class="route-tabs" id="routeTabs" role="tablist" aria-label="Pilih variasi jalur"></div>

                <ol class="route-steps" id="routeSteps">
                    <li class="route-step route-step--empty">Menunggu data rute dari OSRM.</li>
                </ol>

                <div class="route-directions__note">
                    Jalur dan estimasi mengikuti data publik OSRM/OpenStreetMap. Periksa kondisi jalan nyata sebelum berangkat.
                </div>
            </aside>
        </div>
    </section>
@endsection

@push('scripts')
    <script
        src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""
    ></script>
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
    @vite('resources/js/lokasi.js')
@endpush
