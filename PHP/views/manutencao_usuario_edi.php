<style type="text/css">
    #user-list,
    #group-list,
    #modulo-list,
    #rotina-list,
    #programa-list{
        float: left;
        list-style: none;
        margin-top: 58px;
        margin-left: 10px;
        padding: 0;
        height: 140px;
        width: 396px;
        position: absolute;
        z-index: 999;
        overflow: auto;
    }

    #modulo-list,
    #rotina-list,
    #programa-list{
        margin-left: 0;
        margin-top: 0 !important;
        width: 302px;
    }

    #user-list li,
    #group-list li,
    #modulo-list li,
    #rotina-list li,
    #programa-list li{
        padding: 10px;
        background: #f0f0f0;
        border-bottom: #e6e6e6 1px solid;
        /*border-radius: 5px;*/
        border-left: #e6e6e6 1px solid;
        border-right: #e6e6e6 1px solid;
    }

    #user-list li:hover,
    #group-list li:hover,
    #modulo-list li:hover,
    #rotina-list li:hover,
    #programa-list li:hover{
        background: #ece3d2;
        cursor: pointer;
    }

    #search-box {
        padding: 10px;
        border: #a8d4b1 1px solid;
        border-radius: 4px;
    }

    .panel-title {
        cursor: pointer
    }

    .list-user,
    .list-grupo {
        list-style: none;
        margin-left: 0;
        padding-left: 0;
    }

    .list-user li,
    .list-grupo li {
        border-bottom: solid 1px #e6e6e6;
        padding: 6px;
    }

    .list-user li .fa-remove,
    .list-grupo li .fa-remove {
        cursor: pointer;
    }
    .hide-div-select{
        display: none;
    }
    .btn-addMRP{
        height: 38px;
        width: 42px;
    }
    .chevron-arrow{
        float:right
    }
    .badge-danger{
        background-color: #de3c3c
    }
    .select2-container{
        width: 100% !important;
    }
</style>
<div class="col-md-12 col-sm-12 col-xs-12">
    <ol class="breadcrumb">
        <li><a href="<?php echo URL ?>/" onclick="loadingPagia()">Dashboard</a></li>
        <li><a href="<?php echo URL ?>/Manutencao/usuario" onclick="loadingPagia()">Manutenção</a></li>
        <li><a href="<?php echo URL ?>/Manutencao/usuario" onclick="loadingPagia()">Usuário</a></li>
        <li class="active"><?php echo $editausuario[0]['nome_usuario'] ?></li>
    </ol>
