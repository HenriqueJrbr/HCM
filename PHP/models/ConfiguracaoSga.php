<?php
class ConfiguracaoSga extends Model {

      public function __construct(){
      	parent::__construct();
      }

      public function carregaUsuario(){
        $sql = "SELECT * FROM z_sga_usuarios";
            $sql = $this->db->query($sql);
            $array = array();
            if($sql->rowCount()>0){
                  $array = $sql->fetchAll();
            }
            return $array; 
      }

      public function carregaGrupoSga(){
        $sql = "SELECT * FROM z_sga_param_grupo";
            $sql = $this->db->query($sql);
            $array = array();
            if($sql->rowCount()>0){
                  $array = $sql->fetchAll();
            }
            return $array; 
      }

      public function criarUsrSga($login,$nomeUsuario,$email,$senha,$validade,$idTotovs){
            $sql = "INSERT INTO z_sga_param_login SET login = '$login' , nomeUsuario = '$nomeUsuario' , email = '$email' , senha = '$senha' , validade = '$validade' ,idTotovs = '$idTotovs' ";
            $sql = $this->db->query($sql);

            $sql2 = "SELECT max(idLogin) as idLogin FROM z_sga_param_login";
            $sql2 = $this->db->query($sql2);
            
            $array = array();

            if($sql2->rowCount() > 0){
                $array = $sql2->fetch();
            }
            return $array;

      }

      public function addUsrGrupo($idUsr,$grupo){
            $sql = "INSERT INTO z_sga_param_grupo_usuario SET idUsuario = '$idUsr',idGrupo = '$grupo'";
            $sql = $this->db->query($sql);
      }

      public function carregaLogin(){
        $sql = "SELECT DISTINCT
            pl.idLogin,
            pl.login,
            pl.nomeUsuario,
            pl.email,
            DATE_FORMAT(pl.validade, '%d/%m/%Y') AS validade,
            e.ativo ativo
        FROM
            z_sga_param_login pl
        LEFT JOIN
            z_sga_usuarios u
            ON pl.idTotovs = u.z_sga_usuarios_id
        LEFT JOIN
            z_sga_usuario_empresa e
            ON e.idUsuario = u.z_sga_usuarios_id";
        
            $sql = $this->db->query($sql);
            $array = array();
            if($sql->rowCount()>0){
                  $array = $sql->fetchAll();
            }
            return $array; 
      }

      public function editarLogin($id,$nomeUsuario,$email,$validade,$idTotovs,$grupo){
            $sql = "UPDATE z_sga_param_login SET nomeUsuario = '$nomeUsuario',email = '$email',validade ='$validade',idTotovs ='$idTotovs' where idLogin = '$id'";
            $sql = $this->db->query($sql);

            $sql = $this->db->query("SELECT idUsuario FROM z_sga_param_grupo_usuario WHERE idUsuario = $id");
            if($sql->rowCount() > 0):
                $sql2 = "UPDATE z_sga_param_grupo_usuario SET idGrupo = '$grupo' where idUsuario = '$id'";
            else:
                $sql2 = "INSERT INTO z_sga_param_grupo_usuario SET idGrupo = $grupo, idUsuario = $id";
            endif;

            $sql2 = $this->db->query($sql2);
      }

      public function editarSenha($id,$senhaEditar){
           $sql = "UPDATE z_sga_param_login SET senha = '$senhaEditar',trocaSenha = '1' where idLogin = '$id'";
            $sql = $this->db->query($sql); 
            return $sql->rowCount();
      }

      public function trocaSenhaAtual($id,$senhaEditar){
           $sql = "UPDATE z_sga_param_login SET senha = '$senhaEditar',trocaSenha = '0' where idLogin = '$id'";
            $sql = $this->db->query($sql); 
            return $sql->rowCount();
      }

      public function ajaxDadosDoLogin($id){
             $sql = "SELECT
                  * 
                FROM 
                  z_sga_param_login as l
                LEFT JOIN
                  z_sga_param_grupo_usuario as grupo
                    ON l.idLogin = grupo.idUsuario
                WHERE 
                   l.idLogin = $id";
            $sql = $this->db->query($sql);
            $array = array();
            if($sql->rowCount()>0){
                  $array = $sql->fetch(PDO::FETCH_ASSOC);
            }
            return $array;
      }

      public function validaUsr($usr){
            $sql = "SELECT login FROM z_sga_param_login where login = '$usr'";
            $sql = $this->db->query($sql);
            $array = array();
            if($sql->rowCount()>0){
                  return true;
            }
                  return false;
      }
      
