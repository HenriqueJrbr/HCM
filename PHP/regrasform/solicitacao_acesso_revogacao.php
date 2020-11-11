<?php

class solicitacao_acesso_revogacao extends revisao_de_acesso_aprovacao
{
    public function __construct() {
        // Seta o nome do fluxo para os envios dos emails
        $this->__set('tipoFluxo', 'Solicitação de Acesso');
    }
    
    public function solicitacao_acesso_revogacao($control, $idSolicitacao, $idAtividade = '', $fluxo = '', $idMovimentacao = '', $post, $from = '')
    {
        $this->revisao_de_acesso_aprovacao($control, $idSolicitacao, $idAtividade, '', $idMovimentacao, $post, $from);
    }
    
    
    /**
     * Carrega tela com formulário do fluxo
     * @param $control
     * @param $idMovimentacao
     * @param $idSolicitacao
     * @param $idAtividade
     * @param $view
     */
    public function carregaForm($control, $idMovimentacao, $idSolicitacao, $idAtividade, $view)
    {        
        $dados  = array();
        $fluxo  = new Fluxo();
        $helper = new Helper();

        /*******************************************************************************************************
         *       Valida se usuário logado ou alternativo é o mesmo idResponsável na tabela de movimentação     *
         ******************************************************************************************************/
        $userAlt = $fluxo->buscaUsuariosAlternativos(array($_SESSION['idUsrTotvs']));
        $idDonoMovimentacao = (isset($userAlt[0]['idUsrSub']) && $userAlt[0]['idUsrSub'] != '') ? $userAlt[0]['idUsrSub'] : $_SESSION['idUsrTotvs'];
        $rsDonoMovimentacao = $fluxo->buscaMovimentacao($idMovimentacao, $idDonoMovimentacao, $idSolicitacao);
        
        $buscaSolicitante = $fluxo->buscaSolicitante($idSolicitacao);
        $buscaAcompanhante = $fluxo->buscaAcompanhante($idSolicitacao);
                        
        if((!$rsDonoMovimentacao && !(isset($buscaSolicitante['idSolicitante']) && $buscaSolicitante['idSolicitante'] == $_SESSION['idUsrTotvs'])) && (isset($buscaAcompanhante['idAcompanhante']) && $buscaAcompanhante['idAcompanhante'] != $_SESSION['idUsrTotvs'])):
            $helper->setAlert(
                'error',
                'Está tarefa não está sob sua responsabilidade!',
                'Fluxo/centralDeTarefa'
            );
            die('');
        endif;
        /*******************************************************************************************************
         *                       FIM Validação usuário responsável da movimentação                             *
         ******************************************************************************************************/

        $dados['dadosRevisao']   = $fluxo->carregaDocumento($idSolicitacao);
        $dados['historicoMsg']   = $fluxo->buscaHistoricoMsg($idSolicitacao);
        $dados['documento']      = json_decode($dados['dadosRevisao']['documento']);
        $dados['idMovimentacao'] = $idMovimentacao;
        $dados['movimentacao']   = $fluxo->carregaMovimentacao($idMovimentacao);
        $dados['idAtividade']    = $idAtividade;
        $dadosSolicitacao        = $fluxo->buscaDadosAuditoria($idSolicitacao);
        $dados['statusSolicitacao'] = $dadosSolicitacao['status'];
        $dados['idSolicitante']  = $dadosSolicitacao['idSolicitante'];
        $dados['nomeSolicitante'] = $dadosSolicitacao['nome_usuario'];
        $dados['timeline']       = $fluxo->buscaDadosTimeline($idSolicitacao);        
        $dados['from']           = $view;
        $dados['gestMrp']        = ['49' => 'gestorModulo', '50' => 'gestorRotina', '51' => 'gestorPrograma'];
        $dados['atividades']     = isset($dados['movimentacao']['form']) ? $fluxo->carregaAtividadesFluxoParaSI($dados['movimentacao']['form']) : [];
        $dados['idFluxo']        = 8;

        // Monta sequenca de aprovação
        $dados['seqAprov'] = $this->preparaSeqAprovacao(8);
        // Fim sequencia de aprovação        
        
        // Traz o total de todos os programas tabela de log
        //$dados['totalProg']      = $fluxo->getCountTableProgramasLog('', $_SESSION['empresaid'], $idSolicitacao);
        $dados['totalProg']      = 0;
        // Valida se o gestor de grupo tem usuario alternativo
        $idGestores = array();
        $idGestMrp = array();
        
        $grupos = array();
        
        foreach ($dados['documento']->grupos as $value):

            // Retorna substitutos de gestores de grupos
            if(!in_array($value->idCodGest, $idGestores)):
                $grupos[] = $value->idGrupo;
                $idGestores[] = $value->idCodGest;

                $userAlt = $fluxo->buscaUsuariosAlternativos($idGestores);

                if(count($userAlt) > 0):
                    $dados['userAlt'][$value->idCodGest] = $userAlt[0]['idUsrSub'];
                    $dados['userAltSerSub'][$value->idCodGest] = $userAlt[0]['idUsrSerSub'];
                endif;
            endif;
            
            
            foreach($dados['gestMrp'] as $mrp):
                // Retorna substitutos de gestores de modulo, rotina e programa
                foreach($value->$mrp as $key => $mod):
                    if(isset($mod->id) && $mod->id != '' && !in_array($mod->id, $idGestMrp)):
                        $idGestMrp[$key][] = $mod->id;
                        $userAlt = $fluxo->buscaUsuariosAlternativos(array($mod->id));
                        if(count($userAlt) > 0):
                            $dados["userAlt"][$mod->id] = $userAlt[0]['idUsrSub'];
                            $dados["userAltSerSub"][$mod->id] = $userAlt[0]['idUsrSerSub'];
                        endif;
                    endif;
                endforeach;
            endforeach;
        endforeach;
        
        $control->loadTemplate($view, $dados);
    }        
    
