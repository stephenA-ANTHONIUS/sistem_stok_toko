<?php

$tmpDirectories = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
];

foreach ($tmpDirectories as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

putenv('VIEW_COMPILED_PATH=/tmp/storage/framework/views');
putenv('SESSION_DRIVER=cookie');
putenv('CACHE_STORE=array');
putenv('LOG_CHANNEL=stderr');

$_ENV['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';
$_ENV['SESSION_DRIVER'] = 'cookie';
$_ENV['CACHE_STORE'] = 'array';

// Tangkap error fatal sebelum Laravel boot
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error) {
        file_put_contents('/tmp/last_error.txt', json_encode($error, JSON_PRETTY_PRINT));
    }
});

ini_set('display_errors', 0);
error_reporting(E_ALL);

require __DIR__ . '/../public/index.php';