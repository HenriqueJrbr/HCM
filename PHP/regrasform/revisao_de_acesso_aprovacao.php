<?php 
class revisao_de_acesso_aprovacao extends regrasform {
    protected $tipoFluxo = 'Revisão de Acesso';
    
    public function __construct()
    {
      	parent::__construct();
    }

    function __set($attr, $value)
    {
        $this->$attr = $value;
    }
    
    /**
     * Exibe a página de revisão de acesso. Busca o json da tabela z_sga_fluxo_documento e converte para objeto.
     * @param $idSolicitacao
     * @param string $idAtividade
     * @param string $idMovimentacao
     * @param string $idSolicitante
     */
    public function revisao_de_acesso_aprovacao($control, $idSolicitacao, $idAtividade = '', $fluxo = '', $idMovimentacao = '', $post , $from = ''){
        // Se for aprovação, executa o método de gravação da atividade
        if(isset($post['enviar']) && $post['enviar'] == "Enviar"):
            $this->gravaRevisaoAcesso($idSolicitacao, $idAtividade, $idMovimentacao, $post);
        elseif(isset($post['enviar']) && $post['enviar'] == "Cancelar"):
            $this->cancelaSolicitacoes($post, date('Y-m-d H:i:s'), $post['idTotvsRevisao']);
        else:
            $this->carregaForm($control, $idMovimentacao, $idSolicitacao, $idAtividade, $from);
        endif;
    }   
    
    /**
     * Grava os dados de fluxo de revisao de acesso     
     */
    public function gravaRevisaoAcesso($idSolicitacao, $idAtividade, $idMovimentacao, $post)
    {                
        $fluxo = new Fluxo();
        $helper = new Helper();

        $gestorUsuario    = (isset($post['gestorUsuario']) && !empty($post['gestorUsuario'])) ? $post['gestorUsuario'] : '';
        $codUsuario       = (isset($post['idTotvsRevisao']) && !empty($post['idTotvsRevisao'])) ? $post['idTotvsRevisao'] : '';
        $dadosFluxo       = $fluxo->buscaDadosFluxo($idSolicitacao);
        setlocale(LC_TIME, 'pt_BR');
        date_default_timezone_set('America/Sao_Paulo');
        $dataMovimentacao = date('Y-m-d H:i:s');
        $hasReprova = false;
        
        //$helper->debug($post, true);
        
        // Grava as aprovações
        if($post['idFluxo'] == 7):
            $resJsonDocUpdate = $fluxo->atualizaDocAprovacaoAtualGrupoPrograma($idAtividade, $post, $idMovimentacao);
        else:
            $resJsonDocUpdate = $fluxo->atualizaDocAprovacaoAtual($idAtividade, $post, $idMovimentacao);
        endif;
        
        if($resJsonDocUpdate['return'] == false):
            $helper->setAlert(
                'error',
                "Erro ao atualizar fluxo. <br>".$resJsonDocUpdate['return'],
                'Fluxo/centralDeTarefa'
            );
        else:
            $hasReprova = $resJsonDocUpdate['hasReprova'];
        endif;
        //$helper->debug($hasReprova, true);

        //$helper->debug($dadosDoc, true);
        /*********************************************************************************************************************
         * SE BLOQUEAR USUÁRIO FOR MARCADO
         * CONSOME WEBSERVICE E REMOVE TODOS OS GRUPOS E BLOQUEIA USUÁRIO
         * E REMOVE GRUPOS DO USUÁRIO NO SGA
         *********************************************************************************************************************/
        if (isset($post['bloquearUsuario']) && $post['bloquearUsuario'] == 1):
            $params = array(
                'idSolicitacao'     => $idSolicitacao,
                'idAtividade'       => $idAtividade,
                'dadosFluxo'        => $dadosFluxo,
                //'dadosDoc'          => $dadosDoc,
                'cod_usuario'       => $codUsuario,
                'dataMovimentacao'  => $dataMovimentacao
            );
            
            $this->bloqueaUsuario($post, $params);
        endif;

        /*********************************************************************************************************************
         * SE TODOS OS GRUPOS ESTIVEREM DESMARCADOS.
         * CONSOME WEBSERVICE E REMOVE OS ACESSOS SEM PASSAR POR APROVAÇÃO
         *********************************************************************************************************************/
        //$this->helper->debug($post);
        if($post['idFluxo'] == 3):
            $apagaTodosGrupos = true;

            foreach ($post['grupos'] as $valGrupo):
                if($valGrupo['manterStatus'] == 1):
                    $apagaTodosGrupos = false;
                endif;
            endforeach;

            if($apagaTodosGrupos == true):
                $params = array(
                    'idSolicitacao'     => $idSolicitacao,
                    'idAtividade'       => $idAtividade,
                    'dadosFluxo'        => $dadosFluxo,
                    //'dadosDoc'          => $dadosDoc,
                    'cod_usuario'       => $codUsuario,
                    'dataMovimentacao'  => $dataMovimentacao
                );

                $this->removeGrupos($post, $params);
            endif;
        endif;

        // atualiza atividade atual
        $this->atualizaAtividadeAtual($idSolicitacao, $idAtividade, $post['usrLogado']);
        
        // Busca o total de movimentação pendente para a solicitação
        $totalAtiv = $fluxo->totalMovimentacaoAtiva($idSolicitacao);

        // Busca o id da próxima atividade.
        $rsIdProximaAtiv = $fluxo->verificaProximaAtividade($idAtividade);
        $idProximaAtiv = $rsIdProximaAtiv['proximaAtiv'];
        
        // Recupera nome de objeto instancia da proxima atividade
        $objProxAtividade = $fluxo->buscaObjetoProxAtividade($idProximaAtiv);
        //echo "<pre>";
        
        $params = array(
            'idSolicitacao'   => $idSolicitacao,
            'idSolicitante'   => $post['idSolicitante'],
            'idAtividade'     => $idAtividade,
            'idMovimentacao'  => $idMovimentacao,
            'idProximaAtiv'   => $idProximaAtiv,
            //'documentos'      => $dadosDoc,
            'dadosFluxo'      => $dadosFluxo,
            'totalAtiv'       => $totalAtiv,
            //'dadosDoc'        => $dadosDoc,
            
            'usuario'          => (isset($post['usuario']) && !empty($post['usuario'])) ? $post['usuario'] : '',
            'dataMovimentacao' => date('Y-m-d H:i:s')
        );

        // Se não existir movimentação pendente. Movimenta para próxima atividade        
        if($totalAtiv == 0):
            if(!$hasReprova):
                $this->$objProxAtividade($post, $params);
            
                // Envia email para usuários em cópia
                $this->enviaEmailCopiaAprovacao($params, $post);
            else:
                $this->reprova($post, $params);
            endif;
        else:
            // Cria o JSON
            //$documentos = json_encode($dadosDoc);
            
        endif;                                
        
        $helper->setAlert(
            'success',
            'Fluxo alterado com sucesso',
            'Fluxo/centralDeTarefa'
        );
    }

    /**
     * Atualiza o status da atividade atual com base no ID da atividade
     * @param $idSolicitacao
     * @param $idAtividade
     * @param $idUserLogado
     */
    public function atualizaAtividadeAtual($idSolicitacao, $idAtividade, $idUserLogado)
    {
        $fluxo = new Fluxo();
        $fluxo->updateMovimento($idSolicitacao, $idAtividade, $idUserLogado);
    }
       
    /**
     * Volta o status da atividade atual com base no ID da atividade para ativo
     * @param $idSolicitacao
     * @param $idAtividade
     * @param $idUserLogado
     */
    public function statusMovimentoToAtivo($idSolicitacao, $idAtividade, $idUserLogado, $idMovimentacao)
    {
        $fluxo = new Fluxo();
        $fluxo->statusMovimentoToAtivo($idSolicitacao, $idAtividade, $idUserLogado, $idMovimentacao);
    }
    
