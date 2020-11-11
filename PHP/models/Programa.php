
<?php
class Programa extends Model {

      public function __construct(){
      	parent::__construct();
      }

      public function carregaProgramas($idProg){
            $idEmpresa = $_SESSION['empresaid'];
            $sql = "SELECT * FROM 
                  z_sga_programas as prog
                   where  cod_programa LIKE '".$idProg."%' or descricao_programa LIKE '".$idProg."%'   LIMIT 10" ;
            $sql = $this->db->query($sql);
            $array = array();
            if($sql->rowCount()>0){
                  $array = $sql->fetchAll();
            }
            return $array;
      }


       public function carregaGrupo($idProg,$idEmpresa){
            $sql = "SELECT 
                        p.idGrupo,
                        p.idPrograma,
                        p.cod_programa,
                        g.idGrupo,
                        g.idLegGrupo,
                        g.descAbrev,
                        (SELECT 
                            COUNT(idUsuario)
                        FROM
                        z_sga_grupos AS gp
                        JOIN
                        z_sga_usuarios AS u ON gp.idUsuario = z_sga_usuarios_id
                        WHERE
                        gp.idGrupo = g.idGrupo) AS totalUsuario
                        from 
                        z_sga_grupo_programa as p JOIN 
                        z_sga_grupo as g on 
                        g.idGrupo = p.idGrupo  
                        where p.cod_programa = '$idProg' and g.idEmpresa = '$idEmpresa' " ;
            $sql = $this->db->query($sql);
            $array = array();
            if($sql->rowCount()>0){
                  $array = $sql->fetchAll();
            }
            return $array;
      }

        public function carregaDescProg($idProg,$idEmpresa){
            $sql = "SELECT * FROM 
                  z_sga_programas as prog,
                  z_sga_programa_empresa as progEmp where z_sga_programas_id = progEmp.idPrograma and progEmp.idEmpresa = '$idEmpresa' and cod_programa = '$idProg'" ;
            $sql = $this->db->query($sql);
            $array = array();
            if($sql->rowCount()>0){
                  $array = $sql->fetch();
            }
            return $array;
      }


      public function carregaUsuario($idProg,$idEmpresa){
            $sql = "
                SELECT 
                    p.idGrupo,
                    p.idPrograma,
                    p.cod_programa,
                    g.idGrupo,
                    g.idLegGrupo,
                    g.descAbrev,
                    grupoUser.idUsuario,
                    u.cod_usuario,
                    u.nome_usuario,
                    fun.cod_funcao,
                    e.ativo
                FROM
                    z_sga_grupo_programa AS p,
                    z_sga_grupo AS g,
                    z_sga_grupos AS grupoUser,
                    z_sga_usuarios AS u,
                    z_sga_usuario_empresa AS e,
                    z_sga_manut_funcao AS fun
                WHERE
                    g.idGrupo = p.idGrupo
                        AND g.idGrupo NOT IN (SELECT 
                            idgrupo
                        FROM
                            z_sga_grupo_nao_lista)
                        AND grupoUser.idGrupo = g.idGrupo
                        AND grupoUser.idUsuario = u.z_sga_usuarios_id
                        AND e.idUsuario = u.z_sga_usuarios_id
                        AND fun.idFuncao = u.cod_funcao
                        AND p.cod_programa = '$idProg'
                        AND g.idEmpresa = $idEmpresa
                ORDER BY u.nome_usuario" ;
            $sql = $this->db->query($sql);
            $array = array();
            if($sql->rowCount()>0){
                  $array = $sql->fetchAll();
            }
            return $array;
      }

    public function carregaCamposPessoal($idPrograma){
        $sql="
        Select 
            zslc.`name` as 'Nome',
            if(zslc.`sensitive`=1,'Sim','Não') as 'Sensivel',
            if(zslc.`anonymize`=1,'Sim','Não') as 'Anonizado'
        from 
            z_sga_programas zsp 
        inner join 
            z_sga_lgpd_campos_programas zslcp on zslcp.idPrograma=zsp.z_sga_programas_id 
        inner join 
            z_sga_lgpd_campos zslc on zslc.id=zslcp.id_campo 
        where 
            zsp.cod_programa='$idPrograma' and (if(zslc.`sensitive`=0,'Sim','Não')='Sim')";

        $sql = $this->db->query($sql);
        $array = array();
        if($sql->rowCount()>0){
              $array = $sql->fetchAll();
        }

        return $array;
    }

    public function carregaCamposSensivel($idPrograma){
        $sql="
        Select 
            zslc.`name` as 'Nome',
            if(zslc.`sensitive`=0,'Sim','Não') as 'Pessoal',
            if(zslc.`anonymize`=1,'Sim','Não') as 'Anonizado'
        from 
            z_sga_programas zsp 
        inner join 
            z_sga_lgpd_campos_programas zslcp on zslcp.idPrograma=zsp.z_sga_programas_id 
        inner join 
            z_sga_lgpd_campos zslc on zslc.id=zslcp.id_campo 
        where 
            zsp.cod_programa='$idPrograma' and (if(zslc.`sensitive`=1,'Sim','Não')='Sim')";

        $sql = $this->db->query($sql);
        $array = array();
        if($sql->rowCount()>0){
              $array = $sql->fetchAll();
        }

        return $array;
    }
    
