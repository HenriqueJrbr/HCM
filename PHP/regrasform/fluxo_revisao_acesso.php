<?php

class Fluxo extends regrasform
{
    protected $fluxo;

    public function __construct(){
        parent::__construct();

        $this->fluxo = new Fluxo();
    }

	public function aprovacaoGestorUsuario(){}

    public function aprovacaoGestorGrupo(){}

    public function aprovacaoGestorModulo(){}

    public function aprovacaoSI(){}

    /**
     * CONSOME WEBSERVICE E REMOVE TODOS OS GRUPOS E BLOQUEIA USUÁRIO.
     * REMOVE GRUPOS DO USUÁRIO NO SGA
     * @param $post
     * @param $dadosFluxo
     * @param $codUsuario
     * @param $idSolicitacao
     * @param $idAtividade
     * @param $dataMovimentacao
     */
    public function bloqueaUsuario($post, $dadosFluxo, $codUsuario, $idSolicitacao, $idAtividade, $dataMovimentacao){
        if (isset($post['bloquearUsuario']) && $post['bloquearUsuario'] == 1):
            $parametrosFluxo = json_decode($dadosFluxo['parametros']);
            $msgRemoveGrupo = '';
            global $execBo;
            $api = new ExecBO();
            $m = new manutencao();

            if ($execBo['execBo'] == "true"):
                $programa = "esp/essga005b.p";
                $procedure = "piBloqueiaUsuario";
                $dataUserExecBO = array('codUsuario'  => $codUsuario);

                // Consome WEBSERVICE
                $retorno = '';
                try{
                    $retorno = $api->rodaExecBo($programa, $procedure, $dataUserExecBO);
                }catch (Exception $e){
                    $this->helper->setAlert(
                        'error',
                        'Erro ao bloquear usuário no Totvs'."\n <br>".$retorno,
                        'fluxo/centralDeTarefa'
                    );
                    die($e->getMessage());
                }

                // Retorno for OK remove usuário do grupo
                if ($retorno == "OK") :
                    // Se tiver parametrizado removeAcesso = true e integração com TOTVS, CONSOME WEBSERVICE
                    if($parametrosFluxo->removeAcesso == true):

                        foreach ($post['grupos'] as $grupos):
                            $this->helper->debug($grupos);
                            $programa = "esp/essga005b.p";
                            $procedure = "piGrupoUsuario";
                            $dataUserExecBO = array('codUsuario'  => $codUsuario, 'idLegGrupo' => $grupos['idLegGrupo'], 'tipo' => 'ESC');

                            // Consome WEBSERVICE
                            try{
                                $retorno = $api->rodaExecBo($programa, $procedure, $dataUserExecBO);
                            }catch (Exception $e){
                                $this->helper->setAlert(
                                    'error',
                                    'Erro ao remover usuário de grupo'."\n <br>".$retorno,
                                    'fluxo/centralDeTarefa'
                                );
                                die($e->getMessage());
                            }

                            $retorno = explode('-', $retorno);
                            if((isset($retorno[3]) && trim(str_replace(' |','', $retorno[3])) == "OK") || trim(str_replace('núo encontrado |','nao encontrado',$retorno[2])) == 'nao encontrado'):
                                $result = $m->apagaUsuarioGrupo($grupos['idLinhaGrupo']);
                                $result['return'] = true;
                            endif;
                        endforeach;
                        $msgRemoveGrupo = ' Grupos removidos!';
                    endif;
                else:
                    $result['return'] = false;
                endif;

            // Se tiver parametrizado removeAcesso = true e integração com TOTVS, CONSOME WEBSERVICE
            elseif($parametrosFluxo->removeAcesso == true):
                foreach ($post['grupos'] as $grupos):
                    $result = $m->apagaUsuarioGrupo($grupos['idLinhaGrupo']);
                    $result['return'] = true;
                endforeach;
            endif;

            //die('terminou');
            // Valida se executou os processos com sucesso e finaliza movimentação e solicitação
            if(isset($result['return']) && $result['return'] == false):
                $this->helper->setAlert(
                    'error',
                    'Erro ao bloquear usuário!',
                    'fluxo/centralDeTarefa'
                );
                die();
            else:
                // atualiza o status das movimentações para 0.
                $this->fluxo->updateMovimento($idSolicitacao, $idAtividade);
                $this->fluxo->finalizaSolicitacao($idSolicitacao, $dataMovimentacao);
                $m->inativaUsuario($post['idusuario']);
                $this->gravaLogFluxo($post['grupos'], $idSolicitacao);

                $this->helper->setAlert(
                    'success',
                    'Fluxo finalizado com sucesso. Usuário bloqueado! '. $msgRemoveGrupo,
                    'fluxo/centralDeTarefa'
                );
                die('Executou');
            endif;
        endif;
    }

    public function removeGrupos(){}

    public function reprova(){}

    public function enviaEmail(){}

}