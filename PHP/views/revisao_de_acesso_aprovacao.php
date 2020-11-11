<style>
    .page-header{
        padding-bottom: 0;
        margin: 0;
        display: none;
    }

    .nav-tabs > li.active > a,
    .nav-tabs > li.active > a:focus,
    .nav-tabs > li.active > a:hover{
        background-color: #FF8000;
        color: #fff;
    }
    
    .badge-danger{
        background-color: #FF0000;
        color: #fff !important;
    }
    .infosfluxos{
        color: #ff8000
    }    
</style>

    <div class="col-md-12 col-sm-12 col-xs-12">
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo URL ?>/Fluxo/centralDeTarefa">
                    <font style="vertical-align: inherit;" onclick="loadingPagia()">
                        <font style="vertical-align: inherit;">Central de Atividades</font>
                    </font>
                </a>
            </li>
            <li class="active">
                <font style="vertical-align: inherit;">
                    <font style="vertical-align: inherit;">Revisão de Acesso</font>
                </font>
            </li>
        </ol>
    </div>    

    <?php if($idAtividade > 0): ?>
    <?php $url = URL . "/Fluxo/callRegras/$from/".$documento->idSolicitacao."/".$idAtividade."/".'0/'.$idMovimentacao ?>
    <?php //$url = URL . "/fluxo/callRegras/revisao_acesso_teste/".$documento->idSolicitacao."/".$idAtividade."/".'0/'.$idMovimentacao ?>
    <form action="<?php echo $url ;?>" method="POST" id="frmFluxo" enctype="multipart/form-data">
    <?php endif; ?>
        <div class="row"> 
            <div class="col-md-6"></div>
            <div class="col-md-6">
                <?php if($idAtividade > 0): ?>
                    <button type="button" name="enviar" id="btnEnviar" class="btn btn-success pull-right" onclick="loadingPagia()">Enviar</button>
