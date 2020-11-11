<div class="col-md-12 col-sm-12 col-xs-12">
    <ol class="breadcrumb">
        <li>
            <a href="<?php echo URL ?>">
                <font style="vertical-align: inherit;" onclick="loadingPagia()">
                    <font style="vertical-align: inherit;">Dashboard</font></font>
            </a>
        </li>
        <li class="active">Configuração de Fluxo de Atividade</li>
    </ol>
</div>

<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Configuração de Fluxo de Atividade</h2>
            <div class="clearfix"></div>
        </div>
        <br>
        <div class="x_content">
            <div class="row">
                <div class="col-md-12"><?php echo $this->helper->alertMessage(); ?></div>
            </div>
            <div id="alertMessage"></div>
            <div class="row">
                <div class="col-md-3">
                    <label>Fluxo:</label>
                    <select class="form-control" id="fluxo">
                        <option value=""></option>
                        <?php foreach($fluxos as $val): ?>
                            <option value="<?php echo $val['idFluxo']; ?>"><?php echo $val['descricao'] ;?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-9 hide" id="fluxosConfig">
                    <div class="col-md-3 pull-right">
                        <label>&nbsp;</label><br><br> 
                        <button type="button" class="btn btn-success" id="btnConfig">Salvar Configuração</button>
                    </div>
                    <div class="col-md-2 pull-right" id="removeAcesso">
                        <label>Remove acesso:</label>
                        <select class="form-control removeAcesso" name="removeAcesso">
                            <option value="false" selected="true">Não</option>
                            <option value="true">Sim</option>
                        </select>
                    </div>
                    <!--<div class="col-md-2 pull-right" id="cancelaEmAtraso">
                        <label>Cancela em atraso:</label>
                        <select class="form-control cancelaEmAtraso" name="cancelaEmAtraso">
                            <option value="false" selected="true">Não</option>
                            <option value="true">Sim</option>
                        </select>
                    </div>-->
                    <div class="col-md-2 pull-right" id="avisaAcompanhante">
                        <label>Avisa acompanhante(s): </label>
                        <select class="form-control avisaAcompanhante" name="avisaAcompanhante">
                            <option value="false" selected="true">Não</option>
                            <option value="true">Sim</option>
                        </select>
                    </div>
                    <div class="col-md-3 pull-right" class="diasNotifica">
                        <label>Notificar solicitante (Dias): <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" title="Solicitante será notificado a partir da quantidade de dias em que não houver movimentação."></i> </label><br>
                        <input type="number" class="form-control diasNotifica" min="0" name="diasNotifica">
                    </div>

                </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <br><br>
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
                                               style="width: 100%;" id="tableAtiv">
                                            <thead>
                                            <tr role="row">
                                                <!--<th><input type="checkbox" id="checkAllAgendamento"></th>-->
                                                <th width="10%">ID</th>
                                                <th>Descrição</th>
                                                <th width="25%">Próxima Atividade</th>
                                                <th width="10%">Dias p/ Notificação</th>
                                                <th width="10%">Antecedência p/ Notificação</th>
                                                <th width="10%">Situação</th>
                                                <th width="10%">Ação</th>
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
        <br>
    </div>
</div>

