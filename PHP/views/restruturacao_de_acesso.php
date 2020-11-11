<script type="text/javascript">
$(document).ready(function(){


    if($("#atividade").val() == "2"){
        $("#aprovacao").attr("readonly","readonly");
        $(".gestor").hide();
        $("#enviaSi").attr("required","required");
    }
    if($("#atividade").val() == "1"){
        $(".si").hide();
    }
    if($("#atividade").val() != "1" && $("#atividade").val() != "2"){
        $(".gestor").hide();
        $(".si").hide();
        $(".obs").hide();
    }

   
    //Ajax que carrega os progrmas da tela de usuario http://162.144.118.90/sga/usuario/dados_usuario /
    $("#profile-tab51").click(function(){

        var prog = $("#tableProgUsuario2 tbody tr").length;

        var id = $("#idUsuario").val();
        if(prog == 0 ){
            
            table = $('.tabelaProg2').DataTable();
            table.destroy();

            $.ajax({
                type: "POST",
                url: url+"Usuario/ajaxCarregaProgUrsFoto",
                data:'id='+id,
                beforeSend: function(){
                    $("#tableProgUsuario2").hide(); 
                      
                },
                success: function(data){
                    $("#tableProgUsuario2").show(); 
                    $(".loadProgUsr2").hide();
                    $("#carregaProgUsr2").html(data);
                    console.log("Retorno "+data);
                    
                    $('.tabelaProg2').DataTable({
                        "language": {
                        "lengthMenu": "Exibição _MENU_ Registros por página",
                        "zeroRecords": "Registro nao encontrado",
                        "info": "Pagina _PAGE_ de _PAGES_",
                        "infoEmpty": "No records available",
                        "search":"Pesquisar:",
                        "paginate": {
                            "first":      "Primeiro",
                            "last":       "Último",
                             "next":       "Próximo",
                            "previous":   "Anterior"
                            },
                            "infoFiltered": "(Filtro de _MAX_ registro total)"
                        }
                    });    
                }
            });

        }
    });

         //Ajax que carrega os programas Duplicados da tela de usuario http://162.144.118.90/sga/usuario/dados_usuario /
    $("#profile-tab61").click(function(){
        
        var progDuplicado = $("#taleProgDuplicado2 tbody tr").length;
     

        var id = $("#idUsuario").val();

        if(progDuplicado == 0 ){
            table = $('.tabelaProgDuplicado2').DataTable();
            table.destroy();

            $.ajax({
                type: "POST",
                url: url+"Usuario/ajaxCarregaProgDuplicadoUrsFoto",
                data:'id='+id,
                beforeSend: function(){
                    $("#taleProgDuplicado2").hide(); 
                      
                },
                success: function(data){
                    
                    $("#taleProgDuplicado2").show(); 
                    $(".loadProgDuplicadoUsr2").hide();
                    
                    $("#carregaProDuplicadogUsr2").html(data);
                    
                    $('.tabelaProgDuplicado2').DataTable({
                        "language": {
                        "lengthMenu": "Exibição _MENU_ Registros por página",
                        "zeroRecords": "Registro nao encontrado",
                        "info": "Pagina _PAGE_ de _PAGES_",
                        "infoEmpty": "No records available",
                        "search":"Pesquisar:",
                        "paginate": {
                            "first":      "Primeiro",
                            "last":       "Último",
                             "next":       "Próximo",
                            "previous":   "Anterior"
                            },
                            "infoFiltered": "(Filtro de _MAX_ registro total)"
                        }
                    });    
                }
            });

        }
    });

       //Ajax que carrega Matriz de Risco da tela de usuario http://162.144.118.90/sga/usuario/dados_usuario /
    $("#profile-tab71").click(function(){
        
        var url_atual = window.location.href;
        
       var id = $("#idUsuario").val();
        
        if($("#controlaMatriz2").val() == ""){
            $.ajax({
                type: "POST",
                url: url+"Usuario/ajaxMatrizDeRiscoFoto",
                data:'id='+id,
                beforeSend: function(){
                   
                      
                },
                success: function(data){
                    $(".loadMatriz2").hide();
                    $("#carregaMatiz2").html(data);
                    $("#controlaMatriz2").val("OK");
                    
                }
             });

        }
        
    });
    //Ajax que carrega Processo Matriz de Risco da tela de usuario http://162.144.118.90/sga/usuario/dados_usuario /

        //Ajax que carrega Processo Matriz de Risco da tela de usuario http://162.144.118.90/sga/usuario/dados_usuario /
    $("#profile-tab81").click(function(){
        
        var url_atual = window.location.href;
        
       var id = $("#idUsuario").val();


        
        if($("#controlaProcessoMatriz2").val() == ""){
            $.ajax({
                type: "POST",
                url: url+"Usuario/ajaxMatrizProcesDeRiscoFoto",
                data:'id='+id,
                beforeSend: function(){
                   
                      
                },
                success: function(data){
                    $(".loadMatrizProcesso2").hide();
                    $("#carregaProcessoMatiz2").html(data);
                    $("#controlaProcessoMatriz2").val("OK");

                    $('.tabelaMatrizProcesso').DataTable({
                        "language": {
                        "lengthMenu": "Exibição _MENU_ Registros por página",
                        "zeroRecords": "Registro nao encontrado",
                        "info": "Pagina _PAGE_ de _PAGES_",
                        "infoEmpty": "No records available",
                        "search":"Pesquisar:",
                        "paginate": {
                            "first":      "Primeiro",
                            "last":       "Último",
                             "next":       "Próximo",
                            "previous":   "Anterior"
                            },
                            "infoFiltered": "(Filtro de _MAX_ registro total)"
                        }
                    });
                    
                }
             });

        }
        
    });

    //Ajax que carrega Processo Matriz de Risco da tela de usuario http://162.144.118.90/sga/usuario/dados_usuario /
    $("#profile-ta91").click(function(){
        
        var url_atual = window.location.href;
        
        var id = $("#idUsuario").val();
        
        if($("#controlaModulo2").val() == ""){
            $.ajax({
                type: "POST",
                url: url+"Usuario/ajaxAcessoModulosFoto",
                data:'id='+id,
                beforeSend: function(){
                   
                      
                },
                success: function(data){
                    $(".loadModulo2").hide();
                    $("#carregaModulo2").html(data);
                    $("#controlaModulo2").val("OK");

                   
                    
                }
             });

        }
        
    });


        //Ajax que carrega os progrmas da tela de usuario http://162.144.118.90/sga/usuario/dados_usuario /
    $("#profile-tab23").click(function(){
        
        var prog = $("#tableProgUsuario tbody tr").length;
         var id = $("#idUsuario").val();
        if(prog == 0 ){
            
            table = $('.tabelaProg').DataTable();
            table.destroy();

            $.ajax({
                type: "POST",
                url: url+"Usuario/ajaxCarregaProgUrs",
                data:'id='+id,
                beforeSend: function(){
                    $("#tableProgUsuario").hide(); 
                      
                },
                success: function(data){
                    $("#tableProgUsuario").show(); 
                    $(".loadProgUsr").hide();
                    $("#carregaProgUsr").html(data);
                    $('.tabelaProg').DataTable({
                        "language": {
                        "lengthMenu": "Exibição _MENU_ Registros por página",
                        "zeroRecords": "Registro nao encontrado",
                        "info": "Pagina _PAGE_ de _PAGES_",
                        "infoEmpty": "No records available",
                        "search":"Pesquisar:",
                        "paginate": {
                            "first":      "Primeiro",
                            "last":       "Último",
                             "next":       "Próximo",
                            "previous":   "Anterior"
                            },
                            "infoFiltered": "(Filtro de _MAX_ registro total)"
                        }
                    });    
                }
            });

        }
    });
     //Ajax que carrega os programas Duplicados da tela de usuario http://162.144.118.90/sga/usuario/dados_usuario /
    $("#profile-tab33").click(function(){
        
        var progDuplicado = $("#taleProgDuplicado tbody tr").length;
        var id = $("#idUsuario").val();

        if(progDuplicado == 0 ){
            table = $('.tabelaProgDuplicado').DataTable();
            table.destroy();

            $.ajax({
                type: "POST",
                url: url+"Usuario/ajaxCarregaProgDuplicadoUrs",
                data:'id='+id,
                beforeSend: function(){
                    $("#taleProgDuplicado").hide(); 
                      
                },
                success: function(data){
                    
                    $("#taleProgDuplicado").show(); 
                    $(".loadProgDuplicadoUsr").hide();
                    
                    $("#carregaProDuplicadogUsr").html(data);
                    
                    $('.tabelaProgDuplicado').DataTable({
                        "language": {
                        "lengthMenu": "Exibição _MENU_ Registros por página",
                        "zeroRecords": "Registro nao encontrado",
                        "info": "Pagina _PAGE_ de _PAGES_",
                        "infoEmpty": "No records available",
                        "search":"Pesquisar:",
                        "paginate": {
                            "first":      "Primeiro",
                            "last":       "Último",
                             "next":       "Próximo",
                            "previous":   "Anterior"
                            },
                            "infoFiltered": "(Filtro de _MAX_ registro total)"
                        }
                    });    
                }
            });

        }
    });

    //Ajax que carrega Matriz de Risco da tela de usuario http://162.144.118.90/sga/usuario/dados_usuario /
    $("#profile-tab43").click(function(){
        
        var url_atual = window.location.href;
        
         var id = $("#idUsuario").val();
        
        if($("#controlaMatriz").val() == ""){
            $.ajax({
                type: "POST",
                url: url+"Usuario/ajaxMatrizDeRisco",
                data:'id='+id,
                beforeSend: function(){
                   
                      
                },
                success: function(data){
                    $(".loadMatriz").hide();
                    $("#carregaMatiz").html(data);
                    $("#controlaMatriz").val("OK");
                    
                }
             });

        }
        
    });
    //Ajax que carrega Processo Matriz de Risco da tela de usuario http://162.144.118.90/sga/usuario/dados_usuario /
    $("#profile-tab53").click(function(){
        
        var url_atual = window.location.href;
        
         var id = $("#idUsuario").val();
        
        if($("#controlaProcessoMatriz").val() == ""){
            $.ajax({
                type: "POST",
                url: url+"Usuario/ajaxMatrizProcesDeRisco",
                data:'id='+id,
                beforeSend: function(){
                   
                      
                },
                success: function(data){
                    $(".loadMatrizProcesso").hide();
                    $("#carregaProcessoMatiz").html(data);
                    $("#controlaProcessoMatriz").val("OK");

                    $('.tabelaMatrizProcesso').DataTable({
                        "language": {
                        "lengthMenu": "Exibição _MENU_ Registros por página",
                        "zeroRecords": "Registro nao encontrado",
                        "info": "Pagina _PAGE_ de _PAGES_",
                        "infoEmpty": "No records available",
                        "search":"Pesquisar:",
                        "paginate": {
                            "first":      "Primeiro",
                            "last":       "Último",
                             "next":       "Próximo",
                            "previous":   "Anterior"
                            },
                            "infoFiltered": "(Filtro de _MAX_ registro total)"
                        }
                    });
                    
                }
             });

        }
        
    });
       //Ajax que carrega Processo Matriz de Risco da tela de usuario http://162.144.118.90/sga/usuario/dados_usuario /
    $("#profile-ta63").click(function(){
        
        var url_atual = window.location.href;
       var id = $("#idUsuario").val();
        
        if($("#controlaModulo").val() == ""){
            $.ajax({
                type: "POST",
                url: url+"Usuario/ajaxAcessoModulos",
                data:'id='+id,
                beforeSend: function(){
                   
                      
                },
                success: function(data){
                    $(".loadModulo").hide();
                    $("#carregaModulo").html(data);
                    $("#controlaModulo").val("OK");

                   
                    
                }
             });

        }
        
    });
     
});
</script> 

 <?php 

 if(!empty($dadosForm)){ 
        $aprovacao = $dadosForm['aprovacao'];
        $idGestor = $dadosForm['idGestor'];
    }else{
        $aprovacao = "";
        $idGestor = "";
    }
