<legend>Documentação das APIs</legend>
<table class="table">
	<thead>
		<tr>
			<th>API</th>
			<th>Descrição</th>
			<th>Parâmetro</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><?php echo URL ?>/api/token</td>
			<td>Está API gera o token para autenticação</td>
			<td>{usuario,senha}</td>
		</tr>
		<tr>
			<td><?php echo URL ?>/api/atualizaGestorUsuario</td>
			<td>Está API é usada para atualizar o gestor do usuário</td>
			<td>{token,codUsuario,codGestor}</td>
		</tr>
		<tr>
			<td><?php echo URL ?>/api/cadastraGestorUsuario</td>
			<td>Está API é usada para cadatrar um gestor para um usuário</td>
			<td>{token,idGestor,idUsuario}</td>
		</tr>
		<tr>
			<td><?php echo URL ?>/api/cadastraUsuario</td>
			<td>Está API é usada para cadatrar um novo usuário no SGA</td>
			<td>{token,cod_usuario,nome_usuario,cpf,cod_gestor,cod_funcao,funcao,email,solicitante,gestor_usuario,gestor_grupo,gestor_programa,si,idUsrFluig,empresa}</td>
		</tr>
		<tr>
			<td><?php echo URL ?>/api/excluirUsuario</td>
			<td>Está API apaga o usuario do SGA</td>
			<td>{codUsuario,idEmpresa}</td>
		</tr>


		
	</tbody>
</table>
