<?php 

require_once("./config/database.php");
require_once("./models/Usuario.php");

class UsuarioController {
    private $db;
    private $usuario;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConection();
        $this->usuario = new Usuario($this->db);
    }

    // Obtener todos los usuarios
    public function getAll() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(["message" => "Método no permitido"]);
            return;
        }

        $stmt = $this->usuario->getAll();
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Formatear respuesta con el nombre del rol
        $usuariosFormateados = array_map(function ($usuario) {
            return [
                "identificacion" => $usuario["identificacion"],
                "nombre" => $usuario["nombre"],
                "contrasena" => $usuario["contrasena"],
                "email" => $usuario["email"],
                "fk_id_rol" => [
                    "id_rol" => $usuario["id_rol"] ?? null, 
                    "nombre_rol" => $usuario["nombre_rol"] ?? "Sin rol asignado"
                ]
            ];
        }, $usuarios);

        echo json_encode([
            "status" => 200,
            "data" => $usuariosFormateados
        ]);
        http_response_code(200);
    }

    // Obtener un usuario por ID
    public function getById($identificacion) {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(["message" => "Método no permitido"]);
            return;
        }

        $usuario = $this->usuario->getById($identificacion);
        if ($usuario) {
            echo json_encode([
                "status" => 200,
                "data" => [
                    "identificacion" => $usuario["identificacion"],
                    "nombre" => $usuario["nombre"],
                    "contrasena" => $usuario["contrasena"],
                    "email" => $usuario["email"],
                    "fk_id_rol" => [
                        "id_rol" => $usuario["id_rol"] ?? null, 
                        "nombre_rol" => $usuario["nombre_rol"] ?? "Sin rol asignado"
                    ]
                ]
            ]);
            http_response_code(200);
        } else {
            echo json_encode(["message" => "Usuario no encontrado"]);
            http_response_code(404);
        }
    }

    // Crear un usuario
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(["message" => "Método no permitido"]);
            return;
        }

        $data = json_decode(file_get_contents("php://input"), true);
        
        if (!isset($data['identificacion'], $data['nombre'], $data['contrasena'], $data['email'], $data['fk_id_rol'])) {
            echo json_encode(["message" => "Datos incompletos"]);
            http_response_code(400);
            return;
        }

        if ($this->usuario->crearUsuario($data['identificacion'], $data['nombre'], $data['contrasena'], $data['email'], $data['fk_id_rol'])) {
            echo json_encode(["message" => "Usuario registrado exitosamente"]);
            http_response_code(201);
        } else {
            echo json_encode(["message" => "Error al registrar usuario"]);
            http_response_code(500);
        }
    }

    // Actualizar un usuario
    public function update($identificacion) {
        if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
            http_response_code(405);
            echo json_encode(["message" => "Método no permitido"]);
            return;
        }

        $data = json_decode(file_get_contents("php://input"), true);
        
        if (!isset($data['nombre'], $data['contrasena'], $data['email'], $data['fk_id_rol'])) {
            echo json_encode(["message" => "Datos incompletos"]);
            http_response_code(400);
            return;
        }

        if ($this->usuario->actualizarUsuario($identificacion, $data['nombre'], $data['contrasena'], $data['email'], $data['fk_id_rol'])) {
            echo json_encode(["message" => "Usuario actualizado exitosamente"]);
            http_response_code(200);
        } else {
            echo json_encode(["message" => "Error al actualizar usuario"]);
            http_response_code(500);
        }
    }

    // Eliminar un usuario
    public function delete($identificacion) {
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            http_response_code(405);
            echo json_encode(["message" => "Método no permitido"]);
            return;
        }

        if ($this->usuario->eliminarUsuario($identificacion)) {
            echo json_encode(["message" => "Usuario eliminado exitosamente"]);
            http_response_code(200);
        } else {
            echo json_encode(["message" => "Error al eliminar usuario"]);
            http_response_code(500);
        }
    }
}
