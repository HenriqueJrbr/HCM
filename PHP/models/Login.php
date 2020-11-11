<?php
class Login extends Model {
     
      public function __construct(){
      	parent::__construct();
      }

      public function islogin(){
        if(isset($_SESSION['login']) && !empty($_SESSION['login'])){
          return true;
        }else{
          return false;
        }
      }

    public function getIntegrationData()
    {
        $sql = "
            SELECT
                integration_data
            FROM
                z_sga_param_global
        ";
        
        try{
            $rs = $this->db->query($sql);
            
            if($rs->rowCount() > 0):
                return $rs->fetch(PDO::FETCH_ASSOC);
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
      
      public function validaLoginAd($usuario,$senha, $ldap){
          global $ldap;
          //Endereco do servido AD IP ou nome
          $servidor_AD = $ldap->ip_ad;     
          //Domínio

          $dominio = $ldap->dominio;
          // Conexão com servidor AD. 
          $ad = ldap_connect($servidor_AD)
              or die("Não foi possível conexão com Active Directory!");
         
          // Versao do protocolo       
          ldap_set_option($ad, LDAP_OPT_PROTOCOL_VERSION, 3);
          // Usara as referencias do servidor AD, neste caso nao
          ldap_set_option($ad, LDAP_OPT_REFERRALS, 0);
               
          // Bind to the directory server.
          $bd = @ldap_bind($ad, $usuario."@".$dominio, $senha )
              or die("Não foi possível pesquisa no AD.");    
          if( $bd ){
            return true;
          }else{
           $this->validalogin($usuario,$senha);

          }
          //Fecha conexao
          ldap_unbind($ad);
      }
      

      public function validaAcesso($idUsuario,$idMenu){
        $sql = "SELECT 
                  usr.idGrupo, 
                  usr.idUsuario,
                  grperm.idGrupo,
                  grperm.url,
                  menu.idMenu,
                  menu.descricao,
                  menu.url as menuUrl,
                  subCate.descricao as descricaoSubCategoria,
                  subCate.idSubCategoria as idSubCategoria,
                  subCate.icone as subIcone,
                  cate.idCategoria as idCategoria,
                  cate.descricao as descricaoCategoria,
                  cate.icone as iconeCategoria
                  FROM
                  z_sga_param_grupo_usuario as usr,
                  z_sga_param_grupo_permissao as grperm,
                  z_sga_param_menu as menu,
                  z_sga_param_sub_categoria as subCate,
                  z_sga_param_categoria as cate
                  where
                      usr.idGrupo = grperm.idGrupo AND
                      grperm.url = menu.idMenu AND
                      subCate.idSubCategoria = menu.idSubCategoria AND
                      cate.idCategoria = subCate.idCategoria AND
                      usr.idUsuario = '$idUsuario' AND
                      menu.idMenu = '$idMenu'
                      order BY descricaoCategoria,descricaoSubCategoria,descricao";
        $sql = $this->db->query($sql);

        if($sql->rowCount()>0){
          return true;
        }else{
          return false;
        }            
                  
      }

      public function validalogin($usuario,$senha){
        $senha = md5($senha);
        $sql = "SELECT * FROM z_sga_param_login where login = '$usuario' AND senha = '$senha' ";
        $sql = $this->db->query($sql);

        if($sql->rowCount()>0){
              $sql = $sql->fetch();

              if(date("Y-m-d") > $sql['validade'] ){
                  return array('expirado' =>true);
              }else{
                $_SESSION['login'] = $sql['login'];
              
                $sql2 = "SELECT * from z_sga_empresa where matriz = '1' ";
                $sql2 = $this->db->query($sql2);
                $sql2 =$sql2->fetch();

                $idTotvs = $sql['idTotovs'];

                $sql3 = "SELECT * from z_sga_usuarios where z_sga_usuarios_id = '$idTotvs' ";
                $sql3 = $this->db->query($sql3);
                $sql3 =$sql3->fetch();

                $_SESSION['empresaDesc'] = $sql2['razaoSocial'];
                $_SESSION['empresaid'] = $sql2['idEmpresa'];
                $_SESSION['idUsrLogado'] = $sql['idLogin'];
                $_SESSION['idUsrTotvs'] = $sql['idTotovs'];
                $_SESSION['nomeUsuario'] = $sql['nomeUsuario'];
                $_SESSION['codUsuario'] = $sql3['cod_usuario'];
                $_SESSION['email'] = $sql['email'];
                
                if($sql3['gestor_usuario'] == "S"){
                  $_SESSION['acesso'] = 'gestor';
                  $_SESSION['gestor'] = $sql3['cod_usuario'];
                }
                if($sql3['gestor_grupo'] == "S"){
                  $_SESSION['acesso'] = 'gestorGrupo';

                }
                if($sql3['si'] == "S"){
                  $_SESSION['acesso'] = 'SI';
                }

                unset($_SESSION["validaData"]);
                
                return true;
              }   
        }else{
              return false;
        }
      }

    public function novaSenha($usuario){
      $sql = "SELECT * FROM z_sga_param_login where login = '$usuario'";
      $sql = $this->db->query($sql);
    
      if($sql->rowCount()>0){
        $sql =$sql->fetch();
        $configuracaoSga = new ConfiguracaoSga();
        $email = new Email();

        $assunto = "Nova Senha SGA";
        $nomeRemetente = "SGA - Sistema de Gestão de Acesso";

        $senhaEditar = $this->gerar_senha(6, true, true, false, false);
        $retorno = $configuracaoSga->editarSenha($sql['idLogin'],md5($senhaEditar));

        if($retorno>0){
          $mensagem = '
                Olá, <b>'.$sql['nomeUsuario'].'</b>.<br/><br/>
                <span style="font-size:14px;margin-top:20px">Sua nova é: '.$senhaEditar.'</span>';
              
            $template = $email->getTemplate($mensagem);                              
            $email->enviaEmail($nomeRemetente,$assunto, $template, $sql['email']);

            return true;
        }

      }else{
        return false;
      }
    }

    public function validaSenhaAtual($senha,$idUsuario){
       $senha = md5($senha);
        $sql = "SELECT * FROM z_sga_param_login where idLogin = '$idUsuario' AND senha = '$senha' ";
        $sql = $this->db->query($sql);

        if($sql->rowCount()>0){
          return true;
        }
          return false;
        
    }


  private function gerar_senha($tamanho, $maiusculas, $minusculas, $numeros, $simbolos){
      $ma = "ABCDEFGHIJKLMNOPQRSTUVYXWZ"; // $ma contem as letras maiúsculas
      $mi = "abcdefghijklmnopqrstuvyxwz"; // $mi contem as letras minusculas
      $nu = "0123456789"; // $nu contem os números
      $si = "!@#$%¨&*()_+="; // $si contem os símbolos
      $senha = "";
      if ($maiusculas){
            // se $maiusculas for "true", a variável $ma é embaralhada e adicionada para a variável $senha
            $senha .= str_shuffle($ma);
      }
     
      if ($minusculas){
          // se $minusculas for "true", a variável $mi é embaralhada e adicionada para a variável $senha
          $senha .= str_shuffle($mi);
      }
     
      if ($numeros){
          // se $numeros for "true", a variável $nu é embaralhada e adicionada para a variável $senha
          $senha .= str_shuffle($nu);
      }
     
      if ($simbolos){
          // se $simbolos for "true", a variável $si é embaralhada e adicionada para a variável $senha
          $senha .= str_shuffle($si);
      }
     
      // retorna a senha embaralhada com "str_shuffle" com o tamanho definido pela variável $tamanho
      return substr(str_shuffle($senha),0,$tamanho);
  }



  public function validaTrocaSenha(){
      $idLogin = $_SESSION['idUsrLogado'];
      $sql = "SELECT * FROM z_sga_param_login where idLogin = '$idLogin'";
      $sql = $this->db->query($sql);
    
      if($sql->rowCount()>0){
         $sql =$sql->fetch();

         if($sql['trocaSenha'] == 1){
            return true;
         }else{
            return false;
         }

      }else{
        return false;
      }
  }

}