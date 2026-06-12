#!/usr/bin/env php
<?php

// Quick test script to verify Xendit configuration
$envPath = __DIR__ . '/.env';
$configPath = __DIR__ . '/config/services.php';

echo "=== Xendit Configuration Test ===\n\n";

// Check .env file
echo "1. Checking .env file...\n";
if (file_exists($envPath)) {
    $envContent = file_get_contents($envPath);
    $hasSecretKey = preg_match('/XENDIT_SECRET_KEY=xnd_development_/i', $envContent);
    $hasPublicKey = preg_match('/XENDIT_PUBLIC_KEY=xnd_public_development_/i', $envContent);
    
    echo "   ✓ .env file exists\n";
    echo "   " . ($hasSecretKey ? "✓" : "✗") . " XENDIT_SECRET_KEY configured\n";
    echo "   " . ($hasPublicKey ? "✓" : "✗") . " XENDIT_PUBLIC_KEY configured\n";
} else {
    echo "   ✗ .env file not found\n";
}

// Check config file
echo "\n2. Checking config/services.php...\n";
if (file_exists($configPath)) {
    $content = file_get_contents($configPath);
    $hasXenditConfig = strpos($content, "'xendit'") !== false;
    echo "   ✓ config/services.php exists\n";
    echo "   " . ($hasXenditConfig ? "✓" : "✗") . " Xendit config section exists\n";
} else {
    echo "   ✗ config/services.php not found\n";
}

// Check SDK installed
echo "\n3. Checking Xendit SDK installation...\n";
$sdkPath = __DIR__ . '/vendor/xendit/xendit-php/lib/Xendit.php';
if (file_exists($sdkPath)) {
    echo "   ✓ Xendit SDK installed\n";
    
    // Try to load and test
    try {
        require_once $sdkPath;
        echo "   ✓ SDK can be loaded\n";
    } catch (Exception $e) {
        echo "   ✗ SDK load error: " . $e->getMessage() . "\n";
    }
} else {
    echo "   ✗ Xendit SDK not found at: $sdkPath\n";
    echo "   Run: composer require xendit/xendit-php\n";
}

// Check migration
echo "\n4. Checking database migrations...\n";
$migrationsPath = __DIR__ . '/database/migrations/2026_05_27_000000_add_xendit_columns_to_transactions.php';
if (file_exists($migrationsPath)) {
    echo "   ✓ Xendit migration file exists\n";
} else {
    echo "   ✗ Xendit migration file not found\n";
}

// Check controller
echo "\n5. Checking XenditController...\n";
$controllerPath = __DIR__ . '/app/Http/Controllers/XenditController.php';
if (file_exists($controllerPath)) {
    echo "   ✓ XenditController exists\n";
} else {
    echo "   ✗ XenditController not found\n";
}

echo "\n=== Test Complete ===\n";
echo "\nNext steps:\n";
echo "1. Make sure your Xendit keys are correct in .env\n";
echo "2. Run: php artisan migrate\n";
echo "3. Run: php artisan serve\n";
echo "4. Open F12 console while testing\n";
