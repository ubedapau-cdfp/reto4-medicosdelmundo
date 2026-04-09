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
-- 1. CATEGORÍA MADRE (La que engloba todo)
INSERT INTO CATEGORIA (id_categoria, titulo, descripcion, icono, id_madre) 
VALUES (1, 'Guía de Contratos y Relaciones Laborales', 'Toda la información sobre legislación, tipos de contrato y derechos del trabajador.', 'bi-briefcase', NULL);

-- 2. SUBCATEGORÍAS (Hijas de la ID 1)
INSERT INTO CATEGORIA (id_categoria, titulo, descripcion, icono, id_madre) VALUES 
(2, 'La Relación Laboral', 'Requisitos, principios y jerarquía normativa.', 'bi-info-circle', 1),
(3, 'Capacidad para Trabajar', 'Normativa sobre edad y trabajadores extranjeros.', 'bi-person-check', 1),
(4, 'Tipos de Contratos', 'Indefinitos, temporales y formativos.', 'bi-file-earmark-text', 1),
(5, 'Jornada y Horarios', 'Distribución de horas, nocturnidad y reducciones.', 'bi-clock', 1),
(6, 'Finalización y Casos Especiales', 'Indemnizaciones, finiquitos, ETT y TRADE.', 'bi-exclamation-triangle', 1);

-- Contenido para 'La Relación Laboral' (ID 2)
INSERT INTO BLOQUE (titulo, subtitulo, contenido, orden, id_categoria) VALUES 
('¿Qué es una relación laboral?', 'Las 5 condiciones simultáneas', 'Para que exista un contrato de trabajo deben cumplirse siempre: 1. Voluntariedad, 2. Carácter Personal, 3. Dependencia (órdenes del empresario), 4. Ajenidad (beneficios para la empresa) y 5. Retribución.', 1, 2),
('Jerarquía Normativa', '¿Qué ley se aplica primero?', 'El orden de aplicación es: 1. Normas de la UE, 2. La Constitución Española, 3. El Estatuto de los Trabajadores, 4. Convenios Colectivos y 5. El contrato individual.', 2, 2);

-- Contenido para 'Capacidad para Trabajar' (ID 3)
INSERT INTO BLOQUE (titulo, subtitulo, contenido, orden, id_categoria) VALUES 
('Trabajar siendo menor', 'Límites de edad', 'La edad mínima son 16 años (con autorización). Los menores de 18 no pueden realizar horas extra, trabajos nocturnos ni actividades peligrosas.', 1, 3),
('Nacionalidad', 'Comunitarios vs No Comunitarios', 'Ciudadanos UE: Libre circulación y trabajo. Ciudadanos no comunitarios: Requieren permiso de residencia y de trabajo previo.', 2, 3);

-- Contenido para 'Tipos de Contratos' (ID 4)
INSERT INTO BLOQUE (titulo, subtitulo, contenido, orden, id_categoria) VALUES 
('Elementos del Contrato', 'Sustanciales vs Importantes', 'Sustanciales (obligatorios): Consentimiento, Objeto y Causa. Si falta uno, el contrato es nulo. Importantes: Lugar, fecha y duración.', 1, 4),
('Contratos Formativos', 'Alternancia y Práctica', 'Alternancia: Combina formación y trabajo (máx 65% de jornada el primer año). Práctica Profesional: Para titulados, debe firmarse en los 3 años siguientes al título.', 2, 4);

-- Contenido para 'Jornada y Horarios' (ID 5)
INSERT INTO BLOQUE (titulo, subtitulo, contenido, orden, id_categoria) VALUES 
('Trabajo a Turnos y Nocturno', 'Organización 24/7', 'Se considera trabajo nocturno el realizado entre las 22:00 y las 06:00. El trabajo a turnos implica rotación y suele conllevar un plus salarial.', 1, 5),
('Reducción por Guarda Legal', 'Conciliación familiar', 'Derecho a reducir la jornada entre 1/8 y 1/2 por cuidado de menores de 12 años o personas con discapacidad.', 2, 5);

-- Contenido para 'Finalización' (ID 6)
INSERT INTO BLOQUE (titulo, subtitulo, contenido, orden, id_categoria) VALUES 
('Indemnizaciones', 'Cálculo por despido o fin', 'Contrato temporal: 12 días por año. Despido improcedente: 33 días por año (máximo 24 mensualidades).', 1, 6),
('Relaciones Especiales', 'ETT y TRADE', 'ETT: Contratación a través de empresas de trabajo temporal. TRADE: Trabajadores Autónomos Económicamente Dependientes (85% de ingresos de un solo cliente).', 2, 6);

INSERT INTO FAQ (pregunta, respuesta, id_categoria) VALUES 
('¿Si cuido a la hija de una vecina es relación laboral?', 'No, se considera un trabajo de "buena vecindad" o amistad y está excluido del Estatuto de los Trabajadores.', 2),
('¿Puede un menor de 16 años trabajar en una película?', 'Sí, es la única excepción: el trabajo en espectáculos públicos con autorización de la autoridad laboral.', 3),
('¿Qué pasa si mi contrato no tiene forma escrita?', 'Se presume que es indefinido y a jornada completa, salvo que la empresa demuestre lo contrario.', 4),
('¿Con cuánto tiempo debo avisar para una reducción de jornada?', 'Se debe solicitar por escrito a la empresa con al menos 15 días de antelación.', 5);