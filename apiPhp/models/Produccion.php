<?php

class Produccion {
    private $connect;
    private $table = "produccion";

    private $id_produccion;
    private $fk_id_cultivo;
    private $cantidad_producida;
    private $fecha_produccion;
    private $fk_id_lote;
    private $descripcion_produccion;
    private $estado;
    private $fecha_cosecha;

    public function __construct($db) {
        $this->connect = $db;
    }

    // Obtener todas las producciones
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

    // Obtener una producción por ID
    public function getById($id) {
        try {
            $query = "SELECT * FROM $this->table WHERE id_produccion = :id_produccion";
            $stmt = $this->connect->prepare($query);
            $stmt->bindParam(':id_produccion', $id, PDO::PARAM_INT);
            if ($stmt->execute()) {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $error = $stmt->errorInfo();
                die("Error en la consulta de la DB: " . $error[2]);
            }
        } catch (PDOException $e) {
            error_log("Error al obtener producción por ID: " . $e->getMessage());
            return false;
        }
    }

    // Crear una nueva producción
    public function crearProduccion($fk_id_cultivo, $cantidad_producida, $fecha_produccion, $fk_id_lote, $descripcion_produccion, $estado, $fecha_cosecha) {
        try {
            $query = "INSERT INTO $this->table (fk_id_cultivo, cantidad_producida, fecha_produccion, fk_id_lote, descripcion_produccion, estado, fecha_cosecha) 
                      VALUES (:fk_id_cultivo, :cantidad_producida, :fecha_produccion, :fk_id_lote, :descripcion_produccion, :estado, :fecha_cosecha)";
            $stmt = $this->connect->prepare($query);
            $stmt->bindParam(':fk_id_cultivo', $fk_id_cultivo, PDO::PARAM_INT);
            $stmt->bindParam(':cantidad_producida', $cantidad_producida, PDO::PARAM_INT);
            $stmt->bindParam(':fecha_produccion', $fecha_produccion);
            $stmt->bindParam(':fk_id_lote', $fk_id_lote, PDO::PARAM_INT);
            $stmt->bindParam(':descripcion_produccion', $descripcion_produccion);
            $stmt->bindParam(':estado', $estado);
            $stmt->bindParam(':fecha_cosecha', $fecha_cosecha);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al registrar producción: " . $e->getMessage());
            return false;
        }
    }

    // Actualizar una producción
    public function actualizarProduccion($id, $fk_id_cultivo, $cantidad_producida, $fecha_produccion, $fk_id_lote, $descripcion_produccion, $estado, $fecha_cosecha) {
        try {
            $query = "UPDATE $this->table 
                      SET fk_id_cultivo = :fk_id_cultivo, cantidad_producida = :cantidad_producida, fecha_produccion = :fecha_produccion,
                          fk_id_lote = :fk_id_lote, descripcion_produccion = :descripcion_produccion, estado = :estado, fecha_cosecha = :fecha_cosecha 
                      WHERE id_produccion = :id_produccion";
            $stmt = $this->connect->prepare($query);
            $stmt->bindParam(':id_produccion', $id, PDO::PARAM_INT);
            $stmt->bindParam(':fk_id_cultivo', $fk_id_cultivo, PDO::PARAM_INT);
            $stmt->bindParam(':cantidad_producida', $cantidad_producida, PDO::PARAM_INT);
            $stmt->bindParam(':fecha_produccion', $fecha_produccion);
            $stmt->bindParam(':fk_id_lote', $fk_id_lote, PDO::PARAM_INT);
            $stmt->bindParam(':descripcion_produccion', $descripcion_produccion);
            $stmt->bindParam(':estado', $estado);
            $stmt->bindParam(':fecha_cosecha', $fecha_cosecha);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al actualizar producción: " . $e->getMessage());
            return false;
        }
    }

    // Eliminar una producción
    public function eliminarProduccion($id) {
        try {
            $query = "DELETE FROM $this->table WHERE id_produccion = :id_produccion";
            $stmt = $this->connect->prepare($query);
            $stmt->bindParam(':id_produccion', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al eliminar producción: " . $e->getMessage());
            return false;
        }
    }

    public function actualizarParcial($id, $data) {
        $query = "UPDATE produccion SET ";
        $fields = [];
    
        if (isset($data['fk_id_cultivo'])) {
            $fields[] = "fk_id_cultivo = :fk_id_cultivo";
        }
        if (isset($data['cantidad_producida'])) {
            $fields[] = "cantidad_producida = :cantidad_producida";
        }
        if (isset($data['fecha_produccion'])) {
            $fields[] = "fecha_produccion = :fecha_produccion";
        }
        if (isset($data['fk_id_lote'])) {
            $fields[] = "fk_id_lote = :fk_id_lote";
        }
        if (isset($data['descripcion_produccion'])) {
            $fields[] = "descripcion_produccion = :descripcion_produccion";
        }
        if (isset($data['estado'])) {
            $fields[] = "estado = :estado";
        }
        if (isset($data['fecha_cosecha'])) {
            $fields[] = "fecha_cosecha = :fecha_cosecha";
        }
    
        if (empty($fields)) {
            return false;
        }
    
        $query .= implode(", ", $fields) . " WHERE id_produccion = :id";
        $stmt = $this->connect->prepare($query);
        
        if (isset($data['fk_id_cultivo'])) $stmt->bindParam(':fk_id_cultivo', $data['fk_id_cultivo'], PDO::PARAM_INT);
        if (isset($data['cantidad_producida'])) $stmt->bindParam(':cantidad_producida', $data['cantidad_producida'], PDO::PARAM_INT);
        if (isset($data['fecha_produccion'])) $stmt->bindParam(':fecha_produccion', $data['fecha_produccion'], PDO::PARAM_STR);
        if (isset($data['fk_id_lote'])) $stmt->bindParam(':fk_id_lote', $data['fk_id_lote'], PDO::PARAM_INT);
        if (isset($data['descripcion_produccion'])) $stmt->bindParam(':descripcion_produccion', $data['descripcion_produccion'], PDO::PARAM_STR);
        if (isset($data['estado'])) $stmt->bindParam(':estado', $data['estado'], PDO::PARAM_STR);
        if (isset($data['fecha_cosecha'])) $stmt->bindParam(':fecha_cosecha', $data['fecha_cosecha'], PDO::PARAM_STR);
        
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
}
