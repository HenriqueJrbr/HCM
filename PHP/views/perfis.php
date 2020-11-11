<style>
    .dataTables_filter{
        width: 50%;
        margin-top: 15px !important;
        float: none !important;
        text-align: left !important;
    }

    .dataTables_filter, .dataTables_info { display: none; }

    /* > Para o input */
    .input-search{
        border:1px solid #CCC;
        padding:5px 14px;
        font-size:12px;
        margin:10px 0;
        float:right;
        width:100% !important;
        
        -webkit-border-radius:15px;
           -moz-border-radius:15px;
            -ms-border-radius:15px;
             -o-border-radius:15px;
                border-radius:15px;
    }
    .input-search::-webkit-input-placeholder{ font-style:italic }
    .input-search:-moz-placeholder          { font-style:italic }
    .input-search:-ms-input-placeholder     { font-style:italic }
    
    table tr td{
        padding: 5px
    }

    table tr td:last-child,
    table tr th:last-child{
        width: 50px !important;
        text-align: center !important;
    }
</style>

<div class="col-md-12 col-sm-12 col-xs-12">
  <ol class="breadcrumb">
      <li><a href="<?php echo URL ?>/" onclick="loadingPagia()">Dashboard</a></li>          
        <li class="active">Perfis</li>
  </ol>
</div>
<div class="col-md-12">
    <?php $this->helper->alertMessage(); ?>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Perfis
                </h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <button type="button" class="btn btn-success btn-sm" onclick="carregaAdd()" data-toggle="modal" data-target="#addModal">
        		<font style="vertical-align: inherit;">
        			<font style="vertical-align: inherit;">Adicionar Perfil</font>
        		</font>
        	</button>
            <div class="x_content">                
                <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                
                    <div class="row">
                        <div class="col-sm-12">

                            <table class="table table-striped hover table-bordered dt-responsive dataTable no-footer dtr-inline"
                                   cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info"
                                   style="width: 100%;">
                                <thead>
                                <tr role="row">
                                    <th>#</th>
                                    <th>Nome</th>
                                    <th>Ação</th>
                                </tr>
                                </thead>
                                <tbody id="datatableUser"></tbody>
                            </table>
                            <div class="teste"><center>Carregando...</center></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="editaModal" class="modal fade in" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Editar Perfil</h4>
            </div>
            <form action="<?php echo URL?>/Usuario/editarPerfil" method="post">
                <input type="text" name="idPerfil" id="idPerfil" value="" hidden="hidden">
                <div class="modal-body">
                    <div class="row">
        	            <div class="col-md-5">
            	            <label>Nome</label>
                	        <input class="form-control" id="name" name="name" minlength="1" required>
                    	</div>
                	</div>
                    <div class="x_content">                
                        <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                
                            <div class="row">
                                <div class="col-sm-12">
                                    <br>
                                    <input type="text" class="input-search form-control" alt="tabelaEdita" placeholder="Pesquisar">
                                    <table class="tabelaEdita table-striped hover table-bordered dt-responsive nowrap dataTable no-footer dtr-inline"
                                           cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info"
                                            style="width: 100%;">
                                        <thead>
                                            <tr role="row">
                                                <th bgcolor="#2a3f54" style="color: #fff; font-family: Arial, sans-serif; padding: 8px">#</th>
                                                <th bgcolor="#2a3f54" style="color: #fff; font-family: Arial, sans-serif; padding: 8px">Nome</th>
                                                <th bgcolor="#2a3f54" style="color: #fff; font-family: Arial, sans-serif; padding: 8px">Categoria</th>
                                                <th bgcolor="#2a3f54" style="color: #fff; font-family: Arial, sans-serif; padding: 8px; width: 50px" id="clicar">
                                                    Permitir
                                                    <input type="checkbox" id="checkEdita">
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="datatableUserA"></tbody>
                                    </table>
                                <div class="teste"><center>Carregando...</center></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-success">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="addModal" class="modal fade in" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Adicionar Perfil</h4>
            </div>
            <form action="<?php echo URL?>/Usuario/addPerfil" method="post" id="addForm">
                <div class="modal-body">
                    <div class="row">
        	            <div class="col-md-8">
            	            <label>Nome</label>
                	        <input class="form-control" id="addName" name="name" minlength="1" required>
                    	</div>
                	</div>
                    <div class="x_content">
                        <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                
                            <div class="row">
                                <div class="col-sm-12">
                                    <br>
                                    <input type="text" class="input-search form-control" alt="tabelaAdd" placeholder="Pesquisar">
                                    <table class="tabelaAdd table-striped hover table-bordered dt-responsive nowrap dataTable no-footer dtr-inline"
                                           cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info"
                                            style="width: 100%;">
                                        <thead>
                                            <tr role="row">
                                                <th bgcolor="#2a3f54" style="color: #fff; font-family: Arial, sans-serif; padding: 8px">#</th>
                                                <th bgcolor="#2a3f54" style="color: #fff; font-family: Arial, sans-serif; padding: 8px;">Nome</th>
                                                <th bgcolor="#2a3f54" style="color: #fff; font-family: Arial, sans-serif; padding: 8px">Categoria</th>
                                                <th bgcolor="#2a3f54" style="color: #fff; font-family: Arial, sans-serif; padding: 8px; width: 50px" id="clicar">
                                                    Permitir
                                                    <input type="checkbox" name="1" id="checkAdd">
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="datatableUserB"></tbody>
                                    </table>
                                    </div>
                                <div class="teste"><center>Carregando...</center></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-success" id="addBtn">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script type="text/javascript">
