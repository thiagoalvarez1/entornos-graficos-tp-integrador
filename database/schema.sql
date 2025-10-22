-- =====================================================
-- Esquema de base de datos: promociones_app (ejemplo)
-- Autor: Facundo Picia
-- Fecha: CURRENT_DATE
-- =====================================================

CREATE DATABASE IF NOT EXISTS shopping_promos;
USE shopping_promos;

-- -----------------------------------------------------
-- Tabla: usuarios
-- -----------------------------------------------------
CREATE TABLE usuarios (
    codUsuario INT(11) AUTO_INCREMENT PRIMARY KEY,
    nombreUsuario VARCHAR(100) NOT NULL,
    claveUsuario VARCHAR(255) NOT NULL,
    tipoUsuario VARCHAR(15) NOT NULL,
    categoriaCliente VARCHAR(10),
    estado ENUM('pendiente','activo','inactivo','no_verificado') DEFAULT 'pendiente',
    fechaRegistro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    verification_token VARCHAR(64),
    token_expires DATETIME
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------
-- Tabla: locales
-- -----------------------------------------------------
CREATE TABLE locales (
    codLocal INT(11) AUTO_INCREMENT PRIMARY KEY,
    nombreLocal VARCHAR(100) NOT NULL,
    ubicacionLocal VARCHAR(50),
    rubroLocal VARCHAR(20),
    codUsuario INT(11),
    estado ENUM('activo','inactivo') DEFAULT 'activo',
    fechaCreacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (codUsuario) REFERENCES usuarios(codUsuario)
        ON UPDATE CASCADE
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------
-- Tabla: novedades
-- -----------------------------------------------------
CREATE TABLE novedades (
    codNovedad INT(11) AUTO_INCREMENT PRIMARY KEY,
    textoNovedad VARCHAR(200) NOT NULL,
    fechaDesdeNovedad DATE,
    fechaHastaNovedad DATE,
    tipoUsuario VARCHAR(15),
    fechaCreacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------
-- Tabla: promociones
-- -----------------------------------------------------
CREATE TABLE promociones (
    codPromo INT(11) AUTO_INCREMENT PRIMARY KEY,
    textoPromo VARCHAR(200) NOT NULL,
    fechaDesdePromo DATE,
    fechaHastaPromo DATE,
    categoriaCliente VARCHAR(10),
    diasSemana VARCHAR(20),
    estadoPromo ENUM('pendiente','aprobada','denegada') DEFAULT 'pendiente',
    codLocal INT(11),
    fechaCreacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (codLocal) REFERENCES locales(codLocal)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------
-- Tabla: uso_promociones
-- -----------------------------------------------------
CREATE TABLE uso_promociones (
    codUso INT(11) AUTO_INCREMENT PRIMARY KEY,
    codCliente INT(11),
    codPromo INT(11),
    fechaUsoPromo TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('enviada','aceptada','rechazada') DEFAULT 'enviada',
    FOREIGN KEY (codCliente) REFERENCES usuarios(codUsuario)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    FOREIGN KEY (codPromo) REFERENCES promociones(codPromo)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
