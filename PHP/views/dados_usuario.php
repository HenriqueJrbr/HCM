<div class="col-md-12 col-sm-12 col-xs-12">
    <ol class="breadcrumb">
        <li><a href="<?php echo URL ?>/" onclick="loadingPagia()">Dashboard</a></li>          
        <li><a href="<?php echo URL ?>/Usuario"><font style="vertical-align: inherit;" onclick="loadingPagia()"><font style="vertical-align: inherit;">Usuário</font></font></a></li>
        <li class="active"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $usuario['nome_usuario'] ?></font></font></li>
    </ol>
</div>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel"><!--Inicia x_panel-->

        <div class="x_title"><!--Inicia x_title-->
            <h2>Informações do Usuário</h2>
            <div class="clearfix"></div>
        </div><!--Fim x_title-->
        <div class="row">
            <div class="col-md-6 col-xs-12 col-sm-12">
                <label style="font-size: 25px;">Nome: </label>
                <span style="color: #FF8000; font-size: 25px"><?php echo $usuario['nome_usuario'] ?> </span>
            </div>
            <div class="col-md-6 col-xs-12 col-sm-4">
                <label style="font-size: 25px;" >Função: </label>
                <span style="color: #FF8000; font-size: 25px"><?php echo $usuario['descricao']; ?></span>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-2 col-xs-12 col-sm-4">
                <label>ID DataSul: </label> 
                <input type="hidden" id="idUsuario" value="<?php echo $usuario['z_sga_usuarios_id'] ?>">
                <span><?php echo $usuario['cod_usuario'] ?></span>
            </div>
            <div class="col-md-2 col-xs-12 col-sm-4">
                <label>ID Fluig:  </label>
                <span> <?php echo $usuario['idUsrFluig'] ?></span>
            </div>
            <div class="col-md-4">
                <label>Gestor do usuário:  </label>
                <span> <?php if ($dadosGestor == '1') {
    echo "Não Cadastrado";
} else {
    echo $dadosGestor['nome_usuario'];
} ?></span>
            </div>
            <div class="col-md-4 col-xs-12 col-sm-4">
                <label>E-mail: </label>
                <span><?php if ($usuario['email'] == '') {
    echo 'Não Cadastrado';
} else {
    echo $usuario['email'];
} ?></span>
            </div>

        </div>
        <br>
        <div class="row">

            <div class="col-md-3">
                <label>É Gestor de Grupo?  </label>
                <span> <?php if ($usuario['gestor_grupo'] == "S") {
    echo "Sim";
} else {
    echo 'Não';
} ?></span>
            </div>
            <div class="col-md-3">
                <label>É Gestor de Usuário?  </label>
                <span> <?php if ($usuario['gestor_usuario'] == "S") {
    echo "Sim";
} else {
    echo 'Não';
} ?></span>
            </div>
            <div class="col-md-3">
                <label>É Gestor de Programa?  </label>
                <span> <?php if ($usuario['gestor_programa'] == "S") {
    echo "Sim";
} else {
    echo 'Não';
} ?></span>
            </div>
            <div class="col-md-3">
                <label>Segurança de Informação ?  </label>
                <span> <?php if ($usuario['si'] == "S") {
    echo "Sim";
} else {
    echo 'Não';
} ?></span>
            </div>
        </div>
        <br>
        <div class="x_title">
            <h2><i class="fa fa-user"></i> <small> Acessos Usuário</small></h2>     
            <div class="clearfix"></div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-2">
                <a class="btn btn-danger" href="<?php echo URL ?>/views/geraExcell.php?idUsuario=<?php echo $usuario['z_sga_usuarios_id'] ?>&idEmpresa=<?php echo $_SESSION['empresaid'] ?>" >
                    <i class="fa fa-file-excel-o"></i> Exportar
                </a>
            </div>
        </div>
        <br>
        <div class="x_content">
            <div class="" role="tabpanel" data-example-id="togglable-tabs">
                <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#tab_content20" id="profile-tab20" role="tab" data-toggle="tab" aria-expanded="true">Visão Atual</a>
                    </li>
                    <li role="presentation" class=""><a href="#tab_content30" role="tab" id="profile-tab30" data-toggle="tab" aria-expanded="false">Visão Anterior <?php echo (!empty($dataSnapshot) ? "<span class=\"badge\"><strong>".date('d/m/Y H:i:s', strtotime($dataSnapshot['dataHora']))."</strong></span>" : ''); ?></span></a>
                    </li>
                    <li role="presentation" class=""><a href="#tab_content100" role="tab" id="profile-tab100" data-toggle="tab" aria-expanded="false">LGPD</a>
                    </li>
                </ul>
                <div id="myTabContent" class="tab-content">
                    <div role="tabpanel" class="tab-pane fade active in" id="tab_content20" aria-labelledby="profile-tab20"><!--Inicio Tabela 1 -->
                        <div class="" role="tabpanel" data-example-id="togglable-tabs">
                            <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#tab_content1" id="profile-tab1" role="tab" data-toggle="tab" aria-expanded="true">Grupos <span class="badge"><?php echo $totalAcesso['numGrupos'] ?></a>
                                </li>
                                <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Programas <span class="badge"><?php echo $totalAcesso['numProgs'] ?></span></a>
                                </li>
                                <li role="presentation" class=""><a href="#tab_content3" role="tab" id="profile-tab3" data-toggle="tab" aria-expanded="false">Prog. Duplicados <span class="badge"><?php echo $totalAcesso['nroProgDup'] ?></span></a>
                                </li>
                                <li role="presentation" class=""><a href="#tab_content4" role="tab" id="profile-tab4" data-toggle="tab" aria-expanded="false">Matriz de Risco <span class="badge"><?php echo $totalAcesso['nroRiscos'] ?></span></a>
                                </li>
                                <li role="presentation" class=""><a href="#tab_content5" role="tab" id="profile-tab5" data-toggle="tab" aria-expanded="false">Processos <span class="badge"><?php echo $totalAcesso['numProcess'] ?></span></a>
                                </li>
                                <li role="presentation" class=""><a href="#tab_content6" role="tab" id="profile-ta6" data-toggle="tab" aria-expanded="false">Acesso a Módulos <span class="badge"><?php echo $totalAcesso['numModulos'] ?></span></a>
                                </li>   
                            </ul>
                            <div id="myTabContent" class="tab-content">
                                <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="profile-tab1"><!--Inicio Tabela 1 -->
                                    <br>
                                    <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer"><div class="row"><div class="col-sm-6"></div><div class="col-sm-6"></div></div><div class="row"><div class="col-sm-12">
                                                <table  class="tabela table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info" style="width: 100%;" id="tableGrupoUsuario">
                                                    <thead>
                                                        <tr>
                                                            <th>Id Grupo</th>
                                                            <th>Descrição</th>
                                                            <th>Gestor</th>
                                                            <th>Quant. Programa</th>
                                                            <th>Quant. Usuários</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody> 

