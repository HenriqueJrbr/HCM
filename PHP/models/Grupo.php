<?php
class Grupo extends Model {

      public function __construct(){
      	parent::__construct();
      }

      public function carregaGrupo($idEmpresa){
            $sql = "SELECT grupo.*,(SELECT count(gp.cod_programa)
                        from z_sga_grupo_programa as gp 
                        where gp.idGrupo = grupo.idGrupo) as totalProg,
                        (SELECT count(g.idUsuario) from z_sga_grupos as g
                        where   g.idGrupo = grupo.idGrupo) as totalUsuario
                        from z_sga_grupo as grupo where grupo.idEmpresa = '$idEmpresa'";
            $sql = $this->db->query($sql);
            $array = array();
            if($sql->rowCount()>0){
                  $array = $sql->fetchAll();
            }
            return $array;
      }


      public function carregaDadosGrupo($idGrupo){
             $idEmpresa = $_SESSION['empresaid'];
            $sql = "
                SELECT 
                    g.z_sga_grupos_id,
                    u.z_sga_usuarios_id,
                    u.nome_usuario,
                    u.cod_usuario,
                    u.idUsrFluig,
                    u.cod_gestor,
                    fun.cod_funcao,
                    emp.ativo
                FROM
                    z_sga_grupos AS g,
                    z_sga_usuarios AS u,
                    z_sga_usuario_empresa AS emp,
                    z_sga_manut_funcao AS fun
                WHERE                   
                    emp.idUsuario = u.z_sga_usuarios_id
                    #and fun.idFuncao = u.cod_funcao
                    and emp.idEmpresa = '$idEmpresa'
                    and g.idUsuario = u.z_sga_usuarios_id 
                    and idGrupo = '$idGrupo'
                  GROUP BY
                    g.idUsuario";
            $sql = $this->db->query($sql);
            $dados = array();
            if($sql->rowCount()>0){
                  $dados = $sql->fetchAll();
            }
            return $dados;
      }


      public function carregaGestorGrupo($idGrupo){
            $sql = "SELECT distinct(g.idGrupo), g.cod_grupo,g.desc_grupo,g.gestor, u.nome_usuario 
                        FROM z_sga_grupos as g,
                        z_sga_usuarios as u 
                        where idGrupo = '$idGrupo' and u.cod_usuario = g.gestor";
            $sql = $this->db->query($sql);
            $array = array();
            if($sql->rowCount()>0){
                  $array = $sql->fetch();
            }
            return $array;
      }

      public function carregaDescGrupo($idGrupo){
            $sql = "SELECT * FROM z_sga_grupo where idGrupo = '$idGrupo'";
            $sql = $this->db->query($sql);
            $array = array();
            if($sql->rowCount()>0){
                  $array = $sql->fetch();
            }
            return $array;
      }


      public function carregaDadosGrupoProg($idGrupo){
            $sql = "
                SELECT 
                    prog.z_sga_grupo_programa_id,
                    prog.idGrupo,
                    p.descricao_programa,
                    p.cod_programa,
                    IF(p.ajuda_programa = '',
                        'Não Cadstrado',
                        p.ajuda_programa) AS ajuda,
                        IF(p.especific = 'N',
                        'Não',
                        'Sim') AS especific,	
                    p.descricao_rotina
                FROM
                    z_sga_grupo_programa AS prog
                JOIN
                    z_sga_programas AS p 
                    ON p.cod_programa = prog.cod_programa
                WHERE
                    prog.idGrupo =  $idGrupo";
            $sql = $this->db->query($sql);
            $array = array();
            if($sql->rowCount()>0){
                  $array = $sql->fetchAll();
            }
            return $array;
      }


      public function excluirProgramaDoGrupo($idPrograma){
        $sql = "DELETE FROM z_sga_grupo_programa WHERE z_sga_grupo_programa_id = '$idPrograma'";
        $sql = $this->db->query($sql);
      }
      public function excluirUsrGrupo($idUsr){
        $sql = "DELETE FROM z_sga_grupos WHERE z_sga_grupos_id = '$idUsr'";
        $sql = $this->db->query($sql);
      }

      public function ajaxCarregaUsr($idUsr){
        $sql = "SELECT *                 
                FROM z_sga_usuario_empresa AS userEmp,
                     z_sga_usuarios AS u ,
                     z_sga_manut_funcao AS m
                where userEmp.idEmpresa = '".$_SESSION['empresaid']."' and 
                userEmp.idUsuario = u.z_sga_usuarios_id and 
                u.cod_funcao = m.idFuncao  and u.nome_usuario like '%".$idUsr."%'";

        $sql = $this->db->query($sql);
        $array = array();
        if($sql->rowCount()>0){
              $array = $sql->fetchAll();
        }
        return $array;
      }
      public function addUsrGrupo($idGrupo,$idUsr){
        $sqlGrupo = "SELECT * FROM z_sga_grupo where idGrupo = '$idGrupo'";
        $sqlGrupo = $this->db->query($sqlGrupo);
        $array = array();
        if($sqlGrupo->rowCount()>0){
          $sqlGrupo = $sqlGrupo->fetch();

          $idLegGrupo = $sqlGrupo['idLegGrupo'];
          $descAbrev = $sqlGrupo['descAbrev'];
          $idGrupo  = $sqlGrupo['idGrupo'];

          $sqlUsr = "SELECT * FROM z_sga_usuarios where z_sga_usuarios_id = '$idUsr'";
          $sqlUsr = $this->db->query($sqlUsr);
          if($sqlUsr->rowCount()>0){
            $sqlUsr = $sqlUsr->fetch();

            $cod_usuario = $sqlUsr['cod_usuario'];
            $idUsr = $sqlUsr['z_sga_usuarios_id'];
            $gestor = "super";

            $sql = "INSERT INTO z_sga_grupos SET cod_grupo = '$idLegGrupo', desc_grupo = '$descAbrev',gestor = '$gestor',cod_usuario = '$cod_usuario',idGrupo =  '$idGrupo',idUsuario = '$idUsr'";
            $sql = $this->db->query($sql);
          }
        }
      }

      public function addProgGrupo($idProg,$idGrupo){
        $sqlGrupo = "SELECT * FROM z_sga_grupo where idGrupo = '$idGrupo'";
        $sqlGrupo = $this->db->query($sqlGrupo);

        $array = array();
        if($sqlGrupo->rowCount()>0){
            $sqlGrupo = $sqlGrupo->fetch();
            $idLegGrupo = $sqlGrupo['idLegGrupo'];
            $idGrupo = $sqlGrupo['idGrupo'];


            $sqlProg = "SELECT * FROM z_sga_programas where z_sga_programas_id = '$idProg'";
            $sqlProg = $this->db->query($sqlProg);
            
            if($sqlProg->rowCount()>0){
              $sqlProg = $sqlProg->fetch();

              $descricao = $sqlProg['descricao_programa'];
              $cod_programa = $sqlProg['cod_programa'];
              $z_sga_programas_id = $sqlProg['z_sga_programas_id'];
              $gestor = "";

              $sql = "INSERT INTO z_sga_grupo_programa SET cod_grupo = '$idLegGrupo',nome_grupo = '$idLegGrupo', gestor = '$gestor',cod_programa = '$cod_programa',idGrupo = '$idGrupo',idPrograma = '$z_sga_programas_id'";
              $sql = $this->db->query($sql);

            }
        }
      }


       public function ajaxCarregaProg($idProg){
        $sql = "SELECT *                 
                FROM z_sga_usuario_empresa AS userEmp,
                     z_sga_usuarios AS u ,
                     z_sga_manut_funcao AS m
                where userEmp.idEmpresa = '".$_SESSION['empresaid']."' and 
                userEmp.idUsuario = u.z_sga_usuarios_id and 
                u.cod_funcao = m.idFuncao  and u.nome_usuario like '%".$idProg."%'";

        $sql = $this->db->query($sql);
        $array = array();
        if($sql->rowCount()>0){
              $array = $sql->fetchAll();
        }
        return $array;
      }

      public function addGrupo($nameGrupo,$descGrupo){
        $idEmpresa = $_SESSION['empresaid'];
        $sql = "INSERT INTO z_sga_grupo SET idLegGrupo = '$nameGrupo', descAbrev = '$descGrupo',idEmpresa = '$idEmpresa'";
         $sql = $this->db->query($sql);
      }



      
}