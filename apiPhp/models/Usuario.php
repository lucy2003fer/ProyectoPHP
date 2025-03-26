<?php

class Usuario
{
    private $connect;
    private $table = "usuarios";

    public function __construct($db)
    {
        $this->connect = $db;
    }

    // Obtener todos los usuarios con el nombre del rol
    public function getAll()
    {
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

    // Obtener un usuario por su ID
    public function getById($identificacion)
    {
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

    // Crear un nuevo usuario
    public function crearUsuario($identificacion, $nombre, $contrasena, $email, $fk_id_rol)
    {
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

    // Actualizar un usuario existente
    public function actualizarUsuario($identificacion, $nombre, $contrasena, $email, $fk_id_rol)
    {
        try {
            $query = "UPDATE $this->table 
                      SET nombre = :nombre, 
                          contrasena = :contrasena, 
                          email = :email, 
                          fk_id_rol = :fk_id_rol 
                      WHERE identificacion = :identificacion";
            $stmt = $this->connect->prepare($query);

            // Encriptar la contraseña antes de guardarla
            $contrasenaEncriptada = password_hash($contrasena, PASSWORD_DEFAULT);

            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':contrasena', $contrasenaEncriptada);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':fk_id_rol', $fk_id_rol, PDO::PARAM_INT);
            $stmt->bindParam(':identificacion', $identificacion, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error en la base de datos: " . $e->getMessage();
            return false;
        }
    }

    // Eliminar un usuario por su ID
    public function eliminarUsuario($identificacion)
    {
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


    public function actualizarParcial($id, $data) {
        try {
            $fields = [];
            foreach ($data as $key => $value) {
                $fields[] = "$key = :$key";
            }

            if (empty($fields)) {
                return false;
            }

            $query = "UPDATE $this->table SET " . implode(", ", $fields) . " WHERE identificacion = :id";
            $stmt = $this->connect->prepare($query);

            foreach ($data as $key => &$value) {
                $stmt->bindParam(":$key", $value);
            }
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al actualizar usuario: " . $e->getMessage());
            return false;
        }
    }
    
}