?>
<form method="POST">
<input type="text" name="atividade" id="atividade" class="hide" value="<?php echo $atividade ?>">
<input type="text" name="idUsuario" id="idUsuario" class="hide" value="<?php echo $usuario['z_sga_usuarios_id'] ?>">
  <div class="x_panel"><!--Inicia x_panel-->

  	<div class="x_title"><!--Inicia x_title-->
      <h2>Informações do Usuário</h2>
      <div class="clearfix"></div>
    </div><!--Fim x_title-->
    <div class="row">
      <div class="col-md-6 col-xs-12 col-sm-12">
        <label style="font-size: 25px;">Nome: </label>
        <span style="color: #FF8000; font-size: 25px"><?php echo $usuario['nome_usuario']?> </span>
      </div>
       <div class="col-md-6 col-xs-12 col-sm-4">
        <label style="font-size: 25px;" >Função: </label>
        <span style="color: #FF8000; font-size: 25px"><?php echo $usuario['cod_funcao']; ?></span>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-md-2 col-xs-12 col-sm-4">
         <label>ID DataSul: </label> 
        <span><?php echo $usuario['cod_usuario']?></span>
      </div>
      <div class="col-md-2 col-xs-12 col-sm-4">
         <label>ID Fluig:  </label>
        <span> <?php echo $usuario['idUsrFluig']?></span>
      </div>
      <div class="col-md-4">
         <label>Gestor do usuário:  </label>
        <span> <?php if($dadosGestor == '1'){echo "Não Cadastrado";}else{echo $dadosGestor['nome_usuario'];} ?></span>
      </div>
      <div class="col-md-4 col-xs-12 col-sm-4">
        <label>E-mail: </label>
        <span><?php if($usuario['email'] == ''){echo 'Não Cadastrado';}else{echo $usuario['email'];}?></span>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-md-3">
         <label>É Gestor de Grupo?  </label>
        <span> <?php if($usuario['gestor_grupo'] == "S"){ echo "Sim";}else{echo 'Não';} ?></span>
      </div>
       <div class="col-md-3">
         <label>É Gestor de Usuário?  </label>
        <span> <?php if($usuario['gestor_usuario'] == "S"){ echo "Sim";}else{echo 'Não';} ?></span>
      </div>
      <div class="col-md-3">
         <label>É Gestor de Programa?  </label>
        <span> <?php if($usuario['gestor_programa'] == "S"){ echo "Sim";}else{echo 'Não';} ?></span>
      </div>
      <div class="col-md-3">
         <label>Segurança de Informação ?  </label>
        <span> <?php if($usuario['si'] == "S"){ echo "Sim";}else{echo 'Não';} ?></span>
      </div>
    </div>
    <br>
    <div class="x_title">
      <h2><i class="fa fa-user"></i> <small> Aprovação</small></h2>     
      <div class="clearfix"></div>
    </div>
    <div class="row">
    	<div class="col-md-4"></div>
	    <div class="col-md-2">
	    	<label>Aprovado</label>
	    	<select class="form-control" name="aprovacao" id="aprovacao" required="required" >
                <?php if(!empty($aprovacao) && $atividade == 2 || !empty($aprovacao) && $atividade == 0): ?>
                    <option value="<?php echo $aprovacao ?>"><?php echo $aprovacao ?></option>
                 <?php endif; ?>
                 <?php if(empty($aprovacao) || $atividade == 1 ): ?>
                    <option value=""></option>
                    <option value="sim">Sim</option>
                    <option value="nao">Não</option>
                 <?php endif; ?>    
	    		
	    	</select>
	    </div>
	</div>
    <br>
    <legend>Histórico de Mensagens</legend>
    <div class="row">
        <div class="col-md-12">
            <table class="table">
                <thead>
                    <tr>
                        <th>Autor</th>
                        <th>Mensagem</th>
                        <th>Data</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($msg as  $value): ?>
                        <tr>
                            <td><?php echo $value['autor'] ?></td>
                            <td><p><?php echo $value['msg'] ?></p></td>
                            <td><?php echo $value['dataCriacao'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <br>
	<div class="row obs">
    	<div class="col-md-4"></div>
	    <div class="col-md-4">
	    	<label>Observação</label>
       
	    	<textarea class="form-control" name="observacao" id="observacao" rows="5"></textarea>
	    </div>
	</div>
	<br>
	<div class="row">
		<div class="col-md-4"></div>
	  	<div class="col-md-2">
	  		<input type="submit" name="enviar" id="enviar" class="btn btn-info form-control gestor" value="Enviar">
	  	</div>
	 </div>
     <div class="si">
         <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-2">
                <label>Enviar para</label>
                <select class="form-control" name="enviaSi" id="enviaSi">
                    <option value=""></option>
                    <option value="<?php echo $idGestor ?>">Gestor</option>
                    <option value="fim">Fim</option>
                </select>
            </div>
         </div>
         <br>
         <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-2">
                <input type="submit" name="enviarSi" id="enviarSi" class="btn btn-info form-control" value="Enviar">
            </div>
         </div>
    </div>
    <br>
    <div class="x_title">
      <h2><i class="fa fa-user"></i> <small> Acessos Usuário</small></h2>     
      <div class="clearfix"></div>
    </div>
    <br>
    <div class="x_content">
  <div class="" role="tabpanel" data-example-id="togglable-tabs">
      <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
        <li role="presentation" class="active"><a href="#tab_content20" id="profile-tab20" role="tab" data-toggle="tab" aria-expanded="true">Estrutura Proposta</a>
        </li>
        <li role="presentation" class=""><a href="#tab_content30" role="tab" id="profile-tab30" data-toggle="tab" aria-expanded="false">Estrutura Atual</span></a>
        </li>
      </ul>
      <div id="myTabContent" class="tab-content">
        <div role="tabpanel" class="tab-pane fade active in" id="tab_content20" aria-labelledby="profile-tab20"><!--Inicio Tabela 1 -->
          <div class="" role="tabpanel" data-example-id="togglable-tabs">
            <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
              <li role="presentation" class="active"><a href="#tab_content13" id="profile-tab13" role="tab" data-toggle="tab" aria-expanded="true">Grupos <span class="badge"><?php echo $totalAcesso['numGrupos'] ?></a>
              </li>
              <li role="presentation" class=""><a href="#tab_content23" role="tab" id="profile-tab23" data-toggle="tab" aria-expanded="false">Programas <span class="badge"><?php echo $totalAcesso['numProgs'] ?></span></a>
              </li>
              <li role="presentation" class=""><a href="#tab_content33" role="tab" id="profile-tab33" data-toggle="tab" aria-expanded="false">Prog. Duplicados <span class="badge"><?php echo $totalAcesso['nroProgDup'] ?></span></a>
              </li>
              <li role="presentation" class=""><a href="#tab_content43" role="tab" id="profile-tab43" data-toggle="tab" aria-expanded="false">Matriz de Risco <span class="badge"><?php echo $totalAcesso['nroRiscos'] ?></span></a>
              </li>
              <li role="presentation" class=""><a href="#tab_content53" role="tab" id="profile-tab53" data-toggle="tab" aria-expanded="false">Processos <span class="badge"><?php echo $totalAcesso['numProcess'] ?></span></a>
              </li>
              <li role="presentation" class=""><a href="#tab_content63" role="tab" id="profile-ta63" data-toggle="tab" aria-expanded="false">Acesso a Módulos <span class="badge"><?php echo $totalAcesso['numModulos'] ?></span></a>
              </li>   
            </ul>
            <div id="myTabContent" class="tab-content">
              <div role="tabpanel" class="tab-pane fade active in" id="tab_content13" aria-labelledby="profile-tab13"><!--Inicio Tabela 1 -->
                <br>
                  <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer"><div class="row"><div class="col-sm-6"></div><div class="col-sm-6"></div></div><div class="row"><div class="col-sm-12">
                  <table  class="tabela table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info" style="width: 100%;" id="tableGrupoUsuario">
                  <thead>
                    <tr>
                    <th>Id Grupo</th>
                    <th>Descrição</th>
                    <th>Gestor</th>
                    <th>Quant. Programa</th>
                    <th>Quant. Usuários</th>
                    </tr>
                  </thead>
                  <tbody> 
               
                   <?php foreach($grupo as $valor):?>
                      <tr>
                        <td><?php echo utf8_decode($valor['idLegGrupo'])?></td>
                        <td><?php echo $valor['descAbrev']?></td>
                        <td><?php echo $valor['nomeGestor']?></td>
                        <td><span class="badge label-primary"><?php echo $valor['totalPro']?></span></td>
                        <td><span class="badge label-primary"><?php echo $valor['totalUsuario']?></span></td>
                      </tr>
                    <?php endforeach;?>  

                   </tbody>
                </table></div></div></div>
              </div><!--Fim Tabela 1 -->
              <div role="tabpanel" class="tab-pane fade " id="tab_content23" aria-labelledby="profile-tab23"><!--Inicio Tabela 2 -->
                  <div class="loadProgUsr">
                   <center><img src="<?php echo URL ?>/assets/images/loader.gif"></center>
                  </div>
                  <br>
                  <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer"><div class="row"><div class="col-sm-6"></div><div class="col-sm-6"></div></div><div class="row"><div class="col-sm-12"><table  class="tabelaProg table table-striped hover table-striped dt-responsive nowrap  no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info" style="width: 100%;" id="tableProgUsuario">
                  <thead>
                    <tr role="row">
                      <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 71px;" aria-sort="ascending" aria-label="First name: activate to sort column descending">Id Grupo</th>
                      <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 70px;" aria-label="Last name: activate to sort column ascending">Descrição</th>
                      <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 70px;" aria-label="Last name: activate to sort column ascending">Programa</th>
                      <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 70px;" aria-label="Last name: activate to sort column ascending">Descrição</th>
                       <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 70px;" aria-label="Last name: activate to sort column ascending">Observacao</th>
                      <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 70px;" aria-label="Last name: activate to sort column ascending">Rotina</th>
                    </tr>
                  </thead>
                  <tbody id="carregaProgUsr"> 
               
               

                   </tbody>
                </table></div></div></div>
              </div><!--FIM Tabela 2 -->
              <div role="tabpanel" class="tab-pane fade " id="tab_content33" aria-labelledby="profile-tab33"><!--Inicio Tabela 3 -->
                 <div class="loadProgDuplicadoUsr">
                   <center><img src="<?php echo URL ?>/assets/images/loader.gif"></center>
                  </div>
                    <br>
                  <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer"><div class="row"><div class="col-sm-6"></div><div class="col-sm-6"></div></div><div class="row"><div class="col-sm-12">
                    <table  class="tabelaProgDuplicado table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info" style="width: 100%;" id="taleProgDuplicado" >
                    <thead>
                      <tr role="row">
                        <th>Programa</th>
                        <th>Descrição</th>
                        <th>Grupos</th>
                      </tr>
                    </thead>
                    <tbody id="carregaProDuplicadogUsr"> 
                 
                    

                     </tbody>
                  </table></div></div></div>
              </div><!--Inicio Tabela 3-->
              <div role="tabpanel" class="tab-pane fade " id="tab_content43" aria-labelledby="profile-tab43"><!--Inicio Tabela 4 -->
                 <div class="loadMatriz">
                   <center><img src="<?php echo URL ?>/assets/images/loader.gif"></center>
                  </div>
                  <input type="text" name="controlaMatriz" id="controlaMatriz" class="hide">
                  <br>
                  <div id="carregaMatiz"></div>
                  
                  
              </div><!--Fim Tabela 1 -->

              <div role="tabpanel" class="tab-pane fade " id="tab_content53" aria-labelledby="profile-tab53"><!--Inicio Tabela 5 -->
                 <div class="loadMatrizProcesso">
                   <center><img src="<?php echo URL ?>/assets/images/loader.gif"></center>
                  </div>
                  <input type="text" name="controlaProcessoMatriz" id="controlaProcessoMatriz" class="hide">
                   <br>
                   <div id="carregaProcessoMatiz"></div>

                
              </div><!--Fim Tabela 5 -->

              <div role="tabpanel" class="tab-pane fade " id="tab_content63" aria-labelledby="profile-tab63"><!--Inicio Tabela 6 -->
                   <br>
                   <div class="loadModulo">
                   <center><img src="<?php echo URL ?>/assets/images/loader.gif"></center>
                  </div>
                    <input type="text" name="controlaModulo" id="controlaModulo" class="hide">
                    <div id="carregaModulo"></div>  

              </div><!--Fim Tabela 6 -->
            </div>
          </div>
        </div><!--Fim Tabela 1 -->
      
        <div role="tabpanel" class="tab-pane fade " id="tab_content30" aria-labelledby="profile-tab30"><!--Inicio Tabela 2 -->
          <div class="" role="tabpanel" data-example-id="togglable-tabs">
            <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
              <li role="presentation" class="active2"><a href="#tab_content41" id="profile-tab41" role="tab" data-toggle="tab" aria-expanded="true">Grupos <span class="badge"><?php echo $totalAcesso2['numGrupos'] ?></a>
              </li>
              <li role="presentation" class=""><a href="#tab_content51" role="tab" id="profile-tab51" data-toggle="tab" aria-expanded="false">Programas <span class="badge"><?php echo $totalAcesso2['numProgs'] ?></span></a>
              </li>
              <li role="presentation" class=""><a href="#tab_content61" role="tab" id="profile-tab61" data-toggle="tab" aria-expanded="false">Prog. Duplicados <span class="badge"><?php echo $totalAcesso2['nroProgDup'] ?></span></a>
              </li>
              <li role="presentation" class=""><a href="#tab_content71" role="tab" id="profile-tab71" data-toggle="tab" aria-expanded="false">Matriz de Risco <span class="badge"><?php echo $totalAcesso2['nroRiscos'] ?></span></a>
              </li>
              <li role="presentation" class=""><a href="#tab_content81" role="tab" id="profile-tab81" data-toggle="tab" aria-expanded="false">Processos <span class="badge"><?php echo $totalAcesso2['numProcess'] ?></span></a>
              </li>
              <li role="presentation" class=""><a href="#tab_content91" role="tab" id="profile-ta91" data-toggle="tab" aria-expanded="false">Acesso a Módulos <span class="badge"><?php echo $totalAcesso2['numModulos'] ?></span></a>
              </li>   
            </ul>
            <div id="myTabContent" class="tab-content">
              <div role="tabpanel" class="tab-pane fade active2 in" id="tab_content41" aria-labelledby="profile-tab41"><!--Inicio Tabela 1 -->
                <br>
                  <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer"><div class="row"><div class="col-sm-6"></div><div class="col-sm-6"></div></div><div class="row"><div class="col-sm-12">
                  <table  class="tabela table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info" style="width: 100%;" id="tableGrupoUsuario2">
                  <thead>
                    <tr>
                    <th>Id Grupo</th>
                    <th>Descrição</th>
                    <th>Gestor</th>
                    <th>Quant. Programa</th>
                    <th>Quant. Usuários</th>
                    </tr>
                  </thead>
                  <tbody> 
               
                   <?php foreach($grupo2 as $valor):?>
                      <tr>
                        <td><?php echo utf8_decode($valor['idLegGrupo'])?></td>
                        <td><?php echo $valor['descAbrev']?></td>
                        <td><?php echo $valor['nomeGestor']?></td>
                        <td><span class="badge label-primary"><?php echo $valor['totalPro']?></span></td>
                        <td><span class="badge label-primary"><?php echo $valor['totalUsuario']?></span></td>
                      </tr>
                    <?php endforeach;?>  

                   </tbody>
                </table></div></div></div>
              </div><!--Fim Tabela 1 -->
              <div role="tabpanel" class="tab-pane fade " id="tab_content51" aria-labelledby="profile-tab51"><!--Inicio Tabela 2 -->
                  <div class="loadProgUsr2">
                   <center><img src="<?php echo URL ?>/assets/images/loader.gif"></center>
                  </div>
                  <br>
                  <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer"><div class="row"><div class="col-sm-6"></div><div class="col-sm-6"></div></div><div class="row"><div class="col-sm-12"><table  class="tabelaProg2 table table-striped hover table-striped dt-responsive nowrap  no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info" style="width: 100%;" id="tableProgUsuario2">
                  <thead>
                    <tr role="row">
                      <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 71px;" aria-sort="ascending" aria-label="First name: activate to sort column descending">Id Grupo</th>
                      <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 70px;" aria-label="Last name: activate to sort column ascending">Descrição</th>
                      <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 70px;" aria-label="Last name: activate to sort column ascending">Programa</th>
                      <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 70px;" aria-label="Last name: activate to sort column ascending">Descrição</th>
                       <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 70px;" aria-label="Last name: activate to sort column ascending">Observacao</th>
                      <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 70px;" aria-label="Last name: activate to sort column ascending">Rotina</th>
                    </tr>
                  </thead>
                  <tbody id="carregaProgUsr2"> 
                  </tbody>
                </table></div></div></div>
              </div><!--FIM Tabela 2 -->
              <div role="tabpanel" class="tab-pane fade " id="tab_content61" aria-labelledby="profile-tab61"><!--Inicio Tabela 3 -->
                 <div class="loadProgDuplicadoUsr2">
                   <center><img src="<?php echo URL ?>/assets/images/loader.gif"></center>
                  </div>
                    <br>
                  <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer"><div class="row"><div class="col-sm-6"></div><div class="col-sm-6"></div></div><div class="row"><div class="col-sm-12">
                    <table  class="tabelaProgDuplicado2 table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info" style="width: 100%;" id="taleProgDuplicado2" >
                    <thead>
                      <tr role="row">
                        <th>Programa</th>
                        <th>Descrição</th>
                        <th>Grupos</th>
                      </tr>
                    </thead>
                    <tbody id="carregaProDuplicadogUsr2"> 
                    </tbody>
                  </table></div></div></div>
              </div><!--Inicio Tabela 3-->
              <div role="tabpanel" class="tab-pane fade " id="tab_content71" aria-labelledby="profile-tab71"><!--Inicio Tabela 4 -->
                 <div class="loadMatriz2">
                   <center><img src="<?php echo URL ?>/assets/images/loader.gif"></center>
                  </div>
                  <input type="text" name="controlaMatriz2" id="controlaMatriz2" class="hide">
                  <br>
                  <div id="carregaMatiz2"></div>
                  
                  
              </div><!--Fim Tabela 1 -->

              <div role="tabpanel" class="tab-pane fade " id="tab_content81" aria-labelledby="profile-tab81"><!--Inicio Tabela 5 -->
                 <div class="loadMatrizProcesso2">
                   <center><img src="<?php echo URL ?>/assets/images/loader.gif"></center>
                  </div>
                  <input type="text" name="controlaProcessoMatriz2" id="controlaProcessoMatriz2" class="hide">
                   <br>
                   <div id="carregaProcessoMatiz2"></div>

                
              </div><!--Fim Tabela 5 -->

              <div role="tabpanel" class="tab-pane fade " id="tab_content91" aria-labelledby="profile-tab91"><!--Inicio Tabela 6 -->
                   <br>
                   <div class="loadModulo2">
                   <center><img src="<?php echo URL ?>/assets/images/loader.gif"></center>
                  </div>
                    <input type="text" name="controlaModulo2" id="controlaModulo2" class="hide">
                    <div id="carregaModulo2"></div>  

              </div><!--Fim Tabela 6 -->
            </div>
          </div>
        </div><!--FIM Tabela 2 -->
      </div>
  </div>
</div>
</div>
</form>
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><span id="modalTopo"></span></h4>
      </div>
      <div class="modal-body">
       
            <table  class="tabela2 table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info" style="width: 100%;">
              <thead>
                <tr>
                  <th>Programa</th>
                </tr>
              </thead>
              <tbody id="modalApresentaPrograma"> 
              </tbody>
            </table>
         
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>