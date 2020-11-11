<?php
date_default_timezone_set('UTC');
class Fluxo extends Model {

  public function __construct(){
  	parent::__construct();

  }

 public function ajaxSelect($sql){
      $sql = $this->db->query($sql);
      $data = array();

      if($sql->rowCount()>0){
          $data = $sql->fetchAll();
      }
      return $data;
  }

  public function cadastraNumSolicitacao($idForm, $idUsrTotvs, $idAgendamento = '', $dataMovimentacao = null){
    $dataSolicitacao = (!empty($dataMovimentacao)) ? $dataMovimentacao : date('Y-m-d H:i:s');
    //$idUsrTotvs = $_SESSION['idUsrTotvs'];
    $sql = "
        INSERT INTO 
            z_sga_fluxo_solicitacao 
        SET 
            dataSolicitacao = '$dataSolicitacao',
            idSolicitante = '$idUsrTotvs',
            idForm = '$idForm'";
    
    if($idAgendamento != ''):
        $sql .= " ,idAgendamento = $idAgendamento";
    endif;
    
    $sql = $this->db->query($sql);

    $sql4 = "SELECT max(idSolicitacao) as idSolic from z_sga_fluxo_solicitacao";
    $sql4 = $this->db->query($sql4);

    $array = array();
    if($sql4->rowCount()>0){
      $array = $sql4->fetch();
    }
    return $array;
  }

  /*
    Função que salva os dados do formulário no formato JSON.
  */
  public function criaDocumento($documento,$idForm,$idFluxo,$idSolicitacao){
    $dados = array();
    $documento = addslashes($documento);

    $sql = "INSERT INTO z_sga_fluxo_documento SET idSolicitacao = '$idSolicitacao', documento = '$documento',idForm = '$idForm',idFluxo = '$idFluxo'";
    $this->db->query($sql);
    return $this->db->lastInsertId();
  }

/*
  Atualiza a tabela de Documentos.
*/
  public function atualizaDocumento($idSolicitacao,$documento){
    $documento = addslashes($documento);
    $sql = "UPDATE z_sga_fluxo_documento SET documento = '$documento' where idSolicitacao = '$idSolicitacao'";
    $this->db->query($sql);
  }

  public function carregaCartaRisco($idCarta)
  {
    $sql = "
        SELECT 
            cartaRisco
        FROM 
            z_sga_fluxo_carta_risco 
        WHERE 
            id = $idCarta";

      //echo "<pre>";
      //die($sql);

      $sql = $this->db->query($sql);

      return $sql->fetch(PDO::FETCH_ASSOC);
  }

  /*
    Verifica proxima atividade.
  */
  public function verificaProximaAtividade($idAtiviAtual){
      $sql = "SELECT proximaAtiv, objeto FROM z_sga_fluxo_atividade where idAtividade = '$idAtiviAtual'";
      $sql = $this->db->query($sql);

      $array = array();
      if($sql->rowCount()>0){
        $array = $sql->fetch();
      }
      return $array;
  }

    /*
      Verifica atividade SI.
    */
    public function verificaAtividadeSI($idFluxo){
        $sql = "SELECT id, objeto FROM z_sga_fluxo_atividade where idFluxo = '$idFluxo' and objeto = 'criaAtividadeSI'";
        $sql = $this->db->query($sql);

        $array = array();
        if($sql->rowCount()>0){
            $array = $sql->fetch();
        }
        return $array;
    }

    /*
      Verifica proxima atividade.
    */
    public function buscaObjetoProxAtividade($idProxAtividade){
        $sql = "SELECT objeto FROM z_sga_fluxo_atividade where idAtividade = $idProxAtividade";
        $sql = $this->db->query($sql);

        $array = array();
        if($sql->rowCount()>0){
            $array = $sql->fetch();

        }
        return $array['objeto'];
    }


