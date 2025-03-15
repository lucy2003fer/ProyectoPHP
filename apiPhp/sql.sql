CREATE DATABASE IF NOT EXISTS agrosof;
USE agrosof;

CREATE TABLE Rol (
    id_rol INT AUTO_INCREMENT PRIMARY KEY,
    nombre_rol ENUM('aprendiz', 'pasante', 'instructor') NOT NULL,
    fecha_creacion DATE
);

CREATE TABLE usuarios (
    identificacion BIGINT PRIMARY KEY,
    nombre VARCHAR(50),
    contrasena VARCHAR(50),
    email VARCHAR(50),
    fk_id_rol INT,
    FOREIGN KEY (fk_id_rol) REFERENCES Rol(id_rol)
);

CREATE TABLE herramientas (
    id_herramienta INT AUTO_INCREMENT PRIMARY KEY,
    nombre_h VARCHAR(50),
    fecha_prestamo DATE,
    estado VARCHAR(50)
);

CREATE TABLE insumos (
    id_insumo INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50),
    tipo VARCHAR(50),
    precio_unidad INT,
    cantidad INT,
    unidad_medida VARCHAR(50)
);

CREATE TABLE actividad (
    id_actividad INT AUTO_INCREMENT PRIMARY KEY,
    nombre_actividad VARCHAR(50),
    descripcion TEXT
);

CREATE TABLE calendario_lunar (
    id_calendario_lunar INT AUTO_INCREMENT PRIMARY KEY,
    fecha DATE,
    descripcion_evento TEXT,
    evento VARCHAR(50)
);

CREATE TABLE asignacion_actividad (
    id_asignacion_actividad INT AUTO_INCREMENT PRIMARY KEY,
    fecha DATE,
    fk_id_actividad INT,
    fk_identificacion BIGINT,
    FOREIGN KEY (fk_id_actividad) REFERENCES actividad(id_actividad),
    FOREIGN KEY (fk_identificacion) REFERENCES usuarios(identificacion)
);

CREATE TABLE programacion (
    id_programacion INT AUTO_INCREMENT PRIMARY KEY,
    estado VARCHAR(50),
    fecha_programada DATE,
    duracion INT,
    fk_id_asignacion_actividad INT,
    fk_id_calendario_lunar INT,
    FOREIGN KEY (fk_id_asignacion_actividad) REFERENCES asignacion_actividad(id_asignacion_actividad),
    FOREIGN KEY (fk_id_calendario_lunar) REFERENCES calendario_lunar(id_calendario_lunar)
);

CREATE TABLE notificacion (
    id_notificacion INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(50),
    mensaje VARCHAR(50),
    fk_id_programacion INT,
    FOREIGN KEY (fk_id_programacion) REFERENCES programacion(id_programacion)
);
CREATE TABLE requiere (
    id_requiere INT AUTO_INCREMENT PRIMARY KEY,
    fk_id_herramienta INT,
    FOREIGN KEY (fk_id_herramienta) REFERENCES herramientas(id_herramienta),
    fk_id_asignacion_actividad INT,
    FOREIGN KEY (fk_id_asignacion_actividad) REFERENCES asignacion_actividad(id_asignacion_actividad)
);

CREATE TABLE utiliza (
    id_utiliza INT AUTO_INCREMENT PRIMARY KEY,
    fk_id_insumo INT,
    FOREIGN KEY (fk_id_insumo) REFERENCES insumos(id_insumo),
    fk_id_asignacion_actividad INT,
    FOREIGN KEY (fk_id_asignacion_actividad) REFERENCES asignacion_actividad(id_asignacion_actividad)
);

CREATE TABLE PEA (
    id_pea INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50),
    descripcion TEXT
);

CREATE TABLE ubicacion (
    id_ubicacion INT AUTO_INCREMENT PRIMARY KEY,
    latitud DECIMAL(9,6),
    longitud DECIMAL(9,6)
);

CREATE TABLE lote (
    id_lote INT AUTO_INCREMENT PRIMARY KEY,
    dimension INT,
    nombre_lote VARCHAR(50),
    fk_id_ubicacion INT,
    CONSTRAINT ubicacion_lote FOREIGN KEY (fk_id_ubicacion) REFERENCES ubicacion(id_ubicacion),
    estado VARCHAR(50)
);

CREATE TABLE eras (
    id_eras INT AUTO_INCREMENT PRIMARY KEY,
    descripcion VARCHAR(50),
    fk_id_lote INT,
    CONSTRAINT lote_era FOREIGN KEY (fk_id_lote) REFERENCES lote(id_lote)
);

