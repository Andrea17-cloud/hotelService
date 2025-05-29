<?php

$servidor = "172.17.85.123";
$basededatos = "HEP";
$usuario = "root";
$contraseña = "admin-123";
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