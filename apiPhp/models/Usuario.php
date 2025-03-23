<?php

class Usuario {
    private $connect;
    private $table = "usuarios";

    public function __construct($db) {
        $this->connect = $db;
    }

    // Obtener todos los usuarios con el nombre del rol
    public function getAll() {
        $query = "SELECT 
                    u.identificacion, 
                    u.nombre, 
                    u.contrasena, 
                    u.email, 
                    r.id_rol, 
                    r.nombre_rol 
                  FROM usuarios u
                  LEFT JOIN rol r ON u.fk_id_rol = r.id_rol";

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
            $query = "SELECT 
                        u.identificacion, 
                        u.nombre, 
                        u.contrasena, 
                        u.email, 
                        r.id_rol, 
                        r.nombre_rol 
                      FROM usuarios u
                      LEFT JOIN rol r ON u.fk_id_rol = r.id_rol
                      WHERE u.identificacion = :identificacion";

            $stmt = $this->connect->prepare($query);
            $stmt->bindParam(':identificacion', $identificacion, PDO::PARAM_INT);
    
            if ($stmt->execute()) {
                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($usuario) {
                    return $usuario;
                }
                return false;
            } else {
                $error = $stmt->errorInfo();
                die("Error en la consulta de la DB: " . $error[2]);
            }
        } catch (PDOException $e) {
            echo "Error en la base de datos: " . $e->getMessage();
            return false;
        }
    }

    public function crearUsuario($identificacion, $nombre, $contrasena, $email, $fk_id_rol) {
        try {
            $query = "INSERT INTO $this->table (identificacion, nombre, contrasena, email, fk_id_rol) 
                      VALUES (:identificacion, :nombre, :contrasena, :email, :fk_id_rol)";
            $stmt = $this->connect->prepare($query);

            // Encriptar la contraseña antes de guardarla
            $contrasenaEncriptada = password_hash($contrasena, PASSWORD_DEFAULT);

            $stmt->bindParam(':identificacion', $identificacion);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':contrasena', $contrasenaEncriptada);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':fk_id_rol', $fk_id_rol, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error en la base de datos: " . $e->getMessage();
            return false;
        }
    }

    public function eliminarUsuario($identificacion) {
        try {
            $query = "DELETE FROM $this->table WHERE identificacion = :identificacion";
            $stmt = $this->connect->prepare($query);
            $stmt->bindParam(':identificacion', $identificacion, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error en la base de datos: " . $e->getMessage();
            return false;
        }
    }
}
