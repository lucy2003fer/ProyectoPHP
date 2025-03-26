<?php

class Pea {
    private $connect;
    private $table = "PEA";

    private $id_pea;
    private $nombre;
    private $descripcion;

    public function __construct($db) {
        $this->connect = $db;
    }

    // Obtener todos los registros
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

    // Obtener un registro por ID
    public function getById($id) {
        try {
            $query = "SELECT * FROM $this->table WHERE id_pea = :id_pea";
            $stmt = $this->connect->prepare($query);
            $stmt->bindParam(':id_pea', $id, PDO::PARAM_INT);
            if ($stmt->execute()) {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $error = $stmt->errorInfo();
                die("Error en la consulta de la DB: " . $error[2]);
            }
        } catch (PDOException $e) {
            echo "Error en la base de datos: " . $e->getMessage();
            error_log("Error al obtener PEA por ID: " . $e->getMessage());
            return false;
        }
    }

    // Crear un nuevo registro
    public function create($nombre, $descripcion) {
        try {
            $query = "INSERT INTO $this->table (nombre, descripcion) VALUES (:nombre, :descripcion)";
            $stmt = $this->connect->prepare($query);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':descripcion', $descripcion);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error en la base de datos: " . $e->getMessage();
            error_log("Error al registrar un PEA: " . $e->getMessage());
            return false;
        }
    }

    // Actualizar un registro
    public function update($id, $nombre, $descripcion) {
        try {
            $query = "UPDATE $this->table SET nombre = :nombre, descripcion = :descripcion WHERE id_pea = :id_pea";
            $stmt = $this->connect->prepare($query);
            $stmt->bindParam(':id_pea', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':descripcion', $descripcion);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error en la base de datos: " . $e->getMessage();
            error_log("Error al actualizar PEA: " . $e->getMessage());
            return false;
        }
    }

    // Eliminar un registro
    public function delete($id) {
        try {
            $query = "DELETE FROM $this->table WHERE id_pea = :id_pea";
            $stmt = $this->connect->prepare($query);
            $stmt->bindParam(':id_pea', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error en la base de datos: " . $e->getMessage();
            error_log("Error al eliminar PEA: " . $e->getMessage());
            return false;
        }
    }

    public function actualizarParcial($id, $data) {
        $query = "UPDATE PEA SET ";
        $fields = [];
    
        if (isset($data['nombre'])) {
            $fields[] = "nombre = :nombre";
        }
        if (isset($data['descripcion'])) {
            $fields[] = "descripcion = :descripcion";
        }
    
        if (empty($fields)) {
            return false;
        }
    
        $query .= implode(", ", $fields) . " WHERE id_pea = :id";
        $stmt = $this->connect->prepare($query);
        
        if (isset($data['nombre'])) $stmt->bindParam(':nombre', $data['nombre']);
        if (isset($data['descripcion'])) $stmt->bindParam(':descripcion', $data['descripcion']);
        
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
}
