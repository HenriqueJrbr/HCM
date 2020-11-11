
<?php
class Home extends Model {

    public function __construct(){
      	parent::__construct();
      }

    public function contaUsuario($idEmpresa){
            $sql = "SELECT count(idUsrEMp) as total from 
            z_sga_usuario_empresa as emp,
            z_sga_usuarios as usr
            where idEmpresa = '$idEmpresa' and emp.idUsuario = usr.z_sga_usuarios_id and emp.ativo = 1 ";
            $sql = $this->db->query($sql);
            $array = array();
            if($sql->rowCount()>0){
                  $array = $sql->fetch();
            }
            return $array;
      }

       public function contaUsuarioTotal($idEmpresa){
            $sql = "SELECT count(idUsrEMp) as total from 
            z_sga_usuario_empresa as emp,
            z_sga_usuarios as usr
            where idEmpresa = '$idEmpresa' and emp.idUsuario = usr.z_sga_usuarios_id ";
            $sql = $this->db->query($sql);
            $array = array();
            if($sql->rowCount()>0){
                  $array = $sql->fetch();
            }
            return $array;
      }

      public function contaUsuarioInativos($idEmpresa){
            $sql = "SELECT count(idUsrEMp) as total from 
            z_sga_usuario_empresa as emp,
            z_sga_usuarios as usr
            where idEmpresa = '$idEmpresa' and emp.idUsuario = usr.z_sga_usuarios_id and emp.ativo = 0 ";
            $sql = $this->db->query($sql);
            $array = array();
            if($sql->rowCount()>0){
                  $array = $sql->fetch();
            }
            return $array;
      }
      
      public function totalExpostoRisco(){
             $idEmpresa = $_SESSION['empresaid'];
             $sql = "
                 SELECT 
                    usem.idEmpresa,
                    (SELECT 
                        count(DISTINCT vmu.idUsuario) 
                    FROM 
                        z_sga_vm_usuarios_processos_riscos vmu 
                    LEFT JOIN
                        z_sga_usuarios u
                        ON vmu.idUsuario = u.z_sga_usuarios_id
                    LEFT JOIN
                        z_sga_usuario_empresa e
                        ON e.idUsuario = u.z_sga_usuarios_id
                    WHERE
                        e.ativo = 1
                        AND vmu.idEmpresa = $idEmpresa
                    ) as 'expostoArisco'	  
                FROM 
                    z_sga_usuario_empresa usem
                WHERE 
                    usem.idEmpresa = $idEmpresa
                    AND usem.ativo = 1
                    group by usem.idEmpresa
             ";

            /*$sql = " SELECT usem.idEmpresa,
              (Select count(x.idUsuario) from (select distinct idEmpresa, idUsuario  from v_sga_mtz_resumo_matriz_usuario ) x where x.idEmpresa = usem.idEmpresa) as 'expostoArisco'           
                FROM z_sga_usuario_empresa usem where usem.idEmpresa = '$idEmpresa'
                    group by usem.idEmpresa";*/
            $sql = $this->db->query($sql);
            $array = array();
            if($sql->rowCount()>0){
                  $array = $sql->fetch();
            }
            return $array;
      }


      public function totalExpostoRiscoFoto(){
        $idEmpresa = $_SESSION['empresaid'];
        $sql = "
            SELECT 
               usem.idEmpresa,
               (SELECT 
                   count(DISTINCT vmu.idUsuario) 
               FROM 
                   z_sga_vm_usuarios_processos_riscos_foto vmu 
               LEFT JOIN
                   z_sga_usuarios u
                   ON vmu.idUsuario = u.z_sga_usuarios_id
               LEFT JOIN
                   z_sga_usuario_empresa e
                   ON e.idUsuario = u.z_sga_usuarios_id
               WHERE
                   e.ativo = 1
                   AND vmu.idEmpresa = $idEmpresa
               ) as 'expostoArisco'	  
           FROM 
               z_sga_usuario_empresa usem
           WHERE 
               usem.idEmpresa = $idEmpresa
               AND usem.ativo = 1
               group by usem.idEmpresa
        ";

       /*$sql = " SELECT usem.idEmpresa,
         (Select count(x.idUsuario) from (select distinct idEmpresa, idUsuario  from v_sga_mtz_resumo_matriz_usuario ) x where x.idEmpresa = usem.idEmpresa) as 'expostoArisco'           
           FROM z_sga_usuario_empresa usem where usem.idEmpresa = '$idEmpresa'
               group by usem.idEmpresa";*/
       $sql = $this->db->query($sql);
       $array = array();
       if($sql->rowCount()>0){
             $array = $sql->fetch();
       }
       return $array;
 }





