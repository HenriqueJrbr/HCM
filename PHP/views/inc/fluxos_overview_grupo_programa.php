<style>
    ul.bar_tabs > li a{
        padding: 4px !important;
        font-size: 12px;
    }
    
    ul.bar_tabs > li.active a {
        border-bottom: none;
        margin: -3px !important;
        border-radius: 4px 4px 0 0;
    }
</style>
                <div class="row">
                    <div class="col-md-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Overview da solicitação</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <div role="tabpanel" class="tab-pane fade active in" ><!--Inicio Tabela 1 -->
                                    <div class="" role="tabpanel" data-example-id="togglable-tabs">
                                        <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
<!--                                            <li role="presentation" class="active">-->
<!--                                                <a href="#tab_content10" id="profile-tab10" role="tab" data-toggle="tab" aria-expanded="true">Riscos Já Relacionados <span class="badge badge-danger" id="count-riscos-adicionados"></span></a>-->
<!--                                            </li>-->
<!--                                            <li role="presentation">-->
<!--                                                <a href="#tab_content11" id="profile-tab11" role="tab" data-toggle="tab" aria-expanded="true">Progs. Já Relacionados<span class="badge totalProgByGrupoAdicionado"></span></a>-->
<!--                                            </li>-->
                                            <li role="presentation">
                                                <a href="#tab_content1" id="profile-tab1" role="tab" data-toggle="tab" aria-expanded="true">Riscos Adicionais <span class="badge badge-danger" id="count-riscos"></span></a>
                                            </li>
                                            <li role="presentation">
                                                <a href="#tab_content2" id="profile-tab2" role="tab" data-toggle="tab" aria-expanded="true">Programas Adicionais<span class="badge totalProgByGrupo"></span></a>
                                            </li>
                                            <li role="presentation">
                                                <a href="#tab_content3" id="profile-tab3" role="tab" data-toggle="tab" aria-expanded="true">Histórico de observações <span class="badge"><?php echo count($historicoMsg); ?></span></a>
                                            </li>
                                            <li role="presentation">
                                                <a href="#tab_content4" id="profile-tab4" role="tab" data-toggle="tab" aria-expanded="true">Usuários <span class="badge totalUsuariosByGrupo"></span></a>
                                            </li>
                                        </ul>
                                        <div id="myTabContent" class="tab-content">
                                            <div role="tabpanel" class="tab-pane fade" id="tab_content1" aria-labelledby="profile-tab1"><!--Inicio Tabela 1 -->
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="x_panel">
                                                            <div class="x_title">
                                                                <h2>Matriz de Riscos  </h2>
                                                                <ul class="nav navbar-right panel_toolbox">
                                                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                                                </ul>
                                                                <div class="clearfix"></div>
                                                            </div>
                                                            <div class="x_content">
                                                                <div class="col-md-12">
                                                                    <h5 class="text-center" style="display:none" id="text-risco">Analisando riscos...</h5>
                                                                </div>
                                                                <div id="matriz-risco-grupos"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div role="tabpane1" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab2"><!--Inicio Tabela 1 -->                                                                                                
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="panel panel-default">
                                                            <!--<div class="panel-heading" style="background-color:#2A3F54;color: #FFFFFF"><h4>Atividades</h4></div>-->
                                                            <div class="panel-body">
                                                                <div id="datatable-responsive_wrapper"
                                                                     class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                                                    <div class="row">
                                                                        <div class="col-sm-6"></div>
                                                                        <div class="col-sm-6"></div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col-sm-12">
                                                                            <table class="table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline"
                                                                                   cellspacing="0" width="100%" role="grid"
                                                                                   aria-describedby="datatable-responsive_info"
                                                                                   style="width: 100%;" id="tableAbaProgs">
                                                                                <thead>
                                                                                <tr role="row">                                                                                    
                                                                                    <th width="20%">Grupos</th>
                                                                                    <th width="10%">Cód. Programa</th>
                                                                                    <th>Descrição</th>
                                                                                    <th width="10%">Cód. Módulo</th>
                                                                                    <th>Descrição</th>
                                                                                    <th>Rotina</th>
                                                                                    <th>Específico</th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody></tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                
                                            </div>
                                            <div role="tabpane1" class="tab-pane fade" id="tab_content3" aria-labelledby="profile-tab3"><!--Inicio Tabela 1 -->
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="x_panel">
                                                            <div class="x_title">
                                                                <h2>Histórico de observações</h2>
                                                                <ul class="nav navbar-right panel_toolbox">
                                                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                                                </ul>
                                                                <div class="clearfix"></div>
                                                            </div>
                                                            <div class="x_content">
                                                                <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                                                    <div class="row">
                                                                        <div class="col-sm-6 col-md-12"></div>
                                                                        <div class="col-sm-6 col-md-12"></div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-sm-12 col-md-12">
                                                                            <table class="tabela table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline"
                                                                                   cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info"
                                                                                   style="width: 100%;">
                                                                                <thead>
                                                                                <tr role="row">
                                                                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                                                                        rowspan="1" colspan="1" style="width: 5%;" aria-sort="ascending"
                                                                                        aria-label="First name: activate to sort column descending">Seq.
                                                                                    </th>
                                                                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1"
                                                                                        colspan="1" style="width: 20%;"
                                                                                        aria-label="Last name: activate to sort column ascending">Atividade
                                                                                    </th>
                                                                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1"
                                                                                        colspan="1" style="width: 15%;"
                                                                                        aria-label="Last name: activate to sort column ascending">Autor
                                                                                    </th>
                                                                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1"
                                                                                        colspan="1" style="width: 47%;"
                                                                                        aria-label="Last name: activate to sort column ascending">Mensagem
                                                                                    </th>
                                                                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1"
                                                                                        colspan="1" style="width: 18%;"
                                                                                        aria-label="Position: activate to sort column ascending">Data
                                                                                    </th>
                                                                                </thead>
                                                                                <tbody>
                                                                                <?php $i = 1;?>
                                                                                <?php foreach ($historicoMsg as $value): ?>
                                                                                    <tr>
                                                                                        <td><?php echo $i; ?></td>
                                                                                        <td><?php echo $value['atividade']; ?></td>
                                                                                        <td><?php echo $value['autor']; ?></td>
                                                                                        <td><?php echo $value['msg']; ?></td>
                                                                                        <td><?php echo date("d/m/Y à\s h:i:s", strtotime($value['dataCriacao'])); ?></td>
                                                                                    </tr>
                                                                                    <?php $i++; ?>
                                                                                <?php endforeach; ?>
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
                                            <div role="tabpane4" class="tab-pane fade" id="tab_content4" aria-labelledby="profile-tabUsuarios"><!--Inicio Tabela 1 -->
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="panel panel-default">
                                                            <!--<div class="panel-heading" style="background-color:#2A3F54;color: #FFFFFF"><h4>Atividades</h4></div>-->
                                                            <div class="panel-body">
                                                                <div id="datatable-responsive_wrapper"
                                                                     class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                                                    <div class="row">
                                                                        <div class="col-sm-6"></div>
                                                                        <div class="col-sm-6"></div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col-sm-12">
                                                                            <table class="table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline"
                                                                                   cellspacing="0" width="100%" role="grid"
                                                                                   aria-describedby="datatable-responsive_info"
                                                                                   style="width: 100%;" id="tableUsuariosGrupo">
                                                                                <thead>
                                                                                <tr role="row">
                                                                                    <th>Nome</th>
                                                                                    <th width="30%">Gestor</th>
                                                                                    <th width="30%">Departamento</th>
                                                                                    <th width="10%">Situação</th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody id="tableUsuariosGrupo"></tbody>
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
                            </div>
                        </div>
                    </div>
                </div>

