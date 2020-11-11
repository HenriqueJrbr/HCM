
<?php
class SistemaModulo extends Model {

  public function __construct(){
  	parent::__construct();
  }
  public function carregaSistema(){
    $idEmpresa = $_SESSION['empresaid'];
    $sql = "SELECT distinct  Sist.*
                FROM
                    z_sga_usuarios AS u,
                    z_sga_usuario_empresa AS eu,
                    z_sga_grupos AS gu,
                    z_sga_grupo AS g,
                    z_sga_grupo_programa AS gp,
                    z_sga_programas AS p,
                    z_sga_modul_dtsul as modulo,
                    z_sga_sist_dtsul as Sist
                  WHERE eu.idEmpresa = '$idEmpresa'
                  and g.idGrupo not in (select idgrupo from z_sga_grupo_nao_lista)                   
                  AND eu.idUsuario = u.z_sga_usuarios_id
                  AND gu.idUsuario = u.z_sga_usuarios_id
                  AND g.idGrupo = gu.idGrupo
                  AND g.idEmpresa = eu.idEmpresa
                  AND gp.idGrupo = gu.idGrupo
                  AND p.z_sga_programas_id = gp.idPrograma
                  AND modulo.cod_modul_dtsul = p.cod_modulo
                  and Sist.cod_sist_dtsul = modulo.cod_sist_dtsul
                  and eu.ativo = 1
                    ";

    $sql = $this->db->query($sql);
    $dados = array();
    if($sql->rowCount()>0){
      $dados = $sql->fetchAll();
    }
    return $dados;
}

public function carregaSistemaDesc($id){
      $idEmpresa = $_SESSION['empresaid'];
    $sql = "SELECT distinct  Sist.*
                FROM
                    z_sga_usuarios AS u,
                    z_sga_usuario_empresa AS eu,
                    z_sga_grupos AS gu,
                    z_sga_grupo AS g,
                    z_sga_grupo_programa AS gp,
                    z_sga_programas AS p,
                    z_sga_modul_dtsul as modulo,
                    z_sga_sist_dtsul as Sist
                  WHERE eu.idEmpresa = '$idEmpresa'
                  and g.idGrupo not in (select idgrupo from z_sga_grupo_nao_lista)                   
                  AND eu.idUsuario = u.z_sga_usuarios_id
                  AND gu.idUsuario = u.z_sga_usuarios_id
                  AND g.idGrupo = gu.idGrupo
                  AND g.idEmpresa = eu.idEmpresa
                  AND gp.idGrupo = gu.idGrupo
                  AND p.z_sga_programas_id = gp.idPrograma
                  AND modulo.cod_modul_dtsul = p.cod_modulo
                  and Sist.idSistDtsul = '$id'
                  and Sist.cod_sist_dtsul = modulo.cod_sist_dtsul
                  and eu.ativo = 1
            ";

    $sql = $this->db->query($sql);
    $dados = array();
    if($sql->rowCount()>0){
      $dados = $sql->fetch();
    }
    return $dados;
}


  public function carregaMudulo($id){
     $idEmpresa = $_SESSION['empresaid'];
    $sql = "SELECT 
                  u.z_sga_usuarios_id,
                  u.cod_usuario,
                  u.nome_usuario,
                  e.ativo,
                  sis.des_sist_dtsul,
                  p.descricao_modulo,
                  p.descricao_rotina,
                  GROUP_CONCAT(DISTINCT g.idLegGrupo
                      SEPARATOR ' | ') Grupos,
                  COUNT(p.cod_programa) AS numProgramas,
                  GROUP_CONCAT(DISTINCT CONCAT_WS(';',
                              p.cod_programa,
                              p.descricao_programa)
                      SEPARATOR ' | ') Programas,
                  sis.idSistDtsul
              FROM
                z_sga_sist_dtsul AS sis,
                  z_sga_modul_dtsul AS mdl,
                  z_sga_programas AS p,
                  z_sga_usuarios AS u,
                  z_sga_grupos AS gu,
                  z_sga_grupo AS g,
                  z_sga_grupo_programa AS gp,
                  z_sga_usuario_empresa AS e
              WHERE sis.idSistDtsul = '$id'
                AND mdl.cod_sist_dtsul = sis.cod_sist_dtsul
                and p.cod_modulo = mdl.cod_modul_dtsul
                AND p.cod_programa = p.procedimento_pai 
                and gp.idPrograma  = p.z_sga_programas_id
                AND g.idGrupo = gp.idGrupo
                AND g.idEmpresa = '$idEmpresa'
                AND gu.idGrupo = gp.idGrupo 
                AND gu.idUsuario = u.z_sga_usuarios_id
                AND e.idUsuario = u.z_sga_usuarios_id
                AND e.ativo = 1
              GROUP BY sis.cod_sist_dtsul , p.descricao_modulo , u.cod_usuario , p.descricao_rotina";

      $sql = $this->db->query($sql);
      $dados = array();
      if($sql->rowCount()>0){
        $dados = $sql->fetchAll();
      }
      return $dados;

  }


}