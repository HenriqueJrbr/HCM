<?php

class solicitacao_grupo_programa extends revisao_de_acesso_aprovacao
{
    public function __construct() {
        // Seta o nome do fluxo para os envios dos emails
        $this->__set('tipoFluxo', 'Solicitação de Programa em Grupo');
    }
    
    public function solicitacao_grupo_programa($control, $idSolicitacao, $idAtividade = '', $fluxo = '', $idMovimentacao = '', $post, $from = '')
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
        $dados['gestMrp']        = ['41' => 'gestorModulo', '42' => 'gestorRotina', '43' => 'gestorPrograma'];
        $dados['atividades']     = isset($dados['movimentacao']['form']) ? $fluxo->carregaAtividadesFluxoParaSI($dados['movimentacao']['form']) : [];
        $dados['idFluxo']        = 7;
               
        // Monta sequenca de aprovação
        $dados['seqAprov'] = $this->preparaSeqAprovacao(7);
        // Fim sequencia de aprovação        
        
        // Traz o total de todos os programas tabela de log
        //$dados['totalProg']      = $fluxo->getCountTableProgramasLog('', $_SESSION['empresaid'], $idSolicitacao);
        $dados['totalProg']      = 0;
        // Valida se o gestor de grupo tem usuario alternativo
        $idGestores = array();
        $idGestMrp = array();
        
        $grupos = array();

        // Retorna substituto de gestor de grupo
        if(!in_array($dados['documento']->idCodGest, $idGestores)):
            $grupos[] = $dados['documento']->idGrupo;
            $idGestores[] = $dados['documento']->idCodGest;

            $userAlt = $fluxo->buscaUsuariosAlternativos($idGestores);

            if(count($userAlt) > 0):
                $dados['userAlt'][$dados['documento']->idCodGest] = $userAlt[0]['idUsrSub'];
                $dados['userAltSerSub'][$dados['documento']->idCodGest] = $userAlt[0]['idUsrSerSub'];
            endif;
        endif;

        foreach ($dados['documento']->programas as $value):
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
        
        $htmlErro = $this->integraGrupoPrograma($post, $documento, 'INC');        
        
        if($htmlErro == ''):        
            // Finaliza solicitação, grava log e guarda historico do documento            
            $this->atualizaAtividadeAtual($post['idSolicitacao'], $post['numAtividade'], $post['usrLogado']);
            $fluxo->finalizaSolicitacao($params['idSolicitacao'], $params['dataMovimentacao']);
            $grupos[] = [
                'idGrupo' => $post['idGrupo']
            ];
            $fluxo->gravaLogFluxo($grupos, $params['idSolicitacao']);
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
            $manutencao->atualizaVMUsuarios();;
            
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
        
            $helper->setAlert(
                'error',
                'Erro ao finalizar solicitação'. $htmlErro,
                'Fluxo/centralDeTarefa'
            );
            die('');
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
        $criouMovimentacao = false;

        // Busca o documento atualizado
        $rsDoc = $fluxo->carregaDocumento($params['idSolicitacao']);
        $documento = json_decode($rsDoc['documento'], true);

        // Busca se existe alternativo
        $idGestorResponsavel = $fluxo->buscaUsrAlternativoFluxos($documento['idCodGest']);

        // Cadastra movimentação para o gestor
        $fluxo->cadastraMovimentacao($params['idSolicitacao'], $params['idProximaAtiv'], $params['dataMovimentacao'], $post['idSolicitante'], $idGestorResponsavel,$post['idFluxo'],'');

        // Envia email para o gestor responsável
        $this->enviaEmailFluxos('Existe uma nova atividade que está sob sua responsabilidade e precisa de sua ação.', $idGestorResponsavel, $post['idusuario'], $this->tipoFluxo);

        $criouMovimentacao = true;

