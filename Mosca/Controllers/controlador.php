<?php

class Controlador{
    public static $usuario;
    public $usuarioDAO;
    public $phpMailer;

    public $rolDAO;

    public $roles_usuariosDAO;

    public function __construct($usuarioDAO, $phpMailer, $rolDAO, $roles_usuariosDAO){
        $this->usuarioDAO = $usuarioDAO;
        $this->phpMailer = $phpMailer;
        $this->rolDAO = $rolDAO;
        $this->roles_usuariosDAO = $roles_usuariosDAO;
    }

    public function crearUsuario($nombre, $email, $edad, $pass)
    {
        $correoExiste = $this->usuarioDAO->buscarUsuarioPorEmail($email);
        $usuarioExiste = $this->usuarioDAO->buscarPorUsuario($nombre);
        if (!$correoExiste && !$usuarioExiste)
        {
            $usuarioDto = new UsuarioDTO($nombre, $edad, $email, $pass);
            $this->usuarioDAO->guardarUsuario($usuarioDto); 
            $anadirRoles = $this->usuarioDAO->recuperarDatosUsuario($nombre, $pass);
            $recuperarIDRol = $this->rolDAO->recuperarIDRol("user");
            $this->roles_usuariosDAO->anadirRolUsuario($anadirRoles->id, $recuperarIDRol); // default user ya que la func es crear users
            http_response_code(201);
            echo json_encode([
                "mensaje" => "Usuario creado con éxito",
                "nombre" => $nombre,
                "email"=> $email,
                "ok" => true
            ]);
        } 
        else 
        {
            Auxiliar::controlErrores("El correo o nombre de Usuario ya están en uso", false, 400);
        }
    }   

    public function login($nombre, $pass)
    {
        $comprobarUsuario = $this->usuarioDAO->iniciarSesion($nombre, $pass);
        if ($comprobarUsuario)
        {
            echo json_encode([
            "mensaje" => "Logeado con éxito",
            "nombre" => $nombre,
            "ok" => true
            ]);
        } 
        else 
        {
            Auxiliar::loginInvalido();
        }
        return $comprobarUsuario;
    }

    public function recuperar($datinhos)
    {
        $comprobarUsuario =  $this->usuarioDAO->buscarUsuarioPorEmail($datinhos["email"]);
        if($comprobarUsuario)
        {
            return $comprobarUsuario;
        }
        else
        {
            Auxiliar::correoInvalido();
        }
    }

    public function cambiarContraseña($datinhos)
    {
        $comprobarUsuario = $this->usuarioDAO->actualizarUserPassReseteada($datinhos, $datinhos["passNueva"]);
        http_response_code(200);
        echo json_encode([
            "mensaje" => "Se ha actualizado la nueva contraseña",
            "ok" => true
        ]);
    }

    public function eliminarUsuario($id)
    {
        $comprobarUsuario = $this->usuarioDAO->obtenerUsuarioConcreto($id);
        if ($comprobarUsuario)
        {
            $this->usuarioDAO->borrarUsuario($id);
            echo json_encode([
            "mensaje" => "Usuario borrado con éxito",
            "nombre" => $comprobarUsuario->nombre,
            "id" => $id,
            "ok" => true
            ]);
        } 
        else 
        {
            Auxiliar::controlErrores(Constantes::USUARIO_NO_EXISTE, false, 404);
        }
    }

    public function obtenerUsuarios()
    {
        $usuarios = $this->usuarioDAO->obtenerTodosLosUsuarios();
        if ($usuarios) 
        {
            foreach ($usuarios as $usuario) 
            { // esto lo hago asi para evitar tener que enseñar contraseña y demas, dado que me devuelvo toda la informacion del usuario
                $listaUsuarios[] = [
                    "id" => $usuario->id, 
                    "nombre" => $usuario->nombre, 
                    "email" => $usuario->email
                ];
            }
            echo json_encode($listaUsuarios);
        }
    }

    public function probarContrasenaRandom()
    {
        $contrasena = strval(rand(149,7123));
        $concatenar = "qwertyuiopasdfglhkzxcvbnm";


        for ($i = 0; $i < 12; $i++){
            $contrasena .= $concatenar[rand(0,strlen($concatenar)-1)];
        }
        return $contrasena;
    }

    public function enviarCorreoRec($datinhos){
        $correoExiste = $this->recuperar($datinhos);
        if ($correoExiste)
        {
            $contrasenaRandom =  $this->probarContrasenaRandom();
            $this->phpMailer->enviarCorreo($datinhos, $contrasenaRandom);
            $this->usuarioDAO->actualizarUserPassReseteada($datinhos, $contrasenaRandom);
            echo json_encode([
                "mensaje" => "Se ha enviado el correo de recuperacion",
                "ok" => true
            ]);
        }
    }

    public function comprobarAdmin($datinhos)
    {
        $loginCorrecto = $this->usuarioDAO->iniciarSesion($datinhos["nombre"], $datinhos["pass"]);
        if ($loginCorrecto)
        {
            $rol = $this->roles_usuariosDAO->comprobarRolUsuario($loginCorrecto->id);
        }
        else
        {
            Auxiliar::loginInvalido();
            return false;
        }

        if(in_array(0, $rol))
        {
            return $loginCorrecto;
        }
        else if (!in_array(0,  $rol))
        {
            Auxiliar::permisosInsuficientes();
            return false;
        }
    }

