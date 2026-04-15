<?php

$isHttpsRequest = (
    (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (isset($_SERVER['SERVER_PORT']) && (string) $_SERVER['SERVER_PORT'] === '443')
);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params([
        'httponly' => true,
        'secure' => $isHttpsRequest,
        'samesite' => 'Lax',
        'path' => '/',
    ]);
    session_start();
}

load_app_env();

if (app_env('APP_ENV', 'production') !== 'development') {
    ini_set('display_errors', '0');
}

error_reporting(E_ALL);

function load_app_env()
{
    static $loaded = false;

    if ($loaded) {
        return;
    }

    $loaded = true;
    $envPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env';

    if (!is_file($envPath) || !is_readable($envPath)) {
        return;
    }

    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        $trimmed = trim($line);

        if ($trimmed === '' || strpos($trimmed, '#') === 0 || strpos($trimmed, '=') === false) {
            continue;
        }

        list($key, $value) = array_map('trim', explode('=', $trimmed, 2));

        if ($key === '') {
            continue;
        }

        $value = trim($value, "\"'");

        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
        putenv($key . '=' . $value);
    }
}

function app_env($key, $default = null)
{
    $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);

    if ($value === false || $value === null || $value === '') {
        return $default;
    }

    return $value;
}

function app_config()
{
    static $config;

    if ($config !== null) {
        return $config;
    }

    $config = [
        'firebase' => [
            'apiKey' => app_env('FIREBASE_API_KEY', 'AIzaSyDo2UxQs_Jynz8gAMEhzcmdzwP2F6V5uCc'),
            'authDomain' => app_env('FIREBASE_AUTH_DOMAIN', 'auth-firebase-2f5c8.firebaseapp.com'),
            'projectId' => app_env('FIREBASE_PROJECT_ID', 'auth-firebase-2f5c8'),
            'storageBucket' => app_env('FIREBASE_STORAGE_BUCKET', 'auth-firebase-2f5c8.appspot.com'),
            'messagingSenderId' => app_env('FIREBASE_MESSAGING_SENDER_ID', '38532235265'),
            'appId' => app_env('FIREBASE_APP_ID', '1:38532235265:web:ca304c65fd2da6aa27c000'),
            'measurementId' => app_env('FIREBASE_MEASUREMENT_ID', 'G-VGK4ET6R0C'),
        ],
        'openai' => [
            'apiKey' => app_env('OPENAI_API_KEY'),
            'model' => app_env('OPENAI_MODEL', 'gpt-4o-mini'),
            'moderationModel' => app_env('OPENAI_MODERATION_MODEL', 'omni-moderation-latest'),
        ],
        'chat' => [
            'maxQuestionLength' => (int) app_env('CHAT_MAX_QUESTION_LENGTH', 1200),
            'rateLimitPerMinute' => (int) app_env('CHAT_RATE_LIMIT_PER_MINUTE', 12),
        ],
    ];

    return $config;
}

