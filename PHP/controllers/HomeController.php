<?php
class HomeController extends Controller {


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
        $empresa = new Home();
            
        $usuario = $empresa->getdadosUsuario($_SESSION['idUsrTotvs']);
        
        
                

        if(isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa']) ){
        	$empresaId = addslashes($_POST['empresa']);

        	$dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
        	$_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
        	$_SESSION['empresaid'] = $empresaId;
            
            header('Location: '.URL);
        }
       
        if($usuario['gestor_usuario'] != 'S' && $usuario['gestor_grupo'] != 'S' && $usuario['gestor_programa'] != 'S'):
            $this->loadTemplate('home_usuario_comum', []);
        else:
            $dados['contaUsuarioTotal'] = $empresa->contaUsuarioTotal($_SESSION['empresaid']);
            $dados['contUsuario'] = $empresa->contaUsuario($_SESSION['empresaid']);
            $dados['contUsuarioInativo'] = $empresa->contaUsuarioInativos($_SESSION['empresaid']);
            $dados['contGrupo'] = $empresa->contaGrupo($_SESSION['empresaid']);
            $dados['contPrograma'] = $empresa->contaPrograma($_SESSION['empresaid']);
            //$dados['topGrupo'] = $empresa->carregaGrafTopGrupo();
            $dados['TotalexpostoRisco'] = $empresa->totalExpostoRisco();
            $dados['TotalexpostoRiscoInativo'] = $empresa->totalExpostoRiscoInativo();
            //$dados['gestorComMaiorPotencialRisco'] = $empresa->gestorComMaiorPotencialRisco();
            //$dados['riscosMitigadosVsNaoMitigados'] = $empresa->riscosMitigadosVsNaoMitigados();
            //$dados['processosMaisPopulosos'] = $empresa->processosMaisPopulosos();
            //$dados['areaMaiorPotencialRisco'] = $empresa->areaMaiorPotencialRisco();
            //$dados['riscosEmPotencial'] = $empresa->riscosEmPotencial();
            $this->loadTemplate('home', $dados);
        endif;
        
