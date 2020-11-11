<?php

class Processo extends Model
{
    public function __construct(){
        parent::__construct();
    }

    /**
     * Carrega o resumo de varios processos ou de determinado processo
     * @param $idProcesso
     * @return array
     */
    public function carregaProcessos($idProcesso)
    {
        $idEmpresa = $_SESSION['empresaid'];
        $where = ($idProcesso != '') ? ' WHERE p.idProcesso = '. $idProcesso : '';

        $sql = "
            SELECT 
                p.idProcesso,
                mgp.descricao,
                p.descProcesso,
                (SELECT 
                    a.descricao 
                FROM 
                        z_sga_mtz_processo mtzp
                LEFT JOIN
                        z_sga_mtz_risco mtz
                        ON mtzp.idMtzRisco = mtz.idMtzRisco
                LEFT JOIN
                        z_sga_mtz_area a
                        ON mtz.idArea = a.idArea
                WHERE
                        mtzp.idProcesso = p.idProcesso) AS area,
                 (SELECT                
                        COUNT(DISTINCT(gs.idUsuario)) AS numUsuarios    
                    FROM 
                        z_sga_grupos gs
                    INNER JOIN
                        z_sga_grupo g on g.idGrupo = gs.idGrupo
                    LEFT JOIN
                        z_sga_grupo_programa gp
                        ON gs.idGrupo = gp.idGrupo
                    LEFT JOIN
                        z_sga_mtz_apps_processo apps
                        ON gp.idPrograma = apps.idPrograma
                    RIGHT JOIN
                        z_sga_mtz_processo proc
                        ON apps.idProcesso = proc.idProcesso
                    WHERE apps.idProcesso = p.idProcesso and g.idEmpresa = $idEmpresa
                    GROUP BY            
                        p.idProcesso) AS numUsuarios,
                (SELECT COUNT(DISTINCT(apps.idPrograma)) FROM z_sga_mtz_apps_processo apps WHERE apps.idProcesso = p.idProcesso) AS numProgramas,
                (SELECT COUNT(DISTINCT(gp.idGrupo)) FROM z_sga_grupo_programa gp RIGHT JOIN z_sga_mtz_apps_processo apps ON gp.idPrograma = apps.idPrograma
                    WHERE apps.idProcesso = p.idProcesso
                ) AS numGrupos,
                (SELECT COUNT(DISTINCT(pg.cod_modulo)) FROM z_sga_programas pg 
                    LEFT JOIN z_sga_mtz_apps_processo apps ON pg.z_sga_programas_id = apps.idPrograma
                    WHERE apps.idProcesso = p.idProcesso) AS numModulos
            FROM 
                z_sga_mtz_processo p
            LEFT JOIN
                z_sga_mtz_grupo_de_processo mgp
                ON p.idGrpProcesso = mgp.idGrpProcesso
            $where 
            GROUP BY
                p.idProcesso";

        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0):
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        else:
            return array();
        endif;
    }

    /**
     * Retorna os grupos de determinado processo
     * @param $idProcesso
     * @return array
     */
    public function carregaGruposProcesso($idProcesso)
    {
        $idEmpresa = $_SESSION['empresaid'];
         $sql = "
            SELECT DISTINCT
                g.idLegGrupo,
                g.descAbrev,
                u.nome_usuario AS nomeGestor,
                (SELECT COUNT(idUsuario) FROM z_sga_grupos gs WHERE gs.idGrupo = g.idGrupo) AS numUSuarios,
                (SELECT COUNT(idPrograma) FROM z_sga_grupo_programa gp WHERE gp.idGrupo = g.idGrupo) AS numProgramas
            FROM 
                z_sga_grupo_programa gp 
            INNER JOIN
                z_sga_grupo g
                on gp.idGrupo = g.idGrupo
            LEFT JOIN
                z_sga_gestor_grupo gs
                ON gp.idGrupo = gs.idGrupo
            LEFT JOIN
                z_sga_usuarios u
                ON gs.idGestor = u.z_sga_usuarios_id
            RIGHT JOIN 
                z_sga_mtz_apps_processo apps 
                ON gp.idPrograma = apps.idPrograma
            LEFT JOIN
                z_sga_mtz_processo p
                ON apps.idProcesso = p.idProcesso
            WHERE 
                apps.idProcesso =  $idProcesso
                 AND g.idEmpresa =  $idEmpresa
            GROUP BY
                gp.idGrupo";

        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0):
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        else:
            return array();
        endif;
    }

    /**
     * Retorna os programas de determinado processo
     * @param $idProcesso
     * @return array
     */
    public function carregaProgramasProcesso($idProcesso)
    {
        $sql = "
            SELECT 
                apps.idPrograma,
                pg.cod_programa,
                pg.descricao_programa,
                GROUP_CONCAT(DISTINCT concat_ws(';',g.idLegGrupo, g.descAbrev) SEPARATOR ' | ') AS grupos,
                pg.descricao_rotina
            FROM 
                z_sga_mtz_apps_processo apps    
            LEFT JOIN
                z_sga_mtz_processo p
                ON apps.idProcesso = p.idProcesso
            LEFT JOIN
                z_sga_programas pg
                ON apps.idPrograma = pg.z_sga_programas_id
            LEFT JOIN
                z_sga_grupo_programa gp
                ON pg.z_sga_programas_id = gp.idPrograma
            INNER JOIN
                z_sga_grupo g
                ON gp.idGrupo = g.idGrupo
            WHERE
                p.idProcesso = $idProcesso
            GROUP BY
                pg.cod_programa
            ORDER BY
                pg.cod_programa";

        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0):
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        else:
            return array();
        endif;
    }

    /**
     * Retorn os usuários de determinado processo
     * @param $idProcesso
     * @return array
     */
    public function carregaUsuariosProcesso($idProcesso)
    {
        $idEmpresa = $_SESSION['empresaid'];
        $sql = "
            SELECT              
                DISTINCT(gs.idUsuario) AS idUsuario,
                u.nome_usuario,    
                u.cod_usuario AS idTotvs,
                (SELECT us.nome_usuario FROM z_sga_usuarios us WHERE us.cod_usuario = u.cod_gestor) AS gestor,
                IF(e.ativo = 1, 'Ativo', 'Inativo') AS situacao
            FROM 
                z_sga_grupos gs
            INNER JOIN
                z_sga_grupo g on g.idGrupo = gs.idGrupo
            INNER JOIN
                z_sga_usuarios u
                ON gs.idUsuario = u.z_sga_usuarios_id
            INNER JOIN
                z_sga_usuario_empresa e
                ON gs.idUsuario = e.idUsuario
            LEFT JOIN
                z_sga_grupo_programa gp
                ON gs.idGrupo = gp.idGrupo
            LEFT JOIN
                z_sga_mtz_apps_processo apps
                ON gp.idPrograma = apps.idPrograma
            RIGHT JOIN
                z_sga_mtz_processo proc
                ON apps.idProcesso = proc.idProcesso
            LEFT JOIN
                z_sga_mtz_processo p
                ON apps.idProcesso = p.idProcesso
            WHERE 
                apps.idProcesso = $idProcesso AND
                g.idEmpresa = $idEmpresa";

        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0):
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        else:
            return array();
        endif;
    }

    /**
     * Retorna os módulos de determinado processo
     * @param $idProcesso
     * @return array
     */
    public function carregaModulosProcesso($idProcesso)
    {
        $sql = "
            SELECT    
                pg.cod_modulo,
                sis.des_sist_dtsul,
                pg.descricao_modulo,    
                pg.descricao_rotina,
                GROUP_CONCAT(DISTINCT concat_ws(';',pg.cod_programa,pg.descricao_programa) SEPARATOR ' | ') AS Programas,
                (SELECT COUNT(DISTINCT(apps.idPrograma)) FROM z_sga_programas prog WHERE prog.cod_modulo = pg.cod_modulo GROUP BY pg.cod_modulo) AS numProgramas,
                GROUP_CONCAT(DISTINCT g.idLegGrupo SEPARATOR ' | ') AS Grupos
            FROM
                z_sga_programas pg
            LEFT JOIN
                z_sga_mtz_apps_processo apps 
                ON pg.z_sga_programas_id = apps.idPrograma
            LEFT JOIN
                z_sga_grupo_programa gp
                ON apps.idPrograma = gp.idPrograma
            LEFT JOIN
                z_sga_grupo g
                ON gp.idGrupo = g.idGrupo
            LEFT JOIN
                z_sga_modul_dtsul mdl
                ON pg.cod_modulo = mdl.cod_modul_dtsul
            LEFT JOIN
                z_sga_sist_dtsul AS sis
                ON mdl.cod_sist_dtsul = sis.cod_sist_dtsul
            WHERE
                apps.idProcesso = $idProcesso
            GROUP BY
                pg.cod_modulo";

        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0):
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        else:
            return array();
        endif;
    }


    /**************************************************************************************
     *                              SNAPSHOT DOS PROCESSOS                                *
     *************************************************************************************/

    /**
     * Carrega o resumo de varios processos ou de determinado processo
     * @param $idProcesso
     * @return array
     */
    public function carregaProcessosSnapshot($idProcesso)
    {
        $where = ($idProcesso != '') ? ' WHERE p.idProcesso = '. $idProcesso : '';

        $sql = "
            SELECT 
                p.idProcesso,
                mgp.descricao,
                p.descProcesso,
                (SELECT                 
                    a.descricao
                FROM 
                    z_sga_grupos gs
                LEFT JOIN
                    z_sga_grupo_programa gp
                    ON gs.idGrupo = gp.idGrupo
                LEFT JOIN
                    z_sga_mtz_apps_processo apps
                    ON gp.idPrograma = apps.idPrograma
                RIGHT JOIN
                    z_sga_mtz_area a
                    ON gs.idUsuario = a.responsavel
                WHERE 
                    apps.idProcesso = p.idProcesso
                GROUP BY            
                    apps.idProcesso) AS area,
                 (SELECT                
                        COUNT(DISTINCT(gs.idUsuario)) AS numUsuarios    
                    FROM 
                        z_sga_grupos_foto gs
                    LEFT JOIN
                        z_sga_grupo_programa_foto gp
                        ON gs.idGrupo = gp.idGrupo
                    LEFT JOIN
                        z_sga_mtz_apps_processo apps
                        ON gp.idPrograma = apps.idPrograma
                    RIGHT JOIN
                        z_sga_mtz_processo proc
                        ON apps.idProcesso = proc.idProcesso
                    WHERE apps.idProcesso = p.idProcesso
                    GROUP BY            
                        p.idProcesso) AS numUsuarios,
                (SELECT COUNT(DISTINCT(apps.idPrograma)) FROM z_sga_mtz_apps_processo apps WHERE apps.idProcesso = p.idProcesso) AS numProgramas,
                (SELECT COUNT(DISTINCT(gp.idGrupo)) FROM z_sga_grupo_programa_foto gp RIGHT JOIN z_sga_mtz_apps_processo apps ON gp.idPrograma = apps.idPrograma
                    WHERE apps.idProcesso = p.idProcesso
                ) AS numGrupos,
                (SELECT COUNT(DISTINCT(pg.cod_modulo)) FROM z_sga_programas pg 
                    LEFT JOIN z_sga_mtz_apps_processo apps ON pg.z_sga_programas_id = apps.idPrograma
                    WHERE apps.idProcesso = p.idProcesso) AS numModulos
            FROM 
                z_sga_mtz_processo p
            LEFT JOIN
                z_sga_mtz_grupo_de_processo mgp
                ON p.idGrpProcesso = mgp.idGrpProcesso
            $where 
            GROUP BY
                p.idProcesso";

        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0):
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        else:
            return array();
        endif;
    }

    /**
     * Retorna os grupos de determinado processo
     * @param $idProcesso
     * @return array
     */
    public function carregaGruposProcessoSnapshot($idProcesso)
    {
        $sql = "
            SELECT DISTINCT
                g.idLegGrupo,
                g.descAbrev,
                u.nome_usuario AS nomeGestor,
                (SELECT COUNT(idUsuario) FROM z_sga_grupos_foto gs WHERE gs.idGrupo = g.idGrupo) AS numUSuarios,
                (SELECT COUNT(idPrograma) FROM z_sga_grupo_programa_foto gp WHERE gp.idGrupo = g.idGrupo) AS numProgramas
            FROM 
                z_sga_grupo_programa_foto gp 
            INNER JOIN
                z_sga_grupo_foto g
                on gp.idGrupo = g.idGrupo
            LEFT JOIN
                z_sga_grupos_foto gs
                ON gp.idGrupo = gs.idGrupo
            LEFT JOIN
                z_sga_usuarios u
                ON gs.idUsuario = u.z_sga_usuarios_id
            RIGHT JOIN 
                z_sga_mtz_apps_processo apps 
                ON gp.idPrograma = apps.idPrograma
            LEFT JOIN
                z_sga_mtz_processo p
                ON apps.idProcesso = p.idProcesso
            WHERE 
                apps.idProcesso = $idProcesso
            GROUP BY
                gp.idGrupo";

        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0):
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        else:
            return array();
        endif;
    }

    /**
     * Retorna os programas de determinado processo
     * @param $idProcesso
     * @return array
     */
    public function carregaProgramasProcessoSnapshot($idProcesso)
    {
        $sql = "
            SELECT 
                apps.idPrograma,
                pg.cod_programa,
                pg.descricao_programa,
                GROUP_CONCAT(DISTINCT concat_ws(';',g.idLegGrupo, g.descAbrev) SEPARATOR ' | ') AS grupos,
                pg.descricao_rotina
            FROM 
                z_sga_mtz_apps_processo apps    
            LEFT JOIN
                z_sga_mtz_processo p
                ON apps.idProcesso = p.idProcesso
            LEFT JOIN
                z_sga_programas pg
                ON apps.idPrograma = pg.z_sga_programas_id
            LEFT JOIN
                z_sga_grupo_programa_foto gp
                ON pg.z_sga_programas_id = gp.idPrograma
            INNER JOIN
                z_sga_grupo_foto g
                ON gp.idGrupo = g.idGrupo
            WHERE
                p.idProcesso = $idProcesso
            GROUP BY
                pg.cod_programa
            ORDER BY
                pg.cod_programa";

        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0):
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        else:
            return array();
        endif;
    }

    /**
     * Retorn os usuários de determinado processo
     * @param $idProcesso
     * @return array
     */
    public function carregaUsuariosProcessoSnapshot($idProcesso)
    {
        $sql = "
            SELECT              
                DISTINCT(gs.idUsuario) AS idUsuario,
                u.nome_usuario,    
                u.cod_usuario AS idTotvs,
                (SELECT us.nome_usuario FROM z_sga_usuarios us WHERE us.cod_usuario = u.cod_gestor) AS gestor,
                IF(e.ativo = 1, 'Ativo', 'Inativo') AS situacao
            FROM 
                z_sga_grupos_foto gs
            INNER JOIN
                z_sga_usuarios u
                ON gs.idUsuario = u.z_sga_usuarios_id
            INNER JOIN
                z_sga_usuario_empresa e
                ON gs.idUsuario = e.idUsuario
            LEFT JOIN
                z_sga_grupo_programa_foto gp
                ON gs.idGrupo = gp.idGrupo
            LEFT JOIN
                z_sga_mtz_apps_processo apps
                ON gp.idPrograma = apps.idPrograma
            RIGHT JOIN
                z_sga_mtz_processo proc
                ON apps.idProcesso = proc.idProcesso
            LEFT JOIN
                z_sga_mtz_processo p
                ON apps.idProcesso = p.idProcesso
            WHERE 
                apps.idProcesso = $idProcesso";

        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0):
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        else:
            return array();
        endif;
    }

    /**
     * Retorna os módulos de determinado processo
     * @param $idProcesso
     * @return array
     */
    public function carregaModulosProcessoSnapshot($idProcesso)
    {
        $sql = "
            SELECT    
                pg.cod_modulo,
                sis.des_sist_dtsul,
                pg.descricao_modulo,    
                pg.descricao_rotina,
                GROUP_CONCAT(DISTINCT concat_ws(';',pg.cod_programa,pg.descricao_programa) SEPARATOR ' | ') AS Programas,
                (SELECT COUNT(DISTINCT(apps.idPrograma)) FROM z_sga_programas prog WHERE prog.cod_modulo = pg.cod_modulo GROUP BY pg.cod_modulo) AS numProgramas,
                GROUP_CONCAT(DISTINCT g.idLegGrupo SEPARATOR ' | ') AS Grupos
            FROM
                z_sga_programas pg
            LEFT JOIN
                z_sga_mtz_apps_processo apps 
                ON pg.z_sga_programas_id = apps.idPrograma
            LEFT JOIN
                z_sga_grupo_programa_foto gp
                ON apps.idPrograma = gp.idPrograma
            LEFT JOIN
                z_sga_grupo_foto g
                ON gp.idGrupo = g.idGrupo
            LEFT JOIN
                z_sga_modul_dtsul mdl
                ON pg.cod_modulo = mdl.cod_modul_dtsul
            LEFT JOIN
                z_sga_sist_dtsul AS sis
                ON mdl.cod_sist_dtsul = sis.cod_sist_dtsul
            WHERE
                apps.idProcesso = $idProcesso
            GROUP BY
                pg.cod_modulo";

        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0):
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        else:
            return array();
        endif;
    }

    /**
     * Retorna a data do último snaphsot
     * @return array
     */
    public function dataSnapshot()
    {
        $sql = "
            SELECT 
                dataHora
            FROM
                z_sga_snapshots
            WHERE
                idEmpresa = ".$_SESSION['empresaid']."
            ORDER BY 
                dataHora DESC
            LIMIT 
                1";

        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0):
            return $sql->fetch(PDO::FETCH_ASSOC);
        else:
            return array();
        endif;
    }
}