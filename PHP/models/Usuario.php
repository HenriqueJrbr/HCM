<?php
class Usuario extends Model {
      public function __construct(){
        parent::__construct();
      }

      public function countAcessosUsuario($idUsario){

        $idEmpresa = $_SESSION['empresaid'];
        /*$sql = "SELECT
                  (SELECT COUNT(gc.idGrupo) from z_sga_grupos gc, z_sga_grupo g where gc.idUsuario = u.z_sga_usuarios_id
                    and  g.idEmpresa =  userEmp.idEmpresa
                    and  gc.idGrupo = g.idGrupo) as numGrupos,
                    (select sum(somador) from (select 
                           count(distinct p.cod_programa) as somador
                        FROM
                            z_sga_usuarios AS u, z_sga_usuario_empresa AS eu, z_sga_grupos AS gu, z_sga_grupo AS g, z_sga_grupo_programa AS gp, z_sga_programas AS p
                        WHERE u.z_sga_usuarios_id = '$idUsario' -- TROCAR POR VARIAVEL NO PHP
                          and eu.idEmpresa = '$idEmpresa' -- TROCAR POR VARIAVEL NO PHP
                          and eu.idUsuario = u.z_sga_usuarios_id
                          AND gu.idUsuario = u.z_sga_usuarios_id
                          AND g.idGrupo = gu.idGrupo
                          AND g.idEmpresa = eu.idEmpresa
                          AND gp.idGrupo = gu.idGrupo
                          AND p.visualiza_menu LIKE 'yes'
                        AND p.z_sga_programas_id = gp.idPrograma
                        AND p.cod_programa = p.procedimento_pai
                        GROUP BY g.idEmpresa , u.z_sga_usuarios_id , gp.cod_programa
                        HAVING COUNT(gp.cod_programa) > 1) 
                        as dup) 
                        AS nroProgDup,
                        COUNT(DISTINCT rru.codRisco) AS nroRiscos,
                        (select sum(somador) from (select 
                           count(distinct p.cod_programa) as somador
                        FROM
                            z_sga_usuarios AS u, z_sga_usuario_empresa AS eu, z_sga_grupos AS gu, z_sga_grupo AS g, z_sga_grupo_programa AS gp, z_sga_programas AS p
                        WHERE u.z_sga_usuarios_id = '$idUsario' -- TROCAR POR VARIAVEL NO PHP
                          and eu.idEmpresa = '$idEmpresa' -- TROCAR POR VARIAVEL NO PHP
                          and eu.idUsuario = u.z_sga_usuarios_id
                          AND gu.idUsuario = u.z_sga_usuarios_id
                          AND g.idGrupo = gu.idGrupo
                          AND g.idEmpresa = eu.idEmpresa
                          AND gp.idGrupo = gu.idGrupo
                          AND p.visualiza_menu LIKE 'yes'
                        AND p.z_sga_programas_id = gp.idPrograma
                        AND p.cod_programa = p.procedimento_pai
                        GROUP BY g.idEmpresa , u.z_sga_usuarios_id , gp.cod_programa) 
                        as progs) as numProgs,
                         (select sum(somador) from (select 
                           count(distinct appPro.idProcesso) as somador
                        FROM
                            z_sga_usuarios AS u, z_sga_usuario_empresa AS eu, z_sga_grupos AS gu, z_sga_grupo AS g, z_sga_grupo_programa AS gp, z_sga_programas AS p, z_sga_mtz_apps_processo as appPro
                        WHERE u.z_sga_usuarios_id = '$idUsario' -- TROCAR POR VARIAVEL NO PHP
                          and eu.idEmpresa =  '$idEmpresa' -- TROCAR POR VARIAVEL NO PHP
                          and eu.idUsuario = u.z_sga_usuarios_id
                          AND gu.idUsuario = u.z_sga_usuarios_id
                          AND g.idGrupo = gu.idGrupo
                          AND g.idEmpresa = eu.idEmpresa
                          AND gp.idGrupo = gu.idGrupo
                          AND p.visualiza_menu LIKE 'yes'
                        AND p.z_sga_programas_id = gp.idPrograma
                        AND p.cod_programa = p.procedimento_pai
                          and appPro.idPrograma = gp.idPrograma
                        GROUP BY g.idEmpresa , u.z_sga_usuarios_id , appPro.idProcesso) 
                        as Procs) as numProcess,
                         (select sum(somador) from (select 
                           count(distinct p.cod_modulo) as somador
                        FROM
                            z_sga_usuarios AS u, z_sga_usuario_empresa AS eu, 
                            z_sga_grupos AS gu, z_sga_grupo AS g, 
                            z_sga_grupo_programa AS gp, z_sga_programas AS p
                        WHERE u.z_sga_usuarios_id = '$idUsario' -- TROCAR POR VARIAVEL NO PHP
                          and eu.idEmpresa = '$idEmpresa' -- TROCAR POR VARIAVEL NO PHP
                          and eu.idUsuario = u.z_sga_usuarios_id
                          AND gu.idUsuario = u.z_sga_usuarios_id
                          AND g.idGrupo = gu.idGrupo
                          AND g.idEmpresa = eu.idEmpresa
                          AND gp.idGrupo = gu.idGrupo
                          AND p.visualiza_menu LIKE 'yes'
                        AND p.z_sga_programas_id = gp.idPrograma
                        AND p.cod_programa = p.procedimento_pai
                        GROUP BY g.idEmpresa , u.z_sga_usuarios_id , p.cod_modulo) 
                        as Mdls) as numModulos
                    FROM z_sga_usuario_empresa AS userEmp
                    INNER JOIN
                        z_sga_usuarios AS u ON userEmp.idUsuario = u.z_sga_usuarios_id
                    LEFT JOIN
                        v_sga_mtz_resumo_matriz_usuario AS rru ON rru.idUsuario = userEmp.idUsuario
                                          AND rru.idEmpresa = userEmp.idEmpresa                                          
                    WHERE  userEmp.idEmpresa = '$idEmpresa'  -- TROCAR POR VARIAVEL NO PHP
                      AND  userEmp.idUsuario = '$idUsario' -- TROCAR POR VARIAVEL NO PHP
                    GROUP BY u.z_sga_usuarios_id";*/

        $sql = "
            SELECT 
                (SELECT COUNT(gc.idGrupo) from z_sga_grupos gc, z_sga_grupo g where gc.idUsuario = u.z_sga_usuarios_id
                                                                               and  g.idEmpresa =  userEmp.idEmpresa
                                                                               and  gc.idGrupo = g.idGrupo) as numGrupos,
                (select sum(somador) from (select 
                   count(distinct p.cod_programa) as somador
                FROM
                    z_sga_usuarios AS u, z_sga_usuario_empresa AS eu, z_sga_grupos AS gu, z_sga_grupo AS g, z_sga_grupo_programa AS gp, z_sga_programas AS p
                WHERE u.z_sga_usuarios_id = '$idUsario' -- TROCAR POR VARIAVEL NO PHP
                  and eu.idEmpresa = '$idEmpresa' -- TROCAR POR VARIAVEL NO PHP
                  and eu.idUsuario = u.z_sga_usuarios_id
                  AND gu.idUsuario = u.z_sga_usuarios_id
                  AND g.idGrupo = gu.idGrupo
                  AND g.idEmpresa = eu.idEmpresa
                  AND gp.idGrupo = gu.idGrupo
                  AND p.visualiza_menu LIKE 'yes'
                  AND p.z_sga_programas_id = gp.idPrograma
                  AND p.cod_programa = p.procedimento_pai
                GROUP BY g.idEmpresa , u.z_sga_usuarios_id , gp.cod_programa
                HAVING COUNT(gp.cod_programa) > 1) 
                as dup) 
                AS nroProgDup,
                (SELECT COUNT(DISTINCT vm.idProcesso) FROM z_sga_vm_usuarios_processos_riscos vm WHERE vm.idUsuario = u.z_sga_usuarios_id) AS nroRiscos,
                (/*select sum(somador) from (select 
                   count(distinct p.cod_programa) as somador
                FROM
                    z_sga_usuarios AS u, z_sga_usuario_empresa AS eu, z_sga_grupos AS gu, z_sga_grupo AS g, z_sga_grupo_programa AS gp, z_sga_programas AS p
                WHERE u.z_sga_usuarios_id = '$idUsario' -- TROCAR POR VARIAVEL NO PHP
                  and eu.idEmpresa = '$idEmpresa' -- TROCAR POR VARIAVEL NO PHP
                  and eu.idUsuario = u.z_sga_usuarios_id
                  AND gu.idUsuario = u.z_sga_usuarios_id
                  AND g.idGrupo = gu.idGrupo
                  AND g.idEmpresa = eu.idEmpresa
                  AND gp.idGrupo = gu.idGrupo
                  AND p.visualiza_menu LIKE 'yes'
                  AND p.z_sga_programas_id = gp.idPrograma
               --   AND p.cod_programa = p.procedimento_pai
                GROUP BY g.idEmpresa , u.z_sga_usuarios_id , gp.cod_programa) 
                as progs*/
                SELECT
                    count(DISTINCT p.cod_programa) AS somador
                FROM
                    z_sga_usuarios AS u,
                    z_sga_usuario_empresa AS eu,
                    z_sga_grupos AS gu,
                    z_sga_grupo AS g,
                    z_sga_grupo_programa AS gp,
                    z_sga_programas AS p
                WHERE 
                    u.z_sga_usuarios_id = $idUsario
                    AND p.cod_programa = p.procedimento_pai /* Apresenta apenas programas principais */
                    AND eu.idUsuario = u.z_sga_usuarios_id
                    AND gu.idUsuario = u.z_sga_usuarios_id
                    AND g.idGrupo = gu.idGrupo
                    /* AND g.idGrupo <> '13' */    
                    AND g.idEmpresa = eu.idEmpresa
                    AND gp.idGrupo = gu.idGrupo
                    AND p.z_sga_programas_id = gp.idPrograma
                    AND eu.idEmpresa = $idEmpresa
                ) as numProgs,
                 (select sum(somador) from (select 
                   count(distinct appPro.idProcesso) as somador
                FROM
                    z_sga_usuarios AS u, z_sga_usuario_empresa AS eu, z_sga_grupos AS gu, z_sga_grupo AS g, z_sga_grupo_programa AS gp, z_sga_programas AS p, z_sga_mtz_apps_processo as appPro
                WHERE u.z_sga_usuarios_id = '$idUsario' -- TROCAR POR VARIAVEL NO PHP
                  and eu.idEmpresa = '$idEmpresa' -- TROCAR POR VARIAVEL NO PHP
                  and eu.idUsuario = u.z_sga_usuarios_id
                  AND gu.idUsuario = u.z_sga_usuarios_id
                  AND g.idGrupo = gu.idGrupo
                  AND g.idEmpresa = eu.idEmpresa
                  AND gp.idGrupo = gu.idGrupo
                  AND p.visualiza_menu LIKE 'yes'
                  AND p.z_sga_programas_id = gp.idPrograma
                --  AND p.cod_programa = p.procedimento_pai
                  and appPro.idPrograma = gp.idPrograma
                GROUP BY g.idEmpresa , u.z_sga_usuarios_id , appPro.idProcesso) 
                as Procs) as numProcess,
                 (select sum(somador) from (select 
                   count(distinct p.cod_modulo) as somador
                FROM
                    z_sga_usuarios AS u, z_sga_usuario_empresa AS eu, 
                    z_sga_grupos AS gu, z_sga_grupo AS g, 
                    z_sga_grupo_programa AS gp, z_sga_programas AS p
                WHERE u.z_sga_usuarios_id = '$idUsario' -- TROCAR POR VARIAVEL NO PHP
                  and eu.idEmpresa = '$idEmpresa' -- TROCAR POR VARIAVEL NO PHP
                  and eu.idUsuario = u.z_sga_usuarios_id
                  AND gu.idUsuario = u.z_sga_usuarios_id
                  AND g.idGrupo = gu.idGrupo
                  AND g.idEmpresa = eu.idEmpresa
                  AND gp.idGrupo = gu.idGrupo
                  AND p.visualiza_menu LIKE 'yes'
                  AND p.z_sga_programas_id = gp.idPrograma
                --  AND p.cod_programa = p.procedimento_pai
                GROUP BY g.idEmpresa , u.z_sga_usuarios_id , p.cod_modulo) 
                as Mdls) as numModulos
            FROM z_sga_usuario_empresa AS userEmp
            INNER JOIN
                z_sga_usuarios AS u ON userEmp.idUsuario = u.z_sga_usuarios_id
            LEFT JOIN
                (
                    SELECT bmusr.empGrupo AS idEmpresa, bmusr.idUsuario, mtzp.idMtzRisco
                            FROM z_sga_mtz_coorelacao_processo cp, 
                                 v_sga_mtz_base_matriz_usuario bmusr, 
                                 z_sga_usuarios usr, 
                                 z_sga_mtz_processo mtzp
                        WHERE
                            cp.idProcessoPrim = bmusr.idProcesso
                                AND mtzp.idProcesso = cp.idProcessoPrim
                                AND usr.z_sga_usuarios_id = bmusr.idUsuario
                        GROUP BY bmusr.idUsuario , mtzp.idMtzRisco    
                ) AS rru ON rru.idUsuario = userEmp.idUsuario
                                                      AND rru.idEmpresa = userEmp.idEmpresa                                          
            WHERE  userEmp.idEmpresa = '$idEmpresa'  -- TROCAR POR VARIAVEL NO PHP
              AND  userEmp.idUsuario = '$idUsario' -- TROCAR POR VARIAVEL NO PHP
            GROUP BY u.z_sga_usuarios_id            
        ";

            $sql = $this->db->query($sql);
            $array = array();
            if($sql->rowCount()>0){
                  $array = $sql->fetch();
            }
            return $array;
      }

