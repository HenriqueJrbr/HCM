<script type="text/javascript">
	
$(document).ready(function(){

	$("#gestor").change(function(){
		var id = $(this).val();

		$.ajax({
	        type: "POST",
	        url: url+"Fluxo/ajaxConsultaGestor",
	        data:'idGestor='+id,
	        beforeSend: function(){           
	        },
	        success: function(data){


	        	table = $('.TableaddUsr').DataTable();
            	table.destroy();
	  			
	  		 	var dados = JSON.parse(data);
            	var html = '';
	            for(var i = 0;i<dados.length;i++){

	            	if(dados[i].nroRiscos > 0){
	            		var risco = "Sim"
	            	}

	            	if(dados[i].nroRiscos > 0){
	            		var risco = "Sim"
	            	}
	                html += "<tr>";
	          		 html += "<td>"+dados[i].nome_usuario+"</td>";
	          		 html += "<td>"+dados[i].cod_funcao+"</td>";
	          		 html += "<td><center>"+dados[i].nroInstancias+"</center></td>";
	          		 html += "<td>"+risco+"</td>";
	                html += "</tr>";
	            }

             	$(".addUsr").html(html);
             	$('.TableaddUsr').DataTable({
                    "language": {
                        "lengthMenu": "Exibição _MENU_ Registros por página",
                        "zeroRecords": "Registro nao encontrado",
                        "info": "Pagina _PAGE_ de _PAGES_",
                        "infoEmpty": "No records available",
                        "search":"Pesquisar:",
                        "paginate": {
                                "first":      "Primeiro",
                                "last":       "Último",
                                "next":       "Próximo",
                                "previous":   "Anterior"
                            },
                        "infoFiltered": "(Filtro de _MAX_ registro total)"
                    }
              	});
	   
	        }
    	});
	});
});	
</script>

<div class="col-md-12 col-sm-12 col-xs-12">
  <ol class="breadcrumb">
        <li><a href="<?php echo URL ?>/" onclick="loadingPagia()">Dashboard</a></li>              
        <li class="active">Processos</li>
        <li class="active">Inicia Restruturação de Acesso</li>
  </ol>
</div>
<form method="POST">
	<div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Inicia Restruturação de Acesso</h2>
            <ul class="nav navbar-right panel_toolbox">
              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
              </li>  
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
          	<div class="row">
          		<div class="col-md-11"></div>
          		<div class="col-md-1">
          			<label><br></label>
          			<input type="submit" name="iniciaRestruturacao" id="iniciaRestruturacao" value="Enviar" class="btn btn-info">
          		</div>
          	</div>
          	<div class="row">
          		<div class="col-md-4">
          			<label>Gestor</label>
          			<select class="form-control" name="gestor" id="gestor" required="required">
          				<option value=""></option>
          				<?php foreach ($listaGestor as $lista) :?>
          					<option value="<?php echo $lista['z_sga_usuarios_id'] ?>"><?php echo $lista['nome_usuario'] ?></option>
          				<?php endforeach;?>
          			</select>
          		</div>
          	</div>
          	<br><br>
          	<div class="row">
          		<div class="col-md-12">
          			<table class="TableaddUsr table table-striped table-bordered dataTable no-footer">
          				<thead>
          					<tr>
          						<th>Usuário</th>
          						<th>Função</th>
          						<th>Instancia</th>
          						<th>Risco</th>
          					</tr>
          				</thead>
          				<tbody class="addUsr">
          					
          				</tbody>
          			</table>
          		</div>
          	</div>
      			
          </div>
        </div>
      </div>
    </div>
	
</form>