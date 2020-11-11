<style>
    .panel-group{
        margin-bottom: 0 !important ;
    }
    .nav-tabs > li.active > a,
    .nav-tabs > li.active > a:focus,
    .nav-tabs > li.active > a:hover{
        background-color: #FCC287;
        color: #5A738E
    }
    ul.bar_tabs > li a:hover{
        background-color: #FCC287;
        color: #5A738E
    }
</style>
<div class="col-md-12">
    <ol class="breadcrumb">
        <li><a href="<?php echo URL ?>/" onclick="loadingPagia()">Dashboard</a></li>
        <li><a href="<?php echo URL ?>/Processo/" onclick="loadingPagia()">Processos</a></li>
        <li class="active"><?php echo $proc[0]['idProcesso'] . ' - ' . $proc[0]['descProcesso']; ?></li>
    </ol>
</div>

<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel"><!--Inicia x_panel-->

        <div class="x_title"><!--Inicia x_title-->
            <h2>Processo - <?php echo $proc[0]['descProcesso']; ?></h2>
            <div class="clearfix"></div>
        </div><!--Fim x_title-->

        <div class="x_content">
            <div class="" role="tabpanel" data-example-id="togglable-tabs">
                <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#tab_content20" id="profile-tab20" role="tab" data-toggle="tab" aria-expanded="true">Visão Atual</a>
                    </li>
                    <li role="presentation" class="">
                        <a href="#tab_content30" role="tab" id="profile-tab30" data-toggle="tab" aria-expanded="false">Visão Anterior <?php echo (!empty($dataSnapshot) ? "<span class=\"badge\"><strong>".date('d/m/Y H:i:s', strtotime($dataSnapshot['dataHora']))."</strong></span>" : ''); ?></span></a>
                    </li>
                </ul>
                <div class="clearfix"><br></div>
                <div class="tab-content">
                    <!-- INICIO ESTADO ATUAL -->
                    <div role="tabpanel" class="tab-pane fade active in" id="tab_content20" aria-labelledby="profile-tab20"><!--Inicio Tabela 1 -->
                        <div class="" role="tabpanel" data-example-id="togglable-tabs">
                            <ul class="nav nav-tabs bar_tabs" role="tablist">
                                <li role="presentation" class="active">
                                    <a href="#tab_grupos" id="profile-tab_grupos" role="tab" data-toggle="tab" aria-expanded="true">
                                        Grupos <span class="badge"><?php echo $proc[0]['numGrupos'] ?>
                                    </a>
                                </li>
                                <li role="presentation" class="">
                                    <a href="#tab_usuarios" role="tab" id="profile-tab_usuarios" data-toggle="tab" aria-expanded="false">
                                        Usuários <span class="badge"><?php echo $proc[0]['numUsuarios'] ?></span>
                                    </a>
                                </li>
                                <li role="presentation" class="">
                                    <a href="#tab_programas" role="tab" id="profile-tab_programas" data-toggle="tab" aria-expanded="false">
                                        Programas <span class="badge"><?php echo $proc[0]['numProgramas'] ?></span>
                                    </a>
                                </li>
                                <li role="presentation" class="">
                                    <a href="#tab_modulos" role="tab" id="profile-tab_modulos" data-toggle="tab" aria-expanded="false">
                                       Módulos <span class="badge"><?php echo $proc[0]['numModulos'] ?></span>
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <!--Inicio Tab Grupos -->
                                <div role="tabpanel" class="tab-pane fade active in" id="tab_grupos" aria-labelledby="profile-tab_grupos">
                                    <br>
                                    <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                        <div class="row"><div class="col-sm-6"></div><div class="col-sm-6"></div></div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <table  class="tabela table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info" style="width: 100%;" id="tableGrupoUsuario">
                                                    <thead>
                                                        <tr>
                                                            <th>Id Grupo</th>
                                                            <th>Descrição</th>
                                                            <th>Gestor</th>
                                                            <th>Quant. Programas</th>
                                                            <th>Quant. Usuários</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach($grupos as $valor): ?>
                                                        <tr>
                                                            <td><?php echo utf8_decode($valor['idLegGrupo'])?></td>
                                                            <td><?php echo $valor['descAbrev']?></td>
                                                            <td><?php echo $valor['nomeGestor']?></td>
                                                            <td><span class="badge label-primary"><?php echo $valor['numProgramas']?></span></td>
                                                            <td><span class="badge label-primary"><?php echo $valor['numUSuarios']?></span></td>
                                                        </tr>
                                                        <?php endforeach;?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div><!--Fim Tab Grupos -->

                                <!--Inicio Tab Usuários -->
                                <div role="tabpanel" class="tab-pane fade " id="tab_usuarios" aria-labelledby="profile-tab_usuarios">
                                    <!--<div class="loadProgUsr">
                                        <center><img src="<?php /*echo URL */?>/assets/images/loader.gif"></center>
                                    </div>-->
                                    <br>
                                    <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                        <div class="row"><div class="col-sm-6"></div><div class="col-sm-6"></div></div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <table class="tabelaProg table table-striped hover table-striped dt-responsive nowrap no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info" style="width: 100%;" id="tableProgUsuario">
                                                    <thead>
                                                        <tr role="row">
                                                            <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 71px;" aria-sort="ascending" aria-label="First name: activate to sort column descending">ID</th>
                                                            <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 70px;" aria-label="Last name: activate to sort column ascending">Nome</th>
                                                            <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 70px;" aria-label="Last name: activate to sort column ascending">ID Totvs</th>
                                                            <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 70px;" aria-label="Last name: activate to sort column ascending">Gestor</th>
                                                            <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 70px;" aria-label="Last name: activate to sort column ascending">Situação do usuário</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="carregaUsrProc">
                                                        <?php foreach($usuarios as $valor): ?>
                                                        <tr>
                                                            <td><?php echo utf8_decode($valor['idUsuario'])?></td>
                                                            <td><?php echo $valor['nome_usuario']?></td>
                                                            <td><?php echo $valor['idTotvs']?></td>
                                                            <td><?php echo $valor['gestor']?></td>
                                                            <td class="text-center"><span class="badge label-primary"><?php echo $valor['situacao']?></span></td>
                                                        </tr>
                                                        <?php endforeach;?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Fim Tab Usuários -->
                                <!--Inicio Tab Programas -->
                                <div role="tabpanel" class="tab-pane fade " id="tab_programas" aria-labelledby="profile-tab_programas">
                                    <br>
                                    <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                        <div class="row"><div class="col-sm-6"></div><div class="col-sm-6"></div></div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <table  class="table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info" style="width: 100%;" id="tableProgProcesso">
                                                    <thead>
                                                    <tr role="row">
                                                        <th>Grupos</th>
                                                        <th>Programa</th>
                                                        <th>Descrição</th>
                                                        <th>Rotina</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="carregaProgsProc"></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--Inicio Tab Programas-->

                                <!--Inicio Tab módulos -->
                                <div role="tabpanel" class="tab-pane fade " id="tab_modulos" aria-labelledby="profile-tab_modulos">
                                    <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                        <div class="row"><div class="col-sm-6"></div><div class="col-sm-6"></div></div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <table class="table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info" style="width: 100%;" id="tableModuloProcesso">
                                                    <thead>
                                                        <tr>
                                                            <th>Cód. Módulo</th>
                                                            <th>Desc. Módulo Datasul</th>
                                                            <th>Módulo</th>
                                                            <th>Rotina</th>
                                                            <th>Programas</th>
                                                            <th>Total Programas</th>
                                                            <th>Grupos</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--Fim Tab módulos -->
                            </div>
                        </div>
                    </div>
                    <!-- FIM ESTADO ATUAL -->

                    <!-- INICIO SNAPSHOT -->
                    <div role="tabpanel" class="tab-pane fade" id="tab_content30" aria-labelledby="profile-tab30"><!--Inicio Tabela 1 -->
                        <div class="" role="tabpanel" data-example-id="togglable-tabs">
                            <ul id="myTab-snapshot" class="nav nav-tabs bar_tabs" role="tablist">
                                <li role="presentation" class="active">
                                    <a href="#tab_grupos_snapshot" id="profile-tab_grupos_snapshot" role="tab" data-toggle="tab" aria-expanded="true">
                                        Grupos <span class="badge"><?php echo $procSnapshot[0]['numGrupos'] ?>
                                    </a>
                                </li>
                                <li role="presentation" class="">
                                    <a href="#tab_usuarios_snapshot" role="tab" id="profile-tab_usuarios_snapshot" data-toggle="tab" aria-expanded="false">
                                        Usuários <span class="badge"><?php echo ($procSnapshot[0]['numUsuarios'] != '' || $procSnapshot[0]['numUsuarios'] > 0) ? $procSnapshot[0]['numUsuarios'] : 0  ?></span>
                                    </a>
                                </li>
                                <li role="presentation" class="">
                                    <a href="#tab_programas_snapshot" role="tab" id="profile-tab_programas_snapshot" data-toggle="tab" aria-expanded="false">
                                        Programas <span class="badge"><?php echo $procSnapshot[0]['numProgramas'] ?></span>
                                    </a>
                                </li>
                                <li role="presentation" class="">
                                    <a href="#tab_modulos_snapshot" role="tab" id="profile-tab_modulos_snapshot" data-toggle="tab" aria-expanded="false">
                                        Módulos <span class="badge"><?php echo $procSnapshot[0]['numModulos'] ?></span>
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <!--Inicio Tab Grupos -->
                                <div role="tabpanel" class="tab-pane fade active in" id="tab_grupos_snapshot" aria-labelledby="profile-tab_grupos_snapshot">
                                    <br>
                                    <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                        <div class="row"><div class="col-sm-6"></div><div class="col-sm-6"></div></div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <table class="tabela table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info" style="width: 100%;" id="tableGrupoUsuario_snapshot">
                                                    <thead>
                                                    <tr>
                                                        <th>Id Grupo</th>
                                                        <th>Descrição</th>
                                                        <th>Gestor</th>
                                                        <th>Quant. Programas</th>
                                                        <th>Quant. Usuários</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php foreach($gruposSnapshot as $valor): ?>
                                                        <tr>
                                                            <td><?php echo utf8_decode($valor['idLegGrupo'])?></td>
                                                            <td><?php echo $valor['descAbrev']?></td>
                                                            <td><?php echo $valor['nomeGestor']?></td>
                                                            <td><span class="badge label-primary"><?php echo $valor['numProgramas']?></span></td>
                                                            <td><span class="badge label-primary"><?php echo $valor['numUSuarios']?></span></td>
                                                        </tr>
                                                    <?php endforeach;?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div><!--Fim Tab Grupos -->

                                <!--Inicio Tab Usuários -->
                                <div role="tabpanel" class="tab-pane fade " id="tab_usuarios_snapshot" aria-labelledby="profile-tab_usuarios_snapshot">
                                    <br>
                                    <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                        <div class="row"><div class="col-sm-6"></div><div class="col-sm-6"></div></div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <table class="tabelaProg table table-striped hover table-striped dt-responsive nowrap no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info" style="width: 100%;" id="tableProgUsuario_snapshot">
                                                    <thead>
                                                    <tr role="row">
                                                        <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 71px;" aria-sort="ascending" aria-label="First name: activate to sort column descending">ID</th>
                                                        <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 70px;" aria-label="Last name: activate to sort column ascending">Nome</th>
                                                        <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 70px;" aria-label="Last name: activate to sort column ascending">ID Totvs</th>
                                                        <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 70px;" aria-label="Last name: activate to sort column ascending">Gestor</th>
                                                        <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 70px;" aria-label="Last name: activate to sort column ascending">Situação</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="carregaUsrProc">
                                                    <?php foreach($usuariosSnapshot as $valor): ?>
                                                        <tr>
                                                            <td><?php echo utf8_decode($valor['idUsuario'])?></td>
                                                            <td><?php echo $valor['nome_usuario']?></td>
                                                            <td><?php echo $valor['idTotvs']?></td>
                                                            <td><?php echo $valor['gestor']?></td>
                                                            <td><span class="badge label-primary"><?php echo $valor['situacao']?></span></td>
                                                        </tr>
                                                    <?php endforeach;?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Fim Tab Usuários -->
                                <!--Inicio Tab Programas -->
                                <div role="tabpanel" class="tab-pane fade " id="tab_programas_snapshot" aria-labelledby="profile-tab_programas_snapshot">
                                    <br>
                                    <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                        <div class="row"><div class="col-sm-6"></div><div class="col-sm-6"></div></div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <table  class="table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info" style="width: 100%;" id="tableProgProcesso_snapshot">
                                                    <thead>
                                                    <tr role="row">
                                                        <th>Grupos</th>
                                                        <th>Programa</th>
                                                        <th>Descrição</th>
                                                        <th>Rotina</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="carregaProgsProc"></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--Inicio Tab Programas-->

                                <!--Inicio Tab módulos -->
                                <div role="tabpanel" class="tab-pane fade " id="tab_modulos_snapshot" aria-labelledby="profile-tab_modulos_snapshot">
                                    <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                        <div class="row"><div class="col-sm-6"></div><div class="col-sm-6"></div></div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <table class="table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info" style="width: 100%;" id="tableModuloProcesso_snapshot">
                                                    <thead>
                                                        <tr>
                                                            <th>Cód. Módulo</th>
                                                            <th>Desc. Módulo Datasul</th>
                                                            <th>Módulo</th>
                                                            <th>Rotina</th>
                                                            <th>Programas</th>
                                                            <th>Total Programas</th>
                                                            <th>Grupos</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--Fim Tab módulos -->
                            </div>
                        </div>
                    </div>
                    <!-- FIM SNAPSHOT -->
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        /************************ CARREGA DATATABLE ATUAL *****************************/
        // Carrega Datatable de Programas, validando se o mesmo ainda não foi carregado.
        $('#profile-tab_programas').on('click', function(){
            if($('#tableProgProcesso').find('tbody tr').length == 0){
                $("#tableProgProcesso").dataTable({
                    "processing": true,
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
                        "sProcessing": '<div class="box-loading-datatable"><div class="spinner pull-left"><img src="'+url +'assets/images/loader.gif"></div><div class="pull-left" style="margin-top: -10px;">Carregando...</div></div>'
                    },
                    "order": [[ 2, "asc" ]],
                    "ajax": {
                        url: url + "Processo/ajaxCarregaProgramasProcesso/<?php echo $proc[0]['idProcesso']; ?>",
                        type: "POST"
                    },
                });
            }
        });

        // Carrega Datatable de Módulos, validando se o mesmo ainda não foi carregado.
        $('#profile-tab_modulos').on('click', function(){
            if($('#tableModuloProcesso').find('tbody tr').length == 0){
                $("#tableModuloProcesso").dataTable({
                    "processing": true,
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
                        "sProcessing": '<div class="box-loading-datatable"><div class="spinner pull-left"><img src="'+url +'assets/images/loader.gif"></div><div class="pull-left" style="margin-top: -10px;">Carregando...</div></div>'
                    },
                    //"order": [[ 2, "asc" ]],
                    "ajax": {
                        url: url + "Processo/ajaxCarregaModulosProcesso/<?php echo $proc[0]['idProcesso']; ?>",
                        type: "POST"
                    },
                });
            }
        });

        /************************ CARREGA DATATABLE SNAPSHOT *****************************/
        // Carrega Datatable de Programas, validando se o mesmo ainda não foi carregado.
        $('#profile-tab_programas_snapshot').on('click', function(){
            if($('#tableProgProcesso_snapshot').find('tbody tr').length == 0){
                $("#tableProgProcesso_snapshot").dataTable({
                    "processing": true,
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
                        "sProcessing": '<div class="box-loading-datatable"><div class="spinner pull-left"><img src="'+url +'assets/images/loader.gif"></div><div class="pull-left" style="margin-top: -10px;">Carregando...</div></div>'
                    },
                    "order": [[ 2, "asc" ]],
                    "ajax": {
                        url: url + "Processo/ajaxCarregaProgramasProcessoSnapshot/<?php echo $proc[0]['idProcesso']; ?>",
                        type: "POST"
                    },
                });
            }
        });

        // Carrega Datatable de Módulos, validando se o mesmo ainda não foi carregado.
        $('#profile-tab_modulos_snapshot').on('click', function(){
            if($('#tableModuloProcesso_snapshot').find('tbody tr').length == 0){
                $("#tableModuloProcesso_snapshot").dataTable({
                    "processing": true,
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
                        "sProcessing": '<div class="box-loading-datatable"><div class="spinner pull-left"><img src="'+url +'assets/images/loader.gif"></div><div class="pull-left" style="margin-top: -10px;">Carregando...</div></div>'
                    },
                    //"order": [[ 2, "asc" ]],
                    "ajax": {
                        url: url + "Processo/ajaxCarregaModulosProcessoSnapshot/<?php echo $proc[0]['idProcesso']; ?>",
                        type: "POST"
                    },
                });
            }
        });
    });
</script>