function firebase_web_config_json()
{
    return json_encode(app_config()['firebase'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
}

function h($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function json_response($payload, $statusCode = 200)
{
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit();
}

function storage_path($relativePath = '')
{
    $base = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'storage';

    if (!is_dir($base)) {
        mkdir($base, 0775, true);
    }

    if ($relativePath === '') {
        return $base;
    }

    return $base . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $relativePath);
}

function ensure_storage_directory($relativePath)
{
    $path = storage_path($relativePath);

    if (!is_dir($path)) {
        mkdir($path, 0775, true);
    }

    return $path;
}

function current_user()
{
    if (empty($_SESSION['firebase_uid'])) {
        return null;
    }

    return [
        'uid' => (string) $_SESSION['firebase_uid'],
        'email' => (string) ($_SESSION['firebase_email'] ?? ''),
        'name' => (string) ($_SESSION['firebase_name'] ?? 'Utilisateur'),
    ];
}

function set_authenticated_user($user)
{
    if (empty($_SESSION['firebase_uid']) || $_SESSION['firebase_uid'] !== $user['uid']) {
        session_regenerate_id(true);
    }

    $_SESSION['firebase_uid'] = $user['uid'];
    $_SESSION['firebase_email'] = $user['email'] ?? '';
    $_SESSION['firebase_name'] = $user['name'] ?? 'Utilisateur';
    $_SESSION['authenticated_at'] = time();
}

function clear_authenticated_user()
{
    unset(
        $_SESSION['firebase_uid'],
        $_SESSION['firebase_email'],
        $_SESSION['firebase_name'],
        $_SESSION['authenticated_at'],
        $_SESSION['chat']
    );
}

function verify_firebase_id_token($idToken)
{
    $firebaseConfig = app_config()['firebase'];
    $apiKey = $firebaseConfig['apiKey'] ?? '';

    if ($idToken === '' || $apiKey === '') {
        return null;
    }

    $options = [
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/json\r\n",
            'content' => json_encode(['idToken' => $idToken]),
            'ignore_errors' => true,
            'timeout' => 12,
        ],
    ];

    $endpoint = 'https://identitytoolkit.googleapis.com/v1/accounts:lookup?key=' . rawurlencode($apiKey);
    $response = @file_get_contents($endpoint, false, stream_context_create($options));

    if ($response === false) {
        return null;
    }

    $decoded = json_decode($response, true);
    $user = $decoded['users'][0] ?? null;

    if (!is_array($user) || empty($user['localId'])) {
        return null;
    }

    return [
        'uid' => (string) $user['localId'],
        'email' => (string) ($user['email'] ?? ''),
        'name' => (string) ($user['displayName'] ?? $user['email'] ?? 'Utilisateur'),
    ];
}

function authenticate_with_id_token($idToken)
{
    $user = verify_firebase_id_token((string) $idToken);

    if ($user === null) {
        return null;
    }

    set_authenticated_user($user);
    return $user;
}

function safe_storage_filename($value)
{
    $sanitized = preg_replace('/[^a-zA-Z0-9_-]/', '_', (string) $value);
    return trim($sanitized, '_') ?: 'user';
}

function chat_history_file($uid)
{
    ensure_storage_directory('chat_history');
    return storage_path('chat_history/' . safe_storage_filename($uid) . '.json');
}

function load_chat_history($uid)
{
    $file = chat_history_file($uid);

    if (!is_file($file)) {
        return [];
    }

    $contents = file_get_contents($file);
    $messages = json_decode((string) $contents, true);

    return is_array($messages) ? array_values($messages) : [];
}

function save_chat_history($uid, $messages)
{
    file_put_contents(
        chat_history_file($uid),
        json_encode(array_values($messages), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        LOCK_EX
    );
}

function append_chat_history($uid, $question, $answer)
{
    $history = load_chat_history($uid);
    $history[] = [
        'question' => (string) $question,
        'reponse' => (string) $answer,
        'created_at' => date('c'),
    ];

    save_chat_history($uid, $history);
    return $history;
}

function clear_chat_history($uid)
{
    $file = chat_history_file($uid);

    if (is_file($file)) {
        unlink($file);
    }
}

function save_contact_message($payload)
{
    ensure_storage_directory('contact');
    $file = storage_path('contact/messages.log');
    $line = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . PHP_EOL;
    file_put_contents($file, $line, FILE_APPEND | LOCK_EX);
}

function user_request_identifier()
{
    $user = current_user();

    if ($user && !empty($user['uid'])) {
        return 'user:' . $user['uid'];
    }

    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    return 'ip:' . $ip;
}

function rate_limit_file($scope, $identifier)
{
    ensure_storage_directory('rate_limits');
    $filename = safe_storage_filename($scope . '_' . sha1($identifier));
    return storage_path('rate_limits/' . $filename . '.json');
}

function is_rate_limited($scope, $identifier, $limit, $windowSeconds)
{
    $limit = max(1, (int) $limit);
    $windowSeconds = max(1, (int) $windowSeconds);
    $file = rate_limit_file($scope, $identifier);
    $now = time();
    $history = [];

    if (is_file($file)) {
        $decoded = json_decode((string) file_get_contents($file), true);
        if (is_array($decoded)) {
            $history = array_values(array_filter($decoded, static function ($timestamp) use ($now, $windowSeconds) {
                return is_int($timestamp) && ($now - $timestamp) < $windowSeconds;
            }));
        }
    }

    if (count($history) >= $limit) {
        file_put_contents($file, json_encode($history), LOCK_EX);
        return true;
    }

    $history[] = $now;
    file_put_contents($file, json_encode($history), LOCK_EX);
    return false;
}

function moderate_openai_input($input)
{
    $openaiConfig = app_config()['openai'];
    $apiKey = trim((string) ($openaiConfig['apiKey'] ?? ''));
    $model = trim((string) ($openaiConfig['moderationModel'] ?? 'omni-moderation-latest'));

    if ($apiKey === '') {
        return [
            'checked' => false,
            'flagged' => false,
        ];
    }

    $payload = [
        'model' => $model,
        'input' => (string) $input,
    ];

    $options = [
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/json\r\nAuthorization: Bearer {$apiKey}\r\n",
            'content' => json_encode($payload),
            'ignore_errors' => true,
            'timeout' => 20,
        ],
    ];

    $response = @file_get_contents('https://api.openai.com/v1/moderations', false, stream_context_create($options));

    if ($response === false) {
        return [
            'checked' => false,
            'flagged' => false,
        ];
    }

    $decoded = json_decode($response, true);
    $result = $decoded['results'][0] ?? [];

    return [
        'checked' => true,
        'flagged' => !empty($result['flagged']),
        'categories' => $result['categories'] ?? [],
    ];
}

function openai_response_for_question($question)
{
    $openaiConfig = app_config()['openai'];
    $apiKey = trim((string) ($openaiConfig['apiKey'] ?? ''));
    $model = trim((string) ($openaiConfig['model'] ?? ''));

    if ($apiKey === '') {
        return "Le service d'assistance IA n'est pas encore configure. Ajoutez OPENAI_API_KEY dans votre fichier .env pour activer le chat.";
    }

    if ($model === '') {
        $model = 'gpt-4o-mini';
    }

    $payload = [
        'model' => $model,
        'messages' => [
            ['role' => 'system', 'content' => "Tu es un assistant juridique pedagogique. Rappelle que tu ne remplaces pas un avocat et propose une reponse claire, structuree et prudente."],
            ['role' => 'user', 'content' => (string) $question],
        ],
        'temperature' => 0.4,
        'safety_identifier' => sha1(user_request_identifier()),
    ];

    $options = [
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/json\r\nAuthorization: Bearer {$apiKey}\r\n",
            'content' => json_encode($payload),
            'ignore_errors' => true,
            'timeout' => 30,
        ],
    ];

    $response = @file_get_contents('https://api.openai.com/v1/chat/completions', false, stream_context_create($options));

    if ($response === false) {
        return "Le service d'assistance IA est temporairement indisponible. Reessayez dans quelques instants.";
    }

    $decoded = json_decode($response, true);

    if (!empty($decoded['choices'][0]['message']['content'])) {
        return trim((string) $decoded['choices'][0]['message']['content']);
    }

    return "Je n'ai pas pu generer de reponse pour le moment. Merci de reformuler votre question ou de reessayer plus tard.";
}
