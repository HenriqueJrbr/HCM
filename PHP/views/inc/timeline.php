<style>
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
        left: 15px;
        margin-left: -1.5px;
    }

    ul.timeline {margin-bottom: -15px;margin-top: -15px;}
    .timeline > li:last-child{
        border-bottom: none;
    }
    .timeline > li {
        border: none !important;
        margin-bottom: 20px;
        position: relative;
        width: 99%
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
        width: 30px;
        height: 30px;
        line-height: 50px;
        font-size: 1.4em;
        text-align: center;
        position: absolute;
        top: 26px;
/*        left: 25px;*/
        background-color: #999999;
        z-index: 100;
        border-top-right-radius: 50%;
        border-top-left-radius: 50%;
        border-bottom-right-radius: 50%;
        border-bottom-left-radius: 50%;
    }

    /*** inverted panel ***/
    .timeline > li > .timeline-panel-inverted {
        width: 92%;
        float: left;
        border: 1px solid #d4d4d4;
        border-radius: 2px;
        padding: 5px 20px;
        position: relative;
        /*-webkit-box-shadow: 0 1px 6px rgba(0, 0, 0, 0.175);
        box-shadow: 0 1px 6px rgba(0, 0, 0, 0.175);*/
        margin-left: 46px;
        min-height: 57px;
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
        font-size: 12px;
        font-weight: bold;
    }

    .timeline-body > p,
    .timeline-body > ul {
        margin-bottom: 0;
    }

    .timeline-body > p + p {
        margin-top: 5px;
    }

    .timeline-body{
        overflow: hidden
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
    
    #table{    
        height: 190px;

    }

    #table tbody {   

        overflow-y: auto;
        overflow-x: hidden;
        position: relative;
    }


    #table th{
        border:none !important;
        font-size: 13px;
        padding:2px 5px !important;
        cursor: default;                                                            
        height: 10px !important
    }
    #table td{
        border:none !important;
        border-top:solid 1px #dfe3e8 !important;
        padding:2px 5px !important;
        font-size: 12px;
        cursor: default;
        height: 10px !important
    }
