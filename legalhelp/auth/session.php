<?php

require_once __DIR__ . '/../bootstrap.php';

$method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

if ($method === 'POST' && ($_POST['action'] ?? '') === 'logout') {
    clear_authenticated_user();
    session_regenerate_id(true);
    json_response(['ok' => true]);
}

if ($method === 'POST') {
    $idToken = trim((string) ($_POST['id_token'] ?? ''));
    $user = authenticate_with_id_token($idToken);

    if ($user === null) {
        json_response([
            'ok' => false,
            'error' => 'Session invalide. Merci de vous reconnecter.',
        ], 401);
    }

    json_response([
        'ok' => true,
        'user' => $user,
    ]);
} elseif ($method === 'DELETE') {
    clear_authenticated_user();
    session_regenerate_id(true);
    json_response(['ok' => true]);
}

json_response([
    'ok' => false,
    'error' => 'Methode non autorisee.',
], 405);
