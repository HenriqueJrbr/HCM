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
            <a class="hiddenanchor" id="signup"></a>
            <a class="hiddenanchor" id="signin"></a>

            <div class="login_wrapper">
                <div class="animate form login_form">
                    <section class="login_content">
                        <form method="POST">

                            <img src="<?php echo URL ?>/assets/images/logo_sga_200px.png" width="100"/>

                            <?php echo $this->helper->alertMessage(); ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="text" class="form-control" name="usuario" placeholder="Usuário" required="" />
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <input type="password" class="form-control" name="senha" placeholder="Senha" required="" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-danger form-control"  id="recuperaSenha" name="recuperaSenha" onclick="novaSenha()">Esqueceu a senha?</button>
                                </div>
                                <div class="col-md-6">
                                    <input type="submit" name="acessar" class="btn btn-success form-control" value="Acessar"/>
                                </div>                 
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </body>
</html>
<script type="text/javascript">
    function novaSenha() {
        window.location.assign("<?php echo URL; ?>/Login/novaSenha")
    }
</script>