$(document).ready(function(){
    carrega();

    $('#addBtn').on('click', function(event) {
    	event.preventDefault();
    	
    	var validation = false;

    	$('.table').find('tr').each(function(index, value) {
			$(this).find('td').each(function(index, value) {
				if ($('#addName')[0].value == $(this).text()) {
					validation = true;
				}
			});
		});

    	if (validation) {
    		$('#addName')[0].value = '';   		
    		alert('Esse nome já existe, por favor escolha outro.');
    	} else {
    		$('#addForm').submit();
    	}
    });

    $(".input-search").keyup(function(){
        //pega o css da tabela 
        var tabela = $(this).attr('alt');
        if( $(this).val() != ""){
            $("."+tabela+" tbody>tr").hide();
            $("."+tabela+" td:contains-ci('" + $(this).val() + "')").parent("tr").show();
        } else{
            $("."+tabela+" tbody>tr").show();
        }
    });

    $.extend($.expr[":"], {
        "contains-ci": function(elem, i, match, array) {
            return (elem.textContent || elem.innerText || $(elem).text() || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
        }
    });

    var checkTodos = $("#checkEdita");
    
    checkTodos.click(function () {
        if ($(this).is(':checked')){
            $('input:checkbox').prop("checked", true);
        } else{
            $('input:checkbox').prop("checked", false);
        }
    });

    var check = $("#checkAdd");
    
    check.click(function () {
        if ($(this).is(':checked')){
            $('input:checkbox').prop("checked", true);
        } else{
            $('input:checkbox').prop("checked", false);
        }
    });     
});

function editarPerfil(idGrupo) {
	// Informa ao php o id do grupo
    $('#idPerfil').attr('value', idGrupo);

    // Preenche o modal
    var text = $('tr').children('#' + idGrupo).text();
    carregaEdita(idGrupo);
    console.log(idGrupo);

    $('#name').prop('value', text);
}

function removerPerfil(idGrupo) {
	// Variavel com o html
	html = '<form action="<?php echo URL?>/Usuario/excluirPerfil" method="post">';
		html += '<input value="' + idGrupo + '" hidden="hidden" name="idPerfil">';
	html += '</form>';

	// Adiciona o html a pagina
	$('body').append(html);

	// Envia o form
	$('body').children('form').last().submit();
}

function carrega(){
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url+"Usuario/ajaxCarregaPerfis",
        
        beforeSend: function(){
            loadingPagia();
        },

        success: function(data){
            $(".teste").hide();

            var dados = data[0];
            var podeExcluir = data[1];

            // console.log(podeExcluir.selectPermissao.length, podeExcluir.selectGrpUsr);

            html = '';
            for(var i=0;i<dados.length;i++){

                html += "<tr>";
                    html +="<td>";
                    html += dados[i].idGrupo;
                    html +="</td>";
                    html +="<td id='" + dados[i].idGrupo +"'>";
                    html += dados[i].descricao;
                    html +="</td>";
                    html +="<td>";
                    html += '<button type="button" id="editaBtn" class="btn btn-warning btn-xs" onclick="editarPerfil(' + dados[i].idGrupo +')" data-toggle="modal" data-target="#editaModal">Editar</button>';

                    <?php 
                        if (isset($_SESSION['acesso']) && $_SESSION['acesso'] == 'SI') {
                            // echo "html += '<button class=\"btn btn-danger btn-xs\" onclick=\"removerPerfil(' + dados[i].idGrupo +')\">Excluir</button>';";

                            echo "
                            if (podeExcluir.selectGrpUsr[i] && podeExcluir.selectGrpUsr[i].idGrupo == dados[i].idGrupo) {
                            }
                            else {
                                html += '<button class=\"btn btn-danger btn-xs\" onclick=\"removerPerfil(' + dados[i].idGrupo +')\">Excluir</button>';
                            }";
                        } 
                        
                        else {
                            echo '';
                        }
                    ?>;

                    html +="</td>";
                html += "</tr>";
            }

            $("#datatableUser").html(html);
        
            var table = $(".table").dataTable( {
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
            
            loading();
            return true;
        }
    });
}

function carregaEdita(idGrp){
    var tableEdita = $('.tabelaEdita').DataTable();
    tableEdita.destroy();

    console.log(idGrp);

    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url+"Usuario/carregaMenus",
        data: {
            i: idGrp
        },
        
        error: function(req, textStatus, errorThrown) {
            alert('Ooops, logic source code happened: ' + textStatus + ' ' +errorThrown);
        },

        success: function(data){
            $(".teste").hide();

            var dados = data;
            console.log(data);

            var html = '';
            for(var i=0;i<data.length;i++){

                html += "<tr>";
                    html +="<td>";
                    html += data[i].idMenu
                    html +="</td>";
                    html +="<td>";
                    html += data[i].descricao
                    html +="</td>";
                    html +="<td>";
                    html += data[i].cat + '/' + data[i].subCat;
                    html +="</td>";
                    html +="<td>";
                    if (data[i].Ativa == 1) {
                        html += "<input class='mx-auto' name='permitidos[]' value=" + data[i].idMenu + " type='checkbox' checked>";
                    } else {
                        html += "<input class='mx-auto' name='permitidos[]' value=" + data[i].idMenu + " type='checkbox'>";
                    }
                    html +="</td>";
                html += "</tr>";
            }

            $("#datatableUserA").html(html);
        
            var table = $(".tabelaEdita").dataTable( {
                scrollY: 300,
                scrollCollapse: true,
                paging: false,
                order: [[2, 'asc']],
        
               "language": {
                    "zeroRecords": "Registro nao encontrado",
                    "infoEmpty": "No records available",
                }
            });
            // loading();
            return true;
        }
    });
}