CREATE TABLE tipo_cultivo (
    id_tipo_cultivo INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50),
    descripcion TEXT
);

CREATE TABLE especie (
    id_especie INT AUTO_INCREMENT PRIMARY KEY,
    nombre_comun VARCHAR(50),
    nombre_cientifico VARCHAR(50),
    descripcion TEXT,
    fk_id_tipo_cultivo INT,
    CONSTRAINT tipo_especie FOREIGN KEY (fk_id_tipo_cultivo) REFERENCES tipo_cultivo(id_tipo_cultivo)
);

CREATE TABLE semilleros (
    id_semillero INT AUTO_INCREMENT PRIMARY KEY,
    nombre_semilla VARCHAR(50),
    fecha_siembra DATE,
    fecha_estimada DATE,
    cantidad INT
);

CREATE TABLE cultivo (
    id_cultivo INT AUTO_INCREMENT PRIMARY KEY,
    fecha_plantacion DATE NOT NULL,
    nombre_cultivo VARCHAR(50),
    descripcion TEXT,
    fk_id_especie INT,
    CONSTRAINT especie_cultivo FOREIGN KEY (fk_id_especie) REFERENCES especie(id_especie),
    fk_id_semillero INT,
    CONSTRAINT semillero_cultivo FOREIGN KEY (fk_id_semillero) REFERENCES semilleros(id_semillero)
);

CREATE TABLE realiza (
    id_realiza INT AUTO_INCREMENT PRIMARY KEY,
    fk_id_cultivo INT,
    FOREIGN KEY (fk_id_cultivo) REFERENCES cultivo(id_cultivo),
    fk_id_actividad INT,
    CONSTRAINT actividad_realiza FOREIGN KEY (fk_id_actividad) REFERENCES actividad(id_actividad)
);

CREATE TABLE plantacion (
    id_plantacion INT AUTO_INCREMENT PRIMARY KEY,
    fk_id_cultivo INT,
    FOREIGN KEY (fk_id_cultivo) REFERENCES cultivo(id_cultivo),
    fk_id_era INT,
    CONSTRAINT era_plantacion FOREIGN KEY (fk_id_era) REFERENCES eras(id_eras)
);

CREATE TABLE desarrollan (
    id_desarrollan INT AUTO_INCREMENT PRIMARY KEY,
    fk_id_cultivo INT,
    FOREIGN KEY (fk_id_cultivo) REFERENCES cultivo(id_cultivo),
    fk_id_pea INT,
    CONSTRAINT pea_desarrollan FOREIGN KEY (fk_id_pea) REFERENCES PEA(id_pea)
);

CREATE TABLE produccion (
    id_produccion INT AUTO_INCREMENT PRIMARY KEY,
    fk_id_cultivo INT,
    CONSTRAINT fk_cultivo_prod FOREIGN KEY (fk_id_cultivo) REFERENCES cultivo(id_cultivo),
    cantidad_producida INT NOT NULL,
    fecha_produccion DATE NOT NULL,
    fk_id_lote INT,
    CONSTRAINT fk_lote_prod FOREIGN KEY (fk_id_lote) REFERENCES lote(id_lote),
    descripcion_produccion TEXT,
    estado ENUM('En proceso', 'Finalizado', 'Cancelado'),
    fecha_cosecha DATE
);

CREATE TABLE venta (
    id_venta INT AUTO_INCREMENT PRIMARY KEY,
    fk_id_produccion INT,
    CONSTRAINT fk_produccion_venta FOREIGN KEY (fk_id_produccion) REFERENCES produccion(id_produccion),
    cantidad INT NOT NULL,
    precio_unitario INT NOT NULL,
    total_venta INT,
    fecha_venta DATE NOT NULL
);

CREATE TABLE genera (
    id_genera INT AUTO_INCREMENT PRIMARY KEY,
    fk_id_cultivo INT,
    fk_id_produccion INT,
    CONSTRAINT cultivo_gen FOREIGN KEY (fk_id_cultivo) REFERENCES cultivo(id_cultivo),
    CONSTRAINT produ_gen FOREIGN KEY (fk_id_produccion) REFERENCES produccion(id_produccion)
);

CREATE TABLE sensores (
    id_sensor INT AUTO_INCREMENT PRIMARY KEY,
    nombre_sensor VARCHAR(50),
    tipo_sensor VARCHAR(50),
    unidad_medida VARCHAR(50),
    descripcion TEXT,
    medida_minima FLOAT,
    medida_maxima FLOAT
);

