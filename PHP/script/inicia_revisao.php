<?php
set_time_limit(0);
session_start();

require 'conexao.php';
require '../core/Model.php';
require '../models/Fluxo.php';
require '../models/Email.php';

// Busca os agendamentos para o dia corrente com situação = 0. Que ainda não foram processados.
$sql = "
    SELECT
        a.idAgendamento,
        a.dataInicio,
        a.dataFim,
        a.idUsuario,
        a.idSolicitante,
        u.nome_usuario,
        e.razaoSocial AS empresa,
        a.idEmpresa As idEmpresa
    FROM 
        z_sga_fluxo_agendamento_acesso a
    LEFT JOIN
        z_sga_usuarios u
        ON a.idUsuario = u.z_sga_usuarios_id
    LEFT JOIN
        z_sga_empresa e
        ON a.idEmpresa = e.idEmpresa
    WHERE
        a.dataInicio BETWEEN '".date('Y-m-d 00:00:00')."' AND '".date('Y-m-d 23:59:59')."'
        AND a.situacao = 0
    ORDER BY
        dataInicio ASC";


$sql = $db->query($sql);

if($sql->rowCount() > 0):
    $sql = $sql->fetchAll(PDO::FETCH_ASSOC);
    $fluxo = new Fluxo();
    $email = new Email();
    $countAprov = 0;
    $gestoresMailer = [];    
    
    
    // Cria html de email a ser enviado.
    $mensagem = "<!DOCTYPE html>
    <html>
        <head><title></title></head>
        <body>  
            <h1>Olá! {gestor}</h1>
            <p>Existe uma nova atividade que está sob sua responsabilidade e precisa de sua ação.</p>
            <p>Aprovação: Revisão de Acesso do usuário <b> {usuario} </b></p>
            <p>Acesse: <a href='".URL."/fluxo/centralDeTarefa'>".URL."/fluxo/centralDeTarefa</a></p>
        </body>
    </html>";
    $assunto = "Revisão de Acesso";
    $nomeRemetente = "SGA - Sistema de Gestão de Acesso";

    foreach ($sql as $userAgenda):
        setlocale(LC_TIME, 'pt_BR');    
        date_default_timezone_set('America/Sao_Paulo');
        $dataMovimentacao = $userAgenda['dataInicio'];
        echo "<pre> Usuario Agenda";
        print_r($userAgenda);
        echo "</pre>";
        $whereUser = '';
        $idSolicitante = $userAgenda['idSolicitante'];
        //$numAprovadores = $fluxo->totalAtividade(7,3);
        $numAprovadores = 0;

        // Se idUsuario do agendamento for diferente a '*', adiciona à query uma clausula filtrando por usuário.
        // Se for igual a '*', busca por todos os usuários.
        if($userAgenda['idUsuario'] == '*'):
            // Busca o gestor dos usuários
            $sqlGest = $db->query("SELECT z_sga_usuarios_id as idGestor, cod_usuario, SI FROM z_sga_usuarios WHERE z_sga_usuarios_id = " . $userAgenda['idSolicitante'])->fetch(PDO::FETCH_ASSOC);
            if($sqlGest['SI'] == 'N'):
                $whereUser .= " AND u.cod_gestor = '".$sqlGest['cod_usuario']."'";
            elseif($sqlGest['SI'] == 'S'):

            endif;            
        elseif($userAgenda['idUsuario'] != '*'):
            $whereUser .= " AND u.z_sga_usuarios_id = ".$userAgenda['idUsuario'];
        endif;

        $sql = "
            SELECT 
                userEmp.idEmpresa,
                userEmp.idUsuario,
                u.z_sga_usuarios_id,
                u.cod_gestor,
                u.nome_usuario,
                u.cod_usuario,
                u.email,
                u.funcao
            FROM
                z_sga_usuario_empresa AS userEmp
                    JOIN
                z_sga_usuarios AS u ON u.z_sga_usuarios_id = userEmp.idUsuario
            WHERE
                userEmp.idEmpresa = ".$userAgenda['idEmpresa']."
                $whereUser";       
        
        $sql = $db->query($sql);
        
        if($sql->rowCount() > 0):
            $dados['usr'] = $sql->fetchAll(PDO::FETCH_ASSOC);

            echo "<pre>Usuários da revisão";
            print_r($dados['usr']);
            echo "</pre>";
            
            foreach ($dados['usr'] as  $value):
                $usuario = $value['nome_usuario'];
                $idusuario = $value['z_sga_usuarios_id'];
                $idTotvs = $value['cod_usuario'];
                $gestorUsuario = $value['cod_gestor'];
                $idGestor = "";
                $lista = array();

                echo "<pre>";
                print_r($value);
                echo "</pre>";

                if(empty($gestorUsuario)):
                    $gestorUsuario = "super";
                endif;

                // Retorna o gestor do usuário
                $sqlGestor = $db->query("SELECT * FROM z_sga_usuarios where cod_usuario = '$gestorUsuario'");

                if($sqlGestor->rowCount() > 0){
                    //$countAprov += 1;
                    $dados['idGestor'] = $sqlGestor->fetch();
                    $idGestor =  $dados['idGestor']['z_sga_usuarios_id'];
                }

                // Cria a solicitação e retorna o ID da mesma
                $data['idSolic'] = $fluxo->cadastraNumSolicitacao(3, $idSolicitante, $userAgenda['idAgendamento'], $dataMovimentacao);
                $idSolicitacao = $data['idSolic']['idSolic'];

                // Busca os dados do grupo de cada usuário a ser adicionado a um fluxo
                $sqlGrupos = " 
                    SELECT
                        gs.z_sga_grupos_id, 
                        g.idLegGrupo,
                        g.idGrupo, g.descAbrev,
                        (select ui.nome_usuario from z_sga_usuarios as ui  where ui.cod_usuario = gs.gestor) as nomeGestor, 
                        (select ui.cod_usuario from z_sga_usuarios as ui  where ui.cod_usuario = gs.gestor) as codGest,
                        (select ui.z_sga_usuarios_id from z_sga_usuarios as ui  where ui.cod_usuario = gs.gestor) as idCodGest,
                        (SELECT COUNT(*) FROM z_sga_grupo_programa grp WHERE grp.idGrupo = g.idGrupo) AS nrProgramas,
                        (SELECT COUNT(*) FROM z_sga_grupos grp WHERE grp.idGrupo = g.idGrupo) AS nrUsuarios
                    FROM 
                        z_sga_grupos as gs, 
                        z_sga_usuarios as u, 
                        z_sga_grupo as g 
                    WHERE 
                        gs.idUsuario = '$idusuario' and 
                        g.idGrupo = gs.idGrupo and 
                        g.idEmpresa = ".$userAgenda['idEmpresa']."
                        and u.z_sga_usuarios_id = gs.idUsuario";
                $sqlGrupos = $db->query($sqlGrupos);

                if($sqlGrupos->rowCount() > 0){
                    $dados['grupo'] = $sqlGrupos->fetchAll();

                    foreach ($dados['grupo'] as $grupo) {
                        $dadosGestorModulo = '';

                        // Busca se existe gestor de programas ou modulos para os programas existentes no grupo
                        $sqlGestorMod = "
                            Select 
                                usr.z_sga_usuarios_id AS idGestor,
                                usr.nome_usuario AS nomeGestor,
                                #usr.codGestor,
                                mpr.codMdlDtsul 	AS codModul,
                                 (SELECT descricao_modulo FROM z_sga_programas p WHERE p.cod_modulo = mpr.codMdlDtsul limit 1) as modulo,
                                mpr.codRotinaDtsul 	AS codRotina,
                                (SELECT descricao_rotina FROM z_sga_programas p WHERE p.codigo_rotina = mpr.codRotinaDtsul limit 1) as rotina,
                                mpr.codProgDtsul 	AS codProg,
                               (SELECT descricao_programa FROM z_sga_programas p WHERE p.cod_programa = mpr.codProgDtsul limit 1) as programa		
                            from  z_sga_gest_mpr_dtsul mpr,
                                   z_sga_usuarios usr
                            where 
                                mpr.idUsuario = usr.z_sga_usuarios_id
                                and if (mpr.codMdlDtsul <> '*' and 
                                         mpr.codRotinaDtsul = '*' and 
                                         mpr.codProgDtsul = '*',
                                 mpr.codMdlDtsul in (select distinct prg.cod_modulo from z_sga_grupo_programa grp, z_sga_programas prg  where grp.idGrupo in (".$grupo['idGrupo'].") and  prg.z_sga_programas_id = grp.idPrograma),
                                 if(mpr.codMdlDtsul <> '' and mpr.codRotinaDtsul <> '' and mpr.codProgDtsul = '*',
                                                          mpr.codMdlDtsul 	in (select distinct prg.cod_modulo from z_sga_grupo_programa grp, z_sga_programas prg     where grp.idGrupo in (".$grupo['idGrupo'].") and  prg.z_sga_programas_id = grp.idPrograma) and
                                                          mpr.codRotinaDtsul in (select distinct prg.codigo_rotina from z_sga_grupo_programa grp, z_sga_programas prg  where grp.idGrupo in (".$grupo['idGrupo'].") and  prg.z_sga_programas_id = grp.idPrograma)
                                                 ,
                                                         mpr.codProgDtsul in (select distinct prg.cod_programa from z_sga_grupo_programa grp, z_sga_programas prg     where grp.idGrupo in (".$grupo['idGrupo'].") and  prg.z_sga_programas_id = grp.idPrograma)
                                                  )
                                         )                                                    
                        ";
                        
                        //echo $sqlGestorMod;
                        $sqlGestorMod = $db->query($sqlGestorMod);
                        $gestModul = array();
                        $gestRot = array();
                        $gestProg = array();
                        
                        //print_r($sqlGestorMod);
                        
                        if($sqlGestorMod->rowCount() > 0):                            
                            $dadosGestorModulo = $sqlGestorMod->fetchAll(PDO::FETCH_ASSOC);
                            $idGestMod = [];
                            $idGestRot = [];
                            $idGestProg = [];
                            
                            foreach($dadosGestorModulo as $val):
                                if($val['codModul'] != '*' && $val['codRotina'] == '*' && $val['codProg'] == '*' && !in_array($val['idGestor'], $idGestMod)):
                                    $dadosMod = array(
                                        'id'        => $val['idGestor'],
                                        'nome'      => $val['nomeGestor'],
                                        'obs'       => '',
                                        'aprovacao' => '',
                                        'cartaRisco' => '',
                                        'modulo'    => $val['modulo']
                                    );
                                    $gestModul[] = $dadosMod;
                                    $idGestMod[] = $val['idGestor'];                                    
                                elseif($val['codModul'] != '*' && $val['codRotina'] != '*' && $val['codProg'] == '*' && !in_array($val['idGestor'], $idGestRot)):
                                    $dadosRot = array(
                                        'id'        => $val['idGestor'],
                                        'nome'      => $val['nomeGestor'],
                                        'obs'       => '',
                                        'aprovacao' => '',
                                        'cartaRisco' => '',
                                        'rotina'    => $val['rotina']
                                    );                                    
                                    $gestRot[] = $dadosRot;
                                    $idGestRot[] = $val['idGestor'];
                                elseif($val['codModul'] != '*' && $val['codRotina'] != '*' && $val['codProg'] != '*' && !in_array($val['idGestor'], $idGestProg)):
                                    $dadosProg = array(
                                        'id'        => $val['idGestor'],
                                        'nome'      => $val['nomeGestor'],
                                        'obs'       => '',
                                        'aprovacao' => '',
                                        'cartaRisco' => '',
                                        'programa'  => $val['programa']
                                    );                                    
                                    $gestProg[] = $dadosProg;
                                    $idGestProg[] = $val['idGestor'];
                                endif;

                                $numAprovadores++;
                            endforeach;
                        endif;

                        $dados = array(
                            "idLinhaGrupo"  => $grupo['z_sga_grupos_id'],
                            'nrProgramas'   => $grupo['nrProgramas'],
                            'nrUsuarios'    => $grupo['nrUsuarios'],
                            "idLegGrupo"    => $grupo['idLegGrupo'],
                            "idGrupo"       => $grupo['idGrupo'],
                            "descAbrev"     => $grupo['descAbrev'],
                            "nomeGestor"    => $grupo['nomeGestor'],
                            "codGest"       => $grupo['codGest'],
                            "idCodGest"     => $grupo['idCodGest'],
                            'manterStatus'      => 1,
                            'obs'               => '',
                            'aprovacao'         => '',
                            'cartaRisco'        => '',
                            'obsGestorUsuario'  => '',
                            'gestorModulo'      => $gestModul,
                            'gestorRotina'      => $gestRot,
                            'gestorPrograma'    => $gestProg                            
                        );
                        $lista[] = $dados;
                    }
                }                
                
                // Recupera o total de dias que cada atividade tem para movimentação
                $sqlTotalAtiv = "
                    SELECT 
                        count(*) AS total 
                    FROM 
                        z_sga_fluxo_atividade 
                    WHERE 
                        idFluxo = 3
                        AND descricao NOT IN('Final','Solicitante')";
                $sqlTotalAtiv = $db->query($sqlTotalAtiv)->fetch(PDO::FETCH_ASSOC);
                
                
                // Recupera o total de dias para aprovação de cada atividade
                $dateIni  = new DateTime($userAgenda['dataInicio']);
                $dateFim  = new DateTime($userAgenda['dataFim']);                
                $diasAprovacao = floor($dateIni->diff($dateFim)->days / $sqlTotalAtiv['total']);
                $numAprovadores++;
                
                // Prepara o JSON
                $dadosDoc = array(
                    'diasAprovacao'   => $diasAprovacao,
                    'dataInicio'      => $userAgenda['dataInicio'],
                    'dataFim'         => $userAgenda['dataFim'],
                    'idSolicitacao'   => $idSolicitacao,
                    'usuario'         => $usuario,
                    'idusuario'       => $idusuario,
                    'idTotvs'         => $idTotvs,
                    'gestorUsuario'   => $gestorUsuario,
                    'idGestorUsuario' => $idGestor,
                    'aprovacao_si'    => '',
                    'exigirCartaRisco' => '',
                    'numAprovadores'    => $numAprovadores,
                    'grupos'          => $lista
                );

                // Cria o JSON
                $documento = json_encode($dadosDoc,true);               

                echo $documento."<br>";

                // Cria o documento JSON
                $fluxo->criaDocumento($documento,3,3,$idSolicitacao);

                // Busca o id da próxima atividade.
                $idProximaAtiv = $fluxo->verificaProximaAtividade(7);
                $idProximaAtiv = $idProximaAtiv['proximaAtiv'];

                // Se próxima atividade for aprovação de gestor. Cria uma atividade para o gestor.
                if($idProximaAtiv == 8):

                    // Verifica se o gestor possui usuário alternativo
                    $sqlUserAlt = $db->query("SELECT * FROM z_sga_fluxo_substituto where idUsrSerSub = '$idGestor' AND status = 1");
                    if($sqlUserAlt->rowCount() > 0):
                        $rsUserAlt = $sqlUserAlt->fetchAll(PDO::FETCH_ASSOC);
                        // Atribuo o id do usuario alternativo para a variavel idGestor
                        $idGestor = $rsUserAlt[0]['idUsrSub'];
                    endif;

                    try{
                        $fluxo->cadastraMovimentacao($idSolicitacao,$idProximaAtiv,$dataMovimentacao,$idSolicitante,$idGestor,3, '');
                    }catch (Exception $e){
                        die($e->getMessage());
                    }                  
                    
                    // Recupera os dados do gestor e do usuario para email
                    $dadosEmailUsuario  = $email->cadadosUsuario($idusuario);
                    $dadosEmailGestor = $email->cadadosUsuario($idGestor);
                    
                    // Adiciona o usuario ao gestor e remove duplicados
                    $gestoresMailer[$dadosEmailGestor['nome_usuario']][$dataMovimentacao]['usuarios'][] = $dadosEmailUsuario['nome_usuario'];
                    $gestoresMailer[$dadosEmailGestor['nome_usuario']][$dataMovimentacao]['email'] = $dadosEmailGestor['email'];
                    //$gestoresMailer[$dadosEmailGestor['nome_usuario']][]['dataMovimentacao'] = $dataMovimentacao;
                    $gestoresMailer = array_unique($gestoresMailer, SORT_REGULAR);

                    //$mensagem = str_replace('{gestor}', $dadosEmailGestor['nome_usuario'], $mensagem);
                    //$mensagem = str_replace('{usuario}', $dadosEmailUsuario['nome_usuario'], $mensagem);
                    //$mensagem = str_replace('{observacao}', $observacao, $mensagem);
                    //enviaEmail($nomeRemetente,$assunto, $mensagem, $dadosEmailGestor['email'], $db);

                elseif($idProximaAtiv == 9):
                    // Se próxima atividade for aprovação de grupos. Cria uma atividade para cada gestor de grupo
                    // OBS.: Criar uma única atividade por gestor
                    $idGestores = array();

                    foreach($lista as $key => $valGrupo):
                        // Se o id do Gestor de grupos  não estiver no array de gestores cria atividade para o mesmo.
                        if(!in_array($valGrupo['idCodGest'], $idGestores)):
                            $idGestores[] = $valGrupo['idCodGest'];

                            // Verifica se o gestor possui usuário alternativo
                            $sqlUserAlt = $db->query("SELECT * FROM z_sga_fluxo_substituto where idUsrSerSub = ".$valGrupo['idCodGest']." AND status = 1");
                            if($sqlUserAlt->rowCount() > 0):
                                $rsUserAlt = $sqlUserAlt->fetchAll(PDO::FETCH_ASSOC);
                                // Atribuo o id do usuario alternativo para a variavel idGestor
                                $idGestorGrupo = $rsUserAlt[0]['idUsrSub'];
                            endif;
                            $fluxo->cadastraMovimentacao($idSolicitacao, $idProximaAtiv, $dataMovimentacao,1, $idGestorGrupo,3, '');                                                                                                               
                            
                            // Recupera os dados do gestor e do usuario para email
                            $dadosEmailUsuario  = $email->cadadosUsuario($idusuario);
                            $dadosEmailGestor = $email->cadadosUsuario($idGestorGrupo);
                            
                            // Adiciona o usuario ao gestor e remove duplicados
                            $gestoresMailer[$dadosEmailGestor['nome_usuario']][$dataMovimentacao]['usuarios'][] = $dadosEmailUsuario['nome_usuario'];
                            $gestoresMailer[$dadosEmailGestor['nome_usuario']][$dataMovimentacao]['email'] = $dadosEmailGestor['email'];
                            //$gestoresMailer[$dadosEmailGestor['nome_usuario']]['dataMovimentacao'] = $dataMovimentacao;
                            $gestoresMailer = array_unique($gestoresMailer, SORT_REGULAR);

                            //$mensagem = str_replace('{gestor}', $dadosEmailGestor['nome_usuario'], $mensagem);
                            //$mensagem = str_replace('{usuario}', $dadosEmailUsuario['nome_usuario'], $mensagem);
                            //$mensagem = str_replace('{observacao}', $observacao, $mensagem);
                            //enviaEmail($nomeRemetente,$assunto, $mensagem, $dadosEmailGestor['email'], $db);
                        endif;
                    endforeach;
                endif;

                // Atualiza a situacao do registro de agendamento.
                // 0 = A fazer, 1 = Feito
                $sqlUpdate = "
                    UPDATE 
                        z_sga_fluxo_agendamento_acesso 
                    SET 
                        situacao = 1 
                    WHERE 
                      idAgendamento = " . $userAgenda['idAgendamento'];
                $db->query($sqlUpdate);

                // Limpa as variaveis json e array grupos
                unset($documento);
                unset($lista);
                unset($idGestores);
            endforeach;
        endif;
    endforeach;
    
    //echo "<pre>";
    //print_r($gestoresMailer);
    enviaEmail($db, $gestoresMailer);
