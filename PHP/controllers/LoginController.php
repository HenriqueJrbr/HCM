<?php
class LoginController extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $dados = array(); 
        //global $ldap;
    	if(isset($_POST['usuario']) && !empty($_POST['usuario']) && isset($_POST['senha']) && !empty($_POST['senha'])){
            $usuario = addslashes($_POST['usuario']);
            $senha = addslashes($_POST['senha']);

            $login = new Login();
            $helper = new Helper();
            
            // Valida se é para integrar ldap ou azure          
            $rsIntegra = $login->getIntegrationData();
            $jsonIntegra = json_decode($rsIntegra['integration_data']);
            
            if(isset($jsonIntegra->ldap->integra) && $jsonIntegra->ldap->integra === true){
                if($login->validaLoginAd($usuario,$senha,$jsonIntegra->ldap) == true){
                    header('Location: '.URL);
                }
            }else{
                $retorno = $login->validalogin($usuario,$senha);

                if($retorno == true){
                    header('Location: '.URL);
                    
                }
                if($retorno == false){ 
                    $helper->setAlert(
                        'error',
                        'Usuário/Senha invalido',
                        ''
                    );
                }

            }
    		
    		
    	}
        $this->loadView('login', $dados);
    }

    public function sair() {
        $dados = array(); 
       
        unset($_SESSION['login']);
        unset($_SESSION['empresaid']);
        unset($_SESSION['empresaDesc']);
        unset($_SESSION['acesso']);
        unset($_SESSION['gestor']);
        header('Location: '.URL);
    }

    public function novaSenha(){
        $dados = array();

        if(isset($_POST['usuario']) && !empty($_POST['usuario'])){
            $login = new Login();
            $helper = new Helper();
            $usuario = addslashes($_POST['usuario']);
            $retorno = $login->novaSenha($usuario);

            if($retorno == false){
                $helper->setAlert(
                    'error',
                    'Usuário não existe',
                    ''
                );
            }else{
                $helper->setAlert(
                    'error',
                    'Senha enviada para o e-mail cadastrado no SGA',
                    ''
                );
            }
        }

        $this->loadView('nova-senha', $dados);
    }

    public function trocaSenha(){
        $dados = array();

        if(isset($_POST['senhaAtual']) && !empty($_POST['senhaAtual']) && isset($_POST['novaSenha']) && !empty($_POST['novaSenha']) && isset($_POST['novaSenha2']) && !empty($_POST['novaSenha2'])){

            $login = new Login();
            $configuracaoSga = new ConfiguracaoSga();
            $retorno = $login->validaSenhaAtual($_POST['senhaAtual'],$_SESSION['idUsrLogado']);
            if($retorno == '1' || $retorno == true){
                if($_POST['novaSenha'] == $_POST['novaSenha2']){
                   $retornoSenha = $configuracaoSga->trocaSenhaAtual($_SESSION['idUsrLogado'],md5($_POST['novaSenha']));

                   if($retornoSenha > 0){
                        header('Location: '.URL.'/Home');
                   }
                }
            }
        }

        $this->loadView('troca-senha', $dados); 
    }

    public function ajaxValidaSenhaAtual(){
        $login = new Login();
        $retorno = $login->validaSenhaAtual($_POST['senha'],$_SESSION['idUsrLogado']);
        echo $retorno;
    }

}