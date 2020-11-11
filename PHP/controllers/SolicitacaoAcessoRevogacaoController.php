<?php

class SolicitacaoAcessoRevogacaoController extends Controller
{
    public function __construct() {
        parent::__construct();        
    }
    
    /**
     * Carrega a tela de fluxo de solicitação de acesso por grupo
     */
    public function index()
    {                
        $s = new SolicitacaoAcessoRevogacao();
        $data = [];
        
//        echo "<pre>";
//        print_r($_SESSION);
//        die();
        //$_SESSION['idUsrTotvs'] = 5;
        //$_SESSION['empresaid']  = 1;
        
        $solicUsuarios = $s->carregaUsuariosSolicitante($_SESSION["idUsrTotvs"], $_SESSION["empresaid"]);
        $data['usuarios']    = isset($solicUsuarios['usuarios']) ? $solicUsuarios['usuarios'] :  $solicUsuarios['solicitante'];
        $data['solicitante'] = $solicUsuarios['solicitante'];
        $data['acompanhante'] = $s->usuariosAcompanhantes();
        $data['idFluxo'] = 8;
        
        $this->loadTemplate('solicitacao_acesso_revogacao_cadastro', $data);
    }
    
    /**
     * Cria a solicitação para cada usuário selecionado na tela de abertura de solicitação de acesso
     */
    public function criaSolicitacaoAcesso()
    {
        //$this->helper->debug($_POST,true);
        // Valida se foi selecionado ao menos um programa
        //$this->helper->debug($_POST, true);
        /*if(count($_POST['manterStatus']) == 0):
            $this->helper->setAlert(
                'error',
                'Favor selecionar ao menos um grupo',
                'SolicitacaoAcessoGrupo/'
            );
        endif;*/
        
        // Cria array com os ids dos programas
        $idGrupos = [];
        if(isset($_POST['manterStatus'])):
            foreach($_POST['manterStatus'] as $key => $val):
                array_push($idGrupos, $val);
            endforeach;
        endif;   
        $s              = new SolicitacaoAcessoGrupo();
        $fluxo          = new Fluxo();
        $idEmpresa      = $_POST['idEmpresa'];
        $idFluxo        = $_POST['idFluxo'];
        //$grupos         = $_POST['idGrupo'];
        $idUsuarios     = $_POST['idUsuario'];
        $idSolicitante  = $_POST['idSolicitante'];
        
        setlocale(LC_TIME, 'pt_BR');
        date_default_timezone_set('America/Sao_Paulo');
        $dataMovimentacao = date('Y-m-d H:i:s');
                
        // Recupera os dados dos usuários a abrir a solicitação
        // Se não encontrar retorna mensagem de erro
        $rsUsuarios = $s->dadosUsuariosSolicitacao($idUsuarios, $idEmpresa);
        if($rsUsuarios['return'] == false):
            $this->helper->setAlert(
                'error',
                $rsUsuarios['error'],
                'SolicitacaoAcessoGrupo/'
            );
        endif;
        
        // Percorre os usuários e abre a solicitação
        foreach ($rsUsuarios['dados'] as  $value):
            $usuario        = $value['nome_usuario'];
            $idUsuario      = $value['z_sga_usuarios_id'];
            $idTotvs        = $value['cod_usuario'];
            $gestorUsuario  = $value['cod_gestor'];
            $idGestor       = "";
            $lista          = array();           

            if(empty($gestorUsuario)):
                $gestorUsuario = "super";
            endif;

            // Retorna o gestor do usuário
            $rsGestor = $s->dadosGestorUsuario($gestorUsuario);

            if($rsGestor['return'] == true):                                
                $idGestor =  $rsGestor['idGestor'];
            else:
                $this->helper->setAlert(
                    'error',
                    $rsGestor['error'],
                    'SolicitacaoAcessoRevogacao/'
                );
            endif;

            // Cria a solicitação e retorna o ID da mesma
            $solicitacao = $fluxo->cadastraNumSolicitacao($idFluxo, $idSolicitante, '', $dataMovimentacao);
            $idSolicitacao = $solicitacao['idSolic'];

            if(isset($_POST['manterStatus'])):
                // Busca os dados do grupo de cada usuário a ser adicionado a um fluxo
                $rsGrupos = $s->dadosGestorGrupos($idGrupos, $idUsuario, $idEmpresa);
                if($rsGrupos['return'] == true):
                    $dadosGrupos =  $rsGrupos['dados'];
                else:
                    $this->helper->setAlert(
                        'error',
                        $rsGrupos['error'],
                        'SolicitacaoAcessoRevogacao/'
                    );
                endif;
                //$this->helper->debug($_POST, true);
                // Percorre os grupos
                foreach ($dadosGrupos as $key => $grupo):
                    // Busca se existe gestor de programas ou modulos para os programas existentes no grupo
                    $rsGestorMRP = $s->dadosGestorMRP($grupo['idGrupo'], $idEmpresa);
                    
                    $gestModul = array();
                    $gestRot = array();
                    $gestProg = array();

                    if($rsGestorMRP['return'] == true):                            
                        $dadosGestorModulo = $rsGestorMRP['dados'];
                        $idGestMod = [];
                        $idGestRot = [];
                        $idGestProg = [];

                        foreach($dadosGestorModulo as $val):
                            if($val['codModul'] != '*' && $val['codRotina'] == '*' && $val['codProg'] == '*' && !in_array($val['idGestor'], $idGestMod)):
                                $dadosMod = array(
                                    'id'        => $val['idGestor'],
                                    'nome'      => $val['nomeGestor'],
                                    'obs'       => '',
                                    'aprovacao' => '',
                                    'cartaRisco' => '',
                                    'modulo'    => $val['modulo']
                                );
                                $gestModul[] = $dadosMod;
                                $idGestMod[] = $val['idGestor'];                                    
                            elseif($val['codModul'] != '*' && $val['codRotina'] != '*' && $val['codProg'] == '*' && !in_array($val['idGestor'], $idGestRot)):
                                $dadosRot = array(
                                    'id'        => $val['idGestor'],
                                    'nome'      => $val['nomeGestor'],
                                    'obs'       => '',
                                    'aprovacao' => '',
                                    'cartaRisco' => '',
                                    'rotina'    => $val['rotina']
                                );                                    
                                $gestRot[] = $dadosRot;
                                $idGestRot[] = $val['idGestor'];
                            elseif($val['codModul'] != '*' && $val['codRotina'] != '*' && $val['codProg'] != '*' && !in_array($val['idGestor'], $idGestProg) /*&& in_array($val['codProg'], array_column($_POST['programa'], 'codProg'))*/):
                                $dadosProg = array(
                                    'id'        => $val['idGestor'],
                                    'nome'      => $val['nomeGestor'],
                                    'obs'       => '',
                                    'aprovacao' => '',
                                    'cartaRisco' => '',
                                    'programa'  => $val['programa']
                                );                                    
                                $gestProg[] = $dadosProg;
                                $idGestProg[] = $val['idGestor'];
                            endif;

                        endforeach;
                    endif;

                    // Prepara o JSON de grupos, módulos, rotinas e programas
                    $dados = array(
                        'programas'         => $grupo['programas'],
                        'nrProgramas'       => $grupo['nrProgramas'],
                        'nrUsuarios'        => $grupo['nrUsuarios'],
                        'idLinhaGrupo'      => $key,
                        'idLegGrupo'        => $grupo['idLegGrupo'],
                        'idGrupo'           => $grupo['idGrupo'],
                        'descAbrev'         => $grupo['descAbrev'],
                        'nomeGestor'        => $grupo['nomeGestor'],
                        'codGest'           => $grupo['codGest'],
                        'idCodGest'         => $grupo['idCodGest'],
                        'manterStatus'      => (isset($_POST['manterStatus'][$key]) && $_POST['manterStatus'][$key] == true) ? 1 : 0,
                        'obs'               => '',
                        'aprovacao'         => '',
                        'cartaRisco'       => '',
                        'obsGestorUsuario'  => '',
                        'gestorModulo'      => $gestModul,
                        'gestorRotina'      => $gestRot,
                        'gestorPrograma'    => $gestProg
                    );
                    $lista[$key] = $dados;
                endforeach;                                       
                //$this->helper->debug($lista, true);
            endif;
            
            // Prepara os grupos a remover
            $gruposRemover = [];
            foreach($_POST['removerStatus'] as $key => $val):
                $gruposDadosRemover = [
                    'descAbrev'         => $val['descAbrev'],
                    'programas'         => $val['programas'], 
                    'idLegGrupo'        => $val['idLegGrupo'],
                    'nrUsuarios'        => $val['nrUsuarios'],
                    'gestorGrupo'       => $val['gestorGrupo'],
                    'nrProgramas'       => $val['nrProgramas'],
                    'idGrupoRemover'    => $val['idGrupoRemover'],
                    'removerStatus'     => (isset($val['removerStatus']) ? $val['removerStatus'] : 0),
                    'removeStart'       => (isset($val['removerStatus']) ? $val['removerStatus'] : 0)
                ];
                $gruposRemover[] = $gruposDadosRemover;
            endforeach;

            // Prepara o JSON do documento
            $dadosDoc = array(
                'dataInicio'       => $dataMovimentacao,
                'idSolicitacao'    => $idSolicitacao,
                'idSolicitante'   =>  $idSolicitante,
                'usuario'          => $usuario,
                'idusuario'        => $idUsuario,
                'idTotvs'          => $idTotvs,
                'idAcompanhante'   => (isset($_POST['idUsuarioAcompanhante']) && count($_POST['idUsuarioAcompanhante']) > 0 ? $_POST['idUsuarioAcompanhante'] : []),
                'tipoEnvioMailCopia' => $_POST['tipoEnvioMailCopia'],
                'gestorUsuario'    => $gestorUsuario,
                'idGestorUsuario'  => $idGestor,
                'aprovacao_si'     => '',
                'exigirCartaRisco' => '',
                'remover'          => $gruposRemover/*$_POST['removerStatus']*/,
                'grupos'           => (isset($lista) ? $lista : []),
                //'gruposSolicitados' => $_POST['grupo']
            );

            //foreach($_POST['grupo'] as $key => $grupo):
            //   if(isset($_POST['manterStatus'][$key]) && $_POST['manterStatus'][$key] == true):
            //        $dadosDoc['gruposSolicitados'][] = $grupo;
            //    endif;
            //endforeach;

            // Cria o documento JSON
            $documento = json_encode($dadosDoc, true);
            //$this->helper->debug($documento, true);
            // Grava o documento JSON na tabela de documentos
            $fluxo->criaDocumento($documento, $idFluxo, $idFluxo, $idSolicitacao);

            // Recupera o id da atividade de solicitacao
            $ativSolicitante = $s->buscaIdAtividadeSolicitante($idFluxo);
            
            // Busca o id da próxima atividade.
            $idProximaAtiv = $fluxo->verificaProximaAtividade($ativSolicitante);
            $idProximaAtiv = $idProximaAtiv['proximaAtiv'];

            //f(!isset($_POST['manterStatus'])):
            //    $idProximaAtiv = 
            //endif;
            // Verifica se foi o próprio gestor de usuario quem abriu a solicitação se sim, envia direto para o próximo aprovador.
            //if($idSolicitante == $idGestor):
            //    $idProximaAtiv = $fluxo->verificaProximaAtividade($idProximaAtiv);
            //    $idProximaAtiv = $idProximaAtiv['proximaAtiv'];
            //endif;

            // Recupera nome de objeto instancia da proxima atividade
            $objProxAtividade = $s->buscaObjetoProxAtividade($idProximaAtiv);
            
            // Cria array com parametros necessários para execução do método responsável pela criação da movimentação
            $paramsSolicitacao = array(
                'idUsuario'         => $idUsuario,
                'idGestorUsuario'   => $idGestor,
                'idSolicitacao'     => $idSolicitacao,
                'idProximaAtiv'     => $idProximaAtiv,
                'idSolicitante'     => $idSolicitante,
                'idFluxo'           => $idFluxo,
                'lista'             => $lista,
                'usuariosCopia'     => $_POST['idUsuarioAcompanhante'],
                'tipoEnvioMailCopia' => $_POST['tipoEnvioMailCopia'],
                'tipoFluxo'          => 'Solicitação de permissão x revogação de grupos',
                'dataMovimentacao'  => date('Y-m-d H:i:s')
            );

            // Executa método responsável pela criação da movimentação
            if($objProxAtividade == 'criaAtividadeGestorModulo' || $objProxAtividade == 'criaAtividadeGestorRotina' || $objProxAtividade == 'criaAtividadeGestorPrograma'):
                $aprovador = lcfirst(str_replace('criaAtividade', '', $objProxAtividade));
                $this->criaAtividadeGestorMRP($_POST, $paramsSolicitacao, $aprovador);
            else:
                $this->$objProxAtividade($paramsSolicitacao);
            endif;

            //die($idSolicitacao);

            // Insere usuários acompanhante na tabela z_sga_fluxo_agendamento_acesso_acompanhante
            if(count($_POST['idUsuarioAcompanhante']) > 0 && $_POST['idUsuarioAcompanhante'][0] != ''):
                foreach($_POST['idUsuarioAcompanhante'] as $val):
                    $s->guardaUsuarioAcompanhante($idUsuario, $val, $idSolicitacao);
            
                    // Envia email para usuários em cópia
                    $paramsSolicitacao['idUsuarioCopia'] = $val;
                    $this->enviaEmailCopia($paramsSolicitacao);
                endforeach;
            endif;
            
            
            
            // Limpa as variaveis json e array grupos
            unset($documento);
            unset($lista);
            unset($idGestores); 
        endforeach;
        //$this->helper->debug($_POST,true);
        $this->helper->setAlert(
            'success',
            "Solicitação de código <strong style=\"color:#333;font-size;font-size: 14px;\">".$idSolicitacao."</strong> aberta com sucesso!",
            'SolicitacaoAcessoRevogacao/'
        );
    }
    
