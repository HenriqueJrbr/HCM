<div class="col-md-12 col-sm-12 col-xs-12">
  <ol class="breadcrumb">
      <li><a href="<?php echo URL ?>/" onclick="loadingPagia()">Dashboard</a></li>          
        <li class="active">Auditoria LGPD</li>
  </ol>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Filtrar
                </h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
        
            <div class="x_content">
                <div class="row">
                    <div class="col-md-2">
                        <label>Filtrar por:</label>
                        <select class="form-control" name="filtro" id="filtro">
                            <option value=""></option>
                            <option value="1">Gestores</option>
                            <option value="2">Grupos</option>
                            <option value="3">Usuários</option>
                            <option value="4">Programas</option>
                            <option value="5">Rotinas</option>
                            <option value="6">Módulos</option>
                        </select>
                    </div>

                    <div class="col-md-2 hide" id="byGestores">
                        <label>Gestores de:</label>
                        <select class="form-control" name="tGestores" id="tGestores">
                            <option value=""></option>
                            <option value="2">Grupos</option>
                            <option value="3">Usuários</option>
                            <option value="4">Programas</option>
                            <option value="5">Rotinas</option>
                            <option value="6">Módulos</option>
                        </select>
                    </div>
                    <div class="col-sm-6"></div>
                </div>

                

                <div class="row hide" id="fGestores">
                    <div class="row"><div class="clearfix"><hr></div></div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Buscar gestores</label>
                                <input type="text" id="buscaGestores" class="form-control" value="">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-5">
                                <label>Gestores:</label>
                                <select class="form-control" id="gestores" multiple>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <div style="float: none; margin: 30px 0 0 28%;">
                                    <button type="button" class="btn btn-success" id="btnAddGestores"> >> </button><br>
                                    <button type="button" class="btn btn-danger" id="btnRemoveGestores"> << </button>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <label>Gestores à filtrar:</label>
                                <select class="form-control" id="gestoresAdd" name="gestores[]" multiple></select>
                            </div>
                        </div>    
                    </div>
                    
                </div>
                
                <div class="row hide" id="fUsuarios">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Buscar usuários</label>
                                <input type="text" id="buscaUsuarios" class="form-control" value="">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-5">
                                <label>Usuários:</label>
                                <select class="form-control" id="usuarios" multiple>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <div style="float: none; margin: 30px 0 0 28%;">
                                    <button type="button" class="btn btn-success" id="btnAddUsuarios"> >> </button><br>
                                    <button type="button" class="btn btn-danger" id="btnRemoveUsuarios"> << </button>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <label>Usuários à filtrar:</label>
                                <select class="form-control" id="usuariosAdd" name="usuarios[]" multiple></select>
                            </div>
                        </div> 
                        <div class="clearfix"><hr></div>   
                    </div>
                </div>
                
                <div class="row hide" id="fGrupos">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Buscar grupos</label>
                                <input type="text" id="buscaGrupos" class="form-control" value="">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-5">
                                <label>Grupos:</label>
                                <select class="form-control" id="grupos" multiple>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <div style="float: none; margin: 30px 0 0 28%;">
                                    <button type="button" class="btn btn-success" id="btnAddGrupos"> >> </button><br>
                                    <button type="button" class="btn btn-danger" id="btnRemoveGrupos"> << </button>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <label>Grupos à filtrar:</label>
                                <select class="form-control" id="gruposAdd" name="grupos[]" multiple></select>
                            </div>
                        </div>
                        <div class="clearfix"><hr></div>    
                    </div>
                </div>

                <div class="row hide" id="fModulos">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Buscar módulos</label>
                                <input type="text" id="buscaModulos" class="form-control" value="">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-5">
                                <label>Módulos:</label>
                                <select class="form-control" id="modulos" multiple>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <div style="float: none; margin: 30px 0 0 28%;">
                                    <button type="button" class="btn btn-success" id="btnAddModulos"> >> </button><br>
                                    <button type="button" class="btn btn-danger" id="btnRemoveModulos"> << </button>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <label>Módulos à filtrar:</label>
                                <select class="form-control" id="modulosAdd" name="modulos[]" multiple></select>
                            </div>
                        </div>    
                        <div class="clearfix"><hr></div>
                    </div>
                </div>

                <div class="row hide" id="fRotinas">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Buscar rotinas</label>
                                <input type="text" id="buscaRotinas" class="form-control" value="">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-5">
                                <label>Rotinas:</label>
                                <select class="form-control" id="rotinas" multiple>
                                    <option value="1">Consulta</option>
                                    <option value="2">Manutenção</option>
                                    <option value="3">Relatórios</option>
                                    <option value="3">Tarefas</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <div style="float: none; margin: 30px 0 0 28%;">
                                    <button type="button" class="btn btn-success" id="btnAddRotinas"> >> </button><br>
                                    <button type="button" class="btn btn-danger" id="btnRemoveRotinas"> << </button>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <label>Rotinas à filtrar:</label>
                                <select class="form-control" id="rotinasAdd" name="rotinas[]" multiple></select>
                            </div>
                        </div>  
                        <div class="clearfix"><hr></div>  
                    </div>
                </div>

                <div class="row hide" id="fProgramas">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Buscar programas</label>
                                <input type="text" id="buscaProgramas" class="form-control" value="">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-5">
                                <label>Programas:</label>
                                <select class="form-control" id="programas" multiple>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <div style="float: none; margin: 30px 0 0 28%;">
                                    <button type="button" class="btn btn-success" id="btnAddProgramas"> >> </button><br>
                                    <button type="button" class="btn btn-danger" id="btnRemoveProgramas"> << </button>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <label>Programas à filtrar:</label>
                                <select class="form-control" id="programasAdd" name="programas[]" multiple></select>
                            </div>
                        </div>    
                    </div>
                </div>
                <div class="clearfix"><hr></div>
                <div class="col-md-12">
                    <button type="button" id="btnFiltrar" class="btn btn-success pull-right">Filtrar</button>
                </div>


                <div class="clearfix"><hr></div>
                <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer hide tUsuarios">
                    <div class="row">
                        <div class="col-sm-12">

                            <table class="table table-striped hover table-bordered dt-responsive nowrap dataTable no-footer dtr-inline"
                                    cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info"
                                    style="width: 100%;" >
                                <thead>
                                    <tr role="row">
                                        <th>Nome do usuário</th>
                                        <th>Gestor</th>
                                        <th>Módulos</th>
                                        <th>Grupos</th>
                                        <th>Rotinas</th>
                                        <th>Transações</th>
                                        <th>Quant. Dados pessoais</th>
                                        <th>Quant. Dados sensiveis</th>
                                        <th>Quant. Dados anonimizados</th>
                                        <th>Ação</th>
                                    </tr>
                                </thead>
                                <tbody id="tableUsuarios">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> 

                <!-- Modulos ------->
                <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer hide tModulos">
                    <div class="row">
                        <div class="col-sm-12">

                            <table class="table table-striped hover table-bordered dt-responsive nowrap dataTable no-footer dtr-inline"
                                    cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info"
                                    style="width: 100%;" >
                                <thead>
                                <tr role="row">
                                    <th>Módulos</th>
                                    <th>Gestor</th>
                                    <th>Usuários</th>
                                    <th>Grupos</th>
                                    <th>Rotinas</th>
                                    <th>Transações</th>
                                    <th>Quant. Dados pessoais</th>
                                    <th>Quant. Dados sensiveis</th>
                                    <th>Quant. Dados anonimizados</th>
                                    <th>Ação</th>
                                </tr>
                                </thead>
                                <tbody id="tableModulos">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Grupos ------->
                <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer hide tGrupos">
                    <div class="row">
                        <div class="col-sm-12">

                            <table class="table table-striped hover table-bordered dt-responsive nowrap dataTable no-footer  dtr-inline tbGrupo"
                                    cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info"
                                    style="width: 100%;" >
                                <thead>
                                <tr role="row">
                                    <th>Grupos</th>
                                    <th>Gestor</th>
                                    <th>Usuários</th>
                                    <th>Módulos</th>
                                    <th>Rotinas</th>
                                    <th>Transações</th>
                                    <th>Quant. Dados pessoais</th>
                                    <th>Quant. Dados sensiveis</th>
                                    <th>Quant. Dados anonimizados</th>
                                    <th>Ação</th>
                                </tr>
                                </thead>
                                <tbody id="tableGrupos">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Transações ------->
                <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer hide tProgramas">
                    <div class="row">
                        <div class="col-sm-12">

                            <table class="table table-striped hover table-bordered dt-responsive nowrap dataTable no-footer dtr-inline"
                                    cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info"
                                    style="width: 100%;" >
                                <thead>
                                    <tr role="row">
                                        <th>Transações</th>
                                        <th>Gestor</th>
                                        <th>Usuários</th>
                                        <th>Grupos</th>
                                        <th>Módulos</th>
                                        <th>Funções</th>
                                        <th>Quant. Dados pessoais</th>
                                        <th>Quant. Dados sensiveis</th>
                                        <th>Quant. Dados anonimizados</th>
                                        <th>Ação</th>
                                    </tr>
                                </thead>
                                <tbody id="tableTransacoes">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Rotinas ------->
                <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer hide tRotinas">
                    <div class="row">
                        <div class="col-sm-12">

                            <table class="table table-striped hover table-bordered dt-responsive nowrap dataTable no-footer dtr-inline"
                                    cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info"
                                    style="width: 100%;" >
                                <thead>
                                <tr role="row">
                                    <th>Transações</th>
                                    <th>Gestor</th>
                                    <th>Usuários</th>
                                    <th>Grupos</th>
                                    <th>Módulos</th>
                                    <th>Rotinas</th>
                                    <th>Quant. Dados pessoais</th>
                                    <th>Quant. Dados sensiveis</th>
                                    <th>Quant. Dados anonimizados</th>
                                    <th>Ação</th>
                                </tr>
                                </thead>
                                <tbody id="tableRotinas">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<script type="text/javascript">

