<?php
class LogController extends Controller {

    public function __construct() {
        parent::__construct();
         $login = new Login();
        
        if(!$login->isLogin()){
            header('Location: '.URL.'/Login');
        }
    }

    public function index() {
        $dados = array();

        $home = new Home();
      
        if(isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa']) ){
          $empresa = new Home();
          $empresaId = addslashes($_POST['empresa']);

          $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
          $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
          $_SESSION['empresaid'] = $empresaId;

            
            header('Location: '.URL);
        }

        $log = new Log();
        if(isset($_POST['pesquisar']) && $_POST['pesquisar'] = "Filtrar"){
            $dados['dadoslog'] = $log->carregaLog(
                $_POST['dataInicio'],
                $_POST['dataFim'],
                $_POST['solicitante'],
                $_POST['solicitanteFim'],
                $_POST['aprovador'],
                $_POST['aprovadorFim'],
                $_POST['usuario'],
                $_POST['usuarioFim'],
                $_POST['grupo'],
                $_POST['grupoFim'],
                $_POST['programa'],
                $_POST['programaFim']
                );

            $_SESSION['dataInicio'] = $_POST['dataInicio'];
            $_SESSION['dataFim'] = $_POST['dataFim'];            
            $_SESSION['solicitante'] = $_POST['solicitante'];
            $_SESSION['solicitanteFim'] = $_POST['solicitanteFim'];
            $_SESSION['aprovador'] = $_POST['aprovador'];
            $_SESSION['aprovadorFim'] = $_POST['aprovadorFim'];
            $_SESSION['usuario'] = $_POST['usuario'];
            $_SESSION['usuarioFim'] = $_POST['usuarioFim'];
            $_SESSION['grupo'] = $_POST['grupo'];
            $_SESSION['grupoFim'] = $_POST['grupoFim'];
            $_SESSION['programa'] = $_POST['programa'];
            $_SESSION['programaFim'] = $_POST['programaFim'];

        }else{
             $dados['dadoslog'] = $log->carregaLog(
                 date('Y-m-d', strtotime('-15 days')),
                 date('Y-m-d'),
                 '',
                 'ZZZZZZZZZZZZZZZ',
                 '',
                 'ZZZZZZZZZZZZZZZ',
                 '',
                 'ZZZZZZZZZZZZZZZ',
                 '',
                 'ZZZZZZZZZZZZZZZ',
                 '',
                 'ZZZZZZZZZZZZZZZ'
             );
              unset($_SESSION['dataInicio']); 
              unset($_SESSION['dataFimInicio']);
              unset($_SESSION['dataInicioFim']);
              unset($_SESSION['dataFim']); 
              unset($_SESSION['solicitante']);
              unset($_SESSION['solicitanteFim']);
              unset($_SESSION['aprovador']); 
              unset($_SESSION['aprovadorFim']);
              unset($_SESSION['usuario']); 
              unset($_SESSION['usuarioFim']);
              unset($_SESSION['grupo']);
              unset($_SESSION['grupoFim']);
              unset($_SESSION['programa']);
              unset($_SESSION['programaFim']);

        }

  
        $dados['solicitante'] = $log->carregaUsuario();
        $dados['aprovador'] = $log->carregaAprovador();

       
         $this->loadTemplate('log', $dados);
    }






}