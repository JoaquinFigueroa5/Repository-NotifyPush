<?php
require __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$titulo = 'Notificación Global 🌎';
$mensaje = 'Este mensaje será enviado a todos los navegadores';

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();
$channel->queue_declare('push_notifications', false, true, false, false);

$msg = new AMQPMessage(json_encode([
    'titulo' => $titulo,
    'mensaje' => $mensaje
]));

$channel->basic_publish($msg, '', 'push_notifications');
echo "Mensaje enviado a la cola de RabbitMQ\n";

$channel->close();
$connection->close();
