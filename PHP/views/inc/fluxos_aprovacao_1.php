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
    .histBadge{
        background-color: #2a3f54;
        margin-top: 10px
    }    
</style>

<?php 
$idAtividadeGestorUsuario = 0;
$idAtividadeGestorGrupo = 0;

if(isset($movimentacao['form']) && $movimentacao['form'] == 3):
    $idAtividadeGestorUsuario = 8;
elseif(isset($movimentacao['form']) && $movimentacao['form'] == 4):
    $idAtividadeGestorUsuario = 16;  
elseif(isset($movimentacao['form']) && $movimentacao['form'] == 5):
    $idAtividadeGestorUsuario = 24;    
endif;

if(isset($movimentacao['form']) && $movimentacao['form'] == 3):
    $idAtividadeGestorGrupo = 9;
elseif(isset($movimentacao['form']) && $movimentacao['form'] == 4):
    $idAtividadeGestorGrupo = 17;
elseif(isset($movimentacao['form']) && $movimentacao['form'] == 5):
    $idAtividadeGestorGrupo = 25;
endif;
?>
                <table class="tabelaRevisao table table-striped table-bordered dataTable no-footer" role="grid" aria-describedby="datatable_info">
                    <thead>
                    <tr>
                        <!------------- Solicitação de acesso --------------->
                        <?php if(isset($documento->programas)): ?>
                        <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                            aria-label="Office: activate to sort column ascending" style="width: auto;">Programas</th>
                        <?php endif; ?>
                        <?php //if($movimentacao['form'] == 4): ?>
                        <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                            aria-label="Office: activate to sort column ascending" style="width: 3%;">Nr. Programas</th>
                        <?php //endif; ?>
                        <?php //if($movimentacao['form'] == 4): ?>
                        <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                            aria-label="Office: activate to sort column ascending" style="width: 3%;">Nr. Usuários</th>
                        <?php //endif; ?>
                        <!------------- FIM Solicitação de acesso ------------->
                        <th class="sorting_asc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                            aria-sort="ascending" aria-label="Name: activate to sort column descending"
                            style="width: 15%;">Grupo</th>
                        
                        <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                            aria-label="Office: activate to sort column ascending" style="width: 15%;">Gestor de Grupo</th>
                        <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                            aria-label="Office: activate to sort column ascending" style="width: auto;">Gestor de Módulo</th>
                        <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                            aria-label="Office: activate to sort column ascending" style="width: auto;">Gestor de Rotina</th>
                        <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                            aria-label="Office: activate to sort column ascending" style="width: auto;">Gestor de Programa</th>
                        
                        <th class="sorting <?php //echo (isset($movimentacao['form']) && $movimentacao['form'] == 5 ? 'hide' : ''); ?>" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                            aria-label="Office: activate to sort column ascending" style="width: 5%;">Manter</th>                        
                    </tr>
                    </thead>
                    <tbody>
                    <?php //$id = 0; 
                    //echo "<pre>";
                    //print_r($documento->grupos);die('');
                    $contModal = 0;
                    ?>
                    <?php foreach ($documento->grupos as $id => $value): ?>
                        <?php if($idAtividade == $idAtividadeGestorUsuario): ?>
                        <tr role="row" class="odd">
                            <input type="hidden" name="idLinhaGrupo" value="<?php echo $value->idLinhaGrupo; ?>">
                            <!------------- Solicitação de acesso --------------->
                            <?php if(isset($value->programas)): ?>
                            <td>
                                <input type="hidden" name="grupos[<?php echo $id ?>][programas]" id="idLinhaGrupo__<?php echo $id ?>" value="<?php echo $value->programas ?>">
                                <strong><?php echo $value->programas ?></strong>
                            </td>
                            <?php endif; ?>
                            <?php if(isset($value->nrProgramas)): ?>
                            <td>
                                <input type="hidden" name="grupos[<?php echo $id ?>][nrProgramas]" id="idLinhaGrupo__<?php echo $id ?>" value="<?php echo $value->nrProgramas ?>">
                                <center><?php echo $value->nrProgramas ?></center>
                            </td>
                            <?php endif; ?>
                            <?php if(isset($value->nrUsuarios)): ?>
                            <td>
                                <input type="hidden" name="grupos[<?php echo $id ?>][nrUsuarios]" id="idLinhaGrupo__<?php echo $id ?>" value="<?php echo $value->nrUsuarios ?>">
                                <center><?php echo $value->nrUsuarios ?></center>
                            </td>
                            <?php endif; ?>
                            <!------------- FIM Solicitação de acesso ------------->
                            
                            <td>                                                                
                                <input type="hidden" name="grupos[<?php echo $id ?>][codGest]" id="codGestor__<?php echo $id ?>" value="<?php echo $value->codGest ?>">
                                <input type="hidden" class="idGrupo" name="grupos[<?php echo $id ?>][idGrupo]" value="<?php echo $value->idGrupo ?>">
                                <input type="hidden" class="idLegGrupo" name="grupos[<?php echo $id ?>][idLegGrupo]" value="<?php echo $value->idLegGrupo ?>">
                                <input type="text" style="width: 100%" id="idLinha___<?php echo $id ?>"
                                       class="form-control hide" value="<?php echo $id ?>" readonly="readonly">
                                <span class="labelField">Nome:</span><br>
                                <span class="nomeField"><?php echo $value->idLegGrupo ?></span><br><br>
                                <span class="labelField">Descrição</span><br>
                                <span class="nomeField"><?php echo $value->descAbrev ?></span>                                 
                            </td>
                            <td>
                               
                                <input type="text" name="grupos[<?php echo $id ?>][idCodGest]" style="width: 100%" id="idCodGest___<?php echo $id ?>"
                                       class="form-control hide" value="<?php echo $value->idCodGest ?>"
                                       readonly="readonly">
                                <input type="hidden" id="userAlt___<?php echo $id ?>" value="<?php echo (isset($userAlt[$value->idCodGest])) ? $userAlt[$value->idCodGest] : ''; ?>">
                                <input type="hidden" id="userAltSerSub___<?php echo $id ?>" value="<?php echo (isset($userAltSerSub[$value->idCodGest])) ? $userAltSerSub[$value->idCodGest] : ''; ?>">
                                <span class="labelField">Nome:</span><br>
                                <span class="nomeField"><?php echo $value->nomeGestor ?></span>
                                <div class="aprovador">
                                    <span class="labelField">Observação:</span><br>
                                    <span class="nomeField"><?php echo (isset($value->obs) && !empty($value->obs)) ? $value->obs : ''; ?></span>
                                </div>

                                <div class="aprovador">
                                    <span class="labelField">Aprovação:</span><br>                                                                    
                                    <?php if ($value->aprovacao == "sim"): ?>
                                    <span class="nomeField"><?php echo "Aprovado";?></span>
                                        <?php echo "<br>";?>
                                    <?php elseif($value->aprovacao == "nao"): ?>
                                        <span class="nomeField"><?php echo "Reprovado";?></span>
                                        <?php echo "<br>";?>
                                    <?php else: ?>
                                        <span class="nomeField"><?php echo "Aguardando Aprovação";?></span>
                                        <?php echo "<br>";?>
                                    <?php endif ?>                                    
                                </div>

                            </td>


                            <!-- Incluído gestor de modulo, rotina e programa -->
                            <?php foreach($gestMrp as $mrp): ?>
                            <td>                                
                                <?php 
                                $html = '';                                
                                foreach($value->$mrp as $key => $mod):               
                                    $html .= "<tr><td>".$mod->nome."</td><td>";
                                    if($mod->aprovacao === 'sim'):
                                        $html .= 'Aprovado';                                                
                                    elseif($mod->aprovacao === 'nao'):
                                        $html .= 'Reprovado';                                                
                                    else:
                                        $html .= ($statusSolicitacao == 0) ? 'Rejeitado pelo Gestor de Usuários' : 'Aguardando Aprovação';                                                
                                    endif;
                                    $html .= "</td></tr>";
                                endforeach; 
                                    
                                if($html != ''): ?>
                                    <a href="javascript:void(0)"><span class="badge badge-info histBadge pull-right" data-toggle="modal" data-target="#myModalHist_<?php echo $mrp.'_'.$contModal; ?>">Histórico <br>de<br> Aprovação<br> de<br> <?php echo ((str_replace('gestor', '', $mrp) == 'modulo') ? 'Módulo' : ucfirst(str_replace('gestor', '', $mrp))) . 's'; ?></span></a>
                                <?php endif;?>
                                <!-- MODAL COM Historico de aprovacao Gestor modulo -->
                                <div id="myModalHist_<?php echo $mrp.'_'.$contModal; ?>" class="modal fade" role="dialog">
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
                            <?php $contModal++; ?>                            
                            <td <?php //echo (isset($movimentacao['form']) && $movimentacao['form'] == 5)? 'class="hide"': '' ?>>
                                <center>
                                    <input type="checkbox"
                                           id="manter___<?php echo $id ?>" <?php echo (isset($value->manterStatus) && $value->manterStatus == 1 || $value->manterStatus == true) ? 'checked="checked"' : '' ?>                                            
                                           class="manter manterStatus">
                                    <input type="hidden" class="inputManterStatus" name="grupos[<?php echo $id ?>][manterStatus]"
                                           id="manterStatus___<?php echo $id ?>" value="<?php echo (isset($value->manterStatus) && $value->manterStatus == 1 || $value->manterStatus == true) ? 1 : 0; ?>"><br>                                    
                                </center>
                            </td>                            
                        </tr>                        
                        <?php else: ?>                                                
                            <?php if(isset($value->manterStatus) && ($value->manterStatus == 1 || $value->manterStatus == true) || $movimentacao['form'] == 3): ?>
                                <tr role="row" class="odd <?php echo (isset($value->manterStatus) && ($value->manterStatus == 0 || $value->manterStatus == false) && $idAtividade > $idAtividadeGestorUsuario) ? 'hide' : '';?>">
                                    <?php if(isset($value->programas)): ?>
                                    <td>
                                        <input type="hidden" name="grupos[<?php echo $id ?>][programas]" id="idLinhaGrupo__<?php echo $id ?>" value="<?php echo $value->programas ?>">
                                        <strong><?php echo $value->programas; ?></strong>
                                    </td>
                                    <?php endif; ?>
                                    <?php if(isset($value->nrProgramas)): ?>
                                    <td>
                                        <input type="hidden" name="grupos[<?php echo $id ?>][nrProgramas]" id="idLinhaGrupo__<?php echo $id ?>" value="<?php echo $value->nrProgramas ?>">
                                        <center><?php echo $value->nrProgramas; ?></center>
                                    </td>
                                    <?php endif; ?>
                                    <?php if(isset($value->nrUsuarios)): ?>
                                    <td>
                                        <input type="hidden" name="grupos[<?php echo $id ?>][nrUsuarios]" id="idLinhaGrupo__<?php echo $id ?>" value="<?php echo $value->nrUsuarios ?>">
                                        <center><?php echo $value->nrUsuarios; ?></center>
                                    </td>
                                    <?php endif; ?>
                                    <td>
                                        <input type="hidden" class="idGrupo" name="grupos[<?php echo $id ?>][idGrupo]" value="<?php echo $value->idGrupo ?>">
                                        <span class="labelField">Nome:</span><br>
                                        <span class="nomeField"><?php echo $value->idLegGrupo; ?></span><br><br>
                                        <span class="labelField">Descrição</span><br>
                                        <span class="nomeField"><?php echo $value->descAbrev; ?></span>                                          
                                    </td>
                                    <td>                                        
                                        <?php if($idAtividade == $idAtividadeGestorGrupo && (($value->idCodGest == $_SESSION['idUsrTotvs']) || (isset($userAlt[$value->idCodGest]) && $userAlt[$value->idCodGest] == $_SESSION['idUsrTotvs']))):?>
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
                                                <textarea class="form-control obsGestorGrupo" name="grupos[<?php echo $id ?>][obs_grupo]" id="obs___<?php echo $id ?>"
                                                          style="width: 100%"><?php echo (isset($value->obs) && !empty($value->obs)) ? $value->obs : ''; ?></textarea>
                                            </div>

                                            <div class="aprovador">
                                                <label>Aprovação Gestor de Grupo</label>
                                                <select class="form-control aprovacao_gestor_grupo" name="grupos[<?php echo $id ?>][aprovacao_grupo]" id="aprovacao___<?php echo $id ?>" style="width: 100%">                                                    
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
                                            <?php else: ?>
                                                <span class="labelField">Nome:</span><br>
                                                <span class="nomeField"><?php echo $value->nomeGestor ?></span>
                                                <div class="aprovador">
                                                    <span class="labelField">Observação:</span><br>
                                                    <span class="nomeField"><?php echo "<br>".(isset($value->obs) && !empty($value->obs)) ? $value->obs : ''; ?></span><br>
                                                </div>

                                                <div class="aprovador">
                                                    <span class="labelField">Aprovação:</span><br>                                                                    
                                                    <?php if ($value->aprovacao == "sim"): ?>
                                                    <span class="nomeField"><?php echo "Aprovado";?></span>
                                                        <?php echo "<br>";?>
                                                    <?php elseif($value->aprovacao == "nao"): ?>
                                                        <span class="nomeField"><?php echo "Reprovado";?></span>
                                                        <?php echo "<br>";?>
                                                    <?php else: ?>
                                                        <span class="nomeField"><?php echo "Aguardando Aprovação";?></span>
                                                        <?php echo "<br>";?>
                                                    <?php endif ?>                                    
                                                </div>
                                            <?php endif; ?>
                                                
                                        </div>

                                    </td>


                                    <!-- Incluído gestor de modulo, rotina e programa -->
                                    <?php foreach($gestMrp as $keyMrp => $mrp): ?>                            
                                    <td>                                
                                        <?php 
                                        $html = '';                                
                                        ?>                                
                                        <?php $aprovMod = false; ?>
                                        <?php foreach($value->$mrp as $keyMRP => $mod): ?>
                                            <?php if(isset($mod->id)): ?>
                                                <?php $aprovMod = true; ?>
                                                <?php if((($mod->id == $_SESSION['idUsrTotvs']) || (isset($userAlt) && $userAlt == $_SESSION['idUsrTotvs'])) && $idAtividade == $keyMrp): ?>
                                                    <input type="text" name="gestorNome" style="width: 100%"
                                                           id="nome" class="form-control <?php echo (isset($mod->id) && $mod->id != '') ? '' : 'hide'; ?>"
                                                           value="<?php echo $mod->nome; ?>" readonly="readonly">
                                                    <input type="hidden" name="grupos[<?php echo $id; ?>][idLegGrupo]" value="<?php echo $value->idLegGrupo; ?>">
                                                    <input type="hidden" name="grupos[<?php echo $id; ?>][idCodGest]" value="<?php echo $value->idCodGest; ?>">
                                                    <input type="text" name="id" style="width: 100%" id="id_<?php echo $mrp; ?>___<?php echo $id ?>"
                                                           class="form-control hide" value="<?php echo $mod->id; ?>"
                                                           readonly="readonly">
                                                    <input type="hidden" id="userAlt" value="<?php echo (isset($userAlt)) ? $userAlt : ''; ?>">
                                                    <input type="hidden" id="userAltSerSub" value="<?php echo (isset($userAltSerSub)) ? $userAltSerSub : ''; ?>">
                                                    <div class="aprovador <?php echo (isset($mod->id) && $mod->id != '') ? '' : 'hide'; ?>">
                                                        <label>Observação</lbabel>
                                                        <textarea class="form-control aprovacao_<?php echo $mrp; ?>" name="grupos[<?php echo $id; ?>][aprovacao_<?php echo $mrp; ?>][obs]" id="obs_<?php echo $mrp; ?>___<?php echo $id ?>"
                                                                  style="width: 100%"><?php echo (isset($mod->obs) && !empty($mod->obs)) ? $mod->obs : ''; ?></textarea>
                                                    </div>

                                                    <!-- Aprovador de Módulos -->
                                                    <div class="aprovador <?php echo (isset($mod->id) && $mod->id != '') ? '' : 'hide'; ?>">
                                                        <label>Aprovação Gestor de Módulo</label>
                                                        <select class="form-control aprovacao_<?php echo $mrp; ?>" name="grupos[<?php echo $id; ?>][aprovacao_<?php echo $mrp; ?>][aprovacao]" id="aprovacao_<?php echo $mrp; ?>___<?php echo $id ?>" style="width: 100%">
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
                                                    if($mod->aprovacao === 'sim'):
                                                        $html .= 'Aprovado';                                                
                                                    elseif($mod->aprovacao === 'nao'):
                                                        $html .= 'Reprovado';                                                
                                                    else:
                                                        $html .= ($statusSolicitacao == 0) ? 'Rejeitado pelo Gestor de Usuários' : 'Aguardando Aprovação';                                                
                                                    endif;
                                                    $html .= "</td></tr>"
                                                    ?>
                                                <?php endif; ?>  
                                            <?php endif; ?> 

                                        <?php endforeach; ?>
                                        <?php if($aprovMod == false): ?>
