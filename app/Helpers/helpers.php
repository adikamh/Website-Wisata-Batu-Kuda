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

if (! function_exists('mask_address')) {
    function mask_address(?string $address): string
    {
        $address = trim((string) $address);

        if ($address === '') {
            return '';
        }

        $length = strlen($address);

        if ($length <= 12) {
            return substr($address, 0, 2)
                . str_repeat('*', max(0, $length - 4))
                . substr($address, -2);
        }

        return substr($address, 0, 8)
            . str_repeat('*', min(24, max(8, $length - 12)))
            . substr($address, -4);
    }
}