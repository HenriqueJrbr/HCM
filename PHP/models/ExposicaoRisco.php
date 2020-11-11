<?php
class ExposicaoRisco extends Model {

      public function __construct(){
      	parent::__construct();
      }

      public function carregaGestor(){
      	$idEmpresa = $_SESSION['empresaid'];

      	$sql = "
            SELECT 
                usuarios.cod_usuario,
                usuarios.nome_usuario,
                usuarios.idUsrFluig,
                empresaUsr.ativo
            FROM 
                z_sga_usuario_empresa as empresaUsr 
            INNER JOIN
                z_sga_usuarios as usuarios
                on usuarios.z_sga_usuarios_id = empresaUsr.idUsuario 
            WHERE 
                empresaUsr.idEmpresa = '$idEmpresa' 
                and usuarios.gestor_usuario = 'S'";
		    $sql = $this->db->query($sql);

		    $array = array();

        if($sql->rowCount()>0){
              $array = $sql->fetchAll();
        }

        return $array;
      }

      public function carregaGestorPesquisa($idGestor){
        $idEmpresa = $_SESSION['empresaid'];

        $sql = "SELECT * FROM 
          z_sga_usuario_empresa as empresaUsr INNER JOIN
          z_sga_usuarios as usuarios
          on usuarios.z_sga_usuarios_id = empresaUsr.idUsuario where empresaUsr.idEmpresa = '$idEmpresa' and usuarios.gestor_usuario = 'S' and usuarios.nome_usuario LIKE '%".$idGestor."%' ";
        $sql = $this->db->query($sql);

        $array = array();

        if($sql->rowCount()>0){
              $array = $sql->fetchAll();
        }

        return $array;
      }

      public function carregausuarioPesquisa($idUsuario,$idGestor){
        $idEmpresa = $_SESSION['empresaid'];
        if(empty($idGestor)){
          $sql = "SELECT * FROM 
          z_sga_usuario_empresa as empresaUsr INNER JOIN
          z_sga_usuarios as usuarios
          on usuarios.z_sga_usuarios_id = empresaUsr.idUsuario where empresaUsr.idEmpresa = '$idEmpresa' and usuarios.nome_usuario LIKE '%".$idUsuario."%' LIMIT 10 ";
        }else{
           $sql = "SELECT * FROM 
          z_sga_usuario_empresa as empresaUsr INNER JOIN
          z_sga_usuarios as usuarios
          on usuarios.z_sga_usuarios_id = empresaUsr.idUsuario where empresaUsr.idEmpresa = '$idEmpresa' and usuarios.cod_gestor = '$idGestor' and usuarios.nome_usuario LIKE '%".$idUsuario."%' LIMIT 10 ";
        }
        
        $sql = $this->db->query($sql);

        $array = array();

        if($sql->rowCount()>0){
              $array = $sql->fetchAll();
        }

        return $array;
      }


      public function carregaUsuariosGestor($id){
      	$idEmpresa = $_SESSION['empresaid'];

        $sql = "
            SELECT 
                distinct(empresaUsr.idUsuario), 
                usuarios.nome_usuario,
                usuarios.cod_usuario,
                usuarios.idUsrFluig,
                empresaUsr.idUsuario,
                empresaUsr.ativo
            FROM 
                z_sga_usuario_empresa as empresaUsr, 
                z_sga_usuarios as usuarios,
                v_sga_mtz_resumo_matriz_usuario as res
            where 	
                usuarios.z_sga_usuarios_id = empresaUsr.idUsuario 
                and empresaUsr.idEmpresa = '$idEmpresa'
                and usuarios.cod_gestor = '$id'
                and res.idUsuario = empresaUsr.idUsuario";
		    $sql = $this->db->query($sql);

		    $array = array();

        if($sql->rowCount()>0){
              $array = $sql->fetchAll();
        }

        return $array;
      }


