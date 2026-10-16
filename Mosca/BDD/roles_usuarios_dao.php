<?php

require_once __DIR__ . '/../Constantes/sqlqueries.php';
class RolesUsuariosDao {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function anadirRolUsuario($idUser, $idRol)
    {
        $conexion = $this->db->connect();
        $stmt = $conexion->prepare(SqlQueries::ANADIR_ROL_A_USUARIO);
        $stmt->bind_param('ii',  $idUser, $idRol);
        $ok = $stmt->execute();
        $stmt->close();
        $conexion->close();
        return $ok;
    }

    public function removerRolUsuario($idUser, $idRol)
    {
        $conexion = $this->db->connect();
        $stmt = $conexion->prepare(SqlQueries::REMOVER_ROL_A_USUARIO);
        $stmt->bind_param('ii',  $idUser, $idRol);
        $ok = $stmt->execute();
        $stmt->close();
        $conexion->close();
        return $ok;
    }

    public function actualizarRolUsuario($idUser, $idRolNuevo, $idRolViejo)
    {
        $conexion = $this->db->connect();
        $stmt = $conexion->prepare(SqlQueries::ACTUALIZAR_ROL_A_USUARIO);
        $stmt->bind_param('iii',   $idRolNuevo, $idUser, $idRolViejo);
        $ok = $stmt->execute();
        $stmt->close();
        $conexion->close();
        return $ok;
    }

    public function comprobarRolUsuario($idUser)
    {
        $conexion = $this->db->connect();
        $stmt = $conexion->prepare(SqlQueries::COMPROBAR_ROL_USUARIO);
        $stmt->bind_param('i',  $idUser);
        $ok = $stmt->execute();
        $resultado = $stmt->get_result();
        while ($fila = $resultado->fetch_assoc()) {
        $roles[] = $fila["id_rol"];
        }
        $stmt->close();
        $conexion->close();
        return $roles;
    }
}