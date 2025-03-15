<?php
require_once(__DIR__ . '/../config/dataBase.php');

require_once(__DIR__ . '/../models/Usuario.php');

require_once(__DIR__ . '/../vendor/autoload.php');


use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Conexión DB
$database = new Database();
$db = $database->getConection();
$usuario = new Usuario($db);

// Recibimos los datos del JSON
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['identificacion']) || !isset($data['contrasena'])) {
    echo json_encode(["message" => "Datos incompletos"]);
    http_response_code(400);
    exit();
}

// Buscamos el usuario por identificación
$user = $usuario->getById($data['identificacion']);
if (!$user || !password_verify($data['contrasena'], $user['contrasena'])) {
    echo json_encode(["message" => "Credenciales incorrectas"]);
    http_response_code(401);
    exit();
}


// Generamos el JWT
$secret_key = "lucy";
$payload = [
    "iss" => "localhost",
    "aud" => "localhost",
    "iat" => time(),
    "exp" => time() + (60 * 60), // Expira en 1 hora
    "data" => [
        "id" => $user['identificacion'],
        "nombre" => $user['nombre']
    ]
];

$jwt = JWT::encode($payload, $secret_key, 'HS256');

echo json_encode(["token" => $jwt]);
http_response_code(200);
