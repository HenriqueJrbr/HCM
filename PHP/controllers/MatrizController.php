<?php
class MatrizController extends Controller {

    public function __construct() {
        parent::__construct();
         $login = new Login();
        
        if(!$login->islogin()){
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

        $home = new Home();
        if(isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa'])){
            $empresaId = addslashes($_POST['empresa']);
            
            $dados['descEmpresa'] = $home->carregaDescEmpresa($empresaId);
            $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
            $_SESSION['empresaid'] = $empresaId;
            
            header('Location: '.URL);
            //$this->loadTemplate('home', $dados);
        }

        $m = new Matriz();


        if(isset($_POST['addRiscoModal']) && $_POST['addRiscoModal'] == "Salvar"){
            if(isset($_POST['riscoModal']) && !empty($_POST['riscoModal']) && isset($_POST['descricaoModal']) && !empty($_POST['descricaoModal']) && isset($_POST['aplicativoModal']) && !empty($_POST['aplicativoModal'])){

                $codConflito = addslashes($_POST['riscoModal']);
                $descConflito = addslashes($_POST['descricaoModal']);
                $appMain = addslashes($_POST['aplicativoModal']);
                $appMainDesc = addslashes($_POST['aplicativoDescModal']);
                $appMainObs = addslashes($_POST['obsModal']);
                $dados['idTabela'] = $m->addRisco($codConflito,$descConflito,$appMain,$appMainDesc,$appMainObs);

                header('Location: '.URL.'/Matriz/dados_riscos/'.$dados['idTabela'][0]);
            }
        }

        //$dados['matriz'] = $m->carregaMatriz();
         $this->loadTemplate('matriz', $dados);
    }

    public function dados_riscos($id){
        $dados = array();
        $c = new Matriz();


        if(isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa']) ){
          $empresa = new Home();
          $empresaId = addslashes($_POST['empresa']);

          $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
          $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
          $_SESSION['empresaid'] = $empresaId;

            
            header('Location: '.URL);
        }


        if(isset($_POST['salvarEditas']) && $_POST['salvarEditas'] == "Salvar"){
            
        }
        

        if(isset($_POST['salvarConflito']) && $_POST['salvarConflito'] == "Salvar Conflito"){
            if(isset($_POST['addAplicativo']) && !empty($_POST['addAplicativo'])){
                
                $idRisco = addslashes($_POST['codRisco']);
                $aplicativo = addslashes($_POST['addAplicativo']);
                $addDescricao = addslashes($_POST['addDescricao']);
                $addRisco = addslashes($_POST['addRisco']);

                if($c->validaPrograma($aplicativo) == 0 || $c->validaPrograma($aplicativo) == false){
                    echo "<script>alert('Programa  não existe')</script>";
                }else{
                    $c->salvaEditaConflito($idRisco,$aplicativo,$addDescricao,$addRisco);
                }   
            }
        }

        $dados['conflito'] = $c->carregaConflito($id);
        $dados['risco'] = $c->carregaRisco($id);
       

        $this->loadTemplate('dados_risco',$dados);
    }


    public function ajaxExcluiConflito(){
        $id = $_POST['id'];
        $e = new Matriz();
        $e->exluirConflito($id);
    }



     public function ajaxCarregaProg(){
        $dados = array(); 
    
        $idProg = $_POST['idProg'];

        if(isset($idProg) && !empty($idProg) ){
            
            $g = new Programa();
            $dados['dadosProg'] = $g->carregaProgramas($idProg);
         
             foreach ($dados['dadosProg'] as $value) {
                     echo'<li onclick="carregaProgConf('."'".$value["cod_programa"]."'".","."'".$value["descricao_programa"]."'".')">'. $value['cod_programa']. ' - '.$value['descricao_programa'] .'</li>';
             }  
        }
    }


    public function ajaxCarregaProgAddRiscoModal(){
        $dados = array(); 
    
        $idProg = $_POST['idProg'];

        if(isset($idProg) && !empty($idProg) ){
            
            $g = new Programa();
            $dados['dadosProg'] = $g->carregaProgramas($idProg);
         
             foreach ($dados['dadosProg'] as $value) {
                     echo'<li onclick="carregaProgAddRiscoModal('."'".$value["cod_programa"]."'".","."'".$value["cod_programa"]."'".","."'".$value["descricao_programa"]."'".')">'. $value['cod_programa']. ' - '.$value['descricao_programa'] .'</li>';
             }  
        }
    }

    public function ajaxCarregaProgProcesso(){
        $dados = array(); 
    
        $idProg = $_POST['idProg'];

        if(isset($idProg) && !empty($idProg) ){
            
            $m = new Matriz();
            $dados['dadosProg'] = $m->carregaProgramas($idProg);
         
             foreach ($dados['dadosProg'] as $value) {
                     echo'<li onclick="carregaProgProcesso('."'".$value["z_sga_programas_id"]."'".","."'".$value["cod_programa"]."'".","."'".$value["descricao_programa"]."'".')">'.$value['cod_programa']. ' - '.$value['descricao_programa'] .'</li>';
             }  
        }
    }

    public function ajaxaCarregaProcessoCorrelato(){
        $dados = array(); 
    
        $codProdCorrelato = $_POST['codProdCorrelato'];
    
        if(isset($codProdCorrelato) && !empty($codProdCorrelato) ){
            
            $m = new Matriz();
            $dados['processoCorrlato'] = $m->carregaProcessoCorrelato($codProdCorrelato);
             foreach ($dados['processoCorrlato'] as $value) {
                     echo'<li onclick="carregaProcessoCorrelatos('."'".$value["idProcesso"]."'".","."'".str_replace('\r', "", str_replace("\n", "", trim($value["descProcesso"])))."'".')">'.$value['codRisco']. ' - '.$value['descProcesso'] .'</li>';
             } 
            
             
        }
    }


    public function cadastroDeRisco(){


        if(isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa']) ){
          $empresa = new Home();
          $empresaId = addslashes($_POST['empresa']);

          $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
          $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
          $_SESSION['empresaid'] = $empresaId;

            header('Location: '.URL);
        }

        $dados = array();   

        $matriz = new Matriz();

        if(isset($_POST['salvar']) && $_POST['salvar'] == "Salvar"){
          if(isset($_POST['risco']) && !empty($_POST['risco']) && isset($_POST['descricao']) && !empty($_POST['descricao'])){
              $risco = addslashes($_POST['risco']);
              $area = addslashes($_POST['area']);
              $descricao = addslashes($_POST['descricao']);

              if(!empty($risco) && !empty($area) && !empty($descricao)){
                $retorno =  $matriz->cadastroMatriz($risco,$descricao,$area);
                if($retorno >0){
                  $_SESSION['msg']['success'] = 'Risco cadastrado com sucesso';
                  unset($_POST['salvar']);
                }else{
                  $_SESSION['msg']['error'] = 'Erro ao cadastrar risco';
                }
              }else{
                $_SESSION['msg']['error'] = 'Todos os campos são obrigatórios';
                unset($_POST['salvar']);
              }
        
              
          }
        }
      

        if(isset($_POST['salvarEditar']) && $_POST['salvarEditar'] == "Salvar" ){
            $idRiscoEditar = addslashes($_POST['idRiscoEditar']);
            $riscoEditar = addslashes($_POST['riscoEditar']);
            $areaEditar = addslashes($_POST['areaEditar']);
            $descricaoEditar = addslashes($_POST['descricaoEditar']);
          
            $matriz->atualizaMatriz($idRiscoEditar,$riscoEditar,$areaEditar,$descricaoEditar);
        }

        $dados['matriz'] =  $matriz->carregaCadastroMatriz();
        $dados['area'] = $matriz->carregaArea();

         $this->loadTemplate('cadastro_risco',$dados);
    }


    public function carregaMatrizRisco(){

        if(isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa']) ){
          $empresa = new Home();
          $empresaId = addslashes($_POST['empresa']);

          $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
          $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
          $_SESSION['empresaid'] = $empresaId;

          header('Location: '.URL);
        }
        $dados = array();   

        $matriz = new Matriz();

        
        $dados['matrizDeRisco'] =  $matriz->carregaMatrizDeRisco();

         $this->loadTemplate('matriz_de_Risco',$dados);
    }

    public function cadastroArea(){

        if(isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa']) ){
          $empresa = new Home();
          $empresaId = addslashes($_POST['empresa']);

          $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
          $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
          $_SESSION['empresaid'] = $empresaId;

            
            header('Location: '.URL);
        }
        $dados = array();
        $idEmpresa =   $_SESSION['empresaid']; 

        $matriz = new Matriz();
        $usr = new Usuario();


        if(isset($_POST['salvarArea']) && $_POST['salvarArea'] == "Salvar"){
            $area = $_POST['area'];
            $responsavel = $_POST['responsavel'];

            if(isset($area) && !empty($area) && isset($area) && !empty($area)){
               $retorno =  $matriz->cadastraArea($area,$responsavel);
              if($retorno > 0){
                  $_SESSION['msg']['success'] = 'Área cadastrado com sucesso';
              }else{
                 $_SESSION['msg']['error'] = 'Erro ao cadastrar';
              }
            }else{
               $_SESSION['msg']['error'] = 'Todos os campos são obrigatórios';
            }
           
        }

        if(isset($_POST['salvarAtualiza']) && $_POST['salvarAtualiza'] == "Salvar"){
            $areaEdit = $_POST['areaEdit'];
            $idAreaEdit = $_POST['idAreaEdit'];
            $responsavelEdit = $_POST['responsavelEdit'];
            $matriz->atualizaArea($areaEdit,$idAreaEdit,$responsavelEdit);

        }

        $dados['area'] = $matriz->carregaArea();
        $dados['respon'] = $usr->carregaUsuario($idEmpresa, '');

        $this->loadTemplate('cadastro_area',$dados);
    }

    public function cadastroMitigacao(){

        if(isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa']) ){
          $empresa = new Home();
          $empresaId = addslashes($_POST['empresa']);

          $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
          $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
          $_SESSION['empresaid'] = $empresaId;

            
            header('Location: '.URL);
        }
        $dados = array();
        $idEmpresa =   $_SESSION['empresaid']; 
        $matriz = new Matriz();

        if(isset($_POST['salvar']) && $_POST['salvar'] == "Salvar"){
            $mitigacao = addslashes($_POST['mitigacao']);
            $descMedigacao = addslashes($_POST['descMedigacao']);
            $arquivo = $_FILES['documentoMedigacao'];

            $dados['id'] = $matriz->cadastraMitigacao($mitigacao,$descMedigacao);

            if(isset($arquivo['tmp_name']) && !empty($arquivo['tmp_name'])){
                $nomeDoArquivo = md5(time()).rand(0,99);
                move_uploaded_file($arquivo['tmp_name'], './arquivos/'.$nomeDoArquivo.$arquivo['name']);

                $matriz->cadastraArquivo($arquivo['name'],$dados['id']['id'],$nomeDoArquivo.$arquivo['name']);
            }

            header('Location: '.URL.'/Matriz/editar_mitigacao/'.$dados['id']['id']);
        }

       

        $dados['mit'] = $matriz->carregaMitigacao();
        $this->loadTemplate('cadastro_mitigacao',$dados);
    }

    public function editar_mitigacao($id){
      $dados = array();
      $matriz = new Matriz();

     if(isset($_POST['salvarEdit']) && $_POST['salvarEdit'] == "Salvar"){
        
          $mitigacao = addslashes($_POST['mitigacaoEdit']);
          $descMedigacao = addslashes($_POST['descMedigacaoEdit']);
          $arquivo = $_FILES['documentoMedigacaoEdit'];
          $idMitigacao = addslashes($_POST['idMitigacao']);

          $matriz->updateMitigacao($mitigacao,$descMedigacao,$id);


          if(isset($arquivo['tmp_name']) && !empty($arquivo['tmp_name'])){
              $nomeDoArquivo = md5(time()).rand(0,99);
              move_uploaded_file($arquivo['tmp_name'], './arquivos/'.$nomeDoArquivo.$arquivo['name']);

              $matriz->cadastraArquivo($arquivo['name'],$id,$nomeDoArquivo.$arquivo['name']);
          }

          header('Location: '.URL.'/Matriz/editar_mitigacao/'.$id);

      }

      if(isset($_POST['addMitigaRisco']) && $_POST['addMitigaRisco'] == "Adicionar Risco"){

            $idCodRiscoMitiga = $_POST['idCodRiscoMitiga'];
            $idMitigacao = $_POST['idMitigacao'];

            if(isset($idCodRiscoMitiga) && isset($idMitigacao)){
                $matriz->ajaxCadastraRiscoMitiga($idCodRiscoMitiga,$id);
            }

            header('Location: '.URL.'/Matriz/editar_mitigacao/'.$id);
        }
      $dados['dadosRisco'] = $matriz->ajaxCarregaTabelaRiscoMitiga($id);
      $dados['dadosMitiga'] = $matriz->carregaMitigacaoAjax($id);
      $dados['mitiga2'] = $matriz->carregaMitigacaoDocumentoAjax($id);
      $dados['idMitigacao'] = $id;


      $this->loadTemplate('editar_midigacao',$dados);
    }

    public function cadastroProcesso(){

        if(isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa']) ){
          $empresa = new Home();
          $empresaId = addslashes($_POST['empresa']);

          $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
          $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
          $_SESSION['empresaid'] = $empresaId;
            header('Location: '.URL);
        }

        $dados = array();
        $m = new Matriz();

        if(isset($_POST['codIdRisco']) && !empty($_POST['codIdRisco']) && isset($_POST['codGrupoProcesso']) && !empty($_POST['codGrupoProcesso']) && isset($_POST['codGrauRisco']) && !empty($_POST['codGrauRisco'])){

            $codIdRisco = $_POST['codIdRisco'];
            $codGrupoProcesso = $_POST['codGrupoProcesso'];
            $codGrauRisco = $_POST['codGrauRisco'];
            $descricaoRisco = $_POST['descricaoRisco'];
            $dados['UltimoRegistro'] = $m->cadatraMtzProcesso($codIdRisco,$codGrupoProcesso,$codGrauRisco,$descricaoRisco );

            header('Location: '.URL.'/Matriz/dados_Processo/'.$dados['UltimoRegistro'][0]);
           
        }
        $dados['processo'] = $m->carregaProcessoTabela();


        $this->loadTemplate('cadastro_processo',$dados);   
    }

    public function dados_Processo($id){

        if(isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa']) ){
          $empresa = new Home();
          $empresaId = addslashes($_POST['empresa']);

          $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
          $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
          $_SESSION['empresaid'] = $empresaId;

            
            header('Location: '.URL);
        }
        $dados = array();
        $m = new Matriz();

        if(isset($_POST['processoSalvaPrograma']) && $_POST['processoSalvaPrograma'] == "Salvar"){
            if(isset($_POST['idProcessoPrograma']) && !empty($_POST['idProcessoPrograma']) && isset($_POST['processoProgramaDesc']) && !empty($_POST['processoProgramaDesc'])){
                $idProcessoPrograma = $_POST['idProcessoPrograma'];
                $m->cadastrarProgProcesso($idProcessoPrograma,$id);

            }
        }

        if(isset($_POST['processoCorrelatoSalva']) && $_POST['processoCorrelatoSalva'] == "Salvar"){
            if(isset($_POST['idProcessoCorrelato']) && !empty($_POST['idProcessoCorrelato']) && isset($_POST['idGrauCorrelato']) && !empty($_POST['idGrauCorrelato'])){
                $idProcessoCorrelato = $_POST['idProcessoCorrelato'];
                $idGrauCorrelato = $_POST['idGrauCorrelato'];                
                $res = $m->cadastrarProcessoCorrelatos($idProcessoCorrelato,$idGrauCorrelato,$id);
                
                /*if($res['return']):
                    $this->helper->setAlert(
                        'success',
                        'Processo relacionado com sucesso'
                    );
                else:
                    $this->helper->setAlert(
                        'error',
                        'Erro ao relacionar processo. \n ' . $res['error']
                    );
                endif;*/
            }
        }

        $dados['ProgProcessos'] =  $m->carregaProgProcesso($id);
        $dados['processo'] = $m->carregaProcesso($id);
        $dados['processoCoorelato'] = $m->carregaProcessoCorrelatoTabela($id);
        $dados['idProcesso'] = $id;
        $this->loadTemplate('dados_processo',$dados);

    }

    public function cadastroGrupoProcesso(){

        if(isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa']) ){
          $empresa = new Home();
          $empresaId = addslashes($_POST['empresa']);

          $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
          $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
          $_SESSION['empresaid'] = $empresaId;  
            header('Location: '.URL);
        }

        $dados = array();
        $m = new Matriz();


        if(isset($_POST['salvarProcesso']) && $_POST['salvarProcesso'] == "Salvar"){
          if(isset($_POST['descricaoGrupo']) && !empty($_POST['descricaoGrupo'])){
              $descricaoGrupo = $_POST['descricaoGrupo'];
              $retorno = $m->cadastraGrupoProcesso($descricaoGrupo);

              if($retorno > 0){
                 $_SESSION['msg']['success'] = 'Grupo de processo cadastrado sucesso';
                 unset($_POST['salvarProcesso']);
              }else{
                $_SESSION['msg']['error'] = 'Erro ao cadastrar grupo de processo';
              }   
          }else{
            $_SESSION['msg']['error'] = 'Todos os campos são obrigatórios';
          }
        }
      



        if(isset($_POST['descricaoGrupoModal']) && !empty($_POST['descricaoGrupoModal']) && isset($_POST['idDescricaoGrupoModal']) && !empty($_POST['idDescricaoGrupoModal'])){
            $descricaoGrupoModal = $_POST['descricaoGrupoModal'];
            $idDescricaoGrupoModal = $_POST['idDescricaoGrupoModal'];
            $m->atualizaGrupoProcesso($descricaoGrupoModal,$idDescricaoGrupoModal);
        }

        $dados['grupoProcesso'] = $m->carregaCadstroGrupoProcesso();
        $this->loadTemplate('cadastro_grupo_processo',$dados);

    }

    public function cadastroGrau(){
        
        if(isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa']) ){
          $empresa = new Home();
          $empresaId = addslashes($_POST['empresa']);

          $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
          $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
          $_SESSION['empresaid'] = $empresaId;

            
            header('Location: '.URL);
        }
        $dados = array();
        $m = new Matriz();
        
        if(isset($_POST['salvarEdit']) && $_POST['salvarEdit'] = "Salvar" ){
            $descricao = addslashes($_POST['descricaoModal']);
            $background = addslashes($_POST['backgroundModal']);
            $texto = addslashes($_POST['textoModal']);
            $idModal = addslashes($_POST['idModal']);

            if(!empty($descricao) && !empty($background) && !empty($texto) ){
              $retorno = $m->editaGrau($descricao,$background,$texto,$idModal);
              if($retorno > 0){
                $_SESSION['msg']['success'] = 'Grau alterado com sucesso';
              }
            }else{
              $_SESSION['msg']['error'] = 'Todos os campos são obrigatórios';
            }
            
        }

        if(isset($_POST['salvar']) && $_POST['salvar'] = "Salvar" ){
            $descricao = addslashes($_POST['descricao']);
            $background = addslashes($_POST['background']);
            $texto = addslashes($_POST['texto']);

            if(!empty($descricao) && !empty($background) && !empty($texto) ){
              $retorno = $m->cadastraGrau($descricao,$background,$texto);
              if($retorno > 0){
                $_SESSION['msg']['success'] = 'Grau cadastrado com sucesso';
              }
            }else{
              $_SESSION['msg']['error'] = 'Todos os campos são obrigatórios';
            }
            
        }

        $dados['grau'] = $m->carregaGrau();
        $this->loadTemplate("cadastro_grau",$dados);
    }

    /**
     * Exclui Grau no menu Matriz de risco
     */
    public function ajaxExcluiGrauRisco(){
        $m = new Matriz();
        if(isset($_POST) && count($_POST) > 0):
            $idGrauRisco = $_POST['idGrauRisco'];

            $result = $m->excluiGrauRisco($idGrauRisco);

            if($result['return']):
                if($result['return'] == true):
                    $_SESSION['msg']['success'] = 'Grau excluído com sucesso';
                else:
                    $_SESSION['msg']['error'] = "Erro ao excluir Grau\n".$result['error'];
                endif;
            endif;
        endif;
    }

    /**
     * Exclui Area no menu Matriz de risco
     */
    public function ajaxExcluiAreaRisco()
    {
        $m = new Matriz();
        if(isset($_POST) && count($_POST) > 0):
            $idAreaRisco = $_POST['idAreaRisco'];

            $result = $m->excluiAreaRisco($idAreaRisco);

            if($result['return']):
                if($result['return'] == true):
                    $_SESSION['msg']['success'] = 'Área excluído com sucesso';
                    die(true);
                else:
                    $_SESSION['msg']['error'] = "Erro ao excluir Área\n".$result['error'];
                    die(false);
                endif;
            endif;
        endif;
    }

    public function ajaxCarregaRisco(){
        $dados = array(); 
    
        $codRisco = $_POST['codRisco'];

        if(isset($codRisco) && !empty($codRisco) ){
            
            $m = new Matriz();
            $dados['codRisco'] = $m->carregaMtzRisco($codRisco);
         
             foreach ($dados['codRisco'] as $value) {
                     echo'<li onclick="carregaMtzRisco('."'".$value["idMtzRisco"]."'".","."'".$value["codRisco"]."'".')">'.$value['codRisco']. ' - '.$value['descricao'] .'</li>';
             }  
        }

    }

    public function ajaxCarregaRiscoDesc(){
        $dados = array();
        $idMtzRisco = $_POST['idMtzRisco'];
        $m = new Matriz();

        $dados['descRisco'] = $m->carregaMtzRiscoDesc($idMtzRisco);
        echo json_encode($dados['descRisco']);


    }

    public function ajaxCarregaGrupoProcesso(){
        $dados = array(); 
    
        $grupoProcesso = $_POST['grupoProcesso'];

        if(isset($grupoProcesso) && !empty($grupoProcesso) ){
            
            $m = new Matriz();
            $dados['grupoProcesso'] = $m->carregaMtzGrupoProcesso($grupoProcesso);
         
             foreach ($dados['grupoProcesso'] as $value) {
                     echo'<li onclick="carregaMtzGrupoPocesso('."'".$value["idGrpProcesso"]."'".","."'".$value["descricao"]."'".')">'.$value['descricao'] .'</li>';
             }  
        }

    }

    public function ajaxCarregaGrauRisco(){
        $dados = array(); 
    
        $grauRisco = $_POST['grauRisco'];

        if(isset($grauRisco) && !empty($grauRisco) ){
            
            $m = new Matriz();
            $dados['grauRisco'] = $m->carregaMtzGrauRisco($grauRisco);
         
             foreach ($dados['grauRisco'] as $value) {
                     echo'<li onclick="carregaMtzGrauRisco('."'".$value["idGrauRisco"]."'".","."'".$value["descricao"]."'".')" >'.$value['descricao'] .'</li>';
             }  
        }
    }

    public function ajaxCarregaGrauRiscoEditaRisco(){
        $dados = array(); 
    
        $grauRisco = $_POST['grauRisco'];

        if(isset($grauRisco) && !empty($grauRisco) ){
            
            $m = new Matriz();
            $dados['grauRisco'] = $m->carregaMtzGrauRisco($grauRisco);
         
             foreach ($dados['grauRisco'] as $value) {
                     echo'<li onclick="carregaMtzGrauRiscoEditaRisco('."'".$value["idGrauRisco"]."'".","."'".$value["descricao"]."'".')" >'.$value['descricao'] .'</li>';
             }  
        }
    }
    public function ajaxCarregaGrauRiscoCadastroRisco(){
        $dados = array(); 
    
        $grauRisco = $_POST['grauRisco'];

        if(isset($grauRisco) && !empty($grauRisco) ){
            
            $m = new Matriz();
            $dados['grauRisco'] = $m->carregaMtzGrauRisco($grauRisco);
         
             foreach ($dados['grauRisco'] as $value) {
                     echo'<li onclick="carregaMtzGrauRiscoCadastroRisco('."'".$value["idGrauRisco"]."'".","."'".$value["descricao"]."'".')" >'.$value['descricao'] .'</li>';
             }  
        }
    }
     public function ajaxCarregaGrauRiscoCorrelato(){
        $dados = array(); 
    
        $grauCorrelato = $_POST['grauCorrelato'];

        if(isset($grauCorrelato) && !empty($grauCorrelato) ){
            
            $m = new Matriz();
            $dados['grauCorrelato'] = $m->carregaMtzGrauRisco($grauCorrelato);
         
             foreach ($dados['grauCorrelato'] as $value) {
                     echo'<li onclick="carregaMtzGrauRiscoCorelato('."'".$value["idGrauRisco"]."'".","."'".$value["descricao"]."'".')" >'.$value['descricao'] .'</li>';
             }  
        }
    }


    public function ajaxExcluirProgProcesso(){
        $idAppProcesso = $_POST['idAppProcesso'];
        $e = new Matriz();
        $e->exluirProgProcesso($idAppProcesso);
    }

    public function ajaxExcluirProcessoCoorelato(){
        $idCorrelacao = $_POST['idCorrelacao'];        
        $e = new Matriz();
        $e->exluirProcessoCoorelato($idCorrelacao);
    }

    public function ajaxExcluiProcesso(){
        $idProcesso = $_POST['idProcesso'];
        $e = new Matriz();
        $e->excluirProcesso($idProcesso);
    }
    public function ajaxExcluirGrupoProcesso(){
        $idGrpProcesso = $_POST['idGrpProcesso'];
        $e = new Matriz();
        $e->excluirGrupoProcesso($idGrpProcesso);
    }
    public function ajaxCarregaMitigacao(){
        $data = array();
        $idMitigacao = $_POST['idMitigacao'];
        $e = new Matriz();
        $data['mitiga'] = $e->carregaMitigacaoAjax($idMitigacao);

       echo json_encode($data['mitiga']);
    }
    public function ajaxCarregaMitigacaoDocumento(){
        $data = array();
        $idMitigacao = $_POST['idMitigacao'];
        $e = new Matriz();
        $data['mitiga2'] = $e->carregaMitigacaoDocumentoAjax($idMitigacao);

       echo json_encode($data['mitiga2']);
    }
    public function ajaxExluiArquivcoMidigacao($id,$nomeArquivo,$idMitigacao){
        $e = new Matriz();
        $e->excluirDocumentoMitigacao($id);
        
        unlink("./arquivos/".$nomeArquivo);
        header("Location:".URL."/Matriz/editar_mitigacao/".$idMitigacao);
    }

    public function ajaxCarregaPlanoMedigacao(){
        $m = new Matriz();
        $idMedigacao = $_POST['idMedigacao'];
        $dados['dadosRisco'] = $m->ajaxCarregaPlanoMedigacao($idMedigacao);

        echo json_encode($dados['dadosRisco']);
    }

    //AJAX MITIGACAO
      public function ajaxCarregaRiscoMitiga(){
        $m = new Matriz();
        $mitigacao = $_POST['mitigacao'];
        $dados['dadosRisco'] = $m->ajaxCarregaRiscoMitiga($mitigacao);

        echo json_encode($dados['dadosRisco']);
      }
      public function excluirRiscoMitigacao($idRisco,$idMitigacao){
        $m = new Matriz();
        $m->excluirRiscoMitigacao($idRisco);


        header("Location:".URL."/Matriz/editar_mitigacao/".$idMitigacao);
      }


      public function ajaxCarregaTabelaRiscoMitiga(){
        $m = new Matriz();
        $id = $_POST['id'];
        $dados['dadosRisco'] = $m->ajaxCarregaTabelaRiscoMitiga($id);

        echo json_encode($dados['dadosRisco']);
      }

      public function excluirPlanoMitiga($id){
        $m = new Matriz();
        $m->excluirPlanoMitiga($id);

        header("Location:".URL."/Matriz/cadastroMitigacao/");
      }


    // FIM AJAX MITIGACAO

    public function excluiRisco($idRisco)
    {
        $m = new Matriz();        
        $res = $m->excluiRisco($idRisco);

        if($res['return']):
            $this->helper->setAlert(
                'success',
                'Risco excluído com sucesso!',
                'Matriz/cadastroDeRisco'
            );
        else:
            $this->helper->setAlert(
                'error',
                'Erro ao excluír risco! \n'.$res['error'],
                'Matriz/cadastroDeRisco'
            );
        endif;
        
        header("Location:".URL."/Matriz/cadastroDeRisco");
    }


}