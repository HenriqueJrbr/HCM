<?php

class ManutencaoController extends Controller
{

    public function __construct()
    {

        parent::__construct();

        $login = new Login();

        if(!$login->isLogin()){
              header('Location: '.URL.'/Login');   
        }else{
            if($login->validaTrocaSenha() == true){
                header('Location: '.URL.'/Login/trocaSenha');
            }
        }
    }

    public function index()
    {
        $dados = array();

        if (isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa'])) {
            $empresa = new Home();
            $empresaId = addslashes($_POST['empresa']);

            $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
            $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
            $_SESSION['empresaid'] = $empresaId;


            header('Location: ' . URL);
        }
        //$usuario  = new Usuario();        
        
        //$dados['usuarios']  = $usuario->carregaUsuariosAtivos(1);        

        $this->loadTemplate('cadastro_funcao', $dados);
    }

    public function Grupo()
    {
        $dados = array();

        if (isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa'])){
            $empresa = new Home();
            $empresaId = addslashes($_POST['empresa']);
            $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
            $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
            $_SESSION['empresaid'] = $empresaId;
            header('Location: ' . URL);
        }

        $g = new Grupo();
        $dados['listaGrupo'] = $g->carregaGrupo($_SESSION['empresaid']);

        $this->loadTemplate('manutencao_grupo', $dados);
    }

    /**
     * Edita programas e usuarios para os grupos
     */
    public function manutencao_grupo_edita($idGrupo)
    {
        $dados = array();

        $m = new Manutencao();
        $usuario = new Usuario();
        $programa = new Programa();

        $dados['dadosGrupoProd'] = $m->carregaDadosGrupoProg($idGrupo);
        $dados['carregaUsuario'] = $m->carregaDadosGrupo($idGrupo);
        $dados['totalUser'] = $m->totalUserManutencao($idGrupo);
        $dados['gestorGrupo']    = $m->carregaGestorGrupo($idGrupo);
        $dados['grupo']          = $m->carregaDescGrupo($idGrupo);
        $dados['usuarios']       = $usuario->carregaUsuarios(1, '' ,'', $idGrupo);
        //$dados['programas']      = $programa->carregaProgramasGrupoEdita('', '', $idGrupo);
        //$dados['usuariosAdicionados']  = $usuario->carregaUsuariosAdicionados($idGrupo);
        //$dados['programasAdicionados'] = $programa->carregaProgramasAdicionadosGrupoEdita($idGrupo);
        $dados['idGrupo']        = $idGrupo;

        //echo "<pre>";
        //print_r($dados['dadosGrupoProd'])."<br>";
        //print_r($dados['carregaUsuario'])."<br>";
        //print_r($dados['gestorGrupo'])."<br>";
        //print_r($dados['grupo'])."<br>";
        //die;
        if(empty($dados['gestorGrupo'])){
            $dados['gestorGrupo'] = "1";
        }

        $this->loadTemplate('manutencao_grupo_edita', $dados);
    }
    
    /**
     * Retorna os grupos pelo id da instancia selecionada
     * @return type
     */
    public function ajaxBuscaUsuarios()
    {   
        // Valida se foi selecionado uma instancia e uma função
        if(!isset($_POST['nome']) || $_POST['nome'] == ''):
            echo '';
        endif;
        
        $idsAdicionados = (isset($_POST['eliminar'])) ? implode(',', $_POST['eliminar']) : '';
        
        $usuario = new Usuario();        
        $usuarios = $usuario->carregaUsuarios(1, $_POST['nome'], $idsAdicionados, $_POST['idGrupo']);
        
        $optUsuarios = "<option></option>";
        
        foreach($usuarios as $val):
            $optUsuarios .= '<option value="'.$val['idUsuario'].'|'.$val['cod_usuario'].'">'.$val['nome_usuario'].'</option>';
        endforeach;
        
        echo $optUsuarios;
    }


    public function funcao()
    {

        if (isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa'])) {
            $empresa = new Home();
            $empresaId = addslashes($_POST['empresa']);

            $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
            $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
            $_SESSION['empresaid'] = $empresaId;


            header('Location: ' . URL);
        }
        $dados = array();
        $m = new Manutencao();


        if (isset($_POST['salvar']) && $_POST['salvar'] == "Salvar" && isset($_POST['funcao']) && !empty($_POST['funcao'])) {
            $funcao = $_POST['funcao'];
            $descricao = $_POST['descricao'];
            $retorno = $m->cadastrarFuncao($funcao, $descricao);
            if($retorno > 0){
                $_SESSION['msg']['success'] = 'Fução cadsatrada com sucesso';
                unset($_POST['salvar']);
            }else{
                $_SESSION['msg']['error'] = 'Erro ao cadastrar função';
            }
           
        }

        if (isset($_POST['modaleditsalvar']) && $_POST['modaleditsalvar'] == "Salvar" && isset($_POST['modalfuncao']) && !empty($_POST['modalfuncao'])) {

            $funcao = $_POST['modalfuncao'];
            $descricao = $_POST['modaldescricao'];
            $id = $_POST['modalid'];

            $m->alterarCadastro($id, $funcao, $descricao);
            header('Location: ' . URL . "/Manutencao/funcao");
        }

        $dados['funcao'] = $m->carregaFuncao();

        $this->loadTemplate('manutencao_funcao', $dados);
    }

    public function excluiFuncao($id)
    {

        $dados = array();
        $m = new Manutencao();
        $m->excluirFuncao($id);

        header('Location: ' .URL."/Manutencao/funcao");

    }

    public function Usuario()
    {

        if (isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa'])) {
            $empresa = new Home();
            $empresaId = addslashes($_POST['empresa']);

            $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
            $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
            $_SESSION['empresaid'] = $empresaId;


            header('Location: ' . URL);
        }
        $dados = array();
        $m = new Manutencao();
        $dados['usuario'] = $m->carregaUsuario();

        $this->loadTemplate('manutencao_usuario', $dados);
    }

    public function editausuario($id)
    {
        if (isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa'])) {
            $empresa = new Home();
            $empresaId = addslashes($_POST['empresa']);

            $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
            $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
            $_SESSION['empresaid'] = $empresaId;


            header('Location: ' . URL);
        }

        $dados = array();
        $m = new Manutencao();
        $dados['editausuario'] = $m->carregaUsuarioedi($id);

        if ($dados['editausuario'][0]['gestor_usuario'] == 'S'):
            $dados['usuarios'] = $m->usuariosGestor($id);
        endif;

        if ($dados['editausuario'][0]['gestor_grupo'] == 'S'):
            $dados['grupos'] = $m->gruposGestor($id);
        endif;

        if($dados['editausuario'][0]['gestor_programa'] == 'S'):
            $dados['mpr']   = $m->mprGestor($id);            
        endif;

        // Valida se usuário possui fluxo revisão em aberto
        $dados['possuiRevisao'] = $m->usuarioPossuiRevisao($id);
        
        $this->loadTemplate('manutencao_usuario_edi', $dados);
    }

