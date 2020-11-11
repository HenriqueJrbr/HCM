<div class="col-md-12 col-sm-12 col-xs-12">
    <ol class="breadcrumb">
        <li><a href="<?php echo URL ?>/" onclick="loadingPagia()">Dashboard</a></li>
        <li class="active">Matriz</li>
        <li class="active">Grau de Risco</li>
    </ol>
</div> 

<br>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Cadastro Grau</h2>
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
                            <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal">Cadastrar Grau</button>
                        </div>
                        <div class="col-sm-12 col-md-12">
                            <table class="tabela table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline"
                                   cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info"
                                   style="width: 100%;">
                                <thead>
                                <tr role="row">
                                    <th width="10%">Descrição</th>
                                    <th width="10%">Configuração de cor</th>
                                    <th width="10%">Ação</th>
                                </thead>
                                <tbody>
                                <?php foreach ($grau as $value): ?>
                                    <tr>
                                        <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $value['descricao'] ?></font></font></td>
                                        <!-- <td style="background: <?php echo $value['background'] ?>;color: black">Cor de Fundo</td>-->
                                        <td style="color: <?php echo $value['texto'] ?>; background: <?php echo $value['background'] ?>"><?php echo $value['descricao'] ?></td>
                                        <td>
                                            <button <?= ($value['relacionamentos'] == 0) ? 'onclick="excluiGrauRisco(\''.$value['idGrauRisco'].'\')"' : ' disabled="true"'; ?>
                                                    class="btn btn-danger btn-xs">Excluir
                                            </button>

                                            <button
                                                    type="button" class="btn btn-warning btn-xs" data-toggle="modal"
                                                    data-target="#myModal2"
                                                    onclick="editaGrau('<?php echo $value['idGrauRisco'] ?>','<?php echo $value['descricao'] ?>','<?php echo $value['background'] ?>','<?php echo $value['texto'] ?>')">
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


<div id="myModal2" class="modal fade" role="dialog">
    <form method="POST">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Editar Cor </h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Descrição</label>
                            <input type="text" name="descricaoModal" id="descricaoModal" class="form-control">
                            <input type="text" name="idModal" id="idModal" class="form-control hide">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <label>Cor de Fundo</label>
                            <input type="color" class="form-control" name="backgroundModal" id="backgroundModal"
                                   readonly="readonly">
                        </div>
                        <div class="col-md-2">
                            <label>Cor do Texto</label>
                            <input type="color" value="#e01ab5" class="form-control" name="textoModal" id="textoModal"
                                   readonly="readonly">
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Sair</button>
                    <input type="submit" name="salvarEdit" class="btn btn-success" value="Salvar">
                </div>
            </div>

        </div>
    </form>
</div>


<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <form method="POST">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Cadastro de Grau</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Descrição</label>
                            <input type="text" name="descricao" class="form-control" required="required">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label>background</label>
                            <input type="color" class="form-control" name="background" readonly="readonly" required="required">
                        </div>
                        <div class="col-md-4">
                            <label>Texto</label>
                            <input type="color" class="form-control" name="texto" readonly="readonly" required="required">
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Sair</button>
                    <input type="submit" name="salvar" class="btn btn-success" value="Salvar">
                </div>
            </div>

        </div>
    </form>
</div>

<script>
    // Exclui um Grau quando esse não tiver relacionamento
    function excluiGrauRisco(idGrauRisco){
        $.ajax({
            type: 'POST',
            url: url + 'Matriz/ajaxExcluiGrauRisco',
            data: {
                idGrauRisco: idGrauRisco
            },
            success: function(data){
                console.log(data);
                window.location.href = window.location.href;
            }
        });
    }
</script>