CREATE TABLE mide (
    id_mide INT AUTO_INCREMENT PRIMARY KEY,
    fk_id_sensor INT,
    FOREIGN KEY (fk_id_sensor) REFERENCES sensores(id_sensor),
    fk_id_era INT,
    CONSTRAINT era_mide FOREIGN KEY (fk_id_era) REFERENCES eras(id_eras)
);

CREATE TABLE tipo_residuos (
    id_tipo_residuo INT AUTO_INCREMENT PRIMARY KEY,
    nombre_residuo VARCHAR(50),
    descripcion TEXT
);

CREATE TABLE residuos (
    id_residuo INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50),
    fecha DATE,
    descripcion TEXT,
    fk_id_tipo_residuo INT,
    CONSTRAINT tipo_residuo_residuo FOREIGN KEY (fk_id_tipo_residuo) REFERENCES tipo_residuos(id_tipo_residuo),
    fk_id_cultivo INT,
    CONSTRAINT cultivo_residuo FOREIGN KEY (fk_id_cultivo) REFERENCES cultivo(id_cultivo)
);

CREATE TABLE control_fitosanitario (
    id_control_fitosanitario INT AUTO_INCREMENT PRIMARY KEY,
    fecha_control DATE,
    descripcion TEXT,
    fk_id_desarrollan INT,
    CONSTRAINT desarrollan_control_fitosanitario FOREIGN KEY (fk_id_desarrollan) REFERENCES desarrollan(id_desarrollan)
);

CREATE TABLE control_usa_insumo (
    id_control_usa_insumo INT AUTO_INCREMENT PRIMARY KEY,
    fk_id_insumo INT,
    FOREIGN KEY (fk_id_insumo) REFERENCES insumos(id_insumo),
    fk_id_control_fitosanitario INT,
    CONSTRAINT control_fitosanitario_usa_insumo FOREIGN KEY (fk_id_control_fitosanitario) REFERENCES control_fitosanitario(id_control_fitosanitario),
    cantidad INT
);





-- Inserción en la tabla Rol
INSERT INTO Rol (nombre_rol, fecha_creacion) VALUES
('aprendiz', '2024-01-01'),
('pasante', '2024-01-02'),
('instructor', '2024-01-03');

-- Inserción en la tabla usuarios
INSERT INTO usuarios (identificacion, nombre, contrasena, email, fk_id_rol) VALUES
(1001, 'Juan Perez', '123456', 'juan@example.com', 1),
(1002, 'Maria Lopez', 'abcdef', 'maria@example.com', 2),
(1003, 'Carlos Diaz', 'qwerty', 'carlos@example.com', 3);

-- Inserción en la tabla herramientas
INSERT INTO herramientas (nombre_h, fecha_prestamo, estado) VALUES
('Azadón', '2024-02-01', 'Disponible'),
('Pala', '2024-02-02', 'Prestado'),
('Rastrillo', '2024-02-03', 'Disponible');

-- Inserción en la tabla insumos
INSERT INTO insumos (nombre, tipo, precio_unidad, cantidad, unidad_medida) VALUES
('Fertilizante', 'Químico', 5000, 100, 'Kg'),
('Semillas de Maíz', 'Biológico', 3000, 50, 'Bolsa'),
('Insecticida', 'Químico', 7000, 30, 'Litro');

-- Inserción en la tabla actividad
INSERT INTO actividad (nombre_actividad, descripcion) VALUES
('Siembra de maíz', 'Plantación de maíz en el lote principal'),
('Riego de cultivos', 'Riego programado para mejorar la producción'),
('Aplicación de fertilizante', 'Uso de fertilizantes para mejorar el crecimiento');

-- Inserción en la tabla calendario_lunar
INSERT INTO calendario_lunar (fecha, descripcion_evento, evento) VALUES
('2024-02-15', 'Luna llena', 'Siembra'),
('2024-02-28', 'Luna menguante', 'Poda');

-- Inserción en la tabla asignacion_actividad
INSERT INTO asignacion_actividad (fecha, fk_id_actividad, fk_identificacion) VALUES
('2024-02-10', 1, 1001),
('2024-02-15', 2, 1002);

-- Inserción en la tabla programacion
INSERT INTO programacion (estado, fecha_programada, duracion, fk_id_asignacion_actividad, fk_id_calendario_lunar) VALUES
('Pendiente', '2024-02-20', 3, 1, 1),
('Completado', '2024-02-25', 2, 2, 2);

-- Inserción en la tabla notificacion
INSERT INTO notificacion (titulo, mensaje, fk_id_programacion) VALUES
('Recordatorio', 'Revisión de cultivos hoy', 1),
('Aviso', 'Poda programada para mañana', 2);

