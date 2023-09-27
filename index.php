<?php

/*
Verificamos si la solicitud HTTP incluye un encabezado HTTP_ORIGIN.
Este encabezado indica desde qué origen se está realizando la solicitud.
Si existe este encabezado, significa que se trata de una solicitud CORS
*/
if(isset($_SERVER['HTTP_ORIGIN']))
{
    // Establecemos tres encabezados de respuesta CORS:

    // Esto establece el encabezado Access-Control-Allow-Origin en la respuesta,
    // permitiendo que el origen especificado en HTTP_ORIGIN acceda a recursos en el servidor.
    // Esto habilita el acceso cruzado desde ese origen
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");

    // Habilita el envío de cookies y credenciales en la solicitud CORS, lo que es necesario en algunos escenarios de autenticación.
    header('Access-Control-Allow-Credentials: true');

    // Establece un tiempo de caché para la configuración CORS, en este caso, por un día (86400 segundos).
    // Esto significa que el navegador puede almacenar en caché la configuración CORS para futuras solicitudes desde el mismo origen,
    // evitando la necesidad de hacer una nueva solicitud de opciones preflight CORS en cada solicitud.
    header('Access-Control-Max-Age: 86400'); //Cache por un día
}

// El código se ejecuta cuando la solicitud HTTP es de tipo OPTIONS, que es una solicitud preflight que se utiliza para determinar
// qué métodos HTTP y encabezados son permitidos para una solicitud CORS específica
if($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
{
    // Se establecen los encabezados CORS apropiados para permitir los métodos HTTP
    // y encabezados específicos que se incluyeron en la solicitud de opciones preflight
    if(isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

    if(isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}

// Incluimos el archivo de la clase PersonaApi
require_once "PersonaApi.php";

// Creamos una instancia de la clase PersonaApi, que se encarga de manejar las solicitudes relacionadas con personas
$personaAPI = new PersonaApi();

// Se llama al método API()
$personaAPI->start();

?>