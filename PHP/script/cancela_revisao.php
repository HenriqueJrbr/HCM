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
        fm.dataMovimentacao,
        fm.idResponsavel,
        fm.idSolicitacao,
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
    WHERE
        fm.status = 1
    ORDER BY
        fm.dataMovimentacao ASC
";
$sql = $db->query($sql);

if($sql->rowCount() > 0):
    $movimentacoes = $sql->fetchAll(PDO::FETCH_ASSOC);

    // Percorre as movimentações encontradas
    foreach($movimentacoes as $valMov):
        $documento = json_decode($valMov['documento']);
        $parametrosFluxo = json_decode($valMov['parametros']);
        $dataMovimentacao = date('Y-m-d', strtotime($valMov['dataMovimentacao']));
        $diasAtraso = $valMov['diasAtraso'];
        $diasNotifica = $valMov['diasNotifica'];
        $dataExpira = date('Y-m-d', strtotime($dataMovimentacao.' + '.$diasAtraso.' days'));
        $iniciaNotificacao = date('Y-m-d', strtotime($dataExpira.' - '.$diasNotifica.' days'));

        echo '-------------------------------------------------------------------'."<br>";
        echo 'Solicitação: ' . $valMov['idSolicitacao']."<br>";
        echo 'Data Movimentação: ' . $dataMovimentacao . ' ---- Expira apartir de ' . $dataExpira ."<br>";
        echo 'Data Movimentação: ' . $dataMovimentacao . ' ---- Notificar apartir de ' . $iniciaNotificacao ."<br>";

        /********************************************************************************************************************
        * VALIDA SE JÁ EXPIROU A SOLICITAÇÃO                                                                                *
        * A REGRA PARA REMOÇÃO DE ACESSOS E FINALIZAÇÃO DE SOLICITAÇÃO É A SEGUINTE                                         *
        * Se DataAtual >= (dataMovimentacao + diasAtraso) && status == 0                                                    *
        ********************************************************************************************************************/
        if(strtotime(date('Y-m-d')) > strtotime(date('Y-m-d', strtotime($documento->dataFim)))):
            echo '----------------------------- EXPIROU -----------------------------'."<br><br><br>";
            //continue;
            //die('dd');
            global $execBo;
            $api = new ExecBO();
            $m = new Manutencao();
            $fluxo = new Fluxo();
            $email = new Email();
            $gruposInfo = array();
            $assunto        = "Revisão de Acesso";
            $nomeRemetente  = "SGA - Sistema de Gestão de Acesso";

            // Envia email para o gestor responsável
            $dadosEmailUsuario  = $email->cadadosUsuario($documento->idusuario);
            $dadosEmailGestor   = $email->cadadosUsuario($valMov['idResponsavel']);

            //if($parametrosFluxo->cancelaEmAtraso == true):
                // atualiza o status das movimentações e solicitação para 0.
                $fluxo->updateMovimento($documento->idSolicitacao, $valMov['idAtividade']);
                $fluxo->finalizaSolicitacao($documento->idSolicitacao, date('Y-m-d H:i:s'));

                echo 'Fluxo finalizado com sucesso!';

                $mensagem = "<!DOCTYPE html>
                    <html>
                        <head><title></title></head>
                        <body>  
                            <h1>Olá! ".$dadosEmailGestor['nome_usuario']."</h1>
                            <p>Informamos que a revisão de acessos do usuário ".$dadosEmailUsuario['nome_usuario']. " de código ". $valMov['idSolicitacao'].", foi cancelada por falta de aprovação.</p>
                            <p>Grupos removidos: ".implode(', ', $gruposInfo)."</b></p>
                            <p>Acesse: <a href='".URL."/fluxo/centralDeTarefa'>".URL."/fluxo/centralDeTarefa</a></p>
                        </body>
                    </html>";
                $email->enviaEmail($nomeRemetente,$assunto, $mensagem, $dadosEmailGestor['email']);
            /*else:
                $mensagem = "<!DOCTYPE html>
                    <html>
                        <head><title></title></head>
                        <body>  
                            <h1>Olá! ".$dadosEmailGestor['nome_usuario']."</h1>
                            <p>Informamos que a atividade a seguir encontra-se em atraso</p>
                            <p>Aprovação: Revisão de Acesso do usuário <b> ".$dadosEmailUsuario['nome_usuario']." </b></p>
                            <p>Acesse: <a href='".URL."/fluxo/centralDeTarefa'>".URL."/fluxo/centralDeTarefa</a></p>
                        </body>
                    </html>";
                $email->enviaEmail($nomeRemetente,$assunto, $mensagem, $dadosEmailGestor['email']);
            endif;*/

            // Se tiver parametrizado no fluxo removeAcesso = true e integração com TOTVS, CONSOME WEBSERVICE
            /*if($parametrosFluxo->removeAcesso == true && $parametrosFluxo->cancelaEmAtraso == true):
                foreach ($documento->grupos as $grupos):
                    if ($execBo['execBo'] == "true"):
                        $programa = "esp/essga005b.p";
                        $procedure = "piGrupoUsuario";
                        $dataUserExecBO = array('codUsuario'  => $documento->idTotvs, 'idLegGrupo' => $grupos->idLegGrupo, 'tipo' => 'ESC');

                        // Consome WEBSERVICE
                        $retorno = '';
                        try{
                            $retorno = $api->rodaExecBo($programa, $procedure, $dataUserExecBO);
                        }catch (Exception $e){
                            die($e->getMessage() . " ". $retorno);
                        }
                        print_r($retorno);

                        $retorno = explode('-', $retorno);
                        print_r($retorno);
                        echo trim(str_replace('OK |','OK',$retorno[3]))."<br>";
                        // Retorno for OK remove usuário do grupo
                        if ((isset($retorno[3]) && trim(str_replace('OK |','OK',$retorno[3])) == "OK") || trim(str_replace('núo encontrado |','nao encontrado',$retorno[2])) == 'nao encontrado') {
                            echo $grupos->idLinhaGrupo."<br>";
                            $result = $m->apagaUsuarioGrupo($grupos->idLinhaGrupo);
                            $result['return'] = true;
                            $gruposInfo[] = $grupos->idLegGrupo.' - '.$grupos->descAbrev;
                        } else {
                            $result['return'] = false;

                        }
                    else:
                        $result = $m->apagaUsuarioGrupo($grupos['idLinhaGrupo']);
                        $result['return'] = true;
                        $gruposInfo[] = $grupos->idLegGrupo.' - '.$grupos->descAbrev;

                    endif;
                endforeach;
                $result = $m->inativaUsuario($documento->idusuario);
                echo ' Grupos removidos'. "<br>";
            endif;*/

            continue;
        endif;

        /********************************************************************************************************************
        * SE NÃO EXPIROU VALIDA SE ESTÁ DENTRO DO PERÍODO DE NOTIFICAÇÃO E NOTIFICA CASO ESTEJA                             *
        * A REGRA PARA NOTIFICAR O RESPONSÁVEL PELO ATRASO DA MOVIMENTAÇÃO É A SEGUINTE                                     *
        * Se dataAtual >= ((dataMovimentacao + diasAtraso) - diasNotifica) && <= DataExpira                                 *
        ********************************************************************************************************************/
        if((strtotime(date('Y-m-d')) >= strtotime(date('Y-m-d', strtotime($documento->dataInicio)))) && strtotime(date('Y-m-d')) <= strtotime(date('Y-m-d', strtotime($documento->dataFim)))):
            echo '---------------------------- NOTIFICOU ----------------------------'."<br><br><br>";
            $email = new Email();

            $assunto        = "Revisão de Acesso";
            $nomeRemetente  = "SGA - Sistema de Gestão de Acesso";

            // Envia email para o gestor responsável
            $dadosEmailUsuario  = $email->cadadosUsuario($documento->idusuario);
            $dadosEmailGestor   = $email->cadadosUsuario($valMov['idResponsavel']);

            // Cria html de email a ser enviado.
            $mensagem = "<!DOCTYPE html>
                <html>
                    <head><title></title></head>
                    <body>  
                        <h1>Olá! ".$dadosEmailGestor['nome_usuario']."</h1>
                        <p>Existe uma atividade que está sob sua responsabilidade e precisa de sua ação.</p>
                        <p>Aprovação: Revisão de Acesso do usuário <b> ".$dadosEmailUsuario['nome_usuario']." </b></p>
                        <p>Acesse: <a href='".URL."/fluxo/centralDeTarefa'>".URL."/fluxo/centralDeTarefa</a></p>
                    </body>
                </html>";

            $email->enviaEmail($nomeRemetente,$assunto, $mensagem, $dadosEmailGestor['email']);
        endif;
    endforeach;

endif;