    /**
     * Cria novo grupo na base
     */
    public function addGrupo()
    {
        $post = $_POST;
        $nameGrupo = addslashes($post['nameGrupo']);
        $descGrupo = addslashes($post['descGrupo']);
        $idLegGrupoClone = $post['idLegGrupoClone'];
        $htmlErro = '';
        $htmlSucesso = '';
        $result = '';

        // Valida se os campos foram preenchidos
        if(empty($nameGrupo) || empty($descGrupo)):
            $_SESSION['msg']['error'] = 'Favor preencher todos os campos';
            header('Location:' .URL. '/Manutencao/grupo');
            die;
        endif;

        $m = new Manutencao();
        $fluxo = new Fluxo();

        // Integra caso esteja parametrizado
        // Valida se é para integrar com datasul
        $empresa = new Empresa();
        $dadosEmpresa = $empresa->carregaEmpresa($_SESSION['empresaid']);
        $jsonApi = json_decode($dadosEmpresa[0]['integration_data']);

        //$this->helper->debug($_POST, true);

        if(isset($jsonApi->execBO->integra) && $jsonApi->execBO->integra == "true"):
            $api = new ExecBO();
            $dataUserExecBO = [
                'id_leg_grupo' => $nameGrupo,
                'desc_abrev' => $descGrupo,
                'acao' => 'INC',
                'copyUsr' => (isset($post['deseja_clonar_usuario'])  && $post['deseja_clonar_usuario'] == "on") ? $idLegGrupoClone : '',
                'copyProgs' => (isset($post['deseja_clonar_programa'])  && $post['deseja_clonar_programa'] == "on") ? $idLegGrupoClone : ''
            ];

            // Consome WEBSERVICE
            $retorno = $api->execboGrupo('INC', $dataUserExecBO);

            // Relaciona os usuarios e grava log
            if(isset($retorno['success']) && count($retorno['success']) > 0):
                //$result = $manutencao->addGrupoExecbo($idGrupo, $retorno['success']);
                $result = $m->addGrupo($nameGrupo, $descGrupo);

                if(isset($post['deseja_clonar_Grupo']) && $post['deseja_clonar_Grupo'] == "on"){
                    if(isset($post['deseja_clonar_programa'])  && $post['deseja_clonar_programa'] == "on"){
                        $m->clonaPrograma($result['return'],$post['grupoClone']);
                    }
                    if(isset($post['deseja_clonar_usuario'])  && $post['deseja_clonar_usuario'] == "on"){
                        $m->clonaUsuario($result['return'],$post['grupoClone']);
                    }
                }

                // Valida se o retorno foi ok e grava o log
                if($result['return']):
                    //foreach($usuarios as $key => $val):
                        //if(in_array($val['cod_usuario'], $retorno['success'])):
                            $fluxo->gravaLogAuditoria(
                                1,
                                date('Y-m-d H:i:s'),
                                date('Y-m-d H:i:s'),
                                '',
                                '',
                                'ADICIONADO',
                                $nameGrupo .' - '. $descGrupo,
                                '',
                                $_SESSION['nomeUsuario'],
                                'm',
                                0
                            );
                        //endif;
                    //endforeach;
                endif;

                foreach($retorno['success'] as $val):
                    foreach($val as $key => $info):
                        $htmlSucesso .= "<strong>". $key . "</strong>: " . $info ."<br>";
                    endforeach;
                endforeach;
            endif;

            // Cria o html de erros se houver
            if(isset($retorno['error']) && count($retorno['error']) > 0):
                // Cria mensagem de erros, se houver
                $htmlErro .= '<p>';
                if(is_string($retorno['error']) && !empty($retorno['error'])):
                    $htmlErro .= $retorno['error'] ."<br>";
                else:
                    foreach($retorno['error'] as $val):
                        foreach($val as $key => $info):
                            $htmlErro .= "<strong>". $key . "</strong>: " . $info ."<br>";
                        endforeach;
                    endforeach;
                endif;

                $htmlErro .= '</p>';
            endif;
        else:
            $result = $m->addGrupo($nameGrupo,$descGrupo);

            if($result['return'] > 0):
                 if(isset($post['deseja_clonar_Grupo']) && $post['deseja_clonar_Grupo'] == "on"){
                    if(isset($post['deseja_clonar_programa'])  && $post['deseja_clonar_programa'] == "on"){
                        $m->clonaPrograma($result['return'],$post['grupoClone']);
                    }
                    if(isset($post['deseja_clonar_usuario'])  && $post['deseja_clonar_usuario'] == "on"){
                        $m->clonaUsuario($result['return'],$post['grupoClone']);
                    }
                }

                $fluxo->gravaLogAuditoria(
                    1,
                    date('Y-m-d H:i:s'),
                    date('Y-m-d H:i:s'),
                    '',
                    '',
                    'ADICIONADO',
                    $nameGrupo .' - '. $descGrupo,
                    '',
                    $_SESSION['nomeUsuario'],
                    'm',
                    0
                );
            endif;
        endif;
        //echo $htmlErro;
        //print_r($result);
//die;
        if($htmlErro == ''):
            $_SESSION['msg']['success'] = 'Grupo criado com sucesso'."<br>".$htmlSucesso;
            header('Location:' .URL. '/Manutencao/manutencao_grupo_edita/'.$result['return']);
        else:
            $_SESSION['msg']['error'] = "Erro ao criar novo grupo\n".$htmlErro;
            header('Location:' .URL. '/Manutencao/grupo');
        endif;
    }
    
    
    
