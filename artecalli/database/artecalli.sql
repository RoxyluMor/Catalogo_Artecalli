-- =============================================
-- ARTECALLI - Base de datos MySQL para XAMPP
-- =============================================

CREATE DATABASE IF NOT EXISTS artecalli_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE artecalli_db;

-- Tabla de usuarios (administradores)
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    rol VARCHAR(50) DEFAULT 'Administrador',
    estado TINYINT(1) DEFAULT 1,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabla de categorías
CREATE TABLE categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre_categoria VARCHAR(100) NOT NULL UNIQUE,
    descripcion TEXT,
    estado TINYINT(1) DEFAULT 1
) ENGINE=InnoDB;

-- Tabla de colores disponibles
CREATE TABLE colores (
    id_color INT AUTO_INCREMENT PRIMARY KEY,
    nombre_color VARCHAR(50) NOT NULL UNIQUE,
    codigo_hex VARCHAR(7) DEFAULT '#000000'
) ENGINE=InnoDB;

-- Tabla de tipos de material
CREATE TABLE tipos_material (
    id_tipo INT AUTO_INCREMENT PRIMARY KEY,
    nombre_tipo VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- Tabla de productos
CREATE TABLE productos (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    nombre_producto VARCHAR(100) NOT NULL UNIQUE,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0,
    imagen VARCHAR(255),
    id_categoria INT,
    id_color INT,
    id_tipo INT,
    estado TINYINT(1) DEFAULT 1,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria) ON DELETE SET NULL,
    FOREIGN KEY (id_color) REFERENCES colores(id_color) ON DELETE SET NULL,
    FOREIGN KEY (id_tipo) REFERENCES tipos_material(id_tipo) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Tabla de registro de accesos 
CREATE TABLE registro_accesos (
    id_registro INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    nombre_usuario VARCHAR(100),
    fecha_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
    exito TINYINT(1) DEFAULT 1,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE SET NULL
) ENGINE=InnoDB;

-- =============================================
-- DATOS INICIALES
-- =============================================

-- Colores predefinidos
INSERT INTO colores (nombre_color, codigo_hex) VALUES
('Blanco', '#FFFFFF'),
('Negro', '#000000'),
('Gris', '#808080'),
('Beige', '#F5F5DC'),
('Verde', '#228B22'),
('Rosa', '#FFC0CB'),
('Café', '#8B4513'),
('Dorado', '#FFD700'),
('Crema', '#FFFDD0'),
('Naranja', '#FFA500');

-- Tipos de material predefinidos
INSERT INTO tipos_material (nombre_tipo) VALUES
('Mármol'),
('Ónix'),
('Granito'),
('Travertino'),
('Cantera'),
('Obsidiana');

-- Categorías iniciales
INSERT INTO categorias (nombre_categoria, descripcion, estado) VALUES
('Mesas', 'Mesas de mármol y ónix para interiores y exteriores', 1),
('Esculturas', 'Esculturas decorativas talladas a mano', 1),
('Vajillas', 'Platos, bowls y utensilios de cocina', 1),
('Decoración', 'Artículos decorativos para el hogar', 1),
('Fuentes', 'Fuentes de agua decorativas', 1);

-- No se crea usuario por defecto
-- La primera vez que se ingresa al panel, se mostrará el formulario de registro
-- para que el administrador cree su propia cuenta
