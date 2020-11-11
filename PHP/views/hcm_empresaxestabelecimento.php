<style>
    #grupos,
    #gruposAdd{
        min-height: 300px;
        background: #f2f2f2;
    }
    .multiselect-container>li>a>label {
  padding: 4px 20px 3px 20px;
}
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" rel="stylesheet"/>
<div class="col-md-12 col-sm-12 col-xs-12">
    <ol class="breadcrumb">
        <li>
            <a href="<?php echo URL ?>">
                <font style="vertical-align: inherit;" onclick="loadingPagia()">
                <font style="vertical-align: inherit;">Dashboard</font></font>
            </a>
        </li>
        <li class="active">Empresa x Estabelecimento</li>
        
    </ol>
</div>

<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Configuração Empresa x Estabelecimento</h2>
            <div class="clearfix"></div>
        </div>
        <br>
        <div class="x_content">
            <form action="<?= URL; ?>/HCM/">
                <div class="row">
                    <div class="col-md-12"><?php echo $this->helper->alertMessage(); ?></div>
                </div>
                <div class="row">
                <br>
                    <div class="col-md-4">
                    <label>Empresa</label>
                        <select name="empresa" class="form-control" id="instancia">
                            <option value=""></option>
                            <?php 
                            foreach($empresas as $val): ?>
                                <option value="<?php echo $val['idEmpresa']; ?>"><?php echo $val['razaoSocial'] ;?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-5">
                        <label>Estabelecimentos:</label>
                        <select class="form-control" size="9" id="estabelecimentos" multiple>
                        <!--  -->
                        </select>
                    </div>
                    <div class="col-md-2">
                        <div style="float: none; margin: 70px 0 0 28%;">
                            <button type="button" class="btn btn-success" id="btnAddEstabelecimentos"> >> </button><br>
                            <button type="button" class="btn btn-danger" id="btnRemoveEstabelecimentos"> << </button>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <label>Estabelecimentos Já Vinculados:</label>
                        <select class="form-control" size="9" id="EstabelecimentosAdd" name="EstabelecimentosVinculados[]" multiple></select>
                    </div>
                </div>
                <br><br>
                <div class="row">
                    <button id="btnSalvar" class="btn btn-success pull-right">Salvar</button>
                </div>
            </form>
            <div class="clearfix"></div>
            <br><br>
           
        </div>
        <br>
    </div>
</div>  

<div id="myModalResult" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <p id="result_msg"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let arr_original=[];
let arr_modificado=[];

$(function() {

});

    
    $("#btnSalvar").click(function(){
        if(arr_original.toString()!=arr_modificado.toString()){
            $.ajax({
            type: 'POST',
            url: url+'HCM/gravarEstabexEmpresa',
            data: {'idOriginais':arr_original,'idSelecionados':arr_modificado,'idEmpresa':$("#instancia").val()},
            success: function (res) {
                console.log(res);
                
            }
        });
        }
    });

   

    $("#instancia").on('change',function(){

        if($("#instancia").val().length>0){
            carregaEstabelecimentos($("#instancia").val());
            carregaEstabelecimentosVinculados($("#instancia").val());
        }
    });

    function carregaEstabelecimentos(idEmpresa){
        $.ajax({
            type: 'POST',
            url: url+'HCM/ajaxEstabelecimentos',
            data: {'idEmpresa':idEmpresa},
            success: function (res) {
                $('#estabelecimentos').html('');
                $('#estabelecimentos').append(res);
            }
        });
    }
    
    function carregaEstabelecimentosVinculados(idEmpresa){
        $.ajax({
            type: 'POST',
            dataType:'JSON',
            url: url+'HCM/ajaxEstabelecimentosVinculados',
            data: {'idEmpresa':idEmpresa},
            success: function (res) {
                $('#EstabelecimentosAdd').html('');
                $('#EstabelecimentosAdd').append(res[0][0]);
                
                arr_original=Array.from(res[0][1]);
                arr_modificado=Array.from(res[0][1]);
            }
        });
    }

    $('#btnAddEstabelecimentos').click(function(){            
        $("#estabelecimentos option:selected").each(function() {  
            $(this).remove();
            $("#EstabelecimentosAdd").append('<option id="'+$(this).attr('id')+'"value="'+$(this).val()+'">'+$(this).text()+"</option>");
            arr_modificado.push($(this).attr('id'));
        });
    });
    
    $('#btnRemoveEstabelecimentos').click(function(){
        $("#EstabelecimentosAdd option:selected" ).each(function() {  
            $(this).remove();                
            $("#estabelecimentos").append('<option id="'+$(this).attr('id')+'"value="'+$(this).val()+'">'+$(this).text()+"</option>");
            var index=arr_modificado.indexOf($(this).attr('id'));
            if(index > -1){
                arr_modificado.splice(index,1);
            }
        });
    }); 
</script>