      public function carregaDadosGestor($id){
          $idEmpresa = $_SESSION['empresaid'];

        $sql = "SELECT * FROM 
          z_sga_usuario_empresa as empresaUsr INNER JOIN
          z_sga_usuarios as usuarios
          on usuarios.z_sga_usuarios_id = empresaUsr.idUsuario where empresaUsr.idEmpresa = '$idEmpresa'and usuarios.cod_usuario = '$id'";
        $sql = $this->db->query($sql);

        $array = array();

        if($sql->rowCount()>0){
              $array = $sql->fetch();
        }

        return $array;
      }

      public function carregaConflito($id){
      		$idEmpresa = $_SESSION['empresaid'];

      	$sql = "SELECT *
					FROM
   					 z_sga_usrexpostorisco where idUsuario = '$id' and idEmpresa = '$idEmpresa'";
		    $sql = $this->db->query($sql);

		    $array = array();

        if($sql->rowCount()>0){
              $array = $sql->fetchAll();
        }

        return $array;
      }


      public function carregaRiscoUsuario(){
        $idEmpresa = $_SESSION['empresaid'];

        $sql = "SELECT * FROM v_sga_mtz_resumo_matriz_usuario";
        
        $sql = $this->db->query($sql);

        $array = array();

        if($sql->rowCount()>0){
              $array = $sql->fetchAll();
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
    public function carregaDatatableRiscoUsuario($search, $orderColumn, $orderDir, $offset, $limit){
//         $sql  = "
//             SELECT 
//                 mu.idUsuario,
//                 mu.nome_usuario,
//                 mu.descArea,
//                 mu.codrisco,
//                 mu.descRisco,
//                 mu.CombinacoesDoRisco,
//                 IF(e.ativo = 1, 'Ativo', 'Inativo') AS ativo,
//                 IF((SELECT idRisco FROM z_sga_mtz_mitigacao_risco mr WHERE mr.idRisco = m.idMtzRisco LIMIT 1) IS NOT NULL, 'Mitigado' , 'Não mitigado') as mitigado
//             FROM 
//                 v_sga_mtz_resumo_matriz_usuario mu
//             LEFT JOIN
//                 z_sga_mtz_risco m
//                 ON mu.codRisco = m.codRisco
//             LEFT JOIN
//                 z_sga_usuarios u
//                 ON mu.idusuario = u.z_sga_usuarios_id 
//             LEFT JOIN
//                 z_sga_usuario_empresa e
//                 ON e.idUsuario = u.z_sga_usuarios_id
// ";
        $sql = "
            select base.idEmpresa,
                base.idUsuario,
                base.nome_usuario,
                base.descArea,
                base.codrisco,
                base.descRisco,
                ((sum(base.nrAppsPrimUsr) + sum(base.nrAppsSecUsr)) / (sum(base.nroAppsPPrinc)+sum(base.nroAppsPSec))) * 100 as CombinacoesDoRisco,
                IF(base.ativo = 1, 'Ativo', 'Inativo') AS ativo,
                IF((SELECT idRisco FROM z_sga_mtz_mitigacao_risco mr WHERE mr.idRisco = base.idMtzRisco LIMIT 1) IS NOT NULL, 'Mitigado' , 'Não mitigado') as mitigado
        from (
            select bmusr.empGrupo as idempresa,
                        bmusr.idUsuario,
                        usr.nome_usuario,
                        area.descricao as descArea,
                        mtzr.codRisco,
                        mtzr.descricao as descRisco,
                        cp.idProcessoPrim,
                        mtzp.descProcesso as ProcessoPri,    
                    totPcPrim.nrProgsProcUsr as nrAppsPrimUsr,
                    (select count(mtapsp.idPrograma) from z_sga_mtz_apps_processo mtapsp Where mtapsp.idProcesso = cp.idProcessoPrim)  as nroAppsPPrinc,
                    (select mtzpi.descProcesso from z_sga_mtz_processo mtzpi where mtzpi.idProcesso = cp.idProcessoSec) as processoSec, 
                    totPcSec.nrProgsProcUsr as nrAppsSecUsr,
                    (select count(mtapsp.idPrograma) from z_sga_mtz_apps_processo mtapsp Where mtapsp.idProcesso = cp.idProcessoSec)  as nroAppsPSec,                    
                    usrInstancia.ativo,
                    mtzr.idMtzRisco
                from z_sga_usuario_empresa usrInstancia,
                        z_sga_usuarios usr, 
                        v_sga_mtz_base_matriz_usuario bmusr,
                        z_sga_mtz_coorelacao_processo cp,
                        z_sga_mtz_processo mtzp,
                        z_sga_mtz_risco mtzr,
                        z_sga_mtz_area area ,
                        (select a.empGrupo, a.idProcesso, a.idUsuario,  count(cod_programa) as nrProgsProcUsr from v_sga_mtz_res_agrupa  a group by a.empGrupo, a.idUsuario,a.idProcesso) as totPcPrim,
                        (select a.empGrupo, a.idProcesso, a.idUsuario,  count(cod_programa) as nrProgsProcUsr from v_sga_mtz_res_agrupa  a group by a.empGrupo, a.idUsuario,a.idProcesso) as totPcSec
                where usrInstancia.idEmpresa = {$_SESSION['empresaid']}
                    and usrInstancia.ativo = 1
                    and usr.z_sga_usuarios_id = usrInstancia.idUsuario
                    and bmusr.idUsuario = usr.z_sga_usuarios_id
                    and bmusr.empGrupo  = usrInstancia.idEmpresa  -- filtro de empresa
                    and cp.idProcessoPrim = bmusr.idProcesso
                    and mtzp.idProcesso = cp.idProcessoPrim
                    and mtzr.idMtzRisco = mtzp.idMtzRisco
                    and area.idArea = mtzr.idArea        
                    and totPcPrim.idProcesso = cp.idProcessoPrim   	
                    and	totPcPrim.idUsuario  = bmusr.idUsuario 
                    and totPcPrim.empGrupo   = bmusr.empGrupo
                    and totPcSec.idProcesso = cp.idProcessoSec   	
                    and totPcSec.idUsuario  = bmusr.idUsuario 
                    and totPcSec.empGrupo   = bmusr.empGrupo        
                    group by bmusr.idUsuario, mtzr.codRisco, cp.idProcessoPrim, cp.idProcessoSec      
                ) as base  ";
                
        // Se a variavel $search não estiver vazia limita cria o where no select
        if($search != ''):
            $sql .= " WHERE base.nome_usuario LIKE '%$search%'";
            $sql .= " OR base.descArea LIKE '%$search%'";
            $sql .= " OR base.codrisco LIKE '%$search%'";
            $sql .= " OR base.descRisco LIKE '%$search%'";
            //$sql .= " OR CombinacoesDoRisco LIKE '%$search%'";
        endif;

        $sql .= " group by base.idUsuario, base.codRisco, base.idProcessoPrim
        having sum(base.nrAppsSecUsr) > 0
        ";

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
        $sql = "SELECT count($fields[0]) AS total FROM $table ";

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

        // Se a variavel $search não estiver vazia limita cria o where no select
        if($idEmpresa != '' && $idEmpresa != null):
            $sql .= (($where == '') ? ' WHERE ' : ' AND ') . " idEmpresa = '".$idEmpresa."'";
        endif;

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
    public function getCountTableUsrs($table, $search, $fields, $join, $idEmpresa = ''){
        $dados = array();
        $where = '';
        $sql = "
            select count(base.idEmpresa) as total                
        from (
            select bmusr.empGrupo as idempresa,
                        bmusr.idUsuario,
                        usr.nome_usuario,
                        area.descricao as descArea,
                        mtzr.codRisco,
                        mtzr.descricao as descRisco,
                        cp.idProcessoPrim,
                        mtzp.descProcesso as ProcessoPri,    
                    totPcPrim.nrProgsProcUsr as nrAppsPrimUsr,
                    (select count(mtapsp.idPrograma) from z_sga_mtz_apps_processo mtapsp Where mtapsp.idProcesso = cp.idProcessoPrim)  as nroAppsPPrinc,
                    (select mtzpi.descProcesso from z_sga_mtz_processo mtzpi where mtzpi.idProcesso = cp.idProcessoSec) as processoSec, 
                    totPcSec.nrProgsProcUsr as nrAppsSecUsr,
                    (select count(mtapsp.idPrograma) from z_sga_mtz_apps_processo mtapsp Where mtapsp.idProcesso = cp.idProcessoSec)  as nroAppsPSec,                    
                    usrInstancia.ativo,
                    mtzr.idMtzRisco
                from z_sga_usuario_empresa usrInstancia,
                        z_sga_usuarios usr, 
                        v_sga_mtz_base_matriz_usuario bmusr,
                        z_sga_mtz_coorelacao_processo cp,
                        z_sga_mtz_processo mtzp,
                        z_sga_mtz_risco mtzr,
                        z_sga_mtz_area area ,
                        (select a.empGrupo, a.idProcesso, a.idUsuario,  count(cod_programa) as nrProgsProcUsr from v_sga_mtz_res_agrupa  a group by a.empGrupo, a.idUsuario,a.idProcesso) as totPcPrim,
                        (select a.empGrupo, a.idProcesso, a.idUsuario,  count(cod_programa) as nrProgsProcUsr from v_sga_mtz_res_agrupa  a group by a.empGrupo, a.idUsuario,a.idProcesso) as totPcSec
                where usrInstancia.idEmpresa = {$_SESSION['empresaid']}
                    and usrInstancia.ativo = 1
                    and usr.z_sga_usuarios_id = usrInstancia.idUsuario
                    and bmusr.idUsuario = usr.z_sga_usuarios_id
                    and bmusr.empGrupo  = usrInstancia.idEmpresa  -- filtro de empresa
                    and cp.idProcessoPrim = bmusr.idProcesso
                    and mtzp.idProcesso = cp.idProcessoPrim
                    and mtzr.idMtzRisco = mtzp.idMtzRisco
                    and area.idArea = mtzr.idArea        
                    and totPcPrim.idProcesso = cp.idProcessoPrim   	
                    and	totPcPrim.idUsuario  = bmusr.idUsuario 
                    and totPcPrim.empGrupo   = bmusr.empGrupo
                    and totPcSec.idProcesso = cp.idProcessoSec   	
                    and totPcSec.idUsuario  = bmusr.idUsuario 
                    and totPcSec.empGrupo   = bmusr.empGrupo        
                    group by bmusr.idUsuario, mtzr.codRisco, cp.idProcessoPrim, cp.idProcessoSec      
                ) as base    ";
        
        

        $sql .= $join;

        // Se a variavel $search não estiver vazia limita cria o where no select
        if($search != '' && count($fields) > 0):
            $where .= " WHERE base.nome_usuario LIKE '%$search%'";
            $where .= " OR base.descArea LIKE '%$search%'";
            $where .= " OR base.codrisco LIKE '%$search%'";
            $where .= " OR base.descRisco LIKE '%$search%'";
            //$where .= " OR CombinacoesDoRisco LIKE '%$search%'";
        endif;

        $sql .= $where;        

        $sql .= " group by base.idUsuario, base.codRisco, base.idProcessoPrim
        having sum(base.nrAppsSecUsr) > 0
        ";
        //echo "<pre>";
        //die($sql);
        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0):
            $dados = $sql->fetch(PDO::FETCH_ASSOC);
            return $sql->rowCount();
        endif;

        
    }



  }