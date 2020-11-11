<?php 

if(!empty($documento['documento'])){
    $doc = json_decode($documento['documento']);
}


if(!empty($doc)){
    $fim = $doc->fim;
    $inicio = $doc->inicio;
    $gestor = $doc->gestor;
    $usuario = $doc->usuario;
    $idGestor = $doc->idGestor;
    $idUsuario = $doc->idGestor;
    $aprovacaoGestor = $doc->aprovacaoGestor;
    $tabela = $doc->tabela;
    $idSolicitante = $doc->idSolicitante;
}else{
    $fim = "";
    $inicio = "";
    $gestor = "";
    $usuario = "";
    $idGestor = "";
    $idUsuario = "";
    $aprovacaoGestor = "";
    $tabela = "";
    $idSolicitante = "";

}



?>
<style type="text/css">
	#country-list {
        float:left;
        list-style:none;
        margin-top:1px;
        margin-left: 10px;
        padding:0;
        height: 210px;
        width:396px;
        position: absolute;
        z-index:999;
        overflow: auto;
    }
    #country-list li{
        padding: 10px;
        background: #f0f0f0;
        border-bottom: #e6e6e6 1px solid;
        /*border-radius: 5px;*/
        border-left: #e6e6e6 1px solid;
        border-right: #e6e6e6 1px solid;
    }
    #country-list li:hover{background:#ece3d2;cursor: pointer;}
    #search-box {padding: 10px;border: #a8d4b1 1px solid;border-radius:4px;}

    #country-list2 {
        float:left;
        list-style:none;
        margin-top:1px;
        margin-left: 10px;
        padding:0;
        height: 210px;
        width:396px;
        position: absolute;
        z-index:999;
        overflow: auto;
    }
    #country-list2 li{
        padding: 10px;
        background: #f0f0f0;
        border-bottom: #e6e6e6 1px solid;
        /*border-radius: 5px;*/
        border-left: #e6e6e6 1px solid;
        border-right: #e6e6e6 1px solid;
    }
    #country-list2 li:hover{background:#ece3d2;cursor: pointer;}
    #search-box2 {padding: 10px;border: #a8d4b1 1px solid;border-radius:4px;}

     .list-grupo{
        list-style: none;
        margin-left: 0;
        padding-left: 0;
    }
    .list-grupo li{
        border-bottom: solid 1px #e6e6e6;
        padding: 6px;
    }
    .list-grupo li .fa-remove{
        cursor: pointer;
    }
