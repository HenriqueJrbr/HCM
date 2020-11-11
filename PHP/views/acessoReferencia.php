<style type="text/css">
	@media (min-width: 992px){
		.modal-lg {
	    	width: 1250px;
		}
	}
</style>
<form method="POST">
	<input type="text" name="fluxoAtividade" id="fluxoAtividade" value="<?php echo $numAtividade ?>">
	<input type="text" name="aprovadoresGrupo" id="aprovadoresGrupo">
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
				<label>Solicitante</label>
				<input type="text" name="solicitante" id="solicitante" class="form-control" value="<?php echo $_SESSION['nomeUsuario'] ?>" readonly="readonly">
				<input type="text" name="idSolicitante" id="idSolicitante" class="form-control hide" value="<?php echo $_SESSION['idUsrTotvs'] ?>" readonly="readonly">
			</div>
		</div>
		<div class="row">
			<div class="col-md-3">
				<label>Usuário Referencia</label>
				<select name="usuarioReferencia" id="usuarioReferencia" class="form-control" required="required">
					
					
					<option></option>
					<?php foreach ($usuarios as $value):?>
						<option value="<?php echo $value['idUsuario']; ?>"><?php echo $value['cod_usuario'];?></option>
					<?php endforeach; ?>

				</select>
			</div>
			<div class="col-md-3">
				<label><br></label>
				<input type="text" name="nomeUsuarioReferencia" id="nomeUsuarioReferencia" class="form-control" readonly="readonly">
			</div>
			<div class="col-md-3">
				<label><br></label>
				<input type="text" name="gestorUsrReferencia" id="gestorUsrReferencia" class="form-control" readonly="readonly">
			</div>
		</div>
		<div class="row">
			<div class="col-md-3">
				<label>Usuário</label>
				<select name="usuarioSolicitante" id="usuarioSolicitante" class="form-control" required="required">
					<option></option>
					<?php foreach ($usuarios as $value):?>
						<option value="<?php echo $value['idUsuario']; ?>"><?php echo $value['cod_usuario'];?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="col-md-3">
				<label><br></label>
				<input type="text" name="nomeUsuarioSolicitante" id="nomeUsuarioSolicitante" class="form-control" readonly="readonly">
			</div>
			<div class="col-md-3">
				<label><br></label>
				<input type="text" name="gestorUsrSolicitante" id="gestorUsrSolicitante" class="form-control" readonly="readonly">
				<input type="text" name="idGestorSoclicitante" id="idGestorSoclicitante" class="form-control hide" readonly="readonly">
			</div>
		</div>
	</div>
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
			  
			              <th width="20%">Grupo</th>
			              <th width="20%">Descrição</th>
			              <th width="20%">Gestor</th>
			            </tr>
			          </thead>
			          <tbody id="carregaGrupo">
			    
			          </tbody>
	        		</table>
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
	       	<img src="assets/images/referencia.jpg">
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
	      </div>
	    </div>

	  </div>
	</div>
</form>