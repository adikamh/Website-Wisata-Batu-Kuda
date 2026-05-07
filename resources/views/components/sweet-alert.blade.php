@props([
    'flash' => true,
    'validation' => true,
])

@once
    @vite(['resources/js/sweet-alert.js'])
@endonce

@php
    $alerts = [];

    if ($flash) {
        $flashMessages = [
            'success' => ['icon' => 'success', 'title' => 'Berhasil'],
            'status' => ['icon' => 'success', 'title' => 'Informasi'],
            'info' => ['icon' => 'info', 'title' => 'Informasi'],
            'warning' => ['icon' => 'warning', 'title' => 'Perhatian'],
            'error' => ['icon' => 'error', 'title' => 'Gagal'],
        ];

        foreach ($flashMessages as $key => $config) {
            if (session()->has($key)) {
                $alerts[] = [
                    'icon' => $config['icon'],
                    'title' => $config['title'],
                    'text' => session($key),
                ];
            }
        }
    }

    if ($validation && isset($errors) && $errors->any()) {
        $errorItems = collect($errors->all())
            ->map(fn ($message) => '<li>' . e($message) . '</li>')
            ->implode('');

        $alerts[] = [
            'icon' => 'error',
            'title' => 'Periksa kembali input',
            'html' => '<ul style="margin:0;padding-left:1.2rem;text-align:left;">' . $errorItems . '</ul>',
        ];
    }
@endphp

@if (! empty($alerts))
    <script type="application/json" data-sweet-alert-payload>
        {!! json_encode($alerts, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}
    </script>
@endif
