<?php

// Tangkap error PHP sebelum Laravel handle
set_exception_handler(function($e) {
    http_response_code(500);
    echo '<pre style="color:red;font-size:14px;">';
    echo '<b>REAL ERROR:</b> ' . get_class($e) . "\n";
    echo '<b>Message:</b> ' . $e->getMessage() . "\n";
    echo '<b>File:</b> ' . $e->getFile() . ':' . $e->getLine() . "\n";
    echo '<b>Trace:</b>' . "\n" . $e->getTraceAsString();
    echo '</pre>';
    exit(1);
});

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

require __DIR__ . '/../public/index.php';