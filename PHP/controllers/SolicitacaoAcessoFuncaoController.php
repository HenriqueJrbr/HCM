<?php

class SolicitacaoAcessoFuncaoController extends Controller
{
    public function __construct() {
        parent::__construct();        
    }
    
    /**
     * Carrega a tela de fluxo de solicitação de acesso
     */
    public function index()
    {                
        $s = new SolicitacaoAcessoFuncao();
        $data = [];
        $idUsrTotvs = $_SESSION["idUsrTotvs"];
        $empresaid = $_SESSION["empresaid"];
        
        $solicUsuarios        = $s->carregaUsuariosSolicitante($idUsrTotvs, $empresaid);
        $data['usuarios']     = $solicUsuarios['usuarios'];
        $data['acompanhante'] = $s->usuariosAcompanhantes();
        $data['funcoes']      = $s->funcoes();
        $data['idFluxo']      = 5;        
        
        $this->loadTemplate('solicitacao_acesso_funcao_cadastro', $data);
    }
    
    /**
     * Cria a solicitação para cada usuário selecionado na tela de abertura de solicitação de acesso
     */
    public function criaSolicitacaoAcesso()
    {
        // Valida se foi selecionado ao menos um programa
        //$this->helper->debug($_POST, true);
        if(empty($_POST['idFuncao'])):
            $this->helper->setAlert(
                'error',
                'Favor selecionar ao menos um programa',
                'SolicitacaoAcesso/'
            );
        endif;
        
//        // Cria array com os ids dos programas
//        $idProgramas = [];
//        foreach($_POST['programa'] as $key => $val):
//            array_push($idProgramas, $val['idProg']);
//        endforeach;
                
        $s      = new SolicitacaoAcessoFuncao();
        $fluxo  = new Fluxo();        
        $idEmpresa      = $_POST['idEmpresa'];
        $idFluxo        = $_POST['idFluxo'];
        $idFuncao       = $_POST['idFuncao'];
        $grupos         = $_POST['idGrupo'];
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
                'SolicitacaoAcessoFuncao/'
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
                    'SolicitacaoAcessoFuncao/'
                );
            endif;

            // Cria a solicitação e retorna o ID da mesma
            $solicitacao = $fluxo->cadastraNumSolicitacao($idFluxo, $idSolicitante, '',$dataMovimentacao);
            $idSolicitacao = $solicitacao['idSolic'];

            // Busca os dados do grupo de cada usuário a ser adicionado a um fluxo
            $rsGrupos = $s->dadosGestorGrupos($idFuncao, $idEmpresa);
            if($rsGrupos['return'] == true):
                $dadosGrupos =  $rsGrupos['dados'];
            else:
                $this->helper->setAlert(
                    'error',
                    $rsGrupos['error'],
                    'SolicitacaoAcessoFuncao/'
                );
            endif;
                                                