  /*
    Carrega os dados do documento.
  */
  public function carregaDocumento($idSolicitacao){
      $sql = "SELECT * FROM z_sga_fluxo_documento where idSolicitacao = '$idSolicitacao'";
      $sql = $this->db->query($sql);

      $array = array();
      if($sql->rowCount()>0){
        $array = $sql->fetch(PDO::FETCH_ASSOC);
      }
      return $array;
  }

  
    /**
     * Atualiza número de aprovadores no documento json do fluxo
     * @param type $idSolicitacao
     * @param type $numAprovadores
     */
    public function atualizaDocNumAprovadores($idSolicitacao, $numAprovadores)
    {
        $sql = "
            UPDATE
                z_sga_fluxo_documento
            SET
                documento = JSON_SET(documento, \"$.numAprovadores\", '$numAprovadores')
            WHERE
                idSolicitacao = $idSolicitacao
            ";
        
        try{
            $this->db->query($sql);
            
            return array(
                'return' => true
            );
        } catch (Exception $e) {
            return array(
                'return'  => false,
                'error'   => $e->getMessage()
            );
        }
        
    }

    // METODOS

    public function criaDocCartaRisco($file_name)
    {
        $hash = md5(date('d m Y H i S'));

        move_uploaded_file($file_name, "arquivos/carta_risco/" . $hash.".pdf");

        return $hash;
    }

    public function insereNovaCartaRisco($idSolicitacao, $cartaRisco)
    {
        $cartaRisco = $cartaRisco.'.pdf';
        $insert = "INSERT INTO z_sga_fluxo_carta_risco (idSolicitacao, cartaRisco) VALUES ('$idSolicitacao', '$cartaRisco')";
        $this->db->query($insert);

        $id = $this->db->lastInsertId();

        return $id;
    }

    /**
    * Atualiza aprovação ou reprovação da atividade atual
    * @param type $idAtividade
    * @param type $post
    * @return type
    */
    public function atualizaDocAprovacaoAtual($idAtividade, $post, $idMovimentacao)
    {        
        $idGestorResponsavel = $_SESSION['idUsrTotvs'];
        $rsdoc = $this->carregaDocumento($post['idSolicitacao']);
        $documento = json_decode($rsdoc['documento'], true);
        $hasReprova = false;
        $jsonSetCartaRisco = '';
        $cartaRisco = '';
        $idSolicitacao = $post['idSolicitacao'];
        
        // Valida se é usuário alternativo
        $sqlUserSub = "
            SELECT
                idUsrSerSub,
                idUsrSub
            FROM
                z_sga_fluxo_substituto
            WHERE
                idUsrSerSub = $idGestorResponsavel
                AND status = 1
        ";
        $sqlUserSub = $this->db->query($sqlUserSub);

        if($sqlUserSub->rowCount() > 0):
            $sqlUserSub = $sqlUserSub->fetch(PDO::FETCH_ASSOC);
            $idGestorResponsavel = $sqlUserSub['idUsrSerSub'];
        endif;
        
        // Busca nível de aprovação
        $sql = "
            SELECT
                objeto
            FROM
                z_sga_fluxo_atividade
            WHERE
                id = $idAtividade
        ";
        $sql = $this->db->query($sql);
        
        if($sql->rowCount() > 0):
            $sql = $sql->fetch(PDO::FETCH_ASSOC);
            
            $tipoAprov = lcfirst(str_replace('criaAtividade', '', $sql['objeto']));
            
            if(strtolower($tipoAprov) != 'si' && $tipoAprov != 'gestorUsuario' && $tipoAprov != 'gestorGrupo' && $tipoAprov != 'final'):
                //echo "<pre>";            
                $idGestores = array();
                
                foreach ($post['grupos'] as $keyGrupo => $grupo):                    
                    if($grupo['manterStatus'] == 1 && isset($post['grupos'][$keyGrupo]["aprovacao_$tipoAprov"]['aprovacao'])):
                        $sqlJsonExtract = "
                            SELECT JSON_SEARCH(
                                documento, 
                                'one',
                                \"$idGestorResponsavel\", 
                                NULL, 
                                '$.grupos[".$keyGrupo."].$tipoAprov'
                            ) AS path
                            FROM 
                                z_sga_fluxo_documento
                            WHERE
                                idSolicitacao = ".$post['idSolicitacao'];

                        //print_r($sqlJsonExtract);

                        $sqlJsonExtract = $this->db->query($sqlJsonExtract);

                        if($sqlJsonExtract->rowCount() > 0):
                            $sqlJsonExtract = $sqlJsonExtract->fetch(PDO::FETCH_ASSOC);
                            //print_r($sqlJsonExtract);

                            if(isset($_FILES['grupos']['tmp_name'][$keyGrupo]["aprovacao_$tipoAprov"]['cartaRisco']) && !empty($_FILES['grupos']['tmp_name'][$keyGrupo]["aprovacao_$tipoAprov"]['cartaRisco'])):
                                $hash = $this->criaDocCartaRisco($_FILES['grupos']['tmp_name'][$keyGrupo]["aprovacao_$tipoAprov"]['cartaRisco']);
                                $lastId =  $this->insereNovaCartaRisco($post['idSolicitacao'], $hash);
                                // VOLTAR

                                $jsonSetCartaRisco = "
                                    UPDATE 
                                        z_sga_fluxo_documento 
                                    SET 
                                        documento = JSON_SET(
                                            documento,
                                            ".str_replace('.id','.cartaRisco', $sqlJsonExtract['path']).",
                                            '$lastId'
                                        ),
                                        documento = JSON_SET(documento, \"$.exigirCartaRisco\", '0')
                                    WHERE
                                        idSolicitacao = " . $post['idSolicitacao'];

                                    $this->db->query($jsonSetCartaRisco);
                            endif;

                            $sqlUpdate = "
                                UPDATE 
                                    z_sga_fluxo_documento 
                                SET 
                                    documento = JSON_SET(
                                        documento, 
                                        ".str_replace('.id','.aprovacao', $sqlJsonExtract['path']).", 
                                        '".$post['grupos'][$keyGrupo]["aprovacao_$tipoAprov"]['aprovacao']."'
                                    ),
                                    documento = JSON_SET(
                                        documento, 
                                        ".str_replace('.id','.obs', $sqlJsonExtract['path']).", 
                                        '".$post['grupos'][$keyGrupo]["aprovacao_$tipoAprov"]['obs']."'
                                    )
                                WHERE 
                                    idSolicitacao = ".$post['idSolicitacao']."
                                    AND JSON_EXTRACT(documento, ".$sqlJsonExtract['path'].") = '$idGestorResponsavel'
                            ";
                            //echo "<pre>";
                            //die($sqlUpdate);
                            //print_r($sqlUpdate);

                            try{
                                $this->db->query($sqlUpdate);

                                if(isset($_FILES['grupos']['tmp_name'][$keyGrupo]["aprovacao_$tipoAprov"]['cartaRisco']) && !empty($_FILES['grupos']['tmp_name'][$keyGrupo]["aprovacao_$tipoAprov"]['cartaRisco'])):
                                    $sql = "
                                        UPDATE
                                            z_sga_fluxo_documento
                                        SET                                        
                                            documento = JSON_SET(documento, \"$.exigirCartaRisco\", '0')
                                        WHERE
                                            idSolicitacao = ".$post['idSolicitacao'];

                                        $this->db->query($sql);
                                endif;

                                // Grava mensagem na tabela de mensagem para histórico
                                $this->addHistoricoMsg(
                                    $post['idSolicitacao'],
                                    $post['idMovimentacao'],
                                    $post['numAtividade'],
                                    $post['gestorNome'],
                                    $grupo['idLegGrupo'].': '.$post['grupos'][$keyGrupo]["aprovacao_$tipoAprov"]['obs']);
                                
                                // Valida se o gestor já está no array de gestores.                                
                                if(!in_array($grupo['idCodGest'], $idGestores)):
                                    //array_push($idGestores, $grupo['idCodGest']);
                                    if($post['grupos'][$keyGrupo]["aprovacao_$tipoAprov"]['aprovacao'] == 'sim'):
                                        $idGestores[$idGestorResponsavel]['aprovado'][] = $grupo['idLegGrupo'].": ".((isset($grupo['programas'])) ? str_replace('|', ',', $grupo['programas']) : '');
                                    else:
                                        $idGestores[$idGestorResponsavel]['reprovado'][] = $grupo['idLegGrupo'].": ".((isset($grupo['programas'])) ? str_replace('|', ',', $grupo['programas']) : '');
                                    endif;
                                endif;

                                //print_r($idGestores);
                                //return array('return' => true);
                            } catch (Exception $e) {
                                die($e->getMessage());
                                /*return array(
                                    'return' => true,
                                    'error'  => $e->getMessage()
                                );*/

                            }

                        endif;                        
                    endif;                    
                endforeach;
                           
                $rsdoc = $this->carregaDocumento($post['idSolicitacao']);
                $documento = json_decode($rsdoc['documento'], true);
                
                foreach ($documento['grupos'] as $grp):                    
                    foreach($grp[$tipoAprov] as $val):
                        if($val['aprovacao'] == 'nao'):
                            $hasReprova = true;
                        endif;
                    endforeach;

                endforeach;
                
                // Insere no histórico para timeline
                if(count($idGestores) > 0):
                    foreach($idGestores as $key => $val):
                        $this->insereDocumentoHistorico(
                            $post['idSolicitacao'], 
                            $post['idMovimentacao'], 
                            'Aprovado', 
                            "<td>".((isset($val['aprovado'])) ? implode('|', $val['aprovado']) : '' )."</td>".
                            "<td>".((isset($val['reprovado'])) ? implode('|', $val['reprovado']) : '' )."</td>"
                        );
                    endforeach;
                    //echo "<br>";
                endif;
                //print_r($idGestores);
                return array('return' => true, 'hasReprova' => $hasReprova);
                //print_r($post);
                //die('');
                
            // Aprovação SI
            elseif(strtolower($tipoAprov) == 'si'):

                $sql = "
                    UPDATE
                        z_sga_fluxo_documento
                    SET
                        documento = JSON_SET(documento, \"$.aprovacao_si\", '".$post['aprovacao_si']."'),
                        documento = JSON_SET(documento, \"$.exigirCartaRisco\", '".$post['exigirCartaRisco']."')
                    WHERE
                        idSolicitacao = ".$post['idSolicitacao'];
                //print_r($sql);
                try{
                    $this->db->query($sql);                                                                                                    
                   
                } catch (Exception $e) {
                    return array(
                        'return'  => false,
                        'error'   => $e->getMessage()
                    );
                }
                
                $si = $this->buscaSI();
                
                $aprovacao = (($post['aprovacao_si'] == 1) ? 'Aprovado' : 'Reprovado');
                $this->insereDocumentoHistorico($post['idSolicitacao'], $post['idMovimentacao'], $aprovacao, '');
                
                // Insere no histórico para timeline
                $this->addHistoricoMsg($post['idSolicitacao'], $post['idMovimentacao'], $post['numAtividade'], $si[0]['nome_usuario'], (isset($post['obs_historico']) ? $post['obs_historico'] : ""));
                
                if($post['aprovacao_si'] == 'nao' || $post['aprovacao_si'] == 0):
                    $hasReprova = true;
                endif;
                
                return array('return' => true, 'hasReprova' => $hasReprova);                
            
            // Aprovação Gestor de Usuários
            elseif(strtolower($tipoAprov) == 'gestorusuario'):
                //echo "<pre>";
                
                $idGrupoProgHist = array();
                
                //print_r($post);
                
                // Percorre os grupos e atualiza manterStatus
                if(isset($post['grupos'])):
                    foreach ($post['grupos'] as $keyGrupo => $grupo):
                        //print_r($grupo);
                        $sqlJsonExtract = "
                            SELECT CONCAT(SUBSTR(JSON_SEARCH(
                                documento, 
                                'one', 
                                \"".$grupo['idGrupo']."\", 
                                NULL, 
                                '$.grupos[*].idGrupo'
                            ), 2, 40), '.idGrupo') AS path
                            FROM 
                                z_sga_fluxo_documento
                            WHERE
                                idSolicitacao = ".$post['idSolicitacao'];
                            //print_r($sqlJsonExtract);
                        $sqlJsonExtract = $this->db->query($sqlJsonExtract);
                        
                        if($sqlJsonExtract->rowCount() > 0):
                            $sqlJsonExtract = $sqlJsonExtract->fetch(PDO::FETCH_ASSOC);

                            //print_r($sqlJsonExtract);
                            $sqlUpdate = "
                                UPDATE 
                                    z_sga_fluxo_documento 
                                SET 
                                    documento = JSON_SET(
                                        documento, 
                                        \"".str_replace('.idGrupo".idGrupo','.manterStatus', $sqlJsonExtract['path'])."\", 
                                        '".$grupo['manterStatus']."'
                                    )
                                WHERE 
                                    idSolicitacao = ".$post['idSolicitacao']."
                                    AND JSON_EXTRACT(documento, \"".str_replace('.idGrupo".idGrupo','.idGrupo', $sqlJsonExtract['path'])."\") = '".$grupo['idGrupo']."'
                            ";
                            
                            //print_r($sqlUpdate);
                            try{
                                $this->db->query($sqlUpdate);
                                                                                        
                                // Insere no array de historico
                                if($grupo['manterStatus'] == 1 || $post['idFluxo'] == 5):
                                    $idGrupoProgHist['aprovado'][] = $grupo['idLegGrupo'].((isset($grupo['programas'])) ? ": ".str_replace('|', ',', $grupo['programas']) : '' );
                                //else:
                                    //$idGrupoProgHist['reprovado'][] = $grupo['idLegGrupo'].((isset($grupo['programas'])) ? ": ".str_replace('|', ',', $grupo['programas']) : '' );
                                endif;
                                //print_r($idGestores);
                                
                                /*if($grupo['manterStatus'] == 'nao' || $grupo['aprovacao'] == 'nao'):
                                    $hasReprova = true;
                                endif;*/
                                
                                //return array('return' => true);
                                continue;
                            } catch (Exception $e) {                            
                                //die($e->getMessage());
                                return array(
                                    'return' => false,
                                    'error'  => $e->getMessage()
                                );
                            }
                        endif;                    
                    endforeach;
                endif;

                // Se fluxo for de concessão e revogação idFluxo = 8
                if($post['idFluxo'] == 8):
                    //echo "<pre>";
                    //die(print_r($documento['remover']));
                    foreach ($documento['remover'] as $grupo):
                        $statusRemover = 0;
                        foreach($post['remover'] as $remover):
                            //echo $remover['removerStatus']." = ". $grupo['idGrupoRemover']."<br>";
                            if($remover['removerStatus'] == $grupo['idGrupoRemover']):
                                $statusRemover = $remover['removerStatus'];
                                break;
                            endif;
                        endforeach;

                        $sqlJsonExtract = "
                            SELECT CONCAT(SUBSTR(JSON_SEARCH(
                                documento, 
                                'one', 
                                \"".$grupo['idGrupoRemover']."\", 
                                NULL, 
                                '$.remover[*].idGrupoRemover'
                            ), 2, 40), '.idGrupo') AS path
                            FROM 
                                z_sga_fluxo_documento
                            WHERE
                                idSolicitacao = ".$post['idSolicitacao'];
                            //print_r($sqlJsonExtract);
                        $sqlJsonExtract = $this->db->query($sqlJsonExtract);
                        
                        if($sqlJsonExtract->rowCount() > 0):
                            $sqlJsonExtract = $sqlJsonExtract->fetch(PDO::FETCH_ASSOC);

                            //print_r($sqlJsonExtract);
                            $sqlUpdate = "
                                UPDATE 
                                    z_sga_fluxo_documento 
                                SET 
                                    documento = JSON_SET(
                                        documento, 
                                        \"".str_replace('.idGrupoRemover".idGrupo','.removerStatus', $sqlJsonExtract['path'])."\", 
                                        '".$statusRemover."'
                                    )
                                WHERE 
                                    idSolicitacao = ".$post['idSolicitacao']."
                                    AND JSON_EXTRACT(documento, \"".str_replace('.idGrupoRemover".idGrupo','.idGrupoRemover', $sqlJsonExtract['path'])."\") = '".$grupo['idGrupoRemover']."'";
                            //print_r($sqlUpdate);
                            try{
                                $this->db->query($sqlUpdate);
                                                                                        
                                // Insere no array de historico
                                // if($grupo['removerStatus'] != 0):
                                //     $idGrupoProgHist['R'][] = $grupo['idLegGrupo'].((isset($grupo['programas'])) ? ": ".str_replace('|', ',', $grupo['programas']) : '' );
                                // //else:
                                //     //$idGrupoProgHist['reprovado'][] = $grupo['idLegGrupo'].((isset($grupo['programas'])) ? ": ".str_replace('|', ',', $grupo['programas']) : '' );
                                // endif;
                                //print_r($idGestores);
                                
                                /*if($grupo['manterStatus'] == 'nao' || $grupo['aprovacao'] == 'nao'):
                                    $hasReprova = true;
                                endif;*/
                                
                                //return array('return' => true);
                                //continue;
                            } catch (Exception $e) {                            
                                //die($e->getMessage());
                                return array(
                                    'return' => false,
                                    'error'  => $e->getMessage()
                                );
                            }
                        endif;                    
                    endforeach;
                    //die;
                endif;
                // Fim fluxo de concessão e revogação idFluxo = 8
                
                if(isset($_FILES['remove']['tmp_name']['cartaRisco']) && !empty($_FILES['remove']['tmp_name']['cartaRisco'])):
                    $hash = $this->criaDocCartaRisco($_FILES['remove']['tmp_name']['cartaRisco']);
                    $lastId =  $this->insereNovaCartaRisco($post['idSolicitacao'], $hash);
                    // VOLTAR

                    $jsonSetCartaRisco = "
                        UPDATE 
                            z_sga_fluxo_documento 
                        SET 
                            documento = JSON_SET(
                                documento,
                                '$.cartaRisco',
                                '$lastId'
                            ),
                            documento = JSON_SET(documento, \"$.exigirCartaRisco\", '0')
                        WHERE
                            idSolicitacao = " . $post['idSolicitacao'];

                        $this->db->query($jsonSetCartaRisco);
                endif;


                if(count($idGrupoProgHist) > 0):                    
                    $this->insereDocumentoHistorico(
                        $post['idSolicitacao'],
                        $post['idMovimentacao'],
                        'Aprovado', 
                        "<td>".implode('|', $idGrupoProgHist['aprovado'])."</td>".
                        "<td></td>"
                        //"<td>".implode('|', $idGrupoProgHist['reprovado'])."</td>"
                    );
                    //echo "<br>";
                endif; 
                
                // Insere no histórico para timeline
                $this->addHistoricoMsg($post['idSolicitacao'], $post['idMovimentacao'], $post['numAtividade'], $post['gestorUsuario'], (isset($post['obs_historico']) ? $post['obs_historico'] : ""));
                
                return array('return' => true, 'hasReprova' => $hasReprova);
                //die('');
                
            // Aprovação Gestor de grupo
            elseif(strtolower($tipoAprov) == 'gestorgrupo'):
                //die($tipoAprov);
                //echo "<pre>";
                //print_r($_SESSION);
                $idGestores = array();                
                // Percorre os grupos e atualiza manterStatus
                foreach ($documento['grupos'] as $key => $grupo):                    
                    //echo $grupo['codGest'].' == '.$_SESSION['codUsuario']."<br>";
                    if($grupo['manterStatus'] == 1 && $grupo['codGest'] == $_SESSION['codUsuario']):                                                
                        $sqlJsonExtract = "
                            SELECT CONCAT(SUBSTR(JSON_SEARCH(
                                documento, 
                                'one', 
                                \"".$grupo['idGrupo']."\", 
                                NULL, 
                                '$.grupos[*].idGrupo'
                            ), 2, 40), '.idGrupo') AS path
                            FROM 
                                z_sga_fluxo_documento
                            WHERE
                                idSolicitacao = ".$post['idSolicitacao'];
                    
                        //print_r($sqlJsonExtract);
                    
                        $sqlJsonExtract = $this->db->query($sqlJsonExtract);
                        
                        if($sqlJsonExtract->rowCount() > 0):
                            $sqlJsonExtract = $sqlJsonExtract->fetch(PDO::FETCH_ASSOC);
                            //print_r($sqlJsonExtract);                            

                            // Insere carta de risco
                            if(isset($_FILES['grupos']['tmp_name'][0]['cartaRisco']) && !empty($_FILES['grupos']['tmp_name'][0]['cartaRisco'])):
                                $hash = $this->criaDocCartaRisco($_FILES['grupos']['tmp_name'][0]['cartaRisco']);
                                $lastId =  $this->insereNovaCartaRisco($post['idSolicitacao'], $hash);                            

                                // Prepara o json
                                $jsonSetCartaRisco = ", documento = JSON_SET(
                                    documento,
                                    \"".str_replace('.idGrupo".idGrupo','.cartaRisco', $sqlJsonExtract['path'])."\",
                                        '".$lastId."'
                                    ),
                                    documento = JSON_SET(documento, \"$.exigirCartaRisco\", '0')";
                            endif;

                            $sqlUpdate = "
                                UPDATE 
                                    z_sga_fluxo_documento 
                                SET 
                                    documento = JSON_SET(
                                        documento, 
                                        \"".str_replace('.idGrupo".idGrupo','.aprovacao', $sqlJsonExtract['path'])."\",
                                        '".$post['grupos'][$key]['aprovacao_grupo']."'
                                    ),
                                    documento = JSON_SET(
                                        documento, 
                                        \"".str_replace('.idGrupo".idGrupo','.obs', $sqlJsonExtract['path'])."\",
                                        '".$post['grupos'][$key]['obs_grupo']."'
                                    )
                                    $jsonSetCartaRisco
                                WHERE 
                                    idSolicitacao = ".$post['idSolicitacao']."
                                    AND JSON_EXTRACT(documento, \"".str_replace('.idGrupo".idGrupo','.idCodGest', $sqlJsonExtract['path'])."\") = '".$idGestorResponsavel."'
                            ";

                            //print_r($sqlUpdate);

                            try{
                                $this->db->query($sqlUpdate);

                                // Grava mensagem na tabela de mensagem para histórico
                                $this->addHistoricoMsg(
                                    $post['idSolicitacao'], 
                                    $post['idMovimentacao'], 
                                    $post['numAtividade'], 
                                    $post['grupos'][$key]['nomeGestor'], 
                                    $grupo['idLegGrupo'].': '.$post['grupos'][$key]['obs_grupo']);
                                
                                // Valida se o gestor já está no array de gestores.                                
                                if(!in_array($grupo['idCodGest'], $idGestores)):
                                    //array_push($idGestores, $grupo['idCodGest']);
                                    if($post['grupos'][$key]['aprovacao_grupo'] == 'sim'):
                                        $idGestores[$grupo['idCodGest']]['aprovado'][] = "<strong>".$grupo['idLegGrupo']."</strong>".(isset($grupo['programas']) ? ": ".str_replace('|', ',', $grupo['programas']) : '' );
                                    else:                                        
                                        $idGestores[$grupo['idCodGest']]['reprovado'][] = "<strong>".$grupo['idLegGrupo']."</strong>".(isset($grupo['programas']) ? ": ".str_replace('|', ',', $grupo['programas']) : '' );
                                    endif;
                                endif;
                                //print_r($idGestores);                                                                
                                //return array('return' => true);
                            } catch (Exception $e) { 
                                //die($e->getMessage());
                                return array(
                                    'return' => false,
                                    'error'  => $e->getMessage()
                                );
                            }
                        endif;
                    endif;                    
                endforeach;                
                //die;
                // Insere no histórico para timeline
                if(count($idGestores) > 0):
                    foreach($idGestores as $key => $val):
                        $this->insereDocumentoHistorico(
                            $post['idSolicitacao'], 
                            $post['idMovimentacao'], 
                            (isset($val['aprovado']) ? 'Aprovado' : 'Reprovado' ), 
                            "<td>".((isset($val['aprovado'])) ? implode('|', $val['aprovado']) : '' )."</td>".
                            "<td>".((isset($val['reprovado'])) ? implode('|', $val['reprovado']) : '' )."</td>"
                        );
                    endforeach;
                    //echo "<br>";
                endif;
                
                $rsdoc = $this->carregaDocumento($post['idSolicitacao']);
                $documento = json_decode($rsdoc['documento'], true);
                
                foreach ($documento['grupos'] as $grupo):                    
                    if($grupo['aprovacao'] == 'nao'):
                        $hasReprova = true;
                    endif;                    
                endforeach;
                
                //print_r($idGestores);
                //die;
                return array('return' => true, 'hasReprova' => $hasReprova);
                //die('');
            endif;
        else:
            return array();
        endif;
    }

    /**
     * Atualiza aprovação ou reprovação da atividade atual
     * @param type $idAtividade
     * @param type $post
     * @return type
     */
    public function atualizaDocAprovacaoAtualGrupoPrograma($idAtividade, $post, $idMovimentacao)
    {

        $idGestorResponsavel = $_SESSION['idUsrTotvs'];
        $rsdoc = $this->carregaDocumento($post['idSolicitacao']);
        $documento = json_decode($rsdoc['documento'], true);
        $hasReprova = false;
        $jsonSetCartaRisco= '';

        // Valida se é usuário alternativo
        $sqlUserSub = "
            SELECT
                idUsrSerSub,
                idUsrSub
            FROM
                z_sga_fluxo_substituto
            WHERE
                idUsrSerSub = $idGestorResponsavel
                AND status = 1
        ";
        $sqlUserSub = $this->db->query($sqlUserSub);

        if($sqlUserSub->rowCount() > 0):
            $sqlUserSub = $sqlUserSub->fetch(PDO::FETCH_ASSOC);
            $idGestorResponsavel = $sqlUserSub['idUsrSerSub'];
        endif;

        // Busca nível de aprovação
        $sql = "
            SELECT
                objeto
            FROM
                z_sga_fluxo_atividade
            WHERE
                id = $idAtividade
        ";
        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0):
            $sql = $sql->fetch(PDO::FETCH_ASSOC);

            $tipoAprov = lcfirst(str_replace('criaAtividade', '', $sql['objeto']));

            if(strtolower($tipoAprov) != 'si' && $tipoAprov != 'gestorGrupo' && $tipoAprov != 'final'):
                //echo "<pre>";
                $idGestores = array();

                foreach ($post['programas'] as $keyProg => $prog):
                    if($prog['manterStatus'] == 1 && isset($post['programas'][$keyProg]["aprovacao_$tipoAprov"]['aprovacao'])):
                        $sqlJsonExtract = "
                            SELECT JSON_SEARCH(
                                documento, 
                                'one', 
                                \"$idGestorResponsavel\", 
                                NULL, 
                                '$.programas[".$keyProg."].$tipoAprov'
                            ) AS path
                            FROM 
                                z_sga_fluxo_documento
                            WHERE
                                idSolicitacao = ".$post['idSolicitacao'];

                        //print_r($sqlJsonExtract);

                        $sqlJsonExtract = $this->db->query($sqlJsonExtract);

                        if($sqlJsonExtract->rowCount() > 0):
                            $sqlJsonExtract = $sqlJsonExtract->fetch(PDO::FETCH_ASSOC);
                            //print_r($sqlJsonExtract);
                          if(isset($_FILES['programas']['tmp_name'][$keyProg]["aprovacao_$tipoAprov"]['cartaRisco']) && !empty($_FILES['programas']['tmp_name'][$keyProg]["aprovacao_$tipoAprov"]['cartaRisco'])):
                                $jsonSetCartaRisco = ", documento = JSON_SET(
                                    documento,
                                    ".str_replace('.id','.cartaRisco', $sqlJsonExtract['path']).",
                                        '".str_replace(['\n', '\r' , '\\'], ['', '', ''], chunk_split(base64_encode(file_get_contents($_FILES['programas']['tmp_name'][$keyProg]["aprovacao_$tipoAprov"]['cartaRisco']))))."'
                                    )";
                            endif;

                            $sqlUpdate = "
                                UPDATE 
                                    z_sga_fluxo_documento 
                                SET 
                                    documento = JSON_SET(
                                        documento, 
                                        ".str_replace('.id','.aprovacao', $sqlJsonExtract['path']).", 
                                        '".$post['programas'][$keyProg]["aprovacao_$tipoAprov"]['aprovacao']."'
                                    ),
                                    documento = JSON_SET(
                                        documento, 
                                        " . str_replace('.id', '.obs', $sqlJsonExtract['path']) . ", 
                                        '" . $post['programas'][$keyProg]["aprovacao_$tipoAprov"]['obs'] . "'
                                    )
                                    $jsonSetCartaRisco
                                WHERE 
                                    idSolicitacao = ".$post['idSolicitacao']."
                                    AND JSON_EXTRACT(documento, ".$sqlJsonExtract['path'].") = '$idGestorResponsavel'
                            ";
                            //print_r($sqlUpdate);

                            try{
                                $this->db->query($sqlUpdate);

                                if(isset($_FILES['programas']['tmp_name'][0]["aprovacao_$tipoAprov"]['cartaRisco']) && !empty($_FILES['programas']['tmp_name'][0]["aprovacao_$tipoAprov"]['cartaRisco'])):
                                    $sql = "
                                        UPDATE
                                            z_sga_fluxo_documento
                                        SET                                        
                                            documento = JSON_SET(documento, \"$.exigirCartaRisco\", '0')
                                        WHERE
                                            idSolicitacao = ".$post['idSolicitacao'];

                                    $this->db->query($sql);
                                endif;

                                // Grava mensagem na tabela de mensagem para histórico
                                $this->addHistoricoMsg(
                                    $post['idSolicitacao'],
                                    $post['idMovimentacao'],
                                    $post['numAtividade'],
                                    $post['gestorNome'],
                                    $post['programas'][$keyProg]['codProg'].': '.$post['programas'][$keyProg]["aprovacao_$tipoAprov"]['obs']);

                                // Valida se o gestor já está no array de gestores.
                                if(!in_array($idGestorResponsavel, $idGestores)):
                                    //array_push($idGestores, $grupo['idCodGest']);
                                    if($post['programas'][$keyProg]["aprovacao_$tipoAprov"]['aprovacao'] == 'sim'):
                                        $idGestores[$idGestorResponsavel]['aprovado'][] = $prog['codProg'];
                                    else:
                                        $idGestores[$idGestorResponsavel]['reprovado'][] = $prog['codProg'];
                                    endif;
                                endif;

                                //print_r($idGestores);
                                //return array('return' => true);
                            } catch (Exception $e) {
                                die($e->getMessage());
                                /*return array(
                                    'return' => true,
                                    'error'  => $e->getMessage()
                                );*/

                            }

                        endif;
                    endif;
                endforeach;

                $rsdoc = $this->carregaDocumento($post['idSolicitacao']);
                $documento = json_decode($rsdoc['documento'], true);

                foreach ($documento['programas'] as $grp):
                    foreach($grp[$tipoAprov] as $val):
                        if($val['aprovacao'] == 'nao'):
                            $hasReprova = true;
                        endif;
                    endforeach;

                endforeach;

                // Insere no histórico para timeline
                if(count($idGestores) > 0):
                    foreach($idGestores as $key => $val):
                        $this->insereDocumentoHistorico(
                            $post['idSolicitacao'],
                            $post['idMovimentacao'],
                            'Aprovado',
                            "<td>".((isset($val['aprovado'])) ? implode('|', $val['aprovado']) : '' )."</td>".
                            "<td>".((isset($val['reprovado'])) ? implode('|', $val['reprovado']) : '' )."</td>"
                        );
                    endforeach;
                    //echo "<br>";
                endif;
                //print_r($idGestores);
                return array('return' => true, 'hasReprova' => $hasReprova);
            //print_r($post);
            //die('');

            // Aprovação SI
            elseif(strtolower($tipoAprov) == 'si'):
                $sql = "
                    UPDATE
                        z_sga_fluxo_documento
                    SET
                        documento = JSON_SET(documento, \"$.aprovacao_si\", '".$post['aprovacao_si']."'),
                        documento = JSON_SET(documento, \"$.exigirCartaRisco\", '".$post['exigirCartaRisco']."')
                    WHERE
                        idSolicitacao = ".$post['idSolicitacao'];

                try{
                    $this->db->query($sql);

                } catch (Exception $e) {
                    return array(
                        'return'  => false,
                        'error'   => $e->getMessage()
                    );
                }

                $si = $this->buscaSI();

                $aprovacao = (($post['aprovacao_si'] == 1) ? 'Aprovado' : 'Reprovado');
                $this->insereDocumentoHistorico($post['idSolicitacao'], $post['idMovimentacao'], $aprovacao, '');

                // Insere no histórico para timeline
                $this->addHistoricoMsg($post['idSolicitacao'], $post['idMovimentacao'], $post['numAtividade'], $si[0]['nome_usuario'], (isset($post['obs_historico']) ? $post['obs_historico'] : ""));

                if($post['aprovacao_si'] == 'nao' || $post['aprovacao_si'] == '0'):
                    $hasReprova = true;
                endif;

                return array('return' => true, 'hasReprova' => $hasReprova);
            // Aprovação Gestor de grupo
            elseif(strtolower($tipoAprov) == 'gestorgrupo'):
                //echo "<pre>";
                //print_r($_SESSION);
                $idGestores = array();
                // Percorre os grupos e atualiza manterStatus
                foreach ($documento['programas'] as $key => $prog):
                    if($documento['codGest'] == $_SESSION['codUsuario']):
                        $sqlJsonExtract = "
                            SELECT CONCAT(SUBSTR(JSON_SEARCH(
                                documento, 
                                'one', 
                                \"".$prog['idProg']."\", 
                                NULL, 
                                '$.programas[*].idProg'
                            ), 2, 40), '.idProg') AS path
                            FROM 
                                z_sga_fluxo_documento
                            WHERE
                                idSolicitacao = ".$post['idSolicitacao'];

                        //print_r($sqlJsonExtract);

                        $sqlJsonExtract = $this->db->query($sqlJsonExtract);

                        if($sqlJsonExtract->rowCount() > 0):
                            $sqlJsonExtract = $sqlJsonExtract->fetch(PDO::FETCH_ASSOC);
                            //print_r($sqlJsonExtract);
                            //print_r($post);

                            if(isset($_FILES['programas']['tmp_name'][0]['cartaRisco']) && !empty($_FILES['programas']['tmp_name'][0]['cartaRisco'])):
                                $jsonSetCartaRisco = ", documento = JSON_SET(
                                    documento,
                                    \"".str_replace('.idProg".idProg','.cartaRisco', $sqlJsonExtract['path'])."\",
                                        '".str_replace(['\n', '\r' , '\\'], ['', '', ''], chunk_split(base64_encode(file_get_contents($_FILES['programas']['tmp_name'][0]['cartaRisco']))))."'
                                    )";
                            endif;

                            $sqlUpdate = "
                                UPDATE 
                                    z_sga_fluxo_documento 
                                SET 
                                    documento = JSON_SET(
                                        documento, 
                                        \"".str_replace('.idProg".idProg','.manterStatus', $sqlJsonExtract['path'])."\",
                                        '".$prog['manterStatus']."'
                                    ),
                                    documento = JSON_SET(
                                        documento, 
                                        \"".str_replace('.idProg".idProg','.obs', $sqlJsonExtract['path'])."\",
                                        '".(isset($post['obs_historico']) ? $post['obs_historico'] : "")."'
                                    )
                                    $jsonSetCartaRisco
                                WHERE 
                                    idSolicitacao = ".$post['idSolicitacao']."
                                    AND JSON_EXTRACT(documento, \"$.idCodGest\") = '".$idGestorResponsavel."'
                            ";

                            //print_r($sqlUpdate);
                            try{
                                $this->db->query($sqlUpdate);

                                if(isset($_FILES['programas']['tmp_name'][0]["aprovacao_$tipoAprov"]['cartaRisco']) && !empty($_FILES['programas']['tmp_name'][0]["aprovacao_$tipoAprov"]['cartaRisco'])):
                                    $sql = "
                                        UPDATE
                                            z_sga_fluxo_documento
                                        SET                                        
                                            documento = JSON_SET(documento, \"$.exigirCartaRisco\", '0')
                                        WHERE
                                            idSolicitacao = ".$post['idSolicitacao'];

                                    $this->db->query($sql);
                                endif;

                                // Grava mensagem na tabela de mensagem para histórico
                                $this->addHistoricoMsg(
                                    $post['idSolicitacao'],
                                    $post['idMovimentacao'],
                                    $post['numAtividade'],
                                    $post['nomeGestor'],
                                    $documento['idLegGrupo'].': '.(isset($post['obs_historico']) ? $post['obs_historico'] : ""));

                                //array_push($idGestores, $grupo['idCodGest']);
                                if($prog['manterStatus'] == '1'):
                                    $idGestores[$post['idCodGest']]['aprovado'][] = $prog['codProg'];
                                else:
                                    $idGestores[$post['idCodGest']]['reprovado'][] = $prog['codProg'];
                                endif;
                                //die;
                                //print_r($idGestores);
                                //return array('return' => true);
                            } catch (Exception $e) {
                                die($e->getMessage());
                                return array(
                                    'return' => false,
                                    'error'  => $e->getMessage()
                                );
                            }
                        endif;
                    endif;
                endforeach;

                // Insere no histórico para timeline
                //print_r($idGestores[$post['idCodGest']]['aprovado']);die;
                if(count($idGestores) > 0):
                    //foreach($idGestores as $key => $val):
                        $this->insereDocumentoHistorico(
                            $post['idSolicitacao'],
                            $post['idMovimentacao'],
                            'Aprovado',
                            "<td>".implode('|', $idGestores[$post['idCodGest']]['aprovado'])."</td>".
                            "<td>".((isset($idGestores[$post['idCodGest']]['reprovado'])) ? implode('|', $idGestores[$post['idCodGest']]['reprovado']) : '')."</td>"
                        );
                    //endforeach;
                    //echo "<br>";
                endif;

                return array('return' => true, 'hasReprova' => false);
                //die('');
            endif;
        else:
            return array();
        endif;
    }

  public function cadastraMovimentacao($idSolicitacao,$idAtividade,$dataMovimentacao,$idSolicitante,$idResponsavel,$form,$banco){      
      $sql = "
        INSERT INTO 
            z_sga_fluxo_movimentacao 
        SET 
            idSolicitacao = '$idSolicitacao',
            idAtividade = '$idAtividade', 
            dataMovimentacao = '$dataMovimentacao', 
            idSolicitante = '$idSolicitante', 
            idResponsavel = '$idResponsavel',
            form = '$form',
            banco = '$banco'";
    //die($sql);
    try{
        $this->db->query($sql);
        $this->insereDocumentoHistorico($idSolicitacao, $this->db->lastInsertId(), 'pendente de aprovação', '');
    } catch (Exception $ex) {
        
    }
  }

  public function finalizaSolicitacao($idSolicitacao,$dataMovimentacao){
    $sql = "
        UPDATE 
            z_sga_fluxo_solicitacao 
        SET 
            status = '0',
            dataFim = '$dataMovimentacao' 
        WHERE 
            idSolicitacao = '$idSolicitacao'";
    
    try{
        $this->db->query($sql);            
            
        return array('return' => true);
    } catch (Exception $e) {
        return array(
            'return' => false,
            'error'  => $e->getMessage()
        );
    }
    
  }

  public function updateMovimento($solic,$ativ, $idResponsavel = ""){
      setlocale(LC_TIME, 'pt_BR');
        date_default_timezone_set('America/Sao_Paulo');
      $whereIdResponsavel = (!empty($idResponsavel)) ? " AND idResponsavel = $idResponsavel" : '';
        $sql = "
            UPDATE
                z_sga_fluxo_movimentacao
            SET
                status = '0',
                dataAcao = '".date('Y-m-d H:i:s')."'
            WHERE
                idSolicitacao = '$solic'
                and idAtividade = '$ativ'
                $whereIdResponsavel";
        $this->db->query($sql);
  }
  
   public function statusMovimentoToAtivo($solic,$ativ, $idResponsavel = "", $idMovimentacao){
      $whereIdResponsavel = (!empty($idResponsavel)) ? " AND idResponsavel = $idResponsavel" : '';
    $sql = "
        UPDATE
            z_sga_fluxo_movimentacao
        SET
            status = '1',
            dataAcao = '".date('Y-m-d H:i:s')."'
        WHERE
            idSolicitacao = '$solic'
            and idAtividade = '$ativ'
            AND idMovimentacao = $idMovimentacao
            $whereIdResponsavel";    
    try{
        $this->db->query($sql);
        
        $sql = "
            UPDATE
                z_sga_fluxo_solicitacao
            SET
                status = '1',
                dataFim = NULL
            WHERE
                idSolicitacao = '$solic'";
        
        $this->db->query($sql);
        
    } catch (Exception $e) {
        die($e->getMessage());
    }
    
  }

  public function callSPSgaRefreshVmUsuariosProcessosRiscos()
  {
      $this->db->query('CALL sp_sga_refresh_vm_usuarios_processos_riscos()');
  }

  public function updateRestruturacaoDeAcesso($idSolicitacao,$aprovacao){
    $sql = "UPDATE z_sga_fluxo_aprovacaorestruturacao SET aprovacao = '$aprovacao' where idSolicitacao = '$idSolicitacao'";
    $this->db->query($sql);
  }

  public function cadastraMensagem($idSolicitacao,$autor,$observacao,$dataMovimentacao){
    $sql = "INSERT INTO z_sga_fluxo_mensagem SET idSolicitacao = '$idSolicitacao', autor = '$autor', msg = '$observacao', dataCriacao = '$dataMovimentacao'";
    $this->db->query($sql);
  }

  public function carregaDadosRestruturacao($idSolicitacao){
    $sql4 = "SELECT * from z_sga_fluxo_aprovacaorestruturacao where idSolicitacao = '$idSolicitacao'";
    $sql4 = $this->db->query($sql4);

    $array = array();
    if($sql4->rowCount()>0){
      $array = $sql4->fetch();
    }
    return $array;
  }

  /*
    Carrega todas as mensagens de aprovações.
  */
  public function carregaMensagem($idSolicitacao){
    $sql4 = "SELECT * from z_sga_fluxo_mensagem where idSolicitacao = '$idSolicitacao'";
    $sql4 = $this->db->query($sql4);

    $array = array();
    if($sql4->rowCount()>0){
      $array = $sql4->fetchAll();
    }
    return $array;
  }

    /**
     * Retorna os dados da função
     * @param type $idFuncao
     * @return type
     */
    public function buscaDadosFuncao($idFuncao)
    {
        $query = "SELECT * FROM z_sga_manut_funcao WHERE idFuncao = $idFuncao";
        
        try{
            $rs = $this->db->query($query);
            if($rs->rowCount() > 0):
                return $rs->fetch(PDO::FETCH_ASSOC);                
            endif;
        } catch (Exception $e) {
            return $e->getMessage();
        }
        
        return array();
    }

  public function carregaAtividade($id){
    $sql = "SELECT 
        solic.idSolicitacao,
        solic.dataSolicitacao,
        form.url,
        form.idForm,
        form.descricao,
        usr.nome_usuario,
        ativ.descricao as descAtividade,
        mov.idResponsavel,
        mov.idAtividade,
        mov.idMovimentacao,
        mov.idSolicitante,

        (SELECT nome_usuario FROM z_sga_usuarios WHERE z_sga_usuarios_id IN(
            SELECT
                REPLACE(JSON_EXTRACT(documento, \"$.idusuario\"),'\"','') AS z_sga_usuarios_id                                                                        
            FROM
                z_sga_fluxo_documento                                                                                                     
            WHERE
                idSolicitacao = solic.idSolicitacao
        )) AS usuarios,

        (SELECT h.status FROM z_sga_fluxo_documento_historico as h 
            WHERE 
                h.idMovimentacao = IF(
                    (SELECT count(h.idSolicitacao) FROM z_sga_fluxo_documento_historico as h 
                        WHERE 
                            h.idSolicitacao = solic.idSolicitacao
                    ) = 1, 
                    mov.idMovimentacao,
                    IF(
                        mov.idMovimentacao - 1 = 0, 
                        (mov.idMovimentacao), 
                        (mov.idMovimentacao -1)
                    )
                ) AND
                h.idSolicitacao = solic.idSolicitacao
            ORDER BY h.status
            LIMIT 1
        ) AS HStatus
        FROM
        z_sga_fluxo_solicitacao as solic, 
        z_sga_fluxo_movimentacao as mov,
        z_sga_fluxo_form as form,
        z_sga_usuarios as usr,
        z_sga_fluxo_atividade as ativ
        where 
        solic.idSolicitacao = mov.idSolicitacao AND
        usr.z_sga_usuarios_id = mov.idResponsavel AND
        mov.idAtividade = ativ.idAtividade AND
        mov.status = '1' AND
        form.idForm = mov.form AND
        mov.idResponsavel = '$id'
        #AND CONCAT(CURDATE(), ' ', CURTIME()) >= mov.dataMovimentacao";
    $sql = $this->db->query($sql);
    $dados = array();
    if($sql->rowCount()>0){
      $dados = $sql->fetchAll();
    }
    return $dados;
  }

  public function carregaAtividadeFinalizada($idSolicitante){
    $where = '';
    if(isset($_SESSION['acesso']) && $_SESSION['acesso'] != 'SI'):
      $where = " and solic.idSolicitante = '$idSolicitante'";
  endif;

    $sql = "SELECT
        solic.idSolicitacao,
        solic.dataSolicitacao,
        solic.dataFim,
        solic.idSolicitante,
        form.url,
        form.descricao,
        usr.nome_usuario,
        (SELECT nome_usuario FROM z_sga_usuarios WHERE z_sga_usuarios_id IN(
            SELECT
                REPLACE(JSON_EXTRACT(documento, \"$.idusuario\"),'\"','') AS z_sga_usuarios_id                                
            FROM
                z_sga_fluxo_documento                                            
            WHERE
                idSolicitacao = solic.idSolicitacao
        )) AS usuarios,

        (SELECT h.status FROM z_sga_fluxo_documento_historico as h 
            WHERE 
                h.idMovimentacao = IF(
                    (SELECT count(h.idSolicitacao) FROM z_sga_fluxo_documento_historico as h 
                        WHERE 
                            h.idSolicitacao = solic.idSolicitacao
                    ) = 1, 
                    mov.idMovimentacao,
                    IF(
                        mov.idMovimentacao - 1 = 0, 
                        (mov.idMovimentacao), 
                        (mov.idMovimentacao -1)
                    )
                ) AND
                h.idSolicitacao = solic.idSolicitacao
            ORDER BY h.status 
            LIMIT 1
        ) AS HStatus
        FROM
        z_sga_fluxo_movimentacao as mov,
        z_sga_fluxo_solicitacao as solic, 
        z_sga_fluxo_form as form,
        z_sga_usuarios as usr
        where 
        solic.idform = form.idform and
        solic.status = 0 and
        usr.z_sga_usuarios_id = solic.idSolicitante 
        $where";
    $sql = $this->db->query($sql);
    $array = array();
    if($sql->rowCount()>0){
      $array = $sql->fetchAll();
    }
    return $array;
  }

  public function carregaAtividadeSolicitante($idSolicitante){
    $where = '';
      if(isset($_SESSION['acesso']) && $_SESSION['acesso'] != 'SI'):
        $where = "
        AND (solic.idSolicitante = '$idSolicitante'
                OR solic.idSolicitacao IN(
                    SELECT ac.idSolicitacao FROM z_sga_fluxo_agendamento_acesso_acompanhante ac
                    WHERE ac.idAcompanhante = '$idSolicitante'
                )
            ) ";
      endif;
    $sql = "SELECT
        solic.idSolicitacao,
        solic.dataSolicitacao,
        solic.idSolicitante,
        form.url,
        form.descricao,
        usr.nome_usuario,
        fm.idAtividade as idSolicitacaoAtiv,
        (SELECT descricao FROM z_sga_fluxo_atividade where idAtividade =  idSolicitacaoAtiv ) as descricaoAtividade,
        fm.idResponsavel as idResponsavel,
        (SELECT nome_usuario  from z_sga_usuarios where z_sga_usuarios_id = idResponsavel) as Responsavel,
        (SELECT nome_usuario FROM z_sga_usuarios WHERE z_sga_usuarios_id IN(
            SELECT
                REPLACE(JSON_EXTRACT(documento, \"$.idusuario\"),'\"','') AS z_sga_usuarios_id                                        
            FROM
                z_sga_fluxo_documento                                                                                      
            WHERE
                idSolicitacao = solic.idSolicitacao
        )) AS usuarios,

        (SELECT h.status FROM z_sga_fluxo_documento_historico as h
            WHERE
                h.idMovimentacao = IF(
                    (SELECT count(h.idSolicitacao) FROM z_sga_fluxo_documento_historico as h
                        WHERE
                            h.idSolicitacao = solic.idSolicitacao
                    ) = 1,
                    fm.idMovimentacao,
                    IF(
                        fm.idMovimentacao - 1 = 0,
                        (fm.idMovimentacao),
                        (fm.idMovimentacao -1)
                    )
                ) AND
                h.idSolicitacao = solic.idSolicitacao
            ORDER BY h.status 
            LIMIT 1
        ) AS HStatus
    FROM
        z_sga_fluxo_solicitacao as solic,
        z_sga_fluxo_form as form,
        z_sga_usuarios as usr,
        z_sga_fluxo_movimentacao as fm
    WHERE
        solic.idform = form.idform
        AND solic.status = 1 
        AND fm.idSolicitacao = solic.idSolicitacao
        AND usr.z_sga_usuarios_id = solic.idSolicitante
        $where
        AND fm.status = 1
        GROUP BY solic.idSolicitacao";
    
    //die($sql);

    $sql = $this->db->query($sql);
    $array = array();
    if($sql->rowCount()>0){
      $array = $sql->fetchAll();
    }
    return $array;
  }

    public function carregaAtividadeEmAndamento($id){

        $where = '';

        if (isset($_SESSION['acesso']) && $_SESSION['acesso'] != 'SI') {
            $where = "AND \'$id\' IN 
                (
                    REPLACE(REPLACE(REPLACE(JSON_EXTRACT(d.documento, '$.grupos[*].gestorModulo[*].id'), '[', ''), '\"', ''), ']', ''),
                    REPLACE(REPLACE(REPLACE(JSON_EXTRACT(d.documento, '$.grupos[*].gestorRotina[*].id'), '[', ''), '\"', ''), ']', ''),
                    REPLACE(REPLACE(REPLACE(JSON_EXTRACT(d.documento, '$.grupos[*].gestorPrograma[*].id'), '[', ''), '\"', ''), ']', ''),
                    REPLACE(REPLACE(REPLACE(JSON_EXTRACT(d.documento, '$.idGestorUsuario'), '[', ''), '\"', ''), ']', ''),
                    REPLACE(REPLACE(REPLACE(JSON_EXTRACT(d.documento, '$.grupos[*].idCodGest'), '[', ''), '\"', ''), ']', '')
                )";
        }

        $sql = "SELECT 
        solic.idSolicitacao,
        solic.dataSolicitacao,
        form.url,
        form.idForm,
        form.descricao,
        usr.nome_usuario,
        ativ.descricao as 'descAtividade',
        mov.idResponsavel,
        mov.idAtividade,
        mov.idMovimentacao,
        mov.idSolicitante,

        (SELECT nome_usuario FROM z_sga_usuarios WHERE z_sga_usuarios_id IN(
            SELECT
                REPLACE(JSON_EXTRACT(documento, \"$.idusuario\"),'\"','') AS z_sga_usuarios_id                                                                        
            FROM
                z_sga_fluxo_documento                                                                                                     
            WHERE
                idSolicitacao = solic.idSolicitacao
        )) AS usuarios,

        (SELECT h.status FROM z_sga_fluxo_documento_historico as h 
            WHERE 
                h.idMovimentacao = IF(
                    (SELECT count(h.idSolicitacao) FROM z_sga_fluxo_documento_historico as h 
                        WHERE 
                            h.idSolicitacao = solic.idSolicitacao
                    ) = 1, 
                    mov.idMovimentacao,
                    IF(
                        mov.idMovimentacao - 1 = 0, 
                        (mov.idMovimentacao), 
                        (mov.idMovimentacao -1)
                    )
                ) AND
                h.idSolicitacao = solic.idSolicitacao
            ORDER BY h.status
            LIMIT 1
        ) AS HStatus
        FROM
        z_sga_fluxo_solicitacao as solic, 
        z_sga_fluxo_movimentacao as mov,
        z_sga_fluxo_form as form,
        z_sga_usuarios as usr,
        z_sga_fluxo_atividade as ativ,
        z_sga_fluxo_documento as d
        where 
        form.idForm = mov.form AND
        usr.z_sga_usuarios_id = mov.idResponsavel AND
        mov.idAtividade = ativ.idAtividade AND
        mov.status = '1' AND
        mov.idResponsavel != '$id' AND
        mov.idSolicitacao = solic.idSolicitacao AND
        solic.idSolicitacao IN (SELECT d.idSolicitacao FROM z_sga_fluxo_documento as d WHERE
            '$id' IN
                (
                    REPLACE(REPLACE(REPLACE(JSON_EXTRACT(d.documento, '$.grupos[*].gestorModulo[*].id'), '[', ''), '\"', ''), ']', ''),
                    REPLACE(REPLACE(REPLACE(JSON_EXTRACT(d.documento, '$.grupos[*].gestorRotina[*].id'), '[', ''), '\"', ''), ']', ''),
                    REPLACE(REPLACE(REPLACE(JSON_EXTRACT(d.documento, '$.grupos[*].gestorPrograma[*].id'), '[', ''), '\"', ''), ']', ''),
                    REPLACE(REPLACE(REPLACE(JSON_EXTRACT(d.documento, '$.idGestorUsuario'), '[', ''), '\"', ''), ']', ''),
                    REPLACE(REPLACE(REPLACE(JSON_EXTRACT(d.documento, '$.grupos[*].idCodGest'), '[', ''), '\"', ''), ']', '')
                )
            group by d.idSolicitacao)
        GROUP BY solic.idSolicitacao";
        
        $sql = $this->db->query($sql);
        $dados = array();
    if($sql->rowCount()>0){
      $dados = $sql->fetchAll();
    }
    return $dados;


        // $sql = "SELECT 
        //     JSON_EXTRACT(d.documento, \"$.grupos[{$count}]\") AS ,
        //     JSON_EXTRACT(d.documento, \"\") AS ,
        //     JSON_EXTRACT(d.documento, \"\") AS ,
        //     JSON_EXTRACT(d.documento, \"\") AS ,
        //     JSON_EXTRACT(d.documento, \"\") AS ,

        //     (SELECT nome_usuario FROM z_sga_usuarios WHERE z_sga_usuarios_id IN(
        //         SELECT
        //             REPLACE(JSON_EXTRACT(documento, \"$.idusuario\"),'\"','') AS z_sga_usuarios_id                                                                        
        //         FROM
        //             z_sga_fluxo_documento                                                                                                     
        //         WHERE
        //             idSolicitacao = solic.idSolicitacao
        //     )) AS usuarios,

        //     (SELECT h.status FROM z_sga_fluxo_documento_historico as h 
        //         WHERE 
        //             h.idMovimentacao = IF(
        //                 (SELECT count(h.idSolicitacao) FROM z_sga_fluxo_documento_historico as h 
        //                     WHERE 
        //                         h.idSolicitacao = solic.idSolicitacao
        //                 ) = 1, 
        //                 mov.idMovimentacao,
        //                 IF(
        //                     mov.idMovimentacao - 1 = 0, 
        //                     (mov.idMovimentacao), 
        //                     (mov.idMovimentacao -1)
        //                 )
        //             ) AND
        //             h.idSolicitacao = solic.idSolicitacao
        //         ORDER BY h.status desc
        //         LIMIT 1
        //     ) AS HStatus
        // FROM
        //     z_sga_fluxo_solicitacao as solic, 
        //     z_sga_fluxo_movimentacao as mov,
        //     z_sga_fluxo_form as form,
        //     z_sga_usuarios as usr,
        //     z_sga_fluxo_atividade as ativ
        // WHERE 
        //     solic.idSolicitacao = mov.idSolicitacao AND
        //     usr.z_sga_usuarios_id = mov.idResponsavel AND
        //     mov.idAtividade = ativ.idAtividade AND
        //     mov.status = '1' AND
        //     form.idForm = mov.form AND
        //     mov.idResponsavel = '$id'
        //     #AND CONCAT(CURDATE(), ' ', CURTIME()) >= mov.dataMovimentacao";
        
        // $sql = $this->db->query($sql);
        // $dados = array();
    
        // if($sql->rowCount()>0){
            // $dados = $sql->fetchAll();
        // }
    
        // return $dados;
    }

  public function iniciaFluxoRestruturacao($idGestor,$idEmpresa){
    $e = new Email();
    $dados = array();
    $dados['usr'] = $this->ajaxConsultaGestor($idGestor,$idEmpresa);
    $dataMovimentacao = date('Y-m-d H:i:s');

    $sql = "SELECT email,nome_usuario FROM z_sga_usuarios where z_sga_usuarios_id = '$idGestor'";
    $sql = $this->db->query($sql);

    if($sql->rowCount()>0){
      $sql = $sql->fetch();
    }

    foreach ($dados['usr'] as $value) {
      $dados['idSolicitacao'] = $this->cadastraNumSolicitacao("1",$_SESSION['idUsrTotvs']);
      
      $this->cadastraDadosFluxoRestruturacao($dados['idSolicitacao']['idSolic'],$value['gestor'],$idGestor,$value['nome_usuario'],$value['z_sga_usuarios_id']);
      
      $this->cadastraMovimentacao($dados['idSolicitacao']['idSolic'],"1",$dataMovimentacao,$_SESSION['idUsrTotvs'],$idGestor,"1","z_sga_fluxo_aprovacaorestruturacao");


      $mensagem = "<!DOCTYPE html>
                  <html>
                    <head>
                      <title></title>
                    </head>
                    <body>  
                      <h1>Ola! ".$sql['nome_usuario']."</h1>

                      <p>Existe uma nova atividade que est&aacute; sob sua responsabilidade e precisa de sua a&ccedil;&atilde;o.</p>
                      <p>Aprova&ccedil;&atilde;o: Restrutura&ccedil;&atilde;o de Acesso ".$value['nome_usuario']."</p>
                      <p>Acesse : <a href='".URL."/fluxo/centralDeTarefa'>".URL."/fluxo/centralDeTarefa</a></p>
                    </body>
                  </html>";

      $e->enviaEmail('SGA - Sistema de Gestão de Acesso','Restruturação de Acesso',$mensagem,$sql['email']);

    }
  }

  public function cadastraDadosFluxoRestruturacao($idSolicitacao,$gestor,$idGestor,$usuario,$idUsuario){
    $sql = "INSERT INTO z_sga_fluxo_AprovacaoRestruturacao SET idSolicitacao = '$idSolicitacao',gestor = '$gestor',idGestor = '$idGestor',usuario = '$usuario',idUsuario = '$idUsuario'";
    $this->db->query($sql);
  }

  public function totalAtividade($idAtividade, $idFluxo)
  {
      $sql = "SELECT count(*) AS total FROM z_sga_fluxo_atividade WHERE idAtividade >= $idAtividade AND idFluxo = $idFluxo AND descricao NOT IN('solicitante','final') AND ativo = 1";
      $total = $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
      return $total['total'];
  }

  public function carragaDadosSolicitacaoRestruturacao($id) {
    $sql = "SELECT * from z_sga_fluxo_aprovacaorestruturacao where idSolicitacao = '$id'";
    $sql = $this->db->query($sql);
    $dados = array();
    if($sql->rowCount()>0){
      $dados = $sql->fetch();
    }
    return $dados;
  }
  
  public function listaGestor($idEmpresa) {
    $sql = "SELECT 
              userEmp.idEmpresa,
              userEmp.idUsuario,
              u.z_sga_usuarios_id,
              u.nome_usuario,
              u.cod_usuario
          FROM z_sga_usuario_empresa AS userEmp
          INNER JOIN
              z_sga_usuarios AS u ON userEmp.idUsuario = u.z_sga_usuarios_id
          LEFT JOIN
              z_sga_manut_funcao AS m ON u.cod_funcao = m.idFuncao
          LEFT JOIN
              v_sga_mtz_resumo_matriz_usuario AS rru ON rru.idUsuario = userEmp.idUsuario
                                AND rru.idEmpresa = userEmp.idEmpresa

          where userEmp.idEmpresa = '$idEmpresa' AND u.gestor_usuario = 'S'  GROUP BY u.z_sga_usuarios_id";
    $sql = $this->db->query($sql);
    $dados = array();
    if($sql->rowCount()>0){
      $dados = $sql->fetchAll();
    }
    return $dados;
  }
  
  public function buscaSolicitante($idSolicitacao) {
    $sql = "
        SELECT 
            idSolicitante           
        FROM 
            z_sga_fluxo_solicitacao            
        WHERE 
           idSolicitacao = $idSolicitacao";
    $sql = $this->db->query($sql);
    $dados = array();
    if($sql->rowCount()>0){
      $dados = $sql->fetch();
    }
    return $dados;
  }
  
  public function buscaAcompanhante($idSolicitacao) {
    $sql = "
        SELECT 
            idAcompanhante
        FROM 
            z_sga_fluxo_agendamento_acesso_acompanhante            
        WHERE 
           idSolicitacao = $idSolicitacao";
    $sql = $this->db->query($sql);
    $dados = array();
    if($sql->rowCount()>0){
      $dados = $sql->fetch();
    }
    return $dados;
  }


    /**
     * Retorna o total de movimentação ativa para uma solicitação
     * @param $idSolicitacao
     * @return mixed
     */
      public function totalMovimentacaoAtiva($idSolicitacao)
      {
          $sql = "
              SELECT 
                  COUNT(*) AS total 
              FROM 
                  z_sga_fluxo_movimentacao 
              WHERE
                  idSolicitacao = $idSolicitacao
                  AND status = 1 
              ";
          $sql = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

          return $sql[0]['total'];
      }

    /**
     * Retorna os usuários alternativos
     * @param $idGestores
     * @return mixed
     */
    public function buscaUsuariosAlternativos($idGestores)
    {
        $idsGestores = is_array($idGestores) ? "'" . implode("','", $idGestores). "'" : 'dsfghgfdhsbhngfdhfgsd';
        
        $sql = "
            SELECT
                idUsrSerSub, 
                idUsrSub 
            FROM 
                z_sga_fluxo_substituto 
            WHERE
                idUsrSerSub IN($idsGestores)                 
                AND status = '1'
                AND dataFim >= '".date('Y-m-d')."'
            ";
        
        $sql = $this->db->query($sql);
        
        $dados = array();
        if($sql->rowCount() > 0):
            $dados =  $sql->fetchAll(PDO::FETCH_ASSOC);
        endif;
        return $dados;
    }

    /**
     * Retorna usuário alternativo para fluxo
     * @param $idGestor
     * @return mixed
     */
    public function buscaUsrAlternativoFluxos($idGestor)
    {
        $sql = "
              SELECT
                  idUsrSerSub, 
                  idUsrSub 
              FROM 
                  z_sga_fluxo_substituto 
              WHERE
                  idUsrSerSub = '$idGestor'                  
                  AND status = 1 
              ";

        //echo "<pre>";
        //die($sql);

        $sql = $this->db->query($sql);

        $dados = array();
        if($sql->rowCount() == 0):
            return $idGestor;
        elseif($sql->rowCount() > 0):
            $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
            return $dados[0]['idUsrSub'];
        endif;
    }

    /**
     * Busca movimentação pelo usuario logado ou alternativo
     * @param $idMovimentacao
     * @param $idResponsavel
     * @param $idSolicitacao
     * @return array
     */
    public function buscaMovimentacao($idMovimentacao, $idResponsavel, $idSolicitacao)
    {
        $whereMovimentacao = ($idMovimentacao > 0) ? " AND idMovimentacao = $idMovimentacao" : '';

        $sql = "
            SELECT
                *
            FROM
                z_sga_fluxo_movimentacao
            WHERE                
                idSolicitacao = $idSolicitacao
                AND idResponsavel = $idResponsavel
                $whereMovimentacao
        ";

        try{
            $sql = $this->db->query($sql);
            $dados  = array();
            if($sql->rowCount() > 0):
                return true;
            else:
                return false;
            endif;
        }catch (Exception $e){
            return array(
                'return'    => false,
                'error'     => $e->getMessage()
            );
        }
    }

    /**
     * Busca o usuário SI do sistema
     * @return array
     */
    public function buscaSI()
    {
        $sql = "
            SELECT
                z_sga_usuarios_id AS idUsuario,
                nome_usuario
            FROM
                z_sga_usuarios
            WHERE
                si = 's'
                AND cod_usuario = 'super'
        ";
        $sql = $this->db->query($sql);

        $dados = array();

        if($sql->rowCount() > 0):
            $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
        endif;

        return $dados;
    }

    /**
     * Busca os dados pda atividade     
     * @param type $idAtividade
     */
    public function buscaDadosAtividade($idAtividade)
    {
        $sql = "
            SELECT	
                descricao AS atividade
            FROM
                z_sga_fluxo_atividade
            WHERE
                id = $idAtividade";
        
        $sql = $this->db->query($sql);
        
        if($sql->rowCount() > 0):
            $dados = $sql->fetch(PDO::FETCH_ASSOC);
            return $dados['atividade'];
        else:
            return 0;
        endif;
    }
    
    /**
     * Busca os dados para envio de email para usuários em cópia da solicitação
     * @param type $idUsuarioCopia
     * @param type $idSolicitacao
     */
    public function buscaDadosEmailCopiaGeral($idUsuarioCopia, $idSolicitacao)
    {
        $sql = "
            SELECT
                (SELECT su.nome_usuario FROM z_sga_usuarios su LEFT JOIN z_sga_usuario_empresa e ON e.idUsuario = z_sga_usuarios_id WHERE z_sga_usuarios_id = $idUsuarioCopia AND e.idEmpresa = ".$_SESSION['empresaid'].") as usuario_copia,
                (SELECT su.email FROM z_sga_usuarios su LEFT JOIN z_sga_usuario_empresa e ON e.idUsuario = z_sga_usuarios_id WHERE z_sga_usuarios_id = $idUsuarioCopia AND e.idEmpresa = ".$_SESSION['empresaid'].") as email,                
                u.nome_usuario AS nome_gestor,
                fa.descricao AS atividade
            FROM
                z_sga_fluxo_movimentacao fm
            LEFT JOIN
                z_sga_usuarios u
                ON fm.idResponsavel = u.z_sga_usuarios_id
            LEFT JOIN
                z_sga_fluxo_atividade fa
                ON fm.idAtividade = fa.id
            LEFT JOIN 
                z_sga_usuario_empresa ue 
                ON ue.idUsuario = z_sga_usuarios_id
            WHERE
                fm.idSolicitacao = $idSolicitacao
                AND fm.status = 1
                AND ue.idEmpresa = ".$_SESSION['empresaid'];

        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0):
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        else:
            return 0;
        endif;
    }

    /**
     * Busca os dados para envio de email para usuários em cópia da solicitação
     * @param type $idUsuarioCopia
     * @param type $idSolicitacao
     */
    public function buscaProximosGestores($idSolicitacao)
    {
        $sql = "
            SELECT                                
                u.nome_usuario AS nome_gestor,
                fa.descricao AS atividade
            FROM
                z_sga_fluxo_movimentacao fm
            LEFT JOIN
                z_sga_usuarios u
                ON fm.idResponsavel = u.z_sga_usuarios_id
            LEFT JOIN
                z_sga_fluxo_atividade fa
                ON fm.idAtividade = fa.id
            LEFT JOIN 
                z_sga_usuario_empresa ue 
                ON ue.idUsuario = z_sga_usuarios_id
            WHERE
                fm.idSolicitacao = $idSolicitacao
                AND fm.status = 1
                AND ue.idEmpresa = ".$_SESSION['empresaid'];

        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0):
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        else:
            return 0;
        endif;
    }
    
    /**
     * Busca os dados para envio de email para usuários em cópia da solicitação
     * @param type $idUsuarioCopia     
     */
    public function buscaDadosUsuariosCopia($idUsuarioCopia)
    {
        $sql = "
            SELECT
                u.nome_usuario,
                u.email
            FROM                
                z_sga_usuarios u
            LEFT JOIN
                z_sga_usuario_empresa ue
                ON ue.idUsuario = u.z_sga_usuarios_id
            WHERE                
                z_sga_usuarios_id = $idUsuarioCopia
                AND ue.idEmpresa = ". $_SESSION['empresaid'];
        
        $sql = $this->db->query($sql);
        
        if($sql->rowCount() > 0):
            return $sql->fetch(PDO::FETCH_ASSOC);
        else:
            return 0;
        endif;
    }
    
    /**
     * Retorna o histórico de mensagem da solicitação
     * @param $idSolicitacao
     * @return array
     */
    public function buscaHistoricoMsg($idSolicitacao)
    {
        $sql = "
            SELECT DISTINCT
                fmh.idmsg,
                fmh.autor AS autor,
                fmh.msg AS msg,
                fmh.dataCriacao AS dataCriacao,
                fa.descricao AS atividade
            FROM
                z_sga_fluxo_mensagem fmh
            LEFT JOIN
                z_sga_fluxo_movimentacao fm
                ON fmh.idSolicitacao = fm.idSolicitacao
            LEFT JOIN
                z_sga_fluxo_solicitacao fs
                ON fmh.idSolicitacao = fs.idSolicitacao
            LEFT JOIN
                z_sga_fluxo_atividade fa
                ON fmh.idAtividade = fa.idAtividade
            WHERE
                fmh.idSolicitacao = $idSolicitacao
            ORDER BY
                fmh.idmsg";

        try{
            $sql = $this->db->query($sql);
            $result = $sql->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }catch (Exception $e){
            return array(
                'return'    => false,
                'error'     => $e->getMessage()
            );
        }
    }

    /**
     * Adiciona mensagem de histórico de solicitação
     * @param $idSolicitacao
     * @param $autor
     * @param $msg
     * @return array
     */
    public function addHistoricoMsg($idSolicitacao, $idMovimentacao, $idAtividade, $autor, $msg)
    {
        // DEFINE O FUSO HORARIO COMO O HORARIO DE BRASILIA
        date_default_timezone_set('America/Sao_Paulo');
        $sql = "
            INSERT INTO
                z_sga_fluxo_mensagem( 
                    idSolicitacao,                    
                    idMovimentacao,
                    idAtividade,
                    autor,
                    msg,
                    dataCriacao
                )VALUES(
                    $idSolicitacao,
                    $idMovimentacao,     
                    $idAtividade,
                    '".$autor."',
                    '".$msg."',
                    '".date('Y-m-d H:i:s')."'
                )";

        try{
            $this->db->query($sql);
            return array('return' => true);
        }catch (Exception $e){
            return array(
                'return' => false,
                'error'  => $e->getMessage()
            );
        }
    }

    /**
     * Busca os dados do responsável da atividade da solicitação
     * @param $idSolicitacao
     * @return array
     */
    public function buscaDadosMovimentacaoAtiva($idSolicitacao)
    {
        $sql = "
            SELECT
                fm.idAtividade,
                fm.idResponsavel,
                fd.documento,
                fm.idSolicitante,
                fm.idMovimentacao
            FROM
                z_sga_fluxo_movimentacao fm
            LEFT JOIN
                z_sga_fluxo_documento fd
                ON fm.idSolicitacao = fd.idSolicitacao
            WHERE
                fm.idSolicitacao = $idSolicitacao
                AND status = 1";

        try{
            $result = $this->db->query($sql);

            return array(
                'return' => true,
                'result' => ($result->rowCount() > 0) ? $result->fetch() : 0
            );
        }catch (Exception $e){
            return array(
                'return' => false,
                'error'  => $e->getMessage()
            );
        }
    }

    /**
     * Recupera as atividades do fluxo passado por parâmetro
     * @param type $idFluxo
     * @return type
     */
    public function carregaAtividadesFluxoParaSI($idFluxo)
    {        
        $sql = "
            SELECT
                id,
                objeto,
                descricao             
            FROM 
                z_sga_fluxo_atividade 
            WHERE 
                idFluxo = $idFluxo
                AND descricao NOT IN('Final','Solicitante', 'S.I')
                AND ativo = 1";
        $sql = $this->db->query($sql);
        
        if($sql->rowCount() > 0):
            return $sql->fetchAll();
        else:
            return array();
        endif;
    }            
        
    /**
     * Busca os dados dos gestores participantes da solicitacao
     * @param $idSolicitacao
     * @return array
     */
    public function buscaDadosGestorMovimentacao($idSolicitacao)
    {
        $sql = "
            SELECT
                fm.idResponsavel,
                u.nome_usuario,
                u.email
            FROM                
                z_sga_usuarios u
            LEFT JOIN
                z_sga_usuario_empresa ue
                ON ue.idUsuario = u.z_sga_usuarios_id
            LEFT JOIN
                z_sga_fluxo_movimentacao fm
                ON fm.idResponsavel = u.z_sga_usuarios_id
            WHERE	
                ue.idEmpresa = ".$_SESSION['empresaid']."
                AND fm.idSolicitacao = $idSolicitacao";

        try{
            $result = $this->db->query($sql);

            return array($result->rowCount() > 0) ? $result->fetchAll(PDO::FETCH_ASSOC) : 0;
        }catch (Exception $e){
            return array(
                'return' => false,
                'error'  => $e->getMessage()
            );
        }
    }
    
    
    /**
     * Busca os dados do gestor de usuários
     * @param $cod_gestor
     * @return array
     */
    public function buscaDadosGestorUsuario($cod_gestor)
    {
        $sql = "
            SELECT	                
                u.email,
                u.nome_usuario                
            FROM
                z_sga_usuarios u
            LEFT JOIN
                z_sga_usuario_empresa ue
                ON ue.idUsuario = u.z_sga_usuarios_id
            WHERE                
                u.cod_usuario = '$cod_gestor'
                AND ue.idEmpresa = ".$_SESSION['empresaid'];

        try{
            $result = $this->db->query($sql);

            return array($result->rowCount() > 0) ? $result->fetch(PDO::FETCH_ASSOC) : 0;
        }catch (Exception $e){
            return array(
                'return' => false,
                'error'  => $e->getMessage()
            );
        }
    }
    
    /**
     * Busca os dados do usuario solicitante
     * @param $idSolicitante
     * @return array
     */
    public function buscaDadosSolicitante($idSolicitante)
    {
        $sql = "
            SELECT DISTINCT
                l.email,
                u.nome_usuario,
                u.cod_usuario                
            FROM
                z_sga_param_login l
            LEFT JOIN
                z_sga_usuarios u
                ON u.z_sga_usuarios_id = l.idTotovs
            LEFT JOIN
                z_sga_usuario_empresa ue
                ON ue.idUsuario = u.z_sga_usuarios_id
            WHERE                
                u.z_sga_usuarios_id = $idSolicitante
                AND ue.idEmpresa = ".$_SESSION['empresaid'];
        
        try{
            $result = $this->db->query($sql);

            return array($result->rowCount() > 0) ? $result->fetch(PDO::FETCH_ASSOC) : 0;
        }catch (Exception $e){
            return array(
                'return' => false,
                'error'  => $e->getMessage()
            );
        }
    }
    
    /**
     * Busca os dados dos usuarios em cópia
     * @param $post
     * @return array
     */
    public function buscaDadosEmailCopia($post)
    {
        $sql = "
            SELECT	
                u.nome_usuario AS usuario_copia,
                u.email,
                (SELECT nome_usuario FROM z_sga_usuarios SU LEFT JOIN z_sga_usuario_empresa e ON e.idUsuario = SU.z_sga_usuarios_id WHERE su.z_sga_usuarios_id = ".$post['idSolicitante']." AND e.idEmpresa = ".$_SESSION['empresaid'].") AS solicitante,
                (SELECT nome_usuario FROM z_sga_usuarios SU LEFT JOIN z_sga_usuario_empresa e ON e.idUsuario = SU.z_sga_usuarios_id WHERE su.z_sga_usuarios_id = ".$post['idUsuario']." AND e.idEmpresa = ".$_SESSION['empresaid'].") AS nome_usuario
            FROM
                z_sga_usuarios u
            LEFT JOIN
                z_sga_usuario_empresa ue
                ON ue.idUsuario = u.z_sga_usuarios_id
            WHERE                
                u.z_sga_usuarios_id = ".$post['idUsuarioCopia']."
                AND ue.idEmpresa = ".$_SESSION['empresaid'];

        try{
            $result = $this->db->query($sql);

            return array($result->rowCount() > 0) ? $result->fetch(PDO::FETCH_ASSOC) : 0;
        }catch (Exception $e){
            return array(
                'return' => false,
                'error'  => $e->getMessage()
            );
        }
    }
    
    /**
     * Busca os dados do fluxo
     * @param $idSolicitacao
     * @return array
     */
    public function buscaDadosFluxo($idSolicitacao)
    {
        $sql = "
            SELECT                
                f.*
            FROM
                z_sga_fluxo_movimentacao fm
            LEFT JOIN
                z_sga_fluxo_atividade fa
                ON fm.idAtividade = fa.id
            LEFT JOIN
                z_sga_fluxo_documento fd
                ON fm.idSolicitacao = fd.idSolicitacao
            LEFT JOIN
                z_sga_fluxo f
                ON fa.idFluxo = f.idFluxo
            WHERE
                fd.idSolicitacao = $idSolicitacao";

        try{
            $result = $this->db->query($sql);

            return ($result->rowCount() > 0) ? $result->fetch(PDO::FETCH_ASSOC) : 0;
        }catch (Exception $e){
            return array(
                'return' => false,
                'error'  => $e->getMessage()
            );
        }
    }

    /**
     * Busca os dados para TIMELINE
     * @param $idSolicitacao
     * @return array
     */
    public function buscaDadosTimeline($idSolicitacao)
    {
        $sql = "
            SELECT
                fmv.idAtividade,            
                fmv.idMovimentacao,
                fmv.idSolicitacao,
                fmv.dataMovimentacao,
                fmv.dataAcao,
                REPLACE(JSON_EXTRACT(d.documento, \"$.dataInicio\"),'\"','') AS dataInicio,
                fmv.status,	
                u.nome_usuario As responsavel,
                #fm.msg,
                fa.descricao AS descAtividade,
                fmv.idResponsavel,
                fh.status AS acao,
                fh.grupos_programas,
                fmv.form
            FROM
                z_sga_fluxo_movimentacao fmv
            LEFT JOIN
                z_sga_fluxo_documento_historico fh
                ON fmv.idMovimentacao = fh.idMovimentacao
            LEFT JOIN
                z_sga_fluxo_documento d
                ON d.idSolicitacao = fmv.idSolicitacao
            LEFT JOIN
                z_sga_fluxo_atividade fa
                ON fmv.idAtividade = fa.id
            /*LEFT JOIN
                z_sga_fluxo_mensagem fm
                ON fmv.idMovimentacao = fm.idMovimentacao*/
            LEFT JOIN
                z_sga_usuarios u
                ON fmv.idResponsavel = u.z_sga_usuarios_id
            WHERE
                fmv.idSolicitacao = $idSolicitacao
            ORDER BY
	        #fmv.idMovimentacao, fmv.dataMovimentacao, fmv.dataAcao ASC
                fmv.dataMovimentacao";

        try{
            $result = $this->db->query($sql);

            return ($result->rowCount() > 0) ? $result->fetchAll(PDO::FETCH_ASSOC) : 0;
        }catch (Exception $e){
            return array(
                'return' => false,
                'error'  => $e->getMessage()
            );
        }
    }

    /*
      Insere historico do documento na tabela de histórico de documentos.
    */
    public function insereDocumentoHistorico($idSolicitacao, $idMovimentacao, $status, $gruposProgramas){
        $sql = "
            INSERT INTO 
                z_sga_fluxo_documento_historico(
                    idSolicitacao, 
                    idMovimentacao, 
                    status,
                    grupos_programas
                )VALUES(
                    $idSolicitacao,
                    $idMovimentacao,
                    '$status',
                    '".addslashes(substr($gruposProgramas, 0, 500))."'
                )";
        //echo "<pre>";
        //die($sql);
        $this->db->query($sql);
    }

    public function ajaxConsultaGestor($idGestor,$idEmpresa){
    $sql = "SELECT * from z_sga_usuarios where z_sga_usuarios_id = '$idGestor'";
    $sql = $this->db->query($sql);
    $dados = array();
    if($sql->rowCount()>0){
      $sql = $sql->fetch();
      $gestor =  $sql['cod_usuario'];

      $sql2 = "SELECT 
                      userEmp.idEmpresa,
                      userEmp.idUsuario,
                      u.z_sga_usuarios_id,
                      u.nome_usuario,
                      u.cod_usuario,
                      (SELECT 
                              ug.nome_usuario
                          FROM
                              z_sga_usuarios ug
                          WHERE
                              ug.cod_usuario = u.cod_gestor) AS gestor,
                      u.funcao,
                      m.cod_funcao,
                      COUNT(DISTINCT userEmp.idEmpresa) AS nroInstancias,
                      COUNT(DISTINCT rru.codRisco) AS nroRiscos
                  FROM z_sga_usuario_empresa AS userEmp
                  INNER JOIN
                      z_sga_usuarios AS u ON userEmp.idUsuario = u.z_sga_usuarios_id
                  LEFT JOIN
                      z_sga_manut_funcao AS m ON u.cod_funcao = m.idFuncao
                  LEFT JOIN
                      v_sga_mtz_resumo_matriz_usuario AS rru ON rru.idUsuario = userEmp.idUsuario
                                        AND rru.idEmpresa = userEmp.idEmpresa

                  where userEmp.idEmpresa = '$idEmpresa' AND cod_gestor = '$gestor' GROUP BY u.z_sga_usuarios_id";
      //die($sql2);
      
      $sql2 = $this->db->query($sql2);
      if($sql2->rowCount()>0){
        $dados = $sql2->fetchAll();
      }

    }
    return $dados;
  }

    /**
     * Responsável por retornar o total de registros encontrados na tabela
     * @param $table String contendo nome da tabela a se consultar
     * @param $search String contendo o filtro
     * @param $fields Array com campos a filtrar
     * @param $join String com a relação entre tabelas
     * @param $idEmpresa Int contendo o id da empresa a incluir no filtro
     * @return mixed Retorna o total de registros encontrados
     */
    public function getCountTableProgramasLog($search, $idEmpresa = '', $idSolicitacao){
        $dados = array();

        $sql = "
            SELECT 
                count(fl.idSolicitacao) AS total              
            FROM
                z_sga_fluxo_log fl 
            LEFT JOIN
                z_sga_programas p
                ON fl.idPrograma = p.z_sga_programas_id
            LEFT JOIN
                z_sga_grupo g
                ON fl.idGrupo = g.idGrupo ";

        $sql .= " WHERE fl.idEmpresa = $idEmpresa AND idSolicitacao = $idSolicitacao";

        // Se a variavel $search não estiver vazia o where no select
        if($search != ''):
            $sql .= " AND g.idLegGrupo LIKE '%$search%'";
            $sql .= " OR g.descAbrev LIKE '%$search%'";
            $sql .= " OR p.cod_programa LIKE '%$search%'";
            $sql .= " OR p.descricao_programa LIKE '%$search%'";
            $sql .= " OR p.descricao_rotina LIKE '%$search%'";
            $sql .= " OR p.ajuda_programa LIKE '%$search%'";
        endif;

        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0):
            $dados = $sql->fetchAll();
        endif;

        return $dados[0]['total'];
    }

    /**
     * Retorna os grupos filtrando pelo id da empresa
     * @param $idEmpresa
     * @return array
     */
    public function carregaDatatableFluxoProgramaLog($search, $orderColumn, $orderDir, $offset, $limit, $idSolicitacao){
        $sql = "
            SELECT
                g.idLegGrupo,
                g.descAbrev,
                p.cod_programa,
                p.descricao_programa,
                p.descricao_rotina,
                p.ajuda_programa        
            FROM
                z_sga_fluxo_log fl 
            LEFT JOIN
                z_sga_programas p
                ON fl.idPrograma = p.z_sga_programas_id
            LEFT JOIN
                z_sga_grupo g
                ON fl.idGrupo = g.idGrupo ";

        $sql .= " WHERE fl.idEmpresa = '".$_SESSION['empresaid']."' AND idSolicitacao = $idSolicitacao";

        // Se a variavel $search não estiver vazia o where no select
        if($search != ''):
            $sql .= " AND g.idLegGrupo LIKE '%$search%'";
            $sql .= " OR g.descAbrev LIKE '%$search%'";
            $sql .= " OR p.cod_programa LIKE '%$search%'";
            $sql .= " OR p.descricao_programa LIKE '%$search%'";
            $sql .= " OR p.descricao_rotina LIKE '%$search%'";
            $sql .= " OR p.ajuda_programa LIKE '%$search%'";
        endif;

        // Se a variavel $order não estiver vazia ordena o retorno
        if($orderColumn != ''):
            $sql .= " ORDER BY $orderColumn $orderDir";
        endif;

        // Se as variaveis $offset e $limit não estiverem vazias limita o retorno
        if($offset != '' && $limit != ''):
            $sql .= " LIMIT $offset, $limit";
        endif;

        $sql = $this->db->query($sql);
        $array = array();
        if($sql->rowCount()>0){
            $array = $sql->fetchAll();
        }
        return $array;
    }

    public function carregaMovimentacao($idMovimentacao)
    {
        $sql = "
            SELECT
                m.*,
                a.descricao
            FROM
                z_sga_fluxo_movimentacao m
            INNER JOIN
                z_sga_fluxo_atividade a
                ON a.id = m.idAtividade
            WHERE
                idMovimentacao = $idMovimentacao";
        
        $sql = $this->db->query($sql);
        
        if($sql->rowCount() > 0):
            return $sql->fetch(PDO::FETCH_ASSOC);
        else:
            return array();
        endif;
    }
    
    /**
     * Busca os dados da solicitacao
     * @param $idSolicitacao
     */
    public function buscaDadosAuditoria($idSolicitacao)
    {
        $sql = "
            SELECT                 
                s.dataSolicitacao as dataInicio,
                u.cod_usuario,
                u.nome_usuario,
                s.idSolicitante,
                s.status
            FROM
                z_sga_fluxo_solicitacao s
            LEFT JOIN
                z_sga_usuarios u
                ON s.idSolicitante = u.z_sga_usuarios_id
            WHERE
                s.idSolicitacao = $idSolicitacao
        ";
        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0):
            return $sql->fetch(PDO::FETCH_ASSOC);
        else:
            return array();
        endif;
    }


    /**
     * Cria log para auditoria na tabela z_sga_log
     */
    /**
     * @param $idProcesso
     * @param $dataInicio
     * @param $dataFim
     * @param $solicitante
     * @param $usuario
     * @param $tipoAcao
     * @param $grupo
     * @param $programa
     * @param $aprovadorAcao
     * @param $tipoMovimentacao m = Manutenção, f = Fluxo
     * @param $idSolicitacao
     * @return array
     */
    public function gravaLogAuditoria($idProcesso, $dataInicio, $dataFim, $solicitante, $usuario, $tipoAcao, $grupo, $programa, $aprovadorAcao, $tipoMovimentacao, $idSolicitacao)
    {
        $sqlInsertLog = " 
             INSERT INTO `z_sga_log`(                    
                `idProcesso`,
                `dataInicio`,
                `dataFim`,
                `solicitante`,
                `usuario`,
                `acao`,
                `grupo`,
                `programa`,
                `aprovadorAcao`,
                `tipoMovimentacao`,
                `idSolicitacao`)
            VALUES(                    
                $idProcesso,
                '".$dataInicio."',
                '".$dataFim."',
                '".$solicitante."',
                '".$usuario."',
                '".$tipoAcao."',
                '".$grupo."',
                '".$programa."',
                '".$aprovadorAcao."',
                '".$tipoMovimentacao."',
                $idSolicitacao)";

        //echo "<pre>";
        //die($sqlInsertLog);

        try{
            $this->db->query($sqlInsertLog);

            return array('return' => true);
        }catch (Exception $e){
            return array(
                'return'    => false,
                'error'     => $e->getMessage()
            );
        }
    }


    /**
     * Insere os programas de cada grupo da solicitação quando aprovado
     * @param $grupos
     * @param $idSolicitacao
     */
    public function gravaLogFluxo($grupos, $idSolicitacao)
    {
        foreach($grupos as $valGrupo):
            $sqlInsertLog = " 
                INSERT INTO 
                    z_sga_fluxo_log(idSolicitacao, idGrupo, idPrograma, idEmpresa)
                SELECT 
                    $idSolicitacao, ".
                    $valGrupo['idGrupo'].", 
                    idPrograma,
                    ".$_SESSION['empresaid']."
                FROM
                    z_sga_grupo_programa
                WHERE
                    idGrupo = ".$valGrupo['idGrupo']."                                
                LIMIT 
                    5000000";
            try{
                $this->db->query($sqlInsertLog);

                return array('return' => true);
            }catch (Exception $e){
                return array(
                    'return'    => false,
                    'error'     => $e->getMessage()
                );
            }
        endforeach;
    }

    public function fluxoMatrizRisco($grupos)
    {
        $sql = "
            select 
                bmgrp.empGrupo as idEmpresa,
                bmgrp.idGrupo,
                area.descricao as descArea,
                mtzr.codRisco,
                mtzr.descricao as descRisco,
                gr.descricao as grau,
                gr.background as bgcolor,
                gr.texto as fgcolor,
                -- bmgrp.idGrupo,
                mtzp.descProcesso as processoPri, 
                IF((SELECT idRisco FROM z_sga_mtz_mitigacao_risco mr WHERE mr.idRisco = mtzr.idMtzRisco LIMIT 1) IS NOT NULL, 'Mitigado' , 'Não mitigado') as mitigado,
                group_concat(distinct bmgrp.cod_programa order by bmgrp.cod_programa separator ' | ')   
                -- (select  group_concat(distinct bmgrpi.cod_programa order by bmgrpi.cod_programa separator ' | ') 
                --	from v_sga_mtz_base_matriz_por_grupo bmgrpi where bmgrpi.idProcesso = cp.idProcessoPrim 
                --												and bmgrpi.idGrupo  = bmgrp.idGrupo 
                --                                              and bmgrpi.empGrupo   = bmgrp.empGrupo
                --           									group by bmgrpi.idProcesso) 
                as progspPri, 
                (select count(mtapsp.idPrograma) from z_sga_mtz_apps_processo mtapsp Where mtapsp.idProcesso = cp.idProcessoPrim)  as nroAppsPPrinc,
                (select mtzpi.descProcesso from z_sga_mtz_processo mtzpi where mtzpi.idProcesso = cp.idProcessoSec) as processoSec,
                (select group_concat(distinct bmgrpi.cod_programa separator ' | ') 
                 from v_sga_mtz_base_matriz_usuario bmgrpi where bmgrpi.idProcesso = cp.idProcessoSec  
                                                             and bmgrpi.idGrupo = bmgrp.idGrupo
                                                             and bmgrpi.empGrupo  = bmgrp.empGrupo
                                                             group by bmgrpi.idProcesso) 
                        as progspSec,
                     (select count(mtapsp.idPrograma) from z_sga_mtz_apps_processo mtapsp Where mtapsp.idProcesso = cp.idProcessoSec)  as nroAppsPSec
                  from z_sga_mtz_coorelacao_processo cp,
                       v_sga_mtz_base_matriz_por_grupo bmgrp,
                       z_sga_mtz_grau_risco gr,
                       z_sga_mtz_processo mtzp,
                       z_sga_mtz_risco mtzr,
                       z_sga_mtz_area area
                  where (cp.idProcessoPrim = bmgrp.idProcesso
                          and cp.idProcessoSec in (select bmgrpi.idProcesso from v_sga_mtz_base_matriz_usuario bmgrpi where bmgrpi.idProcesso = cp.idProcessoSec and bmgrpi.idGrupo = bmgrp.idGrupo )
                         )
                    and gr.idGrauRisco = cp.idGrauRisco
                    and mtzp.idProcesso = cp.idProcessoPrim
                    and mtzr.idMtzRisco = mtzp.idMtzRisco
                    and area.idArea = mtzr.idArea
                    -- filtros aqui
                     and bmgrp.idGrupo in (".implode(',', $grupos).") -- Filtro de grupos
                    --
                   group by bmgrp.idGrupo, cp.idProcessoPrim, cp.idProcessoSec
                    ORDER BY area.descricao";
        
        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0):
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        else:
            return array();
        endif;
    }
    
    
    public function fluxoMatrizRiscoAdicionados($idUsuario)
    {
        $sql = "
            select 
                bmgrp.empGrupo as idEmpresa,
                bmgrp.idGrupo,
                area.descricao as descArea,
                mtzr.codRisco,
                mtzr.descricao as descRisco,
                gr.descricao as grau,
                gr.background as bgcolor,
                gr.texto as fgcolor,
                -- bmgrp.idGrupo,
                mtzp.descProcesso as processoPri,    
                IF((SELECT idRisco FROM z_sga_mtz_mitigacao_risco mr WHERE mr.idRisco = mtzr.idMtzRisco LIMIT 1) IS NOT NULL, 'Mitigado' , 'Não mitigado') as mitigado,
                group_concat(distinct bmgrp.cod_programa order by bmgrp.cod_programa separator ' | ')   
                -- (select  group_concat(distinct bmgrpi.cod_programa order by bmgrpi.cod_programa separator ' | ') 
                --	from v_sga_mtz_base_matriz_por_grupo bmgrpi where bmgrpi.idProcesso = cp.idProcessoPrim 
                --												and bmgrpi.idGrupo  = bmgrp.idGrupo 
                --                                              and bmgrpi.empGrupo   = bmgrp.empGrupo
                --           									group by bmgrpi.idProcesso) 
                as progspPri, 
                (select count(mtapsp.idPrograma) from z_sga_mtz_apps_processo mtapsp Where mtapsp.idProcesso = cp.idProcessoPrim)  as nroAppsPPrinc,
                (select mtzpi.descProcesso from z_sga_mtz_processo mtzpi where mtzpi.idProcesso = cp.idProcessoSec) as processoSec,
                (select group_concat(distinct bmgrpi.cod_programa separator ' | ') 
                 from v_sga_mtz_base_matriz_usuario bmgrpi where bmgrpi.idProcesso = cp.idProcessoSec  
                                                             and bmgrpi.idGrupo = bmgrp.idGrupo
                                                             and bmgrpi.empGrupo  = bmgrp.empGrupo
                                                             group by bmgrpi.idProcesso) 
                        as progspSec,
                     (select count(mtapsp.idPrograma) from z_sga_mtz_apps_processo mtapsp Where mtapsp.idProcesso = cp.idProcessoSec)  as nroAppsPSec
                  from z_sga_mtz_coorelacao_processo cp,
                       v_sga_mtz_base_matriz_por_grupo bmgrp,
                       z_sga_mtz_grau_risco gr,
                       z_sga_mtz_processo mtzp,
                       z_sga_mtz_risco mtzr,
                       z_sga_mtz_area area
                  where (cp.idProcessoPrim = bmgrp.idProcesso
                          and cp.idProcessoSec in (select bmgrpi.idProcesso from v_sga_mtz_base_matriz_usuario bmgrpi where bmgrpi.idProcesso = cp.idProcessoSec and bmgrpi.idGrupo = bmgrp.idGrupo )
                         )
                    and gr.idGrauRisco = cp.idGrauRisco
                    and mtzp.idProcesso = cp.idProcessoPrim
                    and mtzr.idMtzRisco = mtzp.idMtzRisco
                    and area.idArea = mtzr.idArea
                    -- filtros aqui
                     and bmgrp.idGrupo in (SELECT idGrupo FROM z_sga_grupos WHERE idUsuario = $idUsuario) -- Filtro de grupos
                    --
                   group by bmgrp.idGrupo, cp.idProcessoPrim, cp.idProcessoSec
                    ";

        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0):
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        else:
            return array();
        endif;
    }
    
    public function fluxoMatrizCountRiscoAdicionados($idUsuario)
    {
        $sql = "
            select 
                COUNT(DISTINCT mtzr.codRisco) AS total               
            from z_sga_mtz_coorelacao_processo cp,
               v_sga_mtz_base_matriz_por_grupo bmgrp,
               z_sga_mtz_grau_risco gr,
               z_sga_mtz_processo mtzp,
               z_sga_mtz_risco mtzr,
               z_sga_mtz_area area
            where (cp.idProcessoPrim = bmgrp.idProcesso
                      and cp.idProcessoSec in (select bmgrpi.idProcesso from v_sga_mtz_base_matriz_usuario bmgrpi where bmgrpi.idProcesso = cp.idProcessoSec and bmgrpi.idGrupo = bmgrp.idGrupo )
                     )
            and gr.idGrauRisco = cp.idGrauRisco
            and mtzp.idProcesso = cp.idProcessoPrim
            and mtzr.idMtzRisco = mtzp.idMtzRisco
            and area.idArea = mtzr.idArea            
            -- filtros aqui
             and bmgrp.idGrupo in (SELECT idGrupo FROM z_sga_grupos WHERE idUsuario = $idUsuario) -- Filtro de grupos
            ";

        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0):
            $total = $sql->fetch(PDO::FETCH_ASSOC);
            return $total['total'];
        else:
            return array();
        endif;
    }
    
    
    
    public function fluxoMatrizCountRisco($grupos)
    {
        $sql = "
            select 
                COUNT(DISTINCT mtzr.codRisco) AS total               
            from z_sga_mtz_coorelacao_processo cp,
               v_sga_mtz_base_matriz_por_grupo bmgrp,
               z_sga_mtz_grau_risco gr,
               z_sga_mtz_processo mtzp,
               z_sga_mtz_risco mtzr,
               z_sga_mtz_area area
            where (cp.idProcessoPrim = bmgrp.idProcesso
                      and cp.idProcessoSec in (select bmgrpi.idProcesso from v_sga_mtz_base_matriz_usuario bmgrpi where bmgrpi.idProcesso = cp.idProcessoSec and bmgrpi.idGrupo = bmgrp.idGrupo )
                     )
            and gr.idGrauRisco = cp.idGrauRisco
            and mtzp.idProcesso = cp.idProcessoPrim
            and mtzr.idMtzRisco = mtzp.idMtzRisco
            and area.idArea = mtzr.idArea            
            -- filtros aqui
             and bmgrp.idGrupo in (".implode(',', $grupos).") -- Filtro de grupos
            ";

        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0):
            $total = $sql->fetch(PDO::FETCH_ASSOC);
            return $total['total'];
        else:
            return array();
        endif;
    }
    
    public function fluxoMatrizRiscoFoto($idSolicitacao)
    {
        $sql = "
            select 
                bmgrp.empGrupo as idEmpresa,
                bmgrp.idGrupo,
                area.descricao as descArea,
                mtzr.codRisco,
                mtzr.descricao as descRisco,
                gr.descricao as grau,
                gr.background as bgcolor,
                gr.texto as fgcolor,
                -- bmgrp.idGrupo,
                mtzp.descProcesso as processoPri,    
                IF((SELECT idRisco FROM z_sga_mtz_mitigacao_risco mr WHERE mr.idRisco = mtzr.idMtzRisco LIMIT 1) IS NOT NULL, 'Mitigado' , 'Não mitigado') as mitigado,
                group_concat(distinct bmgrp.cod_programa order by bmgrp.cod_programa separator ' | ')   
                -- (select  group_concat(distinct bmgrpi.cod_programa order by bmgrpi.cod_programa separator ' | ') 
                --	from v_sga_mtz_base_matriz_por_grupo bmgrpi where bmgrpi.idProcesso = cp.idProcessoPrim 
                --												and bmgrpi.idGrupo  = bmgrp.idGrupo 
                --                                              and bmgrpi.empGrupo   = bmgrp.empGrupo
                --           									group by bmgrpi.idProcesso) 
                as progspPri, 
                (select count(mtapsp.idPrograma) from z_sga_mtz_apps_processo mtapsp Where mtapsp.idProcesso = cp.idProcessoPrim)  as nroAppsPPrinc,
                (select mtzpi.descProcesso from z_sga_mtz_processo mtzpi where mtzpi.idProcesso = cp.idProcessoSec) as processoSec,
                (select group_concat(distinct bmgrpi.cod_programa separator ' | ') 
                 from v_sga_mtz_base_matriz_usuario bmgrpi where bmgrpi.idProcesso = cp.idProcessoSec  
                                                             and bmgrpi.idGrupo = bmgrp.idGrupo
                                                             and bmgrpi.empGrupo  = bmgrp.empGrupo
                                                             group by bmgrpi.idProcesso) 
                        as progspSec,
                     (select count(mtapsp.idPrograma) from z_sga_mtz_apps_processo mtapsp Where mtapsp.idProcesso = cp.idProcessoSec)  as nroAppsPSec
                  from z_sga_mtz_coorelacao_processo cp,
                       v_sga_mtz_base_matriz_por_grupo_foto bmgrp,
                       z_sga_mtz_grau_risco gr,
                       z_sga_mtz_processo mtzp,
                       z_sga_mtz_risco mtzr,
                       z_sga_mtz_area area
                  where (cp.idProcessoPrim = bmgrp.idProcesso
                          and cp.idProcessoSec in (select bmgrpi.idProcesso from v_sga_mtz_base_matriz_usuario bmgrpi where bmgrpi.idProcesso = cp.idProcessoSec and bmgrpi.idGrupo = bmgrp.idGrupo )
                         )
                    and gr.idGrauRisco = cp.idGrauRisco
                    and mtzp.idProcesso = cp.idProcessoPrim
                    and mtzr.idMtzRisco = mtzp.idMtzRisco
                    and area.idArea = mtzr.idArea
                    -- filtros aqui
                     and bmgrp.idSolicitacao = $idSolicitacao -- Filtro por solicitação
                    --
                   group by bmgrp.idGrupo, cp.idProcessoPrim, cp.idProcessoSec
                    ";

        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0):
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        else:
            return array();
        endif;
    }
    
    public function parametrosFluxo($idSolicitacao)
    {
        $sql = "
            SELECT 
                f.parametros 
            FROM 
                z_sga_fluxo f
            INNER JOIN
                z_sga_fluxo_solicitacao s
                ON s.idForm = f.idFluxo            
            WHERE 
                s.idSolicitacao = $idSolicitacao";
        $sql = $this->db->query($sql);
        
        if($sql->rowCount() > 0):
            return $sql->fetch(PDO::FETCH_ASSOC);
        endif;
        
        return false;
    }
    
    /**
     * Retorna os programas pertencetes aos grupos informados
     * @param type $grupos
     * @return type
     */
    public function carregaAbaProgFluxoByGrupoAdicionados($search, $orderColumn, $orderDir, $offset, $limit, $idUsuario, $idEmpresa)
    {
        
        if(empty($idUsuario)):
            return array();
        endif;
        
        $sql = "
            SELECT                
                GROUP_CONCAT(DISTINCT concat_ws(' - ', g.idLegGrupo) ORDER BY g.idLegGrupo SEPARATOR ' <br> ') AS grupo,
                p.cod_programa,
                p.descricao_programa,
                p.cod_modulo,
                p.descricao_modulo,
                p.descricao_rotina,
                p.especific
            FROM
                z_sga_programas p
            LEFT JOIN
                z_sga_grupo_programa gp
                ON gp.idPrograma = p.z_sga_programas_id
            LEFT JOIN
                z_sga_grupo g
                ON gp.idGrupo = g.idGrupo
            LEFT JOIN
                z_sga_programa_empresa pe
                ON pe.idPrograma = p.z_sga_programas_id
            WHERE
                pe.idEmpresa = $idEmpresa
                AND gp.idGrupo IN(SELECT idGrupo FROM z_sga_grupos WHERE idUSuario = $idUsuario)
            ";
        
        // Se a variavel $search não estiver vazia o where no select
        if($search != ''):            
            $sql .= " AND g.descAbrev LIKE '%$search%'";
            $sql .= " OR p.cod_programa LIKE '%$search%'";
            $sql .= " OR p.descricao_programa LIKE '%$search%'";
            $sql .= " OR p.cod_modulo LIKE '%$search%'";
            $sql .= " OR p.descricao_modulo LIKE '%$search%'";
        endif;

        $sql .= "
            GROUP BY
                p.cod_programa
        ";

        // Se a variavel $order não estiver vazia ordena o retorno
        if($orderColumn != ''):
            $sql .= " ORDER BY $orderColumn $orderDir";
        endif;

        // Se as variaveis $offset e $limit não estiverem vazias limita o retorno
        if($offset != '' && $limit != ''):
            $sql .= " LIMIT $offset, $limit";
        endif;
        
        $sql = $this->db->query($sql);
        
        if($sql->rowCount() > 0):
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        else:
            return array();
        endif;
    }
    
    /**
     * Retorna os programas pertencetes aos grupos informados
     * @param type $grupos
     * @return type
     */
    public function carregaAbaProgFluxoByGrupo($search, $orderColumn, $orderDir, $offset, $limit, $grupos, $idEmpresa)
    {
        
        if(empty($grupos)):
            return array();
        endif;
        
        $sql = "
            SELECT                
                GROUP_CONCAT(DISTINCT concat_ws(' - ', g.idLegGrupo) ORDER BY g.idLegGrupo SEPARATOR ' <br> ') AS grupo,
                p.cod_programa,
                p.descricao_programa,
                p.cod_modulo,
                p.descricao_modulo,
                p.descricao_rotina,
                p.especific
            FROM
                z_sga_programas p
            LEFT JOIN
                z_sga_grupo_programa gp
                ON gp.idPrograma = p.z_sga_programas_id
            LEFT JOIN
                z_sga_grupo g
                ON gp.idGrupo = g.idGrupo
            LEFT JOIN
                z_sga_programa_empresa pe
                ON pe.idPrograma = p.z_sga_programas_id
            WHERE
                pe.idEmpresa = $idEmpresa
                AND gp.idGrupo IN($grupos)
            GROUP BY
                p.cod_programa";
        
        // Se a variavel $search não estiver vazia o where no select
        if($search != ''):            
            $sql .= " AND g.descAbrev LIKE '%$search%'";
            $sql .= " OR p.cod_programa LIKE '%$search%'";
            $sql .= " OR p.descricao_programa LIKE '%$search%'";
            $sql .= " OR p.cod_modulo LIKE '%$search%'";
            $sql .= " OR p.descricao_modulo LIKE '%$search%'";
        endif;

        // Se a variavel $order não estiver vazia ordena o retorno
        if($orderColumn != ''):
            $sql .= " ORDER BY $orderColumn $orderDir";
        endif;

        // Se as variaveis $offset e $limit não estiverem vazias limita o retorno
        if($offset != '' && $limit != ''):
            $sql .= " LIMIT $offset, $limit";
        endif;
        
        $sql = $this->db->query($sql);
        
        if($sql->rowCount() > 0):
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        else:
            return array();
        endif;
    }
    
    /**
     * Responsável por retornar o total de registros encontrados na tabela
     * @param $table String contendo nome da tabela a se consultar
     * @param $search String contendo o filtro
     * @param $fields Array com campos a filtrar
     * @param $join String com a relação entre tabelas
     * @param $idEmpresa Int contendo o id da empresa a incluir no filtro
     * @return mixed Retorna o total de registros encontrados
     */
    public function getCountTableAbaProgsAdicionados($table, $search, $fields, $join, $idEmpresa = '', $idUsuario){
        
        if(empty($idUsuario)):
            return 0;
        endif;
        
        $dados = array();
        $where = '';
        $sql = "SELECT count(DISTINCT p.z_sga_programas_id) AS total FROM $table ";

        $sql .= "
            LEFT JOIN
                z_sga_grupo_programa gp
                ON gp.idPrograma = p.z_sga_programas_id
            LEFT JOIN
                z_sga_grupo g
                ON gp.idGrupo = g.idGrupo
            LEFT JOIN
                z_sga_programa_empresa e
                ON e.idPrograma = p.z_sga_programas_id";

        // Se a variavel $search não estiver vazia limita cria o where no select
        if($search != '' && count($fields) > 0):
            $i = 0;
            foreach($fields as $val):
                $where .= (($i == 0) ? ' WHERE ' : ' OR ') . "$val LIKE '%$search%'";
                $i++;
            endforeach;
        endif;

        $sql .= $where;
      
        $sql .= (($where == '') ? ' WHERE ' : ' AND ') . " e.idEmpresa = $idEmpresa AND gp.idGrupo IN(SELECT idGrupo FROM z_sga_grupos WHERE idUsuario = $idUsuario)";        
        //echo $sql;//die('');
        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0):
            $dados = $sql->fetchAll();
        endif;

        return $dados[0]['total'];
    }
    
    
    /**
     * Responsável por retornar o total de registros encontrados na tabela
     * @param $table String contendo nome da tabela a se consultar
     * @param $search String contendo o filtro
     * @param $fields Array com campos a filtrar
     * @param $join String com a relação entre tabelas
     * @param $idEmpresa Int contendo o id da empresa a incluir no filtro
     * @return mixed Retorna o total de registros encontrados
     */
    public function getCountTableAbaProgs($table, $search, $fields, $join, $idEmpresa = '', $grupos){
        
         if(empty($grupos)):
            return 0;
        endif;
        
        $dados = array();
        $where = '';
        $sql = "SELECT count(DISTINCT p.z_sga_programas_id) AS total FROM $table ";

        $sql .= "
            LEFT JOIN
                z_sga_grupo_programa gp
                ON gp.idPrograma = p.z_sga_programas_id
            LEFT JOIN
                z_sga_grupo g
                ON gp.idGrupo = g.idGrupo
            LEFT JOIN
                z_sga_programa_empresa e
                ON e.idPrograma = p.z_sga_programas_id";

        // Se a variavel $search não estiver vazia limita cria o where no select
        if($search != '' && count($fields) > 0):
            $i = 0;
            foreach($fields as $val):
                $where .= (($i == 0) ? ' WHERE ' : ' OR ') . "$val LIKE '%$search%'";
                $i++;
            endforeach;
        endif;

        $sql .= $where;
      
        $sql .= (($where == '') ? ' WHERE ' : ' AND ') . " e.idEmpresa = $idEmpresa AND gp.idGrupo IN(".(is_array($grupos) ? implode(',', $grupos) : $grupos).")";        
        //echo $sql;//die('');
        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0):
            $dados = $sql->fetchAll();
        endif;

        return $dados[0]['total'];
    }
    
    
    /**
     * Recupera as atividade solicitante, filtrando pelo idFluxo
     * @param type $idFluxo
     * @param type $idEmpresa
     * @return type
     */
    public function buscaIdAtividadeSolicitante($idFluxo, $idEmpresa = '')
    {
        try{
            $sql = "
               SELECT
                    MIN(id) AS idAtividade
                FROM
                    z_sga_fluxo_atividade                
                WHERE
                    idFluxo = $idFluxo";
            
            $sql = $this->db->query($sql);
            
            if($sql->rowCount() > 0):                
                $sql = $sql->fetch(PDO::FETCH_ASSOC);
                return $sql['idAtividade'];
            else:
                return array(
                    'return'    => false,
                    'error'     => 'Nenhum gestor encontrado'
                );
            endif;
        } catch (Exception $e) {
            return array(
                'return'    => false,
                'error'     => $e->getMessage()
            );
        }
        
    }
    
    /**
     * Recupera as atividade solicitante, filtrando pelo idFluxo
     * @param type $idFuncao
     * @param type $idUsuario
     * @return type
     */
    public function buscaFuncaoGruposRemover($idFuncao, $idUsuario)
    {
        try{
            $sql = "
               SELECT 
                    g.idLegGrupo,
                    g.descAbrev,
                    (SELECT COUNT(*) FROM z_sga_grupo_programa grp WHERE grp.idGrupo = g.idGrupo) AS nrProgramas,
                    (SELECT COUNT(*) FROM z_sga_grupos grp WHERE grp.idGrupo = g.idGrupo) AS nrUsuarios,
                    g.idGrupo,
                    $idUsuario AS iduSuario
                FROM
                    z_sga_grupo g
                LEFT JOIN
                    z_sga_grupos gs
                    ON gs.idGrupo = g.idGrupo
                WHERE
                    gs.idUsuario = $idUsuario
                    AND gs.idGrupo NOT IN(SELECT pfg.idGrupo FROM z_sga_prov_funcao_grupo pfg WHERE pfg.idFuncao = $idFuncao)
                    AND gs.idGrupo NOT IN(SELECT idGrupo FROM z_sga_grupo WHERE idLegGrupo = '*')
                    AND g.idEmpresa = " . $_SESSION['empresaid'];
            
            $sql = $this->db->query($sql);
            
            if($sql->rowCount() > 0):
                return array(
                    'return'    => true,
                    'rs'     => $sql->fetchAll(PDO::FETCH_ASSOC)
                );                
            else:
                return array(
                    'return'    => false,
                    'error'     => 'Nenhum grupo encontrado'
                );
            endif;
        } catch (Exception $e) {
            return array(
                'return'    => false,
                'error'     => $e->getMessage()
            );
        }
        
    }
    
    public function getOrderCollumnsDataTable($atividades, $sequencia_aprovacao = [], $proximaAtiv = null)
    {
        if(count($sequencia_aprovacao) == 0):
            $idGU = $atividades[0]['proximaAtiv'];
            unset($atividades[0]);
            
            foreach($atividades as $key => $val):
                if($val['idAtividade'] == $idGU):
                    $sequencia_aprovacao[$val['proximaAtiv']] = lcfirst(str_replace('ó', 'o', str_replace(' ', '', str_replace('Aprovação ', '', $val['descProxAtividade']))));
                    $proximaAtiv = $val['proximaAtiv'];
                    unset($atividades[$key]);
                    break;
                endif;
            endforeach;
        else:
            foreach($atividades as $key => $val):
                //echo $val['idAtividade'] . ' == ' . $proximaAtiv."<br>";
                if($val['descProxAtividade'] == 'Final'):
                    unset($atividades[$key]);
                    continue;
                else:
                    if($val['idAtividade'] == $proximaAtiv):
                        $sequencia_aprovacao[$val['proximaAtiv']] = lcfirst(str_replace('ó', 'o', str_replace(' ', '', str_replace('Aprovação ', '', $val['descProxAtividade']))));
                        $proximaAtiv = $val['proximaAtiv'];
                        unset($atividades[$key]);
                        break;
                    endif;
                endif;
            endforeach;  
        endif;                        
                
        
        //echo "<pre>";
        //print_r($atividades);
        //print_r($sequencia_aprovacao);
        //echo count($atividades)."<br>";

        //if(isset($atividades[0]['proximaAtiv'])):
        $atividades = array_values($atividades);
        //if(count($atividades) > 0):            
            return array(
                'atividades'          => $atividades,
                'sequencia_aprovacao' => $sequencia_aprovacao,
                'proximaAtiv'         => $proximaAtiv
            );
            //$this->getOrderCollumnsDataTable($atividades, $sequencia_aprovacao, $proximaAtiv);
        //else:
            //print_r($sequencia_aprovacao);
            //return $sequencia_aprovacao;
        //endif;
        
    }
    
    public function getSeqAtividades($idFluxo)
    {                
        // Retorna o id da atividade gestor de usuário
        $sql = "
            SELECT 
                #*,    
                a.id AS idAtividade,
                a.descricao AS descAtividade,
                (SELECT id FROM z_sga_fluxo_atividade fa WHERE fa.id = a.proximaAtiv) AS proximaAtiv,
                (SELECT descricao FROM z_sga_fluxo_atividade fa WHERE fa.id = a.proximaAtiv) AS descProxAtividade,
                ativo
            FROM 
                z_sga_fluxo_atividade a
            WHERE
                idFluxo = $idFluxo
                AND descricao NOT IN('S.I','Final')
                AND id BETWEEN (SELECT MIN(id) FROM z_sga_fluxo_atividade) AND (SELECT MAX(id) FROM z_sga_fluxo_atividade)
                AND ativo = 1
            order by a.proximaAtiv";

        try{
            $rsIdGU = $this->db->query($sql);

            if($rsIdGU->rowCount() > 0):
                return $rsIdGU->fetchAll(PDO::FETCH_ASSOC);                                       
            endif;
        } catch (Exception $e) {                   
            die($e->getMessage());
        }
        
    }

    public function carregaCartasRisco()
    {
        $select = "
            SELECT 
                cr.id,
                cr.idSolicitacao,
                cartaRisco,

                (SELECT 
                    nome_usuario
                FROM
                    z_sga_usuarios as u,
                    z_sga_fluxo_documento as d
                WHERE
                    d.idSolicitacao = cr.idSolicitacao AND
                    u.z_sga_usuarios_id = REPLACE(JSON_EXTRACT(documento, '$.idSolicitante'), '\"', '')
                ) as solicitante,

                REPLACE(JSON_EXTRACT(d.documento, '$.usuario'), '\"', '') as usuario
            FROM
                z_sga_fluxo_carta_risco as cr,
                z_sga_fluxo_documento as d
        ";

        $select = $this->db->query($select);

        $res = array();

        if ($select->rowCount() > 0) {
            $res = $select->fetchAll(PDO::FETCH_ASSOC);
        }

        return $res;
    }
}