    /**
     * SE APROVADOR (S.I) APROVAR. OU SE PRÓXIMA ATIVIDADE FOR A FINAL. ATUALIZA O STATUS DA MOVIMENTAÇÃO PARA 0
     * SE ESTIVER PARAMETRIZADO EXECBO NO CONFIG.PHP. CONSOME WEBSERVICE MANTENDO OU REMOVENDO ACESSO DO USUÁRIO NO TOTVS
     * SE RETORNO DO WEBSERVICE FOR 'OK' EXECUTA O MÉTODO apagaUsuarioGrupo PASSANDO O ID DO USUÁRIO
     * SE NÃO TIVER PARAMETRIZADO EXECBO NO CONFIG.PHP. EXECUTA O MÉTODO apagaUsuarioGrupo PASSANDO O ID DO USUÁRIO
     * @param $post
     * @param $params     
     */
    public function aprovacaoFinal($post, $params)
    {        
        //global $execBo;        
        $manutencao = new Manutencao();
        $fluxo = new Fluxo();
        $helper = new Helper();
        //$params['dataMovimentacao'] = date('Y-m-d H:i:s');
        
        // Busca o documento atualizado
        $rsDoc = $fluxo->carregaDocumento($params['idSolicitacao']);
        $documento = json_decode($rsDoc['documento'], true);
        
        $result = array('return' => true);
        
        //$helper->debug($post, true);                                  

        // Valida se existe riscos. Se sim, cria atividade SI
        if($this->criaAtividadeSI($post, $params)):
            return true;
        endif;       
        
        $htmlErro = $this->integraUsuarios($post, $documento, 'INC');
        
        if($htmlErro == ''):
            // Finaliza solicitação, grava log e guarda historico do documento            
            $this->atualizaAtividadeAtual($post['idSolicitacao'], $post['numAtividade'], $post['usrLogado']);
            $fluxo->finalizaSolicitacao($params['idSolicitacao'], $params['dataMovimentacao']);
            if(isset($post['grupos']) && count($post['grupos']) > 0):
                $fluxo->gravaLogFluxo($post['grupos'], $params['idSolicitacao']);
            endif;
            $params['dadosDoc']['numAprovadores'] = 0;
           
            // Grava o histórico para timeline
            $fluxo->insereDocumentoHistorico(
                $post['idSolicitacao'],
                $post['idMovimentacao'],
                'finalizado',
                'Finalizado'
            );
            
            // Envia e-mail
            
            if(isset($documento['idAcompanhante'][0]) && !empty($documento['idAcompanhante'][0])):
                $post['idAcompanhante'] = $documento['idAcompanhante'];                
            endif;
            $this->enviaEmailAprovacaoFinal($params, $post);

            $manutencao = new Manutencao();
            //$fluxo->callSPSgaRefreshVmUsuariosProcessosRiscos();
            $manutencao->atualizaVMUsuarios();
            //die('fiz');
            $helper->setAlert(
                'success',
                'Solicitação finalizada com sucesso',
                'Fluxo/centralDeTarefa'
            );
            die();
        else:            
            $this->statusMovimentoToAtivo($post['idSolicitacao'], $params['idAtividade'], $post['usrLogado'], $post['idMovimentacao']);
        
            $fluxo->insereDocumentoHistorico(
                $params['idSolicitacao'], 
                $params['idMovimentacao'], 
                'Erro na integração', 
                "<td>$htmlErro</td>"                
            );
            
            $fluxo->insereDocumentoHistorico(
                $params['idSolicitacao'], 
                $params['idMovimentacao'], 
                'pendente de aprovação', 
                "<td></td>"                
            );
            //die('fiz');
            $helper->setAlert(
                'error',
                'Erro ao finalizar solicitação'. $htmlErro,
                'Fluxo/centralDeTarefa'
            );
            die('');
        endif;
    }
}