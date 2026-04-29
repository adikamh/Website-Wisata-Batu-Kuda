document.addEventListener('DOMContentLoaded', () => {
    const registerForm = document.getElementById('registerForm');

    document.querySelectorAll('.toggle-pw').forEach((button) => {
        button.addEventListener('click', () => {
            const target = document.getElementById(button.dataset.target);
            const eyeShow = button.querySelector('.eye-show');
            const eyeHide = button.querySelector('.eye-hide');

            if (! target) {
                return;
            }

            const showPassword = target.type === 'password';
            target.type = showPassword ? 'text' : 'password';

            if (eyeShow && eyeHide) {
                eyeShow.style.display = showPassword ? 'none' : 'block';
                eyeHide.style.display = showPassword ? 'block' : 'none';
            }
        });
    });

    const passwordInput = document.getElementById('password');
    const passwordBar = document.getElementById('pwBar');
    const passwordHint = document.getElementById('pwHint');
    const passwordConfirmation = document.getElementById('password_confirmation');
    const matchHint = document.getElementById('matchHint');
    const usernameInput = document.getElementById('username');
    const usernameStatus = document.getElementById('username-status');
    const loginInput = document.getElementById('login');

    if (passwordInput && passwordBar) {
        passwordInput.addEventListener('input', () => {
            const value = passwordInput.value;
            const score = getPasswordScore(value);
            const colors = ['#dc2626', '#ea580c', '#d97706', '#2f855a', '#276749'];
            const labels = ['Sangat Lemah', 'Lemah', 'Cukup', 'Kuat', 'Sangat Kuat'];
            const widths = ['20%', '40%', '60%', '80%', '100%'];

            if (value.length === 0) {
                passwordBar.style.width = '0%';
                passwordHint.textContent = '';
                passwordHint.style.color = '';
            } else {
                passwordBar.style.width = widths[score];
                passwordBar.style.background = colors[score];
                passwordHint.textContent = labels[score];
                passwordHint.style.color = colors[score];
            }

            validatePasswordMatch();
        });
    }

    if (passwordConfirmation) {
        passwordConfirmation.addEventListener('input', validatePasswordMatch);
    }

    if (usernameInput && usernameStatus) {
        usernameInput.addEventListener('input', () => {
            const value = usernameInput.value.trim();
            const valid = /^[a-zA-Z0-9_]{3,20}$/.test(value);

            if (value === '') {
                usernameStatus.textContent = '';
                usernameInput.classList.remove('is-valid', 'is-invalid');
                return;
            }

            usernameStatus.textContent = valid ? 'Username valid' : '3-20 karakter tanpa spasi';
            usernameStatus.style.color = valid ? 'var(--green-fresh)' : 'var(--error)';
            usernameInput.classList.toggle('is-valid', valid);
            usernameInput.classList.toggle('is-invalid', ! valid);
        });
    }

    if (loginInput) {
        loginInput.addEventListener('blur', () => {
            const value = loginInput.value.trim();

            if (value === '') {
                loginInput.classList.remove('is-valid', 'is-invalid');
                return;
            }

            const looksLikeEmail = value.includes('@');
            const valid = looksLikeEmail
                ? /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)
                : /^[a-zA-Z0-9_]{3,50}$/.test(value);

            loginInput.classList.toggle('is-valid', valid);
            loginInput.classList.toggle('is-invalid', ! valid);
        });
    }

    document.querySelectorAll('.input-wrap input, .input-wrap textarea').forEach((input) => {
        input.addEventListener('focus', () => {
            input.closest('.input-wrap')?.querySelector('.input-icon')?.style.setProperty('color', 'var(--green-fresh)');
        });

        input.addEventListener('blur', () => {
            if (! input.classList.contains('is-valid') && ! input.classList.contains('is-invalid')) {
                input.closest('.input-wrap')?.querySelector('.input-icon')?.style.setProperty('color', 'var(--text-muted)');
            }
        });
    });

    if (registerForm && typeof window.L !== 'undefined') {
        initLocationPicker();
    }

    ['loginForm', 'registerForm'].forEach((formId) => {
        const form = document.getElementById(formId);

        if (! form) {
            return;
        }

        form.addEventListener('submit', () => {
            if (! form.checkValidity()) {
                return;
            }

            const button = form.querySelector('#submitBtn');
            const text = button?.querySelector('.btn-text');
            const icon = button?.querySelector('.btn-icon');
            const loader = button?.querySelector('.btn-loader');

            if (! button) {
                return;
            }

            button.disabled = true;

            if (text) {
                text.style.opacity = '0.72';
            }

            if (icon) {
                icon.style.display = 'none';
            }

            if (loader) {
                loader.style.display = 'flex';
            }
        });
    });

    function getPasswordScore(password) {
        let score = 0;

        if (password.length >= 8) {
            score++;
        }

        if (password.length >= 12) {
            score++;
        }

        if (/[A-Z]/.test(password)) {
            score++;
        }

        if (/[0-9]/.test(password)) {
            score++;
        }

        if (/[^A-Za-z0-9]/.test(password)) {
            score++;
        }

        return Math.min(score, 4);
    }

    function validatePasswordMatch() {
        if (! passwordConfirmation || ! matchHint) {
            return;
        }

        if (passwordConfirmation.value === '') {
            matchHint.textContent = '';
            passwordConfirmation.classList.remove('is-valid', 'is-invalid');
            return;
        }

        const matched = passwordInput && passwordConfirmation.value === passwordInput.value;
        matchHint.textContent = matched ? 'Password cocok' : 'Password tidak cocok';
        matchHint.style.color = matched ? 'var(--green-fresh)' : 'var(--error)';
        passwordConfirmation.classList.toggle('is-valid', matched);
        passwordConfirmation.classList.toggle('is-invalid', ! matched);
    }

    function initLocationPicker() {
        const addressInput = document.getElementById('address');
        const helpText = document.getElementById('locationHelp');
        const modalHelpText = document.getElementById('locationModalHelp');
        const openModalButton = document.getElementById('openLocationModal');
        const confirmLocationButton = document.getElementById('confirmLocationSelection');
        const locationModal = document.getElementById('locationModal');
        const defaultCoordinates = [-6.8593, 107.6349];

        if (! addressInput || ! locationModal) {
            return;
        }

        const hasSavedAddress = addressInput.value.trim() !== '';
        const initialCoordinates = defaultCoordinates;

        const map = window.L.map('locationMap', {
            scrollWheelZoom: false,
        }).setView(initialCoordinates, 11);

        window.L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors',
        }).addTo(map);

        let marker = null;
        let selectedCoordinates = null;
        let isRequestingLocation = false;

        const setHelpMessage = (message) => {
            helpText.textContent = message;
            if (modalHelpText) {
                modalHelpText.textContent = message;
            }
        };

        const setLocationButtonState = (disabled) => {
            if (! openModalButton) {
                return;
            }

            openModalButton.disabled = disabled;
            openModalButton.setAttribute('aria-busy', disabled ? 'true' : 'false');
        };

        const reverseGeocode = async (lat, lng) => {
            try {
                setHelpMessage('Mengambil alamat dari titik lokasi...');

                const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${encodeURIComponent(lat)}&lon=${encodeURIComponent(lng)}`, {
                    headers: {
                        Accept: 'application/json',
                    },
                });

                if (! response.ok) {
                    throw new Error('Reverse geocoding gagal.');
                }

                const data = await response.json();
                const resolvedAddress = data.display_name ?? '';

                if (addressInput && resolvedAddress !== '') {
                    addressInput.value = resolvedAddress;
                    addressInput.classList.add('is-valid');
                    addressInput.classList.remove('is-invalid');
                }

                setHelpMessage(resolvedAddress !== ''
                    ? 'Lokasi dan alamat berhasil diisi otomatis.'
                    : 'Lokasi dipilih, tetapi alamat detail tidak ditemukan.');
            } catch {
                setHelpMessage('Lokasi dipilih, tetapi alamat otomatis tidak berhasil diambil.');
            }
        };

        const updateLocation = (lat, lng, message, shouldResolveAddress = true) => {
            const normalizedLat = Number.parseFloat(lat).toFixed(6);
            const normalizedLng = Number.parseFloat(lng).toFixed(6);
            selectedCoordinates = [Number.parseFloat(normalizedLat), Number.parseFloat(normalizedLng)];
            setHelpMessage(message ?? `Lokasi dipilih pada ${normalizedLat}, ${normalizedLng}.`);

            if (marker) {
                marker.setLatLng([lat, lng]);
            } else {
                marker = window.L.marker([lat, lng]).addTo(map);
            }

            if (shouldResolveAddress) {
                reverseGeocode(normalizedLat, normalizedLng);
            }
        };

        if (hasSavedAddress) {
            setHelpMessage('Alamat tersimpan. Tekan ikon lokasi untuk meminta ulang lokasi perangkat.');
        }

        map.on('click', (event) => {
            const { lat, lng } = event.latlng;
            updateLocation(lat, lng);
        });

        const requestCurrentLocation = () => {
            if (! navigator.geolocation) {
                setHelpMessage('Browser ini tidak mendukung geolokasi.');
                return;
            }

            if (! window.isSecureContext && window.location.hostname !== 'localhost' && window.location.hostname !== '127.0.0.1') {
                setHelpMessage('Lokasi perangkat hanya bisa dipakai pada koneksi aman HTTPS atau localhost.');
                return;
            }

            if (isRequestingLocation) {
                return;
            }

            isRequestingLocation = true;
            setLocationButtonState(true);
            setHelpMessage('Meminta izin lokasi dari perangkat...');

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const { latitude, longitude } = position.coords;
                    map.setView([latitude, longitude], 15);
                    updateLocation(latitude, longitude, 'Lokasi perangkat berhasil dipakai.');
                    isRequestingLocation = false;
                    setLocationButtonState(false);
                },
                (error) => {
                    if (error.code === error.PERMISSION_DENIED) {
                        setHelpMessage('Izin lokasi ditolak. Izinkan akses lokasi di browser lalu coba lagi, atau pilih titik langsung di peta.');
                    } else if (error.code === error.TIMEOUT) {
                        setHelpMessage('Pengambilan lokasi timeout. Coba lagi atau pilih titik langsung di peta.');
                    } else {
                        setHelpMessage('Lokasi perangkat tidak bisa diambil. Pilih titik langsung di peta.');
                    }

                    isRequestingLocation = false;
                    setLocationButtonState(false);
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0,
                }
            );
        };

        const openModal = () => {
            locationModal.hidden = false;
            document.body.classList.add('modal-open');

            window.setTimeout(() => {
                map.invalidateSize();
            }, 150);

            requestCurrentLocation();
        };

        const closeModal = () => {
            locationModal.hidden = true;
            document.body.classList.remove('modal-open');
        };

        openModalButton?.addEventListener('click', openModal);
        confirmLocationButton?.addEventListener('click', () => {
            if (! selectedCoordinates && addressInput.value.trim() === '') {
                setHelpMessage('Pilih lokasi atau izinkan akses lokasi perangkat terlebih dahulu.');
                return;
            }

            closeModal();
        });

        locationModal.querySelectorAll('[data-close-location-modal]').forEach((element) => {
            element.addEventListener('click', closeModal);
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && ! locationModal.hidden) {
                closeModal();
            }
        });

        window.setTimeout(() => {
            map.invalidateSize();
        }, 150);
    }
});
