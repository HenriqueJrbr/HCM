<div class="col-md-12 col-sm-12 col-xs-12">
    <ol class="breadcrumb">
        <li><a href="<?php echo URL ?>/" onclick="loadingPagia()">Dashboard</a></li>            
        <li>Monitoramento</li>            
        <li>Grupo Totvs</li>            
    </ol>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Regras</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
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
                            <table class=" table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline"
                                   cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info"
                                   style="width: 100%;" id="tabGrupo">
                                <thead>
                                <tr role="row">
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="width: 5px;" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">ID Regra
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1"
                                        colspan="1" style="width: 10px;"
                                        aria-label="Last name: activate to sort column ascending">Empresa
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1"
                                        colspan="1" style="width: 10px;"
                                        aria-label="Last name: activate to sort column ascending">Estabelecimento
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1"
                                        colspan="1" style="width: 10px;"
                                        aria-label="Position: activate to sort column ascending">Departamento
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1"
                                        colspan="1" style="width: 10px;"
                                        aria-label="Position: activate to sort column ascending">Cargo
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1"
                                        colspan="1" style="width: 10px;"
                                        aria-label="Position: activate to sort column ascending">Função
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1"
                                        colspan="1" style="width: 10px;"
                                        aria-label="Position: activate to sort column ascending">Ação
                                    </th>
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

<?php $this->helper->scriptDataTable('tabGrupo', 'Grupo/ajaxCarregaRegra', 'POST', 'false'); ?>