        // Valida se foi criado movimentação.
        // Se não, executa método de criação de movimentação da próxima atividade
        $this->criaProximaAtividade($criouMovimentacao, $params, $post);
    }

    /**
     * Método responsável pela integração de programas a grupos
     * @param type $post
     * @param type $documento
     * @param type $tipo 'ESC' = Eliminar relacionamento, 'INC' = Adicionar relacionamento
     * @return string
     */
    public function integraGrupoPrograma($post, $documento, $tipo)
    {
        $htmlErro = '';
        
        // Valida se foi aprovado, percorre os programas
        $programas = [];
        foreach ($documento['programas'] as $prog):
            if ($tipo == 'INC'):
                if($prog['manterStatus'] == 1):
                    array_push(
                        $programas,
                        [
                            'cod_prog_dtsul'    => $prog['codProg'],
                            'cod_grp_usuar'     => $documento['idLegGrupo'],
                            'acao'              => 'INC',
                            'idPrograma'        => $prog['idProg']
                        ]
                    );
                endif;
            endif;
        endforeach;

        // Implementação de envio com array
        // Valida se é para integrar com datasul
        $empresa = new Empresa();
        $manutencao = new Manutencao();
        $fluxo = new Fluxo();
        $dadosEmpresa = $empresa->carregaEmpresa($_SESSION['empresaid']);
        $jsonApi = json_decode($dadosEmpresa[0]['integration_data']);

        if(isset($jsonApi->execBO->integra) && $jsonApi->execBO->integra == "true"):
            $api = new ExecBO();
            $dataUserExecBO = $programas;

            // Consome WEBSERVICE
            $retorno = $api->execboGrupoPrograma('INC', $dataUserExecBO);

            //die;
            // Relaciona os programas e grava log                        
            if(isset($retorno['success']) && count($retorno['success']) > 0):                
                $result = $manutencao->addProgramaGrupo($documento['idGrupo'], $retorno['success']);

                // Valida se o retorno foi ok e grava o log
                if($result['return']):
                    $dadosSolicitante = $fluxo->buscaDadosSolicitante($documento['idSolicitante']);

                    foreach($programas as $key => $val):
                        if(in_array($val['cod_prog_dtsul'], $retorno['success'])):
                            $fluxo->gravaLogAuditoria(
                                1,
                                date('Y-m-d H:i:s'),
                                date('Y-m-d H:i:s'),
                                $dadosSolicitante['cod_usuario'],
                                '',
                                'ADICIONADO',
                                $documento['idLegGrupo'] .' - '. $documento['descAbrev'],
                                $val['cod_prog_dtsul'],
                                $_SESSION['nomeUsuario'],
                                'f',
                                $post['idSolicitacao']
                            );
                        endif;
                    endforeach;
                endif;
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
            $progIds = [];
            foreach($programas as $val):
                array_push($progIds, $val['cod_prog_dtsul']);
            endforeach; 

            $result = $manutencao->addProgramaGrupo($documento['idGrupo'], $progIds);

            // Valida se o retorno foi ok e grava o log
            if($result['return']):          
                $dadosSolicitante = $fluxo->buscaDadosSolicitante($documento['idSolicitante']);
                foreach($programas as $key => $val):                    
                    $fluxo->gravaLogAuditoria(
                        1,
                        date('Y-m-d H:i:s'),
                        date('Y-m-d H:i:s'),
                        $dadosSolicitante['cod_usuario'],
                        '',
                        'ADICIONADO',
                        $documento['codGrupo'] .' - '. $documento['descAbrev'],
                        $val['cod_prog_dtsul'],
                        $_SESSION['nomeUsuario'],
                        'f',
                        0
                    ); 
                endforeach;
            endif;
        endif;

        return $htmlErro;
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
        $assunto = "Solicitação de Programa em Grupo";
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

                        <span style="font-size:14px;margin-top:20px">A atividade de número <b>'.$params['idSolicitacao'].'</b>, do grupo <b>'.$post['idLegGrupo'].' - '. $post['descAbrev'] .'</b> em que você está acompanhando, foi encerrada.</span>
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

                <span style="font-size:14px;margin-top:20px">A atividade de número <b>'.$params['idSolicitacao'].'</b>, do grupo <b>'.$post['idLegGrupo'].' - '. $post['descAbrev'] .'</b>, foi encerrada.</span>
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
        $assunto = "Solicitação de Programa em Grupo";
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

                        <span style="font-size:14px;margin-top:20px">A atividade de número <b>'.$params['idSolicitacao'].'</b>, do grupo <b>'.$post['idLegGrupo'].' - '. $post['descAbrev'] .'</b> em que você está acompanhando, foi movimentada para o status <b>'.$dadosEmail[0]['atividade'].'</b> e está com o(s) gestor(es):</span>
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

                <span style="font-size:14px;margin-top:20px">A atividade de número <b>'.$params['idSolicitacao'].'</b>, do grupo <b>'.$post['idLegGrupo'].' - '. $post['descAbrev'] .'</b>, foi movimentada para o status <b>'.$atividade.'</b> e está com o(s) gestor(es):</span>
                <br/><br/>';


            $dadosGestores = $fluxo->buscaProximosGestores($params['idSolicitacao']);
            if(count($dadosGestores) > 0):
                foreach($dadosGestores as $val):
                    $mensagem .= "<b>".$val['nome_gestor']."</b><br/>";
                endforeach;
            endif;

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
            $fluxo = new Fluxo();
            $assunto = "Solicitação de Programa em Grupo";
            $nomeRemetente = "SGA - Sistema de Gestão de Acesso";

            foreach($documento['idAcompanhante'] as $val):

                $dadosEmail = $fluxo->buscaDadosEmailCopiaGeral($val, $params['idSolicitacao']);
                //$dadosEmail = $f->buscaDadosEmailCopia($params);

                if($dadosEmail != 0):
                    $mensagem = '
                        Olá, <b>'.$dadosEmail[0]['usuario_copia'].'</b>.<br/><br/>

                        <span style="font-size:14px;margin-top:20px">A atividade de número <b>'.$params['idSolicitacao'].'</b>, do grupo <b>'.$_POST['idLegGrupo']. ' - '.$_POST['descAbrev'].'</b> em que você está acompanhando, foi cancelada.</span>
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

                <span style="font-size:14px;margin-top:20px">A atividade de número <b>'.$params['idSolicitacao'].'</b>, do grupo <b>'.$_POST['idLegGrupo']. ' - '.$_POST['descAbrev'].'</b>, foi cancelada.</span>
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
            <span style="font-size:14px;margin-top:20px"><b>Atividade:</b> {tipoFluxo} do grupo <b>'.$_POST['idLegGrupo']. ' - '.$_POST['descAbrev'].'</b></span>
            <br/>
            <br/>
            <br/>					
            <a href="'.URL.'/fluxo/centralDeTarefa" style="background: #4ca5a9;color: #ffffff; text-align: center; text-decoration: none;padding: 15px 30px; font-family: Arial, sans-serif; font-size: 16px">Ir para Central de Tarefas</a>';

        // Envia email para o gestor responsável
        $dadosEmailUsuario  = $email->cadadosUsuario($idUsuario);
        $dadosEmailGestor   = $email->cadadosUsuario($idGestor);

        $mensagem = str_replace('{gestor}', $dadosEmailGestor['nome_usuario'], $mensagem);
        //$mensagem = str_replace('{usuario}', $dadosEmailUsuario['nome_usuario'], $mensagem);
        $mensagem = str_replace('{tipoFluxo}', $tipoFluxo, $mensagem);
        $mensagem = str_replace('{texto}', $textoMensagem, $mensagem);

        $template = $email->getTemplate($mensagem);

        $nomeRemetente = "SGA - Sistema de Gestão de Acesso";

        $email->enviaEmail($nomeRemetente,$assunto, $template, $dadosEmailGestor['email']);
    }

}