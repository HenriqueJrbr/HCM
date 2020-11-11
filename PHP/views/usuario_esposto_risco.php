<style type="text/css">
    #country-list {
        float: left;
        list-style: none;
        margin-top: 5px;
        padding: 0;
        width: 540px;
        position: absolute;
        z-index: 999;
    }

    #country-list li {
        padding: 10px;
        background: #f0f0f0;
        border-bottom: #bbb9b9 1px solid;
        border-radius: 5px;
    }

    #country-list li:hover {
        background: #ece3d2;
        cursor: pointer;
    }

    #search-box {
        padding: 10px;
        border: #a8d4b1 1px solid;
        border-radius: 4px;
    }

    #country-list2 {
        float: left;
        list-style: none;
        margin-top: 5px;
        padding: 0;
        width: 540px;
        position: absolute;
        z-index: 999;
    }

    #country-list2 li {
        padding: 10px;
        background: #f0f0f0;
        border-bottom: #bbb9b9 1px solid;
        border-radius: 5px;
    }

    #country-list2 li:hover {
        background: #ece3d2;
        cursor: pointer;
    }

    #search-box {
        padding: 10px;
        border: #a8d4b1 1px solid;
        border-radius: 4px;
    }
    #thAcao{
        pointer-events: none;
    }

</style>
<div class="col-md-12 col-sm-12 col-xs-12">
    <ol class="breadcrumb">
        <li><a href="<?php echo URL ?>/" onclick="loadingPagia()">Dashboard</a></li>            
        <li>Usuário vs Risco</li>            
    </ol>
</div>
<form method="POST">
    <div class="row">

        <br>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Usuário VS Riscos</h2>
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
                                           id="table"
                                    >
                                        <thead>
                                        <tr role="row">
                                            <th>Usuário</th>
                                            <th>Desc.Area</th>
                                            <th>Risco</th>
                                            <th>Desc.Risco</th>
                                            <th>Percentual de Acesso Composição</th>
                                            <th>Situação <br>do Usuário</th>
                                            <th>Situação</th>
                                            <th id="thAcao">Ação</th>
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

<?php $this->helper->scriptDataTable('table', 'ExposicaoRisco/ajaxDatatableRiscoUsuarios', 'POST'); ?>
