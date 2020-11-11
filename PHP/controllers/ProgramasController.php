<?php
class ProgramasController extends Controller {

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
        $prog = new Programa();

       
        if(isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa']) ){
          $empresa = new Home();
          $empresaId = addslashes($_POST['empresa']);

          $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
          $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
          $_SESSION['empresaid'] = $empresaId;

            
            header('Location: '.URL);
        }

        if(isset($_POST['codProgramas']) && !empty($_POST['codProgramas'])){
            $idProg = addslashes($_POST['codProgramas']);
            $dados['dadosProg'] = $prog->carregaProgramas($idProg);
            $dados['grupoProg'] = $prog->carregaGrupo($idProg,$_SESSION['empresaid']);
            $dados['usuario'] = $prog->carregaUsuario($idProg,$_SESSION['empresaid']);
            $dados['descricaoProg'] = $prog->carregaDescProg($idProg,$_SESSION['empresaid']);
            $dados['camposPessoal'] = $prog->carregaCamposPessoal($idProg);
            $dados['camposSensivel'] = $prog->carregaCamposSensivel($idProg);
            $dados['camposAnonizado'] = $prog->carregaCamposAnonizado($idProg);
        }else{
            $dados['dadosProg'] = array();
            $dados['grupoProg'] = array();
            $dados['usuario'] = array();
            $dados['descricaoProg'] = array();
            $dados['camposPessoal'] = array();
            $dados['camposSensivel'] = array();
            $dados['camposAnonizado'] = array();
        }
       
    
        $this->loadTemplate('programas', $dados);
    }



    public function ajaxCarregaProg(){
      	$dados = array(); 
    
    	$idProg = $_POST['idProg'];

    	if(isset($idProg) && !empty($idProg) ){
	      	
	      	$g = new Programa();
	      	
	      $dados['dadosProg'] = $g->carregaProgramas($idProg);
		     foreach ($dados['dadosProg'] as $value) {
		     		 echo'<li onclick="carregaProg('."'".$value["cod_programa"]."'".')">'. utf8_encode($value['cod_programa']). ' - '.$value['descricao_programa'].'</li>';
		     }	
    	}
    }

    //TESTE COM NOVOS RECURSOS NA MODEL
    public function getModel()
    {
        $p = new Programa();

        $rs = $p->select([
            'p.idGrupo',
            'p.idPrograma',
            'p.cod_programa',
            'g.idGrupo',
            'g.idLegGrupo',
            'g.descAbrev',
            '(SELECT COUNT(idUsuario) FROM z_sga_grupos AS gp JOIN z_sga_usuarios AS u ON gp.idUsuario = z_sga_usuarios_id
                WHERE gp.idGrupo = g.idGrupo) AS totalUsuario'
        ])
            ->from('z_sga_grupo_programa AS p')
            ->join('z_sga_grupo AS g', 'g.idGrupo = p.idGrupo', '')
            ->where([
                ['p.cod_programa', '=', "'pd4000'"],
                ['g.idEmpresa', '=', "4"]
            ])
            ->where_or([
                ['p.cod_programa', '=', "'pd4000'"],
                ['g.idEmpresa', '=', "4"]
            ])
            ->like('p.cod_programa', 'PD4000', 'both')
            ->where_in('g.idEmpresa', [1,2,3,4])
            ->where_not_in('g.idEmpresa', [10,11,13,14])
            ->group_by('p.idGrupo')
            ->order_by('p.cod_programa', 'ASC')
            ->limit(0, 1)
            ->get();

        $this->helper->debug($rs, true);
    }



}

