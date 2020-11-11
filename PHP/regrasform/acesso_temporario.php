<?php

class acesso_temporario  extends regrasform{
     public function __construct(){
        parent::__construct();
    }

    public function acesso_temporario($control, $idSolicitacao, $idAtividade = '', $fluxo = '', $idMovimentacao = '', $post, $from = ''){
        $fluxo = new fluxo();

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
                $idSolicitante = $_POST['idSolicitante'];
                
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
                        'idSolicitante'=>$idSolicitante,
                        'aprovacaoGestor'=>$aprovacaoGestor,
                        'tabela'=>$lista
                      );

                $dataMovimentacao = date('Y-m-d H:i:s');
                $documento = json_encode($dadosDoc,true);
                $fluxo->atualizaDocumento($idSolicitacao,$documento);
                if(!empty($obsGestor)){
                    $fluxo->cadastraMensagem($idSolicitacao,$_SESSION['nomeUsuario'],$obsGestor,$dataMovimentacao);
                }

                if($idAtividade == 5){
                    if($aprovacaoGestor == "nao"){
                        $fluxo->updateMovimento($idSolicitacao,"5");
                        $fluxo->cadastraMovimentacao($idSolicitacao,3,$dataMovimentacao,$idSolicitante,$idSolicitante,"2","");
                        $_SESSION['mensagem'] = "Atividade número ".$idSolicitacao." foi movimentado com sucesso!";
                        header("Location:".URL."/fluxo/centralDeTarefa");

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
                        header("Location:".URL."/fluxo/centralDeTarefa");

                    }   
                }

                if($idAtividade == 3){
                        $data['ativ'] = $fluxo->verificaProximaAtividade($idAtividade);
                        $fluxo->updateMovimento($idSolicitacao,"3");
                        if(!empty($obsGestor)){
                            $fluxo->cadastraMovimentacao($idSolicitacao,$data['ativ']['proximaAtiv'],$dataMovimentacao,$idSolicitante,$idGestor,"2","");
                        }
                        
                        $_SESSION['mensagem'] = "Atividade número ".$idSolicitacao." foi movimentado com sucesso!";
                        header("Location:".URL."/fluxo/centralDeTarefa");
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
        $control->loadTemplate('acesso_temporario', $data); 
    }
 }