    // if(zslc.`sensitive`=1,'Sim','Não') as 'Sensivel',
    // if(zslc.`anonymize`=1,'Sim','Não') as 'Anonizado'

    public function carregaCamposAnonizado($idPrograma){
        $sql="
        Select 
            zslc.`name` as 'Nome',
            if(zslc.`sensitive`=0,'Sim','Não') as 'Pessoal',
            if(zslc.`sensitive`=1,'Sim','Não') as 'Sensivel'
        from 
            z_sga_programas zsp 
        inner join 
            z_sga_lgpd_campos_programas zslcp on zslcp.idPrograma=zsp.z_sga_programas_id 
        inner join 
            z_sga_lgpd_campos zslc on zslc.id=zslcp.id_campo 
        where 
            zsp.cod_programa='$idPrograma' and (if(zslc.`anonymize`=1,'Sim','Não')='Sim')";

        $sql = $this->db->query($sql);
        $array = array();
        if($sql->rowCount()>0){
              $array = $sql->fetchAll();
        }

        return $array;
    }

    public function getG(){

        $rs = $this->select([
                'p.idGrupo',
                'p.idPrograma',
                'p.cod_programa',
                'g.idGrupo',
                'g.idLegGrupo',
                'g.descAbrev',
                '(SELECT COUNT(idUsuario) FROM z_sga_grupos AS gp JOIN z_sga_usuarios AS u ON gp.idUsuario = z_sga_usuarios_id
                WHERE gp.idGrupo = g.idGrupo) AS totalUsuario'
            ])
            ->from('z_sga_grupo_programa AS p')
            ->join('z_sga_grupo AS g', 'g.idGrupo = p.idGrupo', '')
            /*->where([
                ['p.cod_programa', '=', "'pd4000'"],
                ['g.idEmpresa', '=', "4"]
            ])*/
            /*->where_or([
                ['p.cod_programa', '=', "'pd4000'"],
                ['g.idEmpresa', '=', "4"]
            ])*/
            ->like('p.cod_programa', 'PD4000', 'both')
            //->where_in('g.idEmpresa', [1,2,3,4])
            //->where_not_in('g.idEmpresa', [10,11,13,14])
            //->group_by('id')
            //->order_by('nome', 'ASC')
            //->limit(0, 100)
            ->get();

        return $rs;
    }
    
    /**
     * Retorna os programas para tela de manutenção de grupos
     * @param type $nome
     * @param type $eliminar
     * @param $idGrupo
     * @return type
     */
    public function carregaProgramasGrupoEdita($nome = '', $eliminar = '', $idGrupo)
    {
        $idEmpresa = $_SESSION['empresaid'];
        $sql = "
            SELECT 
                p.z_sga_programas_id AS idPrograma,
                p.descricao_programa,
                p.cod_programa
            FROM 
                z_sga_programas p
            LEFT JOIN
                z_sga_programa_empresa e
                ON e.idPrograma = p.z_sga_programas_id
            WHERE
                (p.cod_programa LIKE '$nome%' OR p.descricao_programa) 
                AND p.z_sga_programas_id NOT IN(SELECT idPrograma FROM z_sga_grupo_programa gp WHERE gp.idGrupo = $idGrupo)".
                ((!empty($eliminar)) ? " AND p.z_sga_programas_id NOT IN($eliminar) " : '')
                ." AND e.idEmpresa = $idEmpresa
                LIMIT 100";
        
        //echo "<pre>";
        //print_r($eliminar);
        //die($sql);
        
        $sql = $this->db->query($sql);
        $array = array();
        if($sql->rowCount()>0){
              $array = $sql->fetchAll();
        }
        return $array;
      }
      
      /**
     * Retorna os programas já realcionados com grupo para tela de manutenção de grupos     
     * @param $idGrupo
     * @return type
     */
    public function carregaProgramasAdicionadosGrupoEdita($idGrupo)
    {
        $idEmpresa = $_SESSION['empresaid'];
        $sql = "
            SELECT 
                p.z_sga_programas_id AS idPrograma,
                p.descricao_programa,
                p.cod_programa
            FROM 
                z_sga_programas p
            LEFT JOIN
                z_sga_programa_empresa e
                ON e.idPrograma = p.z_sga_programas_id
            WHERE                
                p.z_sga_programas_id IN(SELECT idPrograma FROM z_sga_grupo_programa gp WHERE gp.idGrupo = $idGrupo)                
                AND e.idEmpresa = $idEmpresa";
        
        //echo "<pre>";
        //die($sql);
        
        $sql = $this->db->query($sql);
        $array = array();
        if($sql->rowCount()>0){
              $array = $sql->fetchAll();
        }
        return $array;
      }
}