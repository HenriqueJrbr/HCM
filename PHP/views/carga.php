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
        <li class="active"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Carga de usuários, grupos e programas</font></font></li>
    </ol>
</div>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Carga <small> Usuários, Grupos e Programas</small></h2>
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
                <form action="<?php echo URL; ?>/Carga/iniciaCarga" method="POST" id="frmAnalisa" enctype="multipart/form-data">
                    <input type="hidden" name="analise" value="1">
                    <div class="row">
                        <div class="col-md-6">
                            <a class="btn btn-danger" id="exportExcel">
                                <i class="fa fa-file-excel-o"></i> Baixar programa para extração de dados SGA
                            </a>
                        </div>	                        
                    </div>
                    <br>            
                    <div class="row">       
						<div class="col-md-12" style="background:#f2f2f2;padding:15px">
							<div class="col-md-1 pull-right">
								<label><br></label>
								<input type="button" class="form-control" id="btnSendFile" value="Analisar" style="background-color: #5cb85c;border-color: #5cb85c;color: #fff">
							</div>
							<div class="col-md-6 pull-right">
								<label>Arquivo CSV para sincronização</label>
								<input type="file" class="form-control" name="file" id="file" accept="´.zip" required>
							</div>
						</div>
                    </div>
                </form>
            </div>

            <?php if(isset($podeSincronizar) && $podeSincronizar == true): ?>
				<div class="row">
					<form action="<?php echo URL; ?>/Carga/iniciaCarga" id="frmSincroniza" method="post">
                        <input type="hidden" name="file" value="<?php echo (isset($file)) ? $file : '' ; ?>">
                        <input type="hidden" name="analise" value="0">
						<div class="col-md-12">
							<label><br></label>
							<input type="button" class="form-control pull-right" value="Sincronizar" id="btnSincroniza" style="background-color: #5cb85c;border-color: #5cb85c;color: #fff">
						</div>
					</form>
				</div>
            <?php endif; ?>	

            <!-- Informações de programas a sincronizar -->
            <?php if(isset($programas)): ?>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Programas</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">                                                                                
                            <div class="row <?php echo (!isset($programas['inicio'])) ? 'hide' : '' ?>" id="result">		
                                <div class="clearfix"><hr></div>				
                                <?php if(isset($programas['inicio'])): ?>
                                    <div class="col-md-2">
                                        <strong class="infosfluxos">Início:</strong> <strong><?php echo $programas['inicio']; ?></strong>
                                    </div>
                                <?php endif; ?>
                                <?php if(isset($programas['fim'])): ?>
                                    <div class="col-md-2">
                                        <strong class="infosfluxos">Fim:</strong> <strong><?php echo $programas['fim']; ?></strong>
                                    </div>
                                <?php endif; ?>
                                <?php if(isset($programas['totCadastrados'])): ?>
                                    <div class="col-md-2">
                                        <strong class="infosfluxos">Já cadastrados:</strong> <strong><?php echo $programas['totCadastrados']; ?></strong>
                                    </div>
                                <?php endif; ?>
                                <?php if(isset($programas['totNaoCadastrados'])): ?>
                                    <div class="col-md-3">
                                        <strong class="infosfluxos">Não cadastrados:</strong> <strong><?php echo $programas['totNaoCadastrados']; ?></strong>
                                    </div>
                                <?php endif; ?>
                                <?php if(isset($programas['totEliminados'])): ?>
                                    <div class="col-md-3">
                                        <strong class="infosfluxos">Cadastrados a remover:</strong> <strong><?php echo $programas['totEliminados']; ?></strong>
                                    </div>
                                <?php endif; ?>
                            </div>                                                                
                                                        
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
                                                        aria-label="Last name: activate to sort column ascending">Cód. Programa
                                                    </th>
                                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                                        rowspan="1" colspan="1" style="width: 10px;"
                                                        aria-label="Last name: activate to sort column ascending">Programa
                                                    </th>
                                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                                        rowspan="1" colspan="1" style="width: 10%;"
                                                        aria-label="Last name: activate to sort column ascending">Ação
                                                    </th>
                                                </thead>
                                                <tbody id="htmlTable">
                                                <?php
                                                    if(isset($programas['htmlTable']) && !empty($programas['htmlTable'])):
                                                        echo $programas['htmlTable'];
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
            <?php endif; ?>
            <!-- FIM Informações de programas a sincronizar -->

            <!-- Informações de programas vs empresas a sincronizar -->
            <?php if(isset($programaEmpresa)): ?>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Programas vs Empresas</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">                                
                            <div class="row <?php echo (!isset($programaEmpresa['inicio'])) ? 'hide' : '' ?>" id="result">
                                <div class="clearfix"><hr></div>				
                                <?php if(isset($programaEmpresa['inicio'])): ?>
                                    <div class="col-md-2">
                                        <strong class="infosfluxos">Início:</strong> <strong><?php echo $programaEmpresa['inicio']; ?></strong>
                                    </div>
                                <?php endif; ?>
                                <?php if(isset($programaEmpresa['fim'])): ?>
                                    <div class="col-md-2">
                                        <strong class="infosfluxos">Fim:</strong> <strong><?php echo $programaEmpresa['fim']; ?></strong>
                                    </div>
                                <?php endif; ?>
                                <?php if(isset($programaEmpresa['totCadastrados'])): ?>
                                    <div class="col-md-2">
                                        <strong class="infosfluxos">Já cadastrados:</strong> <strong><?php echo $programaEmpresa['totCadastrados']; ?></strong>
                                    </div>
                                <?php endif; ?>
                                <?php if(isset($programaEmpresa['totNaoCadastrados'])): ?>
                                    <div class="col-md-3">
                                        <strong class="infosfluxos">Não cadastrados:</strong> <strong><?php echo $programaEmpresa['totNaoCadastrados']; ?></strong>
                                    </div>
                                <?php endif; ?>
                                <?php if(isset($programaEmpresa['totEliminados'])): ?>
                                    <div class="col-md-3">
                                        <strong class="infosfluxos">Cadastrados a remover:</strong> <strong><?php echo $programaEmpresa['totEliminados']; ?></strong>
                                    </div>
                                <?php endif; ?>
                            </div>							                                                               		                			
                                            
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
                                                        aria-label="Last name: activate to sort column ascending">ID Programa
                                                    </th>										
                                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                                        rowspan="1" colspan="1" style="width: 20%;"
                                                        aria-label="Last name: activate to sort column ascending">ID Empresa
                                                    </th>                                        
                                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                                        rowspan="1" colspan="1" style="width: 10%;"
                                                        aria-label="Last name: activate to sort column ascending">Ação
                                                    </th>
                                                </thead>
                                                <tbody id="htmlTable">
                                                <?php
                                                    if(isset($programaEmpresa['htmlTable']) && !empty($programaEmpresa['htmlTable'])):
                                                        echo $programaEmpresa['htmlTable'];
                                                    endif;
                                                ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- Fim datatable -->                    
                            </div>												
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <!-- FIM Informções de programas a vs empresas sincronizar -->

            <!-- Informações de usuários a sincronizar -->
            <?php if(isset($usuarios)): ?>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Usuários</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">                                                                            
                            <div class="row <?php echo (!isset($usuarios['inicio'])) ? 'hide' : '' ?>" id="result">		
                                <div class="clearfix"><hr></div>				
                                <?php if(isset($usuarios['inicio'])): ?>
                                    <div class="col-md-2">
                                        <strong class="infosfluxos">Início:</strong> <strong><?php echo $usuarios['inicio']; ?></strong>
                                    </div>
                                <?php endif; ?>
                                <?php if(isset($usuarios['fim'])): ?>
                                    <div class="col-md-2">
                                        <strong class="infosfluxos">Fim:</strong> <strong><?php echo $usuarios['fim']; ?></strong>
                                    </div>
                                <?php endif; ?>
                                <?php if(isset($usuarios['totCadastrados'])): ?>
                                    <div class="col-md-2">
                                        <strong class="infosfluxos">Já cadastrados:</strong> <strong><?php echo $usuarios['totCadastrados']; ?></strong>
                                    </div>
                                <?php endif; ?>
                                <?php if(isset($usuarios['totNaoCadastrados'])): ?>
                                    <div class="col-md-3">
                                        <strong class="infosfluxos">Não cadastrados:</strong> <strong><?php echo $usuarios['totNaoCadastrados']; ?></strong>
                                    </div>
                                <?php endif; ?>
                                <?php if(isset($usuarios['totEliminados'])): ?>
                                    <div class="col-md-3">
                                        <strong class="infosfluxos">Cadastrados a remover:</strong> <strong><?php echo $usuarios['totEliminados']; ?></strong>
                                    </div>
                                <?php endif; ?>
                            </div>                                                                
                                                        
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
                                                        aria-label="Last name: activate to sort column ascending">Ação
                                                    </th>
                                                </thead>
                                                <tbody id="htmlTable">
                                                <?php
                                                    if(isset($usuarios['htmlTable']) && !empty($usuarios['htmlTable'])):
                                                        echo $usuarios['htmlTable'];
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
            <?php endif; ?>
            <!-- FIM Informações de usuários a sincronizar -->

            <!-- Informações de usuários vs empresa a sincronizar -->
            <?php if(isset($usuarioEmpresa)): ?>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Usuários vs Empresas</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">                                
                            <div class="row <?php echo (!isset($usuarioEmpresa['inicio'])) ? 'hide' : '' ?>" id="result">
                                <div class="clearfix"><hr></div>				
                                <?php if(isset($inicio)): ?>
                                    <div class="col-md-2">
                                        <strong class="infosfluxos">Início:</strong> <strong><?php echo $usuarioEmpresa['inicio']; ?></strong>
                                    </div>
                                <?php endif; ?>
                                <?php if(isset($usuarioEmpresa['fim'])): ?>
                                    <div class="col-md-2">
                                        <strong class="infosfluxos">Fim:</strong> <strong><?php echo $usuarioEmpresa['fim']; ?></strong>
                                    </div>
                                <?php endif; ?>
                                <?php if(isset($usuarioEmpresa['totCadastrados'])): ?>
                                    <div class="col-md-2">
                                        <strong class="infosfluxos">Já cadastrados:</strong> <strong><?php echo $usuarioEmpresa['totCadastrados']; ?></strong>
                                    </div>
                                <?php endif; ?>
                                <?php if(isset($usuarioEmpresa['totNaoCadastrados'])): ?>
                                    <div class="col-md-3">
                                        <strong class="infosfluxos">Não cadastrados:</strong> <strong><?php echo $usuarioEmpresa['totNaoCadastrados']; ?></strong>
                                    </div>
                                <?php endif; ?>
                                <?php if(isset($usuarioEmpresa['totEliminados'])): ?>
                                    <div class="col-md-3">
                                        <strong class="infosfluxos">Cadastrados a remover:</strong> <strong><?php echo $usuarioEmpresa['totEliminados']; ?></strong>
                                    </div>
                                <?php endif; ?>
                            </div>							
                            
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
                                                        aria-label="Last name: activate to sort column ascending">ID Usuário
                                                    </th>
                                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                                        rowspan="1" colspan="1" style="width: 20%;"
                                                        aria-label="Last name: activate to sort column ascending">ID Empresa
                                                    </th>                                        
                                                    </th>                                        
                                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                                        rowspan="1" colspan="1" style="width: 10%;"
                                                        aria-label="Last name: activate to sort column ascending">Ação
                                                    </th>
                                                </thead>
                                                <tbody id="htmlTable">
                                                <?php
                                                    if(isset($usuarioEmpresa['htmlTable']) && !empty($usuarioEmpresa['htmlTable'])):
                                                        echo $usuarioEmpresa['htmlTable'];
                                                    endif;
                                                ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- Fim datatable -->                    
                            </div>												
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <!-- FIM Informações de usuários vs empresa a sincronizar -->
            
            <!-- Informações de grupos a sincronizar -->
            <?php if(isset($grupos)): ?>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Grupos</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">				                                                                                
                            <div class="row <?php echo (!isset($grupos['inicio'])) ? 'hide' : '' ?>" id="result">		
                                <div class="clearfix"><hr></div>				
                                <?php if(isset($inicio)): ?>
                                    <div class="col-md-2">
                                        <strong class="infosfluxos">Início:</strong> <strong><?php echo $grupos['inicio']; ?></strong>
                                    </div>
                                <?php endif; ?>
                                <?php if(isset($grupos['fim'])): ?>
                                    <div class="col-md-2">
                                        <strong class="infosfluxos">Fim:</strong> <strong><?php echo $grupos['fim']; ?></strong>
                                    </div>
                                <?php endif; ?>
                                <?php if(isset($grupos['totCadastrados'])): ?>
                                    <div class="col-md-2">
                                        <strong class="infosfluxos">Já cadastrados:</strong> <strong><?php echo $grupos['totCadastrados']; ?></strong>
                                    </div>
                                <?php endif; ?>
                                <?php if(isset($grupos['totNaoCadastrados'])): ?>
                                    <div class="col-md-3">
                                        <strong class="infosfluxos">Não cadastrados:</strong> <strong><?php echo $grupos['totNaoCadastrados']; ?></strong>
                                    </div>
                                <?php endif; ?>
                                <?php if(isset($grupos['totEliminados'])): ?>
                                    <div class="col-md-3">
                                        <strong class="infosfluxos">Cadastrados a remover:</strong> <strong><?php echo $grupos['totEliminados']; ?></strong>
                                    </div>
                                <?php endif; ?>
                            </div>
                                                                    
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
                                                        aria-label="Last name: activate to sort column ascending">Cód. Grupo
                                                    </th>
                                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                                        rowspan="1" colspan="1" style="width: 10px;"
                                                        aria-label="Last name: activate to sort column ascending">Grupo
                                                    </th>
                                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                                        rowspan="1" colspan="1" style="width: 10%;"
                                                        aria-label="Last name: activate to sort column ascending">Ação
                                                    </th>
                                                </thead>
                                                <tbody id="htmlTable">
                                                <?php
                                                    if(isset($grupos['htmlTable']) && !empty($grupos['htmlTable'])):
                                                        echo $grupos['htmlTable'];
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
            <?php endif; ?>
            <!-- FIM Informações de grupos a sincronizar -->
            
            <!-- Informações de grupos vs usuarios a sincronizar -->
            <?php if(isset($grupoUsuario)): ?>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Grupos vs Usuários</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">                                
                            <div class="row <?php echo (!isset($grupoUsuario['inicio'])) ? 'hide' : '' ?>" id="result">
                                <div class="clearfix"><hr></div>				
                                <?php if(isset($grupoUsuario['inicio'])): ?>
                                    <div class="col-md-2">
                                        <strong class="infosfluxos">Início:</strong> <strong><?php echo $grupoUsuario['inicio']; ?></strong>
                                    </div>
                                <?php endif; ?>
                                <?php if(isset($grupoUsuario['fim'])): ?>
                                    <div class="col-md-2">
                                        <strong class="infosfluxos">Fim:</strong> <strong><?php echo $grupoUsuario['fim']; ?></strong>
                                    </div>
                                <?php endif; ?>
                                <?php if(isset($grupoUsuario['totCadastrados'])): ?>
                                    <div class="col-md-2">
                                        <strong class="infosfluxos">Já cadastrados:</strong> <strong><?php echo $grupoUsuario['totCadastrados']; ?></strong>
                                    </div>
                                <?php endif; ?>
                                <?php if(isset($grupoUsuario['totNaoCadastrados'])): ?>
                                    <div class="col-md-3">
                                        <strong class="infosfluxos">Não cadastrados:</strong> <strong><?php echo $grupoUsuario['totNaoCadastrados']; ?></strong>
                                    </div>
                                <?php endif; ?>
                                <?php if(isset($grupoUsuario['totEliminados'])): ?>
                                    <div class="col-md-3">
                                        <strong class="infosfluxos">Cadastrados a remover:</strong> <strong><?php echo $grupoUsuario['totEliminados']; ?></strong>
                                    </div>
                                <?php endif; ?>
                            </div>							
                                                            
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
                                                        aria-label="Last name: activate to sort column ascending">Cód. Grupo
                                                    </th>
                                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                                        rowspan="1" colspan="1" style="width: 20%;"
                                                        aria-label="Last name: activate to sort column ascending">Grupo
                                                    </th>
                                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                                        rowspan="1" colspan="1" style="width: 20%;"
                                                        aria-label="Last name: activate to sort column ascending">Cód. usuário
                                                    </th>                                        
                                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                                        rowspan="1" colspan="1" style="width: 10%;"
                                                        aria-label="Last name: activate to sort column ascending">Ação
                                                    </th>
                                                </thead>
                                                <tbody id="htmlTable">
                                                <?php
                                                    if(isset($grupoUsuario['htmlTable']) && !empty($grupoUsuario['htmlTable'])):
                                                        echo $grupoUsuario['htmlTable'];
                                                    endif;
                                                ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- Fim datatable -->                    
                            </div>												
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <!-- FIM Informações de grupos vs usuarios a sincronizar -->

            <!-- Informações de grupos vs programas a sincronizar -->
            <?php if(isset($grupoPrograma)): ?>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Grupos vs Programas</small></h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">                                
                            <div class="row <?php echo (!isset($grupoPrograma['inicio'])) ? 'hide' : '' ?>" id="result">
                                <div class="clearfix"><hr></div>				
                                <?php if(isset($inicio)): ?>
                                    <div class="col-md-2">
                                        <strong class="infosfluxos">Início:</strong> <strong><?php echo $grupoPrograma['inicio']; ?></strong>
                                    </div>
                                <?php endif; ?>
                                <?php if(isset($grupoPrograma['fim'])): ?>
                                    <div class="col-md-2">
                                        <strong class="infosfluxos">Fim:</strong> <strong><?php echo $grupoPrograma['fim']; ?></strong>
                                    </div>
                                <?php endif; ?>
                                <?php if(isset($grupoPrograma['totCadastrados'])): ?>
                                    <div class="col-md-2">
                                        <strong class="infosfluxos">Já cadastrados:</strong> <strong><?php echo $grupoPrograma['totCadastrados']; ?></strong>
                                    </div>
                                <?php endif; ?>
                                <?php if(isset($grupoPrograma['totNaoCadastrados'])): ?>
                                    <div class="col-md-3">
                                        <strong class="infosfluxos">Não cadastrados:</strong> <strong><?php echo $grupoPrograma['totNaoCadastrados']; ?></strong>
                                    </div>
                                <?php endif; ?>
                                <?php if(isset($grupoPrograma['totEliminados'])): ?>
                                    <div class="col-md-3">
                                        <strong class="infosfluxos">Cadastrados a remover:</strong> <strong><?php echo $grupoPrograma['totEliminados']; ?></strong>
                                    </div>
                                <?php endif; ?>
                            </div>							                                                                			                			
                                            
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
                                                        aria-label="Last name: activate to sort column ascending">Cód. Grupo
                                                    </th>
                                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                                        rowspan="1" colspan="1" style="width: 20%;"
                                                        aria-label="Last name: activate to sort column ascending">Grupo
                                                    </th>
                                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                                        rowspan="1" colspan="1" style="width: 20%;"
                                                        aria-label="Last name: activate to sort column ascending">Cód. Programa
                                                    </th>                                        
                                                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive"
                                                        rowspan="1" colspan="1" style="width: 10%;"
                                                        aria-label="Last name: activate to sort column ascending">Ação
                                                    </th>
                                                </thead>
                                                <tbody id="htmlTable">
                                                <?php
                                                    if(isset($grupoPrograma['htmlTable']) && !empty($grupoPrograma['htmlTable'])):
                                                        echo $grupoPrograma['htmlTable'];
                                                    endif;
                                                ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- Fim datatable -->                    
                            </div>												
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <!-- FIM Informações de grupos vs programas a sincronizar -->				
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
                                    <th>Usuário</th>
									<th>Programas</th>
                                    <th>Prog. eliminados</th>
                                    <th>Prog. x Empresa</th>
                                    <th>Prog. x Empresa eliminados</th>
                                    <th>Usuários</th>
                                    <th>Usuários eliminados</th>
                                    <th>Usuário x Empresa</th>
                                    <th>Usuário x Empresa eliminados</th>
                                    <th>Grupos</th>
                                    <th>Grupos eliminados</th>
                                    <th>Grupo x Usuário</th>
                                    <th>Grupo x Usuário eliminados</th>
                                    <th>Grupo x Programa</th>
                                    <th>Grupo x Programa eliminados</th>
                                    <th>Anexo</th>									
									<th>Data</th>
                                    <th>Status</th>
								</tr>
								</thead>
								<tbody>
									<?php foreach($logs as $log): ?>
									<tr>										
										<td><?php echo $log['nome_usuario']; ?></td>
                                        <td><?php echo $log['progs']; ?></td>
                                        <td><?php echo $log['progsDel']; ?></td>
                                        <td><?php echo $log['progEmp']; ?></td>
                                        <td><?php echo $log['progEmpDel']; ?></td>
                                        <td><?php echo $log['users']; ?></td>
                                        <td><?php echo $log['usersDel']; ?></td>
                                        <td><?php echo $log['userEmp']; ?></td>
                                        <td><?php echo $log['userEmpDel']; ?></td>
                                        <td><?php echo $log['grupos']; ?></td>
                                        <td><?php echo $log['gruposDel']; ?></td>
                                        <td><?php echo $log['grupoUser']; ?></td>
                                        <td><?php echo $log['grupoUserDel']; ?></td>
                                        <td><?php echo $log['grupoProg']; ?></td>
                                        <td><?php echo $log['grupoProgDel']; ?></td>
                                        <td><?php echo $log['anexo']; ?></td>                                        
										<td><?php echo date('d/m/Y H:i:s', strtotime($log['data'])); ?></td>										
                                        <td><?php echo $log['status']; ?></td> 
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
            <div class="modal-body">Tem certeza que deseja importar os dados para a base?</div>
            <!-- dialog buttons -->
            <div class="modal-footer">
                <button type="button" id="cancel" class="btn btn-danger">Cancelar</button>
                <button type="button" id="continue" class="btn btn-success">Continuar</button>
            </div>
        </div>
    </div>