    /**
     * Cria atividade para gestor de usuário
     * @param type $paramsSolicitacao
     */
    public function criaAtividadeGestorUsuario($paramsSolicitacao)
    {
        $fluxo = new Fluxo();        
        $idGestorResponsavel = $fluxo->buscaUsrAlternativoFluxos($paramsSolicitacao['idGestorUsuario']);

        try{
            $fluxo->cadastraMovimentacao($paramsSolicitacao['idSolicitacao'], $paramsSolicitacao['idProximaAtiv'], $paramsSolicitacao['dataMovimentacao'], $paramsSolicitacao['idSolicitante'], $idGestorResponsavel, $paramsSolicitacao['idFluxo'], '');
            
            // Envia email para os gestores
            $this->enviaEmail($idGestorResponsavel, $paramsSolicitacao['idUsuario']);                      
        }catch (Exception $e){
            return array(
                'return' => false,
                'error'  => $e->getMessage()
            );            
        }
        
        return array('return' => true);
    }
    
    /**
     * Cria atividade para gestor de grupos
     * @param type $paramsSolicitacao
     */
    public function criaAtividadeGestorGrupo($paramsSolicitacao)
    {
        $fluxo = new Fluxo();
        
        // Se próxima atividade for aprovação de grupos. Cria uma atividade para cada gestor de grupo
        // OBS.: Criar uma única atividade por gestor
        $idGestores = array();

        foreach($paramsSolicitacao['lista'] as $valGrupo):
            // Se o id do Gestor de grupos  não estiver no array de gestores cria atividade para o mesmo.
            if(!in_array($valGrupo['idCodGest'], $idGestores)):
                $idGestores[] = $valGrupo['idCodGest'];

                // Verifica se o gestor possui usuário alternativo            
                $idGestorResponsavel = $fluxo->buscaUsrAlternativoFluxos($valGrupo['idCodGest']);
                
                // Cria atividade
                $fluxo->cadastraMovimentacao($paramsSolicitacao['idSolicitacao'], $paramsSolicitacao['idProximaAtiv'], $paramsSolicitacao['dataMovimentacao'], $paramsSolicitacao['idSolicitante'], $idGestorResponsavel, $paramsSolicitacao['idFluxo'], '');

                // Envia email para os gestores
                $this->enviaEmail($idGestorResponsavel, $paramsSolicitacao['idUsuario']);
            endif;
        endforeach;                
    }

