--
-- Estructura de tabla para la tabla `param_vistos_reincorporacion`
--

CREATE TABLE `param_vistos_reincorporacion` (
  `vrei_id` int(11) NOT NULL,
  `vrei_descripcion` varchar(255) NOT NULL,
  `vrei_creador` int(11) NOT NULL,
  `vrei_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `vrei_modificador` int(11) NOT NULL DEFAULT '0',
  `vrei_modificacion` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `param_vistos_reincorporacion`
  ADD PRIMARY KEY (`vrei_id`);

ALTER TABLE `param_vistos_reincorporacion`
  MODIFY `vrei_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Volcado de datos para la tabla `param_vistos_reincorporacion`
--

INSERT INTO `param_vistos_reincorporacion` (`vrei_id`, `vrei_descripcion`, `vrei_creador`, `vrei_creacion`, `vrei_modificador`, `vrei_modificacion`) VALUES
(1, '1.- El mail de Oriana Solange Martinez, solicitando la reincorporación de los bienes a la casa de Adulto mayor, edificio Las Raices, ya que aún presentan una utilidad y pueden ser reparados.', 1, '2017-08-17 02:14:58', 0, '2017-11-24 21:47:57'),
(2, '2.- El Decreto N° 577 del 1978 del Ministerio de Bienes Nacionales.', 1, '2017-08-17 02:16:34', 0, '2017-08-16 23:41:09'),
(3, '3.- Las facultades que me confiere la Ley N° 18.695 Orgánica Constitucional de Municipalidades.', 1, '2017-08-17 02:21:58', 0, '2017-08-16 23:41:24');


--
-- Estructura de tabla para la tabla `param_decreto_reincorporacion`
--

CREATE TABLE `param_decreto_reincorporacion` (
  `derei_id` int(11) NOT NULL,
  `derei_nombreOrg` varchar(60) NOT NULL,
  `derei_nombreDireccion` varchar(100) DEFAULT NULL,
  `derei_nombreSecre` varchar(60) NOT NULL,
  `derei_porOrdenFirma1` varchar(60) NOT NULL,
  `derei_cargoFirma1` varchar(60) NOT NULL,
  `derei_nombreAlcalde` varchar(60) NOT NULL,
  `derei_porOrdenFirma2` varchar(60) NOT NULL,
  `derei_cargoFirma2` varchar(60) NOT NULL,
  `derei_iniciales` varchar(30) NOT NULL,
  `derei_rutaLogo` text NOT NULL,
  `derei_creador` int(11) NOT NULL,
  `derei_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `derei_modificador` int(11) NOT NULL DEFAULT '0',
  `derei_modificacion` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `param_decreto_reincorporacion`
  ADD PRIMARY KEY (`derei_id`);

ALTER TABLE `param_decreto_reincorporacion`
  MODIFY `derei_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Volcado de datos para la tabla `param_decreto_reincorporacion`
--

INSERT INTO `param_decreto_reincorporacion` (`derei_id`, `derei_nombreOrg`, `derei_nombreDireccion`, `derei_nombreSecre`, `derei_porOrdenFirma1`, `derei_cargoFirma1`, `derei_nombreAlcalde`, `derei_porOrdenFirma2`, `derei_cargoFirma2`, `derei_iniciales`, `derei_rutaLogo`, `derei_creador`, `derei_creacion`, `derei_modificador`, `derei_modificacion`) VALUES
(1, 'TEMUCO', 'no requerido', 'MAURICIO REYES JIMENEZ', '', '', 'MIGUEL BECKER ALVEAR', '', '', '', 'imagenes_logo/TEMUCO.png', 1, '2017-08-16 20:51:26', 0, '2017-11-24 22:17:15');





--
-- Estructura de tabla para la tabla `param_distri_reincorporacion`
--

CREATE TABLE `param_distri_reincorporacion` (
  `drei_id` int(11) NOT NULL,
  `drei_descripcion` varchar(60) NOT NULL,
  `drei_creador` int(11) NOT NULL,
  `drei_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `drei_modificador` int(11) NOT NULL DEFAULT '0',
  `drei_modificacion` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `param_distri_reincorporacion`
  ADD PRIMARY KEY (`drei_id`);

ALTER TABLE `param_distri_reincorporacion`
  MODIFY `drei_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- Volcado de datos para la tabla `param_distri_reincorporacion`
--

INSERT INTO `param_distri_reincorporacion` (`drei_id`, `drei_descripcion`, `drei_creador`, `drei_creacion`, `drei_modificador`, `drei_modificacion`) VALUES
(1, 'Recinto Municipal', 1, '2017-08-17 02:31:53', 0, '0000-00-00 00:00:00'),
(2, 'Depto. Abastecimiento', 1, '2017-08-17 02:32:16', 0, '2017-10-30 22:58:58'),
(3, 'Unidad de Inventario', 1, '2017-08-17 02:32:38', 0, '0000-00-00 00:00:00'),
(4, 'Administración y Finanzas', 1, '2017-08-17 02:33:16', 0, '0000-00-00 00:00:00'),
(5, 'Gestión Interna', 1, '2017-08-17 02:33:55', 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `decretos_reincorporacion`
--

CREATE TABLE `decretos_reincorporacion` (
  `derei_id` int(11) NOT NULL,
  `derei_folio` int(11) UNSIGNED ZEROFILL NOT NULL,
  `act_id` int(11) NOT NULL,
  `derei_creador` int(11) NOT NULL,
  `derei_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `derei_modificador` int(11) NOT NULL DEFAULT '0',
  `derei_modificacion` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `decretos_reincorporacion`
  ADD PRIMARY KEY (`derei_id`);

ALTER TABLE `decretos_reincorporacion`
  MODIFY `derei_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