<?php foreach ($grupo as $valor): ?>
                                                            <tr>
                                                                <td><?php echo utf8_decode($valor['idLegGrupo']) ?></td>
                                                                <td><?php echo $valor['descAbrev'] ?></td>
                                                                <td><?php echo $valor['nomeGestor'] ?></td>
                                                                <td><span class="badge label-primary"><?php echo $valor['totalPro'] ?></span></td>
                                                                <td><span class="badge label-primary"><?php echo $valor['totalUsuario'] ?></span></td>
                                                            </tr>
<?php endforeach; ?>  

                                                    </tbody>
                                                </table></div></div></div>
                                </div><!--Fim Tabela 1 -->
                                <div role="tabpanel" class="tab-pane fade " id="tab_content2" aria-labelledby="profile-tab2"><!--Inicio Tabela 2 -->
                                    <!-- <div class="loadProgUsr">
                                        <center><img src="<?php echo URL ?>/assets/images/loader.gif"></center>
                                    </div> -->
                                    <br>
                                    <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                        <div class="row">
                                            <div class="col-sm-12"></div>                                            
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <table  class="tabelaProg table table-striped hover table-striped dt-responsive nowrap  no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info" style="width: 100%;" id="tableProgUsuario">
                                                    <thead>
                                                        <tr role="row">
                                                            <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 71px;" aria-sort="ascending" aria-label="First name: activate to sort column descending">Grupos</th>
<!--                                                            <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 70px;" aria-label="Last name: activate to sort column ascending">Descrição</th>-->
                                                            <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 70px;" aria-label="Last name: activate to sort column ascending">Programa</th>
                                                            <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 70px;" aria-label="Last name: activate to sort column ascending">Descrição</th>
                                                            <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 70px;" aria-label="Last name: activate to sort column ascending">Observacao</th>
                                                            <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 70px;" aria-label="Last name: activate to sort column ascending">Rotina</th>
                                                            <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 70px;" aria-label="Last name: activate to sort column ascending">Específico</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div><!--FIM Tabela 2 -->
                                <div role="tabpanel" class="tab-pane fade " id="tab_content3" aria-labelledby="profile-tab3"><!--Inicio Tabela 3 -->
                                    <div class="loadProgDuplicadoUsr">
                                        <center><img src="<?php echo URL ?>/assets/images/loader.gif"></center>
                                    </div>
                                    <br>
                                    <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer"><div class="row"><div class="col-sm-6"></div><div class="col-sm-6"></div></div><div class="row"><div class="col-sm-12">
                                                <table  class="tabelaProgDuplicado table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info" style="width: 100%;" id="taleProgDuplicado" >
                                                    <thead>
                                                        <tr role="row">
                                                            <th>Programa</th>                            
                                                            <th>Descrição</th>
                                                            <th>Rotina</th>
                                                            <th>Específico</th>
                                                            <th>Grupos</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="carregaProDuplicadogUsr"> 



                                                    </tbody>
                                                </table></div></div></div>
                                </div><!--Inicio Tabela 3-->
                                <div role="tabpanel" class="tab-pane fade " id="tab_content4" aria-labelledby="profile-tab4"><!--Inicio Tabela 4 -->
                                    <div class="loadMatriz">
                                        <center><img src="<?php echo URL ?>/assets/images/loader.gif"></center>
                                    </div>
                                    <input type="text" name="controlaMatriz" id="controlaMatriz" class="hide">
                                    <br>
                                    <div id="carregaMatiz"></div>


                                </div><!--Fim Tabela 1 -->

                                <div role="tabpanel" class="tab-pane fade " id="tab_content5" aria-labelledby="profile-tab5"><!--Inicio Tabela 5 -->
                                    <div class="loadMatrizProcesso">
                                        <center><img src="<?php echo URL ?>/assets/images/loader.gif"></center>
                                    </div>
                                    <input type="text" name="controlaProcessoMatriz" id="controlaProcessoMatriz" class="hide">
                                    <br>
                                    <div id="carregaProcessoMatiz"></div>


                                </div><!--Fim Tabela 5 -->

                                <div role="tabpanel" class="tab-pane fade " id="tab_content6" aria-labelledby="profile-tab6"><!--Inicio Tabela 6 -->
                                    <br>
                                    <div class="loadModulo">
                                        <center><img src="<?php echo URL ?>/assets/images/loader.gif"></center>
                                    </div>
                                    <input type="text" name="controlaModulo" id="controlaModulo" class="hide">
                                    <div id="carregaModulo"></div>  

                                </div><!--Fim Tabela 6 -->
                            </div>
                        </div>
                    </div><!--Fim Tabela 1 -->

                    <div role="tabpanel" class="tab-pane fade " id="tab_content30" aria-labelledby="profile-tab30"><!--Inicio Tabela 2 -->
                        <div class="" role="tabpanel" data-example-id="togglable-tabs">
                            <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                <li role="presentation" class="active2"><a href="#tab_content40" id="profile-tab40" role="tab" data-toggle="tab" aria-expanded="true">Grupos <span class="badge"><?php echo $totalAcesso2['numGrupos'] ?></a>
                                </li>
                                <li role="presentation" class=""><a href="#tab_content50" role="tab" id="profile-tab50" data-toggle="tab" aria-expanded="false">Programas <span class="badge"><?php echo $totalAcesso2['numProgs'] ?></span></a>
                                </li>
                                <li role="presentation" class=""><a href="#tab_content60" role="tab" id="profile-tab60" data-toggle="tab" aria-expanded="false">Prog. Duplicados <span class="badge"><?php echo $totalAcesso2['nroProgDup'] ?></span></a>
                                </li>
                                <li role="presentation" class=""><a href="#tab_content70" role="tab" id="profile-tab70" data-toggle="tab" aria-expanded="false">Matriz de Risco <span class="badge"><?php echo $totalAcesso2['nroRiscos'] ?></span></a>
                                </li>
                                <li role="presentation" class=""><a href="#tab_content80" role="tab" id="profile-tab80" data-toggle="tab" aria-expanded="false">Processos <span class="badge"><?php echo $totalAcesso2['numProcess'] ?></span></a>
                                </li>
                                <li role="presentation" class=""><a href="#tab_content90" role="tab" id="profile-ta90" data-toggle="tab" aria-expanded="false">Acesso a Módulos <span class="badge"><?php echo $totalAcesso2['numModulos'] ?></span></a>
                                </li>   
                            </ul>
                            <div id="myTabContent" class="tab-content">
                                <div role="tabpanel" class="tab-pane fade active2 in" id="tab_content40" aria-labelledby="profile-tab40"><!--Inicio Tabela 1 -->
                                    <br>
                                    <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer"><div class="row"><div class="col-sm-6"></div><div class="col-sm-6"></div></div><div class="row"><div class="col-sm-12">
                                                <table  class="tabela table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info" style="width: 100%;" id="tableGrupoUsuario2">
                                                    <thead>
                                                        <tr>
                                                            <th>Id Grupo</th>
                                                            <th>Descrição</th>
                                                            <th>Gestor</th>
                                                            <th>Quant. Programa</th>
                                                            <th>Quant. Usuários</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody> 