function carregaAdd(){
    // $('#clicar').trigger('click');
    var tableB = $('.tabelaAdd').DataTable();
    tableB.destroy();

    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url+"Usuario/carregaMenus",
        data: {
            i: 0
        },
        
        error: function(req, textStatus, errorThrown) {
            alert('Ooops, logic source code happened: ' + textStatus + ' ' +errorThrown);
        },

        success: function(data){
            $(".teste").hide();

            var dados = data;

            var html = '';
            for(var i=0;i<data.length;i++){

                html += "<tr>";
                    html +="<td>";
                    html += data[i].idMenu
                    html +="</td>";
                    html +="<td>";
                    html += data[i].descricao;
                    html +="</td>";
                    html +="<td>";
                    html += data[i].cat + '/' + data[i].subCat;
                    html +="</td>";
                    html +="<td>";
                    if (data[i].Ativa == 1) {
                        html += "<input class='mx-auto' name='permitidos[]' value=" + data[i].idMenu + " type='checkbox' checked>";
                    } else {
                        html += "<input class='mx-auto' name='permitidos[]' value=" + data[i].idMenu + " type='checkbox'>";
                    }
                    html +="</td>";
                html += "</tr>";
            }

            $("#datatableUserB").html(html);
        
            var tableB = $(".tabelaAdd").dataTable( {
                //scrollResize: true,
                scrollY: 300,
                scrollCollapse: true,
                paging: false,
                order: [[2, 'asc']],
        
                "language": {
                    "lengthMenu": "",
                    "zeroRecords": "Registro nao encontrado",
                    "infoEmpty": "No records available",
                    "infoFiltered": ""
                }

            });

            console.log(tableB);
            // loading();
            return true;
        }
    });
}
</script>



