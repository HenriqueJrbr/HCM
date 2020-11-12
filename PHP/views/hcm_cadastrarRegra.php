<style>
    #grupos,
    #gruposAdd{
        min-height: 300px;
        background: #f2f2f2;
    }
</style>
<div class="col-md-12 col-sm-12 col-xs-12">
    <ol class="breadcrumb">
        <li>
            <a href="<?php echo URL ?>">
                <font style="vertical-align: inherit;" onclick="loadingPagia()">
                <font style="vertical-align: inherit;">Dashboard</font></font>
            </a>
        </li>
        <li class="active"><a href="<?php echo URL; ?>/Provisionamento/">Provisionamento</a></li>
        <li class="active">Cadastro</li>
    </ol>
</div>

<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Cadastro de regras de admissão</h2>
            <div class="clearfix"></div>
        </div>
        <br>
        <div class="x_content">
            <form action="<?= URL; ?>/Provisionamento/gravaProvisionamento" id="formProvisionamento" method="post">
                <div class="row">
                    <div class="col-md-12"><?php echo $this->helper->alertMessage(); ?></div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label>Empresa:</label>
                        <select name="empresa" class="form-control" id="instancia">
                            <option value=""></option>
                            <option value="todas">Todas</option>
                            <?php foreach($empresas as $val): ?>
                                <option value="<?php echo $val['idEmpresa']; ?>"><?php echo $val['razaoSocial'] ;?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Estabelecimento:</label>
                        <select name="empresa" class="form-control" id="estabelecimento">
                            <option value=""></option>
                            <?php foreach($instancias as $val): ?>
                                <option value="<?php echo $val['idEmpresa']; ?>"><?php echo $val['razaoSocial'] ;?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Departamento:</label>
                        <select name="empresa" class="form-control" id="departamento">
                            <option value=""></option>
                            <?php foreach($instancias as $val): ?>
                                <option value="<?php echo $val['idEmpresa']; ?>"><?php echo $val['razaoSocial'] ;?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="clearfix"></div>
                <br>
                <div class="row">
                    <div class="col-md-4">
                        <label>Unidade Lotação:</label>
                        <select name="empresa" class="form-control" id="unlotacao">
                            <option value=""></option>
                            <?php foreach($instancias as $val): ?>
                                <option value="<?php echo $val['idEmpresa']; ?>"><?php echo $val['razaoSocial'] ;?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Centro de custo:</label>
                        <select name="empresa" class="form-control" id="centrocusto">
                            <option value=""></option>
                            <?php foreach($instancias as $val): ?>
                                <option value="<?php echo $val['idEmpresa']; ?>"><?php echo $val['razaoSocial'] ;?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Cargo:</label>
                        <select name="empresa" class="form-control" id="cargobase">
                            <option value=""></option>
                            <?php foreach($instancias as $val): ?>
                                <option value="<?php echo $val['idEmpresa']; ?>"><?php echo $val['razaoSocial'] ;?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="clearfix"></div>
                <br>
                <div class="row">
                    <div class="col-md-4">
                        <label>Nível Hierárquico:</label>
                        <select name="empresa" class="form-control" id="nvlh">
                            <option value=""></option>
                            <?php foreach($instancias as $val): ?>
                                <option value="<?php echo $val['idEmpresa']; ?>"><?php echo $val['razaoSocial'] ;?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Função:</label>
                        <select name="empresa" class="form-control" id="funcaoSistema">
                            <option value=""></option>
                            <?php foreach($instancias as $val): ?>
                                <option value="<?php echo $val['idEmpresa']; ?>"><?php echo $val['razaoSocial'] ;?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-5">
                        <label>Grupos:</label>
                        <select class="form-control" size="9" id="gp" multiple>
                        <!--  -->
                        </select>
                    </div>
                    <div class="col-md-2">
                        <div style="float: none; margin: 70px 0 0 28%;">
                            <button type="button" class="btn btn-success" id="btnAddGrupos"> >> </button><br>
                            <button type="button" class="btn btn-danger" id="btnRemoveGrupos"> << </button>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <label>Grupos à adicionar:</label>
                        <select class="form-control" size="9" id="gruposadd" name="EstabelecimentosVinculados[]" multiple></select>
                    </div>
                </div>
                <div class="clearfix"></div>
                <br> 
                <div class="clearfix"></div>
                <br><br>
                <div class="row">
                    <div class="col-md-2 pull-right">
                        <button type="button" class="btn btn-danger" onclick="javascript:history.back(-1)">Voltar</button>                
                        <button type="button" class="btn btn-success" id="btnSalvarRegra">Salvar</button>
                    </div>
                </div>
            </form>
            
            <div class="clearfix"></div>
            <br><br>
            <!--
            <div class="row">
                <div class="col-md-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Overview da solicitação</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div role="tabpanel" class="tab-pane fade active in" >
                                <div class="" role="tabpanel" data-example-id="togglable-tabs">
                                    <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                        <li role="presentation" class="active">
                                            <a href="#tab_content1" id="profile-tab1" role="tab" data-toggle="tab" aria-expanded="true">Riscos <span class="badge badge-danger" id="count-riscos"></span></a>
                                        </li>
                                    </ul>
                                    <div id="myTabContent" class="tab-content">
                                        <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="profile-tab1">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="x_panel">
                                                        <div class="x_title">
                                                            <h2>Matriz de Riscos  </h2>
                                                            <ul class="nav navbar-right panel_toolbox">
                                                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                                            </ul>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <div class="x_content">
                                                            <div class="col-md-12">
                                                                <h5 class="text-center hide" id="text-risco">Analisando riscos...</h5>
                                                            </div>
                                                            <div id="matriz-risco-grupos"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            -->
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
    $(document).ready(function () {

        $('#btnRemoveGrupos').click(function(){
        $("#gruposadd option:selected" ).each(function() {  
            $(this).remove();                
            $("#gp").append('<option id="'+$(this).attr('id')+'"value="'+$(this).val()+'">'+$(this).text()+"</option>");
            // var index=arr_modificado.indexOf($(this).attr('id'));
            // if(index > -1){
            //     arr_modificado.splice(index,1);
            // }
        });
      }); 

        $('#btnAddGrupos').click(function(){            
        $("#gp option:selected").each(function() {  
            $(this).remove();
            $("#gruposadd").append('<option id="'+$(this).attr('id')+'"value="'+$(this).val()+'">'+$(this).text()+"</option>");
            // arr_modificado.push($(this).attr('id'));
        });
       });

        $("#btnSalvarRegra").click(function(){
        if($("#instancia").val()=="" || $("#estabelecimento").val()=="" || $("#departamento").val()=="" || $("#unlotacao").val()=="" || $("#centrocusto").val()=="" || $("#cargobase").val()=="" || $("#nvlh").val()=="" || $("#gruposadd").find('option').val()==undefined)
        {
            $('#myModalResult .modal-body').html('<h5>Por favor preencha todos os campos</h5>');
            $("#myModalResult").modal('show');
        }
        else{
                var op = $('#gruposadd').find('option');
                var arrayOp = new Array();
                for(var i=0;i<op.length;i++)
                {
                arrayOp[i]= op[i].id;
                }   
                var arrayEmp = new Array();
                if($("#instancia").val()=="todas")     
                {
                    arrayEmp[0]="1";
                    arrayEmp[1]="2";
                    arrayEmp[2]="3";
    
                     $.ajax({
                            type: 'POST',
                            url: url+'HCM/ajaxGravarRegra2',
                            data: {'idGrupo': arrayOp,'idEmpresa':arrayEmp,'idEstabelecimento':$("#estabelecimento").val(),'idDepartamentoHCM':$("#departamento").val(),'idUnidadeLotacao':$("#unlotacao").val(),'idCentroCusto':$("#centrocusto").val(), 'idCargoBase':$("#cargobase").val(), 'idNivelHierarquico':$("#nvlh").val(), 'idFuncao':$("#funcaoSistema").val()},
                            success: function (res) {
                                $('#myModalResult .modal-body').html('<h5>Nova regra salva com sucesso!</h5>');
                                $("#myModalResult").modal('show');
                                $("#estabelecimento").empty();
                                $("#departamento").empty();
                                $("#unlotacao").empty();
                                $("#centrocusto").empty();
                                $("#cargobase").empty();
                                $("#nvlh").empty();
                                $("#funcaoSistema").empty();
                                $("#gp").empty();
                                $("#gruposadd").empty();
                                $("#instancia").val("");
                                // console.log("essa é a res:"+res);

                                }
                         });
                }
                else{
                $.ajax({
                type: 'POST',
                url: url+'HCM/ajaxGravarRegra',
                data: {'idGrupo': arrayOp,'idEmpresa':$("#instancia").val(),'idEstabelecimento':$("#estabelecimento").val(),'idDepartamentoHCM':$("#departamento").val(),'idUnidadeLotacao':$("#unlotacao").val(),'idCentroCusto':$("#centrocusto").val(), 'idCargoBase':$("#cargobase").val(), 'idNivelHierarquico':$("#nvlh").val(), 'idFuncao':$("#funcaoSistema").val()},
                success: function (res) {
                    $('#myModalResult .modal-body').html('<h5>Nova regra salva com sucesso!</h5>');
                    $("#myModalResult").modal('show');
                    $("#estabelecimento").empty();
                    $("#departamento").empty();
                    $("#unlotacao").empty();
                    $("#centrocusto").empty();
                    $("#cargobase").empty();
                    $("#nvlh").empty();
                    $("#funcaoSistema").empty();
                    $("#gp").empty();
                    $("#gruposadd").empty();
                    $("#instancia").val("");
                    // console.log("essa é a res:"+res);
                    
                }
            });
                }
          

        }
                 
            });

        $("#instancia").on('change',function(){
           if($("#instancia").val().length>0){
                    carregaEstabelecimentos($("#instancia").val());
                    carregaCargoBase();
                    carregaDepartamento();
                    carregaCentroCusto();
                    carregaUnLotacao();
                    carregaNivelHierarquico();
                    carregaFuncao();
                    carregaGrupo();
                }
        });

        function carregaEstabelecimentos(idEmpresa){
        $.ajax({
            type: 'POST',
            url: url+'HCM/ajaxEstabelecimentos',
            data: {'idEmpresa':idEmpresa},
            success: function (res) {
                $('#estabelecimento').html('');
                $('#estabelecimento').append(res);
            }
        });
        }
        
        
        function carregaNivelHierarquico(){
        $.ajax({
            type: 'POST',
            url: url+'HCM/ajaxNvlHier',
            success: function (res) {
                $('#nvlh').html('');
                $('#nvlh').append(res);
            }
        });
        }

        function carregaGrupo(){
        $.ajax({
            type: 'POST',
            url: url+'HCM/ajaxGrupos',
            success: function (res) {
                $('#gp').html('');
                $('#gp').append(res);
            }
        });
        }

        function carregaFuncao(){
        $.ajax({
            type: 'POST',
            url: url+'HCM/ajaxCarregaFuncao',
            success: function (res) {
                $('#funcaoSistema').html('');
                $('#funcaoSistema').append(res);
            }
        });
        }

        function carregaCargoBase(){
        $.ajax({
            type: 'POST',
            url: url+'HCM/ajaxCargoBase',
            success: function (res) {
                $('#cargobase').html('');
                $('#cargobase').append(res);
            }
        });
        }

        function carregaCentroCusto(){
        $.ajax({
            type: 'POST',
            url: url+'HCM/ajaxCentroCusto',
            success: function (res) {
                $('#centrocusto').html('');
                $('#centrocusto').append(res);
            }
        });
        }

        function carregaDepartamento(){
        $.ajax({
            type: 'POST',
            url: url+'HCM/ajaxDepartamento',
            success: function (res) {
                $('#departamento').html('');
                $('#departamento').append(res);
            }
        });
        }

        function carregaUnLotacao(){
        $.ajax({
            type: 'POST',
            url: url+'HCM/ajaxUnLotacao',
            success: function (res) {
                $('#unlotacao').html('');
                $('#unlotacao').append(res);
            }
        });
        }


        $('#btnAddGrupo').click(function(){            
            $("#grupos option:selected" ).each(function() {  
                $(this).remove();
                $("#gruposAdd").append('<option value="'+$(this).val()+'">'+$(this).text()+"</option>");
                carregaRiscosGrupos();
            });
        });
        
        $('#btnRemoveGrupo').click(function(){
            $("#gruposAdd option:selected" ).each(function() {  
                $(this).remove();                
                $("#grupos").append('<option value="'+$(this).val()+'">'+$(this).text()+"</option>");
                carregaRiscosGrupos();
            });
        });

        $('#buscaGrupos').on('keyup', function(){                                               
            var idsAdicionados = [];
            
            $("#gruposAdd option").each(function() {
                var users = $(this).val().split('-');
                idsAdicionados.push(users[0]);            
            });
                            
            $.ajax({
                type: 'POST',
                url: url+'Provisionamento/ajaxBuscaGrupos',
                data: {
                    grupo: $('#buscaGrupos').val(),
                    eliminar: idsAdicionados,
                    empresa: <?php echo $_SESSION['empresaid']; ?>                            
                },
                success: function (res) {                
                    $('#grupos').html('');
                    $('#grupos').append(res);
                }
            });
        });
        
        // Cria instancia do datatable para poder recarregar o mesmo depois
        // Carrega datatable com programas de cada grupo selecionado
        /*table = $('#tableFuncaoGrupo').DataTable({
            "processing": false,
            "serverSide": true,
            "oLanguage": {
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "Nenhum registro encontrado",
                //"sInfo": "Exibindo de _START_ ate _END_ de _TOTAL_ registros",
                "sInfo": "Página _PAGE_ de _PAGES_",
                "sInfoEmpty": "Nenhum registro para ser exibido",
                //"sInfoFiltered": "(Filtrado de _MAX_ registros no total)",
                "sInfoFiltered": "",
                "sSearch": "Pesquisar:",
                "oPaginate": {
                    "sFirst": "Primeiro",
                    "sLast": "Último",
                    "sNext": "Proximo",
                    "sPrevious": "Anterior"
                },
                "sLoadingRecords": "&nbsp;",
                "sProcessing": '<div class="box-loading-datatable"><div class="spinner pull-left"><img src="'+url+'/assets/images/loader.gif"></div><div class="pull-left" style="margin-top: -10px;">Carregando...</div></div>'
            },
            "ajax": {                            
                type: "POST",
                url: url + "provisionamento/ajaxDatatableProvisionamento",
                "data": function (d){                        
                    d.empresa = $('#instancia').val();
                    d.funcao = $('#funcao').val();                        
                }                    
            }
        });*/

        // Recupera as atividades do fluxo selecionado e atualiza datatable
        $('#instancia').on('change', function (){
            $('#funcao').val('');
            
            // Recarrega o datatable
            $('#tableFuncaoGrupo').DataTable().ajax.reload();
        });
        
        // Carrega os grupos filtrando pela função e instância
        /*$('#funcao').on('change', function (){
            $.ajax({
                type: 'POSt',
                url: url+'provisionamento/ajaxCarregaGrupos',
                data: {
                    empresa: $('#instancia').val(),
                    funcao: $('#funcao').val()
                },
                success: function (res) {
                    $('#grupos').html('');
                    $('#grupos').append(res);
                }
            });
            
            // Recarrega o datatable
            //$('#tableFuncaoGrupo').DataTable().ajax.reload();
        });*/

        /************** Campo de busca e seleção para o grupo ****************/
        $('#funcao').select2({
            //allowClear: true,
            matcher: matchStart,
            multiple: false
        });

        // Valida se grupo selecionado é igual ao grupo que receberá os programas.
        $("#funcao").select2().on("select2:select", function (e){
            // Remove a seleção do option Nenhum
            if(e.params.data.id === ''){
                $("#funcao").val('').change();
            }else{
                var values = $("#funcao").val();
                var i = $("#funcao").val().indexOf('');

                if (i > 0) {
                    values.splice(i, 1);
                    $("#funcao").val(values).change();
                    //$('#idGrupoHidden').val($("#idGrupoSelect2").val());
                }
            }
        });
        /************* FIM campo busca e seleção para o grupo *****************/

        // Apaga provisionamentos selecionados no datatable
        /*$('#btnApagaFuncaoGrupo').on('click', function(){
            var funcaoGrupoDel = [];

            $('.checkFuncaoGrupo').each(function(){
                if($(this).prop('checked')) {
                    funcaoGrupoDel.push($(this).attr('id'));
                }
            });

            // Valida se foi selecionado ao menos um módulo
            if(funcaoGrupoDel.length == 0){
                return false;
            }

            $('#formApagaFuncaoGrupo').submit();
        });*/
        
        // Submete o formulario com grupos adicionados
        // $('#btnSalvarProv').on('click', function(){
        //     if($("#gruposAdd option").length == 0){
        //         $('#myModalResult .modal-body').html('<h5>Favor selecionar ao menos um grupo.</h5>');
        //         $("#myModalResult").modal('show');
        //         return false;
        //     }
            
        //     $("#gruposAdd option" ).each(function() {  
        //         $(this).remove();                
        //         $("#formProvisionamento").append('<input type="hidden" name="grupos[]" value="'+$(this).val()+'">');                                
        //     });
            
            
        //     $("#formProvisionamento").submit();
        // });

        // Recupera matriz de risco
        function carregaRiscosGrupos(){
            var grupos = new Array();

            // Adiciona os grupos a adicionar para o array de grupos
            $("#gruposAdd option" ).each(function() {
                grupos.push($(this).val());
            });

            // Adiciona os grupos ja adicionados para o array de grupos
            $(".checkFuncaoGrupo").each(function() {
                grupos.push($(this).val());
            });

            console.log(grupos);

            if(grupos.length == 0) {
                console.log('teste')
                grupos.push(0);
            }

            $('#matriz-risco-grupos').html('');
            $('#text-risco').css('display', 'block');
            $('#load').css('display', 'block');

            $.ajax({
                type: 'POST',
                url:  url+'Provisionamento/ajaxMatrizDeRisco',
                data: {grupos: grupos, funcao: $('#funcao').val()},
                success: function(res){
                    var data = JSON.parse(res);
                    $('#matriz-risco-grupos').html(data.html);
                    $('#count-riscos').html(data.totalRiscos);
                    $('#riscos').val(data.totalRiscos);
                    $('#text-risco').css('display', 'none');
                    $('#load').css('display', 'none');
                    $('.totalProgByGrupo').text(data.totalProgByGrupo);

                    $('#tableAbaProgs').DataTable().ajax.reload();

                }
            });
        }
    });
    function matchStart(params, data) {
        // If there are no search terms, return all of the data
        if ($.trim(params.term) === '') {
            return data;
        }

        // Skip if there is no 'children' property
        if (typeof data.children === 'undefined') {
            return null;
        }

        // `data.children` contains the actual options that we are matching against
        var filteredChildren = [];
        $.each(data.children, function (idx, child) {
            if (child.text.toUpperCase().indexOf(params.term.toUpperCase()) == 0) {
                filteredChildren.push(child);
            }
        });

        // If we matched any of the timezone group's children, then set the matched children on the group
        // and return the group object
        if (filteredChildren.length) {
            var modifiedData = $.extend({}, data, true);
            modifiedData.children = filteredChildren;

            // You can return modified objects from here
            // This includes matching the `children` how you want in nested data sets
            return modifiedData;
        }

        // Return `null` if the term should not be displayed
        return null;
    }
</script>