<?php foreach ($grupo2 as $valor): ?>
                                                            <tr>
                                                                <td><?php echo utf8_decode($valor['idLegGrupo']) ?></td>
                                                                <td><?php echo $valor['descAbrev'] ?></td>
                                                                <td><?php echo $valor['nomeGestor'] ?></td>
                                                                <td><span class="badge label-primary"><?php echo $valor['totalPro'] ?></span></td>
                                                                <td><span class="badge label-primary"><?php echo $valor['totalUsuario'] ?></span></td>
                                                            </tr>
<?php endforeach; ?>  

                                                    </tbody>
                                                </table></div></div></div>
                                </div><!--Fim Tabela 1 -->
                                <div role="tabpanel" class="tab-pane fade " id="tab_content50" aria-labelledby="profile-tab50"><!--Inicio Tabela 2 -->
                                    <!-- <div class="loadProgUsr2">
                                        <center><img src="<?php echo URL ?>/assets/images/loader.gif"></center>
                                    </div> -->
                                    <br>
                                    <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                        <div class="row">
                                            <div class="col-sm-6"></div>
                                            <div class="col-sm-6"></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <table  class="tabelaProg2 table table-striped hover table-striped dt-responsive nowrap  no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info" style="width: 100%;" id="tableProgUsuario2">
                                                    <thead>
                                                        <tr role="row">
                                                            <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 71px;" aria-sort="ascending" aria-label="First name: activate to sort column descending">Grupos</th>