</div>

<!-- set up the modal to start hidden and fade in and out -->
<div id="myModalAjaxResult" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- dialog body -->
            <div class="modal-body">
                <h3>Processando...</h3>
                <p id="resAjax"></p>
            </div>
            <!-- dialog buttons -->
            <div class="modal-footer">
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
            //$('#myModalConfirm .modal-body').html('<h5>Tem certeza que deseja importar os dados para a base?</h5>');
            $("#myModalConfirm").modal('show');

            // Se botao de continuar for clicado
            $('#myModalConfirm').find('#continue').on("click", function(e) {
                $('#load').css('display', 'block');
                //$('#frmSincroniza').submit();
                $("#myModalConfirm").modal('hide');
                $("#myModalAjaxResult").modal('show');

                $.ajax({
                    type: 'POST',
                    url: url+'/Carga/iniciaCarga',
                    data: {
                        analise: 0,
                        file: '<?php echo (isset($file)) ? $file : '' ; ?>'
                    },
                    success: function(data){     
                        if('success'){
                            location.href = url + 'Carga';
                        }else{
                            $('#resAjax').html(data);
                        }            
                        
                    }
                });
            });

            // Se botao de cancelar for clicado
            $('#myModalConfirm').find('#cancel').on("click", function(e) {
                $("#myModalConfirm").modal('hide');
            });

        });
    });    
</script>