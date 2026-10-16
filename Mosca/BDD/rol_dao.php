<?php

require_once __DIR__ . '/../Constantes/sqlqueries.php';
class RolDao {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function anadirRol($rol)
    {
        $conexion = $this->db->connect();
        $stmt = $conexion->prepare(SqlQueries::ANADIR_ROL);
        $stmt->bind_param('s', $rol);
        $ok = $stmt->execute();
        $stmt->close();
        $conexion->close();
        return $ok;
    }

    public function removerRol($rol)
    {
        $conexion = $this->db->connect();
        $stmt = $conexion->prepare(SqlQueries::REMOVER_ROL);
        $stmt->bind_param('s',  $rol);
        $ok = $stmt->execute();
        $stmt->close();
        $conexion->close();
        return $ok;
    }

    public function obtenerTodosLosRoles()
    {
        $roles = [];
        $conexion = $this->db->connect();
        $query = SqlQueries::OBTENER_TODOS_ROLES;
        $resultado = $conexion->query($query);
        while ($fila = $resultado->fetch_assoc()) 
        {
        $rol = new Rol($fila["id"], $fila["rol"]);
        $roles[] = $rol;
        }
        $conexion->close();
        return $roles;
    }

    public function recuperarIdRol($rol)
    {
        $conexion = $this->db->connect();
        $stmt = $conexion->prepare(SqlQueries::CONSEGUIR_ID_ROL);
        $stmt->bind_param('s', $rol);
        $ok = $stmt->execute();
        $resultado = $stmt->get_result();
        $rol = null;
        if ($fila = $resultado->fetch_assoc()) 
        {
            $rol = $fila["id"];
        }
        $stmt->close();
        $conexion->close();
        return $rol;
    }

    public function recuperarNombreRol($id){
        $conexion = $this->db->connect();
        $stmt = $conexion->prepare(SqlQueries::CONSEGUIR_NOMBRE_ROL);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $rol = null;
        if ($fila = $resultado->fetch_assoc()) 
        {
            $rol = $fila["rol"];
        }
        $stmt->close();
        $conexion->close();
        return $rol;
    }
}