<!--                                                            <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 70px;" aria-label="Last name: activate to sort column ascending">Descrição</th>-->
                                                            <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 70px;" aria-label="Last name: activate to sort column ascending">Programa</th>
                                                            <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 70px;" aria-label="Last name: activate to sort column ascending">Descrição</th>
                                                            <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 70px;" aria-label="Last name: activate to sort column ascending">Observacao</th>
                                                            <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 70px;" aria-label="Last name: activate to sort column ascending">Rotina</th>
                                                            <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 70px;" aria-label="Last name: activate to sort column ascending">Específico</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div><!--FIM Tabela 2 -->
                                <div role="tabpanel" class="tab-pane fade " id="tab_content60" aria-labelledby="profile-tab60"><!--Inicio Tabela 3 -->
                                    <div class="loadProgDuplicadoUsr2">
                                        <center><img src="<?php echo URL ?>/assets/images/loader.gif"></center>
                                    </div>
                                    <br>
                                    <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer"><div class="row"><div class="col-sm-6"></div><div class="col-sm-6"></div></div><div class="row"><div class="col-sm-12">
                                                <table  class="tabelaProgDuplicado2 table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info" style="width: 100%;" id="taleProgDuplicado2" >
                                                    <thead>
                                                        <tr role="row">
                                                            <th>Programa</th>                            
                                                            <th>Descrição</th>
                                                            <th>Rotina</th>
                                                            <th>Específico</th>
                                                            <th>Grupos</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="carregaProDuplicadogUsr2"> 
                                                    </tbody>
                                                </table></div></div></div>
                                </div><!--Inicio Tabela 3-->
                                <div role="tabpanel" class="tab-pane fade " id="tab_content70" aria-labelledby="profile-tab70"><!--Inicio Tabela 4 -->
                                    <div class="loadMatriz2">
                                        <center><img src="<?php echo URL ?>/assets/images/loader.gif"></center>
                                    </div>
                                    <input type="text" name="controlaMatriz2" id="controlaMatriz2" class="hide">
                                    <br>
                                    <div id="carregaMatiz2"></div>


                                </div><!--Fim Tabela 1 -->

                                <div role="tabpanel" class="tab-pane fade " id="tab_content80" aria-labelledby="profile-tab80"><!--Inicio Tabela 5 -->
                                    <div class="loadMatrizProcesso2">
                                        <center><img src="<?php echo URL ?>/assets/images/loader.gif"></center>
                                    </div>
                                    <input type="text" name="controlaProcessoMatriz2" id="controlaProcessoMatriz2" class="hide">
                                    <br>
                                    <div id="carregaProcessoMatiz2"></div>


                                </div><!--Fim Tabela 5 -->

                                <div role="tabpanel" class="tab-pane fade " id="tab_content90" aria-labelledby="profile-tab90"><!--Inicio Tabela 6 -->
                                    <br>
                                    <div class="loadModulo2">
                                        <center><img src="<?php echo URL ?>/assets/images/loader.gif"></center>
                                    </div>
                                    <input type="text" name="controlaModulo2" id="controlaModulo2" class="hide">
                                    <div id="carregaModulo2"></div>  

                                </div><!--Fim Tabela 6 -->
                            </div>
                        </div>
                    </div><!--FIM Tabela 2 -->

                    <div role="tabpanel" class="tab-pane fade " id="tab_content100" aria-labelledby="profile-tab100"><!--Inicio Tabela 2 -->
                        <div class="" role="tabpanel" data-example-id="togglable-tabs">
                            <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                <li role="presentation" class=""><a href="#tab_content110" id="profile-tab110" role="tab" data-toggle="tab" aria-expanded="true">Campos Pessoais <span class="badge totalPessoais"></span></a>
                                </li>
                                <li role="presentation" class=""><a href="#tab_content120" role="tab" id="profile-tab120" data-toggle="tab" aria-expanded="false">Campos Sensiveis <span class="badge totalSensiveis"></span></a>
                                </li>
                                <li role="presentation" class=""><a href="#tab_content130" role="tab" id="profile-tab130" data-toggle="tab" aria-expanded="false">Campos Anonizados <span class="badge totalAnonizados"></span></a>
                                </li>                               
                            </ul>
                            <div id="myTabContent" class="tab-content">
                            <div role="tabpanel" class="tab-pane fade" id="tab_content110" aria-labelledby="profile-tab110"><!--Inicio Tabela 1 -->
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="panel panel-default">
                                                            <!--<div class="panel-heading" style="background-color:#2A3F54;color: #FFFFFF"><h4>Atividades</h4></div>-->
                                                            <div class="panel-body">
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
                                                                                   style="width: 100%;" id="tableAbaPessoais">
                                                                                <thead>
                                                                                <tr role="row">                                                                                    
                                                                                    <th width="20%">Grupos</th>
                                                                                    <th width="10%">Cód. Programa</th>
                                                                                    <th width="30%">Descrição</th>
                                                                                    <th width="10%">Campo</th>
                                                                                    <th width="10%">Anonizado</th>
                                                                                </tr>
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
                                            </div>
                                            <div role="tabpanel" class="tab-pane fade" id="tab_content120" aria-labelledby="profile-tab120"><!--Inicio Tabela 1 -->
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="panel panel-default">
                                                            <!--<div class="panel-heading" style="background-color:#2A3F54;color: #FFFFFF"><h4>Atividades</h4></div>-->
                                                            <div class="panel-body">
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
                                                                                   style="width: 100%;" id="tableAbaSensiveis">
                                                                                <thead>
                                                                                <tr role="row">                                                                                    
                                                                                    <th width="20%">Grupos</th>
                                                                                    <th width="10%">Cód. Programa</th>
                                                                                    <th width="30%">Descrição</th>
                                                                                    <th width="10%">Campo</th>
                                                                                    <th width="10%">Anonizado</th>
                                                                                </tr>
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
                                            </div>
                                            <div role="tabpanel" class="tab-pane fade" id="tab_content130" aria-labelledby="profile-tab130"><!--Inicio Tabela 1 -->
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="panel panel-default">
                                                            <!--<div class="panel-heading" style="background-color:#2A3F54;color: #FFFFFF"><h4>Atividades</h4></div>-->
                                                            <div class="panel-body">
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
                                                                                   style="width: 100%;" id="tableAbaAnonizados">
                                                                                <thead>
                                                                                <tr role="row">                                                                                    
                                                                                    <th width="20%">Grupos</th>
                                                                                    <th width="10%">Cód. Programa</th>
                                                                                    <th width="30%">Descrição</th>
                                                                                    <th width="10%">Campo</th>
                                                                                    <th width="10%">Pessoal</th>
                                                                                    <th width="10%">Sensivel</th>
                                                                                </tr>
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
                                            </div>
                                <div role="tabpanel" class="tab-pane fade " id="tab_content70" aria-labelledby="profile-tab70"><!--Inicio Tabela 4 -->
                                    <div class="loadMatriz2">
                                        <center><img src="<?php echo URL ?>/assets/images/loader.gif"></center>
                                    </div>
                                    <input type="text" name="controlaMatriz2" id="controlaMatriz2" class="hide">
                                    <br>
                                    <div id="carregaMatiz2"></div>


                                </div><!--Fim Tabela 1 -->

                                <div role="tabpanel" class="tab-pane fade " id="tab_content80" aria-labelledby="profile-tab80"><!--Inicio Tabela 5 -->
                                    <div class="loadMatrizProcesso2">
                                        <center><img src="<?php echo URL ?>/assets/images/loader.gif"></center>
                                    </div>
                                    <input type="text" name="controlaProcessoMatriz2" id="controlaProcessoMatriz2" class="hide">
                                    <br>
                                    <div id="carregaProcessoMatiz2"></div>


                                </div><!--Fim Tabela 5 -->

                                <div role="tabpanel" class="tab-pane fade " id="tab_content90" aria-labelledby="profile-tab90"><!--Inicio Tabela 6 -->
                                    <br>
                                    <div class="loadModulo2">
                                        <center><img src="<?php echo URL ?>/assets/images/loader.gif"></center>
                                    </div>
                                    <input type="text" name="controlaModulo2" id="controlaModulo2" class="hide">
                                    <div id="carregaModulo2"></div>  

                                </div><!--Fim Tabela 6 -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div><!--Fim x_panel-->
