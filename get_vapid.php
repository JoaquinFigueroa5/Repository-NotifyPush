<?php
header('Content-Type: application/json');
echo json_encode([
    'publicKey' => file_get_contents('vapid_public.key')
]);