    /**
     * CRIA UMA NOVA MOVIMENTAÇÃO PARA GESTORES DE PROGRAMA, ROTINA ou MÓDULO, VALIDANDO SE GESTOR POSSUI USUÁRIO APROVADOR ALTERNATIVO
     * @param $post
     * @param $params
     */
    public function criaAtividadeGestorMRP($post, $params, $aprovador)
    {
        $fluxo = new Fluxo();

        // Busca o documento atualizado
        $rsDoc = $fluxo->carregaDocumento($params['idSolicitacao']);
        $documento = json_decode($rsDoc['documento'], true);

        // OBS.: Cria uma única atividade por gestor
        $idGestores = array();

        $criouMovimentacao = false;

        foreach($documento['grupos'] as $grupos):
            // Se o id do Gestor não estiver no array de gestores cria atividade para o mesmo.
            if($grupos['manterStatus'] == 1 && isset($grupos[$aprovador])):
                foreach($grupos[$aprovador] as $gest):
                    if(is_array($gest) && !in_array($gest['id'], $idGestores)):
                        $idGestores[] = $gest['id'];
                        $idGestorResponsavel = $fluxo->buscaUsrAlternativoFluxos($gest['id']);

                        // Cadastra movimentação para o gestor
                        $fluxo->cadastraMovimentacao($params['idSolicitacao'], $params['idProximaAtiv'], $params['dataMovimentacao'], $post['idSolicitante'], $idGestorResponsavel,$post['idFluxo'],'');

                        // Envia email para o gestor responsável
                        $this->enviaEmail($idGestorResponsavel, $params['idUsuario']);

                        $criouMovimentacao = true;
                    endif;
                endforeach;
            endif;
        endforeach;

        // Valida se foi criado movimentação.
        // Se não, executa método de criação de movimentação da próxima atividade
        $this->criaProximaAtividade($criouMovimentacao, $params, $post);
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

            // Executa método responsável pela criação da movimentação
            if($objProxAtividade == 'criaAtividadeGestorModulo' || $objProxAtividade == 'criaAtividadeGestorRotina' || $objProxAtividade == 'criaAtividadeGestorPrograma'):
                $aprovador = lcfirst(str_replace('criaAtividade', '', $objProxAtividade));
                $this->criaAtividadeGestorMRP($post, $params, $aprovador);
            else:
                $this->$objProxAtividade($params);
            endif;
        endif;
    }

