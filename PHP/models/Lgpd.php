
<?php
class Lgpd extends Model {
      public function __construct(){
        parent::__construct();
      }

    /**
     * Retorna os gestores de grupos e caso seja informado uma string. Busca pela mesma
     * @param $post 
     */
    public function carregaGestorGrupo($post)
    {
        $where = (isset($post['string']) && !empty($post['string']) ? " 
            AND (u.nome_usuario LIKE '{$post['string']}%'
            OR u.cod_usuario LIKE '{$post['string']}%') " : '');

        $where .= (isset($post['eliminar']) && count($post['eliminar']) > 0 ? " AND gg.idGestor NOT IN('".implode("', '", $post['eliminar'])."') ": '');

        $sql = "
            SELECT
                gg.idGestor     AS id,
                u.cod_usuario   AS codigo,
                u.nome_usuario  AS descricao,
                gg.idGrupo
            FROM
                z_sga_usuarios u
            LEFT JOIN
                z_sga_gestor_grupo gg
                ON gg.idGestor = u.z_sga_usuarios_id  
            LEFT JOIN
                z_sga_usuario_empresa e
                ON e.idUsuario = gg.idGestor
            WHERE
                e.ativo = 1
                AND e.idEmpresa = {$_SESSION['empresaid']}
                $where
            GROUP BY
                gg.idGestor
            ORDER By
                u.nome_usuario
        ";
        
        $rs = $this->db->query($sql);

        if($rs->rowCount() > 0):
            return $rs->fetchAll(PDO::FETCH_ASSOC);
        else:
            return [];
        endif;
    }

    /**
     * Retorna os gestores de usuários e caso seja informado uma string. Busca pela mesma
     * @param $post 
     */
    public function carregaGestorUsuario($post)
    {
        $where = (!empty($post['string']) ? " 
        AND (u.cod_usuario LIKE '{$post['string']}%'
            OR u.nome_usuario LIKE '{$post['string']}%') " : '');

        $where .= (isset($post['eliminar']) && count($post['eliminar']) > 0 ? " AND gu.idGestor NOT IN('".implode("', '", $post['eliminar'])."') ": '');

        $sql = "
            SELECT
                u.z_sga_usuarios_id AS id,
                u.cod_usuario       AS codigo,
                u.nome_usuario		AS descricao
            FROM
                z_sga_usuarios u
            LEFT JOIN
                z_sga_usuario_empresa e
                ON e.idUsuario = u.z_sga_usuarios_id
            LEFT JOIN
                z_sga_gestor_usuario gu
                ON gu.idGestor = e.idUsuario
            WHERE
                e.ativo = 1
                AND e.idEmpresa = {$_SESSION['empresaid']}
                #AND u.cod_gestor IS NOT NULL 
                #AND u.cod_gestor <> ''
                $where
            GROUP BY
                gu.idGestor
            ORDER BY
                u.nome_usuario
        ";

        $rs = $this->db->query($sql);

        if($rs->rowCount() > 0):
            return $rs->fetchAll(PDO::FETCH_ASSOC);
        else:
            return [];
        endif;
    }

    /**
     * Retorna os gestores de usuários e caso seja informado uma string. Busca pela mesma
     * @param $post 
     */
    public function carregaGestorMPR($post)
    {
        $where = (!empty($post['string']) ? " 
            AND (u.cod_usuario LIKE '{$post['string']}%'
            OR u.nome_usuario LIKE '{$post['string']}%') " : '');

        $where .= (isset($post['eliminar']) && count($post['eliminar']) > 0 ? " AND mpr.idUsuario NOT IN('".implode("', '", $post['eliminar'])."') ": '');

        $whereTipo = '';
        if(isset($post['tipo'])):
            switch($post['tipo']):
                case '4':
                    $whereTipo = " AND mpr.codMdlDtsul <> '*' AND mpr.codProgDtsul <> '*'  AND mpr.codRotinaDtsul <> '*' ";
                    break;
                case '5':
                    $whereTipo = " AND mpr.codMdlDtsul = '*' AND mpr.codProgDtsul = '*' AND mpr.codRotinaDtsul <> '*' ";
                    break;
                case '6':
                    $whereTipo = " AND codMdlDtsul <> '*' AND codProgDtsul = '*' AND codRotinaDtsul = '*' ";
                    break;
            endswitch;
        endif;

        $sql = "
            SELECT
                mpr.idUsuario   AS id,
                u.cod_usuario   AS codigo,
                u.nome_usuario  AS descricao
            FROM
                z_sga_usuarios u
            LEFT JOIN
                z_sga_gest_mpr_dtsul mpr
                ON mpr.idUsuario = u.z_sga_usuarios_id
            LEFT JOIN
                z_sga_usuario_empresa e
                ON e.idUsuario = mpr.idUsuario
            WHERE
                e.ativo = 1
                AND e.idEmpresa = {$_SESSION['empresaid']}
                $where
                $whereTipo
            GROUP BY
                mpr.idUsuario
            ORDER By
                u.nome_usuario";

        $rs = $this->db->query($sql);

        if($rs->rowCount() > 0):
            return $rs->fetchAll(PDO::FETCH_ASSOC);
        else:
            return [];
        endif;
    }

    /**
     * Carrega modulos
     * @param $post
     * @return array
     */
    public function carregaModulos($post)
    {
        $where = ((isset($post['string']) && !empty($post['string'])) ? " 
            AND (m.cod_modul_dtsul LIKE '{$post['string']}%' 
            OR m.des_mudul_dtsul LIKE '{$post['string']}%'
            OR m.cod_sist_dtsul LIKE '{$post['string']}%') " : '');
        
        $where .= (isset($post['eliminar']) && count($post['eliminar']) > 0 ? " AND m.cod_modul_dtsul NOT IN('".implode("', '", $post['eliminar'])."') ": '');

        // Usado quando filtrado por gestor de modulos
        $join = ((isset($post['idsGestores']) && count($post['idsGestores']) > 0) ? "
            LEFT JOIN
                z_sga_gest_mpr_dtsul mpr
                ON mpr.codMdlDtsul = p.cod_modulo
            " : '');

        $where .= ((isset($post['idsGestores']) && count($post['idsGestores']) > 0) ? " 
            AND mpr.idUsuario IN(".implode(",", $post['idsGestores']).") 
            AND (codMdlDtsul <> '*' AND codProgDtsul = '*' AND codRotinaDtsul = '*')" : '');

        $sql = "
            SELECT DISTINCT
                m.cod_modul_dtsul AS codigo,
                m.des_mudul_dtsul As descricao               
            FROM 
                z_sga_modul_dtsul m
            LEFT JOIN
                z_sga_programas p
                ON m.cod_modul_dtsul = p.cod_modulo
            RIGHT JOIN
                z_sga_programa_empresa pe
                ON p.z_sga_programas_id = pe.idPrograma
                $join
            WHERE
                pe.idEmpresa = {$_SESSION['empresaid']}
                AND m.cod_modul_dtsul IS NOT NULL 
                AND m.des_mudul_dtsul IS NOT NULL
                AND m.cod_sist_dtsul IS NOT NULL
                $where
            GROUP BY
                m.cod_modul_dtsul
            ORDER BY
                m.des_mudul_dtsul
            LIMIT 100";
        
        $sql = $this->db->query($sql);

        if($sql->rowCount()>0):
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        else:
            return [];
        endif;
    }

    /**
     * Carrega rotinas
     * @param $post
     * @return array
     */
    public function carregaRotinas($post)
    {
        $where = ((isset($post['string']) && !empty($post['string'])) ? " 
            AND (p.codigo_rotina LIKE '{$post['string']}%' 
            OR p.descricao_rotina LIKE '{$post['string']}%') " : '');
        
        $where .= (isset($post['eliminar']) && count($post['eliminar']) > 0 ? " AND p.codigo_rotina NOT IN(".implode(', ', $post['eliminar']).") ": '');

        // Usado quando filtrado por gestor de rotinas
        $join = ((isset($post['idsGestores']) && count($post['idsGestores']) > 0) ? "
            LEFT JOIN
                z_sga_gest_mpr_dtsul mpr
                ON mpr.codRotinaDtsul = p.codigo_rotina
            " : '');

        $where .= ((isset($post['idsGestores']) && count($post['idsGestores']) > 0) ? " 
            AND mpr.idUsuario IN(".implode(",", $post['idsGestores']).") 
            AND (mpr.codMdlDtsul = '*' AND mpr.codProgDtsul = '*' AND mpr.codRotinaDtsul <> '*')" : '');

        $sql = "
            SELECT DISTINCT
                p.codigo_rotina     AS codigo,
                p.descricao_rotina  As descricao               
            FROM 
                z_sga_programas p
            RIGHT JOIN
                z_sga_programa_empresa pe
                ON p.z_sga_programas_id = pe.idPrograma
                $join
            WHERE
                pe.idEmpresa = {$_SESSION['empresaid']}
                AND p.codigo_rotina IS NOT NULL 
                AND p.descricao_rotina IS NOT NULL
                $where
            GROUP BY
                p.codigo_rotina
            ORDER BY
                p.descricao_rotina
            LIMIT 100";

        $sql = $this->db->query($sql);

        if($sql->rowCount()>0):
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        else:
            return [];
        endif;
    }

    /**
     * Retorna os programas
     * @param type $post
     * @return type
     */
    public function carregaProgramas($post)
    {
        $where = ((isset($post['string']) && !empty($post['string'])) ? " AND (p.cod_programa LIKE '{$post['string']}%' OR p.descricao_programa LIKE '{$post['string']}%') " : '');
        $where .= (isset($post['eliminar']) && count($post['eliminar']) > 0 ? " AND p.z_sga_programas_id NOT IN(".implode(', ', $post['eliminar']).") ": '');

        // Usado quando filtrado por gestor de programas
        $join = ((isset($post['idsGestores']) && count($post['idsGestores']) > 0) ? "
            LEFT JOIN
                z_sga_gest_mpr_dtsul mpr
                ON mpr.codProgDtsul = p.cod_programa
            " : '');

        $where .= ((isset($post['idsGestores']) && count($post['idsGestores']) > 0) ? " 
            AND mpr.idUsuario IN(".implode(",", $post['idsGestores']).") 
            AND (codMdlDtsul <> '*' AND codProgDtsul <> '*' AND codRotinaDtsul <> '*')" : '');

        $sql = "
            SELECT 
                p.z_sga_programas_id    AS id,
                p.cod_programa          AS codigo,
                p.descricao_programa    AS descricao
            FROM 
                z_sga_programas p
            LEFT JOIN
                z_sga_programa_empresa e
                ON e.idPrograma = p.z_sga_programas_id
                $join
            WHERE
                e.idEmpresa = {$_SESSION['empresaid']}
                AND p.cod_programa IS NOT NULL 
                AND p.descricao_programa IS NOT NULL
                $where
            ORDER BY
                p.cod_programa
            LIMIT 100";
        
        $sql = $this->db->query($sql);
        $array = array();
        if($sql->rowCount()>0){
              $array = $sql->fetchAll();
        }
        return $array;
    }

    /**
     * Retorna os grupos
     * @param type $post
     * @return type
     */
    
     public function carregaGrupos($post)
    {
        $where = (isset($post['string']) && !empty($post['string']) ? " AND (g.idLegGrupo LIKE '%{$post['string']}%' OR g.descAbrev LIKE '%{$post['string']}%') " : '');
        $where .= (isset($post['eliminar']) && count($post['eliminar']) > 0 ? " AND g.idGrupo NOT IN(".implode(', ', $post['eliminar']).") ": '');

        // Usado quando filtrado por gestor de grupos
        $join = ((isset($post['idsGestores']) && count($post['idsGestores']) > 0) ? "
            LEFT JOIN
                z_sga_gestor_grupo gg
                ON gg.idGrupo = g.idLegGrupo
            " : '');

        $where .= ((isset($post['idsGestores']) && count($post['idsGestores']) > 0) ? " AND gg.idGestor IN(".implode(",", $post['idsGestores']).") " : '');

        $sql = "
            SELECT
                g.idGrupo     AS id,
                g.idLegGrupo  AS codigo,
                g.descAbrev   AS descricao
            FROM
                z_sga_grupo g
                $join
            WHERE
                g.idEmpresa = {$_SESSION['empresaid']}
                $where
            GROUP BY
                g.idGrupo
            ORDER BY
                g.idLegGrupo
            LIMIT 100";
        
        $rs = $this->db->query($sql);
        
        if($rs->rowCount() > 0):
              return $rs->fetchAll(PDO::FETCH_ASSOC);
        else:
            return [];
        endif;
        
    }

    /**
     * Retorna os usuarios
     * @param type $post
     * @return type
     */
    public function carregaUsuarios($post)
    {
        $where = (isset($post['string']) && !empty($post['string']) ? " AND (u.cod_usuario LIKE '{$post['string']}%' OR u.nome_usuario LIKE '{$post['string']}%') ": '');
        $where .= (isset($post['eliminar']) && count($post['eliminar']) > 0 ? " AND u.z_sga_usuarios_id NOT IN(".implode(', ', $post['eliminar']).") ": '');

        // Usado quando filtrado por gestor de usuarios
        $join = ((isset($post['idsGestores']) && count($post['idsGestores']) > 0) ? "
            LEFT JOIN
                z_sga_gestor_usuario gu
                ON gu.idUsuario = u.z_sga_usuarios_id
            " : '');

        $where .= ((isset($post['idsGestores']) && count($post['idsGestores']) > 0) ? " AND gu.idGestor IN(".implode(",", $post['idsGestores']).") " : '');

        $sql = "
            SELECT
                u.z_sga_usuarios_id AS id,
                u.cod_usuario       AS codigo,
                u.nome_usuario      AS nome
            FROM
                z_sga_usuarios u
            LEFT JOIN
                z_sga_usuario_empresa e
                ON e.idUsuario = u.z_sga_usuarios_id
                $join
            WHERE
                e.ativo = 1
                AND e.idEmpresa = {$_SESSION['empresaid']}
                $where
            GROUP BY
                u.z_sga_usuarios_id
            ORDER BY
                u.nome_usuario
            LIMIT 100";
        
        $sql = $this->db->query($sql);
        
        if($sql->rowCount()>0):
              return $sql->fetchAll(PDO::FETCH_ASSOC);
        else:
            return [];
        endif;
        
      }

      public function retornaDadosGestorGrupo($post){
        $where='';
        $where.=(isset($post['filtrarGestor'])) ? "AND z_sga_gestor_grupo.idGestor IN (".implode(',',$post['filtrarGestor']).")":'';
        $where.=(isset($post['filtrarGrupo'])) ? "AND z_sga_grupo.idGrupo IN (".implode(',',$post['filtrarGrupo']).")":'';
        $sql = "
            SELECT 
                concat(idLegGrupo,' - ',descAbrev) as 'Grupo',
                ifnull(z_sga_usuarios.nome_usuario,'Sem Gestor')as 'Gestor',
                count(distinct(z_sga_grupos.idUsuario)) as 'Usuarios',
                count(distinct(upper(z_sga_programas.cod_modulo))) as 'Modulos',
                count(distinct(z_sga_programas.codigo_rotina)) as 'Rotinas',
                count(distinct(z_sga_grupo_programa.idPrograma)) as 'Transações',
                count(if(z_sga_lgpd_campos.`sensitive`=0,1,null)) as 'Dados Pessoais',
                sum(ifnull(z_sga_lgpd_campos.`sensitive`,0)) as 'Dados Sensiveis',
                sum(ifnull(z_sga_lgpd_campos.anonymize,0)) as 'Dados Anonizados',
                z_sga_grupo.idGrupo as 'idGrupo'
            FROM
                z_sga_grupo
            INNER JOIN z_sga_gestor_grupo
                on z_sga_gestor_grupo.idGrupo=z_sga_grupo.idLegGrupo
            LEFT JOIN
                z_sga_usuarios on z_sga_usuarios.z_sga_usuarios_id=z_sga_gestor_grupo.idGestor
            LEFT JOIN
                z_sga_grupos on z_sga_grupos.cod_grupo=z_sga_grupo.idLegGrupo
            LEFT JOIN
                z_sga_grupo_programa on z_sga_grupo_programa.idGrupo=z_sga_grupo.idGrupo
            LEFT JOIN
                z_sga_programas on z_sga_programas.cod_programa=z_sga_grupo_programa.cod_programa
            LEFT JOIN
                z_sga_modul_dtsul on z_sga_modul_dtsul.cod_modul_dtsul=z_sga_programas.cod_modulo
            LEFT JOIN
                z_sga_lgpd_campos_programas on z_sga_lgpd_campos_programas.idPrograma=z_sga_programas.z_sga_programas_id
            LEFT JOIN
                z_sga_lgpd_campos on z_sga_lgpd_campos.id=z_sga_lgpd_campos_programas.id_campo
            LEFT JOIN
                z_sga_lgpd_document_types on z_sga_lgpd_document_types.id=z_sga_lgpd_campos.id_document_type
            WHERE 
                z_sga_grupo.idEmpresa={$_SESSION['empresaid']}
                $where
            GROUP BY
                z_sga_grupo.idGrupo";
            $sql=$this->db->query($sql);

            if($sql->rowCount()>0):
                return $sql->fetchAll(PDO::FETCH_ASSOC);
            else:
                return [];
            endif;
    }

    public function retornarDadosGestorUsuario($post){
        $where='';
        $where.=(isset($post['filtrarGestor'])) ? "AND zsgu.idGestor IN (".implode(',',$post['filtrarGestor']).")":'';
        $where.=(isset($post['filtrarUsuario'])) ? "AND zsu.z_sga_usuarios_id IN (".implode(',',$post['filtrarUsuario']).")":'';
        $sql="
        SELECT
            zsu.nome_usuario as 'Usuario',
            zsug.nome_usuario as 'Gestor',
            count(distinct(zsp.cod_modulo)) as 'Modulos',
            count(distinct(zsgp.idGrupo)) as 'Grupos',
            count(distinct(zsp.codigo_rotina)) as 'Rotinas',
            count(distinct(zsgp.idPrograma)) as 'Transações',
            count(if(zslc.`sensitive`=0,1,null)) as 'Dados Pessoais',
            sum(ifnull(zslc.`sensitive`,0)) as 'Dados Sensiveis',
            sum(ifnull(zslc.anonymize,0)) as 'Dados Anonizados',
            zsu.z_sga_usuarios_id as 'idUsuario'
        FROM
            z_sga_gestor_usuario zsgu
        INNER JOIN 
            z_sga_usuarios zsu on zsgu.idUsuario=zsu.z_sga_usuarios_id
        INNER JOIN 
            z_sga_usuarios zsug on zsgu.idGestor=zsug.z_sga_usuarios_id
        INNER JOIN
            z_sga_grupos zsgs on zsu.z_sga_usuarios_id=zsgs.idUsuario
        INNER JOIN
            z_sga_grupo zsg on zsg.idGrupo=zsgs.idGrupo
        LEFT JOIN
            z_sga_grupo_programa zsgp on zsgs.idGrupo=zsgp.idGrupo
        LEFT JOIN
            z_sga_programas zsp on zsgp.idPrograma=zsp.z_sga_programas_id
        LEFT JOIN
            z_sga_lgpd_campos_programas zslcp on zslcp.idPrograma=zsp.z_sga_programas_id
        LEFT JOIN
            z_sga_lgpd_campos zslc on zslc.id=zslcp.id_campo
        WHERE
            zsg.idEmpresa={$_SESSION['empresaid']}
            $where
        GROUP BY
            zsu.z_sga_usuarios_id";
            
        $sql=$this->db->query($sql);

        if($sql->rowCount()>0):
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        else:
            return [];
        endif;
    }
    
    public function retornarDadosGestorTransacao($post){
        $where='';
        $where.=(isset($post['filtrarGestor'])) ? " AND zsgp.idUsuario IN (".implode(',',$post['filtrarGestor']).")":'';
        $where.=(isset($post['filtrarTransacoes'])) ? " AND zsp.z_sga_programas_id IN (".implode(',',$post['filtrarTransacoes']).")":'';
        $sql="
        Select 
            concat(zsp.cod_programa,' - ',zsp.descricao_programa) as 'Programa',
            ifnull(zsu.cod_usuario, 'Sem Gestor de Programa') as 'Gestor',
            count(distinct(ifnull(zsgs.idUsuario,null))) as 'Usuarios',
            count(distinct(zspg.idGrupo)) as 'Grupos',
            count(distinct(zsp.cod_modulo)) as 'Modulos',
            count(distinct(zsp.codigo_rotina)) as 'Rotinas',
            count(if(zslc.`sensitive`=0,1,null)) as 'Dados Pessoais',
            sum(ifnull(zslc.`sensitive`,0)) as 'Dados Sensiveis',
            sum(ifnull(zslc.anonymize,0)) as 'Dados Anonizados',
            zsp.cod_programa as 'Codigo'
        From 
            z_sga_programas as zsp 
        left join
            (Select idUsuario,codProgDtsul from z_sga_gest_mpr_dtsul where codProgDtsul!='*') as zsgp on zsp.cod_programa=zsgp.codProgDtsul 
        left join
            z_sga_usuarios as zsu on zsu.z_sga_usuarios_id=zsgp.idUsuario 
        left join
            z_sga_grupo_programa zspg on zspg.idPrograma=z_sga_programas_id 
        left join
            z_sga_grupos zsgs on zsgs.idGrupo=zspg.idGrupo 
        left join
            z_sga_grupo zsg on zsg.idGrupo=zsgs.idGrupo 
        left join
            z_sga_lgpd_campos_programas zslcp on zslcp.idPrograma=zsp.z_sga_programas_id 
        left join
            z_sga_lgpd_campos zslc on zslc.id=zslcp.id_campo 
        left join
            z_sga_programa_empresa zspe on zspe.idPrograma=zspg.idPrograma
        where 
            zspg.cod_grupo!='*' and zspe.idEmpresa=1 
            $where
        group by
            zsp.cod_programa";
            
        $sql=$this->db->query($sql);

        if($sql->rowCount()>0):
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        else:
            return [];
        endif;
    }
    public function retornarDadosGestorRotina($post){
        $where='';
        $where.=(isset($post['filtrarGestor'])) ? "AND zsgp.idUsuario IN (".implode(',',$post['filtrarGestor']).")":'';
        $where.=(isset($post['filtrarRotinas'])) ? "AND zsp.codigo_rotina IN (".implode(',',$post['filtrarRotinas']).")":'';
        $sql="
        Select 
            concat(zsp.descricao_rotina) as 'Rotinas',
            ifnull(zsu.cod_usuario, 'Sem Gestor de Rotina') as 'Gestor',
            count(distinct(ifnull(zsgs.idUsuario,null))) as 'Usuarios',
            count(distinct(zspg.idGrupo)) as 'Grupos',
            count(distinct(zsp.z_sga_programas_id)) as 'Transacoes',
            count(distinct(zsp.cod_modulo)) as 'Modulos',
            count(if(zslc.`sensitive`=0,1,null)) as 'Dados Pessoais',
            sum(ifnull(zslc.`sensitive`,0)) as 'Dados Sensiveis',
            sum(ifnull(zslc.anonymize,0)) as 'Dados Anonizados',
            zsp.codigo_rotina as 'Codigo'
        From 
            z_sga_programas as zsp 
        left join 
            (Select idUsuario,codRotinaDtsul from z_sga_gest_mpr_dtsul where codProgDtsul='*' and codRotinaDtsul!='*') as zsgp on zsp.codigo_rotina=zsgp.codRotinaDtsul 
        left join 
            z_sga_usuarios as zsu on zsu.z_sga_usuarios_id=zsgp.idUsuario 
        left join 
            z_sga_grupo_programa zspg on zspg.idPrograma=z_sga_programas_id 
        left join 
            z_sga_grupos zsgs on zsgs.idGrupo=zspg.idGrupo 
        left join 
            z_sga_grupo zsg on zsg.idGrupo=zsgs.idGrupo 
        left join 
            z_sga_lgpd_campos_programas zslcp on zslcp.idPrograma=zsp.z_sga_programas_id 
        left join 
            z_sga_lgpd_campos zslc on zslc.id=zslcp.id_campo 
        where 
            nullif(zsp.codigo_rotina,'') is not null 
            $where
        group by
            zsp.codigo_rotina";
            
        $sql=$this->db->query($sql);

        if($sql->rowCount()>0):
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        else:
            return [];
        endif;
    }
    public function retornarDadosGestorModulo($post){
        $where='';
        $where.=(isset($post['filtrarGestor'])) ? "AND zsgp.idUsuario IN (".implode(',',$post['filtrarGestor']).")":'';
        $where.=(isset($post['filtrarModulos'])) ? "AND zsp.cod_modulo IN (".sprintf("'%s'", implode("', '", $post['filtrarModulos'])).")":'';
        $sql="
        Select 
            concat(zsp.cod_modulo,' - ',zsp.descricao_modulo) as 'Modulo',
            ifnull(zsu.cod_usuario, 'Sem Gestor de Modulo') as 'Gestor',
            count(distinct(ifnull(zsgs.idUsuario,null))) as 'Usuarios',
            count(distinct(zspg.idGrupo)) as 'Grupos',
            count(distinct(zsp.z_sga_programas_id)) as 'Transacoes',
            count(distinct(zsp.codigo_rotina)) as 'Rotinas',
            count(if(zslc.`sensitive`=0,1,null)) as 'Dados Pessoais',
            sum(ifnull(zslc.`sensitive`,0)) as 'Dados Sensiveis',
            sum(ifnull(zslc.anonymize,0)) as 'Dados Anonizados',
            zsp.cod_modulo as 'Codigo' 
        From 
            z_sga_programas as zsp 
        left join
            (Select idUsuario,codMdlDtsul from z_sga_gest_mpr_dtsul where codProgDtsul='*' and codRotinaDtsul='*') as zsgp on zsp.cod_modulo=zsgp.codMdlDtsul 
        left join 
            z_sga_usuarios as zsu on zsu.z_sga_usuarios_id=zsgp.idUsuario 
        left join 
            z_sga_grupo_programa zspg on zspg.idPrograma=z_sga_programas_id 
        left join 
            z_sga_grupos zsgs on zsgs.idGrupo=zspg.idGrupo 
        left join 
            z_sga_grupo zsg on zsg.idGrupo=zsgs.idGrupo 
        left join 
            z_sga_lgpd_campos_programas zslcp on zslcp.idPrograma=zsp.z_sga_programas_id 
        left join 
            z_sga_lgpd_campos zslc on zslc.id=zslcp.id_campo 
        where 
            nullif(zsp.cod_modulo,'')is not null 
            $where
        group by 
            zsp.cod_modulo";
            
        $sql=$this->db->query($sql);

        if($sql->rowCount()>0):
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        else:
            return [];
        endif;
    }
}