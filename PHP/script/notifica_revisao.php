<?php
set_time_limit(0);
session_start();

require 'conexao.php';
require '../core/Model.php';
require '../models/Fluxo.php';
require '../models/Manutencao.php';
require '../models/ExecBO.php';
require '../models/Email.php';

/********************************************************************************************************************
* A REGRA PARA REMOÇÃO DE ACESSOS E FINALIZAÇÃO DE SOLICITAÇÃO É A SEGUINTE                                         *
* Se DataAtual >= (dataMovimentacao + diasAtraso) && status == 0                                                    *
* ----------------------------------------------------------------------------------------------------------------- *
* A REGRA PARA NOTIFICAR O RESPONSÁVEL PELO ATRASO DA MOVIMENTAÇÃO É A SEGUINTE                                     *
* Se dataAtual >= ((dataMovimentacao + diasAtraso) - diasNotifica)                                                  *
********************************************************************************************************************/
echo "<pre>";
// Busca todas as movimentações que estão pendentes
$sql = "
    SELECT
        fm.idAtividade,    
        fm.idMovimentacao,
        DATE(fm.dataMovimentacao) AS dataMovimentacao,
        fm.idSolicitante,
        GROUP_CONCAT(fm.idResponsavel SEPARATOR ',') AS idResponsavel,
        fm.idSolicitacao,
        fm.form,
        frm.descricao,
        fa.diasAtraso,
        fa.diasNotifica,
        fd.documento,
        f.parametros
    FROM
        z_sga_fluxo_movimentacao fm
    LEFT JOIN
        z_sga_fluxo_atividade fa
        ON fm.idAtividade = fa.id
    LEFT JOIN
        z_sga_fluxo_documento fd
        ON fm.idSolicitacao = fd.idSolicitacao
    LEFT JOIN
	    z_sga_fluxo f
        ON fa.idFluxo = f.idFluxo
    LEFT JOIN
        z_sga_fluxo_form frm
        ON frm.idForm = f.idFluxo
    WHERE
        fm.status = 1
    GROUP BY
	    fm.idSolicitacao
    ORDER BY
        fm.dataMovimentacao ASC
";
$sql = $db->query($sql);