</div>


<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span id="modalTopo"></span></h4>
            </div>
            <div class="modal-body">

                <table  class="tabela2 table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Programa</th>
                        </tr>
                    </thead>
                    <tbody id="modalApresentaPrograma"> 
                    </tbody>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>



<div role="tabpanel" class="tab-pane fade " id="tab_content30" aria-labelledby="profile-tab30"><!--Inicio Tabela 2 -->
    <div class="" role="tabpanel" data-example-id="togglable-tabs">
        <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
            <li role="presentation" class="active2"><a href="#tab_content40" id="profile-tab40" role="tab" data-toggle="tab" aria-expanded="true">Grupos <span class="badge"><?php echo $totalAcesso2['numGrupos'] ?></a>
            </li>
            <li role="presentation" class=""><a href="#tab_content50" role="tab" id="profile-tab50" data-toggle="tab" aria-expanded="false">Programas <span class="badge"><?php echo $totalAcesso2['numProgs'] ?></span></a>
            </li>
            <li role="presentation" class=""><a href="#tab_content60" role="tab" id="profile-tab60" data-toggle="tab" aria-expanded="false">Prog. Duplicados <span class="badge"><?php echo $totalAcesso2['nroProgDup'] ?></span></a>
            </li>
            <li role="presentation" class=""><a href="#tab_content70" role="tab" id="profile-tab70" data-toggle="tab" aria-expanded="false">Matriz de Risco <span class="badge"><?php echo $totalAcesso2['nroRiscos'] ?></span></a>
            </li>
            <li role="presentation" class=""><a href="#tab_content80" role="tab" id="profile-tab80" data-toggle="tab" aria-expanded="false">Processos <span class="badge"><?php echo $totalAcesso2['numProcess'] ?></span></a>
            </li>
            <li role="presentation" class=""><a href="#tab_content90" role="tab" id="profile-ta90" data-toggle="tab" aria-expanded="false">Acesso a Módulos <span class="badge"><?php echo $totalAcesso2['numModulos'] ?></span></a>
            </li>
        </ul>

    </div>
</div>