    /**
     * Busca os programas com filtro no campo de busca de programas
     */
    public function ajaxBuscaGrupos()
    {                
        $s = new SolicitacaoAcessoRevogacao();

        if (isset($_POST['string']) && !empty($_POST['string'])) {
            $grupos = $s->buscaGrupos($_POST['string'], $_POST['idUsuario'], (isset($_POST['gruposJaAdd']) ? $_POST['gruposJaAdd'] : []));
            foreach ($grupos as $value) {
                echo '<li onclick="carregaDadosGrupo(' . "'" . $value['idLegGrupo'] . ' - ' . $value["descAbrev"] . "'" . "," . "'" . $value["idGrupo"] . "'" . ')">' . $value['idLegGrupo'] . ' - ' . $value['descAbrev'] . '</li>';
            }
        }
    }
    
    /**
     * Envia o email dos fluxos     
     * @param $idGestor
     * @param $idUsuario
     * @param $mensagem
     */
    public function enviaEmail($idGestor, $idUsuario, $mensagem = '')
    {
        $email = new Email();
        $assunto = "Solicitação de Acesso";
        $nomeRemetente = "SGA - Sistema de Gestão de Acesso por Revogação";

        // Substitui mensagem padrão por mensagem recebida por parametro
        if(empty($mensagem)):
            // Cria html de email a ser enviado.
            
            $mensagem = '
                Olá, <b>{gestor}</b>.<br/><br/>

                <span style="font-size:14px;margin-top:20px">Informamos que a atividade abaixo, está sob sua responsabilidade e precisa de sua ação.</span>
                <br><br>
                <span style="font-size:14px;margin-top:20px"><b>Atividade:</b> Solicitação de Acesso por Revogação do usuário <strong>{usuario}</strong>.</span>
                <br>
                <span style="font-size:14px;margin-top:20px">Acompanhe a solicitação em seu painel de atividades.</span><br>';
            
            $mensagem .= '<br/>
                <br/>
                <br/>				
                <a href="'.URL.'/Fluxo/centralDeTarefa" style="background: #4ca5a9;color: #ffffff; text-align: center; text-decoration: none;padding: 15px 30px; font-family: Arial, sans-serif; font-size: 16px">Ir para Central de Tarefas</a>';                        
        endif;


        // Envia email para o gestor responsável
        $dadosEmailUsuario  = $email->cadadosUsuario($idUsuario);
        $dadosEmailGestor   = $email->cadadosUsuario($idGestor);

        $mensagem = str_replace('{gestor}', $dadosEmailGestor['nome_usuario'], $mensagem);
        $mensagem = str_replace('{usuario}', $dadosEmailUsuario['nome_usuario'], $mensagem);
        
        $template = $email->getTemplate($mensagem);
        $email->enviaEmail($nomeRemetente,$assunto, $template, $dadosEmailGestor['email']);
    }        
    