</div> 
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Edição de Usuários</h2>
                <ul class="nav navbar-right panel_toolbox">
                  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                  </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-md-12">
                        <?php echo $this->helper->alertMessage(); ?>
                    </div>
                </div>
                <?php foreach ($editausuario as $value): ?>
                    <form method="POST" id="frmUsuario" action="<?= URL; ?>/Manutencao/salvarUsuario" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="hidden" id="idusu" name="idusu" class="form-control" value="<?php echo $value['idusu'] ?>" readonly="readonly">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label>Código</label>
                                <input type="hidden" id="codusu" name="codusu" value="<?php echo $value['cod_usuario'] ?>">
                                <input type="text" id="codusu" name="codusu" class="form-control" disabled value="<?php echo $value['cod_usuario'] ?>">
                            </div>
                            <div class="col-md-5">
                                <label>Nome</label>
                                <input type="hidden" id="nomeusu" name="nomeusu" value="<?php echo $value['nome_usuario'] ?>">
                                <input type="text" class="form-control" value="<?php echo $value['nome_usuario'] ?>" disabled>
                            </div>
                            <div class="col-md-4">
                                <label>Função</label><br>
                                <input type="hidden"  id="nomeusu" name="funcaousu" class="form-control" value="<?php echo $value['codfuncao'] ?>">
                                <input type="text"  class="form-control" value="<?php echo $value['funcao'] ?>" disabled>
                                <!--<select style="padding-left: 8px;height:32px; min-width:130px;" name="funcaousu" id="funcaousu" class="form-control" required readonly>
                                    <option value="<?php echo $value['codfuncao'] ?>"><?php echo $value['funcao'] ?></option>
                                    <?php
                                    $sql2 = "SELECT manut.idFuncao,manut.cod_funcao,manut.descricao, 
                                        (select count(cod_funcao) from z_sga_usuarios where cod_funcao = manut.idFuncao) as total
                                        FROM 
                                        z_sga_manut_funcao as manut";
                                    $sql2 = $this->db->query($sql2);
                                    $dadosfuncao = array();
                                    if ($sql2->rowCount() > 0) {
                                        $dadosfuncao = $sql2->fetchAll();
                                    }

                                    foreach ($dadosfuncao as $value2):
                                        echo '<option value="' . $value2['idFuncao'] . '">' . $value2['descricao'] . '</option>';
                                    endforeach;
                                    ?>-->
                                </select>
                            </div>
                        </div>
                        <div class="clearfix">&nbsp;</div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>E-mail</label>
                                <input type="text" id="emailusu" name="emailusu" class="form-control" value="<?php echo $value['email'] ?>">
                            </div>
                        </div>
                        <div class="clearfix"><hr></div>
                        <!-- VALIDA SE EXISTE REVISAO EM ABERTO PARA USUARIO -->                        
                        <?php if($possuiRevisao['total'] > 0): ?>
                        <div class="row">                                                        
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>Gestor de Usuário</label>
                                        <input type="hidden" name="gestorusu" value="<?php echo $value['gestor_usuario']; ?>">
                                        <input type="text" class="form-control" value="<?php echo ($value['gestor_usuario'] == 'S') ? 'Sim' : 'Não'; ?>" <?php echo ($value['gestor_usuario'] == 'S') ? 'readonly' : ''; ?>>                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>Gestor de Grupo</label>
                                        <input type="hidden" name="gestorgrupo" value="<?php echo $value['gestor_grupo']; ?>">
                                        <input type="text" class="form-control" value="<?php echo ($value['gestor_grupo'] == 'S') ? 'Sim' : 'Não'; ?>" <?php echo ($value['gestor_grupo'] == 'S') ? 'readonly' : ''; ?>>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <br>
                                <span class="badge badge-danger">Não é possível alterar usuários e grupos. Usuário possui revisão em aberto!</span>
                                <br>
                                <hr>
                            </div>
                            <?php else: ?>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>Gestor de Usuário</label>
                                        <select style="padding-left: 8px;height:32px; min-width:130px;" name="gestorusu" id="gestorusu" class="form-control">
                                            <option value="S" <?= ($value['gestor_usuario'] == 'S') ? 'selected' : '' ?>>Sim</option>
                                            <option value="" <?= ($value['gestor_usuario'] == 'N') ? 'selected' : '' ?>>Não</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="clearfix">&nbsp;</div>
                                <div class="row <?= ($value['gestor_usuario'] != 'S') ? 'hide' : ''; ?>" id="boxUsuario">
                                    <div class="col-md-12">
                                        <div class="panel-group">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <div class="col-md-12 chevron" data-toggle="collapse"
                                                         data-target="#userCollapse">
                                                        <p class="panel-title">
                                                            Adicionar Usuários
                                                            <!-- <span class="badge"><? /*= (isset($usuarios) && count($usuarios) > 0) ? count($usuarios) :  '0'; */ ?></span>-->
                                                            <i class="fa fa-chevron-up chevron-arrow"></i>
                                                        </p>
                                                        
                                                    </div>
                                                    <br>
                                                </div>
                                                <div id="userCollapse" class="panel-collapse collapse in">
                                                    <div class="panel-body">
                                                        <div class="col-md-11">
                                                            <label>Usuário</label>
                                                            <input type="text" name="usr" id="usr" class="form-control" autocomplete="off">
                                                            <input type="text" name="idUsr2" id="idUsr2" class="form-control  hide">
                                                        </div>
                                                        <div class="col-md-1">
                                                            <label>&nbsp;</label>
                                                            &nbsp;<button type="button" class="btn btn btn-success pull-right" id="addUsuario">
                                                                <i class="fa fa-plus"></i>
                                                            </button>
                                                        </div>
                                                        <div id="suggesstion-user-box">
                                                            <ul id="user-list"></ul>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <hr>
                                                            <p><strong>Usuários adicionados</strong></p>
                                                            <ul class="list-user"></ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4">
                                    <label>Gestor de Grupo</label>
                                    <select style="padding-left: 8px;height:32px; min-width:130px;" name="gestorgrupo" id="gestorgrupo" class="form-control">
                                        <option value="S" <?= ($value['gestor_grupo'] == 'S') ? 'selected' : '' ?>>Sim</option>
                                        <option value="" <?= ($value['gestor_grupo'] == 'N' || $value['gestor_grupo'] == '') ? 'selected' : '' ?>>Não</option>
                                    </select>
                                    </div>
                                </div>
                                <div class="clearfix">&nbsp;</div>
                                <div class="row <?= ($value['gestor_grupo'] != 'S') ? 'hide' : ''; ?>" id="boxGrupo">
                                    <div class="col-md-12">
                                        <div class="panel-group">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <div class="col-md-12 chevron" data-toggle="collapse" data-target="#grupoCollapse">
                                                        <p class="panel-title">
                                                            Adicionar Grupos
                                                            <i class="fa fa-chevron-up chevron-arrow"></i>
                                                        </p>                                                        
                                                    </div>
                                                    <br>
                                                </div>
                                                <div id="grupoCollapse" class="panel-collapse collapse in">
                                                    <div class="panel-body">
                                                        <div class="col-md-11">
                                                            <label>Grupo</label>
                                                            <input type="text" name="grupo" id="grp" class="form-control" autocomplete="off">
                                                            <input type="text" name="idGrp2" id="idGrp2" class="form-control hide">
                                                        </div>
                                                        <div class="col-md-1">
                                                            <label>&nbsp;</label>
                                                            &nbsp;<button type="button" class="btn btn btn-success pull-right" id="addGrupo"><i class="fa fa-plus"></i></button>
                                                        </div>
                                                        <div id="suggesstion-group-box">
                                                            <ul id="group-list"></ul>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <hr>
                                                            <p><strong>Grupos adicionados</strong></p>
                                                            <ul class="list-grupo"></ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>                            
                        </div>
                        <?php endif; ?>                        
    
                        <div class="clearfix"><hr></div>

                        <!-- DIVS PARA GESTOR DE MÓDULO- ROTINA - PROGRAMA -->
                        <div class="row">
                            <div class="col-md-2">
                                <label>Gestor de Módulos</label>
                                <select style="padding-left: 8px;height:32px; min-width:130px;" name="gestorprograma" id="gestorModulo" class="form-control">
                                    <option value="S" <?= ($value['gestor_programa'] == 'S') ? 'selected' : '' ?>>Sim</option>
                                    <option value="" <?= ($value['gestor_programa'] == 'N' || $value['gestor_programa'] == '') ? 'selected' : '' ?>>Não</option>
                                </select>
                            </div>
                            <div class="col-md-2">&nbsp;</div>
                        </div>
                        <div class="clearfix">&nbsp;</div>

                        <!-- EXIBE OS SELECTS DE PROGRAMA - ROTINA - MÓDULO -->
                        <div class="row <?= ($value['gestor_programa'] == 'S') ? '' : 'hide' ?>" id="boxModulo">
                            <!--<div class="<?php ($value['gestor_programa'] == 'S') ? '' : 'hide'; ?>" id="boxMRP">-->
                            <div>
                                <div class="col-md-4">
                                    <label>Módulo</label>
                                    <input type="text" class="form-control" id="txtModulos" placeholder="Buscar um módulo">
                                    <div id="suggesstion-modulo-box" class="hide">
                                        <ul id="modulo-list"></ul>
                                    </div>
                                    <br>
                                    <select name="modulos" class="form-control" id="modulo">                                        
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label>Rotina</label>
                                    <input type="text" class="form-control" id="txtRotinas" placeholder="Buscar uma rotina">
                                    <div id="suggesstion-rotina-box" class="hide">
                                        <ul id="rotina-list"></ul>
                                    </div>
                                    <br>
                                    <select name="rotinas" class="form-control" id="rotina">                                        
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label>Programa</label>
                                    <input type="text" class="form-control" id="txtProgramas" placeholder="Buscar um programa">
                                    <div id="suggesstion-programa-box" class="hide">
                                        <ul id="programa-list"></ul>
                                    </div>
                                    <br>
                                    <select name="programas" class="form-control" id="programa" style="min-width: 230px">                                        
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <label>&nbsp;</label><br>
                                    <button type="button" class="btn btn-success btn-addMRP" id="addMRP" style="margin-top:53px"><i class="fa fa-plus"></i> </button>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <hr>
                                <p><strong>Módulos, rotinas e programas adicionados</strong></p>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table class="table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline"
                                               cellspacing="0" width="100%" role="grid"
                                               aria-describedby="datatable-responsive_info" style="width: 100%;">
                                            <thead>
                                            <tr>
                                                <!--<th><input type="checkbox" id="checkAllUsuarios"></th>-->
                                                <th>Módulo</th>
                                                <th>Rotina</th>
                                                <th>Programa</th>
                                                <th>Gestor Atual</th>
                                                <th>Ação</th>
                                            </tr>
                                            </thead>
                                            <tbody id="list-mrp"></tbody>
                                        </table>
                                    </div>
                                </div>
                                <ul class=""></ul>
                            </div>
                        </div>
                        <!-- FIM DIVS PARA GESTOR DE MÓDULO- ROTINA - PROGRAMA -->

                        <div class="clearfix"><hr></div>

                        <div class="row">
                            <div class="col-md-12">
                                <input type="submit" name="modaleditsalvar" class="btn btn-success pull-right" value="Salvar">
                                <button type="button" class="btn btn-danger pull-right" onclick="javascript:history.back(-1)">Voltar</button>
                                <!--<button type="button" class="btn btn-default" data-dismiss="modal">Sair</button>-->
                            </div>
                        </div>
                    </form>
                <?php endforeach; ?>
            </div>
        </div>
        <?php if ($value['gestor_usuario'] == 'S' || $value['gestor_grupo'] == 'S' || $value['gestor_programa'] == 'S') : ?>
        <div class="x_panel">
            <div class="x_title">
                <h2>Listagem de Usuários, grupos, módulos, rotinas e programas</h2>
                <ul class="nav navbar-right panel_toolbox">
                  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="" role="tabpanel" data-example-id="togglable-tabs">
                    <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                        <?php if ($value['gestor_usuario'] == 'S') : ?>
                            <li role="presentation" class="active">
                                <a href="#tab_content1" id="profile-tab1" role="tab" data-toggle="tab" aria-expanded="true">
                                    Usuários Gestor <span class="badge"><?php echo count($usuarios) ?>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($value['gestor_grupo'] == 'S') : ?>
                            <li role="presentation" class="">
                                <a href="#tab_content2" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">
                                    Grupos Gestor <span class="badge"><?php echo count($grupos) ?></span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($value['gestor_programa'] == 'S' && count($mpr) > 0) : ?>
                            <li role="presentation" class="">
                                <a href="#tab_content3" role="tab" id="profile-tab3" data-toggle="tab" aria-expanded="false">
                                    Módulos Gestor <span class="badge"><?php echo count($mpr) ?></span>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <div id="myTabContent" class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in" id="tab_content1"
                             aria-labelledby="profile-tab1"><!--Inicio Tabela 1 -->
                            <br>
                            <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                <form action="<?= URL; ?>/Manutencao/apagaUsuariosSelecionados" id="formUsuarios" method="post">
                                    <input type="hidden" name="idGestor" value="<?= $value['idusu']; ?>">
                                    <div class="row">
                                        <div class="col-sm-6"></div>
                                        <div class="col-sm-6"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table class="tabela table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline"
                                                   cellspacing="0" width="100%" role="grid"
                                                   aria-describedby="datatable-responsive_info" style="width: 100%;">
                                                <thead>
                                                <tr>
                                                    <th><?php echo ($possuiRevisao['total'] == 0) ? '<input type="checkbox" id="checkAllUsuarios">' : ''; ?> </th>
                                                    <th>Nome</th>
                                                    <th>Código Usuário</th>
                                                    <th>Situação do Usuário</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                <?php if(isset($usuarios) && count($usuarios) > 0): ?>
                                                <?php foreach ($usuarios as $valorlista): ?>
                                                    <tr>
                                                        <th><input type="checkbox" class="checkUsuarios" name="usuarios[]" value="<?= $valorlista['idUsuario']; ?>"></th>
                                                        <td><?php echo $valorlista['nome_usuario'] ?></td>
                                                        <td><?php echo utf8_decode($valorlista['cod_usuario']) ?></td>
                                                        <td><?php echo ($valorlista['ativo'] == 1) ? 'Sim' : 'Não'; ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                                <?php endif; ?>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <?php if($possuiRevisao['total'] == 0): ?>
                                            <button type="button" class="btn btn-danger pull-left" id="btnApagaUsuarios">Apagar Selecionados</button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div><!--Fim Tabela 1 -->

                        <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab2"><!--Inicio Tabela 2 -->
                            <br>
                            <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                <form action="<?= URL; ?>/Manutencao/apagaGruposSelecionados" id="formGrupos" method="post">
                                    <input type="hidden" name="idGestor" value="<?= $value['idusu']; ?>">
                                    <input type="hidden" name="codUsuario" value="<?= $value['cod_usuario']; ?>">
                                    <div class="row">
                                        <div class="col-sm-6"></div>
                                        <div class="col-sm-6"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table class="tabela table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline"
                                                   cellspacing="0" width="100%" role="grid"
                                                   aria-describedby="datatable-responsive_info" style="width: 100%;">
                                                <thead>
                                                <tr>
                                                    <th><?php echo ($possuiRevisao['total'] == 0) ? '<input type="checkbox" id="checkAllGrupos">' : ''; ?></th>
                                                    <th>ID Grupo</th>
                                                    <th>Descrição Abreviada</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                <?php if(isset($grupos) && count($grupos) > 0): ?>
                                                    <?php foreach ($grupos as $valorlista): ?>
                                                        <tr>
                                                            <th><input type="checkbox" name="grupos[]" class="checkGrupos" value="<?= $valorlista['idLegGrupo']; ?>"></th>
                                                            <td><?php echo $valorlista['idLegGrupo']; ?></td>
                                                            <td><?php echo $valorlista['descAbrev']; ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <?php if($possuiRevisao['total'] == 0): ?>
                                            <button type="button" class="btn btn-danger pull-left" id="btnApagaGrupos">Apagar Selecionados</button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div><!--Fim Tabela 2 -->
                        
                        <div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="profile-tab3"><!--Inicio Tabela 3 -->
                            <br>
                            <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                <form action="<?= URL; ?>/Manutencao/apagaMPRSelecionados" id="formMPR" method="post">                                    
                                    <input type="hidden" name="idGestor" value="<?php echo $value['idusu']?>">
                                    <div class="row">
                                        <div class="col-sm-6"></div>
                                        <div class="col-sm-6"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table class="tabela table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline"
                                                   cellspacing="0" width="100%" role="grid"
                                                   aria-describedby="datatable-responsive_info" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th><?php echo ($possuiRevisao['total'] == 0) ? '<input type="checkbox" id="checkAllMPR">' : ''; ?></th>
                                                        <th>Módulo</th>
                                                        <th>Rotina</th>
                                                        <th>Programa</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php if(isset($mpr) && count($mpr) > 0): ?>
                                                    <?php foreach ($mpr as $valorlista): ?>
                                                        <tr>
                                                            <th><input type="checkbox" name="mpr[]" class="checkMPR" value="<?= $valorlista['id']; ?>"></th>
                                                            <td><?php echo ($valorlista['codMod'] == '*') ? 'TODOS' : $valorlista['codMod']; ?></td>
                                                            <td><?php echo ($valorlista['codRot'] == '*') ? 'TODOS' : $valorlista['codRot']; ?></td>
                                                            <td><?php echo ($valorlista['codProg'] == '*') ? 'TODOS' : $valorlista['codProg']; ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <?php if($possuiRevisao['total'] == 0): ?>
                                            <button type="button" class="btn btn-danger pull-left" id="btnApagaMPR">Apagar Selecionados</button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div><!--Fim Tabela 2 -->
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<div id="myModalResult" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <p id="result_msg"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- set up the modal to start hidden and fade in and out -->
<div id="myModalConfirm" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- dialog body -->
            <div class="modal-body"></div>
            <!-- dialog buttons -->
            <div class="modal-footer">
                <button type="button" id="cancel" class="btn btn-danger">Cancelar</button>
                <button type="button" id="continue" class="btn btn-success">Continuar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        /************************ Funções de manipulação de usuários ************************/
        $('#gestorusu').on('change', function () {
            if ($(this).val() == 'S') {
                $('#boxUsuario').removeClass('hide');
                $('#userCollapse').collapse('show');
            } else {
                $('#myModalConfirm .modal-body').html('<h5>Ao continuar os usuários adicionados serão retirados. Deseja continuar?</h5>');
                $("#myModalConfirm").modal('show');

                // Se botao de continuar for clicado
                $('#myModalConfirm').find('#continue').on("click", function(e) {
                    $('#boxUsuario').addClass('hide');
                    $('#userCollapse').collapse('hide');
                    $(".list-user").html('');
                    $("#myModalConfirm").modal('hide');
                });

                // Se botao de cancelar for clicado
                $('#myModalConfirm').find('#cancel').on("click", function(e) {
                    $('#gestorusu').val('S');
                    $("#myModalConfirm").modal('hide');
                });
            }
        });

        $("#usr").keyup(function () {

            if ($(this).val().length === 0) {
                $("#idUsr2").val("");
                return false;
            }
            var idUsr = $(this).val();
            $.ajax({
                type: "POST",
                url: url + "Manutencao/ajaxCarregaUsr",
                data: {
                    idUsr: idUsr,
                    idGestor: $('#idusu').val()
                },
                beforeSend: function () {
                    $("#usr").css("background", "#FFF url(LoaderIcon.gif) no-repeat 165px");
                },
                success: function (data) {
                    $("#suggesstion-user-box").show();
                    $("#user-list").html(data);

                }
            });
        });

        // Adiciona o usuário que estiver no campo usuário
        $('#addUsuario').on('click', function () {
            if ($("#usr").val() == '' && $("#idUsr2").val() == '') {
                return false;
            }

            var idUser = $("#idUsr2").val();
            var user = $("#usr").val();
            var usrExists = false;

            // percorre os usuarios adicionados e valida se existe usuario com mesmo id
            $('.list-user li input').each(function () {
                var id = $(this).val();
                if (id == idUser) {
                    $('.modal-title').text('');
                    $('#result_msg').text('Usuário já adicionado!');
                    $('#myModalResult').modal('show');
                    usrExists = true;
                    return false;
                }
            });

            if (usrExists) {
                return false;
            }

            // retorna o gestor do usuário caso exista
            $.ajax({
                type: 'POST',
                url: url+'/Manutencao/ajaxBuscaGestor',
                data: {
                    idUsr: $("#idUsr2").val(),
                    tipo: 'usuario'
                },
                success: function(data){
                    var obj = JSON.parse(data)
                    var htmluser = "";

                    if(parseInt(obj.total) > 0){
                        htmluser = "<li><input type=\"hidden\" name=\"usuarios[]\" value=\"" + idUser + "\"> " + user + " <span class=\"label label-warning\"> Gestor: "+obj.gestor['nome_usuario']+"</span> <i class=\"fa fa-remove pull-right\"></i>  </li>";
                    }else{
                        htmluser = "<li><input type=\"hidden\" name=\"usuarios[]\" value=\"" + idUser + "\"> " + user + " </span> <i class=\"fa fa-remove pull-right\"></i>  </li>";
                    }
                    $(".list-user").append(htmluser);
                    $("#usr").val('');
                    $("#idUsr2").val('')
                }
            });
        });

        /************************ FIM Funções de manipulação de usuários ************************/

        /************************ Funções de manipulação de grupos ************************/
        $('#gestorgrupo').on('change', function () {
            if ($(this).val() == 'S') {
                $('#boxGrupo').removeClass('hide');
                $('#GrupoCollapse').collapse('show');
            } else {
                $('#myModalConfirm .modal-body').html('<h5>Ao continuar os grupos adicionados serão retirados. Deseja continuar?</h5>');
                $("#myModalConfirm").modal('show');

                // Se botao de continuar for clicado
                $('#myModalConfirm').find('#continue').on("click", function(e) {
                    $('#boxGrupo').addClass('hide');
                    $('#GrupoCollapse').collapse('hide');
                    $('.list-grupo').html('');
                    $("#myModalConfirm").modal('hide');
                });

                // Se botao de cancelar for clicado
                $('#myModalConfirm').find('#cancel').on("click", function(e) {
                    $('#gestorgrupo').val('S');
                    $("#myModalConfirm").modal('hide');
                });
            }
        });

        $("#grp").keyup(function () {

            if ($(this).val().length == 0) {
                $("#idGrp").val("");
            }
            var idGrp = $(this).val();

            $.ajax({
                type: "POST",
                url: url + "Manutencao/ajaxCarregaGrp",
                data: {
                    idGrp: idGrp,
                    idGestor: $('#idusu').val()
                },
                beforeSend: function () {
                    $("#grpusr").css("background", "#FFF url(LoaderIcon.gif) no-repeat 165px");
                },
                success: function (data) {
                    $("#suggesstion-group-box").show();
                    $("#group-list").html(data);
                }
            });
        });

        // Adiciona o grupo que estiver no campo grupo
        $('#addGrupo').on('click', function () {
            if ($("#grp").val() == '' && $("#idGrp2").val() == '') {
                return false;
            }

            var idLegGrupo = $("#idGrp2").val();
            var group = $("#grp").val();
            var grpExists = false;

            // percorre os grupos adicionados e valida se existe grupo com mesmo id
            $('.list-grupo li input').each(function () {
                var id = $(this).val();
                if (id == idLegGrupo) {
                    $('.modal-title').text('');
                    $('#result_msg').text('Grupo já adicionado!');
                    $('#myModalResult').modal('show');
                    grpExists = true;
                    return false;
                }
            });

            if (grpExists) {
                return false;
            }

            // retorna o gestor do usuário caso exista
            $.ajax({
                type: 'POST',
                url: url+'/Manutencao/ajaxBuscaGestor',
                data: {
                    idUsr: $("#idGrp2").val(),
                    tipo: 'grupo'
                },
                success: function(data){
                    var obj = JSON.parse(data)
                    var htmlgrupo = "";

                    if(parseInt(obj.total) > 0){
                        htmlgrupo = "<li><input type=\"hidden\" name=\"grupos[]\" value=\"" + idLegGrupo + "\"> " + group + " <span class=\"label label-warning\"> Gestor: "+obj.gestor['nome_usuario']+"</span> <i class=\"fa fa-remove pull-right\"></i>  </li>";
                    }else{
                        htmlgrupo = "<li><input type=\"hidden\" name=\"grupos[]\" value=\"" + idLegGrupo + "\"> " + group + " </span> <i class=\"fa fa-remove pull-right\"></i>  </li>";
                    }
                    $(".list-grupo").append(htmlgrupo);
                    $("#grp").val('');
                    $("#idGrp2").val('');
                }
            });
        });
        
        // Altera icone chevron das divs collapse adiciona usuario e grupos
        $('.chevron').on('click', function(){
            if($(this).find('.panel-title i').first().hasClass('fa fa-chevron-up')){
                $(this).find('.panel-title i').first().removeClass('fa fa-chevron-up');
                $(this).find('.panel-title i').first().addClass('fa fa-chevron-down');
            }else{
                $(this).find('.panel-title i').first().removeClass('fa fa-chevron-down');
                $(this).find('.panel-title i').first().addClass('fa fa-chevron-up');
            }
        });
    });

    // Remove o usuário ou grupo selecionado quando clicado
    $(document).on('click', '.fa-remove', function () {
        $(this).closest('li').remove();
    });

    // Marca ou desmarca checkbox de usuarios
    $('#checkAllUsuarios').on('click', function(){
        $('.checkUsuarios').not(this).prop('checked', this.checked);
    });

    // Marca ou desmarca checkbox de grupos
    $('#checkAllGrupos').on('click', function(){
        $('.checkGrupos').not(this).prop('checked', this.checked);
    });

    function carregaDadosGrp(idLegGrupo, descAbrev) {
        $("#grp").val(descAbrev);
        $("#idGrp2").val(idLegGrupo);
        $("#suggesstion-group-box").hide();
    }

    function carregaDadosUsr(nomeUsr, idUsuario) {
        $("#usr").val(nomeUsr);
        $("#idUsr2").val(idUsuario);
        $("#suggesstion-user-box").hide();
    }

    // Apaga usuarios selecionados no datatable
    $('#btnApagaUsuarios').on('click', function(){
        var usuariosDel = [];

        $('.checkUsuarios').each(function(){
            if($(this).prop('checked')){
                usuariosDel.push($(this).attr('id'));
            }
        });

        // Valida se foi selecionado ao menos um usuário
        if(usuariosDel.length == 0){
            return false;
        }

        $('#formUsuarios').submit();
    });

    // Apaga grupos selecionados no datatable
    $('#btnApagaGrupos').on('click', function(){
        var gruposDel = [];

        $('.checkGrupos').each(function(){
            if($(this).prop('checked')) {
                gruposDel.push($(this).attr('id'));
            }
        });

        // Valida se foi selecionado ao menos um grupo
        if(gruposDel.length == 0){
            return false;
        }

        $('#formGrupos').submit();
    });
    /************************ FIM Funções de manipulação de grupos ************************/

