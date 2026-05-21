<?php

// Daftar direktori yang dibutuhkan Laravel untuk cache/view
$tmpDirectories = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
];

// Buat direktori jika belum ada
foreach ($tmpDirectories as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

// Paksa Laravel menggunakan direktori /tmp
$_ENV['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';
$_ENV['SESSION_DRIVER'] = 'array'; // Atau 'cookie'
$_ENV['CACHE_STORE'] = 'array';

// Jalankan file public/index.php bawaan Laravel
require __DIR__ . '/../public/index.php';