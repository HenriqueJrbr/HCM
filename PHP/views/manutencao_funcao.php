<div class="col-md-12 col-sm-12 col-xs-12">
    <ol class="breadcrumb">
        <li><a href="<?php echo URL ?>/" onclick="loadingPagia()">Dashboard</a></li>
        <li class="active">Manutenção</li>
        <li class="active">Função</li>
    </ol>
</div>   
<br>


<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Função</h2>
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
          <div class="row">
              <div class="col-sm-12">
                  <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal">Cadastro de Função</button> 
                  <a href="<?php echo URL;?>/Carga/funcao" class="btn btn-success btn-sm" >Importar Funções</a>
              </div>
          </div>
        <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer"><div class="row"><div class="col-sm-6"></div><div class="col-sm-6"></div></div><div class="row"><div class="col-sm-12"><table  class="tabela table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info" style="width: 100%;">
          <thead>
            <tr role="row">
              <th>Função</th>
              <th>Configuração</th>
              <th>Total Usuários</th>
              <th>Ação</th>

          <tbody> 
       
            <?php foreach ($funcao as  $value):?>
          
                      <tr>
                       
                        <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $value['cod_funcao'] ?></font></font></td>
                         <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $value['descricao'] ?></font></font></td>
                          <td style="text-align:center;"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><span class="badge"><?php echo $value['total'] ?></span></font></font></td>

                          <td >
                             <button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#myModalEdifuncao" onclick="editManut('<?php echo $value['idFuncao'] ?>','<?php echo $value['descricao'] ?>','<?php echo $value['cod_funcao'] ?>')">Editar</button> 
                            <?php if($value['total'] > 0): ?>
                              <button type="button" class="btn btn-danger btn-xs" name="excluirManut" id="excluirManut" disabled>Excluir</button>
                            <?php endif; ?>
                            <?php if($value['total'] == 0): ?>
                              <a href='<?php echo URL ?>/Manutencao/excluiFuncao/<?php echo $value["idFuncao"] ?> ' class="btn btn-danger btn-xs" data-confirm='Tem certeza de que deseja excluir esta função?'>Excluir</a>
                            <?php endif; ?>

                          </td>
                           	
                          
                      </tr>
                  <?php endforeach; ?>

           </tbody>
        </table></div></div></div>
        
        
      </div>
    </div>
  </div>
</div>


<form method="POST">
<div id="myModal" class="modal fade" role="dialog">
  <form method="POST">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Cadastro de Função</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <label>Função</label>
              <input type="text" name="funcao" class="form-control" required="required">
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <label>Descrição</label>
              <input type="text" name="descricao" class="form-control">
            </div>
          </div>
          
       
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Sair</button>
            <input type="submit" name="salvar" class="btn btn-success" value="Salvar">
        </div>
      </div>

    </div>
  </form>
</div>
</form>


<div id="myModalEdifuncao" class="modal fade" role="dialog">
  <form method="POST">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Edição de Função</h4>
        </div>
        <div class="modal-body">

           <div class="row">
            <div class="col-md-6">
              <input type="hidden"  id="modalid" name="modalid" class="form-control" readonly="readonly">
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <label>Função</label>
              <input type="text" id="modalfuncao" name="modalfuncao" class="form-control" required="required" maxlength="15">
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <label>Descrição</label>
              <input type="text" id="modaldescricao" name="modaldescricao" class="form-control" >
            </div>
          </div>
          
       
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Sair</button>
            <input type="submit" name="modaleditsalvar" class="btn btn-success" value="Salvar">

        </div>
      </div>

    </div>
  </form>
</div>


