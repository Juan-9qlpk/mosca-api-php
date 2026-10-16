<?php

class RolDTO
{
    public $id;

    public $rol;

    public function __construct($id, $rol)
    {
        $this->id = $id;
        $this->rol = $rol;
    }

}