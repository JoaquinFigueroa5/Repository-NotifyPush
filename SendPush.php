<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/db.php';

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

$targetId = $_GET['navegadorId'] ?? null;

if (!$targetId) die("No se especificó navegadorId");

$stmt = $pdo->prepare("SELECT * FROM subscriptions WHERE navegadorId = :nid");
$stmt->execute([':nid' => $targetId]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) die("No se encontró suscripción para este navegador");

$subscription = Subscription::create([
    'endpoint' => $data['endpoint'],
    'keys' => [
        'p256dh' => $data['p256dh'],
        'auth'   => $data['auth']
    ]
]);

$auth = [
    'VAPID' => [
        'subject'    => 'mailto:kinchinfigueroa@gmail.com',
        'publicKey'  => trim(file_get_contents('vapid_public.key')),
        'privateKey' => trim(file_get_contents('vapid_private.key')),
    ],
];

$webPush = new WebPush($auth);

$webPush->queueNotification(
    $subscription,
    json_encode([
        'title' => 'Hola 👋',
        'body'  => '¡Notificación push individual!',
    ])
);

foreach ($webPush->flush() as $report) {
    echo $report->isSuccess()
        ? "Notificación enviada correctamente"
        : "Error al enviar: " . $report->getReason();
}
