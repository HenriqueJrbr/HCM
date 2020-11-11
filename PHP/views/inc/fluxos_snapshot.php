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
                                        <!--<li role="presentation" class="active">
                                            <a href="#tab_content4" id="profile-tab4" role="tab" data-toggle="tab" aria-expanded="true">Riscos <span class="badge badge-danger" id="count-riscos">0</span></a>
                                        </li>-->
                                        <li role="presentation">
                                            <a href="#tab_content5" id="profile-tab5" role="tab" data-toggle="tab" aria-expanded="true">Programas <span class="badge"><?php echo $totalProg; ?></span></a>
                                        </li>
                                    </ul>
                                    <div id="myTabContent2" class="tab-content">
                                        <div role="tabpane2" class="tab-pane fade active in" id="tab_content4" aria-labelledby="profile-tab4">--><!--Inicio Tabela 1 -->
                                            <div id="matriz-risco-grupos-foto"></div>
                                                <script>
                                                    $(document).ready(function(){
                                                        $.ajax({
                                                           type: 'POST' ,
                                                           url: url + 'Fluxo/ajaxMatrizDeRiscoFoto',
                                                           data: {
                                                               idSolicitacao: $('#idSolicitacao').val()
                                                           },
                                                           dataType: 'json',
                                                           success: function(data){
                                                               //var res = JSON.parse(data);
                                                               $('#count-riscos-foto').text(data.totalRiscos);
                                                               $('#matriz-risco-grupos-foto').html(data.html);
                                                           }
                                                        });
                                                    });
                                                </script>
                                        </div>
                                        <div role="tabpane2" class="tab-pane fade active in" id="tab_content5" aria-labelledby="profile-tab5"><!--Inicio Tabela 1 -->
                                            <?php $this->helper->tabLogProgramas($documento->idSolicitacao); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>