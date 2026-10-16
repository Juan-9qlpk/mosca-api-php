<?php

class UsuarioDTO{
    public $nombre;
    public $edad;
    public $email;
    public $pass;
    public $id;
    public $id_rol;


    public function __construct($nombre, $edad, $email, $pass){
        $this->nombre = $nombre;
        $this->edad = $edad;
        $this->email = $email;
        $this->pass = $pass;
    }
    
}