<!--                                            <input type="hidden" name="grupos[<?php echo $id; ?>][<?php echo $mrp; ?>][]" value="">-->
                                        <?php endif; ?>
                                        <?php if($html != ''): ?>
                                            <a href="javascript:void(0)"><span class="badge badge-info histBadge pull-right" data-toggle="modal" data-target="#myModalHist_<?php echo $mrp.'_'.$contModal; ?>">Histórico <br>de<br> Aprovação</span></a>
                                        <?php endif;?>
                                        <!-- MODAL COM Historico de aprovacao Gestor modulo -->
                                        <div id="myModalHist_<?php echo $mrp.'_'.$contModal; ?>" class="modal fade" role="dialog">
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
                                    <?php $contModal++; ?>
                                    
                                    <td <?php //echo (isset($movimentacao['form']) && $movimentacao['form'] == 5)? 'class="hide"': '' ?>>
                                        <center>
                                            <input type="checkbox"
                                                   id="manter___<?php echo $id ?>" <?php echo (isset($value->manterStatus) && $value->manterStatus == 1 || $value->manterStatus == true) ? 'checked="checked"' : '' ?>                                                    
                                                   class="manter manterStatus">
                                            <input type="hidden" class="inputManterStatus" name="grupos[<?php echo $id ?>][manterStatus]"
                                                   id="manterStatus___<?php echo $id ?>" value="<?php echo (isset($value->manterStatus) && $value->manterStatus == 1 || $value->manterStatus == true) ? 1 : 0; ?>"><br>                                            
                                        </center>
                                    </td>
                                    
                                </tr>
                            <?php endif; ?>
                        
                        
                        
                        <?php endif;?>
                        <?php //$id++ ?>
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

                
                <?php 
                $ativWithObsHist = array(5,8,16,13,21,24);
                if(in_array($idAtividade, $ativWithObsHist)):
                ?>
                <div class="row obs_historico">
                    <div class="clearfix"><hr></div>
                    <div class="col-md-12">
                        <label>Observação</label>
                        <textarea class="form-control" name="obs_historico"></textarea>
                    </div>
                </div>
                <?php endif; ?>
                
