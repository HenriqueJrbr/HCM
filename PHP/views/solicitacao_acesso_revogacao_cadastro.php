<style>        
    #grupo-list{
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

    #grupo-list li{
        padding: 10px;
        background: #f0f0f0;
        border-bottom: #e6e6e6 1px solid;
        /*border-radius: 5px;*/
        border-left: #e6e6e6 1px solid;
        border-right: #e6e6e6 1px solid;
    }

    #grupo-list li:hover {
        background: #ece3d2;
        cursor: pointer;
    }

    .gruposAdicionados{
        list-style: none;
        margin-left: 0;
        padding-left: 0;
    }

    .gruposAdicionados li{
        border-bottom: solid 1px #e6e6e6;
        padding: 6px;
    }

    .gruposAdicionados li .fa-remove{
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
    #addGrupo{
        margin-top:23px
    }
    
    .manterStatus {
        margin-top: -10px;
    }

    .dataTables_scrollBody{
        margin-top: -23px !important;
    }
    .dataTables_filter, .dataTables_info { display: none; }

    /* > Para o input */
    .input-search{
        border:1px solid #CCC;
        padding:5px 14px;
        font-size:12px;
        margin:10px 0;
        float:right;
        width:100% !important;
        
        -webkit-border-radius:15px;
           -moz-border-radius:15px;
            -ms-border-radius:15px;
             -o-border-radius:15px;
                border-radius:15px;
    }
    .input-search::-webkit-input-placeholder{ font-style:italic }
    .input-search:-moz-placeholder          { font-style:italic }
    .input-search:-ms-input-placeholder     { font-style:italic }
</style>

<div class="col-md-12 col-sm-12 col-xs-12">
    <ol class="breadcrumb">
        <li>
            <a href="<?php echo URL ?>">
                <font style="vertical-align: inherit;" onclick="loadingPagia()">
                <font style="vertical-align: inherit;">Dashboard</font></font>
            </a>
        </li>        
        <li class="active">Solicitação de permissão x revogação de grupos</li>
    </ol>
</div>

