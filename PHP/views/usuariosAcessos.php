<div class="col-md-12 col-sm-12 col-xs-12">
  <ol class="breadcrumb">
      <li><a href="<?php echo URL ?>/" onclick="loadingPagia()">Dashboard</a></li>          
        <li class="active">Usuários x Acessos</li>
  </ol>
</div>
<div class="col-md-12">
    <?php $this->helper->alertMessage(); ?>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Usuários x Acessos
                </h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">                
                <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                
                    <div class="row">
                        <div class="col-sm-12">

                            <table class="table table-striped hover table-bordered dt-responsive nowrap dataTable no-footer dtr-inline"
                                   cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info"
                                   style="width: 100%;">
                                <thead>
                                <tr role="row">
                                    <th>#</th>
                                    <th>Desc.Menu</th>
                                    <th>Url</th>
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

<div id="myModal" class="modal fade in" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Editar Acesso</h4>
            </div>
            <form onsubmit="addAll()" action="<?php echo URL?>/Usuario/editarAcesso" method="post">
                <input type="text" name="idMenu" id="idMenu" value="" hidden="hidden">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-5">
                            <p>Usuarios permitidos</p>
                            <select class="form-control" id="usrAllow" multiple name="perfisPermitidos[]">
                            </select>
                        </div>
                        <div class="col-md-1">
                            <br><br>
                            <button type="button" class="btn btn-danger" id="removeUsr">&gt;&gt; </button>
                            <button type="button" class="btn btn-success" id="addUsr">&lt;&lt; </button>
                        </div>
                        <!-- <div class="col-md-1"></div> -->
                        <div class="col-md-5">
                            <p>Usuarios bloqueados</p>
                            <select class="form-control" id="usrRm" multiple name="negados[]">
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                    <button type="submit" id="salvaModal" class="btn btn-success">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script type="text/javascript">
$(document).ready(function(){
    $('#addUsr').click(function(){            
        $("#usrRm option:selected" ).each(function() {  
            $(this).remove();
            $("#usrAllow").append('<option value="'+$(this).val()+'">'+$(this).text()+"</option>");
        });
    });

    $('#removeUsr').click(function(){
        $("#usrAllow option:selected" ).each(function() {  
            $(this).remove();                
            $("#usrRm").append('<option value="'+$(this).val()+'">'+$(this).text()+"</option>");

            $('#usrRm option').each(function(){
                //$(this).remove();                               
                console.log($(this).val());
                var users = $(this).val().split('-');                              
            });
        });
    });

    carrega();

    $('#myModal').on('hidden.bs.modal', function (event) {
        $('#usrAllow').children().remove();
        $('#usrRm').children().remove();
    });

    $('#myModal').on('show.bs.modal', function (event) {
        if ($('#usrAllow')) {} else {}
    });
});

function addAll() {
    $('#usrAllow > option').prop('selected' , 'selected');
}

function editarAcesso(idMenu) {

    $('#idMenu').attr('value', idMenu);

    $.post(
        url + '/Usuario/ajaxCarregaAcessos', 
        {idMenu: idMenu},
        
        function(data, textStatus, xhr) {
            // Prepara o json
            data = $.parseJSON(data);

            // Variaveis
            var permitidos = data.permitidos;
            var negados = data.negados;

            // Debug
            console.log(permitidos.length);
            console.log(negados.length);
            console.log(data.todos.length);
            console.log('Proxima linha');

            // Se houver mais de um permitido
            if (permitidos.length > 1) {
                for (var i = 0; i < permitidos.length; i++) {
                    $('#usrAllow').append('<option value="' + permitidos[i].idGrupo + '">' + permitidos[i].descGrupo + '</option>');
                }
            }
            // Se houver só um permitido
            else if (permitidos.length > 0){
                $('#usrAllow').append('<option selected="selected" value="' + permitidos[0].idGrupo + '">' + permitidos[0].descGrupo + '</option>');
            }
            // Se não houver nenhum permitido
            else if(permitidos.length == 0 && negados.length == 0) {
                // Pega todos os registros
                todos = data.todos;

                // Se houver mais de um
                if (todos.length > 1) {
                    for (var i = 0; i < todos.length; i++) {
                        $('#usrRm').append('<option value="' + todos[i].idGrupo + '">' + todos[i].descGrupo + '</option>');
                    }
                }
                // Se houver um
                else if (todos.length > 0) {
                    $('#usrRm').append('<option value="' + todos[0].idGrupo + '">' + todos[0].descGrupo + '</option>');
                }
            }
            
            // Se houver mais de um negado
            if (negados.length > 1) {
                for (var i = 0; i < negados.length; i++) {
                    $('#usrRm').append('<option value="' + negados[i].idGrupo + '">' + negados[i].descGrupo + '</option>');
                }
            }
            // Se houver um negado
            else if (negados.length > 0){
                $('#usrRm').append('<option selected="selected" value="' + negados[0].idGrupo + '">' + negados[0].descGrupo + '</option>');
            } 
            // for (var i = 0; i < negados.length; i++) {
            //     $('#usrRm').append('<option value="' + negados[i].idGrupo + '">' + negados[i].descGrupo + ' </option>');
            // }
        }
    );
}

function carrega(){
    // var table = $('.table').DataTable();
    // table.destroy();

    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url+"Usuario/carregaMenus",
        data: {},
        
        beforeSend: function(){
            loadingPagia();
        },
        
        error: function(req, textStatus, errorThrown) {
            alert('Ooops, logic source code happened: ' + textStatus + ' ' +errorThrown);
        },

        success: function(data){
            $(".teste").hide();

            var dados = data;

            html = '';
            for(var i=0;i<dados.length;i++){

                html += "<tr>";
                    html +="<td>";
                    html += dados[i].idMenu
                    html +="</td>";
                    html +="<td>";
                    html += dados[i].descricao
                    html +="</td>";
                    html +="<td>";
                    html += url + dados[i].url.replace('/', '');
                    html +="</td>";
                    html +="<td>";
                    html += '<button type="button" class="btn btn-warning btn-xs" onclick="editarAcesso(' + dados[i].idMenu +')" data-toggle="modal" data-target="#myModal">Editar</button>';
                    html +="</td>";
                html += "</tr>" 
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
</script>



