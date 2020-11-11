<?php
class EditaGrupoController extends Controller {

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
        $dados = array(); 
       
       	$g = new Grupo();

        $home = new Home();
      
        if(isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa']) ){
          $empresa = new Home();
          $empresaId = addslashes($_POST['empresa']);

          $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
          $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
          $_SESSION['empresaid'] = $empresaId;

            
            header('Location: '.URL);
        }


        if(isset($_POST['addGrupo']) && $_POST['addGrupo'] == "Salvar"){
          $nameGrupo = addslashes($_POST['nameGrupo']);
          $descGrupo = addslashes($_POST['descGrupo']);

          $g->addGrupo($nameGrupo,$descGrupo);
          header('Location: '.URL.'/Grupo');
        }
        $dados['carregaGrupo'] = $g->carregaGrupo($_SESSION['empresaid']);
   
        $this->loadTemplate('grupo', $dados);

    }





  public function carregaDadosGrupo($idGrupo){
      
      if(isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa']) ){
        $empresa = new Home();
        $empresaId = addslashes($_POST['empresa']);

        $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
        $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
        $_SESSION['empresaid'] = $empresaId;

          
          header('Location: '.URL);
      }
      $dados = array(); 
      $g = new Grupo();

      if(isset($_POST['addUsr']) && $_POST['addUsr'] == "Salvar"){
        if(isset($_POST['idUsr2']) && !empty($_POST['idUsr2'])){
          $g->addUsrGrupo($idGrupo,$_POST['idUsr2']);
        }
      }

      if(isset($_POST['addPrograma']) && $_POST['addPrograma'] == "Salvar"){
        if(isset($_POST['idProg']) && !empty($_POST['idProg'])){
          $g->addProgGrupo($_POST['idProg'],$idGrupo);
        }
      }


      if(isset($_POST['excluirProd']) && $_POST['excluirProd'] == "Excluir"){
         if(isset($_POST['idPrograma']) && !empty($_POST['idPrograma'])){
            foreach ($_POST['idPrograma'] as $value) {
              $g->excluirProgramaDoGrupo($value);
            }
         }
        
      }
      if(isset($_POST['excluirUsr']) && $_POST['excluirUsr'] == "Excluir"){
        if(isset($_POST['idUsr']) && !empty($_POST['idUsr'])){
          foreach($_POST['idUsr'] as $value) {
            $g->excluirUsrGrupo($value);
         }
        }
         
      }

      $dados['dadosGrupoProd'] = $g->carregaDadosGrupoProg($idGrupo);
      $dados['carregaUsuario'] = $g->carregaDadosGrupo($idGrupo);
      $dados['gestorGrupo']    = $g->carregaGestorGrupo($idGrupo);
      $dados['grupo']    = $g->carregaDescGrupo($idGrupo);

    

     if(empty($dados['gestorGrupo'])){
        $dados['gestorGrupo'] = "1";
     }
      
      $this->loadTemplate('grupo_dados', $dados); 

  }

  public function ajaxCarregaUsr(){
    $dados = array(); 
    $g = new Grupo();
    $idUsr = $_POST['idUsr'];

    if(isset($idUsr) && !empty($idUsr) ){
      $dados['usr'] = $g->ajaxCarregaUsr($idUsr);
       foreach ($dados['usr'] as $value) {
          echo'<li onclick="carregaDadosUsr('."'".$value["nome_usuario"]."'".","."'".$value["idUsuario"]."'".","."'".$value["cod_gestor"]."'".')">'. $value['nome_usuario']. ' - '.$value['cod_usuario'] .'</li>';
       }  
    }
  }

  public function ajaxCarregaProg(){
    $dados = array(); 
    $g = new Grupo();
    $m = new Matriz();
    $idProg = $_POST['idProg'];

    if(isset($idProg) && !empty($idProg) ){
      $dados['prog'] = $m->carregaProgramas($idProg);
       foreach ($dados['prog'] as $value) {
          echo'<li onclick="carregaDadosProg('."'".$value["cod_programa"]."'".","."'".$value["z_sga_programas_id"]."'".","."'".$value["descricao_programa"]."'".')">'. $value['descricao_programa']. ' - '.$value['cod_programa'] .'</li>';
       }  
    }
  }




  

}