<?php
class Archivos{

    static function cargarArchivo($origen,$destino,$archivo) {
        if($archivo["imagen"]["size"]<350000000){
            //verifico que sea imagen:
            $esImagen = getimagesize($archivo["imagen"]["tmp_name"]);
            if($esImagen){//subo la imagen $destino="imagenes/"
                $aleatorio=rand(100,1000000);
                $ext=".png";//hacer bien
                $destino =$destino."Imagen".'.'.$aleatorio.$ext;
                $subido=move_uploaded_file($origen,$destino);
                echo ApiResponse::apiResponse(true, 'Se subio el archivo', $subido);
                return $subido;
            }echo ApiResponse::apiResponse(false, 'El archivo no es una imagen');
            return; 
        }echo ApiResponse::apiResponse(false, 'El archivo es demasiado grande');
        return;
    }





}



?>