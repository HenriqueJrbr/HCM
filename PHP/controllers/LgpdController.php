<?php
class LgpdController extends Controller {

    public function __construct() {
        parent::__construct();
          $login = new Login();
        
        if(!$login->isLogin()){
              header('Location: '.URL.'/login');   
        }else{
            if($login->validaTrocaSenha() == true){
                header('Location: '.URL.'/login/trocaSenha');
            }
        }
    }

    public function index() {
        $dados = array();

        $usr = new Usuario();
        $home = new Home();

        //$dados['usuarios'] = $usr->carregaUsuario($_SESSION['empresaid']);
        $dados['contUsuario'] = $home->contaUsuario($_SESSION['empresaid']);

        if(isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa']) ){
          $empresa = new Home();
          $empresaId = addslashes($_POST['empresa']);

          $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
          $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
          $_SESSION['empresaid'] = $empresaId;

            
            header('Location: '.URL);
        }
     
        
        $this->loadTemplate('usuario', $dados);
    }


    public function auditoriaLgpd() {
      $dados = array();

      $usr = new Usuario();
      $home = new Home();

      if(isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa']) ){
        $empresa = new Home();
        $empresaId = addslashes($_POST['empresa']);

        $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
        $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
        $_SESSION['empresaid'] = $empresaId;

          
          header('Location: '.URL);
      }
   
      
      $this->loadTemplate('lgpd_auditoria_consulta', $dados);
  }

    /**
     * Carrega uma lista de gestores  filtrando pela string
     */
    public function ajaxGestores()
    {
        $post = $_POST;
        $lgpd = new Lgpd();
        $res = [];
        // 2 = grupo
        // 3 = usuario
        // 4 = programa
        // 5 = rotina
        // 6 = modulo

        if(isset($post['tipo']) && $post['tipo'] == 2):
          $res = $lgpd->carregaGestorGrupo($post);
        elseif(isset($post['tipo']) && $post['tipo'] == 3):
          $res = $lgpd->carregaGestorUsuario($post);
        elseif(isset($post['tipo']) && (in_array($post['tipo'], [4,5,6]))):
            $res = $lgpd->carregaGestorMPR($post);
        endif;

        $opt = "";
        foreach($res as $val):
            $opt .= '<option value="'.$val['id'].'-'.$val['codigo'].'">'.$val['codigo'].' - '.$val['descricao'].'</option>';
        endforeach;

        echo $opt;
    }


    /**
     * Carrega uma lista de módulos filtrando pela string digitada nos campo de módulos de usuários
     */
    public function ajaxModulos()
    {
        $post = $_POST;
        $lgpd = new Lgpd();

        $res = $lgpd->carregaModulos($post);

        $opt = "";
        foreach($res as $val):
            $opt .= '<option value="'.$val['codigo'].'">'.$val['codigo'].' - '.$val['descricao'].'</option>';
        endforeach;

        echo $opt;
    }

    /**
     * Carrega uma lista de módulos filtrando pela string digitada nos campo de módulos de usuários
     */
    public function ajaxRotinas()
    {
        $post = $_POST;
        $lgpd = new Lgpd();

        $res = $lgpd->carregaRotinas($post);

        $opt = "";
        foreach($res as $val):
            $opt .= '<option value="'.$val['codigo'].'">'.$val['codigo'].' - '.$val['descricao'].'</option>';
        endforeach;

        echo $opt;
    }

   /**
     * Carrega uma lista de programas filtrando pela string digitada nos campo de programas
     */
    public function ajaxProgramas()
    {                      
        $lgpd = new Lgpd();
        $res = $lgpd->carregaProgramas($_POST);
                
        $opt = "";
        foreach($res as $val):
          $opt .= '<option value="'.$val['id'].'-'.$val['codigo'].'">'.$val['codigo'].' - '.$val['descricao'].'</option>';
        endforeach;       

        echo $opt;
    }

    /**
     * Carrega uma lista de grupos filtrando pela string digitada nos campo de grupos
     */
    public function ajaxGrupos()
    {                      
        $lgpd = new Lgpd();
        $res = $lgpd->carregaGrupos($_POST);
                
        $opt = "";
        foreach($res as $val):
          $opt .= '<option value="'.$val['id'].'-'.$val['codigo'].'">'.$val['codigo'].' - '.$val['descricao'].'</option>';
        endforeach;       

        echo $opt;
    }

    /**
     * Carrega uma lista de Usuarios filtrando pela string digitada nos campo de Usuarios
     */
    public function ajaxUsuarios()
    {                      
        $lgpd = new Lgpd();
        $res = $lgpd->carregaUsuarios($_POST);
               
        $opt = "";
        foreach($res as $val):
          $opt .= '<option value="'.$val['id'].'-'.$val['codigo'].'">'.$val['nome'].'</option>';
        endforeach;       

        echo $opt;
    }

