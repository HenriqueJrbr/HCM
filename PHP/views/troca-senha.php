<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <link rel="shortcut icon" type="image/x-icon" href="./assets/images/favicon.ico">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Sistema de Gestão de Acesso </title>

    <!-- Bootstrap -->
    <link href="<?php echo URL ?>/assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?php echo URL ?>/assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="<?php echo URL ?>/assets/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="<?php echo URL ?>/assets/vendors/animate.css/animate.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="<?php echo URL ?>/assets/build/css/custom.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <style type="text/css">
      
    .login_content form input[type=submit] {
      float: left;
      margin-left: 0;
    }
   
    .login {
    background: #2A3F54;
    }
    </style>
  </head>

  <body class="login">
    <div>

      <div class="login_wrapper">
        <div class="animate form login_form">
         
            <form method="POST">

              <img src="<?php echo URL ?>/assets/images/logo_sga_200px.png"/>

              <?php echo $this->helper->alertMessage(); ?>
              <div class="row">
                  <div class="col-md-12">
                  		<label style="color: #FFFFFF">Senha atual</label>
                      <input type="password" class="form-control verificaSenhaAtual" name="senhaAtual" id="senhaAtual" placeholder="" required="" />
                       <span id="retorno2" style="color: red"></span>
                  </div>
                  <div class="col-md-12">
                      <label style="color: #FFFFFF">Nova senha</label>
                      <input type="password" class="form-control verificaSenha" name="novaSenha" id="novaSenha" placeholder="" required="" />
                  </div>
                  <div class="col-md-12">
                      <label style="color: #FFFFFF">Confirme a nova senha</label>
                      <input type="password" class="form-control verificaSenha" name="novaSenha2" id="novaSenha2" placeholder="" required="" />
                      <span id="retorno" style="color: red"></span>
                  </div>
              </div>
              <br>
               <div class="row">
                  <div class="col-md-12">
                    <input type="submit" name="acessar" id="acessar" class="btn btn-default form-control" value="Continuar" />
                  </div>
              </div>
            </form>
       
        </div>
      </div>
    </div>
  </body>
</html>

<script type="text/javascript">
$(document).ready(function(){
   $(".verificaSenha").change(function(){
    var senha1 = $("#novaSenha").val();
    var senha2 = $("#novaSenha2").val();
    var atual = $("#senhaAtual").val();
    
    if(senha1 != atual){
      if(senha1 != "" && senha2 != ""){
        if(senha1 == senha2){
          $("#retorno").html("");
          $("#acessar").attr("disabled",false);
        }else{
          $("#retorno").html("Nova senha não confere");
          $("#acessar").attr("disabled",true);
        }
      }
    }else{
      $("#retorno").html("Nova senha não pode ser igual a atual");
      $("#acessar").attr("disabled",true);
    }
   
  });

  $(".verificaSenhaAtual").change(function(){
    $.ajax({
        type: "POST",
        url: "/Login/ajaxValidaSenhaAtual",
        data:'senha='+$(this).val(),
        beforeSend: function(){           
        },
        success: function(data){
           if(data != '1'){
            $("#retorno2").html("Senha atual não confere");
            $("#acessar").attr("disabled",true);
            $(".verificaSenha").attr("disabled",true);
           }else{
              $("#retorno2").html("");
              $("#acessar").attr("disabled",false);
              $(".verificaSenha").attr("disabled",false);
           }
          
        }
    });
  });


});
function novaSenha() {
  window.location.assign("/Login");

 
}
</script>
