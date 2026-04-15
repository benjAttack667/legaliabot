<?php

require_once __DIR__ . '/bootstrap.php';

$checks = [
    'php_version' => PHP_VERSION,
    'php_ok' => version_compare(PHP_VERSION, '8.1.0', '>='),
    'storage_available' => is_dir(storage_path()),
    'openai_configured' => app_env('OPENAI_API_KEY', '') !== '',
    'firebase_configured' => (app_config()['firebase']['projectId'] ?? '') !== '',
];

$checks['ok'] = $checks['php_ok'] && $checks['storage_available'];

json_response($checks, $checks['ok'] ? 200 : 500);
