<?php

class SolicitacaoAcessoRevogacao extends Model
{
    
    
    public function __construct() {
        parent::__construct();                        
    }    
    
    /**
     * Carrega os grupos e programas a adicionar
     * @param type $idUsuario
     * @return type
     */
    public function carregaGrupoProgramas($idUsuario)
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
                    
                    gprog.idGrupo = g.idGrupo) AS programas,
                g.idGrupo,
                g.idLegGrupo,
                g.descAbrev,
                (SELECT nome_usuario FROM z_sga_usuarios u LEFT JOIN z_sga_gestor_grupo gg ON gg.idGestor = u.z_sga_usuarios_id WHERE gg.idGrupo = g.idLegGrupo) AS gestorGrupo,
                (SELECT COUNT(*) FROM z_sga_grupo_programa grp WHERE grp.idGrupo = g.idGrupo) AS nrProgramas,
                (SELECT COUNT(*) FROM z_sga_grupos grp WHERE grp.idGrupo = g.idGrupo) AS nrUsuarios
            FROM
                z_sga_grupo g            
            WHERE                
                g.idGrupo NOT IN (SELECT idGrupo FROM z_sga_grupos WHERE idUsuario = ".$idUsuario.")    
                AND g.idEmpresa = " . $_SESSION['empresaid'] . "
                /*AND g.idGrupo NOT IN(
                    SELECT 
                        REPLACE(GROUP_CONCAT(JSON_EXTRACT(d.documento, \"$.idGrupo\") separator ', '), '\"', '')
                    FROM 
                        z_sga_fluxo_documento d
                    LEFT JOIN
                        z_sga_fluxo_solicitacao s
                        ON s.idSolicitacao = d.idSolicitacao
                    WHERE 
                        s.status = 1
                        AND d.idFluxo = 7
                        AND JSON_EXTRACT(d.documento, \"$.idGrupo\")
                )*/
            GROUP BY
                g.idGrupo
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
     * Carrega os grupos e programas a adicionar
     * @param type $idProgramas
     * @param type $idUsuario
     * @return type
     */
    public function carregaGrupoProgramasJaAdicionados(/*$idProgramas, */$idUsuario)
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
                (SELECT nome_usuario FROM z_sga_usuarios u LEFT JOIN z_sga_gestor_grupo gg ON gg.idGestor = u.z_sga_usuarios_id WHERE gg.idGrupo = g.idLegGrupo) AS gestorGrupo,
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
     * Busca se usuário já possui uma solicitação por determinado grupo
     * @param type $post
     * @return type Array
     */
    public function validaSolicitacaoAberta($post)
    {
        $sql = '            
            SELECT 
                d.idSolicitacao
            FROM 
                z_sga_fluxo_documento d
            LEFT JOIN
                z_sga_fluxo_solicitacao s
                ON s.idSolicitacao = d.idSolicitacao
            WHERE 
                #d.idSolicitacao = 1
                s.status = 1
                AND JSON_EXTRACT(d.documento, "$.idusuario") = \''.$post['idUsuario'].'\'';
       
        $sql = $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
        
        if(isset($sql['idSolicitacao']) && count($sql) > 0):                                           
            if($sql['idSolicitacao'] !== ''):
                //echo 'idSolicitacao: ' . $sql['idSolicitacao'];
                return array(
                    'return'      => false,
                    'solicitacao' => $sql['idSolicitacao']
                );
            else:
                return true; 
            endif;
        else:
            return true; 
        endif;
    }
    
    /**
     * Retorna grupos pertencentes a usuário conforme id do usuário
     * @param type $post
     * @return type
     */
    public function validaGrupo($post)
    {
        $sql = "
            SELECT                 
                DISTINCT gs.cod_grupo
            FROM 
                z_sga_grupos gs
            LEFT JOIN
                z_sga_grupo g
                ON gs.idGrupo = g.idGrupo
            LEFT JOIN
                z_sga_usuarios u
                ON gs.idUsuario = u.z_sga_usuarios_id
            WHERE	
                z_sga_usuarios_id in(" . $post['idUsuario'] . ")
                AND g.idEmpresa = " . $_SESSION['empresaid']."
                AND gs.idGrupo = " . $post['idGrupo'];
        
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
     * Retorna os grupos pela string digitada pelo usuário
     * @param type $search
     * @return type
     */
    public function buscaGrupos($search, $idUsuario, $gruposAdicionados)
    {
        if($idUsuario == ''){
            return [];
        }
        $sqlNotInGrupos = (count($gruposAdicionados) > 0 ? " AND g.idGrupo NOT IN(".implode(',', $gruposAdicionados).") " : '');
        $sql = "        
            SELECT
                g.idGrupo,
                g.idLegGrupo,
                g.descAbrev
            FROM
                z_sga_grupo g
            WHERE
                idEmpresa = ".$_SESSION['empresaid']."
                AND (g.idLegGrupo LIKE '$search%' OR g.descAbrev LIKE '$search%')
                AND g.idGrupo NOT IN(SELECT idGrupo FROM z_sga_grupos WHERE idUsuario = $idUsuario)
                $sqlNotInGrupos";

        $sql = $this->db->query($sql);

        $array = array();
        if($sql->rowCount() > 0){
            $array = $sql->fetchAll(PDO::FETCH_ASSOC);
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
        //echo "<pre>";
        //print_r($_SESSION);
        // Valida se usuário logado é gestor
        $sql = "
            SELECT
                z_sga_usuarios_id AS idUsuario,
                nome_usuario,
                cod_usuario,
                u.gestor_usuario,
                u.gestor_grupo,
                u.si
            FROM
                z_sga_usuarios u
            LEFT JOIN
                z_sga_usuario_empresa e
                ON e.idUsuario = u.z_sga_usuarios_id
            WHERE
                /*e.ativo = 1                
                AND */u.z_sga_usuarios_id = ".$idusuario."
                AND e.idEmpresa = " . $idEmpresa;        
        
        $sql = $this->db->query($sql);
        
        // Caso sim. Busca os usuários sob a responsabilidade do usuário logado incluindo ele mesmo.
        if($sql->rowCount() > 0):           
            $gestor = $sql->fetchAll(PDO::FETCH_ASSOC);
            if($gestor[0]['si'] == 'S'):
                $sqlUsers = "
                    SELECT
                        u.z_sga_usuarios_id AS idUsuario,
                        u.nome_usuario,
                        u.gestor_usuario
                    FROM
                        z_sga_usuarios u
                    LEFT JOIN
                        z_sga_usuario_empresa e
                        ON e.idUsuario = u.z_sga_usuarios_id
                    WHERE
                        e.ativo = 1
                        AND e.idEmpresa = " . $idEmpresa;
                        
                $sqlUsers = $this->db->query($sqlUsers);

                if($sqlUsers->rowCount() > 0):                   
                    return array(
                        'usuarios'    => $sqlUsers->fetchAll(PDO::FETCH_ASSOC),
                        'solicitante' => $gestor
                    );
                else:
                    return array(
                        'usuarios'    => [],
                        'solicitante' => $gestor
                    );
                endif;
            elseif($gestor[0]['gestor_usuario'] == 'S' || $gestor[0]['gestor_grupo'] == 'S'):
                $sqlUsers = "
                    SELECT
                        u.z_sga_usuarios_id AS idUsuario,
                        u.nome_usuario,
                        u.gestor_usuario
                    FROM
                        z_sga_usuarios u
                    LEFT JOIN
                        z_sga_usuario_empresa e
                        ON e.idUsuario = u.z_sga_usuarios_id
                    WHERE
                        e.ativo = 1
                        AND (u.z_sga_usuarios_id = ".$idusuario."
                        OR u.cod_gestor = '".$gestor[0]['cod_usuario']."')
                        AND e.idEmpresa = " . $idEmpresa;
                        
                $sqlUsers = $this->db->query($sqlUsers);

                if($sqlUsers->rowCount() > 0):                   
                    return array(
                        'usuarios'    => $sqlUsers->fetchAll(PDO::FETCH_ASSOC),
                        'solicitante' => $gestor
                    );
                else:
                    return array(
                        'usuarios'    => [],
                        'solicitante' => $gestor
                    );
                endif;
            else:
                return array('solicitante' => $gestor);
            endif;        
        endif;
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
            ";
        
        // Se a variavel $search não estiver vazia o where no select
        if($search != ''):            
            $sql .= " AND (g.idLegGrupo LIKE '%$search%'";
            $sql .= " OR g.descAbrev LIKE '%$search%'";
            $sql .= " OR p.cod_programa LIKE '%$search%'";
            $sql .= " OR p.descricao_programa LIKE '%$search%'"; 
            $sql .= " OR p.cod_modulo LIKE '%$search%'";
            $sql .= " OR p.descricao_modulo LIKE '%$search%') ";
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

    public function carregaAbaPessoaisRevogacao($search, $orderColumn, $orderDir, $offset, $limit, $grupos, $idEmpresa)
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
       $this->gravar($sql);
       $sql = $this->db->query($sql);

       if($sql->rowCount() > 0):
           $dados = $sql->fetchAll();
       endif;

       return $dados[0]['total'];
   }

   public function carregaAbaSensiveisRevogacao($search, $orderColumn, $orderDir, $offset, $limit, $grupos, $idEmpresa)
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

   public function carregaAbaAnonizadosRevogacao($search, $orderColumn, $orderDir, $offset, $limit, $grupos, $idEmpresa)
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
     * @param $gruposRemove
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
                /*(select group_concat(distinct bmgrpi.cod_programa separator ' | ') 
                 from v_sga_mtz_base_matriz_usuario bmgrpi where bmgrpi.idProcesso = cp.idProcessoSec  
                                                             and bmgrpi.idGrupo = bmgrp.idGrupo
                                                             and bmgrpi.empGrupo  = bmgrp.empGrupo
                                                             group by bmgrpi.idProcesso) 
                        as progspSec,*/
                (select group_concat(distinct bmgrpi.cod_programa separator ' | ') 
                from v_sga_mtz_base_matriz_por_grupo bmgrpi where bmgrpi.idProcesso = cp.idProcessoSec  
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
                  where /*(cp.idProcessoPrim = bmgrp.idProcesso
                          and cp.idProcessoSec in (
                                select 
                                    bmgrpi.idProcesso 
                                    from v_sga_mtz_base_matriz_usuario bmgrpi where bmgrpi.idProcesso = cp.idProcessoSec and bmgrpi.idGrupo = bmgrp.idGrupo 
                                )
                         )*/
                         # Compara os processos secundarios
                        (cp.idProcessoPrim = bmgrp.idProcesso)
                        and cp.idProcessoSec in (
                            select 
                                bmgrpi.idProcesso 
                            from 
                                v_sga_mtz_base_matriz_por_grupo bmgrpi 
                            where 
                                bmgrpi.idProcesso = cp.idProcessoSec 
                                and bmgrpi.idGrupo IN(".implode(',', $grupos).")
                        )
                    and gr.idGrauRisco = cp.idGrauRisco
                    and mtzp.idProcesso = cp.idProcessoPrim
                    #and (mtzp.idProcesso = cp.idProcessoPrim OR mtzp.idProcesso = cp.idProcessoSec)
                    and mtzr.idMtzRisco = mtzp.idMtzRisco
                    and area.idArea = mtzr.idArea
                    -- filtros aqui
                     and bmgrp.idGrupo in (".implode(',', $grupos).") -- Filtro de grupos
                    --
                   #group by bmgrp.idGrupo, cp.idProcessoPrim, cp.idProcessoSec
                    #ORDER BY area.descricao
                    #group by mtzr.codRisco, cp.idProcessoPrim/*, bmgrp.idGrupo, cp.idProcessoPrim, cp.idProcessoSec*/
                    #ORDER BY area.descricao
                    #group by mtzr.codRisco,cp.idProcessoPrim,progspSec/*, bmgrp.idGrupo, cp.idProcessoPrim, cp.idProcessoSec*/
                    group by mtzr.codRisco, bmgrp.cod_programa, bmgrp.idProcesso,cp.idProcessoPrim
                    #having progspSec is not null
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
            where /*(cp.idProcessoPrim = bmgrp.idProcesso
                      and cp.idProcessoSec in (select bmgrpi.idProcesso from v_sga_mtz_base_matriz_usuario bmgrpi where bmgrpi.idProcesso = cp.idProcessoSec and bmgrpi.idGrupo = bmgrp.idGrupo )
                     )*/
                    (cp.idProcessoPrim = bmgrp.idProcesso)
                    and cp.idProcessoSec in (
                        select 
                            bmgrpi.idProcesso 
                        from 
                            v_sga_mtz_base_matriz_por_grupo bmgrpi 
                        where 
                            bmgrpi.idProcesso = cp.idProcessoSec 
                            and bmgrpi.idGrupo IN(".implode(',', $grupos).")
                    )
            and gr.idGrauRisco = cp.idGrauRisco
            and mtzp.idProcesso = cp.idProcessoPrim
            and mtzr.idMtzRisco = mtzp.idMtzRisco
            and area.idArea = mtzr.idArea            
            -- filtros aqui
             and bmgrp.idGrupo in (".implode(',', $grupos).") -- Filtro de grupos
             /*AND (select group_concat(distinct bmgrpi.cod_programa separator ' | ') 
	            from v_sga_mtz_base_matriz_por_grupo bmgrpi where bmgrpi.idProcesso = cp.idProcessoSec  
												 and bmgrpi.idGrupo = bmgrp.idGrupo
												 and bmgrpi.empGrupo  = bmgrp.empGrupo
												 group by bmgrpi.idProcesso) is not null*/
             
            ";
        //echo "<pre>";
        //die($sql);
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
     * @param type $idGrupos
     * @param type $idUsuario
     * @param type $idEmpresa
     * @return type
     */
    public function dadosGestorGrupos($idGrupos, $idUsuario, $idEmpresa)
    {
        try{
            $sql = "
                SELECT
                    g.idGrupo AS z_sga_grupos_id,
                    #gp.idPrograma,                
                    (SELECT 
                        GROUP_CONCAT(DISTINCT concat_ws(' - ', pg.cod_programa) ORDER BY pg.cod_programa SEPARATOR ' | ')
                    FROM
                        z_sga_programas pg
                    LEFT JOIN
                        z_sga_grupo_programa gprog
                        ON pg.z_sga_programas_id = gprog.idPrograma
                    WHERE
                        gprog.idGrupo = g.idGrupo) AS programas,
                    g.idGrupo,
                    g.idLegGrupo,
                    g.descAbrev,
                    (select ui.nome_usuario from z_sga_usuarios as ui  where ui.z_sga_usuarios_id = gg.idGestor) as nomeGestor, 
                    (select ui.cod_usuario from z_sga_usuarios as ui  where ui.z_sga_usuarios_id = gg.idGestor) as codGest,
                    (select ui.z_sga_usuarios_id from z_sga_usuarios as ui  where ui.z_sga_usuarios_id = gg.idGestor) as idCodGest,
                    (SELECT COUNT(*) FROM z_sga_grupo_programa grp WHERE grp.idGrupo = g.idGrupo) AS nrProgramas,
                    (SELECT COUNT(*) FROM z_sga_grupos grp WHERE grp.idGrupo = g.idGrupo) AS nrUsuarios
                FROM
                    z_sga_grupo g
                RIGHT JOIN
                    z_sga_gestor_grupo gg
                    ON g.idLegGrupo = gg.idGrupo
                /*LEFT JOIN
                    z_sga_grupo_programa gp
                    ON gp.idGrupo = g.idGrupo            
                LEFT JOIN
                    z_sga_programa_empresa pe
                    ON pe.idPrograma = gp.idPrograma*/
                WHERE
                    g.idGrupo IN(".(is_array($idGrupos) ? implode(', ', $idGrupos) : $idGrupos).")                
                    AND g.idGrupo NOT IN(SELECT idGrupo FROM z_sga_grupos gs WHERE idUsuario = $idUsuario AND gs.idGrupo = g.idGrupo)
                    AND g.idEmpresa = $idEmpresa
                GROUP BY
                    g.idGrupo
                ORDER BY
                    nrProgramas ASC";
            
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
}