<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Solicitação de permissão x revogação de grupos</h2>
            <div class="row">
                <div class="col-md-3 pull-right">
                    <button type="button" class="btn btn-success pull-right btnSalvarSolicAcesso" onclick="loadingPagia();">Salvar</button>
                    <button type="button" class="btn btn-danger pull-right" onclick="loadingPagia();">Voltar</button>                                        
                </div>
            </div>  
            <div class="clearfix"></div>
        </div>        
        <div class="x_content">
            <form action="<?php echo URL; ?>/SolicitacaoAcessoRevogacao/criaSolicitacaoAcesso" id="frmSolicitacao" method="post">
                <input type="hidden" name="idFluxo" value="<?php echo $idFluxo; ?>">
                <input type="hidden" name="idEmpresa" value="<?php echo $_SESSION['empresaid']; ?>">
                <div class="row">
                    <div class="col-md-12"><?php echo $this->helper->alertMessage(); ?></div>
                </div>
                <div class="row">
                    <!--<div class="col-md-4">
                        <strong>Nr. Solicitação:</strong> <?php echo $nrSolicitacao; ?>
                    </div>-->
                    <div class="col-md-9">
                        <strong>Data Solicitação:</strong> <?php echo date('d/m/Y'); ?>
                    </div>
                    <div class="col-md-3">
                        <input type="hidden" name="idSolicitante" value="<?php echo $solicitante[0]['idUsuario']; ?>">
                        <strong>Solicitante:</strong> <?php echo $solicitante[0]['nome_usuario']; ?>
                    </div>
                    <hr>
                </div>
                <div class="clearfix"></div>
                                
                <div class="row">
                    <div class="col-md-4">
                        <label>Usuário:</label>
                        <input type="hidden" id="idUsuarioHidden" value="<?php echo $_SESSION['idUsrTotvs']; ?>">
                        
                        <select name="idUsuario" class="form-control select2" id="idUsuario">                            
                            <option value="" selected></option>
                            <?php foreach($usuarios as $val): ?>
                            <option value="<?php echo $val['idUsuario']; ?>" <?php //echo ($val['idUsuario'] == $_SESSION['idUsrTotvs']) ? 'selected' : '' ?>><?php echo $val['nome_usuario'] ;?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">&nbsp;</div>

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
                    <div class="col-md-6">
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

                <?php if(isset($solicitante[0]['gestor_usuario']) && $solicitante[0]['gestor_usuario'] =='S'): ?>
            
                <div class="row">
                    <div class="col-md-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Grupos já relacionado ao usuário</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
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
                                        <div class="col-md-3 pull-right">
                                            <input type="text" class="input-search form-control" alt="adicionados" placeholder="Buscar" />
                                        </div>
                                        <div class="col-sm-12">
                                            <table class="table table-striped table-bordered dataTable no-footer adicionados"
                                                cellspacing="0" width="100%" role="grid"
                                                aria-describedby="datatable-responsive_info"
                                                style="width: 100%;" id="tableRemover">
                                                <thead>
                                                <tr role="row">
                                                    <th width="10%">Grupo</th>
                                                    <th width="10%">Desc. Grupo</th>
                                                    <th width="10%">Gestor Grupo</th>
                                                    <th >Programa</th>
                                                    <th width="10%">Nr. Programas</th>
                                                    <th width="10%">Nr. Usuários</th>
                                                    <th width="8%">Remover<br><input type="checkbox" id="checkAllGrupoRemover" ></th>
                                                </tr>
                                                </thead>
                                                <tbody id="tbodyRemover"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                        </div>                       
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Grupos vs Programas à adicionar</h2> 
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
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
                                        <div class="col-md-3 pull-right">
                                            <input type="text" class="input-search form-control" alt="adicionar" placeholder="Buscar" />
                                        </div>
                                        <div class="col-sm-12">
                                            <table class="table table-striped table-bordered dataTable no-footer adicionar"
                                                cellspacing="0" width="100%" role="grid"
                                                aria-describedby="datatable-responsive_info"
                                                style="width: 100%;" id="tableAdicionar">
                                                <thead>
                                                <tr role="row">                                                  

                                                    <th width="10%">Grupo</th>
                                                    <th width="10%">Desc. Grupo</th>
                                                    <th width="10%">Gestor Grupo</th>
                                                    <th>Programa</th>
                                                    <th width="8%">Nr. Programas</th>
                                                    <th width="8%">Nr. Usuários</th>
                                                    <th width="8%">Adicionar</th>
                                                </tr>
                                                </thead>
                                                <tbody id="tbodyAdicionar"></tbody>
                                            </table>
                                            <br>
                                            <p class="red">Obs.: Os grupos que estiverem envolvidos em solicitações de grupos x programas. Não serão listados por questão de segurança.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>                     
                    </div>
                </div>
                
                <?php endif; ?>
                <div class="clearfix"></div>
        
                <br>
                <?php if(isset($solicitante[0]['gestor_usuario']) && $solicitante[0]['gestor_usuario'] =='S'): ?>
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
                                                <a href="#tab_content3" id="profile-tabPessoais" role="tab" data-toggle="tab" aria-expanded="true">Dados Pessoais <span class="badge totalPessoais"></span></a>
                                            </li> 
                                            <li role="presentation">
                                                <a href="#tab_content4" id="profile-tabSensiveis" role="tab" data-toggle="tab" aria-expanded="true">Dados Sensiveis <span class="badge totalSensiveis"></span></a>
                                            </li> 
                                            <li role="presentation">
                                                <a href="#tab_content5" id="profile-tabAnonizados" role="tab" data-toggle="tab" aria-expanded="true">Dados Anonizados <span class="badge totalAnonizados"></span></a>
                                            </li>                                       
                                        </ul>
                                        <div id="myTabContent" class="tab-content">
                                            <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="profile-tab1"><!--Inicio Tabela 1 -->
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="x_panel">
                                                            <div class="x_title">
                                                                <h2>Matriz de Riscos  </h2>
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
                                                                                    <th width="50%">Cód. Programa</th>
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

                                            <div role="tabpane1" class="tab-pane fade" id="tab_content3" aria-labelledby="profile-tabPessoais"><!--Inicio Tabela 1 -->


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
                                                                                    <th width="50%">Cód. Programa</th>
                                                                                    <th>Descrição</th>
                                                                                    <th width="10%">Campo</th>
                                                                                    <th>Anonizado</th>
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
                                            <div role="tabpane1" class="tab-pane fade" id="tab_content4" aria-labelledby="profile-tabSensiveis"><!--Inicio Tabela 1 -->


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
                                                                                    <th width="50%">Cód. Programa</th>
                                                                                    <th>Descrição</th>
                                                                                    <th width="10%">Campo</th>
                                                                                    <th>Anonizado</th>
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
                                            <div role="tabpane1" class="tab-pane fade" id="tab_content5" aria-labelledby="profile-tabAnonizados"><!--Inicio Tabela 1 -->


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
                                                                                    <th width="30%">Cód. Programa</th>
                                                                                    <th>Descrição</th>
                                                                                    <th width="10%">Campo</th>
                                                                                    <th>Pessoal</th>
                                                                                    <th>Sensivel</th>
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
                <?php endif; ?>
                <div class="clearfix"></div>
                <div class="row">
                    <div class="col-md-3 pull-right">
                        <button type="button" class="btn btn-success pull-right btnSalvarSolicAcesso" onclick="loadingPagia();">Salvar</button>
                        <button type="button" class="btn btn-danger pull-right" onclick="loadingPagia();">Voltar</button>                                        
                    </div>
                </div>           
                <div class="clearfix"></div>
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
    /**************************** Busca dos datatables *****************************/
    $(document).ready(function(){
        $(".input-search").keyup(function(){
            //pega o css da tabela 
            var tabela = $(this).attr('alt');
            if( $(this).val() != ""){
                $("."+tabela+" tbody>tr").hide();
                $("."+tabela+" td:contains-ci('" + $(this).val() + "')").parent("tr").show();
            } else{
                $("."+tabela+" tbody>tr").show();
            }
        });

        $.extend($.expr[":"], {
            "contains-ci": function(elem, i, match, array) {
                return (elem.textContent || elem.innerText || $(elem).text() || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
            }
        });         
    });

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
        $(document).ready(function () {
            // Marca ou desmarca checkbox de grupos remover
            $('#checkAllGrupoRemover').on('click', function () {
                $('.removerStatus').not(this).prop('checked', this.checked);
                setTimeout(function(){ carregaRiscosGrupos(); }, 300); 
            });
        });    
        
        /************************ Funções de manipulação de usuários ************************/
        $('#idUsuario').on('change', function () {
            $('#idUsuarioHidden').val($('#idUsuario').val());
            //carregaGruposProgramas();
            //carregaGruposJaAdicionados();          
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
            }else{
                var values = $("#idUsuario").val();
                var i = $("#idUsuario").val().indexOf('');

                if (i > 0) {
                    values.splice(i, 1);                    
                    $("#idUsuario").val(values).change();
                }
            }
        });

        $('#idUsuarioAcompanhante').select2({
            matcher: matchStart,
            multiple: true
        });        

        // Valida se usuário selecionado é igual ao usuario que receberá os acessos.
        $("#idUsuarioAcompanhante").select2().on("select2:select", function (e){
            if(e.params.data.id === $('#idUsuario').val()){
                // Remove a seleção
                var values = $("#idUsuarioAcompanhante").val();
                
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
            }else{
                
                var values = $("#idUsuarioAcompanhante").val();
                var i = $("#idUsuarioAcompanhante").val().indexOf('');
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

        // Busca os dados do grupo e adiciona o mesmo
        $("#idUsuario").select2().on("select2:select", function (e){          
            // Valida se usuário ja possui grupo solicitado
            var idUsr = $('#idUsuario').val();             
            
            $.ajax({
                type: "POST",
                url: url + "SolicitacaoAcessoRevogacao/ajaxValidaGrupo",
                data: {
                    idUsuario: idUsr,
                    idGrupo: $("#idGrupo").val()
                },
                beforeSend: function () {
                    $("#grupo").css("background", "#FFF url(LoaderIcon.gif) no-repeat 165px");
                },
                success: function (data) {
                    
                    var res = JSON.parse(data);                    
                    //console.log(res);
                    
                    if(res.return === 'duplicidade'){
                        $('#idusuario').val('');
                        $("#idUsuario").val('').change();
                        $('#myModalResult .modal-body').html("<h5>Usuário já possui uma solicitação em andamento para esse grupo!\n\n <br><br><strong>Número da solicitação</strong>: "+res.solicitacao+"</h5>");
                        $("#myModalResult").modal('show');
                        return false;
                    }else{
                        carregaGruposProgramas();
                        carregaGruposJaAdicionados(); 
                    }
                    
                    // if(res.return === false){
                    //     var grupos = "<br><br>Grupos: ";
                    //     for(i = 0; i < res.grupos.length; i++){
                    //         grupos += res.grupos[i].cod_grupo + ((i + 1 < res.grupos.length) ? ', ' : ' ');
                    //     }

                    //     $('#myModalResult .modal-body').html("<h5>Usuário já possui acesso ao grupo</h5>");
                    //     $("#myModalResult").modal('show');
                    //     return false;
                    // }else{
                    //     /*var totalGrupoAdd = $('.gruposAdicionados tr').length;
                    //     var grupoSpl = $('#grupo').val().split(' - ');
                    //     var inputGrupo = '<tr>';
                    //     inputGrupo += '<td><input type="hidden" name="grupo['+totalGrupoAdd+'][idGrupo]" class="idGrupo" value="'+$("#idGrupo").val()+'"><i class="fa fa-remove pull-right"></i>';
                    //     inputGrupo += '<td><input type="hidden" name="grupo['+totalGrupoAdd+'][idLegGrupo]" value="'+grupoSpl[0]+'">'+grupoSpl[0]+'</td>';
                    //     inputGrupo += '<td><input type="hidden" name="grupo['+totalGrupoAdd+'][descAbrev]" value="'+grupoSpl[1]+'">'+grupoSpl[1]+'</td>';
                    //     inputGrupo += '<td><textarea class="form-control" name="grupo['+totalGrupoAdd+'][obsGrupo]" style="width:100%;height: 30px"></textarea></td>';
                    //     inputGrupo += '</tr>';
                    //     $('.gruposAdicionados').append(inputGrupo);*/

                    //     carregaGruposProgramas();
                    //     carregaGruposJaAdicionados();      
                    // }
                }
            });
        });

        /************************ FIM Funções de manipulação de usuários ************************/

        // Submete o formulario
        $('.btnSalvarSolicAcesso').on('click', function(){
            var grupos = new Array();
            $('.manterStatus').each(function() {            
                if($(this).is(':checked') == true){                
                    grupos.push($(this).val());
                }            
            });

            var gruposRemove = new Array();
            $('.removerStatus').each(function() {            
                if($(this).is(':checked') == true){                
                    gruposRemove.push($(this).val());
                }            
            });

            if(grupos.length == 0 && gruposRemove.length == 0) {
                $('#load').css('display', 'none');
                $('#myModalResult .modal-body').html('<h5>Favor selecionar ao menos um grupo para remover ou adicionar.</h5>');
                $("#myModalResult").modal('show');
                return false;
            }
                                                
            $("#frmSolicitacao").submit();
        }); 
    });
    
    function carregaGruposProgramas(){

        if($('#idUsuario').val() == undefined){
            return false;
        }
        var tableAdicionar = $('#tableAdicionar').DataTable();
        tableAdicionar.destroy();

        var idUsr = $('#idUsuario').val();
        $.ajax({
            type: "POST",
            url: url + "SolicitacaoAcessoRevogacao/ajaxCarregaGruposProgramasAdd",
            data: {
                idUsuario: idUsr
            },
            beforeSend: function () {
                $("#prog").css("background", "#FFF url(LoaderIcon.gif) no-repeat 165px");
            },
            success: function (data) {                                                                 
                $('#tbodyAdicionar').html(data);
                var tableAdicionar = $("#tableAdicionar").dataTable( {
                    scrollY:        300,
                    scrollX:        true,
                    scrollCollapse: true,
                    paging:         false,
                    "language": {
                            //"lengthMenu": "Exibição _MENU_ Registros por página",
                            "lengthMenu": "",
                            "zeroRecords": "Registro nao encontrado",
                            //"info": "Pagina _PAGE_ de _PAGES_",
                            "info": "",
                            "infoEmpty": "No records available",
                            "search":"Pesquisar:",
                            /*"paginate": {
                                    "first":      "Primeiro",
                                    "last":       "Último",
                                    "next":       "Próximo",
                                    "previous":   "Anterior"
                                },*/
                                //"infoFiltered": "(Filtro de _MAX_ registro total)"
                                "infoFiltered": ""
                        }

                });
            }
        });
        
    }
    
    function carregaGruposJaAdicionados(){
        if($('#idUsuario').val() == undefined){
            return false;
        }
        var tableRemover = $('#tableRemover').DataTable();
        tableRemover.destroy();

        var idUsr = $('#idUsuario').val();
        $.ajax({
            type: "POST",
            url: url + "SolicitacaoAcessoRevogacao/ajaxCarregaGruposJaAdicionados",
            data: {
                idUsuario: idUsr
            },
            beforeSend: function () {
                $("#prog").css("background", "#FFF url(LoaderIcon.gif) no-repeat 165px");
            },
            success: function (data) {
                if(data == '0'){
                    $('#myModalResult .modal-body').html('<h5>Nenhum grupo relacionado ao usuário escolhido.</h5>');
                    $("#myModalResult").modal('show');
                    return false;
                }else{                    
                    $('#tbodyRemover').html(data);
                    var tableRemover = $("#tableRemover").dataTable( {
                        scrollY:        300,
                        scrollX:        true,
                        scrollCollapse: true,
                        paging:         false,
                        "language": {
                                //"lengthMenu": "Exibição _MENU_ Registros por página",
                                "lengthMenu": "",
                                "zeroRecords": "Registro nao encontrado",
                                //"info": "Pagina _PAGE_ de _PAGES_",
                                "info": "",
                                "infoEmpty": "No records available",
                                "search":"Pesquisar:",
                                /*"paginate": {
                                        "first":      "Primeiro",
                                        "last":       "Último",
                                        "next":       "Próximo",
                                        "previous":   "Anterior"
                                    },*/
                                 //"infoFiltered": "(Filtro de _MAX_ registro total)"
                                 "infoFiltered": ""
                            }

                    });
                    //return true;
                    carregaRiscosGrupos();
                }                                                
            }
        });
    }
    
    // Recupera matriz de risco
    function carregaRiscosGrupos(){
        var grupos = new Array();  
        $('.manterStatus').each(function() {            
            if($(this).is(':checked') == true){                
                grupos.push($(this).val());
            }            
        });

        $('.removerStatus').each(function() {            
            if($(this).is(':checked') == false){                
                grupos.push($(this).val());
            }            
        });
        
        $('#matriz-risco-grupos').html('');
        $('#text-risco').css('display', 'block');
        $('#load').css('display', 'block');

        if(grupos.length == 0) {
            grupos.push(0);
            $('#tableAbaProgs').DataTable().ajax.reload();
            $('#tableAbaPessoais').DataTable().ajax.reload();
            $('#tableAbaSensiveis').DataTable().ajax.reload();
            $('#tableAbaAnonizados').DataTable().ajax.reload();
            $('.totalProgByGrupo').text("0");
            $('.totalPessoais').text("0");
            $('.totalSensiveis').text("0");
            $('.totalAnonizados').text("0");
            $('#count-riscos').html("0");
            $('#load').css('display', 'none');
            return false;
        }
        
        $.ajax({
            type: 'POST',
            url:  url+'SolicitacaoAcessoRevogacao/ajaxMatrizDeRisco',
            data: {grupos: grupos},
            success: function(res){
                var data = JSON.parse(res);
                $('#matriz-risco-grupos').html(data.html);
                $('#count-riscos').html(data.totalRiscos);
                $('#riscos').val(data.totalRiscos);
                $('#text-risco').css('display', 'none');
                $('#load').css('display', 'none');
                $('.totalProgByGrupo').text(data.totalProgByGrupo);
                $('.totalPessoais').text(data.totalPessoais);
                $('.totalSensiveis').text(data.totalSensiveis);
                $('.totalAnonizados').text(data.totalAnonizados);
                
                $('#tableAbaProgs').DataTable().ajax.reload();
                $('#tableAbaPessoais').DataTable().ajax.reload();
                $('#tableAbaSensiveis').DataTable().ajax.reload();
                $('#tableAbaAnonizados').DataTable().ajax.reload();
                
            }
        });
    }

    $(document).ready(function(){ 
        //carregaGruposProgramas();
        //carregaGruposJaAdicionados();
       
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
                url: url + "SolicitacaoAcessoRevogacao/ajaxCarregaAbaProg",
                "data": function (d){                   
                    var grupos = new Array();
                    $('.manterStatus').each(function() {            
                        if($(this).is(':checked') == true){                           
                            grupos.push($(this).val());
                        }            
                    });

                    $('.removerStatus').each(function() {            
                        if($(this).is(':checked') == false){
                            grupos.push($(this).val());
                        }            
                    });

                    d.empresa = $('#instancia').val();
                    d.grupos = grupos;
                }                    
            }
        });

        table = $('#tableAbaPessoais').DataTable({
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
                url: url + "SolicitacaoAcessoRevogacao/ajaxCarregaAbaPessoais",
                "data": function (d){                   
                    var grupos = new Array();
                    $('.manterStatus').each(function() {            
                        if($(this).is(':checked') == true){                           
                            grupos.push($(this).val());
                        }            
                    });

                    $('.removerStatus').each(function() {            
                        if($(this).is(':checked') == false){
                            grupos.push($(this).val());
                        }            
                    });

                    d.empresa = $('#instancia').val();
                    d.grupos = grupos;
                }                    
            }
        });

        table = $('#tableAbaSensiveis').DataTable({
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
                url: url + "SolicitacaoAcessoRevogacao/ajaxCarregaAbaSensiveis",
                "data": function (d){                   
                    var grupos = new Array();
                    $('.manterStatus').each(function() {            
                        if($(this).is(':checked') == true){                           
                            grupos.push($(this).val());
                        }            
                    });

                    $('.removerStatus').each(function() {            
                        if($(this).is(':checked') == false){
                            grupos.push($(this).val());
                        }            
                    });

                    d.empresa = $('#instancia').val();
                    d.grupos = grupos;
                }                    
            }
        });

        table = $('#tableAbaAnonizados').DataTable({
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
                url: url + "SolicitacaoAcessoRevogacao/ajaxCarregaAbaAnonizados",
                "data": function (d){                   
                    var grupos = new Array();
                    $('.manterStatus').each(function() {            
                        if($(this).is(':checked') == true){                           
                            grupos.push($(this).val());
                        }            
                    });

                    $('.removerStatus').each(function() {            
                        if($(this).is(':checked') == false){
                            grupos.push($(this).val());
                        }            
                    });

                    d.empresa = $('#instancia').val();
                    d.grupos = grupos;
                }                    
            }
        });

    });
</script>