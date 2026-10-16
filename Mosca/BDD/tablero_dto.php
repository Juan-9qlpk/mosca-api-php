<?php

class TableroDTO
{
    public $id;
    public $tablero;
    public $intentos;
    public $intentos_iniciales;
    public $posicion_mosca;
    public $fecha_creacion;
    public $id_usuario;
    public $estado;

    public function __construct($tablero, $intentos, $id_usuario, $intentos_iniciales, $posicion_mosca, $estado)
    {
        $this->tablero = $tablero;
        $this->intentos = $intentos;
        $this->id_usuario = $id_usuario;
        $this->intentos_iniciales = $intentos_iniciales;
        $this->posicion_mosca = $posicion_mosca;
        $this->estado = $estado;
    }

}