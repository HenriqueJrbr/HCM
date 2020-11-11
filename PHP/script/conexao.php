<?php
/**
 * Created by PhpStorm.
 * User: a2
 * Date: 07/01/2019
 * Time: 15:30
 */
require '../config.php';
global $config;

try{
    $db = new PDO("mysql:dbname=".$config['dbname'].";host=".$config['host'], $config['dbuser'], $config['dbpass']);
}catch(PDOException $e){
    echo"Erro na Conexao ".$e->getMessage();
}