    /**
     * Envia o email de cópia dos fluxos
     * @param $paramSolicitacao     
     */
    public function enviaEmailCopia($paramsSolicitacao)
    {
        $email = new Email();
        $f = new Fluxo();
        $assunto = "Solicitação de Acesso";
        $nomeRemetente = "SGA - Sistema de Gestão de Acesso por Revogação";
        
        $dadosEmail = $f->buscaDadosEmailCopia($paramsSolicitacao);
        
        if($dadosEmail != 0):
            $mensagem = '
                Olá, <b>'.$dadosEmail['usuario_copia'].'</b>.<br/><br/>

                <span style="font-size:14px;margin-top:20px">O solicitante <b>'.$dadosEmail['solicitante'].'</b> te colocou em cópia da seguinte atividade:</span>
                <br><br>
                <span style="font-size:14px;margin-top:20px"><b>Atividade:</b> '.$paramsSolicitacao['tipoFluxo'].' do usuário <strong>'.$dadosEmail['nome_usuario'].'</strong>.</span>
                <br>
                <span style="font-size:14px;margin-top:20px">Acompanhe a solicitação em seu painel de atividades.</span><br>';
            
            if($paramsSolicitacao['tipoEnvioMailCopia'] == 'tudo'):
                $mensagem .= '<span style="font-size:14px;margin-top:20px">Você receberá um e-mail quando houver movimentação do status dessa atividade.</span>';
            else:
                $mensagem .= '<span style="font-size:14px;margin-top:20px">Você receberá um e-mail quando a atividade for cancelada ou finalizada.</span>';
            endif;
                

            $mensagem .= '<br/>
                <br/>
                <br/>				
                <a href="'.URL.'/Fluxo/centralDeTarefa" style="background: #4ca5a9;color: #ffffff; text-align: center; text-decoration: none;padding: 15px 30px; font-family: Arial, sans-serif; font-size: 16px">Ir para Central de Tarefas</a>';

            $template = $email->getTemplate($mensagem);                              
            $email->enviaEmail($nomeRemetente,$assunto, $template, $dadosEmail['email']);
        endif;
    }
    
    /**
     * Adiciona grupo e programa à relação de grupos adicionados
     */
    public function ajaxValidaGrupo()
    {
        // Valida se foi selecionado um usuario e um grupo
        if(!isset($_POST['idUsuario']) || $_POST['idUsuario'] == ''):
            return '0';
        endif;                                   
        
        $s = new SolicitacaoAcessoRevogacao();
        
        // Valida se usuário já possui solitição aberta com mesmo grupo
        // true = possui, false = não possu
        $validaSolicitacaoAberta = $s->validaSolicitacaoAberta($_POST);

        if(is_array($validaSolicitacaoAberta) && $validaSolicitacaoAberta['return'] === false):
            echo json_encode(array(
                'return' => 'duplicidade',
                'solicitacao' => $validaSolicitacaoAberta['solicitacao']
            ));
            die('');
        else:
            echo json_encode(array(
                'return' => true                
            ));
        endif;
        
        
        // Valida se o usuário já possui algum grupo igual ao solicitado
        /*$validaGrupo = $s->validaGrupo($_POST);
        if($validaGrupo['return']):
            echo json_encode(array(
                'return' => false,
                'grupos' => $validaGrupo['grupos']
            ));
            die('');
        else:
            echo json_encode(array(
                'return' => true                
            ));
        die('');
        endif;*/
    }
    
    /**
     * Adiciona grupo e programa à relação de grupos adicionados
     */
    public function ajaxCarregaGruposProgramasAdd()
    {
        // Valida se foi selecionado um usuario e um programa
        if(!isset($_POST['idUsuario']) || $_POST['idUsuario'] == ''):
            return '0';
        endif;                                   
        
        $s = new SolicitacaoAcessoRevogacao();
        
        // Retorna os grupos a adicionar removendo os grupos que o usuário já possui
        $grupos = $s->carregaGrupoProgramas($_POST['idUsuario']);
        
        $html = '';
        foreach ($grupos as $key => $val):
            $html .= '    <tr valign="top">';
            //$html .= '        <td><input type="hidden" name="idGrupo[]" class="idGrupo" value="'.$val['idGrupo'].'">'.$val['idLegGrupo'].'</td>';
            $html .= '        <td>'.$val['idLegGrupo'].'</td>';
            $html .= '        <td>'.$val['descAbrev'].'</td>';
            $html .= '        <td>'.$val['gestorGrupo'].'</td>';
            $html .= '        <td><div style="max-height:150px;overflow:auto">'.$val['programas'].'</div></td>';
            $html .= '        <td><center>'.$val['nrProgramas'].'</center></td>';
            $html .= '        <td><center>'.$val['nrUsuarios'].'</center></td>';
            $html .= '        <td><center><input type="checkbox" name="manterStatus[]" class="manterStatus"  value="'.$val['idGrupo'].'" onClick="carregaRiscosGrupos();"></center></td>';
            $html .= '    </tr>';
        endforeach;
        
        echo $html;
    }        
    
