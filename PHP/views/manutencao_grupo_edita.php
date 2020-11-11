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
<style>
    #usuarios,
    #usuariosAdd,
    #programas,
    #programasAdd{
        min-height: 300px;
        background: #f2f2f2;
    }
</style>
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

    #search-box2 {
        padding: 10px;
        border: #a8d4b1 1px solid;
        border-radius: 4px;
    }

</style>

<script type="text/javascript">

    $(document).ready(function () {
        $("#usr").keyup(function () {

            if ($(this).val().length == 0) {
                $("#idUsr").val("");
            }
            var idUsr = $(this).val();
            $.ajax({
                type: "POST",
                url: url + "Grupo/ajaxCarregaUsr",
                data: 'idUsr=' + idUsr,
                beforeSend: function () {
                    $("#usr").css("background", "#FFF url(LoaderIcon.gif) no-repeat 165px");
                },
                success: function (data) {
                    $("#suggesstion-box").show();
                    $("#country-list").html(data);

                }
            });
        });
        $("#prog").keyup(function () {

            if ($(this).val().length == 0) {
                $("#idProg").val("");
            }


            var idProg = $(this).val();

            console.log("prog " + idProg);
            $.ajax({
                type: "POST",
                url: url + "Grupo/ajaxCarregaProg",
                data: 'idProg=' + idProg,
                beforeSend: function () {
                    $("#prog").css("background", "#FFF url(LoaderIcon.gif) no-repeat 165px");
                },
                success: function (data) {
                    $("#suggesstion-box2").show();
                    $("#country-list2").html(data);

                }
            });
        });
    });

    function carregaDadosUsr(nomeUsr, idUsuario, codGestor,cod_usuario) {
        $("#usr").val(nomeUsr);
        $("#idUsr2").val(idUsuario);
        $("#cod_usuario").val(cod_usuario);
        $("#suggesstion-box").hide();
    }

    function carregaDadosProg(programa, idPrograma) {
        console.log(programa + ' ' + idPrograma)
        $("#prog").val(programa);
        $("#idProg").val(idPrograma);
        $("#suggesstion-box2").hide();
    }


</script>
<div class="col-md-12 col-sm-12 col-xs-12">
    <ol class="breadcrumb">
        <li><a href="<?php echo URL ?>/" onclick="loadingPagia()">Dashboard</a></li>
        <li><a href="<?php echo URL ?>/Manutencao/grupo" onclick="loadingPagia()">Grupos</a></li>
        <li class="active"><font style="vertical-align: inherit;"><font
                        style="vertical-align: inherit;"><?php echo $grupo['descAbrev'] ?></font></font></li>
    </ol>
