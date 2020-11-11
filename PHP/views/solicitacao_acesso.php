<style>
    .nomeField{
        line-height: 0;
        font-size: 11px;
        /*font-weight: bold;*/
        color: #355C86
    }
    .labelField{
        color: #CB6804;
        font-weight: bold;
    }
    .page-header{
        padding-bottom: 0;
        margin: 0;
        display: none;
    }       

    .nav-tabs > li.active > a,
    .nav-tabs > li.active > a:focus,
    .nav-tabs > li.active > a:hover{
        background-color: #FF8000;
        color: #fff
    }
    
    .badge-danger{
        background-color: #FF0000;
        color: #fff !important
    }   
    
    .infosfluxos{
        color: #ff8000
    }
</style>

    <div class="col-md-12 col-sm-12 col-xs-12">
        <ol class="breadcrumb">
            <li><a href="<?php echo URL ?>/" onclick="loadingPagia()">Dashboard</a></li>
            <li>
                <a href="<?php echo URL ?>/Fluxo/centralDeTarefa">
                    <font style="vertical-align: inherit;" onclick="loadingPagia()">
                        <font style="vertical-align: inherit;">Central de Atividades</font>
                    </font>
                </a>
            </li>
            <li class="active">Solicitação de Acesso</li>
        </ol>
    </div>    

    <?php if($idAtividade > 0): ?>
    <?php $url = URL . "/Fluxo/callRegras/$from/".$documento->idSolicitacao."/".$idAtividade."/".'0/'.$idMovimentacao ?>    
    <form action="<?php echo $url ;?>" method="POST" id="frmFluxo" enctype="multipart/form-data">
        <?php endif; ?>
        <div class="row">
            <div class="col-md-6"></div>
            <div class="col-md-6">
                <?php if($idAtividade > 0): ?>
                    <button type="button" name="enviar" id="btnEnviar" class="btn btn-success pull-right" onclick="loadingPagia()">Enviar</button>                    
                    <?php if($idAtividade == 16): ?>
                    <button type="button" class="btn btn-danger pull-left" id="btnRejeitaSolicitacao">Rejeitar Solicitação</button>
                    <?php endif; ?>
                <?php endif; ?>
                <button type="button" class="btn btn-info pull-right" data-toggle="modal" data-target="#myModalTimeline">TimeLine</button>
                <!--<button type="button" class="btn btn-info pull-right">Fluxo</button>  -->              
                <a href="<?php echo URL; ?>/Fluxo/centralDeTarefa" id="btnRejeitaSolicitacao" class="btn btn-warning pull-right hide" onclick="loadingPagia(); javascript:history.back(-1)">Voltar</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <input type="hidden" name="enviar" value="Enviar">
                <input type="hidden" name="aprovadorGrupo" id="aprovadorGrupo" class="form-control">
                <input type="hidden" name="idMovimentacao" id="idMovimentacao" class="form-control" value="<?php echo $idMovimentacao; ?>">
                <input type="hidden" name="numAtividade" id="numAtividade" class="form-control" value="<?php echo $idAtividade ?>">
                <input type="hidden" name="usrLogado" id="usrLogado" class="form-control" value="<?php echo $_SESSION['idUsrTotvs'] ?>">
                <input type="hidden" name="gestorUsuario" id="idGestorUsuario" class="form-control" value="<?php echo $documento->idGestorUsuario; ?>">
                <input type="hidden" name="idSolicitacao" id="idSolicitacao" class="form-control" value="<?php echo $documento->idSolicitacao; ?>">
                <input type="hidden" name="idSolicitante" id="idSolicitante" class="form-control" value="<?php echo $idSolicitante; ?>">                
                <input type="hidden" name="idTotvs" id="idTotvs" class="form-control" value="<?php echo $documento->idTotvs; ?>">                
                <input type="hidden" name="idTotvsRevisao" id="idTotvsRevisao"  value="<?php echo $documento->idTotvs ?>">                
                <input type="hidden" name="idFluxo" id="idFluxo" class="form-control" value="<?php echo $movimentacao['form']; ?>">
                <input type="hidden" name="riscos" id="riscos">                
                <input type="hidden" name="usuario" id="usuario" value="<?php echo $documento->usuario ?>">
                <input type="hidden" name="idusuario" id="idusuario" value="<?php echo $documento->idusuario ?>">
                <input type="hidden" name="gestorUsuario" id="gestorUsuario" value="<?php echo $documento->gestorUsuario ?>">
                <input type="hidden" name="idGestorUsuario" id="idGestorUsuario" value="<?php echo $documento->idGestorUsuario ?>">               
            </div>
        </div>

        <div class="clearfix">&nbsp;</div>

        <div class="x_panel">
            <div class="x_title">
                <h2>Fluxo de solicitação de acesso</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
                                    
            <div class="row">
                <div class="col-md-4">
                    Código da solicitação: <strong class="infosfluxos"><?php echo $documento->idSolicitacao; ?></strong>
                </div>
                <div class="col-md-4">
                    <?php echo (isset($movimentacao['descricao'])) ? "Atividade atual: <strong  class=\"infosfluxos\">" . $movimentacao['descricao'] . "</strong>" : ''; ?>
                </div>
                <div class="col-md-4">
                    Início da solicitação: <strong class="infosfluxos"><?php echo date('d/m/Y', strtotime($documento->dataInicio)); ?></strong>
                </div>  
            </div>
            
            <hr>            
            <div class="row">                
                <div class="col-md-4">
                    Usuário:
                    <strong class="infosfluxos"><?php echo $documento->usuario ?></strong>
                </div>
                <div class="col-md-4">
                    Usuário Totvs:
                    <strong class="infosfluxos"><?php echo $documento->idTotvs ?></strong>
                </div>
                <div class="col-md-4">
                    Gestor do Usuário:
                    <strong class="infosfluxos"><?php echo $documento->gestorUsuario ?></strong>                  
                </div>
            </div>
            <div class="row hide">
                <div class="col-md-6">
                    <div class="checkbox">
                        <label style="color: red">
                            <input type="checkbox" id="bloquearUsuario"  readonly="true">
                            <span data-toggle="tooltip" title="Esta opção remove os grupos e inativa o usuário">Bloquear Usuário?</span>
                        </label>
                        <input type="hidden" name="bloquearUsuario" id="bloqueaUsuario" value="0">
                    </div>
                </div>
            </div>
            <div class="clearfix"><hr></div>
            <div class="row">
                <div class="col-md-12">                                               
                    <h2>Programas Solicitados</h2>
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
                                       style="width: 100%;" id="tableProgManter">
                                    <thead>
                                    <tr role="row">                                              
                                        <th width="10%">Código</th>
                                        <th width="13%">Descrição</th>
                                        <th width="25%">Observação</th>                                           
                                    </tr>
                                    </thead>
                                    <tbody class="progsAdicionados">
                                        <?php foreach($documento->programas as $key => $val): ?>
                                        <tr>                                            
                                            <td><input type="hidden" name="programa[<?php echo $key; ?>][idProg]" class="idProg" value="<?php echo $val->idProg; ?>"><input type="hidden" name="programa[<?php echo $key; ?>][codProg]" value="<?php echo $val->codProg; ?>"><?php echo $val->codProg; ?></td>
                                            <td><input type="hidden" name="programa[<?php echo $key; ?>][descProg]" value="<?php echo $val->descProg; ?>"><?php echo $val->descProg; ?></td>
                                            <td><?php echo $val->obsProg; ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>                        
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="display:none; color:#ff0000" id="divMensagemGruposUsuarios">Usuário não possui grupo relacionado.</div>
                <div class="col-md-12"  id="divGruposUsuarios">
                    <h2>Grupos já relacionados ao usuário</h2>                       
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
                                       style="width: 100%;" id="tableProgManter">
                                    <thead>
                                    <tr role="row">                                                  
                                        <th width="10%">Programa</th>
                                        <th width="13%">Grupo</th>
                                        <th width="25%">Desc. Grupo</th>
                                        <th width="20%">Gestor</th>
                                        <th width="12%">Nr. Programas</th>
                                        <th width="9%">Nr. Usuários</th>                                            
                                    </tr>
                                    </thead>
                                    <tbody id="tbodyGruposJaAdicionados">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>                        
                </div>
            </div>
            <div class="clearfix"><hr></div>
            
            <!-- Aprovação dos gestores responsáveis -->
            <div class="x_content">                
                <h2>Aprovação dos Gestores</h2>
                <div class="clearfix"></div>
                <?php require 'inc/fluxos_aprovacao.php'; ?>

                <div class="clearfix"><hr></div>
                <?php require 'inc/fluxos_overview.php'; ?>

                <?php if($idAtividade == 0): ?>
                <?php //require 'inc/fluxos_snapshot.php'; ?>
                <?php endif; ?>
            </div>
        </div>
    <?php if($idAtividade > 0): ?>
    </form>
    <?php endif; ?>


    <div id="myModalResult" class="modal fade" role="dialog">
        <div class="modal-dialog modal-sm">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-exclamation"></i> &nbsp;</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <p id="result_msg"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Voltar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL COM TIMELINE -->
    <?php require 'inc/timeline.php'; ?>
    </div></div>
    <!-- set up the modal to start hidden and fade in and out -->
    <div id="myModalConfirm" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- dialog body -->
                <div class="modal-body"></div>
                <!-- dialog buttons -->
                <div class="modal-footer">
                    <button type="button" id="cancel" class="btn btn-danger">Voltar</button>
                    <button type="button" id="continue" class="btn btn-success">Rejeitar</button>
                </div>
            </div>
        </div>
    </div>