</style>
    <div id="myModalTimeline" class="modal fade" role="dialog">
        <div class="modal-dialog modal-md">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Timeline da Solicitação</h4>      
                </div>
                <div class="modal-body">                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="container">
                                <div class="page-header">
                                    <h1 id="timeline">Timeline</h1>
                                </div>                                                                     
                            
                                <ul class="timeline" style="width: 97%;">   
                                    <li>                                        
                                        <div class="timeline-badge success"></div>
                                        <div class="timeline-panel-inverted">
                                            <div class="timeline-heading">
                                                <h4 class="timeline-title">Inicio da Solicitação</h4>
                                                <a class="collapse-link" style="float:right;margin-top: -25px;"><i class="fa fa-chevron-up"></i></a>
                                            </div>
                                            <div class="timeline-body">
                                                
                                                <small class="text-muted">Núm. da Solicitação: <strong><?php echo $dadosRevisao['idSolicitacao']; ?></strong></small><br>
                                                <small class="text-muted">Nome do Solicitante: <strong><?php echo $nomeSolicitante; ?></strong></small><br>
                                                <?php if(isset($funcao) && $funcao['descricao'] != ''): ?>
                                                <small class="text-muted">Função: <strong><?php echo $funcao['descricao']; ?></strong></small><br>
                                                <?php endif; ?>
                                                <small class="text-muted">Data: <strong><?php echo date('d/m/Y', strtotime($documento->dataInicio)); ?></strong></small><br>
                                                <small class="text-muted">Horário: <strong><?php echo date('H:i:s', strtotime($documento->dataInicio)); ?></strong></small><br>
                                                
                                                <?php 
                                                //echo "<pre>";
                                                //print_r($documento->programas);
                                                if(isset($documento->programas)):
                                                    $progs = '';
                                                    foreach($documento->programas as $key => $prog):
                                                        $progs .= "<span style=\"margin-left:30px\">".$prog->codProg . ' - ' . $prog->descProg."</span><br>";
                                                    endforeach;
                                                ?>
                                                    <?php if(isset($timeline[0]['form']) && $timeline[0]['form'] == 7): ?>
                                                        <small class="text-muted">Grupo: <strong><?php echo $documento->idLegGrupo . ' - ' . $documento->descAbrev; ?></strong></small><br>
                                                    <?php endif; ?>

                                                    <small class="text-muted">Programas solicitados: <br><strong><?php echo $progs; ?></strong></small>
                                                    <?php if(isset($timeline[0]['form']) && $timeline[0]['form'] != 7): ?>
                                                    <small class="text-muted">Grupos que contém os programas solicitados: </small><br>
                                                    <?php endif; ?>
                                                <?php endif; ?>

                                                <?php if(isset($timeline[0]['form']) && $timeline[0]['form'] == 7): ?>
                                                    <!-- Grupos disponiveis -->
                                                    <div  id="table">
                                                        <table class="table-striped no-footer "
                                                               cellspacing="0" width="100%" role="grid"
                                                               style="width: 100%;" >
                                                         <thead>
                                                             <tr role="row" style="border:none;border-bottom:solid 1px #ccc">
                                                             <th width="10%">Grupo</th>
                                                             <?php if(isset($documento->programas)): ?>
                                                                 <th width="25%">Cód. Programa</th>
                                                             <th width="25%">Descrição</th>
                                                             <?php endif; ?>
                                                         </tr>
                                                         </thead>
                                                         <tbody class="progsAdicionados">
                                                             <?php foreach($documento->programas as $key => $val): ?>
                                                             <tr style="background-color: <?php echo (($val->manterStatus)  == 1 ? '#c0dbbe73' : '#f0b8b8b5'); ?>">
                                                                 <td><?php echo $documento->idLegGrupo; ?></td>
                                                                 <td><?php echo $val->codProg; ?></td>
                                                                 <td><?php echo $val->descProg; ?></td>
                                                             </tr>
                                                             <?php endforeach; ?>
                                                         </tbody>
                                                        </table>
                                                    </div>
                                                <?php else: ?>
                                                    <!-- Grupos disponiveis -->
                                                    <div  id="table">
                                                        <?php if(isset($timeline[0]['form']) && $timeline[0]['form'] == 7): ?>
                                                            <table class="table-striped no-footer "
                                                                   cellspacing="0" width="100%" role="grid"
                                                                   style="width: 100%;" >
                                                                <thead>
                                                                <tr role="row" style="border:none;border-bottom:solid 1px #ccc">
                                                                    <th width="10%">Grupo</th>
                                                                    <?php if(isset($documento->programas)): ?>
                                                                        <th width="25%">Programas</th>
                                                                    <?php endif; ?>
                                                                </tr>
                                                                </thead>
                                                                <tbody class="progsAdicionados">
                                                                <?php foreach($documento->programas as $key => $val): ?>
                                                                    <tr style="background-color: <?php echo (($val->manterStatus)  == 1 ? '#c0dbbe73' : '#f0b8b8b5'); ?>">
                                                                        <td><?php echo $documento->idLegGrupo; ?></td>
                                                                        <td><?php echo $val->programas; ?></td>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                                </tbody>
                                                            </table>
                                                        <?php else: ?>
                                                            <?php if($idFluxo == 8): ?>
                                                                <br>
                                                                <p>Grupos a adicionar</p>
                                                            <?php endif; ?>
                                                            <table class="table-striped no-footer "
                                                                cellspacing="0" width="100%" role="grid"
                                                                style="width: 100%;" >
                                                                <thead>
                                                                <tr role="row" style="border:none;border-bottom:solid 1px #ccc">
                                                                    <th width="10%">Grupo</th>
                                                                    <th width="13%">Nr. usuários</th>
                                                                    <th width="25%">Nr. Programas</th>
                                                                    <?php if(isset($documento->programas)): ?>
                                                                        <th width="25%">Programas</th>
                                                                    <?php endif; ?>
                                                                </tr>
                                                                </thead>
                                                                <tbody class="progsAdicionados">
                                                                <?php foreach($documento->grupos as $key => $val): ?>
                                                                    <tr style="background-color: <?php echo (($val->manterStatus)  == 1 ? '#c0dbbe73' : '#f0b8b8b5'); ?>">
                                                                        <td><?php echo $val->idLegGrupo; ?></td>
                                                                        <td><?php echo $val->nrUsuarios; ?></td>
                                                                        <td><?php echo $val->nrProgramas; ?></td>
                                                                        <?php if(isset($documento->programas)): ?>
                                                                            <td><?php echo $val->programas; ?></td>
                                                                        <?php endif; ?>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                                </tbody>
                                                            </table>
                                                            <div class="clearfix">&nbsp;</div>
                                                            <?php if($idFluxo == 8): ?>
                                                                <p>Grupos a remover</p>
                                                                <table class="table-striped no-footer "
                                                                    cellspacing="0" width="100%" role="grid"
                                                                    style="width: 100%;" >
                                                                    <thead>
                                                                    <tr role="row" style="border:none;border-bottom:solid 1px #ccc">
                                                                        <th width="10%">Grupo</th>
                                                                        <th width="13%">Nr. usuários</th>
                                                                        <th width="25%">Nr. Programas</th>
                                                                        <?php if(isset($documento->programas)): ?>
                                                                            <th width="25%">Programas</th>
                                                                        <?php endif; ?>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody class="progsAdicionados">
                                                                    
                                                                    <?php foreach($documento->remover as $key => $val): ?>
                                                                        <?php if(isset($val->removeStart) && $val->removeStart > 0): ?>
                                                                            <tr>
                                                                                <td><?php echo $val->idLegGrupo; ?></td>
                                                                                <td><?php echo $val->nrUsuarios; ?></td>
                                                                                <td><?php echo $val->nrProgramas; ?></td>
                                                                                <?php if(isset($documento->programas)): ?>
                                                                                    <td><?php echo $val->programas; ?></td>
                                                                                <?php endif; ?>
                                                                            </tr>
                                                                        <?php endif; ?>
                                                                    <?php endforeach; ?>
                                                                    </tbody>
                                                                </table>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endif; ?>
                                                <br>
                                                <span style="background-color: #c0dbbe73; padding:0px 8px;margin-right: 3px;"></span> <?php echo isset($timeline[0]['form']) && $timeline[0]['form'] == 7 ? 'Programas em análise' : 'Grupos em análise'; ?>
                                                <span style="background-color: #f0b8b8b5; padding:0px 8px; margin-left:30px;margin-right: 3px;"></span> <?php echo isset($timeline[0]['form']) && $timeline[0]['form'] == 7 ? 'Programas não selecionados' : 'Grupos não selecionados'; ?>
                                                           
                                                <!-- Fim Grupos disponiveis -->
                                            </div>
                                        </div>                                        
                                    </li>
                                </ul>
                                <div style="margin-top:-20px; height: 500px; overflow-y: auto;padding-top: 0;" id="timelineOverflow">                                    
                                <ul class="timeline" >
                                    <?php foreach($timeline as $key => $val): ?>
                                        <?php if(strtolower($val['acao']) == 'finalizado' || strtolower($val['acao']) == 'rejeitado'): ?>
                                        <li>                                        
                                            <div class="timeline-badge danger"></div>
                                            <div class="timeline-panel-inverted">
                                                <div class="timeline-heading">
                                                    <h4 class="timeline-title">Fim da Solicitação</h4>
                                                    <a class="collapse-link" style="float:right;margin-top: -25px;"><i class="fa fa-chevron-up"></i></a>
                                                </div>
                                                <div class="timeline-body">

                                                    <small class="text-muted">Núm. da Solicitação: <strong>55</strong></small><br>
