<?php
class Usuario{
    public $nombre;
    public $email;
    public $edad;
    public $pass;
    public $id;
    
    public $id_rol;

    public function __construct($nombre, $email, $edad, $pass){
        $this->nombre = $nombre;
        $this->email = $email;
        $this->edad = $edad;
        $this->pass = $pass;
    }
    
}