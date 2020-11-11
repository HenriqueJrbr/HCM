<?php
class FluxoController extends Controller {

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
        $data = array();
        $this->loadTemplate('404', $data);
    }

    public function cartaRisco($path, $idCarta) {
        $fluxo = new Fluxo();
        $encoded = $fluxo->carregaCartaRisco($idCarta);
        $file = URL.'/arquivos/carta_risco/' . $encoded['cartaRisco'];
        //die($file);


        //file_get_contents is standard function
        $content = file_get_contents($file);
        header('Content-Type: application/pdf');
        header("Content-Disposition: attachment; filename=\"".$encoded['cartaRisco']."\"");
        header("Content-Length: ".filesize($file));
        readfile($file);
        //echo $content;
        die;
        # Perform a basic validation to make sure that the result is a valid PDF file
        # Be aware! The magic number (file signature) is not 100% reliable solution to validate PDF files
        # Moreover, if you get Base64 from an untrusted source, you must sanitize the PDF contents
        //echo file_get_contents(URL.'arquivos/carta_risco/' . $encoded['cartaRisco']);
    }


    public function centralDeTarefa() {
        $data = array();

        $fluxo = new Fluxo();

        $data['atividade'] = $fluxo->carregaAtividade($_SESSION['idUsrTotvs']);
        $data['atividadeFIm'] = $fluxo->carregaAtividadeFinalizada($_SESSION['idUsrTotvs']);
        $data['atividadeSolicitante'] = $fluxo->carregaAtividadeSolicitante($_SESSION['idUsrTotvs']);
        $data['atividadeEmAndamento'] = $fluxo->carregaAtividadeEmAndamento($_SESSION['idUsrTotvs']);

        $this->loadTemplate('centralDeTarefa', $data);
    }

    public function cartasDeRisco()
    {
        // VIEW
        $this->loadTemplate('allCartasRisco');
    }

    /**
     * Método que interage entre as classes responsáveis pelos fluxos
     * @param $from
     * @param $idSolicitacao
     * @param $idAtividade
     * @param $fluxo
     * @param $idMovimentacao
     */
    public function callRegras($from, $idSolicitacao,$idAtividade,$fluxo,$idMovimentacao){
        if(isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa']) ){
            $empresa = new home();
            $empresaId = addslashes($_POST['empresa']);

            $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
            $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
            $_SESSION['empresaid'] = $empresaId;

            header('Location: '.URL);
        }

        $r = new $from();
        $r->$from($this, $idSolicitacao, $idAtividade, $fluxo, $idMovimentacao, $_POST, $from);
    }


    public function iniciaProvacaoReestruturacao() {
        $data = array();
        $fluxo = new Fluxo();
        $e = new Email();

        if(isset($_POST['iniciaRestruturacao']) && $_POST['iniciaRestruturacao'] == "Enviar"){
            $idGestor = $_POST['gestor'];
            $fluxo->iniciaFluxoRestruturacao($idGestor,$_SESSION['empresaid']);
            header("Location:".URL."/Fluxo/centralDeTarefa"); 
        }

        $data['listaGestor'] = $fluxo->listaGestor($_SESSION['empresaid']);
        $this->loadTemplate('iniciaProvacaoReestruturacao', $data);
    }

    public function restruturacao_de_acesso($idSolicitacao,$idAtividade="",$idMovimentacao="",$idSolicitante="") {
        $data = array();
        $fluxo = new Fluxo();
        $email = new Email();

        $data['datosFluxo'] = $fluxo->carragaDadosSolicitacaoRestruturacao($idSolicitacao);
        $d = new Usuario();
      

        if(isset($_POST['enviar']) && $_POST['enviar'] == "Enviar"){
            $aprovacao = $_POST['aprovacao'];
            $idUsuario = $_POST['idUsuario'];

            $observacao = addslashes($_POST['observacao']);

            $dataMovimentacao = date('Y-m-d H:i:s');
            $form = "1";
            $banco = "z_sga_fluxo_aprovacaorestruturacao";

            $fluxo->updateRestruturacaoDeAcesso($idSolicitacao,$aprovacao);

            $fluxo->updateMovimento($idSolicitacao,$idAtividade);

            $fluxo->cadastraMovimentacao($idSolicitacao,'2',$dataMovimentacao,$idSolicitante,$idSolicitante,$form,$banco);
            
            if(!empty($observacao)){
                $fluxo->cadastraMensagem($idSolicitacao,$_SESSION['nomeUsuario'],$observacao,$dataMovimentacao);
            }

            $dados  = $email->cadadosUsuario($idSolicitante);
            $dadosUsr = $email->cadadosUsuario($idUsuario);


             $mensagem = "<!DOCTYPE html>
                  <html>
                    <head>
                      <title></title>
                    </head>
                    <body>  
                      <h1>Ola! ".$dados['nome_usuario']."</h1>

                      <p>Existe uma nova atividade que est&aacute; sob sua responsabilidade e precisa de sua a&ccedil;&atilde;o.</p>
                      <p>Aprova&ccedil;&atilde;o: Restrutura&ccedil;&atilde;o de Acesso do usu&aacute;rio <b>".$dadosUsr['nome_usuario']."</b></p>
                      <p>Mensagem: ".$observacao."</p>
                      <p>Acesse : <a href='".URL."/Fluxo/centralDeTarefa'>".URL."/Fluxo/centralDeTarefa</a></p>
                    </body>
                  </html>";

            $email->enviaEmail("SGA - Sistema de Gestão de Acesso",'Restruturação de Acesso',$mensagem,$dados['email']);
            


            header("Location:".URL."/Fluxo/centralDeTarefa");
        }

        if(isset($_POST['enviarSi']) && $_POST['enviarSi'] == "Enviar"){
            $envia = $_POST['enviaSi'];
            $idUsuario = $_POST['idUsuario'];
            $dataMovimentacao = date('Y-m-d H:i:s');
            $form = "1";
            $banco = "z_sga_fluxo_aprovacaorestruturacao";

            if($envia == "fim"){
                $fluxo->finalizaSolicitacao($idSolicitacao,$dataMovimentacao);
                $fluxo->updateMovimento($idSolicitacao,$idAtividade);

                $dados  = $email->cadadosUsuario($idSolicitacao);
                $dadosUsr = $email->cadadosUsuario($idUsuario);

                header("Location:".URL."/Fluxo/centralDeTarefa"); 
            }else{
                $observacao = addslashes($_POST['observacao']);
                if(!empty($observacao)){
                    $fluxo->cadastraMensagem($idSolicitacao,$_SESSION['nomeUsuario'],$observacao,$dataMovimentacao);
                }
                $fluxo->updateMovimento($idSolicitacao,$idAtividade);
                $fluxo->cadastraMovimentacao($idSolicitacao,'1',$dataMovimentacao,$idSolicitante,$envia,$form,$banco);



            $dados  = $email->cadadosUsuario($envia);
            $dadosUsr = $email->cadadosUsuario($idUsuario);


             $mensagem = "<!DOCTYPE html>
                  <html>
                    <head>
                      <title></title>
                    </head>
                    <body>  
                      <h1>Ola! ".$dados['nome_usuario']."</h1>

                      <p>Existe uma nova atividade que est&aacute; sob sua responsabilidade e precisa de sua a&ccedil;&atilde;o.</p>
                      <p>Aprova&ccedil;&atilde;o: Restrutura&ccedil;&atilde;o de Acesso do usu&aacute;rio <b>".$dadosUsr['nome_usuario']."</b></p>
                      <p>Mensagem: ".$observacao."</p>
                      <p>Acesse : <a href='".URL."/Fluxo/centralDeTarefa'>".URL."/Fluxo/centralDeTarefa</a></p>
                    </body>
                  </html>";

            $email->enviaEmail("SGA - Sistema de Gestão de Acesso",'Restruturação de Acesso',$mensagem,$dados['email']);
                header("Location:".URL."/Fluxo/centralDeTarefa"); 
            }  
        }

        $data['usuario'] = $d->usuarioSelecionado($data['datosFluxo']['idUsuario']);
        $data['grupo'] = $d->carregaGruposUsuario($data['datosFluxo']['idUsuario'],$_SESSION['empresaid']);
        $data['grupo2'] = $d->carregaGruposUsuarioFoto($data['datosFluxo']['idUsuario'],$_SESSION['empresaid']);
        $data['totalAcesso'] = $d->countAcessosUsuario($data['datosFluxo']['idUsuario']);
        $data['totalAcesso2'] = $d->countAcessosUsuarioFoto($data['datosFluxo']['idUsuario']);
        
        $data['dadosForm'] = $fluxo->carregaDadosRestruturacao($idSolicitacao);
        $data['msg'] = $fluxo->carregaMensagem($idSolicitacao);
        $data['atividade'] = $idAtividade;
        
        if($data['usuario'][4] != ''){
          $data['dadosGestor'] = $d->dadosGestor($data['usuario'][4]);
        }else{
          $data['dadosGestor']  = '1';
        }

      
        $this->loadTemplate('restruturacao_de_acesso', $data);
    }

    public function acesso_temporario($idSolicitacao,$idAtividade=0,$idMovimentacao="",$idSolicitante=""){
        $data = array();
        $fluxo = new Fluxo();

        if($idSolicitacao == 0){
            $data['atividade'] = 0;
             if(isset($_POST['enviar']) && $_POST['enviar'] == "Enviar" && isset($_POST['grupoTab']) && !empty($_POST['grupoTab'])){
            
                $grupoTab[] = "";
                $usuario = $_POST['usuario'];
                $idUsuario = $_POST['idUsuario'];
                $gestor = $_POST['gestor'];
                $idGestor = $_POST['idGestor'];
                $inicio = $_POST['inicio'];
                $fim = $_POST['fim'];
                $grupoTab = $_POST['grupoTab'];
                $idGrupoTab = $_POST['idGrupoTab'];
                $descGrupo = $_POST['descGrupo'];
                $aprovacaoGestor = $_POST['aprovacaoGestor'];
                $obsGestor = $_POST['obsGestor'];
                $idGestorTab = $_POST['idGestorTab'];
                
                for ($i=0; $i < count($grupoTab); $i++) {
                    $dados = array("codGrupo"=>$grupoTab[$i],"idGrupo"=>$idGrupoTab[$i],"descGrupo"=>$descGrupo[$i],"idGestorGrupo"=>$idGestorTab[$i]);
                    $lista[] = $dados;    
                }
          
                $dadosDoc = array( 'usuario'=> $usuario,
                        'idUsuario'=>$idUsuario,
                        'gestor'=>$gestor,
                        'idGestor'=>$idGestor,
                        'inicio'=>$inicio,
                        'fim'=>$fim,
                        'aprovacaoGestor'=>$aprovacaoGestor,
                        'idSolicitante'=>$_SESSION['idUsrTotvs'],
                        'tabela'=>$lista
                      );

                $documento = json_encode($dadosDoc,true);
                $idForm = 2;
                $idFluxo = 2;
                $banco = "";
                $dataMovimentacao = date('Y-m-d H:i:s');

                $dados['idSolicitacao'] = $fluxo->cadastraNumSolicitacao($idForm,$_SESSION['idUsrTotvs']);
                $idSolicitacao = $dados['idSolicitacao']['idSolic'];
                
                if(!empty($idSolicitacao)){
                    $retorno =  $fluxo->criaDocumento($documento,$idForm,$idFluxo,$idSolicitacao);
                                if(!empty($aprovacaoGestor)){
                                    $fluxo->cadastraMensagem($idSolicitacao,$_SESSION['nomeUsuario'],$obsGestor,$dataMovimentacao);
                                }
                }
                

                if(!empty($retorno)){
                    $idAtiviAtual = 3;
                    $data['ativ'] = $fluxo->verificaProximaAtividade($idAtiviAtual);

                    if($data['ativ']['proximaAtiv'] == 5){
                        $fluxo->cadastraMovimentacao($idSolicitacao,$data['ativ']['proximaAtiv'],$dataMovimentacao,$_SESSION['idUsrTotvs'],$idGestor,$idForm,$banco);
                    }

                     if($data['ativ']['proximaAtiv'] == 6){
                         $controla = "";
                        for ($i=0; $i < count($idGestorTab); $i++) {
                            
                            if($controla != $idGestorTab[$i]){
                                $fluxo->cadastraMovimentacao($idSolicitacao,$data['ativ']['proximaAtiv'],$dataMovimentacao,$_SESSION['idUsrTotvs'],$idGestorTab[$i],"2","");
                                $controla  = $idGestorTab[$i];
                            }
                            
                        }
                      
                    }
                                    
                    $_SESSION['mensagem'] = "Solicitação numero ".$retorno." criada com sucesso!";
                    header("Location:".URL."/Fluxo/centralDeTarefa");
                }else{

                }
                     
            }
        }

        if($idAtividade == 5 || $idAtividade == 3 || $idAtividade == 6){

            if(isset($_POST['enviar']) && $_POST['enviar']){
   
                $grupoTab[] = "";
                $usuario = $_POST['usuario'];
                $idUsuario = $_POST['idUsuario'];
                $gestor = $_POST['gestor'];
                $idGestor = $_POST['idGestor'];
                $inicio = $_POST['inicio'];
                $fim = $_POST['fim'];
                $grupoTab = $_POST['grupoTab'];
                $idGrupoTab = $_POST['idGrupoTab'];
                $descGrupo = $_POST['descGrupo'];
                $aprovacaoGestor = $_POST['aprovacaoGestor'];
                $obsGestor = $_POST['obsGestor'];
                $idGestorTab = $_POST['idGestorTab'];

                
                for ($i=0; $i < count($grupoTab); $i++) {
                    $dados = array("codGrupo"=>$grupoTab[$i],"idGrupo"=>$idGrupoTab[$i],"descGrupo"=>$descGrupo[$i],"idGestorGrupo"=>$idGestorTab[$i]);
                    $lista[] = $dados;    
                }
          
                $dadosDoc = array( 'usuario'=> $usuario,
                        'idUsuario'=>$idUsuario,
                        'gestor'=>$gestor,
                        'idGestor'=>$idGestor,
                        'inicio'=>$inicio,
                        'fim'=>$fim,
                        'aprovacaoGestor'=>$aprovacaoGestor,
                        'tabela'=>$lista
                      );

                $dataMovimentacao = date('Y-m-d H:i:s');
                $documento = json_encode($dadosDoc,true);
                $fluxo->atualizaDocumento($idSolicitacao, $documento);
                if(!empty($obsGestor)){
                    $fluxo->cadastraMensagem($idSolicitacao,$_SESSION['nomeUsuario'],$obsGestor,$dataMovimentacao);
                }

                if($idAtividade == 5){
                    if($aprovacaoGestor == "nao"){
                        $fluxo->updateMovimento($idSolicitacao,"5");
                        $fluxo->cadastraMovimentacao($idSolicitacao,3,$dataMovimentacao,$idSolicitante,$idSolicitante,"2","");
                        $_SESSION['mensagem'] = "Atividade número ".$idSolicitacao." foi movimentado com sucesso!";
                        header("Location:".URL."/Fluxo/centralDeTarefa");

                    }
                    if($aprovacaoGestor == "sim"){
                        $fluxo->updateMovimento($idSolicitacao,"5");
                        $controla = "";
                        for ($i=0; $i < count($idGestorTab); $i++) {
                            
                            $data['ativ'] = $fluxo->verificaProximaAtividade($idAtividade);
                            if($controla =! $idGestorTab[$i]){
                                $fluxo->cadastraMovimentacao($idSolicitacao,$data['ativ']['proximaAtiv'],$dataMovimentacao,$idSolicitante,$idGestorTab[$i],"2","");
                                $controla  = $idGestorTab[$i];
                            }
                            
                        }
                      
                       
                        $_SESSION['mensagem'] = "Atividade número ".$idSolicitacao." foi movimentado com sucesso!";
                        header("Location:".URL."/Fluxo/centralDeTarefa");

                    }   
                }

                if($idAtividade == 3){
                        $data['ativ'] = $fluxo->verificaProximaAtividade($idAtividade);
                        $fluxo->updateMovimento($idSolicitacao,"3");
                        if(!empty($obsGestor)){
                            $fluxo->cadastraMovimentacao($idSolicitacao,$data['ativ']['proximaAtiv'],$dataMovimentacao,$idSolicitante,$idGestor,"2","");
                        }
                        
                        $_SESSION['mensagem'] = "Atividade número ".$idSolicitacao." foi movimentado com sucesso!";
                        header("Location:".URL."/Fluxo/centralDeTarefa");
                }

                
                
            }

            $data['documento'] = $fluxo->carregaDocumento($idSolicitacao);
            $data['mensagem'] =  $fluxo->carregaMensagem($idSolicitacao);
            
            if($idAtividade == 5){
                $data['atividade'] = 5;
            }
            if($idAtividade == 3){
                $data['atividade'] = 3;
            }
            if($idAtividade == 6){
                $data['atividade'] = 6;
            }
           
             
             
             
        }
       
        

        $this->loadTemplate('acesso_temporario', $data); 
    }
    
    
    //Ajax que carrega os usuários do form acesso temporario
    public function ajaxCarregaTodosUsuario(){
        $dados = array();
        $f = new Fluxo();
        $idUsr = $_POST['idUsr'];
        $empresaid = $_SESSION['empresaid'];
        if(isset($idUsr) && !empty($idUsr) ){

            $sql = "
               SELECT 
                    u.nome_usuario     AS nome_usuario,
                    u.cod_gestor       AS cod_gestor,
                    u.cod_usuario      AS cod_usuario,
                    userEmp.idUsuario  AS idUsuario,
                   (SELECT z_sga_usuarios_id FROM z_sga_usuarios WHERE cod_usuario = u.cod_gestor  ) as idGestor,
                   (SELECT nome_usuario FROM z_sga_usuarios WHERE cod_usuario = u.cod_gestor  ) as nomeGestor    
                FROM 
                    z_sga_usuario_empresa AS userEmp,
                    z_sga_usuarios AS u ,
                    z_sga_manut_funcao AS m
                WHERE 
                    userEmp.idEmpresa = '$empresaid'  
                    AND userEmp.idUsuario = u.z_sga_usuarios_id  
                    AND u.cod_funcao = m.idFuncao 
                    AND u.nome_usuario LIKE '%".$idUsr."%'";

            $dados['usr'] = $f->ajaxSelect($sql);
            foreach ($dados['usr'] as $value) {
                echo'<li onclick="carregaDadosUsr('."'".$value["nome_usuario"]."'".","."'".$value["idUsuario"]."'".","."'".$value["nomeGestor"]."'".","."'".$value["idGestor"]."'".')">'. $value['nome_usuario']. ' - '.$value['cod_usuario'] .'</li>';
            }
        }
    }

    public function ajaxCarregaTodosGrupos(){
        $dados = array();
        $f = new Fluxo();
        $idGrupo = $_POST['idGrupo'];
        $empresaid = $_SESSION['empresaid'];
        $sql = "SELECT distinct(grupo.idGrupo), grupo.idLegGrupo,grupo.descAbrev,
        (select ui.cod_usuario from z_sga_usuarios as ui  where ui.cod_usuario = grupos.gestor) as codGest,
        (select ui.z_sga_usuarios_id from z_sga_usuarios as ui  where ui.cod_usuario = grupos.gestor) as idGestor  
        from 
        z_sga_grupo as grupo
        LEFT JOIN			
			z_sga_grupos as grupos 
            ON grupo.idGrupo = grupos.idGrupo
        where     
        grupo.idEmpresa = '$empresaid' AND grupo.idLegGrupo  LIKE '%".$idGrupo."%' OR grupo.descAbrev LIKE '%".$idGrupo."%'";
        $dados['grupo'] = $f->ajaxSelect($sql);
        foreach ($dados['grupo'] as $value) {
            echo'<li onclick="carregaDadosGrupo('."'".$value["idLegGrupo"]."'".","."'".$value["descAbrev"]."'".","."'".$value["idGrupo"]."'".","."'".$value["idGestor"]."'".","."'".$value["codGest"]."'".')">'. $value['idLegGrupo']. ' - '.$value['descAbrev'] .'</li>';
        }


    }

    public function ajaxConsultaGestor(){
        $idGestor = $_POST['idGestor'];
        $data = array();
        $fluxo = new Fluxo();

        $data['usr'] = $fluxo->ajaxConsultaGestor($idGestor,$_SESSION['empresaid']);
        echo json_encode($data['usr']);
    }

    /**
     * método para criação de jquery datatable na tela de grupos
     */
    public function ajaxDatatableProgramasLog($idSolicitacao)
    {
        $dados = array();
        $data = array();
        $f = new Fluxo();


        // usado pelo order by
        $fields = array(
            0 => 'idLegGrupo',
            1 => 'descAbrev',
            2 => 'cod_programa',
            3 => 'descricao_programa',
            4 => 'ajuda_programa',
            5 => 'descricao_rotina'
        );

        // Variaveis usadas na paginação do Jquery DataTable
        $search = (isset($_POST["search"]["value"])) ? $_POST["search"]["value"] : '';
        $orderColumn = (isset($_POST['order']['0']['column'])) ? $fields[$_POST['order']['0']['column']] : '';
        $orderDir = (isset($_POST['order']['0']['dir'])) ? $_POST['order']['0']['dir'] : 'ASC';
        $limit = (isset($_POST["length"]) && $_POST["length"] != -1) ? $_POST['length'] : '10';
        $offset = (isset($_POST["length"]) && $_POST["length"] != -1) ? $_POST['start'] : '0';

        // Traz apenas 10 usuarios utilizando paginação
        $dados = $f->carregaDatatableFluxoProgramaLog($search, $orderColumn, $orderDir, $offset, $limit, $idSolicitacao);

        // Traz o total de todos os programas
        $total_all_records = $f->getCountTableProgramasLog($search, $_SESSION['empresaid'], $idSolicitacao);

        // cria linhas customizadas no jquery datatable.
        if (count($dados) > 0):
            foreach ($dados as $key => $value):
                $sub_dados = array();
                $sub_dados[] = $value["idLegGrupo"];
                $sub_dados[] = $value["descAbrev"];
                $sub_dados[] = $value["cod_programa"];
                $sub_dados[] = $value["descricao_programa"];
                $sub_dados[] = $value["ajuda_programa"];
                $sub_dados[] = (!empty($value['ajuda_programa']) ? $value['ajuda_programa'] : 'Não cadastrado');
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

    public function ajaxMatrizDeRisco(){
        $dados = array();
        $f = new Fluxo();
        $s = new SolicitacaoGrupoPrograma();
        $dados['conflitos'] = $f->fluxoMatrizRisco($_POST['grupos']);
        $totalRiscos = $f->fluxoMatrizCountRisco($_POST['grupos']);
        $dados['totalProgByGrupo'] = $f->getCountTableAbaProgs("z_sga_programas p", '', array(0 => 'e.idPrograma'), '', $_SESSION['empresaid'], $_POST['grupos']);
        $dados['totalUsuariosByGrupo'] = $s->getCountTableAbaUsuarios("z_sga_grupos gs", '', array('DISTINCT u.z_sga_usuarios_id'), '', $_SESSION['empresaid'], $_POST['grupos']);
        
        //$this->helper->debug($dados['totalProgByGrupo']);
        
        $area = "";
        $risco = "";
        $idCollapseArea = 10000;
        $next = array();
        $idTabela = 0;

        $html = "";

        $html .= '<div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                  <div class="row">
                    <div class="col-sm-6"></div>
                    <div class="col-sm-6"></div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="panel-group" id="accordion">';

        foreach ($dados['conflitos'] as $value) {

            
            if($area != $value['descArea']){
                $area = $value['descArea'];
                $html .='<div class="panel panel-default">
                          <div class="panel-heading">
                            <h4 class="panel-title">
                              <a data-toggle="collapse" data-parent="#accordion" href="#collapse'.$idCollapseArea.'">
                              Area '.$value["descArea"].'</a>
                            </h4>
                          </div>
                          <div id="collapse'.$idCollapseArea.'" class="panel-collapse collapse">
                            <div class="panel-body">';
            }
            if($risco != $value['codRisco']){
                $risco = $value['codRisco'];
                $idTabela = $idTabela + 1;
                $html .=  "<h5><strong>".$value["codRisco"]."</strong> <span class=\"badge\" style=\"background-color:".(($value['mitigado'] == 'Mitigado') ? '#26B99A' : '#d9534f' ).'" >'.$value['mitigado'] . "</span> - ".$value["descRisco"]."</h5><br>";

                $html .= '<table class="tabela table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="DataTables_Table_0_info" style="width: 100%;" id="DataTables_Table_<?php echo $idTabela?>">
                          <thead>
                          <tr role="row"><td><strong>Composiçao do Risco</strong></td></tr>
                          <tr role="row">
                            <th>Grau de Risco</th>
                            <th>Processo Referencia</th>
                            <th>Programas do processo</th>
                            <th>Processos vinculados</th>
                            <th>Programas do processo</th>
                          </tr>
                         </thead>
                         <tbody>';

            }
            $html .='<tr>';
            $html .= '<td bgcolor="'.$value["bgcolor"].'"><font color="'.$value["fgcolor"].'">'.$value["grau"].'</font></td>';
            $html .= '<td>'.$value["processoPri"].'</td>';
            $html .= '<td>'.$value["progspPri"].'</td>';
            $html .= '<td>'.$value["processoSec"].'</td>';
            $html .=  '<td>'.$value["progspSec"].'</td>';
            $html .='</tr>';
            
            $next = next($dados['conflitos']);
            if($risco != $next['codRisco']){
                $html .='</tbody></table>';
            }


            if($area != $next['descArea']){
                $html .='</div></div></div>';
                $idCollapseArea = $idCollapseArea+1;
            }
        }

        $html .='</div></div></div></div>';

        //echo $html;
        echo json_encode(array(
            'html' => $html,
            'totalRiscos' => $totalRiscos,
            'totalProgByGrupo' => $dados['totalProgByGrupo'],
            'totalUsuariosByGrupo' =>$dados['totalUsuariosByGrupo']
        ));
    }
    
    
    public function ajaxMatrizDeRiscoJaAdicionados(){
        $dados = array();
        $f = new Fluxo();
        $dados['conflitos'] = $f->fluxoMatrizRiscoAdicionados($_POST['idUsuario']);
        $totalRiscos = $f->fluxoMatrizCountRiscoAdicionados($_POST['idUsuario']);
        $dados['totalProgByGrupo'] = $f->getCountTableAbaProgsAdicionados("z_sga_programas p", '', array(0 => 'e.idPrograma'), '', $_SESSION['empresaid'], $_POST['idUsuario']);
        
        //$this->helper->debug($dados['totalProgByGrupo']);
        
        $area = "";
        $risco = "";
        $idCollapseArea = 10000;
        $next = array();
        $idTabela = 0;

        $html = "";

        $html .= '<div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                  <div class="row">
                    <div class="col-sm-6"></div>
                    <div class="col-sm-6"></div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="panel-group" id="accordion">';

        foreach ($dados['conflitos'] as $value) {


            if($area != $value['descArea']){
                $area = $value['descArea'];
                $html .='<div class="panel panel-default">
                          <div class="panel-heading">
                            <h4 class="panel-title">
                              <a data-toggle="collapse" data-parent="#accordion" href="#collapse3'.$idCollapseArea.'">
                              Area '.$value["descArea"].'</a>
                            </h4>
                          </div>
                          <div id="collapse3'.$idCollapseArea.'" class="panel-collapse collapse">
                            <div class="panel-body">';
            }
            if($risco != $value['codRisco']){
                $risco = $value['codRisco'];
                $idTabela = $idTabela + 1;
                $html .=  "<h5><strong>".$value["codRisco"]."</strong> <span class=\"badge\" style=\"background-color:".(($value['mitigado'] == 'Mitigado') ? '#26B99A' : '#d9534f' ).'" >'.$value['mitigado'] . "</span> - ".$value["descRisco"]."</h5><br>";

                $html .= '<table class="tabela table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="DataTables_Table_0_info" style="width: 100%;" id="DataTables_Table_<?php echo $idTabela?>">
                          <thead>
                          <tr role="row"><td><strong>Composiçao do Risco</strong></td></tr>
                          <tr role="row">
                            <th>Grau de Risco</th>
                            <th>Processo Referencia</th>
                            <th>Programas do processo</th>
                            <th>Processos vinculados</th>
                            <th>Programas do processo</th>
                          </tr>
                         </thead>
                         <tbody>';

            }
            $html .='<tr>';
            $html .= '<td bgcolor="'.$value["bgcolor"].'"><font color="'.$value["fgcolor"].'">'.$value["grau"].'</font></td>';
            $html .= '<td>'.$value["processoPri"].'</td>';
            $html .= '<td>'.$value["progspPri"].'</td>';
            $html .= '<td>'.$value["processoSec"].'</td>';
            $html .=  '<td>'.$value["progspSec"].'</td>';
            $html .='</tr>';

            $next = next($dados['conflitos']);
            if($risco != $next['codRisco']){
                $html .='</tbody></table>';
            }


            if($area != $next['descArea']){
                $html .='</div></div></div>';
                $idCollapseArea = $idCollapseArea+1;
            }
        }

        $html .='</div></div></div></div>';

        //echo $html;
        echo json_encode(array(
            'html' => $html,
            'totalRiscos' => $totalRiscos,
            'totalProgByGrupo' => $dados['totalProgByGrupo']
        ));
    }
    
    
    public function ajaxMatrizDeRiscoFoto(){
        $dados = array();
        $f = new Fluxo();
        $dados['conflitos'] = $f->fluxoMatrizRiscoFoto($_POST['idSolicitacao']);        
        
        //$this->helper->debug($dados['totalProgByGrupo']);
        
        $area = "";
        $risco = "";
        $idCollapseArea = 10000;
        $next = array();
        $idTabela = 0;

        $html = "";

        $html .= '<div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                  <div class="row">
                    <div class="col-sm-6"></div>
                    <div class="col-sm-6"></div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="panel-group" id="accordion">';

        foreach ($dados['conflitos'] as $value) {


            if($area != $value['descArea']){
                $area = $value['descArea'];
                $html .='<div class="panel panel-default">
                          <div class="panel-heading">
                            <h4 class="panel-title">
                              <a data-toggle="collapse" data-parent="#accordion" href="#collapse'.$idCollapseArea.'">
                              Area '.$value["descArea"].'</a>
                            </h4>
                          </div>
                          <div id="collapse'.$idCollapseArea.'" class="panel-collapse collapse">
                            <div class="panel-body">';
            }
            if($risco != $value['codRisco']){
                $risco = $value['codRisco'];
                $idTabela = $idTabela + 1;
                $html .=  "<h5><strong>".$value["codRisco"]."</strong> <span class=\"badge\" style=\"background-color:".(($value['mitigado'] == 'Mitigado') ? '#26B99A' : '#d9534f' ).'" >'.$value['mitigado'] . "</span> - ".$value["descRisco"]."</h5><br>";

                $html .= '<table class="tabela table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="DataTables_Table_0_info" style="width: 100%;" id="DataTables_Table_<?php echo $idTabela?>">
                          <thead>
                          <tr role="row"><td><strong>Composiçao do Risco</strong></td></tr>
                          <tr role="row">
                            <th>Grau de Risco</th>
                            <th>Processo Referencia</th>
                            <th>Programas do processo</th>
                            <th>Processos vinculados</th>
                            <th>Programas do processo</th>
                          </tr>
                         </thead>
                         <tbody>';

            }
            $html .='<tr>';
            $html .= '<td bgcolor="'.$value["bgcolor"].'"><font color="'.$value["fgcolor"].'">'.$value["grau"].'</font></td>';
            $html .= '<td>'.$value["processoPri"].'</td>';
            $html .= '<td>'.$value["progspPri"].'</td>';
            $html .= '<td>'.$value["processoSec"].'</td>';
            $html .=  '<td>'.$value["progspSec"].'</td>';
            $html .='</tr>';

            $next = next($dados['conflitos']);
            if($risco != $next['codRisco']){
                $html .='</tbody></table>';
            }


            if($area != $next['descArea']){
                $html .='</div></div></div>';
                $idCollapseArea = $idCollapseArea+1;
            }
        }

        $html .='</div></div></div></div>';

        //echo $html;
        echo json_encode(array(
            'html' => $html,
            'totalRiscos' => count($dados['conflitos']),            
        ));
    }
    
    /**
     * método para criação de jquery datatable na tela de edição de grupos para tab usuários.
     */
    public function ajaxCarregaAbaProsRevisaoAcessoAdicionados()
    {
        $dados = array();
        $data = array();
        $f = new Fluxo();


        // usado pelo order by
        $fields = array(
            0   => 'g.idLegGrupo',
            1   => 'g.descAbrev',
            2   => 'p.cod_programa',
            3   => 'p.descricao_programa',
            4   => 'p.cod_modulo',
            5   => 'p.descricao_modulo',
            6   => 'p.descricao_rotina',
            7   => 'p.especific'           
        );

        // Variaveis usadas na paginação do Jquery DataTable
        $search = (isset($_POST["search"]["value"])) ? $_POST["search"]["value"] : '';
        $orderColumn = (isset($_POST['order']['0']['column'])) ? $fields[$_POST['order']['0']['column']] : '';
        $orderDir = (isset($_POST['order']['0']['dir'])) ? $_POST['order']['0']['dir'] : 'ASC';
        $limit = (isset($_POST["length"]) && $_POST["length"] != -1) ? $_POST['length'] : '10';
        $offset = (isset($_POST["length"]) && $_POST["length"] != -1) ? $_POST['start'] : '0';

        
        // Traz apenas 10 usuarios utilizando paginação
        $dados = $f->carregaAbaProgFluxoByGrupoAdicionados($search, $orderColumn, $orderDir, $offset, $limit, $_POST['idUsuario'], $_SESSION['empresaid']);

        // Traz todos os usuarios
        $total_all_records = $f->getCountTableAbaProgsAdicionados("z_sga_programas p", $search, $fields, '', $_SESSION['empresaid'], $_POST['idUsuario']);

        // Cria linhas customizadas no jquery datatable.
        if (count($dados) > 0):
            foreach ($dados as $value):
                $sub_dados = array();            
                $sub_dados[] = $value["grupo"];                
                $sub_dados[] = $value["cod_programa"];
                $sub_dados[] = $value["descricao_programa"];
                $sub_dados[] = $value["cod_modulo"];
                $sub_dados[] = $value['descricao_modulo'];
                $sub_dados[] = $value['descricao_rotina'];
                $sub_dados[] = ($value['especific'] == 'S') ? 'Sim' : 'Não';
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
    
    
    /**
     * método para criação de jquery datatable na tela de edição de grupos para tab usuários.
     */
    public function ajaxCarregaAbaProsRevisaoAcesso()
    {
        $dados = array();
        $data = array();
        $f = new Fluxo();


        // usado pelo order by
        $fields = array(
            0   => 'g.idLegGrupo',
            1   => 'g.descAbrev',
            2   => 'p.cod_programa',
            3   => 'p.descricao_programa',
            4   => 'p.cod_modulo',
            5   => 'p.descricao_modulo',
            6   => 'p.descricao_rotina',
            7   => 'p.especific'
        );

        // Variaveis usadas na paginação do Jquery DataTable
        $search = (isset($_POST["search"]["value"])) ? $_POST["search"]["value"] : '';
        $orderColumn = (isset($_POST['order']['0']['column'])) ? $fields[$_POST['order']['0']['column']] : '';
        $orderDir = (isset($_POST['order']['0']['dir'])) ? $_POST['order']['0']['dir'] : 'ASC';
        $limit = (isset($_POST["length"]) && $_POST["length"] != -1) ? $_POST['length'] : '10';
        $offset = (isset($_POST["length"]) && $_POST["length"] != -1) ? $_POST['start'] : '0';

        
        // Traz apenas 10 usuarios utilizando paginação
        $dados = $f->carregaAbaProgFluxoByGrupo($search, $orderColumn, $orderDir, $offset, $limit, $_POST['grupos'], $_SESSION['empresaid']);

        // Traz todos os usuarios
        $total_all_records = $f->getCountTableAbaProgs("z_sga_programas p", $search, $fields, '', $_SESSION['empresaid'], $_POST['grupos']);

        // Cria linhas customizadas no jquery datatable.
        if (count($dados) > 0):
            foreach ($dados as $value):
                $sub_dados = array();            
                $sub_dados[] = $value["grupo"];                
                $sub_dados[] = $value["cod_programa"];
                $sub_dados[] = $value["descricao_programa"];
                $sub_dados[] = $value["cod_modulo"];
                $sub_dados[] = $value['descricao_modulo'];
                $sub_dados[] = $value['descricao_rotina'];
                $sub_dados[] = ($value['especific'] == 'S') ? 'Sim' : 'Não';
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
    
    /**
     * Cancela as solicitações selecionadas na tela central de tarefas, aba minhas solicitações
     */
    public function cancelaSolicitacoes()
    {
        $fluxo = new Fluxo();
        $helper = new Helper();
        $dataMovimentacao = date('Y-m-d H:i:s');

        // Valida se foi seleciona ao menos uma solicitação
        if(empty($_POST)):
            $helper->setAlert(
                'error',
                'Nenhuma solicitação selecionada',
                'Fluxo/centralDeTarefa'
            );
            die('');
        endif;
        $post = $_POST;
        $documento = '';
        $idSolicitante = '';
        
        // Percorre os ids selecionados e cancela a solicitação
        foreach ($post['solicitacao'] as $idSolicitacao):
            $result = $fluxo->finalizaSolicitacao($idSolicitacao, $dataMovimentacao);

            // Recupera os dados da solicitação
            $dadosSolic = $fluxo->buscaDadosMovimentacaoAtiva($idSolicitacao);
            
            if($dadosSolic['return']):
                $fluxo->updateMovimento($idSolicitacao, $dadosSolic['result']['idAtividade']);
                //$idSolicitante = $dadosSolic['result']['idSolicitante'];
                // Grava o log para auditoria
                //$dadosLogAuditoria = $fluxo->buscaDadosAuditoria($idSolicitacao);
                //$fluxo->gravaLogAuditoria($idSolicitacao, $dadosLogAuditoria['dataInicio'], $dataMovimentacao, $dadosLogAuditoria['cod_usuario'], $dadosLogAuditoria['cod_usuario'], 'UPDATE', 'com - com', $_SESSION['nomeUsuario']);


                $documento = json_decode($dadosSolic['result']['documento']);
                // Cria html de email a ser enviado.
                //$mensagem = "<!DOCTYPE html>
                //    <html>
                //        <head><title></title></head>
                //        <body>  
                //            <h1>Olá! {gestor}</h1>
                //            <p>Informamos que a atividade abaixo, que estava sob sua responsabilidade, foi cancelada pelo solicitante.</p>
                //            <p>Aprovação: Revisão de Acesso do usuário <b> {usuario} </b></p>
                //        </body>
                //    </html>";

                // Envia email para o gestor responsável
                //$this->enviaEmailFluxos($mensagem, $dadosSolic['result']['idResponsavel'], $documento->idusuario);
                $fluxo->insereDocumentoHistorico(
                    $idSolicitacao,
                    $dadosSolic['result']['idMovimentacao'],
                    'encerrado',
                    'Cancelado pelo solicitante'
                );
                
                $paramsSolicitacao = array(
                    'idSolicitacao'  => $idSolicitacao,
                    'idSolicitante'  => $dadosSolic['result']['idSolicitante'],
                    'idAcompanhante' => ($documento->idAcompanhante != "") ? $documento->idAcompanhante : '' ,
                    'usuario'        => $documento->usuario
                );

                $this->enviaEmailCopiaCancela($paramsSolicitacao);
            endif;
        endforeach;       
        
        // Redireciona para a tela de central de tarefas
        $helper->setAlert(
            'success',
            'Solicitações canceladas com sucesso!',
            'Fluxo/centralDeTarefa'
        );
        die('');
    }
    
    /**
     * Cancela as solicitações selecionadas na tela central de tarefas, aba minhas solicitações
     */
    public function rejeitaSolicitacao($idSolicitacao, $idMovimentacao)
    {
        $fluxo = new Fluxo();
        $helper = new Helper();
        setlocale(LC_TIME, 'pt_BR');
        date_default_timezone_set('America/Sao_Paulo');
        $dataMovimentacao = date('Y-m-d H:i:s');        

        // Valida se foi seleciona ao menos uma solicitação
        if(empty($idSolicitacao)):
            $helper->setAlert(
                'error',
                'Nenhuma solicitação selecionada',
                'Fluxo/centralDeTarefa'
            );
            die('');
        endif;
        $post = $_POST;
        $documento = '';
        $idSolicitante = '';
        
        // cancela a solicitação        
        $result = $fluxo->finalizaSolicitacao($idSolicitacao, $dataMovimentacao);

        if($result['return']):
            // Recupera os dados da solicitação
            $dadosSolic = $fluxo->buscaDadosMovimentacaoAtiva($idSolicitacao);
            
            if($dadosSolic['return']):
                $fluxo->updateMovimento($idSolicitacao, $dadosSolic['result']['idAtividade']);
                $idSolicitante = $dadosSolic['result']['idSolicitante'];
                // Grava o log para auditoria
                //$dadosLogAuditoria = $fluxo->buscaDadosAuditoria($idSolicitacao);
                //$fluxo->gravaLogAuditoria($idSolicitacao, $dadosLogAuditoria['dataInicio'], $dataMovimentacao, $dadosLogAuditoria['cod_usuario'], $dadosLogAuditoria['cod_usuario'], 'Atualização', 'com - com', $_SESSION['nomeUsuario']);

                $documento = json_decode($dadosSolic['result']['documento']);                

                $fluxo->insereDocumentoHistorico(
                    $idSolicitacao,
                    $idMovimentacao,
                    'rejeitado',
                    'Rejeitado pelo Gestor de Usuários'
                );
            else:
                // Redireciona para a tela de central de tarefas
                $helper->setAlert(
                    'error',
                    'Erro ao rejeitar solicitação!\n'. $dadosSolic['error'],
                    'Fluxo/centralDeTarefa'
                );
                die('');
            endif;         
            
            $paramsSolicitacao = array(
                'idSolicitacao'  => $idSolicitacao,
                'idSolicitante'  => $idSolicitante,
                'idAcompanhante' => $documento->idAcompanhante,
                'usuario'        => $documento->usuario,
                'gestorUsuario'  => $documento->gestorUsuario
            );                

            if(isset($documento->idAcompanhante[0]) && !empty($documento->idAcompanhante[0])):
                $this->enviaEmailCopiaRejeita($paramsSolicitacao);
            endif;
            

            // Redireciona para a tela de central de tarefas
            $helper->setAlert(
                'success',
                'Solicitação rejeitada com sucesso!',
                'Fluxo/centralDeTarefa'
            );
            die('');
        else:
            // Redireciona para a tela de central de tarefas
            $helper->setAlert(
                'error',
                'Erro ao rejeitar solicitação!\n'. $result['error'],
                'Fluxo/centralDeTarefa'
            );
            die('');
        endif;
        
    }
    
    /**
     * Envia o email de cópia de cancelamento dos fluxos 
     * @param $paramSolicitacao          
     */
    public function enviaEmailCopiaCancela($paramsSolicitacao)
    {
        $email = new Email();
        $fluxo = new Fluxo();
        $assunto = "Solicitação de Acesso";
        $nomeRemetente = "SGA - Sistema de Gestão de Acesso";
            
        // Envia email para usuários em cópia.                
        if(isset($paramsSolicitacao['idAcompanhante']) && !empty($paramsSolicitacao['idAcompanhante'])):            
            

            // Envia email para o solicitante
            $dadosSolic = $fluxo->buscaDadosSolicitante($paramsSolicitacao['idSolicitante']);
            
            foreach($paramsSolicitacao['idAcompanhante'] as $val):
                if(empty($val)):
                    return false;
                endif;
                $dadosEmail = $fluxo->buscaDadosUsuariosCopia($val);
                                
                if($dadosEmail != 0):
                    $mensagem = '
                        Olá, <b>'.$dadosEmail['nome_usuario'].'</b>.<br/><br/>

                        <span style="font-size:14px;margin-top:20px">A atividade de número <b>'.$paramsSolicitacao['idSolicitacao'].'</b> '.(($paramsSolicitacao['idFluxo'] != 7) ? ', do usuário <b>'.$paramsSolicitacao['usuario'].'</b>' : '' ). '</b> em que você está acompanhando, foi cancelada pelo solicitante <b>'.$dadosSolic['nome_usuario'].'</b>.</span>
                        <br/><br/>';                
                    $mensagem .= '<br/><span style="font-size:14px;margin-top:20px; padding">Acompanhe a solicitação em seu painel de atividades.</span><br>';        
                    $mensagem .= '
                        <br/>
                        <br/>				
                        <a href="'.URL.'/Fluxo/centralDeTarefa" style="background: #4ca5a9;color: #ffffff; text-align: center; text-decoration: none;padding: 15px 30px; font-family: Arial, sans-serif; font-size: 16px">Ir para Central de Tarefas</a>';

                    $template = $email->getTemplate($mensagem);
                    $email->enviaEmail($nomeRemetente,$assunto, $template, $dadosEmail['email']);
                endif;
            endforeach;
        endif;
        
        // Envia e-mail para os gestores participantes.
        $acao = 'foi cancelada pelo solicitante, <b>'.$dadosSolic['nome_usuario'].'</b>';
        $paramsSolicitacao['nomeGestorUsuario'] = '';
        $this->enviaEmailGestorMovimentacao($paramsSolicitacao, $acao);
        //die('foi');
    }
    
    /**
     * Envia o email de cópia dos fluxos rejeitados
     * @param $paramSolicitacao          
     */
    public function enviaEmailCopiaRejeita($paramsSolicitacao)
    {
        $email = new Email();
        $fluxo = new Fluxo();
        $assunto = "Solicitação de Acesso";
        $nomeRemetente = "SGA - Sistema de Gestão de Acesso";
        
        // Envia email para o solicitante
        $dadosGestorUsuario = $fluxo->buscaDadosGestorUsuario($paramsSolicitacao['gestorUsuario']);
        $dadosFluxo = $fluxo->buscaDadosFluxo($paramsSolicitacao['idSolicitacao']);

        // Envia email para usuários em cópia.                
        if(isset($paramsSolicitacao['idAcompanhante']) && count($paramsSolicitacao['idAcompanhante']) > 0):
            foreach($paramsSolicitacao['idAcompanhante'] as $val):
                $dadosEmail = $fluxo->buscaDadosUsuariosCopia($val);
                                
                if($dadosEmail != 0):
                    $mensagem = '

                        Olá, <b>'.$dadosEmail['nome_usuario'].'</b>.<br/><br/>

                        <span style="font-size:14px;margin-top:20px">A atividade de número <b>'.$paramsSolicitacao['idSolicitacao'].'</b> '.(($dadosFluxo['idFluxo'] != 7) ? ', do usuário <b>'.$paramsSolicitacao['usuario'].'</b>' : '' ). ' em que você está acompanhando, foi rejeitada pelo gestor de '.(($dadosFluxo['idFluxo'] != 7) ? "usuários <b>".$dadosGestorUsuario['nome_usuario'] : ' grupos' ).'</b>.</span>
                        <br/><br/>';                
                    $mensagem .= '<br/><span style="font-size:14px;margin-top:20px; padding">Acompanhe a solicitação em seu painel de atividades.</span><br>';        
                    $mensagem .= '
                        <br/>
                        <br/>				
                        <a href="'.URL.'/Fluxo/centralDeTarefa" style="background: #4ca5a9;color: #ffffff; text-align: center; text-decoration: none;padding: 15px 30px; font-family: Arial, sans-serif; font-size: 16px">Ir para Central de Tarefas</a>';

                    $template = $email->getTemplate($mensagem);
                    $email->enviaEmail($nomeRemetente,$assunto, $template, $dadosEmail['email']);
                endif;
            endforeach;
        endif;                
        
        // Envia e-mail para os gestores participantes.
        $acao = 'foi rejeitada pelo gestor de usuários, <b>'.$dadosGestorUsuario['nome_usuario'].'</b>';
        $paramsSolicitacao['nomeGestorUsuario'] = $dadosGestorUsuario['nome_usuario'];
        $this->enviaEmailGestorMovimentacao($paramsSolicitacao, $acao);
        
        // Envia email para o solicitante
        $dadosSolic = $fluxo->buscaDadosSolicitante($paramsSolicitacao['idSolicitante']);
        if($dadosSolic != 0):
            $mensagem = '
                Olá, <b>'.$dadosSolic['nome_usuario'].'</b>.<br/><br/>

                <span style="font-size:14px;margin-top:20px">A atividade de número <b>'.$paramsSolicitacao['idSolicitacao'].'</b> '.(($dadosFluxo['idFluxo'] != 7) ? ', do usuário <b>'.$paramsSolicitacao['usuario'].'</b>' : '' ). ', foi rejeitada pelo gestor de '.(($dadosFluxo['idFluxo'] != 7) ? "usuários <b>".$dadosGestorUsuario['nome_usuario'] : ' grupos' ).'</b>. </span>
                <br/><br/>';                
            $mensagem .= '<br/><span style="font-size:14px;margin-top:20px; padding">Acompanhe a solicitação em seu painel de atividades.</span><br>';        
            $mensagem .= '
                <br/>
                <br/>				
                <a href="'.URL.'/Fluxo/centralDeTarefa" style="background: #4ca5a9;color: #ffffff; text-align: center; text-decoration: none;padding: 15px 30px; font-family: Arial, sans-serif; font-size: 16px">Ir para Central de Tarefas</a>';

            $template = $email->getTemplate($mensagem);
            $email->enviaEmail($nomeRemetente,$assunto, $template, $dadosSolic['email']);
        endif;
        //die('foi');
    }
    
    
    
    /**
     * Envia E-mail para os gestores participantes da solicitação
     * @param $paramsSolicitacao Array
     * @param $acao String  
     */
    public function enviaEmailGestorMovimentacao($paramsSolicitacao, $acao)
    {
        $email = new Email();
        $fluxo = new Fluxo();
        $assunto = "Solicitação de Acesso";
        $nomeRemetente = "SGA - Sistema de Gestão de Acesso";
        
        $idGestores = array();
        
        // Envia email para os gestores participantes da solicitacao
        $dadosGestores = $fluxo->buscaDadosGestorMovimentacao($paramsSolicitacao['idSolicitacao']);
        if($dadosGestores != 0):
            foreach($dadosGestores as $val):
                if(!in_array($val['idResponsavel'], $idGestores)):                    
                    array_push($idGestores, $val['idResponsavel']);
                
                    if($val['nome_usuario'] != $paramsSolicitacao['nomeGestorUsuario']):
                        $mensagem = '
                            Olá, <b>'.$val['nome_usuario'].'</b>.<br/><br/>

                            <span style="font-size:14px;margin-top:20px">A atividade de número <b>'.$paramsSolicitacao['idSolicitacao'].'</b>, do usuário <b>'.$paramsSolicitacao['usuario'].'</b>, '.$acao.'.</span>
                            <br/><br/>';                
                        $mensagem .= '<br/><span style="font-size:14px;margin-top:20px; padding">Acompanhe a solicitação em seu painel de atividades.</span><br>';        
                        $mensagem .= '
                            <br/>
                            <br/>				
                            <a href="'.URL.'/Fluxo/centralDeTarefa" style="background: #4ca5a9;color: #ffffff; text-align: center; text-decoration: none;padding: 15px 30px; font-family: Arial, sans-serif; font-size: 16px">Ir para Central de Tarefas</a>';

                        $template = $email->getTemplate($mensagem);
                        $email->enviaEmail($nomeRemetente,$assunto, $template, $val['email']);
                    endif;
                endif;
            endforeach;
        endif;
    }

    public function carregaCartasRisco()
    {
        $fluxo = new Fluxo();

        $res = $fluxo->carregaCartasRisco();

        echo json_encode($res);
    }

}