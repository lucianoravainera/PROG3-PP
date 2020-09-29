<?php
require_once './archivos.php';
require_once './JWT.php';
require_once './auto.php';

class Manejadores {
    //registro de usuarios:
    public static function altaUsuarios()
    {
        if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['tipo']) && isset($_FILES['imagen']['tmp_name'])) {
            $destino="./imagenes/";
            $email = $_POST['email'];
            //PassManager::Hash($_POST['password']);
            $pass = Manejadores::hash($_POST['password']); //creo un hash de la contraseña
            $tipo = $_POST['tipo'] ?? 'Usuario';
            $imagen = $_FILES['imagen']['tmp_name'];

            if (Usuario::exists($email)) {
                echo ApiResponse::apiResponse(false, 'El email ya se encuentra registrado');
            } else {
                if ($tipo != 'admin' && $tipo != 'usuario')
                    $tipo = 'usuario';

                $imagenFinal = Archivos::cargarArchivo($imagen,$destino,$_FILES);//guardo la foto
                $usuario = new Usuario($email, $pass, $tipo, $imagenFinal);
                $resultado = Usuario::guardarUsuario($usuario);
                echo ApiResponse::apiResponse($resultado, 'Usuario creado con exito!', $usuario->email);
            }
        } else {
            echo ApiResponse::apiResponse(false, 'Debe ingresar Usuario, pass tipo e imagen para continuar');
        }
    }


//logueo de usuario
public static function Login()
{
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $pass = Manejadores::hash($_POST['password']); //creo un hash de la contraseña//$_POST['password']; 

        if (Manejadores::validarCredenciales($email, $pass)) {
            $rol =  Usuario::getRole($email);
            $token = Token::getToken($email, $rol);

            // Realiza autenticaciónn.
            echo ApiResponse::apiResponse(true, 'Logueado con exito ' . $rol, $token);
        } else {
            echo ApiResponse::apiResponse(false, 'Credenciales invalidas.');
        }
    } else {
        echo ApiResponse::apiResponse(false, 'Email y password obligatorios');
    }
}

public static function validarCredenciales($email, $pass)
{
    try {
        $usuarios = Manejadores::traerUsuarios();
        foreach ($usuarios as $usuario) {
            if ($usuario->email == $email && $usuario->pass == $pass)
                return true;
        }

        return false;
    } catch (Exception $e) {
        return false;
    }
}

public static function traerUsuarios()
{
    $archivoArray = (array) JsonHandler::readJson('Usuarios.json');
    $listaUsuarios = [];

    foreach ($archivoArray as $datos) {
        $nuevoUsuario = new Usuario($datos->email, $datos->pass, $datos->tipo, $datos->imagen);
        array_push($listaUsuarios, $nuevoUsuario);
    }

    return $listaUsuarios;
}

public static function Hash(string $pass)
    {
        return hash('SHA512', $pass);
    }

    public static function IsInRole($role)
    {
        $token = getallheaders()['token'] ?? '';

        if (!empty($role)) {
            if (!empty($token) && $token != '{{token}}') {
                $isInRole = Token::isInRole($token, $role);

                return $isInRole;
            } else {
                echo ApiResponse::apiResponse(false, 'Token invalido');
            }
        } else {
            echo ApiResponse::apiResponse(false, 'Tipo de usuario no autorizado');
        }
    }

    public static function CrearAuto()
    {
        if (isset($_POST['patente'])) {

            $patente = $_POST['patente'];

            $auto = new Auto($patente, time(),time(), Token::getEmail());
            $resultado = Auto::save($auto);
            echo ApiResponse::apiResponse($resultado, $resultado ? 'Auto almacenado' : 'El auto ya se encuentra registrado.', $resultado ? $auto : null);
        }
    }

}



?>