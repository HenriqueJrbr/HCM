<style>
    #validaExiste ul{
        padding-left: 15px;
        list-style: none;
        max-height: 150px;
        overflow: auto;
    }



    #validaExiste li{
        padding: 3px;
        border-bottom: solid 1px #e1e1e1;
    }

    .bgList{
        background: #edeff5;
    }
</style>
<div class="col-md-12 col-sm-12 col-xs-12">
    <ol class="breadcrumb">
        <li><a href="<?php echo URL ?>/" onclick="loadingPagia()">Dashboard</a></li>
        <li class="active">Manutenção</li>
        <li class="active">Grupo</li>
    </ol>
</div>   
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Grupos Totvs</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <div class="row">
                    <div class="col-md-12">
                        <?php $this->helper->alertMessage(); ?>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModalAddGrupo">
                            Cadastar novo grupo
                        </button>
                    </div>
                </div>
                <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                    <div class="row">
                        <div class="col-sm-6 col-md-12"></div>
                        <div class="col-sm-6 col-md-12"></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-12">
                            <table class="table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline"
                                   cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info"
                                   style="width: 100%;" id="table">
                                <thead>
                                <tr role="row">
                                    <th>ID Grupo</th>
                                    <th>Descrição</th>
                                    <th>Quant. Programas</th>
                                    <th>Quant. Usuário</th>
                                    <th>Ação</th>
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

<form method="POST" action="<?= URL; ?>/Manutencao/addGrupo" id="formAddGrupo">
    <!-- Modal -->
    <div id="myModalAddGrupo" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Cadastrar Grupo</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Grupo</label>
                            <input type="text" name="nameGrupo" class="form-control" maxlength="3" required="required">
                            <span class="red hide" id="labelErroNomeGrupo">Favor preencher com o nome do grupo.</span>
                        </div>
                        <div class="col-md-6">
                            <label>Descrição</label>
                            <input type="text" name="descGrupo" class="form-control" required="required" max="32">
                            <span class="red hide" id="labelErroDescGrupo">Favor preencher com a descrição do grupo.</span>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-3">
                            <label>
                                <input type="checkbox" name="deseja_clonar_Grupo" id="deseja_clonar_Grupo"> Deseja clonar grupo ?
                            </label>
                        </div>
                    </div>
                    <div class="clone hide">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Grupo a ser clonado</label>
                                <input type="hidden" name="idLegGrupoClone" id="idLegGrupoClone">
                                <select class="form-control" name="grupoClone" id="grupoClone" style="width: 100%">
                                    <option value="0"></option>
                                    <?php foreach ($listaGrupo as $value):?>
                                        <option value="<?php echo $value['idGrupo'] ?>"><?php echo $value['idLegGrupo'] ?> - <?php echo $value['descAbrev'] ?> </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Deseja clonar os Usuários ?</label>
                                <input type="checkbox" class="form-control" name="deseja_clonar_usuario" id="deseja_clonar_usuario"> 
                            </div>
                            <div class="col-md-3">
                                <label>
                                    Deseja clonar os Programas ?
                                </label>
                                <input type="checkbox" class="form-control" name="deseja_clonar_programa" id="deseja_clonar_programa"> 
                            </div>
                        </div>
                    </div>
                    <div class="clearfix">&nbsp;</div>
                    <div class="row">
                        <div class="col-md-12">
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
                    <button type="button" name="addGrupo" id="btnAddGrupo" class="btn btn-success">Salvar</button>
                </div>
            </div>
        </div>
    </div>
</form>


<?php $this->helper->scriptDataTable('table', 'Manutencao/ajaxDatatableGrupos', 'POST'); ?>