    public function ajaxCarregaDatatableGestorGrupoLGPD(){
      $data=[];
      $lgpd = new Lgpd();
      $dados = $lgpd->retornaDadosGestorGrupo($_POST);      
            foreach($dados as $key => $value ):
              $sub_dados= array();
              $sub_dados[] =$value['Grupo'];
              $sub_dados[] =$value['Gestor'];
              $sub_dados[] =$value['Usuarios'];
              $sub_dados[] =$value['Modulos'];
              $sub_dados[] =$value['Rotinas'];
              $sub_dados[] =$value['Transações'];
              $sub_dados[] =$value['Dados Pessoais'];
              $sub_dados[] =$value['Dados Sensiveis'];
              $sub_dados[] =$value['Dados Anonizados'];
              $sub_dados[] =$value['idGrupo'];
              $data[]=$sub_dados;
            endforeach;       

      echo json_encode($data);
    }

    public function ajaxCarregaDatatableGestorUsuarioLGPD(){
      $data=[];
      $lgpd = new Lgpd();
      $dados = $lgpd->retornarDadosGestorUsuario($_POST);        
            foreach($dados as $key => $value ):
              $sub_dados= array();
              $sub_dados[] =$value['Usuario'];
              $sub_dados[] =$value['Gestor'];               
              $sub_dados[] =$value['Modulos'];
              $sub_dados[] =$value['Grupos'];
              $sub_dados[] =$value['Rotinas'];
              $sub_dados[] =$value['Transações'];
              $sub_dados[] =$value['Dados Pessoais'];
              $sub_dados[] =$value['Dados Sensiveis'];
              $sub_dados[] =$value['Dados Anonizados'];
              $sub_dados[] =$value['idUsuario'];
              $data[]=$sub_dados;
            endforeach;       
  
      echo json_encode($data);
    }

    public function ajaxCarregaDatatableGestorTransacoesLGPD(){
        $data=[];
        $lgpd = new Lgpd();
        $dados = $lgpd->retornarDadosGestorTransacao($_POST); 
              foreach($dados as $key => $value ):
                $sub_dados= array();
                $sub_dados[] =$value['Programa'];
                $sub_dados[] =$value['Gestor'];               
                $sub_dados[] =$value['Usuarios'];
                $sub_dados[] =$value['Grupos'];
                $sub_dados[] =$value['Modulos'];
                $sub_dados[] =$value['Rotinas'];
                $sub_dados[] =$value['Dados Pessoais'];
                $sub_dados[] =$value['Dados Sensiveis'];
                $sub_dados[] =$value['Dados Anonizados'];
                $sub_dados[] =$value['Codigo'];
                $data[]=$sub_dados;
              endforeach;       
    
      echo json_encode($data);
    }

    public function ajaxCarregaDatatableGestorRotinasLGPD(){
        $data=[];
        $lgpd = new Lgpd();
        $dados = $lgpd->retornarDadosGestorRotina($_POST);
            foreach($dados as $key => $value ):
              $sub_dados= array();
              $sub_dados[] =$value['Rotinas'];
              $sub_dados[] =$value['Gestor'];               
              $sub_dados[] =$value['Usuarios'];
              $sub_dados[] =$value['Grupos'];
              $sub_dados[] =$value['Transacoes'];
              $sub_dados[] =$value['Modulos'];
              $sub_dados[] =$value['Dados Pessoais'];
              $sub_dados[] =$value['Dados Sensiveis'];
              $sub_dados[] =$value['Dados Anonizados'];
              $sub_dados[] =$value['Codigo'];
              $data[]=$sub_dados;
            endforeach;       
      
      echo json_encode($data);
    }
    
    public function ajaxCarregaDatatableGestorModulosLGPD(){
        $data=[];
        $lgpd = new Lgpd();
        $dados = $lgpd->retornarDadosGestorModulo($_POST);
            foreach($dados as $key => $value ):
              $sub_dados= array();
              $sub_dados[] =$value['Modulo'];
              $sub_dados[] =$value['Gestor'];               
              $sub_dados[] =$value['Usuarios'];
              $sub_dados[] =$value['Grupos'];
              $sub_dados[] =$value['Transacoes'];
              $sub_dados[] =$value['Rotinas'];
              $sub_dados[] =$value['Dados Pessoais'];
              $sub_dados[] =$value['Dados Sensiveis'];
              $sub_dados[] =$value['Dados Anonizados'];
              $sub_dados[] =$value['Codigo'];
              $data[]=$sub_dados;
            endforeach;       
        
      echo json_encode($data);
    }
      
}