<?php
use PHPMailer\PHPMailer\PHPMailer;
header("Content-Type: application/json");
// esto es un lio xd voy a ordenarlo jaja

//externo (cosas que me da el propio php)
require_once 'phpmailer/src/Exception.php';
require_once 'phpmailer/src/PHPMailer.php';
require_once 'phpmailer/src/SMTP.php';

// Base de datinhos
require_once './BDD/database.php';
require_once './BDD/usuario_dao.php';
require_once './BDD/tablero_dao.php';
require_once './BDD/tablero_dto.php';
require_once './BDD/usuario_dto.php';
require_once './BDD/roles_usuarios_dao.php';
require_once './BDD/roles_usuarios_dto.php';
require_once './BDD/rol_dao.php';
require_once './BDD/rol_dto.php';

// modelinhos
require_once './Models/tablero.php';
require_once './Models/usuario.php';
require_once './Models/rol.php';

// constantinhos // helpers // // correinhos
require_once './Constantes/constantes.php';
require_once './Helpers/auxiliar.php';
require_once './Helpers/phpmailer.php';

// controladorinhos
require_once './Controllers/controlador.php';
require_once './Controllers/controlador_parametros.php';
require_once './Controllers/controlador_tablero.php';

$metodo = $_SERVER["REQUEST_METHOD"];
$db = new Database();
$usuarioDAO = new UsuarioDAO($db);
$tableroDAO = new TableroDAO($db);
$rolDAO = new RolDao($db);
$roles_usuariosDAO = new RolesUsuariosDao($db);
$phpMailer = new Correo();
$phpMailer->cargarVariablesEntorno();
$controlador = new Controlador($usuarioDAO, $phpMailer, $rolDAO, $roles_usuariosDAO);
$controladorParametros = new ControladorParametros($usuarioDAO);
ControladorTablero::$tableroDAO = $tableroDAO;
ControladorTablero::$usuarioDAO = $usuarioDAO;
try
{
    $url = $_SERVER["REQUEST_URI"];
    $parametros = explode("/", $url);
    $datos = file_get_contents("php://input");
    $datinhos = json_decode($datos, true);

    switch ($metodo){
                    // hecho -> Solo el admin puede comprobar los usuarios / control de errores hecho
        case "GET";
                // fixed mensaje json todo en 1
                if ($parametros[1] == "intentos")
                {
                    if ($controladorParametros->comprobarUsernamePassword($datinhos))
                    {
                        if ($controlador->comprobarUsuario($datinhos))
                        {
                            ControladorTablero::devolverIntentos2($datinhos);
                        }
                    }
                }
                // fixed mensaje json todo en 1
                else if ($parametros[1] == "usuarios")
                {
                    if ($controladorParametros->comprobarUsernamePassword($datinhos))
                    {
                        if ($controlador->comprobarAdmin($datinhos))
                        {
                            $opcion = $controladorParametros->comprobarParametrosGET($parametros);
                            switch ($opcion)
                            { // null - devuelvo to los users pq la url no tendria nah
                                case 1: $controlador->obtenerUsuarios();
                                break;
                                case 2: $controlador->obtenerUnUsuario($parametros[2]);
                                break;
                                default: Auxiliar::controlErrores(Constantes::RUTA_INVALIDA, false, 404);
                            } // true - devuelvo el numero q tiene la url
                        }
                    }
                }
                else 
                {
                    Auxiliar::controlErrores(Constantes::RUTA_INVALIDA, false, 404);
                }
        break;
        case "POST"; 
        // comprobar login / registrar
            if ($controladorParametros->comprobarParametrosPOST($parametros))
            {
                switch ($parametros[1])
                {
                    case "login":
                        if ($controladorParametros->comprobarUsernamePassword($datinhos))
                        {
                            if ($controladorParametros->comprobarParametrosPostLogin($datinhos))
                            {
                                $controlador->login($datinhos["nombre"], $datinhos["pass"]);
                            }
                        }
                    break; 
                    case "registrar":
                        if ($controladorParametros->comprobarUsernamePassword($datinhos))
                        {
                            if ($controladorParametros->comprobarParametrosPostRegister($datinhos))
                            {
                                $controlador->crearUsuario($datinhos["nombre"], $datinhos["email"], $datinhos["edad"], $datinhos["pass"]);
                            }
                        }
                    break;
                    case "crear":
                        // compruebo dentro si existe el user y si tiene tablero
                        if ($controladorParametros->comprobarUsernamePassword($datinhos))
                        {
                            if ($controlador->comprobarUsuario($datinhos))
                            {
                                if ($controladorParametros->comprobarParametrosCrear($datinhos))
                                { // si mete bien los parametros, guay te creo uno  datiinhos y userid
                                    ControladorTablero::tamanoIntentosCustom($datinhos);
                                } 
                                else
                                { // si te cagas encima con los parametros, se crea por default
                                    ControladorTablero::generarTableroDefault($datinhos);
                                }
                            }
                        }
                    break;
                    case "jugar":
                        if ($controladorParametros->comprobarUsernamePassword($datinhos))
                        {
                            if ($controlador->comprobarUsuario($datinhos))
                            {
                                if ($controladorParametros->comprobarParametrosGolpe($datinhos))
                                {
                                    ControladorTablero::golpearControlador($datinhos);
                                }
                            }
                        }
                    break;
                    case "recuperar":
                        // comprobar enviar correo -> necesito comprobar que el user exista aaaaaaaaa matenme
                                if ($controladorParametros->comprobarParametrosPostRecuperar($datinhos))
                                {
                                    $controlador->enviarCorreoRec($datinhos);
                                }
                    break;
                    case "rol":
                    if ($controladorParametros->comprobarUsernamePassword($datinhos))
                    {
                        if ($controlador->comprobarAdmin($datinhos))
                        {
                            if ($controladorParametros->comprobarDatosCrearRol($datinhos))
                            {
                                $controlador->crearRol($datinhos);
                            }

                        }
                    }
                    break;
                    default; Auxiliar::controlErrores(Constantes::RUTA_INVALIDA, false, 404);
                }
            } 
            else 
            {
                Auxiliar::controlErrores(Constantes::RUTA_INVALIDA, false, 404);
            }
        break;
        case "PUT";
            switch ($parametros[1])
            {
                case "usuarios":
                    if ($controladorParametros->comprobarUsernamePassword($datinhos))
                    {
                        if ($controlador->comprobarAdmin($datinhos))
                        {
                            if ($controladorParametros->comprobarParametrosPUTyDELETE($parametros))
                            {//actualizar por ID solo
                                $controlador->actualizarUsuario($parametros[2], $datinhos["rol"]);
                            }
                        }
                    }
                    break;
                case "rendirse":
                    if ($controladorParametros->comprobarUsernamePassword($datinhos))
                    {
                        if ($controlador->comprobarUsuario($datinhos))
                        {
                            ControladorTablero::rendirsePUT($datinhos);
                        }
                    }
                    break;
                case "perfil":
                        if ($controladorParametros->comprobarParametrosPostRecuperar($datinhos) && $controladorParametros->comprobarPasswd($datinhos))
                        {
                            $controlador->cambiarContraseña($datinhos);
                        }
                    break;
                    default: Auxiliar::controlErrores(Constantes::RUTA_INVALIDA, false, 404);
            }
        break;
        case "DELETE"; 
            switch ($parametros[1])
            {
                case "usuarios":
                if ($controladorParametros->comprobarUsernamePassword($datinhos))
                {
                    if ($controlador->comprobarAdmin($datinhos))
                    {
                        if ($controladorParametros->comprobarParametrosPUTyDELETE($parametros))
                        {// comprobarPorIDSOLO 
                            $controlador->eliminarUsuario($parametros[2]);
                        } 
                    }
                }
                break;
                case "rol":
                    if ($controladorParametros->comprobarUsernamePassword($datinhos))
                    {
                        if ($controlador->comprobarAdmin($datinhos))
                        {
                            if ($controladorParametros->comprobarDatosCrearRol($datinhos))
                            {
                                $controlador->borrarRol($datinhos);
                            }

                        }
                    }
                break;
                default: Auxiliar::controlErrores(Constantes::RUTA_INVALIDA, false, 404);
            }
        break;
        default;
            Auxiliar::controlErrores(Constantes::PARAMETROS_INVALIDOS, false, 400);
    }

} 
catch (Throwable $e)
{
    http_response_code(500);
    echo json_encode([
        'ErrorReal' => $e->getMessage(),
        'Linea' => $e->getLine(),
        'Archivo' => $e->getFile(),
        'ok' => false
    ]);
}