       public function countAcessosUsuarioFoto($idUsario){

        $idEmpresa = $_SESSION['empresaid'];


           $sql = "
            SELECT 
                (SELECT COUNT(gc.idGrupo) from z_sga_grupos_foto gc, z_sga_grupo g where gc.idUsuario = u.z_sga_usuarios_id
                                                                               and  g.idEmpresa =  userEmp.idEmpresa
                                                                               and  gc.idGrupo = g.idGrupo) as numGrupos,
                (select sum(somador) from (select 
                   count(distinct p.cod_programa) as somador
                FROM
                    z_sga_usuarios AS u, z_sga_usuario_empresa AS eu, z_sga_grupos_foto AS gu, z_sga_grupo AS g, z_sga_grupo_programa_foto AS gp, z_sga_programas AS p
                WHERE u.z_sga_usuarios_id = '$idUsario' -- TROCAR POR VARIAVEL NO PHP
                  and eu.idEmpresa = '$idEmpresa' -- TROCAR POR VARIAVEL NO PHP
                  and eu.idUsuario = u.z_sga_usuarios_id
                  AND gu.idUsuario = u.z_sga_usuarios_id
                  AND g.idGrupo = gu.idGrupo
                  AND g.idEmpresa = eu.idEmpresa
                  AND gp.idGrupo = gu.idGrupo
                  AND p.visualiza_menu LIKE 'yes'
                  AND p.z_sga_programas_id = gp.idPrograma
                --  AND p.cod_programa = p.procedimento_pai
                GROUP BY g.idEmpresa , u.z_sga_usuarios_id , gp.cod_programa
                HAVING COUNT(gp.cod_programa) > 1) 
                as dup) 
                AS nroProgDup,
                COUNT(DISTINCT rru.idMtzRisco) AS nroRiscos,
                (/*select sum(somador) from (select 
                   count(distinct p.cod_programa) as somador
                FROM
                    z_sga_usuarios AS u, z_sga_usuario_empresa AS eu, z_sga_grupos_foto AS gu, z_sga_grupo AS g, z_sga_grupo_programa_foto AS gp, z_sga_programas AS p
                WHERE u.z_sga_usuarios_id = '$idUsario' -- TROCAR POR VARIAVEL NO PHP
                  and eu.idEmpresa = '$idEmpresa' -- TROCAR POR VARIAVEL NO PHP
                  and eu.idUsuario = u.z_sga_usuarios_id
                  AND gu.idUsuario = u.z_sga_usuarios_id
                  AND g.idGrupo = gu.idGrupo
                  AND g.idEmpresa = eu.idEmpresa
                  AND gp.idGrupo = gu.idGrupo
                  AND p.visualiza_menu LIKE 'yes'
                  AND p.z_sga_programas_id = gp.idPrograma
                --  AND p.cod_programa = p.procedimento_pai
                GROUP BY g.idEmpresa , u.z_sga_usuarios_id , gp.cod_programa) 
                as progs*/
                SELECT
                    count(DISTINCT p.cod_programa) AS somador    
                FROM
                    z_sga_usuarios AS u,
                    z_sga_usuario_empresa AS eu,
                    z_sga_grupos_foto AS gu,
                    z_sga_grupo_foto AS g,
                    z_sga_grupo_programa_foto AS gp,
                    z_sga_programas AS p
                WHERE 
                    u.z_sga_usuarios_id = $idUsario
                    AND p.cod_programa = p.procedimento_pai /* Apresenta apenas programas principais */
                    AND eu.idUsuario = u.z_sga_usuarios_id
                    AND gu.idUsuario = u.z_sga_usuarios_id
                    AND g.idGrupo = gu.idGrupo
                    /* AND g.idGrupo <> '13' */    
                    AND g.idEmpresa = eu.idEmpresa
                    AND gp.idGrupo = gu.idGrupo
                    AND p.z_sga_programas_id = gp.idPrograma
                    AND eu.idEmpresa = $idEmpresa) as numProgs,
                 (select sum(somador) from (select 
                   count(distinct appPro.idProcesso) as somador
                FROM
                    z_sga_usuarios AS u, z_sga_usuario_empresa AS eu, z_sga_grupos_foto AS gu, z_sga_grupo AS g, z_sga_grupo_programa_foto AS gp, z_sga_programas AS p, z_sga_mtz_apps_processo as appPro
                WHERE u.z_sga_usuarios_id = '$idUsario' -- TROCAR POR VARIAVEL NO PHP
                  and eu.idEmpresa = '$idEmpresa' -- TROCAR POR VARIAVEL NO PHP
                  and eu.idUsuario = u.z_sga_usuarios_id
                  AND gu.idUsuario = u.z_sga_usuarios_id
                  AND g.idGrupo = gu.idGrupo
                  AND g.idEmpresa = eu.idEmpresa
                  AND gp.idGrupo = gu.idGrupo
                  AND p.visualiza_menu LIKE 'yes'
                  AND p.z_sga_programas_id = gp.idPrograma
                --  AND p.cod_programa = p.procedimento_pai
                  and appPro.idPrograma = gp.idPrograma
                GROUP BY g.idEmpresa , u.z_sga_usuarios_id , appPro.idProcesso) 
                as Procs) as numProcess,
                 (select sum(somador) from (select 
                   count(distinct p.cod_modulo) as somador
                FROM
                    z_sga_usuarios AS u, z_sga_usuario_empresa AS eu, 
                    z_sga_grupos_foto AS gu, z_sga_grupo AS g, 
                    z_sga_grupo_programa_foto AS gp, z_sga_programas AS p
                WHERE u.z_sga_usuarios_id = '$idUsario' -- TROCAR POR VARIAVEL NO PHP
                  and eu.idEmpresa = '$idEmpresa' -- TROCAR POR VARIAVEL NO PHP
                  and eu.idUsuario = u.z_sga_usuarios_id
                  AND gu.idUsuario = u.z_sga_usuarios_id
                  AND g.idGrupo = gu.idGrupo
                  AND g.idEmpresa = eu.idEmpresa
                  AND gp.idGrupo = gu.idGrupo
                  AND p.visualiza_menu LIKE 'yes'
                  AND p.z_sga_programas_id = gp.idPrograma
                --  AND p.cod_programa = p.procedimento_pai
                GROUP BY g.idEmpresa , u.z_sga_usuarios_id , p.cod_modulo) 
                as Mdls) as numModulos
            FROM z_sga_usuario_empresa AS userEmp
            INNER JOIN
                z_sga_usuarios AS u ON userEmp.idUsuario = u.z_sga_usuarios_id
            LEFT JOIN
                (
                    SELECT bmusr.empGrupo AS idEmpresa, bmusr.idUsuario, mtzp.idMtzRisco
                            FROM z_sga_mtz_coorelacao_processo cp, 
                                 v_sga_mtz_base_matriz_usuario_foto bmusr, 
                                 z_sga_usuarios usr, 
                                 z_sga_mtz_processo mtzp
                        WHERE
                            cp.idProcessoPrim = bmusr.idProcesso
                                AND mtzp.idProcesso = cp.idProcessoPrim
                                AND usr.z_sga_usuarios_id = bmusr.idUsuario
                        GROUP BY bmusr.idUsuario , mtzp.idMtzRisco    
                ) AS rru ON rru.idUsuario = userEmp.idUsuario
                                                      AND rru.idEmpresa = userEmp.idEmpresa                                          
            WHERE  userEmp.idEmpresa = '$idEmpresa'  -- TROCAR POR VARIAVEL NO PHP
              AND  userEmp.idUsuario = '$idUsario' -- TROCAR POR VARIAVEL NO PHP
            GROUP BY u.z_sga_usuarios_id            
        ";





        /*$sql = "SELECT
                  (SELECT COUNT(gc.idGrupo) from z_sga_grupos_foto gc, z_sga_grupo_foto g where gc.idUsuario = u.z_sga_usuarios_id
                    and  g.idEmpresa =  userEmp.idEmpresa
                    and  gc.idGrupo = g.idGrupo) as numGrupos,
                    (select sum(somador) from (select 
                           count(distinct p.cod_programa) as somador
                        FROM
                            z_sga_usuarios AS u, z_sga_usuario_empresa AS eu, z_sga_grupos_foto AS gu, z_sga_grupo_foto AS g, z_sga_grupo_programa_foto AS gp, z_sga_programas AS p
                        WHERE u.z_sga_usuarios_id = '$idUsario' -- TROCAR POR VARIAVEL NO PHP
                          and eu.idEmpresa = '$idEmpresa' -- TROCAR POR VARIAVEL NO PHP
                          and eu.idUsuario = u.z_sga_usuarios_id
                          AND gu.idUsuario = u.z_sga_usuarios_id
                          AND g.idGrupo = gu.idGrupo
                          AND g.idEmpresa = eu.idEmpresa
                          AND gp.idGrupo = gu.idGrupo
                          AND p.visualiza_menu LIKE 'yes'
                        AND p.z_sga_programas_id = gp.idPrograma
                        AND p.cod_programa = p.procedimento_pai
                        GROUP BY g.idEmpresa , u.z_sga_usuarios_id , gp.cod_programa
                        HAVING COUNT(gp.cod_programa) > 1) 
                        as dup) 
                        AS nroProgDup,
                        COUNT(DISTINCT rru.codRisco) AS nroRiscos,
                        (select sum(somador) from (select 
                           count(distinct p.cod_programa) as somador
                        FROM
                            z_sga_usuarios AS u, z_sga_usuario_empresa AS eu, z_sga_grupos_foto AS gu, z_sga_grupo_foto AS g, z_sga_grupo_programa_foto AS gp, z_sga_programas AS p
                        WHERE u.z_sga_usuarios_id = '$idUsario' -- TROCAR POR VARIAVEL NO PHP
                          and eu.idEmpresa = '$idEmpresa' -- TROCAR POR VARIAVEL NO PHP
                          and eu.idUsuario = u.z_sga_usuarios_id
                          AND gu.idUsuario = u.z_sga_usuarios_id
                          AND g.idGrupo = gu.idGrupo
                          AND g.idEmpresa = eu.idEmpresa
                          AND gp.idGrupo = gu.idGrupo
                          AND p.visualiza_menu LIKE 'yes'
                        AND p.z_sga_programas_id = gp.idPrograma
                        AND p.cod_programa = p.procedimento_pai
                        GROUP BY g.idEmpresa , u.z_sga_usuarios_id , gp.cod_programa) 
                        as progs) as numProgs,
                         (select sum(somador) from (select 
                           count(distinct appPro.idProcesso) as somador
                        FROM
                            z_sga_usuarios AS u, z_sga_usuario_empresa AS eu, z_sga_grupos_foto AS gu, z_sga_grupo_foto AS g, z_sga_grupo_programa_foto AS gp, z_sga_programas AS p, z_sga_mtz_apps_processo as appPro
                        WHERE u.z_sga_usuarios_id = '$idUsario' -- TROCAR POR VARIAVEL NO PHP
                          and eu.idEmpresa =  '$idEmpresa' -- TROCAR POR VARIAVEL NO PHP
                          and eu.idUsuario = u.z_sga_usuarios_id
                          AND gu.idUsuario = u.z_sga_usuarios_id
                          AND g.idGrupo = gu.idGrupo
                          AND g.idEmpresa = eu.idEmpresa
                          AND gp.idGrupo = gu.idGrupo
                          AND p.visualiza_menu LIKE 'yes'
                        AND p.z_sga_programas_id = gp.idPrograma
                        AND p.cod_programa = p.procedimento_pai
                          and appPro.idPrograma = gp.idPrograma
                        GROUP BY g.idEmpresa , u.z_sga_usuarios_id , appPro.idProcesso) 
                        as Procs) as numProcess,
                         (select sum(somador) from (select 
                           count(distinct p.cod_modulo) as somador
                        FROM
                            z_sga_usuarios AS u, z_sga_usuario_empresa AS eu, 
                            z_sga_grupos_foto AS gu, z_sga_grupo_foto AS g, 
                            z_sga_grupo_programa_foto AS gp, z_sga_programas AS p
                        WHERE u.z_sga_usuarios_id = '$idUsario' -- TROCAR POR VARIAVEL NO PHP
                          and eu.idEmpresa = '$idEmpresa' -- TROCAR POR VARIAVEL NO PHP
                          and eu.idUsuario = u.z_sga_usuarios_id
                          AND gu.idUsuario = u.z_sga_usuarios_id
                          AND g.idGrupo = gu.idGrupo
                          AND g.idEmpresa = eu.idEmpresa
                          AND gp.idGrupo = gu.idGrupo
                          AND p.visualiza_menu LIKE 'yes'
                        AND p.z_sga_programas_id = gp.idPrograma
                        AND p.cod_programa = p.procedimento_pai
                        GROUP BY g.idEmpresa , u.z_sga_usuarios_id , p.cod_modulo) 
                        as Mdls) as numModulos
                    FROM z_sga_usuario_empresa AS userEmp
                    INNER JOIN
                        z_sga_usuarios AS u ON userEmp.idUsuario = u.z_sga_usuarios_id
                    LEFT JOIN
                        v_sga_mtz_resumo_matriz_usuario AS rru ON rru.idUsuario = userEmp.idUsuario
                                          AND rru.idEmpresa = userEmp.idEmpresa                                          
                    WHERE  userEmp.idEmpresa = '$idEmpresa'  -- TROCAR POR VARIAVEL NO PHP
                      AND  userEmp.idUsuario = '$idUsario' -- TROCAR POR VARIAVEL NO PHP
                    GROUP BY u.z_sga_usuarios_id";*/
            $sql = $this->db->query($sql);
            $array = array();
            if($sql->rowCount()>0){
                  $array = $sql->fetch();
            }
            return $array;
      }