function escondeTudo(){
    // Esconde filtro por gestores
    //$('#byGestores').addClass('hide');

    // Esconde gestores
    $('#fGestores').addClass('hide');

    // Esconde usuários
    $('#fUsuarios').addClass('hide');
    $('.tUsuarios').addClass('hide');
    $('#usuariosAdd').html('');
    $('#buscaUsuarios').val('');

    // Esconde grupos
    $('#fGrupos').addClass('hide');
    $('.tGrupos').addClass('hide');
    $('#gruposAdd').html('');
    $('#buscaGrupos').val('');

    // Esconde modulos
    $('#fModulos').addClass('hide');
    $('.tModulos').addClass('hide');
    $('#modulosAdd').html('');
    $('#buscaModulos').val('');

    // Esconde rotinas
    $('#fRotinas').addClass('hide');
    $('.tRotinas').addClass('hide');
    $('#rotinasAdd').html('');
    $('#buscaRotinas').val('');

    // Esconde programas
    $('#fProgramas').addClass('hide');
    $('.tProgramas').addClass('hide');
    $('#programasAdd').html('');
    $('#buscaProgramas').val('');
}


$(document).ready(function () {  
    // Relaciona gestores ao filtro
    $('#btnAddGestores').click(function(){            
        $("#gestores option:selected").each(function() {  
            $(this).remove();
            $("#gestoresAdd").append('<option value="'+$(this).val()+'">'+$(this).text()+"</option>");
        });
    });
    
    $('#btnRemoveGestores').click(function(){
        $("#gestoresAdd option:selected" ).each(function() {  
            $(this).remove();                
            $("#gestores").append('<option value="'+$(this).val()+'">'+$(this).text()+"</option>");
        });
    });        
    // Fim 

    // Relaciona usuários ao filtro      
    $('#btnAddUsuarios').click(function(){            
        $("#usuarios option:selected").each(function() {  
            $(this).remove();
            $("#usuariosAdd").append('<option value="'+$(this).val()+'">'+$(this).text()+"</option>");
        });
    });
    
    $('#btnRemoveUsuarios').click(function(){
        $("#usuariosAdd option:selected" ).each(function() {  
            $(this).remove();                
            $("#usuarios").append('<option value="'+$(this).val()+'">'+$(this).text()+"</option>");
        });
    });        
    // Fim 

    // Relaciona grupos ao filtro  
    $('#btnAddGrupos').click(function(){            
        $("#grupos option:selected").each(function() {  
            $(this).remove();
            $("#gruposAdd").append('<option value="'+$(this).val()+'">'+$(this).text()+"</option>");
        });
    });
    
    $('#btnRemoveGrupos').click(function(){
        $("#gruposAdd option:selected" ).each(function() {  
            $(this).remove();                
            $("#grupos").append('<option value="'+$(this).val()+'">'+$(this).text()+"</option>");
        });
    });        
    // Fim Grupos

    // Relaciona rotinas ao filtro
    $('#btnAddRotinas').click(function(){            
        $("#rotinas option:selected").each(function() {  
            $(this).remove();
            $("#rotinasAdd").append('<option value="'+$(this).val()+'">'+$(this).text()+"</option>");
        });
    });
    
    $('#btnRemoveRotinas').click(function(){
        $("#rotinasAdd option:selected" ).each(function() {  
            $(this).remove();                
            $("#rotinas").append('<option value="'+$(this).val()+'">'+$(this).text()+"</option>");
        });
    });        
    // Fim Rotinas

    // Relaciona módulos ao filtro
    $('#btnAddModulos').click(function(){            
        $("#modulos option:selected").each(function() {  
            $(this).remove();
            $("#modulosAdd").append('<option value="'+$(this).val()+'">'+$(this).text()+"</option>");
        });
    });
    
    $('#btnRemoveModulos').click(function(){
        $("#modulosAdd option:selected" ).each(function() {  
            $(this).remove();                
            $("#modulos").append('<option value="'+$(this).val()+'">'+$(this).text()+"</option>");
        });
    });        
    // Fim Módulos

    // Relaciona programas ao filtro
    $('#btnAddProgramas').click(function(){            
        $("#programas option:selected").each(function() {  
            $(this).remove();
            $("#programasAdd").append('<option value="'+$(this).val()+'">'+$(this).text()+"</option>");
        });
    });
    
    $('#btnRemoveProgramas').click(function(){
        $("#programasAdd option:selected" ).each(function() {  
            $(this).remove();                
            $("#programas").append('<option value="'+$(this).val()+'">'+$(this).text()+"</option>");
        });
    });        
    // Fim Programas
});


