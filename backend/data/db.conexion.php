<?php

$servidor = "172.22.160.223";
$basededatos = "HEP";
$usuario = "root";
$contraseña = "admin1306";
$puerto = 3307;

try {
    $conexion = new PDO(
        "mysql:host=$servidor;
        port=$puerto;dbname=$basededatos;
        charset=utf8",
        $usuario,
        $contraseña
    ) ;

    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

} catch (PDOException $error) { 
    die("Error de conexión: " . $error->getMessage()); 
}


?>