      public function totalExpostoRiscoInativo(){
             $idEmpresa = $_SESSION['empresaid'];
             /*$sql = "
                 SELECT 
                    usem.idEmpresa,
                    (SELECT 
                        count(DISTINCT vmu.idUsuario) 
                    FROM 
                        z_sga_vm_usuarios_processos_riscos vmu 
                    LEFT JOIN
                        z_sga_usuarios u
                        ON vmu.idUsuario = u.z_sga_usuarios_id
                    WHERE
                        usem.ativo = 0
                        AND vmu.idEmpresa = $idEmpresa
                    ) as 'expostoArisco'      
                FROM 
                    z_sga_usuario_empresa usem
                WHERE 
                    usem.idEmpresa = '$idEmpresa'
                    AND usem.ativo = 0
                    group by usem.idEmpresa
             ";*/
             $sql = "                
                SELECT 
                    count(DISTINCT vmu.idUsuario) AS expostoArisco
                FROM 
                    z_sga_vm_usuarios_processos_riscos vmu
                WHERE                   
                    vmu.idEmpresa = $idEmpresa
                    AND vmu.idUsuario IN(SELECT idUsuario FROM z_sga_usuario_empresa e WHERE e.ativo = 0 AND e.idUsuario = vmu.idUsuario) 
             ";

            /*$sql = " SELECT usem.idEmpresa,
              (Select count(x.idUsuario) from (select distinct idEmpresa, idUsuario  from v_sga_mtz_resumo_matriz_usuario ) x where x.idEmpresa = usem.idEmpresa) as 'expostoArisco'           
                FROM z_sga_usuario_empresa usem where usem.idEmpresa = '$idEmpresa'
                    group by usem.idEmpresa";*/
            $sql = $this->db->query($sql);
            $array = array();
            if($sql->rowCount()>0){
                  $array = $sql->fetch();
            }
            return $array;
      }


     


      public function contaGrupo($idEmpresa){
            $sql = "SELECT count(idGrupo) as total from z_sga_grupo where idEmpresa = '$idEmpresa'";
            $sql = $this->db->query($sql);
            $array = array();
            if($sql->rowCount()>0){
                  $array = $sql->fetch();
            }
            return $array;
      }

        public function contaPrograma($idEmpresa){
            $sql = "
				SELECT 
					COUNT(z_sga_programas_id) AS total
				FROM
					z_sga_programas p
				LEFT JOIN
					z_sga_programa_empresa e
					ON e.idPrograma = p.z_sga_programas_id
				WHERE
					e.idEmpresa = $idEmpresa";
            $sql = $this->db->query($sql);
            $array = array();
            if($sql->rowCount()>0){
                  $array = $sql->fetch();
            }
            return $array;
      }



      public function carregaEmpresa(){
      	$sql = "SELECT * FROM z_sga_empresa";
      	$sql = $this->db->query($sql);

      	$array = array();
      	if($sql->rowCount()>0){
      		$array = $sql->fetchAll();
      	}
      	return $array;
      }

      public function carregaDescEmpresa($empresaId){
            $sql = "SELECT razaoSocial FROM z_sga_empresa where idEmpresa = '$empresaId'";
            $sql = $this->db->query($sql);

            $array = array();
            if($sql->rowCount()>0){
                  $array = $sql->fetch();
            }
            return $array;
      }