<!--                    <a href="<?php echo URL; ?>/Fluxo/rejeitaSolicitacao/<?php echo $documento->idSolicitacao; ?>" class="btn btn-danger pull-right" onclick="loadingPagia();">Rejeitar Solicitação</a>-->
                <?php endif; ?>
                <button type="button" class="btn btn-info pull-right" data-toggle="modal" data-target="#myModalTimeline">TimeLine</button>
                <!--<button type="button" class="btn btn-info pull-right">Fluxo</button>-->
                <a href="<?php echo URL; ?>/Fluxo/centralDeTarefa" class="btn btn-warning pull-right" onclick="loadingPagia(); javascript:history.back(-1)">Voltar</a>
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
                <input type="hidden" name="numAprovadores" id="numAprovadores" class="form-control" value="<?php echo $documento->numAprovadores; ?>">
                <input type="hidden" name="idFluxo" id="idFluxo" class="form-control" value="<?php echo $movimentacao['form']; ?>">
                <input type="hidden" name="riscos" id="riscos">
            </div>
        </div>

        <div class="clearfix">&nbsp;</div>

        <div class="x_panel">
            <div class="x_title">
                <h2>Fluxo de revisão de acesso</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <?php if(isset($movimentacao['dataMovimentacao'])): ?>
            <div class="row">
                <?php
                $currentDate = new DateTime(date('Y-m-d'));
                //$dateIni  = new DateTime(date('Y-02-25'));
                //$dateIni  = new DateTime($documento->dataInicio);
                $dateIni  = new DateTime($movimentacao['dataMovimentacao']);
                $dateFim  = new DateTime($documento->dataFim);                               
                
                $diasTotal = $dateIni->diff($dateFim);
                //echo $diasTotal->days."<br>";
                
                // Recupera o total de dias da revisão
                $diasAprovacao = @floor($dateIni->diff($dateFim)->days / ($documento->numAprovadores == 0) ? 1 : $documento->numAprovadores);
                //echo $documento->numAprovadores;
                $diasFim = new DateTime(date('Y-m-d', strtotime($movimentacao['dataMovimentacao'] . '+ ' . ($diasAprovacao) . ' days')));
                //print_r($diasFim);
                $diasAtraso = $currentDate->diff($diasFim);
                //print_r($movimentacao);
                ?>
                <div class="col-md-6">
                    Código da solicitação: <strong class="infosfluxos"><?php echo $documento->idSolicitacao; ?></strong><br>
                    Atividade atual: <strong class="infosfluxos"><?php echo $movimentacao['descricao']; ?></strong>
                </div>                        
                <div class="col-md-6">
                    <input type="hidden" name="dataInicio" value="<?php echo $documento->dataInicio; ?>">
                    <input type="hidden" name="dataFim" value="<?php echo $documento->dataFim; ?>">
                    <input type="hidden" name="diasAprovacao" value="<?php echo $documento->diasAprovacao; ?>">
                    Início da revisão: <strong class="infosfluxos"><?php echo date('d/m/Y', strtotime($documento->dataInicio)); ?></strong> &nbsp;&nbsp;&nbsp;&nbsp; Data para corte automático: <strong style="color:#ff0000"><?php echo date('d/m/Y', strtotime($documento->dataFim)); ?></strong><br>
                    Prazo limite para execução da tarefa: <strong <?php echo (($diasAtraso->invert == 1) ? 'style="color:#ff0000"' : 'class="infosFluxos"' ) ?>><?php echo ($documento->numAprovadores == 1) ? $dateFim->format('d/m/Y') : $diasFim->format('d/m/Y'); ?> <?php echo (($diasAtraso->invert == 1) ? ' - ' . $diasAtraso->days . ' dia(s) de atraso' : '' ) ?></strong>
                </div>              
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="pull-left" style="margin-top:22px; margin-bottom: -15px;">
                        <span class="badge badge-danger">Obs. </span> Ao exceder o prazo limite da atividade, você pode estar prejudicando a conclusão da revisão. 
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="row">
                <div class="col-md-6">
                    Código da solicitação: <strong class="infosfluxos"><?php echo $documento->idSolicitacao; ?></strong><br>                    
                </div>
                <div class="col-md-6">                    
                    Início da revisão: <strong class="infosfluxos"><?php echo date('d/m/Y', strtotime($documento->dataInicio)); ?></strong> &nbsp;&nbsp;&nbsp;&nbsp; Data para corte automático: <strong style="color:#ff0000"><?php echo date('d/m/Y', strtotime($documento->dataFim)); ?></strong><br>                    
                </div>  
            </div>
            <?php endif; ?>
            <hr>            
            <div class="row">                
                <div class="col-md-4">
                    Usuário:
                    <strong class="infosfluxos"><?php echo $documento->usuario ?></strong>
                    <input type="hidden" name="usuario" id="usuario" class="form-control" readonly="readonly" value="<?php echo $documento->usuario ?>">
                    <input type="hidden" name="idusuario" id="idusuario" class="form-control hide" value="<?php echo $documento->idusuario ?>">
                </div>
                <div class="col-md-4">
                    Usuário Totvs: 
                    <strong class="infosfluxos"><?php echo $documento->idTotvs ?></strong>
                    <input type="hidden" name="idTotvsRevisao" id="idTotvsRevisao" class="form-control" readonly="readonly" value="<?php echo $documento->idTotvs ?>">
                </div>
                <div class="col-md-4">
                    Gestor do Usuário: 
                    <strong class="infosfluxos"><?php echo $documento->gestorUsuario ?></strong>                  
                    <input type="hidden" name="gestorUsuario" id="gestorUsuario" class="form-control" readonly="readonly" value="<?php echo $documento->gestorUsuario ?>">
                    <input type="hidden" name="idGestorUsuario" id="idGestorUsuario" class="form-control hide" readonly="readonly" value="<?php echo $documento->idGestorUsuario ?>">
                </div>
            </div>
            
            <div class="clearfix"><hr></div>
            
            <div class="row">
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
            
            <div class="x_content">
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