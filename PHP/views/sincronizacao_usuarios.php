<style type="text/css">
    .percent{
        background: #5eff4b;
    }
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
        <li><a href="<?php echo URL ?>"><font style="vertical-align: inherit;" onclick="loadingPagia()"><font style="vertical-align: inherit;">Dashboard</font></font></a></li>
		<li><font style="vertical-align: inherit;">Sincronização</font></font></li>
        <li class="active"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Usuários</font></font></li>
    </ol>
</div>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Sincronização<small> Usuários</small></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
				<div class="row">
					<div class="col-md-12">
						<?php echo $this->helper->alertMessage(); ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<a class="btn btn-danger" id="exportExcel">
							<i class="fa fa-file-excel-o"></i> Baixar programa para extração de dados SGA
						</a>
					</div>	
				</div>
				<br>
                <form action="" method="POST" id="frmAnalisa" enctype="multipart/form-data">
                    <div class="row">       
						<div class="col-md-12" style="background:#f2f2f2;padding:15px">
							<div class="col-md-1 pull-right">
								<label><br></label>
								<input type="button" class="form-control" id="btnSendFile" value="Analisar" style="background-color: #5cb85c;border-color: #5cb85c;color: #fff">
							</div>
							<div class="col-md-6 pull-right">
								<label>Arquivo CSV para sincronização</label>
								<input type="file" class="form-control" name="file" id="file" accept=".csv,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" required>
							</div>
						</div>
                    </div>
                </form>
                				
				<div class="row <?php echo (!isset($inicio)) ? 'hide' : '' ?>" id="result">		
					<div class="clearfix"><hr></div>				
					<?php if(isset($inicio)): ?>
						<div class="col-md-3">
							<strong class="infosfluxos">Início:</strong> <strong><?php echo $inicio; ?></strong>
						</div>
					<?php endif; ?>
					<?php if(isset($fim)): ?>
						<div class="col-md-3">
							<strong class="infosfluxos">Fim:</strong> <strong><?php echo $fim; ?></strong>
						</div>
					<?php endif; ?>
					<?php if(isset($totCadastrados)): ?>
						<div class="col-md-3">
							<strong class="infosfluxos">Total já cadastrados:</strong> <strong><?php echo $totCadastrados; ?></strong>
						</div>
					<?php endif; ?>
					<?php if(isset($totNaoCadastrados)): ?>
						<div class="col-md-3">
							<strong class="infosfluxos">Total não cadastrados:</strong> <strong><?php echo $totNaoCadastrados; ?></strong>
						</div>
					<?php endif; ?>
				</div>
				
				<?php if(isset($podeSincronizar) && $podeSincronizar == true): ?>
				<div class="row">
					<form action="<?php echo URL; ?>/Sincronizacao/sincronizaUsuarios" id="frmSincroniza" method="post">                            
						<div class="col-md-12">
							<label><br></label>
							<input type="button" class="form-control pull-right" value="Sincronizar" id="btnSincroniza" style="background-color: #5cb85c;border-color: #5cb85c;color: #fff">
						</div>
					</form>					
				</div>
				<?php endif; ?>
											
                <div class="x_content">
					<div class="clearfix"><hr></div>
					<!-- Começo datatable -->
                    <div id="datatable-responsive_wrapper"
                         class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                        <div class="row">
                            <div class="col-sm-6"></div>
                            <div class="col-sm-6"></div>
                        </div>				
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="tabela table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline"
                                       cellspacing="0" width="100%" role="grid"
                                       aria-describedby="datatable-responsive_info" style="width: 100%;">
                                    <thead>
                                    <tr role="row">
                                        <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive"
                                            rowspan="1" colspan="1" style="width: 10%;" aria-sort="ascending"
                                            aria-label="First name: activate to sort column descending">Linha
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                            rowspan="1" colspan="1" style="width: 20%;"
                                            aria-label="Last name: activate to sort column ascending">Cód. Usuário
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                            rowspan="1" colspan="1" style="width: 10px;"
                                            aria-label="Last name: activate to sort column ascending">Nome
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                            rowspan="1" colspan="1" style="width: 10%;"
                                            aria-label="Last name: activate to sort column ascending">Cadastrado?
                                        </th>
                                    </thead>
                                    <tbody id="htmlTable">
                                    <?php
                                        if(isset($htmlTable) && !empty($htmlTable)):
                                            echo $htmlTable;
                                        endif;
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>                    
                </div>
				<!-- Fim datatable -->
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Logs de sincronização</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
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
								   style="width: 100%;" id="table">
								<thead>
								<tr role="row">									
									<th>#</th>
									<th>Usuário</th>
									<th>Data</th>
									<th>Cadastros</th>
								</tr>
								</thead>
								<tbody>
									<?php foreach($logs as $log): ?>
									<tr>
										<td><?php echo $log['id']; ?></td>
										<td><?php echo $log['nome_usuario']; ?></td>
										<td><?php echo date('d/m/Y H:i:s', strtotime($log['data'])); ?></td>
										<td><?php echo $log['numSincronizado']; ?></td>
									</tr>
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

<!-- set up the modal to start hidden and fade in and out -->
<div id="myModalConfirm" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- dialog body -->
            <div class="modal-body">Tem certeza que deseja sincronizar os dados?</div>
            <!-- dialog buttons -->
            <div class="modal-footer">
                <button type="button" id="cancel" class="btn btn-danger">Cancelar</button>
                <button type="button" id="continue" class="btn btn-success">Continuar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
		$('#exportExcel').on('click', function(){
			location.href = url + "programa_progress/Extracao_de_dados_SGA.p";
		});
		
        $('#btnSendFile').on('click', function(){
            if($('#file').val() == ''){
                return false;
            }else{
                $('#load').css('display', 'block');
                $('#frmAnalisa').submit();
            }
        });

        $('#btnSincroniza').on('click', function(){
            $('#myModalConfirm .modal-body').html('<h5>Tem certeza que deseja realizar a sincronização?</h5>');
            $("#myModalConfirm").modal('show');

            // Se botao de continuar for clicado
            $('#myModalConfirm').find('#continue').on("click", function(e) {
                $('#load').css('display', 'block');
                $('#frmSincroniza').submit();
                $("#myModalConfirm").modal('hide');
            });

            // Se botao de cancelar for clicado
            $('#myModalConfirm').find('#cancel').on("click", function(e) {
                $("#myModalConfirm").modal('hide');
            });

        });
    });
</script>