      public function carregaGrafTopGrupo(){
            $idEmpresa = $_SESSION['empresaid'];
            $sql = "
                SELECT 
                    grupo.idLegGrupo,
                    grupo.descAbrev,
                    #(SELECT count(gp.cod_programa) FROM z_sga_grupo_programa as gp WHERE gp.idGrupo = grupo.idGrupo) as totalProg,
                    (SELECT count(g.idUsuario) from z_sga_grupos as g LEFT JOIN z_sga_usuario_empresa ue ON g.idUsuario = ue.idUsuario WHERE g.idGrupo = grupo.idGrupo AND ue.ativo = 1) as totalUsuario
                FROM 
                    z_sga_grupo AS grupo 
                WHERE 
                    grupo.idEmpresa = $idEmpresa 
                ORDER BY
                    totalUsuario desc limit 10 ";

            $sql = $this->db->query($sql);

            $array = array();
            if($sql->rowCount()>0){
                  $array = $sql->fetchAll();
            }
            return $array;  
      }

    public function carregaGrafTopGrupoFoto(){
        $idEmpresa = $_SESSION['empresaid'];
        $sql = "
            SELECT 
                grupo.idLegGrupo,
                grupo.descAbrev,
                #(SELECT count(gp.cod_programa) FROM z_sga_grupo_programa_foto as gp WHERE gp.idGrupo = grupo.idGrupo) as totalProg,
                (SELECT count(g.idUsuario) from z_sga_grupos_foto as g LEFT JOIN z_sga_usuario_empresa ue ON g.idUsuario = ue.idUsuario WHERE g.idGrupo = grupo.idGrupo AND ue.ativo = 1) as totalUsuario
            FROM 
                z_sga_grupo_foto AS grupo 
            WHERE 
                grupo.idEmpresa = $idEmpresa 
            ORDER BY
                totalUsuario desc limit 10 ";

        $sql = $this->db->query($sql);

        $array = array();
        if($sql->rowCount()>0){
              $array = $sql->fetchAll();
        }
        return $array;  
  }
      
      public function getDadosUsuario($idUsuario){
            $sql = "SELECT * from z_sga_usuarios where z_sga_usuarios_id = $idUsuario";
            $sql = $this->db->query($sql);
            $array = array();
            if($sql->rowCount()>0){
                  $array = $sql->fetch();
            }
            return $array;
      }

