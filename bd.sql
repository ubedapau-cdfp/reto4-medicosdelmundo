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

-- ROLES
INSERT INTO ROL(id_rol, nombre_rol) VALUES 
    (1, 'Usuaria'),
    (2, 'Orientadora'),
    (3, 'Administradora');

-- USUARIOS
INSERT INTO USUARIOS(id_usuario, email, password_hash, nombre, id_rol) VALUES 
    (1, 'correodeprueba@google.com', '$2a$12$BOn7nGRmV/J0p6vpXzJOVOAlfYtfaLY2WBUgMIrLBKz5G.ouOYO3S', 'Prueba', 3), 
    (2, 'correo@holamail.com', '$2a$12$V0Gat3GouS0lWD44Oovz6u3VY8UZbNA7Q7YY5DmU9nqlYzfmNhea.', 'Usuaria123', 3),
    (3, 'megustanlaspatatas@bravas.com', '$2a$12$VSDjDWfXVhtDd5r8ZPlOwuwcocY8xhTGZIPK8BXIXj.XqohEV9i8y', 'Bravas', 2);

-- ==========================================
-- CATEGORÍAS MADRE
-- ==========================================
INSERT INTO CATEGORIA (id_categoria, titulo, descripcion, icono, id_madre) VALUES 
(1, 'Mis Derechos Iniciales', 'Todo sobre el inicio de tu relación laboral y requisitos.', 'fa-solid fa-scale-balanced', NULL),
(2, 'Protección y Cambios', 'Tus derechos ante cambios, pausas o el fin del contrato.', 'fa-solid fa-shield-halved', NULL),
(3, 'Mi Tiempo Laboral', 'Guía sobre tus horas de trabajo, descansos, vacaciones y conciliación.', 'fa-solid fa-clock', NULL);

-- SUB-CATEGORÍAS MADRE 1
INSERT INTO CATEGORIA (id_categoria, titulo, descripcion, icono, id_madre) VALUES 
(11, 'Ser Trabajadora', 'Requisitos y condiciones para que exista un contrato.', 'fa-solid fa-person', 1),
(12, 'Edad y Nacionalidad', 'Capacidad legal según tus circunstancias personales.', 'fa-solid fa-id-card', 1),
(13, 'Tipos de Contrato', 'Detalles de contratos indefinidos, temporales y formativos.', 'fa-solid fa-file-contract', 1),
(14, 'Jornada y Descanso', 'Límites de horas, descansos y horarios especiales.', 'fa-solid fa-clock', 1);

-- SUB-CATEGORÍAS MADRE 2
INSERT INTO CATEGORIA (id_categoria, titulo, descripcion, icono, id_madre) VALUES 
(21, 'Cambios de Condiciones', 'Derechos ante traslados o cambios de funciones.', 'fa-solid fa-arrows-rotate', 2),
(22, 'Maternidad y Pausas', 'Bajas por nacimiento, excedencias y suspensiones.', 'fa-solid fa-baby', 2),
(23, 'Despido y Cierre', 'Tipos de despido y cómo defender tus derechos.', 'fa-solid fa-door-open', 2),
(24, 'Finiquito y Liquidación', 'Cálculo de las cantidades que te deben al marcharte.', 'fa-solid fa-money-bill', 2);

-- SUB-CATEGORÍAS MADRE 3
INSERT INTO CATEGORIA (id_categoria, titulo, descripcion, icono, id_madre) VALUES 
(31, 'Jornada y Horarios', 'Límites legales y cómo se distribuyen tus horas.', 'fa-solid fa-calendar', 3),
(32, 'Descansos y Vacaciones', 'Tus periodos de desconexión y descanso anual.', 'fa-solid fa-sun', 3),
(33, 'Horas y Turnos', 'Horas extras, trabajo nocturno y registro diario.', 'fa-solid fa-moon', 3),
(34, 'Permisos y Conciliación', 'Días libres pagados, reducción de jornada y teletrabajo.', 'fa-solid fa-house', 3);

-- ==========================================
-- BLOQUES
-- ==========================================

