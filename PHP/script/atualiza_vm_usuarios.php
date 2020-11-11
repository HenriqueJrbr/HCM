<?php
set_time_limit(0);
session_start();
require 'D:/xampp/htdocs/sga/config.php';
global  $config;
try{
    $db = new PDO("mysql:dbname=".$config['dbname'].";host=".$config['host'], $config['dbuser'], $config['dbpass']);
}catch(PDOException $e){
    echo"Erro na Conexao ".$e->getMessage();
}

/********************************************************************************************************************
* Roda proc sp_sga_refresh_vm_usuarios_processos_riscos                                                             *
********************************************************************************************************************/
echo "<pre>";
// Busca todas as movimentações que estão pendentes
$sql = "
    SELECT
        atualiza        
    FROM
        z_sga_vm_usuarios_refresh
    WHERE
        atualiza = 1";
$sql = $db->query($sql);

if($sql->rowCount() > 0):
    echo '-------------------------------------------------------------------'."<br>";
    echo 'Rodando Proc: ' . date('d/m/Y H:i:s')."<br>";
    try{
        $db->query("CALL sp_sga_refresh_vm_usuarios_processos_riscos()");

        $sql = "
            UPDATE
                z_sga_vm_usuarios_refresh
            SET                            
                atualiza = 0";

        $sql = $db->query($sql);
    }catch (Exception $e){
        print_r($e);
    }

    echo 'Terminou de Rodar Proc: ' . date('d/m/Y H:i:s')."<br>";
else:
    echo 'Sem registros para atualizar';
endif;

