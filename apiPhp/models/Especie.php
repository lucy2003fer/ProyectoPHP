<?php

class Especie {
    private $connect;
    private $table = "especie";

    private $id_especie;
    private $nombre_comun;
    private $nombre_cientifico;
    private $descripcion;
    private $fk_id_tipo_cultivo;

    public function __construct($db) {
        $this->connect = $db;
    }

    // Obtener todas las especies
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

    // Obtener una especie por ID
    public function getById($id) {
        try {
            $query = "SELECT * FROM $this->table WHERE id_especie = :id_especie";
            $stmt = $this->connect->prepare($query);
            $stmt->bindParam(':id_especie', $id, PDO::PARAM_INT);
            if ($stmt->execute()) {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $error = $stmt->errorInfo();
                die("Error en la consulta de la DB: " . $error[2]);
            }
        } catch (PDOException $e) {
            echo "Error en la base de datos: " . $e->getMessage();
            error_log("Error al obtener la especie por ID: " . $e->getMessage());
            return false;
        }
    }

    // Crear una nueva especie
    public function crearEspecie($nombre_comun, $nombre_cientifico, $descripcion, $fk_id_tipo_cultivo) {
        try {
            $query = "INSERT INTO $this->table (nombre_comun, nombre_cientifico, descripcion, fk_id_tipo_cultivo) 
                      VALUES (:nombre_comun, :nombre_cientifico, :descripcion, :fk_id_tipo_cultivo)";
            $stmt = $this->connect->prepare($query);
            $stmt->bindParam(':nombre_comun', $nombre_comun);
            $stmt->bindParam(':nombre_cientifico', $nombre_cientifico);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':fk_id_tipo_cultivo', $fk_id_tipo_cultivo, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error en la base de datos: " . $e->getMessage();
            error_log("Error al registrar una especie: " . $e->getMessage());
            return false;
        }
    }

    // Actualizar una especie
    public function actualizarEspecie($id, $nombre_comun, $nombre_cientifico, $descripcion, $fk_id_tipo_cultivo) {
        try {
            $query = "UPDATE $this->table 
                      SET nombre_comun = :nombre_comun, nombre_cientifico = :nombre_cientifico, 
                          descripcion = :descripcion, fk_id_tipo_cultivo = :fk_id_tipo_cultivo 
                      WHERE id_especie = :id_especie";
            $stmt = $this->connect->prepare($query);
            $stmt->bindParam(':id_especie', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nombre_comun', $nombre_comun);
            $stmt->bindParam(':nombre_cientifico', $nombre_cientifico);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':fk_id_tipo_cultivo', $fk_id_tipo_cultivo, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error en la base de datos: " . $e->getMessage();
            error_log("Error al actualizar una especie: " . $e->getMessage());
            return false;
        }
    }

    // Eliminar una especie
    public function eliminarEspecie($id) {
        try {
            $query = "DELETE FROM $this->table WHERE id_especie = :id_especie";
            $stmt = $this->connect->prepare($query);
            $stmt->bindParam(':id_especie', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error en la base de datos: " . $e->getMessage();
            error_log("Error al eliminar una especie: " . $e->getMessage());
            return false;
        }
    }

    public function actualizarParcial($id, $data) {
        $query = "UPDATE especie SET ";
        $fields = [];
    
        if (isset($data['nombre_comun'])) {
            $fields[] = "nombre_comun = :nombre_comun";
        }
        if (isset($data['nombre_cientifico'])) {
            $fields[] = "nombre_cientifico = :nombre_cientifico";
        }
        if (isset($data['descripcion'])) {
            $fields[] = "descripcion = :descripcion";
        }
        if (isset($data['fk_id_tipo_cultivo'])) {
            $fields[] = "fk_id_tipo_cultivo = :fk_id_tipo_cultivo";
        }
    
        if (empty($fields)) {
            return false;
        }
    
        $query .= implode(", ", $fields) . " WHERE id_especie = :id";
        $stmt = $this->connect->prepare($query);
        
        if (isset($data['nombre_comun'])) $stmt->bindParam(':nombre_comun', $data['nombre_comun']);
        if (isset($data['nombre_cientifico'])) $stmt->bindParam(':nombre_cientifico', $data['nombre_cientifico']);
        if (isset($data['descripcion'])) $stmt->bindParam(':descripcion', $data['descripcion']);
        if (isset($data['fk_id_tipo_cultivo'])) $stmt->bindParam(':fk_id_tipo_cultivo', $data['fk_id_tipo_cultivo'], PDO::PARAM_INT);
        
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
}
