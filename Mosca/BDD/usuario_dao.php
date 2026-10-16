<?php
require_once __DIR__ . '/../Constantes/sqlqueries.php';
class UsuarioDAO{
    private $db;

    public function __construct(Database $db){
        $this->db = $db;
    }

    public function guardarUsuario(UsuarioDTO $usuario){
        $conexion = $this->db->connect();
        $stmt = $conexion->prepare(SqlQueries::GUARDAR_USUARIO);
        $nombre = $usuario->nombre;
        $edad = $usuario->edad;
        $email = $usuario->email;
        $pass = md5($usuario->pass);
        $stmt->bind_param('siss',  $nombre, $edad, $email, $pass);
        
        $ok = $stmt->execute();
        $stmt->close();
        $conexion->close();
        return $ok;
    }

    public function recuperarDatosUsuario($nombre, $pass)
    {
        $conexion = $this->db->connect();
        $stmt = $conexion->prepare(SqlQueries::RECUPERAR_DATOS_USUARIO);
        $hashedpw = md5($pass);
        $stmt->bind_param('ss', $nombre, $hashedpw);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $user = null;
        
        if ($fila = $resultado->fetch_assoc()) 
        {
            $user = new Usuario($fila["nombre"], $fila["email"], $fila["edad"],$fila["pass"]);
            $user->id = $fila["id"];
        }
        $stmt->close();
        $conexion->close();
        return $user;
    }

    public function obtenerTodosLosUsuarios()
    {
        $conexion = $this->db->connect();
        $query = SqlQueries::SELECCIONAR_TODOS_USUARIOS;
        $resultado = $conexion->query($query);
        $user = null;
        while ($fila = $resultado->fetch_assoc()) {
        $user = new Usuario($fila["nombre"], $fila["email"], $fila["edad"], $fila["pass"]);
        $user->id = $fila["id"];
        $usuarios[] = $user;
        }
        $conexion->close();
        return $usuarios;
    }
            
    public function obtenerUsuarioConcreto($id)
    {
        $conexion = $this->db->connect();
        $stmt = $conexion->prepare(SqlQueries::OBTENER_ID_USUARIO);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $user = null;
        
        if ($fila = $resultado->fetch_assoc()) 
        {
            $user = new Usuario($fila["nombre"], $fila["email"], $fila["edad"],$fila["pass"]);
            $user->id = $fila["id"];
        }
        $stmt->close();
        $conexion->close();
        return $user;
    }

    public function iniciarSesion($nombre, $pass)
    {
        $conexion = $this->db->connect();
        $stmt = $conexion->prepare(SqlQueries::INICIAR_SESION);
        $hashedpw = md5($pass);
        $stmt->bind_param('ss', $nombre, $hashedpw);
        $stmt->execute(); 
        $resultado = $stmt->get_result();
        $user = null;

        if ($fila = $resultado->fetch_assoc())
        {
            $user = new Usuario($fila["nombre"], $fila["email"], $fila["edad"], $fila["pass"]);
            $user->id = $fila["id"];
        }
        $stmt->close();
        $conexion->close();
        return $user;
    }
    
    public function actualizarDatosPass($id, $pass)
    {
        $conexion = $this->db->connect();
        $comprobarUsuario = $this->obtenerUsuarioConcreto($id);
            if ($comprobarUsuario) 
            {
                $stmt = $conexion->prepare(SqlQueries::ACTUALIZAR_PASS);
                $hashedpw = md5($pass);
                $stmt->bind_param('si', $hashedpw, $id);
                $ok = $stmt->execute();
                $stmt->close();
            }
        $conexion->close();
        return $ok;
    }

    public function borrarUsuario($id)
    {
        $conexion = $this->db->connect();
        $stmt = $conexion->prepare(SqlQueries::BORRAR_USUARIO);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $stmt->close();
        $conexion->close();
        return $resultado;
    }

    public function buscarUsuarioPorEmail($email)
    {
        $conexion = $this->db->connect();
        $stmt = $conexion->prepare(SqlQueries::BUSCAR_USUARIO_EMAIL);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        $user = null;
        if ($fila = $resultado->fetch_assoc())
        {
            $user = new Usuario($fila["nombre"], $fila["email"], $fila["edad"], $fila["pass"]);
            $user->id = $fila["id"];
        }
        $stmt->close();
        $conexion->close();
        return $user;
    }

    public function actualizarUserPassReseteada($datinhos, $contrasenaNueva)
    {
        $usuario = $this->buscarUsuarioPorEmail($datinhos["email"]);
        if ($usuario)
        {
            $conexion = $this->db->connect();
            $stmt = $conexion->prepare(SqlQueries::ACTUALIZAR_PASS_RESET);
            $hashedpw = md5($contrasenaNueva);
            $stmt->bind_param('si', $hashedpw , $usuario->id);
            $ok = $stmt->execute();
            $stmt->close();
        }
        return $ok;
    }

    public function buscarPorUsuario($nombre)
    {
        $conexion = $this->db->connect();
        $stmt = $conexion->prepare(SqlQueries::BUSCAR_USUARIO_NOMBRE);
        $stmt->bind_param('s', $nombre);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $existe = $resultado->num_rows > 0;
        $stmt->close();
        $conexion->close();
        return $existe;
    }


}