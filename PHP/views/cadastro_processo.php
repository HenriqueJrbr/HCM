
<style type="text/css">
    #country-list{float:left;list-style:none;margin-top:30px;padding:0;width:540px; position: absolute; z-index:999;}
    #country-list li{padding: 10px; background: #f0f0f0; border-bottom: #bbb9b9 1px solid; border-radius: 5px;}
    #country-list li:hover{background:#ece3d2;cursor: pointer;}
    #search-box{padding: 10px;border: #a8d4b1 1px solid;border-radius:4px ;}

    #country-list2{float:left;list-style:none;margin-top:30px;padding:0;width:540px; position: absolute; z-index:999;}
    #country-list2 li{padding: 10px; background: #f0f0f0; border-bottom: #bbb9b9 1px solid; border-radius: 5px;}
    #country-list2 li:hover{background:#ece3d2;cursor: pointer;}
    #search-box2{padding: 10px;border: #a8d4b1 1px solid;border-radius:4px ;}

    #country-list3{float:left;list-style:none;margin-top:30px;padding:0;width:540px; position: absolute; z-index:999;}
    #country-list3 li{padding: 10px; background: #f0f0f0; border-bottom: #bbb9b9 1px solid; border-radius: 5px;}
    #country-list3 li:hover{background:#ece3d2;cursor: pointer;}
    #search-box3{padding: 10px;border: #a8d4b1 1px solid;border-radius:4px ;}



 

 country-

</style>
<div class="col-md-12 col-sm-12 col-xs-12">
    <ol class="breadcrumb">
        <li><a href="<?php echo URL ?>/" onclick="loadingPagia()">Dashboard</a></li>
        <li class="active">Matriz</li>
        <li class="active">Processo</li>
    </ol>
</div> 


<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Processo<small></small></h2>
        <ul class="nav navbar-right panel_toolbox">
          <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
          </li>      
        </ul>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
          <div class="col-sm-12">
              <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target=".bs-example-modal-lg">Cadastrar Processo</button>
          </div>
          <div class="x_content">
            <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer"><div class="row"><div class="col-sm-6"></div><div class="col-sm-6"></div></div><div class="row"><div class="col-sm-12"><table  class="tabela table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info" style="width: 100%;">
              <thead>
                <tr role="row">
                  <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 5px;" aria-sort="ascending" aria-label="First name: activate to sort column descending">#</th>
                  <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 10px;" aria-label="Last name: activate to sort column ascending">Descrição do Processo</th>
                  <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 10px;" aria-label="Last name: activate to sort column ascending">Grupo de Processo</th>
                  <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 10px;" aria-label="Last name: activate to sort column ascending">Total Programas</th>
                  <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 10px;" aria-label="Last name: activate to sort column ascending">Total Processo</th>
                  <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 10px;" aria-label="Last name: activate to sort column ascending">Ação</th>
              </thead>
              <tbody> 
           
              <?php foreach ($processo as $value): ?>
                    <tr>
                       <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $value['idProcesso'] ?></font></font></td>
                       <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $value['descProcesso'] ?></font></font></td>
                       <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $value['descricao'] ?></font></font></td>
                       <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><span class="badge label-primary"><?php echo $value['totalPrograma'] ?></span></font></font></td>
                       <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><span class="badge label-primary"><?php echo $value['totalCoorelacao'] ?></span></font></font></td>
                       <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><button onclick="excluirProceso('<?php  echo $value['idProcesso'];  ?>','<?php  echo $value['descProcesso'];  ?>','<?php  echo $value['descProcesso']; ?>')" class="btn btn-danger btn-xs">Excluir</button><button onclick="location.href='<?php echo URL ?>/Matriz/dados_Processo/<?php echo $value['idProcesso'] ?>';loadingPagia()" class="btn btn-warning btn-xs">Editar</button></td>

                    </tr>
              <?php endforeach; ?>

               </tbody>
            </table></div></div></div>        
      </div>


      </div>
    </div>
  </div>
</div>


<form method="POST">
  <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">

          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel"> Processo </h4>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-4">
                <label>Cod. Risco</label>
                <input type="text" id="codRisco" name="codRisco" required="required" class="form-control  " autocomplete="off">
                <input type="text" id="codIdRisco" name="codIdRisco" required="required" class="form-control hide" autocomplete="off">
                 <div id="suggesstion-box">
                    <ul id="country-list"></ul>
                  </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <label>Descrição do Risco</label>
                <textarea name="descricaoRisco" id="descricaoRisco" required="required" class="form-control col-md-7 col-xs-12" rows="5" readonly="readonly" ></textarea>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <label>Grupo de Processos</label>
                <input type="text" id="grupoProcesso" name="grupoProcesso" required="required" class="form-control col-md-7 col-xs-12" autocomplete="off">
                <input type="text" id="codGrupoProcesso" name="codGrupoProcesso" required="required" class="form-control hide " autocomplete="off ">
                 <div id="suggesstion-box2">
                    <ul id="country-list2"></ul>
                  </div>
              </div>
              <div class="col-md-4">
                <label>Grau de Risco</label>
                <input type="text" id="grauRisco" name="grauRisco" required="required" class="form-control col-md-7 col-xs-12" autocomplete="off">
                <input type="text" id="codGrauRisco" name="codGrauRisco" required="required" class="form-control hide" autocomplete="off">
                 <div id="suggesstion-box3">
                    <ul id="country-list3"></ul>
                  </div>
              </div>
            </div>
             <div class="row">
              <div class="col-md-12">
                <label>Descrição do Processo</label>
                <textarea name="descricaoRisco" id="descricaoRisco" required="required" class="form-control col-md-7 col-xs-12" rows="5" ></textarea>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Sair</button>
            <input type="submit" name="salvarProcesso"  class="btn btn-success" value="Salvar">
           
          </div>

        </div>
      </div>
  </div>
</form>