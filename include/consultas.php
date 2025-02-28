<?php
function obtenerEstados() { 
    return "SELECT id_estado, nom_estado FROM estados";
}
    function obtenerEstadosRango($id_min, $id_max) {
        return "SELECT id_estado, nom_estado FROM estados WHERE id_estado BETWEEN :id_min AND :id_max";
    }
    
function obtenerUsuarios() {
    return "SELECT usuarios.doc, usuarios.nom_usu, usuarios.email, estados.id_estado, nom_estado, roles.id_rol, roles.nom_rol
    FROM usuarios
    LEFT JOIN estados ON usuarios.id_estado = estados.id_estado
    LEFT JOIN roles ON usuarios.id_rol = roles.id_rol";
}

// function obtenerMaquinas() {
//     return "SELECT maquinas.serial,
//         marcas.id AS marcaId, 
//         marcas.nombre AS marca_nombre,
//         tiposmaquinas.id AS tipoMaquinId, 
//         tiposmaquinas.nombre AS tipo_maquina_nombre,
//         estados.id AS estadoId, 
//         estados.nombre AS estado_nombre,
//         proveedores.nit AS proveedorNit, 
//         proveedores.nombre AS proveedor_nombre,
//         maquinas.imagen AS imagen_maquina
//     FROM maquinas
//     LEFT JOIN marcas ON maquinas.marcaId = marcas.id
//     LEFT JOIN tiposmaquinas ON maquinas.tipoMaquinaId = tiposmaquinas.id
//     LEFT JOIN estados ON maquinas.estadoId = estados.id
//     LEFT JOIN proveedores ON maquinas.proveedorNit = proveedores.nit";
// }

// function obtenerTipoMaquinas() { 
//     return "SELECT * FROM tiposmaquinas";
// }

// function obtenerMarcas() { 
//     return "SELECT * FROM marcas";
// }
// function obtenerProveedores() { 
//     return "SELECT * FROM proveedores";
// }

?>
