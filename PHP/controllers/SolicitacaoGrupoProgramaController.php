<?php

class SolicitacaoGrupoProgramaController extends Controller
{
    public function __construct() {
        parent::__construct();
    }

    /**
     * Carrega a tela de fluxo de solicitação de programa em grupo
     */
    public function index()
    {
        $s = new SolicitacaoGrupoPrograma();
        $data = [];

        $solicUsuarios          = $s->carregaUsuariosSolicitante($_SESSION["idUsrTotvs"], $_SESSION["empresaid"]);
        $data['grupos']         = $s->carregaGrupos($_SESSION["empresaid"]);
        $data['solicitante']    = $solicUsuarios['solicitante'];
        $data['acompanhante']   = $s->usuariosAcompanhantes();
        $data['idFluxo']        = 7;

        $this->loadTemplate('solicitacao_grupo_programa_cadastro', $data);
    }

    /**
     * Cria a solicitação para cada usuário selecionado na tela de abertura de solicitação de acesso
     */
    public function criaSolicitacaoGrupoPrograma()
    {
        // Valida se foi selecionado ao menos um programa
        //$this->helper->debug($_POST, true);
        if(count($_POST['programa']) == 0):
            $this->helper->setAlert(
                'error',
                'Favor selecionar ao menos um programa',
                'SolicitacaoGrupoPrograma/'
            );
        endif;

        $s      = new SolicitacaoGrupoPrograma();
        $fluxo  = new Fluxo();
        $idEmpresa      = $_POST['idEmpresa'];
        $idFluxo        = $_POST['idFluxo'];
        $idGrupos       = $_POST['idGrupo'];
        $programas      = $_POST['programa'];
        $idSolicitante  = $_POST['idSolicitante'];

        setlocale(LC_TIME, 'pt_BR');
        date_default_timezone_set('America/Sao_Paulo');
        $dataMovimentacao = date('Y-m-d H:i:s');

        // Percorre os grupos e abre a solicitação
        foreach ($idGrupos as  $idGrupo):
            // Recupera os dados do grupo a abrir a solicitação
            // Se não encontrar retorna mensagem de erro
            $rsGrupos = $s->dadosGrupoSolicitacao($idGrupo, $idEmpresa);
            if($rsGrupos['return'] == false):
                $this->helper->setAlert(
                    'error',
                    $rsGrupos['error'],
                    'SolicitacaoGrupoPrograma/'
                );
            endif;
            $grupo = $rsGrupos['dados'];

            $gestorGrupo    = $grupo['gestor'];
            $idGestor       = "";
            $lista          = array();

            if(empty($gestorGrupo)):
                $gestorGrupo = "super";
            endif;

            // Retorna o gestor do grupo
            $rsGestor = $s->dadosGestorGrupo($gestorGrupo);
            $dadosGestor = '';

            if($rsGestor['return'] == true):
                $dadosGestor =  $rsGestor['dados'];
            else:
                $this->helper->setAlert(
                    'error',
                    $rsGestor['error'],
                    'SolicitacaoGrupoPrograma/'
                );
            endif;

            // Cria a solicitação e retorna o ID da mesma
            $solicitacao = $fluxo->cadastraNumSolicitacao($idFluxo, $idSolicitante, '', $dataMovimentacao);
            $idSolicitacao = $solicitacao['idSolic'];

            // Percorre os programas a serem adicionados ao grupo
            foreach ($programas as $key => $prog):
                // Busca se existe gestor de programas ou modulos para os programas existentes no grupo
                $rsGestorMRP = $s->dadosGestorMRP($prog['idProg'], $idEmpresa);

                $gestModul = array();
                $gestRot = array();
                $gestProg = array();

                if($rsGestorMRP['return'] == true):
                    $dadosGestorModulo = $rsGestorMRP['dados'];

                    $idGestMod  = [];
                    $idGestRot  = [];
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
                        elseif($val['codModul'] != '*' && $val['codRotina'] != '*' && $val['codProg'] != '*' && !in_array($val['idGestor'], $idGestProg) && in_array($val['codProg'], array_column($_POST['programa'], 'codProg'))):
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
                    "idLinhaProg"    => $key,
                    "idProg"         => $prog['idProg'],
                    "codProg"        => $prog['codProg'],
                    "descProg"       => $prog['descProg'],
                    "aprovacao"      => "",
                    "obs"            => "",
                    "manterStatus"   => 1,
                    'cartaRisco'    => '',
                    'gestorModulo'   => $gestModul,
                    'gestorRotina'   => $gestRot,
                    'gestorPrograma' => $gestProg
                );
                $lista[$key] = $dados;
            endforeach;

