$(document).ready(function(){

// Aprovação de gestor
if($("#numAtividade").val() == "7"){
    $(".aprovador").hide();
    $(".manter").click(function(){
        var teste = $(this).is(":checked");
        var arr = [] ;

        $('input[id^="idCodGest___"]').each(function(x){
            var context = $(this);

            var linha = context.attr('id').split("___")[1];
            var manter = $("#manter___"+linha).is(":checked");

            if(manter == true && arr.indexOf($("#idCodGest___" + linha).val()) === -1){
                arr.push($("#idCodGest___" + linha).val());
            }
        });

        $("#aprovadorGrupo").val(arr);
    });


    $(".materStatus").click(function(){
        var context = $(this);
        var status = $(this).is(":checked");
        var linha = context.attr('id').split("___")[1];
        $("#manterStatus___"+linha).val(status);
    });

    setTimeout(function(){ concatenaDescRevisao(); }, 300);
    setTimeout(function(){concatenaDescRevisao(); }, 600);
}

// Aprovação de grupos
if($("#numAtividade").val() == "8"){
    $(".manter").attr("disabled","disabled");

    $('input[id^="idCodGest___"]').each(function(){
        var context = $(this);
        var linha = context.attr('id').split("___")[1];

        if($("#userAlt___"+linha).val() == $("#usrLogado").val()){
            return false;
        }

        if($("#idCodGest___"+linha).val() != $("#usrLogado").val()){
            if($("#manterStatus___"+linha).val() == "true" || $("#manterStatus___"+linha).val() == 1){
                $("#aprovacao___"+linha).attr("readonly","true");
                $("#aprovacao___"+linha).css({
                    'pointer-events': 'none',
                    'touch-action': 'none'
                });
                //$("#aprovacao___"+linha).attr("disabled","disabled");
                $("#obs___"+linha).attr("readonly","true");
            }
        }
    });
}

// Aprovação de TI
/*if($("#numAtividade").val() == "8"){
    $(".manter").attr("disabled","disabled");

    $('input[id^="idCodGest___"]').each(function(){
        var context = $(this);
        var linha = context.attr('id').split("___")[1];

        $("#aprovacao___"+linha).attr("disabled","disabled");
        $("#obs___"+linha).attr("disabled","disabled");

    });
}*/



});


$(document).ready(function () {
    $('.manter').change(function() {
        var line = $(this).attr('id').split('___');

        if($(this).is(":checked")) {
            $('#manterStatus___'+line[1]).val(1);
        }else{
            $('#manterStatus___'+line[1]).val(0);
        }

    });

    $('#btnEnviar').on('click', function () {
        // Valida se o gestor aprovou ou reprovou os registros que estão pra ele
        console.log($(".aprovador select").length)
        var vazio = false;

        $(".aprovador select").each(function () {
            if($(this).attr('readonly') == 'false' && ($(this).val() == '')){
                vazio = true;
            }
        });

        if(vazio == false){
            $('#frmRevisaoAcesso').submit();
        }else{
            alert('Favor aprovar ou reprovar os acessos.');
        }
    })
})


function manter(){
    $('input[id^="manter___"]').each(function(){
        var context = $(this);
        var linha = context.attr('id').split("___")[1];

        $("#manter___"+linha).attr("checked", "checked");
    });
}


function concatenaDescRevisao(){

    var arr = [] ;
    $('input[id^="idCodGest___"]').each(function(x){
        var context = $(this);

        var linha = context.attr('id').split("___")[1];

        if(arr.indexOf($("#idCodGest___" + linha).val()) === -1){
            arr.push($("#idCodGest___" + linha).val());
        }
    });

    $("#aprovadorGrupo").val(arr);
}