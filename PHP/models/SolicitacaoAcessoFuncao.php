<?php

class SolicitacaoAcessoFuncao extends Model 
{
    
    
    public function __construct() {
        parent::__construct();
    }    
    
    /**
     * Retorna as funções cadastrada
     * @return type
     */
    public function funcoes()
    {
        $sql = "
            SELECT idFuncao, descricao FROM z_sga_manut_funcao WHERE descricao NOT IN('Não Cadastrado')";
        
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    /**
     * Retorna os grupos pertecentes a função passada
     * @param type $idFuncao
     * @param type $idUsuario
     * @return type
     */
    public function carregaGruposFuncaoByIdFuncao($idFuncao, $idUsuario)
    {
        $query = "
            SELECT 
                g.idGrupo,
                g.idLegGrupo,
                g.descAbrev,
                (SELECT nome_usuario FROM z_sga_usuarios u LEFT JOIN z_sga_gestor_grupo gg ON gg.idGestor = u.z_sga_usuarios_id WHERE gg.idGrupo = g.idLegGrupo) AS gestorGrupo,
                (SELECT COUNT(*) FROM z_sga_grupo_programa grp WHERE grp.idGrupo = g.idGrupo) AS nrProgramas,
                (SELECT COUNT(*) FROM z_sga_grupos grp WHERE grp.idGrupo = g.idGrupo) AS nrUsuarios,
                IF((SELECT idGrupo FROM z_sga_grupos gs WHERE gs.idUsuario = $idUsuario AND gs.idGrupo = g.idGrupo), 'sim', 'não') AS possui
            FROM
                z_sga_grupo g
            LEFT JOIN
                z_sga_prov_funcao_grupo pfg
                ON pfg.idGrupo = g.idGrupo
            WHERE
                pfg.idFuncao = $idFuncao
                AND g.idEmpresa = ". $_SESSION['empresaid'];
        
        try{
            $rs = $this->db->query($query);
            
            if($rs->rowCount() > 0):
                return array(
                    'return' => true,
                    'rs'     => $rs->fetchAll(PDO::FETCH_ASSOC)
                );
            endif;
        } catch (Exception $e) {
            return array(
                'return' => false,
                'error'  => $e->getMessage()
            );
        }
               
        return array();                
    }
    
    /**
     * Retorna os grupos pertecentes a função que o usuário possui
     * @param type $idUsuario
     * @return type
     */
    public function carregaGruposFuncaoByIdUsuario($idUsuario)
    {
        $query = "
            SELECT 
                g.idGrupo,
                g.idLegGrupo,
                g.descAbrev,
                (SELECT nome_usuario FROM z_sga_usuarios u LEFT JOIN z_sga_gestor_grupo gg ON gg.idGestor = u.z_sga_usuarios_id WHERE gg.idLegGrupo = g.idLegGrupo) AS gestorGrupo,
                (SELECT COUNT(*) FROM z_sga_grupo_programa grp WHERE grp.idGrupo = g.idGrupo) AS nrProgramas,
                (SELECT COUNT(*) FROM z_sga_grupos grp WHERE grp.idGrupo = g.idGrupo) AS nrUsuarios
            FROM
                z_sga_grupo g
            LEFT JOIN
                z_sga_prov_funcao_grupo pfg
                ON pfg.idGrupo = g.idGrupo
            WHERE
                pfg.idFuncao IN(SELECT funcao FROM z_sga_usuarios WHERE z_sga_usuarios_id = $idUsuario)
                AND g.idEmpresa = ". $_SESSION['empresaid'];
        try{
            $rs = $this->db->query($query);
            
            if($rs->rowCount() > 0):
                return array(
                    'return' => true,
                    'rs'     => $rs->fetchAll(PDO::FETCH_ASSOC)
                );
            else:
                return array('return' => false);
            endif;
        } catch (Exception $e) {
            return array(
                'return' => false,
                'error'  => $e->getMessage()
            );
        }
                       
        return array('return' => false);
    }
    
    /**
     * Retorna os grupos pertecentes a função que o usuário possui
     * @param type $idUsuario
     * @param type $idFuncao
     * @return type
     */
    public function carregaGruposUsuarioPossuiNaFuncao($idUsuario, $idFuncao)
    {
        $query = "
            SELECT 
                g.idGrupo,
                g.idLegGrupo,
                g.descAbrev,
                (SELECT nome_usuario FROM z_sga_usuarios u LEFT JOIN z_sga_gestor_grupo gg ON gg.idGestor = u.z_sga_usuarios_id WHERE gg.idGrupo = g.idLegGrupo) AS gestorGrupo,
                (SELECT COUNT(*) FROM z_sga_grupo_programa grp WHERE grp.idGrupo = g.idGrupo) AS nrProgramas,
                (SELECT COUNT(*) FROM z_sga_grupos grp WHERE grp.idGrupo = g.idGrupo) AS nrUsuarios
            FROM
                z_sga_grupo g
            LEFT JOIN
                z_sga_grupos gs
                ON gs.idGrupo = g.idGrupo
            LEFT JOIN
                z_sga_prov_funcao_grupo pfg
                ON pfg.idGrupo = gs.idGrupo
            WHERE
                gs.idUsuario = $idUsuario
                AND pfg.idFuncao IN($idFuncao)
                AND g.idEmpresa = ". $_SESSION['empresaid'];
        try{
            $rs = $this->db->query($query);
            
            if($rs->rowCount() > 0):
                return array(
                    'return' => true,
                    'rs'     => $rs->fetchAll(PDO::FETCH_ASSOC)
                );
            else:
                return array('return' => false);
            endif;
        } catch (Exception $e) {
            return array(
                'return' => false,
                'error'  => $e->getMessage()
            );
        }
               
        return array('return' => false);
    }
    
    /**
     * Retorna os grupos pertecentes a função que o usuário possui
     * @param type $idUsuario
     * @param type $idFuncao
     * @return type
     */
    public function carregaGruposUsuarioPossuiForaFuncao($idUsuario, $idFuncao)
    {
        $query = "
            SELECT
                g.idGrupo,
                g.idLegGrupo,
                g.descAbrev,
                (SELECT nome_usuario FROM z_sga_usuarios u LEFT JOIN z_sga_gestor_grupo gg ON gg.idGestor = u.z_sga_usuarios_id WHERE gg.idGrupo = g.idLegGrupo) AS gestorGrupo,
                (SELECT COUNT(*) FROM z_sga_grupo_programa grp WHERE grp.idGrupo = g.idGrupo) AS nrProgramas,
                (SELECT COUNT(*) FROM z_sga_grupos grp WHERE grp.idGrupo = g.idGrupo) AS nrUsuarios
            FROM
                z_sga_grupo g
            LEFT JOIN
                z_sga_grupos gs
                ON gs.idGrupo = g.idGrupo
            WHERE               
                gs.idUsuario = $idUsuario
                AND gs.idGrupo NOT IN(SELECT pfg.idGrupo FROM z_sga_prov_funcao_grupo pfg WHERE pfg.idFuncao = $idFuncao)
                AND g.idEmpresa = ". $_SESSION['empresaid'];
        try{
            $rs = $this->db->query($query);
            
            if($rs->rowCount() > 0):
                return array(
                    'return' => true,
                    'rs'     => $rs->fetchAll(PDO::FETCH_ASSOC)
                );
            else:
                return array('return' => false);
            endif;
        } catch (Exception $e) {
            return array(
                'return' => false,
                'error'  => $e->getMessage()
            );
        }
               
        return array('return' => false);
    }
    
    /**
     * Retorna os grupos pertecentes a função passada
     * @param type $idFuncao
     * @param type $idUsuario
     * @return type
     */
    public function carregaGruposFuncaoAdicionar($idFuncao, $idUsuario)
    {
        $query = "
            SELECT 
                g.idGrupo,
                g.idLegGrupo,
                g.descAbrev,
                (SELECT nome_usuario FROM z_sga_usuarios u LEFT JOIN z_sga_gestor_grupo gg ON gg.idGestor = u.z_sga_usuarios_id WHERE gg.idGrupo = g.idLegGrupo) AS gestorGrupo,
                (SELECT COUNT(*) FROM z_sga_grupo_programa grp WHERE grp.idGrupo = g.idGrupo) AS nrProgramas,
                (SELECT COUNT(*) FROM z_sga_grupos grp WHERE grp.idGrupo = g.idGrupo) AS nrUsuarios
            FROM
                z_sga_grupo g
            LEFT JOIN
                z_sga_prov_funcao_grupo pfg
                ON pfg.idGrupo = g.idGrupo
            WHERE
                pfg.idFuncao = $idFuncao
                AND pfg.idGrupo NOT IN(SELECT idGrupo FROM z_sga_grupos WHERE idUsuario = $idUsuario)
                AND g.idEmpresa = ". $_SESSION['empresaid'];
        
        try{
            $rs = $this->db->query($query);
            
            if($rs->rowCount() > 0):
                return array(
                    'return' => true,
                    'rs'     => $rs->fetchAll(PDO::FETCH_ASSOC)
                );
            endif;
        } catch (Exception $e) {
            return array(
                'return' => false,
                'error'  => $e->getMessage()
            );
        }
               
        return array();                
    }
    
    public function carregaDadosUsuario($idUsuario)
    {
        $query = "
            SELECT
                z_sga_usuarios_id AS idUsuario,
                funcao
            FROM
                z_sga_usuarios u
            LEFT JOIN
                z_sga_usuario_empresa e
                ON e.idUsuario = u.z_sga_usuarios_id
            WHERE
                u.z_sga_usuarios_id = $idUsuario
        ";
        
        try{
            $rs = $this->db->query($query);
            
            if($rs->rowCount() > 0):
                return $rs->fetch(PDO::FETCH_ASSOC);
            else:
                return array();
            endif;            
        } catch (Exception $e) {
            die($e);
        }
        
    }
    
    /**
     * Carrega os grupos e programas a adicionar
     * @param $idFuncoes
     * @param $idUsuario
     * @return type
     */
    public function carregaGrupoFuncoes($idProgramas, $idUsuario)
    {
        $sql = "
            SELECT
                gp.idPrograma,                
                (SELECT 
                    GROUP_CONCAT(DISTINCT concat_ws(' - ', pg.cod_programa) ORDER BY pg.cod_programa SEPARATOR ' | ')
                FROM
                    z_sga_programas pg
                LEFT JOIN
                    z_sga_grupo_programa gprog
                    ON pg.z_sga_programas_id = gprog.idPrograma
                WHERE
                    pg.z_sga_programas_id IN(".(is_array($idProgramas) ? implode(', ', $idProgramas) : $idProgramas).") AND gprog.idGrupo = gp.idGrupo) AS programas,
                g.idGrupo,
                g.idLegGrupo,
                g.descAbrev,
                (SELECT COUNT(*) FROM z_sga_grupo_programa grp WHERE grp.idGrupo = g.idGrupo) AS nrProgramas,
                (SELECT COUNT(*) FROM z_sga_grupos grp WHERE grp.idGrupo = gp.idGrupo) AS nrUsuarios
            FROM
                z_sga_grupo g
            LEFT JOIN
                z_sga_grupo_programa gp
                ON gp.idGrupo = g.idGrupo            
            LEFT JOIN
                z_sga_programa_empresa pe
                ON pe.idPrograma = gp.idPrograma            
            RIGHT JOIN
                z_sga_gestor_grupo gg
                ON gg.idGrupo = g.idLegGrupo
            WHERE
                gp.idPrograma IN(".(is_array($idProgramas) ? implode(', ', $idProgramas) : $idProgramas).")                
                AND gp.idGrupo NOT IN(SELECT idGrupo FROM z_sga_grupos gs WHERE idUsuario = $idUsuario AND gs.idGrupo = g.idGrupo)
                AND pe.idEmpresa = " . $_SESSION['empresaid'] . "
            GROUP BY
                gp.idGrupo
            ORDER BY
                nrProgramas ASC";
        
        $sql = $this->db->query($sql);
        
        if($sql->rowCount() > 0):
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        else:
            return [];
        endif;
    }
    
    /**
     * Carrega os grupos e funções a adicionar     
     * @param type $idUsuario
     * @return type
     */
    public function carregaGrupoFuncoesJaAdicionados($idUsuario)
    {
        $sql = "
            SELECT                              
                (SELECT 
                    GROUP_CONCAT(DISTINCT concat_ws(' - ', pg.cod_programa) ORDER BY pg.cod_programa SEPARATOR ' | ')
                FROM
                    z_sga_programas pg
                LEFT JOIN
                    z_sga_grupo_programa gprog
                    ON pg.z_sga_programas_id = gprog.idPrograma
                WHERE
                    
                    gprog.idGrupo = gps.idGrupo) AS programas,
                g.idGrupo,
                g.idLegGrupo,
                g.descAbrev,
                (SELECT COUNT(*) FROM z_sga_grupo_programa grp WHERE grp.idGrupo = g.idGrupo) AS nrProgramas,
                (SELECT COUNT(*) FROM z_sga_grupos grp WHERE grp.idGrupo = g.idGrupo) AS nrUsuarios
            FROM
                z_sga_grupo g
            LEFT JOIN
                z_sga_grupos gps
                ON gps.idGrupo = g.idGrupo
            LEFT JOIN
                z_sga_usuario_empresa e
                ON e.idUsuario = gps.idUsuario
            WHERE                
                gps.idUsuario = $idUsuario
                AND e.idEmpresa = " . $_SESSION['empresaid'] . "
            GROUP BY
                g.idGrupo
            ORDER BY
                nrProgramas ASC";
        
        $sql = $this->db->query($sql);
        
        if($sql->rowCount() > 0):
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        else:
            return array();
        endif;
    }
    
    /**
     * Busca se usuário já possui uma solicitação em aberto
     * @param type $post
     * @return type Array
     */
    public function validaSolicitacaoAberta($post)
    {
        $sql = "
            SELECT 
                s.idSolicitacao
            FROM 
                z_sga_fluxo_solicitacao s
            LEFT JOIN
                z_sga_fluxo_documento d
                ON s.idSolicitacao = d.idSolicitacao
            WHERE                 
                s.status = 1
                AND JSON_EXTRACT(d.documento, '$.idusuario') = '".$post['idUsuario']."'";
        
        $sql = $this->db->query($sql);
        
        if($sql->rowCount() > 0):                                                                   
            
            return array(
                'return'      => false,
                'solicitacao' => $sql->fetch(PDO::FETCH_ASSOC)['idSolicitacao']
            );            
        else:
            return true;
        endif;
    }
    
    /**
     * Retorna grupos pertencentes a usuário conforme id da função e id do usuário
     * @param type $post
     * @return type
     */
    public function validaGrupoFuncao($post)
    {
        $sql = "
            SELECT                 
                DISTINCT gs.cod_grupo
            FROM 
                z_sga_grupos gs
            LEFT JOIN
                z_sga_grupo_programa gp
                ON gs.idGrupo = gp.idGrupo
            LEFT JOIN
                z_sga_usuarios u
                ON gs.idUsuario = u.z_sga_usuarios_id
            WHERE
                gp.idPrograma IN(".$post['idPrograma'].")
                AND z_sga_usuarios_id in(".$post['idUsuario'].")
            ";
        
        $sql = $this->db->query($sql);
        
        if($sql->rowCount() > 0):
            return array(
                'return' => true,
                'grupos' => $sql->fetchAll(PDO::FETCH_ASSOC)
            );
        else:
            return array(
                'return' => false
            );
        endif;
    }
    
    /**
     * Retorna as funções pela string digitada pelo usuário
     * @param type $search
     * @return type
     */
    public function buscaFuncoes($search, $idUsuario, $funcsAdicionados)
    {
        $sqlNotInFuncs = (count($funcsAdicionados) > 0 ? " AND f.idFuncao NOT IN(".implode(',', $funcsAdicionados).") " : '');
        $sql = "
            SELECT 
                f.idFuncao,
                f.cod_funcao,
                f.descricao
            FROM 
                z_sga_manut_funcao f
            LEFT JOIN
                z_sga_prov_funcao_grupo fg 
                ON fg.idFuncao = f.idFuncao
            WHERE 
                /*pe.idEmpresa = ".$_SESSION['empresaid']."
                AND*/ (f.cod_funcao LIKE '$search%' 
                $sqlNotInFuncs
                OR f.descricao LIKE '$search%')";

        $sql = $this->db->query($sql);

        $array = array();
        if($sql->rowCount()>0){
            $array = $sql->fetchAll();
        }
        return $array;
    }
    
    /**
     * Carrega os usuários solicitantes
     * @param type $post
     * @return type
     */
    public function carregaUsuariosSolicitante($idusuario, $idEmpresa)
    {                
        // Busca os usuários sob a responsabilidade do usuário logado excluindo o mesmo da busca.                                              
        $sqlUsers = "
            SELECT
                u.z_sga_usuarios_id AS idUsuario,
                u.nome_usuario,
                f.descricao AS funcao
            FROM
                z_sga_usuarios u
            LEFT JOIN
                z_sga_manut_funcao f
                ON u.cod_funcao = f.idFuncao
            LEFT JOIN
                z_sga_usuario_empresa e
                ON e.idUsuario = u.z_sga_usuarios_id
            WHERE
                e.ativo = 1
                AND u.cod_gestor = '".$_SESSION['gestor']."'
                AND u.z_sga_usuarios_id NOT IN(".$_SESSION["idUsrTotvs"].")
                AND e.idEmpresa = " . $idEmpresa;

        try{
            $sqlUsers = $this->db->query($sqlUsers);

            if($sqlUsers->rowCount() > 0):                   
                return array(
                    'usuarios'    => $sqlUsers->fetchAll(PDO::FETCH_ASSOC)                    
                );                
            endif;
        } catch (Exception $e) {
            return array(
                'return' => false,
                'error'  => $e->getMessage()
            );
        }
        
    }
    
    /**
     * Retorna os usuários
     * @return type
     */
    public function usuariosAcompanhantes()
    {
        $sql = "
            SELECT 
                u.z_sga_usuarios_id,
                u.nome_usuario
            FROM 
                z_sga_usuarios u
            LEFT JOIN
                z_sga_usuario_empresa ue
                ON u.z_sga_usuarios_id = ue.idUsuario
            WHERE		
                ue.idEmpresa = ".$_SESSION['empresaid']."
                AND ue.ativo = 1
        ";
        
        $sql = $this->db->query($sql);
        
        if($sql->rowCount() > 0):
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        else:
            return [];
        endif;
    }
    
    /**
     * Retorna o id da ultima solicitação
     * @return type
     */
    public function nrSolicitacao()
    {
        $sql = "SELECT MAX(idSolicitacao) AS nrSolicitacao FROM z_sga_fluxo_solicitacao";
        $sql = $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
        return $sql['nrSolicitacao'];
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
            SELECT DISTINCT
                GROUP_CONCAT(DISTINCT concat_ws(' - ', g.idLegGrupo) ORDER BY g.idLegGrupo SEPARATOR ' | ') AS grupo,
                p.cod_programa,
                p.descricao_programa,
                p.cod_modulo,
                p.descricao_modulo
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
                AND gp.idGrupo IN(".(is_array($grupos) ? implode(',', $grupos) : $grupos).")
            GROUP BY
                p.cod_programa";
        
        // Se a variavel $search não estiver vazia o where no select
        if($search != ''):            
            $sql .= " AND g.idLegGrupo LIKE '%$search%'";
            $sql .= " OR g.descAbrev LIKE '%$search%'";
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
    public function getCountTableAbaProgs($table, $search, $fields, $join, $idEmpresa = '', $grupos){
        
         if(empty($grupos)):
            return 0;
        endif;
        
        $dados = array();
        $where = '';
        $sql = "SELECT count($fields[0]) AS total FROM $table ";

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
        
        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0):
            $dados = $sql->fetchAll();
        endif;

        return $dados[0]['total'];
    }

    public function carregaAbaPessoaisFluxoByFuncao($search, $orderColumn, $orderDir, $offset, $limit, $grupos, $idEmpresa)
    {
        
        if(empty($grupos)):
            return array();
        endif;
        
        $sql = " 
        SELECT DISTINCT
            GROUP_CONCAT(DISTINCT concat_ws(' - ', g.idLegGrupo) ORDER BY g.idLegGrupo SEPARATOR ' | ') AS grupo,
            p.cod_programa as 'cod_programa',
            p.descricao_programa as 'descricao_programa',
            zslc.`name` as 'Nome',
            if(zslc.`anonymize`=1,'Sim','Não') as 'Anonizado'
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
        INNER JOIN 
            z_sga_lgpd_campos_programas zslcp
            ON zslcp.idPrograma=p.z_sga_programas_id
        INNER join 
            z_sga_lgpd_campos zslc 
            ON zslc.id=zslcp.id_campo
        WHERE
            pe.idEmpresa = $idEmpresa
            AND gp.idGrupo IN(".(is_array($grupos) ? implode(',', $grupos) : $grupos).")
            AND (if(zslc.`sensitive`=0,'Sim','Não')='Sim')
        GROUP BY
            zslc.name";
               
        
        // Se a variavel $search não estiver vazia o where no select
        if($search != ''):            
            $sql .= " AND g.idLegGrupo LIKE '%$search%'";
            $sql .= " OR g.descAbrev LIKE '%$search%'";
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

    public function getCountTableAbaPessoais($table, $search, $fields, $join, $idEmpresa = '', $grupos){
        
        if(empty($grupos)):
           return 0;
       endif;
       
       $dados = array();
       $where = '';
       $sql = "SELECT count($fields[0]) AS total FROM $table ";

       $sql .= "
           LEFT JOIN
               z_sga_grupo_programa gp
               ON gp.idPrograma = p.z_sga_programas_id
           LEFT JOIN
               z_sga_grupo g
               ON gp.idGrupo = g.idGrupo
           LEFT JOIN
               z_sga_programa_empresa e
               ON e.idPrograma = p.z_sga_programas_id
           INNER JOIN 
               z_sga_lgpd_campos_programas zslcp
               ON zslcp.idPrograma=p.z_sga_programas_id
           INNER join 
               z_sga_lgpd_campos zslc 
               ON zslc.id=zslcp.id_campo";

       // Se a variavel $search não estiver vazia limita cria o where no select
       if($search != '' && count($fields) > 0):
           $i = 0;
           foreach($fields as $val):
               $where .= (($i == 0) ? ' WHERE ' : ' OR ') . "$val LIKE '%$search%'";
               $i++;
           endforeach;
       endif;

       $sql .= $where;
     
       $sql .= (($where == '') ? ' WHERE ' : ' AND ') . " e.idEmpresa = $idEmpresa AND gp.idGrupo IN(".(is_array($grupos) ? implode(',', $grupos) : $grupos).") AND (if(zslc.`sensitive`=0,'Sim','Não')='Sim')";                        
       
       $sql = $this->db->query($sql);

       if($sql->rowCount() > 0):
           $dados = $sql->fetchAll();
       endif;

       return $dados[0]['total'];
   }

   public function carregaAbaSensiveisFluxoByFuncao($search, $orderColumn, $orderDir, $offset, $limit, $grupos, $idEmpresa)
    {
        
        if(empty($grupos)):
            return array();
        endif;
        
        $sql = " 
        SELECT DISTINCT
            GROUP_CONCAT(DISTINCT concat_ws(' - ', g.idLegGrupo) ORDER BY g.idLegGrupo SEPARATOR ' | ') AS grupo,
            p.cod_programa as 'cod_programa',
            p.descricao_programa as 'descricao_programa',
            zslc.`name` as 'Nome',
            if(zslc.`anonymize`=1,'Sim','Não') as 'Anonizado'
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
        INNER JOIN 
            z_sga_lgpd_campos_programas zslcp
            ON zslcp.idPrograma=p.z_sga_programas_id
        INNER join 
            z_sga_lgpd_campos zslc 
            ON zslc.id=zslcp.id_campo
        WHERE
            pe.idEmpresa = $idEmpresa
            AND gp.idGrupo IN(".(is_array($grupos) ? implode(',', $grupos) : $grupos).")
            AND (if(zslc.`sensitive`=1,'Sim','Não')='Sim')
        GROUP BY
            zslc.name";
               
        
        // Se a variavel $search não estiver vazia o where no select
        if($search != ''):            
            $sql .= " AND g.idLegGrupo LIKE '%$search%'";
            $sql .= " OR g.descAbrev LIKE '%$search%'";
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

    public function getCountTableAbaSensiveis($table, $search, $fields, $join, $idEmpresa = '', $grupos){
        
        if(empty($grupos)):
           return 0;
       endif;
       
       $dados = array();
       $where = '';
       $sql = "SELECT count($fields[0]) AS total FROM $table ";

       $sql .= "
           LEFT JOIN
               z_sga_grupo_programa gp
               ON gp.idPrograma = p.z_sga_programas_id
           LEFT JOIN
               z_sga_grupo g
               ON gp.idGrupo = g.idGrupo
           LEFT JOIN
               z_sga_programa_empresa e
               ON e.idPrograma = p.z_sga_programas_id
           INNER JOIN 
               z_sga_lgpd_campos_programas zslcp
               ON zslcp.idPrograma=p.z_sga_programas_id
           INNER join 
               z_sga_lgpd_campos zslc 
               ON zslc.id=zslcp.id_campo";

       // Se a variavel $search não estiver vazia limita cria o where no select
       if($search != '' && count($fields) > 0):
           $i = 0;
           foreach($fields as $val):
               $where .= (($i == 0) ? ' WHERE ' : ' OR ') . "$val LIKE '%$search%'";
               $i++;
           endforeach;
       endif;

       $sql .= $where;
     
       $sql .= (($where == '') ? ' WHERE ' : ' AND ') . " e.idEmpresa = $idEmpresa AND gp.idGrupo IN(".(is_array($grupos) ? implode(',', $grupos) : $grupos).") AND (if(zslc.`sensitive`=1,'Sim','Não')='Sim')";                        
       
       $sql = $this->db->query($sql);

       if($sql->rowCount() > 0):
           $dados = $sql->fetchAll();
       endif;

       return $dados[0]['total'];
   }

   public function carregaAbaAnonizadosFluxoByFuncao($search, $orderColumn, $orderDir, $offset, $limit, $grupos, $idEmpresa)
    {
        
        if(empty($grupos)):
            return array();
        endif;
        
        $sql = " 
        SELECT DISTINCT
            GROUP_CONCAT(DISTINCT concat_ws(' - ', g.idLegGrupo) ORDER BY g.idLegGrupo SEPARATOR ' | ') AS grupo,
            p.cod_programa as 'cod_programa',
            p.descricao_programa as 'descricao_programa',
            zslc.`name` as 'Nome',
            if(zslc.`sensitive`=0,'Sim','Não') as 'Pessoal',
            if(zslc.`sensitive`=1,'Sim','Não') as 'Sensivel'
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
        INNER JOIN 
            z_sga_lgpd_campos_programas zslcp
            ON zslcp.idPrograma=p.z_sga_programas_id
        INNER join 
            z_sga_lgpd_campos zslc 
            ON zslc.id=zslcp.id_campo
        WHERE
            pe.idEmpresa = $idEmpresa
            AND gp.idGrupo IN(".(is_array($grupos) ? implode(',', $grupos) : $grupos).")
            AND (if(zslc.`anonymize`=1,'Sim','Não')='Sim')
        GROUP BY
            zslc.name";
               
        
        // Se a variavel $search não estiver vazia o where no select
        if($search != ''):            
            $sql .= " AND g.idLegGrupo LIKE '%$search%'";
            $sql .= " OR g.descAbrev LIKE '%$search%'";
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

    public function getCountTableAbaAnonizados($table, $search, $fields, $join, $idEmpresa = '', $grupos){
        
        if(empty($grupos)):
           return 0;
       endif;
       
       $dados = array();
       $where = '';
       $sql = "SELECT count($fields[0]) AS total FROM $table ";

       $sql .= "
           LEFT JOIN
               z_sga_grupo_programa gp
               ON gp.idPrograma = p.z_sga_programas_id
           LEFT JOIN
               z_sga_grupo g
               ON gp.idGrupo = g.idGrupo
           LEFT JOIN
               z_sga_programa_empresa e
               ON e.idPrograma = p.z_sga_programas_id
           INNER JOIN 
               z_sga_lgpd_campos_programas zslcp
               ON zslcp.idPrograma=p.z_sga_programas_id
           INNER join 
               z_sga_lgpd_campos zslc 
               ON zslc.id=zslcp.id_campo";

       // Se a variavel $search não estiver vazia limita cria o where no select
       if($search != '' && count($fields) > 0):
           $i = 0;
           foreach($fields as $val):
               $where .= (($i == 0) ? ' WHERE ' : ' OR ') . "$val LIKE '%$search%'";
               $i++;
           endforeach;
       endif;

       $sql .= $where;
     
       $sql .= (($where == '') ? ' WHERE ' : ' AND ') . " e.idEmpresa = $idEmpresa AND gp.idGrupo IN(".(is_array($grupos) ? implode(',', $grupos) : $grupos).") AND (if(zslc.`anonymize`=1,'Sim','Não')='Sim')";                        
       
       $sql = $this->db->query($sql);

       if($sql->rowCount() > 0):
           $dados = $sql->fetchAll();
       endif;

       return $dados[0]['total'];
   }
    
    /**
     * Carrega matriz de risco
     * @param $grupos
     * @return type
     */
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
                   group by mtzr.idArea, bmgrp.idGrupo, cp.idProcessoPrim, cp.idProcessoSec
                    ORDER BY area.descricao";

        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0):
            return $sql->fetchAll(PDO::FETCH_ASSOC);
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
    
    /**
     * Recupera os dados do(s) usuário(s) a criar a solicitação
     * @param type $idUsuarios ids dos usuários a abrir a solicitação
     * @param type $idEmpresa id da instância dos usuários
     * @return type Array com os dados dos usuários
     */
    public function dadosUsuariosSolicitacao($idUsuarios, $idEmpresa)
    {
        try{
            // Recupera os dados do usuário
            $sql = "
                SELECT 
                    e.idEmpresa,
                    e.idUsuario,
                    u.z_sga_usuarios_id,
                    u.cod_gestor,
                    u.nome_usuario,
                    u.cod_usuario,
                    u.email,
                    u.funcao
                FROM
                    z_sga_usuario_empresa AS e
                LEFT JOIN
                    z_sga_usuarios AS u 
                    ON e.idUsuario = u.z_sga_usuarios_id
                WHERE
                    e.idEmpresa = $idEmpresa
                    AND e.idUsuario IN(" . ((is_array($idUsuarios)) ? implode(',', $idUsuarios) : $idUsuarios) . ")";

            $sql = $this->db->query($sql);
        

            if($sql->rowCount() > 0):
                return array(
                    'return' => true,
                    'dados'  => $sql->fetchAll(PDO::FETCH_ASSOC)
                ); 
            else:
                return array(
                    'return' => false,
                    'error' => 'Nenhum usuário encontrado!',
                );        
        endif;
        } catch (EXCEPTION $e){
            return array(
                'return' => false,
                'error' => $e->getMessage()
            );
        }
    }
    
    /**
     * Recupera os dados do gestor, filtrando pelo código do mesmo
     * @param type $codGestor
     * @return type
     */
    public function dadosGestorUsuario($codGestor)
    {
        try{
            $sql = "
                SELECT 
                    z_sga_usuarios_id AS id
                FROM 
                    z_sga_usuarios 
                WHERE 
                    cod_usuario = '$codGestor'";
            
            $sql = $this->db->query($sql);
            
            if($sql->rowCount() > 0):
                $sql = $sql->fetch(PDO::FETCH_ASSOC);
                return array(
                    'return'    => true,
                    'idGestor'  => $sql['id']
                );
            else:
                return array(
                    'return'    => false,
                    'error'     => 'Gestor não encontrado'
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
     * Recupera os dados dos grupos, filtrando pelos ids
     * @param type $idFuncao      
     * @param type $idEmpresa
     * @return type
     */
    public function dadosGestorGrupos($idFuncao, $idEmpresa)
    {
        try{
            $sql = "
                SELECT 
                    g.idGrupo AS z_sga_grupos_id,
                    #gp.idPrograma,
                    g.idGrupo,
                    g.idLegGrupo,
                    g.descAbrev,
                    (select ui.nome_usuario from z_sga_usuarios as ui  where ui.z_sga_usuarios_id = gg.idGestor) as nomeGestor, 
                    (select ui.cod_usuario from z_sga_usuarios as ui  where ui.z_sga_usuarios_id = gg.idGestor) as codGest,
                    (select ui.z_sga_usuarios_id from z_sga_usuarios as ui  where ui.z_sga_usuarios_id = gg.idGestor) as idCodGest,
                    (SELECT COUNT(*) FROM z_sga_grupo_programa grp WHERE grp.idGrupo = g.idGrupo) AS nrProgramas,
                    (SELECT COUNT(*) FROM z_sga_grupos grp WHERE grp.idGrupo = g.idGrupo) AS nrUsuarios,    
                    IF((SELECT idGrupo FROM z_sga_grupos gs WHERE gs.idUsuario = 6 AND gs.idGrupo = g.idGrupo), 'sim', 'não') AS possui
                FROM
                    z_sga_grupo g
                LEFT JOIN
                    z_sga_prov_funcao_grupo pfg
                    ON pfg.idGrupo = g.idGrupo
                LEFT JOIN
                    z_sga_gestor_grupo gg
                    ON gg.idGrupo = g.idLegGrupo
                WHERE
                    pfg.idFuncao = $idFuncao
                    AND g.idEmpresa = $idEmpresa
                GROUP BY
                    g.idGrupo";
            
            $sql = $this->db->query($sql);
            
            if($sql->rowCount() > 0):                
                return array(
                    'return' => true,
                    'dados'  => $sql->fetchAll(PDO::FETCH_ASSOC)
                );
            else:
                return array(
                    'return'    => false,
                    'error'     => 'Nenhum gestor encontrado para o(s) grupo(s) informado'
                );
            endif;
        } catch (Exception $e) {
            return array(
                'return'    => false,
                'error'     => $e->getMessage() . "<br>FILE: " . $e->getFile() . "<br>LINE: " . $e->getLine()
            );
        }
        
    }
    
    /**
     * Recupera dados de gestores de módulos, rotinas e programas
     * @param type $idGrupo
     * @param type $idEmpresa
     * @return type
     */
    public function dadosGestorMRP($idGrupo, $idEmpresa)
    {
        try{
            $sql = "
                Select 
                    usr.z_sga_usuarios_id AS idGestor,
                    usr.nome_usuario AS nomeGestor,
                    #usr.codGestor,
                    mpr.codMdlDtsul 	AS codModul,
                    (SELECT descricao_modulo FROM z_sga_programas p WHERE p.cod_modulo = mpr.codMdlDtsul limit 1) as modulo,
                    mpr.codRotinaDtsul 	AS codRotina,
                    (SELECT descricao_rotina FROM z_sga_programas p WHERE p.codigo_rotina = mpr.codRotinaDtsul limit 1) as rotina,
                    mpr.codProgDtsul 	AS codProg,
                    (SELECT descricao_programa FROM z_sga_programas p WHERE p.cod_programa = mpr.codProgDtsul limit 1) as programa
                from  z_sga_gest_mpr_dtsul mpr,
                       z_sga_usuarios usr
                where 
                    mpr.idUsuario = usr.z_sga_usuarios_id
                    and if (mpr.codMdlDtsul <> '*' and 
                             mpr.codRotinaDtsul = '*' and 
                             mpr.codProgDtsul = '*',
                     mpr.codMdlDtsul in (select distinct prg.cod_modulo from z_sga_grupo_programa grp, z_sga_programas prg  where grp.idGrupo in ($idGrupo) and  prg.z_sga_programas_id = grp.idPrograma),
                     if(mpr.codMdlDtsul <> '' and mpr.codRotinaDtsul <> '' and mpr.codProgDtsul = '*',
                                              mpr.codMdlDtsul 	in (select distinct prg.cod_modulo from z_sga_grupo_programa grp, z_sga_programas prg     where grp.idGrupo in ($idGrupo) and  prg.z_sga_programas_id = grp.idPrograma) and
                                              mpr.codRotinaDtsul in (select distinct prg.codigo_rotina from z_sga_grupo_programa grp, z_sga_programas prg  where grp.idGrupo in ($idGrupo) and  prg.z_sga_programas_id = grp.idPrograma)
                                     ,
                                             mpr.codProgDtsul in (select distinct prg.cod_programa from z_sga_grupo_programa grp, z_sga_programas prg     where grp.idGrupo in ($idGrupo) and  prg.z_sga_programas_id = grp.idPrograma)
                                      )
                             )
               ";
            
            $sql = $this->db->query($sql);
            
            if($sql->rowCount() > 0):                
                return array(
                    'return' => true,
                    'dados'  => $sql->fetchAll(PDO::FETCH_ASSOC)
                );
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
     * @param type $Fluxo
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
     * Retorna o método as ser executado, filtrando pelo idAtividade 
     * @param type $idProxAtividade
     * @return type
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
    
    /**
     * Grava o usuario acompanhante para solicitação de acesso
     * @param type $idUsuario
     * @param type $idUsuarioAcompanhante
     * @param type $idSolicitacao
     * @return type
     */
    public function guardaUsuarioAcompanhante($idUsuario, $idUsuarioAcompanhante, $idSolicitacao)
    {
        $sql = "
            INSERT INTO
                z_sga_fluxo_agendamento_acesso_acompanhante(idUsuario, idAcompanhante, idSolicitacao) 
            VALUES ($idUsuario, $idUsuarioAcompanhante, $idSolicitacao)";
        
        try{
            $this->db->query($sql);
            
            return [
                'return' => true
            ];
        } catch (Exception $e) {
            return [
                'return' => false,
                'error'  => $e->getMessage()
            ];
        }
    }
    
    /**
     * Retorna os processos de cada grupo da função
     * @param type $idFuncao
     * @return type
     */
    public function carregaProcessos($idFuncao)
    {
        try{
            $sql = "
                SELECT   
                    `proc`.`descProcesso` AS `descProcesso`,
                    `gpross`.`descricao` AS `grupoProcesso`,
                    GROUP_CONCAT(DISTINCT `base`.`cod_programa` SEPARATOR ' | ') AS `cod_programa`,   
                    GROUP_CONCAT(DISTINCT `grp`.`idLegGrupo` SEPARATOR ' | ') AS `grupos`
                FROM
                    ((((`z_sga_mtz_processo` `proc` JOIN `v_sga_mtz_base_matriz_usuario` `base`) 			
                    JOIN `z_sga_mtz_grupo_de_processo` `gpross`)
                    JOIN `z_sga_programas` `prog`)
                    JOIN `z_sga_grupo` `grp`)
                WHERE
                          ((`proc`.`idProcesso` = `base`.`idProcesso`)
                            AND (`gpross`.`idGrpProcesso` = `proc`.`idGrpProcesso`)
                            AND (`prog`.`z_sga_programas_id` = `base`.`idPrograma`)
                            AND (`grp`.`idGrupo` = `base`.`idGrupo`)	  
                        AND grp.idGrupo IN(SELECT pfg.idGrupo FROM z_sga_prov_funcao_grupo pfg WHERE pfg.idFuncao = $idFuncao)
                            AND (`base`.`empGrupo` = ".$_SESSION['empresaid'].")
                          )
                   GROUP BY `gpross`.`idGrpProcesso`";                   
            
            $sql = $this->db->query($sql);
            
            if($sql->rowCount() > 0):                
                return array(
                    'return' => true,
                    'dados'  => $sql->fetchAll(PDO::FETCH_ASSOC)
                );
            else:
                return array(
                    'return'    => false,
                    'error'     => 'Nenhum processo encontrado'
                );
            endif;
        } catch (Exception $e) {
            return array(
                'return'    => false,
                'error'     => $e->getMessage()
            );
        }        
    }
    
}