<div class="col-md-12 col-sm-12 col-xs-12">
  <ol class="breadcrumb">
      <li><a href="<?php echo URL ?>/" onclick="loadingPagia()">Dashboard</a></li>          
        <li class="active">Usuários</li>
  </ol>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Usuários
                </h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-md-2">
                        <label>Ativos / Inativos</label>
                        <select class="form-control" name="ativoInativo" id="ativoInativo">
                            <option value="*">Ambos</option>
                            <option value="1" selected="true">Ativo</option>
                            <option value="0">Inativo</option>
                        </select>
                    </div>
                    <div class="col-sm-6"></div>
                </div>
                <br>
                
                <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                
                    <div class="row">
                        <div class="col-sm-12">

                            <table class="table table-striped hover table-bordered dt-responsive nowrap dataTable no-footer dtr-inline"
                                   cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info"
                                   style="width: 100%;" >
                                <thead>
                                <tr role="row">
                                    <th>#</th>
                                    <th>Usuário</th>
                                    <th>Id Totvs</th>
                                    <th>Gestor</th>
                                    <th>Função</th>
                                    <th>Quant. Instancias</th>
                                    <th>Quant. Risco</th>
                                    <th>Situação</th>
                                    <th>Ação</th>
                                </tr>
                                </thead>
                                <tbody id="datatableUser"></tbody>
                            </table>
                            <div class="teste"><center>Carregando...</center></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
$(document).ready(function(){   
    var retorno = carrega();
  $("#ativoInativo").change(function(){
    $('#datatableUser').empty(); 
     carrega();
  });
});

function carrega(){
    var table = $('.table').DataTable();
    table.destroy();
   $.ajax({
        type: "POST",
        url: url+"Usuario/ajaxCarregaDatatableUsr",
        data:'tipo='+$("#ativoInativo").val(),
        beforeSend: function(){

            loadingPagia();         
        },
        success: function(data){
            $(".teste").hide();
            var dados = JSON.parse(data);
            html = '';
            for(var i=0;i<dados.length;i++){

                if(dados[i].ativo == "1"){
                    var ativo = "Ativo";
                }else{
                    var ativo = "Inativo";
                }

                if(dados[i].gestor == "" || dados[i].gestor  == null ){
                    var gestor = "Não Cadastrado"
                }else{
                    var gestor = dados[i].gestor;
                }
                html += "<tr>";
                    html +="<td>";
                    html += dados[i].z_sga_usuarios_id
                    html +="</td>";
                    html +="<td>";
                    html += dados[i].nome_usuario
                    html +="</td>";
                    html +="<td>";
                    html += dados[i].cod_usuario
                    html +="</td>";
                    html +="<td>";
                    html += gestor
                    html +="</td>";
                    html +="<td>";
                    html += dados[i].cod_funcao
                    html +="</td>";
                    html +="<td>";
                    html += '<center><small class="badge">'+dados[i].nroInstancias+'</small></center>';
                    html +="</td>";
                    html +="<td>";
                    html += '<center><small class="badge">'+dados[i].nroRiscos+'</small></center>';
                    html +="</td>";
                    html +="<td>";
                    html += '<center><small class="badge">'+ativo+'</small></center>';
                    html +="</td>";
                    html +="<td>";
                    html += '<button type="button" class="btn btn-success btn-xs" data-toggle="modal" onclick="location.href=\''+ url + 'Usuario/dados_usuario/'+dados[i].z_sga_usuarios_id+'\',loadingPagia()">Visualizar</button>';
                    html +="</td>";
                html += "</tr>" 
            }

            $("#datatableUser").html(html);
        
            var table = $(".table").dataTable( {
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
            loading();
            return true;
        }
    });
}
</script>