    /**
     * Atribui usuario para um grupo
     */
    public function addUsuarioGrupo()
    {
        //$this->helper->debug($_POST, true);
        //global $execBo;
        $post = $_POST;
        $usuarios = $post['usuarios'];
        $idGrupo = addslashes($post['idGrupo']);
        $codGrupo = addslashes($post['codGrupo']);
        $descAbrev = addslashes($post['descAbrev']);
        $cod_usuario = $post['cod_usuario'];
        $fluxo = new Fluxo();
        $manutencao = new Manutencao();
        $htmlErro = '';
        
        // Valida se os campos foram preenchidos
        if(empty($usuarios) || empty($idGrupo) || empty($codGrupo)):
            $_SESSION['msg']['error'] = 'Favor preencher todos os campos';
            header('Location:' .URL. '/Manutencao/manutencao_grupo_edita/'.$idGrupo);
            die;
        endif;                
        
        // Valida se existe alguma solicitação ou revisão em aberto para o grupo
        $res = $manutencao->existeSolicitacaoAbertaGrupo($idGrupo);
        //$this->helper->debug($res, true);
        if($res['return'] && !empty($res['dados'])):
            $_SESSION['msg']['error'] = "Existe solicitações em aberto para o grupo.\n Solicitações em aberto: ".$res['dados'];
            header('Location:' .URL. '/Manutencao/manutencao_grupo_edita/'.$idGrupo);
            die();
        endif;	
        
        // Valida se é para integrar com datasul
        $empresa = new Empresa();
        $dadosEmpresa = $empresa->carregaEmpresa($_SESSION['empresaid']);
        $jsonApi = json_decode($dadosEmpresa[0]['integration_data']);
        
        if(isset($jsonApi->execBO->integra) && $jsonApi->execBO->integra == "true"):
            $api = new ExecBO();            
            $dataUserExecBO = $usuarios;

            // Consome WEBSERVICE
            $retorno = $api->execboGrupoUsuario('INC', $dataUserExecBO);
            
            // Relaciona os usuarios e grava log
            if(isset($retorno['success']) && count($retorno['success']) > 0):
                $result = $manutencao->addUsuarioGrupoExecbo($idGrupo, $retorno['success']);
            
                // Valida se o retorno foi ok e grava o log
                if($result['return']):                    
                    foreach($usuarios as $key => $val):                       
                        if(in_array($val['cod_usuario'], $retorno['success'])):
                            $fluxo->gravaLogAuditoria(
                                1,
                                date('Y-m-d H:i:s'),
                                date('Y-m-d H:i:s'),
                                $cod_usuario,
                                $val['cod_usuario'],
                                'ADICIONADO',
                                $codGrupo .' - '. $descAbrev,
                                '',
                                $_SESSION['nomeUsuario'],
                                'm',
                                0
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
                    foreach($retorno['error'] as $key => $val):
                        $htmlErro .= "<strong>". $key . "</strong>: " . $val ."<br>";
                    endforeach;
                endif;
                               
                $htmlErro .= '</p>';
            endif;
        else:
            $userIds = [];
            foreach($usuarios as $val):
                array_push($userIds, $val['cod_usuario']);
            endforeach;            
            $result = $manutencao->addUsuarioGrupoExecbo($idGrupo, $userIds);
            
            if($result['return']):
                foreach($usuarios as $key => $val):                                           
                    $fluxo->gravaLogAuditoria(
                        1,
                        date('Y-m-d H:i:s'),
                        date('Y-m-d H:i:s'),
                        $cod_usuario,
                        $val['cod_usuario'],
                        'ADICIONADO',
                        $codGrupo .' - '. $descAbrev,
                        '',
                        $_SESSION['nomeUsuario'],
                        'm',
                        0
                    );                    
                endforeach;
            endif;            
        endif;
               
        if(empty($htmlErro)):
            $_SESSION['msg']['success'] = 'Usuários(s) adicionados(s) com sucesso';
            header('Location:' .URL. '/Manutencao/manutencao_grupo_edita/'.$idGrupo);
            die();
        else:
            $_SESSION['msg']['error'] = "Erro ao adicionar usuários(s)\n".$htmlErro;
            header('Location:' .URL. '/Manutencao/manutencao_grupo_edita/'.$idGrupo);
            die();
        endif;
    }

    /**
     * Atribui programa para um grupo
     */
    public function addProgramaGrupo()
    {        
        set_time_limit(0);
        //$this->helper->debug($_POST['programas'], true);
        
        //global $execBo;
        $post = $_POST;
        $programas = $post['programas'];
        $idGrupo = addslashes($post['idGrupo']);
        $descAbrev = addslashes($post['descAbrev']);
        $codGrupo = addslashes($post['idLegGrupo']);
        $cod_usuario = addslashes($post['cod_usuario']);
        $htmlErro = '';
        
        // Valida se os campos foram preenchidos
        if(empty($programas) || empty($idGrupo) || empty($codGrupo)):
            $_SESSION['msg']['error'] = 'Favor preencher todos os campos';
            header('Location:' .URL. '/Manutencao/manutencao_grupo_edita/'.$idGrupo);
            die;
        endif;
        
        $manutencao = new Manutencao();
        $fluxo = new Fluxo(); 
        
        // Valida se existe alguma solicitação ou revisão em aberto para o grupo
        $res = $manutencao->existeSolicitacaoAbertaGrupo($idGrupo);
        //$this->helper->debug($res, true); 
        if($res['return'] && !empty($res['dados'])):
            $_SESSION['msg']['error'] = "Existe solicitações em aberto para o grupo.\n Solicitações em aberto: ".$res['dados'];
            header('Location:' .URL. '/Manutencao/manutencao_grupo_edita/'.$idGrupo);
            die();
        endif;	                
        
        // Valida se é para integrar com datasul
        $empresa = new Empresa();
        $dadosEmpresa = $empresa->carregaEmpresa($_SESSION['empresaid']);
        $jsonApi = json_decode($dadosEmpresa[0]['integration_data']);
        if(isset($jsonApi->execBO->integra) && $jsonApi->execBO->integra == "true"):
            $api = new ExecBO();
            $programa = "esp/essga005b.p";
            $procedure = "piGrupoPrograma";
            $dataProgExecBO = $programas;

            // Consome WEBSERVICE
            $retorno = $api->execboGrupoPrograma('INC', $dataProgExecBO);
            
            // Relaciona os programas e grava log
            if(isset($retorno['success']) && count($retorno['success']) > 0):
                $result = $manutencao->addProgramaGrupo($idGrupo, $retorno['success']);
            
                // Valida se o retorno foi ok e grava o log
                if($result['return']):
                    foreach($programas as $key => $val):
                        if(in_array($val['cod_prog_dtsul'], $retorno['success'])):
                            $fluxo->gravaLogAuditoria(
                                1,
                                date('Y-m-d H:i:s'),
                                date('Y-m-d H:i:s'),
                                $cod_usuario,
                                '',
                                'ADICIONADO',
                                $codGrupo .' - '. $descAbrev,
                                $val['cod_prog_dtsul'],
                                $_SESSION['nomeUsuario'],
                                'm',
                                0
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
                    foreach($retorno['error'] as $key => $val):
                        $htmlErro .= "<strong>". $key . "</strong>: " . $val ."<br>";
                    endforeach;
                endif;
                               
                $htmlErro .= '</p>';
            endif;
        else:

            $progIds = [];
            foreach($programas as $val):
                array_push($progIds, $val['cod_prog_dtsul']);
            endforeach;            
            $result = $manutencao->addProgramaGrupo($idGrupo, $progIds);

            if($result['return']):
                foreach($programas as $key => $val):                                           
                    $return = $fluxo->gravaLogAuditoria(
                        1,
                        date('Y-m-d H:i:s'),
                        date('Y-m-d H:i:s'),
                        $cod_usuario,
                        '',
                        'ADICIONADO',
                        $codGrupo .' - '. $descAbrev,
                        $val['cod_prog_dtsul'],
                        $_SESSION['nomeUsuario'],
                        'm',
                        0
                    );
                //$this->helper->debug($return);
                endforeach;
            endif;            
        endif;

        if(empty($htmlErro)):
            $_SESSION['msg']['success'] = 'Programa(s) atribuído(s) com sucesso';
            header('Location:' .URL. '/Manutencao/manutencao_grupo_edita/'.$idGrupo);
            die();
        else:
            $_SESSION['msg']['error'] = "Erro ao atribuir programa(s)\n".$htmlErro;
            header('Location:' .URL. '/Manutencao/manutencao_grupo_edita/'.$idGrupo);
            die();
        endif;               						        		       
    }
    
    /**
     * Valida se determinado grupo já existe
     */
    public function validaGrupoExistente()
    {
        $post = $_POST;
        $nameGrupo = addslashes($post['nameGrupo']);
        $descGrupo = addslashes($post['descGrupo']);

        // Valida se os campos foram preenchidos
        if(empty($nameGrupo) || empty($descGrupo)):
            echo json_encode(array(
                'return' => 'error',
                'msg'    => 'Favor preencher todos campos'
            ));
            die;
        endif;

        $m = new Manutencao();
        echo json_encode($m->validaGrupoExistente($nameGrupo, $descGrupo));

    }

    /**
     * Atualiza registros de usuários caso tenha ID. Pode ser usado para insert
     */
    public function salvarUsuario()
    {
		//$this->helper->debug($_POST);
        if (isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa'])) {
            $empresa = new Home();
            $empresaId = addslashes($_POST['empresa']);

            $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
            $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
            $_SESSION['empresaid'] = $empresaId;

            header('Location: ' . URL);
        }

        // Valida se existe id e se é maior que 0. Caso sim atualiza programa.
        // Para insert, basta adicionar ELSE e utilizar um método de insert na model
        if (isset($_POST['idusu']) && $_POST['idusu'] != '' && $_POST['idusu'] != 0):
            $m = new Manutencao();

            $data = array(
                'idusu'     => $_POST['idusu'],
                'codusu'    => $_POST['codusu'],
                'nomeusu'   => $_POST['nomeusu'],
                'funcaousu' => $_POST['funcaousu'],
                'emailusu'  => $_POST['emailusu'],
                'gestorusu' => $_POST['gestorusu'],
                'gestorgrupo' => $_POST['gestorgrupo'],
                'gestorprograma' => $_POST['gestorprograma']
            );

            $result = $m->salvarUsuario($data);
            if ($result['return']):
                // Se salvou com sucesso valido se gestor usuario é igual S.
                // Se sim adiciono os usuários
                if($_POST['gestorusu'] == 'S' && (isset($_POST['usuarios']) && count($_POST['usuarios']) > 0)):
                    $data['usuarios'] = $_POST['usuarios'];
                    $result = $m->insereUsuarioGestor($data);

                    if($result['return'] == 'false'):
                        $_SESSION['msg']['error'] = "Erro ao inserir usuários!<br>{$result['error']}";
                        header('Location: ' . URL . "/Manutencao/editausuario/" . $_POST['idusu']);
                        die;
                    endif;
                elseif($_POST['gestorusu'] == ''):
                    $result = $m->apagaUsuarioGestor($_POST['idusu'], '');

                    if($result['return'] == 'false'):
                        $_SESSION['msg']['error'] = "Erro ao apagar usuários!<br>{$result['error']}";
                        header('Location: ' . URL . "/Manutencao/editausuario/" . $_POST['idusu']);
                    endif;
                endif;

                // Se salvou usuarios com sucesso valido se gestor grupo é igual S.
                // Se sim adiciono os grupos
                if($_POST['gestorgrupo'] == 'S' && (isset($_POST['grupos']) && count($_POST['grupos']) > 0)):
                    $data['grupos'] = $_POST['grupos'];
                    $result = $m->insereGrupoGestor($data);

                    if($result['return'] == false):
                        $_SESSION['msg']['error'] = "Erro ao inserir grupos!<br>{$result['error']}";
                        header('Location: ' . URL . "/Manutencao/editausuario/" . $_POST['idusu']);
                    endif;
                elseif($_POST['gestorgrupo'] == ''):
                    $result = $m->apagaGrupoGestor($_POST['idusu'], '', $_POST['codusu']);

                    if($result['return'] == false):
                        $_SESSION['msg']['error'] = "Erro ao apagar grupos!<br>{$result['error']}";
                        header('Location: ' . URL . "/Manutencao/editausuario/" . $_POST['idusu']);
                        die;
                    endif;
                endif;

                // Valido se gestor de módulo é igual S.
                // Se sim adiciono os módulos
                if($_POST['gestorprograma'] == 'S' && (isset($_POST['modulos']) && count($_POST['modulos']) > 0)):                        
                    $data['modulos'] = $_POST['modulos'];
                    $result = $m->insereModuloGestor($data);

                    if($result['return'] == false):
                        $_SESSION['msg']['error'] = "Erro ao inserir módulos!<br>{$result['error']}";
                        header('Location: ' . URL . "/Manutencao/editausuario/" . $_POST['idusu']);
                    endif;
                elseif($_POST['gestorprograma'] == ''):                    
                    $result = $m->apagaMPRGestor('', $_POST['idusu']);

                    if($result['return'] == false):
                        $_SESSION['msg']['error'] = "Erro ao apagar Módulos!<br>{$result['error']}";
                        header('Location: ' . URL . "/Manutencao/editausuario/" . $_POST['idusu']);
                        die;
                    endif;
                endif;
				
                $_SESSION['msg']['success'] = "Usuário <strong>{$_POST['nomeusu']}</strong> atualizado com sucesso!";
                header('Location: ' . URL . "/Manutencao/usuario");
            else:
                $_SESSION['msg']['error'] = "Erro ao atualizar usuário!<br>{$result['error']}";
                header('Location: ' . URL . "/Manutencao/editausuario/" . $_POST['idusu']);
            endif;
        endif;
    }

    /**
     * Apaga o gestor dos usuarios selecionados
     */
    public function apagaUsuariosSelecionados(){
        // valida se foi selecionado ao menos um usuário
        if(count($_POST['usuarios']) == 0):
            $_SESSION['msg']['error'] = 'Selecione um ou mais usuários por favor.';
            header('Location: ' . URL . "/Manutencao/editausuario/".$_POST['idGestor']);
            die;
        endif;

        $post = $_POST;
        $m = new Manutencao();

        $result = $m->apagaUsuarioGestor($post['idGestor'], $post['usuarios']);

        if($result['return'] == 'true'):
            $_SESSION['msg']['success'] = "Usuários apagados com sucesso!";
            header('Location: ' . URL . "/Manutencao/editausuario/".$post['idGestor']);
        else:
            $_SESSION['msg']['error'] = "Erro ao apagar usuários!\n ". $result['error'];
            header('Location: ' . URL . "/Manutencao/editausuario/".$post['idGestor']);
        endif;
    }

    /**
     * Apaga o gestor dos grupos selecionados
     */
    public function apagaGruposSelecionados(){
        // valida se foi selecionado ao menos um grupo
        if(count($_POST['grupos']) == 0):
            $_SESSION['msg']['error'] = 'Selecione um ou mais grupos por favor.';
            header('Location: ' . URL . "/Manutencao/editausuario/".$_POST['idGestor']);
            die;
        endif;

        $post = $_POST;
        $m = new Manutencao();

        $result = $m->apagaGrupoGestor($post['idGestor'], $post['grupos'], $post['codUsuario']);

        if($result['return'] == 'true'):
            $_SESSION['msg']['success'] = "Grupos apagados com sucesso!";
            header('Location: ' . URL . "/Manutencao/editausuario/".$post['idGestor']);
        else:
            $_SESSION['msg']['error'] = "Erro ao apagar grupos!\n ". $result['error'];
            header('Location: ' . URL . "/Manutencao/editausuario/".$post['idGestor']);
        endif;
    }

    
    
    
    /**
     * Apaga o usuario para o grupo em edição
     * Tela de manutenção de grupos
     */
    public function apagaUsuariosGrupos()
    {
        //$this->helper->debug($_POST, true);        
        //global $execBo;
        $post = $_POST;
        $usuarios = $post['usuarios'];
        $idGrupo = addslashes($post['idGrupo']);
        $codGrupo = addslashes($post['idLegGrupo']);
        $descAbrev = addslashes($post['descAbrev']);
        $cod_usuario = $post['cod_usuario'];
        $fluxo = new Fluxo();
        $manutencao = new Manutencao();                
        $htmlErro = '';
        
        // Valida se foi selecionado ao menos um usuário
        if(empty($usuarios) || empty($idGrupo) || empty($codGrupo)):
            $_SESSION['msg']['error'] = 'Favor selecionar ao menos um usuário!';
            header('Location:' .URL. '/Manutencao/manutencao_grupo_edita/'.$idGrupo);
            die;
        endif;                
        
        // Valida se existe alguma solicitação ou revisão em aberto para o grupo
        $res = $manutencao->existeSolicitacaoAbertaGrupo($idGrupo);
        //$this->helper->debug($res, true);
        if($res['return'] && !empty($res['dados'])):
            $_SESSION['msg']['error'] = "Existe solicitações em aberto para o grupo.\n Solicitações em aberto: ".$res['dados'];
            header('Location:' .URL. '/Manutencao/manutencao_grupo_edita/'.$idGrupo);
            die();
        endif;	
        
        // Valida se é para integrar com datasul
        $empresa = new Empresa();
        $dadosEmpresa = $empresa->carregaEmpresa($_SESSION['empresaid']);
        $jsonApi = json_decode($dadosEmpresa[0]['integration_data']);
        
        if(isset($jsonApi->execBO->integra) && $jsonApi->execBO->integra == "true"):
            $api = new ExecBO();            
            $dataUserExecBO = $usuarios;

            // Consome WEBSERVICE
            $retorno = $api->execboGrupoUsuario('ESC', $dataUserExecBO);
            
            // Relaciona os usuarios e grava log
            if(isset($retorno['success']) && count($retorno['success']) > 0):
                $result = $manutencao->apagaUsuarioGrupo($idGrupo, $retorno['success']);
            
                // Valida se o retorno foi ok e grava o log
                if($result['return']):                    
                    foreach($usuarios as $key => $val):                       
                        //echo in_array($val['cod_usuario'], $retorno['success'])."<br>";
                        if(in_array($val['cod_usuario'], $retorno['success'])):
                            $fluxo->gravaLogAuditoria(
                                1,
                                date('Y-m-d H:i:s'),
                                date('Y-m-d H:i:s'),
                                $cod_usuario,
                                $val['cod_usuario'],
                                'REMOVIDO',
                                $codGrupo .' - '. $descAbrev,
                                '',
                                $_SESSION['nomeUsuario'],
                                'm',
                                ''
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
                    foreach($retorno['error'] as $key => $val):
                        $htmlErro .= "<strong>". $key . "</strong>: " . $val ."<br>";
                    endforeach;
                endif;
                               
                $htmlErro .= '</p>';
            endif;
        else:
            $userIds = [];
            foreach($usuarios as $val):
                array_push($userIds, $val['cod_usuario']);
            endforeach;            
            $result = $manutencao->apagaUsuarioGrupo($idGrupo, $userIds);
            
            if($result['return']):
                foreach($usuarios as $key => $val):                                           
                    $fluxo->gravaLogAuditoria(
                        1,
                        date('Y-m-d H:i:s'),
                        date('Y-m-d H:i:s'),
                        $cod_usuario,
                        $val['cod_usuario'],
                        'REMOVIDO',
                        $codGrupo .' - '. $descAbrev,
                        '',
                        $_SESSION['nomeUsuario'],
                        'm',
                        ''
                    );                    
                endforeach;
            endif;            
        endif;
               
        if(empty($htmlErro)):
            $_SESSION['msg']['success'] = 'Usuários(s) removido(s) com sucesso';
            header('Location:' .URL. '/Manutencao/manutencao_grupo_edita/'.$idGrupo);
            die();
        else:
            $_SESSION['msg']['error'] = "Erro ao remover usuários(s)\n".$htmlErro;
            header('Location:' .URL. '/Manutencao/manutencao_grupo_edita/'.$idGrupo);
            die();
        endif;
    }
    
    /**
     * Apaga o programa para o grupo em edição
     * Tela de manutenção de grupos
     */
    public function apagaProgramasGrupos()
    {       
        set_time_limit(0);
        //$this->helper->debug($_POST['programas'], true);
        
        //global $execBo;
        $post = $_POST;
        $programas = $post['programas'];
        $idGrupo = addslashes($post['idGrupo']);
        $descAbrev = addslashes($post['descAbrev']);
        $codGrupo = addslashes($post['idLegGrupo']);
        $cod_usuario = addslashes($post['cod_usuario']);
        $htmlErro = '';
        
        // Valida se os campos foram preenchidos
        if(empty($programas) || empty($idGrupo) || empty($codGrupo)):
            $_SESSION['msg']['error'] = 'Favor preencher todos os campos';
            header('Location:' .URL. '/Manutencao/manutencao_grupo_edita/'.$idGrupo);
            die;
        endif;
        
        $manutencao = new Manutencao();
        $fluxo = new Fluxo(); 
        
        // Valida se existe alguma solicitação ou revisão em aberto para o grupo
        $res = $manutencao->existeSolicitacaoAbertaGrupo($idGrupo);
        //$this->helper->debug($res, true); 
        if($res['return'] && !empty($res['dados'])):
            $_SESSION['msg']['error'] = "Existe solicitações em aberto para o grupo.\n Solicitações em aberto: ".$res['dados'];
            header('Location:' .URL. '/Manutencao/manutencao_grupo_edita/'.$idGrupo);
            die();
        endif;	                
        
        // Valida se é para integrar com datasul
        $empresa = new Empresa();
        $dadosEmpresa = $empresa->carregaEmpresa($_SESSION['empresaid']);
        $jsonApi = json_decode($dadosEmpresa[0]['integration_data']);
		
        if(isset($jsonApi->execBO->integra) && $jsonApi->execBO->integra == "true"):
            $api = new ExecBO();
            $programa = "esp/essga005b.p";
            $procedure = "piGrupoPrograma";
            $dataProgExecBO = $programas;

            // Consome WEBSERVICE
            $retorno = $api->execboGrupoPrograma('ESC', $dataProgExecBO);
            //$this->helper->debug($retorno, true);
            // Relaciona os programas e grava log
            if(isset($retorno['success']) && count($retorno['success']) > 0):
		    $result = $manutencao->apagaProgramaGrupoExcebo($idGrupo, $retorno['success']);
            
                // Valida se o retorno foi ok e grava o log
                if($result['return']):                    
                    foreach($programas as $key => $val):                       
                        if(in_array($val['cod_prog_dtsul'], $retorno['success'])):                            
                            $fluxo->gravaLogAuditoria(
                                1,
                                date('Y-m-d H:i:s'),
                                date('Y-m-d H:i:s'),
                                $cod_usuario,
                                '',
                                'REMOVIDO',
                                $codGrupo .' - '. $descAbrev,
                                $val['cod_prog_dtsul'],
                                $_SESSION['nomeUsuario'],
                                'm',
                                0
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
                    foreach($retorno['error'] as $key => $val):
                        $htmlErro .= "<strong>". $key . "</strong>: " . $val ."<br>";
                    endforeach;
                endif;
                               
                $htmlErro .= '</p>';
            endif;
        else:
            //die('asdfg');
            $progIds = [];

            foreach($programas as $val):
                array_push($progIds, $val['cod_prog_dtsul']);
            endforeach;

            $result = $manutencao->apagaProgramaGrupoExcebo($idGrupo, $progIds);

            if($result['return']):
                foreach($programas as $key => $val):                                           
                    $fluxo->gravaLogAuditoria(
                        1,
                        date('Y-m-d H:i:s'),
                        date('Y-m-d H:i:s'),
                        $cod_usuario,
                        '',
                        'REMOVIDO',
                        $codGrupo .' - '. $descAbrev,
                        $val['cod_prog_dtsul'],
                        $_SESSION['nomeUsuario'],
                        'm',
                        0
                    );                    
                endforeach;
            endif;            
        endif;
               
        if(empty($htmlErro)):
            $_SESSION['msg']['success'] = 'Programa(s) removido(s) com sucesso';
            header('Location:' .URL. '/Manutencao/manutencao_grupo_edita/'.$idGrupo);
            die();
        else:
            $_SESSION['msg']['error'] = "Erro ao remover programa(s)\n".$htmlErro;
            header('Location:' .URL. '/Manutencao/manutencao_grupo_edita/'.$idGrupo);
            die();
        endif;
    }

    /**
     * Método de busca de gestores
     */
    public function ajaxBuscaGestor()
    {
        $m = new Manutencao();
        $idUsr = $_POST['idUsr'];
        $tipo = $_POST['tipo'];

        if (isset($idUsr) && !empty($idUsr)) {
            $gestor = $m->ajaxBuscaGestor($idUsr, $tipo);

            if(count($gestor) > 0):
                echo json_encode(array(
                    'gestor'    => $gestor[0],
                    'total'     => count($gestor)
                ));
            else:
                echo json_encode(array(
                    'total'     => 0
                ));
            endif;
        }
    }

    /**
     * Método de busca de usuários autocomplete
     */
    public function ajaxCarregaUsr()
    {
        $dados = array();
        $m = new Manutencao();
        $idUsr = $_POST['idUsr'];
        $idGestor = $_POST['idGestor'];

        if (isset($idUsr) && !empty($idUsr)) {
            $dados['usr'] = $m->ajaxCarregaUsr($idUsr, $idGestor);
            foreach ($dados['usr'] as $value) {
                echo '<li onclick="carregaDadosUsr(' . "'" . $value["nome_usuario"] . "'" . "," . "'" . $value["idUsuario"] . "'" . ')">' . $value['nome_usuario'] . ' - ' . $value['cod_usuario'] . '</li>';
            }
        }
    }

    /**
     * Método de busca de grupos autocomplete
     */
    public function ajaxCarregaGrp()
    {
        $m = new Manutencao();
        $idGrp = $_POST['idGrp'];
        $idGestor = $_POST['idGestor'];

        if (isset($idGrp) && !empty($idGrp)) {
            $dados = $m->ajaxCarregaGrp($idGrp, $idGestor);
            foreach ($dados as $value) {
                echo '<li onclick="carregaDadosGrp(' . "'" . $value["idLegGrupo"] . "'" . "," . "'" . $value["descAbrev"] . "'" . ')">' . $value['idLegGrupo'] . ' - ' . $value['descAbrev'] . '</li>';
            }
        }
    }

    /*
      Está função carrega a lista de programa para ser adicionado ao grupo.
      grupo/carregaDadosGrupo/
    */
    public function ajaxCarregaProg(){
        $dados = array();
        $m = new Manutencao();
        $idProg = $_POST['idProg'];

        if(isset($idProg) && !empty($idProg) ){
            $dados['prog'] = $m->carregaProgramas($idProg);
            foreach ($dados['prog'] as $value) {
                echo'<li onclick="carregaDadosProg('."'".$value["cod_programa"]."'".","."'".$value["z_sga_programas_id"]."'".","."'".$value["descricao_programa"]."'".')">'. $value['descricao_programa']. ' - '.$value['cod_programa'] .'</li>';
            }
        }
    }

    /**
     * Carrega tela de exibição com todos os programas
     */
    public function Programa()
    {
        if (isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa'])) {
            $empresa = new Home();
            $empresaId = addslashes($_POST['empresa']);

            $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
            $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
            $_SESSION['empresaid'] = $empresaId;

            header('Location: ' . URL);
        }

        $dados = array();
        $this->loadTemplate('manutencao_programas', $dados);
    }

    /**
     * Carrega tela de edição de programa por id
     * @param $id
     */
    public function programa_edit($id)
    {
        $m = new Manutencao();

        $dados = array();
        $dados['programa'] = $m->programaById($id);

        $this->loadTemplate('programa_edit', $dados);
    }

    /**
     * Atualiza registros de programa caso tenha ID. Pode ser usado para insert
     */
    public function salvarPrograma()
    {
        if (isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa'])) {
            $empresa = new Home();
            $empresaId = addslashes($_POST['empresa']);

            $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
            $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
            $_SESSION['empresaid'] = $empresaId;

            header('Location: ' . URL);
        }

        // Valida se existe id e se é maio que 0. Caso sim atualiza programa.
        // Para insert, basta adicionar ELSE e utilizar um método de insert na model
        if (isset($_POST['id']) && $_POST['id'] != '' && $_POST['id'] != 0):
            $m = new Manutencao();

            $data = array(
                'id' => $_POST['id'],
                'ajuda' => $_POST['ajudaPrograma']
            );

            $result = $m->salvarPrograma($data);
            if ($result['return']):
                $_SESSION['msg']['success'] = "Programa <strong>{$_POST['codPrograma']}</strong> atualizado com sucesso!";
                header('Location: ' . URL . "/Manutencao/programa");
            else:
                $_SESSION['msg']['error'] = "Erro ao atualizar programa!<br>{$result['error']}";
                header('Location: ' . URL . "/Manutencao/programa_edit/" . $_POST['id']);
            endif;
        endif;
    }

    /**
     * método para criação de jquery datatable na tela de programas
     */
    public function ajaxDatatablePrograma()
    {
        if (isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa'])) {
            $empresa = new Home();
            $empresaId = addslashes($_POST['empresa']);

            $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
            $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
            $_SESSION['empresaid'] = $empresaId;

            header('Location: ' . URL);
        }

        $dados = array();
        $data = array();
        $m = new Manutencao();


        // usado pelo order by
        $fields = array(
            0 => 'prog.cod_programa',
            1 => 'prog.descricao_programa',
            2 => 'prog.especific',
            3 => 'prog.ajuda_programa',
            4 => 'prog.codigo_rotina'
        );

        // Variaveis usadas na paginação do Jquery DataTable
        $search = (isset($_POST["search"]["value"])) ? $_POST["search"]["value"] : '';
        $orderColumn = (isset($_POST['order']['0']['column'])) ? $fields[$_POST['order']['0']['column']] : '';
        $orderDir = (isset($_POST['order']['0']['dir'])) ? $_POST['order']['0']['dir'] : 'ASC';
        $limit = (isset($_POST["length"]) && $_POST["length"] != -1) ? $_POST['length'] : '10';
        $offset = (isset($_POST["length"]) && $_POST["length"] != -1) ? $_POST['start'] : '0';

        // Traz apenas 10 programas utilizando paginação
        $dados = $m->carregaDatatablePrograma($search, $orderColumn, $orderDir, $offset, $limit);

        // Traz todos os programas
        $total_all_records = $m->getCountTable("z_sga_programas prog", $search, $fields, '');

        // cria linhas customizadas no jquery datatable.
        if (count($dados) > 0):
            foreach ($dados as $key => $value):
                $sub_dados = array();
                $sub_dados[] = $value["codigo"];
                $sub_dados[] = $value["descricao"];
                $sub_dados[] = $value["especifico"];
                $sub_dados[] = $value["ajuda"];
                $sub_dados[] = $value["codigo_rotina"];
                $sub_dados[] = '<button type="button" class="btn btn-warning btn-xs" data-toggle="modal" onclick="location.href=\'' . URL . '/Manutencao/programa_edit/' . $value['id'] . '\'">Editar</button>';
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
     * método para criação de jquery datatable na tela de usuários
     */
    public function ajaxDatatableUsuarios()
    {
        $dados = array();
        $data = array();
        $m = new Manutencao();


        // usado pelo order by
        $fields = array(
            0 => 'u.cod_usuario',
            1 => 'u.nome_usuario',
            2 => 'm.descricao',
            3 => 'u.email',
            4 => 'u.gestor_usuario',
            5 => 'u.gestor_grupo',
            6 => 'u.ativo',
        );

        // Variaveis usadas na paginação do Jquery DataTable
        $search = (isset($_POST["search"]["value"])) ? $_POST["search"]["value"] : '';
        $orderColumn = (isset($_POST['order']['0']['column'])) ? $fields[$_POST['order']['0']['column']] : '';
        $orderDir = (isset($_POST['order']['0']['dir'])) ? $_POST['order']['0']['dir'] : 'ASC';
        $limit = (isset($_POST["length"]) && $_POST["length"] != -1) ? $_POST['length'] : '10';
        $offset = (isset($_POST["length"]) && $_POST["length"] != -1) ? $_POST['start'] : '0';

        // Traz apenas 10 usuarios utilizando paginação
        $dados = $m->carregaDatatableUsuario($search, $orderColumn, $orderDir, $offset, $limit);

        // relacionamentos entre tabelas
        $join = " INNER JOIN
                    z_sga_usuarios AS u 
                    ON userEmp.idUsuario = u.z_sga_usuarios_id
              LEFT JOIN
                    z_sga_manut_funcao AS m 
                    ON u.cod_funcao = m.idFuncao ";


        // Traz todos os usuarios
        $total_all_records = $m->getCountTable("z_sga_usuario_empresa AS userEmp", $search, $fields, $join);

        // cria linhas customizadas no jquery datatable.
        if (count($dados) > 0):
            foreach ($dados as $key => $value):
                $sub_dados = array();
                $sub_dados[] = $value["cod_usuario"];
                $sub_dados[] = $value["nome_usuario"];
                $sub_dados[] = $value["descricao"];
                $sub_dados[] = $value["email"];
                $sub_dados[] = $value["gestor_usuario"];
                $sub_dados[] = $value["gestor_grupo"];
                $sub_dados[] = '<center><span class="badge label-primary">'.$value["ativo"].'</span></center>';
                $sub_dados[] = '<button type="button" class="btn btn-warning btn-xs" data-toggle="modal" onclick="location.href=\'' . URL . '/Manutencao/editausuario/' . $value['z_sga_usuarios_id'] . '\'">Editar</button>';
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
     * método para criação de jquery datatable na tela de grupos
     */
    public function ajaxDatatableGrupos()
    {
        $dados = array();
        $data = array();
        $m = new Manutencao();


        // usado pelo order by
        $fields = array(
            0 => 'idLegGrupo',
            1 => 'descAbrev',
            2 => 'totalProg',
            3 => 'totalUsuario'
        );

        // Variaveis usadas na paginação do Jquery DataTable
        $search = (isset($_POST["search"]["value"])) ? $_POST["search"]["value"] : '';
        $orderColumn = (isset($_POST['order']['0']['column'])) ? $fields[$_POST['order']['0']['column']] : '';
        $orderDir = (isset($_POST['order']['0']['dir'])) ? $_POST['order']['0']['dir'] : 'ASC';
        $limit = (isset($_POST["length"]) && $_POST["length"] != -1) ? $_POST['length'] : '10';
        $offset = (isset($_POST["length"]) && $_POST["length"] != -1) ? $_POST['start'] : '0';

        // Traz apenas 10 usuarios utilizando paginação
        $dados = $m->carregaDatatableGrupo($search, $orderColumn, $orderDir, $offset, $limit);

        // Traz todos os usuarios
        $total_all_records = $m->getCountTableGrupos("z_sga_grupo", $search, array(0 => 'idLegGrupo', 1 => 'descAbrev'), '', $_SESSION['empresaid']);

        // cria linhas customizadas no jquery datatable.
        if (count($dados) > 0):
            foreach ($dados as $key => $value):
                $sub_dados = array();
                $sub_dados[] = $value["idLegGrupo"];
                $sub_dados[] = $value["descAbrev"];
                $sub_dados[] = $value["totalProg"];
                $sub_dados[] = $value["totalUsuario"];
                $sub_dados[] = '<button type="button" class="btn btn-warning btn-xs" data-toggle="modal" onclick="location.href=\'' . URL . '/Manutencao/manutencao_grupo_edita/' . $value['idGrupo'] . '\'">Editar</button>';
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
     * método para criação de jquery datatable na tela de edição de grupos para tab usuários. Não utilizado ainda
     */
    public function ajaxDatatableProgramasGrupos()
    {
        $dados = array();
        $data = array();
        $m = new Manutencao();


        // usado pelo order by
        $fields = array(
            //1   => 'u.z_sga_usuarios_id',
            0   => 'u.nome_usuario',
            1   => 'u.cod_usuario',
            2   => 'u.idUsrFluig',
            3   => 'u.cod_gestor',
            4   => 'fun.cod_funcao'
        );

        // Variaveis usadas na paginação do Jquery DataTable
        $search = (isset($_POST["search"]["value"])) ? $_POST["search"]["value"] : '';
        $orderColumn = (isset($_POST['order']['0']['column'])) ? $fields[$_POST['order']['0']['column']] : '';
        $orderDir = (isset($_POST['order']['0']['dir'])) ? $_POST['order']['0']['dir'] : 'ASC';
        $limit = (isset($_POST["length"]) && $_POST["length"] != -1) ? $_POST['length'] : '10';
        $offset = (isset($_POST["length"]) && $_POST["length"] != -1) ? $_POST['start'] : '0';

        $join = "
            LEFT JOIN
                z_sga_grupos as g
                ON u.z_sga_usuarios_id = g.idUsuario
			LEFT JOIN				
                z_sga_usuario_empresa as emp
                ON u.z_sga_usuarios_id = emp.idUsuario
			LEFT JOIN
                z_sga_manut_funcao as fun
                ON u.cod_funcao = fun.idFuncao
        ";

        // Traz apenas 10 usuarios utilizando paginação
        $dados = $m->carregaDatatableGrupo($search, $orderColumn, $orderDir, $offset, $limit);

        // Traz todos os usuarios
        $total_all_records = $m->getCountTable("z_sga_grupo", $search, array(0 => 'idLegGrupo', 1 => 'descAbrev'), $join, $_SESSION['empresaid']);

        // cria linhas customizadas no jquery datatable.
        if (count($dados) > 0):
            foreach ($dados as $key => $value):
                $sub_dados = array();
                $sub_dados[] = '<td><input type="checkbox" name="idUsr[]" value="'.$value['z_sga_grupos_id'].'"></td>';
                $sub_dados[] = $value["u.nome_usuario"];
                $sub_dados[] = $value["u.cod_usuario"];
                $sub_dados[] = $value["u.idUsrFluig"];
                $sub_dados[] = $value["u.cod_gestor"];
                $sub_dados[] = $value["fun.cod_funcao"];
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
     * método para criação de jquery datatable na tela de edição de grupos para tab programas. Não utilizado ainda
     */
    public function ajaxDatatableUsuariosGrupos()
    {
        $dados = array();
        $data = array();
        $m = new Manutencao();


        // usado pelo order by
        $fields = array(
            //1   => 'u.z_sga_usuarios_id',
            0   => 'u.nome_usuario',
            1   => 'u.cod_usuario',
            2   => 'u.idUsrFluig',
            3   => 'u.cod_gestor',
            4   => 'fun.cod_funcao'
        );

        // Variaveis usadas na paginação do Jquery DataTable        
        $search = (isset($_POST["search"]["value"])) ? $_POST["search"]["value"] : '';
        $orderColumn = (isset($_POST['order']['0']['column'])) ? $fields[$_POST['order']['0']['column']] : '';
        $orderDir = (isset($_POST['order']['0']['dir'])) ? $_POST['order']['0']['dir'] : 'ASC';
        $limit = (isset($_POST["length"]) && $_POST["length"] != -1) ? $_POST['length'] : '10';
        $offset = (isset($_POST["length"]) && $_POST["length"] != -1) ? $_POST['start'] : '0';

        $join = "
            LEFT JOIN
                z_sga_grupos as g
                ON u.z_sga_usuarios_id = g.idUsuario
			LEFT JOIN				
                z_sga_usuario_empresa as emp
                ON u.z_sga_usuarios_id = emp.idUsuario
			LEFT JOIN
                z_sga_manut_funcao as fun
                ON u.cod_funcao = fun.idFuncao
        ";


        // Traz apenas 10 usuarios utilizando paginação
        $dados = $m->carregaDatatableGrupo($search, $orderColumn, $orderDir, $offset, $limit);

        // Traz todos os usuarios
        $total_all_records = $m->getCountTable("z_sga_grupo", $search, array(0 => 'idLegGrupo', 1 => 'descAbrev'), $join, $_SESSION['empresaid']);

        // cria linhas customizadas no jquery datatable.
        if (count($dados) > 0):
            foreach ($dados as $key => $value):
                $sub_dados = array();
                $sub_dados[] = '<td><input type="checkbox" name="idUsr[]" value="'.$value['z_sga_grupos_id'].'"></td>';
                $sub_dados[] = $value["cod_programa"];
                $sub_dados[] = $value["descricao_programa"];
                $sub_dados[] = $value["ajuda"];
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

    /*
    Esta função carrega e monta o datatable Grupo Vs Programas grupo/carregaDadosGrupo
  */
    public function ajaxCarregaGrupoProgrma($idGrupo){
        $data = array();
        $m = new Manutencao();
        $dados['carregaProg'] = $m->carregaDadosGrupoProg($idGrupo);
        foreach ($dados['carregaProg'] as $key => $value):
            $sub_dados = array();
            $sub_dados[] = '<td><input type="checkbox" name="idGrupoPrograma[]" class="checkProg" value="'.$value['z_sga_programas_id'].'|'.$value['cod_programa'].'"></td>';
            $sub_dados[] = $value["cod_programa"];
            $sub_dados[] = $value["descricao_programa"];
            $sub_dados[] = $value["descricao_rotina"];
            $sub_dados[] = ($value["especific"] == 'N') ? 'Não' : 'Sim';
            $data[] = $sub_dados;
        endforeach;

        $output = array(
            "data" => (count($data) > 0 ) ? $data : ''
        );
        echo json_encode($output);


    }

    /*
    Esta função carrega e monta o datatable da pagina de grupos Usuario http://162.144.118.90:84_v2/carregaDadosGrupo
  */
    public function ajaxCarregaUsrGrupo($idGrupo){
        $data = array();
        $m = new Manutencao();
        //$dados['carregaUsrGrupo'] = $m->carregaDadosGrupo($idGrupo);
        $dados['carregaUsrGrupo'] = $m->carregaUsrGrupo($idGrupo);

        foreach ($dados['carregaUsrGrupo'] as $key => $value):
            $sub_dados = array();
            $sub_dados[] = '<td><input type="checkbox" name="idUsr[]" class="checkUser" value="'.$value['z_sga_usuarios_id'].'|'.$value['cod_usuario'].'"><input type="hidden" name="nomeUsuario['.$value['z_sga_grupos_id'].']" value="'.$value['z_sga_usuarios_id'].'-'.$value['cod_usuario'].'"></td>';
            $sub_dados[] = $value["nome_usuario"];
            $sub_dados[] = $value["cod_usuario"];
            $sub_dados[] = $value["idUsrFluig"];
            $sub_dados[] = $value["cod_gestor"];
            $sub_dados[] = ($value['cod_gestor'] != "") ? $value['cod_gestor'] : "Não Cadastrado";
            $sub_dados[] = '<center><span class="badge label-primary">'.(($value['ativo'] == 1) ? 'Ativo' : "Inativo").'</span></center>';
            $data[] = $sub_dados;
        endforeach;
        $output = array(
            "data" => (count($data) > 0 ) ? $data : ''
        );
        echo json_encode($output);
    }

    /**
     * Carrega os options dos Módulos e Rotinas filtrando por Programa selecionado
     * @param $idMod
     */
    public function buscaModuloRotinaByProg(){
        $post = $_POST;
        $m = new Manutencao();

        $res = $m->carregaModuloRotinaByProg($post['idProg']);

        $optionMod = '';
        $optionRotina = '';

        if(count($res) > 0):
            $optionMod  .= '<option value="'.$res['cod_modul_dtsul'].'">'.$res['des_mudul_dtsul'].'</option>';
            $optionRotina .= '<option value="'.$res['codigo_rotina'].'">'.$res['descricao_rotina'].'</option>';

            echo json_encode(array(
                'modulo' => $optionMod,
                'rotina' => $optionRotina
            ));
        endif;
    }

    /**
     * Carrega os options dos Modulos Por rotina
     * @param $idModulo
     */
    public function ajaxBuscaModuloProgByRotina(){
        $post = $_POST;
        $m = new Manutencao();

        $modulos = $m->ajaxCarregaModuloProgByRotina($post['idRotina']);

        $option = '<option value="*" selected><span style="font-size:20px">*</span> - TODOS</option>';
        foreach ($modulos as $value):
            $option .= '<option value="'.$value['id'].'">'.$value['id'].' - '.$value['descModulo'].'</option>';
        endforeach;

        echo $option;
    }

    /**
     * Carrega os options das Rotinas por Módulo
     * @param $idModulo
     */
    public function ajaxBuscaRotinaProgByModulo(){
        $post = $_POST;
        $m = new Manutencao();

        $rotinas = $m->ajaxCarregaRotinaProgByModulo($post['idMod']);

        $option = '<option value="*" selected><span style="font-size:20px">*</span> - TODOS</option>';
        foreach ($rotinas as $value):
            $option .= '<option value="'.$value['id'].'">'.$value['id'].' - '.$value['descRotina'].'</option>';
        endforeach;

        echo $option;
    }

    /**
     * Carrega uma lista de módulos filtrando pela string digitada nos campo de módulos de usuários
     */
    public function ajaxBuscaModulos()
    {
        $post = $_POST;
        $m = new Manutencao();

        $modulos = $m->ajaxCarregaModulos($post['search']);
        foreach($modulos as $value):
            echo '<li onclick="carregaDadosModulo(' . "'" . $value["id"] . "'" . "," . "'" . $value["text"] . "'" . ')">' . $value['id'] . ' - ' . $value['text'] . '</li>';
        endforeach;
    }

   /**
     * Carrega uma lista de programas filtrando pela string digitada nos campo de programas de usuários
     */
    public function ajaxBuscaProgramasUsuarios()
    {                      
        $manutencao = new Manutencao();
        $programas = $manutencao->ajaxCarregaProgramas($_POST['search']);
                
        
        foreach($programas as $value):
            echo '<li onclick="carregaDadosPrograma(' . "'" . $value["id"] . "'" . "," . "'" . $value["text"] . "'" . ')">' . $value['id'] . ' - ' . $value['text'] . '</li>';
        endforeach;       
    }
	
    /**
     * Carrega uma lista de programas filtrando pela string digitada
     */
    public function ajaxBuscaProgramas()
    {
        // Valida se foi selecionado uma instancia e uma função
        if(!isset($_POST['nome']) || $_POST['nome'] == ''):
            echo '';
        endif;
        
        $idsAdicionados = (isset($_POST['eliminar'])) ? implode(',', $_POST['eliminar']) : '';
        
        $programa = new Programa();
        $programas = $programa->carregaProgramasGrupoEdita($_POST['nome'], $idsAdicionados, $_POST['idGrupo']);
        
        $optProgramas = "<option></option>";
        
        foreach($programas as $val):
            $optProgramas .= '<option value="'.$val['idPrograma'].'|'.$val['cod_programa'].'">'.$val['cod_programa'].' - '.$val['descricao_programa'].'</option>';
        endforeach;
        
        echo $optProgramas;
    }

    /**
     * Carrega uma lista de rotinas filtrando pela string digitada nos campo de rotinas de usuários
     */
    public function ajaxBuscaRotinas()
    {
        $post = $_POST;
        $m = new Manutencao();

        $rotinas = $m->ajaxCarregaRotinas($post['search']);

        foreach($rotinas as $value):
            echo '<li onclick="carregaDadosRotina(' . "'" . $value["id"] . "'" . "," . "'" . $value["descRotina"] . "'" . ')">' . $value['id'] . ' - ' . $value['descRotina'] . '</li>';
        endforeach;

    }

    public function ajaxValidaGestorPrograma()
    {
        $m = new Manutencao();
        $post = $_POST;

        $res = $m->ajaxValidaGestorPrograma($post['codModulo'], $post['codRotina'], $post['codPrograma']);

        if(count($res) > 0):
            
            // Valida se o gestor é o mesmo que está sendo editado
            if($res['idUsuario'] == $post['idUsuario']):
                echo json_encode(array(                    
                    'total'  => 'ja possui mrp',                    
                ));
            elseif($res['idUsuario'] != $post['idUsuario']):
                echo json_encode(array(
                    'total'  => 'ja possui gestor',
                    'gestor' => $res
                ));
            else:
                echo json_encode(array(
                    'total'  => count($res),
                    'gestor' => $res
                ));
            endif;
        else:
            echo json_encode(array(
                'total' => 0,
            ));
        endif;
    }  
    
        /**
     * Apaga o gestor Módulo, rotina e programa selecionados
     */
    public function apagaMPRSelecionados(){
        // valida se foi selecionado ao menos um registro        
        if(count($_POST['mpr']) == 0):
            $_SESSION['msg']['error'] = 'Selecione um ou mais registro por favor.';
            header('Location: ' . URL . "/Manutencao/editausuario/".$_POST['idGestor']);
            die;
        endif;

        $post = $_POST;
        $m = new Manutencao();

        $result = $m->apagaMPRGestor($post['mpr']);

        if($result['return'] == 'true'):
            $_SESSION['msg']['success'] = "Módulo(s) apagado(s) com sucesso!";
            header('Location: ' . URL . "/Manutencao/editausuario/".$post['idGestor']);
        else:
            $_SESSION['msg']['error'] = "Erro ao apagar módulo(s)!\n ". $result['error'];
            header('Location: ' . URL . "/Manutencao/editausuario/".$post['idGestor']);
        endif;
    }
}

?>