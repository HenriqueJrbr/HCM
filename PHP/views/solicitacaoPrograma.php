<style type="text/css">
	@media (min-width: 992px){
		.modal-lg {
	    	width: 1250px;
		}
	}
</style>
<div class="x_panel">
	<div class="row">
		<div class="col-md-12">
			<div class="x_title">
		        <h2>Solicitação de Programa</h2>
		        <div class="clearfix"></div>
		     </div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-3">
			<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#myModal">Fluxo</button>
		</div>
	</div>
	<div class="row">
		<div class="col-md-3">
			<label>Usuário</label>
			<select name="usuarioSolicProg" id="usuarioSolicProg" class="form-control">
				<option></option>
				<?php foreach ($usuarios as $value):?>
					<option value="<?php echo $value['idUsuario']; ?>"><?php echo $value['nome_usuario'];?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="col-md-3">
			<label><br></label>
			<input type="text" name="nomeUsuarioSolicProg" id="nomeUsuarioSolicProg" class="form-control" readonly="readonly">
		</div>
		<div class="col-md-3">
			<label><br></label>
			<input type="text" name="gestorUsrSolicProg" id="gestorUsrSolicProg" class="form-control" readonly="readonly">
		</div>
	</div>
</div>
<div class="row">
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2><i class="fa fa-bars"></i> Acessos do Usuário</h2>
        <div class="clearfix"></div>
      </div>
      <div class="" role="tabpanel" data-example-id="togglable-tabs">
          <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
            <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Matriz de Risco</a>
            </li>
            <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Programas</a>
            </li>
            <li role="presentation" class=""><a href="#tab_content3" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Grupos</a>
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
            <div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="profile-tab">
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
						            <tr role="row">
						              <th class="col-sm-3 col-md-3">Grupo</th>
						              <th class="col-sm-3 col-md-3">Descrição</th>
						              <th class="col-sm-3 col-md-6">Gestor</th>
						            </tr>
						          </thead>
						          <tbody id="carregaGrupo"> 
						       
						       

						           </tbody>
				        		</table>
				    		</div>
				    	</div>
			    	</div>
			    </div>
            </div><!--fim table-->
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
        <h4 class="modal-title">Fluxo - Referencia de Programa</h4>
      </div>
      <div class="modal-body">
       	<img src="/sga/assets/images/programa.jpg">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
      </div>
    </div>

  </div>
</div>