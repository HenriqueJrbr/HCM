  <style type="text/css">
    #country-list3 {float:left;list-style:none;margin-top:5px;padding:0;width:540px; position: absolute; z-index:999;}
    #country-list3 li{padding: 10px; background: #f0f0f0; border-bottom: #bbb9b9 1px solid; border-radius: 5px;}
    #country-list3 li:hover{background:#ece3d2;cursor: pointer;}
    #search-box3 {padding: 10px;border: #a8d4b1 1px solid;border-radius:4px;}
  
  .dadosModal{
    width: 50%;
    background: red;
  };

</style>
<div class="col-md-12 col-sm-12 col-xs-12">
    <ol class="breadcrumb">
        <li><a href="<?php echo URL ?>/" onclick="loadingPagia()">Dashboard</a></li>
        <li><a href="<?php echo URL ?>/Matriz/cadastroDeRisco" onclick="loadingPagia()">Matriz</a></li>
        <li><a href="<?php echo URL ?>/Matriz/cadastroMitigacao" onclick="loadingPagia()">Mitigação</a></li>
        <li class="active"><?php echo $dadosMitiga['mitigacao'] ?></li>
    </ol>
</div> 
<div class="x_panel">
    <div class="x_title">
        <h2>Editar mitigação de risco - <?php echo $dadosMitiga['mitigacao'] ?></h2>
        <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </li>
        </ul>
        <div class="clearfix"></div>
    </div>
  <form method="POST" enctype="multipart/form-data">

  	<div class="row">
	  <div class="col-md-4">
	    <label>Mitigação</label>
	    <input type="text" name="mitigacaoEdit" id="mitigacaoEdit"   class="form-control" maxlength="250" value="<?php echo $dadosMitiga['mitigacao'] ?>">
	    <input type="text" name="idMitigacao" id="idMitigacao" value="<?php echo $idMitigacao ?>"   class="form-control hide" maxlength="250">

	  </div>
	</div>
    <div class="row">
      <div class="col-md-4">
        <label>Descrição</label>
        <textarea class="form-control" name="descMedigacaoEdit" id="descMedigacaoEdit" rows="5"><?php echo $dadosMitiga['descricao'] ?></textarea>
      </div>
    </div>
	<div class="row">
	  <div class="col-md-2">
	    <label>Mitigação</label>
	    <input type="file" name="documentoMedigacaoEdit" name="documentoMedigacaoEdit" class="form-control">
	  </div>
	</div>
	<br>
	<div class="row">
		<div class="col-md-4">
			<input type="submit" name="salvarEdit" class="btn btn-info " value="Salvar">
		</div>
	</div>

    <br><br>

    <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
      <div class="row">
          <div class="col-sm-6"></div>
          <div class="col-sm-6"></div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <table  class="tabela table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info" style="width: 100%;">
              <thead>
                <tr role="row">
                  <th>Doumento</th>
                  <th>Ação</th>
              </thead>
              <tbody> 
              	<?php foreach ($mitiga2 as $value):?>
              		<tr>
                    <td><a href="<?php echo URL ?>/arquivos/<?php echo $value['codArquivo'] ?>" download><?php echo $value['nomeArquivo'] ?></a></td>
              			<td><a href="<?php echo URL ?>/Matriz/ajaxExluiArquivcoMidigacao/<?php echo $value['idArquivo'] ?>/<?php echo $value['codArquivo'] ?>/<?php echo $idMitigacao ?>" class="btn btn-danger btn-xs">Excluir</a></td>

              		</tr>
              	<?php endforeach;?>
               </tbody>
            </table>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-2">
        <label>Cod.Ricos</label>
        <input type="text" name="codRiscoMitiga" id="codRiscoMitiga" class="form-control" autocomplete="off">
        <input type="text" name="idCodRiscoMitiga" id="idCodRiscoMitiga" class="form-control hide">
         <div id="suggesstion-box3">
            <ul id="country-list3"></ul>
          </div>
      </div>
      <div class="col-md-1">
         <label><br></label>
         <input type="submit" name="addMitigaRisco" class="btn btn-info btn-xs form-control" value="Adicionar Risco">
      </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
          <table  class="tabela table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info" style="width: 100%;">
            <thead>
              <tr role="row">
                <th>Codigo Risco</th>
                <th>Descrição</th>
                <th>Ação</th>
            </thead>
            <tbody> 
            	<?php foreach ($dadosRisco as $value):?>
            		<tr>
            			<td><?php echo $value['codRisco'] ?></td>
            			<td><?php echo $value['descricao'] ?></td>
            			<td><a href="<?php echo URL ?>/Matriz/excluirRiscoMitigacao/<?php echo $value['idMitigacaoRisco'] ?>/<?php echo $idMitigacao ?>" class="btn btn-danger btn-xs">Excluir</a></td>
            		</tr>
            	<?php endforeach; ?>
             </tbody>
          </table>
      </div>
    </div>
  </form>
</div>