</script>

<script>
    /* Funções para gestor de Módulo - Rotinas - Programas */
    /* NÃO PODE DEIXAR SELECIONADO * PRA TODOS OS SELECTS */

    function carregaDadosModulo(id, descModulo) {
        //$('#txtModulos').val(descModulo);
        $('#txtModulos').val('');
        $("#suggesstion-modulo-box").addClass('hide');

        $.ajax({
            type: 'POST',
            url: url+'Manutencao/ajaxBuscaRotinaProgByModulo',
            data: {idMod: id},
            success: function(res){
                $('#modulo').html('<option value="'+id+'">'+id+' - '+descModulo+'</option>');
                $('#rotina').html(res);
                $('#programa').html('<option value="*">* - TODOS</option>');
            }
        });
    }

    function carregaDadosRotina(id, descRotina) {
        $('#txtRotinas').val('');
        $("#suggesstion-rotina-box").addClass('hide');

        $.ajax({
            type: 'POST',
            url: url+'Manutencao/ajaxBuscaModuloProgByRotina',
            data: {idRotina: id},
            success: function(res){
                $('#modulo').html(res);
                $('#rotina').html('<option value="'+id+'">'+descRotina+'</option>');
                $('#programa').html('<option value="*">* - TODOS</option>');
            }
        });
    }

    function carregaDadosPrograma(id, descPrograma) {
        //$('#txtProgramas').val(descPrograma);
        $('#txtProgramas').val('');
        $("#suggesstion-programa-box").addClass('hide');

        $.ajax({
            type: 'POST',
            url: url + 'Manutencao/buscaModuloRotinaByProg',
            data: {idProg: id},
            success: function (res) {
                var data = JSON.parse(res);
                $('#programa').html('<option value="'+id+'">'+id+' - '+descPrograma+'</option>');
                $('#modulo').html(data.modulo);
                $('#rotina').html(data.rotina);
            }
        });
    }

    function matchStart(params, data) {
        // If there are no search terms, return all of the data
        if ($.trim(params.term) === '') {
            return data;
        }

        // Skip if there is no 'children' property
        if (typeof data.children === 'undefined') {
            return null;
        }

        // `data.children` contains the actual options that we are matching against
        var filteredChildren = [];
        $.each(data.children, function (idx, child) {
            if (child.text.toUpperCase().indexOf(params.term.toUpperCase()) == 0) {
                filteredChildren.push(child);
            }
        });

        // If we matched any of the timezone group's children, then set the matched children on the group
        // and return the group object
        if (filteredChildren.length) {
            var modifiedData = $.extend({}, data, true);
            modifiedData.children = filteredChildren;

            // You can return modified objects from here
            // This includes matching the `children` how you want in nested data sets
            return modifiedData;
        }

        // Return `null` if the term should not be displayed
        return null;
    }

    $(document).ready(function(){
        /************** Campo de busca e seleção para o grupo ****************/
        $('#funcaousu').select2({
            //allowClear: true,
            matcher: matchStart,
            multiple: false
        });

        // Valida se grupo selecionado é igual ao grupo que receberá os programas.
        $("#funcaousu").select2().on("select2:select", function (e){
            // Remove a seleção do option Nenhum
            if(e.params.data.id === ''){
                $("#funcaousu").val('').change();
            }else{
                var values = $("#funcaousu").val();
                var i = $("#funcaousu").val().indexOf('');

                if (i > 0) {
                    values.splice(i, 1);
                    $("#funcaousu").val(values).change();
                    //$('#idGrupoHidden').val($("#idGrupoSelect2").val());
                }
            }
        });
        /************* FIM campo busca e seleção para o grupo *****************/



        $('#gestorModulo').on('change', function(){
            
            if($(this).val() != 'S'){
                $('#myModalConfirm .modal-body').html('<h5>Ao continuar os módulos, rotinas e programas adicionados serão retirados. Deseja continuar?</h5>');
                $("#myModalConfirm").modal('show');
                
                // Se botao de cancelar for clicado
                $('#myModalConfirm').find('#cancel').on("click", function(e) {
                    $('#gestorModulo').val('S');                
                    $("#myModalConfirm").modal('hide');                
                    return false;
                });                                
                
                $('#myModalConfirm').find('#continue').on("click", function(e) {
                    $('#boxModulo').addClass('hide');
                    $("#myModalConfirm").modal('hide'); 
                });
            }else{
                $('#boxModulo').toggleClass('hide');
            }            
        });
        
        
        $("#modulo").select2();
        $("#txtModulos").keyup(function(){
            $.ajax({
                type: 'POST',
                url: url+"Manutencao/ajaxBuscaModulos",
                data: {search: $(this).val()},
                success: function (data) {
                    $("#suggesstion-modulo-box").removeClass('hide');
                    $('#modulo-list').html(data);
                }
            });
        });

        $("#rotina").select2();
        $('#txtRotinas').keyup(function(){
            $.ajax({
                type: 'POST',
                url: url+"Manutencao/ajaxBuscaRotinas",
                data: {search: $(this).val()},
                success: function (data) {
                    $("#suggesstion-rotina-box").removeClass('hide');
                    $('#rotina-list').html(data);
                }
            });
        });

        $("#programa").select2();
        $('#txtProgramas').keyup(function(){
            $.ajax({
                type: 'POST',
                url: url+"Manutencao/ajaxBuscaProgramasUsuarios",
                data: {search: $(this).val()},
                success: function (data) {
                    $("#suggesstion-programa-box").removeClass('hide');
                    $('#programa-list').html(data);
                }
            });
        });

        // Adiciona o usuário que estiver no campo usuário
        $('#addMRP').on('click', function () {
            if (($("#modulo").val() == '' || $("#modulo").val() == null) || ($("#rotina").val() == '' || $("#rotina").val() == null) && ($("#programa").val() == '' || $("#programa").val() == null)) {
                return false;
            }

            var codModulo = $("#modulo").val();
            var descModulo = $("#modulo option:selected").text();

            var codRotina = $("#rotina").val();
            var descRotina = $("#rotina option:selected").text();

            var codPrograma = $("#programa").val();
            var descPrograma = $("#programa option:selected").text();
            var idUsuario = $('#idusu').val();
            var usrExists = false;


            // percorre as linhas adicionadas e valida se já existe uma linha identica
            $('#list-mrp tr input').each(function () {
                var id = $(this).val();
                if (id == idUsuario+'-'+codModulo+'-'+codRotina+'-'+codPrograma) {
                    $('.modal-title').text('');
                    $('#result_msg').text('Já existe linha com os dados selecionados!');
                    $('#myModalResult').modal('show');
                    usrExists = true;
                    return false;
                }
            });

            if (usrExists) {
                return false;
            }

            // Valida se o usuário já é gestor do módulo, programa, e rotina específicado
            $.ajax({
                type: 'POST',
                url: url+'/Manutencao/ajaxValidaGestorPrograma',
                data: {
                    codModulo: codModulo,
                    codRotina: codRotina,
                    codPrograma: codPrograma,
                    idUsuario: idUsuario
                },
                success: function(data){                    
                    var obj = JSON.parse(data);
                    //var htmluser = "";
                    var htmltr = "";
                    
                    if(obj.total == 'ja possui mrp'){
                        $('.modal-title').text('');
                        $('#result_msg').text('Usuário já é gestor do módulo, rotina e programa específicado.');
                        $('#myModalResult').modal('show');
                        return false;
                    }

                    if(obj.total == 'ja possui gestor'){
                        $('.modal-title').text('');
                        $('#result_msg').text('Já existe gestor do módulo, rotina e programa específicado. Favor eliminar o gestor atual primeiro!');
                        $('#myModalResult').modal('show');
                        return false;
                    }
                    
                    if(obj.total > 0){
                        $('#frmUsuario').append('<input type="hidden" name="modulos[]" value="' + idUsuario+ '-'+codModulo+'-'+codRotina+'-'+codPrograma + '">');
                        htmltr += '<tr>';
                        htmltr += '<td>'+descModulo+'</td>';
                        htmltr += '<td>'+descRotina+'</td>';
                        htmltr += '<td>'+descPrograma+'</td>';
                        htmltr += '<td>'+obj.gestor['nome_usuario']+'</td>';
                        htmltr += '<td><i class="fa fa-remove pull-right"></i></td>';
                        htmltr += '</tr>';                        
                    }else{
                        $('#frmUsuario').append('<input type="hidden" name="modulos[]" value="' + idUsuario+ '-'+codModulo+'-'+codRotina+'-'+codPrograma + '">');
                        htmltr += '<tr>';
                        htmltr += '<td>'+descModulo+'</td>';
                        htmltr += '<td>'+descRotina+'</td>';
                        htmltr += '<td>'+descPrograma+'</td>';
                        htmltr += '<td></td>';
                        htmltr += '<td><i class="fa fa-remove pull-right"></i></td>';
                        htmltr += '</tr>';                        
                    }
                    $("#list-mrp").append(htmltr);
                    $("#modulo").html('');
                    $("#rotina").html('');
                    $("#programa").html('');
                }
            });
        });

        // Evento ao selecionar se é gestor de módulo, rotina, programa ou não
        $('#gestorModulo').on('change', function(){
            if($('#gestorModulo').val() == 'S'){
                $('#boxMRP').removeClass('hide');
            }else{
                $('#boxMRP').addClass('hide');
            }
        });

        // Remove o linha com módulo, rotina e programa quando clicado
        $(document).on('click', '.fa-remove', function () {
            $(this).closest('tr').remove();
        });
        
        // Apaga módulos selecionados no datatable
        $('#btnApagaMPR').on('click', function(){
            var mprDel = [];

            $('.checkMPR').each(function(){
                if($(this).prop('checked')) {
                    mprDel.push($(this).attr('id'));
                }
            });

            // Valida se foi selecionado ao menos um módulo
            if(mprDel.length == 0){
                return false;
            }

            $('#formMPR').submit();
        });
        
        // Marca ou desmarca checkbox de grupos
        $('#checkAllMPR').on('click', function(){
            $('.checkMPR').not(this).prop('checked', this.checked);
        });
    });
</script>