    /**
     * Retorna o último snapshot
     * @return type
     */
    public function carregaSnapshots()
    {       
        $sql = "
            SELECT 
                u.nome_usuario AS usuario,
                e.razaoSocial  AS empresa,
                dataHora
            FROM
                z_sga_snapshots s
            LEFT JOIN
                z_sga_usuarios u
                ON s.idUsuario = u.z_sga_usuarios_id
            LEFT JOIN
                z_sga_empresa e
                ON s.idEmpresa = e.idEmpresa
            WHERE
                s.idEmpresa = ".$_SESSION['empresaid']."
            ORDER BY 
                id DESC";
        
        $sql = $this->db->query($sql);

        $array = array();

        if($sql->rowCount() > 0){
            $array = $sql->fetchAll(PDO::FETCH_ASSOC);
        }
        return $array;       
    }
    
    /**
     * Executa store procedure que atualiza snapshot
     * @return type
     */
    public function atualizaSnapshot()
    {       
        
        try{                        
            $rs = $this->db->query('CALL sp_sga_refresh_snapshots('.$_SESSION['empresaid'].', '.$_SESSION['idUsrTotvs'].')')->fetch(PDO::FETCH_ASSOC);

            if($rs['retorno'] == 1):
                return array('return' => true);
            else:
                return array(
                    'return' => true,
                    'error'  => $rs['msg']
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
     * Retorna os parametros globais
     * @return type
     */
    public function carregaParametrosGlobais()
    {       
        $sql = "
            SELECT 
                *
            FROM
                z_sga_param_global";
        
        $sql = $this->db->query($sql);

        $array = array();

        if($sql->rowCount() > 0){

            $array['pGlobal'] = $sql->fetch(PDO::FETCH_ASSOC);

            $sql = "
                SELECT 
                    * 
                FROM 
                    z_sga_param_email
                ";
            
            $sql = $this->db->query($sql);

            if($sql->rowCount() > 0):
                $array['email'] = $sql->fetch(PDO::FETCH_ASSOC);
            endif;
        }
        return $array;       
    }

    /**
     * Salva os dados de parametros do sga
     * @param $host
     * @param $ambiente
     * @param $integrationData
     * @return type
     */
    public function salvaParamGlobal($host, $ambiente, $integrationData)
    {       
        // Valida se já existe registro
        $sql = $this->db->query("SELECT host FROM z_sga_param_global");
        if($sql->rowCount() > 0):
            $sql = "
                UPDATE
                    z_sga_param_global
                SET
                    host             = '$host',
                    ambiente         = '$ambiente',
                    integration_data = '$integrationData'
            ";
        else:
            $sql = "
                INSERT INTO
                    z_sga_param_global
                SET
                    host             = '$host',
                    ambiente         = '$ambiente',
                    integration_data = '$integrationData'
            ";
        endif;
       
        try{
            $rs = $this->db->query($sql);

            return array('return' => true);
        } catch (Exception $e) {
            return array(
                'return'    => false,
                'error'     => $e->getMessage()
            );
        }                
    }

    /**
     * Salva os dados de email do sga
     * @param $email
     * @return type
     */
    public function salvaParamEmail($email)
    {       
        // Valida se já existe registro
        $sql = $this->db->query("SELECT email FROM z_sga_param_email");
        if($sql->rowCount() > 0):
            $sql = "
                UPDATE
                    z_sga_param_email
                SET
                    email                  = '".$email['email']."',
                    senha                  = '".$email['senha']."',
                    remetente              = '".$email['remetente']."',
                    smtp                   = '".$email['smtp']."',
                    portaSmtp              = '".$email['portaSmtp']."',
                    envia_logo_instancia   = ".$email['envia_logo_instancia'];
        else:
            $sql = "
                INSERT INTO
                    z_sga_param_email
                SET
                    email                  = '".$email['email']."',
                    senha                  = '".$email['senha']."',
                    remetente              = '".$email['remetente']."',
                    smtp                   = '".$email['smtp']."',
                    portaSmtp              = '".$email['portaSmtp']."',
                    envia_logo_instancia   = ".$email['envia_logo_instancia'];
        endif;
        
        try{
            $rs = $this->db->query($sql);
            
            return array('return' => true);
        } catch (Exception $e) {
            return array(
                'return'    => false,
                'error'     => $e->getMessage()
            );
        }                
    }
}