if($sql->rowCount() > 0):
    $movimentacoes = $sql->fetchAll(PDO::FETCH_ASSOC);


    // Percorre as movimentações encontradas
    foreach($movimentacoes as $valMov):
        $documento = json_decode($valMov['documento']);
        $dataMovimentacao = date('Y-m-d', strtotime($valMov['dataMovimentacao']));
        $jsonParam = json_decode($valMov['parametros'], true);
        $iniciaNotificacao = date('Y-m-d', strtotime($dataMovimentacao.' + '.$jsonParam['diasNotifica'].' days'));
        $iniciaNotificacaoGestor = date('Y-m-d', strtotime($dataMovimentacao.' + '.$valMov['diasAtraso'].' days'));

        echo '-------------------------------------------------------------------'."<br>";
        echo 'Solicitação: ' . $valMov['idSolicitacao']."<br>";
        //echo 'Data Movimentação: ' . $dataMovimentacao . ' ---- Expira apartir de ' . $dataExpira ."<br>";
        echo 'Data Movimentação: ' . $dataMovimentacao . ' ---- Notificar apartir de ' . $iniciaNotificacao ."<br>";

        echo '----------------------------- EXPIROU -----------------------------'."<br><br><br>";
        //continue;
        //die('dd');
        /********************************************************************************************************************
        * SE NÃO EXPIROU VALIDA SE ESTÁ DENTRO DO PERÍODO DE NOTIFICAÇÃO E NOTIFICA CASO ESTEJA                             *
        * A REGRA PARA NOTIFICAR O RESPONSÁVEL PELO ATRASO DA MOVIMENTAÇÃO É A SEGUINTE                                     *
        * Se dataAtual >= ((dataMovimentacao + diasAtraso) - diasNotifica) && <= DataExpira                                 *
        ********************************************************************************************************************/
        if((strtotime(date('Y-m-d')) >= strtotime(date('Y-m-d', strtotime($iniciaNotificacao)))) &&  !in_array($valMov['form'], [3])):

            $m = new Manutencao();
            $fluxo = new Fluxo();
            $email = new Email();
            $gruposInfo = array();
            $assunto        = $valMov['descricao'];
            $nomeRemetente  = "SGA - Sistema de Gestão de Acesso";

            /***********************************  Envia email para o SOLICITANTE  ************************************/
            $sql = "
                SELECT DISTINCT
                    l.email,
                    u.nome_usuario,
                    u.cod_usuario                
                FROM
                    z_sga_param_login l
                LEFT JOIN
                    z_sga_usuarios u
                    ON u.z_sga_usuarios_id = l.idTotovs
                WHERE                
                    l.idTotovs = ".$valMov['idSolicitante']."
                    #AND ue.idEmpresa = 1";

            $rsSolic = $db->query($sql);
            if($rsSolic->rowCount() > 0):
                $dadosSolic = $rsSolic->fetch(PDO::FETCH_ASSOC);

                echo '---------------------------- NOTIFICOU ----------------------------'."<br>".$dadosSolic['email']."<br><br>";
                $email = new email();

                // Cria html de email a ser enviado.
                $mensagem = "<!DOCTYPE html>
                <html>
                    <head><title></title></head>
                    <body>  
                        <h3>Olá! ".$dadosSolic['nome_usuario']."</h3>
                        <p style=\"font-size:14px;margin-top:20px\">A solicitação de número <b>".$valMov['idSolicitacao']."</b>, a seguir encontra-se em atraso.</p>
                        <p style=\"font-size:14px;margin-top:20px\">Solicitação: <b>".$valMov['descricao']." </b></p>
                        <p style=\"font-size:14px;margin-top:20px\">Acesse: <a href='".URL."/fluxo/centralDeTarefa'>".URL."/fluxo/centralDeTarefa</a></p>
                    </body>
                </html>";

                $template = $email->getTemplate($mensagem);
                $email->enviaEmail($nomeRemetente,$assunto, $template, $dadosSolic['email']);
            endif;
            /*********************************** FIM Envia email para o SOLICITANTE  ************************************/

            /***********************************  Envia email para os acompanhantes caso exista  ************************************/
            if((isset($documento->idAcompanhante[0]) && !empty($documento->idAcompanhante[0])) && $jsonParam['avisaAcompanhante']):
                foreach($documento->idAcompanhante as $idAcompanhante):
                    $sql = "
                        SELECT DISTINCT
                            l.email,
                            u.nome_usuario,
                            u.cod_usuario                
                        FROM
                            z_sga_param_login l
                        LEFT JOIN
                            z_sga_usuarios u
                            ON u.z_sga_usuarios_id = l.idTotovs
                        WHERE                
                            l.idTotovs = $idAcompanhante
                            #AND ue.idEmpresa = 1";

                    $rsSolic = $db->query($sql);
                    if($rsSolic->rowCount() > 0):
                        $dadosSolic = $rsSolic->fetch(PDO::FETCH_ASSOC);
                        echo '---------------------------- NOTIFICOU ----------------------------'."<br>".$dadosSolic['email']."<br><br>";
                        $email = new email();

                        // Cria html de email a ser enviado.
                        $mensagem = "<!DOCTYPE html>
                            <html>
                                <head><title></title></head>
                                <body>  
                                    <h3>Olá! ". $dadosSolic['nome_usuario']."</h3>
                                    <p  style=\"font-size:14px;margin-top:20px\">A solicitação de número <b>".$valMov['idSolicitacao']."</b> a seguir, que você está em cópia encontra-se em atraso.</p>
                                    <p style=\"font-size:14px;margin-top:20px\">Solicitação: <b>".$valMov['descricao']." </b></p>
                                    <p style=\"font-size:14px;margin-top:20px\">Acesse: <a href='".URL."/fluxo/centralDeTarefa'>".URL."/fluxo/centralDeTarefa</a></p>
                                </body>
                            </html>";

                        $template = $email->getTemplate($mensagem);
                        $email->enviaEmail($nomeRemetente,$assunto, $template, $dadosSolic['email']);
                    endif;
                endforeach;
            endif;
            /*********************************** FIM Envia email para os acompanhantes caso exista  ************************************/
        endif;

        if((strtotime(date('Y-m-d')) >= strtotime(date('Y-m-d', strtotime($iniciaNotificacaoGestor)))) &&  !in_array($valMov['form'], [3])):
            /***********************************  Envia email para o gestor  ************************************/
            $expGestor = explode(',', $valMov['idResponsavel']);
            foreach ($expGestor as $idResponsavel):
                $sql = "
                    SELECT DISTINCT
                        l.email,
                        u.nome_usuario,
                        u.cod_usuario                
                    FROM
                        z_sga_param_login l
                    LEFT JOIN
                        z_sga_usuarios u
                        ON u.z_sga_usuarios_id = l.idTotovs
                    WHERE                
                        l.idTotovs = $idResponsavel
                        #AND ue.idEmpresa = 1";


                $rsSolic = $db->query($sql);
                if($rsSolic->rowCount() > 0):
                    $dadosSolic = $rsSolic->fetch(PDO::FETCH_ASSOC);

                    echo '---------------------------- NOTIFICOU ----------------------------'."<br>".$dadosSolic['email']."<br><br>";
                    $email = new Email();

                    // Cria html de email a ser enviado.
                    $mensagem = "<!DOCTYPE html>
                    <html>
                        <head><title></title></head>
                        <body>  
                            <h3>Olá! ".$dadosSolic['nome_usuario']."</h3>
                            <p style=\"font-size:14px;margin-top:20px\">A solicitação de número <b>".$valMov['idSolicitacao']."</b>, a seguir encontra-se em atraso.</p>
                            <p style=\"font-size:14px;margin-top:20px\">Solicitação: <b>".$valMov['descricao']." </b></p>
                            <p style=\"font-size:14px;margin-top:20px\">Acesse: <a href='".URL."/fluxo/centralDeTarefa'>".URL."/fluxo/centralDeTarefa</a></p>
                        </body>
                    </html>";

                    $template = $email->getTemplate($mensagem);
                    $email->enviaEmail($nomeRemetente,$assunto, $template, $dadosSolic['email']);
                endif;
            endforeach;
            /***********************************  FIM Envia email para o gestor  ************************************/



        endif;
    endforeach;

endif;