    /**
     * @param $search String com o filtro a se buscar
     * @param $orderColumn Int com o índice da coluna a se ordenar na consulta
     * @param $orderDir String ASC ou DESC
     * @param $offset Int numero com salto da consulta
     * @param $limit Total de registros as se buscar por pagina
     * @return array Retorna um array com os registros encontrados
     */
    public function carregaDatatableUsuario($search, $orderColumn, $orderDir, $offset, $limit){
        if($_SESSION['acesso'] == "SI"){
            $addFiltro = "";
        }else if($_SESSION['acesso'] == "gestor"){
            $gestor = $_SESSION['gestor'];
            $addFiltro = " AND cod_gestor = '$gestor' ";
        }

        $sql  = "
            SELECT 
                  userEmp.idEmpresa,
                  userEmp.idUsuario,
                  u.z_sga_usuarios_id,
                  u.nome_usuario,
                  u.cod_usuario,
                  (SELECT ug.nome_usuario FROM z_sga_usuarios ug WHERE ug.cod_usuario = u.cod_gestor) AS gestor,
                  u.cod_funcao,
                  m.cod_funcao,
                  COUNT(DISTINCT userEmp.idEmpresa) AS nroInstancias,
                  COUNT(DISTINCT rru.codRisco) AS nroRiscos
            FROM 
                z_sga_usuario_empresa AS userEmp
            INNER JOIN
                z_sga_usuarios AS u ON userEmp.idUsuario = u.z_sga_usuarios_id
            LEFT JOIN
                z_sga_manut_funcao AS m ON u.cod_funcao = m.idFuncao
            LEFT JOIN
                v_sga_mtz_resumo_matriz_usuario AS rru 
                ON rru.idUsuario = userEmp.idUsuario AND rru.idEmpresa = userEmp.idEmpresa
            WHERE 
                userEmp.idEmpresa = '".$_SESSION['empresaid']."' ";

        $sql .= $addFiltro;

        // Se a variavel $search não estiver vazia cria o where no select
        if($search != ''):
            //$sql .= " OR userEmp.idUsuario LIKE '%$search%'";
            $sql .= " AND u.z_sga_usuarios_id LIKE '%$search%'";
            $sql .= " OR u.nome_usuario LIKE '%$search%'";
            $sql .= " OR u.cod_usuario LIKE '%$search%'";
            $sql .= " OR u.funcao LIKE '%$search%'";
            $sql .= " OR u.cod_funcao LIKE '%$search%'";
        endif;

        $sql .= " GROUP BY u.z_sga_usuarios_id";

        // Se a variavel $order não estiver vazia ordena o retorno
        if($orderColumn != ''):
            $sql .= " ORDER BY $orderColumn $orderDir";
        endif;

        // Se as variaveis $offset e $limit não estiverem vazias limita o retorno
        if($offset != '' && $limit != ''):
            $sql .= " LIMIT $offset, $limit";
        endif;

        $sql = $this->db->query($sql);

        $dados = array();
        if($sql->rowCount()>0){
            $dados = $sql->fetchAll();
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
    public function getCountTable($table, $search, $fields, $join, $idEmpresa = ''){
        $dados = array();
        $where = '';
        $sql = "SELECT count(DISTINCT $fields[0]) AS total FROM $table ";

        $sql .= $join;

        // Se a variavel $search não estiver vazia limita cria o where no select
        if($search != '' && count($fields) > 0):
            $i = 0;
            foreach($fields as $val):
                $where .= (($i == 0) ? ' WHERE ' : ' OR ') . "$val LIKE '%$search%'";
                $i++;
            endforeach;
        endif;

        $sql .= $where;

        // Se a variavel $idEmpresa não estiver vazia adiciona o filtro por empresa no where
        if($idEmpresa != '' && $idEmpresa != null):
            $sql .= (($where == '') ? ' WHERE ' : ' AND ') . " userEmp.idEmpresa = '$idEmpresa'";
        endif;
        
        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0):
            $dados = $sql->fetchAll();
        endif;

        return $dados[0]['total'];
    }

      public function carregaUsuario($idEmpresa,$tipo){
          $addFiltro = '';
          if($tipo == "1" || $tipo == "0"){
            $query = "AND userEmp.ativo = '$tipo'";
          }else{
            $query = "";
          }


            if(isset($_SESSION['acesso']) && $_SESSION['acesso'] == "SI"){
              $addFiltro = "";
            }else if(isset($_SESSION['acesso']) && $_SESSION['acesso'] == "gestor"){
              $gestor = $_SESSION['gestor'];
              $addFiltro = "AND cod_gestor = '$gestor'";
            }

            $sql = "
                SELECT 
                    userEmp.idEmpresa,
                    userEmp.idUsuario,
                    u.z_sga_usuarios_id,
                    u.nome_usuario,
                    u.cod_usuario,
                    userEmp.ativo,
                    (SELECT 
                            ug.nome_usuario
                        FROM
                            z_sga_usuarios ug
                        WHERE
                            ug.cod_usuario = u.cod_gestor) AS gestor,
                    u.cod_funcao,
                    m.cod_funcao,
                    COUNT(DISTINCT userEmp.idEmpresa) AS nroInstancias,
                    (SELECT COUNT(DISTINCT vm.idProcesso) FROM z_sga_vm_usuarios_processos_riscos vm WHERE vm.idUsuario = u.z_sga_usuarios_id) AS nroRiscos
                FROM
                    z_sga_usuario_empresa AS userEmp
                        INNER JOIN
                    z_sga_usuarios AS u ON userEmp.idUsuario = u.z_sga_usuarios_id
                        LEFT JOIN
                    z_sga_manut_funcao AS m ON u.cod_funcao = m.idFuncao
                    LEFT JOIN
                    (SELECT bmusr.empGrupo AS idEmpresa, bmusr.idUsuario, mtzp.idMtzRisco
                        FROM z_sga_mtz_coorelacao_processo cp, 
                             v_sga_mtz_base_matriz_usuario bmusr, 
                             z_sga_usuarios usr, 
                             z_sga_mtz_processo mtzp
                    WHERE
                        cp.idProcessoPrim = bmusr.idProcesso
                            AND mtzp.idProcesso = cp.idProcessoPrim
                            AND usr.z_sga_usuarios_id = bmusr.idUsuario
                    GROUP BY bmusr.idUsuario , mtzp.idMtzRisco) AS rru ON rru.idUsuario = userEmp.idUsuario
                        AND rru.idEmpresa = userEmp.idEmpresa
                WHERE
                    userEmp.idEmpresa = '$idEmpresa'
                     $query
                    $addFiltro 
                 GROUP BY u.z_sga_usuarios_id ";
                //echo "<pre>";
                //die($sql);
            $sql = $this->db->query($sql);
            $array = array();
            if($sql->rowCount()>0){
                  $array = $sql->fetchAll();
            }
            return $array;
      }

       public function usuarioSelecionado($id){
            $sql = "SELECT * FROM 
                    z_sga_usuarios as us,
                    z_sga_manut_funcao as fun
                    where us.z_sga_usuarios_id = '$id' and fun.idFuncao = us.cod_funcao";
            $sql = $this->db->query($sql);
            $array = array();
            if($sql->rowCount()>0){
                  $array = $sql->fetch();
            }
            return $array;
      }


      public function dadosGestor($id){
            $sql = "SELECT * FROM z_sga_usuarios where cod_usuario = '$id'";
            $sql = $this->db->query($sql);
            $array = array();
            if($sql->rowCount()>0){
                  $array = $sql->fetch();
            }
            return $array;
      }
      
      public function carregaGruposUsuario($id,$idEmpresa){
            $sql = "SELECT 
                      g.idLegGrupo,
                      g.descAbrev,
                      g.idGrupo,
                      (SELECT 
                              ui.nome_usuario
                          FROM
                              z_sga_usuarios AS ui
                          WHERE ui.cod_usuario = gs.gestor) AS nomeGestor,
                      (SELECT 
                            COUNT(gp.idPrograma)
                        FROM 
                            z_sga_grupo_programa AS gp
                        LEFT JOIN
                            z_sga_programas AS p 
                            ON gp.cod_programa = p.cod_programa
                        WHERE 	
                            gp.idGrupo = g.idGrupo) AS totalPro,
                      (SELECT 
                              COUNT(idUsuario)
                          FROM
                              z_sga_grupos AS gp
                                  JOIN
                              z_sga_usuarios AS u ON gp.idUsuario = z_sga_usuarios_id
                          WHERE
                              gp.idGrupo = g.idGrupo) AS totalUsuario
                  FROM
                      z_sga_grupos AS gs,
                      z_sga_usuarios AS u,
                      z_sga_grupo AS g
                  WHERE  gs.idUsuario = '$id'
                    AND g.idGrupo = gs.idGrupo
                    AND g.idEmpresa = '$idEmpresa'
                    AND u.z_sga_usuarios_id = gs.idUsuario";

            $sql = $this->db->query($sql);
            $array = array();
            if($sql->rowCount()>0){
                  $array = $sql->fetchAll();
            }
            return $array;

      }

      public function carregaGruposUsuarioFoto($id,$idEmpresa){
            $sql = "SELECT 
                      g.idLegGrupo,
                      g.descAbrev,
                      g.idGrupo,
                      (SELECT 
                              ui.nome_usuario
                          FROM
                              z_sga_usuarios AS ui
                          WHERE ui.cod_usuario = gs.gestor) AS nomeGestor,
                      (SELECT 
                            COUNT(gp.idPrograma)
                        FROM 
                            z_sga_grupo_programa_foto AS gp
                        LEFT JOIN
                            z_sga_programas AS p 
                            ON gp.cod_programa = p.cod_programa
                        WHERE 	
                            gp.idGrupo = g.idGrupo) AS totalPro,
                      (SELECT 
                              COUNT(idUsuario)
                          FROM
                              z_sga_grupos_foto AS gp
                                  JOIN
                              z_sga_usuarios AS u ON gp.idUsuario = z_sga_usuarios_id
                          WHERE
                              gp.idGrupo = g.idGrupo) AS totalUsuario
                  FROM
                      z_sga_grupos_foto AS gs,
                      z_sga_usuarios AS u,
                      z_sga_grupo_foto AS g
                  WHERE  gs.idUsuario = '$id'
                    AND g.idGrupo = gs.idGrupo
                    AND g.idEmpresa = '$idEmpresa'
                    AND u.z_sga_usuarios_id = gs.idUsuario";

            $sql = $this->db->query($sql);
            $array = array();
            if($sql->rowCount()>0){
                  $array = $sql->fetchAll();
            }
            return $array;

      }


      public function carregaProgramasUsuarioFoto($idUsuario,$idEmpresa){
        
            $sql = "SELECT
                   u.z_sga_usuarios_id, 
                   u.cod_usuario, 
                   u.nome_usuario, 
                   #g.descAbrev,
                   #g.idLegGrupo, 
                   GROUP_CONCAT(DISTINCT concat_ws(' - ' , g.idLegGrupo, g.descAbrev) 
                    ORDER BY g.idLegGrupo
                    SEPARATOR ' <br> ') AS grupos,
                   p.cod_programa,p.descricao_programa,p.descricao_rotina,p.ajuda_programa,p.especific
                  FROM
                  z_sga_usuarios AS u,
                  z_sga_usuario_empresa AS eu,
                  z_sga_grupos_foto AS gu,
                  z_sga_grupo_foto AS g,
                  z_sga_grupo_programa_foto AS gp,
                  z_sga_programas AS p
                  WHERE u.z_sga_usuarios_id = '$idUsuario'
                  AND p.cod_programa = p.procedimento_pai /* Apresenta apenas programas principais */
                  AND eu.idUsuario = u.z_sga_usuarios_id
                  AND gu.idUsuario = u.z_sga_usuarios_id
                  AND g.idGrupo = gu.idGrupo
                  /* AND g.idGrupo <> '13' */
                  AND g.idEmpresa = eu.idEmpresa
                  AND gp.idGrupo = gu.idGrupo
                  AND p.z_sga_programas_id = gp.idPrograma
                  AND eu.idEmpresa = '$idEmpresa'
                  GROUP BY
                    p.cod_programa";

            $sql = $this->db->query($sql);
            $array = array();
            if($sql->rowCount()>0){
                  $array = $sql->fetchAll();
            }
            return $array;    

      }

    /**
     * Retorna os grupos filtrando pelo id da empresa
     * @param $idEmpresa
     * @return array
     */
    public function carregaDatatableProgramasUsuario($search, $orderColumn, $orderDir, $offset, $limit, $idUsuario, $idEmpresa){

        $sql = "
            SELECT
                u.z_sga_usuarios_id, 
                u.cod_usuario, 
                u.nome_usuario, 
                GROUP_CONCAT(DISTINCT concat_ws(' - ' , g.idLegGrupo, g.descAbrev) ORDER BY g.idLegGrupo SEPARATOR ' <br> ') AS grupos,
                #g.descAbrev,
                #g.idLegGrupo, 
                p.cod_programa,
                p.descricao_programa,
                p.descricao_rotina,
                p.ajuda_programa,
                p.especific
            FROM
                z_sga_usuarios AS u,
                z_sga_usuario_empresa AS eu,
                z_sga_grupos AS gu,
                z_sga_grupo AS g,
                z_sga_grupo_programa AS gp,
                z_sga_programas AS p
            WHERE u.z_sga_usuarios_id = '$idUsuario'
                AND p.cod_programa = p.procedimento_pai /* Apresenta apenas programas principais */
                AND eu.idUsuario = u.z_sga_usuarios_id
                AND gu.idUsuario = u.z_sga_usuarios_id
                AND g.idGrupo = gu.idGrupo
                /* AND g.idGrupo <> '13' */
                AND g.idEmpresa = eu.idEmpresa
                AND gp.idGrupo = gu.idGrupo
                AND p.z_sga_programas_id = gp.idPrograma
                AND eu.idEmpresa = '$idEmpresa'
            ";

        

        // Se a variavel $search não estiver vazia o where no select
        if($search != ''):
            $sql .= " AND g.idLegGrupo LIKE '%$search%'";
            $sql .= " OR g.descAbrev LIKE '%$search%'";
            $sql .= " OR p.cod_programa LIKE '%$search%'";
            $sql .= " OR p.descricao_programa LIKE '%$search%'";
            $sql .= " OR p.descricao_rotina LIKE '%$search%'";            
        endif;

        $sql .= "
            GROUP BY
                p.cod_programa ";

        // Se a variavel $order não estiver vazia ordena o retorno
        if($orderColumn != ''):
            $sql .= " ORDER BY $orderColumn $orderDir";
        endif;

        // Se as variaveis $offset e $limit não estiverem vazias limita o retorno
        if($offset != '' && $limit != ''):
            $sql .= " LIMIT $offset, $limit";
        endif;

        //echo "<pre>";
        //die($sql);

        $sql = $this->db->query($sql);

        $array = array();
        if($sql->rowCount()>0){
            $array = $sql->fetchAll();
        }
        return $array;
    }

    /**
     * Responsável por retornar o total de registros encontrados na tabela     
     * @param $search String contendo o filtro
     * @param $fields Array com campos a filtrar          
     * @return mixed Retorna o total de registros encontrados
     */
    public function getCountTableProg($search, $fields, $idUsuario, $idEmpresa){
        $dados = array();        
        $sql = "
            SELECT
                count(DISTINCT p.cod_programa) as total
            FROM
                z_sga_usuarios AS u,
                z_sga_usuario_empresa AS eu,
                z_sga_grupos AS gu,
                z_sga_grupo AS g,
                z_sga_grupo_programa AS gp,
                z_sga_programas AS p
            WHERE u.z_sga_usuarios_id = '$idUsuario'
                AND p.cod_programa = p.procedimento_pai /* Apresenta apenas programas principais */
                AND eu.idUsuario = u.z_sga_usuarios_id
                AND gu.idUsuario = u.z_sga_usuarios_id
                AND g.idGrupo = gu.idGrupo
                /* AND g.idGrupo <> '13' */
                AND g.idEmpresa = eu.idEmpresa
                AND gp.idGrupo = gu.idGrupo
                AND p.z_sga_programas_id = gp.idPrograma
                AND eu.idEmpresa = '$idEmpresa'
            ";

        

        // Se a variavel $search não estiver vazia o where no select
        if($search != ''):
            $sql .= " AND g.idLegGrupo LIKE '%$search%'";
            $sql .= " OR g.descAbrev LIKE '%$search%'";
            $sql .= " OR p.cod_programa LIKE '%$search%'";
            $sql .= " OR p.descricao_programa LIKE '%$search%'";
            $sql .= " OR p.descricao_rotina LIKE '%$search%'";            
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
    public function carregaDatatableProgramasUsuarioFoto($search, $orderColumn, $orderDir, $offset, $limit, $idUsuario, $idEmpresa){

        $sql = "
            SELECT
                u.z_sga_usuarios_id, 
                u.cod_usuario, 
                u.nome_usuario, 
                GROUP_CONCAT(DISTINCT concat_ws(' - ' , g.idLegGrupo, g.descAbrev) ORDER BY g.idLegGrupo SEPARATOR ' <br> ') AS grupos,
                #g.descAbrev,
                #g.idLegGrupo, 
                p.cod_programa,
                p.descricao_programa,
                p.descricao_rotina,
                p.ajuda_programa,
                p.especific
            FROM
                z_sga_usuarios AS u,
                z_sga_usuario_empresa AS eu,
                z_sga_grupos_foto AS gu,
                z_sga_grupo_foto AS g,
                z_sga_grupo_programa_foto AS gp,
                z_sga_programas AS p
            WHERE u.z_sga_usuarios_id = '$idUsuario'
                AND p.cod_programa = p.procedimento_pai /* Apresenta apenas programas principais */
                AND eu.idUsuario = u.z_sga_usuarios_id
                AND gu.idUsuario = u.z_sga_usuarios_id
                AND g.idGrupo = gu.idGrupo
                /* AND g.idGrupo <> '13' */
                AND g.idEmpresa = eu.idEmpresa
                AND gp.idGrupo = gu.idGrupo
                AND p.z_sga_programas_id = gp.idPrograma
                AND eu.idEmpresa = '$idEmpresa'
            ";

        

        // Se a variavel $search não estiver vazia o where no select
        if($search != ''):
            $sql .= " AND g.idLegGrupo LIKE '%$search%'";
            $sql .= " OR g.descAbrev LIKE '%$search%'";
            $sql .= " OR p.cod_programa LIKE '%$search%'";
            $sql .= " OR p.descricao_programa LIKE '%$search%'";
            $sql .= " OR p.descricao_rotina LIKE '%$search%'";            
        endif;

        $sql .= "
            GROUP BY
                p.cod_programa ";

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

    /**
     * Responsável por retornar o total de registros encontrados na tabela     
     * @param $search String contendo o filtro
     * @param $fields Array com campos a filtrar          
     * @return mixed Retorna o total de registros encontrados
     */
    public function getCountTableProgFoto($search, $fields, $idUsuario, $idEmpresa){
        $dados = array();        
        $sql = "
            SELECT
                count(DISTINCT p.cod_programa) as total
            FROM
                z_sga_usuarios AS u,
                z_sga_usuario_empresa AS eu,
                z_sga_grupos AS gu,
                z_sga_grupo AS g,
                z_sga_grupo_programa AS gp,
                z_sga_programas AS p
            WHERE u.z_sga_usuarios_id = '$idUsuario'
                AND p.cod_programa = p.procedimento_pai /* Apresenta apenas programas principais */
                AND eu.idUsuario = u.z_sga_usuarios_id
                AND gu.idUsuario = u.z_sga_usuarios_id
                AND g.idGrupo = gu.idGrupo
                /* AND g.idGrupo <> '13' */
                AND g.idEmpresa = eu.idEmpresa
                AND gp.idGrupo = gu.idGrupo
                AND p.z_sga_programas_id = gp.idPrograma
                AND eu.idEmpresa = '$idEmpresa'
            ";

        

        // Se a variavel $search não estiver vazia o where no select
        if($search != ''):
            $sql .= " AND g.idLegGrupo LIKE '%$search%'";
            $sql .= " OR g.descAbrev LIKE '%$search%'";
            $sql .= " OR p.cod_programa LIKE '%$search%'";
            $sql .= " OR p.descricao_programa LIKE '%$search%'";
            $sql .= " OR p.descricao_rotina LIKE '%$search%'";            
        endif;

        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0):
            $dados = $sql->fetchAll();
        endif;

        return $dados[0]['total'];
    }








      public function carregaProgramasUsuario($idUsuario,$idEmpresa){        
            $sql = "SELECT
                   u.z_sga_usuarios_id, 
                   u.cod_usuario, 
                   u.nome_usuario, 
                   GROUP_CONCAT(DISTINCT concat_ws(' - ' , g.idLegGrupo, g.descAbrev) 
                            ORDER BY g.idLegGrupo
                            SEPARATOR ' <br> ') AS grupos,
                   #g.descAbrev,
                   #g.idLegGrupo, 
                   p.cod_programa,p.descricao_programa,p.descricao_rotina,p.ajuda_programa,p.especific
                  FROM
                  z_sga_usuarios AS u,
                  z_sga_usuario_empresa AS eu,
                  z_sga_grupos_foto AS gu,
                z_sga_grupo_foto AS g,
                z_sga_grupo_programa_foto AS gp,
                  z_sga_programas AS p
                  WHERE u.z_sga_usuarios_id = '$idUsuario'
                  AND p.cod_programa = p.procedimento_pai /* Apresenta apenas programas principais */
                  AND eu.idUsuario = u.z_sga_usuarios_id
                  AND gu.idUsuario = u.z_sga_usuarios_id
                  AND g.idGrupo = gu.idGrupo
                  /* AND g.idGrupo <> '13' */
                  AND g.idEmpresa = eu.idEmpresa
                  AND gp.idGrupo = gu.idGrupo
                  AND p.z_sga_programas_id = gp.idPrograma
                  AND eu.idEmpresa = '$idEmpresa'
                GROUP BY
                    p.cod_programa";

            $sql = $this->db->query($sql);
            $array = array();
            if($sql->rowCount()>0){
                  $array = $sql->fetchAll();
            }
            return $array;    

      }

      public function carregaAbaPessoaisUsuario($search, $orderColumn, $orderDir, $offset, $limit, $usuario, $idEmpresa)
    {
        
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
        INNER JOIN
            z_sga_grupos zsgs
            ON zsgs.idGrupo=g.idGrupo
        WHERE
            pe.idEmpresa = $idEmpresa
            AND zsgs.idUsuario IN(".$usuario.")
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

    public function getCountTableAbaPessoais($table, $search, $fields, $join, $idEmpresa = '', $usuario){
       
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
              ON zslc.id=zslcp.id_campo          
          INNER JOIN
              z_sga_grupos zsgs
              ON zsgs.idGrupo=g.idGrupo";

       // Se a variavel $search não estiver vazia limita cria o where no select
       if($search != '' && count($fields) > 0):
           $i = 0;
           foreach($fields as $val):
               $where .= (($i == 0) ? ' WHERE ' : ' OR ') . "$val LIKE '%$search%'";
               $i++;
           endforeach;
       endif;

       $sql .= $where;
     
       $sql .= (($where == '') ? ' WHERE ' : ' AND ') . " e.idEmpresa = $idEmpresa AND zsgs.idUsuario IN(".$usuario.") AND (if(zslc.`sensitive`=0,'Sim','Não')='Sim')";                        
       
       $sql = $this->db->query($sql);

       if($sql->rowCount() > 0):
           $dados = $sql->fetchAll();
       endif;

       return $dados[0]['total'];
   }

   public function carregaAbaSensiveisUsuario($search, $orderColumn, $orderDir, $offset, $limit, $usuario, $idEmpresa)
    {
        
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
        INNER JOIN
            z_sga_grupos zsgs
            ON zsgs.idGrupo=g.idGrupo
        WHERE
            pe.idEmpresa = $idEmpresa
            AND zsgs.idUsuario IN(".$usuario.")
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

    public function getCountTableAbaSensiveis($table, $search, $fields, $join, $idEmpresa = '', $usuario){
       
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
              ON zslc.id=zslcp.id_campo
          INNER JOIN
              z_sga_grupos zsgs
              ON zsgs.idGrupo=g.idGrupo";

       // Se a variavel $search não estiver vazia limita cria o where no select
       if($search != '' && count($fields) > 0):
           $i = 0;
           foreach($fields as $val):
               $where .= (($i == 0) ? ' WHERE ' : ' OR ') . "$val LIKE '%$search%'";
               $i++;
           endforeach;
       endif;

       $sql .= $where;
     
       $sql .= (($where == '') ? ' WHERE ' : ' AND ') . " e.idEmpresa = $idEmpresa AND zsgs.idUsuario IN(".$usuario.") AND (if(zslc.`sensitive`=1,'Sim','Não')='Sim')";                        
       
       $sql = $this->db->query($sql);

       if($sql->rowCount() > 0):
           $dados = $sql->fetchAll();
       endif;

       return $dados[0]['total'];
   }

      public function carregaAbaAnonizadosUsuario($search, $orderColumn, $orderDir, $offset, $limit, $usuario, $idEmpresa)
    {        
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
        INNER JOIN
            z_sga_grupos zsgs
            ON zsgs.idGrupo=g.idGrupo
        WHERE
            pe.idEmpresa = $idEmpresa
            AND zsgs.idUsuario IN(".$usuario.")
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

    public function getCountTableAbaAnonizados($table, $search, $fields, $join, $idEmpresa = '', $usuario){
       
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
              ON zslc.id=zslcp.id_campo
          INNER JOIN
               z_sga_grupos zsgs
               ON zsgs.idGrupo=g.idGrupo";

       // Se a variavel $search não estiver vazia limita cria o where no select
       if($search != '' && count($fields) > 0):
           $i = 0;
           foreach($fields as $val):
               $where .= (($i == 0) ? ' WHERE ' : ' OR ') . "$val LIKE '%$search%'";
               $i++;
           endforeach;
       endif;

       $sql .= $where;
     
       $sql .= (($where == '') ? ' WHERE ' : ' AND ') . " e.idEmpresa = $idEmpresa AND zsgs.idUsuario IN(".$usuario.") AND (if(zslc.`anonymize`=1,'Sim','Não')='Sim')";                        
       
       $sql = $this->db->query($sql);

       if($sql->rowCount() > 0):
           $dados = $sql->fetchAll();
       endif;

       return $dados[0]['total'];
   }

      public function carregaProgramasDuplicado($idUsuario,$idEmpresa){
            $sql = "SELECT 
                        g.idEmpresa,
                        u.z_sga_usuarios_id,
                        p.cod_programa,
                        p.descricao_programa,
                     p.descricao_rotina,
                        p.descricao_modulo,
                        p.especific,                        
                        GROUP_CONCAT(DISTINCT concat_ws(' - ' , g.idLegGrupo, g.descAbrev) 
                            ORDER BY g.idLegGrupo
                            SEPARATOR ' <br> ') AS grupos
                    FROM
                        z_sga_usuarios AS u,
                        z_sga_usuario_empresa AS eu,
                        z_sga_grupos AS gu,
                        z_sga_grupo AS g,
                        z_sga_grupo_programa AS gp,
                        z_sga_programas AS p
                    WHERE  u.z_sga_usuarios_id = '$idUsuario'
                     AND 
                      eu.idEmpresa = '$idEmpresa'
                       AND 
                      p.cod_programa = p.procedimento_pai
                      AND eu.idUsuario = u.z_sga_usuarios_id
                      AND gu.idUsuario = u.z_sga_usuarios_id
                      AND g.idGrupo = gu.idGrupo
                      AND g.idEmpresa = eu.idEmpresa
                      AND gp.idGrupo = gu.idGrupo
                      AND p.visualiza_menu LIKE 'yes'
                      AND p.z_sga_programas_id = gp.idPrograma
                    GROUP by g.idEmpresa, u.z_sga_usuarios_id, gp.cod_programa
                    HAVING COUNT(gp.cod_programa) > 1";

            $sql = $this->db->query($sql);
            
            $array = array();
            if($sql->rowCount()>0){
                  $array = $sql->fetchAll();
            }
            return $array;

      }

      public function carregaProgramasDuplicadoFoto($idUsuario,$idEmpresa){
            $sql = "SELECT 
                        g.idEmpresa,
                        u.z_sga_usuarios_id,
                        p.cod_programa,
                        p.descricao_programa,
                        p.descricao_rotina,
                        p.descricao_modulo,
                        GROUP_CONCAT(DISTINCT concat_ws(' - ' , g.idLegGrupo, g.descAbrev) 
                            ORDER BY g.idLegGrupo
                            SEPARATOR ' <br> ') AS grupos,
                        p.especific
                    FROM
                        z_sga_usuarios AS u,
                        z_sga_usuario_empresa AS eu,
                        z_sga_grupos_foto AS gu,
                        z_sga_grupo_foto AS g,
                        z_sga_grupo_programa AS gp,
                        z_sga_programas AS p
                    WHERE  u.z_sga_usuarios_id = '$idUsuario'
                     AND 
                      eu.idEmpresa = '$idEmpresa'
                       AND 
                      p.cod_programa = p.procedimento_pai
                      AND eu.idUsuario = u.z_sga_usuarios_id
                      AND gu.idUsuario = u.z_sga_usuarios_id
                      AND g.idGrupo = gu.idGrupo
                      AND g.idEmpresa = eu.idEmpresa
                      AND gp.idGrupo = gu.idGrupo
                      AND p.z_sga_programas_id = gp.idPrograma
                      AND p.visualiza_menu LIKE 'yes'
                    GROUP by g.idEmpresa, u.z_sga_usuarios_id, gp.cod_programa
                    HAVING COUNT(gp.cod_programa) > 1";

            $sql = $this->db->query($sql);
            $array = array();
            if($sql->rowCount()>0){
                  $array = $sql->fetchAll();
            }
            return $array;

      }


    public function carregaConflitos($id,$conta){
      $idEmpresa = $_SESSION['empresaid'];
      if(empty($conta)){
         $sql = "SELECT bmusr.empGrupo as idEmpresa,
           bmusr.idUsuario,
       usr.nome_usuario,
           area.descricao as descArea,
       mtzr.codRisco,
       mtzr.descricao as descRisco,
       gr.descricao as grau,
       IF((SELECT idRisco FROM z_sga_mtz_mitigacao_risco mr WHERE mr.idRisco = mtzr.idMtzRisco LIMIT 1) IS NOT NULL, 'Mitigado' , 'Não mitigado') as mitigado,
           gr.background as bgcolor,
           gr.texto as fgcolor,
          -- bmusr.idGrupo,
       mtzp.descProcesso as processoPri,    
           group_concat(distinct bmusr.cod_programa order by bmusr.cod_programa separator ' | ')
            as progspPri, 
      (select count(mtapsp.idPrograma) from z_sga_mtz_apps_processo mtapsp Where mtapsp.idProcesso = cp.idProcessoPrim)  as nroAppsPPrinc,
          (select mtzpi.descProcesso from z_sga_mtz_processo mtzpi where mtzpi.idProcesso = cp.idProcessoSec) as processoSec,
          (select group_concat(distinct bmusri.cod_programa separator ' | ') 
       from v_sga_mtz_base_matriz_usuario bmusri where bmusri.idProcesso = cp.idProcessoSec  
                                                         and bmusri.idUsuario = bmusr.idUsuario
                                                         and bmusri.empGrupo  = bmusr.empGrupo
                             group by bmusri.idProcesso) 
        as progspSec,
     (select count(mtapsp.idPrograma) from z_sga_mtz_apps_processo mtapsp Where mtapsp.idProcesso = cp.idProcessoSec)  as nroAppsPSec
    from z_sga_mtz_coorelacao_processo cp,
       v_sga_mtz_base_matriz_usuario bmusr,
       z_sga_usuarios usr,
       z_sga_mtz_grau_risco gr,
       z_sga_mtz_processo mtzp,
           z_sga_mtz_risco mtzr,
           z_sga_mtz_area area
    where (cp.idProcessoPrim = bmusr.idProcesso
            and cp.idProcessoSec in (select bmusri.idProcesso from v_sga_mtz_base_matriz_usuario bmusri where bmusri.idProcesso = cp.idProcessoSec and bmusri.idUsuario = bmusr.idUsuario )
             )
        and gr.idGrauRisco = cp.idGrauRisco
    and mtzp.idProcesso = cp.idProcessoPrim
    and mtzr.idMtzRisco = mtzp.idMtzRisco
        and usr.z_sga_usuarios_id = bmusr.idUsuario
        and area.idArea = mtzr.idArea
        -- filtros aqui
    and bmusr.idUsuario = '$id'
        and bmusr.empGrupo = '$idEmpresa'
       group by area.descricao, cp.idProcessoPrim, cp.idProcessoSec";



         
         $sql = $this->db->query($sql);
            $array = array();
            if($sql->rowCount()>0){
                  $array = $sql->fetchAll();
            }
            return $array;
      }else{
         $sql = "SELECT count(codRisco) as total FROM v_sga_mtz_matriz_usuario where idUsuario = '$id' and idEmpresa = '$idEmpresa' order by descArea,codRisco,processoPri";
         $sql = $this->db->query($sql);
            $array = array();
            if($sql->rowCount()>0){
                  $array = $sql->fetch();
            }
            return $array;
      }
     
    }

  public function carregaConflitosFoto($id){
      $idEmpresa = $_SESSION['empresaid'];
 
         $sql = "SELECT bmusr.empGrupo as idEmpresa,
            bmusr.idUsuario,
            usr.nome_usuario,
            area.descricao as descArea,
            mtzr.codRisco,
            mtzr.descricao as descRisco,
            gr.descricao as grau,
            gr.background as bgcolor,
            gr.texto as fgcolor,
            -- bmusr.idGrupo,
            mtzp.descProcesso as processoPri,    
            IF((SELECT idRisco FROM z_sga_mtz_mitigacao_risco mr WHERE mr.idRisco = mtzr.idMtzRisco LIMIT 1) IS NOT NULL, 'Mitigado' , 'Não mitigado') as mitigado,
           group_concat(distinct bmusr.cod_programa order by bmusr.cod_programa separator ' | ')
            as progspPri, 
          (select count(mtapsp.idPrograma) from z_sga_mtz_apps_processo mtapsp Where mtapsp.idProcesso = cp.idProcessoPrim)  as nroAppsPPrinc,
          (select mtzpi.descProcesso from z_sga_mtz_processo mtzpi where mtzpi.idProcesso = cp.idProcessoSec) as processoSec,
          (select group_concat(distinct bmusri.cod_programa separator ' | ') 
          from v_sga_mtz_base_matriz_usuario_foto bmusri where bmusri.idProcesso = cp.idProcessoSec  
                                                         and bmusri.idUsuario = bmusr.idUsuario
                                                         and bmusri.empGrupo  = bmusr.empGrupo
          group by bmusri.idProcesso) 
          as progspSec,
          (select count(mtapsp.idPrograma) from z_sga_mtz_apps_processo mtapsp Where mtapsp.idProcesso = cp.idProcessoSec)  as nroAppsPSec
          from z_sga_mtz_coorelacao_processo cp,
          v_sga_mtz_base_matriz_usuario_foto bmusr,
          z_sga_usuarios usr,
          z_sga_mtz_grau_risco gr,
          z_sga_mtz_processo mtzp,
          z_sga_mtz_risco mtzr,
          z_sga_mtz_area area
          where (cp.idProcessoPrim = bmusr.idProcesso
            and cp.idProcessoSec in (select bmusri.idProcesso from v_sga_mtz_base_matriz_usuario_foto bmusri where bmusri.idProcesso = cp.idProcessoSec and bmusri.idUsuario = bmusr.idUsuario )
             )
          and gr.idGrauRisco = cp.idGrauRisco
          and mtzp.idProcesso = cp.idProcessoPrim
          and mtzr.idMtzRisco = mtzp.idMtzRisco
          and usr.z_sga_usuarios_id = bmusr.idUsuario
          and area.idArea = mtzr.idArea
          -- filtros aqui
          and bmusr.idUsuario = '$id'
          and bmusr.empGrupo = '$idEmpresa'
          group by area.descricao, cp.idProcessoPrim, cp.idProcessoSec";



         
         $sql = $this->db->query($sql);
            $array = array();
            if($sql->rowCount()>0){
                  $array = $sql->fetchAll();
            }
            return $array;
   
     
    }

    public function carregaConflitosProcesso($id){
      $idEmpresa = $_SESSION['empresaid'];
      $sql = " SELECT 
              `base`.`empGrupo` AS `empresa`,
              `base`.`idUsuario` AS `idUsuario`,
              `usr`.`cod_usuario` AS `cod_usuario`,
              `proc`.`descProcesso` AS `descProcesso`,
              `gpross`.`descricao` AS `GrupoProcesso`,
              `base`.`cod_programa` AS `cod_programa`,
              `prog`.`ajuda_programa` AS `ajuda_programa`,
              `prog`.`descricao_programa` AS `descricao_programa`,
              GROUP_CONCAT(DISTINCT `grp`.`idLegGrupo`
                  SEPARATOR ' | ') AS `Grupos`
          FROM
              (((((`z_sga_usuarios` `usr`
              JOIN `v_sga_mtz_base_matriz_usuario` `base`)
              JOIN `z_sga_mtz_processo` `proc`)
              JOIN `z_sga_mtz_grupo_de_processo` `gpross`)
              JOIN `z_sga_programas` `prog`)
              JOIN `z_sga_grupo` `grp`)
          WHERE
              ((`usr`.`z_sga_usuarios_id` = `base`.`idUsuario`)
                  AND (`proc`.`idProcesso` = `base`.`idProcesso`)
                  AND (`gpross`.`idGrpProcesso` = `proc`.`idGrpProcesso`)
                  AND (`prog`.`z_sga_programas_id` = `base`.`idPrograma`)
                  AND (`grp`.`idGrupo` = `base`.`idGrupo`)
                  AND (`usr`.`z_sga_usuarios_id` = '$id')
                  AND (`base`.`empGrupo` = '$idEmpresa')
                  
                  
                  )
             GROUP BY  `gpross`.`descricao`,`proc`.`descProcesso`,`usr`.`cod_usuario` , `base`.`cod_programa`";
      $sql = $this->db->query($sql);
            $array = array();
            if($sql->rowCount()>0){
                  $array = $sql->fetchAll();
            }
            return $array;
    }

    public function carregaConflitosProcessoFoto($id){
      $idEmpresa = $_SESSION['empresaid'];
      $sql = " SELECT 
              `base`.`empGrupo` AS `empresa`,
              `base`.`idUsuario` AS `idUsuario`,
              `usr`.`cod_usuario` AS `cod_usuario`,
              `proc`.`descProcesso` AS `descProcesso`,
              `gpross`.`descricao` AS `GrupoProcesso`,
              `base`.`cod_programa` AS `cod_programa`,
              `prog`.`ajuda_programa` AS `ajuda_programa`,
              `prog`.`descricao_programa` AS `descricao_programa`,
              GROUP_CONCAT(DISTINCT `grp`.`idLegGrupo`
                  SEPARATOR ' | ') AS `Grupos`
          FROM
              (((((`z_sga_usuarios` `usr`
              JOIN `v_sga_mtz_base_matriz_usuario_foto` `base`)
              JOIN `z_sga_mtz_processo` `proc`)
              JOIN `z_sga_mtz_grupo_de_processo` `gpross`)
              JOIN `z_sga_programas` `prog`)
              JOIN `z_sga_grupo_foto` `grp`)
          WHERE
              ((`usr`.`z_sga_usuarios_id` = `base`.`idUsuario`)
                  AND (`proc`.`idProcesso` = `base`.`idProcesso`)
                  AND (`gpross`.`idGrpProcesso` = `proc`.`idGrpProcesso`)
                  AND (`prog`.`z_sga_programas_id` = `base`.`idPrograma`)
                  AND (`grp`.`idGrupo` = `base`.`idGrupo`)
                  AND (`usr`.`z_sga_usuarios_id` = '$id')
                  AND (`base`.`empGrupo` = '$idEmpresa')
                  
                  
                  )
             GROUP BY  `gpross`.`descricao`,`proc`.`descProcesso`,`usr`.`cod_usuario` , `base`.`cod_programa`";
      $sql = $this->db->query($sql);
            $array = array();
            if($sql->rowCount()>0){
                  $array = $sql->fetchAll();
            }
            return $array;
    }

    public function carregaAcessoModuloFoto($id){
      $idEmpresa = $_SESSION['empresaid'];
      $sql = "SELECT 
                u.z_sga_usuarios_id,
                u.cod_usuario,
                sis.des_sist_dtsul,
                p.descricao_modulo,    
                p.descricao_rotina,
                group_concat(distinct g.idLegGrupo SEPARATOR ' | ') Grupos ,
                count(p.cod_programa) as numProgramas,
                group_concat(distinct concat_ws(';',p.cod_programa,p.descricao_programa) SEPARATOR ' | ') Programas
            FROM
                z_sga_usuarios AS u,
                z_sga_usuario_empresa AS eu,
                z_sga_grupos_foto AS gu,
                z_sga_grupo_foto AS g,
                z_sga_grupo_programa_foto AS gp,
                z_sga_programas AS p,
                z_sga_modul_dtsul as mdl,
                z_sga_sist_dtsul as sis
            WHERE  u.z_sga_usuarios_id = '$id'
                AND p.cod_programa = p.procedimento_pai /* Apresenta apenas programas principais */
                AND eu.idUsuario = u.z_sga_usuarios_id
                AND gu.idUsuario = u.z_sga_usuarios_id
                AND g.idGrupo = gu.idGrupo
                AND g.idEmpresa = eu.idEmpresa
                AND gp.idGrupo = gu.idGrupo
                AND p.z_sga_programas_id = gp.idPrograma
                and mdl.cod_modul_dtsul = p.cod_modulo
                and sis.cod_sist_dtsul = mdl.cod_sist_dtsul
                AND eu.idEmpresa = '$idEmpresa'
            group by u.cod_usuario,p.descricao_modulo, p.descricao_rotina";
      $sql = $this->db->query($sql);
            $array = array();
            if($sql->rowCount()>0){
                  $array = $sql->fetchAll();
            }
            return $array;
    }



    public function carregaAcessoModulo($id){
      $idEmpresa = $_SESSION['empresaid'];
      $sql = "SELECT 
                u.z_sga_usuarios_id,
                u.cod_usuario,
                sis.des_sist_dtsul,
                p.descricao_modulo,    
                p.descricao_rotina,
                group_concat(distinct g.idLegGrupo SEPARATOR ' | ') Grupos ,
                count(p.cod_programa) as numProgramas,
                group_concat(distinct concat_ws(';',p.cod_programa,p.descricao_programa) SEPARATOR ' | ') Programas
            FROM
                z_sga_usuarios AS u,
                z_sga_usuario_empresa AS eu,
                z_sga_grupos AS gu,
                z_sga_grupo AS g,
                z_sga_grupo_programa AS gp,
                z_sga_programas AS p,
                z_sga_modul_dtsul as mdl,
                z_sga_sist_dtsul as sis
            WHERE  u.z_sga_usuarios_id = '$id'
                AND p.cod_programa = p.procedimento_pai /* Apresenta apenas programas principais */
                AND eu.idUsuario = u.z_sga_usuarios_id
                AND gu.idUsuario = u.z_sga_usuarios_id
                AND g.idGrupo = gu.idGrupo
                AND g.idEmpresa = eu.idEmpresa
                AND gp.idGrupo = gu.idGrupo
                AND p.z_sga_programas_id = gp.idPrograma
                and mdl.cod_modul_dtsul = p.cod_modulo
                and sis.cod_sist_dtsul = mdl.cod_sist_dtsul
                AND eu.idEmpresa = '$idEmpresa'
            group by u.cod_usuario,p.descricao_modulo, p.descricao_rotina";
      $sql = $this->db->query($sql);
            $array = array();
            if($sql->rowCount()>0){
                  $array = $sql->fetchAll();
            }
            return $array;
    }

    /**
     * Carrega todos usuários ativos ou inativos
     * @param $ativo Situação para filtro 1 = Ativo, 0 = Inativo
     * @param $nome String para filtrar pelo nome
     * @param $idGrupo
     * @return type
     */
    public function carregaUsuarios($ativo, $nome = '', $eliminar = '', $idGrupo)
    {
        $sql = "
            SELECT
                u.z_sga_usuarios_id AS idUsuario,
                u.nome_usuario,
                u.cod_usuario
            FROM 
                z_sga_usuarios u
            LEFT JOIN
                z_sga_usuario_empresa e
                ON e.idUsuario = u.z_sga_usuarios_id
            WHERE                
                e.ativo = $ativo
                AND u.nome_usuario LIKE '$nome%'
                AND u.z_sga_usuarios_id NOT IN(SELECT idUsuario FROM z_sga_grupos gs WHERE gs.idGrupo = $idGrupo)".
                ((!empty($eliminar)) ? " AND u.z_sga_usuarios_id NOT IN($eliminar) " : '')
                ."AND e.idEmpresa = ". $_SESSION['empresaid'];
        
        //echo "<pre>";
        //die($sql);
        
        try{
            $rsDados = $this->db->query($sql);
            
            if($rsDados->rowCount() > 0):     
                return $rsDados->fetchAll(PDO::FETCH_ASSOC);
            else:
                return array();
            endif;
            
            
        } catch (Exception $e) {
            return array(
                'return' => false,
                'error'  => $e->getMessage()
            );
        }
    }
    
    /**
     * Carrega todos usuários relacionados com grupo para tela de manutenção de grupos  
     * @param $idGrupo
     * @return type
     */
    public function carregaUsuariosAdicionados($idGrupo)
    {
        $sql = "
            SELECT
                u.z_sga_usuarios_id AS idUsuario,
                u.nome_usuario    
            FROM 
                z_sga_usuarios u
            LEFT JOIN
                z_sga_usuario_empresa e
                ON e.idUsuario = u.z_sga_usuarios_id
            WHERE                
                u.z_sga_usuarios_id IN(SELECT idUsuario FROM z_sga_grupos gs WHERE gs.idGrupo = $idGrupo) 
                AND e.idEmpresa = ". $_SESSION['empresaid'];
        //echo "<pre>";
        //die($sql);
        try{
            $rsDados = $this->db->query($sql);
            
            if($rsDados->rowCount() > 0):                
                return $rsDados->fetchAll(PDO::FETCH_ASSOC);
            else:
                return array();
            endif;
            
            
        } catch (Exception $e) {
            return array(
                'return' => false,
                'error'  => $e->getMessage()
            );
        }
    }


  public function carregaMenu($idGrp)
  {
    $sql = "
      SELECT 
        a.idMenu, 
        a.descricao, 
        a.url,
        c.descricao as subCat,
        d.descricao as cat,
        (SELECT 1 
        FROM 
          z_sga_param_grupo_permissao as b 
        WHERE 
          idGrupo = $idGrp AND 
          b.url = a.idMenu LIMIT 1) as Ativa

      FROM 
        z_sga_param_menu as a,
        z_sga_param_sub_categoria as c,
        z_sga_param_categoria as d
      WHERE
        a.idSubCategoria = c.idSubCategoria AND
        c.idCategoria = d.idCategoria
      ORDER BY
        d.descricao, c.descricao ASC";
    
    $sql = $this->db->query($sql);
    $dados = array();

    if ($sql->rowCount() > 0) {
      $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    return $dados;
  }

  public function carregaPerfis()
  {
    // Prepara o select
    $sql = "SELECT idGrupo, descricao FROM z_sga_param_grupo";

    // Executa o select
    $sql = $this->db->query($sql);

    // Checa se houve resultados
    if ($sql->rowCount() > 0) {
      $res = $this->checaExcluirPerfiL();

      // Retorna os resultados caso hajam
      $data = $sql->fetchAll(PDO::FETCH_ASSOC);
      
      return array($data, $res);
    }
  }

  public function editaPerfil($grupo, $name, $permitidos)
  {
    $update = "UPDATE z_sga_param_grupo SET descricao = '$name' WHERE idGrupo = $grupo";
    $delete = "DELETE FROM z_sga_param_grupo_permissao WHERE idGrupo = $grupo";
    $insert = "INSERT INTO z_sga_param_grupo_permissao (url, idGrupo) VALUES ";

    // Prepara o insert
    foreach ($permitidos as $key => $value) {
      $insert .= "($value, $grupo)";
  
      if (isset($permitidos[$key+1])) {
        $insert .= ', ';
      }
    }

    // die($insert);

    try {
      $this->db->query($update);
      $this->db->query($delete);
      $this->db->query($insert);
      
      return array('return' => true, 'message' => 'Perfil editado com sucesso!');
    }
    catch (Exception $e) {
      return array('return' => true, 'error' => $e->getMessage(), 'message' => 'Perfil editado com sucesso!');
    }
  }

  public function addPerfil($name, $permitidos)
  {
    // Prepara o insert
    $insertGroup = "INSERT INTO z_sga_param_grupo (descricao) VALUES ('$name')";
    $insertPermi = "";

    if (!empty($permitidos)) {
      $this->db->query($insertGroup);

      $id = $this->db->lastInsertId();    
      $insertPermi = "INSERT INTO z_sga_param_grupo_permissao (url, idGrupo) VALUES ";

      // Prepara o insert
      foreach ($permitidos as $key => $value) {
        $insertPermi .= "($value, $id)";
  
        if (isset($permitidos[$key+1])) {
          $insertPermi .= ', ';
        }
      }

      try {
        // Executa o insert
        $this->db->query($insertPermi);

        // Retorna para o controller
        return array('return' => true, 'message' => 'Perfil adicionado com sucesso!');
      } catch (Exception $e) {
        // Retorna o erro para o controller
        return array('return' => false, 'error' => $e->getMessage(), 'message' => 'Ocorreu o seguinte erro: ');
      }
    }

    // Tenta executar
    try {
      // Executa o insert
      $this->db->query($insertGroup);

      // Retorna para o controller
      return array('return' => true, 'message' => 'Perfil adicionado com sucesso!');
    } catch (Exception $e) {
      // Retorna o erro para o controller
      return array('return' => false, 'error' => $e->getMessage(), 'message' => 'Ocorreu o seguinte erro: ');
    }
  }

  public function excluirPerfil($perfil)
  {
    // Prepara o  delete
    $sql = "DELETE FROM z_sga_param_grupo WHERE idGrupo = $perfil";

    // Tenta executar
    try {
      // Executa o delete
      $this->db->query($sql);

      // Retorna para o controller
      return array('return' => true, 'message' => 'Perfil excluido com sucesso');
    } 
    catch (Exception $e) {
      return array('return' => false, 'error' => $e->getMessage(), 'message' => 'Ocorreu o seguinte erro: ');
    }
  }

  public function checaExcluirPerfil()
  {
    // Variaveis
    $res = array();

    // Prepara os selects
    $selectPermissao = "SELECT idGrupo FROM z_sga_param_grupo_permissao GROUP BY idGrupo";
    $selectGrpUsr = "SELECT idGrupo FROM z_sga_param_grupo_usuario GROUP BY idGrupo";

    // Tenta executar
    try {
      // Executa os selects
      $selectPermissao = $this->db->query($selectPermissao);
      $selectGrpUsr = $this->db->query($selectGrpUsr);

      // Faz o row count deles
      if ($selectPermissao->rowCount() > 0) {
        $res['selectPermissao'] = $selectPermissao->fetchAll(PDO::FETCH_ASSOC);
      }
      else {
        $res['selectPermissao'] = false;
      }

      if ($selectGrpUsr->rowCount() > 0) {
        $res['selectGrpUsr'] = $selectGrpUsr->fetchAll(PDO::FETCH_ASSOC);
      }
      else {
        $res['selectGrpUsr'] = false;
      }
      
      // Retorna para o controller
      return $res;
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }
}