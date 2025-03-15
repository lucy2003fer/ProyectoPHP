<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Encabezados para permitir el acceso desde el frontend
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");

// Manejar solicitudes OPTIONS (CORS Preflight)
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit();
}

// Obtener la URL de la solicitud y descomponerla
$request = explode("/", trim($_SERVER["REQUEST_URI"], "/"));
$method = $_SERVER["REQUEST_METHOD"];

// Identificar la posición del controlador en la URL
$index = array_search("apiphp", $request);
if ($index === false || !isset($request[$index + 1])) {
    http_response_code(400);
    echo json_encode(["message" => "Solicitud incorrecta"]);
    exit();
}

// Manejo de rutas especiales para autenticación
if ($request[$index + 1] === "auth") {
    $authAction = $request[$index + 2] ?? null;

    if ($authAction === "login") {
        require_once __DIR__ . "/auth/login.php";
        exit();
    }

    if ($authAction === "validate") {
        require_once __DIR__ . "/auth/validate.php";
        exit();
    }

    http_response_code(404);
    echo json_encode(["message" => "Acción de autenticación no válida"]);
    exit();
}

// 🔒 **Proteger todo menos login/validate**
require_once 'auth/authMiddleware.php';
verifyToken(); // Si no pasa, corta aquí mismo el flujo

// Obtener el controlador
$table = ucfirst(strtolower($request[$index + 1])) . "Controller";
$controllerFile = __DIR__ . DIRECTORY_SEPARATOR . "Controllers" . DIRECTORY_SEPARATOR . $table . ".php";

// Verificar si el controlador existe
if (!file_exists($controllerFile)) {
    http_response_code(404);
    echo json_encode(["message" => "Recurso no encontrado"]);
    exit();
}

require_once $controllerFile;
$tableController = new $table();

// Obtener el ID si existe en la URL
$id = $request[$index + 2] ?? null;

// 🚀 **Manejar métodos CRUD**
switch ($method) {
    case 'GET':
        if ($id) {
            $tableController->getById($id);
        } else {
            $tableController->getAll();
        }
        break;

    case 'POST':
        $tableController->create();
        break;

    case 'PUT':
        if (!$id) {
            http_response_code(400);
            echo json_encode(["message" => "ID requerido para actualizar"]);
            exit();
        }
        $tableController->update($id);
        break;

    case 'DELETE':
        if (!$id) {
            http_response_code(400);
            echo json_encode(["message" => "ID requerido para eliminar"]);
            exit();
        }
        $tableController->delete($id);
        break;

    case 'PATCH':
        if (!$id) {
            http_response_code(400);
            echo json_encode(["message" => "ID requerido para actualizar"]);
            exit();
        }
        $tableController->patch($id);
        break;

    default:
        http_response_code(405);
        echo json_encode(["message" => "Método no permitido"]);
        break;
}
