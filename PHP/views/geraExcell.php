<?php
session_start();
/*
* Criando e exportando planilhas do Excel
* /
*/
// Definimos o nome do arquivo que será exportado

//$conexao = "mysql:dbname=sga_v2.2;host=localhost";
//	$dbuser = "root";
//	$dbpass = "ngf#2018@@";
//
//	try{
//		$pdo = new PDO($conexao,$dbuser,$dbpass);
//	}catch(PDOException $e){
//		echo"Erro na Conexao ".$e->geMessage();
//	}

if(isset($_SESSION['empresaid']) && !empty($_SESSION['empresaid'])){
    require '../config.php';
    $pdo = $db;
    $dadosUser = '';
	$html = '';
	$html .= '<table border="1">';

		$html .= '<tr style="background: #2A3F54;">';
			$html .= '<td colspan="5" style="color: white"><center><b>Detalhamento de Acessos DataSul<b></center></tr>';
		$html .= '</tr>';

		$html .= '<tr>';
			$html .= '<td colspan="5"></tr>';
		$html .= '</tr>';


	 $idUsuario = $_GET['idUsuario'];
	 $idEmpresa = $_GET['idEmpresa'];
        

	 $sql = "SELECT * from z_sga_usuario_empresa as userEmp JOIN z_sga_usuarios as u on u.z_sga_usuarios_id = userEmp.idUsuario where userEmp.idEmpresa = '$idEmpresa' and userEmp.idUsuario = '$idUsuario'";
		$sql = $pdo->query($sql);
		if($sql->rowCount()>0){

				$dadosUser = $sql->fetch();
				$html .= '<tr>';

				$html .= '<td><b>Usu&aacute;rio:&nbsp</b> '.$dadosUser['nome_usuario'].'&nbsp&nbsp&nbsp</td>';
				$html .= '<td><b>C&oacute;digo DataSul:&nbsp</b> '.$dadosUser['cod_usuario'].'&nbsp&nbsp&nbsp</td>';
				$html .= '<td><b>C&oacute;digo Fluig:&nbsp</b> '.$dadosUser['idUsrFluig'].'&nbsp&nbsp&nbsp</td>';
				if($dadosUser['funcao'] == ''){$funcao  = "N&atilde;o cadastrado";}else{$funcao = $dadosUser['funcao'];}
				$html .= '<td><b>Funç&atilde;o:&nbsp</b>'.$funcao.'&nbsp&nbsp&nbsp</td>';
				if($dadosUser['email'] == ''){$email  = "N&atilde;o cadastrado";}else{$email = $dadosUser['email'];}
				$html .= '<td><b>E-mail:&nbsp</b>'.$email.'&nbsp&nbsp&nbsp</td>';
				
				$html .= '</tr>';

				$html .= '<tr>';

				$gestor = "SELECT * FROM z_sga_usuarios where cod_usuario = '".$dadosUser['cod_gestor']."'";
				$gestor = $pdo->query($gestor);
				$dadosGestor = $gestor->fetch();

				$html .= '<td><b>Gestor do usu&aacute;rio:&nbsp</b> '.$dadosGestor['nome_usuario'].'&nbsp&nbsp&nbsp</td>';
				if($dadosUser['gestor_grupo'] == 'S'){$gestorGrupo  = "Sim";}else{$gestorGrupo = "N&atilde;o";}
				$html .= '<td><b>&Eacute; gestor de grupo? </b> '.$gestorGrupo.'&nbsp&nbsp&nbsp</td>';

				if($dadosUser['gestor_usuario'] == 'S'){$gestorUsuario  = "Sim";}else{$gestorUsuario = "N&atilde;o";}
				$html .= '<td><b>&Eacute; gestor de Usu&aacute;rio? </b> '.$gestorUsuario.'&nbsp&nbsp&nbsp</td>';

				$html .= '<td>'.'</td>';
				
				$html .= '<td>'.'</td>';
				
				$html .= '</tr>';
		}
	$html .= '</table>';
	


	$html .= '<br></br>';
	$html .= '<table border="1">';
		$html .= '<tr style="background: #2A3F54;">';
			$html .= '<td colspan="2" style="color: white"><center><b>Grupos<b></center></tr>';
		$html .= '</tr>';
	$html .= '<tr>';
	$html .= '<td colspan="2"></tr>';
	$html .= '</tr>';
	$html .= '<tr>';
	$html .= '<td><b>ID Grupo</b></td>';
	$html .= '<td><b>Descricao</b></td>';
	$html .= '</tr>';

	 $idUsuario = $_GET['idUsuario'];
	 $idEmpresa = $_GET['idEmpresa'];


	 $sql = "SELECT g.idLegGrupo, g.descAbrev, (select ui.nome_usuario from z_sga_usuarios as ui where ui.cod_usuario = gs.gestor) as nomeGestor
                  from z_sga_grupos as gs,
                  z_sga_usuarios as u,
                  z_sga_grupo as g
                  where gs.idUsuario = '$idUsuario'
                  and g.idGrupo = gs.idGrupo
                  and u.z_sga_usuarios_id = gs.idUsuario";

		$sql = $pdo->query($sql);
		if($sql->rowCount()>0){
			foreach ($sql->fetchAll() as $value) {
				
				$html .= '<tr>';
				$html .= '<td>'.$value['idLegGrupo'].'</td>';
				$html .= '<td>'.$value['descAbrev'].'</td>';
				$html .= '</tr>';
			}
		}
	$html .= '</table>';

	$html .= '<br></br>';
	$html .= '<table border="1"> ';

	$html .= '<tr style="background: #2A3F54;">';
			$html .= '<td colspan="4" style="color: white"><center><b>Programas Duplicados<b></center></tr>';
		$html .= '</tr>';
	$html .= '<tr>';
	$html .= '<td colspan="4"></tr>';
	$html .= '</tr>';
	$html .= '<tr>';
	$html .= '<td><b>ID Grupo</b></td>';
	$html .= '<td><b>Descricao</b></td>';
	$html .= '<td><b>Programa</b></td>';
	$html .= '<td><b>Descricao</b></td>';
	$html .= '</tr>';

	 $idUsuario = $_GET['idUsuario'];
	 $idEmpresa = $_GET['idEmpresa'];


	 $sql = "SELECT u.z_sga_usuarios_id, u.cod_usuario, u.nome_usuario, g.descAbrev,g.idLegGrupo, p.cod_programa,p.descricao_programa
                        FROM
                            z_sga_usuarios AS u,
                            z_sga_usuario_empresa AS eu,
                            z_sga_grupos AS gu,
                            z_sga_grupo AS g,
                            z_sga_grupo_programa AS gp,
                            z_sga_programas AS p
                          WHERE u.z_sga_usuarios_id = '$idUsuario'
                          AND eu.idUsuario = u.z_sga_usuarios_id
                          AND gu.idUsuario = u.z_sga_usuarios_id
                          AND g.idGrupo = gu.idGrupo
                          AND g.idGrupo <> '13'
                          AND g.idEmpresa = eu.idEmpresa
                          AND gp.idGrupo = gu.idGrupo
                          AND p.z_sga_programas_id = gp.idPrograma
                          AND eu.idEmpresa = '$idEmpresa'
                          and gp.cod_programa in 
                            (SELECT p.cod_programa
                             FROM z_sga_usuarios AS u,
                                z_sga_usuario_empresa AS eu,
                                z_sga_grupos AS gu,
                                z_sga_grupo AS g,
                                z_sga_grupo_programa AS gp,
                                z_sga_programas AS p
                          WHERE u.z_sga_usuarios_id = '$idUsuario'
                          AND eu.idUsuario = u.z_sga_usuarios_id
                          AND gu.idUsuario = u.z_sga_usuarios_id
                          AND g.idGrupo = gu.idGrupo
                          AND g.idGrupo <> '13'
                          AND g.idEmpresa = eu.idEmpresa
                          AND gp.idGrupo = gu.idGrupo
                          AND p.z_sga_programas_id = gp.idPrograma
                          AND eu.idEmpresa = '$idEmpresa'
                          group BY gp.cod_programa 
                        HAVING Count(gp.cod_programa) > 1
          )
          order by gp.cod_programa";

		$sql = $pdo->query($sql);
		if($sql->rowCount()>0){
			foreach ($sql->fetchAll() as $value) {
				
				$html .= '<tr>';
				$html .= '<td>'.$value['idLegGrupo'].'</td>';
				$html .= '<td>'.$value['descAbrev'].'</td>';
				$html .= '<td>'.$value['cod_programa'].'</td>';
				$html .= '<td>'.$value['descricao_programa'].'</td>';
				$html .= '</tr>';
			}
		}
	$html .= '</table>';

	$html .= '<br></br>';
	$html .= '<table border="1"> ';

	$html .= '<tr style="background: #2A3F54;">';
			$html .= '<td colspan="7" style="color: white"><center><b>Matriz de Risco<b></center></tr>';
	$html .= '</tr>';
	$html .= '<tr>';
	$html .= '<td colspan="7"></tr>';
	$html .= '</tr>';
	$html .= '<tr>';
	$html .= '<td><b>Area</b></td>';
	$html .= '<td><b>Risco</b></td>';
	$html .= '<td style="background-color: black; color: #FFFFFF"><b>Grau de Risco</b></td>';
	$html .= '<td><b>Processo Referencia</b></td>';
	$html .= '<td><b>Programa do Processo</b></td>';
	$html .= '<td ><b>Processo Vinculados</b></td>';
	$html .= '<td ><b>Programa do Processo</b></td>';
	$html .= '</tr>';

	 $idUsuario = $_GET['idUsuario'];
	 $idEmpresa = $_GET['idEmpresa'];


	 $sql = "SELECT * FROM v_sga_mtz_matriz_usuario where idUsuario = '$idUsuario' and idEmpresa = '$idEmpresa' order by descArea,codRisco,processoPri";

		$sql = $pdo->query($sql);
		if($sql->rowCount()>0){
			foreach ($sql->fetchAll() as $value) {

		

				$back = $value["bgcolor"];
				$cor = $value["fgcolor"];
				
				$html .= '<tr>';
				$html .= '<td>'.$value['codRisco'].'</td>';
				$html .= '<td>'.$value['descRisco'].'</td>';
				$html .= '<td style="background-color:'.$back.';color:'.$cor.'">'.$value['grau'].'</td>';
				$html .= '<td>'.$value['processoPri'].'</td>';
				$html .= '<td>'.$value['progspPri'].'</td>';
				$html .= '<td>'.$value['processoSec'].'</td>';
				$html .= '<td>'.$value['progspSec'].'</td>';

					
				$html .= '</tr>';
			}
		}
	$html .= '</table>';


	$html .= '<br></br>';
	$html .= '<table border="1"> ';

	$html .= '<tr style="background: #2A3F54;">';
			$html .= '<td colspan="5" style="color: white"><center><b>Processos Matriz de Risco<b></center></tr>';
		$html .= '</tr>';
	$html .= '<tr>';
	$html .= '<td colspan="5"></tr>';
	$html .= '</tr>';
	$html .= '<tr>';
	$html .= '<td><b>Processo</b></td>';
	$html .= '<td><b>Descrição</b></td>';
	$html .= '<td><b>Programa</b></td>';
	$html .= '<td><b>Descrição</b></td>';
	$html .= '<td><b>Grupos</b></td>';
	$html .= '</tr>';

	 $idUsuario = $_GET['idUsuario'];
	 $idEmpresa = $_GET['idEmpresa'];


	 $sql = "SELECT * FROM v_sga_mtz_usuario_processos_matriz where idUsuario = '$idUsuario' and empresa = '$idEmpresa' order by GrupoProcesso";

		$sql = $pdo->query($sql);
		if($sql->rowCount()>0){
			foreach ($sql->fetchAll() as $value) {
				
				$html .= '<tr>';
				$html .= '<td>'.$value['GrupoProcesso'].'</td>';
				$html .= '<td>'.$value['descProcesso'].'</td>';
				$html .= '<td>'.$value['cod_programa'].'</td>';
				$html .= '<td>'.$value['descricao_programa'].'</td>';
				$html .= '<td>'.$value['Grupos'].'</td>';
				$html .= '</tr>';
			}
		}
	$html .= '</table>';






	$html .= '<br></br>';
	$html .= '<table border="1"> ';

	$html .= '<tr style="background: #2A3F54;">';
			$html .= '<td colspan="4" style="color: white"><center><b>Programas<b></center></tr>';
		$html .= '</tr>';
	$html .= '<tr>';
	$html .= '<td colspan="4"></tr>';
	$html .= '</tr>';
	$html .= '<tr>';
	$html .= '<td><b>ID Grupo</b></td>';
	$html .= '<td><b>Descricao</b></td>';
	$html .= '<td><b>Programa</b></td>';
	$html .= '<td><b>Descricao</b></td>';
	$html .= '</tr>';

	 $idUsuario = $_GET['idUsuario'];
	 $idEmpresa = $_GET['idEmpresa'];


	 $sql = "SELECT u.z_sga_usuarios_id, u.cod_usuario, u.nome_usuario, g.descAbrev,g.idLegGrupo, p.cod_programa,p.descricao_programa
                FROM
                    z_sga_usuarios AS u,
                    z_sga_usuario_empresa AS eu,
                    z_sga_grupos AS gu,
                    z_sga_grupo AS g,
                    z_sga_grupo_programa AS gp,
                    z_sga_programas AS p
                  WHERE u.z_sga_usuarios_id = '$idUsuario'
                  AND eu.idUsuario = u.z_sga_usuarios_id
                  AND gu.idUsuario = u.z_sga_usuarios_id
                  AND g.idGrupo = gu.idGrupo
                  AND g.idGrupo <> '13'
                  AND g.idEmpresa = eu.idEmpresa
                  AND gp.idGrupo = gu.idGrupo
                  AND p.z_sga_programas_id = gp.idPrograma
                  AND eu.idEmpresa = '$idEmpresa'";

		$sql = $pdo->query($sql);
		if($sql->rowCount()>0){
			foreach ($sql->fetchAll() as $value) {
				
				$html .= '<tr>';
				$html .= '<td>'.$value['idLegGrupo'].'</td>';
				$html .= '<td>'.$value['descAbrev'].'</td>';
				$html .= '<td>'.$value['cod_programa'].'</td>';
				$html .= '<td>'.$value['descricao_programa'].'</td>';
				$html .= '</tr>';
			}
		}
	$html .= '</table>';

	$arquivo = 'Dados de acesso SGA - '.$dadosUser['nome_usuario'].'.xls';
	// Configurações header para forçar o download
	header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
	header ("Cache-Control: no-cache, must-revalidate");
	header ("Pragma: no-cache");
	header ("Content-Type: text/csv; charset=utf-8");
	header ("Content-type: application/x-msexcel");
	header ("Content-Disposition: attachment; filename=\"{$arquivo}\"" );
	header ("Content-Description: PHP Generated Data" );
	// Envia o conteúdo do arquivo
	echo $html;


	exit;

}
?>


<script type="text/javascript">
	
</script>
