<?php
$host="localhost";
$bd="pokedex";
$usuario="root";
$constrasena="";

try {
    $conexion=new PDO("mysql:host=$host;dbname=$bd", $usuario, $constrasena);
   // if($conexion){echo "conectado... a sistema ";}

} catch (Exception $ex) {
    echo $ex->getMessage();
}
?>