            // Prepara o JSON do documento
            $dadosDoc = array(
                "idGrupo"           => $idGrupo,
                "idLegGrupo"        => $grupo['idLegGrupo'],
                "descAbrev"         => $grupo['descAbrev'],
                "codGest"           => $grupo['gestor'],
                "idCodGest"         => $dadosGestor['idGestor'],
                "nomeGestor"        => $dadosGestor['nomeGestor'],
                'dataInicio'        => $dataMovimentacao,
                'idSolicitacao'     => $idSolicitacao,
                'idSolicitante'     =>  $idSolicitante,
                'idAcompanhante'    => (isset($_POST['idUsuarioAcompanhante']) && count($_POST['idUsuarioAcompanhante']) > 0 ? $_POST['idUsuarioAcompanhante'] : []),
                'tipoEnvioMailCopia' => $_POST['tipoEnvioMailCopia'],
                'aprovacao_si'      => '',
                'exigirCartaRisco' => '',
                'programas'         => $lista
            );

            // Cria o documento JSON
            $documento = json_encode($dadosDoc, true);

            // Grava o documento JSON na tabela de documentos
            $fluxo->criaDocumento($documento, $idFluxo, $idFluxo, $idSolicitacao);

            // Recupera o id da atividade de solicitacao
            $ativSolicitante = $s->buscaIdAtividadeSolicitante($idFluxo);
            //die($ativSolicitante);
            // Busca o id da próxima atividade.
            $idProximaAtiv = $fluxo->verificaProximaAtividade($ativSolicitante);
            $idProximaAtiv = $idProximaAtiv['proximaAtiv'];
            //die($idProximaAtiv);

            // Verifica se foi o próprio gestor de usuario quem abriu a solicitação se sim, envia direto para o próximo aprovador.
            //if($idSolicitante == $idGestor):
            //    $idProximaAtiv = $fluxo->verificaProximaAtividade($idProximaAtiv);
            //    $idProximaAtiv = $idProximaAtiv['proximaAtiv'];
            //endif;

            // Recupera nome de objeto instancia da proxima atividade
            $objProxAtividade = $s->buscaObjetoProxAtividade($idProximaAtiv);
            //die($objProxAtividade);
            // Cria array com parametros necessários para execução do método responsável pela criação da movimentação
            $paramsSolicitacao = array(
                //'idUsuario'         => $idUsuario,
                'idGestorGrupo'     => $dadosGestor['idGestor'],
                'idSolicitacao'     => $idSolicitacao,
                'idProximaAtiv'     => $idProximaAtiv,
                'idSolicitante'     => $idSolicitante,
                'idFluxo'           => $idFluxo,
                'lista'             => $lista,
                'usuariosCopia'     => $_POST['idUsuarioAcompanhante'],
                'tipoEnvioMailCopia' => $_POST['tipoEnvioMailCopia'],
                'tipoFluxo'          => 'Solicitação de Programa em Grupo',
                'dataMovimentacao'  => date('Y-m-d H:i:s'),
                "idGrupo"           => $idGrupo,
                "idLegGrupo"        => $grupo['idLegGrupo'],
                "descAbrev"         => $grupo['descAbrev'],
            );

