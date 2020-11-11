<div class="col-md-12 col-sm-12 col-xs-12">
  <ol class="breadcrumb">
      <li><a href="<?php echo URL ?>/" onclick="loadingPagia()">Dashboard</a></li>          
        <li class="active">Cartas de Risco</li>
  </ol>
</div>
<div class="col-md-12">
    <?php $this->helper->alertMessage(); ?>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Cartas de Riscos
                </h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">                
                <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                
                    <div class="row">
                        <div class="col-sm-12">

                            <table class="table table-striped hover table-bordered dt-responsive nowrap dataTable no-footer dtr-inline"
                                   cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info"
                                   style="width: 100%;">
                                <thead>
                                <tr role="row">
                                    <th>#</th>
                                    <th>Solicitação</th>
                                    <th>Solicitante</th>
                                    <th>Usuario</th>
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
    carrega();
});

function download(cartaRisco) {
}

function carrega(){
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: url+"Fluxo/carregaCartasRisco",
        
        beforeSend: function(){
            loadingPagia();
        },

        error: function(a, b, c) {alert(a + ' ------;' + b + ' ------;' + c);},

        success: function(data){
            $(".teste").hide();

            var dados = data;

            html = '';
            for(var i=0;i<dados.length;i++){

                html += "<tr>";
                    html +="<td>";
                    html += dados[i].id
                    html +="</td>";
                    html +="<td>";
                    html += dados[i].idSolicitacao;
                    html +="</td>";
                    html +="<td>";
                    html += dados[i].solicitante;
                    html +="</td>";
                    html +="<td>";
                    html += dados[i].usuario;
                    html +="</td>";
                    html +="<td>";
                    html += '<a class="btn btn-primary btn-xs" download="cartaDeRisco" href="' + url + '/arquivos/carta_risco/' + dados[i].cartaRisco + '">Baixar</a>';
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