    /**
     * Cria atividade para gestor de usuário
     * @param type $post
     * @param type $params
     */
    public function criaAtividadeGestorUsuario($post, $params)
    {        
        $fluxo = new Fluxo();
        //$params['dataMovimentacao'] = date('Y-m-d H:i:s');        
        $idGestorResponsavel = $fluxo->buscaUsrAlternativoFluxos($post['idGestorUsuario']);
        
        // Busca o documento atualizado
        $rsDoc = $fluxo->carregaDocumento($params['idSolicitacao']);
        $documento = json_decode($rsDoc['documento'], true);
                
        // Cadastra movimentação para o gestor
        $fluxo->cadastraMovimentacao($params['idSolicitacao'], $params['idProximaAtiv'], $params['dataMovimentacao'], $post['idSolicitante'], $idGestorResponsavel,$post['idFluxo'],'');
        
        // Atualiza número de aprovadores no documento, caso exista a variável        
        if(isset($documento['numAprovadores'])):
            $numAprovadores = $fluxo->totalAtividade($params['idProximaAtiv'], $post['idFluxo']);
            $fluxo->atualizaDocNumAprovadores($params['idSolicitacao'], $numAprovadores);
        endif;        
    }

    /**
     * CRIA UMA NOVA MOVIMENTAÇÃO PARA GESTORES DE GRUPOS, VALIDANDO SE GESTOR POSSUI USUÁRIO APROVADOR ALTERNATIVO
     * @param $post
     * @param $params     
     */
    public function criaAtividadeGestorGrupo($post, $params)
    {        
        $fluxo = new Fluxo();
        //$params['dataMovimentacao'] = date('Y-m-d H:i:s');
        
        $idGestores = array();
        $criouMovimentacao = false;
        
        // Busca o documento atualizado
        $rsDoc = $fluxo->carregaDocumento($params['idSolicitacao']);
        $documento = json_decode($rsDoc['documento'], true);
        
        /*foreach($documento['grupos'] as $val):            
            if($val['manterStatus'] == 1 && (isset($val['aprovacao']) && $val['aprovacao'] == 'nao')):
                $this->reprova($post, $params);
            endif;
        endforeach;          */
        
        // OBS.: Criar uma única atividade por gestor
        foreach($documento['grupos'] as $grupos):            
            // Se o id do Gestor de grupos  não estiver no array de gestores cria atividade para o mesmo.
            if($grupos['manterStatus'] == 1):
                $expAtividade = (isset($post['si_atividades']) && $post['si_atividades'] != '') ? explode('-', $post['si_atividades']) : '';
                if(!in_array($grupos['idCodGest'], $idGestores) && (($grupos['aprovacao'] == '' || $grupos['aprovacao'] == 'nao') || (isset($post['si_atividades']) && $post['si_atividades'] != '' && $expAtividade[1] == 'criaAtividadeGestorGrupo'))):
                    $idGestores[] = $grupos['idCodGest'];
                    //echo $grupos['idCodGest'];
                    $idGestorResponsavel = $fluxo->buscaUsrAlternativoFluxos($grupos['idCodGest']);
                    
                    // Cadastra movimentação para o gestor
                    
                    $fluxo->cadastraMovimentacao($params['idSolicitacao'], $params['idProximaAtiv'], $params['dataMovimentacao'], $post['idSolicitante'], $idGestorResponsavel,$post['idFluxo'],'');

                    // Envia email para o gestor responsável
                    $this->enviaEmailFluxos('Existe uma nova atividade que está sob sua responsabilidade e precisa de sua ação.', $idGestorResponsavel, $post['idusuario'], $this->tipoFluxo);
                    
                    $criouMovimentacao = true;
                endif;
            endif;
        endforeach;
                
        // Cria o JSON
        if(isset($documento['numAprovadores'])):
            $numAprovadores = $fluxo->totalAtividade($params['idProximaAtiv'], $post['idFluxo']);
            $fluxo->atualizaDocNumAprovadores($params['idSolicitacao'], $numAprovadores);
        endif;                                
        
        // Valida se foi criado movimentação. 
        // Se não, executa método de criação de movimentação da próxima atividade
        $this->criaProximaAtividade($criouMovimentacao, $params, $post);
    }

    /**
     * CRIA UMA NOVA MOVIMENTAÇÃO PARA GESTORES DE MÓDULOS, VALIDANDO SE GESTOR POSSUI USUÁRIO APROVADOR ALTERNATIVO
     * @param $post
     * @param $params   
     */
    public function criaAtividadeGestorModulo($post, $params)
    {        
        $fluxo = new Fluxo();
        
        // Busca o documento atualizado
        $rsDoc = $fluxo->carregaDocumento($params['idSolicitacao']);
        $documento = json_decode($rsDoc['documento'], true);
                       
        //$params['dataMovimentacao'] = date('Y-m-d H:i:s');
        $criouMovimentacao = false;
        
        // OBS.: Cria uma única atividade por gestor
        $idGestores = array();
        $aprovador = ($post['idFluxo'] == 7) ? 'programas' : 'grupos';

        foreach($documento[$aprovador] as $grupos):
            // Se o id do Gestor não estiver no array de gestores cria atividade para o mesmo.
            if($grupos['manterStatus'] == 1):
                foreach($grupos['gestorModulo'] as $gestMod):
                    $expAtividade = (isset($post['si_atividades']) && $post['si_atividades'] != '') ? explode('-', $post['si_atividades']) : '';
                    if(is_array($gestMod) && !in_array($gestMod['id'], $idGestores) && (($gestMod['aprovacao'] == '' || $gestMod['aprovacao'] == 'nao') || (isset($post['si_atividades']) && $post['si_atividades'] != '' && $expAtividade[1] == 'criaAtividadeGestorModulo'))):
                        $idGestores[] = $gestMod['id'];
                        $idGestorResponsavel = $fluxo->buscaUsrAlternativoFluxos($gestMod['id']);

                        // Cadastra movimentação para o gestor
                        $fluxo->cadastraMovimentacao($params['idSolicitacao'], $params['idProximaAtiv'], $params['dataMovimentacao'], $post['idSolicitante'], $idGestorResponsavel,$post['idFluxo'],'');

                        // Envia email para o gestor responsável
                        $this->enviaEmailFluxos('Existe uma nova atividade que está sob sua responsabilidade e precisa de sua ação.', $idGestorResponsavel, $documento['idusuario'], $this->tipoFluxo);
                        
                        $criouMovimentacao = true;
                    endif;
                endforeach;                                
            endif;
        endforeach;  
        
        // Cria o JSON        
        if(isset($documento['numAprovadores'])):     
            $numAprovadores = $fluxo->totalAtividade($params['idProximaAtiv'], $post['idFluxo']);
            $fluxo->atualizaDocNumAprovadores($params['idSolicitacao'], $numAprovadores);
        endif; 
        
        // Valida se foi criado movimentação. 
        // Se não, executa método de criação de movimentação da próxima atividade
        $this->criaProximaAtividade($criouMovimentacao, $params, $post);                
    }        

