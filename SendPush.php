<?php
require __DIR__ . '/vendor/autoload.php';

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

header('Content-Type: text/plain');

$subscriptionData = json_decode(file_get_contents('subscription.json'), true);
if (!$subscriptionData) die("No hay suscripción registrada\n");

$subscription = Subscription::create($subscriptionData);

$auth = [
    'VAPID' => [
        'subject' => 'mailto:kinchin.figueroa@gmail.com',
        'publicKey' => trim(file_get_contents('vapid_public.key')),
        'privateKey' => trim(file_get_contents('vapid_private.key')),
    ],
];

$webPush = new WebPush($auth);

$webPush->queueNotification(
    $subscription,
    json_encode([
        'title' => 'Hola 👋',
        'body' => '¡Notificación push desde PHP!',
    ])
);

$output = '';

foreach ($webPush->flush() as $report) {
    if ($report->isSuccess()) {
        $output .= "Enviado correctamente al endpoint: " . $report->getEndpoint() . "\n";
    } else {
        $output .= "Error al enviar: " . $report->getReason() . "\n";
    }
}

echo $output;
