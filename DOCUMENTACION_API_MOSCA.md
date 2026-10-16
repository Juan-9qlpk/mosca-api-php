# Documentación de Endpoints API

### GET /intentos

* **Método HTTP:** GET

* **URL:** /intentos

* **Si requiere autenticación:** Sí

* **Rol necesario:** user

* **Parámetros:** Ninguno

* **Body:**

```
{
    "nombre": "pepe2",
    "pass": "prueba"
}

```

* **Respuesta correcta:**
  codigo http utilizado -> 200 OK

```
{
    "sesion": "Has iniciado sesión correctamente",
    "intentos": 5,
    "ok": true
}

```

* **Posibles errores:**

  * No hay un tablero creado -> 404 Not Found

```
{
    "sesion": "Has iniciado sesión correctamente",
    "Error": "No hay un tablero creado",
    "ok": false
}

```

* Parámetros Inválidos -> 400 Bad Request

```
{
    "Error": "Parámetros Inválidos",
    "ok": false
}

```

* Login Inválido -> 400 Bad Request

```
{
    "mensaje": "Login Inválido",
    "ok": false
}

```

* No tienes permisos suficientes -> 403 Forbidden

```
{
    "mensaje": "No tienes permisos suficientes",
    "ok": false
}

```

### GET /usuarios

* **Método HTTP:** GET

* **URL:** /usuarios

* **Si requiere autenticación:** Sí

* **Rol necesario:** admin

* **Parámetros:** Ninguno

* **Body:**

```
{
    "nombre": "pepeadmin",
    "pass": "prueba"
}

```

* **Respuesta correcta:**
  codigo http utilizado -> 200 OK

```
[
    {
        "id": "1",
        "nombre": "pepeadmin",
        "email": "pepetijeras@gmail.com"
    },
    {
        "id": "2",
        "nombre": "pepeplebe",
        "email": "pepeplebe@gmail.com"
    },
    {
        "id": "3",
        "nombre": "pepe2",
        "email": "pepetijeras23@gmail.com"
    }
]

```

* **Posibles errores:**

  * Parámetros Inválidos -> 400 Bad Request

```
{
    "Error": "Parámetros Inválidos",
    "ok": false
}

```

* Login Inválido -> 400 Bad Request

```
{
    "mensaje": "Login Inválido",
    "ok": false
}

```

* No tienes permisos suficientes -> 403 Forbidden

```
{
    "mensaje": "No tienes permisos suficientes",
    "ok": false
}

```

### GET /usuarios/{id}

* **Método HTTP:** GET

* **URL:** /usuarios/3

* **Si requiere autenticación:** Sí

* **Rol necesario:** admin

* **Parámetros:** `id`

* **Body:**

```
{
    "nombre": "pepeadmin",
    "pass": "prueba"
}

```

* **Respuesta correcta:**
  codigo http utilizado -> 200 OK

```
{
    "sesion": "Has iniciado sesión correctamente",
    "usuario": "pepe2",
    "email": "pepetijeras23@gmail.com"
}

```

* **Posibles errores:**

  * El usuario con ese id no existe -> 404 Not Found

```
{
    "Error": "El usuario con ese id no existe",
    "ok": false
}

```

* Parámetros Inválidos -> 400 Bad Request

```
{
    "Error": "Parámetros Inválidos",
    "ok": false
}

```

* Login Inválido -> 400 Bad Request

```
{
    "mensaje": "Login Inválido",
    "ok": false
}

```

* No tienes permisos suficientes -> 403 Forbidden

```
{
    "mensaje": "No tienes permisos suficientes",
    "ok": false
}

```

### POST /login

* **Método HTTP:** POST

* **URL:** /login

* **Si requiere autenticación:** No

* **Rol necesario:** Ninguno

* **Parámetros:** Ninguno

* **Body:**

```
{
    "nombre": "pepe2",
    "pass": "prueba"
}

```

* **Respuesta correcta:**
  codigo http utilizado -> 200 OK

```
{
    "mensaje": "Logeado con éxito",
    "nombre": "pepe2",
    "ok": true
}

```

