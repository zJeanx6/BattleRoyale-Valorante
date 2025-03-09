<?php
function obtenerEstados() { 
    return "SELECT id_estado, nom_estado FROM estados";
}
    function obtenerEstadosRango($id_min, $id_max) {return "SELECT id_estado, nom_estado FROM estados 
    WHERE id_estado BETWEEN :id_min AND :id_max";
    }

function obtenerUsuarios() {
    return "SELECT * FROM usuarios LEFT JOIN estados ON usuarios.id_estado = estados.id_estado 
    LEFT JOIN roles ON usuarios.id_rol = roles.id_rol";
}


//Header - Index jugadores
function ObtenerRolPorId(){
    return "SELECT usuarios.doc, usuarios.nom_usu, roles.id_rol, roles.nom_rol, usuarios.id_estado FROM usuarios 
    INNER JOIN roles ON usuarios.id_rol = roles.id_rol WHERE usuarios.doc = ?";
}
function obtenerUsuarioPorId($id_usuario) {
    return "SELECT usuarios.nom_usu, usuarios.doc FROM usuarios WHERE usuarios.doc = $id_usuario";
}
function obtenerNivelPorUsuario($id_usuario) {
    return "SELECT niveles.nom_nivel, niveles.img FROM usuarios_niveles 
    INNER JOIN niveles ON usuarios_niveles.id_nivel = niveles.id_nivel WHERE usuarios_niveles.id_usuario = $id_usuario";
}
function obtenerAvatarPorUsuario($id_usuario) {
    return "SELECT img FROM avatar WHERE id_avatar = (SELECT id_avatar FROM usuarios WHERE doc = $id_usuario)";
}


//Vista de Mundos jugadores
function obtenerNivelPorUsuario2($id_usuario) {
    return "SELECT id_nivel FROM usuarios_niveles WHERE id_usuario = $id_usuario";
}
function obtenerMundosPorNivel($nivel) {
    return "SELECT id_mundo, nom_mundo, img FROM mundos WHERE id_mundo <= $nivel";
}


//Vista de Armas jugadores
function obtenerArmasActuales($id_usuario) {
    return "SELECT id_arma FROM jugadores_armas WHERE id_jugador = $id_usuario";
}
function obtenerArmasDisponiblesPorNivel($id_usuario) { 
    global $con;
    $nivel = $con->query("SELECT id_nivel FROM usuarios_niveles WHERE id_usuario = $id_usuario")->fetchColumn(); //fetchColumn() solo el valor de la primera columna de la primera fila que cumple con los criterios de la consulta.
        if ($nivel == 1) {
            return "SELECT id_arma, nom_arma, img, balas, dano FROM armas 
            INNER JOIN tipos_armas ON tipos_armas.id_tip_arma = armas.id_tipo_arma WHERE id_tipo_arma IN (1, 2)";
        } else {
            return "SELECT id_arma, nom_arma, img, balas, dano FROM armas 
            INNER JOIN tipos_armas ON tipos_armas.id_tip_arma = armas.id_tipo_arma";
        }
}
function verificarExistenciaArma($id_arma) {
    return "SELECT id_arma FROM armas WHERE id_arma = ?";
}
function eliminarArmasJugador($id_usuario) {
    return "DELETE FROM jugadores_armas WHERE id_jugador = ?";
}
function insertarArmasJugador($id_usuario, $id_arma) {
    return "INSERT INTO jugadores_armas (id_jugador, id_arma) VALUES (?, ?)";
}

function obtenerEstadisticasTotales($id_usuario) {
    return "SELECT 
            u.nom_usu, 
            COALESCE(SUM(CASE WHEN pe.id_tipo_evento IN (1, 2) THEN pe.puntos ELSE 0 END), 0) AS puntos,
            COALESCE(SUM(CASE WHEN pe.id_tipo_evento = 2 THEN 1 ELSE 0 END), 0) AS muertes
        FROM partidas_eventos pe
        INNER JOIN usuarios u ON pe.id_jugador = u.doc
        WHERE u.doc = ?
        GROUP BY u.nom_usu
    ";
}

?>
