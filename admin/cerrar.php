<?php
session_start() ; 
session_unset(); 
session_destroy();

$url_base = "http://localhost:3000/admin/";

    header("Location: " . $url_base . "sign-in.php");

?>