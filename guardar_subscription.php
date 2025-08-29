<?php
require __DIR__ . '/db.php';

$data = json_decode(file_get_contents('php://input'), true);

if (
    !$data ||
    !isset($data['endpoint'], $data['keys']['p256dh'], $data['keys']['auth'], $data['navegadorId'])
) {
    http_response_code(400);
    die("Datos inválidos");
}

$userAgent = $_SERVER['HTTP_USER_AGENT'];
$isFirefox = stripos($userAgent, 'Firefox') !== false;

try {
    if ($isFirefox) {
        $delete = $pdo->prepare("DELETE FROM subscriptions WHERE endpoint = :endpoint");
        $delete->execute([
            ':endpoint' => $data['endpoint']
        ]);
    }

    $sql = "INSERT INTO subscriptions (navegadorId, endpoint, p256dh, auth)
            VALUES (:navegadorId, :endpoint, :p256dh, :auth)
            ON DUPLICATE KEY UPDATE 
                p256dh = VALUES(p256dh),
                auth   = VALUES(auth)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':navegadorId' => $data['navegadorId'],
        ':endpoint'    => $data['endpoint'],
        ':p256dh'      => $data['keys']['p256dh'],
        ':auth'        => $data['keys']['auth']
    ]);

    echo "Suscripción guardada correctamente";
} catch (PDOException $e) {
    http_response_code(500);
    echo "Error en la base de datos: " . $e->getMessage();
}
