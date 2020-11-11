<style type="text/css">
	#country-list{float:left;list-style:none;margin-top:2px;padding:0;width:540px; position: absolute; z-index:999;}
    #country-list li{padding: 10px; background: #f0f0f0; border-bottom: #bbb9b9 1px solid; border-radius: 5px;}
    #country-list li:hover{background:#ece3d2;cursor: pointer;}
    #search-box{padding: 10px;border: #a8d4b1 1px solid;border-radius:4px;}

    #country-list2{float:left;list-style:none;margin-top:2px;padding:0;width:540px; position: absolute; z-index:999;}
    #country-list2 li{padding: 10px; background: #f0f0f0; border-bottom: #bbb9b9 1px solid; border-radius: 5px;}
    #country-list2 li:hover{background:#ece3d2;cursor: pointer;}
    #search-box2{padding: 10px;border: #a8d4b1 1px solid;border-radius:4px;}

    #country-list3{float:left;list-style:none;margin-top:2px;padding:0;width:540px; position: absolute; z-index:999;}
    #country-list3 li{padding: 10px; background: #f0f0f0; border-bottom: #bbb9b9 1px solid; border-radius: 5px;}
    #country-list3 li:hover{background:#ece3d2;cursor: pointer;}
    #search-box3{padding: 10px;border: #a8d4b1 1px solid;border-radius:4px;}
</style>


<div class="col-md-12 col-sm-12 col-xs-12">
  <ol class="breadcrumb">
    <li><a href="<?php echo URL ?>/Matriz/cadastroProcesso"><font style="vertical-align: inherit;" onclick="loadingPagia()"><font style="vertical-align: inherit;">Processo</font></font></a></li>
    <li class="active"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Cadastro</font></font></li>
  </ol>
</div>

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Detalhe Processo</h2>
        <ul class="nav navbar-right panel_toolbox">
          <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
          </li>      
        </ul>
        <div class="clearfix"></div>
      </div>
        <div class="x_content">
            <div class="row">
                <div class="col-md-12">
                    <?php $this->helper->alertMessage(); ?>
                </div>
            </div>
        <br>
        <form id="demo-form2" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">

          <div class="row">
          	<div class="col-md-2">
          	  <label  for="first-name">Cod. Risco</label>
              <input type="text" id="codRisco" name="codRisco" required="required" class="form-control" value="<?php echo $processo['codRisco']; ?> " readonly="readonly">
              <input type="text" id="idCodRisco" name="idCodRisco" required="required" class="form-control hide" value="<?php echo $processo['idMtzRisco']; ?> ">
          	</div>
          </div>
          <div class="row">
          	<div class="col-md-6">
          	  	<label  for="first-name">Descrição</label>
             	<textarea class="form-control" id="descricaoCodRisco" name="descricaoCodRisco" rows="5" readonly="readonly"><?php echo $processo['descricaoRisco']; ?></textarea>
          	</div>
          </div>
          <div class="row">
          	<div class="col-md-2">
          	  <label  for="first-name">Grupo Processo</label>
              <input type="text" id="grupoProcesso" name="grupoProcesso" required="required" class="form-control" value="<?php echo $processo['descricaoGrupo']; ?> " readonly="readonly">
              <input type="text" id="idgrupoProcesso" name="idgrupoProcesso" required="required" class="form-control hide" value="<?php echo $processo['idGrpProcesso']; ?> ">
          	</div>
          	<div class="col-md-2">
          	  <label  for="first-name">Grau de Risco</label>
              <input type="text" id="grauRisco" name="grauRisco" required="required" class="form-control" value="<?php echo $processo['descricaoGrau']; ?> " readonly="readonly">
              <input type="text" id="idGrauRisco" name="idGrauRisco" required="required" class="form-control hide" value="<?php echo $processo['idGrauRisco']; ?> ">
          	</div>
          </div>
          <div class="row">
          	<div class="col-md-6">
          	  	<label  for="first-name">Descrição do Proceso</label>
             	<textarea class="form-control" id="descricaoProcesso" name="descricaoProcesso" rows="5" readonly="readonly"><?php echo $processo['descProcesso']; ?></textarea>
          	</div>
          </div>
     
        </form>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Aplicativo<small>Processos</small></h2>
        <ul class="nav navbar-right panel_toolbox">
          <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
          </li>      
        </ul>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
      	<form method="POST">
            <div class="row">
                <div class="col-md-2">
                    <label>Programa</label>
                    <input type="text" class="form-control" name="processoPrograma" id="processoPrograma" autocomplete="off">
                    <input type="text" class="form-control hide" name="idProcessoPrograma" id="idProcessoPrograma">
                    <div id="suggesstion-box">
                    <ul id="country-list"></ul>
                </div>
                </div>
                <div class="col-md-3">
                    <label>Descrição</label>
                    <input type="text" class="form-control" name="processoProgramaDesc" id="processoProgramaDesc" readonly="readonly">
                </div>
                <div class="col-md-1">
                    <label><br></label>
                    <input type="submit" class="form-control" name="processoSalvaPrograma" value="Salvar" onclick="loadingPagia()" style="background-color: #5cb85c;border-color: #5cb85c;color: #fff">
                </div>
            </div>
        </form>
      		<br>
      		<div class="x_content">
		        <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer"><div class="row"><div class="col-sm-6"></div><div class="col-sm-6"></div></div><div class="row"><div class="col-sm-12"><table  class="tabela table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info" style="width: 100%;">
		          <thead>
		            <tr role="row">
		              <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 5px;" aria-sort="ascending" aria-label="First name: activate to sort column descending">Cod.Programa</th>
		              <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 10px;" aria-label="Last name: activate to sort column ascending">Descrição</th>
		              <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 10px;" aria-label="Last name: activate to sort column ascending">Rotina</th>
                              <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 10px;" aria-label="Last name: activate to sort column ascending">Específico</th>
                              <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 10px;" aria-label="Last name: activate to sort column ascending">Ação</th>
		          </thead>
		          <tbody> 
		       
		          <?php foreach ($ProgProcessos as $value): ?>
		                <tr>
		                   <td><?php echo $value['cod_programa'] ?></td>
		                   <td><?php echo $value['descricao_programa'] ?></td>
                                   <td><?php echo $value['descricao_rotina'] ?></td>
                                   <td><?php echo (($value['especific'] == 'N') ? 'Não' : 'Sim'); ?></td>
		                   <td><button onclick="excluirProProcesso('<?php  echo $value['idAppProcesso'];  ?>','<?php  echo $value['cod_programa'];  ?>','<?php  echo $value['idProcesso'];  ?>')" class="btn btn-danger btn-xs">Excluir</button></td>

		                </tr>
		          <?php endforeach; ?>

		           </tbody>
		        </table></div></div></div>
			</div>
      </div>
    </div>
  </div>
