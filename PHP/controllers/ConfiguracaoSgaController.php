<?php
class ConfiguracaoSgaController extends Controller {

    public function __construct() {
          parent::__construct();
         $login = new Login();
        
        if(!$login->islogin()){
            header('Location: '.URL.'/Login');
        }
    }

    public function index() {
        $dados = array(); 
       
        if(isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa']) ){
          $empresa = new Home();
          $empresaId = addslashes($_POST['empresa']);

          $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
          $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
          $_SESSION['empresaid'] = $empresaId;

            
            header('Location: '.URL);
        }

   
        $this->loadTemplate('home', $dados);

    }

    public function usuario(){
      $login = new Login();
      if(!$login->validaAcesso($_SESSION['idUsrLogado'],20)){
        header('Location: '.URL);
      }
      
      if(isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa']) ){
        $empresa = new Home();
        $empresaId = addslashes($_POST['empresa']);

        $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
        $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
        $_SESSION['empresaid'] = $empresaId;

          
          header('Location: '.URL);
      }
      $dados = array();
      $conf = new ConfiguracaoSga();
      if(isset($_POST['salvar']) &&  $_POST['salvar'] == "Salvar"){
          if(isset($_POST['usuario']) && !empty($_POST['usuario']) && isset($_POST['senha']) && !empty($_POST['senha']) && isset($_POST['validadeAcesso']) && !empty($_POST['validadeAcesso']) && isset($_POST['idTotvs']) && !empty($_POST['idTotvs']) &&  isset($_POST['grupo']) && !empty($_POST['grupo'])  ){
              
              $login = addslashes($_POST['usuario']);
              $nomeUsuario = addslashes($_POST['nomeUsuario']);
              $email = addslashes($_POST['emailUsuario']);
              $senha = addslashes($_POST['senha']);
              $validade = addslashes($_POST['validadeAcesso']);
              $idTotovs = addslashes($_POST['idTotvs']);
              $grupo = addslashes($_POST['grupo']);

              $dados['idUsr'] = $conf->criarUsrSga($login,$nomeUsuario,$email,md5($senha),$validade,$idTotovs);
              $conf->addUsrGrupo($dados['idUsr']['idLogin'],$grupo);

              header('Location: '.URL.'/ConfiguracaoSga/usuario');

          }
      }


        if(isset($_POST['editarSalvar']) &&  $_POST['editarSalvar'] == "Salvar"){
            if(isset($_POST['usuarioEditar']) && !empty($_POST['usuarioEditar'])){
                
                $id = addslashes($_POST['idUsuarioEditar']);
                $nomeUsuario = addslashes($_POST['nomeUsuarioEditar']);
                $email = addslashes($_POST['emailUsuarioEditar']);
                $validade = addslashes($_POST['validadeAcessoEditar']);
                $idTotovs = addslashes($_POST['idTotvsEditar']);
                $grupo = addslashes($_POST['grupoEditar']);

                $conf->editarLogin($id,$nomeUsuario,$email,$validade,$idTotovs,$grupo);

                header('Location: '.URL.'/ConfiguracaoSga/usuario');

            }
        }

        if(isset($_POST['salvarSenha']) &&  $_POST['salvarSenha'] == "Salvar"){
            if(isset($_POST['senhaEditar']) && !empty($_POST['senhaEditar'])){
                
               $senhaEditar = md5($_POST['senhaEditar']);
               $id = $_POST['idSenhaEditar'];

                $conf->editarSenha($id,$senhaEditar);

                header('Location: '.URL.'/ConfiguracaoSga/usuario');

            }
        }
        $dados['usr'] = $conf->carregaUsuario();
        $dados['grupo'] = $conf->carregaGrupoSga();
        $dados['login'] = $conf->carregaLogin();

        $this->loadTemplate("confiuracaoUsuario",$dados);
    }

    public function ajaxValidaUsrSga(){
        $usr = $_POST['$usr'];
        $dados = array();
        $conf = new ConfiguracaoSga();
        $dados['validado'] = $conf->validaUsr($usr);
        echo $dados['validado'];
    }

