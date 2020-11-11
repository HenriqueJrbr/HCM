<?php
class SistemaController extends Controller {

    public function __construct() {
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

    public function index() {

        if(isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa']) ){
          $empresa = new Home();
          $empresaId = addslashes($_POST['empresa']);

          $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
          $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
          $_SESSION['empresaid'] = $empresaId;

            
            header('Location: '.URL);
        }

        $data = array();
        $m = new SistemaModulo();
        $data['sistema'] = $m->carregaSistema();
        
        $this->loadTemplate('sistema', $data);
    }

    public function modulo($id) {


        if(isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa']) ){
          $empresa = new Home();
          $empresaId = addslashes($_POST['empresa']);

          $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
          $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
          $_SESSION['empresaid'] = $empresaId;

            
            header('Location: '.URL);
        }

        $data = array();
        $m = new SistemaModulo();
        $data['modulo'] = $m->carregaMudulo($id);
        $data['descSistema'] = $m->carregaSistemaDesc($id);
        
        $this->loadTemplate('modulo', $data);
    }

}