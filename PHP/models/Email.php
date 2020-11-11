<?php

require 'src/OAuth.php';
require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

class Email extends Model {

    public function __construct() {
        parent::__construct();
    }

    public function buscaDadosUsuarioLogin($id) {
        $sql = "SELECT * FROM z_sga_param_login where idTotovs = '$id'";
        $sql = $this->db->query($sql);

        $dados = array();
        if ($sql->rowCount() > 0) {
            $dados = $sql->fetch();
        }
        return $dados;
    }
    
    public function cadadosUsuario($id) {
        $sql = "SELECT * FROM z_sga_usuarios where z_sga_usuarios_id = '$id'";
        $sql = $this->db->query($sql);

        $dados = array();
        if ($sql->rowCount() > 0) {
            $dados = $sql->fetch();
        }
        return $dados;
    }

    public function enviaEmail($nomeRemetente, $assuntoMensagem, $mensagem, $destinatario) {

        $sql = "SELECT * FROM z_sga_param_email";
        $sql = $this->db->query($sql);                
        
        if ($sql->rowCount() > 0) {
            $sql = $sql->fetch();                        

            $Mailer = new PHPMailer\PHPMailer\PHPMailer();

            //Define que será usado SMTP
            // 1 = errors and messages
            // 2 = messages only
            $Mailer->SMTPDebug = 0;


            /* $Mailer->SMTPOptions = array(
              'ssl' => array(
              'verify_peer' => false,
              'verify_peer_name' => false,
              'allow_self_signed' => true
              )
              ); */
            //Define que será usado SMTP
            $Mailer->IsSMTP();                      

            //Configurações
            $Mailer->SMTPAuth = true;
            $Mailer->SMTPSecure = 'tsl';
            
            //Aceitar carasteres especiais
            $Mailer->Charset = 'UTF-8';

            //nome do servidor
            $Mailer->Host = $sql['smtp'];
            
            //Porta de saida de e-mail 
            $Mailer->Port = $sql['portaSmtp'];

            //Dados do e-mail de saida - autenticação
            $Mailer->Username = $sql['email'];
            $Mailer->Password = $sql['senha'];

            //E-mail remetente (deve ser o mesmo de quem fez a autenticação)
            $Mailer->From = $sql['remetente'];

            //Nome do Remetente
            $Mailer->FromName = utf8_decode($nomeRemetente);

            //Enviar e-mail em HTML
            $Mailer->isHTML(true);
            
            //Assunto da mensagem
            $Mailer->Subject = utf8_decode($assuntoMensagem);

            //Corpo da Mensagem
            $Mailer->Body = utf8_decode($mensagem);

            //Corpo da mensagem em texto
            //$Mailer->AltBody = utf8_decode('conteudo do E-mail em texto');
            //Destinatario 
            $Mailer->AddAddress($destinatario);

            if (!$Mailer->Send()) {
                //echo 'Contato não pode ser enviado.';
                //echo 'Erro: ' . $Mailer->ErrorInfo;
                return false;
            } else {
                //echo 'Contato enviado com sucesso!';
            }

            /* try{
              $Mailer->Send();
              }catch (Exception $e){
              print_r($e->getMessage());
              } */
        }
    }

    public function getTemplate($texto)
    {
        
        $template = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
            <html xmlns="http://www.w3.org/1999/xhtml">
                <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                    <title>Sistema de Gestão de Acesso</title>
                    <meta name="viewport" content="width=device-width" />
                   <style type="text/css">
                        @media only screen and (max-width: 550px), screen and (max-device-width: 550px) {
                            body[yahoo] .buttonwrapper { background-color: transparent !important; }
                            body[yahoo] .button { padding: 0 !important; }
                            body[yahoo] .button a { background-color: #4ca5a9; padding: 15px 25px !important; }
                        }

                        @media only screen and (min-device-width: 601px) {
                            .content { width: 600px !important; }
                            .col387 { width: 387px !important; }
                        }
                    </style>
                </head>
                <body bgcolor="#fff" style="margin: 0; padding: 0;" yahoo="fix">
                    <!--[if (gte mso 9)|(IE)]>
                    <table width="600" align="center" cellpadding="0" cellspacing="0" border="0">
                      <tr>
                        <td>
                    <![endif]-->
                    <table align="center" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse; width: 100%; max-width: 600px;" class="content">                            
                        <tr>
                            <td align="center" bgcolor="#2A3F54" style="padding: 20px 20px 20px 20px; color: #ffffff; font-family: Arial, sans-serif; font-size: 36px; font-weight: bold;">
                                <img src="'.URL.'/assets/images/logoMenu.png" alt="Sistema de Gestão de Acessos" width="203" height="79" style="display:block;" />
                            </td>
                        </tr>
                        <tr>
                            <td align="center" bgcolor="#f0f0f0" style="padding: 40px; color: #555555; font-family: Arial, sans-serif; font-size: 20px;">
                                '.$texto.'                               
                            </td>
                        </tr>                        
                        <tr>
                            <td align="center" bgcolor="#dddddd" style="padding: 15px 10px 15px 10px; color: #555555; font-family: Arial, sans-serif; font-size: 12px; line-height: 18px;">
                                <a href="http://www.bitistech.com.br" style="color: #4ca5a9;">
                                    Sistema de Gestão de Acessos (SGA) &copy; 2019 www.bitistech.com.br | versão: 2.03.000
                                </a>
                            </td>
                        </tr>                        
                    </table>
                    <!--[if (gte mso 9)|(IE)]>
                            </td>
                        </tr>
                    </table>
                    <![endif]-->
                </body>
            </html>';
        return $template;
    }
}
