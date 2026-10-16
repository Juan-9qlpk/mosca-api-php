<?php
class ControladorTablero {

    public static $tablero;
    public static $tableroDAO;
    public static $usuarioDAO;

    public static function devolverIntentos2($datinhos) 
    {
        $existeUsuario = self::$usuarioDAO->iniciarSesion($datinhos["nombre"], $datinhos["pass"]);
        if ($existeUsuario)
        {
            $recuperarTablero = self::$tableroDAO->obtenerTablero($existeUsuario->id);
            if ($recuperarTablero)
            {
                self::$tablero = $recuperarTablero;
                echo Auxiliar::mensajeDevolverIntentos(self::$tablero->devolverIntentos());
            } 
            else 
            {
                Auxiliar::tableroNoCreado();
            }
        } 
    }

    public static function tamanoIntentosCustom($datinhos) 
    {
        $existeUsuario = self::$usuarioDAO->iniciarSesion($datinhos["nombre"], $datinhos["pass"]);
        if ($existeUsuario)
        {
        $id = self::buscarTableroActivo($existeUsuario->id);
        if ($id == null){
            $tablero = new Tablero($datinhos["tamano"], $datinhos["intentos"]); 
            $tableroDTO = new TableroDTO($tablero->tablero, $tablero->intentos, $existeUsuario->id, $tablero->intentos,
            $tablero->posicion_mosca, $tablero->estado);
            Auxiliar::mensajeCreacionTablero(Constantes::TABLERO_CREADO, Constantes::DATOS_CORRECTOS, 201, $datinhos["tamano"], $datinhos["intentos"]);
            self::$tableroDAO->guardarTableroGenerado($tableroDTO, $existeUsuario->id);
        }
        else
        {
        return Auxiliar::yaExisteUnTablero();
        }
        }
    }

    public static function generarTableroDefault($datinhos) 
    {
        $existeUsuario = self::$usuarioDAO->iniciarSesion($datinhos["nombre"], $datinhos["pass"]);
        if ($existeUsuario)
        {
            $id = self::buscarTableroActivo($existeUsuario->id);
            if ($id == null)
            {
                $tablero = new Tablero(Constantes::TAMANO_DEFAULT, Constantes::INTENTOS_DEFAULT);
                $tableroDTO = new TableroDTO($tablero->tablero, $tablero->intentos, $existeUsuario->id, $tablero->intentos,
                $tablero->posicion_mosca, $tablero->estado);
                Auxiliar::mensajeCreacionTablero(Constantes::TABLERO_CREADO_2,Constantes::PARAMETROS_INVALIDOS, 201, Constantes::TAMANO_DEFAULT, Constantes::INTENTOS_DEFAULT);
                self::$tableroDAO->guardarTableroGenerado($tableroDTO, $existeUsuario->id);
            } 
            else
            {
                return  Auxiliar::yaExisteUnTablero();
            }
        }
    }

    public static function golpearControlador($datinhos){
        $usuario = self::$usuarioDAO->iniciarSesion($datinhos["nombre"], $datinhos["pass"]);
        if ($usuario)
        {
            $recuperarTablero = self::$tableroDAO->obtenerTablero($usuario->id);
            if ($recuperarTablero)
                {
                    self::$tablero = $recuperarTablero;
                    $pos = $datinhos["posicion"];
                    $condicion = self::$tablero->golpe($pos);

                    switch ($condicion)
                    {
                        case 1; 
                            $victoria = "ganado";
                            self::$tableroDAO->modificarTableroIntentos($usuario->id, self::$tablero->id, self::$tablero->intentos); 
                            self::$tableroDAO->modificarTableroStatus($usuario->id, self::$tablero->id, $victoria);
                            Auxiliar::mensajePiniazo(1, Constantes::GOLPE, self::$tablero->devolverIntentos());
                        break;
                        case 2;
                            self::$tableroDAO->modificarTableroArray($usuario->id, self::$tablero->id, self::$tablero->tablero, self::$tablero->intentos, self::$tablero->posicionMosca());
                            Auxiliar::mensajePiniazo(2, Constantes::CERCA, self::$tablero->devolverIntentos());
                        break;
                        case 3; // OK FIXED
                            self::$tableroDAO->modificarTableroIntentos($usuario->id, self::$tablero->id, self::$tablero->intentos); 
                            Auxiliar::mensajePiniazo(3, Constantes::FALLO, self::$tablero->devolverIntentos());
                        break;
                        case -1; // OK FIXED
                            self::$tableroDAO->modificarTableroStatus($usuario->id, self::$tablero->id, "perdido");
                        break;
                        default;
                        Auxiliar::controlErrores(Constantes::PARAMETROS_INVALIDOS, false, 400);
                        break;
                    }
            } 
            else 
            {
                Auxiliar::tableroNoCreado();
            }
        }
    }

    public static function rendirsePUT($datinhos)
    {
        $usuario = self::$usuarioDAO->iniciarSesion($datinhos["nombre"], $datinhos["pass"]);
        if ($usuario)
        {
            $tableroActivo = self::$tableroDAO->obtenerTablero($usuario->id);
            if ($tableroActivo)
            {
                Auxiliar::mensajeRendicion(self::devolverPosMosca($usuario->id), self::imprimirTablero());
                self::$tablero = $tableroActivo;
                self::$tablero->rendirse();
                self::$tableroDAO->modificarTableroStatus($usuario->id, $tableroActivo->id, self::$tablero->devolverEstado());
            }
            else
            {
                return Auxiliar::tableroNoCreado();
            }
        }
    }

    public static function devolverPosMosca($id) 
    {
        $recuperarTablero = self::$tableroDAO->obtenerTablero($id);
        if ($recuperarTablero)
        {
            self::$tablero = $recuperarTablero;
            return self::$tablero->posicionMosca();
        }
    }

    public static function imprimirTablero()
    {
        $imprimirTablero = "";
        for ($i = 0; $i < self::$tablero->tamano; $i++)
        {
            $imprimirTablero = $imprimirTablero . self::$tablero->tablero[$i] . "";
        }

        return $imprimirTablero;
    }

    public static function buscarTableroActivo($id)
    {
        $existe = self::$tableroDAO->obtenerTablero($id);
        return $existe;
    }

}
