<?php

class Matriz extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function cadastraArquivo($nome, $idMitigacao, $codArquivo)
    {
        $sql = "INSERT INTO z_sga_mtz_midigacao_arquivo SET nomeArquivo = '$nome',idMitigacao ='$idMitigacao',codArquivo = '$codArquivo' ";
        $sql = $this->db->query($sql);
    }

    public function cadastraMitigacao($mitigacao, $descricao)
    {
        $sql = "INSERT INTO z_sga_mtz_mitigacao SET mitigacao = '$mitigacao',descricao ='$descricao'";
        $sql = $this->db->query($sql);

        $sql2 = "SELECT MAX(idMitigacao) as id FROM z_sga_mtz_mitigacao ";
        $sql2 = $this->db->query($sql2);
        $dados = array();
        if ($sql2->rowCount() > 0) {
            $dados = $sql2->fetch();
        }
        return $dados;
    }


    public function excluirPlanoMitiga($id)
    {
        $sql = "DELETE FROM z_sga_mtz_mitigacao where idMitigacao = '$id'";
        $this->db->query($sql);
        $sql2 = "DELETE FROM z_sga_mtz_mitigacao_risco where idMitigacao = '$id'";
        $this->db->query($sql2);
        $sql3 = "DELETE FROM z_sga_mtz_midigacao_arquivo where idMitigacao = '$id'";
        $this->db->query($sql3);
    }

    public function ajaxCarregaRiscoMitiga($risco)
    {
        $sql2 = "SELECT * FROM z_sga_mtz_risco where codRisco like '%" . $risco . "%' LIMIT 10 ";
        $sql2 = $this->db->query($sql2);
        $dados = array();
        if ($sql2->rowCount() > 0) {
            $dados = $sql2->fetchAll();
        }

        return $dados;
    }

    public function ajaxCadastraRiscoMitiga($idCodRiscoMitiga, $idMitigacao)
    {
        $sql = "INSERT INTO z_sga_mtz_mitigacao_risco set idMitigacao = '$idMitigacao',idRisco = '$idCodRiscoMitiga' ";
        $sql = $this->db->query($sql);
    }


    public function ajaxCarregaTabelaRiscoMitiga($id)
    {
        $sql2 = "SELECT * from 
              z_sga_mtz_mitigacao_risco as mr,
              z_sga_mtz_risco as riscos
              where
              mr.idRisco = riscos.idMtzRisco AND idMitigacao = '$id'";
        $sql2 = $this->db->query($sql2);
        $dados = array();
        if ($sql2->rowCount() > 0) {
            $dados = $sql2->fetchAll();
        }

        return $dados;
    }


    public function updateMitigacao($mitigacao, $descricao, $idMitigacao)
    {
        $sql = "UPDATE z_sga_mtz_mitigacao SET mitigacao = '$mitigacao',descricao = '$descricao' where idMitigacao = '$idMitigacao'  ";
        $sql = $this->db->query($sql);
    }


    public function carregaMitigacao()
    {
        $sql = "SELECT m.idMitigacao,m.mitigacao,m.descricao, (SELECT COUNT(idArquivo) FROM z_sga_mtz_midigacao_arquivo where idMitigacao = m.idMitigacao) as total FROM z_sga_mtz_mitigacao as m";
        $sql = $this->db->query($sql);
        $dados = array();
        if ($sql->rowCount() > 0) {
            $dados = $sql->fetchAll();
        }
        return $dados;
    }

    public function carregaMitigacaoAjax($id)
    {
        $sql = "SELECT * FROM z_sga_mtz_mitigacao where idMitigacao = '$id' ";
        $sql = $this->db->query($sql);
        $dados = array();
        if ($sql->rowCount() > 0) {
            $dados = $sql->fetch();
        }
        return $dados;
    }

    public function excluirDocumentoMitigacao($id)
    {
        $sql = "DELETE FROM z_sga_mtz_midigacao_arquivo WHERE idArquivo ='$id' ";
        $sql = $this->db->query($sql);
    }

    public function carregaMitigacaoDocumentoAjax($id)
    {
        $sql = "SELECT * from z_sga_mtz_midigacao_arquivo where idMitigacao = '$id' ";        
        $sql = $this->db->query($sql);
        $dados = array();
        if ($sql->rowCount() > 0) {
            $dados = $sql->fetchAll();
        }
        return $dados;
    }


    public function excluirRiscoMitigacao($id)
    {
        $sql = "DELETE FROM z_sga_mtz_mitigacao_risco where idMitigacaoRisco = '$id'";
        $sql = $this->db->query($sql);
    }


    public function carregaMatrizDeRisco()
    {
        //$sql = "SELECT * FROM v_sga_mtz_matriz_de_risco  order by descArea,codRisco";
        $sql = "
            SELECT 
                *,
                IF((SELECT idRisco FROM z_sga_mtz_mitigacao_risco mr WHERE mr.idRisco = m.idMtzRisco LIMIT 1) IS NOT NULL, 'Mitigado' , 'Não mitigado') as mitigado
            FROM 
                v_sga_mtz_matriz_de_risco mdr
            LEFT JOIN
                z_sga_mtz_risco m
                ON mdr.codRisco = m.codRisco
            ORDER BY 
                mdr.descArea, mdr.codRisco";
        $sql = $this->db->query($sql);
        $dados = array();
        if ($sql->rowCount() > 0) {
            $dados = $sql->fetchAll();
        }
        return $dados;
    }

    public function cadastraArea($area, $responsavel)
    {
        $sql = "INSERT INTO z_sga_mtz_area SET descricao='$area',responsavel='$responsavel'";
        $this->db->query($sql);
        return $this->db->lastInsertId();
    }

    public function atualizaArea($areaEdit, $idAreaEdit, $responsavelEdit)
    {
        $sql = "UPDATE z_sga_mtz_area SET descricao='$areaEdit',responsavel='$responsavelEdit' where idArea = '$idAreaEdit'";
        $this->db->query($sql);
    }

    public function carregaArea()
    {
        $sql = "
            SELECT 
                area.idArea, 
                area.descricao,
                area.responsavel,
                usr.nome_usuario,
                e.ativo,
                (SELECT COUNT(idArea) FROM z_sga_mtz_risco WHERE idArea = area.idArea)  AS relacionamentos
            FROM 
                z_sga_mtz_area as area,
                z_sga_usuarios as usr,
                z_sga_usuario_empresa as e
            WHERE 
                area.responsavel = usr.z_sga_usuarios_id
                AND e.idUsuario = usr.z_sga_usuarios_id";
        $sql = $this->db->query($sql);
        $dados = array();
        if ($sql->rowCount() > 0) {
            $dados = $sql->fetchAll();
        }
        return $dados;
    }

    public function carregaMtzRisco($idProg)
    {
        $sql = "SELECT * FROM z_sga_mtz_risco where codRisco  LIKE '%" . $idProg . "%' or descricao LIKE '%" . $idProg . "%'  LIMIT 10";
        $sql = $this->db->query($sql);
        $dados = array();
        if ($sql->rowCount() > 0) {
            $dados = $sql->fetchAll();
        }
        return $dados;
    }

    public function carregaMtzRiscoDesc($idMtzRisco)
    {
        $sql = "SELECT * FROM z_sga_mtz_risco where idMtzRisco = '$idMtzRisco' ";
        $sql = $this->db->query($sql);
        $dados = array();
        if ($sql->rowCount() > 0) {
            $dados = $sql->fetch();
        }
        return $dados;
    }

    public function carregaDadosMitiga($idMtzRisco)
    {
        $sql = "SELECT r.idMtzRisco,r.codRisco,r.descricao,r.idArea,r.idRisco,r.impactoRisco,g.idGrauRisco,g.descricao as RiscoDesc FROM 
      z_sga_mtz_risco as r,
      z_sga_mtz_grau_risco as g
      where r.idRisco = g.idGrauRisco
      AND idMtzRisco = '$idMtzRisco' ";
        $sql = $this->db->query($sql);
        $dados = array();
        if ($sql->rowCount() > 0) {
            $dados = $sql->fetch();
        }
        return $dados;
    }

    public function carregaMtzGrupoProcesso($grupoProcesso)
    {
        $sql = "SELECT * FROM z_sga_mtz_grupo_de_processo where descricao  LIKE '%" . $grupoProcesso . "%' LIMIT 10";
        $sql = $this->db->query($sql);
        $dados = array();
        if ($sql->rowCount() > 0) {
            $dados = $sql->fetchAll();
        }
        return $dados;
    }

    public function carregaMtzGrauRisco($grauRisco)
    {
        $sql = "SELECT * FROM z_sga_mtz_grau_risco where descricao  LIKE '%" . $grauRisco . "%' LIMIT 10";
        $sql = $this->db->query($sql);
        $dados = array();
        if ($sql->rowCount() > 0) {
            $dados = $sql->fetchAll();
        }
        return $dados;
    }

    public function cadatraMtzProcesso($codIdRisco, $codGrupoProcesso, $codGrauRisco, $descricaoRisco)
    {
        $sql = "INSERT INTO z_sga_mtz_processo SET idMtzRisco = '$codIdRisco',descProcesso = '$descricaoRisco',idGrpProcesso = '$codGrupoProcesso',idGrauRisco = '$codGrauRisco'";
        $sql = $this->db->query($sql);

        $sql = "SELECT max(idProcesso) from z_sga_mtz_processo";
        $sql = $this->db->query($sql);

        $this->atualizaVMUsuarios();

        $dados = array();
        if ($sql->rowCount() > 0) {
            $dados = $sql->fetch();
        }
        return $dados;
    }

    public function carregaProcesso($id)
    {
        $sql = "SELECT process.idMtzRisco, risco.codRisco, risco.descricao as descricaoRisco,process.idGrpProcesso, grupo.descricao as descricaoGrupo, process.idGrauRisco, grau.descricao as descricaoGrau, process.descProcesso FROM 
      z_sga_mtz_processo as process, 
      z_sga_mtz_grau_risco as grau, 
      z_sga_mtz_grupo_de_processo as grupo,
      z_sga_mtz_risco as risco
      where idProcesso = '$id' and process.idMtzRisco = risco.idMtzRisco and  process.idGrpProcesso = grupo.idGrpProcesso and process.idGrauRisco =  grau.idGrauRisco ";
        $sql = $this->db->query($sql);
        $dados = array();
        if ($sql->rowCount() > 0) {
            $dados = $sql->fetch();
        }
        return $dados;
    }

    public function carregaProgramas($idProg)
    {
        $idEmpresa = $_SESSION['empresaid'];
        $sql = "
            SELECT 
                * 
            FROM 
                z_sga_programas as prog
            WHERE 
                cod_programa LIKE '" . $idProg . "%' or descricao_programa LIKE '" . $idProg . "%' 
            ORDER BY
                cod_programa
            LIMIT 11";
        $sql = $this->db->query($sql);
        $array = array();
        if ($sql->rowCount() > 0) {
            $array = $sql->fetchAll();
        }
        return $array;
    }

    public function cadastrarProgProcesso($idProcessoPrograma, $id)
    {
        $sql = "INSERT INTO z_sga_mtz_apps_processo set idProcesso = '$id', idPrograma = '$idProcessoPrograma'";
        
        try{
            $this->db->query($sql);            
            $this->atualizaVMUsuarios();
        } catch (Exception $e){
            die($e->getMessage());
        }
        
    }


    public function carregaCadastroMatriz()
    {
        $sql = "
            SELECT 
            *,     
            (
            SELECT 
                    COUNT(*)
                FROM
                    z_sga_mtz_processo p	
                WHERE
                    p.idMtzRisco = mtzr.idMtzRisco) as processos,
                (
            SELECT 
                    COUNT(*)
                FROM		
                    z_sga_mtz_mitigacao_risco mr		
                WHERE
                    mr.idRisco = mtzr.idMtzRisco) as mitigacoes
            FROM
                z_sga_mtz_risco mtzr
            ";
        $sql = $this->db->query($sql);
        $dados = array();
        if ($sql->rowCount() > 0) {
            $dados = $sql->fetchAll();
        }
        return $dados;
    }

    public function cadastroMatriz($risco, $descricao, $area)
    {
        $sql = "INSERT INTO z_sga_mtz_risco SET codRisco = '$risco',descricao = '$descricao',idArea = '$area'";
        $sql = $this->db->query($sql);
        return $this->db->lastInsertId();
    }

    public function atualizaMatriz($idRiscoEditar, $riscoEditar, $areaEditar, $descricaoEditar)
    {
        $sql = "UPDATE z_sga_mtz_risco SET codRisco = '$riscoEditar',descricao = '$descricaoEditar',idArea = '$areaEditar' where idMtzRisco = '$idRiscoEditar' ";
        $sql = $this->db->query($sql);
    }


    public function carregaProgProcesso($id)
    {
        $sql = "SELECT * FROM 
              z_sga_mtz_apps_processo as app,
              z_sga_programas as prog
              where app.idProcesso = '$id' and app.idPrograma = prog.z_sga_programas_id";
        $sql = $this->db->query($sql);
        $dados = array();
        if ($sql->rowCount() > 0) {
            $dados = $sql->fetchAll();
        }
        return $dados;
    }


    public function exluirProgProcesso($idAppProcesso)
    {
        $sql = "DELETE FROM z_sga_mtz_apps_processo where idAppProcesso = '$idAppProcesso' ";
        try{
            $this->db->query($sql);
            $this->atualizaVMUsuarios();
        } catch (Exception $e){
            die($e->getMessage());
        }
    }

    public function exluirProcessoCoorelato($idCorrelacao)
    {
        $sql = "DELETE FROM z_sga_mtz_coorelacao_processo where idCorrelacao = '$idCorrelacao' ";                
        try{
            $this->db->query($sql);
            
            $this->atualizaVMUsuarios();
        } catch (Exception $e) {
            die($e->getMessage());
        }        
    }

    public function excluirGrupoProcesso($idGrpProcesso)
    {
        $sql = "DELETE FROM z_sga_mtz_grupo_de_processo where idGrpProcesso = '$idGrpProcesso' ";
        $this->atualizaVMUsuarios();
        $this->db->query($sql);
    }

    // Exclui rigstros na tabela z_sga_mtz_grau_risco
    public function excluirGrauRisco($idGrpProcesso)
    {
        $sql = "DELETE FROM z_sga_mtz_grau_risco where idGrpProcesso = '$idGrpProcesso' ";
        $this->db->query($sql);
    }


    public function validaPrograma($aplicativo)
    {
        $sql = "SELECT * FROM  z_sga_programas where cod_programa = '$aplicativo'";
        $sql = $this->db->query($sql);
        if ($sql->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }


    public function carregaGrau()
    {
        $sql = "
            SELECT 
                gr.idGrauRisco AS idGrauRisco,
                gr.descricao AS descricao,
                gr.background AS background,
                gr.texto AS texto,
                (SELECT COUNT(idGrauRisco) FROM z_sga_mtz_processo WHERE idGrauRisco = gr.idGrauRisco) AS relacionamentos
            FROM z_sga_mtz_grau_risco AS gr";
            
        $sql = $this->db->query($sql);
        $dados = array();
        if ($sql->rowCount() > 0) {
            $dados = $sql->fetchAll();
        }
        return $dados;
  }

    /**
     * Exclui registros na tabela z_sga_mtz_grau_risco
     * @param $idGrauRisco
     * @return array|bool
     */
    public function excluiGrauRisco($idGrauRisco)
    {
        $sql = "DELETE FROM z_sga_mtz_grau_risco where idGrauRisco = '$idGrauRisco' ";
        try{
            $this->db->query($sql);
            return array('return' => true);
        }catch (Exception $e){
            return array(
                'return' => false,
                'error'  => $e->getMessage()
                );
        }
        return false;
    }

    /**
     * Exclui registros na tabela z_sga_mtz_area
     * @param $idArea
     * @return array|bool
     */
    public function excluiAreaRisco($idArea)
    {
        $sql = "DELETE FROM z_sga_mtz_area where idArea = '$idArea' ";
        try{
            $this->db->query($sql);
            return array('return' => true);
        }catch (Exception $e){
            return array(
                'return' => false,
                'error'  => $e->getMessage()
            );
        }
        return false;
    }

    public function ajaxCarregaPlanoMedigacao($idRisco)
    {
        $sql = "SELECT * FROM z_sga_mtz_mitigacao  WHERE mitigacao LIKE '%" . $idRisco . "%'";
        $sql = $this->db->query($sql);
        $dados = array();
        if ($sql->rowCount() > 0) {
            $dados = $sql->fetchAll();
        }
        return $dados;
    }


    public function carregaProcessoCorrelato($codProdCorrelato)
    {
        $sql = "SELECT * FROM 
            z_sga_mtz_processo as processo,
            z_sga_mtz_risco as risco
            where processo.idMtzRisco = risco.idMtzRisco and processo.descProcesso  LIKE '%" . $codProdCorrelato . "%' or risco.codRisco  LIKE '%" . $codProdCorrelato . "%' limit 10 ";
        $sql = $this->db->query($sql);
        $dados = array();
        if ($sql->rowCount() > 0) {
            $dados = $sql->fetchAll();
        }
        return $dados;
    }

    public function carregaProcessoCorrelatoTabela($id)
    {
        $sql = "SELECT coorelacao.idProcessoSec as IdProcesso,coorelacao.idCorrelacao,
    risco.codRisco as Risco,
       processo.descProcesso,
       gp.descricao as GrupoProcesso,       
       (select count(appspr.idPrograma) from z_sga_mtz_apps_processo appspr where appspr.idProcesso = coorelacao.idProcessoSec) as NroProgramas,
       gr.descricao as GraudeRisco,
       risco.codRisco
  FROM z_sga_mtz_coorelacao_processo as coorelacao,
       z_sga_mtz_processo as processo,
       z_sga_mtz_grupo_de_processo gp,
       z_sga_mtz_risco as risco,
       z_sga_mtz_grau_risco gr
    where coorelacao.idProcessoPrim = '$id' 
     and processo.idProcesso  = coorelacao.idProcessoSec
     and processo.idMtzRisco = risco.idMtzRisco
     and gr.idGrauRisco = coorelacao.idGrauRisco
     and gp.idGrpProcesso = processo.idGrpProcesso ";
        $sql = $this->db->query($sql);
        $dados = array();
        if ($sql->rowCount() > 0) {
            $dados = $sql->fetchAll();
        }
        return $dados;
    }

    public function cadastrarProcessoCorrelatos($idProcessoCorrelato, $idGrauCorrelato, $id)
    {
        $this->db->beginTransaction();
        $sql = "
            INSERT INTO 
                z_sga_mtz_coorelacao_processo 
            SET 
                idPRocessoPrim = '$id',
                idProcessoSec = '$idProcessoCorrelato',
                idGrauRisco = '$idGrauCorrelato'";

        try{
            $this->db->query($sql);
            
            $this->db->commit();
            
            $this->atualizaVMUsuarios();
            
            return array('return' => true);
        } catch (Exception $e) {
            $this->db->rollBack();
            return array(
                'return' => false,
                'error'  => $e->getMessage()
            );            
        }
        
    }


    public function carregaProcessoTabela()
    {
        $sql = "SELECT processo.idProcesso, processo.descProcesso, grupo.descricao, (SELECT count(idAppProcesso) FROM z_sga_mtz_apps_processo where idProcesso = processo.idProcesso) as totalPrograma, (SELECT count(idCorrelacao) FROM z_sga_mtz_coorelacao_processo where idProcessoPrim = processo.idProcesso) as totalCoorelacao FROM z_sga_mtz_processo as processo,
            z_sga_mtz_risco as risco,
            z_sga_mtz_grupo_de_processo as grupo
            where processo.idMtzRisco = risco.idMtzRisco and processo.idGrpProcesso = grupo.idGrpProcesso";
        $sql = $this->db->query($sql);
        $dados = array();
        if ($sql->rowCount() > 0) {
            $dados = $sql->fetchAll();
        }
        return $dados;
    }


    public function excluirProcesso($idProcesso)
    {
        $sql = "DELETE FROM z_sga_mtz_processo where idProcesso = '$idProcesso' ";
        $this->db->query($sql);

        $sql2 = "DELETE FROM z_sga_mtz_apps_processo where idProcesso = '$idProcesso' ";
        $this->db->query($sql2);

        $sql3 = "DELETE FROM z_sga_mtz_coorelacao_processo where idProcessoPrim = '$idProcesso' ";
        $this->atualizaVMUsuarios();
        $this->db->query($sql3);
    }

    public function cadastraGrupoProcesso($descricaoGrupo)
    {
        $sql = "INSERT into z_sga_mtz_grupo_de_processo set descricao  = '$descricaoGrupo'";
        $this->db->query($sql);
        $this->atualizaVMUsuarios();
        return $this->db->lastInsertId();
    }

    public function cadastraGrau($descricao, $background, $texto)
    {
        $sql = "INSERT into z_sga_mtz_grau_risco set descricao  = '$descricao',background = '$background',texto = '$texto'";
        $this->db->query($sql);
         return $this->db->lastInsertId();
    }

    public function editaGrau($descricao, $background, $texto, $idModal)
    {
        $sql = "UPDATE  z_sga_mtz_grau_risco set descricao  = '$descricao',background = '$background',texto = '$texto' where idGrauRisco = '$idModal'";
        $sql = $this->db->query($sql);

        return $sql->rowCount(); 
    }

    public function carregaCadstroGrupoProcesso()
    {
        $sql = "SELECT gr.idGrpProcesso, gr.descricao, (SELECT count(idGrpProcesso) FROM z_sga_mtz_processo where idGrpProcesso = gr.idGrpProcesso) as total FROM  z_sga_mtz_grupo_de_processo as gr";
        $sql = $this->db->query($sql);
        $dados = array();
        if ($sql->rowCount() > 0) {
            $dados = $sql->fetchAll();
        }
        return $dados;
    }

    public function atualizaGrupoProcesso($descricaoGrupoModal, $idDescricaoGrupoModal)
    {
        $sql = "UPDATE z_sga_mtz_grupo_de_processo set descricao = '$descricaoGrupoModal' where idGrpProcesso = '$idDescricaoGrupoModal' ";
        $this->db->query($sql);
    }
    
    public function excluiRisco($idRisco)
    {
        $sql = "DELETE FROM z_sga_mtz_risco where idMtzRisco = $idRisco";
        
        try{
            $this->db->query($sql);
            $this->atualizaVMUsuarios();
            return array(
                'return' => true
            );
        } catch (Exception $e) {
            return array(
                'return' => false,
                'error'  => $e->getMessage()
            );
        }
        
    }
	
	/**
     * Atualiza tabela z_sga_vm_usuarios_refresh
     */
    public function atualizaVMUsuarios()
    {
        $sql = "            
            UPDATE
                z_sga_vm_usuarios_refresh
            SET                            
                atualiza = 1";

        $this->db->query($sql);

    }


}