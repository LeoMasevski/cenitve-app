<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

header('Content-Type: application/json');

if (!is_logged_in()) {
    http_response_code(401);

    echo json_encode([
        'success' => false,
        'message' => 'Uporabnik ni prijavljen.'
    ]);

    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

$id = (int) ($input['id'] ?? 0);

if ($id <= 0) {
    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Neveljaven ID cenitve.'
    ]);

    exit;
}

$stmt = $pdo->prepare(
    'DELETE FROM valuations
     WHERE id = ? AND user_id = ?'
);

$stmt->execute([
    $id,
    current_user_id()
]);

if ($stmt->rowCount() === 0) {
    http_response_code(404);

    echo json_encode([
        'success' => false,
        'message' => 'Cenitev ni bila najdena ali nimate dovoljenja za brisanje.'
    ]);

    exit;
}

echo json_encode([
    'success' => true,
    'message' => 'Cenitev je bila uspešno izbrisana.'
]);