    /**
     * CRIA UMA NOVA MOVIMENTAÇÃO PARA GESTORES DE ROTINAS, VALIDANDO SE GESTOR POSSUI USUÁRIO APROVADOR ALTERNATIVO
     * @param $post
     * @param $params   
     */
    public function criaAtividadeGestorRotina($post, $params)
    {        
        $fluxo = new Fluxo();
        
        // Busca o documento atualizado
        $rsDoc = $fluxo->carregaDocumento($params['idSolicitacao']);
        $documento = json_decode($rsDoc['documento'], true);                       

        $criouMovimentacao = false;
        
        // OBS.: Cria uma única atividade por gestor
        $idGestores = array();
        $aprovador = ($post['idFluxo'] == 7) ? 'programas' : 'grupos';

        foreach($documento[$aprovador] as $grupos):
            // Se o id do Gestor não estiver no array de gestores cria atividade para o mesmo.
            if($grupos['manterStatus'] == 1 /*&& ($this->existeReprovacao($post, array('gestorRotina')))*/):
                foreach($grupos['gestorRotina'] as $gestRot):
                    $expAtividade = (isset($post['si_atividades']) && $post['si_atividades'] != '') ? explode('-', $post['si_atividades']) : '';
                    if(is_array($gestRot) && !in_array($gestRot['id'], $idGestores) && (($gestRot['aprovacao'] == '' || $gestRot['aprovacao'] == 'nao') || (isset($post['si_atividades']) && $post['si_atividades'] != '' && $expAtividade[1] == 'criaAtividadeGestorRotina'))):
                        $idGestores[] = $gestRot['id'];
                        $idGestorResponsavel = $fluxo->buscaUsrAlternativoFluxos($gestRot['id']);                        
                        
                        // Cadastra movimentação para o gestor
                        $fluxo->cadastraMovimentacao($params['idSolicitacao'], $params['idProximaAtiv'], $params['dataMovimentacao'], $post['idSolicitante'], $idGestorResponsavel,$post['idFluxo'],'');
                        
                        // Envia email para o gestor responsável
                        $this->enviaEmailFluxos('Existe uma nova atividade que está sob sua responsabilidade e precisa de sua ação.', $idGestorResponsavel, $documento['idusuario'], $this->tipoFluxo);
                        
                        $criouMovimentacao = true;
                    endif;
                endforeach;                
            endif;
        endforeach;

        // Cria o JSON        
        if(isset($documento['numAprovadores'])):
            $numAprovadores = $fluxo->totalAtividade($params['idProximaAtiv'], $post['idFluxo']);
            $fluxo->atualizaDocNumAprovadores($params['idSolicitacao'], $numAprovadores);
        endif; 
        
        // Valida se foi criado movimentação. 
        // Se não, executa método de criação de movimentação da próxima atividade
        $this->criaProximaAtividade($criouMovimentacao, $params, $post);
    }

    /**
     * CRIA UMA NOVA MOVIMENTAÇÃO PARA GESTORES DE PROGRAMA, VALIDANDO SE GESTOR POSSUI USUÁRIO APROVADOR ALTERNATIVO     
     * @param $post
     * @param $params
     */
    public function criaAtividadeGestorPrograma($post, $params)
    {
        $fluxo = new Fluxo();
        
        // Busca o documento atualizado
        $rsDoc = $fluxo->carregaDocumento($params['idSolicitacao']);
        $documento = json_decode($rsDoc['documento'], true);                        

        $criouMovimentacao = false;
        
        // OBS.: Cria uma única atividade por gestor
        $idGestores = array();
        $aprovador = ($post['idFluxo'] == 7) ? 'programas' : 'grupos';

        foreach($documento[$aprovador] as $grupos):
            // Se o id do Gestor não estiver no array de gestores cria atividade para o mesmo.
            if($grupos['manterStatus'] == 1):
                foreach($grupos['gestorPrograma'] as $gestProg):
                    $expAtividade = (isset($post['si_atividades']) && $post['si_atividades'] != '') ? explode('-', $post['si_atividades']) : '';
                    if(is_array($gestProg) && !in_array($gestProg['id'], $idGestores) && (($gestProg['aprovacao'] == '' || $gestProg['aprovacao'] == 'nao') || (isset($post['si_atividades']) && $post['si_atividades'] != '' && $expAtividade[1] == 'criaAtividadeGestorPrograma'))):
                        $idGestores[] = $gestProg['id'];
                        $idGestorResponsavel = $fluxo->buscaUsrAlternativoFluxos($gestProg['id']);

                        // Cadastra movimentação para o gestor
                        $fluxo->cadastraMovimentacao($params['idSolicitacao'], $params['idProximaAtiv'], $params['dataMovimentacao'], $post['idSolicitante'], $idGestorResponsavel,$post['idFluxo'],'');

                        // Envia email para o gestor responsável                        
                        $this->enviaEmailFluxos('Existe uma nova atividade que está sob sua responsabilidade e precisa de sua ação.', $idGestorResponsavel, $documento['idusuario'], $this->tipoFluxo);
                        
                        $criouMovimentacao = true;
                    endif;
                endforeach;                
            endif;
        endforeach;                               
        
        // Cria o JSON        
        if(isset($documento['numAprovadores'])):
            $numAprovadores = $fluxo->totalAtividade($params['idProximaAtiv'], $post['idFluxo']);
            $fluxo->atualizaDocNumAprovadores($params['idSolicitacao'], $numAprovadores);
        endif;
        
        // Valida se foi criado movimentação. 
        // Se não, executa método de criação de movimentação da próxima atividade        
        $this->criaProximaAtividade($criouMovimentacao, $params, $post);
    }

    /**
     * Cria atividade para aprovador SI
     * @param $post
     * @param $params   
     */
    public function criaAtividadeSI($post, $params)
    {

        if($post['riscos'] > 0 && (isset($post['aprovacao_si']) && ($post['aprovacao_si'] != '1' && $post['aprovacao_si'] != 'sim')/* && $post['si_atividades'] == ''*/)):
            $fluxo = new Fluxo();

            // Busca o documento atualizado
            $rsDoc = $fluxo->carregaDocumento($params['idSolicitacao']);
            $documento = json_decode($rsDoc['documento'], true);

            // Busca o id do usuário SI na base
            $rsDadosSI = $fluxo->buscaSI();
            $dadosSI = $fluxo->buscaUsrAlternativoFluxos($rsDadosSI[0]['idUsuario']);

            // Busca o id da atividade.
            $rsIdProximaAtiv = $fluxo->verificaAtividadeSI($post['idFluxo']);

            // Cadastra movimentação para o S.I
            $fluxo->cadastraMovimentacao($params['idSolicitacao'], $rsIdProximaAtiv['id'], $params['dataMovimentacao'], $post['idSolicitante'], $dadosSI,$post['idFluxo'],'');

            // Envia email para o gestor responsável
            $this->enviaEmailFluxos('Existe uma nova atividade que está sob sua responsabilidade e precisa de sua ação.', $dadosSI, $documento['idusuario'], $this->tipoFluxo);

            // Cria o JSON
            if(isset($documento['numAprovadores'])):
                $numAprovadores = $fluxo->totalAtividade($params['idProximaAtiv'], $post['idFluxo']);
                $fluxo->atualizaDocNumAprovadores($params['idSolicitacao'], $numAprovadores);
            endif;

            return true;
        endif;

        return false;

    }

