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
        <li class="active">Provisionamento</li>
    </ol>
</div>

<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Provisionamento por função</h2>
            <div class="clearfix"></div>
        </div>
        <br>
        <div class="x_content">            
            <div class="row">
                    <div class="col-md-12">
                        <?php $this->helper->alertMessage(); ?>
                    </div>
                    <div class="col-md-2">
                        <a href="<?php echo URL; ?>/Provisionamento/cadastro" class="btn btn-success btn-sm">Cadastrar provisionamento</a>
                    </div>
                </div>
            <div class="clearfix"></div>            
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
                                                    <!-- <th width="10%"><input type="checkbox" id="checkAllFuncaoGrupo"></th> -->
                                                    <th>Empresa</th>
                                                    <th>Função</th>
                                                    <th>Grupos</th>
                                                    <th>Ação</th>
                                                </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>                                    
                                </form>
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
        // Cria instancia do datatable para poder recarregar o mesmo depois        
        table = $('#tableFuncaoGrupo').DataTable({
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
                url: url + "Provisionamento/ajaxDatatableProvisionamento"                
            }
        });
    });        
</script>