-- Inserción en la tabla requiere
INSERT INTO requiere (fk_id_herramienta, fk_id_asignacion_actividad) VALUES
(1, 1),
(2, 2);

-- Inserción en la tabla utiliza
INSERT INTO utiliza (fk_id_insumo, fk_id_asignacion_actividad) VALUES
(1, 1),
(2, 2);

-- Inserción en la tabla PEA
INSERT INTO PEA (nombre, descripcion) VALUES
('PEA 1', 'Programa de educación agrícola'),
('PEA 2', 'Formación en cultivos sustentables');

-- Inserción en la tabla ubicacion
INSERT INTO ubicacion (latitud, longitud) VALUES
(5.123456, -72.987654),
(4.567890, -73.543210);

-- Inserción en la tabla lote
INSERT INTO lote (dimension, nombre_lote, fk_id_ubicacion, estado) VALUES
(200, 'Lote A', 1, 'Disponible'),
(150, 'Lote B', 2, 'Ocupado');

-- Inserción en la tabla eras
INSERT INTO eras (descripcion, fk_id_lote) VALUES
('Era 1', 1),
('Era 2', 2);

-- Inserción en la tabla tipo_cultivo
INSERT INTO tipo_cultivo (nombre, descripcion) VALUES
('Cereal', 'Cultivos de cereales'),
('Hortaliza', 'Cultivo de hortalizas');

-- Inserción en la tabla especie
INSERT INTO especie (nombre_comun, nombre_cientifico, descripcion, fk_id_tipo_cultivo) VALUES
('Maíz', 'Zea mays', 'Cultivo de maíz', 1),
('Tomate', 'Solanum lycopersicum', 'Cultivo de tomate', 2);

-- Inserción en la tabla semilleros
INSERT INTO semilleros (nombre_semilla, fecha_siembra, fecha_estimada, cantidad) VALUES
('Semillas de maíz', '2024-02-05', '2024-05-10', 1000);

-- Inserción en la tabla cultivo
INSERT INTO cultivo (fecha_plantacion, nombre_cultivo, descripcion, fk_id_especie, fk_id_semillero) VALUES
('2024-02-10', 'Cultivo de maíz', 'Maíz amarillo', 1, 1);

-- Inserción en la tabla realiza
INSERT INTO realiza (fk_id_cultivo, fk_id_actividad) VALUES
(1, 1);

-- Inserción en la tabla plantacion
INSERT INTO plantacion (fk_id_cultivo, fk_id_era) VALUES
(1, 1);

-- Inserción en la tabla desarrollan
INSERT INTO desarrollan (fk_id_cultivo, fk_id_pea) VALUES
(1, 1);

-- Inserción en la tabla produccion
INSERT INTO produccion (fk_id_cultivo, cantidad_producida, fecha_produccion, fk_id_lote, descripcion_produccion, estado, fecha_cosecha) VALUES
(1, 500, '2024-06-15', 1, 'Buena producción', 'Finalizado', '2024-06-20');

-- Inserción en la tabla venta
INSERT INTO venta (fk_id_produccion, cantidad, precio_unitario, total_venta, fecha_venta) VALUES
(1, 500, 1000, 500000, '2024-07-01');

-- Inserción en la tabla sensores
INSERT INTO sensores (nombre_sensor, tipo_sensor, unidad_medida, descripcion, medida_minima, medida_maxima) VALUES
('Sensor de humedad', 'Humedad', '%', 'Mide la humedad del suelo', 20, 80);

-- Inserción en la tabla mide
INSERT INTO mide (fk_id_sensor, fk_id_era) VALUES
(1, 1);

-- Inserción en la tabla tipo_residuos
INSERT INTO tipo_residuos (nombre_residuo, descripcion) VALUES
('Orgánico', 'Residuos biodegradables');

-- Inserción en la tabla residuos
INSERT INTO residuos (nombre, fecha, descripcion, fk_id_tipo_residuo, fk_id_cultivo) VALUES
('Restos de cultivos', '2024-07-10', 'Hojas y tallos secos', 1, 1);

-- Inserción en la tabla control_fitosanitario
INSERT INTO control_fitosanitario (fecha_control, descripcion, fk_id_desarrollan) VALUES
('2024-07-15', 'Aplicación de fungicida', 1);

-- Inserción en la tabla control_usa_insumo
INSERT INTO control_usa_insumo (fk_id_insumo, fk_id_control_fitosanitario, cantidad) VALUES
(3, 1, 10);