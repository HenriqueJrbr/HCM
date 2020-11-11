<style>
    .page-header{
        padding-bottom: 0;
        margin: 0;
        display: none;
    }
    .timeline {
        list-style: none;
        padding: 20px 0 20px;
        position: relative;
    }

    .timeline:before {
        top: 0;
        bottom: 0;
        position: absolute;
        content: " ";
        width: 3px;
        background-color: #eeeeee;
        left: 50%;
        margin-left: -1.5px;
    }

    ul.timeline {margin-bottom: -15px;margin-top: -15px;}
    .timeline > li:last-child{
        border-bottom: none;
    }
    .timeline > li {
        margin-bottom: 20px;
        position: relative;
    }

    .timeline > li:before,
    .timeline > li:after {
        content: " ";
        display: table;
    }

    .timeline > li:after {
        clear: both;
    }

    .timeline > li:before,
    .timeline > li:after {
        content: " ";
        display: table;
    }

    .timeline > li:after {
        clear: both;
    }

    .timeline > li > .timeline-panel {
        width: 46%;
        float: left;
        border: 1px solid #d4d4d4;
        border-radius: 2px;
        padding: 20px;
        position: relative;
        -webkit-box-shadow: 0 1px 6px rgba(0, 0, 0, 0.175);
        box-shadow: 0 1px 6px rgba(0, 0, 0, 0.175);
    }

    .timeline > li > .timeline-panel:before {
        position: absolute;
        top: 26px;
        right: -15px;
        display: inline-block;
        border-top: 15px solid transparent;
        border-left: 15px solid #ccc;
        border-right: 0 solid #ccc;
        border-bottom: 15px solid transparent;
        content: " ";
    }

    .timeline > li > .timeline-panel:after {
        position: absolute;
        top: 27px;
        right: -14px;
        display: inline-block;
        border-top: 14px solid transparent;
        border-left: 14px solid #fff;
        border-right: 0 solid #fff;
        border-bottom: 14px solid transparent;
        content: " ";
    }

    .timeline > li > .timeline-badge {
        color: #fff;
        width: 50px;
        height: 50px;
        line-height: 50px;
        font-size: 1.4em;
        text-align: center;
        position: absolute;
        top: 16px;
        left: 50%;
        margin-left: -25px;
        background-color: #999999;
        z-index: 100;
        border-top-right-radius: 50%;
        border-top-left-radius: 50%;
        border-bottom-right-radius: 50%;
        border-bottom-left-radius: 50%;
    }

    /*** inverted panel ***/
    .timeline > li > .timeline-panel-inverted {
        width: 46%;
        float: right;
        border: 1px solid #d4d4d4;
        border-radius: 2px;
        padding: 20px;
        position: relative;
        -webkit-box-shadow: 0 1px 6px rgba(0, 0, 0, 0.175);
        box-shadow: 0 1px 6px rgba(0, 0, 0, 0.175);
    }

    .timeline > li > .timeline-panel-inverted:before {
        position: absolute;
        top: 26px;
        left: -15px;
        display: inline-block;
        border-top: 15px solid transparent;
        border-right: 15px solid #ccc;
        border-left: 0 solid #ccc;
        border-bottom: 15px solid transparent;
        content: " ";
    }

    .timeline > li > .timeline-panel-inverted:after {
        position: absolute;
        top: 27px;
        left: -14px;
        display: inline-block;
        border-top: 14px solid transparent;
        border-right: 14px solid #fff;
        border-left: 0 solid #fff;
        border-bottom: 14px solid transparent;
        content: " ";
    }

    .timeline > li > .timeline-badge {
        color: #fff;
        width: 50px;
        height: 50px;
        line-height: 50px;
        font-size: 1.4em;
        text-align: center;
        position: absolute;
        top: 16px;
        left: 50%;
        margin-left: -25px;
        background-color: #999999;
        z-index: 100;
        border-top-right-radius: 50%;
        border-top-left-radius: 50%;
        border-bottom-right-radius: 50%;
        border-bottom-left-radius: 50%;
    }


    .timeline > li.timeline > .timeline-panel-inverted {
        float: right;
    }

    .timeline > li.timeline > .timeline-panel-inverted:before {
        border-left-width: 0;
        border-right-width: 15px;
        left: -15px;
        right: auto;
    }

    .timeline > li.timeline > .timeline-panel-inverted:after {
        border-left-width: 0;
        border-right-width: 14px;
        left: -14px;
        right: auto;
    }
    .timeline-title{
        border-bottom: solid 1px #e9e9e9;
        padding-bottom: 5px;
    }


    .timeline > li.timeline-inverted > .timeline-panel {
        float: right;
    }

    .timeline > li.timeline-inverted > .timeline-panel:before {
        border-left-width: 0;
        border-right-width: 15px;
        left: -15px;
        right: auto;
    }

    .timeline > li.timeline-inverted > .timeline-panel:after {
        border-left-width: 0;
        border-right-width: 14px;
        left: -14px;
        right: auto;
    }

    .timeline h4{
        margin-bottom: 2px !important;
    }

    .timeline-badge.primary {
        background-color: #2e6da4 !important;
    }

    .timeline-badge.success {
        background-color: #3f903f !important;
    }

    .timeline-badge.warning {
        background-color: #f0ad4e !important;
    }

    .timeline-badge.danger {
        background-color: #d9534f !important;
    }

    .timeline-badge.info {
        background-color: #5bc0de !important;
    }

    .timeline-title {
        margin-top: 0;
        color: inherit;
    }

    .timeline-body > p,
    .timeline-body > ul {
        margin-bottom: 0;
    }

    .timeline-body > p + p {
        margin-top: 5px;
    }

    .timeline-body{
        overflow: auto
    }

    .badge-danger{
        background-color: #FF0000;
        color: #fff !important
    }

    .line-in-timeline{
        margin: 0 !important;
    }

    .obs-timeline label,
    .obs-timeline strong{
        font-size: 11px;
    }

    .nav-tabs > li.active > a,
    .nav-tabs > li.active > a:focus,
    .nav-tabs > li.active > a:hover{
        background-color: #FF8000;
        color: #fff
    }
    .timeline-title strong{
        font-size: 14px;
    }
    .timeline-title span{
        font-size: 13px;
    }
    .histBadge{
        margin-top: 10px
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
    <form action="<?php echo $url ;?>" method="POST" id="frmRevisaoAcesso">
        <?php endif; ?>
        <div class="row">
            <div class="col-md-7"></div>
            <div class="col-md-5">
                <?php if($idAtividade > 0): ?>
                    <button type="button" name="enviar" id="btnEnviar" class="btn btn-success pull-right" onclick="loadingPagia()">Enviar</button>
                <?php endif; ?>
                <button type="button" class="btn btn-warning pull-right" data-toggle="modal" data-target="#myModalTimeline">TimeLine</button>
                <button type="button" class="btn btn-info pull-right">Fluxo</button>
                <a href="<?php echo URL; ?>/Fluxo/rejeitaSolicitacao/<?php echo $documento->idSolicitacao; ?>" class="btn btn-danger pull-right">Rejeitar Solicitação</a>
                <a href="<?php echo URL; ?>/Fluxo/centralDeTarefa" class="btn btn-danger pull-right" onclick="loadingPagia(); javascript:history.back(-1)">Voltar</a>
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
                //$dateIni  	  = new DateTime(date('Y-02-25'));
                //$dateIni  = new DateTime($documento->dataInicio);
                $dateIni  = new DateTime($movimentacao['dataMovimentacao']);
                $dateFim  = new DateTime($documento->dataFim);                               
                
                $diasTotal = $dateIni->diff($dateFim);
                //echo $diasTotal->days."<br>";
                
                // Recupera o total de dias da revisão
                $diasAprovacao = floor($dateIni->diff($dateFim)->days / $documento->numAprovadores);
                //echo $documento->numAprovadores;
                $diasFim = new DateTime(date('Y-m-d', strtotime($movimentacao['dataMovimentacao'] . '+ ' . ($diasAprovacao) . ' days')));
                //print_r($diasFim);
                $diasAtraso = $currentDate->diff($diasFim);
                //print_r($movimentacao);
                ?>
                <div class="col-md-6">
                    Código da solicitação: <strong><?php echo $documento->idSolicitacao; ?></strong><br>
                    Atividade atual: <strong><?php echo $movimentacao['descricao']; ?></strong>
                </div>                        
                <div class="col-md-6">
                    <input type="hidden" name="dataInicio" value="<?php echo $documento->dataInicio; ?>">
                    <input type="hidden" name="dataFim" value="<?php echo $documento->dataFim; ?>">
                    <input type="hidden" name="diasAprovacao" value="<?php echo $documento->diasAprovacao; ?>">
                    Início da revisão: <strong><?php echo date('d/m/Y', strtotime($documento->dataInicio)); ?></strong> &nbsp;&nbsp;&nbsp;&nbsp; Data para corte automático: <strong style="color:#ff0000"><?php echo date('d/m/Y', strtotime($documento->dataFim)); ?></strong><br>
                    Prazo limite para execução da tarefa: <strong <?php echo (($diasAtraso->invert == 1) ? 'style="color:#ff0000"' : '' ) ?>><?php echo ($documento->numAprovadores == 1) ? $dateFim->format('d/m/Y') : $diasFim->format('d/m/Y'); ?> <?php echo (($diasAtraso->invert == 1) ? ' - ' . $diasAtraso->days . ' dia(s) de atraso' : '' ) ?></strong>
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
                    Código da solicitação: <strong><?php echo $documento->idSolicitacao; ?></strong><br>                    
                </div>
                <div class="col-md-6">                    
                    Início da revisão: <strong><?php echo date('d/m/Y', strtotime($documento->dataInicio)); ?></strong> &nbsp;&nbsp;&nbsp;&nbsp; Data para corte automático: <strong style="color:#ff0000"><?php echo date('d/m/Y', strtotime($documento->dataFim)); ?></strong><br>                    
                </div>  
            </div>
            <?php endif; ?>
            <hr>
            <br>
            <div class="row">                
                <div class="col-md-4">
                    <label>Usuário</label>
                    <input type="text" name="usuario" id="usuario" class="form-control" readonly="readonly" value="<?php echo $documento->usuario ?>">
                    <input type="text" name="idusuario" id="idusuario" class="form-control hide" value="<?php echo $documento->idusuario ?>">
                </div>
                <div class="col-md-4">
                    <label>Usuário Totvs</label>
                    <input type="text" name="idTotvsRevisao" id="idTotvsRevisao" class="form-control" readonly="readonly" value="<?php echo $documento->idTotvs ?>">
                </div>
                <div class="col-md-4">
                    <label>Gestor do Usuário</label>
                    <input type="text" name="gestorUsuario" id="gestorUsuario" class="form-control" readonly="readonly" value="<?php echo $documento->gestorUsuario ?>">
                    <input type="text" name="idGestorUsuario" id="idGestorUsuario" class="form-control hide" readonly="readonly" value="<?php echo $documento->idGestorUsuario ?>">
                </div>
            </div>
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
                <table class="tabelaRevisao table table-striped table-bordered dataTable no-footer" role="grid" aria-describedby="datatable_info">
                    <thead>
                    <tr>
                        <th class="sorting_asc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                            aria-sort="ascending" aria-label="Name: activate to sort column descending"
                            style="width: 27%;">Grupo</th>
                        <!--<th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                            aria-label="Position: activate to sort column ascending" style="width: 30%;">Descricao
                        </th>-->
                        <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                            aria-label="Office: activate to sort column ascending" style="width: auto;">Gestor de Grupo</th>
                        <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                            aria-label="Office: activate to sort column ascending" style="width: auto;">Gestor de Módulo</th>
                        <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                            aria-label="Office: activate to sort column ascending" style="width: auto;">Gestor de Rotina</th>
                        <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                            aria-label="Office: activate to sort column ascending" style="width: auto;">Gestor de Programa</th>
                        <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                            aria-label="Office: activate to sort column ascending" style="width: 5%;">Manter</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $id = 0; ?>
                    <?php foreach ($documento->grupos as $value): ?>
                        <tr role="row" class="odd">
                            <td>
                                <input type="hidden" name="grupos[<?php echo $id ?>][idLinhaGrupo]" id="idLinhaGrupo__<?php echo $id ?>" value="<?php echo $value->idLinhaGrupo ?>">
                                <input type="hidden" name="grupos[<?php echo $id ?>][codGest]" id="codGestor__<?php echo $id ?>" value="<?php echo $value->codGest ?>">
                                <input type="hidden" name="grupos[<?php echo $id ?>][idGrupo]" id="idGrupo__<?php echo $id ?>" value="<?php echo $value->idGrupo ?>">
                                <input type="text" name="grupos[<?php echo $id ?>][idLegGrupo]" style="width: 100%"
                                       id="idLegGrupo___<?php echo $id ?>" class="form-control"
                                       value="<?php echo $value->idLegGrupo ?>" readonly="readonly">
                                <input type="text" style="width: 100%" id="idLinha___<?php echo $id ?>"
                                       class="form-control hide" value="<?php echo $id ?>" readonly="readonly">
                                <label>Descrição</label>
                                <input type="text" name="grupos[<?php echo $id ?>][descAbrev]" style="width: 100%" id="descAbrev___<?php echo $id ?>"
                                       class="form-control" value="<?php echo $value->descAbrev ?>" readonly="readonly">
                            </td>
                            <td>
                                <input type="text" name="grupos[<?php echo $id ?>][nomeGestor]" style="width: 100%"
                                       id="nomeGestor___<?php echo $id ?>" class="form-control"
                                       value="<?php echo $value->nomeGestor ?>" readonly="readonly">
                                <input type="text" name="grupos[<?php echo $id ?>][idCodGest]" style="width: 100%" id="idCodGest___<?php echo $id ?>"
                                       class="form-control hide" value="<?php echo $value->idCodGest ?>"
                                       readonly="readonly">
                                <input type="hidden" id="userAlt___<?php echo $id ?>" value="<?php echo (isset($userAlt[$value->idCodGest])) ? $userAlt[$value->idCodGest] : ''; ?>">
                                <input type="hidden" id="userAltSerSub___<?php echo $id ?>" value="<?php echo (isset($userAltSerSub[$value->idCodGest])) ? $userAltSerSub[$value->idCodGest] : ''; ?>">
                                <div class="aprovador">
                                    <label>Observação</label>
                                    <textarea class="form-control obsGestorGrupo" name="grupos[<?php echo $id ?>][obs]" id="obs___<?php echo $id ?>"
                                              style="width: 100%"><?php echo (isset($value->obs) && !empty($value->obs)) ? $value->obs : ''; ?></textarea>
                                </div>

                                <div class="aprovador">
                                    <label>Aprovação Gestor de Grupo</label>
                                    <select class="form-control aprovacao_gestor_grupo" name="grupos[<?php echo $id ?>][aprovacao]" id="aprovacao___<?php echo $id ?>" style="width: 100%">
                                        <?php if ($value->aprovacao == "sim"): ?>
                                            <option value="sim" selected="true">Sim</option>
                                            <option value="nao">Não</option>
                                            <option value=""></option>
                                        <?php elseif($value->aprovacao == "nao"): ?>
                                            <option value="nao" selected="true">Não</option>
                                            <option value="sim">Sim</option>
                                            <option value=""></option>
                                        <?php else: ?>
                                            <option value="" selected="true"></option>
                                            <option value="sim">Sim</option>
                                            <option value="nao">Não</option>
                                        <?php endif ?>
                                    </select>
                                </div>

                            </td>


                            <!-- Incluído gestor de modulo, rotina e programa -->
                            <?php foreach($gestMrp as $mrp): ?>                            
                            <td>                                
                                <?php $html = '';?>
                                <?php $aprovMod = false; ?>
                                <?php foreach($value->$mrp as $key => $mod): ?>
                                    <?php if(isset($mod->id)): ?>
                                        <?php $aprovMod = true; ?>
                                        <?php if($mod->id == $_SESSION['idUsrTotvs'] || isset($userAlt[$mrp][$key][$mod->id]) && $userAlt[$mrp][$key][$mod->id] == $_SESSION['idUsrTotvs']/* && $idAtividade == 12*/): ?>
                                            <input type="text" name="grupos[<?php echo $id ?>][<?php echo $mrp; ?>][<?php echo $key; ?>][nome]" style="width: 100%"
                                                   id="nome_<?php echo $mrp; ?>___<?php echo $id ?>" class="form-control aprovacao_<?php echo $mrp; ?> <?php echo (isset($mod->id) && $mod->id != '') ? '' : 'hide'; ?>"
                                                   value="<?php echo $mod->nome; ?>" readonly="readonly">
                                            <input type="text" name="grupos[<?php echo $id ?>][<?php echo $mrp; ?>][<?php echo $key; ?>][id]" style="width: 100%" id="id_<?php echo $mrp; ?>___<?php echo $id ?>"
                                                   class="form-control hide" value="<?php echo $mod->id; ?>"
                                                   readonly="readonly">
                                            <input type="hidden" id="userAlt_<?php echo $mrp; ?>___<?php echo $id ?>" value="<?php echo (isset($userAlt[$mrp][$key][$mod->id])) ? $userAlt[$mrp][$key][$mod->id] : ''; ?>">
                                            <input type="hidden" id="userAltSerSub_<?php echo $mrp; ?>___<?php echo $id; ?>" value="<?php echo (isset($userAltSerSub[$mrp][$key][$mod->id])) ? $userAltSerSub[$mrp][$key][$mod->id] : ''; ?>">
                                            <div class="aprovador <?php echo (isset($mod->id) && $mod->id != '') ? '' : 'hide'; ?>">
                                                <label>Observação</lbabel>
                                                <textarea class="form-control aprovacao_<?php echo $mrp; ?>" name="grupos[<?php echo $id ?>][<?php echo $mrp; ?>][<?php echo $key; ?>][obs]" id="obs_<?php echo $mrp; ?>___<?php echo $id ?>"
                                                          style="width: 100%"><?php echo (isset($mod->obs) && !empty($mod->obs)) ? $mod->obs : ''; ?></textarea>
                                            </div>

                                            <!-- Aprovador de Módulos -->
                                            <div class="aprovador <?php echo (isset($mod->id) && $mod->id != '') ? '' : 'hide'; ?>">
                                                <label>Aprovação Gestor de Módulo</label>
                                                <select class="form-control aprovacao_<?php echo $mrp; ?>" name="grupos[<?php echo $id ?>][<?php echo $mrp; ?>][<?php echo $key; ?>][aprovacao]" id="aprovacao_<?php echo $mrp; ?>___<?php echo $id ?>" style="width: 100%">
                                                    <?php if ($mod->aprovacao == "sim"): ?>
                                                        <option value="sim" selected="true">Sim</option>
                                                        <option value="nao">Não</option>
                                                        <option value=""></option>
                                                    <?php elseif($mod->aprovacao == "nao"): ?>
                                                        <option value="nao" selected="true">Não</option>
                                                        <option value="sim">Sim</option>
                                                        <option value=""></option>
                                                    <?php else: ?>
                                                        <option value="" selected="true"></option>
                                                        <option value="sim">Sim</option>
                                                        <option value="nao">Não</option>
                                                    <?php endif ?>
                                                </select>
                                            </div>
                                        <?php else: ?>
                                            <?php                                            
                                            $html .= "<tr><td>".$mod->nome."</td><td>";                                            
                                            if($mod->aprovacao == 'sim'):
                                                $html .= 'Aprovado';
                                                $html .= '<input type="hidden" name="grupos['.$id.']['.$mrp.']['.$key.'][nome]" value="'.$mod->nome.'">';
                                                $html .= '<input type="hidden" name="grupos['.$id.']['.$mrp.']['.$key.'][aprovacao]" value="sim">';
                                                $html .= '<input type="hidden" name="grupos['.$id.']['.$mrp.']['.$key.'][id]" value="'.$mod->id.'">';
                                                $html .= '<input type="hidden" name="grupos['.$id.']['.$mrp.']['.$key.'][obs]" value="'.((isset($mod->obs) && !empty($mod->obs)) ? $mod->obs : '').'">';
                                            elseif($mod->aprovacao == 'nao'):
                                                $html .= 'Reprovado';
                                                $html .= '<input type="hidden" name="grupos['.$id.']['.$mrp.']['.$key.'][nome]" value="'.$mod->nome.'">';
                                                $html .= '<input type="hidden" name="grupos['.$id.']['.$mrp.']['.$key.'][aprovacao]" value="nao">';
                                                $html .= '<input type="hidden" name="grupos['.$id.']['.$mrp.']['.$key.'][id]" value="'.$mod->id.'">';
                                                $html .= '<input type="hidden" name="grupos['.$id.']['.$mrp.']['.$key.'][obs]" value="'.((isset($mod->obs) && !empty($mod->obs)) ? $mod->obs : '').'">';
                                            else:
                                                $html .= 'Aguardando Aprovação';
                                                $html .= '<input type="hidden" name="grupos['.$id.']['.$mrp.']['.$key.'][nome]" value="'.$mod->nome.'">';
                                                $html .= '<input type="hidden" name="grupos['.$id.']['.$mrp.']['.$key.'][aprovacao]" value="">';
                                                $html .= '<input type="hidden" name="grupos['.$id.']['.$mrp.']['.$key.'][id]" value="'.$mod->id.'">';
                                                $html .= '<input type="hidden" name="grupos['.$id.']['.$mrp.']['.$key.'][obs]" value="'.((isset($mod->obs) && !empty($mod->obs)) ? $mod->obs : '').'">';
                                            endif;
                                            $html .= "</td></tr>"
                                            ?>
                                        <?php endif; ?>  
                                    <?php endif; ?> 
                                <?php endforeach; ?>
                                <?php if($aprovMod == false): ?>
                                    <input type="hidden" name="grupos[<?php echo $id; ?>][<?php echo $mrp; ?>][]" value="">
                                <?php endif; ?>
                                <?php if($html != ''): ?>
                                    <a href="#"><span class="badge badge-info histBadge pull-right" data-toggle="modal" data-target="#myModalHist_<?php echo $mrp; ?>">Histórico<br> de<br> Aprovação<br> de<br> <?php echo ((str_replace('gestor', '', $mrp) == 'modulo') ? 'Módulo' : ucfirst(str_replace('gestor', '', $mrp))) . 's'; ?></span></a>
                                <?php endif;?>
                                <!-- MODAL COM Historico de aprovacao Gestor modulo -->
                                <div id="myModalHist_<?php echo $mrp; ?>" class="modal fade" role="dialog">
                                    <div class="modal-dialog modal-lg">
                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title"><!--<i class="fa fa-exclamation"></i>--> Histórico de aprovação de <?php echo ((str_replace('gestor', '', $mrp) == 'modulo') ? 'Módulo' : ucfirst(str_replace('gestor', '', $mrp))) . 's'; ?></h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-12">
                                                        <table class="tabela table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline"
                                                               cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info"
                                                               style="width: 100%;">
                                                            <thead>
                                                            <tr role="row">                                                                
                                                                <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1"
                                                                    colspan="1" style="width: 20%;"
                                                                    aria-label="Last name: activate to sort column ascending">Nome Gestor
                                                                </th>
                                                                <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1"
                                                                    colspan="1" style="width: 15%;"
                                                                    aria-label="Last name: activate to sort column ascending">Ação
                                                                </th>                                                                
                                                            </thead>
                                                            <tbody>
                                                            <?php echo $html; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>                                
                            </td>
                            <?php endforeach; ?>                                                                                                               
                            
                            <td>
                                <center>
                                    <input type="checkbox" name="manter[]"
                                           id="manter___<?php echo $id ?>" <?php echo (isset($value->manterStatus) && $value->manterStatus == 1 || $value->manterStatus == true) ? 'checked="checked"' : '' ?>
                                            <?php //echo ($value->aprovacao == 'sim') ? 'disabled="disabled"' : ''; ?>
                                           class="manter materStatus">
                                    <input type="text" class="hide" name="grupos[<?php echo $id ?>][manterStatus]"
                                           id="manterStatus___<?php echo $id ?>" value="<?php echo (isset($value->manterStatus) && $value->manterStatus == 1 || $value->manterStatus == true) ? $value->manterStatus : 0; ?>"><br>
                                    <!--<label>Observação</label>
                                <textarea class="form-control obsGestorUsuario" name="grupos[<?php /*echo $id */?>][obsGestorUsuario]" id="obsGestorUsuario___<?php /*echo $id */?>"
                                          style="width: 100%"><?php /*echo (isset($value->obsGestorUsuario) && !empty($value->obsGestorUsuario)) ? $value->obsGestorUsuario : ''; */?></textarea>-->
                                </center>
                            </td>
                        </tr>
                        <?php $id++ ?>
                    <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="row aprovacao_si hide">
                    <div class="clearfix"><hr></div>
                    <div class="col-md-4">
                        <label>Aprovação S.I</label>
                        <select class="form-control" name="aprovacao_si" id="aprovacao_si">
                            <option value="" <?php echo (isset($documento->aprovacao_si) && $documento->aprovacao_si == '') ? 'selected="true"' : '' ?>></option>
                            <option value="1" <?php echo (isset($documento->aprovacao_si) && $documento->aprovacao_si == '1') ? 'selected="true"' : '' ?>>Sim</option>
                            <option value="0" <?php echo (isset($documento->aprovacao_si) && $documento->aprovacao_si == '0') ? 'selected="true"' : '' ?>>Não</option>
                        </select>
                    </div>
                    <div class="col-md-3 <?php echo (isset($documento->aprovacao_si) && $documento->aprovacao_si == 'nao' || $documento->aprovacao_si == '') ? 'hide' : ''; ?>" id="si-movimentacao">
                        <label>Movimentar para</label>
                        <select class="form-control" name="si_atividades">
                            <option value=""></option>
                            <?php foreach($atividades as $val): ?>
                            <option value="<?php echo $val['id'].'-'.$val['objeto']; ?>"><?php echo $val['descricao']; ?></option>
                            <?php endforeach;?>
                        </select>
                        
                    </div>
                </div>

                <div class="clearfix"><hr></div>

                <div class="row obs_historico">
                    <div class="col-md-12">
                        <label>Observação</label>
                        <textarea class="form-control" name="obs_historico"></textarea>
                    </div>
                </div>

                <div class="clearfix"><hr></div>

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
                                                <a href="#tab_content2" id="profile-tab2" role="tab" data-toggle="tab" aria-expanded="true">Programas <span class="badge totalProgByGrupo"></span></a>
                                            </li>
                                            <li role="presentation">
                                                <a href="#tab_content3" id="profile-tab3" role="tab" data-toggle="tab" aria-expanded="true">Histórico de observações <span class="badge"><?php echo count($historicoMsg); ?></span></a>
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
                                                                    <h5 class="text-center" id="text-risco">Analisando riscos...</h5>
                                                                </div>
                                                                <div id="matriz-risco-grupos"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div role="tabpane1" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab2"><!--Inicio Tabela 1 -->
                                                
                                                
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
                                            <div role="tabpane1" class="tab-pane fade" id="tab_content3" aria-labelledby="profile-tab3"><!--Inicio Tabela 1 -->
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="x_panel">
                                                            <div class="x_title">
                                                                <h2>Histórico de observações</h2>
                                                                <ul class="nav navbar-right panel_toolbox">
                                                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
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
                                                                                        rowspan="1" colspan="1" style="width: 5%;" aria-sort="ascending"
                                                                                        aria-label="First name: activate to sort column descending">Seq.
                                                                                    </th>
                                                                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1"
                                                                                        colspan="1" style="width: 20%;"
                                                                                        aria-label="Last name: activate to sort column ascending">Atividade
                                                                                    </th>
                                                                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1"
                                                                                        colspan="1" style="width: 15%;"
                                                                                        aria-label="Last name: activate to sort column ascending">Autor
                                                                                    </th>
                                                                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1"
                                                                                        colspan="1" style="width: 47%;"
                                                                                        aria-label="Last name: activate to sort column ascending">Mensagem
                                                                                    </th>
                                                                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1"
                                                                                        colspan="1" style="width: 18%;"
                                                                                        aria-label="Position: activate to sort column ascending">Data
                                                                                    </th>
                                                                                </thead>
                                                                                <tbody>
                                                                                <?php $i = 1;?>
                                                                                <?php foreach ($historicoMsg as $value): ?>
                                                                                    <tr>
                                                                                        <td><?php echo $i; ?></td>
                                                                                        <td><?php echo $value['atividade']; ?></td>
                                                                                        <td><?php echo $value['autor']; ?></td>
                                                                                        <td><?php echo $value['msg']; ?></td>
                                                                                        <td><?php echo date("d/m/Y à\s h:i:s", strtotime($value['dataCriacao'])); ?></td>
                                                                                    </tr>
                                                                                    <?php $i++; ?>
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
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                <?php if($idAtividade == 0): ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Snapshot da Solicitação</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                    </li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <div role="tabpane2" class="tab-pane fade active in" id="tab_content20" aria-labelledby="profile-tab20"><!--Inicio Tabela 1 -->
                                    <div class="" role="tabpane2" data-example-id="togglable-tabs2">
                                        <ul id="myTab2" class="nav nav-tabs bar_tabs" role="tablist">
                                            <li role="presentation" class="active">
                                                <a href="#tab_content4" id="profile-tab4" role="tab" data-toggle="tab" aria-expanded="true">Riscos <span class="badge badge-danger" id="count-riscos-foto"></span></a>
                                            </li>
                                            <li role="presentation">
                                                <a href="#tab_content5" id="profile-tab5" role="tab" data-toggle="tab" aria-expanded="true">Programas <span class="badge"><?php echo $totalProg; ?></span></a>
                                            </li>
                                        </ul>
                                        <div id="myTabContent2" class="tab-content">
                                            <div role="tabpane2" class="tab-pane fade active in" id="tab_content4" aria-labelledby="profile-tab4"><!--Inicio Tabela 1 -->
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
                                                                <div id="matriz-risco-grupos-foto"></div>
                                                                <script>
                                                                    $(document).ready(function(){                                                                        
                                                                        $.ajax({
                                                                           type: 'POST' ,
                                                                           url: url + 'fluxo/ajaxMatrizDeRiscoFoto',
                                                                           data: {idSolicitacao: $('#idSolicitacao').val()},
                                                                           success: function(data){
                                                                               var res = JSON.parse(data);
                                                                               $('#count-riscos-foto').text(res.totalRiscos);
                                                                               $('#matriz-risco-grupos-foto').html(res.html);
                                                                           }
                                                                        });                                                                        
                                                                    });
                                                                </script>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div role="tabpane2" class="tab-pane fade" id="tab_content5" aria-labelledby="profile-tab5"><!--Inicio Tabela 1 -->
                                                <?php $this->helper->tabLogProgramas($documento->idSolicitacao); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
    <div id="myModalTimeline" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><!--<i class="fa fa-exclamation"></i>--> Timeline da Solicitação</h4>
                    <strong style="color: #b7b7b7">Data Inicial: </strong> <span style="color: #b7b7b7"><?php echo $documento->dataInicio; ?></span> &nbsp;&nbsp;&nbsp;
                    <strong style="color: #b7b7b7">Data Final: </strong> <span style="color: #b7b7b7"><?php echo $documento->dataFim; ?></span>
                </div>
                <div class="modal-body">                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="container">
                                <div class="page-header">
                                    <h1 id="timeline">Timeline</h1>
                                </div>                                                                     
                            
                                <ul class="timeline">                                                                            
                                    <?php foreach($timeline as $val): ?>
                                        
                                        <li>
                                            <div class="timeline-badge success"><i class="fa fa-exchange"></i></div>
                                            <div class="<?php echo ($i % 2 == 0) ? 'timeline-panel' : 'timeline-panel-inverted'; ?>">
                                                <div class="timeline-heading">
                                                    <h4 class="timeline-title"><strong>Movimentado para:</strong> <span><?php echo $val['descAtividade']; ?></span></h4>
                                                    <p><small class="text-muted"><i class="glyphicon glyphicon-time"></i> <?php echo date('d/m/Y à\s H:i:s', strtotime($val['dataMovimentacao'])); ?></small></p>
                                                </div>
                                                <div class="timeline-body">
                                                    <p><strong>Mensagem:</strong> <?php echo nl2br($val['msg']); ?></p>
                                                </div>
                                            </div>
                                            <div class="<?php echo ($i % 2 == 0) ? 'timeline-panel-inverted' : 'timeline-panel'; ?>">
                                                <div class="timeline-heading">
                                                    <!--<p><small class="text-muted"><i class="glyphicon glyphicon-time"></i> 23/01/2019 às 14:35</small></p>-->
                                                    <h4 class="timeline-title"><strong>AVALIAÇÃO DO RESPONSÁVEL</strong></h4>
                                                </div>
                                                <div class="timeline-body">
                                                    <table class="table table-striped table-borderedno-footer" role="grid">
                                                        <thead>
                                                        <tr role="row">
                                                            <th style="width: 40%;">Grupo</th>
                                                            <th style="width: 60%;">Movimentação</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>                                                            
                                                            <?php $jsonDoc = json_decode($val['documento']); ?>
                                                            <?php if($val['status'] == 0 || !isset($jsonDoc->grupos)): ?>
                                                                <?php foreach($jsonDoc->grupos as $grupo): ?>
                                                                <tr role="row" class="odd">
                                                                    <td>
                                                                        <div class="text-center">
                                                                            <?php echo $grupo->idLegGrupo .' - '. $grupo->descAbrev; ?><br>
                                                                            <label class="control-label" for="inputSuccess"><i class="fa fa-check"></i> <?php echo ($grupo->manterStatus == 1) ? 'Manter' : 'Não manter'; ?> </label>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="obs-timeline">
                                                                            <?php                                                                             
                                                                            if($val['idAtividade'] == 7):
                                                                                echo $val['autor'];
                                                                            endif; 
                                                                            ?>
                                                                            <?php if($grupo->manterStatus == 0): ?>
                                                                                <label class="control-label red"><i class="fa fa-trash-o"></i> Remover</label>
                                                                            <?php else: ?>
                                                                                
                                                                                <?php if($val['idAtividade'] == 8): ?>
                                                                                    <strong><?php echo $grupo->nomeGestor; ?></strong><br>
                                                                                    <?php if($grupo->aprovacao == '' && $val['idAtividade'] == 7): ?>
                                                                                        <label class="control-label blue"><i class="fa fa-question-circle"></i> Pendente de Aprovação</label>

                                                                                    <?php elseif($grupo->aprovacao == 'sim' && $val['idAtividade'] > 7): ?>
                                                                                        <label class="control-label green"><i class="fa fa-check"></i> Aprovado</label>

                                                                                    <?php else: ?>
                                                                                        <label class="control-label red"><i class="fa fa-close"></i> Reprovado</label>
                                                                                    <?php endif; ?>
                                                                                    <?php echo "<br>".$grupo->obs; ?>
                                                                                <?php endif; ?>
                                                                                        
                                                                                <?php
                                                                                    $tipoAtividade = '';               
                                                                                    $labels = '';
                                                                                    switch ($val['idAtividade']):
                                                                                        case 12:
                                                                                            $tipoAtividade = 'gestorModulo';                                                                                            
                                                                                            break;
                                                                                        case 13:
                                                                                            $tipoAtividade = 'gestorRotina';                                                                                            
                                                                                            break;
                                                                                        case 14:
                                                                                            $tipoAtividade = 'gestorPrograma';                                                                                            
                                                                                            break;                                                                                        
                                                                                    endswitch;
                                                                                ?>
                                                                                        
                                                                                <!-- Area de gestor de módulos -->
                                                                                <?php if($val['idAtividade'] == 12 || $val['idAtividade'] == 13 || $val['idAtividade'] == 14 ): ?>
                                                                                    <?php foreach($grupo->$tipoAtividade as $key => $mod): ?>
                                                                                        <?php if((isset($mod->id) && $mod->id != '')): ?>
                                                                                            <strong><hr class="line-in-timeline"><?php echo $mod->nome; ?></strong><br>
                                                                                            <?php if($mod->aprovacao  == ''/* && $val['idAtividade'] == 7*/): ?>

                                                                                                <?php if($grupo->manterStatus == 1): ?>
                                                                                                <label class="control-label blue"><i class="fa fa-question-circle"></i> Pendente de Aprovação</label>
                                                                                                <?php else: ?>
                                                                                                <label class="control-label red"><i class="fa fa-trash-o"></i> Remover</label>
                                                                                                <?php endif; ?>

                                                                                            <?php elseif($mod->aprovacao == 'sim'): ?>
                                                                                                <label class="control-label green"><i class="fa fa-check"></i> Aprovado</label>

                                                                                            <?php elseif($mod->aprovacao == 'nao'): ?>
                                                                                                <label class="control-label red"><i class="fa fa-close"></i> Reprovado</label>
                                                                                            <?php endif; ?>
                                                                                            <?php echo "<br>".$mod->obs; ?>
                                                                                        <?php endif; ?>
                                                                                    <?php endforeach; ?>
                                                                                <?php endif; ?>
                                                                                                
                                                                                              
                                                                                
                                                                            <?php endif; ?>

                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <?php endforeach; ?>
                                                            <?php else: ?>
                                                                <tr role="row" class="odd">
                                                                    <td colspan="2">
                                                                        <div class="text-center"><strong>AGUARDANDO AVALIAÇÃO</strong></div>
                                                                    </td>
                                                                </tr>
                                                            <?php endif; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </li>
                                    <?php $i++; ?>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Voltar</button>
                </div>
            </div>
        </div>
    </div>


    </div></div>
    <script>
        $(document).ready(function(){
            // Carrega datatable com programas de cada grupo selecionado
            table = $('#tableAbaProgs').DataTable({
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
                    url: url + "Fluxo/ajaxCarregaAbaProsRevisaoAcesso/",
                    "data": function (d){
                        d.grupos = function(){
                            grupos = new Array();
                            linha = 0;
                            
                            $('.materStatus').each(function() {
                                if($("#manterStatus___"+linha).val() == 'true' || $("#manterStatus___"+linha).val() == "on" || $("#manterStatus___"+linha).val() == 1){
                                    grupos.push($('#idGrupo__'+linha).val());
                                }
                                linha++;
                            });
                            return grupos;

                        };
                    }                    
                }
            });                                    
            
            if($("#numAtividade").val() == 0){
                $('textarea').attr('disabled', 'true');
                $('input').attr('disabled', 'true');
                $('select').attr('disabled', 'true');
                $('checkbox').attr('disabled', 'true');
                $('.obs_historico').css('display','none');
            }


            $('.tabelaRevisao').DataTable( {
                "language": {
                    "lengthMenu": "Mostrar _MENU_ registros",
                    "zeroRecords": "Nenhum registro encontrado",
                    "info": "Página _PAGE_ de _PAGES_",
                    "infoEmpty": "Nenhum registro para ser exibido",
                    "search":"Pesquisar:",
                    "paginate": {
                        "first":      "Primeiro",
                        "last":       "Último",
                        "next":       "Próximo",
                        "previous":   "Anterior"
                    },
                    "infoFiltered": ""
                }
            });

            // Aprovação de gestor
            if($("#numAtividade").val() == "7"){
                //$(".aprovador").hide();


                // Desabilita os selects de aprovação
                $(".aprovador select").attr("readonly","true");
                $(".aprovador select").css({
                    'pointer-events': 'none',
                    'touch-action': 'none'
                });
                $(".aprovador textarea").attr("readonly","true");

                // Desabilita os selects de aprovação SI
                //$('.aprovacao_si').removeClass('hide');
                $(".aprovacao_si select").attr("readonly","true");
                $(".aprovacao_si select").css({
                    'pointer-events': 'none',
                    'touch-action': 'none'
                });
                $(".aprovacao_si textarea").attr("readonly","true");

                $(".aprovacao_gestorModulo").attr("readonly","true");
                $(".aprovacao_gestorModulo").css({
                    'pointer-events': 'none',
                    'touch-action': 'none'
                });
                
                $(".aprovacao_gestorRotina").attr("readonly","true");
                $(".aprovacao_gestorRotina").css({
                    'pointer-events': 'none',
                    'touch-action': 'none'
                });
                
                $(".aprovacao_gestorPrograma").attr("readonly","true");
                $(".aprovacao_gestorPrograma").css({
                    'pointer-events': 'none',
                    'touch-action': 'none'
                });

                $('.aprovador select option:selected[value="nao"]').closest('tr').css('background-color', 'rgb(237, 185, 185)');

                // Evento ao clicar no checkbox manter
                $(".manter").click(function(){
                    var teste = $(this).is(":checked");
                    var arr = [] ;

                    $('input[id^="idCodGest___"]').each(function(x){
                        var context = $(this);

                        var linha = context.attr('id').split("___")[1];
                        var manter = $("#manter___"+linha).is(":checked");

                        if(manter == true && arr.indexOf($("#idCodGest___" + linha).val()) === -1){
                            arr.push($("#idCodGest___" + linha).val());
                        }
                    });

                    $("#aprovadorGrupo").val(arr);
                });


                $(".materStatus").click(function(){
                    var context = $(this);
                    var status = $(this).is(":checked");
                    var linha = context.attr('id').split("___")[1];
                    $("#manterStatus___"+linha).val(status);
                });
                $("input[name='bloquearUsuario']").removeAttr('readonly');

                setTimeout(function(){ concatenaDescRevisao(); }, 300);
                setTimeout(function(){concatenaDescRevisao(); }, 600);

                // Valida Aprovação ou Reprovação
                $('#btnEnviar').on('click', function () {
                    $('#frmRevisaoAcesso').submit();
                });
            }

            // Aprovação de grupos
            if($("#numAtividade").val() == "8"){
                $(".manter").attr("disabled","disabled");
                $("#bloquearUsuario").attr("disabled","disabled");

                //$('.aprovacao_si').removeClass('hide');

                // Desabilita os selects de aprovação do si e gestor de modulo
                $(".aprovacao_si select").attr("readonly","true");
                $(".aprovacao_si select").css({
                    'pointer-events': 'none',
                    'touch-action': 'none'
                });
                $(".aprovacao_si textarea").attr("readonly","true");
                $("textarea.obsGestorUsuario").attr("readonly","true");

                $(".aprovacao_gestor_modulo").attr("readonly","true");
                $(".aprovacao_gestor_modulo").css({
                    'pointer-events': 'none',
                    'touch-action': 'none'
                });

                $('input[id^="idCodGest___"]').each(function(){
                    var context = $(this);
                    var linha = context.attr('id').split("___")[1];

                    // Ou se o usuário logado não for o gestor de grupo.
                    // Ou se usuário a ser substituido não for o gestor
                    if($('#idCodGest___'+linha).val() != $("#usrLogado").val() && $("#usrLogado").val() != $("#userAlt___"+linha).val()){
                        $("#aprovacao___"+linha).attr("readonly","true");
                        $("#aprovacao___"+linha).css({
                            'pointer-events': 'none',
                            'touch-action': 'none'
                        });
                        $("#obs___"+linha).attr("readonly","true");
                    }

                    // Insere atributo readonly caso manterStatus = false.
                    //console.log($("#manterStatus___"+linha).val() + ' linha: ' + linha)
                    if($("#manterStatus___"+linha).val() == "false" || $("#manterStatus___"+linha).val() == 0){
                        $("#aprovacao___"+linha).attr("readonly","true");
                        $("#aprovacao___"+linha).css({
                            'pointer-events': 'none',
                            'touch-action': 'none'
                        });
                        $("#obs___"+linha).attr("readonly","true");
                    }
                });

                // Valida Aprovação ou Reprovação
                $('#btnEnviar').on('click', function () {
                    // Valida se o gestor aprovou ou reprovou os registros que estão pra ele
                    var vazio = false;

                    // Percorre os selects e valida se foi aprovado ou reprovado.
                    // Se o valor for vazio e o grupo pertencer ao usuário logado
                    // mostra o modal com alerta.
                    $(".aprovador select").each(function () {
                        if($(this).attr('readonly') == 'readonly'){
                        }else{
                            if($(this).val() == ''){
                                vazio = true;
                            }
                        }
                    });

                    if(vazio == false){
                        $('#frmRevisaoAcesso').submit();
                    }else{
                        $('#load').css('display','none');
                        $('.modal-title').text('');
                        $('#result_msg').text('Favor aprovar ou reprovar os acessos!');
                        $('#myModalResult').modal('show');
                    }
                })
            }

            // Aprovação de módulos
            if($("#numAtividade").val() == "10"){
                $(".manter").attr("disabled","disabled");
                $("#bloquearUsuario").attr("disabled","disabled");

                // Desabilita os selects de aprovação do si e gestor de grupos, rotinas e programas
                $(".aprovacao_si select").attr("readonly","true");
                $(".aprovacao_si select").css({
                    'pointer-events': 'none',
                    'touch-action': 'none'
                });
                $(".aprovacao_si textarea").attr("readonly","true");
                $("textarea.obsGestorUsuario").attr("readonly","true");
                $("textarea.obsGestorGrupo").attr("readonly","true");

                $(".aprovacao_gestor_grupo").attr("readonly","true");
                $(".aprovacao_gestor_grupo").css({
                    'pointer-events': 'none',
                    'touch-action': 'none'
                });
                
                $(".aprovacao_gestorRotina").attr("readonly","true");
                $(".aprovacao_gestorRotina").css({
                    'pointer-events': 'none',
                    'touch-action': 'none'
                });
                
                $(".aprovacao_gestorPrograma").attr("readonly","true");
                $(".aprovacao_gestorPrograma").css({
                    'pointer-events': 'none',
                    'touch-action': 'none'
                });
                                               
                $('input[id^="id_gestorModulo___"]').each(function(){
                    var context = $(this);
                    var linha = context.attr('id').split("___")[1];

                    // Ou se o usuário logado não for o gestor de grupo.
                    // Ou se usuário a ser substituido não for o gestor
                    if($('#id_gestorModulo___'+linha).val() != $("#usrLogado").val() && $("#usrLogado").val() != $("#userAlt_gestorModulo___"+linha).val()){
                        $("#aprovacao_gestorModulo___"+linha).attr("readonly","true");
                        $("#aprovacao_gestorModulo___"+linha).css({
                            'pointer-events': 'none',
                            'touch-action': 'none'
                        });
                        $("#obs_gestorModulo___"+linha).attr("readonly","true");
                    }

                    // Insere atributo readonly caso manterStatus = false.
                    //console.log($("#manterStatus___"+linha).val() + ' linha: ' + linha)
                    if(($("#manterStatus___"+linha).val() == "false" || $("#manterStatus___"+linha).val() == 0) || $("#aprovacao_gestorModulo___"+linha).val() == 'sim'){
                        $("#aprovacao_gestorModulo___"+linha).attr("readonly","true");
                        $("#aprovacao_gestorModulo___"+linha).css({
                            'pointer-events': 'none',
                            'touch-action': 'none'
                        });
                        $("#obs_gestorModulo___"+linha).attr("readonly","true");
                    }
                });

                // Valida Aprovação ou Reprovação
                $('#btnEnviar').on('click', function () {
                    // Valida se o gestor aprovou ou reprovou os registros que estão pra ele
                    var vazio = false;

                    // Percorre os selects e valida se foi aprovado ou reprovado.
                    // Se o valor for vazio e o grupo pertencer ao usuário logado
                    // mostra o modal com alerta.
                    $(".aprovador select").each(function () {
                        if($(this).attr('readonly') == 'readonly'){
                        }else{
                            if($(this).val() == ''){
                                vazio = true;
                            }
                        }
                    });

                    if(vazio == false){
                        $('#frmRevisaoAcesso').submit();
                    }else{
                        $('#load').css('display','none');
                        $('.modal-title').text('');
                        $('#result_msg').text('Favor aprovar ou reprovar os acessos!');
                        $('#myModalResult').modal('show');
                    }
                });
            }

            // Aprovação de rotinas
            if($("#numAtividade").val() == "11"){
                $(".manter").attr("disabled","disabled");
                $("#bloquearUsuario").attr("disabled","disabled");

                // Desabilita os selects de aprovação do si e gestor de grupos, módulos e programas
                $(".aprovacao_si select").attr("readonly","true");
                $(".aprovacao_si select").css({
                    'pointer-events': 'none',
                    'touch-action': 'none'
                });
                $(".aprovacao_si textarea").attr("readonly","true");
                $("textarea.obsGestorUsuario").attr("readonly","true");
                $("textarea.obsGestorGrupo").attr("readonly","true");

                $(".aprovacao_gestor_grupo").attr("readonly","true");
                $(".aprovacao_gestor_grupo").css({
                    'pointer-events': 'none',
                    'touch-action': 'none'
                });
                
                $(".aprovacao_gestorModulo").attr("readonly","true");
                $(".aprovacao_gestorModulo").css({
                    'pointer-events': 'none',
                    'touch-action': 'none'
                });
                
                $(".aprovacao_gestorPrograma").attr("readonly","true");
                $(".aprovacao_gestorPrograma").css({
                    'pointer-events': 'none',
                    'touch-action': 'none'
                });
                                               
                $('input[id^="id_gestorRotina___"]').each(function(){
                    var context = $(this);
                    var linha = context.attr('id').split("___")[1];

                    // Ou se o usuário logado não for o gestor de grupo.
                    // Ou se usuário a ser substituido não for o gestor
                    if($('#id_gestorRotina___'+linha).val() != $("#usrLogado").val() && $("#usrLogado").val() != $("#userAlt_gestorRotina___"+linha).val()){
                        $("#aprovacao_gestorRotina___"+linha).attr("readonly","true");
                        $("#aprovacao_gestorRotina___"+linha).css({
                            'pointer-events': 'none',
                            'touch-action': 'none'
                        });
                        $("#obs_gestorRotina___"+linha).attr("readonly","true");
                    }

                    // Insere atributo readonly caso manterStatus = false.
                    //console.log($("#manterStatus___"+linha).val() + ' linha: ' + linha)
                    if($("#manterStatus___"+linha).val() == "false" || $("#manterStatus___"+linha).val() == 0){
                        $("#aprovacao_gestorRotina___"+linha).attr("readonly","true");
                        $("#aprovacao_gestorRotina___"+linha).css({
                            'pointer-events': 'none',
                            'touch-action': 'none'
                        });
                        $("#obs_gestorRotina___"+linha).attr("readonly","true");
                    }
                });
                
                // Valida Aprovação ou Reprovação
                $('#btnEnviar').on('click', function () {
                    // Valida se o gestor aprovou ou reprovou os registros que estão pra ele
                    var vazio = false;

                    // Percorre os selects e valida se foi aprovado ou reprovado.
                    // Se o valor for vazio e o grupo pertencer ao usuário logado
                    // mostra o modal com alerta.
                    $(".aprovador select").each(function () {
                        if($(this).attr('readonly') == 'readonly'){
                        }else{
                            if($(this).val() == ''){
                                vazio = true;
                            }
                        }
                    });

                    if(vazio == false){
                        $('#frmRevisaoAcesso').submit();
                    }else{
                        $('#load').css('display','none');
                        $('.modal-title').text('');
                        $('#result_msg').text('Favor aprovar ou reprovar os acessos!');
                        $('#myModalResult').modal('show');
                    }
                });
            }
            
            
            // Aprovação de programas
            if($("#numAtividade").val() == "12"){
                $(".manter").attr("disabled","disabled");
                $("#bloquearUsuario").attr("disabled","disabled");

                // Desabilita os selects de aprovação do si e gestor de grupos, módulos e rotinas
                $(".aprovacao_si select").attr("readonly","true");
                $(".aprovacao_si select").css({
                    'pointer-events': 'none',
                    'touch-action': 'none'
                });
                $(".aprovacao_si textarea").attr("readonly","true");
                $("textarea.obsGestorUsuario").attr("readonly","true");
                $("textarea.obsGestorGrupo").attr("readonly","true");

                $(".aprovacao_gestor_grupo").attr("readonly","true");
                $(".aprovacao_gestor_grupo").css({
                    'pointer-events': 'none',
                    'touch-action': 'none'
                });
                
                $(".aprovacao_gestorModulo").attr("readonly","true");
                $(".aprovacao_gestorModulo").css({
                    'pointer-events': 'none',
                    'touch-action': 'none'
                });
                
                $(".aprovacao_gestorRotina").attr("readonly","true");
                $(".aprovacao_gestorRotina").css({
                    'pointer-events': 'none',
                    'touch-action': 'none'
                });
                                               
                $('input[id^="id_gestorPrograma___"]').each(function(){
                    var context = $(this);
                    var linha = context.attr('id').split("___")[1];

                    // Ou se o usuário logado não for o gestor de grupo.
                    // Ou se usuário a ser substituido não for o gestor
                    if($('#id_gestorPrograma___'+linha).val() != $("#usrLogado").val() && $("#usrLogado").val() != $("#userAlt_gestorPrograma___"+linha).val()){
                        $("#aprovacao_gestorPrograma___"+linha).attr("readonly","true");
                        $("#aprovacao_gestorPrograma___"+linha).css({
                            'pointer-events': 'none',
                            'touch-action': 'none'
                        });
                        $("#obs_gestorPrograma___"+linha).attr("readonly","true");
                    }

                    // Insere atributo readonly caso manterStatus = false.
                    //console.log($("#manterStatus___"+linha).val() + ' linha: ' + linha)
                    if($("#manterStatus___"+linha).val() == "false" || $("#manterStatus___"+linha).val() == 0){
                        $("#aprovacao_gestorPrograma___"+linha).attr("readonly","true");
                        $("#aprovacao_gestorPrograma___"+linha).css({
                            'pointer-events': 'none',
                            'touch-action': 'none'
                        });
                        $("#obs_gestorPrograma___"+linha).attr("readonly","true");
                    }
                });
                
                // Valida Aprovação ou Reprovação
                $('#btnEnviar').on('click', function () {
                    // Valida se o gestor aprovou ou reprovou os registros que estão pra ele
                    var vazio = false;

                    // Percorre os selects e valida se foi aprovado ou reprovado.
                    // Se o valor for vazio e o grupo pertencer ao usuário logado
                    // mostra o modal com alerta.
                    $(".aprovador select").each(function () {
                        if($(this).attr('readonly') == 'readonly'){
                        }else{
                            if($(this).val() == ''){
                                vazio = true;
                            }
                        }
                    });

                    if(vazio == false){
                        $('#frmRevisaoAcesso').submit();
                    }else{
                        $('#load').css('display','none');
                        $('.modal-title').text('');
                        $('#result_msg').text('Favor aprovar ou reprovar os acessos!');
                        $('#myModalResult').modal('show');
                    }
                });
            }


            // Aprovação SI
            if($("#numAtividade").val() == "13"){
                $('.aprovacao_si').removeClass('hide');
                $(".manter").attr("disabled","disabled");
                $("#bloquearUsuario").attr("disabled","disabled");

                $('input[id^="idCodGest___"]').each(function(){
                    var context = $(this);
                    var linha = context.attr('id').split("___")[1];

                    // Remove ação dos combos e textarea
                    $("#aprovacao___"+linha).attr("readonly","true");
                    $("#aprovacao___"+linha).css({
                        'pointer-events': 'none',
                        'touch-action': 'none'
                    });
                    $("#obs___"+linha).attr("readonly", "true");
                    $("textarea.obsGestorUsuario").attr("readonly", "true");

                    $(".aprovacao_gestorModulo, .aprovacao_gestorPrograma, .aprovacao_gestorRotina").attr("readonly","true");
                    $(".aprovacao_gestorModulo, .aprovacao_gestorPrograma, .aprovacao_gestorRotina").css({
                        'pointer-events': 'none',
                        'touch-action': 'none'
                    });
                });
                

                // Submete o formulario
                $('#btnEnviar').on('click', function () {
                    if($(".aprovacao_si select").val() == 1 || $(".aprovacao_si select").val() == 'on' || $(".aprovacao_si select").val() == 0) {
                        $('#frmRevisaoAcesso').submit();
                    }else{
                        $('#load').css('display', 'none');
                        $('.modal-title').text('');
                        $('#result_msg').text('Favor aprovar ou reprovar os acessos!');
                        $('#myModalResult').modal('show');
                    }
                });
                
                
                // Valida se foi selecionado reprova e mostra select com atividades
                $(".aprovacao_si select").on('change', function(){
                    if($(this).val() == 'nao'){
                        $('si_atividades').removeClass('hide');
                    }else{
                        $('si_atividades').addClass('hide');
                    }
                });
            }

            // Evento ao apertar algum checkbox manter status
            $('.manter').change(function() {
                var line = $(this).attr('id').split('___');

                if($(this).is(":checked")) {
                    $('#manterStatus___'+line[1]).val(1);
                }else{
                    $('#manterStatus___'+line[1]).val(0);
                }

            });

            // Evento ao apertar checkbox Bloquear Usuário
            $('#bloquearUsuario').change(function() {
                if($(this).is(":checked")) {
                    $('#bloqueaUsuario').val(1);
                }else{
                    $('#bloqueaUsuario').val(0);
                }

            });

            // Recupera riscos dos grupos
            $('.materStatus').on('change', function () {
                $('#load').css('display', 'block');
                $('#text-risco').css('display', 'block');

                $('#matriz-risco-grupos').html('');               
                carregaRiscosGrupos();                                                             
                $('#tableAbaProgs').DataTable().ajax.reload();
                
            });
                                            
            // Recupera matriz de risco de grupos ao entrar na pagina
            carregaRiscosGrupos();
            
            $('#aprovacao_si').on('change', function(){                
                if($(this).val() == '0'){
                    $('#si-movimentacao').removeClass('hide');
                }else{
                    $('#si-movimentacao').addClass('hide');
                }
            });
        });                                               
        
        
        // Recupera matriz de risco
        function carregaRiscosGrupos(){
            var grupos = new Array();
            var linha = 0;
            $('.materStatus').each(function() {
                if($("#manterStatus___"+linha).val() == 'true' || $("#manterStatus___"+linha).val() == "on" || $("#manterStatus___"+linha).val() == 1){
                    grupos.push($('#idGrupo__'+linha).val());
                }
                linha++;
            });

            if(grupos.length == 0) {
                grupos.push(0);
            }
            $.ajax({
                type: 'POST',
                url:  url+'fluxo/ajaxMatrizDeRisco',
                data: {grupos: grupos},
                success: function(res){
                    var data = JSON.parse(res);
                    $('#matriz-risco-grupos').html(data.html);
                    $('#count-riscos').html(data.totalRiscos);
                    $('#riscos').val(data.totalRiscos);
                    $('#text-risco').css('display', 'none');
                    $('#load').css('display', 'none');
                    $('.totalProgByGrupo').text(data.totalProgByGrupo);
                }
            });
        }
        function concatenaDescRevisao(){

            var arr = [] ;
            $('input[id^="idCodGest___"]').each(function(x){
                var context = $(this);

                var linha = context.attr('id').split("___")[1];

                if(arr.indexOf($("#idCodGest___" + linha).val()) === -1){
                    arr.push($("#idCodGest___" + linha).val());
                }
            });

            $("#aprovadorGrupo").val(arr);
        }
    </script>