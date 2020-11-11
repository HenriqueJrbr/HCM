<style>
    #grupos,
    #gruposAdd{
        min-height: 300px;
        background: #f2f2f2;
    }
</style>
<div class="col-md-12 col-sm-12 col-xs-12">
    <ol class="breadcrumb">
        <li>
            <a href="<?php echo URL ?>">
                <font style="vertical-align: inherit;" onclick="loadingPagia()">
                <font style="vertical-align: inherit;">Dashboard</font></font>
            </a>
        </li>
        <li class="active"><a href="<?php echo URL; ?>/Provisionamento/">Provisionamento</a></li>
        <li class="active">Edição</li>
    </ol>
</div>

<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Edição de provisionamento por função</h2>
            <div class="clearfix"></div>
        </div>
        <br>
        <div class="x_content">
            <form action="<?= URL; ?>/Provisionamento/gravaProvisionamento" id="formProvisionamento" method="post">
                <div class="row">
                    <div class="col-md-12"><?php echo $this->helper->alertMessage(); ?></div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label>Empresa:</label>
                        <select name="empresa" class="form-control" id="instancia" readonly>                            
                            <?php foreach($instancias as $val): ?>
                            <option value="<?php echo $val['idEmpresa']; ?>" selected="true"><?php echo $val['razaoSocial'] ;?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="clearfix"></div>
                <br>
                <div class="row">
                    <div class="col-md-3">
                        <label>Função:</label>
                        <select name="funcao" class="form-control" id="funcao" readonly>
                            <?php foreach($funcoes as $val): ?>
                            <option value="<?php echo $val['idFuncao']; ?>"><?php echo $val['descricao'] ;?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="clearfix"></div>
                <br><br>

                <div class="row">
                    <div class="col-md-4">
                        <label>Grupos:</label>
                        <select class="form-control" id="grupos" multiple>
                            <?php foreach($grupos as $val): ?>
                            <option value="<?php echo $val['idGrupo']; ?>"><?php echo $val['descricao']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <div style="float: none; margin: 120px 0 0 30%;">
                            <button type="button" class="btn btn-success" id="btnAddGrupo"> >> </button><br>
                            <button type="button" class="btn btn-danger" id="btnRemoveGrupo"> << </button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label>Grupos à adicionar:</label>
                        <select class="form-control" id="gruposAdd" multiple></select>
                    </div>
                </div>
                <div class="clearfix"></div>
                <br><br>
                <div class="row">
                    <div class="col-md-2 pull-right">
                        <button type="button" class="btn btn-danger" onclick="javascript:history.back(-1)">Voltar</button>                
                        <button type="button" class="btn btn-success" id="btnSalvarProv">Salvar</button>
                    </div>
                </div>
            </form>
            
            <div class="clearfix"></div>
            <br><br>

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
                                        <li role="presentation" class="active">
                                            <a href="#tab_content1" id="profile-tab1" role="tab" data-toggle="tab" aria-expanded="true">Riscos <span class="badge badge-danger" id="count-riscos"></span></a>
                                        </li>
                                        <li role="presentation">
                                            <a href="#tab_content2" id="profile-tabProg" role="tab" data-toggle="tab" aria-expanded="true">Grupos <span class="badge totalGrupo"></span></a>
                                        </li>
                                        <!-- <li role="presentation">
                                            <a href="#tab_content3" id="profile-tabProg" role="tab" data-toggle="tab" aria-expanded="true">Programas <span class="badge totalProgByGrupo"></span></a>
                                        </li> -->
                                    </ul>
                                    <div id="myTabContent" class="tab-content">
                                        <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="profile-tab1"><!--Inicio Tabela 1 -->
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
                                                                <h5 class="text-center hide" id="text-risco">Analisando riscos...</h5>
                                                            </div>
                                                            <div id="matriz-risco-grupos"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div role="tabpane1" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tabProg"><!--Inicio Tabela 1 -->


                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="panel panel-default">
                                                        <!--<div class="panel-heading" style="background-color:#2A3F54;color: #FFFFFF"><h4>Atividades</h4></div>-->
                                                        <div class="panel-body">
                                                            <div id="datatable-responsive_wrapper"
                                                                 class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                                                <form action="<?= URL; ?>/Provisionamento/excluiProvisionamento" id="formApagaFuncaoGrupo" method="post">
                                                                    <div class="row">
                                                                        <div class="col-sm-6"></div>
                                                                        <div class="col-sm-6"></div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col-sm-12">
                                                                            <table class="table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline"
                                                                                   cellspacing="0" width="100%" role="grid"
                                                                                   aria-describedby="datatable-responsive_info"
                                                                                   style="width: 100%;" id="tableFuncaoGrupo">
                                                                                <thead>
                                                                                <tr role="row">
                                                                                    <th width="10%"><input type="checkbox" class="no-" id="checkAllFuncaoGrupo"></th>
                                                                                    <th>Função</th>
                                                                                    <th>Grupo</th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody></tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <button type="button" class="btn btn-danger pull-left" id="btnApagaFuncaoGrupo">Apagar Selecionados</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
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
        <br>
    </div>
