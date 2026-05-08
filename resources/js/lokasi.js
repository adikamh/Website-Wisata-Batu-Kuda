document.addEventListener('DOMContentLoaded', () => {
    const mapElement = document.getElementById('batuKudaRouteMap');
    const dataElement = document.getElementById('batu-kuda-route-data');
    const panelElement = document.getElementById('routeDirectionsPanel');
    const layoutElement = document.querySelector('.route-layout');
    const panelToggle = document.getElementById('routePanelToggle');
    const panelClose = document.getElementById('routePanelClose');
    const currentLocationButton = document.getElementById('routeUseCurrentLocation');
    const originLabelElement = document.getElementById('routeOriginLabel');
    const statusElement = document.getElementById('routeStatus');
    const statusText = document.getElementById('routeStatusText');
    const tabsElement = document.getElementById('routeTabs');
    const stepsElement = document.getElementById('routeSteps');
    const legendElement = document.getElementById('routeLegend');
    const durationElement = document.querySelector('[data-route-duration]');
    const distanceElement = document.querySelector('[data-route-distance]');

    if (! mapElement || ! dataElement || typeof window.L === 'undefined') {
        return;
    }

    if (! window.L.Routing) {
        setStatus('Leaflet Routing Machine belum termuat. Periksa koneksi CDN.', 'error');
        return;
    }

    const config = parseRouteConfig(dataElement);
    const destination = normalizeCoordinates(
        config?.destination?.latitude,
        config?.destination?.longitude
    );

    if (! destination) {
        setStatus('Koordinat tujuan Batu Kuda tidak valid.', 'error');
        return;
    }

    const routes = [];
    const routeLayers = [];
    const markers = [];
    let activeRouteIndex = 0;
    let routingControl = null;
    let originMarker = null;
    let referenceFastestRoute = null;

    const routeStyles = [
        { label: 'Tercepat', color: '#2563eb', weight: 7, opacity: 0.96, dashArray: null },
        { label: 'Normal', color: '#16a34a', weight: 6, opacity: 0.86, dashArray: null },
        { label: 'Lambat', color: '#f59e0b', weight: 5, opacity: 0.82, dashArray: '12 9' },
    ];

    const map = window.L.map(mapElement, {
        scrollWheelZoom: true,
        zoomControl: false,
    }).setView(destination, 14);

    window.L.control.zoom({
        position: 'bottomright',
    }).addTo(map);

    window.L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    }).addTo(map);

    const destinationMarker = window.L.marker(destination, {
        icon: createPinIcon('destination', 'B'),
        title: config.destination?.name || 'Wisata Batu Kuda',
    }).addTo(map);

    destinationMarker.bindPopup(`
        <strong>${escapeHtml(config.destination?.name || 'Wisata Batu Kuda')}</strong><br>
        <small>${escapeHtml(config.destination?.address || 'Desa Cikadut, Cimenyan')}</small>
    `);
    markers.push(destinationMarker);

    initPanelEvents();
    initLegendEvents();
    initCurrentLocationEvents();
    bootstrapRoute();

    async function bootstrapRoute() {
        if (! config.isAuthenticated) {
            setStatus('Silakan login untuk menghitung rute dari alamat rumah Anda.', 'error');
            destinationMarker.openPopup();
            return;
        }

        try {
            const origin = await resolveOrigin();
            setOriginMarker(origin, config.origin?.address || 'Berdasarkan koordinat akun');
            updateOriginLabel(config.origin?.address || config.origin?.name || 'Lokasi akun');
            fitMapToLayers(markers);
            requestRoutes(origin);
        } catch (error) {
            setStatus(error.message || 'Lokasi awal tidak bisa ditentukan.', 'error');
            destinationMarker.openPopup();
        }
    }

    async function resolveOrigin() {
        const savedOrigin = normalizeCoordinates(config.origin?.latitude, config.origin?.longitude);

        if (savedOrigin) {
            setStatus('Koordinat akun ditemukan. Menghubungi OSRM untuk mencari rute...');
            return savedOrigin;
        }

        const address = (config.origin?.address || '').trim();

        if (! address) {
            throw new Error('Koordinat user belum tersedia. Lengkapi latitude dan longitude di profil atau saat registrasi.');
        }

        setStatus('Koordinat belum tersedia. Mencari alamat akun di OpenStreetMap...');
        return geocodeAddress(address);
    }

    function requestCurrentLocation() {
        if (! navigator.geolocation) {
            setStatus('Browser ini tidak mendukung geolokasi. Gunakan browser modern atau isi koordinat akun.', 'error');
            return;
        }

        if (! window.isSecureContext && ! ['localhost', '127.0.0.1'].includes(window.location.hostname)) {
            setStatus('Lokasi perangkat hanya tersedia melalui HTTPS atau localhost.', 'error');
            return;
        }

        setCurrentLocationButtonBusy(true);
        setStatus('Meminta izin lokasi dari perangkat...');

        navigator.geolocation.getCurrentPosition(
            (position) => {
                const origin = [
                    position.coords.latitude,
                    position.coords.longitude,
                ];

                setOriginMarker(origin, 'Lokasi perangkat saat ini');
                updateOriginLabel('Lokasi perangkat saat ini');
                fitMapToLayers(markers);
                requestRoutes(origin);
                setCurrentLocationButtonBusy(false);
            },
            (error) => {
                const message = {
                    1: 'Izin lokasi ditolak. Aktifkan izin lokasi browser lalu coba lagi.',
                    2: 'Lokasi perangkat belum bisa ditentukan. Pastikan GPS atau lokasi aktif.',
                    3: 'Permintaan lokasi timeout. Coba ulangi beberapa saat lagi.',
                }[error.code] || 'Lokasi perangkat tidak bisa diambil.';

                setStatus(message, 'error');
                setCurrentLocationButtonBusy(false);
            },
            {
                enableHighAccuracy: true,
                timeout: 12000,
                maximumAge: 0,
            }
        );
    }

    function setOriginMarker(origin, description) {
        const popup = `
            <strong>Lokasi Anda</strong><br>
            <small>${escapeHtml(description || 'Titik awal perjalanan')}</small>
        `;

        if (originMarker) {
            originMarker.setLatLng(origin);
            originMarker.setPopupContent(popup);
            return;
        }

        originMarker = window.L.marker(origin, {
            icon: createPinIcon('origin', 'A'),
            title: 'Lokasi Anda',
        }).addTo(map);

        originMarker.bindPopup(popup);
        markers.push(originMarker);
    }

    function updateOriginLabel(label) {
        if (originLabelElement) {
            originLabelElement.textContent = label || 'Lokasi Anda';
        }
    }

    function setCurrentLocationButtonBusy(isBusy) {
        if (! currentLocationButton) {
            return;
        }

        currentLocationButton.disabled = isBusy;
        currentLocationButton.setAttribute('aria-busy', isBusy ? 'true' : 'false');
    }

    function requestRoutes(origin) {
        setStatus('Membuat 3 variasi jalur dengan Leaflet Routing Machine dan OSRM...');
        clearRouteLayers();
        clearRoutingControl();
        renderLoadingState();

        const hiddenLine = {
            addWaypoints: false,
            styles: [{ color: '#000000', opacity: 0, weight: 1 }],
        };

        routingControl = window.L.Routing.control({
            waypoints: [
                window.L.latLng(origin[0], origin[1]),
                window.L.latLng(destination[0], destination[1]),
            ],
            router: window.L.Routing.osrmv1({
                serviceUrl: config.routing?.serviceUrl || 'https://router.project-osrm.org/route/v1',
                profile: config.routing?.profile || 'driving',
                timeout: 30000,
                useHints: false,
            }),
            autoRoute: false,
            addWaypoints: false,
            draggableWaypoints: false,
            routeWhileDragging: false,
            showAlternatives: true,
            fitSelectedRoutes: false,
            show: false,
            collapsible: false,
            createMarker: () => null,
            lineOptions: hiddenLine,
            altLineOptions: hiddenLine,
        }).addTo(map);

        routingControl.on('routesfound', (event) => {
            renderRoutes(event.routes || [], origin).catch((error) => {
                renderFallbackRoute(origin, error);
            });
        });

        routingControl.on('routingerror', (event) => {
            renderFallbackRoute(origin, event.error);
        });

        try {
            const result = routingControl.route({
                geometryOnly: false,
                alternatives: true,
            });

            if (result && typeof result.catch === 'function') {
                result.catch((error) => renderFallbackRoute(origin, error));
            }
        } catch (error) {
            renderFallbackRoute(origin, error);
        }
    }

    async function renderRoutes(osrmRoutes, origin) {
        clearRouteLayers();

        const baseRoutes = osrmRoutes
            .filter((route) => Array.isArray(route.coordinates) && route.coordinates.length > 0)
            .sort((a, b) => getRouteDuration(a) - getRouteDuration(b));

        if (baseRoutes.length === 0) {
            renderFallbackRoute(origin, new Error('OSRM tidak mengembalikan geometri rute.'));
            return;
        }

        const preparedRoutes = await completeRouteVariants(origin, baseRoutes);

        preparedRoutes.forEach((route, index) => {
            const style = routeStyles[index];
            const layer = window.L.polyline(route.coordinates, {
                color: style.color,
                weight: style.weight,
                opacity: index === 0 ? style.opacity : 0.34,
                dashArray: style.dashArray,
                lineCap: 'round',
                lineJoin: 'round',
            }).addTo(map);

            layer.on('click', () => activateRoute(index));
            routeLayers[index] = layer;
            routes[index] = route;
        });

        renderTabs(preparedRoutes.length);
        syncLegend(preparedRoutes.length);
        activateRoute(0);
        fitMapToLayers([...markers, ...routeLayers.filter(Boolean)]);

        const generatedCount = preparedRoutes.filter((route) => route.isGeneratedVariant).length;
        const statusPrefix = preparedRoutes[0]?.isReferenceFastest
            ? 'Jalur tercepat memakai referensi GPX yang kamu lampirkan'
            : generatedCount > 0
            ? '3 jalur ditampilkan; sebagian variasi dibuat agar jalur normal dan lambat tetap terlihat'
            : '3 jalur berhasil dimuat dari OSRM';

        setStatus(`${statusPrefix}. Jalur tercepat: ${formatTime(getRouteDuration(preparedRoutes[0]))} - ${formatDistance(getRouteDistance(preparedRoutes[0]))}.`, 'success');
    }

    async function completeRouteVariants(origin, baseRoutes) {
        const result = [];
        const referenceRoute = await getFastestReferenceRoute(origin, baseRoutes[0]);

        if (referenceRoute) {
            result.push(referenceRoute);
        }

        baseRoutes.forEach((route) => {
            if (result.length < 3 && isDistinctRoute(result, route)) {
                result.push(route);
            }
        });

        if (result.length < 3) {
            const viaRoutes = await fetchViaRouteVariants(origin);

            viaRoutes.forEach((route) => {
                if (result.length < 3 && isDistinctRoute(result, route)) {
                    result.push(route);
                }
            });
        }

        result.sort(sortRoutesForDisplay);

        while (result.length < 3 && result[0]) {
            result.push(createGeneratedVariant(result[0], result.length));
        }

        return result
            .sort(sortRoutesForDisplay)
            .slice(0, 3);
    }

    async function getFastestReferenceRoute(origin, templateRoute) {
        const fastestGpxUrl = config.referenceRoutes?.fastestGpxUrl;

        if (! fastestGpxUrl) {
            return null;
        }

        const route = await loadReferenceFastestRoute(fastestGpxUrl, templateRoute);

        if (! route?.coordinates?.length) {
            return null;
        }

        const snapRadius = Number(config.referenceRoutes?.snapRadiusMeters || 2500);
        const firstPoint = route.coordinates[0];
        const lastPoint = route.coordinates[route.coordinates.length - 1];
        const isOriginClose = map.distance(origin, firstPoint) <= snapRadius;
        const isDestinationClose = map.distance(destination, lastPoint) <= snapRadius;

        return isOriginClose && isDestinationClose ? route : null;
    }

    async function loadReferenceFastestRoute(url, templateRoute) {
        if (referenceFastestRoute) {
            return withTemplateSummary(referenceFastestRoute, templateRoute);
        }

        try {
            const response = await fetch(url, {
                headers: {
                    Accept: 'application/gpx+xml, application/xml, text/xml',
                },
            });

            if (! response.ok) {
                return null;
            }

            const gpxText = await response.text();
            const documentXml = new DOMParser().parseFromString(gpxText, 'application/xml');
            const points = Array.from(documentXml.getElementsByTagName('trkpt'))
                .map((point) => normalizeCoordinates(point.getAttribute('lat'), point.getAttribute('lon')))
                .filter(Boolean)
                .map(([lat, lng]) => window.L.latLng(lat, lng));

            if (points.length === 0) {
                return null;
            }

            referenceFastestRoute = {
                name: 'GPX tercepat',
                coordinates: points,
                instructions: [],
                isReferenceFastest: true,
                summary: {
                    totalDistance: calculatePolylineDistance(points),
                    totalTime: 0,
                },
            };

            return withTemplateSummary(referenceFastestRoute, templateRoute);
        } catch {
            return null;
        }
    }

    function withTemplateSummary(referenceRoute, templateRoute) {
        const distance = calculatePolylineDistance(referenceRoute.coordinates);
        const templateDistance = getRouteDistance(templateRoute);
        const templateDuration = getRouteDuration(templateRoute);
        const estimatedDuration = templateDistance > 0 && templateDuration > 0
            ? templateDuration * (distance / templateDistance)
            : distance / 8.33;

        return {
            ...referenceRoute,
            instructions: Array.isArray(templateRoute?.instructions) ? templateRoute.instructions : referenceRoute.instructions,
            summary: {
                totalDistance: distance,
                totalTime: estimatedDuration,
            },
        };
    }

    function calculatePolylineDistance(points) {
        return points.reduce((total, point, index) => {
            if (index === 0) {
                return 0;
            }

            return total + map.distance(points[index - 1], point);
        }, 0);
    }

    function sortRoutesForDisplay(a, b) {
        if (a.isReferenceFastest && ! b.isReferenceFastest) {
            return -1;
        }

        if (! a.isReferenceFastest && b.isReferenceFastest) {
            return 1;
        }

        return getRouteDuration(a) - getRouteDuration(b);
    }

    async function fetchViaRouteVariants(origin) {
        const viaPoints = createViaPoints(origin);
        const requests = viaPoints.map((viaPoint) => fetchRouteWithViaPoints([origin, viaPoint, destination]));
        const responses = await Promise.allSettled(requests);

        return responses
            .filter((response) => response.status === 'fulfilled' && response.value)
            .map((response) => response.value);
    }

    async function fetchRouteWithViaPoints(points) {
        const serviceUrl = config.routing?.serviceUrl || 'https://router.project-osrm.org/route/v1';
        const profile = config.routing?.profile || 'driving';
        const coordinates = points.map(([lat, lng]) => `${lng},${lat}`).join(';');
        const params = new URLSearchParams({
            overview: 'full',
            geometries: 'geojson',
            steps: 'true',
            alternatives: 'false',
        });

        const response = await fetch(`${serviceUrl}/${profile}/${coordinates}?${params.toString()}`, {
            headers: {
                Accept: 'application/json',
            },
        });

        if (! response.ok) {
            throw new Error('Variasi rute via point tidak tersedia.');
        }

        const data = await response.json();
        const route = data.routes?.[0];

        if (! route?.geometry?.coordinates?.length) {
            return null;
        }

        return normalizeOsrmRoute(route);
    }

    function normalizeOsrmRoute(route) {
        return {
            name: route.weight_name || 'OSRM route',
            coordinates: route.geometry.coordinates.map(([lng, lat]) => window.L.latLng(lat, lng)),
            summary: {
                totalDistance: route.distance,
                totalTime: route.duration,
            },
            instructions: (route.legs || [])
                .flatMap((leg) => leg.steps || [])
                .map((step, index, steps) => ({
                    type: instructionTypeFromOsrmStep(step),
                    road: step.name,
                    distance: step.distance,
                    text: buildOsrmInstructionText(step, index, steps.length),
                })),
        };
    }

    function createViaPoints(origin) {
        const midLat = (origin[0] + destination[0]) / 2;
        const midLng = (origin[1] + destination[1]) / 2;
        const metersPerLat = 111320;
        const metersPerLng = Math.max(1, metersPerLat * Math.cos(midLat * Math.PI / 180));
        const dx = (destination[1] - origin[1]) * metersPerLng;
        const dy = (destination[0] - origin[0]) * metersPerLat;
        const length = Math.hypot(dx, dy) || 1;
        const normalX = -dy / length;
        const normalY = dx / length;
        const routeDistance = map.distance(origin, destination);
        const baseOffset = Math.min(Math.max(routeDistance * 0.22, 1200), 6500);
        const forwardLat = (destination[0] - origin[0]) * 0.12;
        const forwardLng = (destination[1] - origin[1]) * 0.12;

        return [
            pointFromOffset(midLat, midLng, normalX * baseOffset, normalY * baseOffset, metersPerLat, metersPerLng),
            pointFromOffset(midLat, midLng, -normalX * baseOffset, -normalY * baseOffset, metersPerLat, metersPerLng),
            pointFromOffset(midLat + forwardLat, midLng + forwardLng, normalX * baseOffset * 0.65, normalY * baseOffset * 0.65, metersPerLat, metersPerLng),
            pointFromOffset(midLat - forwardLat, midLng - forwardLng, -normalX * baseOffset * 0.65, -normalY * baseOffset * 0.65, metersPerLat, metersPerLng),
        ].filter(Boolean);
    }

    function pointFromOffset(lat, lng, offsetXMeters, offsetYMeters, metersPerLat, metersPerLng) {
        return normalizeCoordinates(
            lat + offsetYMeters / metersPerLat,
            lng + offsetXMeters / metersPerLng
        );
    }

    function createGeneratedVariant(route, index) {
        const multiplier = index === 1 ? 1.14 : 1.32;
        const offsetMeters = index === 1 ? 55 : -75;

        return {
            ...route,
            coordinates: shiftRouteCoordinates(route.coordinates, offsetMeters),
            summary: {
                totalDistance: getRouteDistance(route) * multiplier,
                totalTime: getRouteDuration(route) * multiplier,
            },
            instructions: Array.isArray(route.instructions) ? [...route.instructions] : [],
            isGeneratedVariant: true,
        };
    }

    function shiftRouteCoordinates(coordinates, offsetMeters) {
        return coordinates.map((coordinate, index) => {
            const previous = coordinates[Math.max(index - 1, 0)];
            const next = coordinates[Math.min(index + 1, coordinates.length - 1)];
            const lat = coordinate.lat ?? coordinate[0];
            const lng = coordinate.lng ?? coordinate[1];
            const prevLat = previous.lat ?? previous[0];
            const prevLng = previous.lng ?? previous[1];
            const nextLat = next.lat ?? next[0];
            const nextLng = next.lng ?? next[1];
            const metersPerLat = 111320;
            const metersPerLng = Math.max(1, metersPerLat * Math.cos(lat * Math.PI / 180));
            const dx = (nextLng - prevLng) * metersPerLng;
            const dy = (nextLat - prevLat) * metersPerLat;
            const length = Math.hypot(dx, dy) || 1;
            const normalX = -dy / length;
            const normalY = dx / length;

            return window.L.latLng(
                lat + (normalY * offsetMeters) / metersPerLat,
                lng + (normalX * offsetMeters) / metersPerLng
            );
        });
    }

    function isDistinctRoute(existingRoutes, candidate) {
        const candidateSignature = routeSignature(candidate);

        return ! existingRoutes.some((route) => {
            const distanceDiff = Math.abs(getRouteDistance(route) - getRouteDistance(candidate));
            const durationDiff = Math.abs(getRouteDuration(route) - getRouteDuration(candidate));

            return routeSignature(route) === candidateSignature
                || (distanceDiff < 80 && durationDiff < 60);
        });
    }

    function routeSignature(route) {
        const coordinates = route.coordinates || [];

        if (coordinates.length === 0) {
            return '';
        }

        return [0, 0.25, 0.5, 0.75, 1]
            .map((position) => coordinates[Math.min(coordinates.length - 1, Math.floor((coordinates.length - 1) * position))])
            .map((coordinate) => `${(coordinate.lat ?? coordinate[0]).toFixed(4)},${(coordinate.lng ?? coordinate[1]).toFixed(4)}`)
            .join('|');
    }

    function renderFallbackRoute(origin, error) {
        clearRouteLayers();

        const layer = window.L.polyline([origin, destination], {
            color: '#2563eb',
            weight: 5,
            opacity: 0.8,
            dashArray: '10 8',
            lineCap: 'round',
        }).addTo(map);

        routeLayers[0] = layer;
        routes[0] = {
            summary: {
                totalDistance: map.distance(origin, destination),
                totalTime: 0,
            },
            instructions: [],
        };

        renderTabs(1);
        syncLegend(1);
        activateRoute(0);
        fitMapToLayers([...markers, layer]);

        const message = error?.message || 'Rute jalan tidak tersedia dari OSRM.';
        setStatus(`${message} Ditampilkan garis perkiraan arah.`, 'error');
        stepsElement.innerHTML = '<li class="route-step route-step--empty">Instruksi jalan belum tersedia karena OSRM tidak mengembalikan rute.</li>';
    }

    function activateRoute(index) {
        if (! routeLayers[index]) {
            return;
        }

        activeRouteIndex = index;

        routeLayers.forEach((layer, layerIndex) => {
            if (! layer) {
                return;
            }

            const style = routeStyles[layerIndex] || routeStyles[0];

            if (! map.hasLayer(layer)) {
                layer.addTo(map);
                legendElement
                    ?.querySelector(`[data-route-index="${layerIndex}"]`)
                    ?.classList.remove('is-muted');
            }

            layer.setStyle({
                opacity: layerIndex === index ? style.opacity : 0.3,
                weight: layerIndex === index ? style.weight : Math.max(style.weight - 2, 3),
            });

            if (layerIndex === index) {
                layer.bringToFront();
            }
        });

        document.querySelectorAll('.route-tab').forEach((tab) => {
            tab.classList.toggle('is-active', Number(tab.dataset.routeIndex) === index);
            tab.setAttribute('aria-selected', Number(tab.dataset.routeIndex) === index ? 'true' : 'false');
        });

        renderSummary(routes[index]);
        renderSteps(routes[index]);
    }

    function renderTabs(count) {
        tabsElement.innerHTML = Array.from({ length: count }, (_, index) => `
            <button
                class="route-tab"
                type="button"
                role="tab"
                data-route-index="${index}"
                aria-selected="${index === activeRouteIndex ? 'true' : 'false'}"
            >
                ${routeStyles[index]?.label || `Jalur ${index + 1}`}
            </button>
        `).join('');

        tabsElement.querySelectorAll('.route-tab').forEach((tab) => {
            tab.addEventListener('click', () => activateRoute(Number(tab.dataset.routeIndex)));
        });
    }

    function renderSummary(route) {
        if (! route) {
            durationElement.textContent = '-';
            distanceElement.textContent = '-';
            return;
        }

        const duration = getRouteDuration(route);
        durationElement.textContent = duration > 0 ? formatTime(duration) : '-';
        distanceElement.textContent = formatDistance(getRouteDistance(route));
    }

    function renderSteps(route) {
        const instructions = Array.isArray(route?.instructions) ? route.instructions : [];

        if (instructions.length === 0) {
            stepsElement.innerHTML = '<li class="route-step route-step--empty">Instruksi detail tidak tersedia untuk jalur ini.</li>';
            return;
        }

        stepsElement.innerHTML = instructions.map((instruction, index) => `
            <li class="route-step">
                <span class="route-step__icon">${getInstructionIcon(instruction)}</span>
                <div>
                    <div class="route-step__title">${escapeHtml(getInstructionText(instruction, index, instructions.length))}</div>
                    <div class="route-step__meta">${formatDistance(instruction.distance || 0)}</div>
                </div>
            </li>
        `).join('');
    }

    function syncLegend(count) {
        legendElement?.querySelectorAll('.route-legend-item').forEach((button) => {
            const index = Number(button.dataset.routeIndex);
            button.hidden = index >= count;
            button.classList.remove('is-muted');
        });
    }

    function initPanelEvents() {
        const setPanelOpen = (open) => {
            panelElement?.classList.toggle('is-open', open);
            layoutElement?.classList.toggle('is-panel-collapsed', ! open);
            panelToggle?.setAttribute('aria-expanded', open ? 'true' : 'false');

            window.setTimeout(() => {
                map.invalidateSize();
            }, 320);
        };

        panelToggle?.addEventListener('click', () => {
            setPanelOpen(! panelElement.classList.contains('is-open'));
        });

        panelClose?.addEventListener('click', () => setPanelOpen(false));
    }

    function initCurrentLocationEvents() {
        currentLocationButton?.addEventListener('click', requestCurrentLocation);
    }

    function initLegendEvents() {
        legendElement?.addEventListener('click', (event) => {
            const button = event.target.closest('.route-legend-item');

            if (! button) {
                return;
            }

            const index = Number(button.dataset.routeIndex);
            const layer = routeLayers[index];

            if (! layer) {
                return;
            }

            if (map.hasLayer(layer)) {
                map.removeLayer(layer);
                button.classList.add('is-muted');
                return;
            }

            layer.addTo(map);
            button.classList.remove('is-muted');

            if (index === activeRouteIndex) {
                layer.bringToFront();
            }
        });
    }

    function clearRouteLayers() {
        routeLayers.forEach((layer) => {
            if (layer && map.hasLayer(layer)) {
                map.removeLayer(layer);
            }
        });

        routeLayers.length = 0;
        routes.length = 0;
    }

    function clearRoutingControl() {
        if (routingControl) {
            map.removeControl(routingControl);
            routingControl = null;
        }
    }

    function renderLoadingState() {
        activeRouteIndex = 0;

        if (durationElement) {
            durationElement.textContent = '-';
        }

        if (distanceElement) {
            distanceElement.textContent = '-';
        }

        if (tabsElement) {
            tabsElement.innerHTML = routeStyles.map((style, index) => `
                <button
                    class="route-tab"
                    type="button"
                    role="tab"
                    data-route-index="${index}"
                    aria-selected="${index === 0 ? 'true' : 'false'}"
                    disabled
                >
                    ${style.label}
                </button>
            `).join('');
        }

        if (stepsElement) {
            stepsElement.innerHTML = '<li class="route-step route-step--empty">Mengambil data rute dari OSRM...</li>';
        }

        syncLegend(3);
    }

    function fitMapToLayers(layers) {
        const visibleLayers = layers.filter(Boolean);

        if (visibleLayers.length === 0) {
            map.setView(destination, 14);
            return;
        }

        const group = window.L.featureGroup(visibleLayers);
        map.fitBounds(group.getBounds(), {
            padding: [48, 48],
            maxZoom: 16,
        });
    }

    async function geocodeAddress(address) {
        const params = new URLSearchParams({
            format: 'jsonv2',
            limit: '1',
            countrycodes: 'id',
            q: address,
        });

        const response = await fetch(`https://nominatim.openstreetmap.org/search?${params.toString()}`, {
            headers: {
                Accept: 'application/json',
            },
        });

        if (! response.ok) {
            throw new Error('Alamat akun tidak bisa dicari di OpenStreetMap.');
        }

        const result = await response.json();
        const firstMatch = result[0];

        if (! firstMatch) {
            throw new Error('Alamat akun tidak ditemukan di OpenStreetMap.');
        }

        const coordinates = normalizeCoordinates(firstMatch.lat, firstMatch.lon);

        if (! coordinates) {
            throw new Error('Hasil geocoding alamat tidak valid.');
        }

        return coordinates;
    }

    function setStatus(message, type = '') {
        if (! statusElement || ! statusText) {
            return;
        }

        statusText.textContent = message;
        statusElement.classList.toggle('is-success', type === 'success');
        statusElement.classList.toggle('is-error', type === 'error');
    }

    function parseRouteConfig(element) {
        try {
            return JSON.parse(element.textContent || '{}');
        } catch {
            return {};
        }
    }

    function normalizeCoordinates(latitude, longitude) {
        const lat = Number.parseFloat(latitude);
        const lng = Number.parseFloat(longitude);

        if (! Number.isFinite(lat) || ! Number.isFinite(lng)) {
            return null;
        }

        if (lat < -90 || lat > 90 || lng < -180 || lng > 180) {
            return null;
        }

        return [lat, lng];
    }

    function createPinIcon(type, label) {
        return window.L.divIcon({
            className: `route-marker-icon route-marker--${type}`,
            html: `<span>${escapeHtml(label)}</span>`,
            iconSize: [38, 46],
            iconAnchor: [19, 45],
            popupAnchor: [0, -38],
        });
    }

    function getRouteDuration(route) {
        return Number(route?.summary?.totalTime || route?.summary?.total_time || 0);
    }

    function getRouteDistance(route) {
        return Number(route?.summary?.totalDistance || route?.summary?.total_distance || 0);
    }

    function formatTime(seconds) {
        const minutes = Math.max(1, Math.round(Number(seconds || 0) / 60));
        const hours = Math.floor(minutes / 60);
        const restMinutes = minutes % 60;

        if (hours === 0) {
            return `${minutes} menit`;
        }

        if (restMinutes === 0) {
            return `${hours} jam`;
        }

        return `${hours} jam ${restMinutes} menit`;
    }

    function formatDistance(meters) {
        const distance = Number(meters || 0);

        if (distance < 1000) {
            return `${Math.round(distance)} m`;
        }

        return `${(distance / 1000).toFixed(1)} km`;
    }

    function getInstructionText(instruction, index, totalInstructions) {
        if (instruction?.text) {
            return instruction.text;
        }

        const type = getInstructionType(instruction?.type);
        const road = instruction?.road || instruction?.name || '';

        if (index === 0 || type === 'Head' || type === 'Depart') {
            return road ? `Mulai dari ${road}` : 'Mulai perjalanan';
        }

        if (index === totalInstructions - 1 || type === 'DestinationReached') {
            return 'Tiba di Wisata Batu Kuda';
        }

        const label = {
            Straight: 'Lurus',
            Continue: 'Lanjutkan',
            SlightRight: 'Belok sedikit ke kanan',
            Right: 'Belok kanan',
            SharpRight: 'Belok tajam ke kanan',
            TurnAround: 'Putar balik',
            SharpLeft: 'Belok tajam ke kiri',
            Left: 'Belok kiri',
            SlightLeft: 'Belok sedikit ke kiri',
            WaypointReached: 'Lewati titik antara',
            Roundabout: 'Masuk bundaran',
            EnterRoundabout: 'Masuk bundaran',
            Fork: 'Ambil percabangan',
            Merge: 'Bergabung ke jalan',
            OnRamp: 'Masuk ramp',
            OffRamp: 'Keluar ramp',
            EndOfRoad: 'Di ujung jalan',
            Onto: 'Masuk ke jalan',
            BearRight: 'Ambil arah kanan',
            BearLeft: 'Ambil arah kiri',
        }[type] || 'Lanjutkan perjalanan';

        return road ? `${label} ke ${road}` : label;
    }

    function instructionTypeFromOsrmStep(step) {
        const maneuverType = step?.maneuver?.type;
        const modifier = step?.maneuver?.modifier;

        if (maneuverType === 'depart') {
            return 'Depart';
        }

        if (maneuverType === 'arrive') {
            return 'DestinationReached';
        }

        if (maneuverType === 'roundabout' || maneuverType === 'rotary') {
            return 'Roundabout';
        }

        if (modifier === 'right') {
            return 'Right';
        }

        if (modifier === 'sharp right') {
            return 'SharpRight';
        }

        if (modifier === 'slight right') {
            return 'SlightRight';
        }

        if (modifier === 'left') {
            return 'Left';
        }

        if (modifier === 'sharp left') {
            return 'SharpLeft';
        }

        if (modifier === 'slight left') {
            return 'SlightLeft';
        }

        if (modifier === 'uturn') {
            return 'TurnAround';
        }

        return 'Continue';
    }

    function buildOsrmInstructionText(step, index, totalSteps) {
        const maneuverType = step?.maneuver?.type;
        const modifier = step?.maneuver?.modifier;
        const road = step?.name || '';

        if (maneuverType === 'depart' || index === 0) {
            return road ? `Mulai dari ${road}` : 'Mulai perjalanan';
        }

        if (maneuverType === 'arrive' || index === totalSteps - 1) {
            return 'Tiba di Wisata Batu Kuda';
        }

        const modifierText = {
            straight: 'Lurus',
            'slight right': 'Belok sedikit ke kanan',
            right: 'Belok kanan',
            'sharp right': 'Belok tajam ke kanan',
            uturn: 'Putar balik',
            'sharp left': 'Belok tajam ke kiri',
            left: 'Belok kiri',
            'slight left': 'Belok sedikit ke kiri',
        }[modifier];

        const maneuverText = {
            turn: modifierText || 'Belok',
            continue: modifierText || 'Lanjutkan',
            'new name': 'Lanjutkan',
            merge: 'Bergabung ke jalan',
            fork: modifierText || 'Ambil percabangan',
            'on ramp': 'Masuk ramp',
            'off ramp': 'Keluar ramp',
            'end of road': modifierText || 'Di ujung jalan',
            roundabout: 'Masuk bundaran',
            rotary: 'Masuk bundaran',
            notification: modifierText || 'Lanjutkan perjalanan',
        }[maneuverType] || modifierText || 'Lanjutkan perjalanan';

        return road ? `${maneuverText} ke ${road}` : maneuverText;
    }

    function getInstructionType(type) {
        if (typeof type === 'string') {
            return type;
        }

        const types = [
            'Head',
            'Continue',
            'SlightRight',
            'Right',
            'SharpRight',
            'TurnAround',
            'SharpLeft',
            'Left',
            'SlightLeft',
            'WaypointReached',
            'Roundabout',
            'DestinationReached',
            'Fork',
            'Merge',
            'OnRamp',
            'OffRamp',
            'EndOfRoad',
            'Onto',
            'EnterRoundabout',
            'Continue',
            'BearRight',
            'BearLeft',
            'Depart',
        ];

        return types[type] || 'Continue';
    }

    function getInstructionIcon(instruction) {
        const type = getInstructionType(instruction?.type);
        const icons = {
            Right: '<path d="M7 7h7a3 3 0 0 1 3 3v7"/><path d="M13 13l4 4 4-4"/>',
            SharpRight: '<path d="M7 7h7a3 3 0 0 1 3 3v7"/><path d="M13 13l4 4 4-4"/>',
            SlightRight: '<path d="M6 18c6 0 10-4 10-10"/><path d="M12 8h4v4"/>',
            BearRight: '<path d="M6 18c6 0 10-4 10-10"/><path d="M12 8h4v4"/>',
            Left: '<path d="M17 7h-7a3 3 0 0 0-3 3v7"/><path d="M11 13l-4 4-4-4"/>',
            SharpLeft: '<path d="M17 7h-7a3 3 0 0 0-3 3v7"/><path d="M11 13l-4 4-4-4"/>',
            SlightLeft: '<path d="M18 18C12 18 8 14 8 8"/><path d="M12 8H8v4"/>',
            BearLeft: '<path d="M18 18C12 18 8 14 8 8"/><path d="M12 8H8v4"/>',
            TurnAround: '<path d="M7 7v6h6"/><path d="M17 17a7 7 0 0 0-10-10"/>',
            Roundabout: '<path d="M7 12a5 5 0 1 0 5-5"/><path d="M10 4l2 3-3 2"/>',
            EnterRoundabout: '<path d="M7 12a5 5 0 1 0 5-5"/><path d="M10 4l2 3-3 2"/>',
            DestinationReached: '<path d="M12 21s6-5.3 6-10a6 6 0 1 0-12 0c0 4.7 6 10 6 10z"/><circle cx="12" cy="11" r="2"/>',
        };

        return `<svg viewBox="0 0 24 24" aria-hidden="true">${icons[type] || '<path d="M5 12h14"/><path d="M13 6l6 6-6 6"/>'}</svg>`;
    }

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }
});
