<?php
require __DIR__ . '/vendor/autoload.php';

use Minishlink\WebPush\VAPID;

$keys = VAPID::createVapidKeys();
file_put_contents('vapid_public.key', $keys['publicKey']);
file_put_contents('vapid_private.key', $keys['privateKey']);
echo "Claves VAPID generadas\n";
