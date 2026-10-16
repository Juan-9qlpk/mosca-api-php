<?php

require_once __DIR__ . '/../Constantes/sqlqueries.php';
class TableroDAO {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function guardarTableroGenerado(TableroDTO $tablero, $idUser)
    {
        $conexion = $this->db->connect();
        $stmt = $conexion->prepare(SqlQueries::GUARDAR_TABLERO_GENERADO);
        $casillas = implode(",", $tablero->tablero);
        $intentos = $tablero->intentos;
        $intentosIniciales = $tablero->intentos;
        $posMosca = $tablero->posicion_mosca;
        $estado = $tablero->estado;
        $stmt->bind_param('siisii',  $casillas, $intentos, $intentosIniciales, $estado, $posMosca, $idUser);

        $ok = $stmt->execute();
        $stmt->close();
        $conexion->close();
        return $ok;
    }

    public function guardarTablero(TableroDTO $tablero)
    {
        $conexion = $this->db->connect();
        $stmt = $conexion->prepare(SqlQueries::GUARDAR_TABLERO);
        $casillas = implode(",", $tablero->tablero);
        $intentos = $tablero->intentos;
        $estado = "activo";
        $stmt->bind_param('sii',  $casillas, $intentos, $estado);

        $ok = $stmt->execute();
        $stmt->close();
        $conexion->close();
        return $ok;
    }

    public function obtenerTablero($id)
    {
        $conexion = $this->db->connect();
        $stmt =$conexion->prepare(SqlQueries::OBTENER_TABLERO);
        $activo = "activo";
        $stmt->bind_param('si', $activo, $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $tab = null;

        if ($fila = $resultado->fetch_assoc())
        {
            $tab = new Tablero();
            $tab->id = $fila["id"];
            $tab->tablero = explode(',', $fila["casillas"]);
            $tab->tamano = count($tab->tablero);
            $tab->intentos = $fila["intentos"];
            $tab->estado = $fila["estado"];
        }

        $conexion->close();
        return $tab;
    }

    public function modificarTableroIntentos($idUser, $id, $intentos){
        $conexion = $this->db->connect();
        $tab = self::obtenerTablero($idUser);
        if ($tab){
            $stmt = $conexion->prepare(SqlQueries::MODIFICAR_TABLERO_INTENTOS);
            $stmt->bind_param("ii", $intentos, $id);
            $ok = $stmt->execute();
        } else {
            $ok = false;
        }
        $stmt->close();
        $conexion->close();
        return $ok;
    }

    public function modificarTableroArray($idUser, $id, $tablero, $intentos, $posMosca){
        $conexion = $this->db->connect();
        $tablerinho = self::obtenerTablero($idUser);
        if ($tablerinho){
            $casillas =  implode(",", $tablero);
            $stmt = $conexion->prepare(SqlQueries::MODIFICAR_ARRAY_TABLERO);
            $stmt->bind_param("siii", $casillas, $intentos , $posMosca,$id);
            $ok = $stmt->execute();
        } else {
            $ok = false;
        }
        $stmt->close();
        $conexion->close();
        return $ok;
    }

    public function modificarTableroStatus($idUser, $id, $estado)
    {
        $conexion = $this->db->connect();
        $tablero = self::obtenerTablero($idUser);
        if ($tablero){
            $stmt = $conexion->prepare(SqlQueries::MODIFICAR_TABLERO_STATUS);
            $stmt->bind_param('si', $estado, $id);
            $ok = $stmt->execute();
        } else 
        {
            $ok = false;
        }
        $stmt->close();
        $conexion->close();
        return $ok;
    }
}