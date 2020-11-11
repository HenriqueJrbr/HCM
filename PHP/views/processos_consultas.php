<div class="col-md-12">
    <ol class="breadcrumb">
        <li>
            <a href="<?php echo URL ?>/">
                <font style="vertical-align: inherit;" onclick="loadingPagia()">
                    <font style="vertical-align: inherit;">Dashboard</font>
                </font>
            </a>
        </li>
        <li class="active">
            <font style="vertical-align: inherit;">
                <font style="vertical-align: inherit;">Consulta de Processos</font>
            </font>
        </li>
    </ol>
</div>

<form method="POST">
    <div class="row">

        <br>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Consulta de Processos</h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>

                    <div class="x_content">
                        <div id="datatable-responsive_wrapper"
                             class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                            <div class="row">
                                <div class="col-sm-6"></div>
                                <div class="col-sm-6"></div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline"
                                           cellspacing="0"
                                           width="100%"
                                           role="grid"
                                           aria-describedby="datatable-responsive_info"
                                           style="width: 100%;"
                                           id="table">
                                        <thead>
                                        <tr role="row">
                                            <th>ID</th>
                                            <th>Área</th>
                                            <th>Grupo Processo</th>
                                            <th>Descrição</th>
                                            <th>Núm. Usuários</th>
                                            <th>Núm. Grupos</th>
                                            <th>Núm. Progs.</th>
                                            <th>Núm. Módulos.</th>
                                            <th>Ação</th>
                                        </thead>
                                        <tbody>
                                        <?php //foreach ($risco as  $value):?>
                                        <!--<tr  onclick="window.open('<?php /*echo URL */ ?>/usuario/dados_usuario/<?php /*echo $value['idUsuario'] */ ?>')" >
                                            <td><font style="vertical-align: inherit;text-transform: uppercase;"><font style="vertical-align: inherit;"><?php /*echo $value['nome_usuario']*/ ?></font></font></td>
                                            <td><font style="vertical-align: inherit; text-transform: uppercase;"><font style="vertical-align: inherit;"><?php /*echo utf8_decode($value['descArea'])*/ ?></font></font></td>
                                            <td><font style="vertical-align: inherit; text-transform: uppercase;"><font style="vertical-align: inherit;"><?php /*echo $value['codrisco'] */ ?></font></font></td>
                                            <td><font style="vertical-align: inherit; text-transform: uppercase;"><font style="vertical-align: inherit;"><?php /*echo $value['descRisco'] */ ?></font></font></td>
                                            <td><font style="vertical-align: inherit; text-transform: uppercase;"><font style="vertical-align: inherit;"> <?php /*echo round($value['CombinacoesDoRisco']); */ ?>%</font></font></td>
                                          </tr>-->
                                        <?php //endforeach; ?>
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
</form>

<?php $this->helper->scriptDataTable('table', 'Processo/ajaxCarregaProcessos', 'POST', 'false'); ?>