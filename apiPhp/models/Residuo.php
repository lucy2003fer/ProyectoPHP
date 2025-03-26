<?php

class Residuo {
    private $connect;
    private $table = "residuos";

    private $id_residuo;
    private $nombre;
    private $fecha;
    private $descripcion;
    private $fk_id_tipo_residuo;
    private $fk_id_cultivo;

    public function __construct($db) {
        $this->connect = $db;
    }

    // Obtener todos los residuos
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

    // Obtener un residuo por ID
    public function getById($id) {
        try {
            $query = "SELECT * FROM $this->table WHERE id_residuo = :id_residuo";
            $stmt = $this->connect->prepare($query);
            $stmt->bindParam(':id_residuo', $id, PDO::PARAM_INT);
            if ($stmt->execute()) {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $error = $stmt->errorInfo();
                die("Error en la consulta de la DB: " . $error[2]);
            }
        } catch (PDOException $e) {
            echo "Error en la base de datos: " . $e->getMessage();
            error_log("Error al obtener residuo por ID: " . $e->getMessage());
            return false;
        }
    }

    // Crear un nuevo residuo
    public function crearResiduo($nombre, $fecha, $descripcion, $fk_id_tipo_residuo, $fk_id_cultivo) {
        try {
            $query = "INSERT INTO $this->table (nombre, fecha, descripcion, fk_id_tipo_residuo, fk_id_cultivo) VALUES (:nombre, :fecha, :descripcion, :fk_id_tipo_residuo, :fk_id_cultivo)";
            $stmt = $this->connect->prepare($query);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':fecha', $fecha);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':fk_id_tipo_residuo', $fk_id_tipo_residuo, PDO::PARAM_INT);
            $stmt->bindParam(':fk_id_cultivo', $fk_id_cultivo, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error en la base de datos: " . $e->getMessage();
            error_log("Error al registrar un residuo: " . $e->getMessage());
            return false;
        }
    }

    // Actualizar un residuo
    public function actualizarResiduo($id, $nombre, $fecha, $descripcion, $fk_id_tipo_residuo, $fk_id_cultivo) {
        try {
            $query = "UPDATE $this->table SET nombre = :nombre, fecha = :fecha, descripcion = :descripcion, fk_id_tipo_residuo = :fk_id_tipo_residuo, fk_id_cultivo = :fk_id_cultivo WHERE id_residuo = :id_residuo";
            $stmt = $this->connect->prepare($query);
            $stmt->bindParam(':id_residuo', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':fecha', $fecha);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':fk_id_tipo_residuo', $fk_id_tipo_residuo, PDO::PARAM_INT);
            $stmt->bindParam(':fk_id_cultivo', $fk_id_cultivo, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error en la base de datos: " . $e->getMessage();
            error_log("Error al actualizar un residuo: " . $e->getMessage());
            return false;
        }
    }

    // Eliminar un residuo
    public function eliminarResiduo($id) {
        try {
            $query = "DELETE FROM $this->table WHERE id_residuo = :id_residuo";
            $stmt = $this->connect->prepare($query);
            $stmt->bindParam(':id_residuo', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error en la base de datos: " . $e->getMessage();
            error_log("Error al eliminar un residuo: " . $e->getMessage());
            return false;
        }
    }

    public function actualizarParcial($id, $data) {
        $query = "UPDATE residuos SET ";
        $fields = [];
    
        if (isset($data['nombre'])) {
            $fields[] = "nombre = :nombre";
        }
        if (isset($data['fecha'])) {
            $fields[] = "fecha = :fecha";
        }
        if (isset($data['descripcion'])) {
            $fields[] = "descripcion = :descripcion";
        }
        if (isset($data['fk_id_tipo_residuo'])) {
            $fields[] = "fk_id_tipo_residuo = :fk_id_tipo_residuo";
        }
        if (isset($data['fk_id_cultivo'])) {
            $fields[] = "fk_id_cultivo = :fk_id_cultivo";
        }
    
        if (empty($fields)) {
            return false;
        }
    
        $query .= implode(", ", $fields) . " WHERE id_residuo = :id";
        $stmt = $this->connect->prepare($query);
        
        if (isset($data['nombre'])) $stmt->bindParam(':nombre', $data['nombre'], PDO::PARAM_STR);
        if (isset($data['fecha'])) $stmt->bindParam(':fecha', $data['fecha'], PDO::PARAM_STR);
        if (isset($data['descripcion'])) $stmt->bindParam(':descripcion', $data['descripcion'], PDO::PARAM_STR);
        if (isset($data['fk_id_tipo_residuo'])) $stmt->bindParam(':fk_id_tipo_residuo', $data['fk_id_tipo_residuo'], PDO::PARAM_INT);
        if (isset($data['fk_id_cultivo'])) $stmt->bindParam(':fk_id_cultivo', $data['fk_id_cultivo'], PDO::PARAM_INT);
    
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
}