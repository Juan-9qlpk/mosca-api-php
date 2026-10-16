# Documentación Técnica - Juego de la Mosca

## Descripción del Proyecto

Este proyecto consiste en una **API REST de Backend** desarrollada en **PHP**, diseñada bajo la arquitectura **MVC (Modelo-Vista-Controlador)** para garantizar una clara separación entre la lógica de negocio, la gestión de datos y las respuestas del sistema.

### Aspectos Clave de la Arquitectura e Implementación:

* **Patrón DAO/DTO (Data Access Object / Data Transfer Object):** Desacopla la lógica de negocio del acceso directo a la base de datos. Los objetos DTO encapsulan y transportan la información entre capas, mientras que la capa DAO centraliza las operaciones CRUD y consultas persistentes.
* **Persistencia Segura con MySQLi Nativo:** Toda la comunicación con la base de datos MySQL se realiza mediante la extensión nativa `mysqli` utilizando exclusivamente **consultas preparadas (Prepared Statements)**, lo que inmuniza la aplicación contra ataques de inyección SQL.
* **Control de Acceso y Gestión de Roles (RBAC):** Sistema de autenticación y autorización que segmenta las capacidades y rutas disponibles según el perfil del usuario (**ADMIN** para administración global y **USER** para la dinámica del juego).
* **Integración de Servicios de Correo (PHPMailer):** Incorpora la librería **PHPMailer** para el envío automatizado de notificaciones por correo electrónico (como la recuperación de contraseñas mediante claves).

---

## Referencia de Endpoints y Procesos
Para consultar la especificación detallada de cada endpoint, ejemplos de payloads JSON, respuestas de éxito y códigos de error HTTP estandarizados (200, 201, 400, 403, 404), revisa la documentación oficial de la API:

**[Documentación Completa de Endpoints (`DOCUMENTACION_API_MOSCA.md`)](./DOCUMENTACION_API_MOSCA.md)**

---


## Configuración del Fichero `.env`

El proyecto cuenta con un archivo de variables de entorno (`.env`) situado en la raíz del proyecto para gestionar los parámetros de conexión a la base de datos y la configuración del servicio de correo electrónico de forma segura.

```env
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=moscafix
DB_USERNAME=
DB_PASSWORD=

SMTP_PASSWORD=
MAIL_PROPIO=
```

* **DB_HOST**: Dirección IP o host del servidor de base de datos (127.0.0.1 por defecto).
* **DB_PORT**: Puerto de escucha del servidor MySQL 3306.
* **DB_DATABASE**: Nombre de la base de datos asignada al proyecto.
* **DB_USERNAME**: Nombre de usuario para la autenticación en el servidor de base de datos.
* **DB_PASSWORD**: Contraseña correspondiente al usuario de la base de datos.
* **SMTP_PASSWORD**: Contraseña o token de aplicación empleado por PHPMailer para autenticarse en el servidor SMTP de correo.
* **MAIL_PROPIO**: Dirección de correo electrónico configurada como remitente para el envío de notificaciones automáticas a los usuarios.


## Lógica y Funcionamiento del Juego

El sistema implementa el desarrollo de partidas sobre un tablero unidimensional gestionado a través de las peticiones de los usuarios autenticados:

* **Creación del Tablero (`POST /crear`):**
  * El sistema comprueba mediante el método correspondiente (`yaExisteUnTablero()`) si el usuario ya cuenta con una partida activa. Si es así, se bloquea la creación devolviendo un error.
  * Permite definir un **tablero customizado** enviando parámetros específicos de tamaño e intentos en el cuerpo de la petición.
  * Si no se especifican parámetros o son inválidos, el backend inicializa un **tablero por defecto** utilizando las constantes predefinidas (`Constantes::TAMANO_DEFAULT = 10` y `Constantes::INTENTOS_DEFAULT = 5`), ocultando la mosca de forma aleatoria en una de las posiciones.

* **Mecánica de Juego / Manotazos (`POST /jugar`):**
  * El usuario envía la posición de la casilla a atacar. El motor evalúa la ubicación de la mosca y devuelve códigos numéricos específicos:
    * **Caso 1:** Mosca golpeada / Victoria (código de respuesta `1`), finalizando la partida con éxito.
    * **Caso 2:** "Casi le das" / Casilla adyacente (código de respuesta `2`), lo que asusta a la mosca (revolotea a otra posición) y descuenta un intento.
    * **Caso 3:** Fallo / Impacto lejano (código de respuesta `3`), restando un intento al contador del jugador.
    * **Caso -1:** Derrota automática al agotar la totalidad de los intentos disponibles, cambiando el estado de la partida a "perdido".

* **Intentos y Rendición:**
  * **`GET /intentos`:** Permite consultar de forma rápida los intentos restantes y el estado de la sesión activa.
  * **`PUT /rendirse`:** Permite al usuario abandonar la partida. El sistema actualiza el estado a "rendido", revelando la posición exacta en la que se encontraba la mosca (`Mosca`), el indicador de decepción y la representación en cadena del tablero.

* **Perfil y Seguridad:**
  * **`PUT /perfil`:** Gestiona la actualización de las credenciales de seguridad del usuario, permitiendo modificar de forma segura la contraseña asociada a su cuenta.

---

## Control de Acceso por Roles

La API protege las rutas mediante validaciones lógicas en los controladores (`comprobarUsuario` y `comprobarAdmin`), segmentando las capacidades de cada perfil según su identificador en la base de datos:

* **Rol JUGADOR / USER (ID `1` — Comprobación mediante `comprobarUsuario`):**
  * **Permisos autorizados:** Registro (`POST /registrar`), inicio de sesión (`POST /login`), creación de tablero custom o por defecto (`POST /crear`), ejecución de jugadas (`POST /jugar`), consulta de oportunidades (`GET /intentos`), rendición de partidas (`PUT /rendirse`) y actualización de contraseña en perfil (`PUT /perfil`).
  * **Restricciones:** No posee privilegios para invocar endpoints de administración, listados globales de usuarios ni modificación de roles del sistema.

* **Rol ADMIN / Administrador (ID `0` — Comprobación mediante `comprobarAdmin`):**
  * **Permisos autorizados:** Dispone de la totalidad de los privilegios del rol de usuario y añade acceso total de gestión administrativa.
  * **Endpoints exclusivos de administración:**
    * Consulta del listado global de usuarios registrados (`GET /usuarios`).
    * Consulta detallada de perfiles individuales por clave primaria (`GET /usuarios/{id}`).
    * Modificación dinámica de roles y datos de usuarios (`PUT /usuarios/{id}`).
    * Eliminación segura de usuarios (`DELETE /usuarios/{id}`).
    * Creación y borrado de roles personalizados en el sistema (`POST /rol` y `DELETE /rol`).
  * **Restricciones:** No posee privilegios para jugar, crear tableros o rendirse.
