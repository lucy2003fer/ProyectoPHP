<?php

class Usuario {
    private $connect;
    private $table = "usuarios";

    private $identificacion;
    private $nombre;
    private $contrasena;
    private $email;
    private $fk_id_rol;

    public function __construct($db) {
        $this->connect = $db;
    }

    // Obtener todos los usuarios
    public function getAll() {
        $query = "SELECT * FROM $this->table";
        $stmt = $this->connect->prepare($query);
        if ($stmt->execute()) {
            return $stmt;
        } else {
            $error = $stmt->errorInfo();
            die("Error en la consulta de la DB: " . $error[2]);
        }
    }

    public function getById($identificacion) {
        try {
            $query = "SELECT identificacion, nombre, contrasena FROM $this->table WHERE identificacion = :identificacion";
            $stmt = $this->connect->prepare($query);
            $stmt->bindParam(':identificacion', $identificacion, PDO::PARAM_INT);
    
            if ($stmt->execute()) {
                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($usuario) {
                    return array_map('trim', $usuario); // Limpia espacios en los resultados
                }
                return false;
            } else {
                $error = $stmt->errorInfo();
                die("Error en la consulta de la DB: " . $error[2]);
            }
        } catch (PDOException $e) {
            echo "Error en la base de datos: " . $e->getMessage();
            error_log("Error al obtener usuario por ID: " . $e->getMessage());
            return false;
        }
    }
    
    


    // Crear un nuevo usuario
    public function crearUsuario($identificacion, $nombre, $contrasena, $email, $fk_id_rol) {
        try {
            $query = "INSERT INTO $this->table (identificacion, nombre, contrasena, email, fk_id_rol) VALUES (:identificacion, :nombre, :contrasena, :email, :fk_id_rol)";
            $stmt = $this->connect->prepare($query);

        // ENCRIPTAR la contraseña antes de guardarla
            $contrasenaEncriptada = password_hash($contrasena, PASSWORD_DEFAULT);

            $stmt->bindParam(':identificacion', $identificacion);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':contrasena', $contrasenaEncriptada);  // Guardamos la contraseña encriptada
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':fk_id_rol', $fk_id_rol, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error en la base de datos: " . $e->getMessage();
            error_log("Error al registrar un usuario: " . $e->getMessage());
            return false;
        }
    }


// Actualizar un usuario
    public function update($identificacion) {
        if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
            $data = json_decode(file_get_contents("php://input"), true);

            if (!isset($data['nombre']) || !isset($data['email']) || !isset($data['fk_id_rol'])) {
                echo json_encode(["message" => "Datos incompletos"]);
                http_response_code(400);
                return;
            }

        // Verificamos si se envió una nueva contraseña
            $contrasena = isset($data['contrasena']) && !empty($data['contrasena'])
                ? $data['contrasena']
                : null;

        // Si hay una contraseña nueva, la encripta
            if ($contrasena) {
                $contrasena = password_hash($contrasena, PASSWORD_DEFAULT);
            } else {
            // Si no hay contraseña nueva, obtengo la antigua para no perderla
                $usuarioExistente = $this->usuario->getById($identificacion);
                $contrasena = $usuarioExistente['contrasena'];
            }

            if ($this->usuario->actualizarUsuario($identificacion, $data['nombre'], $contrasena, $data['email'], $data['fk_id_rol'])) {
            echo json_encode(["message" => "Usuario actualizado exitosamente"]);
            http_response_code(200);
            } else {
            echo json_encode(["message" => "Error al actualizar usuario"]);
            http_response_code(500);
            }
        }
    }


    // Eliminar un usuario
    public function eliminarUsuario($identificacion) {
        try {
            $query = "DELETE FROM $this->table WHERE identificacion = :identificacion";
            $stmt = $this->connect->prepare($query);
            $stmt->bindParam(':identificacion', $identificacion, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error en la base de datos: " . $e->getMessage();
            error_log("Error al eliminar un usuario: " . $e->getMessage());
            return false;
        }
    }
}