else:
    echo 'Nenhum registro encontrado';
endif;


function enviaEmail($db, $gestores)
{        
    $sql = "SELECT * FROM z_sga_param_email";
    $sql = $db->query($sql);

    if($sql->rowCount()>0){
        $sql = $sql->fetch();       

        $Mailer = new PHPMailer\PHPMailer\PHPMailer();
        $Mailer->SMTPDebug  = 1; // IMPRIME TODO O LOG DO PHPMAILER
               
        //Define que será usado SMTP
        $Mailer->IsSMTP();

        //Configurações
        $Mailer->SMTPAuth = true;
        $Mailer->SMTPSecure = 'tsl';          

        //Aceitar carasteres especiais
        $Mailer->Charset = 'UTF-8';       

        //nome do servidor
        //$Mailer->Host = 'smtp.'.substr(strstr($sql['email'], '@'), 1);
        $Mailer->Host = $sql['smtp'];
        
        //Porta de saida de e-mail
        $Mailer->Port = $sql['portaSmtp'];

        //Dados do e-mail de saida - autenticação
        $Mailer->Username = $sql['email'];
        $Mailer->Password = $sql['senha'];

        //E-mail remetente (deve ser o mesmo de quem fez a autenticação)
        $Mailer->From = $sql['remetente'];

        //Nome do Remetente
        $Mailer->FromName = utf8_decode("SGA - Sistema de Gestão de Acesso");

        //Enviar e-mail em HTML
        $Mailer->isHTML(true);
        
        //Assunto da mensagem
        $Mailer->Subject = utf8_decode('Revisão de Acesso');
        $email = new Email();        
        echo "<pre>";
        
        // Percorre os gestores e cria o email            
        foreach($gestores as $keyGestor => $valGetor):
            $mensagem = '
                <b>Olá! '.$keyGestor.'</b><br/><br/>
                <span style="font-size:14px;margin-top:20px">Informamos que a atividade abaixo, estará sob sua responsabilidade e precisará de sua ação.</span>
                <br/><br/>  
                <span style="font-size:14px;margin-top:20px"><b>Atividade:</b> Revisão de acesso do(s) usuário(s):</span><br/><br/>';
        
            foreach($valGetor as $keyData => $valData):
                $data = explode(' ', $keyData);
                $mensagem .= '<b style="font-size: 13px">Início:</b> <span style="font-size: 13px">'.implode('/', array_reverse(explode('-', $data[0]))). ' às '.$data[1].'</span><br>';
                foreach($valData['usuarios'] as $val):                    
                    $mensagem .= '<b style="font-size: 13px;float:left;margin-left:32%">'.$val.'</b>';
                endforeach;
                $mensagem .= '<br/>';
            endforeach;
            
            $mensagem .= '
                <br>
                <span style="font-size:14px;margin-top:20px">Acompanhe a solicitação em seu painel de atividades.</span>             
                <br/>
                <br/>				
                <a href="'.URL.'/fluxo/centralDeTarefa" style="background: #4ca5a9;color: #ffffff; text-align: center; text-decoration: none;padding: 15px 30px; font-family: Arial, sans-serif; font-size: 16px">Ir para Central de Tarefas</a>';                        
            $template = $email->getTemplate($mensagem);
            
            echo $template;
            
            //Corpo da Mensagem
            $Mailer->Body = utf8_decode($template);

            //Corpo da mensagem em texto
            //$Mailer->AltBody = 'conteudo do E-mail em texto';

            //Destinatario
            $Mailer->AddAddress($valData['email']);
            
            if(!$Mailer->Send()){
                $mensagemRetorno = 'Erro ao enviar formulário: '. print($Mailer->ErrorInfo);
            }else{
                $mensagemRetorno = 'Formulário enviado com sucesso!';
            } 
            usleep(4000000);
            
        //$email->enviaEmail($nomeRemetente, $assunto, $template, $valGetor['email']);
        endforeach;
                
        die;
        //$Mailer->Send();

    }


}
die;