</div>

        <div class="x_panel">               
            <div class="x_content">
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
                    <div class="col-md-12">
                        <?php $this->helper->alertMessage(); ?>
                    </div>
                </div>
                <div class="" role="tabpanel" data-example-id="togglable-tabs">
                    <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                        <?php if ($grupo['idLegGrupo'] != '*'){ ?>
                        <li role="presentation" class="<?php echo ($grupo['idLegGrupo'] == '*') ? '' : 'active'  ?>"><a href="#tab_content1" id="home-tab" role="tab"
                                                                  data-toggle="tab" aria-expanded="true">Usuários <span
                                        class="badge"><?php echo count($totalUser) ?></a>
                        <?php } ?>
                        </li>
                        <li role="presentation" class="<?php echo ($grupo['idLegGrupo'] == '*') ? 'active' : ''  ?>"><a href="#tab_content2" role="tab" id="profile-tab"
                                                            data-toggle="tab" aria-expanded="false">Programa <span
                                        class="badge"><?php echo count($dadosGrupoProd) ?></a>
                        </li>
                    </ul>
                    <div id="myTabContent" class="tab-content">
                        <?php if ($grupo['idLegGrupo'] != '*'){ ?>
                        <div role="tabpanel" class="tab-pane fade <?php echo ($grupo['idLegGrupo'] == '*') ? '' : 'active in'  ?>" id="tab_content1" aria-labelledby="home-tab">
                            <form action="<?= URL; ?>/Manutencao/apagaUsuariosGrupos" id="formApagaUsuarios" method="post">
                                <input type="hidden" name="idGrupo" value="<?= $grupo['idGrupo']; ?>">
                                <input type="hidden" name="descAbrev" value="<?= $grupo['descAbrev']; ?>">
                                <input type="hidden" name="idLegGrupo" value="<?= $grupo['idLegGrupo']; ?>">
                                <input type="hidden" name="cod_usuario" value="<?= $_SESSION['codUsuario']; ?>">                              
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
                                                <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                                    <div class="row">
                                                        <div class="col-sm-6"></div>
                                                        <div class="col-sm-6"></div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <table class="table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline"
                                                                   cellspacing="0" width="100%" role="grid"
                                                                   aria-describedby="datatable-responsive_info"
                                                                   style="width: 100%;" id="datatableUser">
                                                                <thead>
                                                                <tr role="row">
                                                                    <th><input type="checkbox" id="checkAllUser"></th>
                                                                    <th>Nome</th>
                                                                    <th>Código DataSul</th>
                                                                    <th>Código Fluig</th>
                                                                    <th>Gestor Usuário</th>
                                                                    <th>Função</th>
                                                                    <th>Situação do Usuário</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>

                                                                <?php //foreach ($carregaUsuario as $value): ?>
                                                                <!--<tr>
                                                                    <td><input type="checkbox" name="idUsr[]" value="<?php /*echo $value['z_sga_grupos_id']  */?>"></td>
                                                                    <td><?php /*echo $value['nome_usuario'] */?></td>
                                                                    <td><?php /*echo $value['cod_usuario']  */?></td>
                                                                    <td><?php /*echo $value['idUsrFluig'] */?></td>
                                                                    <?php /*if($value['cod_gestor'] != ""){$gestor = $value['cod_gestor']; }else{$gestor = "Não Cadastrado";}  */?>
                                                                    <td><?php /*echo $gestor  */?></td>
                                                                    <td><?php /*echo $value['cod_funcao'] */?></td>
                                                                  </tr>-->
                                                                <?php //endforeach; ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-1">
                                                        <button type="button" name="excluirUsr" id="btnApagaUser" class="btn btn-danger">Excluir</button>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal">Adicionar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <?php } ?>
                        <div role="tabpanel" class="tab-pane fade <?php echo ($grupo['idLegGrupo'] != '*') ? '' : 'active in'  ?>" id="tab_content2" aria-labelledby="profile-tab">
                            <form action="<?= URL; ?>/Manutencao/apagaProgramasGrupos" id="formApagaProgramas" method="post">
                                <input type="hidden" name="idGrupo" value="<?= $grupo['idGrupo']; ?>">
                                <input type="hidden" name="descAbrev" value="<?= $grupo['descAbrev']; ?>">
                                <input type="hidden" name="idLegGrupo" value="<?= $grupo['idLegGrupo']; ?>">
                                <input type="hidden" name="cod_usuario" value="<?= $_SESSION['codUsuario']; ?>">
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
                                                <div id="datatable-responsive_wrapper"
                                                     class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                                    <div class="row">
                                                        <div class="col-sm-6"></div>
                                                        <div class="col-sm-6"></div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <table class="table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline"
                                                                   cellspacing="0" width="100%" role="grid"
                                                                   aria-describedby="datatable-responsive_info"
                                                                   style="width: 100%;" id="datatableProg">
                                                                <thead>
                                                                <tr role="row">
                                                                    <th class="sorting_asc" tabindex="0"
                                                                        aria-controls="datatable-responsive" rowspan="1"
                                                                        colspan="1" style="width: 5px;"
                                                                        aria-sort="ascending"
                                                                        aria-label="First name: activate to sort column descending">
                                                                    <input type="checkbox" id="checkAllProg">
                                                                    </th>
                                                                    <th class="sorting_asc" tabindex="0"
                                                                        aria-controls="datatable-responsive" rowspan="1"
                                                                        colspan="1" style="width: 5px;"
                                                                        aria-sort="ascending"
                                                                        aria-label="First name: activate to sort column descending">
                                                                        Codigo Programa
                                                                    </th>
                                                                    <th class="sorting" tabindex="0"
                                                                        aria-controls="datatable-responsive" rowspan="1"
                                                                        colspan="1" style="width: 10px;"
                                                                        aria-label="Last name: activate to sort column ascending">
                                                                        Descrição
                                                                    </th>
                                                                    <th class="sorting" tabindex="0"
                                                                        aria-controls="datatable-responsive" rowspan="1"
                                                                        colspan="1" 
                                                                        aria-label="Last name: activate to sort column ascending">
                                                                        Rotina
                                                                    </th>
                                                                    <th class="sorting" tabindex="0"
                                                                        aria-controls="datatable-responsive" rowspan="1"
                                                                        colspan="1" style="width: 10px;"
                                                                        aria-label="Last name: activate to sort column ascending">
                                                                        Específico
                                                                    </th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <?php //foreach ($dadosGrupoProd as $value): ?>
                                                                    <!--<tr>
                                                                        <td><input type="checkbox" name="idPrograma[]" value="<?php /*echo $value['z_sga_grupo_programa_id'] */?>"></td>
                                                                        <td><?php /*echo $value['cod_programa'] */?></td>
                                                                        <td><?php /*echo $value['descricao_programa'] */?></td>

                                                                    </tr>-->
                                                                <?php //endforeach; ?>

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-1">
                                                        <button type="button" name="excluirProd" id="btnApagaProg" class="btn btn-danger">Excluir</button>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal2">Adicionar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->

    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <form action="<?= URL; ?>/Manutencao/addUsuarioGrupo" id="formAddUsuarios" method="post">
                <input type="hidden" name="idGrupo" value="<?= $grupo['idGrupo']; ?>">
                <input type="hidden" name="descAbrev" value="<?= $grupo['descAbrev']; ?>">
                <input type="text" name="codGrupo" class="form-control hide" value="<?php echo $grupo['idLegGrupo']?>">
                <input type="hidden" name="cod_usuario" value="<?= $_SESSION['codUsuario']; ?>">
                
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Adicionar Usuário</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12" style="display:none" id="erroUsuario">
                                <div class="alert alert-error alert-dismissible">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <p>Favor selecionar ao menos um usuário!</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label>Buscar usuários</label>
                                <input type="text" id="buscaUsuarios" class="form-control" value="">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-5">
                                <label>Usuários:</label>
                                <select class="form-control" id="usuarios" multiple>
                                    <?php foreach($usuarios as $val): ?>
                                    <option value="<?php echo $val['idUsuario'].'-'.$val['cod_usuario']; ?>"><?php echo $val['nome_usuario']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <div style="float: none; margin: 120px 0 0 30%;">
                                    <button type="button" class="btn btn-success" id="btnAddUsuarios"> >> </button><br>
                                    <button type="button" class="btn btn-danger" id="btnRemoveUsuarios"> << </button>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <label>Usuários à adicionar:</label>
                                <select class="form-control" id="usuariosAdd" name="usuarios[]" multiple></select>
                            </div>
                        </div>                                                
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Sair</button>
                        <input type="button" name="addUsr" id="btnSalvarUsuario" class="btn btn-success" value="Salvar">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Modal -->
    <div id="myModal2" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <form action="<?= URL; ?>/Manutencao/addProgramaGrupo" id="formAddProg" method="post">
                <input type="hidden" name="idGrupo" value="<?= $grupo['idGrupo'] ?>">
                <input type="hidden" name="idLegGrupo" value="<?= $grupo['idLegGrupo'] ?>">
                <input type="hidden" name="descAbrev" value="<?= $grupo['descAbrev']; ?>">
                <input type="hidden" name="cod_usuario" value="<?= $_SESSION['codUsuario']; ?>">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Adicionar Programa</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
<!--                            <div class="col-md-4">
                                <label>Programa</label>
                                <input type="text" name="prog" id="prog" class="form-control" autocomplete="off">
                                <input type="text" name="idProg" id="idProg" class="form-control hide ">
                                <div id="suggesstion-box2">
                                    <ul id="country-list2"></ul>
                                </div>
                            </div>-->

                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12" style="display:none" id="erroPrograma">
                                        <div class="alert alert-error alert-dismissible">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <p>Favor selecionar ao menos um programa!</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Buscar programas</label>
                                        <input type="text" id="buscaProgramas" class="form-control" value="">
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-5">
                                        <label>Programas:</label>
                                        <select class="form-control" id="programas" multiple>
                                            <?php foreach($programas as $val): ?>
                                            <option value="<?php //echo $val['idPrograma']; ?>"><?php //echo $val['descricao_programa']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <div style="float: none; margin: 120px 0 0 30%;">
                                            <button type="button" class="btn btn-success" id="btnAddProgramas"> >> </button><br>
                                            <button type="button" class="btn btn-danger" id="btnRemoveProgramas"> << </button>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <label>Programas à adicionar:</label>
                                        <select class="form-control" id="programasAdd" name="programas[]" multiple></select>
                                    </div>
                                </div>                                                
                            </div>


                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Sair</button>
                        <input type="button" name="addPrograma" id="btnSalvarProgramas" class="btn btn-success" value="Salvar">
                    </div>
                </div>
            </form>
        </div>
    </div>


<script>
    // Manipula funcionalidades de usuários
    // Busca usuários
    $('#buscaUsuarios').on('keyup', function(){
        $('#txtUsuarioErro').css('display', 'none');
        var idsAdicionados = [];
        
        $("#usuariosAdd option").each(function() {
            var users = $(this).val().split('|');
            idsAdicionados.push(users[0]);            
        });
                        
        $.ajax({
            type: 'POST',
            url: url+'Manutencao/ajaxBuscaUsuarios',
            data: {
                nome: $('#buscaUsuarios').val(),
                eliminar: idsAdicionados,
                idGrupo: $('input[name="idGrupo"]').val()
            },
            success: function (res) {                
                $('#usuarios').html('');
                $('#usuarios').append(res);
            }
        });
    });
    
    // Relaciona usuários com o grupo
    $(document).ready(function () {        
        $('#btnAddUsuarios').click(function(){            
            $("#usuarios option:selected").each(function() {  
                $(this).remove();
                $("#usuariosAdd").append('<option value="'+$(this).val()+'">'+$(this).text()+"</option>");
            });
        });
        
        $('#btnRemoveUsuarios').click(function(){
            $("#usuariosAdd option:selected" ).each(function() {  
                $(this).remove();                
                $("#usuarios").append('<option value="'+$(this).val()+'">'+$(this).text()+"</option>");
            });
        });        
        
        $('#btnSalvarUsuario').on('click', function(){
            if($("#usuariosAdd option").length == 0){
                $('#erroUsuario').css('display', 'block');
                return false;
            }
                        
            var i = 0;            
            $('#usuariosAdd option').each(function(){
                //$(this).remove();                               
                console.log($(this).val());
                var users = $(this).val().split('|');

                $("#formAddUsuarios").append('<input type="hidden" name="usuarios['+i+'][cod_usuario]" value="'+users[1]+'">');
                $("#formAddUsuarios").append('<input type="hidden" name="usuarios['+i+'][cod_grp_usuar]" value="'+$('input[name="idLegGrupo"]').val()+'">');
                $("#formAddUsuarios").append('<input type="hidden" name="usuarios['+i+'][acao]" value="INC">');
                $("#formAddUsuarios").append('<input type="hidden" name="usuarios['+i+'][idUsuario]" value="'+users[0]+'">');
                i++;                                
            });
                        
            $("#formAddUsuarios").submit();
        });   
    });
    // Fim usuários
        
    // Manipula funcionalidades de programas
    // Busca programas
    $('#buscaProgramas').on('keyup', function(){
        $('#txtProgramaErro').css('display', 'none');
        var idsAdicionados = [];
        
        $("#programasAdd option").each(function() {
            var progs = $(this).val().split('|');
            idsAdicionados.push(progs[0]);
        });
                        
        $.ajax({
            type: 'POST',
            url: url+'Manutencao/ajaxBuscaProgramas',
            data: {
                nome: $('#buscaProgramas').val(),
                eliminar: idsAdicionados,
                idGrupo: $('input[name="idGrupo"]').val()
            },
            success: function (res) {                
                $('#programas').html('');
                $('#programas').append(res);
            }
        });
    });
    
    // Relaciona programas com o grupo
    $(document).ready(function () {        
        $('#btnAddProgramas').click(function(){            
            $("#programas option:selected").each(function() {  
                $(this).remove();
                $("#programasAdd").append('<option value="'+$(this).val()+'">'+$(this).text()+"</option>");
            });
        });
        
        $('#btnRemoveProgramas').click(function(){
            $("#programasAdd option:selected" ).each(function() {  
                $(this).remove();                
                $("#programas").append('<option value="'+$(this).val()+'">'+$(this).text()+"</option>");
            });
        });        
        
        $('#btnSalvarProgramas').on('click', function(){
            if($("#programasAdd option").length == 0){
                $('#erroPrograma').css('display', 'block');
                return false;
            }
            $('#myModal2').modal('hide');
            $('#load').css('display', 'block');
            var i = 0;
            $("#programasAdd option" ).each(function() {                
                $(this).remove();
                var progs = $(this).val().split('|');
                $("#formAddProg").append('<input type="hidden" name="programas['+i+'][cod_prog_dtsul]" value="'+progs[1]+'">');
                $("#formAddProg").append('<input type="hidden" name="programas['+i+'][cod_grp_usuar]" value="'+$('input[name="idLegGrupo"]').val()+'">');
                $("#formAddProg").append('<input type="hidden" name="programas['+i+'][acao]" value="INC">');
                $("#formAddProg").append('<input type="hidden" name="programas['+i+'][idPrograma]" value="'+progs[0]+'">');                                
                i++;
            });
                        
            $("#formAddProg").submit();
        });   
    });
    // Fim programas
    
    
    

    // Carrega o datatable usuarios via ajax
    $(document).ready(function(){
        $('#datatableUser').DataTable({
            ajax:url+"Manutencao/ajaxCarregaUsrGrupo/<?= $grupo['idGrupo'] ?>",
        });
    });

    // Carrega o datatable programas via ajax
    $(document).ready(function(){
        $('#datatableProg').DataTable({
            ajax:url+"Manutencao/ajaxCarregaGrupoProgrma/<?= $grupo['idGrupo'] ?>",
        });
    });


    // Marca ou desmarca checkbox de usuarios
    $('#checkAllUser').on('click', function(){
        $('.checkUser').not(this).prop('checked', this.checked);
    });

    // Marca ou desmarca checkbox de programas
    $('#checkAllProg').on('click', function(){
        $('.checkProg').not(this).prop('checked', this.checked);
    });

    // Apaga usuarios selecionados no datatable
    $('#btnApagaUser').on('click', function(){
        var usuariosDel = [];
        var i = 0;
        
        $('.checkUser').each(function(){
            if($(this).prop('checked')){
                usuariosDel.push($(this).attr('id'));
                
                var users = $(this).val().split('|');
                $("#formApagaUsuarios").append('<input type="hidden" name="usuarios['+i+'][cod_usuario]" value="'+users[1]+'">');
                $("#formApagaUsuarios").append('<input type="hidden" name="usuarios['+i+'][cod_grp_usuar]" value="'+$('input[name="idLegGrupo"]').val()+'">');
                $("#formApagaUsuarios").append('<input type="hidden" name="usuarios['+i+'][acao]" value="ESC">');
                $("#formApagaUsuarios").append('<input type="hidden" name="usuarios['+i+'][idUsuario]" value="'+users[0]+'">');
                i++;
            }
        });

        // Valida se foi selecionado ao menos um usuário
        if(usuariosDel.length == 0){
            return false;
        }
        
        $('#formApagaUsuarios').submit();
    });

    // Apaga grupos selecionados no datatable
    $('#btnApagaProg').on('click', function(){
        var gruposDel = [];
        var i = 0;
        $('.checkProg').each(function(){            
            if($(this).prop('checked')) {
                gruposDel.push($(this).val());                
                var progs = $(this).val().split('|');
                $("#formApagaProgramas").append('<input type="hidden" name="programas['+i+'][cod_prog_dtsul]" value="'+progs[1]+'">');
                $("#formApagaProgramas").append('<input type="hidden" name="programas['+i+'][cod_grp_usuar]" value="'+$('input[name="idLegGrupo"]').val()+'">');
                $("#formApagaProgramas").append('<input type="hidden" name="programas['+i+'][acao]" value="ESC">');
                $("#formApagaProgramas").append('<input type="hidden" name="programas['+i+'][idPrograma]" value="'+progs[0]+'">');
                i++;
            }            
        });

        // Valida se foi selecionado ao menos um programa
        if(gruposDel.length == 0){
            return false;
        }

        $('#formApagaProgramas').submit();
    });

</script>