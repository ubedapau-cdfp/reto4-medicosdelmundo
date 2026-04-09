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
    password_hash VARCHAR(255) NOT NULL,
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


INSERT INTO ROL(id_rol, nombre_rol)
VALUES 
    ('1','Usuaria'),
    ('2','Orientadora'),
    ('3','Administradora');

INSERT INTO USUARIOS(id_usuario, email, password_hash, nombre, id_rol)
VALUES 
    ('1','correodeprueba@google.com','$2a$12$BOn7nGRmV/J0p6vpXzJOVOAlfYtfaLY2WBUgMIrLBKz5G.ouOYO3S'/*pass1234*/,'Prueba','3'), 
    ('2','correo@holamail.com','$2a$12$V0Gat3GouS0lWD44Oovz6u3VY8UZbNA7Q7YY5DmU9nqlYzfmNhea.'/*hola*/,'Usuaria123','3');

-- ==========================================
-- 1. INSERTAR CATEGORÍAS (Apartados del PDF)
-- ==========================================
-- Usamos id_categoria explícitos para asegurar la relación con los bloques.

INSERT INTO CATEGORIA (id_categoria, titulo, descripcion)
VALUES 
    (1, 'Relación Laboral', 'Definición, requisitos y principios generales del derecho laboral.'),
    (2, 'Proceso de Contratación', 'Obligaciones de la empresa y requisitos administrativos para contratar.'),
    (3, 'Elementos del Contrato', 'Elementos sustanciales (consentimiento, objeto, causa) y condiciones.'),
    (4, 'Tipología de Contratos', 'Diferentes tipos de contratos: indefinidos, temporales y formativos.'),
    (5, 'Jornada Laboral y Horarios', 'Tipos de jornada, descansos, horas extras y horarios especiales.'),
    (6, 'Extinción del Contrato', 'Causas de finalización del contrato y compensaciones.');

-- ==========================================
-- 2. INSERTAR BLOQUES (Contenido de cada sección)
-- ==========================================

-- --- Categoría 1: Relación Laboral ---
INSERT INTO BLOQUE (titulo, subtitulo, contenido, orden, id_categoria)
VALUES 
    ('Definición y Requisitos', '¿Cuándo existe un contrato?', 'Para que exista una relación laboral deben cumplirse 5 condiciones: Voluntaria, Personal, Por cuenta ajena, Dependiente y Remunerada.', 1, 1),
    ('Principios Generales', 'Normas mínimas', 'Incluye el Principio de Irrenunciabilidad de Derechos (no puedes renunciar a vacaciones) y el de Norma Mínima (el contrato solo puede mejorar la ley).', 2, 1);

-- --- Categoría 2: Proceso de Contratación ---
INSERT INTO BLOQUE (titulo, subtitulo, contenido, orden, id_categoria)
VALUES 
    ('Obligaciones de la Empresa', 'Seguridad Social y SEPE', 'La empresa debe tener cuenta de cotización, dar de alta a la trabajadora antes del inicio y registrar el contrato en el SEPE en 10 días.', 1, 2),
    ('Requisitos de la Trabajadora', 'Edad y Capacidad', 'Edad mínima de 16 años (con autorización hasta los 17). Plena capacidad a los 18 años.', 2, 2),
    ('Nacionalidad', 'UE y Extranjeros', 'Ciudadanos UE requieren DNI/Pasaporte y CUE si están más de 3 meses. No comunitarios necesitan permiso de trabajo y residencia previo.', 3, 2);

-- --- Categoría 3: Elementos del Contrato ---
INSERT INTO BLOQUE (titulo, subtitulo, contenido, orden, id_categoria)
VALUES 
    ('Elementos Sustanciales', 'Imprescindibles', 'Consentimiento (acuerdo), Objeto (materia del contrato) y Causa (intercambio de trabajo por salario). Si falta uno, el contrato es nulo.', 1, 3),
    ('Condiciones Importantes', 'No imprescindibles', 'Datos como el horario, salario detallado, duración, pluses y vacaciones deben constar, pero su ausencia no anula el contrato completo.', 2, 3);

-- --- Categoría 4: Tipología de Contratos ---
INSERT INTO BLOQUE (titulo, subtitulo, contenido, orden, id_categoria)
VALUES 
    ('Contrato Indefinido', 'Estabilidad laboral', 'No tienen fecha de fin. Pueden ser a jornada completa, parcial o fijos-discontinuos (trabajos intermitentes pero estables).', 1, 4),
    ('Contrato Temporal', 'Causas justificadas', 'Solo por circunstancias de producción (máximo 6-12 meses) o sustitución de trabajadores con reserva de puesto.', 2, 4),
    ('Contratos Formativos', 'Prácticas y Alternancia', 'Contrato para obtención de práctica profesional (máximo 1 año) y contrato de alternancia con estudios (máximo 2 años).', 3, 4);

-- --- Categoría 5: Jornada Laboral ---
INSERT INTO BLOQUE (titulo, subtitulo, contenido, orden, id_categoria)
VALUES 
    ('Tipos de Jornada', 'Distribución del tiempo', 'Existen jornadas completas, parciales (proporcionales en salario) y jornadas partidas (con interrupción para comer).', 1, 5),
    ('Horarios Especiales', 'Nocturnidad y Festivos', 'Nocturna (22:00 a 6:00) con plus específico. Festiva compensada con descanso o aumento salarial del 75%.', 2, 5);

-- --- Categoría 6: Extinción del Contrato ---
INSERT INTO BLOQUE (titulo, subtitulo, contenido, orden, id_categoria)
VALUES 
    ('Compensaciones', 'Indemnizaciones', 'Despido improcedente (33 días/año), despido objetivo (20 días/año) y fin de contrato temporal (12 días/año, salvo sustitución).', 1, 6);