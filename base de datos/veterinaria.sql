-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-01-2022 a las 10:11:48
-- Versión del servidor: 10.4.20-MariaDB
-- Versión de PHP: 7.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `veterinaria`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas`
--

CREATE TABLE `citas` (
  `cliente` bigint(20) UNSIGNED NOT NULL,
  `servicio` bigint(20) UNSIGNED NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `citas`
--

INSERT INTO `citas` (`cliente`, `servicio`, `fecha`, `hora`) VALUES
(24, 9, '2022-01-21', '13:25:00'),
(24, 10, '2022-01-23', '14:25:00'),
(24, 11, '2022-01-30', '15:25:00'),
(26, 11, '2022-02-06', '11:27:00'),
(27, 9, '2022-01-27', '14:26:00'),
(27, 10, '2022-02-03', '12:26:00'),
(30, 9, '2022-01-30', '13:26:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `edad` tinyint(3) UNSIGNED NOT NULL,
  `foto` varchar(255) NOT NULL,
  `dni_dueño` varchar(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`id`, `tipo`, `nombre`, `edad`, `foto`, `dni_dueño`) VALUES
(24, 'Perro', 'Coyote', 4, '24.jpg', '11111111A'),
(25, 'Gato', 'Kitty', 2, '25.jpg', '11111111A'),
(26, 'Insecto palo', 'Sudowoodo', 5, '26.jpg', '22222222B'),
(27, 'Cerdo', 'Brave', 7, '27.jpg', '33333333C'),
(28, 'Perro', 'Tobby', 1, '28.jpg', '33333333C'),
(29, 'Gato', 'Pezuñas', 3, '29.jpg', '44444444D'),
(30, 'Perro', 'Luna', 5, '30.png', '55555555E'),
(31, 'Pingüino', 'Isidro', 8, '31.jpg', '55555555E');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dueño`
--

