<?php

class RolesUsuariosDTO
{
    public $id_usuario;

    public $id_rol;

    public function __construct($id_usuario, $id_rol)
    {
        $this->id_usuario = $id_usuario;
        $this->id_rol = $id_rol;
    }

}