-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-03-2025 a las 03:11:33
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `battle_royale`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `armas`
--

CREATE TABLE `armas` (
  `id_arma` int(11) NOT NULL,
  `nom_arma` varchar(255) NOT NULL,
  `balas` int(11) NOT NULL,
  `img` varchar(255) DEFAULT NULL,
  `id_tipo_arma` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `armas`
--

INSERT INTO `armas` (`id_arma`, `nom_arma`, `balas`, `img`, `id_tipo_arma`) VALUES
(1, 'Navaja Tactica', 0, '../img/armas/67c0c19a4831b_Navaja-Tactica.jpg', 1),
(2, 'Classic', 10, '../img/armas/67c0c1d87b959_Classic.jpg', 2),
(3, 'Frenzy', 15, '../img/armas/67c0c1f495758_Frenzy.jpg', 2),
(4, 'Shorty', 8, '../img/armas/67c0c20a06fad_Shorty.jpg', 2),
(5, 'Spectre', 30, '../img/armas/67c0c242c268f_Spectre.jpg', 3),
(6, 'Phantom', 35, '../img/armas/67c0c271aef38_Phantom.jpg', 3),
(7, 'Guardian', 30, '../img/armas/67c0c28b555d3_Guardian.jpg', 3),
(8, 'Outlaw', 6, '../img/armas/67c0c2ab0ff0c_Outlaw.jpg', 4),
(9, 'Marshal', 5, '../img/armas/67c0c2bb102b7_Marshal.jpg', 4),
(10, 'Operator', 8, '../img/armas/67c0c2c9741fd_Operator.jpg', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `avatar`
--

CREATE TABLE `avatar` (
  `id_avatar` int(11) NOT NULL,
  `nom_avatar` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `avatar`
--

INSERT INTO `avatar` (`id_avatar`, `nom_avatar`, `img`) VALUES
(1, 'Chamber', '../img/avatars/67c0c3cc540ae_Chamber.png'),
(2, 'Cypher', '../img/avatars/67c0c3e60ffc9_Cypher.png'),
(3, 'Phoenix', '../img/avatars/67c0c40b2ae7c_Phoenix.png'),
(4, 'Sage', '../img/avatars/67c0c41295bcf_Sage.png'),
(5, 'Viper', '../img/avatars/67c0c41997921_Viper.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estadisticas_juego`
--

CREATE TABLE `estadisticas_juego` (
  `id` int(11) NOT NULL,
  `id_jugador` int(11) NOT NULL,
  `juegos_jugados` int(11) NOT NULL DEFAULT 0,
  `juegos_ganados` int(11) NOT NULL DEFAULT 0,
  `puntos_totales` int(11) NOT NULL DEFAULT 0,
  `muertes_totales` int(11) NOT NULL DEFAULT 0,
  `dano_total` int(11) NOT NULL DEFAULT 0,
  `disparos_cabeza_totales` int(11) NOT NULL DEFAULT 0,
  `ultima_partida` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados`
--

CREATE TABLE `estados` (
  `id_estado` int(11) NOT NULL,
  `nom_estado` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estados`
--

INSERT INTO `estados` (`id_estado`, `nom_estado`) VALUES
(1, 'activado'),
(2, 'desactivado'),
(3, 'inactivo'),
(4, 'en espera'),
(5, 'en juego'),
(6, 'finalizada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jugadores_armas`
--

CREATE TABLE `jugadores_armas` (
  `id` int(11) NOT NULL,
  `id_jugador` int(11) NOT NULL,
  `id_arma` int(11) NOT NULL,
  `equipada_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `jugadores_armas`
--

INSERT INTO `jugadores_armas` (`id`, `id_jugador`, `id_arma`, `equipada_en`) VALUES
(11, 10000005, 1, '2025-02-27 23:05:29'),
(12, 10000005, 2, '2025-02-27 23:05:29'),
(13, 10000004, 2, '2025-02-27 23:26:16'),
(14, 10000004, 3, '2025-02-27 23:26:16'),
(15, 10000006, 1, '2025-02-28 04:04:00'),
(16, 10000006, 2, '2025-02-28 04:04:00'),
(17, 10000007, 1, '2025-02-28 04:38:40'),
(18, 10000007, 2, '2025-02-28 04:38:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jugadores_salas`
--

CREATE TABLE `jugadores_salas` (
  `id` int(11) NOT NULL,
  `id_jugador` int(11) NOT NULL,
  `id_sala` int(11) NOT NULL,
  `hora_entrada` timestamp NOT NULL DEFAULT current_timestamp(),
  `hora_salida` timestamp NOT NULL DEFAULT (current_timestamp() + interval 10 minute),
  `id_estado_sala` int(11) NOT NULL,
  `listo` tinyint(1) DEFAULT 0,
  `vida` int(11) DEFAULT 100
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mundos`
--

CREATE TABLE `mundos` (
  `id_mundo` int(11) NOT NULL,
  `nom_mundo` varchar(255) NOT NULL,
  `max_jugadores` int(11) NOT NULL,
  `img` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mundos`
--

INSERT INTO `mundos` (`id_mundo`, `nom_mundo`, `max_jugadores`, `img`) VALUES
(1, 'Breeze', 5, '../img/mundos/67c0c6f16cf66_Breeze_loading_screen.jpg'),
(2, 'Abyss', 5, '../img/mundos/67c0c708b138c_Loading_Screen_Abyss.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `niveles`
--

CREATE TABLE `niveles` (
  `id_nivel` int(11) NOT NULL,
  `nom_nivel` varchar(255) NOT NULL,
  `puntos_necesarios` int(11) NOT NULL,
  `img` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `niveles`
--

INSERT INTO `niveles` (`id_nivel`, `nom_nivel`, `puntos_necesarios`, `img`) VALUES
(1, 'Plata', 0, '../img/niveles/67c0c73b719f4_silver.png'),
(2, 'Oro', 500, '../img/niveles/67c0c76017692_Gold.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `partidas_eventos`
--

CREATE TABLE `partidas_eventos` (
  `id` int(11) NOT NULL,
  `id_jugador` int(11) NOT NULL,
  `id_jugador_sala` int(11) NOT NULL,
  `id_tipo_evento` int(11) NOT NULL,
  `puntos` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `puntos`
--

CREATE TABLE `puntos` (
  `id_puntos` int(11) NOT NULL,
  `id_doc` int(11) NOT NULL,
  `puntos` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recuperacion_contrasena`
--

CREATE TABLE `recuperacion_contrasena` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `token_recuperacion` varchar(255) NOT NULL,
  `token_creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `token_expiracion` timestamp NOT NULL DEFAULT (current_timestamp() + interval 30 minute),
  `esta_usado` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `nom_rol` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `nom_rol`) VALUES
(1, 'admin'),
(2, 'jugador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salas`
--

CREATE TABLE `salas` (
  `id_sala` int(11) NOT NULL,
  `nom_sala` varchar(255) NOT NULL,
  `jugadores_actuales` int(11) NOT NULL,
  `id_mundo` int(11) NOT NULL,
  `id_nivel` int(11) NOT NULL,
  `max_jugadores` int(11) NOT NULL,
  `id_estado_sala` int(11) NOT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `duracion_segundos` int(11) NOT NULL,
  `id_ganador` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_armas`
--

CREATE TABLE `tipos_armas` (
  `id_tip_arma` int(11) NOT NULL,
  `nom_tip_arma` varchar(255) NOT NULL,
  `dano` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipos_armas`
--

INSERT INTO `tipos_armas` (`id_tip_arma`, `nom_tip_arma`, `dano`) VALUES
(1, 'cuerpo a cuerpo', 1),
(2, 'pistola', 2),
(3, 'subfusil', 10),
(4, 'francotirador', 20);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_eventos`
--

CREATE TABLE `tipos_eventos` (
  `id_tip_evento` int(11) NOT NULL,
  `nom_tip_evento` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipos_eventos`
--

INSERT INTO `tipos_eventos` (`id_tip_evento`, `nom_tip_evento`) VALUES
(1, 'disparo'),
(2, 'kill'),
(3, 'muerte');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `doc` int(11) NOT NULL,
  `nom_usu` varchar(255) NOT NULL,
  `contra` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `id_avatar` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `id_estado` int(11) NOT NULL,
  `ultima_sesion` timestamp NOT NULL DEFAULT current_timestamp(),
  `create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `update_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`doc`, `nom_usu`, `contra`, `email`, `id_avatar`, `id_rol`, `id_estado`, `ultima_sesion`, `create_at`, `update_at`) VALUES
(10000003, 'Jeannn', '$2y$12$COVahk1WpwNeHm9qhyHRde742CHBagEwnVbZBGLqBrXfXimHlElc2', 'stebanrc21@gmail.com', 1, 1, 1, '2025-02-26 20:39:20', '2025-02-26 20:39:20', '2025-02-27 19:44:03'),
(10000004, 'JeannCelPhoneE', '$2y$12$3V6Vf38DrJJ3pr/ZBFIzkejGuMIEtl49x7CcKdX7..ZVFJvP5g91G', 'jeannfacts@gmail.com', 4, 2, 1, '2025-02-26 20:40:03', '2025-02-26 20:40:03', '2025-02-27 23:26:04'),
(10000005, 'Samsung', '$2y$12$cSQpLp1MED4/ml5w6LefXeSzJ.57h.fHjXMWWIuK3I4AsLQSx3JWK', 'samsung@gmail.com', 3, 2, 1, '2025-02-27 23:05:29', '2025-02-27 23:05:29', '2025-02-27 23:07:07'),
(10000006, 'prueba', '$2y$12$CUb4MRFsgls2JGvzS/pcROMHT5mWlYOv392z.wqL6fCV5GhhUTylm', 'prueba@gmail.com', 3, 2, 1, '2025-02-28 04:04:00', '2025-02-28 04:04:00', '2025-03-05 21:16:36'),
(10000007, 'Pureba22', '$2y$12$bj4RqfLrqg0Lm5SxaZJrVOJVI6nLem/HZHmUmv3ffVzlDOlb8uNIq', 'prueba22@gmail.com', 3, 2, 2, '2025-02-28 04:38:40', '2025-02-28 04:38:40', '2025-02-28 04:38:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_mundos`
--

CREATE TABLE `usuarios_mundos` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_mundo` int(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_niveles`
--

CREATE TABLE `usuarios_niveles` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_nivel` int(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios_niveles`
--

INSERT INTO `usuarios_niveles` (`id`, `id_usuario`, `id_nivel`, `fecha`) VALUES
(1, 10000004, 1, '2025-02-27 20:59:24'),
(2, 10000005, 1, '2025-02-27 23:05:29'),
(3, 10000006, 1, '2025-02-28 04:04:00'),
(4, 10000007, 1, '2025-02-28 04:38:40');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `armas`
--
ALTER TABLE `armas`
  ADD PRIMARY KEY (`id_arma`),
  ADD KEY `id_tipo_arma` (`id_tipo_arma`);

--
-- Indices de la tabla `avatar`
--
ALTER TABLE `avatar`
  ADD PRIMARY KEY (`id_avatar`);

--
-- Indices de la tabla `estadisticas_juego`
--
ALTER TABLE `estadisticas_juego`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_jugador` (`id_jugador`);

--
-- Indices de la tabla `estados`
--
ALTER TABLE `estados`
  ADD PRIMARY KEY (`id_estado`);

--
-- Indices de la tabla `jugadores_armas`
--
ALTER TABLE `jugadores_armas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_jugador` (`id_jugador`),
  ADD KEY `id_arma` (`id_arma`);

--
-- Indices de la tabla `jugadores_salas`
--
ALTER TABLE `jugadores_salas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_jugador` (`id_jugador`),
  ADD KEY `id_sala` (`id_sala`),
  ADD KEY `id_estado_sala` (`id_estado_sala`);

--
-- Indices de la tabla `mundos`
--
ALTER TABLE `mundos`
  ADD PRIMARY KEY (`id_mundo`);

--
-- Indices de la tabla `niveles`
--
ALTER TABLE `niveles`
  ADD PRIMARY KEY (`id_nivel`);

--
-- Indices de la tabla `partidas_eventos`
--
ALTER TABLE `partidas_eventos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_jugador` (`id_jugador`),
  ADD KEY `id_jugador_sala` (`id_jugador_sala`),
  ADD KEY `id_tipo_evento` (`id_tipo_evento`);

--
-- Indices de la tabla `puntos`
--
ALTER TABLE `puntos`
  ADD PRIMARY KEY (`id_puntos`),
  ADD KEY `id_doc` (`id_doc`);

--
-- Indices de la tabla `recuperacion_contrasena`
--
ALTER TABLE `recuperacion_contrasena`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token_recuperacion` (`token_recuperacion`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `salas`
--
ALTER TABLE `salas`
  ADD PRIMARY KEY (`id_sala`),
  ADD KEY `id_mundo` (`id_mundo`),
  ADD KEY `id_nivel` (`id_nivel`),
  ADD KEY `id_estado_sala` (`id_estado_sala`),
  ADD KEY `id_ganador` (`id_ganador`);

--
-- Indices de la tabla `tipos_armas`
--
ALTER TABLE `tipos_armas`
  ADD PRIMARY KEY (`id_tip_arma`);

--
-- Indices de la tabla `tipos_eventos`
--
ALTER TABLE `tipos_eventos`
  ADD PRIMARY KEY (`id_tip_evento`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`doc`),
  ADD UNIQUE KEY `nom_usu` (`nom_usu`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_rol` (`id_rol`),
  ADD KEY `id_estado` (`id_estado`),
  ADD KEY `usuarios_ibfk_3` (`id_avatar`);

--
-- Indices de la tabla `usuarios_mundos`
--
ALTER TABLE `usuarios_mundos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_mundo` (`id_mundo`);

--
-- Indices de la tabla `usuarios_niveles`
--
ALTER TABLE `usuarios_niveles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_nivel` (`id_nivel`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `armas`
--
ALTER TABLE `armas`
  MODIFY `id_arma` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `avatar`
--
ALTER TABLE `avatar`
  MODIFY `id_avatar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `estadisticas_juego`
--
ALTER TABLE `estadisticas_juego`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `estados`
--
ALTER TABLE `estados`
  MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `jugadores_armas`
--
ALTER TABLE `jugadores_armas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `jugadores_salas`
--
ALTER TABLE `jugadores_salas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT de la tabla `mundos`
--
ALTER TABLE `mundos`
  MODIFY `id_mundo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `niveles`
--
ALTER TABLE `niveles`
  MODIFY `id_nivel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `partidas_eventos`
--
ALTER TABLE `partidas_eventos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1214;

--
-- AUTO_INCREMENT de la tabla `puntos`
--
ALTER TABLE `puntos`
  MODIFY `id_puntos` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `recuperacion_contrasena`
--
ALTER TABLE `recuperacion_contrasena`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `salas`
--
ALTER TABLE `salas`
  MODIFY `id_sala` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `tipos_armas`
--
ALTER TABLE `tipos_armas`
  MODIFY `id_tip_arma` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tipos_eventos`
--
ALTER TABLE `tipos_eventos`
  MODIFY `id_tip_evento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `doc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10000008;

--
-- AUTO_INCREMENT de la tabla `usuarios_mundos`
--
ALTER TABLE `usuarios_mundos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios_niveles`
--
ALTER TABLE `usuarios_niveles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `armas`
--
ALTER TABLE `armas`
  ADD CONSTRAINT `armas_ibfk_1` FOREIGN KEY (`id_tipo_arma`) REFERENCES `tipos_armas` (`id_tip_arma`);

--
-- Filtros para la tabla `estadisticas_juego`
--
ALTER TABLE `estadisticas_juego`
  ADD CONSTRAINT `estadisticas_juego_ibfk_1` FOREIGN KEY (`id_jugador`) REFERENCES `usuarios` (`doc`);

--
-- Filtros para la tabla `jugadores_armas`
--
ALTER TABLE `jugadores_armas`
  ADD CONSTRAINT `jugadores_armas_ibfk_1` FOREIGN KEY (`id_jugador`) REFERENCES `usuarios` (`doc`),
  ADD CONSTRAINT `jugadores_armas_ibfk_2` FOREIGN KEY (`id_arma`) REFERENCES `armas` (`id_arma`);

--
-- Filtros para la tabla `jugadores_salas`
--
ALTER TABLE `jugadores_salas`
  ADD CONSTRAINT `jugadores_salas_ibfk_1` FOREIGN KEY (`id_jugador`) REFERENCES `usuarios` (`doc`),
  ADD CONSTRAINT `jugadores_salas_ibfk_2` FOREIGN KEY (`id_sala`) REFERENCES `salas` (`id_sala`),
  ADD CONSTRAINT `jugadores_salas_ibfk_3` FOREIGN KEY (`id_estado_sala`) REFERENCES `estados` (`id_estado`);

--
-- Filtros para la tabla `partidas_eventos`
--
ALTER TABLE `partidas_eventos`
  ADD CONSTRAINT `partidas_eventos_ibfk_1` FOREIGN KEY (`id_jugador`) REFERENCES `usuarios` (`doc`),
  ADD CONSTRAINT `partidas_eventos_ibfk_2` FOREIGN KEY (`id_jugador_sala`) REFERENCES `jugadores_salas` (`id`),
  ADD CONSTRAINT `partidas_eventos_ibfk_3` FOREIGN KEY (`id_tipo_evento`) REFERENCES `tipos_eventos` (`id_tip_evento`);

--
-- Filtros para la tabla `puntos`
--
ALTER TABLE `puntos`
  ADD CONSTRAINT `puntos_ibfk_1` FOREIGN KEY (`id_doc`) REFERENCES `usuarios` (`doc`);

--
-- Filtros para la tabla `recuperacion_contrasena`
--
ALTER TABLE `recuperacion_contrasena`
  ADD CONSTRAINT `recuperacion_contrasena_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`doc`);

--
-- Filtros para la tabla `salas`
--
ALTER TABLE `salas`
  ADD CONSTRAINT `salas_ibfk_1` FOREIGN KEY (`id_mundo`) REFERENCES `mundos` (`id_mundo`),
  ADD CONSTRAINT `salas_ibfk_2` FOREIGN KEY (`id_nivel`) REFERENCES `niveles` (`id_nivel`),
  ADD CONSTRAINT `salas_ibfk_3` FOREIGN KEY (`id_estado_sala`) REFERENCES `estados` (`id_estado`),
  ADD CONSTRAINT `salas_ibfk_4` FOREIGN KEY (`id_ganador`) REFERENCES `usuarios` (`doc`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`),
  ADD CONSTRAINT `usuarios_ibfk_2` FOREIGN KEY (`id_estado`) REFERENCES `estados` (`id_estado`),
  ADD CONSTRAINT `usuarios_ibfk_3` FOREIGN KEY (`id_avatar`) REFERENCES `avatar` (`id_avatar`);

--
-- Filtros para la tabla `usuarios_mundos`
--
ALTER TABLE `usuarios_mundos`
  ADD CONSTRAINT `usuarios_mundos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`doc`),
  ADD CONSTRAINT `usuarios_mundos_ibfk_2` FOREIGN KEY (`id_mundo`) REFERENCES `mundos` (`id_mundo`);

--
-- Filtros para la tabla `usuarios_niveles`
--
ALTER TABLE `usuarios_niveles`
  ADD CONSTRAINT `usuarios_niveles_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`doc`),
  ADD CONSTRAINT `usuarios_niveles_ibfk_2` FOREIGN KEY (`id_nivel`) REFERENCES `niveles` (`id_nivel`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
