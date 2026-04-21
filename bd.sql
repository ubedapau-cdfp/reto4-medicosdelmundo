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
    ('2','correo@holamail.com','$2a$12$V0Gat3GouS0lWD44Oovz6u3VY8UZbNA7Q7YY5DmU9nqlYzfmNhea.'/*hola*/,'Usuaria123','3'),
    ('3', 'megustanlaspatatas@bravas.com','$2a$12$VSDjDWfXVhtDd5r8ZPlOwuwcocY8xhTGZIPK8BXIXj.XqohEV9i8y'/*huicibravas*/,'Bravas','2'),
    ('4','estoymosqueado@copabacana.com','$2a$12$iT2882exM.y41zO0DFf.nekov3VBVSkmRGT2o8eZ71opfRPR2Rbsi'/*moscardo*/,'Orientadora','2');

-- ==========================================
-- 1. INSERTAR CATEGORÍAS (Apartados del PDF)
-- ==========================================
-- CATEGORÍAS MADRE
INSERT INTO CATEGORIA (id_categoria, titulo, descripcion, icono, id_madre) VALUES 
(1, 'Mis Derechos Iniciales', 'Todo sobre el inicio de tu relación laboral y requisitos.', 'icon-inicio', NULL),
(2, 'Protección y Cambios', 'Tus derechos ante cambios, pausas o el fin del contrato.', 'icon-escudo', NULL);

-- SUB-CATEGORÍAS MADRE 1 (Contratos y Jornada)
INSERT INTO CATEGORIA (id_categoria, titulo, descripcion, icono, id_madre) VALUES 
(11, 'Ser Trabajadora', 'Requisitos y condiciones para que exista un contrato.', 'icon-mujer', 1),
(12, 'Edad y Nacionalidad', 'Capacidad legal según tus circunstancias personales.', 'icon-id', 1),
(13, 'Tipos de Contrato', 'Detalles de contratos indefinidos, temporales y formativos.', 'icon-file', 1),
(14, 'Jornada y Descanso', 'Límites de horas, descansos y horarios especiales.', 'icon-clock', 1);

