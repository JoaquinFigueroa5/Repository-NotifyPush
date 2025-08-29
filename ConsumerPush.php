<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/db.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();
$channel->queue_declare('push_notifications', false, true, false, false);

$callback = function ($msg) use ($pdo) {
    $data = json_decode($msg->body, true);

    $stmt = $pdo->query("SELECT * FROM subscriptions");
    $subscriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $auth = [
        'VAPID' => [
            'subject' => 'mailto:kinchin.figueroa@gmail.com',
            'publicKey' => trim(file_get_contents('vapid_public.key')),
            'privateKey' => trim(file_get_contents('vapid_private.key')),
        ],
    ];

    $webPush = new WebPush($auth);

    foreach ($subscriptions as $sub) {
        $subscription = Subscription::create([
            'endpoint' => $sub['endpoint'],
            'keys' => [
                'p256dh' => $sub['p256dh'],
                'auth' => $sub['auth']
            ]
        ]);

        $webPush->queueNotification(
            $subscription,
            json_encode([
                'title' => $data['titulo'],
                'body' => $data['mensaje']
            ])
        );
    }

    foreach ($webPush->flush() as $report) {
        if ($report->isSuccess()) {
            echo "Enviado: " . $report->getEndpoint() . "\n";
        } else {
            echo "Error: " . $report->getReason() . "\n";
        }
    }
};

$channel->basic_consume('push_notifications', '', false, true, false, false, $callback);

echo "Esperando mensajes de la cola...\n";

while (true) {
    try {
        $channel->wait(null, false, 1);
    } catch (\PhpAmqpLib\Exception\AMQPTimeoutException $e) {

    }
}
