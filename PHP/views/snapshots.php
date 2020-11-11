<div class="col-md-12 col-sm-12 col-xs-12">
  <ol class="breadcrumb">
      <li><a href="<?php echo URL ?>/" onclick="loadingPagia()">Dashboard</a></li>          
        <li class="active">Usuários</li>
  </ol>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Snapshots</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="row">
                <div class="col-md-12"><?php echo $this->helper->alertMessage(); ?></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-md-3">
                        <button type="button" class="btn btn-success" id="btnAtualiza">Atualizar snapshot</button>
                    </div>
                    <div class="col-sm-6"></div>
                </div>
                <br>
                
                <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-striped hover table-bordered dt-responsive nowrap dataTable no-footer dtr-inline"
                                   cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info"
                                   style="width: 100%;" >
                                <thead>
                                <tr role="row">                                    
                                    <th>Usuário</th>
                                    <th>Empresa</th>
                                    <th>Data do Snapshot</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($snapshots as $val): ?>
                                    <tr>
                                        <td><?php echo $val['usuario']; ?></td>
                                        <td><?php echo $val['empresa']; ?></td>
                                        <td><?php echo date('d/m/Y H:i:s', strtotime($val['dataHora'])); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>                            
                        </div>
                        <div class="col-sm-6">                            
                            <span class="badge" style="padding: 5px;background-color: rgba(243, 156, 18, 0.88)">O SGA não acumula shapshots, só será possível observar o ultimo gerado!</span>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- set up the modal to start hidden and fade in and out -->
<div id="myModalConfirm" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- dialog body -->
            <div class="modal-body">
                <div class="row">
                    <p class="msgBody" style='margin: 5px 10px'>Tem certeza que deseja continuar?</p>                                    
                </div>
            </div>
            <!-- dialog buttons -->
            <div class="modal-footer">
                <button type="button" id="cancel" class="btn btn-danger">Cancelar</button>
                <button type="button" id="continue" class="btn btn-success">Continuar</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){   
        $('#btnAtualiza').on('click', function(){
           $("#myModalConfirm").modal('show');
           $(document).on("click", '#myModalConfirm #continue', function(e) {
                $("#myModalConfirm").modal('hide');
                location.href = url + 'ConfiguracaoSga/atualizaSnapshots';
            });     

            // Se botao de cancelar for clicado
            $('#myModalConfirm').find('#cancel').on("click", function(e) {
                $("#myModalConfirm").modal('hide');                
                return false;
            });
        });
    });
</script>