    /**
     * Recupera 5 gestores com maior potencial de risco
     * @return type
     */
    public function gestorComMaiorPotencialRisco()
    {
        $idEmpresa = $_SESSION['empresaid'];
        $sql = "
            SELECT 		
                u.z_sga_usuarios_id,
                u.nome_usuario,
                u.cod_usuario,	
                (SELECT ug.nome_usuario FROM z_sga_usuarios ug WHERE ug.cod_usuario = u.cod_gestor) AS gestor,
                (SELECT count(*) FROM z_sga_usuarios ug WHERE ug.cod_gestor = u.cod_usuario) AS numUsuarios
            FROM
                z_sga_usuario_empresa AS userEmp
            INNER JOIN
                z_sga_usuarios AS u ON userEmp.idUsuario = u.z_sga_usuarios_id
            LEFT JOIN
                z_sga_manut_funcao AS m ON u.cod_funcao = m.idFuncao
            LEFT JOIN
                (SELECT 
                    bmusr.empGrupo AS idEmpresa, bmusr.idUsuario, mtzp.idMtzRisco
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
                userEmp.idEmpresa = $idEmpresa
                AND userEmp.ativo = 1
                AND rru.idMtzRisco NOT IN(SELECT idRisco FROM z_sga_mtz_mitigacao_risco)
                AND u.gestor_usuario = 'S'
                AND (SELECT count(*) FROM z_sga_usuarios ug WHERE ug.cod_gestor = u.cod_usuario) > 0
            GROUP BY 
                gestor --u.z_sga_usuarios_id 
            LIMIT 5";
        
        $sql = $this->db->query($sql);
        
        if($sql->rowCount() > 0):
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        else:
            return [];
        endif;
    }

    /**
     * Recupera 5 gestores com maior potencial de risco foto
     * @return type
     */
    public function gestorComMaiorPotencialRiscoFoto()
    {
        $idEmpresa = $_SESSION['empresaid'];
        $sql = "
            SELECT 		
                u.z_sga_usuarios_id,
                u.nome_usuario,
                u.cod_usuario,	
                (SELECT ug.nome_usuario FROM z_sga_usuarios ug WHERE ug.cod_usuario = u.cod_gestor) AS gestor,
                (SELECT count(*) FROM z_sga_usuarios ug WHERE ug.cod_gestor = u.cod_usuario) AS numUsuarios
            FROM
                z_sga_usuario_empresa AS userEmp
            INNER JOIN
                z_sga_usuarios AS u ON userEmp.idUsuario = u.z_sga_usuarios_id
            LEFT JOIN
                z_sga_manut_funcao AS m ON u.cod_funcao = m.idFuncao
            LEFT JOIN
                (SELECT 
                    bmusr.empGrupo AS idEmpresa, bmusr.idUsuario, mtzp.idMtzRisco
                FROM z_sga_mtz_coorelacao_processo cp, 
                    v_sga_mtz_base_matriz_usuario_foto bmusr, 
                    z_sga_usuarios usr, 
                    z_sga_mtz_processo mtzp
                WHERE
                    cp.idProcessoPrim = bmusr.idProcesso
                    AND mtzp.idProcesso = cp.idProcessoPrim
                    AND usr.z_sga_usuarios_id = bmusr.idUsuario
                    GROUP BY bmusr.idUsuario , mtzp.idMtzRisco) AS rru ON rru.idUsuario = userEmp.idUsuario
                    AND rru.idEmpresa = userEmp.idEmpresa
            WHERE
                userEmp.idEmpresa = $idEmpresa
                AND userEmp.ativo = 1
                AND rru.idMtzRisco NOT IN(SELECT idRisco FROM z_sga_mtz_mitigacao_risco)
                AND u.gestor_usuario = 'S'
                AND (SELECT count(*) FROM z_sga_usuarios ug WHERE ug.cod_gestor = u.cod_usuario) > 0
            GROUP BY 
                gestor --u.z_sga_usuarios_id 
            LIMIT 5";
        
        $sql = $this->db->query($sql);
        
        if($sql->rowCount() > 0):
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        else:
            return [];
        endif;
    }
    
    /**
     * Recupera riscos mitigados vs não mitigados
     * @return type
     */
    public function riscosMitigadosVsNaoMitigados()
    {
        $sql = "
            SELECT 
                (SELECT 
                        COUNT(*)
                FROM
                        z_sga_mtz_risco
                WHERE
                        idMtzRisco NOT IN(SELECT idRisco FROM z_sga_mtz_mitigacao_risco)
                ) AS riscoNaoMitigados,
            (SELECT 
                        COUNT(*)
                FROM
                        z_sga_mtz_risco
                WHERE
                        idMtzRisco IN(SELECT idRisco FROM z_sga_mtz_mitigacao_risco)
                ) AS riscoMitigados";
        
        $sql = $this->db->query($sql);
                
        return $sql->fetch(PDO::FETCH_ASSOC);        
    }    
    
    /**
     * Recupera processos mais populosos     
     * @return array
     */
    public function processosMaisPopulosos()
    {
        $idEmpresa = $_SESSION['empresaid'];
        $sql = "
            SELECT                
                processo.descProcesso,                
                (SELECT 
                    COUNT(DISTINCT u.z_sga_usuarios_id)
                FROM
                    z_sga_usuarios u
                LEFT JOIN
                    z_sga_usuario_empresa e
                    ON e.idUsuario = u.z_sga_usuarios_id
                LEFT JOIN
                    z_sga_grupos g
                    ON g.idUsuario = u.z_sga_usuarios_id
                LEFT JOIN
                            z_sga_grupo_programa gp
                    ON gp.idGrupo = g.idGrupo
                LEFT JOIN
                    z_sga_apc_mtz_apps_processo bmu
                    ON bmu.codPrograma = gp.cod_programa
                WHERE
                    bmu.idProcesso = processo.idProcesso
                    AND e.idEmpresa = $idEmpresa
                    AND e.ativo = 1) AS numUsuarios
            FROM
                z_sga_mtz_processo AS processo,
                z_sga_mtz_risco AS risco,
                z_sga_mtz_grupo_de_processo AS grupo
            WHERE
                processo.idMtzRisco = risco.idMtzRisco
                AND processo.idGrpProcesso = grupo.idGrpProcesso
                AND processo.idMtzRisco NOT IN(SELECT idRisco FROM z_sga_mtz_mitigacao_risco)
            GROUP BY
                processo.idProcesso
            ORDER BY
                numUsuarios DESC
            LIMIT
                5";

        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0):
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        else:
            return array();
        endif;
    }

