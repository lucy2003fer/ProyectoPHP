<?php

class Plantacion {
    private $connect;
    private $table = "plantacion";

    private $id_plantacion;
    private $fk_id_cultivo;
    private $fk_id_era;

    public function __construct($db) {
        $this->connect = $db;
    }

    // Obtener todas las plantaciones
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

    // Obtener una plantación por ID
    public function getById($id) {
        try {
            $query = "SELECT * FROM $this->table WHERE id_plantacion = :id_plantacion";
            $stmt = $this->connect->prepare($query);
            $stmt->bindParam(':id_plantacion', $id, PDO::PARAM_INT);
            if ($stmt->execute()) {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $error = $stmt->errorInfo();
                die("Error en la consulta de la DB: " . $error[2]);
            }
        } catch (PDOException $e) {
            echo "Error en la base de datos: " . $e->getMessage();
            error_log("Error al obtener plantación por ID: " . $e->getMessage());
            return false;
        }
    }

    // Crear una nueva plantación
    public function crearPlantacion($fk_id_cultivo, $fk_id_era) {
        try {
            $query = "INSERT INTO $this->table (fk_id_cultivo, fk_id_era) VALUES (:fk_id_cultivo, :fk_id_era)";
            $stmt = $this->connect->prepare($query);
            $stmt->bindParam(':fk_id_cultivo', $fk_id_cultivo, PDO::PARAM_INT);
            $stmt->bindParam(':fk_id_era', $fk_id_era, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error en la base de datos: " . $e->getMessage();
            error_log("Error al registrar una plantación: " . $e->getMessage());
            return false;
        }
    }

    // Eliminar una plantación
    public function eliminarPlantacion($id) {
        try {
            $query = "DELETE FROM $this->table WHERE id_plantacion = :id_plantacion";
            $stmt = $this->connect->prepare($query);
            $stmt->bindParam(':id_plantacion', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error en la base de datos: " . $e->getMessage();
            error_log("Error al eliminar una plantación: " . $e->getMessage());
            return false;
        }
    }


    public function actualizarParcial($id, $data) {
        $query = "UPDATE plantacion SET ";
        $fields = [];
    
        if (isset($data['fk_id_cultivo'])) {
            $fields[] = "fk_id_cultivo = :fk_id_cultivo";
        }
        if (isset($data['fk_id_era'])) {
            $fields[] = "fk_id_era = :fk_id_era";
        }
    
        if (empty($fields)) {
            return false;
        }
    
        $query .= implode(", ", $fields) . " WHERE id_plantacion = :id";
        $stmt = $this->connect->prepare($query);
        
        if (isset($data['fk_id_cultivo'])) $stmt->bindParam(':fk_id_cultivo', $data['fk_id_cultivo'], PDO::PARAM_INT);
        if (isset($data['fk_id_era'])) $stmt->bindParam(':fk_id_era', $data['fk_id_era'], PDO::PARAM_INT);
        
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
}
