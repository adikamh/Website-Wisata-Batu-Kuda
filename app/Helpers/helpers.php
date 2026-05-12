<?php

if (! function_exists('mask_phone')) {
    function mask_phone(?string $phone): string
    {
        $phone = trim((string) $phone);

        if ($phone === '') {
            return '';
        }

        $length = strlen($phone);

        if ($length <= 8) {
            return str_repeat('*', $length);
        }

        return substr($phone, 0, 4)
            . str_repeat('*', max(0, $length - 8))
            . substr($phone, -4);
    }
}