CREATE TABLE `dueño` (
  `dni` varchar(9) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `telefono` varchar(9) DEFAULT NULL,
  `nick` varchar(25) NOT NULL,
  `pass` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `dueño`
--

INSERT INTO `dueño` (`dni`, `nombre`, `telefono`, `nick`, `pass`) VALUES
('000000000', 'Administrador', NULL, 'admin', '4c14a808735abb4b205d1c8cb54ec845'),
('11111111A', 'Esteban', '958111111', 'ferran_torres', '961f60be5ee4d2916127234b205bd428'),
('22222222B', 'Raúl', '958222222', 'raulillo', '74e59720dd08b1db45f7152d082c5051'),
('33333333C', 'Pablo', NULL, 'barriga', '78d6ec2638b9e2b60d1737b70a62b8df'),
('44444444D', 'María José', '958333333', 'mj', '5ecf47821d8849255b5eb6365f3eda87'),
('55555555E', 'Raquel', NULL, 'rah', 'b01569eee14495f1aceb97b8262b29e8');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `noticia`
--

CREATE TABLE `noticia` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `titulo` varchar(50) NOT NULL,
  `contenido` varchar(5000) NOT NULL,
  `imagen` varchar(255) NOT NULL,
  `fecha_publicacion` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `noticia`
--

INSERT INTO `noticia` (`id`, `titulo`, `contenido`, `imagen`, `fecha_publicacion`) VALUES
(12, 'Profesora de la Facultad de Lugo muy influyente', 'El talento del campus de Lugo (USC) es reconocido en toda España. Una profesora de la Facultade de Veterinaria, Azucena Mora, aparece entre las científicas españolas más influyentes según el ránking DIH. Se trata de una clasificación elaborada según el método que diseñó Jorge Hirsch, profesor californiano. Los artículos escritos por un investigador y las citas que otros estudiosos hacen de esos trabajos son los criterios que se usan para calcular la influencia de una persona dentro de la comunidad científica. En la lista aparecen 466 investigadoras de toda España; catorce pertenecen a la Universidade de Santiago (USC), y solo Mora trabaja en el campus de Lugo, mientras que el resto forma parte del santiagués.\r\n\r\nLa actividad investigadora de Mora, profesora del Departamento de Microbioloxía e Parasitoloxía, se centra en cuestiones de seguridad alimentaria, con especial atención a la resistencia de algunas bacterias a los antibióticos: «Es un problema grande», reconoce esta docente, que subraya que se está trabajando en muchos frentes. Dice que la cuestión de la seguridad alimentaria debe abordarse desde distintos campos; cree que deben incluirse la medicina y la veterinaria pero también la sociología, ya que, afirma, se trata de una cuestión que afecta a toda la sociedad. «Todos tenemos un papel», explica.', '12.jpg', '2022-01-19'),
(13, 'Noemí Castro, la mejor de España', 'La Universidad de Las Palmas de Gran Canaria (ULPGC) ha celebrado que cinco de sus profesoras han sido incluidas en un ranking elaborado por el Grupo para la Difusión del Índice H (Grupo DIH) que incluye a las científicas más importantes residentes en España, basándose en los datos recogidos durante el último cuatrimestre del 2021 hasta el mes de enero de 2022.\r\n\r\nEl ranking cita a un total de 466 investigadoras residentes en España, de entre 785 investigadoras analizadas, en función de su Índice H, es decir, del indicador que permite evaluar su producción científica. La clasificación recoge el ‘Fhm’ de cada una, es decir, el valor medio de la relación entre el índice h de la investigadora y el valor medio de los h del resto de investigadoras presentes en el ranking.\r\n\r\nConcretamente, las investigadoras incluidas en el ranking son Marisol Izquierdo López, del área de Zoología, con un Fhm de 1,31; Noemí Castro Navarro, del área de Producción Animal, con un Fhm de 0,86; María José Caballero Cansino, del área de Anatomía y Anatomía Patológica Comparadas, con un Fhm de 0,83; Lidia Esther Robaina Robaina, del área de Zoología, con un Fhm de 0,83; Teresa Carrillo Díaz, del área de Medicina, con un Fhm de 0,77.', '13.jpg', '2022-01-24'),
(14, 'Cursos de extensión universitaria en marcha', 'La Universidad de León ha organizado cuatro cursos de extensión universitaria relacionados con el ámbito veterinario. Se trata de los de \'Auxiliar veterinario\', \'Estética y peluquería canina y felina\', \'Auxiliar quirúrgico veterinario\' y \'Etología y adiestramiento canino\', que se vienen impartiendo con éxito desde hace varios años. La edad mínima para iniciar estos estudios es de 18 años (en 2022), y podrán acceder los graduados ESO o equivalente oficial.\r\n\r\nLas personas que estén interesadas ya pueden formalizar su matrícula para recibir una formación que les permitirá adquirir los conocimientos necesarios para el ejercicio profesional de nuevos perfiles laborales cada vez con más demanda en la sociedad, que además serán impartidos por profesores de reconocido prestigio de la Facultad de Veterinaria de la ULE. Hay que apuntar que tanto la enseñanza teórica como las prácticas se combinarán del modo y manera que mejor se adapten a los horarios y disponibilidad de los alumnos.', '14.jpg', '2022-01-21'),
(15, 'Destapadas las irregularidades cometidas', 'La Guardia Civil de la Región de Murcia desarrolla la operación \'Horseon\', dirigida a esclarecer unas supuestas irregularidades en la confección de pasaportes equinos sin conocer su trazabilidad en origen, lo que permitía su entrada en la cadena alimentaria, que se saldó con la detención de tres personas y con la instrucción de diligencias como investigados a otras tres como presuntas autoras de los delitos de cohecho, falsedad documental y contra la salud pública.\r\n\r\nLa investigación se inició en el mes de febrero del pasado año, cuando dos veterinarios denunciaron unas supuestas irregularidades en la expedición de documentos oficiales y en la implantación de microchips a equinos, para regularizar su situación, desconociendo su trazabilidad en origen.\r\n\r\nA la vista de la denuncia, agentes de la Guardia Civil del Seprona (Servicio de Protección de la Naturaleza) abrieron una investigación para verificar los supuestos ilícitos, iniciando una serie de inspecciones sobre granjas ganaderas destinadas a la cría de caballos.', '15.jpg', '2022-01-29'),
(16, 'Veterinaria sobre ruedas en el Pirineo navarro', 'Desde bien pequeña, los animales han estado presentes en su vida y, por ende, su pasión hacia ellos. Recuerda cómo disfrutaba acompañando a su abuelo a atender el ganado y el amor que le transmitió por \"los bichos\", y cómo su padre, se convirtió en su \"cómplice\" al dejarlos meter en casa.\r\n\r\nDe esa vocación forjada en la niñez, la vecina de Mezkiritz Teresa Etxarri Elizalde logró estudiar veterinaria en Zaragoza y trabajar en el Hospital Veterinario de Pamplona. Sin embargo, tras unos años, acaba de lanzarse a emprender por sí misma un nuevo negocio: GureVet, la primera clínica veterinaria a domicilio de Navarra.\r\n\r\nThank you for watching\r\n\r\n\"Había una necesidad en el Pirineo, ya que no hay clínicas ni nada relacionado con animales de compañía y, además, con la pandemia, ha habido un auge de mascotas\", expresa esta joven de 27 años.\r\n\r\nLa idea le rondaba en la cabeza desde hacía años, pero no fue hasta que le avisaron de unas subvenciones de Gu Pirinioa para jóvenes emprendedores cuando se lo tomó más en serio.', '16.jpg', '2022-01-21'),
(17, 'Pamplona convoca pruebas selectivas', 'El Ayuntamiento de Pamplona ha convocado la constitución, a través de pruebas selectivas, de dos relaciones de aspirantes al desempeño del puesto de trabajo de veterinario o veterinaria. Una de las listas se crea para la formación y está dirigida a personal fijo al servicio del Ayuntamiento de Pamplona, y la otra se genera para la contratación temporal y está abierta a cualquier persona que cumpla los requisitos establecidos. Las solicitudes para concurrir a la convocatoria se pueden presentar hasta el 31 de enero en los registros municipales o a través de los medios previstos por ley.\r\n\r\nQuienes quiera apuntarse a la relación de aspirantes a la formación, en situación de servicios especiales, deben tenar la condición de personal fijo al servicio del Ayuntamiento de Pamplona y/o sus organismos autónomos, pertenecer al mismo o inferior nivel al de las plazas convocadas y no haber agotado el periodo máximo de formación a que se tenga derecho. Asimismo, deben haber completado un mínimo de tres años de servicios efectivamente prestados en su puesto de trabajo, no hallarse en situación de excedencia voluntaria o forzosa, tener el permiso de conducir de clase B y hallarse en posesión del título de Grado en Veterinaria o equivalente.', '17.jpg', '2022-02-03'),
(18, 'El paro en veterinaria marcó una caída récord', 'La veterinaria ha sido uno de los sectores que ha podido afrontar los pormenores del Covid-19 sin sufrir tantas consecuencias económicas como en otros ámbitos. Esto también se ha visto reflejado en el paro, que aunque se incrementó en 2020 —el año de la pandemia—, ha marcado una fuerte caída en 2021, situándose ya en cifras inferiores a años previos a la crisis sanitaria.\r\n\r\nAsí lo reflejan los últimos datos del Servicio Público de Empleo Estatal (SEPE), que ha registrado al cierre de 2021 un total de 1.495 veterinarios parados, de los que 333 son graduados y 1.162 son licenciados. Esta cifra supone un descenso del 13,68% en comparación con 2020 y es también inferior a los parados que se registraron en 2019 (1.541), el último año sin pandemia.\r\n\r\nEsto se suma a los recientes datos sobre afiliaciones a la seguridad social, que también crecieron al cierre de 2021 por encima de los niveles prepandemia, marcando un incremento de 1.701 nuevos veterinarios, por encima de los incrementos de 2020 (560), 2019 (1.119) y 2018 (924).\r\n\r\nPara valorar la magnitud de la caída del paro en 2021, hay que señalar que este ha bajado (-12,14%) incluso entre los graduados, que llevaban una tendencia alcista en los últimos 10 años. Además, respecto a los licenciados (-14,12%), que han tenido una tendencia a la baja en la última década, esta caída ha sido récord y superior a la de cualquier año de la serie.', '18.jpg', '2022-01-25'),
(19, 'Como mantener la vida de los equipos de anestesia', 'La compañía veterinaria Fatro sigue apostando por la formación de los veterinarios, ofreciendo webinars y ciclos de conferencias en los que expertos del sector desentrañan diversos aspectos de interés para estos profesionales sanitarios.\r\n\r\nAsí, para el próximo 16 de febrero, a las 15 horas, la compañía veterinaria ha organizado el webinar ‘La importancia de la calibración de los vaporizadores en Anestesia’, que impartirá el experto LLuís Sanz.\r\n\r\n“Durante esta sesión hablaremos de que es un vaporizador y cómo funciona, haciendo una breve descripción de lo que hace, en que consiste su limpieza y calibrado, así como los factores mecánicos, químicos y ambientales que intervienen en el funcionamiento del vaporizador”, señalan desde Fatro.\r\n\r\nLa compañía defiende que este webinar ayudará a garantizar el buen estado de esta parte tan importante del equipo de anestesia, y contribuirá a asegurar su uso por un periodo largo de tiempo, manteniendo la vida útil del equipo.\r\n\r\nAdemás, destacan que el ponente, Lluis Sanz, tiene más de 30 años en el sector veterinario clínico y experimental como técnico instalador y de mantenimiento de equipamiento.\r\n\r\nTambién es representante, distribuidor y servicio técnico de prestigiosos fabricantes de equipos de anestesia y es servicio técnico autorizado de un importante fabricante de vaporizadores, donde realiza el servicio de reparación, limpieza, verificado y calibrado de los vaporizadores.\r\n\r\nPor último, Fatro señala que está autorizado por el Consejo de Seguridad Nuclear como EVAT (Empresa de Venta y Asistencia Técnica para equipos de radiodiagnóstico Veterinario).', '19.jpg', '2022-01-26'),
(20, 'Aumento del estrés entre los profesionales', 'Merck Animal Health, conocida como MSD Animal Health, ha lanzado los nuevos hallazgos de su estudio integral realizado en colaboración con la Asociación Médica Veterinaria Estadounidense (AVMA) que examina el bienestar y la salud mental de los veterinarios estadounidenses.\r\n\r\n\r\n\r\nRealizado en el otoño de 2021, el estudio es la tercera encuesta desde 2017 y la primera desde que comenzó la pandemia de la COVID-19. Tiene el objetivo de examinar y generar conciencia crítica sobre los desafíos que afectan a la profesión veterinaria, al tiempo que destaca el impacto que la pandemia tiene en los profesionales y el personal.\r\n\r\n\r\n\r\nDesde directores de hospitales y dueños de consultorios, hasta técnicos veterinarios y personal administrativo, el último Estudio de Bienestar de MSD Animal Health reveló que las principales barreras que afectan a quienes practican la medicina veterinaria son la escasez de personal cualificado, y el hecho de que no todos los empleados de clínicas u hospitales tienen acceso a las mismas herramientas de cuidado de la salud mental.\r\n\r\n\r\n\r\n\"La medicina veterinaria es una profesión que vive con la gran satisfacción de cuidar animales, pero también incluye el riesgo de agotamiento mental y físico, así como fatiga por compasión\", señala Joseph Hahn, director ejecutivo de MSD.\r\n\r\n\r\n\r\n“Nuestro tercer estudio de bienestar en asociación con AVMA es clave para definir la turbulencia subyacente que está aumentando estos factores estresantes en la profesión, al tiempo que nos ayuda a identificar las soluciones más impactantes para energizar y fortalecer la salud mental de los veterinarios, técnicos y personal de apoyo”, añade.\r\n\r\n\r\n\r\nMientras que el 92 % de los encuestados calificó el aumento del estrés como uno de sus principales desafíos de salud mental, el 88 % citó la deuda económica de la etapa estudiantil y las preocupaciones sobre el riesgo de suicidio como los principales factores estresantes para los veterinarios.', '20.jpg', '2022-01-24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `precio` decimal(5,2) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id`, `nombre`, `precio`) VALUES
(14, 'Peine', '1.00'),
(15, 'Correa', '2.00'),
(16, 'Collar', '1.50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicio`
--

CREATE TABLE `servicio` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `duracion` tinyint(3) UNSIGNED NOT NULL,
  `precio` decimal(5,2) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `servicio`
--

INSERT INTO `servicio` (`id`, `descripcion`, `duracion`, `precio`) VALUES
(9, 'Vacunación', 10, '30.00'),
(10, 'Desparasitación', 180, '20.99'),
(11, 'Corte de pelo', 120, '10.50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `testimonio`
--

CREATE TABLE `testimonio` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `contenido` varchar(500) NOT NULL,
  `fecha` date NOT NULL,
  `dni_autor` varchar(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `testimonio`
--

INSERT INTO `testimonio` (`id`, `contenido`, `fecha`, `dni_autor`) VALUES
(10, 'Me ha gustado mucho la experiencia con este equipo. Lo recomiendo.\r\n\r\nPD: Lo único es que me hicieron hacer varias encuestas y se hizo muy tedioso', '2022-01-21', '11111111A'),
(11, 'Me ha encantado el trato con mis mascotas. 10 de 10.', '2022-01-21', '22222222B'),
(12, 'Su humor a la hora de tratar ciertos temas es algo ácido, pero por lo general muy bien.', '2022-01-21', '33333333C'),
(13, 'Vine de urgencias y todo fue muy rápido y eficiente por su parte la verdad.\r\nSolo espero que la próxima vez tengan un poco más de limpieza en la salita de espera.', '2022-01-21', '44444444D'),
(14, 'No me ha gustado mucho mi primera experiencia, pero se les ve buenos profesionales y probablemente la próxima vez sea mejor.', '2022-01-21', '55555555E');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`cliente`,`fecha`,`hora`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `dueño_cliente` (`dni_dueño`);

--
-- Indices de la tabla `dueño`
--
ALTER TABLE `dueño`
  ADD PRIMARY KEY (`dni`),
  ADD UNIQUE KEY `dni` (`dni`),
  ADD UNIQUE KEY `nick` (`nick`);

--
-- Indices de la tabla `noticia`
--
ALTER TABLE `noticia`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indices de la tabla `servicio`
--
ALTER TABLE `servicio`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indices de la tabla `testimonio`
--
ALTER TABLE `testimonio`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `dueño_testimonio` (`dni_autor`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `noticia`
--
ALTER TABLE `noticia`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `servicio`
--
ALTER TABLE `servicio`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `testimonio`
--
ALTER TABLE `testimonio`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `dueño_cliente` FOREIGN KEY (`dni_dueño`) REFERENCES `dueño` (`dni`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `testimonio`
--
ALTER TABLE `testimonio`
  ADD CONSTRAINT `dueño_testimonio` FOREIGN KEY (`dni_autor`) REFERENCES `dueño` (`dni`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