    public function ajaxDadosDoLogin(){
      $id = $_POST['$id'];
      $conf = new ConfiguracaoSga();
      $dados = array();
      $dados['dadosUsuario'] = $conf->ajaxDadosDoLogin($id);
      echo json_encode($dados['dadosUsuario']);
    }

    /**
     * Carrega tela com snapshots
     */
    public function snapshots()
    {
        $dados = [];
        
        $snapshots = new ConfiguracaoSga();
        $dados['snapshots'] = $snapshots->carregaSnapshots(); 
        
        return $this->loadTemplate('snapshots', $dados);
    }
    
    
    /**
     * Carrega tela com snapshots
     */
    public function atualizaSnapshots()
    {
        $dados = [];
        
        $snapshots = new ConfiguracaoSga();
        $result = $snapshots->atualizaSnapshot(); 
        
        if($result['return']):                    
            $this->helper->setAlert(
                'success',
                'Snapshot atualizado com sucesso!',
                'ConfiguracaoSga/snapshots'
            );
        else:        
            $this->helper->setAlert(
                'error',
                'Erro ao atualizar snapshot! ' . "<br>" . $result['error'],
                'ConfiguracaoSga/snapshots'
            );
        endif;        
    }

    public function parametrosSga() 
    {
        $dados = array(); 
   
        if(isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa']) ){
          $empresa = new Home();
          $empresaId = addslashes($_POST['empresa']);

          $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
          $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
          $_SESSION['empresaid'] = $empresaId;

            
            header('Location: '.URL);
        }

        $configuracao = new ConfiguracaoSga();
        $configs = $configuracao->carregaParametrosGlobais();
        $dados['config'] = (isset($configs['pGlobal'])) ? $configs['pGlobal'] : '';
        $dados['email'] = (isset($configs['email'])) ? $configs['email'] : '';
        $dados['ldap'] = '';
        $dados['azure'] = '';
        
        if(count($dados['config']) > 0):
            $integrationData = '';
            $integrationData = json_decode($dados['config']['integration_data']);

            if(isset($integrationData->ldap)):
                $dados['ldap'] = $integrationData->ldap;
            endif;

            if(isset($integrationData->azure)):
                $dados['azure'] = $integrationData->azure;
            endif;
        endif;        

        $this->loadTemplate('parametros_sga', $dados);

    }

    /**
     * Cadastra novas empresas
     */
    public function salvaParamGlobal()
    {        
        if (isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa'])) {
            $empresa = new Home();
            $empresaId = addslashes($_POST['empresa']);

            $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
            $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
            $_SESSION['empresaid'] = $empresaId;

            header('Location: ' . URL);
        }
        
        // echo "<pre>";
        // print_r($_POST);
        // print_r(json_encode($_POST['ads']));
        // die;

        $config = new ConfiguracaoSga();
        
        if (isset($_POST['ambiente']) && !empty($_POST['ambiente'])):

            $ambiente = addslashes($_POST['ambiente']);
            $host = addslashes($_POST['host']);  
            $email = $_POST['email'];

            $res = $config->salvaParamGlobal($host, $ambiente, json_encode($_POST['ads']));
                        
            if($res['return'] == true):

                // Grava as informações de envio de email
                $res = $config->salvaParamEmail($email);

                if($res['return'] == true):
                    $this->helper->setAlert(
                        'success',
                        'Parâmetros salvos com sucesso',
                        'ConfiguracaoSga/parametrosSga'
                    );
                else:
                    $this->helper->setAlert(
                        'error',
                        "Erro ao salvar parâmetros. <br>".$res['error'],
                        'ConfiguracaoSga/parametrosSga'
                    );
                endif;
            else:
                $this->helper->setAlert(
                    'error',
                    "Erro ao salvar parâmetros. <br>".$res['error'],
                    'ConfiguracaoSga/parametrosSga'
                );
            endif;                
        else:
            $this->helper->setAlert(
                'error',
                'Favor preencher todos os campos obrigatórios!',
                'ConfiguracaoSga/parametrosSga'
            );        
        endif;    
    }

}