            // Executa método responsável pela criação da movimentação
            if($objProxAtividade == 'criaAtividadeGestorModulo' || $objProxAtividade == 'criaAtividadeGestorRotina' || $objProxAtividade == 'criaAtividadeGestorPrograma'):
                $aprovador = lcfirst(str_replace('criaAtividade', '', $objProxAtividade));
                $this->criaAtividadeGestorMRP($_POST, $paramsSolicitacao, $aprovador);
            else:
                $this->$objProxAtividade($paramsSolicitacao);
            endif;

            // Insere usuários acompanhante na tabela z_sga_fluxo_agendamento_acesso_acompanhante
//            if(count($_POST['idUsuarioAcompanhante']) > 0 && $_POST['idUsuarioAcompanhante'][0] != ''):
//                foreach($_POST['idUsuarioAcompanhante'] as $val):
//                    $s->guardaUsuarioAcompanhante($idUsuario, $val, $idSolicitacao);
//
//                    // Envia email para usuários em cópia
//                    $paramsSolicitacao['idUsuarioCopia'] = $val;
//                    $this->enviaEmailCopia($paramsSolicitacao);
//                endforeach;
//            endif;



            // Limpa as variaveis json e array grupos
            unset($documento);
            unset($lista);
            unset($idGestores);
        endforeach;