-- SUB-CATEGORÍAS MADRE 2 (Modificaciones y Extinción)
INSERT INTO CATEGORIA (id_categoria, titulo, descripcion, icono, id_madre) VALUES 
(21, 'Cambios de Condiciones', 'Derechos ante traslados o cambios de funciones.', 'icon-refresh', 2),
(22, 'Maternidad y Pausas', 'Bajas por nacimiento, excedencias y suspensiones.', 'icon-maternidad', 2),
(23, 'Despido y Cierre', 'Tipos de despido y cómo defender tus derechos.', 'icon-end', 2),
(24, 'Finiquito y Liquidación', 'Cálculo de las cantidades que te deben al marcharte.', 'icon-money', 2);
-- Contenido para "Ser Trabajadora"
INSERT INTO BLOQUE (titulo, subtitulo, contenido, orden, id_categoria) VALUES 
('La Relación Laboral', 'Los 5 requisitos clave', 'Para que seas considerada trabajadora y estés protegida por la ley, tu relación debe ser: 
1. Voluntaria: Tú eliges trabajar libremente. 
2. Personal: El trabajo lo realizas tú, nadie puede sustituirte. 
3. Por cuenta ajena: Los beneficios del trabajo son para la empresa. 
4. Dependiente: Trabajas bajo las órdenes de tu jefa. 
5. Retribución: Trabajas a cambio de un salario.', 1, 11),
('Leyes que te Protegen', 'Jerarquía normativa', 'Tus derechos están ordenados. Nada de lo que firmes puede ir en contra de esto:
- Constitución Española: El derecho al trabajo y no discriminación.
- Estatuto de los Trabajadores: La ley principal para todas.
- Convenio Colectivo: Las normas específicas de tu sector.
- Contrato de Trabajo: Tus condiciones particulares (siempre deben mejorar la ley).', 2, 11);

-- Contenido para "Edad y Nacionalidad"
INSERT INTO BLOQUE (titulo, subtitulo, contenido, orden, id_categoria) VALUES 
('Capacidad por Edad', 'Menores de edad', 'Si tienes entre 16 y 18 años, necesitas la autorización de tus tutoras. Tienes prohibido: realizar horas extras, trabajar de noche (22:00 a 06:00) y realizar tareas peligrosas. Tu descanso debe ser de 30 minutos si la jornada supera 4.5 horas.', 1, 12),
('Situación de Nacionalidad', 'Mujeres extranjeras', 'Si eres de fuera de la UE, necesitas la autorización de residencia y trabajo. La empresa tiene la obligación de solicitarla y darte de alta en Seguridad Social. Si no tienes permiso y trabajas, el contrato es nulo pero tienes derecho a reclamar tu sueldo por el tiempo trabajado.', 2, 12);

-- Contenido para "Tipos de Contrato"
INSERT INTO BLOQUE (titulo, subtitulo, contenido, orden, id_categoria) VALUES 
('Contratos Estables', 'Indefinidos y Fijos-Discontinuos', 'El contrato indefinido no tiene fecha de fin. El Fijo-Discontinuo es para trabajos que no son todo el año pero se repiten siempre (ej. campañas de recogida o comedores escolares). Tienes los mismos derechos que una trabajadora a tiempo completo.', 1, 13),
('Contratos de Duración', 'Temporales y Formativos', 'Solo se permiten por:
- Circunstancias de la producción: Máximo 6 meses (ampliable a 1 año) por exceso de trabajo.
- Sustitución: Para cubrir a una compañera (ej. por maternidad).
- Formación: Si no tienes título (Alternancia) o si acabas de obtenerlo (Práctica Profesional, duración de 6 meses a 1 año).', 2, 13);

-- Contenido para "Cambios de Condiciones"
INSERT INTO BLOQUE (titulo, subtitulo, contenido, orden, id_categoria) VALUES 
('Movilidad Geográfica', 'Traslados y Desplazamientos', 'Si la empresa quiere que trabajes en otra ciudad:
- Traslado: Es definitivo. Deben avisarte con 30 días. Puedes aceptar (te pagan mudanza), rescindir el contrato (20 días de indemnización/año) o reclamar al juez.
- Desplazamiento: Es temporal. Tienes derecho a 4 días de permiso pagado por cada 3 meses fuera.', 1, 21),
('Movilidad Funcional', 'Cambio de tareas', 'Si te piden hacer tareas de superior categoría, deben pagarte el sueldo de esa categoría. Si son tareas de inferior categoría, solo pueden obligarte por causas urgentes y deben mantenerte tu sueldo original.', 2, 21);

-- Contenido para "Maternidad y Pausas"
INSERT INTO BLOQUE (titulo, subtitulo, contenido, orden, id_categoria) VALUES 
('Permiso por Nacimiento', 'Tus 16 semanas', 'Tienes derecho a 16 semanas:
- 6 semanas obligatorias tras el parto.
- 10 semanas voluntarias que puedes usar hasta que el bebé cumpla 1 año.
Cobrarás el 100% de tu sueldo a través de la Seguridad Social.', 1, 22),
('Derecho a Excedencia', 'Cuidado de familiares', 'Puedes pedir excedencia para cuidar a tus hijos (hasta 3 años) o a un familiar que no pueda valerse por sí mismo (hasta 2 años). Durante el primer año tienes reserva de tu puesto de trabajo exacto.', 2, 22);

-- Contenido para "Despido y Cierre"
INSERT INTO BLOQUE (titulo, subtitulo, contenido, orden, id_categoria) VALUES 
('Protección ante el Despido', '¿Qué es un Despido Nulo?', 'Si te despiden por estar embarazada, por pedir la lactancia o por ser víctima de violencia de género, el despido es NULO. Esto significa que la empresa está obligada a readmitirte inmediatamente y pagarte los sueldos que no cobraste.', 1, 23),
('Tipos de Despido', 'Objetivo y Disciplinario', 'El despido Objetivo (causas económicas) da derecho a 20 días por año. El Disciplinario (por falta grave) no da indemnización. Si no estás de acuerdo, firma siempre como "NO CONFORME" para poder reclamar ante el juez.', 2, 23);

-- Contenido para "Finiquito y Liquidación"
INSERT INTO BLOQUE (titulo, subtitulo, contenido, orden, id_categoria) VALUES 
('Tu Liquidación Final', '¿Qué te deben pagar?', 'Al irte, te deben el finiquito que incluye:
1. Días trabajados del mes actual.
2. Vacaciones no disfrutadas.
3. Parte proporcional de las pagas extras.
Si se acaba un contrato temporal, también te corresponden 12 días de indemnización por año trabajado.', 1, 24);

INSERT INTO FAQ (pregunta, respuesta, id_categoria) VALUES 
('¿Me pueden despedir por estar embarazada?', 'No. Se considera despido nulo y la empresa deberá readmitirte de inmediato.', 23),
('¿Qué pasa si no tengo papeles y trabajo?', 'El contrato no es válido legalmente, pero tú tienes derecho a cobrar por todo el tiempo que hayas trabajado.', 12),
('¿Cuánto tiempo tengo para descansar entre jornadas?', 'Por ley, deben pasar al menos 12 horas desde que sales del trabajo hasta que vuelves a entrar.', 14),
('¿Puedo pedir una hora para cuidar a mi bebé?', 'Sí, es el permiso de lactancia. Tienes derecho a una hora de ausencia hasta que el bebé cumpla 9 meses.', 22);

-- CATEGORÍA MADRE 3 (Nueva)
INSERT INTO CATEGORIA (id_categoria, titulo, descripcion, icono, id_madre) VALUES 
(3, 'Mi Tiempo Laboral', 'Guía sobre tus horas de trabajo, descansos, vacaciones y conciliación.', 'icon-time', NULL);

-- SUB-CATEGORÍAS MADRE 3
INSERT INTO CATEGORIA (id_categoria, titulo, descripcion, icono, id_madre) VALUES 
(31, 'Jornada y Horarios', 'Límites legales y cómo se distribuyen tus horas.', 'icon-clock', 3),
(32, 'Descansos y Vacaciones', 'Tus periodos de desconexión y descanso anual.', 'icon-sun', 3),
(33, 'Horas y Turnos', 'Horas extras, trabajo nocturno y registro diario.', 'icon-night', 3),
(34, 'Permisos y Conciliación', 'Días libres pagados, reducción de jornada y teletrabajo.', 'icon-home', 3);

INSERT INTO BLOQUE (titulo, subtitulo, contenido, orden, id_categoria) VALUES 
('Tu Jornada Laboral', 'Límites y distribución', 'Como trabajadora, tu jornada es el tiempo que dedicas a tu actividad laboral.
- **Límite Semanal:** No puedes superar las 40 horas semanales de promedio en cómputo anual.
- **Límite Diario:** No puedes trabajar más de 9 horas al día (8 horas si eres menor de 18 años).
- **Distribución Irregular:** La empresa puede distribuir de forma desigual el 10% de tu jornada anual, pero debe avisarte con al menos 5 días de antelación.', 1, 31),
('El Horario de Trabajo', 'Tu organización diaria', 'El horario fija las horas exactas de entrada y salida. Puede ser:
- **Continuo:** Trabajas de tirón con un solo descanso corto.
- **Partido:** Tu jornada se divide en dos partes con una interrupción larga para comer.
- **Rígido o Flexible:** Dependiendo de si tienes libertad para elegir tus horas de entrada y salida respetando un tiempo común.', 2, 31);

INSERT INTO BLOQUE (titulo, subtitulo, contenido, orden, id_categoria) VALUES 
('Tus Descansos Obligatorios', 'Diarios y semanales', 'Para proteger tu salud, tienes derecho a:
- **Descanso Diario:** Al menos 12 horas entre que terminas una jornada y empiezas la siguiente.
- **Descanso en la Jornada:** Si trabajas más de 6 horas seguidas, tienes derecho a 15 minutos (20 minutos si eres menor de 18 tras 4,5 horas).
- **Descanso Semanal:** Un mínimo de un día y medio ininterrumpido (normalmente sábado tarde y domingo). Si eres menor, el descanso es de dos días completos.', 1, 32),
('Vacaciones y Festivos', 'Días de descanso pagado', 'Tienes derecho a un mínimo de **30 días naturales** de vacaciones por año trabajado.
- No pueden ser sustituidas por dinero (salvo que el contrato termine).
- Debes conocer las fechas al menos 2 meses antes de empezar.
- Si coinciden con una baja por embarazo o maternidad, tienes derecho a disfrutarlas en otra fecha aunque haya terminado el año natural.', 2, 32);

INSERT INTO BLOQUE (titulo, subtitulo, contenido, orden, id_categoria) VALUES 
('Horas Extraordinarias', 'Voluntariedad y límites', 'Son las horas que trabajas por encima de tu jornada ordinaria.
- Son voluntarias (salvo que se pacten por contrato) y tienen un límite de 80 horas al año.
- Se pagan más caras que la hora normal o se compensan con tiempo de descanso.
- Las trabajadoras menores y las nocturnas tienen prohibido realizarlas.', 1, 33),
('Trabajo Nocturno y Turnos', 'Condiciones especiales', 'Se considera **trabajo nocturno** el que realizas entre las 22:00 y las 06:00 horas. Tienes derecho a un plus de nocturnidad.
**Registro de Jornada:** Es obligatorio que la empresa registre cada día tu hora de entrada y salida. Tienes derecho a acceder a este registro para comprobar tus horas.', 2, 33);

INSERT INTO BLOQUE (titulo, subtitulo, contenido, orden, id_categoria) VALUES 
('Permisos Retribuidos', 'Días libres pagados', 'Puedes ausentarte del trabajo manteniendo tu sueldo en estos casos:
- **Matrimonio o Registro de Pareja:** 15 días.
- **Enfermedad grave o ingreso de familiares:** 5 días.
- **Fallecimiento de pariente:** 2 días.
- **Mudanza:** 1 día.
- **Exámenes o deberes públicos:** El tiempo indispensable (ej. para votar o ir al médico).', 1, 34),
('Teletrabajo y Desconexión', 'Trabajar desde casa', 'Si trabajas a distancia al menos el 30% de tu tiempo, tienes derecho a un contrato por escrito que incluya:
- Inventario de los equipos (ordenador, silla, etc.) que la empresa debe pagarte.
- Compensación por los gastos (luz, internet).
- **Desconexión Digital:** Tienes derecho a no contestar mensajes ni llamadas de trabajo fuera de tu horario laboral.', 2, 34);

INSERT INTO FAQ (pregunta, respuesta, id_categoria) VALUES 
('¿Puedo elegir yo las fechas de mis vacaciones?', 'Deben ser pactadas entre tú y la empresa. En caso de desacuerdo, un juez decidirá la fecha. Debes conocerlas con 2 meses de antelación.', 32),
('¿Me tienen que pagar las horas extras con dinero?', 'Pueden pagártelas con dinero (según convenio) o compensártelas con tiempo de descanso pagado en los 4 meses siguientes.', 33),
('¿Tengo derecho a ir al médico durante mi jornada?', 'Sí, es un permiso retribuido por el tiempo indispensable para asistir a visitas médicas o exámenes, siempre que lo justifiques adecuadamente.', 34),
('¿Qué pasa si trabajo de noche?', 'Tienes derecho a una retribución específica llamada "plus de nocturnidad" y tu jornada no puede exceder las 8 horas diarias de media.', 33);

INSERT INTO contenido (url_externas, id_bloque) VALUES 
('https://www.mites.gob.es/es/guia/texto/guia_6.htm', 15), -- Enlace sobre Jornada y Descansos
('https://www.boe.es/buscar/act.php?id=BOE-A-2021-11472', 18); -- Ley de Trabajo a Distancia (Teletrabajo)