* **Posibles errores:**

  * Login Inválido -> 400 Bad Request

```
{
    "mensaje": "Login Inválido",
    "ok": false
}

```

* Parámetros Inválidos -> 405 Method Not Allowed

```
{
    "Error": "Parámetros Inválidos",
    "ok": false
}

```

### POST /registrar

* **Método HTTP:** POST

* **URL:** /registrar

* **Si requiere autenticación:** No

* **Rol necesario:** Ninguno

* **Parámetros:** Ninguno

* **Body:**

```
{
    "nombre": "pepe2",
    "email": "pepetijeras23@gmail.com",
    "edad": 58,
    "pass": "prueba"
}

```

* **Respuesta correcta:**
  codigo http utilizado -> 201 Created

```
{
    "mensaje": "Usuario creado con éxito",
    "nombre": "pepe2",
    "email": "pepetijeras23@gmail.com",
    "ok": true
}

```

* **Posibles errores:**

  * El correo o nombre de Usuario ya están en uso -> 400 Bad Request

```
{
    "Error": "El correo o nombre de Usuario ya están en uso",
    "ok": false
}

```

* Introduce una edad válida -> 400 Bad Request

```
{
    "Error": "Introduce una edad válida",
    "ok": false
}

```

* El formato de email no es valido -> 400 Bad Request

```
{
    "Error": "El formato de email no es valido",
    "ok": false
}

```

* Parámetros Inválidos -> 400 Bad Request

```
{
    "Error": "Parámetros Inválidos",
    "ok": false
}

```

### POST /crear

* **Método HTTP:** POST

* **URL:** /crear

* **Si requiere autenticación:** Sí

* **Rol necesario:** user

* **Parámetros:** Ninguno

* **Body (Tablero Custom):**

```
{
    "nombre": "pepe2",
    "pass": "prueba",
    "tamano": 10,
    "intentos": 5
}

```

* **Body (Tablero Default - si se envían tamaño/intentos inválidos):**

```
{
    "nombre": "pepe2",
    "pass": "prueba"
    "tamaño": "asd",
    "intentos": 5,
}

```

* **Respuesta correcta (Tablero Custom):**
  codigo http utilizado -> 201 Created

```
{
    "sesion": "Has iniciado sesión correctamente",
    "datos ": "Se han introducido parametros adecuados para la creación del tablero",
    "mensaje": "Tablero creado con éxito",
    "tamaño": 10,
    "intentos": 5,
    "ok": true
}

```

* **Respuesta correcta (Tablero Default):**
  codigo http utilizado -> 201 Created

```
{
    "sesion": "Has iniciado sesión correctamente",
    "datos ": "Parámetros Inválidos",
    "mensaje": "Se han introducido parametros invalidos, se creará un tablero con parametros por default",
    "tamaño": 10,
    "intentos": 5,
    "ok": true
}

```

* **Posibles errores:**

  * Ya hay un tablero creado -> 400 Bad Request

```
{
    "sesion": "Has iniciado sesión correctamente",
    "Error": "Ya hay un tablero creado",
    "ok": false
}

```

* Parámetros Inválidos -> 400 Bad Request

```
{
    "Error": "Parámetros Inválidos",
    "ok": false
}

```

* No tienes permisos suficientes -> 403 Forbidden

```
{
    "mensaje": "No tienes permisos suficientes",
    "ok": false
}

```

### POST /jugar

* **Método HTTP:** POST

* **URL:** /jugar

* **Si requiere autenticación:** Sí

* **Rol necesario:** user

* **Parámetros:** Ninguno

* **Body:**

```
{
    "nombre": "pepe2",
    "pass": "prueba",
    "posicion": 3
}

```

* **Respuesta correcta:**
  codigo http utilizado -> 200 OK

```
{
    "sesion": "Has iniciado sesión correctamente",
    "cod": 1,
    "mensaje": "Mosca golpeada",
    "intentos": 4,
    "ok": true
}

```

* **Posibles errores:**

  * No hay un tablero creado -> 404 Not Found

