<?php

class Programacion {
    private $connect;
    private $table = "programacion";

    public function __construct($db) {
        $this->connect = $db;
    }

    // Obtener todas las programaciones
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

    // Obtener una programación por ID
    public function getById($id) {
        $query = "SELECT * FROM $this->table WHERE id_programacion = :id_programacion";
        $stmt = $this->connect->prepare($query);
        $stmt->bindParam(':id_programacion', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    // Crear una nueva programación
    public function crearProgramacion($estado, $fecha_programada, $duracion, $fk_id_asignacion_actividad, $fk_id_calendario_lunar) {
        $query = "INSERT INTO $this->table (estado, fecha_programada, duracion, fk_id_asignacion_actividad, fk_id_calendario_lunar) 
                  VALUES (:estado, :fecha_programada, :duracion, :fk_id_asignacion_actividad, :fk_id_calendario_lunar)";
        $stmt = $this->connect->prepare($query);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':fecha_programada', $fecha_programada);
        $stmt->bindParam(':duracion', $duracion, PDO::PARAM_INT);
        $stmt->bindParam(':fk_id_asignacion_actividad', $fk_id_asignacion_actividad, PDO::PARAM_INT);
        $stmt->bindParam(':fk_id_calendario_lunar', $fk_id_calendario_lunar, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Actualizar una programación
    public function actualizarProgramacion($id, $estado, $fecha_programada, $duracion, $fk_id_asignacion_actividad, $fk_id_calendario_lunar) {
        $query = "UPDATE $this->table SET estado = :estado, fecha_programada = :fecha_programada, duracion = :duracion, 
                  fk_id_asignacion_actividad = :fk_id_asignacion_actividad, fk_id_calendario_lunar = :fk_id_calendario_lunar 
                  WHERE id_programacion = :id_programacion";
        $stmt = $this->connect->prepare($query);
        $stmt->bindParam(':id_programacion', $id, PDO::PARAM_INT);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':fecha_programada', $fecha_programada);
        $stmt->bindParam(':duracion', $duracion, PDO::PARAM_INT);
        $stmt->bindParam(':fk_id_asignacion_actividad', $fk_id_asignacion_actividad, PDO::PARAM_INT);
        $stmt->bindParam(':fk_id_calendario_lunar', $fk_id_calendario_lunar, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Eliminar una programación
    public function eliminarProgramacion($id) {
        $query = "DELETE FROM $this->table WHERE id_programacion = :id_programacion";
        $stmt = $this->connect->prepare($query);
        $stmt->bindParam(':id_programacion', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function actualizarParcial($id, $data) {
        $query = "UPDATE programacion SET ";
        $fields = [];
    
        if (isset($data['estado'])) {
            $fields[] = "estado = :estado";
        }
        if (isset($data['fecha_programada'])) {
            $fields[] = "fecha_programada = :fecha_programada";
        }
        if (isset($data['duracion'])) {
            $fields[] = "duracion = :duracion";
        }
        if (isset($data['fk_id_asignacion_actividad'])) {
            $fields[] = "fk_id_asignacion_actividad = :fk_id_asignacion_actividad";
        }
        if (isset($data['fk_id_calendario_lunar'])) {
            $fields[] = "fk_id_calendario_lunar = :fk_id_calendario_lunar";
        }
    
        if (empty($fields)) {
            return false;
        }
    
        $query .= implode(", ", $fields) . " WHERE id_programacion = :id";
        $stmt = $this->connect->prepare($query);
        
        if (isset($data['estado'])) $stmt->bindParam(':estado', $data['estado']);
        if (isset($data['fecha_programada'])) $stmt->bindParam(':fecha_programada', $data['fecha_programada']);
        if (isset($data['duracion'])) $stmt->bindParam(':duracion', $data['duracion'], PDO::PARAM_INT);
        if (isset($data['fk_id_asignacion_actividad'])) $stmt->bindParam(':fk_id_asignacion_actividad', $data['fk_id_asignacion_actividad'], PDO::PARAM_INT);
        if (isset($data['fk_id_calendario_lunar'])) $stmt->bindParam(':fk_id_calendario_lunar', $data['fk_id_calendario_lunar'], PDO::PARAM_INT);
        
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    
}