<script>
$(document).ready(function(){
    tableUsuarioGrupo = $('#tableUsuariosGrupo').DataTable({
        //"sorting": [[3,'asc']],
        "processing": false,
        "serverSide": true,
        "oLanguage": {
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "Nenhum registro encontrado",
            //"sInfo": "Exibindo de _START_ ate _END_ de _TOTAL_ registros",
            "sInfo": "Página _PAGE_ de _PAGES_",
            "sInfoEmpty": "Nenhum registro para ser exibido",
            //"sInfoFiltered": "(Filtrado de _MAX_ registros no total)",
            "sInfoFiltered": "",
            "sSearch": "Pesquisar:",
            "oPaginate": {
                "sFirst": "Primeiro",
                "sLast": "Último",
                "sNext": "Proximo",
                "sPrevious": "Anterior"
            },
            "sLoadingRecords": "&nbsp;",
            "sProcessing": '<div class="box-loading-datatable"><div class="spinner pull-left"><img src="'+url+'/assets/images/loader.gif"></div><div class="pull-left" style="margin-top: -10px;">Carregando...</div></div>'
        },
        "ajax": {
            type: "POST",
            url: url + "SolicitacaoGrupoPrograma/ajaxBuscaUsuariosGrupo",
            "data": function (d){
                d.idGrupo = $('#idGrupo').val();
            }/*,
                success: function (data) {
                    $('.totalUsuariosGrupo').text(data.recordsTotal)
                }*/
        }
    });


    // Carrega datatable com programas de cada grupo selecionado        
    table = $('#tableAbaProgs').DataTable({
        "processing": true,
        "serverSide": true,
        "oLanguage": {
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "Nenhum registro encontrado",
            //"sInfo": "Exibindo de _START_ ate _END_ de _TOTAL_ registros",
            "sInfo": "Página _PAGE_ de _PAGES_",
            "sInfoEmpty": "Nenhum registro para ser exibido",
            //"sInfoFiltered": "(Filtrado de _MAX_ registros no total)",
            "sInfoFiltered": "",
            "sSearch": "Pesquisar:",
            "oPaginate": {
                "sFirst": "Primeiro",
                "sLast": "Último",
                "sNext": "Proximo",
                "sPrevious": "Anterior"
            },
            "sLoadingRecords": "&nbsp;",
            "sProcessing": '<div class="box-loading-datatable"><div class="spinner pull-left"><img src="'+url+'/assets/images/loader.gif"></div><div class="pull-left" style="margin-top: -10px;">Carregando...</div></div>'
        },
        "ajax": {                            
            type: "POST",
            url: url + "Fluxo/ajaxCarregaAbaProsRevisaoAcesso/",
            "data": function (d){
                d.progs = function(){

                    progs = new Array();
                    $('.manterStatus').each(function() {
                        if($(this).is(':checked')){
                            progs.push($(this).closest('tr').find('.idProg').val());
                        }
                    });

                    return progs;
                };
                d.grupos = function(){
                    grupos = new Array();
                    grupos.push($('#idGrupo').val());
                    return grupos;
                };
            }                    
        }
    });
    
    $('.tabelaRevisao').DataTable( {
        "language": {
            "lengthMenu": "Mostrar _MENU_ registros",
            "zeroRecords": "Nenhum registro encontrado",
            "info": "Página _PAGE_ de _PAGES_",
            "infoEmpty": "Nenhum registro para ser exibido",
            "search":"Pesquisar:",
            "paginate": {
                "first":      "Primeiro",
                "last":       "Último",
                "next":       "Próximo",
                "previous":   "Anterior"
            },
            "infoFiltered": ""
        }
    });
    
    // Recupera matriz de risco de grupos ao entrar na pagina

    carregaRiscosGrupos();

    
});