        $this->helper->setAlert(
            'success',
            "Solicitação de código <strong style=\"color:#333;font-size;font-size: 14px;\">".$idSolicitacao."</strong> aberta com sucesso!",
            'SolicitacaoGrupoPrograma/'
        );
    }

    /**
     * Cria atividade para gestor de grupos
     * @param type $paramsSolicitacao
     */
    public function criaAtividadeGestorGrupo($paramsSolicitacao)
    {
        $fluxo = new Fluxo();

        // Se próxima atividade for aprovação de grupos. Cria uma atividade para cada gestor de grupo
        // Verifica se o gestor possui usuário alternativo
        $idGestorResponsavel = $fluxo->buscaUsrAlternativoFluxos($paramsSolicitacao['idGestorGrupo']);

        // Cria atividade
        $fluxo->cadastraMovimentacao($paramsSolicitacao['idSolicitacao'], $paramsSolicitacao['idProximaAtiv'], $paramsSolicitacao['dataMovimentacao'], $paramsSolicitacao['idSolicitante'], $idGestorResponsavel, $paramsSolicitacao['idFluxo'], '');

        // Envia email para os gestores
        $this->enviaEmail($idGestorResponsavel, $paramsSolicitacao);
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

        foreach($documento['programas'] as $progs):
            // Se o id do Gestor não estiver no array de gestores cria atividade para o mesmo.
            if($progs['manterStatus'] == 1 && isset($progs[$aprovador])):
                foreach($progs[$aprovador] as $gest):
                    if(is_array($gest) && !in_array($gest['id'], $idGestores)):
                        $idGestores[] = $gest['id'];
                        $idGestorResponsavel = $fluxo->buscaUsrAlternativoFluxos($gest['id']);

                        // Cadastra movimentação para o gestor
                        $fluxo->cadastraMovimentacao($params['idSolicitacao'], $params['idProximaAtiv'], $params['dataMovimentacao'], $post['idSolicitante'], $idGestorResponsavel,$post['idFluxo'],'');

                        // Envia email para o gestor responsável
                        $this->enviaEmail($idGestorResponsavel, $params);

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
    public function ajaxBuscaProgramas()
    {
        $s = new SolicitacaoGrupoPrograma();

        if (isset($_POST['string']) && !empty($_POST['string'])) {
            $progs = $s->buscaProgramas($_POST['string'], $_POST['idGrupo'], (isset($_POST['progsJaAdd']) ? $_POST['progsJaAdd'] : []));
            foreach ($progs as $value) {
                echo '<li onclick="carregaDadosProg(' . "'" . $value['cod_programa'] . ' - ' . $value["descricao_programa"] . "'" . "," . "'" . $value["idProg"] . "'" . ')">' . $value['cod_programa'] . ' - ' . $value['descricao_programa'] . '</li>';
            }
        }
    }

    /**
     * Envia o email dos fluxos
     * @param $idGestor
     * @param $documento
     * @param $mensagem
     */
    public function enviaEmail($idGestor, $documento, $mensagem = '')
    {
        $email = new Email();
        $assunto = "Solicitação de Acesso";
        $nomeRemetente = "SGA - Sistema de Gestão de Programa em Grupo";

        // Substitui mensagem padrão por mensagem recebida por parametro
        if(empty($mensagem)):
            // Cria html de email a ser enviado.
            $mensagem = '
                Olá, <b>{gestor}</b>.<br/><br/>

                <span style="font-size:14px;margin-top:20px">Informamos que a atividade abaixo, está sob sua responsabilidade e precisa de sua ação.</span>
                <br><br>
                <span style="font-size:14px;margin-top:20px"><b>Atividade:</b> Solicitação de Programas em Grupo do grupo <strong>'.$documento['idLegGrupo'] . ' - ' . $documento['descAbrev'] .'</strong>.</span>
                <br>
                <span style="font-size:14px;margin-top:20px">Acompanhe a solicitação em seu painel de atividades.</span><br>';

            $mensagem .= '<br/>
                <br/>
                <br/>				
                <a href="'.URL.'/Fluxo/centralDeTarefa" style="background: #4ca5a9;color: #ffffff; text-align: center; text-decoration: none;padding: 15px 30px; font-family: Arial, sans-serif; font-size: 16px">Ir para Central de Tarefas</a>';
        endif;


        // Envia email para o gestor responsável
        //$dadosEmailUsuario  = $email->cadadosUsuario($idUsuario);
        $dadosEmailGestor   = $email->cadadosUsuario($idGestor);

        $mensagem = str_replace('{gestor}', $dadosEmailGestor['nome_usuario'], $mensagem);
        //$mensagem = str_replace('{usuario}', $dadosEmailUsuario['nome_usuario'], $mensagem);

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
        $nomeRemetente = "SGA - Sistema de Gestão de Acesso por Função";

        $dadosEmail = $f->buscaDadosEmailCopia($paramsSolicitacao);

        if($dadosEmail != 0):
            $mensagem = '
                Olá, <b>'.$dadosEmail['usuario_copia'].'</b>.<br/><br/>

                <span style="font-size:14px;margin-top:20px">O solicitante <b>'.$dadosEmail['solicitante'].'</b> te colocou em cópia da seguinte atividade:</span>
                <br><br>
                <span style="font-size:14px;margin-top:20px"><b>Atividade:</b> '.$paramsSolicitacao['tipoFluxo'].' do grupo <strong>'.$paramsSolicitacao['idLegGrupo'] . ' - ' . $paramsSolicitacao['descAbrev'] .'</strong>.</span>
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
     * Adiciona grupo e programa à relação de programas adicionados
     */
    public function ajaxValidaGrupoPrograma()
    {
        // Valida se foi selecionado um usuario e um programa
        if(!isset($_POST['idPrograma']) || $_POST['idPrograma'] == '' || !isset($_POST['idGrupo']) || $_POST['idGrupo'] == ''):
            return '0';
        endif;

        $s = new SolicitacaoGrupoPrograma();

        // Valida se grupo já possui solitição aberta com mesmo programa
        // true = possui, false = não possui
        $validaSolicitacaoAberta = $s->validaSolicitacaoAberta($_POST);

        if($validaSolicitacaoAberta['return'] === false):
            echo json_encode(array(
                'return' => 'duplicidade',
                'solicitacao' => $validaSolicitacaoAberta['solicitacao']
            ));
            die('');
        endif;


        // Valida se o grupo já possui algum programa igual ao programas solicitado
        $validaGrupoProg = $s->validaGrupoPrograma($_POST);
        if($validaGrupoProg['return']):
            echo json_encode(array(
                'return' => false,
                'grupos' => $validaGrupoProg['grupos']
            ));
            die('');
        else:
            echo json_encode(array(
                'return' => true
            ));
            die('');
        endif;
    }

    /**
     * Adiciona usuários e programa à relação de programas adicionados
     */
    public function ajaxCarregaUsuariosProgramasAdd()
    {
        // Valida se foi selecionado um usuario e um programa
        if(!isset($_POST['idPrograma']) || $_POST['idPrograma'] == '' || !isset($_POST['idGrupo']) || $_POST['idGrupo'] == ''):
            return '0';
        endif;

        $s = new SolicitacaoGrupoPrograma();

        // Busca grupos pelo id do programa selecionado
        $grupos = $s->carregaUsuariosProgramas($_POST['idPrograma'], $_POST['idGrupo']);

        $html = '';
        foreach ($grupos as $val):
            $html .= '    <tr valign="top">';
            $html .= '        <td><div style="max-height:79px;overflow-x:auto">'.$val['programas'].'</div></td>';
            //$html .= '        <td><input type="hidden" name="idGrupo[]" class="idGrupo" value="'.$val['idGrupo'].'">'.$val['idLegGrupo'].'</td>';
            $html .= '        <td>'.$val['descAbrev'].'</td>';
            //$html .= '        <td>'.$val['usuarios'].'</td>';
            $html .= '        <td><center>'.$val['nrProgramas'].'</center></td>';
            $html .= '        <td><center>'.$val['nrUsuarios'].'</center></td>';
            $html .= '        <td><center><input type="checkbox" name="manterStatus[]" class="manterStatus" onClick="carregaRiscosGrupos();"></center></td>';
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

        $s = new SolicitacaoGrupoPrograma();
        $grupos = $s->carregaGrupoProgramasJaAdicionados($_POST['idGrupo'],/*$_POST['idPrograma'],*/ $_POST['idUsuario']);

        $html = '';
        foreach ($grupos as $val):
            $html .= '    <tr valign="top">';
            $html .= '        <td>'.$val['programas'].'</td>';
            $html .= '        <td><input type="hidden" class="idGrupoJaAdicionados" value="'.$val['idGrupo'].'">'.$val['idLegGrupo'].'</td>';
            $html .= '        <td>'.$val['descAbrev'].'</td>';
            $html .= '        <td><center>'.$val['nrProgramas'].'</center></td>';
            $html .= '        <td><center>'.$val['nrUsuarios'].'</center></td>';
            $html .= '    </tr>';
        endforeach;

        echo $html;

    }

    public function ajaxBuscaUsuariosGrupo()
    {
        if(!isset($_POST['idGrupo']) || empty($_POST['idGrupo'])):
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
        $s = new SolicitacaoGrupoPrograma();

        // usado pelo order by
        $fields = array(
            0   => 'u.nome_usuario',
            1   => 'u.cod_usuario'
        );

        // Variaveis usadas na paginação do Jquery DataTable
        $search = (isset($_POST["search"]["value"])) ? $_POST["search"]["value"] : '';
        $orderColumn = (isset($_POST['order']['0']['column'])) ? $fields[$_POST['order']['0']['column']] : '';
        $orderDir = (isset($_POST['order']['0']['dir'])) ? $_POST['order']['0']['dir'] : 'ASC';
        $limit = (isset($_POST["length"]) && $_POST["length"] != -1) ? $_POST['length'] : '10';
        $offset = (isset($_POST["length"]) && $_POST["length"] != -1) ? $_POST['start'] : '0';


        // Traz apenas 10 usuarios utilizando paginação
        $dados = $s->carregaAbaUsuarios($search, $orderColumn, $orderDir, $offset, $limit, $_POST['idGrupo'], $_SESSION['empresaid']);

        // Traz todos os usuarios
        $total_all_records = $s->getCountTableAbaUsuarios("z_sga_grupos gs", $search, array('DISTINCT u.z_sga_usuarios_id'), '', $_SESSION['empresaid'], $_POST['idGrupo']);

        // Cria linhas customizadas no jquery datatable.
        if (count($dados) > 0):
            foreach ($dados as $key => $value):
                $sub_dados = array();
                $sub_dados[] = $value["nome_usuario"];
                $sub_dados[] = $value["gestor"];
                $sub_dados[] = $value["descDepartamento"];
                $sub_dados[] = ($value["ativo"] == 1  ? '<center><span class="badge label-primary">Ativo</span></center>' : '<center><span class="badge label-primary">Inativo</span></center>');
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
     * Carrega matriz de risco por ids de grupos
     */
    public function ajaxMatrizDeRisco()
    {
        $dados = array();
        $s = new SolicitacaoGrupoPrograma();
        $dados['conflitos'] = $s->fluxoMatrizRisco($_POST['grupos']);
        $totalRiscos = $s->fluxoMatrizCountRisco($_POST['grupos']);
        $dados['totalProgByGrupo'] = $s->getCountTableAbaProgs("z_sga_programas p", '', array(0 => 'DISTINCT p.z_sga_programas_id'), '', $_SESSION['empresaid'], $_POST['grupos']);
        $dados['totalUsuariosByGrupo'] = $s->getCountTableAbaUsuarios("z_sga_grupos gs", '', array('DISTINCT u.z_sga_usuarios_id'), '', $_SESSION['empresaid'], $_POST['grupos']);
        $dados['totalCamposPessoais'] = $s->getCountTableAbaPessoais("z_sga_programas p", '', array(0 => 'zslc.name'), '', $_SESSION['empresaid'], $_POST['grupos']);
        $dados['totalCamposSensiveis'] = $s->getCountTableAbaSensiveis("z_sga_programas p", '', array(0 => 'zslc.name'), '', $_SESSION['empresaid'], $_POST['grupos']);
        $dados['totalCamposAnonizados'] = $s->getCountTableAbaAnonizados("z_sga_programas p", '', array(0 => 'zslc.name'), '', $_SESSION['empresaid'], $_POST['grupos']);

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
            'totalUsuariosByGrupo' => $dados['totalUsuariosByGrupo'],
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
        if(!isset($_POST['grupos']) || empty($_POST['grupos'])):
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
        $s = new SolicitacaoGrupoPrograma();


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
        $total_all_records = $s->getCountTableAbaProgs("z_sga_programas p", $search, $fields, '', $_SESSION['empresaid'], $_POST['grupos']);

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

    public function ajaxCarregaAbaDadosPessoais()
    {
        if(!isset($_POST['grupos']) || empty($_POST['grupos'])):
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
        $s = new SolicitacaoGrupoPrograma();


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
        $dados = $s->carregaAbaPessoaisFluxoByGrupo($search, $orderColumn, $orderDir, $offset, $limit, $_POST['grupos'], $_SESSION['empresaid']);

        // Traz todos os usuarios
        $total_all_records = $s->getCountTableAbaPessoais("z_sga_programas p", $search, $fields, '', $_SESSION['empresaid'], $_POST['grupos']);
        
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


    public function ajaxCarregaAbaDadosSensiveis()
    {
        if(!isset($_POST['grupos']) || empty($_POST['grupos'])):
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
        $s = new SolicitacaoGrupoPrograma();


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
        $dados = $s->carregaAbaSensiveisFluxoByGrupo($search, $orderColumn, $orderDir, $offset, $limit, $_POST['grupos'], $_SESSION['empresaid']);

        // Traz todos os usuarios
        $total_all_records = $s->getCountTableAbaSensiveis("z_sga_programas p", $search, $fields, '', $_SESSION['empresaid'], $_POST['grupos']);

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

    public function ajaxCarregaAbaDadosAnonizados()
    {
        if(!isset($_POST['grupos']) || empty($_POST['grupos'])):
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
        $s = new SolicitacaoGrupoPrograma();


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
        $dados = $s->carregaAbaAnonizadosFluxoByGrupo($search, $orderColumn, $orderDir, $offset, $limit, $_POST['grupos'], $_SESSION['empresaid']);

        // Traz todos os usuarios
        $total_all_records = $s->getCountTableAbaAnonizados("z_sga_programas p", $search, $fields, '', $_SESSION['empresaid'], $_POST['grupos']);

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