

$(document).ready(function(){

  $('#usuarioReferencia,#usuarioSolicitante,#usuarioSolicProg,#responsavel,#responsavelEdit,#idTotvs').select2();
  $(".aprovaGestor").hide();

  if($("#fluxoAtividade").val() == "2"){
    $("#usuarioReferencia").attr("disabled","disabled");
    $("#usuarioSolicitante").attr("disabled","disabled");
    $(".aprovaGestor").show();
  }

if($("#fluxoAtividade").val() == "3"){
    $("#aprovaGestor").attr("disabled","disabled");
    $("#obsGestor").attr("disabled","disabled");
    $(".aprovaGestor").show();
}

if($("#fluxoAtividade").val() == "0"){

  $("#usuarioReferencia").change(function(){
    var idUsr = $("#usuarioReferencia").val();

    $.ajax({
        type: "POST",
        url: url+"fluxo/ajaxCarregaDadosUsr",
        data:'id='+idUsr,
        beforeSend: function(){           
        },
        success: function(data){
            var dados = JSON.parse(data);
         $("#nomeUsuarioReferencia").val(dados[3]);
         $("#gestorUsrReferencia").val(dados[5]);
        }
    });

    $.ajax({
        type: "POST",
        url: url+"fluxo/ajaxCarregaGrupo",
        data:'id='+idUsr,
        beforeSend: function(){           
        },
        success: function(data){
            var dados = JSON.parse(data);
            var html = '';
            for(var i = 0;i<dados.length;i++){
                html += "<tr>";
                html += "<td><input type='text' name='grupo[]' class='form-control'  style='width:100%'  value='"+dados[i].idLegGrupo+"' readonly ></td>";
                html += "<td><input type='text' name='desc[]' class='form-control '  style='width:100%'  value='"+dados[i].descAbrev+"' readonly></td>";
                html += "<td><input type='text' name='gestor[]' class='form-control' style='width:100%' value='"+dados[i].nomeGestor+"' readonly><input type='text' name='idGestor[]' id='idGestor___"+i+"' class='form-control' style='width:100%' value='"+dados[i].idGestor+"' readonly></td>";

                html += "</tr>";
            }

            $("#carregaGrupo").html(html);
             $('.tabelaGrupoReferencia').DataTable({
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

    $.ajax({
        type: "POST",
        url: url+"fluxo/ajaxCarregaPrograma",
        data:'id='+idUsr,
        beforeSend: function(){           
        },
        success: function(data){
            var dados = JSON.parse(data);
            var html = '';
            for(var i = 0;i<dados.length;i++){
                html += "<tr>";
                    html += "<td>"+dados[i].idLegGrupo+"</td>";
                    html += "<td>"+dados[i].descAbrev+"</td>";
                    html += "<td>"+dados[i].cod_programa+"</td>";
                    html += "<td>"+dados[i].descricao_programa+"</td>";
                html += "</tr>";
            }
            $("#carregaPrograma").html(html);
            
            var table = $('.tabelaProgReferencia').DataTable({
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

    setTimeout(function(){ concatenaDesc(); }, 100);

    
  });
  

   $("#usuarioSolicitante").change(function(){
    var idUsr = $("#usuarioSolicitante").val();

    $.ajax({
        type: "POST",
        url: url+"fluxo/ajaxCarregaDadosUsr",
        data:'id='+idUsr,
        beforeSend: function(){           
        },
        success: function(data){
            var dados = JSON.parse(data);
            
         $("#nomeUsuarioSolicitante").val(dados[3]);
         $("#gestorUsrSolicitante").val(dados[5]);
         $("#idGestorSoclicitante").val(dados['idGestor']);


        }
    });
  });

}
});


function concatenaDesc(){

    var arr = [] ;
    $('input[id^="idGestor___"]').each(function(x){
        var context = $(this);

        var linha = context.attr('id').split("___")[1];
        
        if(arr.indexOf($("#idGestor___" + linha).val()) === -1){
            arr.push($("#idGestor___" + linha).val());  
        }  
    });

    $("#aprovadoresGrupo").val(arr);
}