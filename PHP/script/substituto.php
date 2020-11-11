<?php

/* ==================================== */
/* Está função serve para movimentar todas as atividade do usuário 
	que está sendo substituido para o substituto*/
/* ==================================== */
require 'conexao.php';
require '../core/Model.php';
require '../models/Fluxo.php';
require '../models/Email.php';
require '../models/ConfiguracaoFluxo.php';

$conf = new ConfiguracaoFluxo();

$sql = "SELECT * FROM z_sga_fluxo_substituto where  status = '1'";
$sql = $db->query($sql);

if($sql->rowCount()>0):
	$sql =  $sql->fetchAll();

	foreach ($sql as $value):
		if($value['dataInicio'] == date("Y-m-d")):
			$usrSubstituido = $value['idUsrSub'];
			$usrSerSubstituido = $value['idUsrSerSub'];
			$retornoAtualiza =  $conf->atualizaSubMovimento($usrSubstituido,$usrSerSubstituido);
			if($retornoAtualiza > 0):
				echo "FOI ATUALIZADO A TABELA DE MOVIMENTACAO"."<br>";
			endif;
		endif;
		$dataFim =  date('Y-m-d', strtotime($value['dataFim']. ' + 1 days'));
		if($dataFim == date("Y-m-d")):
			$retorno = $conf->atualizaStatus($value['idSub']);
			if($retorno > 0):
				echo "FOI RETIRADO O ACESSO DO USUARIO SUBSTITUTO"."<br>";
			endif;
		endif;
	endforeach;
endif;