    /**
     * Valida se foi criado movimentação. 
     * Se não, executa método de criação de movimentação da próxima atividade
     * @param type $criouMovimentacao
     * @param type $params
     * @param type $post
     */
    public function criaProximaAtividade($criouMovimentacao, $params, $post)
    {
        $fluxo = new Fluxo();
        
        // Valida se foi criado movimentação. 
        // Se não, executa méotod de criação de movimentação da próxima atividade
        if($criouMovimentacao == false):           
            // Busca o id da próxima atividade.
            $rsIdProximaAtiv = $fluxo->verificaProximaAtividade($params['idProximaAtiv']);
            $idProximaAtiv = $rsIdProximaAtiv['proximaAtiv'];

            // Recupera nome de objeto instancia da proxima atividade
            $objProxAtividade = $fluxo->buscaObjetoProxAtividade($idProximaAtiv);
            $params['idProximaAtiv'] = $idProximaAtiv;
            
            $this->$objProxAtividade($post, $params);
        endif;
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
        
        $htmlErro = $this->integraUsuarios($post, $documento, 'ESC');
                
        if($htmlErro == ''):                        
            // Finaliza solicitação, grava log e guarda historico do documento
            $this->atualizaAtividadeAtual($post['idSolicitacao'], $post['numAtividade'], $post['usrLogado']);
            $fluxo->finalizaSolicitacao($params['idSolicitacao'], $params['dataMovimentacao']);
            $fluxo->gravaLogFluxo($post['grupos'], $params['idSolicitacao']);                                                                                          
            
            // Busca o documento atualizado
            $rsDoc = $fluxo->carregaDocumento($params['idSolicitacao']);
            $documento = json_decode($rsDoc['documento'], true);                                  
            
            // Atualiza número de aprovadores no documento, caso exista a variável
            if(isset($documento['numAprovadores'])):
                $numAprovadores = $fluxo->totalAtividade($params['idProximaAtiv'], $post['idFluxo']);
                $fluxo->atualizaDocNumAprovadores($params['idSolicitacao'], $numAprovadores);
            endif; 
            
            // Grava o histórico para timeline
            $fluxo->insereDocumentoHistorico(
                $post['idSolicitacao'],
                $post['idMovimentacao'],
                'finalizado',
                'Finalizado'
            );
                        
            // executa Stored Procedure
            $manutencao = new Manutencao();
            //$fluxo->callSPSgaRefreshVmUsuariosProcessosRiscos();
            $manutencao->atualizaVMUsuarios();
            
            // Envia e-mail
            $this->enviaEmailAprovacaoFinal($params, $post);
            
            $helper->setAlert(
                'success',
                'Fluxo finalizado com sucesso',
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
        
            $helper->setAlert(
                'error',
                'Erro ao remover usuário de grupo! \n'.$htmlErro,
                'Fluxo/centralDeTarefa'
            );
            die('');
        endif;
    }

    
    /**
     * Método responsável pela integração de usuários a grupos
     * @param type $post
     * @param type $documento
     * @param type $tipo 'ESC' = Eliminar relacionamento, 'INC' = Adicionar relacionamento
     * @return string
     */
    public function integraUsuarios($post, $documento, $tipo)    
    {             
        $htmlErro = '';

        // Valida se foi aprovado, percorre os grupos e valida se é pra remover.
        $usuarios = [];
        
        foreach ($documento['grupos'] as $grupo):            
            if($grupo['idLegGrupo'] == '*'):
                continue;
            endif;            
            // Se for revisão de acesso cria o array buscando pelos que foram removidos da aprovação
            if($post['idFluxo'] == 3):
                if ($grupo['manterStatus'] == 0):                
                    array_push(
                        $usuarios,
                        [
                            'cod_usuario'   => $post['idTotvsRevisao'],
                            'cod_grp_usuar' => $grupo['idLegGrupo'],
                            'acao'          => 'ESC',
                            'idUsuario'     => $documento['idusuario'],
                            'descAbrev'     => $grupo['descAbrev'],
                            'idGrupo'       => $grupo['idGrupo']
                        ]
                    );
                endif;
            endif;
            
            // Se for solicitação de acesso cria o array buscando pelos que foram adicionados da aprovação
            if($post['idFluxo'] == 4 || $post['idFluxo'] == 6):
                if ($grupo['manterStatus'] == 1):
                    array_push(
                        $usuarios,
                        [
                            'cod_usuario'   => $post['idTotvsRevisao'],
                            'cod_grp_usuar' => $grupo['idLegGrupo'],
                            'acao'          => 'INC',
                            'idUsuario'     => $documento['idusuario'],
                            'descAbrev'     => $grupo['descAbrev'],
                            'idGrupo'       => $grupo['idGrupo']
                        ]
                    );
                endif;
            endif;
            
            // Se for solicitação de acesso por função cria o array buscando pelos que foram adicionados da aprovação e remove os que são fora da função
            if($post['idFluxo'] == 5 ):
                if ($tipo == 'INC'):
                    if($grupo['manterStatus'] == 1):
                        array_push(
                            $usuarios,
                            [
                                'cod_usuario'   => $post['idTotvsRevisao'],
                                'cod_grp_usuar' => $grupo['idLegGrupo'],
                                'acao'          => 'INC',
                                'idUsuario'     => $documento['idusuario'],
                                'descAbrev'     => $grupo['descAbrev'],
                                'idGrupo'       => $grupo['idGrupo']
                            ]
                        );
                    endif;
                endif;
                
                if ($tipo == 'ESC'):
                    array_push(
                        $usuarios,
                        [
                            'cod_usuario'   => $post['idTotvsRevisao'],
                            'cod_grp_usuar' => $grupo['idLegGrupo'],
                            'acao'          => 'ESC',
                            'idUsuario'     => $documento['idusuario'],
                            'descAbrev'     => $grupo['descAbrev'],
                            'idGrupo'       => $grupo['idGrupo']
                        ]
                    );
                endif;
            endif;            
        endforeach;

        // Se for solicitação de acesso por revogação cria o array buscando pelos que foram adicionados da aprovação e remove os que forem pra remover
        if($post['idFluxo'] == 8 ):
            if(isset($documento['grupos']) && count($documento['grupos']) > 0):
                foreach($documento['grupos'] as $grupo):
                    array_push(
                        $usuarios,
                        [
                            'cod_usuario'   => $post['idTotvsRevisao'],
                            'cod_grp_usuar' => $grupo['idLegGrupo'],
                            'acao'          => 'INC',
                            'idUsuario'     => $documento['idusuario'],
                            'descAbrev'     => $grupo['descAbrev'],
                            'idGrupo'       => $grupo['idGrupo']
                        ]
                    );
                endforeach;
            endif;

            if(isset($documento['remover']) && count($documento['remover']) > 0):
                foreach($documento['remover'] as $grupo):
                    if(isset($grupo['removerStatus']) && !empty($grupo['removerStatus'])):
                        array_push(
                            $usuarios,
                            [
                                'cod_usuario'   => $post['idTotvsRevisao'],
                                'cod_grp_usuar' => $grupo['idLegGrupo'],
                                'acao'          => 'ESC',
                                'idUsuario'     => $documento['idusuario'],
                                'descAbrev'     => $grupo['descAbrev'],
                                'idGrupo'       => $grupo['idGrupoRemover']
                            ]
                        );
                    endif;
                endforeach;
            endif;
        endif;   
        
        //echo "<pre>";
        //print_r($documento);
        //print_r($usuarios);        
        //die("<br>Parei");
        
        // Implementação de envio com array
        // Valida se é para integrar com datasul
        $empresa = new Empresa();
        $manutencao = new Manutencao();
        $fluxo = new Fluxo();
        $dadosEmpresa = $empresa->carregaEmpresa($_SESSION['empresaid']);
        $jsonApi = json_decode($dadosEmpresa[0]['integration_data']);
        
        if(isset($jsonApi->execBO->integra) && $jsonApi->execBO->integra == "true"):
            $api = new ExecBO();            
            $dataUserExecBO = $usuarios;

            // Consome WEBSERVICE
            $retorno = $api->execboGrupoUsuario($tipo, $dataUserExecBO);
            
            //die;
            // Relaciona os usuarios e grava log
            if(isset($retorno['success']) && count($retorno['success']) > 0):                            
                // Valida se o retorno foi ok e grava o log
                //print_r($retorno);
                //if($retorno['return']):
                    foreach($usuarios as $key => $val):                        
                        // Relaciona no SGA
                        if($tipo == 'ESC'):
                            $result = $manutencao->apagaUsuarioGrupo($val['idGrupo'], $retorno['success']);
                        else:
                            $result = $manutencao->addUsuarioGrupoExecbo($val['idGrupo'], $retorno['success']);
                        endif;
                    
                        //echo in_array($val['cod_usuario'], $retorno['success'])."<br>";
                        if(in_array($val['cod_usuario'], $retorno['success'])):
                            $fluxo->gravaLogAuditoria(
                                1,
                                date('Y-m-d H:i:s'),
                                date('Y-m-d H:i:s'),
                                $post['usuario'], //$cod_usuario, // usuario solicitante
                                $val['cod_usuario'],
                                (($tipo == 'ESC') ? 'REMOVIDO' : 'ADICIONADO'),
                                $val['cod_grp_usuar'] .' - '. $val['descAbrev'],
                                '',
                                $_SESSION['nomeUsuario'],
                                'f',
                                $post['idSolicitacao']
                            );
                        endif;
                    endforeach;
                //endif;
            endif;                        
            
            // Cria o html de erros se houver
            if(isset($retorno['error']) && count($retorno['error']) > 0):
                // Cria mensagem de erros, se houver
                $htmlErro .= '<p>';
                if(is_string($retorno['error']) && !empty($retorno['error'])):
                    $htmlErro .= $retorno['error'] ."<br>";                    
                else:
                    if(count($retorno['error']) > 0):
                        foreach($retorno['error'] as $key => $val):
                            $htmlErro .= "<strong>". $key . "</strong>: " . $val ."<br>";
                        endforeach;
                    endif;
                endif;

                $htmlErro .= '</p>';
                
            endif;
        else:
            $userIds = [];
            foreach($usuarios as $val):
                array_push($userIds, $val['cod_usuario']);
            endforeach;            
                        
            foreach($usuarios as $key => $val):
                if($tipo == 'ESC'):
                    $result = $manutencao->apagaUsuarioGrupo($val['idGrupo'], $val['cod_usuario']);
                else:
                    $result = $manutencao->addUsuarioGrupoExecbo($val['idGrupo'], $val['cod_usuario']);
                endif;
                if($result['return']):
                    $fluxo->gravaLogAuditoria(
                        1,
                        date('Y-m-d H:i:s'),
                        date('Y-m-d H:i:s'),
                        $post['usuario'], //$cod_usuario, // usuario solicitante
                        $val['cod_usuario'],
                        (($tipo == 'ESC') ? 'REMOVIDO' : 'ADICIONADO'),
                        $val['cod_grp_usuar'] .' - '. $val['descAbrev'],
                        '',
                        $_SESSION['nomeUsuario'],
                        'f',
                        $post['idSolicitacao']
                    );                    
                endif;
            endforeach;            
        endif;
        
        return $htmlErro;
    }
    
    /**
     * CONSOME WEBSERVICE E REMOVE TODOS OS GRUPOS E BLOQUEIA USUÁRIO.
     * REMOVE GRUPOS DO USUÁRIO NO SGA
     * @param $post
     * @param $params   
     */
    public function bloqueaUsuario($post, $params)
    {
        
        $msgRemoveGrupo = '';
        //$params['dataMovimentacao'] = date('Y-m-d H:i:s');
        //global $execBo;        
        $m = new Manutencao();
        $fluxo = new Fluxo();
        $helper = new Helper();
        
        // Busca o documento atualizado
        $rsDoc = $fluxo->carregaDocumento($params['idSolicitacao']);
        $documento = json_decode($rsDoc['documento'], true);
        
        $parametrosFluxo = $fluxo->parametrosFluxo($params['idSolicitacao']);
        $parametrosFluxo = json_decode($parametrosFluxo['parametros']);

        // Valida se é para integrar com datasul
        $empresa = new Empresa();
        $dadosEmpresa = $empresa->carregaEmpresa($_SESSION['empresaid']);
        $jsonApi = json_decode($dadosEmpresa[0]['integration_data']);
        
        if(isset($jsonApi->execBO->integra) && $jsonApi->execBO->integra == "true"):            
            $api = new ExecBO();
            $programa = "esp/essga005b.p";
            $procedure = "piBloqueiaUsuario";
            $dataUserExecBO = array('codUsuario'  => $post['idTotvsRevisao']);            

            // Consome WEBSERVICE
            $retorno = '';
            try{
                $retorno = $api->rodaExecBo($programa, $procedure, $dataUserExecBO);
            }catch (Exception $e){
                $helper->setAlert(
                    'error',
                    'Erro ao bloquear usuário no Totvs'."\n <br>".$retorno,
                    'Fluxo/centralDeTarefa'
                );
                die($e->getMessage());
            }
            
            // Retorno for OK remove usuário do grupo
            if ($retorno['return'] == "OK"):
                // Se tiver parametrizado removeAcesso = true e integração com TOTVS, CONSOME WEBSERVICE
                if($parametrosFluxo->removeAcesso == true):

                    foreach ($documento['grupos'] as $grupos):
                        //$helper->debug($grupos);
                        $programa = "esp/essga005b.p";
                        $procedure = "piGrupoUsuario";
                        $dataUserExecBO = array('codUsuario'  => $post['idTotvsRevisao'], 'idLegGrupo' => $grupos['idLegGrupo'], 'tipo' => 'ESC');

                        // Consome WEBSERVICE
                        $retorno = $api->rodaExecBo($programa, $procedure, $dataUserExecBO);
                        if($retorno['return'] == "OK" || $retorno['return'] == "nao encontrado"):
                            $result = $m->apagaUsuarioGrupoByGrupo($grupos['idGrupo'], $documento['idusuario']);
                            //print_r($result);                       
                        else:
                            $this->statusMovimentoToAtivo($post['idSolicitacao'], $post['idAtividade'], $post['usrLogado'], $post['idMovimentacao']);
                            $helper->setAlert(
                                'error',
                                'Erro ao remover usuário de grupo'."\n <br>".$retorno['error'],
                                'Fluxo/centralDeTarefa'
                            );
                            die('');                    
                        endif;                                                
                    endforeach;
                    $msgRemoveGrupo = ' Grupos removidos!';
                endif;
            elseif($retorno['return'] == "nao encontrado"):                
                foreach ($documento['grupos'] as $grupos):
                    $result = $m->apagaUsuarioGrupo($grupos['idGrupo'], $post['usuario']);        
                    $fluxo->gravaLogAuditoria(
                        1,
                        $documento['dataInicio'],
                        $params['dataMovimentacao'],
                        $post['idTotvsRevisao'],
                        $post['usuario'],
                        'REMOVIDO',
                        $grupos['idLegGrupo'] .' - '. $grupos['descAbrev'],
                        '',
                        $_SESSION['nomeUsuario'],
                        'f',
                        $params['idSolicitacao']
                    );
                    $result['return'] = true;
                endforeach;          
            else:
                $result['return'] = false;
            endif;

        // Se não tiver parametrizado removeAcesso = true e integração com TOTVS, CONSOME WEBSERVICE
        elseif($parametrosFluxo->removeAcesso == true):
            foreach ($documento['grupos'] as $grupos):
                $result = $m->apagaUsuarioGrupo($grupos['idGrupo'], $post['usuario']);        
                $fluxo->gravaLogAuditoria(
                    1,
                    $documento['dataInicio'],
                    $params['dataMovimentacao'],
                    $post['idTotvsRevisao'],
                    $post['usuario'],
                    'REMOVIDO',
                    $grupos['idLegGrupo'] .' - '. $grupos['descAbrev'],
                    '',
                    $_SESSION['nomeUsuario'],
                    'f',
                    $params['idSolicitacao']
                );
                $result['return'] = true;
            endforeach;
        endif;

        //die('terminou');
        // Valida se executou os processos com sucesso e finaliza movimentação e solicitação
        if(isset($result['return']) && $result['return'] == false):
            $helper->setAlert(
                'error',
                'Erro ao bloquear usuário!',
                'Fluxo/centralDeTarefa'
            );
            die();
        else:           
            // atualiza status de movimentação e solicitação e inativa usuario
            $this->atualizaAtividadeAtual($params['idSolicitacao'], $params['idAtividade'], $post['usrLogado']);
            $fluxo->finalizaSolicitacao($params['idSolicitacao'], $params['dataMovimentacao']);
            $m->inativaUsuario($post['idusuario']);
            $fluxo->gravaLogFluxo($post['grupos'], $params['idSolicitacao']);

            // Grava o histórico para timeline
            $fluxo->insereDocumentoHistorico(
                $post['idSolicitacao'],
                $post['idMovimentacao'],
                'finalizado',
                'Bloqueado pelo gestor de usuário'
            );
            
            $helper->setAlert(
                'success',
                'Fluxo finalizado com sucesso. Usuário bloqueado! '. $msgRemoveGrupo,
                'Fluxo/centralDeTarefa'
            );
            die('Executou');
        endif;
    }

    /**
     * SE TODOS OS GRUPOS ESTIVEREM DESMARCADOS
     * CONSOME WEBSERVICE E REMOVE OS ACESSOS SEM PASSAR POR APROVAÇÃO
     * @param $post
     * @param $params   
     */
    public function removeGrupos($post, $params)
    {
        //global $execBo;        
        $fluxo = new Fluxo();
        $helper = new Helper();
        //$params['dataMovimentacao'] = date('Y-m-d H:i:s');
        
        // Busca o documento atualizado
        $rsDoc = $fluxo->carregaDocumento($params['idSolicitacao']);
        $documento = json_decode($rsDoc['documento'], true);
        
        $htmlErro = $this->integraUsuarios($post, $documento, 'ESC');
                
        if($htmlErro == ''):                        
            // Finaliza solicitação, grava log e guarda historico do documento
            $this->atualizaAtividadeAtual($post['idSolicitacao'], $post['numAtividade'], $post['usrLogado']);
            $fluxo->finalizaSolicitacao($params['idSolicitacao'], $params['dataMovimentacao']);
            $fluxo->gravaLogFluxo($post['grupos'], $params['idSolicitacao']);                                                                                          
            
            // Busca o documento atualizado
            $rsDoc = $fluxo->carregaDocumento($params['idSolicitacao']);
            $documento = json_decode($rsDoc['documento'], true);                                  
            
            // Atualiza número de aprovadores no documento, caso exista a variável
            //if(isset($documento['numAprovadores'])):
                //$numAprovadores = $fluxo->totalAtividade($params['idProximaAtiv'], $post['idFluxo']);
                //$fluxo->atualizaDocNumAprovadores($params['idSolicitacao'], $numAprovadores);
            //endif; 
            
            // Grava o histórico para timeline
            $fluxo->insereDocumentoHistorico(
                $post['idSolicitacao'],
                $post['idMovimentacao'],
                'finalizado',
                'Finalizado'
            );
                        
            // executa Stored Procedure
            $manutencao = new Manutencao();
            //$fluxo->callSPSgaRefreshVmUsuariosProcessosRiscos();
            $manutencao->atualizaVMUsuarios();
            
            // Envia e-mail
            $this->enviaEmailAprovacaoFinal($params, $post);
            
            $helper->setAlert(
                'success',
                'Fluxo finalizado com sucesso',
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
        
            $helper->setAlert(
                'error',
                'Erro ao remover usuário de grupo! \n'.$htmlErro,
                'Fluxo/centralDeTarefa'
            );
            die('');
        endif;
    }

    /**
     * ATUALIZA OS STATUS DAS MOVIMENTAÇÕES PARA 0
     * E CRIA UMA NOVA MOVIMENTAÇÃO PARA GESTOR DE USUÁRIO. ID ATIVIDADE = 7
     * @param $post
     * @param $params     
     */
    public function reprova($post, $params)
    {
        $fluxo = new Fluxo();
        $helper = new Helper();
        //$params['dataMovimentacao'] = date('Y-m-d H:i:s');

        // Busca o id da atividade anterior.
        if(isset($post['si_atividades']) && $post['si_atividades'] != ''):            
            $exp = explode('-', $post['si_atividades']);
            $objAtividade = $exp[1];
            $params['idProximaAtiv'] = $exp[0];
            $this->$objAtividade($post, $params);            
            //$numAprovadores = $fluxo->totalAtividade($params['idProximaAtiv'], $post['idFluxo']);
        else:
            
            //$idProximaAtiv = $fluxo->verificaProximaAtividade(9);
            $idAtivSolicitante = $fluxo->buscaIdAtividadeSolicitante($post['idFluxo'], $idEmpresa = '');
            // Busca o id da próxima atividade.
            $idProximaAtiv = $fluxo->verificaProximaAtividade($idAtivSolicitante);
            $idProximaAtiv = $idProximaAtiv['proximaAtiv'];

            // Busca usuário alternativo
            if($post['idFluxo'] == 7):
                $idGestorResponsavel = $fluxo->buscaUsrAlternativoFluxos($post['idCodGest']);
            else:
                $idGestorResponsavel = $fluxo->buscaUsrAlternativoFluxos($post['idGestorUsuario']);
            endif;

            // Cria uma nova atividade
            $fluxo->cadastraMovimentacao($params['idSolicitacao'], $idProximaAtiv, $params['dataMovimentacao'], $post['idSolicitante'], $idGestorResponsavel, $post['idFluxo'], '');
                
            // Envia email para o gestor responsável
            $this->enviaEmailFluxos('Existe uma nova atividade que está sob sua responsabilidade e precisa de sua ação.', $idGestorResponsavel, $post['idusuario'], $this->tipoFluxo);
                        
            //$numAprovadores = $fluxo->totalAtividade($idProximaAtiv, $post['idFluxo']);
            if(isset($params['dadosDoc']['numAprovadores'])):
                $numAprovadores = $fluxo->totalAtividade($params['idProximaAtiv'], $post['idFluxo']);
                $fluxo->atualizaDocNumAprovadores($params['idSolicitacao'], $numAprovadores);
            endif;
        endif;
                
        // Envia email para usuários em cópia
        $this->enviaEmailCopiaAprovacao($params, $post);
        
        $helper->setAlert(
            'success',
            'Fluxo alterado com sucesso',
            'Fluxo/centralDeTarefa'
        );
        die('');
    }                

    /**
     * Cancela as solicitações selecionadas na tela central de tarefas, aba minhas solicitações
     */
    public function cancelaSolicitacoes($post, $params)
    {
        $fluxo = new Fluxo();
        $helper = new Helper();
        //$params['dataMovimentacao'] = date('Y-m-d H:i:s');

        // Valida se foi seleciona ao menos uma solicitação
        if(empty($post)):
            $helper->setAlert(
                'error',
                'Nenhuma solicitação selecionada',
                'Fluxo/centralDeTarefa'
            );
            die('');
        endif;

        // Percorre os ids selecionados e cancela a solicitação
        foreach ($post['solicitacao'] as $idSolicitacao):
            $result = $fluxo->finalizaSolicitacao($idSolicitacao, $params['dataMovimentacao']);

            // Recupera os dados da solicitação
            $dadosSolic = $fluxo->buscaDadosMovimentacaoAtiva($params['idSolicitacao']);

            if($dadosSolic['return']):
                $fluxo->updateMovimento($params['idSolicitacao'],$dadosSolic['result']['idAtividade'], $dadosSolic['result']['idResponsavel']);

                // Grava o log para auditoria
                $dadosLogAuditoria = $fluxo->buscaDadosAuditoria($params['idSolicitacao']);
                $fluxo->gravaLogAuditoria(
                    1,
                    $dadosLogAuditoria['dataInicio'],
                    $params['dataMovimentacao'],
                    $post['idTotvsRevisao'],
                    $post['usuario'],
                    'UPDATE',
                    'com - com',
                    '',
                    $_SESSION['nomeUsuario'],
                    'f',
                    $params['idSolicitacao']
                );


                $documento = json_decode($dadosSolic['result']['documento']);
                // Cria html de email a ser enviado.
                $mensagem = "Informamos que a atividade abaixo, que estava <br/>sob sua responsabilidade, foi cancelada pelo solicitante.";

                // Envia email para o gestor responsável
                $this->enviaEmailFluxos($mensagem, $dadosSolic['result']['idResponsavel'], $documento->idusuario, $this->tipoFluxo);
            endif;
        endforeach;
        
        // Envia email para usuários em cópia e solicitante
        $post['idAcompanhante'] = $documento->idAcompanhante; 
        $this->enviaEmailCopiaCancela($params, $post);
        
        // Redireciona para a tela de central de tarefas
        $helper->setAlert(
            'success',
            'Solicitações canceladas com sucesso!',
            'Fluxo/centralDeTarefa'
        );
        die('');
    }
    
    /**
     * Envia o email de cópia dos fluxos na aprovação final
     * @param $paramSolicitacao     
     * @param $post     
     */
    public function enviaEmailAprovacaoFinal($params, $post)
    {
        $email = new Email();
        $fluxo = new Fluxo();
        $assunto = "Solicitação de Acesso";
        $nomeRemetente = "SGA - Sistema de Gestão de Acesso";
        $gestores = '';
        $atividade = '';
        
        // Envia email para usuários em cópia.
        if(isset($post['idAcompanhante']) && !empty($post['idAcompanhante'][0])):
            $idUsuarioCopia = explode(', ', $_POST['idAcompanhante']);            
            
            foreach($idUsuarioCopia as $val):
                $dadosEmail = $fluxo->buscaDadosEmailCopiaGeral($val, $params['idSolicitacao']);
                //$dadosEmail = $f->buscaDadosEmailCopia($params);

                if($dadosEmail != 0):
                    $atividade = $dadosEmail[0]['atividade'];
                    $mensagem = '
                        Olá, <b>'.$dadosEmail[0]['usuario_copia'].'</b>.<br/><br/>

                        <span style="font-size:14px;margin-top:20px">A atividade de número <b>'.$params['idSolicitacao'].'</b>, do usuário <b>'.$post['usuario'].'</b> em que você está acompanhando, foi encerrada.</span>
                        <br/><br/>';                    

                    $mensagem .= '<br/><span style="font-size:14px;margin-top:20px; padding">Acompanhe a solicitação em seu painel de atividades.</span><br>';        
                    $mensagem .= '
                        <br/>
                        <br/>				
                        <a href="'.URL.'/fluxo/centralDeTarefa" style="background: #4ca5a9;color: #ffffff; text-align: center; text-decoration: none;padding: 15px 30px; font-family: Arial, sans-serif; font-size: 16px">Ir para Central de Tarefas</a>';

                    $template = $email->getTemplate($mensagem);                              
                    $email->enviaEmail($nomeRemetente,$assunto, $template, $dadosEmail[0]['email']);
                endif;
            endforeach;
        endif;
        
        // Envia email para o solicitante
        $dadosSolic = $fluxo->buscaDadosSolicitante($params['idSolicitante']);        
        
        if($dadosSolic != 0):
            $mensagem = '
                Olá, <b>'.$dadosSolic['nome_usuario'].'</b>.<br/><br/>

                <span style="font-size:14px;margin-top:20px">A atividade de número <b>'.$params['idSolicitacao'].'</b>, do usuário <b>'.$params['usuario'].'</b>, foi encerrada.</span>
                <br/><br/>';
                
            $mensagem .= $gestores;
            
            $mensagem .= '<br/><span style="font-size:14px;margin-top:20px; padding">Acompanhe a solicitação em seu painel de atividades.</span><br>';        
            $mensagem .= '
                <br/>
                <br/>				
                <a href="'.URL.'/fluxo/centralDeTarefa" style="background: #4ca5a9;color: #ffffff; text-align: center; text-decoration: none;padding: 15px 30px; font-family: Arial, sans-serif; font-size: 16px">Ir para Central de Tarefas</a>';

            $template = $email->getTemplate($mensagem);                              
            $email->enviaEmail($nomeRemetente,$assunto, $template, $dadosSolic['email']);
        endif;
    }
    
    /**
     * Envia o email de cópia dos fluxos
     * @param $paramSolicitacao     
     */
    public function enviaEmailCopiaAprovacao($params, $post)
    {
        $email = new Email();
        $fluxo = new Fluxo();
        $rsDoc = $fluxo->carregaDocumento($params['idSolicitacao']);
        $documento = json_decode($rsDoc['documento'], true); 
        $assunto = "Solicitação de Acesso";
        $nomeRemetente = "SGA - Sistema de Gestão de Acesso";
        $gestores = '';
        $atividade = '';
        
        
        
        // Envia email para usuários em cópia.
        if(isset($documento['idAcompanhante']) && !empty($documento['idAcompanhante']) && isset($documento['tipoEnvioMailCopia']) && $documento['tipoEnvioMailCopia'] == 'tudo'):
            $idUsuarioCopia = (is_array($documento['idAcompanhante'])) ? $documento['idAcompanhante'] : explode(', ', $documento['idAcompanhante']);
            
            foreach($idUsuarioCopia as $val):

                $dadosEmail = $fluxo->buscaDadosEmailCopiaGeral($val, $params['idSolicitacao']);
                //$dadosEmail = $f->buscaDadosEmailCopia($params);

                if($dadosEmail != 0):
                    $atividade = $dadosEmail[0]['atividade'];
                    $mensagem = '
                        Olá, <b>'.$dadosEmail[0]['usuario_copia'].'</b>.<br/><br/>

                        <span style="font-size:14px;margin-top:20px">A atividade de número <b>'.$params['idSolicitacao'].'</b>, do usuário <b>'.$post['usuario'].'</b> em que você está acompanhando, foi movimentada para o status <b>'.$dadosEmail[0]['atividade'].'</b> e está com o(s) gestor(es):</span>
                        <br/><br/>';
                    foreach ($dadosEmail as $val):
                        $mensagem .= "<b>".$val['nome_gestor']."</b><br/>";
                        $gestores .= "<b>".$val['nome_gestor']."</b><br/>";
                    endforeach;

                    $mensagem .= '<br/><span style="font-size:14px;margin-top:20px; padding">Acompanhe a solicitação em seu painel de atividades.</span><br>';        
                    $mensagem .= '
                        <br/>
                        <br/>				
                        <a href="'.URL.'/fluxo/centralDeTarefa" style="background: #4ca5a9;color: #ffffff; text-align: center; text-decoration: none;padding: 15px 30px; font-family: Arial, sans-serif; font-size: 16px">Ir para Central de Tarefas</a>';

                    $template = $email->getTemplate($mensagem);                              
                    $email->enviaEmail($nomeRemetente,$assunto, $template, $dadosEmail[0]['email']);
                endif;
            endforeach;
        endif;
        
        // Envia email para o solicitante
        $dadosSolic = $fluxo->buscaDadosSolicitante($params['idSolicitante']);

        if($dadosSolic != 0):
            // Busca dados da atividade caso não tenha sido atribuido
            $atividade = ($atividade == '') ? $fluxo->buscaDadosAtividade($params['idProximaAtiv']): $atividade;
            
            $mensagem = '
                Olá, <b>'.$dadosSolic['nome_usuario'].'</b>.<br/><br/>

                <span style="font-size:14px;margin-top:20px">A atividade de número <b>'.$params['idSolicitacao'].'</b>, do usuário <b>'.$params['usuario'].'</b>, foi movimentada para o status <b>'.$atividade.'</b> e está com o(s) gestor(es):</span>
                <br/><br/>';
                
            $mensagem .= $gestores;
            
            $mensagem .= '<br/><span style="font-size:14px;margin-top:20px; padding">Acompanhe a solicitação em seu painel de atividades.</span><br>';        
            $mensagem .= '
                <br/>
                <br/>				
                <a href="'.URL.'/fluxo/centralDeTarefa" style="background: #4ca5a9;color: #ffffff; text-align: center; text-decoration: none;padding: 15px 30px; font-family: Arial, sans-serif; font-size: 16px">Ir para Central de Tarefas</a>';

            $template = $email->getTemplate($mensagem);                              
            $email->enviaEmail($nomeRemetente,$assunto, $template, $dadosSolic['email']);
        endif;
    }
    
    /**
     * Envia o email de cópia de reprovação dos fluxos 
     * @param $paramSolicitacao     
     */
    public function enviaEmailCopiaCancela($params, $post)
    {
        $fluxo = new Fluxo();
        $rsDoc = $fluxo->carregaDocumento($params['idSolicitacao']);
        $documento = json_decode($rsDoc['documento'], true);
        
        // Envia email para usuários em cópia.        
        if(isset($documento['idAcompanhante']) && !empty($documento['idAcompanhante'])):
            $email = new Email();
            $assunto = "Solicitação de Acesso";
            $nomeRemetente = "SGA - Sistema de Gestão de Acesso";

            foreach($documento['idAcompanhante'] as $val):

                $dadosEmail = $fluxo->buscaDadosEmailCopiaGeral($val, $params['idSolicitacao']); 
                //$dadosEmail = $f->buscaDadosEmailCopia($params);

                if($dadosEmail != 0):
                    $mensagem = '
                        Olá, <b>'.$dadosEmail[0]['usuario_copia'].'</b>.<br/><br/>

                        <span style="font-size:14px;margin-top:20px">A atividade de número <b>'.$params['idSolicitacao'].'</b>, do usuário <b>'.$post['usuario'].'</b> em que você está acompanhando, foi cancelada.</span>
                        <br/><br/>';                
                    $mensagem .= '<br/><span style="font-size:14px;margin-top:20px; padding">Acompanhe a solicitação em seu painel de atividades.</span><br>';        
                    $mensagem .= '
                        <br/>
                        <br/>				
                        <a href="'.URL.'/fluxo/centralDeTarefa" style="background: #4ca5a9;color: #ffffff; text-align: center; text-decoration: none;padding: 15px 30px; font-family: Arial, sans-serif; font-size: 16px">Ir para Central de Tarefas</a>';

                    $template = $email->getTemplate($mensagem);
                    $email->enviaEmail($nomeRemetente,$assunto, $template, $dadosEmail[0]['email']);
                endif;
            endforeach;
        endif;
        
        // Envia email para o solicitante
        $dadosSolic = $fluxo->buscaDadosSolicitante($params['idSolicitante']);

        if($dadosSolic != 0):
            $mensagem = '
                Olá, <b>'.$dadosSolic['nome_usuario'].'</b>.<br/><br/>

                <span style="font-size:14px;margin-top:20px">A atividade de número <b>'.$params['idSolicitacao'].'</b>, do usuário <b>'.$params['usuario'].'</b>, foi cancelada.</span>
                <br/><br/>';                
            $mensagem .= '<br/><span style="font-size:14px;margin-top:20px; padding">Acompanhe a solicitação em seu painel de atividades.</span><br>';        
            $mensagem .= '
                <br/>
                <br/>				
                <a href="'.URL.'/fluxo/centralDeTarefa" style="background: #4ca5a9;color: #ffffff; text-align: center; text-decoration: none;padding: 15px 30px; font-family: Arial, sans-serif; font-size: 16px">Ir para Central de Tarefas</a>';

            $template = $email->getTemplate($mensagem);                              
            $email->enviaEmail($nomeRemetente,$assunto, $template, $dadosSolic['email']);
        endif;
        //die('foi');
    }

    /**
     * Envia o email dos fluxos
     * @param $textoMensagem
     * @param $idGestor
     * @param $idUsuario
     * @param $tipoFluxo
     */
    public function enviaEmailFluxos($textoMensagem, $idGestor, $idUsuario, $tipoFluxo)
    {
        $email = new Email();
        $assunto = $tipoFluxo;                        

        // Substitui mensagem padrão por mensagem recebida por parametro        
        // Cria html de email a ser enviado.
        $mensagem = '
            <b>Olá! {gestor}</b><br/><br/>
            <span style="font-size:14px;margin-top:20px">{texto}</span>
            <br/>
            <br/>
            <span style="font-size:14px;margin-top:20px"><b>Atividade:</b> {tipoFluxo} do usuário <b>{usuario}</b></span>
            <br/>
            <br/>
            <br/>					
            <a href="'.URL.'/fluxo/centralDeTarefa" style="background: #4ca5a9;color: #ffffff; text-align: center; text-decoration: none;padding: 15px 30px; font-family: Arial, sans-serif; font-size: 16px">Ir para Central de Tarefas</a>';

        // Envia email para o gestor responsável
        $dadosEmailUsuario  = $email->cadadosUsuario($idUsuario);
        $dadosEmailGestor   = $email->cadadosUsuario($idGestor);

        $mensagem = str_replace('{gestor}', $dadosEmailGestor['nome_usuario'], $mensagem);
        $mensagem = str_replace('{usuario}', $dadosEmailUsuario['nome_usuario'], $mensagem);
        $mensagem = str_replace('{tipoFluxo}', $tipoFluxo, $mensagem);
        $mensagem = str_replace('{texto}', $textoMensagem, $mensagem);
        
        $template = $email->getTemplate($mensagem);
        
        $nomeRemetente = "SGA - Sistema de Gestão de Acesso";
        
        $email->enviaEmail($nomeRemetente,$assunto, $template, $dadosEmailGestor['email']);                        
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
        
        if(!$rsDonoMovimentacao && !(isset($buscaSolicitante['idSolicitante']) && $buscaSolicitante['idSolicitante'] == $_SESSION['idUsrTotvs'])):
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
        $dados['gestMrp']        = ['10' => 'gestorModulo', '11' => 'gestorRotina', '12' => 'gestorPrograma'];                        
        $dados['atividades']     = isset($dados['movimentacao']['form']) ? $fluxo->carregaAtividadesFluxoParaSI($dados['movimentacao']['form']) : [];
        $dados['idFluxo']        = 3;

        // Monta sequenca de aprovação
        $dados['seqAprov'] = $this->preparaSeqAprovacao(3);                
        // Fim sequencia de aprovação
        
        // Traz o total de todos os programas tabela de log
        $dados['totalProg']      = $fluxo->getCountTableProgramasLog('', $_SESSION['empresaid'], $idSolicitacao);

        // Valida se o gestor de grupo tem usuario alternativo
        $idGestores = array();
        $idGestMrp = array();
        
        $grupos = array();
        foreach ($dados['documento']->grupos as $value):
            if($value->manterStatus == 1):
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
            endif;
        endforeach;                
        $control->loadTemplate($view, $dados);
    }
    
    /**
     * Valida se existe reprovação de gestores de módulos, rotinas e programas
     * @param type $post
     * @return boolean
     */
    public function existeReprovacao($post, $validaMPR = array('gestorModulo', 'gestorRotina', 'gestorPrograma' ))
    {
        // Se SI reprovou retorna true
        if(isset($post['si_atividades']) && $post['si_atividades'] != ''):            
            return true;
        endif;
        $fluxo = new Fluxo();
        // Busca o documento atualizado
        $rsDoc = $fluxo->carregaDocumento($post['idSolicitacao']);
        $documento = json_decode($rsDoc['documento'], true);
        
        //echo "<pre>";
        //print_r($validaMPR);
        //die('d');
        
        // Valida reprova
        foreach($validaMPR as $valida):     
            //echo $valida."<br>";
            // Percorre os grupos
            foreach($documento['grupos'] as $grupos):
                
                // Valida reprova de gestor de grupos
                if($grupos['manterStatus'] == 'sim' && $valida == 'gestorGrupo'):
                    if($grupos['aprovacao'] == 'nao'):
                        return true;
                    endif;
                endif;                               
                
                // Se foi incluído apenas validação de reprovação de gestor de grupso e SI pula validação de MRP
                if($valida == 'gestorGrupos' || $valida == 'SI'):
                    continue;
                endif;
                
                // Percorre os indices a valida na variavel $validaMPR
                foreach($grupos[$valida] as $key => $gest):
                    
                    // Valida se existe as variaveis e se foi reprovado ou está aguardando aprovação
                    if(is_array($grupos[$valida][$key]) && ($gest['aprovacao'] == 'nao' || $gest['aprovacao'] == '') && ($grupos['manterStatus'] == 1)):
                        //print_r($grupos);
                        //print_r($grupos[$valida]);
                        return true;
                    endif;                    
                endforeach;                
            endforeach;
        endforeach;
        
        // Se SI reprovou retorna true
        if(isset($validaMPR['SI'])):
            //echo $post['aprovacao_si'];
            if(isset($post['aprovacao_si']) && $post['aprovacao_si'] == 0):                
                return true;
            endif;
        endif;
        
        //return false;
    }  
    
    /**
     * Retorna sequência de aprovação dos fluxos
     * @param type $form
     * @return type
     */
    public function preparaSeqAprovacao($form)
    {
        // Monta sequenca de aprovação
        $fluxo = new Fluxo();        
        $seqAtiv = $fluxo->getSeqAtividades($form);        
        $seqAprov = $fluxo->getOrderCollumnsDataTable($seqAtiv);
        $totalAtiv = count($seqAprov['atividades']);
        //echo "<pre>";
        while(count($seqAprov['atividades']) > 0): 
            $seqAprov = $fluxo->getOrderCollumnsDataTable($seqAprov['atividades'], $seqAprov['sequencia_aprovacao'], $seqAprov['proximaAtiv']);
            $totalAtiv = count($seqAprov['atividades']);
            //print_r($seqAprov);
        endwhile;
        
        unset($seqAprov['atividades']);
        unset($seqAprov['proximaAtiv']);
        $dados['seqAprov'] = $seqAprov['sequencia_aprovacao'];

        return $seqAprov['sequencia_aprovacao'];        
    }
}