// Recupera matriz de risco
function carregaRiscosGrupos(){

    var progs = new Array();
    
    $('.manterStatus').each(function() {
        if($(this).is(':checked')){                    
            progs.push($(this).closest('tr').find('.idProg').val());
        }        
    });                        

    if(progs.length === 0) {
        //grupos.push(0);
        $('#matriz-risco-grupos').html('');
        $('#count-riscos').html('');
        $('#text-risco').css('display', 'none');
        $('#riscos').val('');
        $('.totalProgByGrupo').text('');
        $('.totalUsuariosByGrupo').text('');
        $('#load').css('display', 'none');
        return false;
    }
    $.ajax({
        type: 'POST',
        url:  url+'Fluxo/ajaxMatrizDeRisco',
        data: {
            grupos: [$('#idGrupo').val()],
            progs: progs
        },
        success: function(res){
            var data = JSON.parse(res);
            $('#matriz-risco-grupos').html(data.html);
            $('#count-riscos').html(data.totalRiscos);
            $('#riscos').val(data.totalRiscos);
            $('#text-risco').css('display', 'none');
            $('#load').css('display', 'none');
            $('.totalProgByGrupo').text(data.totalProgByGrupo);
            $('.totalUsuariosByGrupo').text(data.totalUsuariosByGrupo);
        }
    });
}

