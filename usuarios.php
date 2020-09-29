<?php
require_once "./manejadores.php";
require_once './JsonHandler.php';

class Usuario
{
    public $email;
    public $pass;
    public $tipo;
    public $imagen;

    function __construct($email = '', $pass = '', $tipo	= 'Usuario',$imagen = '')
    {
        $this->email = $email;
        $this->pass = $pass;
        $this->tipo = $tipo;
        $this->imagen = $imagen;
    }
    public static function guardarUsuario($object)
    {
        return JsonHandler::saveJson($object, 'Usuarios.json');
    }

    /* Trae los usuarios existentes */
    public static function getAll()
    {
        $archivoArray = (array) JsonHandler::readJson('Usuarios.json');
        $listaUsuarios = [];

        foreach ($archivoArray as $datos) {
            $nuevoUsuario = new Usuario($datos->email, $datos->pass, $datos->tipo);
            array_push($listaUsuarios, $nuevoUsuario);
        }

        return $listaUsuarios;
    }

    public static function getRole($email)
    {
        $archivoArray = (array) JsonHandler::readJson('Usuarios.json');
        $listaUsuarios = [];

        foreach ($archivoArray as $datos) {
            $nuevoUsuario = new Usuario($datos->email, $datos->pass, $datos->tipo);
            
            if($nuevoUsuario->email == $email){
                return $nuevoUsuario->tipo;
            }
        }

        // Default value.
        return 'usuario';
    }
    //me fijo si el usuario existe, si existe devuelvo true
    public static function exists($email)
    {
        $archivoArray = (array) JsonHandler::readJson('Usuarios.json');
        $listaUsuarios = [];

        foreach ($archivoArray as $datos) {
            $nuevoUsuario = new Usuario($datos->email, $datos->pass, $datos->tipo, $datos->imagen);
            if ($nuevoUsuario->email == $email) {
                return true;
            }
        }

        return false;
    }

    /* Verifica las credenciales de un usuario */
    public static function checkCredentials($email, $pass)
    {
        try {
            $usuarios = Usuario::getAll();

            foreach ($usuarios as $usuario) {
                if ($usuario->email == $email && $usuario->pass == $pass)
                    return true;
            }

            return false;
        } catch (Exception $e) {
            return false;
        }
    }


    /* Métodos mágicos */
    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __get($name)
    {
        return $this->$name;
    }
}




?>