</div>



<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Processo<small>Correlatos</small></h2>
        <ul class="nav navbar-right panel_toolbox">
          <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
          </li>      
        </ul>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
      	<form method="POST">
      		<div class="row">
      			<div class="col-md-2">
      				<label>Processo</label>
      				<input type="text" class="form-control" name="processoCorrelato" id="processoCorrelato" autocomplete="off">
      				<input type="text" class="form-control hide" name="idProcessoCorrelato" id="idProcessoCorrelato">
      				<div id="suggesstion-box2">
                    	<ul id="country-list2"></ul>
                  	</div>
      			</div>
      			<div class="col-md-2">
      				<label>Grau Risco</label>
      				<input type="text" class="form-control" name="grauCorrelato" id="grauCorrelato" autocomplete="off">
      				<input type="text" class="form-control hide" name="idGrauCorrelato" id="idGrauCorrelato">
      				<div id="suggesstion-box3">
                    	<ul id="country-list3"></ul>
                  	</div>
      			</div>
      			<div class="col-md-1">
      				<label><br></label>
      				<input type="submit" class="form-control" name="processoCorrelatoSalva" value="Salvar" onclick="loadingPagia()" style="background-color: #5cb85c;border-color: #5cb85c;color: #fff">
      			</div>
      		</div>
      		</form>
      		<br>
      		<div class="x_content">
		        <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer"><div class="row"><div class="col-sm-6"></div><div class="col-sm-6"></div></div><div class="row"><div class="col-sm-12"><table  class="tabela table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info" style="width: 100%;">
		          <thead>
		            <tr role="row">
                  <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 5px;" aria-sort="ascending" aria-label="First name: activate to sort column descending">#</th>
                  <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 5px;" aria-sort="ascending" aria-label="First name: activate to sort column descending">Risco</th>
		              <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 5px;" aria-sort="ascending" aria-label="First name: activate to sort column descending">Descrição do Processo</th>
		              <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 10px;" aria-label="Last name: activate to sort column ascending">Grupo Processo</th>
                   <!--<th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 10px;" aria-label="Last name: activate to sort column ascending">Risco</th>-->
                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 10px;" aria-label="Last name: activate to sort column ascending">Quant. Prog</th>
                    <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 10px;" aria-label="Last name: activate to sort column ascending">Grau</th>
		              <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 10px;" aria-label="Last name: activate to sort column ascending">Ação</th>
		          </thead>
		          <tbody> 
		       
		          <?php foreach ($processoCoorelato as $value): ?>
		    <tr>
                   
                      <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $value['IdProcesso'] ?></font></font></td>
                      <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $value['codRisco'] ?></font></font></td>
		                   <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $value['descProcesso'] ?></font></font></td>
		                   <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $value['GrupoProcesso'] ?></font></font></td>
                       <!--<td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $value['Risco'] ?></font></font></td>-->
                       <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $value['NroProgramas'] ?></font></font></td>
                       <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $value['GraudeRisco'] ?></font></font></td>
		                   <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><button onclick="excluirProcessoCoorelato('<?php  echo urlencode($value['idCorrelacao']);  ?>','<?php  echo trim($value['descProcesso']);  ?>','<?php  echo $idProcesso; ?>')" class="btn btn-danger btn-xs">Excluir</button></td>

		                </tr>
		          <?php endforeach; ?>

		           </tbody>
		        </table></div></div></div>        
			</div>
      </div>
    </div>
  </div>
</div>