<?php if ($gestorGrupo == "1") {
    $nome_usuario = "Nao Cadastrado";
} else {
    $nome_usuario = $gestorGrupo['nome_usuario'];
} ?>
<?php if ($gestorGrupo == "1") {
    $gestor = "Nao Cadastrado";
} else {
    $gestor = $gestorGrupo['gestor'];
} ?>


<?php $this->helper->scriptDataTable('tabGrupoUsr', 'Grupo/ajaxCarregaUsrGrupo/' . $idGrupo, 'POST', 'false'); ?>
<?php $this->helper->scriptDataTable('tabGrupoPrograma', 'Grupo/ajaxCarregaGrupoProgrma/' . $idGrupo, 'POST', 'false'); ?>

<!--<script type="text/javascript">

  $(document).ready(function(){
      $('#tabGrupoUsr').DataTable({
        ajax:url+"grupo/ajaxCarregaUsrGrupo/"+$("#idGrupo").val(),
      });
      $('#tabGrupoPrograma').DataTable({
        ajax:url+"grupo/ajaxCarregaGrupoProgrma/"+$("#idGrupo").val(),
      });
  });
</script>-->

<form method="POST" id="formulario" name="formulario">
    <input type="text" name="idGrupo" id="idGrupo" class="hide" value="<?php echo $idGrupo ?>">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo URL ?>/" onclick="loadingPagia()">Dashboard</a></li>            
            <li><a href="<?php echo URL ?>/Grupo"><font style="vertical-align: inherit;" onclick="loadingPagia()"><font style="vertical-align: inherit;">Grupos</font></font></a></li>
            <li class="active"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $grupo['descAbrev'] ?></font></font></li>
        </ol>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_title">
                        <h2>Informações Grupo</h2>  
                        <div class="clearfix"></div>
                        <br>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Grupo:</label>
                                <span><?php echo $grupo['idLegGrupo'] . " - " . $grupo['descAbrev'] ?></span>
                            </div>
                            <div class="col-md-4">
                                <label>Gestor do Grupo:</label>
                                <span><?php echo $nome_usuario; ?></span>
                            </div>
                            <div class="col-md-4">
                                <label>ID Gestor DataSul:</label>
                                <span><?php echo $gestor ?></span>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-2">
                            <a class="btn btn-danger" id="exportExcel">
                                <i class="fa fa-file-excel-o"></i> Exportar
                            </a>
                        </div>
                    </div>

                    <div class="x_panel">
                        <div class="x_content">
                            <div class="" role="tabpanel" data-example-id="togglable-tabs">
                                <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                    <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Usuários  <span class="badge"><?php echo count($carregaUsuario) ?></a>
                                    </li>
                                    <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Programa <span class="badge"><?php echo count($dadosGrupoProd) ?></a>
                                    </li>
                                </ul>
                                <div id="myTabContent" class="tab-content">
                                    <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">

                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <div class="x_panel">
                                                    <div class="x_title">
                                                        <h2>Usuários</h2>
                                                        <ul class="nav navbar-right panel_toolbox">
                                                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                                            </li>
                                                        </ul>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                    <div class="x_content">
                                                        <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer"><div class="row"><div class="col-sm-6"></div><div class="col-sm-6"></div></div><div class="row"><div class="col-sm-12">
                                                                    <table  class="table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info" style="width: 100%;" id="tabGrupoUsr">
                                                                        <thead>
                                                                            <tr role="row">
                                                                                <th>Nome</th>
                                                                                <th>Código DataSul</th>
                                                                                <th>Código Fluig</th>
                                                                                <th>Gestor Usuário</th>
                                                                                <th>Função</th>
                                                                                <th>Situação do usuário</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody> 


                                                                        </tbody>
                                                                    </table></div></div></div>                    
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                    <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <div class="x_panel">
                                                    <div class="x_title">
                                                        <h2>Programas</h2>
                                                        <ul class="nav navbar-right panel_toolbox">
                                                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                                            </li>
                                                        </ul>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                    <div class="x_content">
                                                        <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer"><div class="row"><div class="col-sm-6"></div><div class="col-sm-6"></div></div><div class="row"><div class="col-sm-12"><table  class=" table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info" style="width: 100%;" id="tabGrupoPrograma">
                                                                        <thead>
                                                                            <tr role="row">
                                                                                <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 5px;" aria-sort="ascending" aria-label="First name: activate to sort column descending">Codigo Programa</th>
                                                                                <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 10px;" aria-label="Last name: activate to sort column ascending">Descrição</th>
                                                                                <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 10px;" aria-label="Last name: activate to sort column ascending">Ajuda Programa</th>
                                                                                <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1"  aria-label="Last name: activate to sort column ascending">Rotina</th>
                                                                                <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1"  aria-label="Last name: activate to sort column ascending">Específico</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody> 


                                                                        </tbody>
                                                                    </table></div></div></div>
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
    <script type="text/javascript">
        $(document).ready(function(){
            $('#exportExcel').on('click', function(){
                location.href = url + "Grupo/ajaxGerraRelatorio/<?php echo $idGrupo; ?>";
            });

        });
    </script>