```
{
    "sesion": "Has iniciado sesión correctamente",
    "Error": "No hay un tablero creado",
    "ok": false
}

```

* Parámetros Inválidos -> 400 Bad Request

```
{
    "Error": "Parámetros Inválidos",
    "ok": false
}

```

* Te has quedado sin intentos -> 200 OK

```
{
    "sesion": "Has iniciado sesión correctamente",
    "mensaje": "Te has quedado sin intentos",
    "estado": "Has perdido",
    "ok": true
}

```

* No tienes permisos suficientes -> 403 Forbidden

```
{
    "mensaje": "No tienes permisos suficientes",
    "ok": false
}

```

### POST /recuperar

* **Método HTTP:** POST

* **URL:** /recuperar

* **Si requiere autenticación:** No

* **Rol necesario:** Ninguno

* **Parámetros:** Ninguno

* **Body:**

```
{
    "nombre": "pepe2",
    "email": "pepetijeras23@gmail.com"
}

```

* **Respuesta correcta:**
  codigo http utilizado -> 200 OK

```
{
    "mensaje": "Se ha enviado el correo de recuperacion",
    "ok": true
}

```

* **Posibles errores:**

  * El correo no existe -> 400 Bad Request

```
{
    "mensaje": "El correo no existe",
    "ok": false
}

```

* Email o nombre de usuarios inválidos -> 400 Bad Request

```
{
    "Error": "Email o nombre de usuarios inválidos",
    "ok": false
}

```

* Parámetros Inválidos -> 400 Bad Request

```
{
    "Error": "Parámetros Inválidos",
    "ok": false
}

```

### POST /rol

* **Método HTTP:** POST

* **URL:** /rol

* **Si requiere autenticación:** Sí

* **Rol necesario:** admin

* **Parámetros:** Ninguno

* **Body:**

```
{
    "nombre": "pepeadmin",
    "pass": "prueba",
    "rol": "moderador"
}

```

* **Respuesta correcta:**
  codigo http utilizado -> 201 Created

```
{
    "sesion": "Has iniciado sesión correctamente",
    "rol": "El rol moderador se ha creado con exito",
    "ok": true
}

```

* **Posibles errores:**

  * El rol ya existe -> 400 Bad Request

```
{
    "sesion": "Has iniciado sesión correctamente",
    "mensaje": "El rol: moderador ya existe",
    "ok": false
}

```

* Parámetros Inválidos -> 400 Bad Request

```
{
    "Error": "Parámetros Inválidos",
    "ok": false
}

```

* No tienes permisos suficientes -> 403 Forbidden

```
{
    "mensaje": "No tienes permisos suficientes",
    "ok": false
}

```

### PUT /usuarios/{id}

* **Método HTTP:** PUT

* **URL:** /usuarios/3

* **Si requiere autenticación:** Sí

* **Rol necesario:** admin

* **Parámetros:** `id`

* **Body:**

```
{
    "nombre": "pepeadmin",
    "pass": "prueba",
    "rol": "admin"
}

```

* **Respuesta correcta:**
  codigo http utilizado -> 200 OK

```
{
    "nombre": "pepe2",
    "rol_id": 0,
    "rol_nombre": "admin",
    "cambios": true
}

```

* **Posibles errores:**

  * El usuario con ese id no existe -> 404 Not Found

```
{
    "Error": "El usuario con ese id no existe",
    "ok": false
}

```

* El nombre del rol no existe -> 404 Not Found

```
{
    "mensaje": "El nombre del rol no existe",
    "ok": false
}

```

* El usuario ya cuenta con ese rol -> 400 Bad Request

```
{
    "sesion": "Has iniciado sesión correctamente",
    "mensaje": "El usuario ya cuenta con ese rol",
    "ok": false
}

```

* Parámetros Inválidos -> 400 Bad Request

```
{
    "Error": "Parámetros Inválidos",
    "ok": false
}

```

* No tienes permisos suficientes -> 403 Forbidden

```
{
    "mensaje": "No tienes permisos suficientes",
    "ok": false
}

```

### PUT /rendirse

* **Método HTTP:** PUT

