<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Clave secreta para generar y validar el token
$secret_key = "lucy";

// Obtener el token del encabezado
$headers = getallheaders();
$token = $headers['Authorization'] ?? null;

if (!$token) {
    http_response_code(401);
    echo json_encode(["message" => "Token requerido"]);
    exit();
}

try {
    $decoded = JWT::decode($token, new Key($secret_key, 'HS256'));
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(["message" => "Token inválido: " . $e->getMessage()]);
    exit();
}