</div>  

<div id="myModalResult" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <p id="result_msg"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#btnAddGrupo').click(function(){            
            $("#grupos option:selected" ).each(function() {  
                $(this).remove();
                $("#gruposAdd").append('<option value="'+$(this).val()+'">'+$(this).text()+"</option>");
                carregaRiscosGrupos();
            });
        });
        
        $('#btnRemoveGrupo').click(function(){
            $("#gruposAdd option:selected" ).each(function() {  
                $(this).remove();                
                $("#grupos").append('<option value="'+$(this).val()+'">'+$(this).text()+"</option>");
                carregaRiscosGrupos();
            });
        });
        
        // Cria instancia do datatable para poder recarregar o mesmo depois        
        table = $('#tableFuncaoGrupo').DataTable({
            "aoColumns": [
                {"bSortable": false, "aTargets": [0]},
                {"bSortable": true, "aTargets": [1]},
                {"bSortable": true, "aTargets": [2]}
            ],
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
                url: url + "Provisionamento/ajaxDatatableProvisionamento",
                "data": function (d){                        
                    d.empresa = $('#instancia').val();
                    d.funcao = $('#funcao').val();                        
                }                    
            }
        });    

        // Recupera as atividades do fluxo selecionado e atualiza datatable
        /*$('#instancia').on('change', function (){
            $('#funcao').val('');
            
            // Recarrega o datatable
            $('#tableFuncaoGrupo').DataTable().ajax.reload();
        });
        
        // Carrega os grupos filtrando pela função e instância
        $('#funcao').on('change', function (){
            $.ajax({
                type: 'POSt',
                url: url+'provisionamento/ajaxCarregaGrupos',
                data: {
                    empresa: $('#instancia').val(),
                    funcao: $('#funcao').val()
                },
                success: function (res) {
                    $('#grupos').html('');
                    $('#grupos').append(res);
                }
            });
            
            // Recarrega o datatable
            $('#tableFuncaoGrupo').DataTable().ajax.reload();
        });*/
        
        // Marca ou desmarca checkbox de grupos
        $('#checkAllFuncaoGrupo').on('click', function(){
            $('.checkFuncaoGrupo').not(this).prop('checked', this.checked);
        });
        
        // Apaga provisionamentos selecionados no datatable
        $('#btnApagaFuncaoGrupo').on('click', function(){
            var funcaoGrupoDel = [];

            $('.checkFuncaoGrupo').each(function(){
                if($(this).prop('checked')) {
                    funcaoGrupoDel.push($(this).attr('id'));
                }
            });

            // Valida se foi selecionado ao menos um módulo
            if(funcaoGrupoDel.length == 0){
                return false;
            }

            $('#formApagaFuncaoGrupo').submit();
        });
        
        // Submete o formulario com grupos adicionados
        $('#btnSalvarProv').on('click', function(){
            if($("#gruposAdd option").length == 0){
                $('#myModalResult .modal-body').html('<h5>Favor selecionar ao menos um grupo.</h5>');
                $("#myModalResult").modal('show');
                return false;
            }
            
            $("#gruposAdd option" ).each(function() {  
                $(this).remove();                
                $("#formProvisionamento").append('<input type="hidden" name="grupos[]" value="'+$(this).val()+'">');                                
            });
            
            
            $("#formProvisionamento").submit();
        });

        // Recupera matriz de risco
        function carregaRiscosGrupos(){
            var grupos = new Array();

            // Adiciona os grupos a adicionar para o array de grupos
            $("#gruposAdd option" ).each(function() {
                grupos.push($(this).val());
            });

            // Adiciona os grupos ja adicionados para o array de grupos
            $(".checkFuncaoGrupo").each(function() {
                grupos.push($(this).val());
            });

            if(grupos.length == 0) {
                grupos.push(0);
            }

            $('#matriz-risco-grupos').html('');
            $('#text-risco').css('display', 'block');
            $('#load').css('display', 'block');

            $.ajax({
                type: 'POST',
                url:  url+'Provisionamento/ajaxMatrizDeRisco',
                data: {
                    grupos: grupos,
                    funcao: $('#funcao').val()
                },
                success: function(res){
                    var data = JSON.parse(res);
                    $('#matriz-risco-grupos').html(data.html);
                    $('#count-riscos').html(data.totalRiscos);
                    $('#riscos').val(data.totalRiscos);
                    $('#text-risco').css('display', 'none');
                    $('#load').css('display', 'none');
                    $('.totalProgByGrupo').text(data.totalProgByGrupo);
                    $('.totalGrupo').text(data.totalGrupo);

                    $('#tableAbaProgs').DataTable().ajax.reload();

                }
            });
        }

        setTimeout(function () {
            carregaRiscosGrupos();
        }, 2000);
    });
</script>