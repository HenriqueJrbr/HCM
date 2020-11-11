<div class="col-md-12 col-sm-12 col-xs-12">
    <ol class="breadcrumb">
        <li><a href="<?php echo URL ?>/" onclick="loadingPagia()">Dashboard</a></li>            
        <li>Matriz de Risco</li>            
    </ol>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Matriz Risco Composição Padrão</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer"><div class="row"><div class="col-sm-6"></div><div class="col-sm-6"></div></div><div class="row"><div class="col-sm-12">
                        <div class="panel-group" id="accordion"><!-- INICIO COLLAPSE -->
                            <?php
                            $area = "";
                            $risco = "";
                            $colAreaRisco = 1;
                            $idCollapseArea = 10000;
                            $idCollapseRisco = 20000;
                            $next = array();
                            $nextRisco = array();
                            $idTabela = 0;

                            foreach ($matrizDeRisco as $value):
                                ?>



                                <!-- INICIO IF 1 -->
                                <?php
                                if ($area != $value['descArea']):
                                    $area = $value['descArea'];
                                    ?>  
                                    <!-- INICIO COLLAPSE AREA -->      
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $idCollapseArea ?>">
                    <?php echo $value['descArea'] ?></a>
                                            </h4>
                                        </div>
                                        <div id="collapse<?php echo $idCollapseArea ?>" class="panel-collapse collapse">
                                            <div class="panel-body">
                                            <?php endif; ?>


                                            <?php
                                            if ($risco != $value['codRisco']):
                                                $risco = $value['codRisco'];
                                                $idTabela = $idTabela + 1;
                                                echo "<h5><strong>" . $value['codRisco'] . "</strong> <span class=\"badge \" style='background-color:".(($value['mitigado'] == 'Mitigado') ? '#26B99A' : '#d9534f' )."' >".$value['mitigado'] . "</span> - " . $value['descricao'] . "</h5><br>";
                                                ?>

                    <?php // header da tabela ?>      

                                                <table class="tabela table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="DataTables_Table_0_info" style="width: 100%;" id="DataTables_Table_<?php echo $idTabela ?>">
                                                    <thead>
                                                        <tr role="row"><td><strong>Composiçao do Risco</strong></td></tr>
                                                        <tr role="row">
                                                            <th>Grau de Risco</th>
                                                            <th>Processo Referencia</th>
                                                            <th>Programas do processo</th>
                                                            <th>Processos vinculados</th>
                                                            <th>Programas do processo</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                <?php endif; ?>

                                                    <tr>
                                                        <td style="background: <?php echo $value['bgcolor'] ?>;color:<?php echo $value['fgcolor'] ?>"><?php echo $value['gau_de_risco'] ?></font></td>
                                                        <td><?php echo $value['ProcessoPri'] ?></td>
                                                        <td><?php echo $value['programas'] ?></td>
                                                        <td><?php echo $value['processoSec'] ?></td>
                                                        <td><?php echo $value['programasSec'] ?></td>                          
                                                    </tr>


                                            <?php
                                            $next = next($matrizDeRisco);
                                            if ($risco != $next['codRisco']):
                                                ?>   
                                                    </tbody> 
                                                </table>
                                            <?php endif; ?>           




                <?php
                //$next = next($conflitos);
                if ($area != $next['descArea']):
                    ?>     <!-- FIM CONTEUDO DO COLLAPSE AREA -->
                                                <!-- </div> <!--accordionRisco-->           
                                            </div> <!-- FIM BODY AREA -->      
                                        </div>
                                    </div><!-- FIM COLLAPSE AREA -->
                    <?php
                    $colAreaRisco = $colAreaRisco + 1;
                    $idCollapseArea = $idCollapseArea + 1;
                endif;
                ?>

            <?php endforeach; ?>

                        </div><!-- FIM COLLAPSE -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
