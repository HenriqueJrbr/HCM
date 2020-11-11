<script type="text/javascript">
    $(document).ready(function () {

     $("#idTotvs").select2();
    });

    function editarConfigUsuario(id) {
        $.ajax({
            type: "POST",
            url: url + "ConfiguracaoSga/ajaxDadosDoLogin",
            data: '$id=' + id,
            beforeSend: function () {
            },
            success: function (data) {

                var dados = JSON.parse(data);
                $("#idUsuarioEditar").val(id);
                $("#usuarioEditar").val(dados.login);
                $("#nomeUsuarioEditar").val(dados.nomeUsuario);
                $("#emailUsuarioEditar").val(dados.email);
                $("#validadeAcessoEditar").val(dados.validade);
                $("#idTotvsEditar").val(dados.idTotovs);
                $("#grupoEditar").val(dados.idGrupo);
                $("#idTotvsEditar").select2();
                $("#myModal2").modal('show');


            }
        });
    }

    function editarSenha(id) {
        $("#myModal3").modal('show');
        $("#idSenhaEditar").val(id);
    }

</script>
<div class="col-md-12 col-sm-12 col-xs-12">
    <ol class="breadcrumb">
        <li><a href="<?php echo URL ?>/" onclick="loadingPagia()">Dashboard</a></li>          
        <li class="active">Configurações Sga</li>
        <li class="active">Manutenção</li>
        <li class="active">Usuários</li>
    </ol>
</div>
<button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal">Cadastrar Usuário
</button>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Usuários SGA</h2>
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
                            <table class="tabela table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline"
                                   cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info"
                                   style="width: 100%;">
                                <thead>
                                <tr role="row">
                                    <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                        rowspan="1" colspan="1" style="width: 5px;" aria-sort="ascending"
                                        aria-label="First name: activate to sort column descending">Usuário
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1"
                                        colspan="1" style="width: 10px;"
                                        aria-label="Last name: activate to sort column ascending">Nome Usuário
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1"
                                        colspan="1" style="width: 10px;"
                                        aria-label="Last name: activate to sort column ascending">E-mail
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1"
                                        colspan="1" style="width: 10px;"
                                        aria-label="Position: activate to sort column ascending">Validade Acesso
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1"
                                        colspan="1" style="width: 10px;"
                                        aria-label="Position: activate to sort column ascending">Situação
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1"
                                        colspan="1" style="width: 10px;"
                                        aria-label="Position: activate to sort column ascending">Ações
                                    </th>
                                </thead>
                                <tbody>

                                <?php foreach ($login as $value): ?>
                                    <tr>
                                        <td><font style="vertical-align: inherit;"><?php echo $value['login'] ?></td>
                                        <td><?php echo $value['nomeUsuario'] ?></td>
                                        <td><?php echo $value['email'] ?></td>
                                        <td><?php echo $value['validade'] ?></td>
                                        <td><?php echo(($value['ativo'] == 1) ? 'Ativo' : 'Inativo'); ?></td>
                                        <td width="20%">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary btn-sm"
                                                        onclick="editarConfigUsuario('<?php echo $value['idLogin'] ?>')">
                                                    Editar
                                                </button>
                                                <button type="button" class="btn btn-warning btn-sm"
                                                        onclick="editarSenha('<?php echo $value['idLogin'] ?>')">Alterar
                                                    Senha
                                                </button>
                                            </div>
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
    <form method="POST" id="editarUsuario">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Editar Usuário</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Usuário</label>
                            <input type="text" name="usuarioEditar" class="form-control" id="usuarioEditar"
                                   readonly="readonly">
                            <input type="text" name="idUsuarioEditar" class="form-control hide" id="idUsuarioEditar"
                                   readonly="readonly">
                        </div>
                        <div class="col-md-4">
                            <label>Nome Usuário</label>
                            <input type="text" name="nomeUsuarioEditar" class="form-control" id="nomeUsuarioEditar"
                                   required="required" autocomplete="off">

                        </div>
                        <div class="col-md-4">
                            <label for="emailUsuario">Email</label>
                            <input type="email" name="emailUsuarioEditar" class="form-control" id="emailUsuarioEditar"
                                   required="required" autocomplete="off">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label>Validade Acesso</label>
                            <input type="date" name="validadeAcessoEditar" id="validadeAcessoEditar"
                                   class="form-control" required="required">
                        </div>
                        <div class="col-md-4">
                            <label>Grupo de Acesso</label>
                            <select name="grupoEditar" id="grupoEditar" class="form-control">
                                <option></option>
                                <?php foreach ($grupo as $value): ?>
                                    <option value="<?php echo $value['idGrupo'] ?>"><?php echo $value['descricao'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>ID Totovs</label>
                            <select name="idTotvsEditar" id="idTotvsEditar" class="form-control" style="width: 100%">
                                <option></option>
                                <?php foreach ($usr as $value): ?>
                                    <option value="<?php echo $value['z_sga_usuarios_id'] ?>"><?php echo $value['nome_usuario'] . " - " . $value['cod_usuario'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Sair</button>
                    <input type="submit" name="editarSalvar" class="btn btn-success" value="Salvar">
                </div>
            </div>
        </div>
    </form>
</div>


<div id="myModal3" class="modal fade" role="dialog">
    <form method="POST">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Editar Senha</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Senha</label>
                            <input type="password" name="senhaEditar" id="senhaEditar" class="form-control"
                                   required="required" autocomplete="off">
                            <input type="text" name="idSenhaEditar" id="idSenhaEditar" class="form-control hide"
                                   required="required">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Sair</button>
                    <input type="submit" name="salvarSenha" class="btn btn-success" value="Salvar">
                </div>
            </div>
        </div>
    </form>
</div>


<div id="myModal" class="modal fade" role="dialog">
    <form method="POST" id="addUsuario">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Cadastro de Usuário</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Usuário</label>
                            <input type="text" name="usuario" class="form-control" id="usuario" required="required"
                                   autocomplete="off">
                            <span id="validaUsr" style="color:red"></span>
                        </div>
                        <div class="col-md-4">
                            <label>Nome Usuário</label>
                            <input type="text" name="nomeUsuario" class="form-control" id="nomeUsuario"
                                   required="required" autocomplete="off">

                        </div>
                        <div class="col-md-4">
                            <label for="emailUsuario">Email</label>
                            <input type="email" name="emailUsuario" class="form-control" id="emailUsuario"
                                   required="required" autocomplete="off">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label>Senha</label>
                            <input type="password" name="senha" id="senha" class="form-control" required="required">
                        </div>
                        <div class="col-md-4">
                            <label>Validade Acesso</label>
                            <input type="date" name="validadeAcesso" id="validadeAcesso" class="form-control"
                                   required="required">
                        </div>
                        <div class="col-md-4">
                            <label>Grupo de Acesso</label>
                            <select name="grupo" id="grupo" class="form-control" required="required">
                                <option></option>
                                <?php foreach ($grupo as $value): ?>
                                    <option value="<?php echo $value['idGrupo'] ?>"><?php echo $value['descricao'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label>ID Totvs</label>
                            <select name="idTotvs" id="idTotvs" class="form-control" style="width: 100%" required="required">
                                <option></option>
                                <?php foreach ($usr as $value): ?>
                                    <option value="<?php echo $value['z_sga_usuarios_id'] ?>"><?php echo $value['nome_usuario'] . " - " . $value['cod_usuario'] ?></option>
                                <?php endforeach ?>
                            </select>
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

