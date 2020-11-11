<div class="col-md-12 col-sm-12 col-xs-12">
    <ol class="breadcrumb">
        <li><a href="<?php echo URL ?>/" onclick="loadingPagia()">Dashboard</a></li>
        <li class="active">Manutenção</li>
        <li class="active">Programa</li>
    </ol>
</div>   
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Programa</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <?php if(isset($_SESSION['msg']['success'])): ?>
                <div class="alert alert-success alert-dismissible">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <?= $_SESSION['msg']['success']; ?>
                </div>
                <?php unset($_SESSION['msg']['success']); ?>
            <?php endif; ?>
            <div class="x_content">
                <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                    <div class="row">
                        <div class="col-sm-6"></div>
                        <div class="col-sm-6"></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline"
                                   cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info"
                                   style="width: 100%;" id="table">
                                <thead>
                                <tr role="row">
                                    <th>Cód.</th>
                                    <th>Descrição</th>
                                    <th>Especifico</th>
                                    <th>Ajuda Programa</th>
                                    <th>Código Rotina</th>
                                    <th>Ação</th>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->helper->scriptDataTable('table', 'Manutencao/ajaxDatatablePrograma', 'POST'); ?>
