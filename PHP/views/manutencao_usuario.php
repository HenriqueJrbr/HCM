<div class="col-md-12 col-sm-12 col-xs-12">
    <ol class="breadcrumb">
        <li><a href="<?php echo URL ?>/" onclick="loadingPagia()">Dashboard</a></li>
        <li class="active">Manutenção</li>
        <li class="active">Usuário</li>
    </ol>
</div> 
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
                <?php if (isset($_SESSION['msg']['success'])): ?>
                    <div class="alert alert-success alert-dismissible">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <?= $_SESSION['msg']['success']; ?>
                    </div>
                    <?php unset($_SESSION['msg']['success']); ?>
                <?php endif; ?>
            </div>

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
                                    <th>Código</th>
                                    <th>Nome</th>
                                    <th>Função</th>
                                    <th>E-mail</th>
                                    <th>Gestor de Usuário</th>
                                    <th>Gestor de Grupo</th>
                                    <th>Situação</th>
                                    <th>Ação</th>

                                <tbody>

                                <?php //foreach ($usuario as  $value):?>
                                <!--<tr>

                 
                    <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php /*echo $value['cod_usuario'] */ ?></font></font></td>
                    <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php /*echo $value['nome_usuario'] */ ?></font></font></td>
                    <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php /*echo $value['descricao'] */ ?></font></font></td>
                    <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php /*echo $value['email'] */ ?></font></font></td>
                    <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php /*echo $value['gestor_usuario'] */ ?></font></font></td>
                    <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php /*echo $value['gestor_grupo'] */ ?></font></font></td>
                    <td >
                     <button type="button" class="btn btn-warning btn-xs" data-toggle="modal" onclick="location.href='<?php /*echo URL */ ?>/manutencao/editausuario/<?php /*echo $value['z_sga_usuarios_id'] */ ?>';loadingPagia()">Editar</button>
                    </td>
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

<?php $this->helper->scriptDataTable('table', 'Manutencao/ajaxDatatableUsuarios', 'POST', 'true') ;?>

