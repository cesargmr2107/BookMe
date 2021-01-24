-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 24-01-2021 a las 15:52:45
-- Versión del servidor: 10.3.23-MariaDB-0+deb10u1
-- Versión de PHP: 7.3.19-1~deb10u1

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `53196285E`
--
CREATE DATABASE IF NOT EXISTS `53196285E` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci;
USE `53196285E`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CALENDARIOS_DE_USO`
--

DROP TABLE IF EXISTS `CALENDARIOS_DE_USO`;
CREATE TABLE IF NOT EXISTS `CALENDARIOS_DE_USO` (
  `ID_CALENDARIO` int(11) NOT NULL AUTO_INCREMENT,
  `NOMBRE_CALENDARIO` varchar(40) COLLATE utf8_spanish_ci NOT NULL,
  `DESCRIPCION_CALENDARIO` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `FECHA_INICIO_CALENDARIO` date NOT NULL,
  `FECHA_FIN_CALENDARIO` date NOT NULL,
  `HORA_INICIO_CALENDARIO` time NOT NULL COMMENT 'HORA DE COMIENZO DE CUALQUIER DIA DEL CALENDARIO',
  `HORA_FIN_CALENDARIO` time NOT NULL COMMENT 'HORA DE FIN DE CUALQUIER DIA DEL CALENDARIO',
  `BORRADO_LOGICO` enum('SI','NO') COLLATE utf8_spanish_ci NOT NULL COMMENT 'BORRADO LOGICO DE LA TUPLA',
  PRIMARY KEY (`ID_CALENDARIO`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `CALENDARIOS_DE_USO`
--

INSERT INTO `CALENDARIOS_DE_USO` (`ID_CALENDARIO`, `NOMBRE_CALENDARIO`, `DESCRIPCION_CALENDARIO`, `FECHA_INICIO_CALENDARIO`, `FECHA_FIN_CALENDARIO`, `HORA_INICIO_CALENDARIO`, `HORA_FIN_CALENDARIO`, `BORRADO_LOGICO`) VALUES
(1, 'Calendario de RRHH', 'Este es el calendario de disponibilidad de los recursos  de RRHH', '2021-01-01', '2021-12-31', '08:00:00', '14:00:00', 'NO'),
(2, 'Calendario de Ventas', 'Este es el calendario que describe la disponibilidad de los recursos de Ventas', '2021-01-01', '2021-12-31', '15:00:00', '20:00:00', 'NO'),
(3, 'Calendario de Contaduría', 'Este es el calendario que describe la disponibilidad de los recursos de Contaduría', '2021-01-01', '2021-06-30', '09:00:00', '16:00:00', 'NO'),
(4, 'Calendario de Dirección', 'Este calendario describe la disponibilidad de los recursos de Dirección', '2021-01-24', '2021-12-31', '07:00:00', '19:00:00', 'NO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `RECURSOS`
--

DROP TABLE IF EXISTS `RECURSOS`;
CREATE TABLE IF NOT EXISTS `RECURSOS` (
  `ID_RECURSO` int(11) NOT NULL AUTO_INCREMENT,
  `NOMBRE_RECURSO` varchar(40) COLLATE utf8_spanish_ci NOT NULL,
  `DESCRIPCION_RECURSO` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `TARIFA_RECURSO` int(3) NOT NULL COMMENT 'VALOR MONETARIO DE TARIFICACIÓN POR RANGO',
  `RANGO_TARIFA_RECURSO` enum('HORA','DIA','SEMANA','MES') COLLATE utf8_spanish_ci NOT NULL COMMENT 'RANGO DE APLICACIÓN DE LA TARIFA',
  `ID_CALENDARIO` int(11) NOT NULL COMMENT 'CALENDARIO ASOCIADO AL RECURSO',
  `LOGIN_RESPONSABLE` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `BORRADO_LOGICO` enum('SI','NO') COLLATE utf8_spanish_ci NOT NULL COMMENT 'BORRADO LOGICO DE LA TUPLA EN EL CASO DE QUE EXISTA INFORMACIÓN EN LA BD DE ESTE RECURSO',
  PRIMARY KEY (`ID_RECURSO`),
  KEY `LOGIN_RESPONSABLE` (`LOGIN_RESPONSABLE`),
  KEY `ID_CALENDARIO` (`ID_CALENDARIO`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `RECURSOS`
--

INSERT INTO `RECURSOS` (`ID_RECURSO`, `NOMBRE_RECURSO`, `DESCRIPCION_RECURSO`, `TARIFA_RECURSO`, `RANGO_TARIFA_RECURSO`, `ID_CALENDARIO`, `LOGIN_RESPONSABLE`, `BORRADO_LOGICO`) VALUES
(1, 'Proyector Xiaomi', 'Este proyector es utilizado para las reuniones del departamento de Dirección', 20, 'SEMANA', 4, 'resp1', 'NO'),
(2, 'Salón de actos', 'El salón de actos lo usa el departamento de RRH para ruedas de prensa', 5, 'DIA', 1, 'resp2', 'NO'),
(3, 'Ventilador LG', 'Este ventilador lo utilizan los miembros del departamento de Contaduría', 1, 'DIA', 3, 'resp1', 'NO'),
(4, 'iPhone de Última Generación', 'Utilizado por los utilizan los miembros de Dirección como teléfono de la empresa ', 30, 'MES', 4, 'resp2', 'NO'),
(5, 'Portátil HP Pavillion', 'Utilizado por los miembros del departamento de RRHH para sus presentaciones publicitarias', 5, 'HORA', 1, 'resp1', 'NO'),
(6, 'Lámpara de sobremesa', 'Utilizada por los miembros del departamento de Dirección cuando la necesitan', 1, 'DIA', 4, 'resp1', 'NO'),
(7, 'Seminario', 'Utilizado por los miembros del departamento de Contaduría para sus reuniones', 5, 'DIA', 3, 'resp2', 'NO'),
(8, 'Pantalla Digital', 'Usada por RRHH para sus exposiciones sobre el mercado de la empresa', 5, 'HORA', 1, 'resp1', 'NO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `RESERVAS`
--

DROP TABLE IF EXISTS `RESERVAS`;
CREATE TABLE IF NOT EXISTS `RESERVAS` (
  `ID_RESERVA` int(11) NOT NULL AUTO_INCREMENT,
  `LOGIN_USUARIO` varchar(15) COLLATE utf8_spanish_ci NOT NULL COMMENT 'PERSONA QUE HACE LA PETICION DE USO DEL RECURSO',
  `ID_RECURSO` int(11) NOT NULL,
  `FECHA_SOLICITUD_RESERVA` date NOT NULL COMMENT 'FECHA EN QUE SE SOLICITA EL RECURSO',
  `FECHA_RESPUESTA_RESERVA` date DEFAULT NULL COMMENT 'FECHA EN QUE SE AUTORIZA/RECHAZA LA RESERVA POR EL RESPONSABLE',
  `MOTIVO_RECHAZO_RESERVA` varchar(200) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'MOTIVO POR EL CUAL LA RESERVA ES RECHAZADA',
  `ESTADO_RESERVA` enum('PENDIENTE','ACEPTADA','RECHAZADA','CANCELADA','RECURSO_USADO','RECURSO_NO_USADO') COLLATE utf8_spanish_ci NOT NULL DEFAULT 'PENDIENTE',
  `COSTE_RESERVA` double(6,2) NOT NULL,
  PRIMARY KEY (`ID_RESERVA`),
  KEY `LOGIN_USUARIO` (`LOGIN_USUARIO`),
  KEY `ID_RECURSO` (`ID_RECURSO`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `RESERVAS`
--

INSERT INTO `RESERVAS` (`ID_RESERVA`, `LOGIN_USUARIO`, `ID_RECURSO`, `FECHA_SOLICITUD_RESERVA`, `FECHA_RESPUESTA_RESERVA`, `MOTIVO_RECHAZO_RESERVA`, `ESTADO_RESERVA`, `COSTE_RESERVA`) VALUES
(1, 'cesarino', 1, '2021-01-24', NULL, NULL, 'PENDIENTE', 134.29),
(2, 'cesarino', 3, '2021-01-24', NULL, NULL, 'PENDIENTE', 139.92),
(3, 'cesarino', 2, '2021-01-24', NULL, NULL, 'PENDIENTE', 140.00),
(4, '_beltran_', 6, '2021-01-24', NULL, NULL, 'PENDIENTE', 28.00),
(5, '_beltran_', 5, '2021-01-24', NULL, NULL, 'PENDIENTE', 200.00),
(6, '_beltran_', 2, '2021-01-24', NULL, NULL, 'PENDIENTE', 120.00),
(7, 'marta', 4, '2021-01-24', NULL, NULL, 'PENDIENTE', 76.89),
(8, 'marta', 4, '2021-01-24', NULL, NULL, 'CANCELADA', 34.52),
(9, 'marta', 6, '2021-01-24', NULL, NULL, 'PENDIENTE', 58.96),
(10, 'marta', 7, '2021-01-24', NULL, NULL, 'PENDIENTE', 175.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `RESPONSABLES_RECURSO`
--

DROP TABLE IF EXISTS `RESPONSABLES_RECURSO`;
CREATE TABLE IF NOT EXISTS `RESPONSABLES_RECURSO` (
  `LOGIN_RESPONSABLE` varchar(15) COLLATE utf8_spanish_ci NOT NULL COMMENT 'LOGIN EN TABLA USUARIO DEL RESPONSABLE',
  `DIRECCION_RESPONSABLE` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `TELEFONO_RESPONSABLE` int(9) NOT NULL,
  PRIMARY KEY (`LOGIN_RESPONSABLE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `RESPONSABLES_RECURSO`
