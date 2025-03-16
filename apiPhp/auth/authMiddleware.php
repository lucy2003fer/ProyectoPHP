<?php
require 'vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function verifyToken() {
    $headers = getallheaders();

    if (!isset($headers['Authorization'])) {
        echo json_encode(["message" => "Token no proporcionado"]);
        http_response_code(401);
        exit();
    }

    $token = str_replace("Bearer ", "", $headers['Authorization']);

    try {
        $decoded = JWT::decode($token, new Key('lucy', 'HS256'));
        return $decoded;
    } catch (Exception $e) {
        echo json_encode(["message" => "Token inválido", "error" => $e->getMessage()]);
        http_response_code(401);
        exit();
    }
}