<form method="POST" action="<?= URL; ?>/ConfiguracaoFluxo/editarAtividade" id="formEditarAtividade">
    <input type="hidden" name="idAtividade" id="idAtividadeModal" >
    <input type="hidden" name="idFluxo" id="idFluxoModal" >
    <!-- Modal -->
    <div id="myModalEditarAtividade" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Editando Atividade </h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Descrição</label>
                            <input type="text" name="descricao" id="descricao" class="form-control" required="required">
                            <span class="red hide" id="labelErroDescAtiv">Favor preencher com a descrição da atividade.</span>
                        </div>
                        <div class="col-md-6">
                            <label>Próxima Atividade:</label>
                            <select class="form-control" name="proximaAtiv" id="ativModal"></select>
                            <span class="red hide" id="labelErroProvimaAtiv">Favor selecionar a próxima atividade.</span>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-4">
                            <label>Dias de atraso</label>
                            <input type="text" name="diasAtraso" id="diasAtraso" class="form-control" required="required">
                            <span class="red hide" id="labelErroDiasAtrasoAtiv">Favor preencher com número de dias atraso.</span>
                        </div>
                        <div class="col-md-4">
                            <label>Dias de Notificação</label>
                            <input type="text" name="diasNotifica" id="diasNotifica" class="form-control" required="required">
                            <span class="red hide" id="labelErroDiasNotificaAtiv">Favor preencher com número de dias para notificação.</span>
                        </div>
                        <div class="col-md-4">
                            <label>Status:</label>
                            <select class="form-control" name="ativo" id="ativo">
                                <option value="1">Ativo</option>
                                <option value="0">Inativo</option>
                            </select>
                            <span class="red hide" id="labelErroProvimaAtiv">Favor selecionar a próxima atividade.</span>
                        </div>
                    </div>
                    <div class="clearfix">&nbsp;</div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="hide" id="validaExiste">
                                <div class="alert alert-info alert-dismissible">
                                    <!--<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>-->
                                    <div id="msgValidaExiste"></div>
                                </div>
                                <div class="clearfix">&nbsp;</div>
                                <div><ul id="listValidaExiste"></ul></div>

                                <div class="clearfix">&nbsp;</div>
                                <div class="pull-right hide" id="btnsValidaExiste">
                                    <button type="button" class="btn btn-danger" id="btnVoltar">Voltar</button>
                                    <button type="button" class="btn btn-success" id="btnContinuar">Continuar</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnSair" class="btn btn-danger" data-dismiss="modal">Sair</button>
                    <button type="button" id="btnEditarAtividade" class="btn btn-success">Salvar</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function () {
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })

        // Cria instancia do datatable para poder recarregar o mesmo depois
        table = $('#tableAtiv').DataTable({
            paging: false,
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
                url: url + "ConfiguracaoFluxo/ajaxCarregaAtividades/",
                "data": function (d){
                    d.idFluxo = function(){                    
                        return $('#fluxo').val();
                    };
                }
            }
        });

        // Recupera as atividades do fluxo selecionado e atualiza datatable
        $('#fluxo').on('change', function () {
            $('#tableAtiv').DataTable().ajax.reload();

            // Mostra div com as configuraçoes do fluxo selecionado
            if($(this).val() != ''){
                $('#fluxosConfig').removeClass('hide');

                if($(this).val() == 4 || $(this).val() == 5 || $(this).val() == 6 || $(this).val() == 7){
                    $('#diasNotifica').css('display', 'block');
                    $('#avisaAcompanhante').css('display', 'block');
                    $('#cancelaEmAtraso').css('display', 'none');
                    $('#removeAcesso').css('display', 'none');
                }else if($(this).val() == 3){
                    $('#diasNotifica').css('display', 'none');
                    $('#avisaAcompanhante').css('display', 'none');
                    $('#cancelaEmAtraso').css('display', 'block');
                    $('#removeAcesso').css('display', 'block');
                }else{
                    $('#fluxosConfig').addClass('hide');
                }
            }else{
                $('#fluxosConfig').addClass('hide');
            }

            // Busca as informações das configurações do fluxo
            $.ajax({
                type: 'POST',
                url:  url + "ConfiguracaoFluxo/buscaConfigFluxo/",
                data: { idFluxo: $('#fluxo').val() },
                dataType: 'json',
                success: function(data){
                    jsonData = JSON.parse(data);

                    $('.removeAcesso').val(jsonData.removeAcesso);
                    $('.cancelaEmAtraso').val(jsonData.cancelaEmAtraso);
                    $('.diasNotifica').val(jsonData.diasNotifica);
                    $('.avisaAcompanhante').val(jsonData.avisaAcompanhante);
                }
            });
        });

        // Salva as configurações de fluxos
        $('#btnConfig').on('click', function(){
            $.ajax({
                type: 'POST',
                url:  url + "ConfiguracaoFluxo/atualizaConfigFluxo/",
                data: {
                    removeAcesso:       $('.removeAcesso').val(),
                    cancelaEmAtraso:    $('.cancelaEmAtraso').val(),
                    diasNotifica:       $('.diasNotifica').val(),
                    avisaAcompanhante:  $('.avisaAcompanhante').val(),
                    idFluxo:            $('#fluxo').val()
                },
                dataType: 'json',
                beforeSend: function(){
                    $('#load').css('display', 'block');
                },
                success: function(data){
                    $('#load').css('display', 'none');

                    html = '<div class="alert alert-'+data.type+' alert-dismissible">';
                    html += '    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
                    html +=      data.msg;
                    html += '</div>'

                    $('#alertMessage').html(html);
                }
            });
        });
    });

    function carregaModalAtividade(idAtividade, idFluxo){
        $.ajax({
            type: 'POSt',
            url: url+'ConfiguracaoFluxo/buscaAtividade',
            data: {
                idAtividade: idAtividade,
                idFluxo: idFluxo
            },
            success: function (res) {
                data = JSON.parse(res);

                if(data.return == true){
                    $('#descricao').val(data.dados.descricao);
                    $('#diasAtraso').val(data.dados.diasAtraso);
                    $('#diasNotifica').val(data.dados.diasNotifica);
                    $('#idAtividadeModal').val(data.dados.id);
                    $('#idFluxoModal').val(data.dados.idFluxo);
                    $('#ativo').val(data.dados.ativo);

                    // Cria os options para o select de atividades
                    $('#ativModal').html('');
                    $('#ativModal').append('<option value=""></option>');
                    if(data.atividades.length > 0){
                        for(x in data.atividades){
                            $('#ativModal').append('<option value="'+data.atividades[x].id+'"'+((data.atividades[x].id == data.dados.proximaAtiv) ? ' selected' : '')+'>'+data.atividades[x].descricao+'</option>')
                        }
                    }

                    // Abre o modal
                    $('#myModalEditarAtividade').modal('show');
                }else{
                }
            }
        });

        // Valida se campos foram preenchidos ao clicar no botão de envio
        $('#btnEditarAtividade').on('click', function(){
            var desc = $('#descricao').val();
            var diasAtraso = $('#diasAtraso').val();
            var diasNotifica = $('#diasNotifica').val();

            if(desc = ''){
                $('#labelErroDescAtiv').removeClass('hide');
                return false;
            }

            if(diasAtraso == ''){
                $('#labelErroDiasAtrasoAtiv').removeClass('hide');
                return false;
            }

            if( diasNotifica == '') {
                $('#labelErroDiasNotificaAtiv').removeClass('hide');
                return false;
            }

            // Submete o form
            $('#formEditarAtividade').submit()
        });

        // Ao digitar em um dos campos esconde a div de alerta de erros
        $('#descricao, #diasAtraso, #diasNotifica').on('keyup', function(){
            $(this).next('span').addClass('hide');
        });

    }

</script>