-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 10-11-2020 a las 11:55:36
-- Versión del servidor: 10.3.23-MariaDB-0+deb10u1
-- Versión de PHP: 7.3.19-1~deb10u1
SET
  SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

START TRANSACTION;

SET
  time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */
;

/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */
;

/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */
;

/*!40101 SET NAMES utf8mb4 */
;

--
-- Base de datos: `53196285E`
--
DROP DATABASE IF EXISTS `53196285E`;

CREATE DATABASE IF NOT EXISTS `53196285E` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci;

USE `53196285E`;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `USUARIOS`
--
CREATE TABLE `USUARIOS` (
  `LOGIN_USUARIO`   varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `PASSWD_USUARIO`  varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  `NOMBRE_USUARIO`  varchar(60) COLLATE utf8_spanish_ci NOT NULL,
  `EMAIL_USUARIO`   varchar(40) COLLATE utf8_spanish_ci NOT NULL,
  `TIPO_USUARIO`    enum('NORMAL', 'ADMINISTRADOR', 'RESPONSABLE') COLLATE utf8_spanish_ci NOT NULL DEFAULT 'NORMAL',
  `ES_ACTIVO`       enum('SI', 'NO') COLLATE utf8_spanish_ci NOT NULL DEFAULT 'SI' COMMENT 'ATRIBUTO PARA INDICAR SI EL USUARIO PUEDE LOGUEARSE O NO (BANEADO)',

  PRIMARY KEY(`LOGIN_USUARIO`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_spanish_ci;


-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `RESPONSABLES_RECURSO`
--
CREATE TABLE `RESPONSABLES_RECURSO` (
  `LOGIN_RESPONSABLE`     varchar(15) COLLATE utf8_spanish_ci NOT NULL COMMENT 'LOGIN EN TABLA USUARIO DEL RESPONSABLE',
  `DIRECCION_RESPONSABLE` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `TELEFONO_RESPONSABLE`  int(9) NOT NULL,

  PRIMARY KEY(`LOGIN_RESPONSABLE`),
  FOREIGN KEY(`LOGIN_RESPONSABLE`) REFERENCES `USUARIOS` (`LOGIN_USUARIO`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_spanish_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `CALENDARIOS_DE_USO`
--
CREATE TABLE `CALENDARIOS_DE_USO` (
  `ID_CALENDARIO`           int           NOT NULL AUTO_INCREMENT,
  `NOMBRE_CALENDARIO`       varchar(40)   COLLATE utf8_spanish_ci NOT NULL,
  `DESCRIPCION_CALENDARIO`  varchar(200)  COLLATE utf8_spanish_ci NOT NULL,
  `FECHA_INICIO_CALENDARIO` date          NOT NULL,
  `FECHA_FIN_CALENDARIO`    date          NOT NULL,
  `HORA_INICIO_CALENDARIO`  time          NOT NULL COMMENT 'HORA DE COMIENZO DE CUALQUIER DIA DEL CALENDARIO',
  `HORA_FIN_CALENDARIO`     time          NOT NULL COMMENT 'HORA DE FIN DE CUALQUIER DIA DEL CALENDARIO',
  `BORRADO_LOGICO`         enum('SI', 'NO') COLLATE utf8_spanish_ci NOT NULL COMMENT 'BORRADO LOGICO DE LA TUPLA',

  PRIMARY KEY(`ID_CALENDARIO`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_spanish_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `RECURSOS`
--
CREATE TABLE `RECURSOS` (
  `ID_RECURSO`             int           NOT NULL AUTO_INCREMENT,
  `NOMBRE_RECURSO`         varchar(40)   COLLATE utf8_spanish_ci NOT NULL,
  `DESCRIPCION_RECURSO`    varchar(200)  COLLATE utf8_spanish_ci NOT NULL,
  `TARIFA_RECURSO`         int(3)        NOT NULL COMMENT 'VALOR MONETARIO DE TARIFICACIÓN POR RANGO',
  `RANGO_TARIFA_RECURSO`   enum('HORA', 'DIA', 'SEMANA', 'MES') COLLATE utf8_spanish_ci NOT NULL COMMENT 'RANGO DE APLICACIÓN DE LA TARIFA',
  `ID_CALENDARIO`          int          NOT NULL COMMENT 'CALENDARIO ASOCIADO AL RECURSO',
  `LOGIN_RESPONSABLE`      varchar(15)   COLLATE utf8_spanish_ci NOT NULL,
  `BORRADO_LOGICO`         enum('SI', 'NO') COLLATE utf8_spanish_ci NOT NULL COMMENT 'BORRADO LOGICO DE LA TUPLA EN EL CASO DE QUE EXISTA INFORMACIÓN EN LA BD DE ESTE RECURSO',

  PRIMARY KEY(`ID_RECURSO`),
  FOREIGN KEY(`LOGIN_RESPONSABLE`) REFERENCES `RESPONSABLES_RECURSO` (`LOGIN_RESPONSABLE`),
  FOREIGN KEY(`ID_CALENDARIO`) REFERENCES `CALENDARIOS_DE_USO` (`ID_CALENDARIO`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_spanish_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `RESERVAS`
--
CREATE TABLE `RESERVAS` (
  `ID_RESERVA`                 int    NOT NULL AUTO_INCREMENT,

  `LOGIN_USUARIO`              varchar(15) COLLATE utf8_spanish_ci NOT NULL COMMENT 'PERSONA QUE HACE LA PETICION DE USO DEL RECURSO',
  `ID_RECURSO`                 int    NOT NULL,
  
  `FECHA_SOLICITUD_RESERVA`    date NOT NULL COMMENT 'FECHA EN QUE SE SOLICITA EL RECURSO',
  `FECHA_RESPUESTA_RESERVA`    date NULL COMMENT 'FECHA EN QUE SE AUTORIZA/RECHAZA LA RESERVA POR EL RESPONSABLE',

  `MOTIVO_RECHAZO_RESERVA`     varchar(200) NULL COMMENT 'MOTIVO POR EL CUAL LA RESERVA ES RECHAZADA',

  `ESTADO_RESERVA`             enum(
                                 'PENDIENTE',
                                 'ACEPTADA',
                                 'RECHAZADA',
                                 'CANCELADA',
                                 'RECURSO_USADO',
                                 'RECURSO_NO_USADO'
                               ) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'PENDIENTE',
 
  `COSTE_RESERVA`               double(6, 2) NOT NULL,

  PRIMARY KEY(`ID_RESERVA`),
  FOREIGN KEY(`LOGIN_USUARIO`) REFERENCES `USUARIOS` (`LOGIN_USUARIO`),
  FOREIGN KEY(`ID_RECURSO`) REFERENCES `RECURSOS` (`ID_RECURSO`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_spanish_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `RESERVAS`
--
CREATE TABLE `SUBRESERVAS` (
  
  `ID_RESERVA`              int  NOT NULL, 
  `ID_SUBRESERVA`           int  NOT NULL,

  `FECHA_INICIO_SUBRESERVA` date NOT NULL COMMENT 'FECHA EN LA QUE COMIENZA LA RESERVA',
  `FECHA_FIN_SUBRESERVA`    date NOT NULL COMMENT 'FECHA EN LA QUE TERMINA LA RESERVA',
  `HORA_INICIO_SUBRESERVA`  time NOT NULL COMMENT 'HORA DE COMIENZO DE LA RESERVA',
  `HORA_FIN_SUBRESERVA`     time NOT NULL COMMENT 'HORA DE FIN DE LA RESERVA',

  `COSTE_SUBRESERVA` double(6, 2) NOT NULL,

   PRIMARY KEY(`ID_RESERVA`,`ID_SUBRESERVA`),
   FOREIGN KEY(`ID_RESERVA`) REFERENCES `RESERVAS` (`ID_RESERVA`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_spanish_ci;

/* SENTENCIAS DE INSERCIÓN DE DATOS */

-- Para tabla 'USUARIOS'
INSERT INTO `USUARIOS` (`LOGIN_USUARIO`, `PASSWD_USUARIO`, `NOMBRE_USUARIO`, `EMAIL_USUARIO`, `TIPO_USUARIO`, `ES_ACTIVO`) VALUES
('admin', '21232f297a57a5a743894a0e4a801fc3', 'César Gabriel Márquez Rodríguez', 'cmrodriguez17@esei.uvigo.es', 'ADMINISTRADOR', 'SI'),
('resp1', 'a1866c1e61653fd2a77033750c72c90c', 'Eliana Patricia Aray Cappello', 'eacappello17@esei.uvigo.es', 'RESPONSABLE', 'SI'),
('resp2', 'bb1797702574859ad9bab93694ed779d', 'Iria Martínez Álvarez', 'imalvarez17@esei.uvigo.es', 'RESPONSABLE', 'SI'),
('resp3', '6ae899e50b6df45e52866e3ac8c2ba65', 'Samuel Jesús Márquez Rodríguez', 'sjmarquez20@esei.uvigo.es', 'RESPONSABLE', 'SI'),
('emmolina15', 'e8820d56d6d910a7b39e48e3e1cef30d', 'Edgard Orlando Márquez Molina', 'emmolina15@esei.uvigo.es', 'NORMAL', 'SI');

-- Para tabla 'RESPONSABLES_RECURSO'
INSERT INTO `RESPONSABLES_RECURSO` (`LOGIN_RESPONSABLE`, `DIRECCION_RESPONSABLE`, `TELEFONO_RESPONSABLE`) VALUES
('resp1', 'Avda Buenos Aires 234 1ºB, 32004 Ourense', '666555111'),
('resp2', 'Avda Buenos Aires 156 2ºC, 32004 Ourense', '666333444');

-- Para tabla 'CALENDARIOS_DE_USO'
INSERT INTO `CALENDARIOS_DE_USO` (`NOMBRE_CALENDARIO`, `DESCRIPCION_CALENDARIO`, `FECHA_INICIO_CALENDARIO`, `FECHA_FIN_CALENDARIO`, `HORA_INICIO_CALENDARIO`, `HORA_FIN_CALENDARIO`, `BORRADO_LOGICO`) VALUES
('Calendario de invierno 20/21', 'Este es el calendario de invierno, que va desde el 21 de diciembre hasta el 20 de marzo. Horario: 9:00 a 21:00.', '2020-12-21', '2021-03-20', '09:00:00', '21:00:00', 'NO'),
('Calendario de primavera 20/21', 'Este es el calendario de primavera, que va desde el 21 de marzo hasta el 20 de junio. Horario: 10:00 a 21:00.', '2021-03-21', '2021-06-20', '10:00:00', '21:00:00', 'NO'),
('Calendario de verano 20/21', 'Este es el calendario de verano, que va desde el 21 de junio hasta el 20 de septiembre. Horario: 9:00 a 21:00.', '2021-06-21', '2021-09-20', '10:00:00', '14:00:00', 'NO'),
('Calendario de otoño 20/21', 'Este es el calendario de otoño, que va desde el 21 de septiembre hasta el 20 de diciembre. Horario: 9:00 a 22:00.', '2021-09-21', '2021-12-21', '09:00:00', '22:00:00', 'NO');

-- Para tabla 'RECURSOS'
INSERT INTO `RECURSOS` (`NOMBRE_RECURSO`, `DESCRIPCION_RECURSO`, `TARIFA_RECURSO`, `RANGO_TARIFA_RECURSO`, `ID_CALENDARIO`, `LOGIN_RESPONSABLE`, `BORRADO_LOGICO`) VALUES
('Lenovo IdeaPad 3', 'Ordenador Portátil 15.6\" FullHD, Intel Core i5-1035G1, 8 GB RAM, 512 GB SSD', '10', 'SEMANA', '1', 'resp1', 'NO'),
('HP 15s-fq1089ns', 'Ordenador portátil de 16.4\" FullHD, Intel Core i7-1035G1, 16GB RAM, 1TBHDD + 128SSD', '2', 'DIA', '2', 'resp1', 'NO'),
('Seminario 52', 'Seminario 52 localizado en planta 5.', '1', 'HORA', '3', 'resp1', 'NO'),
('Seminario 57', 'Seminario 57 localizado en planta 5.', '1', 'HORA', '2', 'resp2', 'NO'),
('Proyector Epson', 'Epson EH-TW610, Full HD, 3000 Lúmenes, Hasta 300 pulgadas, Blanco', '50', 'MES', '1', 'resp2', 'NO'),
('Monitor BenQ GW2780E', '27\" LED IPS Eye-Care', '3', 'DIA', '4', 'resp2', 'NO');

-- Para tabla 'RESERVAS'
INSERT INTO `RESERVAS` (`LOGIN_USUARIO`, `ID_RECURSO`, `FECHA_SOLICITUD_RESERVA`, `FECHA_RESPUESTA_RESERVA`, `MOTIVO_RECHAZO_RESERVA`, `ESTADO_RESERVA`, `COSTE_RESERVA`) VALUES
('emmolina15', '1', '2020-11-14', '2020-11-20', NULL, 'ACEPTADA', '20'),
('emmolina15', '5', '2020-11-15', NULL, NULL, 'PENDIENTE', '100'),
('emmolina15', '5', '2020-11-15', NULL, NULL, 'PENDIENTE', '100'),
('emmolina15', '5', '2020-11-15', NULL, NULL, 'PENDIENTE', '100'),
('emmolina15', '5', '2020-11-15', NULL, NULL, 'PENDIENTE', '100'),
('emmolina15', '5', '2020-11-15', NULL, NULL, 'PENDIENTE', '100'),
('emmolina15', '5', '2020-11-15', NULL, NULL, 'PENDIENTE', '100'),
('emmolina15', '5', '2020-11-15', NULL, NULL, 'PENDIENTE', '100');

-- Para tabla 'SUBRESERVAS'
INSERT INTO `SUBRESERVAS` (`ID_RESERVA`, `ID_SUBRESERVA`, `FECHA_INICIO_SUBRESERVA`, `FECHA_FIN_SUBRESERVA`, `HORA_INICIO_SUBRESERVA`, `HORA_FIN_SUBRESERVA`, `COSTE_SUBRESERVA`) VALUES
('1', '1', STR_TO_DATE('01/01/2021','%d/%m/%Y'),  STR_TO_DATE('08/01/2021','%d/%m/%Y'), '15:00', '20:00', '10'),
('1', '2', STR_TO_DATE('14/01/2021','%d/%m/%Y'),  STR_TO_DATE('21/01/2021','%d/%m/%Y'), '15:00', '20:00', '10'),
('2', '1', STR_TO_DATE('05/01/2021','%d/%m/%Y'),  STR_TO_DATE('05/02/2021','%d/%m/%Y'), '10:00', '13:00', '50'),
('2', '2', STR_TO_DATE('10/02/2021','%d/%m/%Y'),  STR_TO_DATE('10/03/2021','%d/%m/%Y'), '10:00', '13:00', '50'),
('3', '1', STR_TO_DATE('05/01/2021','%d/%m/%Y'),  STR_TO_DATE('05/02/2021','%d/%m/%Y'), '10:00', '13:00', '50'),
('3', '2', STR_TO_DATE('10/02/2021','%d/%m/%Y'),  STR_TO_DATE('10/03/2021','%d/%m/%Y'), '10:00', '13:00', '50'),
('4', '1', STR_TO_DATE('05/01/2021','%d/%m/%Y'),  STR_TO_DATE('05/02/2021','%d/%m/%Y'), '10:00', '13:00', '50'),
('4', '2', STR_TO_DATE('10/02/2021','%d/%m/%Y'),  STR_TO_DATE('10/03/2021','%d/%m/%Y'), '10:00', '13:00', '50'),
('5', '1', STR_TO_DATE('05/01/2021','%d/%m/%Y'),  STR_TO_DATE('05/02/2021','%d/%m/%Y'), '15:00', '17:00', '50'),
('5', '2', STR_TO_DATE('10/02/2021','%d/%m/%Y'),  STR_TO_DATE('10/03/2021','%d/%m/%Y'), '15:00', '17:00', '50'),
('6', '1', STR_TO_DATE('05/01/2021','%d/%m/%Y'),  STR_TO_DATE('05/02/2021','%d/%m/%Y'), '15:00', '17:00', '50'),
('6', '2', STR_TO_DATE('10/02/2021','%d/%m/%Y'),  STR_TO_DATE('10/03/2021','%d/%m/%Y'), '15:00', '17:00', '50'),
('7', '1', STR_TO_DATE('05/01/2021','%d/%m/%Y'),  STR_TO_DATE('05/02/2021','%d/%m/%Y'), '15:00', '17:00', '50'),
('7', '2', STR_TO_DATE('10/02/2021','%d/%m/%Y'),  STR_TO_DATE('10/03/2021','%d/%m/%Y'), '15:00', '17:00', '50'),
('8', '1', STR_TO_DATE('05/01/2021','%d/%m/%Y'),  STR_TO_DATE('05/02/2021','%d/%m/%Y'), '15:00', '17:00', '50'),
('8', '2', STR_TO_DATE('10/02/2021','%d/%m/%Y'),  STR_TO_DATE('10/03/2021','%d/%m/%Y'), '15:00', '17:00', '50');

grant all privileges on 53196285E.* to pma@localhost identified by "iu";

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */
;

/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */
;

/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */
;