    /**
     * Adiciona grupo e programa à relação de programas adicionados
     */
    public function ajaxCarregaGruposJaAdicionados()
    {
        // Valida se foi selecionado um usuario e um programa
        if(/*!isset($_POST['idPrograma']) || $_POST['idPrograma'] == '' || */!isset($_POST['idUsuario']) || $_POST['idUsuario'] == ''):
            return '0';
        endif;
        
        $s = new SolicitacaoAcessoRevogacao();
        $grupos = $s->carregaGrupoProgramasJaAdicionados(/*$_POST['idPrograma'],*/ $_POST['idUsuario']);
       
        $html = '';
        foreach ($grupos as $key => $val):
            $html .= '    <tr valign="top">';
            $html .= '        <td><input type="hidden" class="idGrupoJaAdicionados" name="removerStatus['.$key.'][idGrupoRemover]" value="'.$val['idGrupo'].'">
            <input type="hidden" class="idGrupoJaAdicionados" name="removerStatus['.$key.'][idLegGrupo]" value="'.$val['idLegGrupo'].'">'.$val['idLegGrupo'].'</td>';
            $html .= '        <td><input type="hidden" class="idGrupoJaAdicionados" name="removerStatus['.$key.'][descAbrev]" value="'.$val['descAbrev'].'">'.$val['descAbrev'].'</td>';
            $html .= '        <td><input type="hidden" class="idGrupoJaAdicionados" name="removerStatus['.$key.'][gestorGrupo]" value="'.$val['gestorGrupo'].'">'.$val['gestorGrupo'].'</td>';
            $html .= '        <td><input type="hidden" class="idGrupoJaAdicionados" name="removerStatus['.$key.'][programas]" value="'.$val['programas'].'"><div style="max-height:79px;overflow-x:auto">'.$val['programas'].'</div></td>';
            $html .= '        <td><input type="hidden" class="idGrupoJaAdicionados" name="removerStatus['.$key.'][nrProgramas]" value="'.$val['nrProgramas'].'"><center>'.$val['nrProgramas'].'</center></td>';
            $html .= '        <td><input type="hidden" class="idGrupoJaAdicionados" name="removerStatus['.$key.'][nrUsuarios]" value="'.$val['nrUsuarios'].'"><center>'.$val['nrUsuarios'].'</center></td>';            
            $html .= '        <td><center><input type="checkbox" name="removerStatus['.$key.'][removerStatus]" class="removerStatus" value="'.$val['idGrupo'].'" onClick="carregaRiscosGrupos();"></center></td>';
            $html .= '    </tr>';
        endforeach;                        
        
        echo $html;
            
    }
    
    /**
     * Carrega matriz de risco por ids de grupos
     */
    public function ajaxMatrizDeRisco()
    {
        $dados = array();
        $s = new SolicitacaoAcessoRevogacao();
        $dados['conflitos'] = $s->fluxoMatrizRisco($_POST['grupos']);
        $totalRiscos = $s->fluxoMatrizCountRisco($_POST['grupos']);
        $dados['totalProgByGrupo'] = $s->getCountTableAbaProgs("z_sga_programas p", '', array(0 => 'DISTINCT p.z_sga_programas_id'), '', $_SESSION['empresaid'], $_POST['grupos']);
        $dados['totalPessoais'] = $s->getCountTableAbaPessoais("z_sga_programas p", '', array(0 => 'distinct zslc.name'), '', $_SESSION['empresaid'], $_POST['grupos']);
        $dados['totalSensiveis'] = $s->getCountTableAbaSensiveis("z_sga_programas p", '', array(0 => 'distinct zslc.name'), '', $_SESSION['empresaid'], $_POST['grupos']);
        $dados['totalAnonizados'] = $s->getCountTableAbaAnonizados("z_sga_programas p", '', array(0 => 'distinct zslc.name'), '', $_SESSION['empresaid'], $_POST['grupos']);
        
        $area = "";
        $risco = "";
        $idCollapseArea = 10000;
        $next = array();
        $idTabela = 0;

        $html = "";

        $html .= '<div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                  <div class="row">
                    <div class="col-sm-6"></div>
                    <div class="col-sm-6"></div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="panel-group" id="accordion">';

        foreach ($dados['conflitos'] as $value) {

            
            if($area != $value['descArea']){
                $area = $value['descArea'];
                $html .='<div class="panel panel-default">
                          <div class="panel-heading">
                            <h4 class="panel-title">
                              <a data-toggle="collapse" data-parent="#accordion" href="#collapse'.$idCollapseArea.'">
                              Area '.$value["descArea"].'</a>
                            </h4>
                          </div>
                          <div id="collapse'.$idCollapseArea.'" class="panel-collapse collapse">
                            <div class="panel-body">';
            }
            if($risco != $value['codRisco']){
                $risco = $value['codRisco'];
                $idTabela = $idTabela + 1;
                $html .=  "<h5><strong>".$value["codRisco"]."</strong> - ".$value["descRisco"]."</h5><br>";

                $html .= '<table class="tabela table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="DataTables_Table_0_info" style="width: 100%;" id="DataTables_Table_<?php echo $idTabela?>">
                          <thead>
                          <tr role="row"><td><strong>Composiçao do Risco</strong></td></tr>
                          <tr role="row">
                            <th>Grau de Risco</th>
                            <th>Processo Referencia</th>
                            <th>Programas do processo</th>
                            <th>Processos vinculados</th>
                            <th>Programas do processo</th>
                          </tr>
                         </thead>
                         <tbody>';

            }
            $html .='<tr>';
            $html .= '<td bgcolor="'.$value["bgcolor"].'"><font color="'.$value["fgcolor"].'">'.$value["grau"].'</font></td>';
            $html .= '<td>'.$value["processoPri"].'</td>';
            $html .= '<td>'.$value["progspPri"].'</td>';
            $html .= '<td>'.$value["processoSec"].'</td>';
            $html .=  '<td>'.$value["progspSec"].'</td>';
            $html .='</tr>';
            
            $next = next($dados['conflitos']);
            if(isset($next) && is_array($next)):
                if($risco != $next['codRisco']){
                    $html .='</tbody></table>';
                }


                if($area != $next['descArea']){
                    $html .='</div></div></div>';
                    $idCollapseArea = $idCollapseArea+1;
                }
            endif;
        }

        $html .='</div></div></div></div>';

        //echo $html;
        echo json_encode(array(
            'html' => $html,
            'totalRiscos' => $totalRiscos,
            'totalProgByGrupo' => $dados['totalProgByGrupo'],
            'totalPessoais' => $dados['totalPessoais'],
            'totalSensiveis' => $dados['totalSensiveis'],
            'totalAnonizados' => $dados['totalAnonizados']
        ));
    }
    