-- Ser Trabajadora
INSERT INTO BLOQUE (titulo, subtitulo, contenido, orden, id_categoria) VALUES 
('La Relación Laboral', 'Requisitos y Tipos de Relación', 'Para que tu actividad sea considerada una relación laboral ordinaria y estés bajo el amparo del Estatuto de los Trabajadores, deben cumplirse cinco condiciones esenciales: 
1. Voluntariedad: Debes prestar tus servicios libremente, sin coacción. 
2. Carácter Personalísimo: El trabajo debe ser realizado por ti directamente, sin posibilidad de ser sustituida por otra persona. 
3. Ajeneidad: Los frutos de tu trabajo pertenecen a la empresa, no a ti, y es la empresa quien asume los riesgos económicos. 
4. Dependencia: Trabajas dentro del ámbito de organización y dirección de otra persona (física o jurídica). 
5. Retribución: Recibes un salario a cambio de tu tiempo y esfuerzo.

Existen relaciones especiales (como empleadas del hogar, deportistas o artistas) que tienen normas propias, y relaciones excluidas (como los funcionarios públicos o trabajos familiares) donde no se aplica el Estatuto de los Trabajadores.', 1, 11),

('Leyes que te Protegen', 'Jerarquía Normativa y Convenios', 'Tus derechos se rigen por un orden jerárquico que garantiza mínimos que nadie puede rebajar:
- Constitución Española: Protege derechos fundamentales como la huelga y la no discriminación.
- Estatuto de los Trabajadores: Es la ley base que establece los derechos mínimos para todas las trabajadoras en España.
- Convenios Colectivos: Son acuerdos entre representantes de trabajadores y empresas de un sector específico (ej. Consultoría, Hostelería). Estos convenios suelen mejorar los mínimos de la ley y establecen tu salario real según tu categoría.
- Contrato de Trabajo: Tu acuerdo particular con la empresa. Es vital recordar que un contrato nunca puede establecer condiciones peores que las del convenio o la ley. Si lo hiciera, esa cláusula sería nula.', 2, 11);

-- Edad y Nacionalidad
INSERT INTO BLOQUE (titulo, subtitulo, contenido, orden, id_categoria) VALUES 
('Capacidad por Edad', 'Protección a menores de 18 años', 'Si tienes entre 16 y 18 años (menor de edad), la ley te otorga una protección especial para garantizar tu salud y formación:
- Autorización: Necesitas el permiso de tus padres o tutores para firmar el contrato.
- Prohibiciones: Tienes prohibido realizar horas extraordinarias, trabajar en horario nocturno (de 22:00 a 06:00) y realizar actividades declaradas insalubres, penosas o peligrosas.
- Descansos: Si tu jornada diaria excede las 4,5 horas, tienes derecho a un descanso mínimo de 30 minutos. Tu descanso semanal debe ser de al menos dos días ininterrumpidos.', 1, 12),

('Situación de Nacionalidad', 'Derechos de mujeres extranjeras', 'Si no posees la nacionalidad de un país de la Unión Europea, necesitas una autorización previa de residencia y trabajo para que el contrato sea plenamente válido. La empresa tiene la obligación legal de solicitar tu alta en la Seguridad Social. 
Es fundamental saber que, si trabajas sin el permiso correspondiente, el contrato se considera nulo por falta de capacidad, pero mantienes el derecho a percibir el salario por el trabajo ya realizado y a las prestaciones derivadas de accidentes de trabajo que pudieras sufrir. La ley protege tu derecho al cobro independientemente de tu situación administrativa.', 2, 12);

-- Tipos de Contrato
INSERT INTO BLOQUE (titulo, subtitulo, contenido, orden, id_categoria) VALUES 
('Contratos Estables', 'Indefinidos y Fijos-Discontinuos 2025', 'La norma general es el contrato indefinido, que garantiza estabilidad sin fecha de finalización. 
- Fijo-Discontinuo: Se utiliza para trabajos que son estables pero intermitentes en el tiempo (como campañas agrícolas, comedores escolares o servicios de temporada). Aunque el contrato se "pausa" cuando no hay actividad, mantienes tu antigüedad y el derecho a ser llamada de nuevo cuando la actividad se reanude según el orden pactado. Tienes los mismos derechos de protección y seguridad social que una trabajadora a tiempo completo durante los periodos de actividad.', 1, 13),

('Contratos de Duración', 'Temporales y Formativos (Reforma 2025)', 'Los contratos temporales solo son legales bajo causas muy específicas:
1. Por circunstancias de la producción: Para incrementos ocasionales e imprevisibles de trabajo. Su duración máxima es de 6 meses (ampliable a 1 año por convenio).
2. Por sustitución: Para cubrir a una trabajadora con derecho a reserva de puesto (ej. baja por maternidad o excedencia).
3. Contratos Formativos: 
   - Formación en alternancia: Para compatibilizar trabajo y estudios (máximo 2 años).
   - Práctica Profesional: Para quienes ya tienen título, con una duración de entre 6 meses y 1 año, asegurando tareas acordes a tu nivel de estudios.', 2, 13);

-- Cambios de Condiciones
INSERT INTO BLOQUE (titulo, subtitulo, contenido, orden, id_categoria) VALUES 
('Movilidad Geográfica', 'Traslados vs Desplazamientos', 'La empresa puede cambiar tu lugar de trabajo si existen causas económicas o técnicas, pero con condiciones:
- Traslado: Implica cambio de residencia definitivo o superior a 12 meses en 3 años. Deben avisarte con 30 días de antelación. Puedes elegir: aceptar (con compensación de gastos de mudanza), impugnar ante el juez o extinguir el contrato recibiendo una indemnización de 20 días por año (máximo 12 mensualidades).
- Desplazamiento: Es temporal (menos de 12 meses en 3 años). Deben avisarte con 5 días de antelación. Tienes derecho a dietas, gastos de viaje y a un permiso de 4 días laborables en tu domicilio de origen por cada 3 meses de desplazamiento.', 1, 21),

('Modificación de Condiciones', 'Sustancial y Funcional', 'Si la empresa cambia tus funciones (Movilidad Funcional), debe respetar tu dignidad y titulación. Si te asignan tareas de categoría superior, tienes derecho al salario de esa categoría; si son inferiores, deben mantener tu salario original y solo puede ser por causas urgentes.
Para cambios en jornada, horario o salarios (Modificación Sustancial), la empresa debe demostrar causas justificadas y notificarte con 15 días de antelación. Si el cambio te perjudica, puedes rescindir el contrato con una indemnización de 20 días por año trabajado.', 2, 21);

-- Maternidad y Pausas
INSERT INTO BLOQUE (titulo, subtitulo, contenido, orden, id_categoria) VALUES 
('Suspensión por Nacimiento', 'Novedades 2025: 17 semanas', 'El derecho a la suspensión del contrato por nacimiento y cuidado del menor se ha actualizado en 2025, extendiéndose a un total de 17 semanas de permiso:
- 6 semanas obligatorias: Deben disfrutarse inmediatamente después del parto, a jornada completa, para asegurar la salud de la madre.
- 11 semanas voluntarias: Se pueden disfrutar de forma seguida o interrumpida durante los primeros 12 meses de vida del bebé.
Durante este periodo, la Seguridad Social te abona el 100% de tu base reguladora, y la empresa no puede despedirte, ya que se consideraría nulo por discriminación.', 1, 22),

('Excedencias y Cuidados', 'Conciliación de la Vida Familiar', 'Tienes derecho a pausar tu contrato mediante excedencias:
- Por cuidado de hijos: Hasta que el menor cumpla 3 años. Durante el primer año tienes derecho a la reserva de tu puesto de trabajo específico; después, la reserva es para un puesto del mismo grupo profesional.
- Por cuidado de familiares: Hasta 2 años para cuidar a parientes de hasta 2º grado que no puedan valerse por sí mismos.
En ambos casos, el periodo computa a efectos de antigüedad en la empresa y tienes derecho a asistir a cursos de formación profesional a los que te convoque la empresa.', 2, 22);

-- Despido y Cierre
INSERT INTO BLOQUE (titulo, subtitulo, contenido, orden, id_categoria) VALUES 
('Protección ante el Despido', 'El Despido Nulo y sus causas', 'Un despido se declara NULO cuando tiene como móvil alguna causa de discriminación prohibida o viola derechos fundamentales. Casos automáticos de nulidad incluyen:
- Embarazo, maternidad o lactancia.
- Ser víctima de violencia de género que ejerce sus derechos de movilidad o reducción.
- Solicitar o estar disfrutando de permisos de conciliación.
La consecuencia del despido nulo es la readmisión inmediata de la trabajadora en su puesto de trabajo y el abono de los "salarios de tramitación" (los sueldos que dejaste de cobrar desde el despido hasta la sentencia).', 1, 23),

('Procedimiento de Despido', 'Objetivo y Disciplinario', 'Existen dos formas principales de despido unilateral por la empresa:
- Despido Objetivo: Por causas económicas, técnicas o de producción. La empresa debe darte 15 días de preaviso y pagarte una indemnización de 20 días por año (máximo 12 mensualidades).
- Despido Disciplinario: Por incumplimientos graves tuyos (faltas, desobediencia). No hay preaviso ni indemnización.
Consejo Vital: Si no estás de acuerdo, firma la carta de despido y el finiquito escribiendo "NO CONFORME" junto a tu firma; esto es esencial para poder reclamar después ante el Juzgado de lo Social en un plazo de 20 días.', 2, 23);

-- Finiquito y Liquidación
INSERT INTO BLOQUE (titulo, subtitulo, contenido, orden, id_categoria) VALUES 
('Liquidación Final', 'Cálculo del Finiquito y Liquidación', 'Al finalizar tu contrato, la empresa debe entregarte el documento de liquidación o finiquito. Este debe desglosar obligatoriamente:
1. Salarios devengados: Los días trabajados del último mes que aún no has cobrado.
2. Vacaciones no disfrutadas: Deben pagarte en dinero los días de vacaciones que has generado pero no has gastado (aproximadamente 2.5 días por mes trabajado).
3. Pagas Extraordinarias: La parte proporcional de las pagas de verano y Navidad si no las tienes prorrateadas en tu sueldo mensual.
Si tu contrato era temporal, además te corresponde una indemnización de 12 días de salario por cada año de servicio prestado.', 1, 24);

-- Jornada y Horarios
INSERT INTO BLOQUE (titulo, subtitulo, contenido, orden, id_categoria) VALUES 
('Jornada Laboral', 'Límites legales y registro obligatorio', 'Tu jornada es el tiempo efectivo en el que estás a disposición de la empresa. Los límites son claros para evitar abusos:
- Máximo semanal: 40 horas de promedio anual.
- Máximo diario: 9 horas de trabajo efectivo (8 horas para menores). Entre jornadas debe haber un descanso mínimo de 12 horas.
- Registro de Jornada: La empresa está obligada por ley a registrar diariamente tu hora exacta de entrada y salida. Este registro debe estar a tu disposición y es tu mejor prueba en caso de que realices horas extras que no te quieran pagar.', 1, 31),

('Organización del Horario', 'Distribución Irregular y Turnos', 'La empresa puede decidir que trabajes más unas semanas que otras (Distribución Irregular) afectando hasta al 10% de tu jornada anual, pero debe avisarte con al menos 5 días de antelación.
En trabajos a turnos (mañana/tarde/noche), ninguna trabajadora puede estar en el turno de noche más de dos semanas consecutivas, salvo que lo pida voluntariamente. Si tu jornada es partida, el tiempo de descanso intermedio (comida) no computa como trabajo a menos que el convenio diga lo contrario.', 2, 31);

-- Descansos y Vacaciones
INSERT INTO BLOQUE (titulo, subtitulo, contenido, orden, id_categoria) VALUES 
('Descansos Obligatorios', 'Diarios, Semanales y en la Jornada', 'El descanso es un derecho irrenunciable para garantizar la seguridad laboral:
- Durante la jornada: Si trabajas más de 6 horas seguidas, tienes derecho a 15 minutos de descanso (20 minutos si eres menor tras 4.5 horas). Este tiempo solo se paga si así lo establece tu convenio.
- Semanal: Tienes derecho a un descanso mínimo de un día y medio ininterrumpido (36 horas), que normalmente comprende la tarde del sábado y el domingo completo. Para menores, el descanso es de 2 días completos.', 1, 32),

('Vacaciones Anuales', 'Derechos y Planificación', 'Tienes derecho a un mínimo de 30 días naturales de vacaciones pagadas al año. 
- Acuerdo: Las fechas deben pactarse de mutuo acuerdo. La empresa no puede imponerlas unilateralmente ni tú cogerlas sin permiso. Debes conocer el calendario oficial con 2 meses de antelación.
- Situaciones Especiales: Si durante tus vacaciones sufres una Incapacidad Temporal (baja médica) o coinciden con el permiso de nacimiento/lactancia, tienes derecho a disfrutarlas en una fecha distinta, incluso aunque haya terminado el año natural.', 2, 32);

-- Horas y Turnos
INSERT INTO BLOQUE (titulo, subtitulo, contenido, orden, id_categoria) VALUES 
('Horas Extraordinarias', 'Límites y Compensación', 'Las horas extra son aquellas que exceden tu jornada ordinaria:
- Voluntariedad: Salvo que se firmen en contrato o sean por fuerza mayor (reparar daños urgentes), son totalmente voluntarias.
- Límite: No puedes hacer más de 80 horas extras al año (las compensadas con descanso no cuentan para este límite).
- Pago: Deben pagarse con un importe superior a la hora normal o compensarse con tiempos de descanso pagado dentro de los 4 meses siguientes a su realización.', 1, 33),

('Nocturnidad y Plus', 'Trabajo entre las 22h y las 06h', 'Se considera trabajadora nocturna a quien realiza al menos 3 horas de su jornada diaria en horario de noche (22:00 a 06:00). 
- Salud: Estas trabajadoras tienen derecho a una evaluación gratuita de salud periódica.
- Salario: Tienes derecho a percibir el "Plus de Nocturnidad" definido en tu convenio, a menos que el salario se haya establecido ya considerando que el trabajo es nocturno por su propia naturaleza. Los menores tienen prohibido este tipo de horario bajo cualquier circunstancia.', 2, 33);

-- Permisos y Conciliación
INSERT INTO BLOQUE (titulo, subtitulo, contenido, orden, id_categoria) VALUES 
('Permisos Retribuidos', 'Ausencias con derecho a sueldo', 'Puedes faltar al trabajo avisando previamente y justificando la causa, manteniendo tu salario íntegro:
- Matrimonio/Pareja de hecho: 15 días naturales.
- Accidente o enfermedad grave, hospitalización o intervención quirúrgica de familiares (hasta 2º grado): 5 días laborables.
- Fallecimiento de pariente (hasta 2º grado): 2 días (4 si requiere desplazamiento).
- Mudanza: 1 día.
- Deber inexcusable: El tiempo necesario para votar, juicios o visitas médicas indispensables.', 1, 34),

('Trabajo a Distancia', 'Teletrabajo y Desconexión Digital', 'Si teletrabajas más del 30% de tu jornada (ej. 2 días a la semana), tienes derecho a firmar un Acuerdo de Teletrabajo por escrito. 
- Gastos: La empresa debe proporcionarte y mantener los equipos (ordenador, silla ergonómica) y compensar los gastos de suministros (luz, internet).
- Desconexión Digital: Tienes derecho legal a no responder emails, WhatsApps o llamadas de trabajo fuera de tu horario laboral. La empresa no puede sancionarte por ejercer este derecho de desconexión.', 2, 34);

-- ==========================================
-- FAQs (Respuestas detalladas)
-- ==========================================
INSERT INTO FAQ (pregunta, respuesta, id_categoria) VALUES 
('¿Me pueden despedir por estar embarazada?', 'Absolutamente no. Según el artículo 55 del Estatuto de los Trabajadores, el despido de una trabajadora embarazada (o que ha solicitado permisos de lactancia o nacimiento) se considera automáticamente NULO. Esto significa que la empresa está obligada legalmente a readmitirte en tu mismo puesto y a pagarte todos los salarios que dejaste de percibir desde el día del despido hasta que vuelvas a tu puesto.', 23),

('¿Qué pasa si trabajo sin contrato o "sin papeles"?', 'Aunque no tengas un contrato escrito o carezcas de permiso de residencia, la ley protege tu derecho al trabajo realizado. Si puedes demostrar que trabajas para la empresa (mediante mensajes, testigos o fotos), se presume que existe un contrato indefinido a jornada completa. Tienes derecho a reclamar judicialmente todos tus salarios, vacaciones y finiquito. La falta de permiso administrativo del trabajador no invalida su derecho a cobrar por el esfuerzo ya prestado.', 12),

('¿Es obligatorio el preaviso para irme de la empresa?', 'Sí, si decides dimitir voluntariamente (Baja Voluntaria), debes dar el preaviso que indique tu convenio colectivo (normalmente 15 días). Si no lo haces, la empresa tiene derecho a descontarte del finiquito un día de salario por cada día de preaviso no cumplido. No necesitas dar preaviso si te vas por un incumplimiento grave de la empresa (ej. si no te pagan el sueldo durante meses).', 24),

('¿Cómo se calcula mi indemnización por despido?', 'Depende del tipo de despido declarado:
- Despido Objetivo (Causas económicas): 20 días de salario por año trabajado (máximo 12 mensualidades).
- Despido Improcedente (Sin causa real): 33 días de salario por año trabajado (máximo 24 mensualidades).
Para el cálculo se usa tu salario regulador (salario bruto anual con pagas extras dividido entre 365 días) y tu antigüedad total en la empresa.', 23),

('¿Puedo pedir la reducción de jornada para cuidar a mi hijo/a?', 'Sí, tienes derecho a la "Reducción de Jornada por Guarda Legal" hasta que el menor cumpla 12 años. Puedes reducir entre un octavo (1/8) y la mitad (1/2) de tu jornada, con una reducción proporcional del salario. Lo más importante es que tú tienes el derecho a elegir el horario que mejor te convenga dentro de tu jornada ordinaria, y la empresa solo puede negarse por razones organizativas muy graves justificadas por escrito.', 34),

('¿Tengo derecho a días libres si mi pareja se pone enferma?', 'Sí. Tras la reforma legal, dispones de un permiso retribuido de 5 días laborables al año para el cuidado de familiares (incluyendo cónyuge o pareja de hecho) en caso de accidente, enfermedad grave u hospitalización que requiera reposo domiciliario. Este permiso es pagado al 100% por la empresa y no tienes que recuperar esas horas después.', 34);

-- ==========================================
-- CONTENIDO (URLs externas)
-- ==========================================
-- Enlaces para la Categoría Madre (ID 1) o Bloques generales
INSERT INTO contenido (url_externas, id_bloque) VALUES 
('https://www.boe.es/buscar/act.php?id=BOE-A-2015-11430', 1), -- Enlace al Estatuto de los Trabajadores (BOE)
('https://revista.seg-social.es/-/c%C3%B3mo-obtener-el-informe-de-vida-laboral', 2); -- Informe de Vida Laboral

-- Enlaces para Despidos y Paro (Subcategoría 9 / Bloques de Extinción)
 -- SEPE: Información sobre el Pago Único (Capitalización)

-- Enlaces para Maternidad/Paternidad (Subcategoría 8 / Bloques de Suspensión)
 -- Seguridad Social: Nacimiento y cuidado de menor

-- Enlaces para Conciliación y SAMA (Subcategoría 9 / Bloques de Finalización)
INSERT INTO contenido (url_externas, id_bloque) VALUES 
('https://digital.gob.es/content/dam/sgad/sefp/es/portalsefp/funcion-publica/dialogo-social/conciliacion/Guia_conciliacion.pdf', 9); -- Guía Laboral del Ministerio: Despidos y Conciliación

-- Imágenes para Categoría Principal 1 (Mis Derechos Iniciales)
-- Crear un bloque con la imagen de portada
INSERT INTO BLOQUE (titulo, subtitulo, contenido, orden, id_categoria) VALUES 
('Mis Derechos Iniciales', 'Bienvenida', 'Imagen de portada de la categoría principal.', 0, 1);

-- ==========================================
-- NUEVA CATEGORÍA MADRE: MI SALARIO
-- ==========================================
INSERT INTO CATEGORIA (id_categoria, titulo, descripcion, icono, id_madre) VALUES 
(4, 'Mi Salario y mi Nómina', 'Todo lo que necesitas saber para entender tu sueldo, tus derechos y tu recibo de salario.', 'fa-solid fa-money-check-dollar', NULL);

-- SUB-CATEGORÍAS DE SALARIO
INSERT INTO CATEGORIA (id_categoria, titulo, descripcion, icono, id_madre) VALUES 
(41, 'Conceptos y Cuantía', 'Qué es el salario, el SMI y los tipos de retribución.', 'fa-solid fa-coins', 4),
(42, 'La Estructura de la Nómina', 'Cómo leer tu recibo: devengos, deducciones y bases.', 'fa-solid fa-file-invoice-dollar', 4),
(43, 'Garantías y Protección', 'Derechos ante impagos, el FOGASA y anticipos.', 'fa-solid fa-hand-holding-dollar', 4);

-- ==========================================
-- BLOQUES DE CONTENIDO (Enriquecidos)
-- ==========================================

-- Conceptos y Cuantía (ID 41)
INSERT INTO BLOQUE (titulo, subtitulo, contenido, orden, id_categoria) VALUES 
('¿Qué es el Salario?', 'Definición y Límites Legales', 'El salario es la totalidad de las percepciones económicas que recibes por la prestación profesional de tus servicios. 
- Salario por Convenio: Normalmente, tu sueldo viene fijado en el Convenio Colectivo de tu sector según tu categoría profesional. 
- El SMI (Sueldo Mínimo Interprofesional): Es la cuantía mínima legal que cualquier trabajadora debe cobrar. Según los datos actuales, el SMI se sitúa en 1.221€/mes en 14 pagas (o 17.094€ brutos anuales). 
- Carácter Inembargable: La ley protege tu salario mínimo; la parte del sueldo que no exceda el SMI no puede ser embargada por deudas, asegurando tu subsistencia básica.', 1, 41),

('Tipos de Salario', 'Dinero vs Especie', 'Tu remuneración puede llegar de dos formas distintas que deben estar claramente reflejadas:
1. Salario en Dinero: Es la forma común, pagada por transferencia o, excepcionalmente, en efectivo (máximo 2.500€). En ningún caso la parte en dinero puede ser inferior al SMI.
2. Salario en Especie: Son beneficios no monetarios como el uso de vivienda, coche de empresa, seguros médicos o cheques restaurante. 
- Límite Legal: El salario en especie nunca puede superar el 30% de tus percepciones salariales totales. Es decir, al menos el 70% de tu sueldo siempre debe ser dinero en efectivo o transferencia.', 2, 41);

-- Estructura de la Nómina (ID 42)
INSERT INTO BLOQUE (titulo, subtitulo, contenido, orden, id_categoria) VALUES 
('Devengos y Deducciones', 'Lo que sumas y lo que restas', 'Tu nómina se divide en dos grandes columnas que determinan lo que finalmente llega a tu banco:
- Devengos: Son las cantidades brutas. Se dividen en "Salariales" (sueldo base, pluses, antigüedad, pagas extra) y "No Salariales" (dietas, indemnizaciones o gastos de transporte que no cotizan igual).
- Deducciones: Es lo que se te retiene legalmente. Incluye tu aportación a la Seguridad Social (para cubrir tu jubilación, desempleo y bajas) y la retención del IRPF (un pago a cuenta de tu impuesto sobre la renta que varía según tu situación familiar y nivel de ingresos). El resultado final tras restar las deducciones a los devengos es tu "Salario Neto" o líquido a percibir.', 1, 42),

('Bases de Cotización', 'Tu protección social futura', 'Las bases de cotización son las cifras sobre las que se calculan tus futuras prestaciones:
- BCCC (Contingencias Comunes): Se usa para calcular tu jubilación, bajas por enfermedad común y permisos de maternidad/paternidad. Incluye el salario base y la parte proporcional de las pagas extras.
- BCCP (Contingencias Profesionales): Cubre accidentes de trabajo, enfermedades profesionales y desempleo. Es la base que determina cuánto cobrarás de "paro" si te quedas sin trabajo.
- Horas Extraordinarias: Tienen una cotización específica y adicional. Entender estas bases es vital, ya que cuanto más alta sea tu cotización, mayor será tu protección en el futuro.', 2, 42);

-- Garantías y Protección (ID 43)
INSERT INTO BLOQUE (titulo, subtitulo, contenido, orden, id_categoria) VALUES 
('Derechos ante el Pago', 'Plazos y Anticipos', 'El cobro del salario es tu derecho principal y está protegido por normas estrictas:
- Puntualidad: El salario debe pagarse en la fecha convenida, sin que el periodo de pago pueda exceder de un mes.
- Anticipos: Tienes derecho a percibir, antes de que llegue el día de pago, anticipos a cuenta del trabajo ya realizado durante el mes en curso.
- Interés por demora: Si la empresa se retrasa en el pago, tienes derecho a reclamar un interés del 10% anual sobre la cantidad adeudada. Si el retraso es continuado y grave, podrías solicitar judicialmente la extinción del contrato con la misma indemnización que un despido improcedente.', 1, 43),

('Garantías del Salario', 'Privilegios y el FOGASA', 'Si la empresa entra en crisis o quiebra, tu salario tiene una protección especial:
- Superprivilegio: Los salarios de los últimos 30 días de trabajo (hasta el doble del SMI) tienen preferencia absoluta de cobro sobre cualquier otra deuda de la empresa.
- FOGASA (Fondo de Garantía Salarial): Es un organismo estatal que garantiza que, si la empresa es declarada insolvente, tú cobres tus salarios e indemnizaciones pendientes (con ciertos límites legales). Funciona como un "seguro" para que nunca te quedes totalmente desprotegida ante el cierre de tu centro de trabajo.', 2, 43);

-- ==========================================
-- FAQs DE SALARIO
-- ==========================================
INSERT INTO FAQ (pregunta, respuesta, id_categoria) VALUES 
('¿Qué pasa si mi empresa no me da la nómina?', 'La empresa está obligada por ley a entregarte un recibo individual de salarios (nómina) cada mes. Es tu comprobante de que se han realizado los pagos y las cotizaciones. Si no te la dan, puedes ser sancionada la empresa y tú tendrías dificultades para reclamar impagos o solicitar prestaciones.', 42),

('¿Me pueden pagar todo el sueldo en "especie" o beneficios?', 'No. Por ley, el salario en especie no puede superar el 30% de las percepciones salariales. Además, la parte que recibes en dinero siempre debe ser, como mínimo, igual a la cuantía del SMI.', 41),

('¿Qué puedo hacer si la empresa se retrasa constantemente en el pago?', 'Tienes dos vías: 1) Reclamar las cantidades con un 10% de interés por demora. 2) Si el retraso es grave y persistente, puedes solicitar ante el juez la resolución del contrato, lo que te daría derecho a la indemnización máxima (como si fuera un despido improcedente) y acceso al paro.', 43),

('¿Qué son los devengos no salariales?', 'Son cantidades que recibes pero que no son "pago por tu trabajo" en sí, sino compensaciones por gastos. Ejemplos comunes son el plus de transporte, las dietas por comer fuera o las indemnizaciones por traslados. La diferencia principal es que estos conceptos no suelen cotizar para la jubilación o el paro.', 42),

('¿Cuál es el salario mínimo (SMI) en 2025?', 'El SMI garantiza un suelo salarial digno. Para una jornada completa, el mínimo legal es de 1.221€ mensuales si se percibe en 14 pagas, lo que suma un total de 17.094€ brutos al año. Ningún contrato a jornada completa puede estar por debajo de esta cifra.', 41);

-- 1. Creamos un bloque específico para la categoría 3 (Mi Tiempo Laboral)
-- Ponemos orden 0 para que sea lo primero que aparezca
INSERT INTO BLOQUE (titulo, subtitulo, contenido, orden, id_categoria) 
VALUES (
    'Mi Tiempo Laboral', 
    'Cabecera', 
    'Imagen principal de la categoría.', 
    0, 
    3
);

-- 2. Ahora sí, relacionamos la foto con ese bloque recién creado
-- Buscamos el bloque que acabamos de crear por su título
INSERT INTO contenido (url_externas, id_bloque) 
VALUES (
    'Imagenes/Contenidos/mi-tiempo-laboral.png', 
    (SELECT id_bloque FROM BLOQUE WHERE titulo = 'Mi Tiempo Laboral' AND id_categoria = 3 LIMIT 1)
);

-- Imágenes de portada para categorías principales y subcategorías
-- Categorías principales
INSERT INTO BLOQUE (titulo, subtitulo, contenido, orden, id_categoria) VALUES
('Mis Derechos Iniciales', 'Bienvenida', 'Imagen de portada de la categoría principal.', 0, 1),
('Protección y Cambios', 'Bienvenida', 'Imagen de portada de la categoría principal.', 0, 2),
('Mi Salario y mi Nómina', 'Bienvenida', 'Imagen de portada de la categoría principal.', 0, 4);

-- Subcategorías
INSERT INTO BLOQUE (titulo, subtitulo, contenido, orden, id_categoria) VALUES
('Ser Trabajadora', 'Portada', 'Imagen de portada de la subcategoría.', 0, 11),
('Edad y Nacionalidad', 'Portada', 'Imagen de portada de la subcategoría.', 0, 12),
('Tipos de Contrato', 'Portada', 'Imagen de portada de la subcategoría.', 0, 13),
('Jornada y Descanso', 'Portada', 'Imagen de portada de la subcategoría.', 0, 14),
('Cambios de Condiciones', 'Portada', 'Imagen de portada de la subcategoría.', 0, 21),
('Maternidad y Pausas', 'Portada', 'Imagen de portada de la subcategoría.', 0, 22),
('Despido y Cierre', 'Portada', 'Imagen de portada de la subcategoría.', 0, 23),
('Finiquito y Liquidación', 'Portada', 'Imagen de portada de la subcategoría.', 0, 24),
('Jornada y Horarios', 'Portada', 'Imagen de portada de la subcategoría.', 0, 31),
('Descansos y Vacaciones', 'Portada', 'Imagen de portada de la subcategoría.', 0, 32),
('Horas y Turnos', 'Portada', 'Imagen de portada de la subcategoría.', 0, 33),
('Permisos y Conciliación', 'Portada', 'Imagen de portada de la subcategoría.', 0, 34),
('Conceptos y Cuantía', 'Portada', 'Imagen de portada de la subcategoría.', 0, 41),
('La Estructura de la Nómina', 'Portada', 'Imagen de portada de la subcategoría.', 0, 42),
('Garantías y Protección', 'Portada', 'Imagen de portada de la subcategoría.', 0, 43);

-- Asignación de imágenes a las categorías y subcategorías
INSERT INTO contenido (url_externas, id_bloque) VALUES
('Imagenes/Contenidos/derechos-del-trabajador.jpg', (SELECT id_bloque FROM BLOQUE WHERE titulo = 'Mis Derechos Iniciales' AND id_categoria = 1 LIMIT 1)),
('Imagenes/Contenidos/proteccion-y-cambios.jpg', (SELECT id_bloque FROM BLOQUE WHERE titulo = 'Protección y Cambios' AND id_categoria = 2 LIMIT 1)),
('Imagenes/Contenidos/mi-salario-y-mi-nomina.jpg', (SELECT id_bloque FROM BLOQUE WHERE titulo = 'Mi Salario y mi Nómina' AND id_categoria = 4 LIMIT 1)),
('Imagenes/Contenidos/ser-trabajadora.jpg', (SELECT id_bloque FROM BLOQUE WHERE titulo = 'Ser Trabajadora' AND id_categoria = 11 LIMIT 1)),
('Imagenes/Contenidos/edad-y-nacionalidad.jpg', (SELECT id_bloque FROM BLOQUE WHERE titulo = 'Edad y Nacionalidad' AND id_categoria = 12 LIMIT 1)),
('Imagenes/Contenidos/tipos-de-contrato.jfif', (SELECT id_bloque FROM BLOQUE WHERE titulo = 'Tipos de Contrato' AND id_categoria = 13 LIMIT 1)),
('Imagenes/Contenidos/jornada-y-descanso.jpg', (SELECT id_bloque FROM BLOQUE WHERE titulo = 'Jornada y Descanso' AND id_categoria = 14 LIMIT 1)),
('Imagenes/Contenidos/cambios-y-condiciones.jpg', (SELECT id_bloque FROM BLOQUE WHERE titulo = 'Cambios de Condiciones' AND id_categoria = 21 LIMIT 1)),
('Imagenes/Contenidos/maternidad-y-pausas.jpg', (SELECT id_bloque FROM BLOQUE WHERE titulo = 'Maternidad y Pausas' AND id_categoria = 22 LIMIT 1)),
('Imagenes/Contenidos/despido-y-cierre.jpg', (SELECT id_bloque FROM BLOQUE WHERE titulo = 'Despido y Cierre' AND id_categoria = 23 LIMIT 1)),
('Imagenes/Contenidos/finiquito-y-liquidacion.png', (SELECT id_bloque FROM BLOQUE WHERE titulo = 'Finiquito y Liquidación' AND id_categoria = 24 LIMIT 1)),
('Imagenes/Contenidos/jornada-y-horarios.jpg', (SELECT id_bloque FROM BLOQUE WHERE titulo = 'Jornada y Horarios' AND id_categoria = 31 LIMIT 1)),
('Imagenes/Contenidos/descansos-y-vacaciones.jpg', (SELECT id_bloque FROM BLOQUE WHERE titulo = 'Descansos y Vacaciones' AND id_categoria = 32 LIMIT 1)),
('Imagenes/Contenidos/horas-y-turnos.jpg', (SELECT id_bloque FROM BLOQUE WHERE titulo = 'Horas y Turnos' AND id_categoria = 33 LIMIT 1)),
('Imagenes/Contenidos/permisos-y-conciliacion.jpg', (SELECT id_bloque FROM BLOQUE WHERE titulo = 'Permisos y Conciliación' AND id_categoria = 34 LIMIT 1)),
('Imagenes/Contenidos/conceptos-y-cuantia.jpg', (SELECT id_bloque FROM BLOQUE WHERE titulo = 'Conceptos y Cuantía' AND id_categoria = 41 LIMIT 1)),
('Imagenes/Contenidos/la-estructura-de-la-nomina.jpg', (SELECT id_bloque FROM BLOQUE WHERE titulo = 'La Estructura de la Nómina' AND id_categoria = 42 LIMIT 1)),
('Imagenes/Contenidos/garantias-y-proteccion.jpg', (SELECT id_bloque FROM BLOQUE WHERE titulo = 'Garantías y Protección' AND id_categoria = 43 LIMIT 1));

-- 1. Borramos primero el contenido (la URL de la imagen) asociado a los bloques de esa categoría
DELETE FROM contenido 
WHERE id_bloque IN (SELECT id_bloque FROM BLOQUE WHERE id_categoria = 14);

-- 2. Borramos el bloque de 'Portada' que creaste para esa categoría
DELETE FROM BLOQUE 
WHERE id_categoria = 14;

-- 3. Finalmente, borramos la categoría (si es que llegaste a insertarla manualmente, 
-- ya que en tu script original no figuraba el INSERT de CATEGORIA para el ID 14)
DELETE FROM CATEGORIA 
WHERE id_categoria = 14;

-- Sincroniza la secuencia de la tabla CATEGORIA
SELECT setval('categoria_id_categoria_seq', (SELECT MAX(id_categoria) FROM CATEGORIA));

-- Sincroniza la secuencia de la tabla ROL
SELECT setval('rol_id_rol_seq', (SELECT MAX(id_rol) FROM ROL));

-- Sincroniza la secuencia de la tabla USUARIOS
SELECT setval('usuarios_id_usuario_seq', (SELECT MAX(id_usuario) FROM USUARIOS));