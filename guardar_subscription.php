<?php
$data = file_get_contents('php://input');
file_put_contents('subscription.json', $data);
echo "Suscripción guardada";