    /**
     * método para criação de jquery datatable na tela de edição de grupos para tab usuários.
     */
    public function ajaxCarregaAbaProg()
    {
        if(!isset($_POST['grupos'])):
           $output = array(
                "draw" => intval(isset($_POST["draw"]) ? $_POST["draw"] : ''),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => ''
            );
            echo json_encode($output);
            die('');
        endif;
        
        $dados = array();
        $data = array();
        $s = new SolicitacaoAcessoRevogacao();


        // usado pelo order by
        $fields = array(
            0   => 'g.idLegGrupo',
            //1   => 'g.descAbrev',
            1   => 'p.cod_programa',
            2   => 'p.descricao_programa',
            3   => 'p.cod_modulo',
            4   => 'p.descricao_modulo'
        );

        // Variaveis usadas na paginação do Jquery DataTable
        $search = (isset($_POST["search"]["value"])) ? $_POST["search"]["value"] : '';
        $orderColumn = (isset($_POST['order']['0']['column'])) ? $fields[$_POST['order']['0']['column']] : '';
        $orderDir = (isset($_POST['order']['0']['dir'])) ? $_POST['order']['0']['dir'] : 'ASC';
        $limit = (isset($_POST["length"]) && $_POST["length"] != -1) ? $_POST['length'] : '10';
        $offset = (isset($_POST["length"]) && $_POST["length"] != -1) ? $_POST['start'] : '0';

        
        // Traz apenas 10 usuarios utilizando paginação
        $dados = $s->carregaAbaProgFluxoByGrupo($search, $orderColumn, $orderDir, $offset, $limit, $_POST['grupos'], $_SESSION['empresaid']);

        // Traz todos os usuarios
        $total_all_records = $s->getCountTableAbaProgs("z_sga_programas p", $search, array('p.z_sga_programas_id'), '', $_SESSION['empresaid'], $_POST['grupos']);

        // Cria linhas customizadas no jquery datatable.
        if (count($dados) > 0):
            foreach ($dados as $key => $value):
                $sub_dados = array();            
                $sub_dados[] = $value["grupo"];                
                $sub_dados[] = $value["cod_programa"];
                $sub_dados[] = $value["descricao_programa"];
                $sub_dados[] = $value["cod_modulo"];
                $sub_dados[] = $value['descricao_modulo'];
                $data[] = $sub_dados;
            endforeach;
        endif;

        $output = array(
            "draw" => intval(isset($_POST["draw"]) ? $_POST["draw"] : ''),
            "recordsTotal" => count($dados),
            "recordsFiltered" => $total_all_records,
            "data" => $data
        );
        echo json_encode($output);
    }

    public function ajaxCarregaAbaPessoais()
    {
        if(!isset($_POST['grupos'])):
           $output = array(
                "draw" => intval(isset($_POST["draw"]) ? $_POST["draw"] : ''),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => ''
            );
            echo json_encode($output);
            die('');
        endif;
        
        $dados = array();
        $data = array();
        $s = new SolicitacaoAcessoRevogacao();


        // usado pelo order by
        $fields = array(
            0   => 'g.idLegGrupo',
            1   => 'p.cod_programa',
            2   => 'p.descricao_programa',
            3   => 'zslc.name',
            4   => 'zslc.anonymize'
        );

        // Variaveis usadas na paginação do Jquery DataTable
        $search = (isset($_POST["search"]["value"])) ? $_POST["search"]["value"] : '';
        $orderColumn = (isset($_POST['order']['0']['column'])) ? $fields[$_POST['order']['0']['column']] : '';
        $orderDir = (isset($_POST['order']['0']['dir'])) ? $_POST['order']['0']['dir'] : 'ASC';
        $limit = (isset($_POST["length"]) && $_POST["length"] != -1) ? $_POST['length'] : '10';
        $offset = (isset($_POST["length"]) && $_POST["length"] != -1) ? $_POST['start'] : '0';

        
        // Traz apenas 10 usuarios utilizando paginação
        $dados = $s->carregaAbaPessoaisRevogacao($search, $orderColumn, $orderDir, $offset, $limit, $_POST['grupos'], $_SESSION['empresaid']);

        // Traz todos os usuarios
        $total_all_records = $s->getCountTableAbaPessoais("z_sga_programas p", $search, array('distinct zslc.name'), '', $_SESSION['empresaid'], $_POST['grupos']);

        // Cria linhas customizadas no jquery datatable.
        if (count($dados) > 0):
            foreach ($dados as $key => $value):
                $sub_dados = array();            
                $sub_dados[] = $value["grupo"];                
                $sub_dados[] = $value["cod_programa"];
                $sub_dados[] = $value["descricao_programa"];
                $sub_dados[] = $value["Nome"];
                $sub_dados[] = $value['Anonizado'];
                $data[] = $sub_dados;
            endforeach;
        endif;

        $output = array(
            "draw" => intval(isset($_POST["draw"]) ? $_POST["draw"] : ''),
            "recordsTotal" => count($dados),
            "recordsFiltered" => $total_all_records,
            "data" => $data
        );
        echo json_encode($output);
    }

