<div class="col-md-12 col-sm-12 col-xs-12">
    <ol class="breadcrumb">
        <li><a href="<?php echo URL ?>/" onclick="loadingPagia()">Dashboard</a></li>  
        <li><a href="<?php echo URL ?>/Sistema"><font style="vertical-align: inherit;" onclick="loadingPagia()"><font style="vertical-align: inherit;">Sistema</font></font></a></li>
        <li class="active"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $descSistema['des_sist_dtsul'] ?></font></font></li>
    </ol>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title"><!--Inicia x_title-->
                <h2>Informações do Sistema/Modulo</h2>
                <div class="clearfix"></div>
            </div><!--Fim x_title-->
            <div class="row">
                <div class="col-md-12">
                    <label>Sistema:</label>
                    <span style="font-size: 16px"><strong><?php echo $descSistema['cod_sist_dtsul']; ?> - <?php echo $descSistema['des_sist_dtsul']; ?></strong></span>
                </div>
            </div>
            <br>
            <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                <div class="row">
                    <div class="col-sm-6"></div>
                    <div class="col-sm-6"></div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel-group" id="accordion"><!-- INICIO COLLAPSE -->
                            <?php 
                                $descModulo = "";
                                $rotina = "";
                                $colAreaRisco = 1;
                                $idCollapseModulo = 10000;
                                $idCollapseUsuario= 20000;
                                $next = array();
                                $nextRisco = array();
                                $idTabela = 0;
                                $usuario = "";
                                //echo "<pre>";
                                //print_r($modulo);
                                foreach ($modulo as $value):
                                    $next = next($modulo);
                                ?>       
                                    <!-- INICIO IF 1 -->
                                    <?php //echo $descModulo ." != ". $value['descricao_modulo']."<br>"; ?>
                                    <?php 
                                        if($descModulo != $value['descricao_modulo']):
                                            $descModulo = $value['descricao_modulo'];
                                            $usuario = '';
                                    ?>
                                        <!-- INICIO COLLAPSE AREA -->      
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $idCollapseModulo ?>">
                                                        Modulo <?php echo $value['descricao_modulo']; ?>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapse<?php echo $idCollapseModulo ?>" class="panel-collapse collapse">
                                                <div class="panel-body">
                                    <?php endif; ?>

                                    <!-- INICIO USUARIO -->
                                    <?php 
                                        if($usuario != $value['cod_usuario']):
                                            $usuario = $value['cod_usuario'];
                                    ?>
                                        <!-- INICIO COLLAPSE AREA -->      
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapse<?php echo $idCollapseUsuario ?>">
                                                        Usuário:<samp style="color:red"> <?php echo $value['cod_usuario'] ?>  - <?php echo $value['nome_usuario'] ?> - <span class="badge label-primary"><?php echo (($value['ativo'] == 1) ? 'Ativo' : 'Inativo'); ?></span> </samp>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapse<?php echo $idCollapseUsuario ?>" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <table class="tabela table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="DataTables_Table_0_info" style="width: 100%;" id="DataTables_Table_<?php echo $idTabela?>">
                                                        <thead>
                                                            <tr role="row">
                                                                <th>Rotina</th>
                                                                <th>Grupo</th>
                                                                <th>Quant. Programas</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                    <?php  endif; ?>
                                    <!-- FIM USUARIO -->        
                                                        <tr onclick="abrirModalModulo('<?php echo addslashes($value['Programas'])  ?>','<?php echo addslashes($value['descricao_rotina']) ?>','<?php echo $value['descricao_modulo'] ?>','<?php echo $value['nome_usuario'] ?>')">                  
                                                            <td><?php echo $value['descricao_rotina'] ?></td>
                                                            <td><?php echo $value['Grupos']  ?></td>
                                                            <td><?php echo $value['numProgramas']  ?></td>                                     
                                                        </tr>

                                    <?php 

                                        if($usuario != $next['cod_usuario'] || ($descModulo != $next['descricao_modulo'])):
                                    ?>     <!-- FIM CONTEUDO DO COLLAPSE AREA -->
                                                            <!-- </div> <!--accordionRisco-->
                                                            </tbody> 
                                                        </table>           
                                                    </div> <!-- FIM BODY AREA -->      
                                                </div>
                                            </div><!-- FIM COLLAPSE AREA -->
                                    <?php 
                                            $idCollapseUsuario = $idCollapseUsuario + 1;
                                        endif; 
                                    ?>
                                    <?php 
                                        //$next = next($modulo);
                                        //echo "<pre>";
                                        //print_r($next);
                                        //echo "</pre>";
                                        //echo $descModulo .' != '. $next['descricao_modulo'];
                                      if($descModulo != $next['descricao_modulo']):
                                     ?>     <!-- FIM CONTEUDO DO COLLAPSE AREA -->
                                           <!-- </div> <!--accordionRisco-->           
                                            </div> <!-- FIM BODY AREA -->      
                                          </div>
                                        </div><!-- FIM COLLAPSE AREA -->
                                  <?php 
                                      $idCollapseModulo = $idCollapseModulo + 1;
                                  endif; ?>

                     <?php endforeach; ?>

                   </div><!-- FIM COLLAPSE -->
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>



<div id="myModal2" class="modal fade" role="dialog">
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
              <tbody id="modalApresentaProgramamodulo"> 
              </tbody>
            </table>
         
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
      </div>
    </div>

  </div>
</div>