--

INSERT INTO `RESPONSABLES_RECURSO` (`LOGIN_RESPONSABLE`, `DIRECCION_RESPONSABLE`, `TELEFONO_RESPONSABLE`) VALUES
('resp1', 'Avenida Buenos Aires 123 3ºA 32004 Ourense', 999888777),
('resp2', 'Avenida Castelao 23 5C 36209 Vigo', 987654321);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `SUBRESERVAS`
--

DROP TABLE IF EXISTS `SUBRESERVAS`;
CREATE TABLE IF NOT EXISTS `SUBRESERVAS` (
  `ID_RESERVA` int(11) NOT NULL,
  `ID_SUBRESERVA` int(11) NOT NULL,
  `FECHA_INICIO_SUBRESERVA` date NOT NULL COMMENT 'FECHA EN LA QUE COMIENZA LA RESERVA',
  `FECHA_FIN_SUBRESERVA` date NOT NULL COMMENT 'FECHA EN LA QUE TERMINA LA RESERVA',
  `HORA_INICIO_SUBRESERVA` time NOT NULL COMMENT 'HORA DE COMIENZO DE LA RESERVA',
  `HORA_FIN_SUBRESERVA` time NOT NULL COMMENT 'HORA DE FIN DE LA RESERVA',
  `COSTE_SUBRESERVA` double(6,2) NOT NULL,
  PRIMARY KEY (`ID_RESERVA`,`ID_SUBRESERVA`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `SUBRESERVAS`
--

INSERT INTO `SUBRESERVAS` (`ID_RESERVA`, `ID_SUBRESERVA`, `FECHA_INICIO_SUBRESERVA`, `FECHA_FIN_SUBRESERVA`, `HORA_INICIO_SUBRESERVA`, `HORA_FIN_SUBRESERVA`, `COSTE_SUBRESERVA`) VALUES
(1, 1, '2021-01-25', '2021-02-07', '09:00:00', '14:00:00', 40.00),
(1, 2, '2021-01-27', '2021-02-28', '18:00:00', '19:00:00', 94.29),
(2, 1, '2021-01-25', '2021-04-04', '15:00:00', '16:00:00', 69.96),
(2, 2, '2021-01-25', '2021-04-04', '09:00:00', '10:00:00', 69.96),
(3, 1, '2021-02-01', '2021-02-28', '08:00:00', '10:00:00', 140.00),
(4, 1, '2021-02-17', '2021-02-26', '14:00:00', '16:00:00', 10.00),
(4, 2, '2021-03-01', '2021-03-18', '14:00:00', '16:00:00', 18.00),
(5, 1, '2021-02-09', '2021-02-16', '09:00:00', '14:00:00', 200.00),
(6, 1, '2021-02-18', '2021-02-24', '09:00:00', '14:00:00', 35.00),
(6, 2, '2021-03-09', '2021-03-25', '09:00:00', '14:00:00', 85.00),
(7, 1, '2021-02-09', '2021-02-25', '10:00:00', '19:00:00', 16.77),
(7, 2, '2021-03-01', '2021-04-30', '10:00:00', '19:00:00', 60.12),
(8, 1, '2021-02-17', '2021-02-26', '08:00:00', '18:00:00', 9.86),
(8, 2, '2021-02-28', '2021-03-24', '08:00:00', '18:00:00', 24.66),
(9, 1, '2021-02-01', '2021-03-31', '08:00:00', '19:00:00', 58.96),
(10, 1, '2021-01-25', '2021-02-28', '09:00:00', '15:00:00', 175.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `USUARIOS`
--

DROP TABLE IF EXISTS `USUARIOS`;
CREATE TABLE IF NOT EXISTS `USUARIOS` (
  `LOGIN_USUARIO` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `PASSWD_USUARIO` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  `NOMBRE_USUARIO` varchar(60) COLLATE utf8_spanish_ci NOT NULL,
  `EMAIL_USUARIO` varchar(40) COLLATE utf8_spanish_ci NOT NULL,
  `TIPO_USUARIO` enum('NORMAL','ADMINISTRADOR','RESPONSABLE') COLLATE utf8_spanish_ci NOT NULL DEFAULT 'NORMAL',
  `ES_ACTIVO` enum('SI','NO') COLLATE utf8_spanish_ci NOT NULL DEFAULT 'SI' COMMENT 'ATRIBUTO PARA INDICAR SI EL USUARIO PUEDE LOGUEARSE O NO (BANEADO)',
  PRIMARY KEY (`LOGIN_USUARIO`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `USUARIOS`
--

INSERT INTO `USUARIOS` (`LOGIN_USUARIO`, `PASSWD_USUARIO`, `NOMBRE_USUARIO`, `EMAIL_USUARIO`, `TIPO_USUARIO`, `ES_ACTIVO`) VALUES
('_beltran_', 'b3da0bd68248b52687332e4d315e6974', 'Beltrán Martínez-Smith', 'beltranms@yahoo.es', 'NORMAL', 'SI'),
('admin', '21232f297a57a5a743894a0e4a801fc3', 'César Gabriel Márquez Rodríguez', 'cmrodriguez17@esei.uvigo.es', 'ADMINISTRADOR', 'SI'),
('cesarino', 'f9e2ede9ed5b31ffb5a9694ed3b02968', 'César Gabriel Márquez Rodríguez', 'cesar@mail.com', 'NORMAL', 'SI'),
('marta', 'a763a66f984948ca463b081bf0f0e6d0', 'Marta Rodríguez Fernández', 'marta.rodriguez.fernandez@gmail.com', 'NORMAL', 'SI'),
('otro_admin', 'e7d881995fbdc209b7cb041f9e1a44cd', 'Javier Rodeiro Iglesias', 'javi@gmail.com', 'ADMINISTRADOR', 'SI'),
('paco_simon', '3645eee297670eb35f413b4f097e6b80', 'Paco Simón Lorenzo', 'pacosl1998@hotmail.com', 'NORMAL', 'SI'),
('resp1', 'a1866c1e61653fd2a77033750c72c90c', 'Eliana Patricia Aray Cappello', 'eliana@mail.com', 'RESPONSABLE', 'SI'),
('resp2', 'bb1797702574859ad9bab93694ed779d', 'Iria Martínez Álvarez', 'iria@mail.com', 'RESPONSABLE', 'SI'),
('roberto17', '49fc712b673c8806e44b36d134fc55c4', 'Roberto Vázquez Alonso', 'rvazquez20@alumnos.uvigo.es', 'NORMAL', 'SI');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `RECURSOS`
--
ALTER TABLE `RECURSOS`
  ADD CONSTRAINT `RECURSOS_ibfk_1` FOREIGN KEY (`LOGIN_RESPONSABLE`) REFERENCES `RESPONSABLES_RECURSO` (`LOGIN_RESPONSABLE`),
  ADD CONSTRAINT `RECURSOS_ibfk_2` FOREIGN KEY (`ID_CALENDARIO`) REFERENCES `CALENDARIOS_DE_USO` (`ID_CALENDARIO`);

--
-- Filtros para la tabla `RESERVAS`
--
ALTER TABLE `RESERVAS`
  ADD CONSTRAINT `RESERVAS_ibfk_1` FOREIGN KEY (`LOGIN_USUARIO`) REFERENCES `USUARIOS` (`LOGIN_USUARIO`),
  ADD CONSTRAINT `RESERVAS_ibfk_2` FOREIGN KEY (`ID_RECURSO`) REFERENCES `RECURSOS` (`ID_RECURSO`);

--
-- Filtros para la tabla `RESPONSABLES_RECURSO`
--
ALTER TABLE `RESPONSABLES_RECURSO`
  ADD CONSTRAINT `RESPONSABLES_RECURSO_ibfk_1` FOREIGN KEY (`LOGIN_RESPONSABLE`) REFERENCES `USUARIOS` (`LOGIN_USUARIO`);

--
-- Filtros para la tabla `SUBRESERVAS`
--
ALTER TABLE `SUBRESERVAS`
  ADD CONSTRAINT `SUBRESERVAS_ibfk_1` FOREIGN KEY (`ID_RESERVA`) REFERENCES `RESERVAS` (`ID_RESERVA`);
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
