<?php

// Pastikan folder ini ada di Vercel untuk menyimpan cache
$tmpDirectories = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/sessions',
];

foreach ($tmpDirectories as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Arahkan config Laravel ke folder /tmp
$_ENV['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';
$_ENV['SESSION_DRIVER'] = 'cookie'; // Jangan pakai file untuk session
$_ENV['CACHE_STORE'] = 'array'; // Pakai array untuk cache (atau redis jika ada)

// Lanjutkan eksekusi file public/index.php bawaan Laravel
require __DIR__ . '/../public/index.php';