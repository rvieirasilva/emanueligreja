<?php
    session_start();
    //conexão
    //require_once "_con.php";
    //  require_once "_configuracao.php";

    session_unset();
    session_destroy();
    header('Location:entrar');
    exit();