<!--                                                    <small class="text-muted">Nome do Solicitante: <strong>Super Usuário</strong></small><br>-->
                                                    <small class="text-muted">Data: <strong><?php echo date('d/m/Y', strtotime($val['dataAcao'])); ?></strong></small><br>
                                                    <small class="text-muted">Horário: <strong><?php echo date('H:i:s', strtotime($val['dataAcao'])); ?></strong></small><br>
                                                    <?php 
                                                    //echo "<pre>";
                                                    //print_r($documento->programas);
                                                    if(isset($documento->programas)):
                                                        $progs = '';
                                                        foreach($documento->programas as $key => $prog):
                                                            $progs .= "<span style=\"margin-left:30px\">".$prog->codProg . ' - ' . $prog->descProg."</span><br>";
                                                        endforeach;
                                                    ?>

                                                    <small class="text-muted">Programas solicitados: <br><strong><?php echo $progs; ?></strong></small>                                                    
                                                    <?php endif; ?>                                                
                                                    <small class="text-muted">Resultado: <strong style="color:#ff0000"><?php echo $val['grupos_programas']; ?></strong></small>

                                                    <!-- Fim Grupos disponiveis -->
                                                </div>
                                            </div>                                        
                                        </li>
                                        
                                        <?php else: ?>
                                        <li>                                            
                                            <div class="timeline-badge" style="background-color:#7E649E"></div>
                                            <div class="timeline-panel-inverted">
                                                <div class="timeline-heading">
                                                    <h4 class="timeline-title">Solicitação <?php echo strtolower(htmlentities($val['acao'])); ?></h4>
                                                    <a class="collapse-link" style="float:right;margin-top: -25px;"><i class="fa fa-chevron-up"></i></a>
                                                </div>
                                                <div class="timeline-body">
                                                    
                                                    
                                                    <small class="text-muted">Atividade: <strong><?php echo $val['descAtividade']; ?></strong></small><br>
                                                    <small class="text-muted">Nome do gestor: <strong><?php echo $val['responsavel']?></strong></small><br>                                                                                                        
                                                    <?php if($val['status'] == 1 || $val['acao'] == 'pendente de aprovação'):?>
                                                        <?php                                                            
                                                            $dateIni  = new DateTime($val['dataMovimentacao']);
                                                            $dateFim  = new DateTime(date('Y-m-d H:i:s'));                               

                                                            $diasTotal = $dateIni->diff($dateFim);
                                                        ?>
                                                    <small class="text-muted">Data: <strong><?php echo date('d/m/Y', strtotime($val['dataMovimentacao'])); ?></strong></small><br>
                                                    <small class="text-muted">Horário: <strong><?php echo date('H:i:s', strtotime($val['dataMovimentacao'])); ?></strong></small><br>
                                                    <?php if($val['status'] == 1):?>
                                                    <small class="text-muted">Quant. de dias aguardando a execução da atividade: <strong><?php echo (($diasTotal->days > 0) ? $diasTotal->days . ' dia(s) ' : '') . (($diasTotal->days > 0 && $diasTotal->h > 0) ? ' e ' : '' ). (($diasTotal->h > 0) ? $diasTotal->h . ' hora(s)' : '') ; ?></strong></small><br>
                                                    <?php endif; ?>
                                                    <?php endif; ?>
                                                    <?php if($val['status'] == 0 && $val['acao'] != 'pendente de aprovação'): ?>
                                                        <?php                                                            
                                                            $dateIni  = new DateTime($val['dataMovimentacao']);
                                                            $dateFim  = new DateTime($val['dataAcao']);                               

                                                            $diasTotal = $dateIni->diff($dateFim);
                                                        ?>
                                                        <small class="text-muted">Data Aprovação: <strong><?php echo date('d/m/Y', strtotime($val['dataAcao'])); ?></strong></small><br>
                                                        <small class="text-muted">Horário Aprovação: <strong><?php echo date('H:i:s', strtotime($val['dataAcao'])); ?></strong></small><br>

