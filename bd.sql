-- Opcional: Borrar tablas si existen (en orden inverso de dependencia)
DROP TABLE IF EXISTS contenido;
DROP TABLE IF EXISTS BLOQUE;
DROP TABLE IF EXISTS FAQ;
DROP TABLE IF EXISTS CATEGORIA;
DROP TABLE IF EXISTS USUARIOS;
DROP TABLE IF EXISTS ROL;

-- 1. Tabla de Roles
CREATE TABLE ROL (
    id_rol SERIAL PRIMARY KEY,
    nombre_rol VARCHAR(50) NOT NULL
);

-- 2. Tabla de Usuarios
CREATE TABLE USUARIOS (
    id_usuario SERIAL PRIMARY KEY,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nombre VARCHAR(100),
    id_rol INT REFERENCES ROL(id_rol)
);

-- 3. Tabla de Categoría
CREATE TABLE CATEGORIA (
    id_categoria SERIAL PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    descripcion TEXT,
    icono VARCHAR(255),
    id_madre INT REFERENCES CATEGORIA(id_categoria),
    fecha_actualizacion DATE DEFAULT CURRENT_DATE
);

-- 4. Tabla de FAQ (Depende de CATEGORIA)
CREATE TABLE FAQ (
    id_faq SERIAL PRIMARY KEY,
    pregunta TEXT NOT NULL,
    respuesta TEXT NOT NULL,
    fecha_actualizacion DATE DEFAULT CURRENT_DATE,
    id_categoria INT REFERENCES CATEGORIA(id_categoria)
);

-- 5. Tabla de Bloque (Depende de CATEGORIA)
CREATE TABLE BLOQUE (
    id_bloque SERIAL PRIMARY KEY,
    titulo VARCHAR(100),
    subtitulo VARCHAR(100),
    contenido TEXT,
    orden INT,
    fecha_actualizacion DATE DEFAULT CURRENT_DATE,
    id_categoria INT REFERENCES CATEGORIA(id_categoria)
);

-- 6. Tabla de Contenido (Depende de BLOQUE)
CREATE TABLE contenido (
    id_url SERIAL PRIMARY KEY,
    url_externas VARCHAR(255),
    id_bloque INT REFERENCES BLOQUE(id_bloque)
);

-- INSERTAR VALORES
-- 2. Insertar Categorías (Jerarquía del índice)
-- Nivel 1: Raíz
INSERT INTO CATEGORIA (id_categoria, titulo) 
VALUES (1, 'Contratos de trabajo');

-- Nivel 2: Hijos de 'Contratos de trabajo'
INSERT INTO CATEGORIA (id_categoria, titulo, id_madre) VALUES 
(2, 'Requisitos para la Relación Laboral', 1),
(3, 'Jerarquía normativa y derechos', 1),
(4, 'Contratar', 1),
(5, 'Ser contratada', 1),
(6, 'Indemnización', 1);

-- Nivel 3: Hijos de 'Ser contratada' (La rama que pediste)
INSERT INTO CATEGORIA (id_categoria, titulo, id_madre) VALUES 
(7, 'Edad', 5),
(8, 'Nacionalidad', 5),
(9, 'Relaciones Excluidas y Especiales', 5);

-- 3. Insertar Contenido en los Bloques (Ejemplos basados en tu texto)

-- Bloque para 'Edad'
INSERT INTO BLOQUE (titulo, contenido, id_categoria, orden) VALUES 
('Requisitos de Edad', 'La edad mínima legal para trabajar es 16 años. Entre los 16 y 17 años se requiere autorización de tutores legales.', 7, 1);

-- Bloque para 'Nacionalidad'
INSERT INTO BLOQUE (titulo, contenido, id_categoria, orden) VALUES 
('Ciudadanos UE', 'Documentación: Pasaporte o DNI en vigor. Registro obligatorio si la estancia supera los 3 meses.', 8, 1),
('Extranjeros NIE', 'Requieren Permiso de Trabajo solicitado en la Oficina de Extranjería.', 8, 2);

-- Bloque para 'Relaciones Excluidas'
INSERT INTO BLOQUE (titulo, contenido, id_categoria, orden) VALUES 
('Exclusiones', 'Quedan fuera los funcionarios públicos, autónomos y trabajos familiares hasta 2º grado.', 9, 1);