    public function ajaxCarregaAbaSensiveis()
    {
        if(!isset($_POST['grupos'])):
           $output = array(
                "draw" => intval(isset($_POST["draw"]) ? $_POST["draw"] : ''),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => ''
            );
            echo json_encode($output);
            die('');
        endif;
        
        $dados = array();
        $data = array();
        $s = new SolicitacaoAcessoRevogacao();


        // usado pelo order by
        $fields = array(
            0   => 'g.idLegGrupo',
            1   => 'p.cod_programa',
            2   => 'p.descricao_programa',
            3   => 'zslc.name',
            4   => 'zslc.anonymize'
        );

        // Variaveis usadas na paginação do Jquery DataTable
        $search = (isset($_POST["search"]["value"])) ? $_POST["search"]["value"] : '';
        $orderColumn = (isset($_POST['order']['0']['column'])) ? $fields[$_POST['order']['0']['column']] : '';
        $orderDir = (isset($_POST['order']['0']['dir'])) ? $_POST['order']['0']['dir'] : 'ASC';
        $limit = (isset($_POST["length"]) && $_POST["length"] != -1) ? $_POST['length'] : '10';
        $offset = (isset($_POST["length"]) && $_POST["length"] != -1) ? $_POST['start'] : '0';

        
        // Traz apenas 10 usuarios utilizando paginação
        $dados = $s->carregaAbaSensiveisRevogacao($search, $orderColumn, $orderDir, $offset, $limit, $_POST['grupos'], $_SESSION['empresaid']);

        // Traz todos os usuarios
        $total_all_records = $s->getCountTableAbaSensiveis("z_sga_programas p", $search, array('distinct zslc.name'), '', $_SESSION['empresaid'], $_POST['grupos']);

        // Cria linhas customizadas no jquery datatable.
        if (count($dados) > 0):
            foreach ($dados as $key => $value):
                $sub_dados = array();            
                $sub_dados[] = $value["grupo"];                
                $sub_dados[] = $value["cod_programa"];
                $sub_dados[] = $value["descricao_programa"];
                $sub_dados[] = $value["Nome"];
                $sub_dados[] = $value['Anonizado'];
                $data[] = $sub_dados;
            endforeach;
        endif;

        $output = array(
            "draw" => intval(isset($_POST["draw"]) ? $_POST["draw"] : ''),
            "recordsTotal" => count($dados),
            "recordsFiltered" => $total_all_records,
            "data" => $data
        );
        echo json_encode($output);
    }

    public function ajaxCarregaAbaAnonizados()
    {
        if(!isset($_POST['grupos'])):
           $output = array(
                "draw" => intval(isset($_POST["draw"]) ? $_POST["draw"] : ''),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => ''
            );
            echo json_encode($output);
            die('');
        endif;
        
        $dados = array();
        $data = array();
        $s = new SolicitacaoAcessoRevogacao();


        // usado pelo order by
        $fields = array(
            0   => 'g.idLegGrupo',
            1   => 'p.cod_programa',
            2   => 'p.descricao_programa',
            3   => 'zslc.name',
            4   => 'zslc.sensitive',
            5   => 'zslc.sensitive'
        );

        // Variaveis usadas na paginação do Jquery DataTable
        $search = (isset($_POST["search"]["value"])) ? $_POST["search"]["value"] : '';
        $orderColumn = (isset($_POST['order']['0']['column'])) ? $fields[$_POST['order']['0']['column']] : '';
        $orderDir = (isset($_POST['order']['0']['dir'])) ? $_POST['order']['0']['dir'] : 'ASC';
        $limit = (isset($_POST["length"]) && $_POST["length"] != -1) ? $_POST['length'] : '10';
        $offset = (isset($_POST["length"]) && $_POST["length"] != -1) ? $_POST['start'] : '0';

        
        // Traz apenas 10 usuarios utilizando paginação
        $dados = $s->carregaAbaAnonizadosRevogacao($search, $orderColumn, $orderDir, $offset, $limit, $_POST['grupos'], $_SESSION['empresaid']);

        // Traz todos os usuarios
        $total_all_records = $s->getCountTableAbaAnonizados("z_sga_programas p", $search, array('distinct zslc.name'), '', $_SESSION['empresaid'], $_POST['grupos']);

        // Cria linhas customizadas no jquery datatable.
        if (count($dados) > 0):
            foreach ($dados as $key => $value):
                $sub_dados = array();            
                $sub_dados[] = $value["grupo"];                
                $sub_dados[] = $value["cod_programa"];
                $sub_dados[] = $value["descricao_programa"];
                $sub_dados[] = $value["Nome"];
                $sub_dados[] = $value['Pessoal'];
                $sub_dados[] = $value['Sensivel'];
                $data[] = $sub_dados;
            endforeach;
        endif;

        $output = array(
            "draw" => intval(isset($_POST["draw"]) ? $_POST["draw"] : ''),
            "recordsTotal" => count($dados),
            "recordsFiltered" => $total_all_records,
            "data" => $data
        );
        echo json_encode($output);
    }
}