<script type="text/javascript">
  
  function editarRisco(id){
     $.ajax({
        type: "POST",
        url: url+"Matriz/ajaxCarregaRiscoDesc",
        data:'idMtzRisco='+id,
        beforeSend: function(){           
        },
        success: function(data){
         var dados = JSON.parse(data);
         console.log(dados);
         $("#riscoEditar").val(dados.codRisco);
         $("#areaEditar").val(dados.idArea);
         $("#descricaoEditar").val(dados.descricao);
         $("#idRiscoEditar").val(dados.idMtzRisco);
   
        }
    });
  }
</script>


<style type="text/css">
   #country-list{float:left;list-style:none;margin-top:5px;padding:0;width:540px; position: absolute; z-index:999;}
    #country-list li{padding: 10px; background: #f0f0f0; border-bottom: #bbb9b9 1px solid; border-radius: 5px;}
    #country-list li:hover{background:#ece3d2;cursor: pointer;}
    #search-box{padding: 10px;border: #a8d4b1 1px solid;border-radius:4px ;}

    #country-list2{float:left;list-style:none;margin-top:5px;padding:0;width:540px; position: absolute; z-index:999;}
    #country-list2 li{padding: 10px; background: #f0f0f0; border-bottom: #bbb9b9 1px solid; border-radius: 5px;}
    #country-list2 li:hover{background:#ece3d2;cursor: pointer;}
    #search-box2{padding: 10px;border: #a8d4b1 1px solid;border-radius:4px ;}

    #country-list3{float:left;list-style:none;margin-top:5px;padding:0;width:540px; position: absolute; z-index:999;}
    #country-list3 li{padding: 10px; background: #f0f0f0; border-bottom: #bbb9b9 1px solid; border-radius: 5px;}
    #country-list3 li:hover{background:#ece3d2;cursor: pointer;}
    #search-box3{padding: 10px;border: #a8d4b1 1px solid;border-radius:4px ;}
</style>
<div class="col-md-12 col-sm-12 col-xs-12">
    <ol class="breadcrumb">
        <li><a href="<?php echo URL ?>/" onclick="loadingPagia()">Dashboard</a></li>
        <li class="active">Matriz</li>
        <li class="active">Risco</li>
    </ol>
</div> 

<br>
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Cadastro Risco</h2>
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
              <div class="col-md-12">
                  <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal">Cadastrar Risco</button>
              </div>
          </div>
        <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer"><div class="row"><div class="col-sm-6"></div><div class="col-sm-6"></div></div><div class="row"><div class="col-sm-12"><table  class="tabela table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info" style="width: 100%;">
          <thead>
            <tr role="row">
              <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 1%;" aria-label="Last name: activate to sort column ascending">#</th>
              <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 5%;" aria-label="Last name: activate to sort column ascending">Risco</th>
              <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 50%;" aria-label="Last name: activate to sort column ascending">Descrição</th>
              <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 1%;" aria-label="Last name: activate to sort column ascending">Situação</th>
              <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 1%;" aria-label="Last name: activate to sort column ascending">Ação</th>
          </thead>
          <tbody>       
            <?php foreach ($matriz as  $value):?>
              <tr> 
                <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $value['idMtzRisco'] ?></font></font></td>
                <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $value['codRisco'] ?></font></font></td>
                <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $value['descricao'] ?></font></font></td>
                <td><center><span class="badge" style="background-color:<?php echo (($value['mitigacoes'] > 0) ? '#26B99A' : '#d9534f' ); ?>"><?php echo ($value['mitigacoes'] > 0) ? 'Mitigado' : 'Não mitigado'; ?> </span></center></td>
                <td>
                    <?php if($value['processos'] == 0 && $value['mitigacoes'] == 0): ?>
                    <a class="btn btn-danger btn-xs" href="<?php echo URL; ?>/Matriz/excluiRisco/<?php echo $value['idMtzRisco']; ?>">Excluir</a>
                    <?php endif; ?>
                    <button class="btn btn-warning btn-xs" data-toggle="modal" data-target="#myModal2" onclick="editarRisco('<?php echo $value['idMtzRisco'] ?>')">Editar</button>                    
                </td>
              </tr>
            <?php endforeach; ?>
           </tbody>
        </table></div></div></div>
        
        
      </div>
    </div>
  </div>
</div>


<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <form method="POST">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Cadastro de Risco</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <label>Risco</label>
              <input type="text" name="risco" class="form-control" required="required" maxlength="16">
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <label>Area</label>
              <select class="form-control " name="area" id="area" required="required">
                <option></option>
                <?php foreach($area as $value):?>
                  <option value="<?php echo $value['idArea']?>"><?php echo $value['descricao'] ?></option>
                <?php endforeach;?>
              </select>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-12">
              <label>Descricao</label>
              <textarea name="descricao" rows="5" class="form-control" required="required"></textarea>
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


<!-- Modal -->
<div id="myModal2" class="modal fade" role="dialog">
  <form method="POST">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Editar  Risco</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <label>Risco</label>
              <input type="text" name="riscoEditar" id="riscoEditar" class="form-control" maxlength="16">
              <input type="text" name="idRiscoEditar" id="idRiscoEditar" class="form-control hide">
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <label>Area</label>
              <select class="form-control " name="areaEditar" id="areaEditar">
                <option></option>
                <?php foreach($area as $value):?>
                  <option value="<?php echo $value['idArea']?>"><?php echo $value['descricao'] ?></option>
                <?php endforeach;?>
              </select>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-12">
              <label>Descricao</label>
              <textarea name="descricaoEditar" id="descricaoEditar" rows="5" class="form-control"></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Sair</button>
            <input type="submit" name="salvarEditar" class="btn btn-success" value="Salvar">
        </div>
      </div>

    </div>
  </form>
</div>