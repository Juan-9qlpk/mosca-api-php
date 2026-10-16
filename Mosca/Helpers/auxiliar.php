<?php

class Auxiliar{
    public static function controlErrores($error, $estado, $nError) {
        http_response_code($nError);
        echo json_encode([
            'Error' => $error,
            'ok' => $estado,
        ]);
    }

    public static function mensajeRendicion($pos, $tablero){
        http_response_code(200);
        echo json_encode([
            "sesion" => Constantes::SESION_CORRECTA,
            'estado' => "Te has rendido",
            'Mosca' => $pos,
            'decepcion' => true,
            'tablero' => $tablero
        ]);
    }

    public static function mensajePiniazo($codigo, $interaccion, $intentos){
        http_response_code(200);
        echo json_encode([
            "sesion" => Constantes::SESION_CORRECTA,
            'cod' => $codigo,
            'mensaje' => $interaccion,
            'intentos' => $intentos,
            "ok" => true
        ]);
    }

    public static function mensajeCreacionTablero($mensaje, $datos, $nRespuesta, $tamano, $intentos){
        http_response_code($nRespuesta);
        echo json_encode([
            "sesion" => Constantes::SESION_CORRECTA,
            "datos " => $datos,
            'mensaje' => $mensaje,
            'tamaño' => $tamano,
            'intentos' => $intentos,
            "ok" => true
        ]);
    }

    public static function mensajeDevolverIntentos($intentos){
        echo json_encode([
            'sesion' => Constantes::SESION_CORRECTA,
            'intentos' => $intentos,
            'ok' => true
        ]);
    }

    public static function perder(){
        echo json_encode([
            "sesion" => Constantes::SESION_CORRECTA,
            "mensaje" => "Te has quedado sin intentos",
            "estado" => "Has perdido",
            "ok" => true
        ],200);
    }

    public static function tableroNoCreado(){
        http_response_code(404);
        echo json_encode([
            "sesion" => Constantes::SESION_CORRECTA,
            'Error' => "No hay un tablero creado",
            'ok' => false,
        ]);
    }

    public static function victoria(){
        http_response_code(200);
        echo json_encode([
            "sesion" => Constantes::SESION_CORRECTA,
            "mensaje" => "Has matado a la mosca",
            "ok" => true,
        ]);
    }

    public static function yaExisteUnTablero(){
        http_response_code(400);
        echo json_encode([
            "sesion" => Constantes::SESION_CORRECTA,
            'Error' => "Ya hay un tablero creado",
            'ok' => false,
        ]);
    }

    public static function permisosInsuficientes()
    {
        http_response_code(403);
        echo json_encode([
            "mensaje" =>"No tienes permisos suficientes",
            "ok" => false
        ]);
    }

    public static function loginInvalido()
    {
        http_response_code(400);
        echo json_encode([
            "mensaje" =>"Login Inválido",
            "ok" => false
        ]);
    }

    public static function rolCrearBorrar($rol, $crearBorrar, $code)
    {
        http_response_code($code);
        echo json_encode([
            "sesion" => Constantes::SESION_CORRECTA,
            "rol" => "El rol $rol se ha $crearBorrar con exito",
            "ok" => true,
        ]);
    }

    public static function mismoRol()
    {
        http_response_code(400);
        echo json_encode([
            "sesion" => Constantes::SESION_CORRECTA,
            "mensaje" => "El usuario ya cuenta con ese rol",
            "ok" => false,
        ]);
    }

    public static function rolYaExistente($rol)
    {
        http_response_code(400);
        echo json_encode([
            "sesion" => Constantes::SESION_CORRECTA,
            "mensaje" => "El rol: $rol ya existe",
            "ok" => false,
        ]);
    }

    public static function correoInvalido()
    {
        http_response_code(400);
        echo json_encode([
            "mensaje" => "El correo no existe",
            "ok" => false
        ]);
    }

    public static function adminToUser()
    {
        http_response_code(200);
        echo json_encode([
            "sesion" => Constantes::SESION_CORRECTA,
            "mensaje" => "Un admin no puede tener rol user",
            "actualizacion" => "Se ha removido el rol admin y se ha otorgado el rol user",
            "ok" => true,
        ]);
    }

    public static function userToAdmin()
    {
        http_response_code(200);
        echo json_encode([
            "sesion" => Constantes::SESION_CORRECTA,
            "mensaje" => "Un user no puede tener rol admin",
            "actualizacion" => "Se ha removido el rol user y se ha otorgado el rol admin",
            "ok" => true,
        ]);
    }

    public static function rolNoExistente($idNombre)
    {
        http_response_code(404);
        echo json_encode([
            "mensaje" => "El $idNombre del rol no existe",
            "ok" => false,
        ]);
    }
}