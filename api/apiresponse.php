<?php
class ApiResponse
{
    public $status;
    public $message;
    public $data;

    function __construct($status, $message = '', $data = '')
    {
        $this->status = $status;
        $this->message = $message;
        $this->data = $data;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __get($name)
    {
        return $this->$name;
    }

    //respuesta generica de la api, parecido a Jsend
    public static function apiResponse($status, $message = '', $data = '')
    {
        if($status === true || $status === false){
            if($status == true){
                $status = "OK";
            }else $status = "Error";
            return json_encode(new ApiResponse($status, $message, $data), JSON_PRETTY_PRINT);
        }else return json_encode(new ApiResponse("Fail", "Error de parametros en APIResponse", "Solo se admite como status un tipo bool True o false"), JSON_PRETTY_PRINT);
    }
}


?>