</style>
<script type="text/javascript">
	
	$(document).ready(function(){
        $("#suggesstion-box2").hide();
        $("#suggesstion-box").hide();
        $(".aprovacaoGestor").hide();
        $(".aprovacao").hide();
        $(".aprovacaoSi").hide();
    
        if($("#idAtividade").val() == 5 ){
            $("#usuario").attr("readonly","readonly");
            $("#inicio").attr("readonly","readonly");
            $("#fim").attr("readonly","readonly");
            $("#grupo").attr("readonly","readonly");
            $("#addGrupo").attr("disabled","disabled");
            $("#addGrupo").hide();
            $(".grupo").hide();
            $(".aprovacaoGestor").show();
            $(".aprovacao").show();
            $("#aprovacaoGestor").attr("required","required");

            if($("#aprovacaoSi").val() != ""){
                 //$(".aprovacaoSi").show();
            }

            $("#aprovacaoGestor").change(function(){
                if($(this).val() == "nao"){
                   $("#obsGestor").attr("required","required"); 
                }
            });
        }

        if($("#idAtividade").val() == 3){

            $("#aprovacaoGestor").attr("readonly","readonly");
            $("#aprovacaoSi").attr("readonly","readonly");
            $(".aprovacao").show();
            if($("#aprovacaoSi").val() != ""){
                // $(".aprovacaoSi").show();
            }
            if($("#aprovacaoGestor").val() != ""){
                 $(".aprovacaoGestor").show();
            }
            
        }

        if($("#idAtividade").val() == 6){
             $("#usuario").attr("readonly","readonly");
            $("#inicio").attr("readonly","readonly");
            $("#fim").attr("readonly","readonly");
            $("#grupo").attr("readonly","readonly");
            $("#addGrupo").attr("disabled","disabled");
            $("#aprovacaoGestor").attr("readonly","readonly");
            $("#aprovacaoSi").attr("readonly","readonly");
            $(".aprovacao").show();
            $("#addGrupo").hide();
            $(".grupo").hide();
            
            if($("#aprovacaoSi").val() != ""){
                 //$(".aprovacaoSi").show();
            }
            if($("#aprovacaoGestor").val() != ""){
                 $(".aprovacaoGestor").show();
            }
            
        }
        
	 	$("#usuario").keyup(function(){
            if($(this).val().length == 0){
                $("#idUsuario").val("");
                $("#gestor").val("");
                $("#idGestor").val("");
            }
            var idUsr  = $(this).val();
            $.ajax({
                type: "POST",
                url: url+"Fluxo/ajaxCarregaTodosUsuario",
                data:'idUsr='+idUsr,
                beforeSend: function(){
                    $("#usuario").css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
                },
                success: function(data){
                    $("#suggesstion-box").show();
                    $("#country-list").html(data);

                    var idUsr = $("#idUsuario").val();
                    
                     $.ajax({
                        type: "POST",
                        url: url+"Fluxo/ajaxCarregaGruposUsr",
                        data:'idUsr='+idUsr,
                        beforeSend: function(){
                            
                        },
                        success: function(data){
                           
                            console.log(data);

                        }
                    });


                }
            });
        });
        $("#grupo").keyup(function(){
            if($(this).val().length == 0){
                
            }
            var idGrupo  = $(this).val();
            $.ajax({
                type: "POST",
                url: url+"Fluxo/ajaxCarregaTodosGrupos",
                data:'idGrupo='+idGrupo,
                beforeSend: function(){
                    $("#grupo").css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
                },
                success: function(data){
                    $("#suggesstion-box2").show();
                    $("#country-list2").html(data);
                    console.log(data);

                }
            });
        });

        // Remove o usuário selecionado quando clicado

        // Adiciona grupo
        $('#addGrupo').on('click', function(){
            if($("#idLegGrupo").val() != ''){
                var idLegGrupo = $("#idLegGrupo").val();
            	var grupo = $("#grupo").val();
            	var idGrupo = $("#idGrupo").val();
                var descGrupo = $("#grupo").val();
                var idGestor = $("#idGestorGrupo").val();
	            var htmlGrupo = "<li><input type=\"hidden\" name=\"grupoTab[]\" value=\""+idLegGrupo+"\"><input type=\"hidden\" name=\"idGrupoTab[]\" value=\""+idGrupo+"\"><input type=\"hidden\" name=\"descGrupo[]\" value=\""+descGrupo+"\"><input type=\"hidden\" name=\"idGestorTab[]\" value=\""+idGestor+"\"> "+grupo+" <i class=\"fa fa-remove pull-right remove \"> </i>  </li>";
	            var usrExists = false;

	            // $('.list-user li input').each(function(){
	            //     var id = $(this).val();
	            //     if(id == idUser){
	            //         alert('Usuário já adicionado!');
	            //         usrExists = true;
	            //         return false;
	            //     }
	            // });

	         

	            $(".list-grupo").append(htmlGrupo);

	            $("#idLegGrupo").val('');
	            $("#grupo").val('');
	            $("#idGrupo").val('');
                $("#idGestorGrupo").val('');
            }

            
        });
	});
    //Remove a linha do grupo
     $(document).on('click','.fa-remove', function(){
        $(this).closest('li').remove();
    });

	function carregaDadosUsr(usuario,idUsuario,nomeGestor,idGestor){
		$("#usuario").val(usuario);
		$("#idUsuario").val(idUsuario);
		$("#gestor").val(nomeGestor);
		$("#idGestor").val(idGestor);
		$("#suggesstion-box").hide();
	}
	function carregaDadosGrupo(idLegGrupo,descAbrev,idGrupo,codGest){
		$("#grupo").val(idLegGrupo+" - "+descAbrev);
		$("#idLegGrupo").val(idLegGrupo);
		$("#idGrupo").val(idGrupo);
        $("#idGestorGrupo").val(codGest);
		$("#suggesstion-box2").hide();
	}



	    
</script>
<div class="col-md-12 col-sm-12 col-xs-12">
  <ol class="breadcrumb">
        <li><a href="<?php echo URL ?>/" onclick="loadingPagia()">Dashboard</a></li>              
        <li class="active">Processos</li>
        <li class="active">Acesso Temporário</li>
  </ol>
