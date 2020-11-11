<div class="col-md-12 col-sm-12 col-xs-12">
    <ol class="breadcrumb">
        <li><a href="<?php echo URL ?>/" onclick="loadingPagia()">Dashboard</a></li>
        <li class="active">Matriz</li>
        <li class="active">Área</li>
    </ol>
</div> 

<br>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Cadastro Área</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                    <div class="row">
                        <div class="col-sm-6"></div>
                        <div class="col-sm-6"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <?php $this->helper->alertMessage(); ?>
                        </div>
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal">Cadastrar Área</button>
                        </div>
                        <div class="col-sm-12">
                            <table class="tabela table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline"
                                   cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info"
                                   style="width: 100%;">
                                <thead>
                                <tr role="row">
                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1"
                                        colspan="1" style="width: 10px;"
                                        aria-label="Last name: activate to sort column ascending">Descrição
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1"
                                        colspan="1" style="width: 10px;"
                                        aria-label="Last name: activate to sort column ascending">Responsável
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1"
                                        colspan="1" style="width: 10px;"
                                        aria-label="Last name: activate to sort column ascending">Ações
                                    </th>
                                </thead>
                                <tbody>

                                <?php foreach ($area as $value): ?>
                                    <tr>
                                        <td><?php echo $value['descricao'] ?></td>
                                        <td class="text-center"><?php echo $value['nome_usuario'] ?> - <span class="badge label-primary"><?php echo (($value['ativo'] == 1) ? 'Ativo' : 'Inativo' ) ;?><span></td>
                                        <td>
                                            <button <?= ($value['relacionamentos'] == 0) ? 'onclick="excluiAreaRisco(\'' . $value['idArea'] . '\')"' : ' disabled="true"'; ?>
                                                    class="btn btn-danger btn-xs">Excluir
                                            </button>
                                            <button type="button" class="btn btn-warning btn-xs" data-toggle="modal"
                                                    data-target="#myModal2"
                                                    onclick="editaArea('<?php echo $value['idArea'] ?>','<?php echo $value['descricao'] ?>','<?php echo $value['responsavel'] ?>')">
                                                Editar
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <form method="POST">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Cadastro Área</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Área</label>
                            <input type="text" name="area" class="form-control" required="required">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label>Responsável</label>
                            <select class="form-control" name="responsavel" id="responsavel" style="width: 100%" required="required">
                                <option></option>
                                <?php foreach ($respon as $value): ?>
                                    <option value="<?php echo $value['idUsuario'] ?>"><?php echo $value['nome_usuario'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Sair</button>
                    <input type="submit" name="salvarArea" class="btn btn-success" value="Salvar">
                </div>
            </div>

        </div>
    </form>
</div>


<!-- Modal -->
<div id="myModal2" class="modal fade" role="dialog">
    <form method="POST">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edita Area</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Area</label>
                            <input type="text" name="areaEdit" id="areaEdit" class="form-control">
                            <input type="text" name="idAreaEdit" id="idAreaEdit" class="form-control hide">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label>Responsável</label>
                            <select class="form-control" name="responsavelEdit" id="responsavelEdit" style="width: 100%"
                                    ;>

                                <?php foreach ($respon as $value): ?>
                                    <option value="<?php echo $value['idUsuario'] ?>"><?php echo $value['nome_usuario'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Sair</button>
                    <input type="submit" name="salvarAtualiza" class="btn btn-success" value="Salvar">
                </div>
            </div>

        </div>
    </form>
</div>

<script>

    $(document).ready(function(){
        $("#responsavel").select2();
         $("#responsavelEdit").select2();
    });
    // Exclui uma Area quando essa não tiver relacionamento
    function excluiAreaRisco(idAreaRisco) {
        $.ajax({
            type: 'POST',
            url: url + 'Matriz/ajaxExcluiAreaRisco',
            data: {
                idAreaRisco: idAreaRisco
            },
            success: function (data) {
                window.location.href = window.location.href;
            }
        });
    }
</script>