$(document).ready(function(){
    
    $("#filtro").on('change', function(){
        // Esconde filtro por gestores
        $('#byGestores').addClass('hide');
        escondeTudo();
        
        // 1 = gestor
        // 2 = grupo
        // 3 = usuario
        // 4 = programa
        // 5 = rotina
        //6 = modulo

        // Mostra filtro por gestores
        if($(this).val() == 1){
            $('#byGestores').removeClass('hide');
        }else if($(this).val() == 2){
            $('#fGrupos').removeClass('hide');
            carregaGrupos();
        }else if($(this).val() == 3){
            $('#fUsuarios').removeClass('hide');
            carregaUsuarios();
        }else if($(this).val() == 4){
            $('#fProgramas').removeClass('hide');
            carregaProgramas();
        }else if($(this).val() == 5){
            $('#fRotinas').removeClass('hide');
            carregaRotinas();
        }else if($(this).val() == 6){
            $('#fModulos').removeClass('hide');
            carregaModulos();
        }
    });

    /********************** Busca os dados por gestores ao clicar no botão de adicionar ou remover os gestores ao filtro **********************/
    $('#btnAddGestores, #btnRemoveGestores').on('click', function(){
        escondeTudo();
        $('#fGestores').removeClass('hide');
        //$('#gestoresAdd').html('');
        //$('#buscaGestores').val('');
        var tipo = $('#tGestores').val();
        
        var action = '';

        switch(tipo){
            case '2':
                action = 'Grupos';
                break;
            case '3':
                action = 'Usuarios';
                break;
            case '4':
                action = 'Programas';
                break;
            case '5':
                action = 'Rotinas';
                break;
            case '6':
                action = 'Modulos';
                break;
        }

        // Recupera os ids dos gestores filtrados
        var idsGestores = [];
        
        $("#gestoresAdd option").each(function() {
            var users = $(this).val().split('-');
            idsGestores.push(users[0]);
        });

        /*if(idsGestores.length == 0){
            $('#'+action.toLowerCase()).html('');
            return false;
        }*/

        // Busca o resultado
        $.ajax({
            type: 'POST',
            url: url+'Lgpd/ajax' + action,
            data: {
                tipo: tipo,
                idsGestores: idsGestores
            },
            success: function (res) {
                $('#'+action.toLowerCase()).html('');
                $('#'+action.toLowerCase()).append(res);

                
            }
        });
        $('#f'+action).removeClass('hide');
    });
    /********************** FIM Busca os dados por gestores ao clicar no botão de adicionar ou remover os gestores ao filtro ******************/

   /********************** INICIO da Lógica do Botão de Filtrar (Gerar Relatório) *********************/
   $('#btnFiltrar').on('click', function(){
        //$('#gestoresAdd').html('');
        //$('#buscaGestores').val('');
        
        var tipo = ($('#filtro').val() != 1) ? $('#filtro').val() : $('#tGestores').val();
        var tipo_envio = ($('#filtro').val() != 1) ? $('#filtro').val() : parseInt($('#tGestores').val())+ 9
        
        var table = $('.table').DataTable();
        table.destroy();

        var action = '';

        switch(tipo){
            case '2':
                action = 'Grupos';
                break;
            case '3':
                action = 'Usuarios';
                break;
            case '4':
                action = 'Programas';
                break;
            case '5':
                action = 'Rotinas';
                break;
            case '6':
                action = 'Modulos';
                break;
        }
        
        if(tipo_envio==11 || tipo_envio== 2){
            var filtrarGestor = [];
            var filtrarGrupos = [];

            $("#gestoresAdd option").each(function() {
                var gestores = $(this).val().split('-');
                filtrarGestor.push(gestores[0]);
            });

            $("#gruposAdd option").each(function() {
                var grupos = $(this).val().split('-');
                filtrarGrupos.push(grupos[0]);
            });

            $.ajax({
            type:'POST',
            url: url+'LGPD/ajaxCarregaDatatableGestorGrupoLGPD ',
            data: {
                'filtrarGestor':filtrarGestor,
                'filtrarGrupo':filtrarGrupos,
                'tipo':tipo_envio
            },
            beforeSend: function(){       
                loadingPagia();
            },
            success: function(res){
                var dados=JSON.parse(res);
                dadosGlobal=res;
                html='';
                for (var i=0;i<dados.length;i++){
                    html+='<tr>';
                        html+=`<th>${dados[i][0]}</th>`;
                        html+=`<th>${dados[i][1]}</th>`;
                        html+=`<th>${dados[i][2]}</th>`;
                        html+=`<th>${dados[i][3]}</th>`;
                        html+=`<th>${dados[i][4]}</th>`;
                        html+=`<th>${dados[i][5]}</th>`;
                        html+=`<th>${dados[i][6]}</th>`;
                        html+=`<th>${dados[i][7]}</th>`;
                        html+=`<th>${dados[i][8]}</th>`;
                        html+='<th><button type="button" class="btn btn-success btn-xs" onclick="location.href=\''+ url + 'Manutencao/manutencao_grupo_edita/'+dados[i][9]+'\',loadingPagia()">Visualizar</button></th>';
                        
                    html+='</tr>';                                    
                }
            $("#tableGrupos").html(html);

            var table = $(".table").dataTable(
        {
            "language":
            {
                "lengthMenu": "Exibição _MENU_ Registros por página",
                "zeroRecords": "Registro nao encontrado",
                "info": "Pagina _PAGE_ de _PAGES_",
                "infoEmpty": "No records available",
                "search":"Pesquisar:",
                "pageLength":10,
                "paginate": 
                {
                    "first":      "Primeiro",
                    "last":       "Último",
                    "next":       "Próximo",
                    "previous":   "Anterior"
                },
                    "infoFiltered": "(Filtro de _MAX_ registro total)"
            }
        });
            
            $('.t'+action).removeClass('hide');
            //$('#f'+action).removeClass('hide');
            loading();
                
            }
        });
    }         
    else if(tipo_envio==12 || tipo_envio== 3){
            var filtrarGestor = [];
            var filtrarUsuarios = [];

            $("#gestoresAdd option").each(function() {
                var gestores = $(this).val().split('-');
                filtrarGestor.push(gestores[0]);
            });

            $("#usuariosAdd option").each(function() {
                var grupos = $(this).val().split('-');
                filtrarUsuarios.push(grupos[0]);
            });

            $.ajax({
            type:'POST',
            url: url+'LGPD/ajaxCarregaDatatableGestorUsuarioLGPD ',
            data: {
                'filtrarGestor':filtrarGestor,
                'filtrarUsuario':filtrarUsuarios,
                'tipo':tipo_envio
            },
            beforeSend: function(){       
                loadingPagia();
            },
            success: function(res){
                var dados=JSON.parse(res);
                dadosGlobal=res;
                html='';
                for (var i=0;i<dados.length;i++){
                    html+='<tr>';
                        html+=`<th>${dados[i][0]}</th>`;
                        html+=`<th>${dados[i][1]}</th>`;
                        html+=`<th>${dados[i][2]}</th>`;
                        html+=`<th>${dados[i][3]}</th>`;
                        html+=`<th>${dados[i][4]}</th>`;
                        html+=`<th>${dados[i][5]}</th>`;
                        html+=`<th>${dados[i][6]}</th>`;
                        html+=`<th>${dados[i][7]}</th>`;
                        html+=`<th>${dados[i][8]}</th>`;
                        html+='<th><button type="button" class="btn btn-success btn-xs" onclick="location.href=\''+ url + 'Usuario/dados_usuario/'+dados[i][9]+'\',loadingPagia()">Visualizar</button></th>';
                        
                    html+='</tr>';                                    
                }
            $("#tableUsuarios").html(html);
            
            var table = $(".table").dataTable(
        {
            "language":
            {
                "lengthMenu": "Exibição _MENU_ Registros por página",
                "zeroRecords": "Registro nao encontrado",
                "info": "Pagina _PAGE_ de _PAGES_",
                "infoEmpty": "No records available",
                "search":"Pesquisar:",
                "pageLength":10,
                "paginate": 
                {
                    "first":      "Primeiro",
                    "last":       "Último",
                    "next":       "Próximo",
                    "previous":   "Anterior"
                },
                    "infoFiltered": "(Filtro de _MAX_ registro total)"
            }
        });
            
            $('.t'+action).removeClass('hide');
            //$('#f'+action).removeClass('hide');
            loading();
                
            }
        });

        
    }else if(tipo_envio==13 || tipo_envio== 4){
            var filtrarGestor = [];
            var filtrarTransacoes = [];

            $("#gestoresAdd option").each(function() {
                var gestores = $(this).val().split('-');
                filtrarGestor.push(gestores[0]);
            });

            $("#programasAdd option").each(function() {
                var grupos = $(this).val().split('-');
                filtrarTransacoes.push(grupos[0]);
            });

            $.ajax({
            type:'POST',
            url: url+'LGPD/ajaxCarregaDatatableGestorTransacoesLGPD ',
            data: {
                'filtrarGestor':filtrarGestor,
                'filtrarTransacoes':filtrarTransacoes,
                'tipo':tipo_envio
            },
            beforeSend: function(){       
                loadingPagia();
            },
            success: function(res){
                var dados=JSON.parse(res);
                dadosGlobal=res;
                html='';
                for (var i=0;i<dados.length;i++){
                    html+='<tr>';
                        html+=`<th>${dados[i][0]}</th>`;
                        html+=`<th>${dados[i][1]}</th>`;
                        html+=`<th>${dados[i][2]}</th>`;
                        html+=`<th>${dados[i][3]}</th>`;
                        html+=`<th>${dados[i][4]}</th>`;
                        html+=`<th>${dados[i][5]}</th>`;
                        html+=`<th>${dados[i][6]}</th>`;
                        html+=`<th>${dados[i][7]}</th>`;
                        html+=`<th>${dados[i][8]}</th>`;
                        // html+='<th><button disabled="true" type="button" class="btn btn-success btn-xs" onclick="location.href=\''+ url + 'Usuario/dados_usuario/'+dados[i][9]+'\',loadingPagia()">Visualizar</button></th>';
                        
                    html+='</tr>';                                    
                }
            $("#tableTransacoes").html(html);
            
            var table = $(".table").dataTable(
        {
            "language":
            {
                "lengthMenu": "Exibição _MENU_ Registros por página",
                "zeroRecords": "Registro nao encontrado",
                "info": "Pagina _PAGE_ de _PAGES_",
                "infoEmpty": "No records available",
                "search":"Pesquisar:",
                "pageLength":10,
                "paginate": 
                {
                    "first":      "Primeiro",
                    "last":       "Último",
                    "next":       "Próximo",
                    "previous":   "Anterior"
                },
                    "infoFiltered": "(Filtro de _MAX_ registro total)"
            }
        });
            
            $('.t'+action).removeClass('hide');
            //$('#f'+action).removeClass('hide');
            loading();
                
            }
        });
        
    }else if(tipo_envio==14 || tipo_envio==5){
            var filtrarGestor = [];
            var filtrarRotinas = [];

            $("#gestoresAdd option").each(function() {
                var gestores = $(this).val().split('-');
                filtrarGestor.push(gestores[0]);
            });

            $("#rotinasAdd option").each(function() {
                var grupos = $(this).val().split('-');
                filtrarRotinas.push(grupos[0]);
            });

            $.ajax({
            type:'POST',
            url: url+'LGPD/ajaxCarregaDatatableGestorRotinasLGPD ',
            data: {
                'filtrarGestor':filtrarGestor,
                'filtrarRotinas':filtrarRotinas,
                'tipo':tipo_envio
            },
            beforeSend: function(){       
                loadingPagia();
            },
            success: function(res){
                var dados=JSON.parse(res);
                dadosGlobal=res;
                html='';
                for (var i=0;i<dados.length;i++){
                    html+='<tr>';
                        html+=`<th>${dados[i][0]}</th>`;
                        html+=`<th>${dados[i][1]}</th>`;
                        html+=`<th>${dados[i][2]}</th>`;
                        html+=`<th>${dados[i][3]}</th>`;
                        html+=`<th>${dados[i][4]}</th>`;
                        html+=`<th>${dados[i][5]}</th>`;
                        html+=`<th>${dados[i][6]}</th>`;
                        html+=`<th>${dados[i][7]}</th>`;
                        html+=`<th>${dados[i][8]}</th>`;
                        // html+='<th><button type="button" class="btn btn-success btn-xs" onclick="location.href=\''+ url + 'Usuario/dados_usuario/'+dados[i][9]+'\',loadingPagia()">Visualizar</button></th>';
                        
                    html+='</tr>';                                    
                }
            $("#tableRotinas").html(html);
            
            var table = $(".table").dataTable(
        {
            "language":
            {
                "lengthMenu": "Exibição _MENU_ Registros por página",
                "zeroRecords": "Registro nao encontrado",
                "info": "Pagina _PAGE_ de _PAGES_",
                "infoEmpty": "No records available",
                "search":"Pesquisar:",
                "pageLength":10,
                "paginate": 
                {
                    "first":      "Primeiro",
                    "last":       "Último",
                    "next":       "Próximo",
                    "previous":   "Anterior"
                },
                    "infoFiltered": "(Filtro de _MAX_ registro total)"
            }
        });
            
            $('.t'+action).removeClass('hide');
            //$('#f'+action).removeClass('hide');
            loading();
                
            }
        });
        
    }else if(tipo_envio==15 || tipo_envio== 6){
            var filtrarGestor = [];
            var filtrarModulos = [];

            $("#gestoresAdd option").each(function() {
                var gestores = $(this).val().split('-');
                filtrarGestor.push(gestores[0]);
            });

            $("#modulosAdd option").each(function() {
                var grupos = $(this).val().split('-');
                filtrarModulos.push(grupos[0]);
            });

            $.ajax({
            type:'POST',
            url: url+'LGPD/ajaxCarregaDatatableGestorModulosLGPD ',
            data: {
                'filtrarGestor':filtrarGestor,
                'filtrarModulos':filtrarModulos,
                'tipo':tipo_envio
            },
            beforeSend: function(){       
                loadingPagia();
            },
            success: function(res){
                var dados=JSON.parse(res);
                dadosGlobal=res;
                html='';
                for (var i=0;i<dados.length;i++){
                    html+='<tr>';
                        html+=`<th>${dados[i][0]}</th>`;
                        html+=`<th>${dados[i][1]}</th>`;
                        html+=`<th>${dados[i][2]}</th>`;
                        html+=`<th>${dados[i][3]}</th>`;
                        html+=`<th>${dados[i][4]}</th>`;
                        html+=`<th>${dados[i][5]}</th>`;
                        html+=`<th>${dados[i][6]}</th>`;
                        html+=`<th>${dados[i][7]}</th>`;
                        html+=`<th>${dados[i][8]}</th>`;
                        html+='<th><button type="button" class="btn btn-success btn-xs" onclick="location.href=\''+ url + 'Sistema/modulo/'+dados[i][9]+'\',loadingPagia()">Visualizar</button></th>';
                        
                    html+='</tr>';                                    
                }
            $("#tableModulos").html(html);
            
            var table = $(".table").dataTable(
        {
            "language":
            {
                "lengthMenu": "Exibição _MENU_ Registros por página",
                "zeroRecords": "Registro nao encontrado",
                "info": "Pagina _PAGE_ de _PAGES_",
                "infoEmpty": "No records available",
                "search":"Pesquisar:",
                "pageLength":10,
                "paginate": 
                {
                    "first":      "Primeiro",
                    "last":       "Último",
                    "next":       "Próximo",
                    "previous":   "Anterior"
                },
                    "infoFiltered": "(Filtro de _MAX_ registro total)"
            }
        });
            
            $('.t'+action).removeClass('hide');
            //$('#f'+action).removeClass('hide');
            loading();
                
            }
        });

    }
      
});


    $("#tGestores").on('change', function(){
        escondeTudo();
        $('#gestoresAdd').html('');
        $('#buscaGestores').val('');
        var tipo = $(this).val();

        // Busca o resultado
        $.ajax({
            type: 'POST',
            url: url+'Lgpd/ajaxGestores',
            data: {tipo: tipo},
            success: function (res) {                
                $('#gestores').html('');
                $('#gestores').append(res);
            }
        });
        $('#fGestores').removeClass('hide');
        $('#btnRemoveGestores').trigger('click');
    });


    // Busca Modulos
    $('#buscaGestores').on('keyup', function(){
        $('#txtModuloErro').css('display', 'none');
        var tipo = $("#tGestores").val();
        var idsAdicionados = [];
        
        $("#gestoresAdd option").each(function() {
            var gestores = $(this).val().split('-');
            idsAdicionados.push(gestores[0]);
        });
                        
        $.ajax({
            type: 'POST',
            url: url+'Lgpd/ajaxGestores',
            data: {
                string: $('#buscaGestores').val(),
                eliminar: idsAdicionados,
                tipo: tipo
            },
            success: function (res) {                
                $('#gestores').html('');
                $('#gestores').append(res);
            }
        });
        
    });

    // Busca Modulos
    $('#buscaModulos').on('keyup', function(){
        $('#txtModuloErro').css('display', 'none');
        var idsAdicionados = [];
        
        $("#modulosAdd option").each(function() {
            var modulos = $(this).val();
            idsAdicionados.push(modulos);
        });

        // Recupera os ids dos gestores filtrados
        var idsGestores = [];
        
        $("#gestoresAdd option").each(function() {
            var users = $(this).val().split('-');
            idsGestores.push(users[0]);
        });
                        
        $.ajax({
            type: 'POST',
            url: url+'Lgpd/ajaxModulos',
            data: {
                string: $('#buscaModulos').val(),
                eliminar: idsAdicionados,
                idsGestores: idsGestores
            },
            success: function (res) {                
                $('#modulos').html('');
                $('#modulos').append(res);
            }
        });
    });

    // Busca rotinas
    $('#buscaRotinas').on('keyup', function(){
        $('#txtRotinaErro').css('display', 'none');
        var idsAdicionados = [];
        
        $("#rotinasAdd option").each(function() {
            var rotina = $(this).val();
            idsAdicionados.push(rotina);
        });

        // Recupera os ids dos gestores filtrados
        var idsGestores = [];
        
        $("#gestoresAdd option").each(function() {
            var users = $(this).val().split('-');
            idsGestores.push(users[0]);
        });
                        
        $.ajax({
            type: 'POST',
            url: url+'Lgpd/ajaxRotinas',
            data: {
                string: $('#buscaRotinas').val(),
                eliminar: idsAdicionados,
                idsGestores: idsGestores
            },
            success: function (res) {                
                $('#rotinas').html('');
                $('#rotinas').append(res);
            }
        });
    });

    // Busca programas
    $('#buscaProgramas').on('keyup', function(){
        $('#txtProgramaErro').css('display', 'none');
        var idsAdicionados = [];
        
        $("#programasAdd option").each(function() {
            var progs = $(this).val().split('-');
            idsAdicionados.push(progs[0]);
        });

        // Recupera os ids dos gestores filtrados
        var idsGestores = [];
        
        $("#gestoresAdd option").each(function() {
            var users = $(this).val().split('-');
            idsGestores.push(users[0]);
        });
                        
        $.ajax({
            type: 'POST',
            url: url+'Lgpd/ajaxProgramas',
            data: {
                string: $('#buscaProgramas').val(),
                eliminar: idsAdicionados,
                idsGestores: idsGestores
            },
            success: function (res) {                
                $('#programas').html('');
                $('#programas').append(res);
            }
        });
    });

    // Busca usuarios
    $('#buscaUsuarios').on('keyup', function(){
        $('#txtUsuariosErro').css('display', 'none');
        var idsAdicionados = [];
        
        $("#usuariosAdd option").each(function() {
            var users = $(this).val().split('-');
            idsAdicionados.push(users[0]);
        });

        // Recupera os ids dos gestores filtrados
        var idsGestores = [];
        
        $("#gestoresAdd option").each(function() {
            var users = $(this).val().split('-');
            idsGestores.push(users[0]);
        });

        var string = $(this).val();
        $.ajax({
            type: 'POST',
            url: url+'Lgpd/ajaxUsuarios',
            data: {
                string: string,
                eliminar: idsAdicionados,
                idsGestores: idsGestores
            },
            success: function (res) {                
                $('#usuarios').html('');
                $('#usuarios').append(res);
            }
        });
    });

    // Busca grupos
    $('#buscaGrupos').on('keyup', function(){
        $('#txtGruposErro').css('display', 'none');
        var idsAdicionados = [];
        
        $("#gruposAdd option").each(function() {
            var grupos = $(this).val().split('-');
            idsAdicionados.push(grupos[0]);
        });

        // Recupera os ids dos gestores filtrados
        var idsGestores = [];
        
        $("#gestoresAdd option").each(function() {
            var users = $(this).val().split('-');
            idsGestores.push(users[0]);
        });

        var string = $(this).val();
        $.ajax({
            type: 'POST',
            url: url+'Lgpd/ajaxGrupos',
            data: {
                string: string,
                eliminar: idsAdicionados,
                idsGestores: idsGestores
            },
            success: function (res) {                
                $('#grupos').html('');
                $('#grupos').append(res);
            }
        });
    });
});


function carregaUsuarios(){
    $.ajax({
        type: 'POST',
        url: url+'Lgpd/ajaxUsuarios',
        success: function (res) {                
            $('#usuarios').html('');
            $('#usuarios').append(res);
        }
    });
}

function carregaGrupos(){
    $.ajax({
        type: 'POST',
        url: url+'Lgpd/ajaxGrupos',
        success: function (res) {                
            $('#grupos').html('');
            $('#grupos').append(res);
        }
    });
}

function carregaProgramas(){
    $.ajax({
        type: 'POST',
        url: url+'Lgpd/ajaxProgramas',
        success: function (res) {                
            $('#programas').html('');
            $('#programas').append(res);
        }
    });
}

function carregaRotinas(){
    $.ajax({
        type: 'POST',
        url: url+'Lgpd/ajaxRotinas',
        success: function (res) {                
            $('#rotinas').html('');
            $('#rotinas').append(res);
        }
    });
}

function carregaModulos(){
    $.ajax({
        type: 'POST',
        url: url+'Lgpd/ajaxModulos',
        success: function (res) {                
            $('#modulos').html('');
            $('#modulos').append(res);
        }
    });
}

</script>



