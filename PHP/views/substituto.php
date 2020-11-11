
<?php $this->helper->scriptDataTable('tabelaSub', 'ConfiguracaoFluxo/ajaxCarregaTabSub/', 'POST', 'false'); ?>


<script type="text/javascript">
  $(document).ready(function(){
    $("#usrSerSubstituido").select2();
    $("#usrSubstituido").select2();


    $(".validaUsr").change(function(){
      var usrSerSubstituido = $("#usrSerSubstituido").val();
      var usrSubstituido = $("#usrSubstituido").val();
      if(usrSubstituido == usrSerSubstituido ){
          if(usrSerSubstituido == usrSubstituido){
            $('#usrSubstituido').val(null).trigger('change');
            $(".msg").html("O usuário substitudo não pode ser o mesmo para o usuário substituido");
          }
      }else{
        $(".msg").html('');
      }

    });


    $(".validaData").change(function(){
      var dataInicio = $("#dataInicio").val(); 
      var dataFim = $("#dataFim").val();

      if(dataInicio != ""){
         dataInicio = dataInicio.replace("-","").replace("-","");
      }else{
        dataInicio = "";
      }
      if(dataFim != ""){
        dataFim = dataFim.replace("-","").replace("-","");
      }else{
        dataFim = "";
      }

      if(dataInicio != "" && dataFim != "" && dataFim < dataInicio){
        console.log("Entrou aqui");
        $(".msg2").html("Data fim não pode ser menor que a data inicio");
        $("#dataFim").val('');
      }else{
         $(".msg2").html("");
      }
     
    });

  });
</script>
<div class="col-md-12 col-sm-12 col-xs-12">
    <ol class="breadcrumb">
        <li>
            <a href="<?php echo URL ?>">
                <font style="vertical-align: inherit;" onclick="loadingPagia()">
                    <font style="vertical-align: inherit;">Dashboard</font></font>
            </a>
        </li>
        <li class="active">Substituto</li>
    </ol>
</div>
<form method="POST">
    <div class="x_panel">
        <div class="x_title">
            <h2>Configurar Usuário Substituto</h2>
            <div class="clearfix"></div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php echo $this->helper->alertMessage(); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <button type="button" class="btn btn-success pull-right" data-toggle="modal" data-target="#myModal">Adicionar</button>
            </div>
        </div>
     
      <br>
      <?php $this->helper->alertMessage();?>
      <table class="table" id="tabelaSub">
        <thead>
          <tr>
            <th>Nome</th>
            <th>Substituto</th>
            <th>Período</th>
            <th>Cancelar</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
        <div class="row">
            <div class="col-md-12">
                <button type="submit" class="btn btn-danger pull-left" name="subCancelar" value="Cancelar">Cancelar</button>
            </div>
        </div>
  </div>

  <!-- Modal -->
  <div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Escolher substituto</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <label>Aprovador</label>
              <select class="form-control validaUsr" name="usrSerSubstituido" id="usrSerSubstituido" style="width: 100%" >
                <option value=""></option>
                <?php foreach ($getUsrSerSubst as $value):?>
                  <option value="<?php echo $value['z_sga_usuarios_id']  ?>"><?php echo $value['nome_usuario']  ?> </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <label>Aprovador Substituto</label>
              <select class="form-control validaUsr" name="usrSubstituido" id="usrSubstituido" style="width: 100%"  >
                <option value=""></option>
                <?php foreach ($getUsrSubst as $value):?>
                  <option value="<?php echo $value['z_sga_usuarios_id']  ?>"><?php echo $value['nome_usuario']  ?> </option>
                <?php endforeach; ?>
              </select>
              <p class="msg" style="color:red"></p>
            </div>
          </div>
          <br>
          <legend>Período da substituição</legend>
          <div class="row">
            <div class="col-md-4">
              <label>Data Inicial</label>
              <input type="date" name="dataInicio" id="dataInicio" class="form-control validaData" >
            </div>
            <div class="col-md-4">
              <label>Data Fim</label>
              <input type="date" name="dataFim" id="dataFim" class="form-control validaData" >
            </div>
          </div>
           <p class="msg2" style="color:red"></p>
          <br>
          <div class="row">
            <div class="col-md-12">
              <label>Observação</label>
              <textarea class="form-control" name="obs" id="obs" rows="5" ></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Sair</button>
         <input type="submit" name="salvar" class="btn btn-success" value="Salvar">
        </div>
      </div>

    </div>
  </div>
</form>