</div>
<form method="POST">
    <div class="x_panel">
       <div class="x_title">
    	    <h2>Acesso Temporário</h2>
    	    <ul class="nav navbar-right panel_toolbox">
    	      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
    	      </li>  
    	    </ul>
    	    <div class="clearfix"></div>
      	</div>
        <div class="row">
            <div class="col-md-11"></div>
             <div class="col-md-1"><input type="submit" name="enviar" value="Enviar" class="btn btn-primary"></div>
        </div>
        <input type="text" name="idAtividade" id="idAtividade" class="hide" value="<?= $atividade ?>">
        <input type="text" name="idSolicitante" id="idSolicitante" class="" value="<?= $idSolicitante ?>">
      	<div class="row">
    		<div class="col-md-4">
    			<label>Usuário</label>
    			<input type="text" name="usuario" id="usuario" class="form-control" required="required" autocomplete="off" value="<?= $usuario ?>">
    			<input type="text" name="idUsuario" id="idUsuario" class="form-control hide" required="required" value="<?= $idUsuario ?>">
    			<div id="suggesstion-box">
                    <ul id="country-list"></ul>
                </div>
    		</div>
    		<div class="col-md-4">
    			<label>Gestor</label>
    			<input type="text" name="gestor" id="gestor" class="form-control" readonly="readonly" value="<?= $gestor ?>">
    			<input type="text" name="idGestor" id="idGestor" class="form-control hide" readonly="readonly" value="<?= $idGestor ?>">
    			
    		</div>
    	</div>
        <div class="row">
            <div class="col-md-2">
                <label>Inicio</label>
                <input type="date" name="inicio" id="inicio" class="form-control" value="<?= $inicio ?>">
            </div>
            <div class="col-md-2">
                <label>Fim</label>
                <input type="date" name="fim" id="fim" class="form-control" value="<?= $fim ?>">
            </div>
        </div>
    	<br><br>
    	<div class="panel panel-default">
    	  <div class="panel-heading" style="background-color:#2A3F54;color: #FFFFFF"><h4>Grupos</h4></div>
    	  <div class="panel-body">
    	  	<div class="row grupo">
    	  		<div class="col-md-4">
    	  			<input type="text" name="grupo" id="grupo" class="form-control" autocomplete="off">
    	  			<input type="text" name="idLegGrupo" id="idLegGrupo" class="form-control hide" readonly="readonly">
    				<input type="text" name="idGrupo" id="idGrupo" class="form-control hide" readonly="readonly">
                    <input type="text" name="idGestorGrupo" id="idGestorGrupo" class="form-control hide" readonly="readonly">
    	  				<div id="suggesstion-box2">
                    		<ul id="country-list2"></ul>
                		</div>
    	  		</div>
    	  		  <div class="col-md-1">
                    <label>&nbsp;</label>
                    &nbsp;<button type="button" class="btn btn btn-success pull-right" id="addGrupo"><i class="fa fa-plus"></i> </button>
                </div>
    	  	</div>
    	  	<br>
    	  	<di2v class="row">
    	  		<div class="col-md-6">
    	  			<div class="x_title">
                        <h2>Grupo adicionados</h2>
                       
                        <div class="clearfix"></div>
                    </div>
    	  			<ul class="list-grupo">
                        <?php if(!empty($tabela)): ?>
                            <?php foreach($tabela as  $value):?>
                                <li>
                                   <?php echo $value->descGrupo ?>
                                    <input type="text" name="grupoTab[]" value="<?php echo $value->codGrupo ?>" class="hide">
                                    <input type="text" name="idGrupoTab[]" value="<?php echo $value->idGrupo ?>" class="hide">
                                    <input type="text" name="descGrupo[]" value="<?php echo $value->descGrupo ?>" class="hide">
                                    <input type="text" name="idGestorTab[]" value="<?php echo $value->idGestorGrupo ?>" class="hide">
                                    <?php if($atividade == 3): ?>
                                        <i class="fa fa-remove pull-right remove"></i>
                                    <?php endif;?>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?> 
                             
                    </ul>
    	  		</div>
    	  	</div>
    	  </div>
          <br>
            
                <div class="row">
                    <div class="col-md-4">
                        <div class="aprovacaoGestor">
                            <div class="panel panel-default">
                              <div class="panel-heading" style="background-color:#2A3F54;color: #FFFFFF"><h4>Aprovação Gestor</h4></div>
                              <div class="panel-body">
                                  <div class="row">
                                    <div class="col-md-12">
                                        <label>Aprovado</label>
                                        <select class="form-control" name="aprovacaoGestor" id="aprovacaoGestor">
                                            <?php if($aprovacaoGestor == "sim" && $atividade == 5): ?>
                                                <option value="sim">Sim</option>
                                                <option value="nao">Não</option>
                                            <?php endif; ?>
                                            <?php if($aprovacaoGestor == "nao" && $atividade == 5): ?>
                                                <option value="nao">Não</option>
                                                <option value="sim">Sim</option>
                                            <?php endif; ?>
                                            <?php if($aprovacaoGestor == ""  && $atividade == 5 || $aprovacaoGestor == ""  && $atividade == 6): ?>
                                                <option value=""></option>
                                                <option value="sim">Sim</option>
                                                <option value="nao">Não</option>
                                            <?php endif; ?>
                                             <?php if($aprovacaoGestor == ""  && $atividade == 0): ?>
                                                <option value=""></option>
                                            <?php endif; ?>
                                         
                                            <?php if($aprovacaoGestor != ""  && $atividade != 5 ): ?>
                                                   
                                                    <?php if($aprovacaoGestor == "sim"): ?>
                                                    <option value="<?php echo $aprovacaoGestor ?>">Sim</option>
                                                    <?php endif; ?>
                                                    
                                                    <?php if($aprovacaoGestor == "nao"): ?>
                                                    <option value="<?php echo $aprovacaoGestor ?>">Não</option>
                                                    <?php endif; ?>
                                                    
                                                </option>
                                            <?php endif; ?>     
                                        </select>
                                    </div>
                                  </div>
                              </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                          <div class="aprovacaoSi">
                            <div class="panel panel-default">
                              <div class="panel-heading" style="background-color:#2A3F54;color: #FFFFFF"><h4>Aprovação SI</h4></div>
                              <div class="panel-body">
                                  <div class="row">
                                    <div class="col-md-12">
                                        <label>Aprovado</label>
                                        <select class="form-control" name="aprovacaoSi" id="aprovacaoSi">
                                            <?php if($aprovacaoGestor == "sim"): ?>
                                                <option value="sim">Sim</option>
                                                <option value="nao">Não</option>
                                            <?php endif; ?>
                                            <?php if($aprovacaoGestor == "nao"): ?>
                                                <option value="nao">Não</option>
                                                <option value="sim">Sim</option>
                                            <?php endif; ?>
                                            <?php if($aprovacaoGestor == ""): ?>
                                                 <option value=""></option>
                                                <option value="sim">Sim</option>
                                                <option value="nao">Não</option>
                                            <?php endif; ?>

                                             <?php if($aprovacaoGestor != ""  && $atividade == 3 ): ?>
                                                   
                                                    <?php if($aprovacaoGestor == "sim"): ?>
                                                    <option value="<?php echo $aprovacaoGestor ?>">Sim</option>
                                                    <?php endif; ?>
                                                    
                                                    <?php if($aprovacaoGestor == "nao"): ?>
                                                    <option value="<?php echo $aprovacaoGestor ?>">Não</option>
                                                    <?php endif; ?>
                                                    
                                                </option>
                                            <?php endif; ?>    
                                        </select>
                                    </div>
                                  </div>
                              </div>
                            </div>
                        </div>
                 
                    </div>
                    <div class="col-md-4"></div>
                
            </div>
            <br>
            <div class="aprovacao">
                <div class="x_title">
                    <h2>Historio de Aprovação</h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>  
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <br>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Usuário</th>
                            <th>Observação</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($mensagem as $mensagens): ?>
                            <tr>
                                <td><?php echo $mensagens['autor'] ?> </td>
                                <td><?php echo $mensagens['msg'] ?> </td>
                                <td><?php echo $mensagens['dataCriacao'] ?> </td>
                            </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-6">
                        <label>Observação</label>
                        <textarea class="form-control" name="obsGestor" id="obsGestor" rows="5"></textarea>
                    </div>
                </div>
            </div>
    	</div>
    </div>
</form>