    public function comprobarUsuario($datinhos)
    {
        $loginCorrecto = $this->usuarioDAO->iniciarSesion($datinhos["nombre"], $datinhos["pass"]);
        if ($loginCorrecto)
        {
            $rol = $this->roles_usuariosDAO->comprobarRolUsuario($loginCorrecto->id);
        }
        else
        {
            Auxiliar::loginInvalido();
            return false;
        }

        if(in_array(1, $rol))
        {
            return $loginCorrecto;
        }
        else if (!in_array(1,  $rol))
        {
            Auxiliar::permisosInsuficientes();
            return false;
        }
    }

    public function obtenerUnUsuario($id){
            $comprobarUsuario = $this->usuarioDAO->obtenerUsuarioConcreto($id);
            if (!$comprobarUsuario)
            {
                Auxiliar::controlErrores(Constantes::USUARIO_NO_EXISTE, false, 404);
            } 
            else if ($comprobarUsuario)
            {
                echo json_encode([
                "sesion" => Constantes::SESION_CORRECTA,
                "usuario"=> $comprobarUsuario->nombre,
                "email"=> $comprobarUsuario->email
                ]);
                return $comprobarUsuario;
            }
    }


    public function actualizarUsuario($id, $nombreRol)
    {
        $obtenerTodosLosRoles = $this->rolDAO->obtenerTodosLosRoles();
        $nombreRol = strtolower(trim($nombreRol));
        $ok = false;
        foreach ($obtenerTodosLosRoles as $rol) 
        { 
            $roles[] = $rol->rol;
        }
        
        if (in_array($nombreRol, $roles))
        {
            $usuario = $this->usuarioDAO->obtenerUsuarioConcreto($id);
            $obtenerRoles = $this->roles_usuariosDAO->comprobarRolUsuario($id);
            $obtenerRolID = $this->rolDAO->recuperarIdRol($nombreRol);
            $adminRol = $this->rolDAO->recuperarIdRol("admin");
            $userRol = $this->rolDAO->recuperarIdRol("user");
            if($usuario)
            { 
                if (in_array($obtenerRolID, $obtenerRoles))
                {
                    Auxiliar::mismoRol();
                }
                else 
                {
                    if (in_array($adminRol, $obtenerRoles) && $obtenerRolID == $userRol) 
                    {
                        $this->roles_usuariosDAO->actualizarRolUsuario($usuario->id, $userRol, $adminRol);
                        Auxiliar::adminToUser();

                    } 
                    else if (in_array($userRol, $obtenerRoles) && $obtenerRolID == $adminRol)
                    {
                        $this->roles_usuariosDAO->actualizarRolUsuario($usuario->id, $adminRol, $userRol);
                        Auxiliar::userToAdmin();
                    }
                    else
                    {
                        $this->roles_usuariosDAO->anadirRolUsuario($usuario->id, $obtenerRolID);
                        $ok = true;
                        echo json_encode([
                        "nombre" => $usuario->nombre,
                        "rol_id" => $obtenerRolID,
                        "rol_nombre" => $nombreRol,
                        "cambios" => $ok,
                        ]);
                    }
                }
            } 
            else 
            {
                Auxiliar::controlErrores(Constantes::USUARIO_NO_EXISTE, false, 404);
            }
        }
        else
        {
            Auxiliar::rolNoExistente("nombre");
        }
    }

    public function crearRol($datinhos)
    {
        $comprobarRol = $this->rolDAO->obtenerTodosLosRoles();
        $rolSiempreMinusculas = strtolower(trim($datinhos["rol"])); // con esto me evito roles tipo Admin admin ADmin xd y posibles errores a futuro :p (me la juego a que la he cagado en otro sitio de forma epica)
        $roles = [];
        foreach ($comprobarRol as $rol)
        {
            $roles[] = strtolower(trim($rol->rol));
        }
        if (in_array($rolSiempreMinusculas, $roles, true))
        {
            Auxiliar::rolYaExistente($rolSiempreMinusculas);
        }
        else
        {
            $this->rolDAO->anadirRol($rolSiempreMinusculas);
            Auxiliar::rolCrearBorrar($rolSiempreMinusculas, "creado", 201);
        }
        
    }

    public function borrarRol($datinhos)
    {
        $comprobarRol = $this->rolDAO->obtenerTodosLosRoles();
        $rolSiempreMinusculas =  strtolower(trim($datinhos["rol"]));

        foreach ($comprobarRol as $rol)
        {
            $roles[] = strtolower(trim($rol->rol));
        }

        if (!in_array($rolSiempreMinusculas, $roles))
        {
            Auxiliar::rolNoExistente("nombre");
        }
        else
        {
            $this->rolDAO->removerRol($rolSiempreMinusculas);
            Auxiliar::rolCrearBorrar($rolSiempreMinusculas, "borrado", 200);
        }
    }

}