    /**
     * Recupera processos mais populosos foto
     * @return array
     */
    public function processosMaisPopulososFoto()
    {
        $idEmpresa = $_SESSION['empresaid'];
        $sql = "
            SELECT                
                processo.descProcesso,                
                (SELECT 
                    COUNT(DISTINCT u.z_sga_usuarios_id)
                FROM
                    z_sga_usuarios u
                LEFT JOIN
                    z_sga_usuario_empresa e
                    ON e.idUsuario = u.z_sga_usuarios_id
                LEFT JOIN
                    z_sga_grupos_foto g
                    ON g.idUsuario = u.z_sga_usuarios_id
                LEFT JOIN
                    z_sga_grupo_programa_foto gp
                    ON gp.idGrupo = g.idGrupo
                LEFT JOIN
                    z_sga_apc_mtz_apps_processo bmu
                    ON bmu.codPrograma = gp.cod_programa
                WHERE
                    bmu.idProcesso = processo.idProcesso
                    AND e.idEmpresa = $idEmpresa
                    AND e.ativo = 1) AS numUsuarios
            FROM
                z_sga_mtz_processo AS processo,
                z_sga_mtz_risco AS risco,
                z_sga_mtz_grupo_de_processo AS grupo
            WHERE
                processo.idMtzRisco = risco.idMtzRisco
                AND processo.idGrpProcesso = grupo.idGrpProcesso
                AND processo.idMtzRisco NOT IN(SELECT idRisco FROM z_sga_mtz_mitigacao_risco)
            GROUP BY
                processo.idProcesso
            ORDER BY
                numUsuarios DESC
            LIMIT
                5";

        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0):
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        else:
            return array();
        endif;
    }
    
    /**     
     * Recupera areas com maior potencial de risco
     * @return type
     */
    public function areaMaiorPotencialRisco()
    {
        $idEmpresa = $_SESSION['empresaid'];
        $sql = "
            SELECT	
                a.descricao,
                (
                    SELECT 
			            COUNT(DISTINCT vmu.idUsuario) 
                    FROM 
			            #v_sga_mtz_matriz_usuario mu 
                        z_sga_vm_usuarios_processos_riscos vmu
                    LEFT JOIN  
                        z_sga_mtz_risco r
                        ON vmu.idRisco = r.idMtzRisco
                    LEFT JOIN
                        z_sga_usuario_empresa e
                        ON e.idUsuario = vmu.idUsuario AND e.idEmpresa = $idEmpresa
                    WHERE 
                        r.idArea = a.idArea 
                        AND e.ativo = 1
                        AND r.idMtzRisco NOT IN (SELECT idRisco FROM z_sga_mtz_mitigacao_risco)
                ) AS numUsuarios
            FROM
                z_sga_mtz_area a
            GROUP BY
                a.idArea";
        $sql = $this->db->query($sql);
        
        if($sql->rowCount() > 0):
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        else:
            return [];
        endif;
    }

    /**     
     * Recupera areas com maior potencial de risco foto
     * @return type
     */
    public function areaMaiorPotencialRiscoFoto()
    {
        $idEmpresa = $_SESSION['empresaid'];
        $sql = "
            SELECT	
                a.descricao,
                (
                    SELECT 
			            COUNT(DISTINCT vmu.idUsuario) 
                    FROM 
			            #v_sga_mtz_matriz_usuario mu 
                        z_sga_vm_usuarios_processos_riscos_foto vmu
                    LEFT JOIN  
                        z_sga_mtz_risco r
                        ON vmu.idRisco = r.idMtzRisco
                    LEFT JOIN
                        z_sga_usuario_empresa e
                        ON e.idUsuario = vmu.idUsuario AND e.idEmpresa = $idEmpresa
                    WHERE 
                        r.idArea = a.idArea 
                        AND e.ativo = 1
                        AND r.idMtzRisco NOT IN (SELECT idRisco FROM z_sga_mtz_mitigacao_risco)
                ) AS numUsuarios
            FROM
                z_sga_mtz_area a
            GROUP BY
                a.idArea";
        $sql = $this->db->query($sql);
        
        if($sql->rowCount() > 0):
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        else:
            return [];
        endif;
    }
    
    /**
     * Recupera riscos em potencial
     * @return type
     */
    public function riscosEmPotencial()
    {
        $idEmpresa = $_SESSION['empresaid'];
        $sql = "
            SELECT 
                r.codRisco,
                COUNT(DISTINCT vmu.idUsuario) AS numUsuarios
            FROM 
                #v_sga_mtz_matriz_usuario mu 
                z_sga_vm_usuarios_processos_riscos vmu
            LEFT JOIN  
                z_sga_mtz_risco r
                ON vmu.idRisco = r.idMtzRisco
            LEFT JOIN
                z_sga_usuario_empresa e
                ON e.idUsuario = vmu.idUsuario AND e.idEmpresa = $idEmpresa
            WHERE 		
                r.idMtzRisco NOT IN (SELECT idRisco FROM z_sga_mtz_mitigacao_risco)
                AND e.ativo = 1
            GROUP BY
                r.idMtzRisco
            ORDER BY
                numUsuarios DESC
            LIMIT 5";
        
        $sql = $this->db->query($sql);
        
        if($sql->rowCount() > 0):
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        else:
            return [];
        endif;
    }

    /**
     * Recupera riscos em potencial foto
     * @return type
     */
    public function riscosEmPotencialFoto()
    {
        $idEmpresa = $_SESSION['empresaid'];
        $sql = "
            SELECT 
                r.codRisco,
                COUNT(DISTINCT vmu.idUsuario) AS numUsuarios
            FROM 
                #v_sga_mtz_matriz_usuario mu 
                z_sga_vm_usuarios_processos_riscos_foto vmu
            LEFT JOIN  
                z_sga_mtz_risco r
                ON vmu.idRisco = r.idMtzRisco
            LEFT JOIN
                z_sga_usuario_empresa e
                ON e.idUsuario = vmu.idUsuario AND e.idEmpresa = $idEmpresa
            WHERE 		
                r.idMtzRisco NOT IN (SELECT idRisco FROM z_sga_mtz_mitigacao_risco)
                AND e.ativo = 1
            GROUP BY
                r.idMtzRisco
            ORDER BY
                numUsuarios DESC
            LIMIT 5";
        
        $sql = $this->db->query($sql);
        
        if($sql->rowCount() > 0):
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        else:
            return [];
        endif;
    }

  public function favoritar($idUsuario, $idMenu)
  {
    $isFavorite = 0;
    $sql = "SELECT COUNT(*) as total FROM z_sga_param_menu_favorito WHERE idUsuario = $idUsuario AND idMenu = $idMenu";
    $data = array();
    $select = $this->db->query($sql);

    if ($select->rowCount() > 0) {
      $data = $select->fetchAll(PDO::FETCH_ASSOC);
    }

    foreach ($data as $value) {
      if ($value['total'] != 0) { // Desfavorita
        $sql = "DELETE FROM z_sga_param_menu_favorito WHERE idMenu = $idMenu AND idUsuario = $idUsuario";
        $isFavorite = 0;
        break;
      } else { // Favorita
        $sql = "INSERT INTO z_sga_param_menu_favorito (idMenu, idUsuario) VALUES ($idMenu, $idUsuario)";
        $isFavorite = 1;
        break;
      }
    }

    try {
      $this->db->query($sql);
      $return = array("return" => true, "isFavorite" => $isFavorite);
      return $return;
    } catch (Exception $e) {
      return $e;
    }
  }
}