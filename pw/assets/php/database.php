<?php
session_start();
ob_start();


$hasDB = false;
$server = $Servidor;
$user =$Usuario;
$pass =$Password;
$db = $BaseDeDatos;

$mySQL = new mysqli($server,$user,$pass,$db);
if ($mySQL->connect_error)
{
    die('Connect Error (' . $mySQL->connect_errno . ') '. $mySQL->connect_error);
}
$acentos = $mySQL->query("SET NAMES 'utf8'");
?>