* **URL:** /rendirse

* **Si requiere autenticación:** Sí

* **Rol necesario:** user

* **Parámetros:** Ninguno

* **Body:**

```
{
    "nombre": "pepe2",
    "pass": "prueba"
}

```

* **Respuesta correcta:**
  codigo http utilizado -> 200 OK

```
{
    "sesion": "Has iniciado sesión correctamente",
    "estado": "Te has rendido",
    "Mosca": 4,
    "decepcion": true,
    "tablero": "0000100000"
}

```

* **Posibles errores:**

  * No hay un tablero creado -> 404 Not Found

```
{
    "sesion": "Has iniciado sesión correctamente",
    "Error": "No hay un tablero creado",
    "ok": false
}

```

* Parámetros Inválidos -> 400 Bad Request

```
{
    "Error": "Parámetros Inválidos",
    "ok": false
}

```

* No tienes permisos suficientes -> 403 Forbidden

```
{
    "mensaje": "No tienes permisos suficientes",
    "ok": false
}

```

### PUT /perfil

* **Método HTTP:** PUT

* **URL:** /perfil

* **Si requiere autenticación:** Sí

* **Rol necesario:** user

* **Parámetros:** Ninguno

* **Body:**

```
{
    "nombre": "pepe2",
    "email": "pepetijeras23@gmail.com",
    "pass": "prueba",
    "passNueva": "nuevopass"
}

```

* **Respuesta correcta:**
  codigo http utilizado -> 200 OK

```
{
    "mensaje": "Se ha actualizado la nueva contraseña",
    "ok": true
}

```

* **Posibles errores:**

  * Parámetros Inválidos -> 400 Bad Request

```
{
    "Error": "Parámetros Inválidos",
    "ok": false
}

```

* Email o nombre de usuarios inválidos -> 400 Bad Request

```
{
    "Error": "Email o nombre de usuarios inválidos",
    "ok": false
}

```

* No tienes permisos suficientes -> 403 Forbidden

```
{
    "mensaje": "No tienes permisos suficientes",
    "ok": false
}

```

### DELETE /usuarios/{id}

* **Método HTTP:** DELETE

* **URL:** /usuarios/3

* **Si requiere autenticación:** Sí

* **Rol necesario:** admin

* **Parámetros:** `id`

* **Body:**

```
{
    "nombre": "pepeadmin",
    "pass": "prueba"
}

```

* **Respuesta correcta:**
  codigo http utilizado -> 200 OK

```
{
    "mensaje": "Usuario borrado con éxito",
    "nombre": "pepe2",
    "id": "3",
    "ok": true
}

```

* **Posibles errores:**

  * El usuario con ese id no existe -> 404 Not Found

```
{
    "Error": "El usuario con ese id no existe",
    "ok": false
}

```

* Parámetros Inválidos -> 400 Bad Request

```
{
    "Error": "Parámetros Inválidos",
    "ok": false
}

```

* No tienes permisos suficientes -> 403 Forbidden

```
{
    "mensaje": "No tienes permisos suficientes",
    "ok": false
}

```

### DELETE /rol

* **Método HTTP:** DELETE

* **URL:** /rol

* **Si requiere autenticación:** Sí

* **Rol necesario:** admin

* **Parámetros:** Ninguno

* **Body:**

```
{
    "nombre": "pepeadmin",
    "pass": "prueba",
    "rol": "moderador"
}

```

* **Respuesta correcta:**
  codigo http utilizado -> 200 OK

```
{
    "sesion": "Has iniciado sesión correctamente",
    "rol": "El rol moderador se ha borrado con exito",
    "ok": true
}

```

* **Posibles errores:**

  * El nombre del rol no existe -> 404 Not Found

```
{
    "mensaje": "El nombre del rol no existe",
    "ok": false
}

```

* Parámetros Inválidos -> 400 Bad Request

```
{
    "Error": "Parámetros Inválidos",
    "ok": false
}

```

* No tienes permisos suficientes -> 403 Forbidden

```
{
    "mensaje": "No tienes permisos suficientes",
    "ok": false
}

```