<script type="text/javascript" language="javascript">
    $(document).ready(function () {


        $("#deseja_clonar_Grupo").change(function(){
            var valor = $(this).is(":checked");

            if(valor == true){
                $(".clone").removeClass('hide');
            }else{
                $(".clone").addClass('hide');
                $("#grupoClone").select2("val","0");
                $("#deseja_clonar_programa").prop("checked", false);
                 $("#deseja_clonar_usuario").prop("checked", false);
            }
           
        });

        $("#grupoClone").on('change', function(){
            var grupo = $('#grupoClone option:selected').text().split('-');
            console.log(grupo);
            $('#idLegGrupoClone').val(grupo[0].trim());
        });

        $("#grupoClone").select2();
        // Recupera evento de click do botao de salvar grupo
        $('#btnAddGrupo').on('click', function(){
            // Valida se os inputs foram preenchidos
            var nomeGrupo = $('input[name=nameGrupo]').val();
            var descGrupo = $('input[name=descGrupo]').val();

            // Valida se foi preenchido o nome do grupo.
            // caso não exibe o label de erro e retorna false
            if(nomeGrupo == ''){
                $('#myModalAddGrupo').find('#labelErroNomeGrupo').toggleClass('hide');
                return false;
            }

            // Valida se foi preenchido a descrição do grupo.
            // caso não exibe o label de erro e retorna false
            if(descGrupo == ''){
                $('#myModalAddGrupo').find('#labelErroDescGrupo').toggleClass('hide');
                return false;
            }

            if(nomeGrupo.length > 3){
                return false;
            }

            // Valida se grupo ja exsite
            $.ajax({
                type: 'POST',
                url: url + '/Manutencao/validaGrupoExistente',
                data: {
                    nameGrupo: nomeGrupo,
                    descGrupo: descGrupo
                },
                success: function(data){
                    var result = JSON.parse(data);

                    // Se já exibe uma alert e os dados do grupo encontrado na base
                    if(result.return == 'existe'){
                        var html = '';
                        for (let i = 0; i < result.data.length; i++) {
                            html += '<li '+(((i % 2) == 1) ? 'class="bgList"' : '')+'>' + result.data[i]['idLegGrupo'] + ' - ' + result.data[i]['descAbrev'] + '</li>';
                        }

                        $('#myModalAddGrupo').find('#msgValidaExiste').html("<strong>Grupo já criado na base de dados.</strong>");
                        $('#myModalAddGrupo').find('#listValidaExiste').html(html);
                        $('#myModalAddGrupo').find('#validaExiste').removeClass('hide');
                        return false;
                    }else if(result.return == true){
                        // Se não encontrou um grupo com nome identico ao digitado. mostra os parecidos
                        var html = '';

                        for (let i = 0; i < result.data.length; i++) {
                            html += '<li '+(((i % 2) == 1) ? 'class="bgList"' : '')+'>' + result.data[i]['idLegGrupo'] + ' - ' + result.data[i]['descAbrev'] + '</li>';
                        }
                        $('#myModalAddGrupo').find('#msgValidaExiste').html("<strong>Encontramos grupos semelhantes ao preenchido.</strong>");
                        $('#myModalAddGrupo').find('#listValidaExiste').html(html);
                        $('#myModalAddGrupo').find('#validaExiste').removeClass('hide');
                        $('#myModalAddGrupo').find('#btnsValidaExiste').removeClass('hide');

                        // Desabilito os inputs evitando que usuario mude os valores digitados e escondo os botões de salvar e sair
                        $('input[name=nameGrupo').attr('readonly', 'true');
                        $('input[name=descGrupo').attr('readonly', 'true');
                        $('#btnAddGrupo').addClass('hide');
                        $('#btnSair').addClass('hide');
                        return false;
                    }else if(result.return == 'salvar'){
                        $('#formAddGrupo').submit();
                    }
                }
            });
        });

        // se botao de continuar for clicado submete o form
        $('#btnContinuar').on('click', function(){
            $('#formAddGrupo').submit();
        });

        // se botao de voltar for clicado limpa a div de validação e habilita os inputs
        $('#btnVoltar').on('click', function(){
            // Habilito os inputs para que o usuario digite outros valores
            $('input[name=nameGrupo').removeAttr('readonly', 'true');
            $('input[name=descGrupo').removeAttr('readonly', 'true');
            $('#btnAddGrupo').removeClass('hide');
            $('#btnSair').removeClass('hide');

            // Limpo os dados de validação e escondo a div
            $('#myModalAddGrupo').find('#validaExiste').addClass('hide');
            $('#myModalAddGrupo').find('#btnsvalidaExiste').addClass('hide');
            $('#myModalAddGrupo').find('#msgValidaExiste').html("");
            $('#myModalAddGrupo').find('#listValidaExiste').html('');
        });

        // se botao de sair for clicado limpa os inputs
        $('#btnSair').on('click', function() {
            $('input[name=nameGrupo').val('');
            $('input[name=descGrupo').val('');
        });

        // Esconde o label de erro do nome do grupo
        $('input[name=nameGrupo]').on('keyup', function(){
            if($('#myModalAddGrupo').find('#validaExiste').hasClass('hide')){
                $('#myModalAddGrupo').find('#labelErroNomeGrupo').addClass('hide');
                $('#myModalAddGrupo').find('#validaExiste').addClass('hide');
                $('#myModalAddGrupo').find('#btnsvalidaExiste').addClass('hide');
                $('#myModalAddGrupo').find('#msgValidaExiste').html("");
                $('#myModalAddGrupo').find('#listValidaExiste').html('');
            }
        });

        // Esconde o label de erro de descrição do grupo
        $('input[name=descGrupo]').on('keyup', function(){
            if($('#myModalAddGrupo').find('#validaExiste').hasClass('hide')){
                $('#myModalAddGrupo').find('#labelErroDescGrupo').addClass('hide');
                $('#myModalAddGrupo').find('#validaExiste').addClass('hide');
                $('#myModalAddGrupo').find('#btnsvalidaExiste').addClass('hide');
                $('#myModalAddGrupo').find('#msgValidaExiste').html("");
                $('#myModalAddGrupo').find('#listValidaExiste').html('');
            }
        });
    });

    function habilitaInputs(){
        // Habilito os inputs para que o usuario digite outros valores
        $('input[name=nameGrupo').removeAttr('readonly', 'true');
        $('input[name=descGrupo').removeAttr('readonly', 'true');
        $('#btnAddGrupo').removeClass('hide');
        $('#btnSair').removeClass('hide');

        // Limpo os dados de validação e escondo a div
        $('#myModalAddGrupo').find('#validaExiste').addClass('hide');
        $('#myModalAddGrupo').find('#btnsvalidaExiste').addClass('hide');
        $('#myModalAddGrupo').find('#msgValidaExiste').html("");
        $('#myModalAddGrupo').find('#listValidaExiste').html('');
    }

</script>