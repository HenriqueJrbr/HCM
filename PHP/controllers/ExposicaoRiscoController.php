<?php
class ExposicaoRiscoController extends Controller {
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
        $dados = array(); 
       
       	$g = new Grupo();
       	$dados['carregaGrupo'] = $g->carregaGrupo($_SESSION['empresaid']);
     
        $this->loadTemplate('gestor_usuario_risco', $dados);
    }

    public function gestor(){

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
       

       	$dados['carregaGrupo'] = $g->carregaGrupo($_SESSION['empresaid']);
        $risco = new ExposicaoRisco();

        $dados['gestor'] = $risco->carregaGestor();
     
        $this->loadTemplate('gestor_usuario_risco', $dados);

    }

    public function gestor_usuario_exposicao($id){


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
        $dados['carregaGrupo'] = $g->carregaGrupo($_SESSION['empresaid']);

        $risco = new ExposicaoRisco();

        $dados['usuarios'] = $risco->carregaUsuariosGestor($id);
        $dados['codUsuario'] = $risco->carregaDadosGestor($id);

        $this->loadTemplate('gestor_usuario_exposicao_risco', $dados);

    }

    public function usuario_risco($id){

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
       

       	$dados['carregaGrupo'] = $g->carregaGrupo($_SESSION['empresaid']);


        $risco = new ExposicaoRisco();

        $dados['risco'] = $risco->carregaConflito($id);

        $this->loadTemplate('exposicao_usuario_risco', $dados);
    }

    public function usuarioExposto(){

        
        if(isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa']) ){
          $empresa = new Home();
          $empresaId = addslashes($_POST['empresa']);

          $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
          $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
          $_SESSION['empresaid'] = $empresaId;

            
            header('Location: '.URL);
        }
        $dados = array(); 
       
       
        $home = new Home();

         //$risco = new ExposicaoRisco();
         //$dados['risco'] = $risco->carregaRiscoUsuario();
        
         $this->loadTemplate('usuario_esposto_risco', $dados);
    }

    public function ajaxCarregaGestor(){
        $dados = array(); 
    
        $idGestor = $_POST['idGestor'];

        if(isset($idGestor) && !empty($idGestor) ){
            
            $g = new ExposicaoRisco();
                
            $dados['dadosProg'] = $g->carregaGestorPesquisa($idGestor);
            foreach ($dados['dadosProg'] as $value) {
                     echo'<li onclick="carregaGestorPesq('."'".$value["cod_usuario"]."'".')">'. utf8_encode($value['nome_usuario']). ' - '.utf8_encode($value['cod_usuario']) .'</li>';
            }  
        }
    }

    public function ajaxCarregaUsuario(){
        $dados = array(); 
    
        $idUsuario = $_POST['idUsuario'];
        $idGestor = $_POST['idGestor'];

        if(isset($idUsuario) && !empty($idUsuario) ){
            
            $g = new ExposicaoRisco();
                
            $dados['dadosProg'] = $g->carregausuarioPesquisa($idUsuario,$idGestor);
            foreach ($dados['dadosProg'] as $value) {
                     echo'<li onclick="carregaUsuarioPesq('."'".$value["cod_usuario"]."'".')">'. utf8_encode($value['nome_usuario']). ' - '.utf8_encode($value['cod_usuario']) .'</li>';
            }  
        }
    }



    /**
     * método para criação de jquery datatable
     */
    public function ajaxDatatableRiscoUsuarios()
    {        
        $dados = array();
        $data = array();
        $e = new ExposicaoRisco();


        // usado pelo order by
        $fields = array(
            0   => 'base.nome_usuario',
            1   => 'base.descArea',
            2   => 'base.codrisco',
            3   => 'base.descRisco',
            4   => 'CombinacoesDoRisco',
            5   => 'ativo',
            6   => 'mitigado'
        );

        // Variaveis usadas na paginação do Jquery DataTable
        $search = (isset($_POST["search"]["value"])) ? $_POST["search"]["value"] : '';
        $orderColumn = (isset($_POST['order']['0']['column'])) ? $fields[$_POST['order']['0']['column']] : '';
        $orderDir = (isset($_POST['order']['0']['dir'])) ? $_POST['order']['0']['dir'] : 'ASC';
        $limit = (isset($_POST["length"]) && $_POST["length"] != -1) ? $_POST['length'] : '10';
        $offset = (isset($_POST["length"]) && $_POST["length"] != -1) ? $_POST['start'] : '0';

        $join = "";


        // Traz apenas 10 usuarios utilizando paginação
        $dados = $e->carregaDatatableRiscoUsuario($search, $orderColumn, $orderDir, $offset, $limit);

        // Traz todos o total de registros
        $total_all_records = $e->getCountTableUsrs("v_sga_mtz_resumo_matriz_usuario", $search, $fields, $join, $_SESSION['empresaid']);
        // cria linhas customizadas no jquery datatable.
        if (count($dados) > 0):
            foreach ($dados as $key => $value):
                $sub_dados = array();
                $sub_dados[] = $value['nome_usuario'];
                $sub_dados[] = utf8_decode($value['descArea']);
                $sub_dados[] = $value['codrisco'];
                $sub_dados[] = $value['descRisco'];
                $sub_dados[] = round($value['CombinacoesDoRisco'])."%";
                $sub_dados[] = '<span class="badge label-primary">'.$value['ativo'].'</span>';
                $sub_dados[] = '<span class="badge" style="background-color:'.(($value['mitigado'] == 'Mitigado') ? '#26B99A' : '#d9534f' ).'">'.$value['mitigado'].'</span>';
                $sub_dados[] = '<button type="button" class="btn btn-success btn-xs" data-toggle="modal" onclick="location.href=\'' . URL . '/Usuario/dados_usuario/' . $value['idUsuario'] . '\'">Visualizar</button>';
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
