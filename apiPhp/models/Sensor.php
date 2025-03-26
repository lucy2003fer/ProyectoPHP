<?php

class Sensor {
    private $connect;
    private $table = "sensores";

    private $id_sensor;
    private $nombre_sensor;
    private $tipo_sensor;
    private $unidad_medida;
    private $descripcion;
    private $medida_minima;
    private $medida_maxima;

    public function __construct($db) {
        $this->connect = $db;
    }

    // Obtener todos los sensores
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

    // Obtener un sensor por ID
    public function getById($id) {
        try {
            $query = "SELECT * FROM $this->table WHERE id_sensor = :id_sensor";
            $stmt = $this->connect->prepare($query);
            $stmt->bindParam(':id_sensor', $id, PDO::PARAM_INT);
            if ($stmt->execute()) {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $error = $stmt->errorInfo();
                die("Error en la consulta de la DB: " . $error[2]);
            }
        } catch (PDOException $e) {
            echo "Error en la base de datos: " . $e->getMessage();
            error_log("Error al obtener sensor por ID: " . $e->getMessage());
            return false;
        }
    }

    // Crear un nuevo sensor
    public function crearSensor($nombre, $tipo, $unidad, $descripcion, $min, $max) {
        try {
            $query = "INSERT INTO $this->table (nombre_sensor, tipo_sensor, unidad_medida, descripcion, medida_minima, medida_maxima) 
                      VALUES (:nombre_sensor, :tipo_sensor, :unidad_medida, :descripcion, :medida_minima, :medida_maxima)";
            $stmt = $this->connect->prepare($query);
            $stmt->bindParam(':nombre_sensor', $nombre);
            $stmt->bindParam(':tipo_sensor', $tipo);
            $stmt->bindParam(':unidad_medida', $unidad);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':medida_minima', $min);
            $stmt->bindParam(':medida_maxima', $max);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error en la base de datos: " . $e->getMessage();
            error_log("Error al registrar un sensor: " . $e->getMessage());
            return false;
        }
    }

    // Actualizar un sensor
    public function actualizarSensor($id, $nombre, $tipo, $unidad, $descripcion, $min, $max) {
        try {
            $query = "UPDATE $this->table SET 
                        nombre_sensor = :nombre_sensor, 
                        tipo_sensor = :tipo_sensor, 
                        unidad_medida = :unidad_medida, 
                        descripcion = :descripcion, 
                        medida_minima = :medida_minima, 
                        medida_maxima = :medida_maxima 
                      WHERE id_sensor = :id_sensor";
            $stmt = $this->connect->prepare($query);
            $stmt->bindParam(':id_sensor', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nombre_sensor', $nombre);
            $stmt->bindParam(':tipo_sensor', $tipo);
            $stmt->bindParam(':unidad_medida', $unidad);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':medida_minima', $min);
            $stmt->bindParam(':medida_maxima', $max);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error en la base de datos: " . $e->getMessage();
            error_log("Error al actualizar un sensor: " . $e->getMessage());
            return false;
        }
    }

    // Eliminar un sensor
    public function eliminarSensor($id) {
        try {
            $query = "DELETE FROM $this->table WHERE id_sensor = :id_sensor";
            $stmt = $this->connect->prepare($query);
            $stmt->bindParam(':id_sensor', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error en la base de datos: " . $e->getMessage();
            error_log("Error al eliminar un sensor: " . $e->getMessage());
            return false;
        }
    }

    public function actualizarParcial($id, $data) {
        $query = "UPDATE sensores SET ";
        $fields = [];
    
        if (isset($data['nombre_sensor'])) {
            $fields[] = "nombre_sensor = :nombre_sensor";
        }
        if (isset($data['tipo_sensor'])) {
            $fields[] = "tipo_sensor = :tipo_sensor";
        }
        if (isset($data['unidad_medida'])) {
            $fields[] = "unidad_medida = :unidad_medida";
        }
        if (isset($data['descripcion'])) {
            $fields[] = "descripcion = :descripcion";
        }
        if (isset($data['medida_minima'])) {
            $fields[] = "medida_minima = :medida_minima";
        }
        if (isset($data['medida_maxima'])) {
            $fields[] = "medida_maxima = :medida_maxima";
        }
    
        if (empty($fields)) {
            return false;
        }
    
        $query .= implode(", ", $fields) . " WHERE id_sensor = :id";
        $stmt = $this->connect->prepare($query);
        
        if (isset($data['nombre_sensor'])) $stmt->bindParam(':nombre_sensor', $data['nombre_sensor'], PDO::PARAM_STR);
        if (isset($data['tipo_sensor'])) $stmt->bindParam(':tipo_sensor', $data['tipo_sensor'], PDO::PARAM_STR);
        if (isset($data['unidad_medida'])) $stmt->bindParam(':unidad_medida', $data['unidad_medida'], PDO::PARAM_STR);
        if (isset($data['descripcion'])) $stmt->bindParam(':descripcion', $data['descripcion'], PDO::PARAM_STR);
        if (isset($data['medida_minima'])) $stmt->bindParam(':medida_minima', $data['medida_minima'], PDO::PARAM_STR);
        if (isset($data['medida_maxima'])) $stmt->bindParam(':medida_maxima', $data['medida_maxima'], PDO::PARAM_STR);
        
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
}