        //$this->loadTemplate('home', $dados);
    }


    public function ajaxGruposMaiorNumeroUsuarios()
    {
        $empresa = new Home();
        $topGrupo = $empresa->carregaGrafTopGrupo();
        
        $grupoTop = array();
        $grupoTopTotal = array();
        foreach($topGrupo as $grupo){
            $grupoTop[] = str_replace($grupo['idLegGrupo'], "'".$grupo['idLegGrupo']. "'", $grupo['idLegGrupo']);
            //$grupoTop[] = $grupo['descAbrev'];
            $grupoTopTotal[] = $grupo['totalUsuario'];
        }       
        
        echo json_encode(array(
            'grupoTop'      => $grupoTop,
            'grupoTopTotal' => $grupoTopTotal
        ));
    }

    public function ajaxGruposMaiorNumeroUsuariosFoto()
    {
        $empresa = new Home();
        $topGrupo = $empresa->carregaGrafTopGrupoFoto();
        
        $grupoTop = array();
        $grupoTopTotal = array();
        foreach($topGrupo as $grupo){
            $grupoTop[] = str_replace($grupo['idLegGrupo'], "'".$grupo['idLegGrupo']. "'", $grupo['idLegGrupo']);
            //$grupoTop[] = $grupo['descAbrev'];
            $grupoTopTotal[] = $grupo['totalUsuario'];
        }       
        
        echo json_encode(array(
            'grupoTop'      => $grupoTop,
            'grupoTopTotal' => $grupoTopTotal
        ));
    }

    
    
    public function ajaxUsuariosVsRiscos()
    {
        $empresa = new Home();
        $totalexpostoRisco = $empresa->totalExpostoRisco();
        $contUsuario = $empresa->contaUsuario($_SESSION['empresaid']);                     
        
        echo json_encode(array(
            'data'      => [(($contUsuario[0] - $totalexpostoRisco['expostoArisco']) <= 0) ? 0 : ($contUsuario[0] - $totalexpostoRisco['expostoArisco']), $totalexpostoRisco['expostoArisco']]
        ));
    }

    public function ajaxUsuariosVsRiscosFoto()
    {
        $empresa = new Home();
        $totalexpostoRisco = $empresa->totalExpostoRiscoFoto();
        $contUsuario = $empresa->contaUsuario($_SESSION['empresaid']);                     
        
        echo json_encode(array(
            'data'      => [(($contUsuario[0] - $totalexpostoRisco['expostoArisco']) <= 0) ? 0 : ($contUsuario[0] - $totalexpostoRisco['expostoArisco']), $totalexpostoRisco['expostoArisco']]
        ));
    }

    
    public function ajaxAreaUsuariosRiscosNaoMitigados()
    {
        $empresa = new Home();
        $areaMaiorPotencialRisco = $empresa->areaMaiorPotencialRisco();
        
        // Prepara os dados das areas com maior potencial de riscos
        $areaTop = array();
        $areaTopTotal = array();
        foreach($areaMaiorPotencialRisco as $area){
            $areaTop[] = $area['descricao'];
            $areaTopTotal[] = $area['numUsuarios'];
        }                 
        echo json_encode(array(
            'areaTop'      => $areaTop,
            'areaTopTotal' => $areaTopTotal
        ));
    }

    public function ajaxAreaUsuariosRiscosNaoMitigadosFoto()
    {
        $empresa = new Home();
        $areaMaiorPotencialRisco = $empresa->areaMaiorPotencialRiscoFoto();
        
        // Prepara os dados das areas com maior potencial de riscos
        $areaTop = array();
        $areaTopTotal = array();
        foreach($areaMaiorPotencialRisco as $area){
            $areaTop[] = $area['descricao'];
            $areaTopTotal[] = $area['numUsuarios'];
        }                 
        echo json_encode(array(
            'areaTop'      => $areaTop,
            'areaTopTotal' => $areaTopTotal
        ));
    }
    
    // Não tem foto
    public function ajaxRiscosMitigadosVsNaoMitigados()
    {
        $empresa = new Home();
        $riscosMitigadosVsNaoMitigados = $empresa->riscosMitigadosVsNaoMitigados();                            
        
        echo json_encode(array(
            'data'      => [$riscosMitigadosVsNaoMitigados['riscoMitigados'], $riscosMitigadosVsNaoMitigados['riscoNaoMitigados']]
        ));
    }
    

    public function ajaxProcessosMaisPopulosos()
    {
        $empresa = new Home();
        $processosMaisPopulosos = $empresa->processosMaisPopulosos();
        
        // Prepara os dados dos processos com mais usuários
        $processosTop = array();
        $processosTopTotal = array();
        foreach($processosMaisPopulosos as $processos){
            $processosTop[] = $processos['descProcesso'];
            $processosTopTotal[] = $processos['numUsuarios'];
        }
       
        echo json_encode(array(
            'processosTop'      => $processosTop,
            'processosTopTotal' => $processosTopTotal
        ));
    }

    public function ajaxProcessosMaisPopulososFoto()
    {
        $empresa = new Home();
        $processosMaisPopulosos = $empresa->processosMaisPopulososFoto();
        
        // Prepara os dados dos processos com mais usuários
        $processosTop = array();
        $processosTopTotal = array();
        foreach($processosMaisPopulosos as $processos){
            $processosTop[] = $processos['descProcesso'];
            $processosTopTotal[] = $processos['numUsuarios'];
        }
       
        echo json_encode(array(
            'processosTop'      => $processosTop,
            'processosTopTotal' => $processosTopTotal
        ));
    }
    
    public function ajaxTopFiveGestoresComMaisRiscos()
    {
        $empresa = new Home();
        $gestorComMaiorPotencialRisco = $empresa->gestorComMaiorPotencialRisco();
        
        // Prepara os dados dos gestores com maior potencial de riscos
        $gestorTop = array();
        $gestorTopTotal = array();
        foreach($gestorComMaiorPotencialRisco as $gestor){
            $gestorTop[] = $gestor['nome_usuario'];
            $gestorTopTotal[] = $gestor['numUsuarios'];
        }        
       
        echo json_encode(array(
            'gestorTop'      => $gestorTop,
            'gestorTopTotal' => $gestorTopTotal
        ));
    }

    public function ajaxTopFiveGestoresComMaisRiscosFoto()
    {
        $empresa = new Home();
        $gestorComMaiorPotencialRisco = $empresa->gestorComMaiorPotencialRiscoFoto();
        
        // Prepara os dados dos gestores com maior potencial de riscos
        $gestorTop = array();
        $gestorTopTotal = array();
        foreach($gestorComMaiorPotencialRisco as $gestor){
            $gestorTop[] = $gestor['nome_usuario'];
            $gestorTopTotal[] = $gestor['numUsuarios'];
        }        
       
        echo json_encode(array(
            'gestorTop'      => $gestorTop,
            'gestorTopTotal' => $gestorTopTotal
        ));
    }
    
    public function ajaxRiscosEmPotencial()
    {
        $empresa = new Home();
        $riscosEmPotencial = $empresa->riscosEmPotencial();
        
        // Prepara os riscos com maior potencial
        $riscosTop = array();
        $riscosTopTotal = array();
        foreach($riscosEmPotencial as $riscos){
            $riscosTop[] = $riscos['codRisco'];
            $riscosTopTotal[] = $riscos['numUsuarios'];
        }
        
        echo json_encode(array(
            'riscosTop'      => $riscosTop,
            'riscosTopTotal' => $riscosTopTotal
        ));
    }

    public function ajaxRiscosEmPotencialFoto()
    {
        $empresa = new Home();
        $riscosEmPotencial = $empresa->riscosEmPotencialFoto();
        
        // Prepara os riscos com maior potencial
        $riscosTop = array();
        $riscosTopTotal = array();
        foreach($riscosEmPotencial as $riscos){
            $riscosTop[] = $riscos['codRisco'];
            $riscosTopTotal[] = $riscos['numUsuarios'];
        }
        
        echo json_encode(array(
            'riscosTop'      => $riscosTop,
            'riscosTopTotal' => $riscosTopTotal
        ));
    }
    
    public function ajaxNotificacao(){
        $dados = array();
        $f = new Fluxo();
        $dados['atividades'] = $f->carregaAtividade($_SESSION['idUsrTotvs']);

        echo json_encode($dados['atividades']);
    }

    public function favoritar()
    {
        $modal = new Home();
        $return = $modal->favoritar($_POST['idUsuario'], $_POST['idMenu']);

        if ($return) {
            echo json_encode(array(
                "return" => $return['return'],
                "isFavorite" => $return['isFavorite']
            ));
        } else {
            echo json_encode(array("return" => false));
        }
    }
}