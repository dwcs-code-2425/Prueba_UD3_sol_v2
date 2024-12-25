<?php

/*
 * Funcións de sesión
 */

 //Si se realizan dos llamadas seguidas a session_start(), se producira un mensaje de E_NOTICE A session had already been started
//Comprobamos si ya hay una sesión iniciada previamente
function iniciarSesion(): bool {
    $iniciada = true;
    if (session_status() !== PHP_SESSION_ACTIVE) {
        $iniciada = session_start();
    }
    return $iniciada;
}

function addError(string $msg){
    iniciarSesion();
    $_SESSION["error"][] = $msg;
}