<?php if(empty($dados)){
	$usuarioReferencia = "";
	$usuarioSolicitante = "";
	$nomeUsuarioReferencia = "";
	$gestorUsrReferencia = "";
	$nomeUsuarioSolicitante = "";
	$gestorUsrSolicitante = "";
	$idGestorSoclicitante = "";
	$aprovadoresGrupo = "";
	$solicitante = "";
	$idSolicitante = "";
}else{
	$usuarioReferencia = $dados['usuarioReferencia'];
	$usuarioSolicitante = $dados['usuarioSolicitante'];
	$nomeUsuarioReferencia = $dados['nomeUsuarioReferencia'];
	$gestorUsrReferencia = $dados['gestorUsrReferencia'];
	$nomeUsuarioSolicitante = $dados['nomeUsuarioSolicitante'];
	$gestorUsrSolicitante = $dados['gestorUsrSolicitante'];
	$idGestorSoclicitante = $dados['idGestorSoclicitante'];
	$solicitante = $dados['solicitante'];
	$aprovadoresGrupo = $dados['aprovadoresGrupo'];

	$aprovaGestor = $dados['aprovaGestor'];
	$obsGestor = $dados['obsGestor'];
	$idSolicitante = $dados['idSolicitante'];
} 
?>


<style type="text/css">
	@media (min-width: 992px){
		.modal-lg {
	    	width: 1250px;
		}
	}

	.panel-heading{
    color: #fff;
    background-color: #2A3F54;
    border-color: #337ab7;
	}
