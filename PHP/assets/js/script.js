//var url = "http://localhost/sga/";
//var url = "http://162.144.118.90:84/sga_v2/";
//var url = 'http://dev.appsga.com.br/'
function loading(){
    $('#load').css('display','none');
}
function loadingPagia(){
    $('#load').css('display','block');
}
$(document).ready(function(){

$("#usuario").change(function(){
    var $usr = $("#usuario").val();

    $.ajax({
        type: "POST",
        url: url+"ConfiguracaoSga/ajaxValidaUsrSga",
        data:'$usr='+$usr,
        beforeSend: function(){           
        },
        success: function(data){

            if(data == 1 || data == true){
                $("#usuario").css("border-color","red");
                $("#validaUsr").html("Usuário ja existe");
                jQuery('form#addUsuario').submit(function(){ return false; });
            }else{
                jQuery('form#addUsuario').submit(function(){ return true; });
                $("#usuario").css("border-color","green");
                $("#validaUsr").html("");

            }
  
   
        }
    });
});


$("#usuarioSolicProg").change(function(){
    var idUsr = $("#usuarioSolicProg").val();

    $.ajax({
        type: "POST",
        url: url+"Fluxo/ajaxCarregaDadosUsr",
        data:'id='+idUsr,
        beforeSend: function(){           
        },
        success: function(data){
            var dados = JSON.parse(data);
  
         $("#nomeUsuarioSolicProg").val(dados[3]);
         $("#gestorUsrSolicProg").val(dados[5]);
        }
    });

    $.ajax({
        type: "POST",
        url: url+"Fluxo/ajaxCarregaGrupo",
        data:'id='+idUsr,
        beforeSend: function(){           
        },
        success: function(data){
            var dados = JSON.parse(data);
            var html = '';
            for(var i = 0;i<dados.length;i++){
                html += "<tr>";
                html += "<td><input type='text' name='grupo[]' class='form-control'  value='"+dados[i].idLegGrupo+"' readonly ></td>";
                html += "<td><input type='text' name='desc[]' class='form-control '  value='"+dados[i].descAbrev+"' readonly></td>";
                html += "<td><input type='text' name='gestor[]' class='form-control' value='"+dados[i].nomeGestor+"' readonly></td>";
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
        url: url+"Fluxo/ajaxCarregaPrograma",
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
            console.log(html);
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
  });


    $('.tabela').DataTable( {
        "language": {
            "lengthMenu": "Mostrar _MENU_ registros",
            "zeroRecords": "Nenhum registro encontrado",
            "info": "Página _PAGE_ de _PAGES_",
            "infoEmpty": "Nenhum registro para ser exibido",
            "search":"Pesquisar:",
            "paginate": {
                    "first":      "Primeiro",
                    "last":       "Último",
                    "next":       "Próximo",
                    "previous":   "Anterior"
                },
            //"infoFiltered": "(Filtro de _MAX_ registro total)"
            "infoFiltered": ""
        }
    });

    $('a[data-confirm]').click(function(ev){
        var href = $(this).attr('href');
        if(!$('#confirm-delete').length){
            $('body').append('<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header bg-danger text-white">EXCLUIR<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div><div class="modal-body">Tem certeza de que deseja excluir está função?</div><div class="modal-footer"><button type="button" class="btn btn-success" data-dismiss="modal">Cancelar</button><a class="btn btn-danger text-white" id="dataComfirmOK">Apagar</a></div></div></div></div>');
        }
        $('#dataComfirmOK').attr('href', href);
        $('#confirm-delete').modal({show: true});
        return false;
        
    });


    

  




//cadastro de Processo
 $("#processoCorrelato").keyup(function(){
         var id  = $(this).val();
        $.ajax({
            type: "POST",
            url: url+"Matriz/ajaxaCarregaProcessoCorrelato",
            data:'codProdCorrelato='+id,
                beforeSend: function(){
                    $("#processoCorrelato").css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
                },
                success: function(data){
                    $("#suggesstion-box2").show();
                    $("#country-list2").html(data);
                 
                }
        });
    });
    $("#codRisco").keyup(function(){
         var id  = $(this).val();
        $.ajax({
            type: "POST",
            url: url+"Matriz/ajaxCarregaRisco",
            data:'codRisco='+id,
                beforeSend: function(){
                    $("#codRisco").css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
                },
                success: function(data){
                    $("#suggesstion-box").show();
                    $("#country-list").html(data);
                 
                }
        });
    });

    $("#grupoProcesso").keyup(function(){
         var id  = $(this).val();
        $.ajax({
            type: "POST",
            url: url+"Matriz/ajaxCarregaGrupoProcesso",
            data:'grupoProcesso='+id,
                beforeSend: function(){
                    $("#grupoProcesso").css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
                },
                success: function(data){
                    $("#suggesstion-box2").show();
                    $("#country-list2").html(data);
                }
        });
    });

    $("#grauRisco").keyup(function(){
         var id  = $(this).val();
        $.ajax({
            type: "POST",
            url: url+"Matriz/ajaxCarregaGrauRisco",
            data:'grauRisco='+id,
                beforeSend: function(){
                    $("#grauRisco").css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
                },
                success: function(data){
                    $("#suggesstion-box3").show();
                    $("#country-list3").html(data);
                }
        });
    });
//Funcao da tela matriz/cadastroDeRisco
    $("#grauRiscoEita").keyup(function(){

        var id  = $(this).val();
        $.ajax({
            type: "POST",
            url: url+"Matriz/ajaxCarregaGrauRiscoEditaRisco",
            data:'grauRisco='+id,
                beforeSend: function(){
                    $("#grauRiscoEita").css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
                },
                success: function(data){
                    $("#suggesstion-box").show();
                    $("#country-list").html(data);
                }
        });
    });
    $("#cadastraGrauRisco").keyup(function(){

        var id  = $(this).val();
        $.ajax({
            type: "POST",
            url: url+"Matriz/ajaxCarregaGrauRiscoCadastroRisco",
            data:'grauRisco='+id,
                beforeSend: function(){
                    $("#cadastraGrauRisco").css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
                },
                success: function(data){
                    $("#suggesstion-box2").show();
                    $("#country-list2").html(data);
                }
        });
    });
    $("#planoMedigacao").keyup(function(){

        var id  = $(this).val();
        $.ajax({
            type: "POST",
            url: url+"Matriz/ajaxCarregaPlanoMedigacao",
            data:'idMedigacao='+id,
                beforeSend: function(){
                    $("#planoMedigacao").css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
                },
                success: function(data){
                    //console.log(data);
                    var dados = JSON.parse(data);
                    console.log(dados);
                    var html = "";
                    for(var i = 0;i<dados.length;i++){
                      html += '<li onclick="cadastroPlanoMidigacao('+"'"+dados[i].idMitigacao +"'"+','+"'"+dados[i].mitigacao +"'"+')">'+dados[i].mitigacao+'</li>';
                    }
        
                    $("#suggesstion-box3").show();
                    $("#country-list3").html(html);
                    console.log(html);
                    
                }
        });
    });



    
// FIM Funcao da tela matriz/CadastroDeRisco

    $("#grauCorrelato").keyup(function(){
         var id  = $(this).val();
        $.ajax({
            type: "POST",
            url: url+"Matriz/ajaxCarregaGrauRiscoCorrelato",
            data:'grauCorrelato='+id,
                beforeSend: function(){
                    $("#grauCorrelato").css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
                },
                success: function(data){
                    $("#suggesstion-box3").show();
                    $("#country-list3").html(data);
                }
        });
    });

     $("#processoPrograma").keyup(function(){
         var id  = $(this).val();
         $.ajax({
            type: "POST",
            url: url+"Matriz/ajaxCarregaProgProcesso",
            data:'idProg='+id,
                beforeSend: function(){
                    $("#processoPrograma").css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
                },
                success: function(data){
                    $("#suggesstion-box").show();
                    $("#country-list").html(data);
                }
        });
    });

//FIM cadastro de PRocesso

    $("#programas").keyup(function(){
         var id  = $(this).val();
        $.ajax({
            type: "POST",
            url: url+"Programas/ajaxCarregaProg",
            data:'idProg='+id,
                beforeSend: function(){
                    $("#programas").css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
                },
                success: function(data){
                    $("#suggesstion-box").show();
                    $("#country-list").html(data);     
                }
        });
    });
    $("#pesquisaGestor").keyup(function(){
         var id  = $(this).val();
         console.log(id);
        $.ajax({
            type: "POST",
            url: url+"ExposicaoRisco/ajaxCarregaGestor",
            data:'idGestor='+id,
                beforeSend: function(){
                    $("#pesquisaGestor").css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
                },
                success: function(data){
                    $("#suggesstion-box").show();
                    $("#country-list").html(data);
                }
        });
    });
    $("#pesquisaUsuario").keyup(function(){
         var id  = $(this).val();
        if($("#pesquisaGestorId").val() != ""){
            var idGestor = $("#pesquisaGestorId").val() 
            $.ajax({
                type: "POST",
                url: url+"ExposicaoRisco/ajaxCarregaUsuario",
                data:{idUsuario:id,idGestor:idGestor},
                    beforeSend: function(){
                        $("#pesquisaUsuario").css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
                    },
                    success: function(data){
                        $("#suggesstion-box2").show();
                        $("#country-list2").html(data);
                    }
            });

        }else{
            $.ajax({
                type: "POST",
                url: url+"ExposicaoRisco/ajaxCarregaUsuario",
                data:{idUsuario:id,idGestor:""},
                    beforeSend: function(){
                        $("#pesquisaUsuario").css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
                    },
                    success: function(data){
                        $("#suggesstion-box2").show();
                        $("#country-list2").html(data);
                    }
            });
        }
       
    });



    //TELA DE MITIGACAO

    $("#codRiscoMitiga").keyup(function(){
        var mitigacao = $("#codRiscoMitiga").val();

        $.ajax({
            type: "POST",
            url: url+"Matriz/ajaxCarregaRiscoMitiga",
            data:'mitigacao='+mitigacao,
                beforeSend: function(){
                    $("#codRiscoMitiga").css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
                },
                success: function(data){
                    var dados = JSON.parse(data);


                    var html = "";
                    for(var i = 0;i<dados.length;i++){
                      html += '<li onclick="cadastroRicsoMitiga('+"'"+dados[i].idMtzRisco +"'"+','+"'"+dados[i].codRisco +"'"+')">'+dados[i].codRisco+'</li>';
                    }
                    $("#suggesstion-box3").show();
                    $("#country-list3").html(html); 
                }
        });
    });

    //FIM TELA DE MITIGACAO



});



function cadastroRicsoMitiga(id,codRisco){
    $("#suggesstion-box3").hide();
    $("#idCodRiscoMitiga").val(id);
    $("#codRiscoMitiga").val(codRisco);
}


function excluirGrupoProceso(idGrpProcesso,descricao){
    var confirma = confirm("Deseja realmente exluir o grupo de processo ? "+descricao);
    if(confirma == true){
        $.ajax({
            type: "POST",
            url: url+"Matriz/ajaxExcluirGrupoProcesso",
            data:'idGrpProcesso='+idGrpProcesso,
                beforeSend: function(){
                  
                },
                success: function(data){
                    
                    window.location.href=url+'Matriz/cadastroGrupoProcesso/';
                }
        });
     }
}
function excluirProProcesso(idAppProcesso,descricao,idProcesso){
    var confirma = confirm("Deseja realmente exluir este programa ? "+descricao);
    if(confirma == true){
        $.ajax({
            type: "POST",
            url: url+"Matriz/ajaxExcluirProgProcesso",
            data:'idAppProcesso='+idAppProcesso,
                beforeSend: function(){
                  
                },
                success: function(data){
                    
                    window.location.href=url+'Matriz/dados_Processo/'+idProcesso;
                }
        });
     }
}
function excluirProcessoCoorelato(idCorrelacao,codRisco,idProcesso){    
    var confirma = confirm("Deseja realmente exluir este processo ? "+idCorrelacao);
    
    if(confirma == true){
        loadingPagia();
        $.ajax({
            type: "POST",
            url: url+"Matriz/ajaxExcluirProcessoCoorelato",
            data:'idCorrelacao='+idCorrelacao,
                beforeSend: function(){
                  
                },
                success: function(data){                   
                    window.location.href=url+'Matriz/dados_Processo/'+idProcesso;
                }
        });
     }
}

function excluirProceso(idProcesso,codRisco,descProcesso){
    var confirma = confirm("Deseja realmente exluir este processo ? "+codRisco+" - "+descProcesso);
    if(confirma == true){
        loadingPagia();
        $.ajax({
            type: "POST",
            url: url+"Matriz/ajaxExcluiProcesso",
            data:'idProcesso='+idProcesso,
                beforeSend: function(){
                  
                },
                success: function(data){   
                    window.location.href=url+'Matriz/cadastroProcesso/';
                }
        });
     }

}

function editaArea(id,descricao,responsavel){
    $("#responsavelEdit").val(responsavel);
    $("#areaEdit").val(descricao);
    $("#idAreaEdit").val(id);
}
function copiaDataInicio(){
    $("#dataFimInicio").val($("#dataInicio").val());
}
function copiaDataFim(){
    $("#dataFim").val($("#dataInicioFim").val());
}
function copiaSolicitante(){
    $("#solicitanteFim").val($("#solicitante").val());
}
function aprovadorFim(){
    $("#aprovadorFim").val($("#aprovador").val());
}
function copiaUsuario(){
    $("#usuarioFim").val($("#usuario").val());
}
function copiaGrupo(){
    $("#grupoFim").val($("#grupo").val());
}
function copiaPrograma(){
    $("#programaFim").val($("#programa").val());
}

function editaGrau(id,descricao,background,texto){
    $("#idModal").val(id);
    $("#descricaoModal").val(descricao);
    $("#backgroundModal").val(background);
    $("#textoModal").val(texto);
}
function carregaModalGrupo(idGrpProcesso,descricao){
    $("#descricaoGrupoModal").val(descricao);
    $("#idDescricaoGrupoModal").val(idGrpProcesso);
}
function carregaProcessoCorrelatos(idPrcesso,descProcesso){
    $("#idProcessoCorrelato").val(idPrcesso);
    $("#processoCorrelato").val(descProcesso);
    $("#suggesstion-box2").hide();
}
function carregaMtzGrauRiscoCorelato(idGrauRisco,descricao){
    $("#grauCorrelato").val(descricao);
    $("#idGrauCorrelato").val(idGrauRisco);
    $("#suggesstion-box3").hide();
}
function carregaProg(prog){
    $("#programas").val(prog);
    $("#codProgramas").val(prog);
    $("#suggesstion-box").hide();
}
function carregaProgProcesso(idprograma,cod_programa,descricao_programa){
    $("#processoPrograma").val(cod_programa);
    $("#processoProgramaDesc").val(descricao_programa);
    $("#idProcessoPrograma").val(idprograma);
    $("#suggesstion-box").hide();
}
function carregaGestorPesq(codGestor){
    $("#pesquisaGestor").val(codGestor);
    $("#pesquisaGestorId").val(codGestor);
    $("#suggesstion-box").hide();
}
function carregaMtzGrupoPocesso(idGrpProcesso,descricao){
    $("#codGrupoProcesso").val(idGrpProcesso);
    $("#grupoProcesso").val(descricao);
    $("#suggesstion-box2").hide();
}
function carregaMtzGrauRisco(idGrauRisco,descricao){
    $("#codGrauRisco").val(idGrauRisco);
    $("#grauRisco").val(descricao);
    $("#suggesstion-box3").hide();
}
function carregaUsuarioPesq(codGestor){
    $("#pesquisaUsuario").val(codGestor);
    $("#pesquisaUsuarioId").val(codGestor);
    $("#suggesstion-box2").hide();
}
function editEmpresa(idEmpresa,idLegEmpresa,razaoSocial,cnpj,matriz){
    $("#idTotvs").val(idLegEmpresa);
    $("#idEmpresa").val(idEmpresa);
    $("#razaoSocial").val(razaoSocial);
    $("#cnpj").val(cnpj);
}
function carregaMtzRisco(idMtzRisco,codRisco,descricao){
    $("#codRisco").val(codRisco);
    
    $("#codIdRisco").val(idMtzRisco);

     $.ajax({
        type: "POST",
        url: url+"Matriz/ajaxCarregaRiscoDesc",
        data:'idMtzRisco='+idMtzRisco,
            beforeSend: function(){
            },
            success: function(data){
                var dados = JSON.parse(data);
                $("#descricaoRisco").val(dados.descricao);
             
            }
    });


    $("#suggesstion-box").hide();
}
function editManut(idManut,descricao,cod_funcao){
    $("#modalid").val(idManut);
    $("#modaldescricao").val(descricao);
    $("#modalfuncao").val(cod_funcao);

}

function abrirModal(dados,retina){
    var arrayDados = dados.split("|");
    table = $('.tabela2').DataTable();
    table.destroy();
    var html = "";
    for(var i=0;i<arrayDados.length;i++){
        var valor = arrayDados[i].replace(";","  -  ");
        html += "<tr>";
            html += "<td>"+valor+"</td>";
        html += "</tr>";
    }
    $("#modalApresentaPrograma").html(html);
    $("#modalTopo").text(retina);
    $("#myModal").modal();

    var table = $('.tabela2').DataTable( {
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

function abrirModalModulo(dados,rotina,modulo,usuario){
    var arrayDados = dados.split("|");
    table = $('.tabela2').DataTable();
    table.destroy();
    var html = "";
    for(var i=0;i<arrayDados.length;i++){
        var valor = arrayDados[i].replace(";","  -  ");
        html += "<tr>";
            html += "<td>"+valor+"</td>";
        html += "</tr>";
    }
    var dados = "Modulo: "+modulo+" Usuário: "+usuario+" Rotina: "+rotina
    $("#modalApresentaProgramamodulo").html(html);
    $("#modalTopo").text(dados);
    $("#myModal2").modal();

    var table = $('.tabela2').DataTable( {
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



function excluiManut(idManut,descricaoManut){
   
    var confirma = confirm("Favor aperte OK para excluir a função "+descricaoManut);
    if(confirma == true){
        $.ajax({
            type: "POST",
            url: url+"Manutencao/funcao/ajaxExcluiFuncao",
            data:'idManut='+idManut,
                beforeSend: function(){
                  
                },
                success: function(data){
                    alert(data);
                    
                }
        });
     }
}


//FUNCAO TELA DE MITIGACAO

function excluirRiscoMidigacao(id){

     $('#excluirRiscoMitiga___'+id).click(function(ev){

        if(!$('#confirm-delete___'+id).length){
            $('body').append('<div class="modal fade confirm-delete" id="confirm-delete___'+id+'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header bg-danger text-white">EXCLUIR<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div><div class="modal-body">Tem certeza de que deseja excluir este Risco ?</div><div class="modal-footer"><button type="button" class="btn btn-success" data-dismiss="modal">Cancelar</button><a class="btn btn-danger text-white dataComfirmOK" id="dataComfirmOK___'+id+'">Apagar</a></div></div></div></div>');
        }

        $('#dataComfirmOK___'+id).click(function(){
            
            var idMitigacao = $("#idMitigacao").val();
            carregaTabelaRiscoMitiga(idMitigacao);
            $('#confirm-delete___'+id).modal('hide');
        });
        $('#confirm-delete___'+id).modal({show: true});
        return false;
    });
}

function carregaMidigacao(idMitigacao){
      $.ajax({
        type: "POST",
        url: url+"Matriz/ajaxCarregaMitigacao",
        data:'idMitigacao='+idMitigacao,
            beforeSend: function(){
            },
            success: function(data){
                var dados = JSON.parse(data);
                $("#mitigacaoEdit").val(dados[1]);
                $("#descMedigacaoEdit").val(dados[2]);
                $("#idMitigacao").val(dados[0])
                 

                $.ajax({
                    type: "POST",
                    url: url+"Matriz/ajaxCarregaMitigacaoDocumento",
                    data:'idMitigacao='+dados[0],
                        beforeSend: function(){
                        },
                        success: function(data2){
                            var dados2 = JSON.parse(data2);
                            var html = '';
                           for(i = 0;i<dados2.length;i++){
                                html += "<tr>";
                                    html += "<td>"+dados2[i].nomeArquivo+"</td>";
                                    html += "<td><a href='"+url+"matriz/ajaxExluiArquivcoMidigacao/"+dados2[i].idArquivo+"/"+dados2[i].codArquivo+"' class='btn btn-danger btn-xs' data-confirm='Tem certeza de que deseja excluir esta função?'>Excluir Documento</a><button type='button' id='abrirDocMidigacao' class='btn btn-success btn-xs' onclick='window.open("+'"'+url+'arquivos/'+dados2[i].codArquivo+'"'+")' >Visualizar Documento</button></td>";
                                html += "</tr>";   
                           }

                           $("#bodyDocMedigacao").html(html);

                            $('a[data-confirm]').click(function(ev){
                                var href = $(this).attr('href');
                                if(!$('#confirm-delete').length){
                                    $('body').append('<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header bg-danger text-white">EXCLUIR<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div><div class="modal-body">Tem certeza de que deseja excluir este arquivo ?</div><div class="modal-footer"><button type="button" class="btn btn-success" data-dismiss="modal">Cancelar</button><a class="btn btn-danger text-white" id="dataComfirmOK">Apagar</a></div></div></div></div>');
                                }
                                $('#dataComfirmOK').attr('href', href);
                                $('#confirm-delete').modal({show: true});
                                return false;
                            });

                        }
                });

                carregaTabelaRiscoMitiga(idMitigacao);

            }
    });
     
}
// TELA DE MITIGACAO
//FUNCAO DA TELA matriz/cadastroDeRisco

function carregaMtzGrauRiscoEditaRisco(idGrauRisco,descricao){
    $("#grauRiscoEita").val(descricao);
    $("#codDrauRiscoEita").val(idGrauRisco);
    $("#suggesstion-box").hide();
}
function carregaMtzGrauRiscoCadastroRisco(idGrauRisco,descricao){
    $("#cadastraGrauRisco").val(descricao);
    $("#codCadastraGrauRisco").val(idGrauRisco);
    $("#suggesstion-box2").hide();
}
function cadastroPlanoMidigacao(id,mitigacao){
    $("#planoMedigacao").val(mitigacao);
    $("#idPlanoMedigacao").val(id);
    $("#suggesstion-box3").hide();
}

//FIM FUNCAO DA TELA matriz/cadastroDeRisco




