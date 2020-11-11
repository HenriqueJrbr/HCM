<style>        
    #prog-list{
        float: left;
        list-style: none;
        margin-top: 0;
        margin-left: 0;
        padding: 0;
        max-height: 340px;
        width: 96%;
        position: absolute;
        z-index: 999;
        overflow: auto;
    }   

    #prog-list li{
        padding: 10px;
        background: #f0f0f0;
        border-bottom: #e6e6e6 1px solid;
        /*border-radius: 5px;*/
        border-left: #e6e6e6 1px solid;
        border-right: #e6e6e6 1px solid;
    }

    #prog-list li:hover {
        background: #ece3d2;
        cursor: pointer;
    }

    .progsAdicionados{
        list-style: none;
        margin-left: 0;
        padding-left: 0;
    }

    .progsAdicionados li{
        border-bottom: solid 1px #e6e6e6;
        padding: 6px;
    }

    .progsAdicionados li .fa-remove{
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

    
    .hide-div-select{
        display: none;
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
    #addProg{
        margin-top:23px
    }
    
    .manterStatus {
        margin-top: -10px;
    }
</style>
<div class="col-md-12 col-sm-12 col-xs-12">
    <ol class="breadcrumb">
        <li>
            <a href="<?php echo URL ?>">
                <font style="vertical-align: inherit;" onclick="loadingPagia()">
                <font style="vertical-align: inherit;">Dashboard</font></font>
            </a>
        </li>        
        <li class="active">Solicitação de Acesso Por Função</li>
    </ol>
</div>

<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Solicitação de Acesso Por Função</h2>
            <div class="clearfix"></div>
        </div>        
        <div class="x_content">
            <form action="<?= URL; ?>/SolicitacaoAcessoFuncao/criaSolicitacaoAcesso" id="formSolicitacaoAcesso" method="post">
                <input type="hidden" name="idFluxo" value="<?php echo $idFluxo; ?>">                
                <input type="hidden" name="idEmpresa" value="<?php echo $_SESSION['empresaid']; ?>">
                <div class="row">
                    <div class="col-md-12"><?php echo $this->helper->alertMessage(); ?></div>
                </div>
                <div class="row">                    
                    <div class="col-md-9">
                        <strong>Data Solicitação:</strong> <?php echo date('d/m/Y'); ?>
                    </div>
                    <div class="col-md-3">
                        <input type="hidden" name="idSolicitante" value="<?php echo $_SESSION['idUsrTotvs']; ?>">
                        <strong>Solicitante:</strong> <?php echo $_SESSION['nomeUsuario']; ?>
                    </div>
                    <hr>
                </div>
                <div class="clearfix"></div>
                                
                <div class="row">
                    <div class="col-md-4">
                        <label>Usuário:</label>
                        <input type="hidden" id="idUsuarioHidden" value="<?php echo $_SESSION['idUsrTotvs']; ?>">
                        <input type="hidden" id="idUsuarioFuncHidden" value="">                        
                        <select name="idUsuario" class="form-control select2" id="idUsuario">
                            <option value="" selected></option>
                            <?php foreach($usuarios as $val): ?>
                            <option value="<?php echo $val['idUsuario']; ?>"><?php echo $val['nome_usuario']/* . ' - ' . $val['funcao']*/ ;?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">&nbsp;</div>
                    <div class="col-md-4">
                        <label>Funções:</label>
                        <input type="hidden" id="idFuncHidden" class="form-control" autocomplete="off">
                        <select name="idFuncao" class="form-control select2" id="idFuncao">                            
                            <option value="" selected></option>
                            <?php foreach($funcoes as $val): ?>
                            <option value="<?php echo $val['idFuncao']; ?>" ><?php echo $val['descricao'] ;?></option>
                            <?php endforeach; ?>
                        </select>

                    </div>
                    <hr>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <strong><u>Recebimento de e-mails</u></strong>
                    </div>
                    <div class="col-md-8">&nbsp;</div>
                </div>
                <br>
                <div class="row">                                            
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Usuários em cópia:</label>
                            <select class="form-control select2" name="idUsuarioAcompanhante[]" id="idUsuarioAcompanhante" >
                                <option value="">Nenhum</option>
                                <?php foreach($acompanhante as $val): ?>
                                    <option value="<?php echo $val['z_sga_usuarios_id']; ?>"><?php echo $val['nome_usuario'] ;?></option>
                                <?php endforeach; ?>
                            </select>                        
                         </div>
                    </div>

                    <div class="col-md-5">
                        <label>Como o(s) usuário(s) em cópia recebe(m) os e-mails:</label>
                        <select class="form-control select2" name="tipoEnvioMailCopia" id="tipoEnvioMailCopia">                            
                            <option value="fim" selected="selected">Ao encerrar solicitação</option>
                            <option value="tudo">Em cada movimentação</option>
                        </select>                        
                    </div>                                                                    
                </div>                               
                <hr>                               
                                
                
                <div class="row">
                    <div class="col-md-12">                                               
                        <h2>Grupos padrão da função</h2>                       
                        <div class="clearfix"></div>
                        <div id="datatable-responsive_wrapper"
                             class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                            <div class="row">
                                <div class="col-sm-6"></div>
                                <div class="col-sm-6"></div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="tabelaRevisao table table-striped table-bordered dataTable no-footer"
                                           cellspacing="0" width="100%" role="grid"
                                           aria-describedby="datatable-responsive_info"
                                           style="width: 100%;" id="">
                                        <thead>
                                        <tr role="row">                                                  
<!--                                            <th width="10%">Função</th>-->
                                            <th width="13%">Grupo</th>
                                            <th width="25%">Desc. Grupo</th>
                                            <th width="25%">Gestor Grupo</th>
                                            <th width="12%">Nr. Programas</th>
                                            <th width="9%">Nr. Usuários</th>
                                            <th width="9%">Já possui</th>
                                            <th width="9%">Adicionar</th>
                                        </tr>
                                        </thead>
                                        <tbody id="tableFuncaoGrupo">                                                                                                                              
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>                        
                    </div>
                </div>
                <hr>
                
                <div class="row">
                    <div class="col-md-12">                                               
                        <h4 style="float:right">Grupos não padrão da função <span class="red">(serão removidos)</span></h4>
                        <div class="clearfix"></div>
                        <div id="datatable-responsive_wrapper"
                             class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                            <div class="row">
                                <div class="col-sm-6"></div>
                                <div class="col-sm-6"></div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="tabelaRevisao table table-striped table-bordered dataTable no-footer"
                                           cellspacing="0" width="100%" role="grid"
                                           aria-describedby="datatable-responsive_info"
                                           style="width: 100%;" id="">
                                        <thead>
                                        <tr role="row">                                                  
<!--                                            <th width="10%">Função</th>-->
                                            <th width="13%">Grupo</th>
                                            <th width="25%">Desc. Grupo</th>
                                            <th width="25%">Gestor Grupo</th>
                                            <th width="12%">Nr. Programas</th>
                                            <th width="9%">Nr. Usuários</th>                                            
                                        </tr>
                                        </thead>
                                        <tbody id="tableGrupoPadraoForaFuncao">                                           
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>                        
                    </div>
                </div>
                <hr>
                
                <div class="clearfix"></div>
                <br><br>
                <div class="row">
                    <div class="col-md-3 pull-right">
                        <button type="button" class="btn btn-success pull-right" id="btnSalvarSolicAcesso" onclick="loadingPagia();">Salvar</button>
                        <button type="button" class="btn btn-danger pull-right" onclick="loadingPagia();">Voltar</button>                                        
                    </div>
                </div>           
                <div class="clearfix"></div>
                <br>
               
                <div class="row">
                    <div class="col-md-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Overview da solicitação</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <div role="tabpanel" class="tab-pane fade active in" ><!--Inicio Tabela 1 -->
                                    <div class="" role="tabpanel" data-example-id="togglable-tabs">
                                        <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                            <li role="presentation" class="active">
                                                <a href="#tab_content1" id="profile-tab1" role="tab" data-toggle="tab" aria-expanded="true">Riscos <span class="badge badge-danger" id="count-riscos"></span></a>
                                            </li>
                                            <li role="presentation">
                                                <a href="#tab_content2" id="profile-tabProg" role="tab" data-toggle="tab" aria-expanded="true">Programas <span class="badge totalProgByGrupo"></span></a>
                                            </li>
                                            <li role="presentation">
                                                <a href="#tab_content3" id="profile-tabProcess" role="tab" data-toggle="tab" aria-expanded="true">Processos <span class="badge totalProcess"></span></a>
                                            </li>
                                            <li role="presentation">
                                                <a href="#tab_content4" id="profile-tabCamposPessoais" role="tab" data-toggle="tab" aria-expanded="true">Campos Pessoais <span class="badge totalCamposPessoais"></span></a>
                                            </li>
                                            <li role="presentation">
                                                <a href="#tab_content5" id="profile-tabCamposSensiveis" role="tab" data-toggle="tab" aria-expanded="true">Campos Sensiveis <span class="badge totalCamposSensiveis"></span></a>
                                            </li>
                                            <li role="presentation">
                                                <a href="#tab_content6" id="profile-tabAnonizados" role="tab" data-toggle="tab" aria-expanded="true">Campos Anonizados <span class="badge totalCamposAnonizados"></span></a>
                                            </li>
                                        </ul>
                                        <div id="myTabContent" class="tab-content">
                                            <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="profile-tab1"><!--Inicio Tabela 1 -->
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="x_panel">
                                                            <div class="x_title">
                                                                <h2>Matriz de Riscos com base na função selecionada </h2>
                                                                <ul class="nav navbar-right panel_toolbox">
                                                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                                                </ul>
                                                                <div class="clearfix"></div>
                                                            </div>
                                                            <div class="x_content">
                                                                <div class="col-md-12">
                                                                    <h5 class="text-center hide" id="text-risco">Analisando riscos...</h5>
                                                                </div>
                                                                <div id="matriz-risco-grupos"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div role="tabpane1" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tabProg"><!--Inicio Tabela 1 -->
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
                                                                                   style="width: 100%;" id="tableAbaProgs">
                                                                                <thead>
                                                                                <tr role="row">                                                                                    
                                                                                    <th width="20%">Grupos</th>
                                                                                    <th width="10%">Cód. Programa</th>
                                                                                    <th>Descrição</th>
                                                                                    <th width="10%">Cód. Módulo</th>
                                                                                    <th>Descrição</th>
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
                                            <div role="tabpane1" class="tab-pane fade" id="tab_content3" aria-labelledby="profile-tabProcess"><!--Inicio Tabela 3 -->
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
                                                                                   style="width: 100%;" id="tableAbaProcess">
                                                                                <thead>
                                                                                <tr role="row">
                                                                                    <th width="20%">Descrição</th>
                                                                                    <th>Grupo processo</th>
                                                                                    <th>Cód. programa</th>
                                                                                    <th>Grupos</th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody id="tableAbaProcessBody"></tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div role="tabpane1" class="tab-pane fade" id="tab_content4" aria-labelledby="profile-tabCamposPessoais"><!--Inicio Tabela 1 -->
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
                                                                                   style="width: 100%;" id="tableAbaCamposPessoais">
                                                                                <thead>
                                                                                <tr role="row">                                                                                    
                                                                                    <th width="20%">Grupos</th>
                                                                                    <th width="10%">Cód. Programa</th>
                                                                                    <th>Descrição</th>
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
                                            <div role="tabpane1" class="tab-pane fade" id="tab_content5" aria-labelledby="profile-tabCamposSensiveis"><!--Inicio Tabela 1 -->
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
                                                                                   style="width: 100%;" id="tableAbaCamposSensiveis">
                                                                                <thead>
                                                                                <tr role="row">                                                                                    
                                                                                    <th width="20%">Grupos</th>
                                                                                    <th width="10%">Cód. Programa</th>
                                                                                    <th>Descrição</th>
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
                                            <div role="tabpane1" class="tab-pane fade" id="tab_content6" aria-labelledby="profile-tabCamposAnonizados"><!--Inicio Tabela 1 -->
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
                                                                                   style="width: 100%;" id="tableAbaCamposAnonizados">
                                                                                <thead>
                                                                                <tr role="row">                                                                                    
                                                                                    <th width="20%">Grupos</th>
                                                                                    <th width="10%">Cód. Programa</th>
                                                                                    <th>Descrição</th>
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
               
            </form>
        </div>
        <br>
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
    
    $(document).ready(function () {                                     
        // Submete o formulario
        $('#btnSalvarSolicAcesso').on('click', function(){
            // Valida se foi adicionado programa
            if($('#idUsuario').val() == '' || $('#idFuncao').val() == ''){
                $('#myModalResult .modal-body').html('<h5>Favor selecionar um usuário e uma função.</h5>');
                $("#myModalResult").modal('show');
                return false;
            }
            
            
            $.ajax({
                type: "POST",
                url: url + "SolicitacaoAcessoFuncao/ajaxValidaGrupoFuncao",
                data: {
                    idUsuario: $('#idUsuario').val()
                },
                beforeSend: function () {
                    $("#prog").css("background", "#FFF url(LoaderIcon.gif) no-repeat 165px");
                },
                success: function (data) {
                    $('#load').css('display', 'none');
                    var res = JSON.parse(data);                    
                    //console.log(res);
                    
                    if(res.return === 'duplicidade'){
                        $('#prog').val('');
                        $('#idProg').val('');
                        $('#myModalResult .modal-body').html("<h5>Usuário já possui uma solicitação em andamento!\n\n <br><br><strong>Número da solicitação</strong>: "+res.solicitacao+"</h5>");
                        $("#myModalResult").modal('show');
                        return false;
                    }
                    else{
                        $("#formSolicitacaoAcesso").submit();
                    }
                }
            });
                                                
            
        });            
    });        
</script>

<script>
    
    $(document).ready(function () {                                
        /************************ Funções de manipulação de usuários ************************/                
        $('#idUsuario').on('change', function () {            
            if($(this).val() == ''){
                $('#myModalResult .modal-body').html('<h5>Nenhum usuário selecionado.</h5>');
                $("#myModalResult").modal('show');
                return false;
            }                        
                        
            var idUsuario = $(this).val();
            var idFuncao = $('#idFuncao').val();
            
            $.ajax({
                type: 'POST',
                url:  url+'SolicitacaoAcessoFuncao/ajaxCarregaGruposFuncao',
                data: {
                    idUsuario: idUsuario,
                    idFuncao: idFuncao
                },
                success: function(res){
                    var data = JSON.parse(res);
                    $('#tableFuncaoGrupo').html(data.gruposFuncao);
                    //$('#tableGrupoPadraoFuncao').html(data.gruposFuncaoNaFuncao);
                    $('#tableGrupoPadraoForaFuncao').html(data.gruposFuncaoForaFuncao);
                    //$('#tableGrupoAdicionar').html(data.gruposFuncaoAdicionar);

                    $('#riscos').val(data.totalRiscos);
                    $('#text-risco').css('display', 'none');
                    $('#load').css('display', 'none');
                    $('.totalFuncByGrupo').text(data.totalFuncByGrupo);
                    $('.totalCamposPessoais').text(data.totalCamposPessoais);
                    $('.totalCamposSensiveis').text(data.totalCamposSensiveis);
                    $('.totalCamposAnonizados').text(data.totalCamposAnonizados);

                    // Seleciona a função baseada no que o usuário já possui
                    $('#idFuncao').val(data.idFuncaoUsuario);
                    $('#idUsuarioFuncHidden').val(data.idFuncaoUsuario);

                    carregaRiscosGrupos();

                    $('#tableAbaProgs').DataTable().ajax.reload();
                    $('#tableAbaCamposPessoais').DataTable().ajax.reload();
                    $('#tableAbaCamposSensiveis').DataTable().ajax.reload();
                    $('#tableAbaCamposAnonizados').DataTable().ajax.reload();
                    carregaProcessos();

                }
            });               
        });
        
         $('#idFuncao').on('change', function () {            
            if($(this).val() == ''){
                $('#myModalResult .modal-body').html('<h5>Nenhuma função selecionada.</h5>');
                $("#myModalResult").modal('show');
                return false;
            }
                        
            var idUsuario = $(this).val();
            var idFuncao = $('#idFuncao').val();
            
            $.ajax({
                type: 'POST',
                url:  url+'SolicitacaoAcessoFuncao/ajaxCarregaGruposFuncao',
                data: {
                    idUsuario: idUsuario,
                    idFuncao: idFuncao
                },
                success: function(res){
                    var data = JSON.parse(res);
                    $('#tableFuncaoGrupo').html(data.gruposFuncao);
                    $('#tableGrupoPadraoFuncao').html(data.gruposFuncaoNaFuncao);
                    $('#tableGrupoPadraoForaFuncao').html(data.gruposFuncaoForaFuncao);
                    $('#tableGrupoAdicionar').html(data.gruposFuncaoAdicionar);
                                        
                    $('#riscos').val(data.totalRiscos);
                    $('#text-risco').css('display', 'none');
                    $('#load').css('display', 'none');
                    $('.totalFuncByGrupo').text(data.totalFuncByGrupo);
                    $('.totalCamposPessoais').text(data.totalCamposPessoais);
                    $('.totalCamposSensiveis').text(data.totalCamposSensiveis);
                    $('.totalCamposAnonizados').text(data.totalCamposAnonizados);

                    // Seleciona a função baseada no que o usuário já possui
                    $('#idFuncao').val(data.idFuncaoUsuario);
                    
                    carregaRiscosGrupos();
                    $('#tableAbaProgs').DataTable().ajax.reload();
                    $('#tableAbaCamposPessoais').DataTable().ajax.reload();
                    $('#tableAbaCamposSensiveis').DataTable().ajax.reload();
                    $('#tableAbaCamposAnonizados').DataTable().ajax.reload();
                    carregaProcessos();

                }
            });
        });

        $('#idUsuario').select2({
            //allowClear: true,
            matcher: matchStart,
            multiple: false
        });

        // Valida se usuário selecionado é igual ao usuario que receberá os acessos.
        $("#idUsuario").select2().on("select2:select", function (e){
            // Remove a seleção do option Nenhum
            if(e.params.data.id === ''){                
                $("#idUsuario").val('').change();
                //console.log($("#idUsuarioAcompanhante").val())
            }else{
                
                var values = $("#idUsuario").val();
                var i = $("#idUsuario").val().indexOf('');
                //console.log(i)
                if (i >= 0) {
                    values.splice(i, 1);                    
                    $("#idUsuario").val(values).change();
                }
            }
        });

        $('#idFuncao').select2({
            //allowClear: true,
            matcher: matchStart,
            multiple: false
        });

        // Valida se usuário selecionado é igual ao usuario que receberá os acessos.
        $("#idFuncao").select2().on("select2:select", function (e){
            // Remove a seleção do option Nenhum
            if(e.params.data.id === ''){                
                $("#idFuncao").val('').change();
                //console.log($("#idUsuarioAcompanhante").val())
            }else{
                
                var values = $("#idFuncao").val();
                var i = $("#idFuncao").val().indexOf('');
                //console.log(i)
                if (i >= 0) {
                    values.splice(i, 1);                    
                    $("#idFuncao").val(values).change();
                }
            }
        });
        
        $('#idUsuarioAcompanhante').select2({
            //allowClear: true,
            matcher: matchStart,
            multiple: true
        });        

        // Valida se usuário selecionado é igual ao usuario que receberá os acessos.
        $("#idUsuarioAcompanhante").select2().on("select2:select", function (e){
            //console.log('id: ' + e.params.data.id);
            if(e.params.data.id === $('#idUsuario').val()){
                // Remove a seleção
                var values = $("#idUsuarioAcompanhante").val();
                //console.log('length: ' + values.length);
                
                if (values) {
                    var i = values.indexOf(e.params.data.id);
                    if(i < 0){
                        $("#idUsuarioAcompanhante").val('').change();
                    }else if (i >= 0) {
                        values.splice(i, 1);
                        $("#idUsuarioAcompanhante").val(values).change();
                    }
                }
                
                $('#myModalResult .modal-body').html("<h5>Usuário acompanhante não pode ser igual ao usuário que receberá os acessos.</h5>");
                $("#myModalResult").modal('show');
                return false;
            }
            
            // Remove a seleção do option Nenhum
            if(e.params.data.id === ''){                
                $("#idUsuarioAcompanhante").val('').change();
                //console.log($("#idUsuarioAcompanhante").val())
            }else{
                
                var values = $("#idUsuarioAcompanhante").val();
                var i = $("#idUsuarioAcompanhante").val().indexOf('');
                //console.log(i)
                if (i >= 0) {
                    values.splice(i, 1);                    
                    $("#idUsuarioAcompanhante").val(values).change();
                }
            }
        });
        
        // Valida se usuário selecionado é igual ao usuario que receberá os acessos.
        $("#idUsuarioAcompanhante").select2().on("select2:unselect", function (e){
            if($("#idUsuarioAcompanhante").val() == null){
                $("#idUsuarioAcompanhante").val('').change();
            }
        });
        
        /*$('#idUsuarioAcompanhante').on('change', function () {
            //console.log($("#idUsuarioAcompanhante :selected").select2('data')[0]);
            if($("#idUsuarioAcompanhante :selected").val() === $('#idUsuario').val()){
                $('#idUsuarioAcompanhante').val('');
                $('#myModalResult .modal-body').html("<h5>Usuário acompanhante não pode ser igual ao usuário que receberá os acessos.</h5>");
                $("#myModalResult").modal('show');
                return false;
            }
        });*/
        
    });                        
    
    // Recupera matriz de risco
    function carregaRiscosGrupos(){
        var grupos = new Array();        
        $('input[name="idGrupo[]"]').each(function(){            
            grupos.push($(this).val());
        });                

        if(grupos.length === 0) {
            grupos.push(0);
        }
        
        $('#matriz-risco-grupos').html('');
        $('#text-risco').css('display', 'block');
        $('#load').css('display', 'block');
        
        $.ajax({
            type: 'POST',
            url:  url+'SolicitacaoAcessoFuncao/ajaxMatrizDeRisco',
            data: {grupos: grupos},
            success: function(res){
                var data = JSON.parse(res);
                $('#matriz-risco-grupos').html(data.html);
                $('#count-riscos').html(data.totalRiscos);
                $('#riscos').val(data.totalRiscos);
                $('#text-risco').css('display', 'none');
                $('#load').css('display', 'none');
                $('.totalProgByGrupo').text(data.totalProgByGrupo);
                $('.totalCamposPessoais').text(data.totalCamposPessoais);
                $('.totalCamposSensiveis').text(data.totalCamposSensiveis);
                $('.totalCamposAnonizados').text(data.totalCamposAnonizados);
                
                //$('#tableAbaProgs').DataTable().ajax.reload();
                
            }
        });
    }
    
    // Recupera matriz de risco
    function carregaProcessos(){                    
        if($('#idFuncao').val() == '') {
            return false;
        }
                       
        $.ajax({
            type: 'POST',
            url:  url+'SolicitacaoAcessoFuncao/ajaxCarregaProcessosGrupos',
            data: {idFuncao: $('#idFuncao').val()},
            success: function(res){
                var data = JSON.parse(res);
                $('#tableAbaProcessBody').html(data.html);
                $('.totalProcess').html(data.total);                
            }
        });
    }
    
    $(document).ready(function(){                
        // Cria instancia do datatable para poder recarregar o mesmo depois
        // Carrega datatable com programas de cada grupo selecionado
        table = $('#tableAbaProgs').DataTable({
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
                url: url + "SolicitacaoAcessoFuncao/ajaxCarregaAbaProg",
                "data": function (d){                   
                    var grupos = new Array();        
                    $('input[name="idGrupo[]"]').each(function(){            
                        grupos.push($(this).val());
                    });                
                                                            
                    d.empresa = $('#instancia').val();
                    d.grupos = grupos;
                }                    
            }
        });
        
        // Cria instancia do datatable para poder recarregar o mesmo depois
        // Carrega datatable com processos da função selecionada
        tableProcess = $('#tableAbaProcess').DataTable({
            //"sorting": [[3,'asc']],
            "processing": false,
            "serverSide": false,
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
        });
        table2 = $('#tableAbaCamposPessoais').DataTable({
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
                url: url + "SolicitacaoAcessoFuncao/ajaxCarregaAbaPessoais",
                "data": function (d){    
                    var grupos = new Array();        
                    $('.manterStatus').each(function() {            
                        if($(this).is(':checked') == true){                           
                            grupos.push($(this).closest('tr').find('.idGrupo').val());
                        }            
                    });
                                        
                    $('#formSolicitacaoAcesso #tbodyGruposJaAdicionados').find('input').each(function() {
                        grupos.push($(this).val());            
                    });
                    d.empresa = $('#instancia').val();
                    d.grupos = grupos;   
                }                             
            }
        });

        table3 = $('#tableAbaCamposSensiveis').DataTable({
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
                url: url + "SolicitacaoAcessoFuncao/ajaxCarregaAbaSensiveis",
                "data": function (d){    
                    var grupos = new Array();        
                    $('.manterStatus').each(function() {            
                        if($(this).is(':checked') == true){                           
                            grupos.push($(this).closest('tr').find('.idGrupo').val());
                        }            
                    });
                                        
                    $('#formSolicitacaoAcesso #tbodyGruposJaAdicionados').find('input').each(function() {
                        grupos.push($(this).val());            
                    });
                    d.empresa = $('#instancia').val();
                    d.grupos = grupos;   
                }                             
            }
        });

        table4 = $('#tableAbaCamposAnonizados').DataTable({
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
                url: url + "SolicitacaoAcessoFuncao/ajaxCarregaAbaAnonizados",
                "data": function (d){    
                    var grupos = new Array();        
                    $('.manterStatus').each(function() {            
                        if($(this).is(':checked') == true){                           
                            grupos.push($(this).closest('tr').find('.idGrupo').val());
                        }            
                    });
                                        
                    $('#formSolicitacaoAcesso #tbodyGruposJaAdicionados').find('input').each(function() {
                        grupos.push($(this).val());            
                    });
                    d.empresa = $('#instancia').val();
                    d.grupos = grupos;   
                }                             
            }
        });
    });
    
</script>