            //$this->helper->debug($_POST, true);
            // Percorre os grupos
            foreach ($dadosGrupos as $key => $grupo):
                // Valida se foi selecionado na tela de cadastro                
                if(!in_array($grupo['idGrupo'], $_POST['manterStatus'])):
                    continue;
                else:
                    //echo 'Selecionado: '.  $grupo['idGrupo'];
                endif;
                
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
                    #'programas'         => $grupo['programas'],
                    'nrProgramas'       => $grupo['nrProgramas'],
                    'nrUsuarios'        => $grupo['nrUsuarios'],
                    'idLinhaGrupo'      => $key,
                    'idLegGrupo'        => $grupo['idLegGrupo'],
                    'idGrupo'           => $grupo['idGrupo'],
                    'descAbrev'         => $grupo['descAbrev'],
                    'nomeGestor'        => $grupo['nomeGestor'],
                    'codGest'           => $grupo['codGest'],
                    'idCodGest'         => $grupo['idCodGest'],
                    'manterStatus'      => 1,
                    'possui'            => $grupo['possui'],
                    'obs'               => '',
                    'aprovacao'         => '',
                    'cartaRisco'       => '',
                    'obsGestorUsuario'  => '',
                    'gestorModulo'      => $gestModul,
                    'gestorRotina'      => $gestRot,
                    'gestorPrograma'    => $gestProg
                );
                $lista[] = $dados;
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
                'grupos'           => $lista,
                'idFuncao'         => $idFuncao
            );

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
                'tipoFluxo'          => 'Solicitação de Acesso',
                'dataMovimentacao'  => date('Y-m-d H:i:s')
            );
            
            // Executa método responsável pela criação da movimentação
            $this->$objProxAtividade($paramsSolicitacao);
            
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
                        
        $this->helper->setAlert(
            'success',
            "Solicitação de código <strong style=\"color:#333;font-size;font-size: 14px;\">".$idSolicitacao."</strong> aberta com sucesso!",
            'SolicitacaoAcessoFuncao/'
        );
    }
    
    /**
     * Cria atividade para gestor de usuário
     * @param type $paramsSolicitacao
     * @return type
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
     * Envia o email dos fluxos     
     * @param $idGestor
     * @param $idUsuario
     * @param $mensagem
     */
    public function enviaEmail($idGestor, $idUsuario, $mensagem = '')
    {
        $email = new Email();
        $assunto = "Solicitação de Acesso";
        $nomeRemetente = "SGA - Sistema de Gestão de Acesso";

        // Sobstitui mensagem padrão por mensagem recebida por parametro
        if(empty($mensagem)):
            // Cria html de email a ser enviado.
            
            $mensagem = '
                Olá, <b>{gestor}</b>.<br/><br/>

                <span style="font-size:14px;margin-top:20px">Informamos que a atividade abaixo, está sob sua responsabilidade e precisa de sua ação.</span>
                <br><br>
                <span style="font-size:14px;margin-top:20px"><b>Atividade:</b> Solicitação de Acesso do usuário <strong>{usuario}</strong>.</span>
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
        $nomeRemetente = "SGA - Sistema de Gestão de Acesso";
        
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
     * Adiciona grupo e função à relação de funções adicionadas
     */
    public function ajaxValidaGrupoFuncao()
    {
        // Valida se foi selecionado um usuario e uma função
        if(!isset($_POST['idUsuario']) || $_POST['idUsuario'] == ''):            
            return '0';
        endif;                                   
        
        $s = new SolicitacaoAcessoFuncao();
        
        // Valida se usuário já possui solitição aberta com mesma função        
        // true = possui, false = não possui        
        $validaSolicitacaoAberta = $s->validaSolicitacaoAberta($_POST);
        
        if($validaSolicitacaoAberta['return'] === false):
            echo json_encode(array(
                'return' => 'duplicidade',
                'solicitacao' => $validaSolicitacaoAberta['solicitacao']
            ));
            die('');
        else:
            echo json_encode(array(
                'return' => false                
            ));
        endif;                       
    }
    
    /**
     * Adiciona grupo e função à relação de funções adicionadas
     */
    public function ajaxCarregaGruposFuncao()
    {
        // Valida se foi selecionado um usuario
        if(!isset($_POST['idUsuario']) || $_POST['idUsuario'] == ''):
            return '0';
        endif;                                   
                
        $s = new SolicitacaoAcessoFuncao();                
        
        // Busca os dados do usuário selecionado
        $usuario = $s->carregaDadosUsuario($_POST['idUsuario']);
        
        if(!isset($_POST['idFuncao']) && !isset($usuario['funcao'])):
            return 'sem funcao';
        endif;
        
        // Se existir uma função enviada pela requisição, usa a mesma. Caso contrário usa a função do cadastro do usuário.
        $idFuncao = isset($_POST['idFuncao']) && !empty($_POST['idFuncao']) ? $_POST['idFuncao'] : $usuario['funcao'];
        
        // Busca grupos pelo id da função
        $gruposFuncao = $s->carregaGruposFuncaoByIdFuncao($idFuncao, $_POST['idUsuario']);
        //$this->helper->debug($gruposFuncao, true);
        $htmlGruposFuncao = '';
        if(isset($gruposFuncao['return']) && $gruposFuncao['return']):            
            foreach ($gruposFuncao['rs'] as $val):            
                $htmlGruposFuncao .= '    <tr valign="top">';                            
                $htmlGruposFuncao .= '        <th><input type="hidden" name="idGrupo[]" class="idGrupo" value="'.$val['idGrupo'].'">'.$val['idLegGrupo'].'</th>';
                $htmlGruposFuncao .= '        <th>'.$val['descAbrev'].'</th>';
                $htmlGruposFuncao .= '        <th>'.$val['gestorGrupo'].'</th>';
                $htmlGruposFuncao .= '        <th><center>'.$val['nrProgramas'].'</center></th>';
                $htmlGruposFuncao .= '        <th><center>'.$val['nrUsuarios'].'</center></th>';                
                $htmlGruposFuncao .= '        <th><center><input type="hidden" name="possui[]" value="'.$val['possui'].'">'.$val['possui'].'</center></th>';
                $htmlGruposFuncao .= '        <th><center><input type="checkbox" name="manterStatus[]" class="manterStatus" value="'.$val['idGrupo'].'" checked="true"></center></th>';
                $htmlGruposFuncao .= '    </tr>';
            endforeach;
        endif;
        
        
        /*// Busca grupos que o usuário possui dentro da função
        $gruposPossuiNaFuncao = $s->carregaGruposUsuarioPossuiNaFuncao($_POST['idUsuario'], $idFuncao);
        $htmlGruposFuncaoNaFuncao = '';
        if($gruposPossuiNaFuncao['return']):            
            foreach ($gruposPossuiNaFuncao['rs'] as $val):            
                $htmlGruposFuncaoNaFuncao .= '    <tr valign="top">';                            
                $htmlGruposFuncaoNaFuncao .= '        <th><input type="hidden" name="idGrupo[]" class="idGrupo" value="'.$val['idGrupo'].'">'.$val['idLegGrupo'].'</th>';
                $htmlGruposFuncaoNaFuncao .= '        <th>'.$val['descAbrev'].'</th>';
                $htmlGruposFuncaoNaFuncao .= '        <th><center>'.$val['nrProgramas'].'</center></th>';
                $htmlGruposFuncaoNaFuncao .= '        <th><center>'.$val['nrUsuarios'].'</center></th>';                
                $htmlGruposFuncaoNaFuncao .= '    </tr>';
            endforeach;               
        endif;*/
        
        // Busca grupos que o usuário possui fora da função
        $gruposPossuiForaFuncao = $s->carregaGruposUsuarioPossuiForaFuncao($_POST['idUsuario'], $idFuncao);
        $htmlGruposFuncaoForaFuncao = '';
        if($gruposPossuiForaFuncao['return']):
            foreach ($gruposPossuiForaFuncao['rs'] as $val):
                $htmlGruposFuncaoForaFuncao .= '    <tr valign="top">';                
                $htmlGruposFuncaoForaFuncao .= '        <th>'.$val['idLegGrupo'].'</th>';
                $htmlGruposFuncaoForaFuncao .= '        <th>'.$val['descAbrev'].'</th>';
                $htmlGruposFuncaoForaFuncao .= '        <th>'.$val['gestorGrupo'].'</th>';
                $htmlGruposFuncaoForaFuncao .= '        <th><center>'.$val['nrProgramas'].'</center></th>';
                $htmlGruposFuncaoForaFuncao .= '        <th><center>'.$val['nrUsuarios'].'</center></th>';
                $htmlGruposFuncaoForaFuncao .= '    </tr>';
            endforeach;               
        endif;
        
        /*// Busca grupos irá receber na solicitação
        $gruposFuncaoAdicionar = $s->carregaGruposFuncaoAdicionar($idFuncao, $_POST['idUsuario']);
        $htmlGruposFuncaoAdicionar = '';
        if($gruposFuncaoAdicionar['return']):
            foreach ($gruposFuncaoAdicionar['rs'] as $val):
                $htmlGruposFuncaoAdicionar .= '    <tr valign="top">';                
                $htmlGruposFuncaoAdicionar .= '        <th><input type="hidden" name="idGrupo[]" class="idGrupo" value="'.$val['idGrupo'].'">'.$val['idLegGrupo'].'</th>';
                $htmlGruposFuncaoAdicionar .= '        <th>'.$val['descAbrev'].'</th>';
                $htmlGruposFuncaoAdicionar .= '        <th><center>'.$val['nrProgramas'].'</center></th>';
                $htmlGruposFuncaoAdicionar .= '        <th><center>'.$val['nrUsuarios'].'</center></th>';
                $htmlGruposFuncaoAdicionar .= '    </tr>';
            endforeach;               
        endif;*/
        
        
        
        echo json_encode(array(
            'gruposFuncao'           => $htmlGruposFuncao,
            //'gruposFuncaoNaFuncao'   => $htmlGruposFuncaoNaFuncao,
            'gruposFuncaoForaFuncao' => $htmlGruposFuncaoForaFuncao,
            //'gruposFuncaoAdicionar'  => $htmlGruposFuncaoAdicionar,
            'idFuncaoUsuario'        => $idFuncao
        ));
    }        
       
    /**
     * Carrega matriz de risco por ids de grupos
     */
    public function ajaxMatrizDeRisco()
    {
        $dados = array();
        $s = new SolicitacaoAcessoFuncao();
        $dados['conflitos'] = $s->fluxoMatrizRisco($_POST['grupos']);
        $totalRiscos = $s->fluxoMatrizCountRisco($_POST['grupos']);
        $dados['totalProgByGrupo'] = $s->getCountTableAbaProgs("z_sga_programas p", '', array(0 => 'DISTINCT p.z_sga_programas_id'), '', $_SESSION['empresaid'], $_POST['grupos']);                
        $dados['totalCamposPessoais'] = $s->getCountTableAbaPessoais("z_sga_programas p", '', array(0 => 'DISTINCT zslc.name'), '', $_SESSION['empresaid'], $_POST['grupos']);
        $dados['totalCamposSensiveis'] = $s->getCountTableAbaSensiveis("z_sga_programas p", '', array(0 => 'DISTINCT zslc.name'), '', $_SESSION['empresaid'], $_POST['grupos']);
        $dados['totalCamposAnonizados'] = $s->getCountTableAbaAnonizados("z_sga_programas p", '', array(0 => 'DISTINCT zslc.name'), '', $_SESSION['empresaid'], $_POST['grupos']);
        
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
            if($risco != $next['codRisco']){
                $html .='</tbody></table>';
            }


            if($area != $next['descArea']){
                $html .='</div></div></div>';
                $idCollapseArea = $idCollapseArea+1;
            }
        }

        $html .='</div></div></div></div>';

        //echo $html;
        echo json_encode(array(
            'html' => $html,
            'totalRiscos' => $totalRiscos,
            'totalProgByGrupo' => $dados['totalProgByGrupo'],
            'totalCamposPessoais' => $dados['totalCamposPessoais'],
            'totalCamposSensiveis' => $dados['totalCamposSensiveis'],
            'totalCamposAnonizados' => $dados['totalCamposAnonizados']

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
        $s = new SolicitacaoAcessoFuncao();


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
        $total_all_records = $s->getCountTableAbaProgs("z_sga_programas p", $search, array('DISTINCT p.z_sga_programas_id'), '', $_SESSION['empresaid'], $_POST['grupos']);

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

    /**
     * Método para criação do jquery datatable de processos baseado na função selecionada
     */
    public function ajaxCarregaProcessosGrupos()
    {
        if(!isset($_POST['idFuncao']) || empty($_POST['idFuncao'])):
            echo json_encode(array(
                'return' => 0
            ));
            die('');
        endif;
        
        $solicFuncao = new SolicitacaoAcessoFuncao();
        
        $process = $solicFuncao->carregaProcessos($_POST['idFuncao']);
        $html = '';
        if($process['return']):            
        
            foreach($process['dados'] as $val):                
                $html .= '    <tr valign="top">';                
                $html .= '        <th>'.$val['descProcesso'].'</th>';
                $html .= '        <th>'.$val['grupoProcesso'].'</th>';
                $html .= '        <th><center>'.$val['cod_programa'].'</center></th>';
                $html .= '        <th><center>'.$val['grupos'].'</center></th>';
                $html .= '    </tr>';
            endforeach;                       
            echo json_encode(array(
                'total' => count($process['dados']),
                'html'  => $html                
            ));
        else:
            echo json_encode(array(
                'total' => 0,
                'html'  => $html  
            ));
        endif;
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
        $s = new SolicitacaoAcessoFuncao();


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
        $dados = $s->carregaAbaPessoaisFluxoByFuncao($search, $orderColumn, $orderDir, $offset, $limit, $_POST['grupos'], $_SESSION['empresaid']);

        // Traz todos os usuarios
        $total_all_records = $s->getCountTableAbaPessoais("z_sga_programas p", $search, array('DISTINCT zslc.name'), '', $_SESSION['empresaid'], $_POST['grupos']);

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
        $s = new SolicitacaoAcessoFuncao();


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
        $dados = $s->carregaAbaSensiveisFluxoByFuncao($search, $orderColumn, $orderDir, $offset, $limit, $_POST['grupos'], $_SESSION['empresaid']);

        // Traz todos os usuarios
        $total_all_records = $s->getCountTableAbaSensiveis("z_sga_programas p", $search, array('DISTINCT zslc.name'), '', $_SESSION['empresaid'], $_POST['grupos']);

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
        $s = new SolicitacaoAcessoFuncao();


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
        $dados = $s->carregaAbaAnonizadosFluxoByFuncao($search, $orderColumn, $orderDir, $offset, $limit, $_POST['grupos'], $_SESSION['empresaid']);

        // Traz todos os usuarios
        $total_all_records = $s->getCountTableAbaAnonizados("z_sga_programas p", $search, array('DISTINCT zslc.name'), '', $_SESSION['empresaid'], $_POST['grupos']);

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