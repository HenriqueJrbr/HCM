<?php
class GrupoController extends Controller {

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
      $dados['idGrupo'] = $idGrupo;
    

     if(empty($dados['gestorGrupo'])){
        $dados['gestorGrupo'] = "1";
     }
      
      $this->loadTemplate('grupo_dados', $dados); 

  }

   /*
    Está função carrga a lista de usuário para ser adicionado ao grupo.
    grupo/carregaDadosGrupo/
  */
  public function ajaxCarregaUsr(){
    $dados = array(); 
    $g = new Grupo();
    $idUsr = $_POST['idUsr'];

    if(isset($idUsr) && !empty($idUsr) ){
      $dados['usr'] = $g->ajaxCarregaUsr($idUsr);
       foreach ($dados['usr'] as $value) {
          echo'<li onclick="carregaDadosUsr('."'".$value["nome_usuario"]."'".","."'".$value["idUsuario"]."'".","."'".$value["cod_gestor"]."'".","."'".$value["cod_usuario"]."'".')">'. $value['nome_usuario']. ' - '.$value['cod_usuario'] .'</li>';
       }  
    }
  }

  /*
    Está função carrga a lista de programa para ser adicionado ao grupo.
    grupo/carregaDadosGrupo/
  */
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


  /*
    Esta função carrega e monta o datatable da pagina de grupos http://162.144.118.90:84/sga_v2/grupo
  */
  public function ajaxCarregaGrupo(){
      $data = array();
        $g = new Grupo();

        $dados['carregaGrupo'] = $g->carregaGrupo($_SESSION['empresaid']);
        foreach ($dados['carregaGrupo'] as $key => $value):
            $sub_dados = array();
            $sub_dados[] = $value["idLegGrupo"];
            $sub_dados[] = $value["descAbrev"];
            $sub_dados[] = $value["totalProg"];
            $sub_dados[] = $value["totalUsuario"];
            $sub_dados[] = '<button type="button" class="btn btn-success btn-xs" data-toggle="modal" onclick="location.href=\'' . URL . '/Grupo/carregaDadosGrupo/' . $value['idGrupo'] . '\'">Visualizar</button>';
            $data[] = $sub_dados;
        endforeach;

        $output = array(
            "data" => $data
        );
        echo json_encode($output);
  }


    /*
    Esta função carrega e monta o datatable da pagina de grupos Usuario http://162.144.118.90:84/sga_v2/carregaDadosGrupo
  */
  public function ajaxCarregaUsrGrupo($idGrupo){
      $data = array();
      $g = new Grupo();
      $dados['carregaUsrGrupo'] = $g->carregaDadosGrupo($idGrupo);
      foreach ($dados['carregaUsrGrupo'] as $key => $value):
          $sub_dados = array();
          $sub_dados[] = $value["nome_usuario"];
          $sub_dados[] = $value["cod_usuario"];
          $sub_dados[] = $value["idUsrFluig"];
          $sub_dados[] = $value["cod_gestor"];
          $sub_dados[] = $value["cod_funcao"];
          $sub_dados[] = '<center><span class="badge label-primary">'.(($value['ativo'] == 1) ? 'Ativo' : 'Inativo').'</span></center>';
          $data[] = $sub_dados;
      endforeach;
      $output = array(
          "data" => (count($data) > 0 ) ? $data : ''
      );
        echo json_encode($output);
  }
  /*
    Esta função carrega e monta o datatable Grupo Vs Programas grupo/carregaDadosGrupo
  */
  public function ajaxCarregaGrupoProgrma($idGrupo){
      $g = new Grupo();
      $data = array();
      $dados['carregaProg'] = $g->carregaDadosGrupoProg($idGrupo);

      foreach ($dados['carregaProg'] as $key => $value):
            $sub_dados = array();
            $sub_dados[] = $value["cod_programa"];
            $sub_dados[] = $value["descricao_programa"];
            $sub_dados[] = $value["ajuda"];
            $sub_dados[] = $value["descricao_rotina"];
            $sub_dados[] = $value["especific"];
            $data[] = $sub_dados;
      endforeach;

      $output = array(
          "data" => $data
      );
      echo json_encode($output);
  }


  public function ajaxGerraRelatorio($idGrupo){
    $login = new Login();
    if(!$login->isLogin()){
        header('Location: '.URL.'/login');   
      }else{
        if($login->validaTrocaSenha() == true){
            header('Location: '.URL.'/Login/trocaSenha');
        }
      }

    $g = new Grupo();
    $data = array();
    $retornoGrupo = $g->carregaDadosGrupo($idGrupo);
    $grupoDesc  =  $g->carregaDescGrupo($idGrupo);
    $progrmas = $g->carregaDadosGrupoProg($idGrupo);

    $dadosXls  = "";
    $dadosXls .= "  <table border='1' >";
    $dadosXls .= "      <tr>";
    $dadosXls .= "          <td colspan='5'><center><h1>Rel&aacute;torio de grupo</h1></center></td>";
    $dadosXls .= "      </tr>";
    $dadosXls .= "      <tr>";
    $dadosXls .= "          <td colspan='5'><h3>Grupo: ".$grupoDesc['idLegGrupo']." - ".utf8_decode($grupoDesc['descAbrev'])."</h3></td>";
    $dadosXls .= "      </tr>";
    $dadosXls .= "      <tr>";
    $dadosXls .= "          <td colspan='5'></td>";
    $dadosXls .= "      </tr>";
    $dadosXls .= "      <tr>";
    $dadosXls .= "          <td colspan='5'><center><h4>Usu&aacute;rio</h4></center></td>";
    $dadosXls .= "      </tr>";
    $dadosXls .= "      <tr>";
    $dadosXls .= "          <td colspan='5'><b>Total de Usu&aacute;rio:</b> ".count($retornoGrupo)." </td>";
    $dadosXls .= "      </tr>";
    $dadosXls .= "  </table>";


    $dadosXls .= "  <table border='1' >";
    $dadosXls .= "      <tr>";
    $dadosXls .= "          <th>Nome</th>";
    $dadosXls .= "          <th>Gestor</th>";
    $dadosXls .= "          <th>ID DataSul</th>";
    $dadosXls .= "          <th>Cod Gestor</th>";
    $dadosXls .= "          <th>Fun&ccedil;&atilde;o</th>";
    $dadosXls .= "      </tr>";
    foreach($retornoGrupo as $res){
        $dadosXls .= "      <tr>";
        $dadosXls .= "          <td>".utf8_decode($res['nome_usuario'])."</td>";
        $dadosXls .= "          <td>".$res['cod_gestor']."</td>";
        $dadosXls .= "          <td>".$res['cod_usuario']."</td>";
        $dadosXls .= "          <td>".$res['cod_gestor']."</td>";
        $dadosXls .= "          <td>".utf8_decode($res['cod_funcao'])."</td>";
        $dadosXls .= "      </tr>";
    }
    $dadosXls .= "      <tr>";
    $dadosXls .= "          <td colspan='5'><center><h4>Programas</h4></center></td>";
    $dadosXls .= "      </tr>";
     $dadosXls .= "      <tr>";
    $dadosXls .= "          <td colspan='5'><b>Total de Progamas:</b> ".count($progrmas)." </td>";
    $dadosXls .= "      </tr>";
    $dadosXls .= "  </table>";

    $dadosXls .= "  <table border='1' >";
    $dadosXls .= "      <tr>";
    $dadosXls .= "          <td><b>Programa</b></td>";
    $dadosXls .= "          <td colspan='3'><b>Descri&ccedil;&atilde;o</b></td>";
    $dadosXls .= "          <td><b>Ajuda</b></td>";
    $dadosXls .= "      </tr>";
    foreach($progrmas as $prog){
        $dadosXls .= "      <tr>";
        $dadosXls .= "          <td>".utf8_decode($prog['cod_programa'])."</td>";
        $dadosXls .= "          <td colspan='3'>".utf8_decode($prog['descricao_programa'])."</td>";
        $dadosXls .= "          <td>".utf8_decode($prog['ajuda'])."</td>";
        $dadosXls .= "      </tr>";
    }

    $dadosXls .= "  </table>";

     // Definimos o nome do arquivo que será exportado  
    $arquivo = "Relátorio de grupo ".$grupoDesc['idLegGrupo']." - ".$grupoDesc['descAbrev'].".xls";  
    // Configurações header para forçar o download  
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.$arquivo.'"');
    header('Cache-Control: max-age=0');
    // Se for o IE9, isso talvez seja necessário
    header('Cache-Control: max-age=1');
       
    // Envia o conteúdo do arquivo  
    echo $dadosXls;  
  }


  
}