<script>
    $(document).ready(function(){     

        function AtualizaBadge(){
            $.ajax({
                type: "POST",
                url: url+"Usuario/AjaxCarregaBadges",
                data:{
                    empresa: $('#instancia').val(),
                    id: $('#idUsuario').val()
                },success: function(res){
                    var dados=JSON.parse(res);
                    $(".totalPessoais").text(dados.totalCamposPessoais);
                    $(".totalSensiveis").text(dados.totalCamposSensiveis);
                    $(".totalAnonizados").text(dados.totalCamposAnonizados);

                }
            })
        }   
    AtualizaBadge();
        
        // Carrega datatable com programas de cada grupo selecionado
        /*table = */$('#tableProgUsuario2').DataTable({
            //"sorting": [[3,'asc']],
            "processing": false,
            "serverSide": true,
            "oLanguage": {
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "Nenhum registro encontrado",
                //"sInfo": "Exibindo de _START_ ate _END_ de _TOTAL_ registros",
                "sInfo": "Página _PAGE_ de _PAGES_",
                "sInfoEmpty": "Nenhum registro para ser exibido",
                //"sInfoFiltered": "(Filtrado de _MAX_ registros no total)",
                "sInfoFiltered": "",
                "sSearch": "Pesquisar:",
                "oPaginate": {
                    "sFirst": "Primeiro",
                    "sLast": "Último",
                    "sNext": "Proximo",
                    "sPrevious": "Anterior"
                },
                "sLoadingRecords": "&nbsp;",
                "sProcessing": '<div class="box-loading-datatable"><div class="spinner pull-left"><img src="'+url+'/assets/images/loader.gif"></div><div class="pull-left" style="margin-top: -10px;">Carregando...</div></div>'
            },
            "ajax": {                            
                type: "POST",
                url: url+"Usuario/ajaxDatatableProgUrsFoto",
                "data": function (d){
                    d.empresa = $('#instancia').val();
                    d.id = $('#idUsuario').val();
                }                    
            }
        });

        //Ajax que carrega os progrmas da tela de usuario http://162.144.118.90/sga/usuario/dados_usuario /    
        // $("#profile-tab50").click(function(){
        //     var prog = $("#tableProgUsuario2 tbody tr").length;
        //     var url_atual = window.location.href;

        //     url_atual = url_atual.split("/");

        //     //var id = url_atual[6];
        //     var id = $('#idUsuario').val();
        //     if(prog == 0 ){
                
        //         table = $('.tabelaProg2').DataTable();
        //         table.destroy();

        //         $.ajax({
        //             type: "POST",
        //             url: url+"usuario/ajaxCarregaProgUrsFoto",
        //             data:'id='+id,
        //             beforeSend: function(){
        //                 $("#tableProgUsuario2").hide(); 
                        
        //             },
        //             success: function(data){
        //                 $("#tableProgUsuario2").show(); 
        //                 $(".loadProgUsr2").hide();
        //                 $("#carregaProgUsr2").html(data);
        //                 console.log("Retorno "+data);
                        
        //                 $('.tabelaProg2').DataTable({
        //                     "language": {
        //                     "lengthMenu": "Exibição _MENU_ Registros por página",
        //                     "zeroRecords": "Registro nao encontrado",
        //                     "info": "Pagina _PAGE_ de _PAGES_",
        //                     "infoEmpty": "No records available",
        //                     "search":"Pesquisar:",
        //                     "paginate": {
        //                         "first":      "Primeiro",
        //                         "last":       "Último",
        //                         "next":       "Próximo",
        //                         "previous":   "Anterior"
        //                         },
        //                         "infoFiltered": "(Filtro de _MAX_ registro total)"
        //                     }
        //                 });    
        //             }
        //         });

        //     }
        // });

        //Ajax que carrega os programas Duplicados da tela de usuario http://162.144.118.90/sga/usuario/dados_usuario /
        $("#profile-tab60").click(function(){

            var progDuplicado = $("#taleProgDuplicado2 tbody tr").length;
            var url_atual = window.location.href;

            url_atual = url_atual.split("/");

            //var id = url_atual[6];
            var id = $('#idUsuario').val();

            if(progDuplicado == 0 ){
                table = $('.tabelaProgDuplicado2').DataTable();
                table.destroy();

                $.ajax({
                    type: "POST",
                    url: url+"Usuario/ajaxCarregaProgDuplicadoUrsFoto",
                    data:'id='+id,
                    beforeSend: function(){
                        $("#taleProgDuplicado2").hide(); 
                        
                    },
                    success: function(data){
                        
                        $("#taleProgDuplicado2").show(); 
                        $(".loadProgDuplicadoUsr2").hide();
                        
                        $("#carregaProDuplicadogUsr2").html(data);
                        
                        $('.tabelaProgDuplicado2').DataTable({
                            "language": {
                            "lengthMenu": "Exibição _MENU_ Registros por página",
                            "zeroRecords": "Registro nao encontrado",
                            "info": "Pagina _PAGE_ de _PAGES_",
                            "infoEmpty": "No records available",
                            "search":"Pesquisar:",
                            "paginate": {
                                "first":      "Primeiro",
                                "last":       "Último",
                                "next":       "Próximo",
                                "previous":   "Anterior"
                                },
                                "infoFiltered": "(Filtro de _MAX_ registro total)"
                            }
                        });    
                    }
                });

            }
        });

        //Ajax que carrega Matriz de Risco da tela de usuario http://162.144.118.90/sga/usuario/dados_usuario /
        $("#profile-tab70").click(function(){

            var url_atual = window.location.href;

            url_atual = url_atual.split("/");
            //var id = url_atual[6];
            var id = $('#idUsuario').val();

            if($("#controlaMatriz2").val() == ""){
                $.ajax({
                    type: "POST",
                    url: url+"Usuario/ajaxMatrizDeRiscoFoto",
                    data:'id='+id,
                    beforeSend: function(){
                    
                        
                    },
                    success: function(data){
                        $(".loadMatriz2").hide();
                        $("#carregaMatiz2").html(data);
                        $("#controlaMatriz2").val("OK");
                        
                    }
                });

            }
        });    

        //Ajax que carrega Processo Matriz de Risco da tela de usuario http://162.144.118.90/sga/usuario/dados_usuario /
        $("#profile-tab80").click(function(){

            var url_atual = window.location.href;

            url_atual = url_atual.split("/");
            //var id = url_atual[6];
            var id = $('#idUsuario').val();

            if($("#controlaProcessoMatriz2").val() == ""){
                $.ajax({
                    type: "POST",
                    url: url+"Usuario/ajaxMatrizProcesDeRiscoFoto",
                    data:'id='+id,
                    beforeSend: function(){
                    
                        
                    },
                    success: function(data){
                        $(".loadMatrizProcesso2").hide();
                        $("#carregaProcessoMatiz2").html(data);
                        $("#controlaProcessoMatriz2").val("OK");

                        $('.tabelaMatrizProcesso').DataTable({
                            "language": {
                            "lengthMenu": "Exibição _MENU_ Registros por página",
                            "zeroRecords": "Registro nao encontrado",
                            "info": "Pagina _PAGE_ de _PAGES_",
                            "infoEmpty": "No records available",
                            "search":"Pesquisar:",
                            "paginate": {
                                "first":      "Primeiro",
                                "last":       "Último",
                                "next":       "Próximo",
                                "previous":   "Anterior"
                                },
                                "infoFiltered": "(Filtro de _MAX_ registro total)"
                            }
                        });
                        
                    }
                });
            }
        });

        //Ajax que carrega Processo Matriz de Risco da tela de usuario http://162.144.118.90/sga/usuario/dados_usuario /
        $("#profile-ta90").click(function(){
            var url_atual = window.location.href;

            url_atual = url_atual.split("/");
            //var id = url_atual[6];
            var id = $('#idUsuario').val();

            if($("#controlaModulo2").val() == ""){
                $.ajax({
                    type: "POST",
                    url: url+"Usuario/ajaxAcessoModulosFoto",
                    data:'id='+id,
                    beforeSend: function(){
                    
                        
                    },
                    success: function(data){
                        $(".loadModulo2").hide();
                        $("#carregaModulo2").html(data);
                        $("#controlaModulo2").val("OK");

                    
                        
                    }
                });
            }
        });


        // Cria instancia do datatable para poder recarregar o mesmo depois
        // Carrega datatable com programas de cada grupo selecionado
        /*table = */
        $('#tableProgUsuario').DataTable({
            //"sorting": [[3,'asc']],
            "processing": false,
            "serverSide": true,
            "oLanguage": {
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "Nenhum registro encontrado",
                //"sInfo": "Exibindo de _START_ ate _END_ de _TOTAL_ registros",
                "sInfo": "Página _PAGE_ de _PAGES_",
                "sInfoEmpty": "Nenhum registro para ser exibido",
                //"sInfoFiltered": "(Filtrado de _MAX_ registros no total)",
                "sInfoFiltered": "",
                "sSearch": "Pesquisar:",
                "oPaginate": {
                    "sFirst": "Primeiro",
                    "sLast": "Último",
                    "sNext": "Proximo",
                    "sPrevious": "Anterior"
                },
                "sLoadingRecords": "&nbsp;",
                "sProcessing": '<div class="box-loading-datatable"><div class="spinner pull-left"><img src="'+url+'/assets/images/loader.gif"></div><div class="pull-left" style="margin-top: -10px;">Carregando...</div></div>'
            },
            "ajax": {                            
                type: "POST",
                url: url+"Usuario/ajaxDatatableProgUrs",
                "data": function (d){
                    d.empresa = $('#instancia').val();
                    d.id = $('#idUsuario').val();
                } 
            }
        });  

    table2 = $('#tableAbaPessoais').DataTable({
            //"sorting": [[3,'asc']],
            "processing": true,
            "serverSide": true,
            "oLanguage": {
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "Nenhum registro encontrado",
                //"sInfo": "Exibindo de _START_ ate _END_ de _TOTAL_ registros",
                "sInfo": "Página _PAGE_ de _PAGES_",
                "sInfoEmpty": "Nenhum registro para ser exibido",
                //"sInfoFiltered": "(Filtrado de _MAX_ registros no total)",
                "sInfoFiltered": "",
                "sSearch": "Pesquisar:",
                "oPaginate": {
                    "sFirst": "Primeiro",
                    "sLast": "Último",
                    "sNext": "Proximo",
                    "sPrevious": "Anterior"
                },
                "sLoadingRecords": "&nbsp;",
                "sProcessing": '<div class="box-loading-datatable"><div class="spinner pull-left"><img src="'+url+'/assets/images/loader.gif"></div><div class="pull-left" style="margin-top: -10px;">Carregando...</div></div>'
            },
            "ajax": {                            
                type: "POST",
                url: url + "Usuario/ajaxCarregaAbaPessoais",
                "data": function (d){    
                    d.empresa = $('#instancia').val();
                    d.id = $('#idUsuario').val(); 
                }                    
            }
        });

        table3 = $('#tableAbaSensiveis').DataTable({
            //"sorting": [[3,'asc']],
            "processing": false,
            "serverSide": true,
            "oLanguage": {
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "Nenhum registro encontrado",
                //"sInfo": "Exibindo de _START_ ate _END_ de _TOTAL_ registros",
                "sInfo": "Página _PAGE_ de _PAGES_",
                "sInfoEmpty": "Nenhum registro para ser exibido",
                //"sInfoFiltered": "(Filtrado de _MAX_ registros no total)",
                "sInfoFiltered": "",
                "sSearch": "Pesquisar:",
                "oPaginate": {
                    "sFirst": "Primeiro",
                    "sLast": "Último",
                    "sNext": "Proximo",
                    "sPrevious": "Anterior"
                },
                "sLoadingRecords": "&nbsp;",
                "sProcessing": '<div class="box-loading-datatable"><div class="spinner pull-left"><img src="'+url+'/assets/images/loader.gif"></div><div class="pull-left" style="margin-top: -10px;">Carregando...</div></div>'
            },
            "ajax": {                            
                type: "POST",
                url: url+"Usuario/ajaxCarregaAbaSensiveis",
                "data": function (d){
                    d.empresa = $('#instancia').val();
                    d.id = $('#idUsuario').val();
                }                    
            }
        });  
        
        table4 =  $('#tableAbaAnonizados').DataTable({
            //"sorting": [[3,'asc']],
            "processing": false,
            "serverSide": true,
            "oLanguage": {
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "Nenhum registro encontrado",
                //"sInfo": "Exibindo de _START_ ate _END_ de _TOTAL_ registros",
                "sInfo": "Página _PAGE_ de _PAGES_",
                "sInfoEmpty": "Nenhum registro para ser exibido",
                //"sInfoFiltered": "(Filtrado de _MAX_ registros no total)",
                "sInfoFiltered": "",
                "sSearch": "Pesquisar:",
                "oPaginate": {
                    "sFirst": "Primeiro",
                    "sLast": "Último",
                    "sNext": "Proximo",
                    "sPrevious": "Anterior"
                },
                "sLoadingRecords": "&nbsp;",
                "sProcessing": '<div class="box-loading-datatable"><div class="spinner pull-left"><img src="'+url+'/assets/images/loader.gif"></div><div class="pull-left" style="margin-top: -10px;">Carregando...</div></div>'
            },
            "ajax": {                            
                type: "POST",
                url: url+"Usuario/ajaxCarregaAbaAnonizados",
                "data": function (d){
                    d.empresa = $('#instancia').val();
                    d.id = $('#idUsuario').val();
                }                    
            }
        });  
       
        //Ajax que carrega os progrmas da tela de usuario http://162.144.118.90/sga/usuario/dados_usuario /
        // $("#profile-tab2").click(function(){

        //     var prog = $("#tableProgUsuario tbody tr").length;
        //     var url_atual = window.location.href;

        //     url_atual = url_atual.split("/");

        //     //var id = url_atual[5];
        //     var id = $('#idUsuario').val();
        //     if(prog == 0 ){
                
        //         table = $('.tabelaProg').DataTable();
        //         table.destroy();

        //         $.ajax({
        //             type: "POST",
        //             //url: url+"usuario/ajaxCarregaProgUrs",
        //             url: url+"usuario/ajaxDatatableProgUrs",
        //             data:'id='+id,
        //             beforeSend: function(){
        //                 $("#tableProgUsuario").hide(); 
                        
        //             },
        //             success: function(data){
        //                 $("#tableProgUsuario").show(); 
        //                 $(".loadProgUsr").hide();
        //                 //$("#carregaProgUsr").html(data);
        //                 $('.tabelaProg').DataTable({
        //                     "language": {
        //                     "lengthMenu": "Exibição _MENU_ Registros por página",
        //                     "zeroRecords": "Registro nao encontrado",
        //                     "info": "Pagina _PAGE_ de _PAGES_",
        //                     "infoEmpty": "No records available",
        //                     "search":"Pesquisar:",
        //                     "paginate": {
        //                         "first":      "Primeiro",
        //                         "last":       "Último",
        //                         "next":       "Próximo",
        //                         "previous":   "Anterior"
        //                         },
        //                         "infoFiltered": "(Filtro de _MAX_ registro total)"
        //                     }
        //                 });    
        //             }
        //         });
        //     }
        // });

        //Ajax que carrega os programas Duplicados da tela de usuario http://162.144.118.90/sga/usuario/dados_usuario /
        $("#profile-tab3").click(function(){

            var progDuplicado = $("#taleProgDuplicado tbody tr").length;
            var url_atual = window.location.href;

            url_atual = url_atual.split("/");

            //var id = url_atual[6];
            var id = $('#idUsuario').val();

            if(progDuplicado == 0 ){
                table = $('.tabelaProgDuplicado').DataTable();
                table.destroy();

                $.ajax({
                    type: "POST",
                    url: url+"Usuario/ajaxCarregaProgDuplicadoUrs",
                    data:'id='+id,
                    beforeSend: function(){
                        $("#taleProgDuplicado").hide(); 
                        
                    },
                    success: function(data){
                        
                        $("#taleProgDuplicado").show(); 
                        $(".loadProgDuplicadoUsr").hide();
                        
                        $("#carregaProDuplicadogUsr").html(data);
                        
                        $('.tabelaProgDuplicado').DataTable({
                            "language": {
                            "lengthMenu": "Exibição _MENU_ Registros por página",
                            "zeroRecords": "Registro nao encontrado",
                            "info": "Pagina _PAGE_ de _PAGES_",
                            "infoEmpty": "No records available",
                            "search":"Pesquisar:",
                            "paginate": {
                                "first":      "Primeiro",
                                "last":       "Último",
                                "next":       "Próximo",
                                "previous":   "Anterior"
                                },
                                "infoFiltered": "(Filtro de _MAX_ registro total)"
                            }
                        });    
                    }
                });
            }
        });

        //Ajax que carrega Matriz de Risco da tela de usuario http://162.144.118.90/sga/usuario/dados_usuario /
        $("#profile-tab4").click(function(){

            var url_atual = window.location.href;

            url_atual = url_atual.split("/");
            //var id = url_atual[6];
            var id = $('#idUsuario').val();

            if($("#controlaMatriz").val() == ""){
                $.ajax({
                    type: "POST",
                    url: url+"Usuario/ajaxMatrizDeRisco",
                    data:'id='+id,
                    beforeSend: function(){
                    
                        
                    },
                    success: function(data){
                        $(".loadMatriz").hide();
                        $("#carregaMatiz").html(data);
                        $("#controlaMatriz").val("OK");
                        
                    }
                });
            }
        });

        //Ajax que carrega Processo Matriz de Risco da tela de usuario http://162.144.118.90/sga/usuario/dados_usuario /
        $("#profile-tab5").click(function(){

            var url_atual = window.location.href;

            url_atual = url_atual.split("/");
            //var id = url_atual[6];
            var id = $('#idUsuario').val();

            if($("#controlaProcessoMatriz").val() == ""){
                $.ajax({
                    type: "POST",
                    url: url+"Usuario/ajaxMatrizProcesDeRisco",
                    data:'id='+id,
                    beforeSend: function(){
                    
                        
                    },
                    success: function(data){
                        $(".loadMatrizProcesso").hide();
                        $("#carregaProcessoMatiz").html(data);
                        $("#controlaProcessoMatriz").val("OK");

                        $('.tabelaMatrizProcesso').DataTable({
                            "language": {
                            "lengthMenu": "Exibição _MENU_ Registros por página",
                            "zeroRecords": "Registro nao encontrado",
                            "info": "Pagina _PAGE_ de _PAGES_",
                            "infoEmpty": "No records available",
                            "search":"Pesquisar:",
                            "paginate": {
                                "first":      "Primeiro",
                                "last":       "Último",
                                "next":       "Próximo",
                                "previous":   "Anterior"
                                },
                                "infoFiltered": "(Filtro de _MAX_ registro total)"
                            }
                        });
                        
                    }
                });
            }
        });

        //Ajax que carrega Processo Matriz de Risco da tela de usuario http://162.144.118.90/sga/usuario/dados_usuario /
        $("#profile-ta6").click(function(){

            var url_atual = window.location.href;

            url_atual = url_atual.split("/");
            //var id = url_atual[6];
            var id = $('#idUsuario').val();

            if($("#controlaModulo").val() == ""){
                $.ajax({
                    type: "POST",
                    url: url+"Usuario/ajaxAcessoModulos",
                    data:'id='+id,
                    beforeSend: function(){
                    
                        
                    },
                    success: function(data){
                        $(".loadModulo").hide();
                        $("#carregaModulo").html(data);
                        $("#controlaModulo").val("OK");                                    
                    }
                });
            }
        });
    });
</script>