<script>
$(document).ready(function(){                            

    if($("#numAtividade").val() == 0){
        $('textarea').attr('disabled', 'true');
        $('input').attr('disabled', 'true');
        $('select').attr('disabled', 'true');
        $('checkbox').attr('disabled', 'true');
        $('.obs_historico').css('display','none');
    }


    if($("#numAtividade").val() == "16" || $("#numAtividade").val() == "24"){
        $("#btnRejeitaSolicitacao").on('click', function(){            
            $('#myModalConfirm .modal-body').html('<h5>Tem certeza de que quer rejeitar a solicitação?</h5>');
            $("#myModalConfirm").modal('show');
            
            // Se botao de continuar for clicado
            $('#myModalConfirm').find('#continue').on("click", function(e) {
                $("#myModalConfirm").modal('hide');
                $('#load').css('display', 'block');
                window.location.href = url + 'Fluxo/rejeitaSolicitacao/'+$('#idSolicitacao').val()+'/'+$('#idMovimentacao').val();
            });

            // Se botao de cancelar for clicado
            $('#myModalConfirm').find('#cancel').on("click", function(e){                            
                $('#load').css('display', 'none');
                $("#myModalConfirm").modal('hide');
            });
        });        
    }

    // Aprovação de gestor
    if($("#numAtividade").val() == "8" || $("#numAtividade").val() == "16" || $("#numAtividade").val() == "24"){
        $("#btnRejeitaSolicitacao").removeClass('hide');

        

        // Desabilita os selects de aprovação SI
        //$('.aprovacao_si').removeClass('hide');
        $(".aprovacao_si select").attr("readonly","true");
        $(".aprovacao_si select").css({
            'pointer-events': 'none',
            'touch-action': 'none'
        });
        $(".aprovacao_si textarea").attr("readonly","true");     

        $('.aprovador select option:selected[value="nao"]').closest('tr').css('background-color', 'rgb(237, 185, 185)');     

        $(".manterStatus").click(function(){                                                         
            $(this).val($(this).is(":checked"));
        });
        $("input[name='bloquearUsuario']").removeAttr('readonly');

        setTimeout(function(){ concatenaDescRevisao(); }, 300);
        setTimeout(function(){concatenaDescRevisao(); }, 600);

        // Valida Aprovação ou Reprovação
        $('#btnEnviar').on('click', function () {
            $('#load').css('display', 'none');

            // Valida se foi selecionado ao menos um grupo. E caso não, Mostra um prompt questionando se
            // o gestor deseja rejeitar a solicitação
            var validaSelect = false;          
            $(".manterStatus").each(function(){
                if($(this).is(":checked")){
                    validaSelect = true;
                    return false;
                }  
            });

            if(validaSelect === false && ($('#idFluxo').val() != 3)){
                $('#myModalConfirm .modal-body').html('<h5>Para continuar, deve ser selecionado ao menos um grupo. Deseja rejeitar a solicitação?</h5>');
                $("#myModalConfirm").modal('show');

                // Se botao de continuar for clicado
                $('#myModalConfirm').find('#continue').on("click", function(e) {
                    $("#myModalConfirm").modal('hide');
                    $('#load').css('display', 'block');
                    //window.location.href = url + 'fluxo/rejeitaSolicitacao/'+$('#idSolicitacao').val()+'/'+;
                    window.location.href = url + 'Fluxo/rejeitaSolicitacao/'+$('#idSolicitacao').val()+'/'+$('#idMovimentacao').val();
                });

                // Se botao de cancelar for clicado
                $('#myModalConfirm').find('#cancel').on("click", function(e){                            
                    $('#load').css('display', 'none');
                    $("#myModalConfirm").modal('hide');
                });
            }else{
                $('#frmFluxo').submit();
            }


        });
    }

    // Aprovação de grupos
    if($("#numAtividade").val() == "9" || $("#numAtividade").val() == "17" || $("#numAtividade").val() == "25"){
        $(".manter").attr("disabled","disabled");
        $("#bloquearUsuario").attr("disabled","disabled");

        // Desabilita os selects de aprovação do si e gestor de modulo
        $(".aprovacao_si select").attr("readonly","true");
        $(".aprovacao_si select").css({
            'pointer-events': 'none',
            'touch-action': 'none'
        });
        $(".aprovacao_si textarea").attr("readonly","true");
        $("textarea.obsGestorUsuario").attr("readonly","true");               

        // Valida Aprovação ou Reprovação
        $('#btnEnviar').on('click', function () {
            // Valida se o gestor aprovou ou reprovou os registros que estão pra ele
            var vazio = false;

            // Percorre os selects e valida se foi aprovado ou reprovado.
            // Se o valor for vazio e o grupo pertencer ao usuário logado
            // mostra o modal com alerta.
            $(".manterStatus").each(function () {
                if(!$(this).is(":checked")){
                }else{                            
                    if($(this).closest('tr').find('.aprovacao_gestor_grupo').val() === ''){                                
                        vazio = true;
                    }
                }
            });

            if(vazio == false){
                $('#frmFluxo').submit();
            }else{
                $('#load').css('display','none');
                $('.modal-title').text('');
                $('#result_msg').text('Favor aprovar ou reprovar os acessos!');
                $('#myModalResult').modal('show');
            }
        });
    }

    // Aprovação de módulos
    if($("#numAtividade").val() == "10" || $("#numAtividade").val() == "18" || $("#numAtividade").val() == "26"){
        $(".manter").attr("disabled","disabled");
        $("#bloquearUsuario").attr("disabled","disabled");

        // Desabilita os selects de aprovação do si e gestor de grupos, rotinas e programas
        $(".aprovacao_si select").attr("readonly","true");
        $(".aprovacao_si select").css({
            'pointer-events': 'none',
            'touch-action': 'none'
        });
        $(".aprovacao_si textarea").attr("readonly","true");        

        // Valida Aprovação ou Reprovação
        $('#btnEnviar').on('click', function () {
            // Valida se o gestor aprovou ou reprovou os registros que estão pra ele
            var vazio = false;

            // Percorre os selects e valida se foi aprovado ou reprovado.
            // Se o valor for vazio e o grupo pertencer ao usuário logado
            // mostra o modal com alerta.
            $(".manterStatus").each(function () {
                if(!$(this).is(":checked")){
                }else{                            
                    if($(this).closest('tr').find('.aprovacao_gestorModulo').val() === ''){                                
                        vazio = true;
                    }
                }
            });

            if(vazio == false){
                $('#frmFluxo').submit();
            }else{
                $('#load').css('display','none');
                $('.modal-title').text('');
                $('#result_msg').text('Favor aprovar ou reprovar os acessos!');
                $('#myModalResult').modal('show');
            }
        });
    }

    // Aprovação de rotinas
    if($("#numAtividade").val() == "11" || $("#numAtividade").val() == "19" || $("#numAtividade").val() == "27"){
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

        // Valida Aprovação ou Reprovação
        $('#btnEnviar').on('click', function () {
            // Valida se o gestor aprovou ou reprovou os registros que estão pra ele
            var vazio = false;

            // Percorre os selects e valida se foi aprovado ou reprovado.
            // Se o valor for vazio e o grupo pertencer ao usuário logado
            // mostra o modal com alerta.
            $(".manterStatus").each(function () {
                if(!$(this).is(":checked")){
                }else{                            
                    if($(this).closest('tr').find('.aprovacao_gestorRotina').val() === ''){                                
                        vazio = true;
                    }
                }
            });

            if(vazio == false){
                $('#frmFluxo').submit();
            }else{
                $('#load').css('display','none');
                $('.modal-title').text('');
                $('#result_msg').text('Favor aprovar ou reprovar os acessos!');
                $('#myModalResult').modal('show');
            }
        });
    }


    // Aprovação de programas
    if($("#numAtividade").val() == "12" || $("#numAtividade").val() == "20" || $("#numAtividade").val() == "28"){
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

        // Valida Aprovação ou Reprovação
        $('#btnEnviar').on('click', function () {
            // Valida se o gestor aprovou ou reprovou os registros que estão pra ele
            var vazio = false;

            // Percorre os selects e valida se foi aprovado ou reprovado.
            // Se o valor for vazio e o grupo pertencer ao usuário logado
            // mostra o modal com alerta.
            $(".manterStatus").each(function () {
                if(!$(this).is(":checked")){
                }else{                            
                    if($(this).closest('tr').find('.aprovacao_gestorPrograma').val() === ''){
                        vazio = true;
                    }
                }
            });

            if(vazio == false){
                $('#frmFluxo').submit();
            }else{
                $('#load').css('display','none');
                $('.modal-title').text('');
                $('#result_msg').text('Favor aprovar ou reprovar os acessos!');
                $('#myModalResult').modal('show');
            }
        });
    }


    // Aprovação SI
    if($("#numAtividade").val() == "13" || $("#numAtividade").val() == "21"){
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
        });


        // Submete o formulario
        $('#btnEnviar').on('click', function () {
            if($(".aprovacao_si select").val() == 1 || $(".aprovacao_si select").val() == 'on' || $(".aprovacao_si select").val() == 0) {
                $('#frmFluxo').submit();
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
    $('.manterStatus').change(function() {                
        if($(this).is(":checked")){
            $(this).closest('td').find('.inputManterStatus').val(1);
        }else{
            $(this).closest('td').find('.inputManterStatus').val(0);
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
    $('.manterStatus').on('change', function () {                
        $('#load').css('display', 'block');
        $('#text-risco').css('display', 'block');

        $('#matriz-risco-grupos').html('');
        carregaRiscosGrupos();
        $('#tableAbaProgs').DataTable().ajax.reload();

    });

    

    $('#aprovacao_si').on('change', function(){                
        if($(this).val() == '0'){
            $('#si-movimentacao').removeClass('hide');
        }else{
            $('#si-movimentacao').addClass('hide');
        }
    });
});                                               

</script>