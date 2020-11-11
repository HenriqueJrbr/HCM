<div class="col-md-12 col-sm-12 col-xs-12">
    <ol class="breadcrumb">
        <li><a href="<?php echo URL ?>/" onclick="loadingPagia()">Dashboard</a></li>
        <li class="active">Matriz</li>
        <li class="active">Grupo de Processo</li>
    </ol>
</div> 

<br>
 <div class="row">
    <div class="col-md-12">
        <?php $this->helper->alertMessage(); ?>
    </div>
  </div>

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Grupo Processo</h2>
        <ul class="nav navbar-right panel_toolbox">
          <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
          </li>      
        </ul>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
          <div class="col-sm-12">
              <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target=".bs-example-modal-lg">Cadastrar Grupo de processo</button>
          </div>
          <div class="x_content">
            <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer"><div class="row"><div class="col-sm-6"></div><div class="col-sm-6"></div></div><div class="row"><div class="col-sm-12"><table  class="tabela table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info" style="width: 100%;">
              <thead>
                <tr role="row">
                  <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 5px;" aria-sort="ascending" aria-label="First name: activate to sort column descending">#</th>
                  <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 10px;" aria-label="Last name: activate to sort column ascending">Descrição</th>
                  <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 10px;" aria-label="Last name: activate to sort column ascending">Total</th>
                  <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 10px;" aria-label="Last name: activate to sort column ascending">Ação</th>
              </thead>
              <tbody> 
           
              <?php foreach ($grupoProcesso as $value): ?>
                    <tr>
                       <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $value['idGrpProcesso'] ?></font></font></td>
                       <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $value['descricao'] ?></font></font></td>
                        <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><span class="badge label-primary"><?php echo $value['total'] ?></span></font></font></td>
                     
                       <td>
                      <?php if($value['total'] > 0): ?>
                       <button onclick="excluirGrupoProceso('<?php  echo $value['idGrpProcesso'];  ?>','<?php  echo $value['descricao'];  ?>','<?php  echo $value['descricao']; ?>')" class="btn btn-danger btn-xs" disabled="disabled">Excluir</button>
                     <?php endif;?>
                     <?php if($value['total'] == 0): ?>
                       <button onclick="excluirGrupoProceso('<?php  echo $value['idGrpProcesso'];  ?>','<?php  echo $value['descricao'];  ?>','<?php  echo $value['descricao']; ?>')" class="btn btn-danger btn-xs" >Excluir</button>
                     <?php endif; ?>
                       <button class="btn btn-warning  btn-xs" data-toggle="modal" data-target=".bs-example-modal-lg2" onclick="carregaModalGrupo('<?php echo $value['idGrpProcesso'] ?>','<?php echo $value['descricao'] ?>')" >Editar</button>
                       </td>

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
  <div class="modal fade bs-example-modal-lg2" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">

          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel"> Processo </h4>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                <label>Descrição do Grupo</label>
                <input type="text" id="descricaoGrupoModal" name="descricaoGrupoModal" required="required" class="form-control  " autocomplete="off">
                <input type="text" id="idDescricaoGrupoModal" name="idDescricaoGrupoModal" required="required" class="form-control hide " autocomplete="off">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Sair</button>
            <input type="submit" name="salvarProcesso" class="btn btn-success" value="Salvar">
          </div>

        </div>
      </div>
  </div>
</form>


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
              <div class="col-md-6">
                <label>Descrição do Grupo</label>
                <input type="text" id="descricaoGrupo" name="descricaoGrupo" required="required" class="form-control  " autocomplete="off">
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