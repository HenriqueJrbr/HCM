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

<script type="text/javascript">
  $(document).ready(function(){
    var url = <?php echo "'".URL."'" ?>

    $('a[data-confirm]').click(function(ev){
      var href = $(this).attr('href');
      if(!$('#confirm-delete').length){
          $('body').append('<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header bg-danger text-white">EXCLUIR<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div><div class="modal-body">Tem certeza de que deseja excluir este plano ?</div><div class="modal-footer"><button type="button" class="btn btn-success" data-dismiss="modal">Cancelar</button><a class="btn btn-danger text-white" id="dataComfirmOK">Apagar</a></div></div></div></div>');
      }
      $('#dataComfirmOK').attr('href', href);
      $('#confirm-delete').modal({show: true});
      return false;
      
    });


  });
</script>
<div class="col-md-12 col-sm-12 col-xs-12">
    <ol class="breadcrumb">
        <li><a href="<?php echo URL ?>/" onclick="loadingPagia()">Dashboard</a></li>
        <li class="active">Matriz</li>
        <li class="active">Mitigação de Risco</li>
    </ol>
</div> 
<button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal">Cadastrar Midigação</button>


<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Plano de Mitigação</h2>
        <ul class="nav navbar-right panel_toolbox">
          <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
          </li>
        </ul>
        <div class="clearfix"></div>
      </div>
      
      <div class="x_content">
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
                      <th>Mitigação</th>
                      <th>Descrição</th>
                      <th>Anexo</th>
                      <th>Ação</th>
                  </thead>
                  <tbody> 
                    <?php foreach ($mit as  $value):?>
                        <tr>
                          <td>
                            <font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $value['mitigacao'] ?></font></font>
                          </td>
                           <td>
                            <font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo substr($value['descricao'],0,100 )?></font></font>
                          </td>
                           <td onclick="window.open('<?php echo URL ?>/arquivos/<?php echo $value['nomeArquivo'] ?>')">
                            <font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><a><span class='badge'><?php echo $value['total'] ?></span></a></font></font>

                          </td>
                           <td>
                            <font style="vertical-align: inherit;"><font style="vertical-align: inherit;">
                                <div class="btn-group">
                                    <a href='<?php echo URL ?>/matriz/excluirPlanoMitiga/<?php echo $value["idMitigacao"] ?> ' class="btn btn-danger btn-xs" data-confirm=''>Excluir</a>
                                    <button type="button" class="btn btn-xs btn-warning" onclick="location.href='<?php echo URL ?>/matriz/editar_mitigacao/<?php echo $value['idMitigacao'] ?>'" >Editar</button>
                                </div>
                              </font>
                            </font>
                          </td> 
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

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <form method="POST" enctype="multipart/form-data">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Cadastro de Mitigação</h4>
        </div>
        <div class="modal-body">
              <div class="row">
                <div class="col-md-6 col-sm-6">
                  <label>Mitigação</label>
                  <input type="text" name="mitigacao" class="form-control" maxlength="250">
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 col-sm-6" >
                  <label>Descrição</label>
                  <textarea class="form-control" name="descMedigacao" rows="5"></textarea>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 col-sm-6" >
                  <label>Mitigação</label>
                  <input type="file" name="documentoMedigacao" class="form-control">
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
  <form method="POST" enctype="multipart/form-data">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Editar Mitigação</h4>
        </div>
        <div class="modal-body">

            <div class="row">
              <div class="col-md-10">
                <label>Mitigação</label>
                <input type="text" name="mitigacaoEdit" id="mitigacaoEdit"   class="form-control" maxlength="250">
                <input type="text" name="idMitigacao" id="idMitigacao"   class="form-control hide" maxlength="250">

              </div>
            </div>
            <div class="row">
              <div class="col-md-10">
                <label>Descrição</label>
                <textarea class="form-control" name="descMedigacaoEdit" id="descMedigacaoEdit" rows="5"></textarea>
              </div>
            </div>
             <div class="row">
              <div class="col-md-10">
                <label>Mitigação</label>
                <input type="file" name="documentoMedigacaoEdit" name="documentoMedigacaoEdit" class="form-control">
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
                  <tbody id="bodyDocMedigacao"> 
                  
                   </tbody>
                </table>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-4">
            <label>Cod.Ricos</label>
            <input type="text" name="codRiscoMitiga" id="codRiscoMitiga" class="form-control">
            <input type="text" name="idCodRiscoMitiga" id="idCodRiscoMitiga" class="form-control hide">
             <div id="suggesstion-box3">
                <ul id="country-list3"></ul>
              </div>
          </div>
          <div class="col-md-4">
             <label><br></label>
              <button type="button" name="addMitigaRisco" id="addMitigaRisco" class="btn btn-success btn-xs form-control">Adicionar Risco</button>
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
                <tbody id="bodyRiscoMitiga"> 
                
                 </tbody>
              </table>
          </div>
        </div>
     
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Sair</button>
            <input type="submit" name="salvarEdit" class="btn btn-success" value="Salvar">
        </div>
      </div>
    </div>
  </form>
</div>