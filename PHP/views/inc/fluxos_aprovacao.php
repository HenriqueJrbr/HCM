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
elseif(isset($movimentacao['form']) && $movimentacao['form'] == 6):
    $idAtividadeGestorUsuario = 32;
elseif(isset($movimentacao['form']) && $movimentacao['form'] == 8):
        $idAtividadeGestorUsuario = 47;
endif;

if(isset($movimentacao['form']) && $movimentacao['form'] == 3):
    $idAtividadeGestorGrupo = 9;
elseif(isset($movimentacao['form']) && $movimentacao['form'] == 4):
    $idAtividadeGestorGrupo = 17;
elseif(isset($movimentacao['form']) && $movimentacao['form'] == 5):
    $idAtividadeGestorGrupo = 25;
elseif(isset($movimentacao['form']) && $movimentacao['form'] == 6):
    $idAtividadeGestorGrupo = 33;
elseif(isset($movimentacao['form']) && $movimentacao['form'] == 8):
    $idAtividadeGestorGrupo = 48;
endif;
?>
                <table class="tabelaRevisao table table-striped table-bordered dataTable no-footer" role="grid" aria-describedby="datatable_info">
                    <thead>
                    <tr>
                        <!------------- Solicitação de acesso --------------->
                        <?php if(isset($documento->programas) || $idFluxo == 8): ?>
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
                        <?php foreach($seqAprov as $val): ?>
                        <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                            aria-label="Office: activate to sort column ascending" style="width: 15%;"><?php echo str_replace(['Gestor', 'Modulo'], ['Gestor de ', 'Módulo'], ucfirst($val)); ?></th>
                        <?php endforeach; ?>                        
                        
                        <th class="sorting <?php //echo (isset($movimentacao['form']) && $movimentacao['form'] == 5 ? 'hide' : ''); ?>" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                            aria-label="Office: activate to sort column ascending" style="width: 5%;">
                            <?php echo (($idFluxo == 3) ? 'Manter' : 'Adicionar'); ?>

                        </th>                        
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
                                <?php if(isset($value->programas) && ($timeline[0]['form'] != 6)): ?>
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
                                <!------------- INICIO SEQUÊNCIA DINÂMICA DE APROVAÇÃO ------------->
                                <?php foreach($seqAprov as $keyAprov => $valAprov): ?>
                                
                                    <!------------- Inicio bloco de aprovação de grupos ------------->
                                    <?php if($valAprov == 'gestorGrupo'): ?>
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
                                                <br>
                                                <span class="labelField">Carta Risco:</span><br>
                                                <?php if(!empty($value->cartaRisco)): ?>
                                                    <a href="<?php echo URL; ?>/Fluxo/cartaRisco/grupos[<?php echo $id; ?>].cartaRisco/<?php echo $documento->grupos[$id]->cartaRisco; ?>" title="Visualizar carta de risco" target="_blank"> <i class="fa fa-download"></i> Carta Risco <i class="fa fa-file-pdf-o"></i></a>
                                                <?php else: ?>
                                                    <span class="nomeField">Não</span>
                                                <?php endif; ?>
                                            </div>

                                        </td>
                                    <?php else: ?>
                                    <!-- Inicio bloco gestor de modulo, rotina e programa -->
                                        <?php
                                        //$gestMrp = $seqAprov;
                                        //unset($gestMrp['gestorGrupo']);
                                        //echo "<pre>";
                                        //print_r($gestMrp);
                                        //foreach($gestMrp as $mrp):
                                        $mrp = $valAprov;
                                        ?>
                                        <td>
                                            <?php 
                                            $html = '';
                                            var_dump($value);
                                            echo '<br>';
                                            var_dump($mrp);
                                            foreach($value->mrp as $key => $mod):
                                                $html .= "<tr><td>".$mod->nome."</td><td>";
                                                if($mod->aprovacao === 'sim'):
                                                    $html .= 'Aprovado';                                                
                                                elseif($mod->aprovacao === 'nao'):
                                                    $html .= 'Reprovado';                                                
                                                else:
                                                    $html .= ($statusSolicitacao == 0) ? 'Rejeitado pelo Gestor de Usuários' : 'Aguardando Aprovação';                                                
                                                endif;

                                                $html .= '</td>';

                                                if(isset($mod->modulo)):
                                                    $html .= '<td>'.$mod->modulo.'</td>';
                                                endif;

                                                if(isset($mod->rotina)):
                                                    $html .= '<td>'.$mod->rotina.'</td>';
                                                endif;

                                                if(isset($mod->programa)):
                                                    $html .= '<td>'.$mod->programa.'</td>';
                                                endif;

                                                if(!isset($mod->programa) && ! isset($mod->modulo) && !isset($mod->rotina)):
                                                    $html .= '<td></td>';
                                                endif;

                                                if(!empty($mod->cartaRisco)):
                                                    $html .= '<td><a href="'.URL.'/Fluxo/cartaRisco/grupos['.$id.'].'.$mrp.'['.$key.'].cartaRisco/'.$documento->grupos[$id]->cartaRisco.'" title="Visualizar carta de risco" target="_blank"> <i class="fa fa-download"></i> Carta Risco <i class="fa fa-file-pdf-o"></i></a></td>';
                                                else:
                                                    $html .= '<td><span class="nomeField">Não</span></td>';
                                                endif;

                                                $html .= '</tr>';
                                            endforeach; 

                                            if($html != ''): ?>
                                                <a href="javascript:void(0)"><span class="badge badge-info histBadge pull-right" data-toggle="modal" data-target="#myModalHist_<?php echo $mrp.'_'.$contModal; ?>">Histórico de Aprovação</span></a>
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

                                                                            <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1"
                                                                                colspan="1" style="width: 15%;"
                                                                                aria-label="Last name: activate to sort column ascending">
                                                                                <?php if($valAprov == 'gestorModulo'): echo 'Módulo'; endif; ?>
                                                                                <?php if($valAprov == 'gestorRotina'): echo 'Rotina'; endif; ?>
                                                                                <?php if($valAprov == 'gestorPrograma'): echo  'Programa'; endif; ?>
                                                                            </th>
                                                                            <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1"
                                                                                colspan="1" style="width: 15%;"
                                                                                aria-label="Last name: activate to sort column ascending">
                                                                                Carta de Risco
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
                                        <?php //endforeach; ?>   
                                        <?php $contModal++; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <!------------- INICIO SEQUÊNCIA DINÂMICA DE APROVAÇÃO ------------->
                                
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
                                    <?php if(isset($value->programas) && $movimentacao['form'] != 6): ?>
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
                                    
                                    <!------------- INICIO SEQUÊNCIA DINÂMICA DE APROVAÇÃO ------------->
                                    <?php foreach($seqAprov as $keyAprov => $valAprov): ?>
                                
                                    <!------------- Inicio bloco de aprovação de grupos ------------->
                                    <?php if($valAprov == 'gestorGrupo'): ?>
                                    
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
                                                    <br>
                                                    <br>
                                                    <span class="labelField">Carta Risco:</span><br>
                                                    <?php if(!empty($value->cartaRisco)): ?>
                                                        <a href="<?php echo URL; ?>/Fluxo/cartaRisco/grupos[<?php echo $id; ?>].cartaRisco/<?php echo $documento->grupos[$id]->cartaRisco; ?>" title="Visualizar carta de risco" target="_blank"> <i class="fa fa-download"></i> Carta Risco <i class="fa fa-file-pdf-o"></i></a>
                                                    <?php else: ?>
                                                        <span class="nomeField">Não</span>
                                                    <?php endif; ?>

                                                    <br>
                                                    <?php if(isset($documento->exigirCartaRisco) && $documento->exigirCartaRisco == '1'): ?>
                                                        <label>Inserir carta risco</label>
                                                        <input type="file" class="form-control carta_risco" name="grupos[<?php echo $id; ?>][cartaRisco]" accept="application/pdf">
                                                    <?php endif; ?>
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
                                                        <br>
                                                        <br>
                                                        <span class="labelField">Carta Risco:</span><br>
                                                        <?php if(!empty($value->cartaRisco)): ?>
                                                            <a href="<?php echo URL; ?>/Fluxo/cartaRisco/grupos[<?php echo $id; ?>].cartaRisco/<?php echo $documento->grupos[$id]->cartaRisco; ?>" title="Visualizar carta de risco" target="_blank"> <i class="fa fa-download"></i> Carta Risco <i class="fa fa-file-pdf-o"></i></a>
                                                        <?php else: ?>
                                                            <span class="nomeField">Não</span>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endif; ?>

                                            </div>
                                        </td>

                                    <?php else: ?>
                                    <!-- Inicio bloco gestor de modulo, rotina e programa -->
                                        <?php 
                                        //$gestMrp = $seqAprov;
                                        //unset($gestMrp['gestorGrupo']);

                                        //foreach($gestMrp as $keyMrp => $mrp): 
                                        ?>                                    
                                    <td>                                
                                            <?php
                                            $html = '';
                                            $mrp = $valAprov;
                                            ?>                                
                                            <?php $aprovMod = false; ?>
                                            <?php foreach($value->$mrp as $keyMRP => $mod): ?>                                            
                                                <?php if(isset($mod->id)): ?>
                                                    <?php $aprovMod = true; ?>
                                                    <?php if((($mod->id == $_SESSION['idUsrTotvs']) || (isset($userAlt) && $userAlt == $_SESSION['idUsrTotvs'])) && $idAtividade == $keyAprov): ?>
                                                        <label>
                                                                <?php if($valAprov == 'gestorModulo'): echo 'Módulo: '; endif; ?>
                                                                <?php if($valAprov == 'gestorRotina'): echo 'Rotina: '; endif; ?>
                                                                <?php if($valAprov == 'gestorPrograma'): echo 'Programa: '; endif; ?>
                                                            </label>
                                                        <span  class="labelField">
                                                                <?php if($valAprov == 'gestorModulo'): echo ($mod->modulo ); endif; ?>
                                                                <?php if($valAprov == 'gestorRotina'): echo ($mod->rotina ) ; endif; ?>
                                                                <?php if($valAprov == 'gestorPrograma'): echo ($mod->programa ); endif; ?>
                                                            </span><br>
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
                                                            <label>Aprovação</label>
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
                                                            <br>
                                                            <span class="labelField">Carta Risco:</span><br>
                                                            <?php if(!empty($mod->cartaRisco)): ?>
                                                                <a href="<?php echo URL; ?>/Fluxo/cartaRisco/grupos[<?php echo $id; ?>].<?php echo $mrp; ?>[<?php echo $keyMRP; ?>].cartaRisco/<?php echo $documento->grupos[$id]->$mrp[$keyMRP]->cartaRisco; ?>" title="Visualizar carta de risco" target="_blank"> <i class="fa fa-download"></i> Carta Risco <i class="fa fa-file-pdf-o"></i></a>
                                                            <?php else: ?>
                                                                <span class="nomeField">Não</span>
                                                            <?php endif; ?>
                                                            <br>
                                                            <?php if(isset($documento->exigirCartaRisco) && $documento->exigirCartaRisco == '1'): ?>
                                                                <label>Inserir carta risco</label>
                                                                <input type="file" class="form-control carta_risco" name="grupos[<?php echo $id; ?>][aprovacao_<?php echo $mrp; ?>][cartaRisco]" accept="application/pdf">
                                                            <?php endif; ?>
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

                                                        $html .= '</td>';

                                                        if(isset($mod->modulo)):
                                                            $html .= '<td>'.$mod->modulo.'</td>';
                                                        endif;

                                                        if(isset($mod->rotina)):
                                                            $html .= '<td>'.$mod->rotina.'</td>';
                                                        endif;

                                                        if(isset($mod->programa)):
                                                            $html .= '<td>'.$mod->programa.'</td>';
                                                        endif;

                                                        if(!isset($mod->programa) && ! isset($mod->modulo) && !isset($mod->rotina)):
                                                            $html .= '<td></td>';
                                                        endif;

                                                        if(!empty($mod->cartaRisco)):
                                                            $html .= '<td><a href="'.URL.'/Fluxo/cartaRisco/grupos['.$id.'].'.$mrp.'['.$keyMRP.'].cartaRisco/'.$documento->grupos[$id]->$mrp[$keyMRP]->cartaRisco.'" title="Visualizar carta de risco" target="_blank"> <i class="fa fa-download"></i> Carta Risco <i class="fa fa-file-pdf-o"></i></a></td>';
                                                        else:
                                                            $html .= '<td><span class="nomeField">Não</span></td>';
                                                        endif;

                                                        $html .= '</tr>';
                                                        ?>
                                                    <?php endif; ?>  
                                                <?php endif; ?> 
                                            <?php endforeach; ?>                                            
                                            <?php if($html != ''): ?>
                                                <div class="col-md-12">
                                                    <a href="javascript:void(0)" style="text-align: center;float: left;margin: 0 13%;"><span class="badge badge-info histBadge pull-right" data-toggle="modal" data-target="#myModalHist_<?php echo $mrp.'_'.$contModal; ?>">Histórico de Aprovação</span></a>
                                                </div>
                                                
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
                                                                            <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1"
                                                                                colspan="1" style="width: 15%;"
                                                                                aria-label="Last name: activate to sort column ascending">
                                                                                <?php if($valAprov == 'gestorModulo'): echo 'Módulo'; endif; ?>
                                                                                <?php if($valAprov == 'gestorRotina'): echo 'Rotina'; endif; ?>
                                                                                <?php if($valAprov == 'gestorPrograma'): echo  'Programa'; endif; ?>
                                                                            </th>
                                                                            <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1"
                                                                                colspan="1" style="width: 15%;"
                                                                                aria-label="Last name: activate to sort column ascending">
                                                                                Carta de Risco
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
                                        <?php //endforeach; ?>   
                                        <?php $contModal++; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                        
                                    
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
                    <div class="col-md-3">
                        <label>Aprovação S.I</label>
                        <select class="form-control" name="aprovacao_si" id="aprovacao_si">
                            <option value="" <?php //echo (isset($documento->aprovacao_si) && $documento->aprovacao_si == '') ? 'selected="true"' : '' ?>></option>
                            <option value="1" <?php //echo (isset($documento->aprovacao_si) && $documento->aprovacao_si == '1') ? 'selected="true"' : '' ?>>Sim</option>
                            <option value="0" <?php //echo (isset($documento->aprovacao_si) && $documento->aprovacao_si == '0') ? 'selected="true"' : '' ?>>Não</option>
                        </select>
                    </div>
                    <div class="col-md-3 hide<?php //echo (isset($documento->aprovacao_si) && $documento->aprovacao_si == 1 || $documento->aprovacao_si == '') ? 'hide' : ''; ?>" id="si-movimentacao">
                        <label>Movimentar para</label>
                        <select class="form-control" name="si_atividades">
                            <?php foreach($atividades as $val): ?>
                            <option value="<?php echo $val['id'].'-'.$val['objeto']; ?>"><?php echo $val['descricao']; ?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <div class="col-md-3 hide<?php //echo (isset($documento->aprovacao_si) && $documento->aprovacao_si == 1 || $documento->aprovacao_si == '') ? 'hide' : ''; ?>" id="si-cartaRisco">
                        <label>Exigir carta risco?</label>
                        <select class="form-control" name="exigirCartaRisco" id="exigirCartaRisco">
                            <option value="" <?php echo (isset($documento->exigirCartaRisco) && $documento->exigirCartaRisco == '') ? 'selected="true"' : '' ?>></option>
                            <option value="1" <?php echo (isset($documento->exigirCartaRisco) && $documento->exigirCartaRisco == '1') ? 'selected="true"' : '' ?>>Sim</option>
                            <option value="0" <?php echo (isset($documento->exigirCartaRisco) && $documento->exigirCartaRisco == '0') ? 'selected="true"' : '' ?>>Não</option>
                        </select>
                    </div>
                </div>

                
                <?php 
                $ativWithObsHist = array(5,8,16,13,21,24,29,32,47,52);
                if(in_array($idAtividade, $ativWithObsHist)):
                ?>
                <div class="row obs_historico">
                    <div class="clearfix"><hr></div>
                    <div class="col-md-12">
                        <label>Observação</label>
                        <textarea class="form-control" name="obs_historico"></textarea>
                    </div>
                </div>
                <div class="row">
                        <div class="col-md 6">
                        <?php if(isset($documento->exigirCartaRisco) && $documento->exigirCartaRisco == '1'): ?>
                            <label>Inserir carta risco</label>
                            <input type="file" class="form-control carta_risco" name="remove[cartaRisco]" accept="application/pdf">
                        <?php endif; ?>
                    </div>
                </div>
                <div class="clearfix"><hr></div>
                <div class="row">
                    <div class="col-md-12">
                        <h2>Gestor usuário</h2>
                        <span class="labelField">Carta Risco:</span><br>
                        <?php if(!empty($documento->cartaRisco)): ?>
                            <a href="<?php echo URL; ?>/Fluxo/cartaRisco/cartaRisco/<?php echo $documento->idSolicitacao; ?>" title="Visualizar carta de risco" target="_blank"> <i class="fa fa-download"></i> Carta Risco <i class="fa fa-file-pdf-o"></i></a>
                        <?php else: ?>
                            <span class="nomeField">Não</span>
                        <?php endif; ?>
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

    if($('#idFluxo').val() == 8 && $("#numAtividade").val() != "47"){
        $(".removerStatus").attr("readonly","true");
        $(".removerStatus").css({
            'pointer-events': 'none',
            'touch-action': 'none',
            'cursor': 'not-allowed',
        });
    }

    if($("#numAtividade").val() == "16" || $("#numAtividade").val() == "24" || $("#numAtividade").val() == "32"){
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
    if($("#numAtividade").val() == "8" || $("#numAtividade").val() == "16" || $("#numAtividade").val() == "24" || $("#numAtividade").val() == "32" || $("#numAtividade").val() == "47"){
        //$("#btnRejeitaSolicitacao").removeClass('hide');
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

            if(validaSelect === false && ($('#idFluxo').val() != 3 && $('#idFluxo').val() != 8)){
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
    if($("#numAtividade").val() == "9" || $("#numAtividade").val() == "17" || $("#numAtividade").val() == "25" || $("#numAtividade").val() == "33" || $("#numAtividade").val() == "48"){
        
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
    if($("#numAtividade").val() == "10" || $("#numAtividade").val() == "18" || $("#numAtividade").val() == "26" || $("#numAtividade").val() == "34" || $("#numAtividade").val() == "49"){
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
    if($("#numAtividade").val() == "11" || $("#numAtividade").val() == "19" || $("#numAtividade").val() == "27" || $("#numAtividade").val() == "35" || $("#numAtividade").val() == "50"){
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
    if($("#numAtividade").val() == "12" || $("#numAtividade").val() == "20" || $("#numAtividade").val() == "28" || $("#numAtividade").val() == "36" || $("#numAtividade").val() == "51"){
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
    if($("#numAtividade").val() == "13" || $("#numAtividade").val() == "21" || $("#numAtividade").val() == "29" || $("#numAtividade").val() == "37" || $("#numAtividade").val() == "52"){
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
        /*$(".aprovacao_si select").on('change', function(){
            if($(this).val() == 0){
                $('si_atividades').removeClass('hide');
               
                if($('#idFluxo').val() == 8){
                    $('#si-cartaRisco').removeClass('hide');
                }
            }else{
                $('si_atividades').addClass('hide');
                if($('#idFluxo').val() == 8){
                    $('#si-cartaRisco').addClass('hide');
                }
            }
        });*/
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

    // Recupera riscos dos grupos em fluxo de concessão e revogação
    if($('#idFluxo').val() == 8){
        $('.removerStatus').on('change', function () {                
            $('#load').css('display', 'block');
            $('#text-risco').css('display', 'block');

            $('#matriz-risco-grupos').html('');
            carregaRiscosGrupos();
            $('#tableAbaProgs').DataTable().ajax.reload();

        });
    }
    

    $('#aprovacao_si').on('change', function(){                
        if($(this).val() == '0'){
            $('#si-movimentacao').removeClass('hide');
            $('#si-cartaRisco').removeClass('hide');
        }else{
            $('#si-movimentacao').addClass('hide'); 
            $('#si-cartaRisco').addClass('hide');
        }
    });

    $('#si-movimentacao').on('change', function(){
        
        var el = $('#si-movimentacao option:selected').val().split('-');

        if($('#idFluxo').val() == 8){
            if(el[1] == 'criaAtividadeGestorUsuario' || el[1] == 'criaAtividadeGestorGrupo' || el[1] == 'criaAtividadeGestorModulo' || el[1] == 'criaAtividadeGestorRotina' || el[1] == 'criaAtividadeGestorPrograma'){
                $('#si-cartaRisco').removeClass('hide');
            }else{
                $("#si-cartaRisco option:selected").prop("selected", false);
                $("#si-cartaRisco option:first").prop("selected", true);
                $('#si-cartaRisco').addClass('hide');
            }
        }else{
            if(el[1] == 'criaAtividadeGestorGrupo' || el[1] == 'criaAtividadeGestorModulo' || el[1] == 'criaAtividadeGestorRotina' || el[1] == 'criaAtividadeGestorPrograma'){
                $('#si-cartaRisco').removeClass('hide');
            }else{
                $("#si-cartaRisco option:selected").prop("selected", false);
                $("#si-cartaRisco option:first").prop("selected", true);
                $('#si-cartaRisco').addClass('hide');
            }
        }
        
    });

});                                               

</script>