function concatenaDescRevisao(){

    var arr = [] ;
    $('input[id^="idCodGest___"]').each(function(x){
        var context = $(this);

        var linha = context.attr('id').split("___")[1];

        if(arr.indexOf($("#idCodGest___" + linha).val()) === -1){
            arr.push($("#idCodGest___" + linha).val());
        }
    });

    $("#aprovadorGrupo").val(arr);
}

// Recupera matriz de risco
function carregaRiscosGruposJaAdicionados(){   
    $.ajax({
        type: 'POST',
        url:  url+'Fluxo/ajaxMatrizDeRiscoJaAdicionados',
        data: {idUsuario: $('#idusuario').val()},
        success: function(res){
            var data = JSON.parse(res);
            $('#matriz-risco-grupos-adicionados').html(data.html);
            $('#count-riscos-adicionados').html(data.totalRiscos);
            $('#riscos-adicionados').val(data.totalRiscos);
            $('#text-risco-adicionados').css('display', 'none');
            $('#load').css('display', 'none');
            $('.totalProgByGrupoAdicionado').text(data.totalProgByGrupo);
        }
    });
}

function carregaGruposJaAdicionados(){
    
    var idUsr = $('#idusuario').val();
    $.ajax({
        type: "POST",
        url: url + "SolicitacaoAcesso/ajaxCarregaGruposJaAdicionados",
        data: {
            idUsuario: idUsr,
            //idPrograma: progs
        },
        beforeSend: function () {
            $("#prog").css("background", "#FFF url(LoaderIcon.gif) no-repeat 165px");
        },
        success: function (data) {
            if(data === ''){
                $('#divGruposUsuarios').css('display', 'none');
                $('#divMensagemGruposUsuarios').css('display', 'block');
                return false;
            }
            
            if(data == '0'){
                $('#myModalResult .modal-body').html('<h5>Nenhum grupo relacionado ao programa escolhido.</h5>');
                $("#myModalResult").modal('show');
                return false;
            }else{
                $('#frmFluxo #tbodyGruposJaAdicionados').html(data);
                $("#prog").val('');
                $("#idProg").val('');
                //carregaRiscosGruposJaAdicionados();
                // Carrega datatable com programas de cada grupo selecionado
                $('#tableAbaProgsAdicionado').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "oLanguage": {
                        "sLengthMenu": "Mostrar _MENU_ registros",
                        "sZeroRecords": "Nenhum registro encontrado",
                        //"sInfo": "Exibindo de _START_ ate _END_ de _TOTAL_ registros",
                        "sInfo": "Página _PAGE_ de _PAGES_",
                        "sInfoEmpty": "Nenhum registro para ser exibido",
                        //"sInfoFiltered": "(Filtrado de _MAX_ registros no total)",
                        "sInfoFiltered": "",
                        "sSearch": "Pesquisar:",
                        "oPaginate": {
                            "sFirst": "Primeiro",
                            "sLast": "Último",
                            "sNext": "Proximo",
                            "sPrevious": "Anterior"
                        },
                        "sLoadingRecords": "&nbsp;",
                        "sProcessing": '<div class="box-loading-datatable"><div class="spinner pull-left"><img src="'+url+'/assets/images/loader.gif"></div><div class="pull-left" style="margin-top: -10px;">Carregando...</div></div>'
                    },
                    "ajax": {
                        type: "POST",
                        url: url + "Fluxo/ajaxCarregaAbaProsRevisaoAcessoAdicionados/",
                        "data": function (d){
                            d.idUsuario = function(){                                
                                return $('#idusuario').val();
                            };
                        }                    
                    }
                }); 
            }                                                
        }
    });
}


</script>