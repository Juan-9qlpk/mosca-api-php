<?php

class ControladorParametros{

    public $usuarioDAO;

    public function __construct($usuarioDAO){
        $this->usuarioDAO = $usuarioDAO;
    }


    public function comprobarUsernamePassword($datinhos){
        $nombre = $datinhos['nombre'];
        $pass = $datinhos['pass'];

        $datosCorrectos = true;
        if (!isset($nombre)  || $nombre == ""  || !isset($pass)  || $pass == ""){
            $datosCorrectos = false;
            Auxiliar::controlErrores(Constantes::PARAMETROS_INVALIDOS, false, 400);
        }
        return $datosCorrectos;
    }


    public function comprobarParametrosPostRecuperar($datinhos){
        $nombre = $datinhos['nombre'];
        $email = $datinhos['email'];

        $datosCorrectos = false;
        if (isset($nombre) && $nombre != "" && filter_var($email, FILTER_VALIDATE_EMAIL)){
            $datosCorrectos = true;
            $datosCorrectos = $this->comprobarParametrosCoinciden($nombre, $email);
        }
        else
        {
            Auxiliar::controlErrores(Constantes::PARAMETROS_INVALIDOS, false, 400);
        }

        return $datosCorrectos;
    }

    public function comprobarPasswd($datinhos)
    {
        $contrasena = $datinhos["passNueva"];
        $datosCorrectos = false;
        if (isset($contrasena) && $contrasena != "")
        {
            $datosCorrectos =  true;
            return $datosCorrectos;
        } 
        else
        {
            Auxiliar::controlErrores(Constantes::PARAMETROS_INVALIDOS, false, 400);
        }
    }

    
    public function comprobarParametrosPOST($parametros){
        $comprobarParametros = false;
        $datinhosoperacion = $parametros[1] ?? null; // esto lo enseño migue hace 2 semanas 
        $datinhos2 = $parametros[2] ?? null;
        // jaja esto si te lo comento, bienvenido a mi cloaca de filtros xD esta fatal manejado
        if (($datinhosoperacion == "login" || $datinhosoperacion == "registrar" || $datinhosoperacion == "crear" || $datinhosoperacion == "jugar" 
        || $datinhosoperacion == "recuperar" || $datinhosoperacion == "rol") && empty($datinhos2))
        {
            $comprobarParametros = true;
        }
        return $comprobarParametros;
    }

    public function comprobarParametrosPostLogin($datinhos){
        $correcto = true;
        if (empty($datinhos["nombre"]) || empty($datinhos["pass"])){
            $correcto = false;
            Auxiliar::controlErrores(Constantes::PARAMETROS_INVALIDOS, false, 405);
        }
        return $correcto;
    }

    public function comprobarParametrosCoinciden($nombre, $email){
        $encontrado = $this->usuarioDAO->buscarUsuarioPorEmail($email);
        $datosCorrectos = false;
        if ($encontrado)
        {
            if ($encontrado->nombre == $nombre && $encontrado != null)
            {
            $datosCorrectos = true;
            }
            else
            {
                Auxiliar::controlErrores("Email o nombre de usuarios inválidos", false, 400);
            }
        }
        else
        {
            Auxiliar::controlErrores("Email o nombre de usuarios inválidos", false, 400);
        }
        return $datosCorrectos;
    }

    public function comprobarParametrosPostRegister($datinhos){
        $correcto = true;
        if (empty($datinhos["nombre"]) || empty($datinhos["pass"]) || empty($datinhos["email"]) || empty($datinhos["edad"])){
            $correcto = false;
            Auxiliar::controlErrores(Constantes::PARAMETROS_INVALIDOS, false, 400);
        } else if (!is_numeric($datinhos["edad"]) || $datinhos["edad"] <= 0){
            $correcto = false;
            Auxiliar::controlErrores(Constantes::EDAD_INVALIDA, false, 400);
        } else if (!filter_var($datinhos["email"], FILTER_VALIDATE_EMAIL)){
            $correcto = false;
            Auxiliar::controlErrores(Constantes::EMAIL_INVALIDO, false, 400);
        }
        return $correcto;
    }

    public function comprobarParametrosGET($parametros)
    {
        $correcto = null;
            if ($parametros[1] == "usuarios" && $parametros[2] == "")
            { // esto es pa devolver todos los usuarios
                $correcto = 1;
            } 
            else if ($parametros[1] == "usuarios" && ctype_digit($parametros[2]) && count($parametros) == 3)
            {
                $correcto = 2;
            } 
        return $correcto;
    }

    public function comprobarParametrosPUTyDELETE($parametros)
    {
        $correcto = false;
        if (ctype_digit($parametros[2]))
        {
            $correcto = true;
        }
        else
        {
            Auxiliar::controlErrores(Constantes::RUTA_INVALIDA, false, 404);
        }
        return $correcto;
    }

    public function comprobarDatosCrearRol($datinhos)
    {
        $parametrosCorrectos = false;
        if (is_numeric($datinhos["rol"]) || !isset($datinhos["rol"]))
        {
            Auxiliar::controlErrores(Constantes::PARAMETROS_INVALIDOS, false, 400);
        } 
        else
        {
            $parametrosCorrectos = true;
        }
        return $parametrosCorrectos;
    }

    function comprobarParametrosCrear($datinhos){
        $parametrosCorrectos = false;
        if (!isset($datinhos["tamano"]) || !isset($datinhos["intentos"])){
            return false;

        } else if (!is_int($datinhos["tamano"]) || !is_int($datinhos["intentos"])){
            return false;

        } else {
            $parametrosCorrectos = true;
        }
        return $parametrosCorrectos;
    }

    function comprobarParametrosGolpe($datinhos){
        $parametrosCorrectos = false;
        if (!isset($datinhos["posicion"]) || !is_int($datinhos["posicion"])){
            Auxiliar::controlErrores(Constantes::PARAMETROS_INVALIDOS, false, 400);
        } else {
            $parametrosCorrectos = true;
        }
        return $parametrosCorrectos;
    }


}