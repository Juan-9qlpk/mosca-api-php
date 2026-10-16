<?php
class SqlQueries{

    /*
    QUERIES DEL TABLERO
    */
    public const GUARDAR_TABLERO_GENERADO = "INSERT INTO tableros (casillas, intentos, intentos_iniciales, estado, posicion_mosca, id_usuario) VALUES (?,?,?,?,?,?)";

    public const GUARDAR_TABLERO = "INSERT INTO tableros (tablero, intentos, estado) VALUES (?,?,?)";

    public const OBTENER_TABLERO = "SELECT * FROM tableros WHERE estado = ? and id_usuario = ?";

    public const MODIFICAR_TABLERO_INTENTOS = "UPDATE tableros set intentos = ? where id = ?";

    public const MODIFICAR_ARRAY_TABLERO = "UPDATE tableros set casillas = ?, intentos = ?, posicion_mosca = ? where id = ?";

    public const MODIFICAR_TABLERO_STATUS = "UPDATE tableros set estado = ? where id = ?";

    
    /*
    QUERIES DEL USUARIO
    */

    public const BUSCAR_USUARIO_NOMBRE = "SELECT nombre from usuarios where nombre = ?";

    public const GUARDAR_USUARIO = "INSERT INTO usuarios (nombre, edad, email, pass) VALUES (?,?,?,?)";

    public const RECUPERAR_DATOS_USUARIO = "SELECT * FROM usuarios where nombre = ? and pass = ?";

    public const SELECCIONAR_TODOS_USUARIOS = "SELECT * FROM usuarios";

    public const OBTENER_ID_USUARIO = "SELECT * from usuarios where id = ?";

    public const INICIAR_SESION = "SELECT * FROM usuarios where nombre = ? and pass = ?";

    public const ACTUALIZAR_PASS = "UPDATE usuarios SET pass = ? WHERE id = ?";

    public const BORRAR_USUARIO = "DELETE FROM usuarios where id = ?";

    public const BUSCAR_USUARIO_EMAIL = "SELECT id, nombre, email, edad, pass from usuarios where email = ?";

    public const ACTUALIZAR_PASS_RESET = "UPDATE usuarios set pass = ? where id = ?";

    /*
    QUERIES DE ROLES_USUARIOS
    */
    
    public const ANADIR_ROL_A_USUARIO = "INSERT INTO roles_usuarios (id_usuario, id_rol) VALUES (?,?)";

    public const REMOVER_ROL_A_USUARIO = "DELETE FROM roles_usuarios where id_usuario = ? and id_rol = ?";

    public const ACTUALIZAR_ROL_A_USUARIO = "UPDATE roles_usuarios set id_rol = ? where id_usuario = ? and id_rol = ?";

    public const COMPROBAR_ROL_USUARIO = "SELECT id_rol FROM roles_usuarios where id_usuario = ?";

    /*
    QUERIES DE ROL
    */
    public const ANADIR_ROL = "INSERT INTO roles (rol) VALUES (?)";

    public const REMOVER_ROL = "DELETE FROM roles where rol = ?";

    public const OBTENER_TODOS_ROLES = "SELECT * FROM roles";

    public const CONSEGUIR_ID_ROL = "SELECT id from roles where rol  = ?";

    public const CONSEGUIR_NOMBRE_ROL = "SELECT rol from roles where id  = ?";


}





/*
IGNORAR
    public const OBTENER_TABLERO = "SELECT tableros.* FROM tableros join usuarios as u on tableros.id_usuario = u.id 
    WHERE estado = ? and u.id=?";
*/