<!--                                                        <small class="text-muted">Observação: <strong><?php //echo $val['msg']; ?></strong></small><br>-->
                                                        <small class="text-muted">Quant. de dias para execução da atividade: <strong><?php echo (($diasTotal->days > 0) ? $diasTotal->days . ' dia(s) ' : '') . (($diasTotal->days > 0 && $diasTotal->h > 0) ? ' e ' : '' ). (($diasTotal->h > 0) ? $diasTotal->h . ' hora(s)' : '') ; ?></strong></small><br>
                                                    <?php endif; ?>
                                                    <?php if(!empty($val['grupos_programas'])): ?>
                                                        <style>
                                                            .tableProgs th,
                                                            .tableProgs td{
                                                                padding:2px 10px;
                                                                color: #777;
                                                                font-size: 12px
                                                            }
                                                            .tableProgs td{                                                                
                                                                border-top: solid 1px #ccc
                                                            }
                                                            
                                                        </style>
                                                        <table class="table-striped no-footer tableProgs" cellspacing="5" cellpadding="5">
                                                            <thead>
                                                                <tr>
                                                                    <th><small class="text-muted">Grupo(s) aprovado(s):</small></th>
                                                                    <th><small class="text-muted">Grupo(s) reprovado(s):</small></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>                                                                                                                            
                                                                <tr>
                                                                    <?php echo str_replace('|', "<br>", $val['grupos_programas']); ?><br>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    <?php endif; ?>

                                                    <?php if($idFluxo == 8 && $val['idAtividade'] == 47 && ($val['acao'] != 'pendente de aprovação')): ?><br>
                                                        <p>Grupos a remover</p><br>
                                                        <table class="table-striped no-footer "
                                                            cellspacing="0" width="100%" role="grid"
                                                            style="width: 100%;" >
                                                            <thead>
                                                            <tr role="row" style="border:none;border-bottom:solid 1px #ccc">
                                                                <th width="10%">Grupo</th>
                                                                <th width="13%">Nr. usuários</th>
                                                                <th width="25%">Nr. Programas</th>
                                                                <?php if(isset($documento->programas)): ?>
                                                                    <th width="25%">Programas</th>
                                                                <?php endif; ?>
                                                            </tr>
                                                            </thead>
                                                            <tbody class="progsAdicionados">
                                                            
                                                            <?php foreach($documento->remover as $key => $valR): ?>
                                                                <?php if(isset($valR->removerStatus) && $valR->removerStatus > 0): ?>
                                                                    <tr>
                                                                        <td><?php echo $valR->idLegGrupo; ?></td>
                                                                        <td><?php echo $valR->nrUsuarios; ?></td>
                                                                        <td><?php echo $valR->nrProgramas; ?></td>
                                                                        <?php if(isset($documento->programas)): ?>
                                                                            <td><?php echo $valR->programas; ?></td>
                                                                        <?php endif; ?>
                                                                    </tr>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            
                                        </li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                                </div>
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

    <!-- perfect-scrollbar -->
    <link href='<?php echo URL ?>/assets/css/perfect-scrollbar.css' rel="stylesheet">
    <script src='<?php echo URL ?>/assets/js/perfect-scrollbar.js'></script>

<script>
    $(document).ready(function(){
        /*$('#tableGruposDisponiveis').DataTable( {
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
        });*/               
        
        new PerfectScrollbar('#table');
        //new PerfectScrollbar('#timelineOverflow');
        
        $('.collapse-link').on('click', function(){
            $(this).closest('li').find('.timeline-body').toggleClass('hide');
        });
    });

    


</script>