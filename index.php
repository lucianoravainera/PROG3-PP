<?php
require_once './usuarios.php';
require_once './manejadores.php';
require_once './api/apiResponse.php';
require_once './JsonHandler.php';

$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['PATH_INFO'] ?? 0;
$token = $_SERVER['HTTP_TOKEN'] ?? '';

switch ($path) {
    case '/registro'://punto 1
        if ($method == 'POST')
        Manejadores::altaUsuarios();   
    break;
    case '/login'://punto 2
        Manejadores::Login();
    break;
    case '/ingreso'://punto4
        if ($method == 'POST') {
            if (!Manejadores::IsInRole('user')) {
                Manejadores::CrearAuto();
            } else {
                echo ApiResponse::apiResponse(false, 'No esta autorizado, solo Usuario');
            }
        }
    break;
    case '/retiro':
        if ($method == 'GET') {
            if (LoginController::IsInRole('user')) {
                $patente = explode('/', $path)[2];
                AutoController::Remove($patente);
            } else {
                echo ApiResponse::apiResponse(false, 'No esta autorizado, solo Usuario');
            }
        }
        break;
    default:
    echo ApiResponse::apiResponse(false, 'ruta invalida');
    break;
    }


?>