</style>
<form method="POST">
	<input type="text" name="fluxoAtividade" id="fluxoAtividade" value="<?php echo $numAtiv ?>">
	<input type="text" name="aprovadoresGrupo" id="aprovadoresGrupo" value="<?php echo $aprovadoresGrupo ?>">
	<div class="x_panel">
		<div class="row">
			<div class="col-md-12">
				<div class="x_title">
			        <h2><i class="fa fa-user"></i> Usuário por Referencia</h2>
			        <div class="clearfix"></div>
			     </div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-3">
				<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#myModal">Fluxo</button>
			</div>
			<div class="col-md-6">
				
			</div>
			<div class="col-md-3">
				<input type="submit" class="btn btn-primary btn-sm" name="enviar" value="Enviar">
			</div>
		</div>
		<div class="row">
			<div class="col-md-3">
				<input type="text" name="solicitante" id="solicitante" class="form-control" value="<?php echo $solicitante ?>" disabled="disabled">
				<input type="text" name="idSolicitante" id="idSolicitante" class="form-control" value="<?php echo $idSolicitante ?>" readonly="readonly">
			</div>
		</div>
		<div class="row">
			<div class="col-md-3">
				<label>Usuário Referencia</label>
				<select name="usuarioReferencia" id="usuarioReferencia" class="form-control" disabled="disabled">
					<option></option>
					<?php foreach ($usuarios as $value):?>
						<option value="<?php echo $value['idUsuario']; ?>" <?php if($value['idUsuario'] == $usuarioReferencia ){echo "selected";} ?> ><?php echo $value['cod_usuario'];?></option>
					<?php endforeach; ?>

				</select>
			</div>
			<div class="col-md-3">
				<label><br></label>
				<input type="text" name="nomeUsuarioReferencia" id="nomeUsuarioReferencia" value="<?php echo $nomeUsuarioReferencia ?>" class="form-control" readonly="readonly">
			</div>
			<div class="col-md-3">
				<label><br></label>
				<input type="text" name="gestorUsrReferencia" id="gestorUsrReferencia" value="<?php echo $gestorUsrReferencia ?>" class="form-control" readonly="readonly">
			</div>
		</div>
		<div class="row">
			<div class="col-md-3">
				<label>Usuário</label>
				<select name="usuarioSolicitante" id="usuarioSolicitante" class="form-control" disabled="disabled">
					<option></option>
					<?php foreach ($usuarios as $value):?>
						<option value="<?php echo $value['idUsuario']; ?>" <?php if($value['idUsuario'] == $usuarioSolicitante ){echo "selected";} ?>><?php echo $value['cod_usuario'];?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="col-md-3">
				<label><br></label>
				<input type="text" name="nomeUsuarioSolicitante" id="nomeUsuarioSolicitante" value="<?php echo $nomeUsuarioSolicitante ?>" class="form-control" readonly="readonly">
			</div>
			<div class="col-md-3">
				<label><br></label>
				<input type="text" name="gestorUsrSolicitante" id="gestorUsrSolicitante" value="<?php echo $gestorUsrSolicitante ?>" class="form-control" readonly="readonly">
				<input type="text" name="idGestorSoclicitante" id="idGestorSoclicitante" value="<?php echo $idGestorSoclicitante ?>" class="form-control hide" readonly="readonly">
			</div>
		</div>
	</div>
	<div class="x_panel">
		<div class="aprovaGestor">
			<div class="row">
				<div class="col-md-3"></div>
				<div class="col-md-5">
				  <div class="panel panel-primary">
				      <div class="panel-heading"><center>Aprovação Gestor</center></div>
				      <div class="panel-body">

				      	<select class="form-control" name="aprovaGestor" id="aprovaGestor" required="required">
				      		<?php if(!empty($aprovaGestor) ): ?>
				      			<?php if($aprovaGestor == "sim"):  ?>
				      				<option value="<?php echo $aprovaGestor ?>">Sim</option>
				      				<option value="nao">Não</option>
				      			<?php endif; ?>
				      			
				      			<?php if($aprovaGestor == "nao"):  ?>
				      				<option value="<?php echo $aprovaGestor ?>">Não</option>
				      				<option value="sim">Sim</option>
				      			<?php endif; ?>
				      		<?php endif; ?>
				      		<?php if(empty($aprovaGestor)): ?>
				      			<option value=""></option>
				      			<option value="sim">Sim</option>
				      			<option value="nao">Não</option>
				      		<?php endif ;?>
				      	</select>
				      	<label>Observação</label>
				      	<textarea class="form-control" name="obsGestor" id="obsGestor" rows="5" required="readonly"><?php echo $obsGestor ?></textarea>
				      </div>
				  
					</div>
				</div>
				<div class="col-md-4"></div>
			</div>
		</div>
	</div>
	<div class="x_panel">
		<div class="x_content">
	        <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
	        	<div class="row">
	        		<div class="col-sm-6"></div>
	        		<div class="col-sm-6"></div>
	        	</div>
	        	<div class="row">
	        		<div class="col-sm-12">
	        			<table  class="tabelaGrupoReferencia table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info" style="width: 100%;">
				          <thead>
				            <tr>
				              <th width="2%">#</th>
				              <th width="20%">Grupo</th>
				              <th width="20%">Descrição</th>
				              <th width="20%">Gestor</th>
				            </tr>
				          </thead>
				          <tbody id="carregaGrupo">
				         
				          		<?php foreach ($dadosFilho as $value): ?>
				          			<tr>
				          				<td><button type="button" class=" btn btn-danger btn-sm" onclick="deletaLinha('<?php echo $value['id'] ?>')">Excluir</button></td>
				          				<td><input type="" name=""  class="form-control" style="width: 100%" readonly="readonly" value="<?php echo $value['grupo'] ?>"></td>
				          				<td><input type="" name="" class="form-control" style="width: 100%" readonly="readonly" value="<?php echo $value['descricao'] ?>"></td>
				          				<td><input type="" name="" class="form-control" style="width: 100%" readonly="readonly" value="<?php echo $value['gestor'] ?>"></td>
				          			</tr>
				          		<?php endforeach; ?>
				          	
				          </tbody>
		        		</table>
		    		</div>
		    	</div>
	    	</div>
	    </div>
	</div>
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
	    <div class="x_panel">
	      <div class="x_title">
	        <h2><i class="fa fa-bars"></i> Acessos Usuário Referencia</h2>
	        <div class="clearfix"></div>
	      </div>
	      <div class="" role="tabpanel" data-example-id="togglable-tabs">
	          <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
	            <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Matriz de Risco</a>
	            </li>
	            <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Programas</a>
	            </li>
	          </ul>
	          <div id="myTabContent" class="tab-content">
	            <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
	              


	            </div>
	            <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
	              <div class="x_content">
				        <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
				        	<div class="row">
				        		<div class="col-sm-6"></div>
				        		<div class="col-sm-6"></div>
				        	</div>
				        	<div class="row">
				        		<div class="col-sm-12">
				        			<table  class="tabelaProgReferencia table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info" style="width: 100%;">
							          <thead>
							            <tr role="row">
							              <th>Grupo</th>
							              <th>Descrição</th>
							              <th>Programa</th>
							              <th>Descrição</th>
							          	</tr>
							          </thead>
							          <tbody id="carregaPrograma"> 
							           </tbody>
					        		</table>
					    		</div>
					    	</div>
				    	</div>
				    </div>
	            </div>
	          </div>
	        </div>
	    </div>
	  </div>
	</div>

	<div id="myModal" class="modal fade" role="dialog">
	  <div class="modal-dialog modal-lg">

	    <!-- Modal content-->
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title">Fluxo - Referencia por Usuário</h4>
	      </div>
	      <div class="modal-body">
	       	<img src="<?php echo URL ?>/assets/images/referencia.jpg">
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
	      </div>
	    </div>

	  </div>
	</div>
</form>