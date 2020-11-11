<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <link rel="shortcut icon" type="image/x-icon" href="/assets/images/favicon.ico">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Sistema de Gestão de Acessos </title>
    <!-- Bootstrap -->
    <link href="<?php echo URL ?>/assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?php echo URL ?>/assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="<?php echo URL ?>/assets/vendors/nprogress/nprogress.css" rel="stylesheet">

    <link href="<?php echo URL ?>/assets/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

    <link href="<?php echo URL ?>/assets/vendors/mjolnic-bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css"
          rel="stylesheet">
    <!-- bootstrap-wysiwyg -->
    <link href="<?php echo URL ?>/assets/vendors/google-code-prettify/bin/prettify.min.css" rel="stylesheet">
    <link href="<?php echo URL ?>/assets/build/css/select2.min.css" rel="stylesheet"/>




    <style type="text/css">
        .bg-danger {
            background-color: #2A3F54;
            color: #FFFFFF;
        }

        .modal-header {
            padding: 15px;
            border-bottom: 1px solid #e5e5e5;
            background-color: #2A3F54;
            color: #FFFFFF;
        }

        .tabela tbody tr:hover {
            background-color: #EDEDED;
        }

        .table thead tr {
            background-color: #2A3F54;
            color: #FFF
        }

        tr {
            cursor: pointer;
        }

        #load {
            position: fixed;
            display: block;
            z-index: 9999;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            margin: auto;
            padding-top: 200px;
        }

        nav-tabs > li.active > a, .nav-tabs > li.active > a:focus, .nav-tabs > li.active > a:hover {
            color: #FFFFFF;
            cursor: default;
            background-color: #FF8000;
            border: 1px solid #ddd;
            border-bottom-color: transparent;
        }

        .breadcrumb {
            padding: 10px 15px;
            margin-bottom: 20px;
            list-style: none;
            background-color: #fff;
            border-radius: 4px;
            margin-left: -30px;
            margin-right: -30px;
            border-top: none;
            border-bottom: 1px solid #D9DEE4;
            margin-top: -10px;
        }

        /*.top_nav .navbar-right li{height: 50px !important;}*/

        /*
         * Component: Control sidebar. By default, this is the right sidebar.
         */
        .control-sidebar-bg {
            position: fixed;
            z-index: 1000;
            bottom: 0;

        }
        .control-sidebar-bg,
        .control-sidebar {
            top: 50px;
            border-top: solid 1px #405060;
            right: -230px;
            width: 230px;
            -webkit-transition: right 0.3s ease-in-out;
            -o-transition: right 0.3s ease-in-out;
            transition: right 0.3s ease-in-out;
        }
        .control-sidebar {
            position: absolute;
            padding-top: 0;
            z-index: 1010;
        }
        @media (max-width: 767px) {
            .control-sidebar {
                padding-top: 100px;
            }
        }
        .control-sidebar > .tab-content {
            padding: 10px 15px;
        }
        .control-sidebar.control-sidebar-open,
        .control-sidebar.control-sidebar-open + .control-sidebar-bg {
            right: 0;
        }
        .control-sidebar-hold-transition .control-sidebar-bg,
        .control-sidebar-hold-transition .control-sidebar,
        .control-sidebar-hold-transition .content-wrapper {
            transition: none;
        }
        .control-sidebar-open .control-sidebar-bg,
        .control-sidebar-open .control-sidebar {
            right: 0;
        }
        @media (min-width: 768px) {
            .control-sidebar-open .content-wrapper,
            .control-sidebar-open .right-side,
            .control-sidebar-open .main-footer {
                margin-right: 230px;
            }
        }
        .fixed .control-sidebar {
            position: fixed;
            height: 100%;
            overflow-y: auto;
            padding-bottom: 50px;
        }
        .nav-tabs.control-sidebar-tabs > li:first-of-type > a,
        .nav-tabs.control-sidebar-tabs > li:first-of-type > a:hover,
        .nav-tabs.control-sidebar-tabs > li:first-of-type > a:focus {
            border-left-width: 0;
        }
        .nav-tabs.control-sidebar-tabs > li > a {
            border-radius: 0;
        }
        .nav-tabs.control-sidebar-tabs > li > a,
        .nav-tabs.control-sidebar-tabs > li > a:hover {
            border-top: none;
            border-right: none;
            border-left: 1px solid transparent;
            border-bottom: 1px solid transparent;
        }
        .nav-tabs.control-sidebar-tabs > li > a .icon {
            font-size: 16px;
        }
        .nav-tabs.control-sidebar-tabs > li.active > a,
        .nav-tabs.control-sidebar-tabs > li.active > a:hover,
        .nav-tabs.control-sidebar-tabs > li.active > a:focus,
        .nav-tabs.control-sidebar-tabs > li.active > a:active {
            border-top: none;
            border-right: none;
            border-bottom: none;
        }
        @media (max-width: 768px) {
            .nav-tabs.control-sidebar-tabs {
                display: table;
            }
            .nav-tabs.control-sidebar-tabs > li {
                display: table-cell;
            }
        }
        .control-sidebar-heading {
            font-weight: 400;
            font-size: 16px;
            padding: 10px 0;
            margin-bottom: 10px;
        }
        .control-sidebar-subheading {
            display: block;
            font-weight: 400;
            font-size: 14px;
        }
        .control-sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0 -15px;
        }
        .control-sidebar-menu > li > a {
            display: block;
            padding: 10px 15px;
        }
        .control-sidebar-menu > li > a:before,
        .control-sidebar-menu > li > a:after {
            content: " ";
            display: table;
        }
        .control-sidebar-menu > li > a:after {
            clear: both;
        }
        .control-sidebar-menu > li > a > .control-sidebar-subheading {
            margin-top: 0;
        }
        .control-sidebar-menu .menu-icon {
            float: left;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            text-align: center;
            line-height: 35px;
        }
        .control-sidebar-menu .menu-info {
            margin-left: 45px;
            margin-top: 3px;
        }
        .control-sidebar-menu .menu-info > .control-sidebar-subheading {
            margin: 0;
        }
        .control-sidebar-menu .menu-info > p {
            margin: 0;
            font-size: 11px;
        }
        .control-sidebar-menu .progress {
            margin: 0;
        }
        .control-sidebar-dark {
            color: #b8c7ce;
        }
        .control-sidebar-dark,
        .control-sidebar-dark + .control-sidebar-bg {
            background: #222d32;
        }
        .control-sidebar-dark .nav-tabs.control-sidebar-tabs {
            border-bottom: #1c2529;
        }
        .control-sidebar-dark .nav-tabs.control-sidebar-tabs > li > a {
            background: #181f23;
            color: #b8c7ce;
        }
        .control-sidebar-dark .nav-tabs.control-sidebar-tabs > li > a,
        .control-sidebar-dark .nav-tabs.control-sidebar-tabs > li > a:hover,
        .control-sidebar-dark .nav-tabs.control-sidebar-tabs > li > a:focus {
            border-left-color: #141a1d;
            border-bottom-color: #141a1d;
        }
        .control-sidebar-dark .nav-tabs.control-sidebar-tabs > li > a:hover,
        .control-sidebar-dark .nav-tabs.control-sidebar-tabs > li > a:focus,
        .control-sidebar-dark .nav-tabs.control-sidebar-tabs > li > a:active {
            background: #1c2529;
        }
        .control-sidebar-dark .nav-tabs.control-sidebar-tabs > li > a:hover {
            color: #fff;
        }
        .control-sidebar-dark .nav-tabs.control-sidebar-tabs > li.active > a,
        .control-sidebar-dark .nav-tabs.control-sidebar-tabs > li.active > a:hover,
        .control-sidebar-dark .nav-tabs.control-sidebar-tabs > li.active > a:focus,
        .control-sidebar-dark .nav-tabs.control-sidebar-tabs > li.active > a:active {
            background: #222d32;
            color: #fff;
        }
        .control-sidebar-dark .control-sidebar-heading,
        .control-sidebar-dark .control-sidebar-subheading {
            color: #fff;
        }
        .control-sidebar-dark .control-sidebar-menu > li > a:hover {
            background: #1e282c;
        }
        .control-sidebar-dark .control-sidebar-menu > li > a .menu-info > p {
            color: #b8c7ce;
        }
        .control-sidebar-light {
            color: #5e5e5e;
        }
        .control-sidebar-light,
        .control-sidebar-light + .control-sidebar-bg {
            background: #f9fafc;
            border-left: 1px solid #d2d6de;
        }
        .control-sidebar-light .nav-tabs.control-sidebar-tabs {
            border-bottom: #d2d6de;
        }
        .control-sidebar-light .nav-tabs.control-sidebar-tabs > li > a {
            background: #e8ecf4;
            color: #444;
        }
        .control-sidebar-light .nav-tabs.control-sidebar-tabs > li > a,
        .control-sidebar-light .nav-tabs.control-sidebar-tabs > li > a:hover,
        .control-sidebar-light .nav-tabs.control-sidebar-tabs > li > a:focus {
            border-left-color: #d2d6de;
            border-bottom-color: #d2d6de;
        }
        .control-sidebar-light .nav-tabs.control-sidebar-tabs > li > a:hover,
        .control-sidebar-light .nav-tabs.control-sidebar-tabs > li > a:focus,
        .control-sidebar-light .nav-tabs.control-sidebar-tabs > li > a:active {
            background: #eff1f7;
        }
        .control-sidebar-light .nav-tabs.control-sidebar-tabs > li.active > a,
        .control-sidebar-light .nav-tabs.control-sidebar-tabs > li.active > a:hover,
        .control-sidebar-light .nav-tabs.control-sidebar-tabs > li.active > a:focus,
        .control-sidebar-light .nav-tabs.control-sidebar-tabs > li.active > a:active {
            background: #f9fafc;
            color: #111;
        }
        .control-sidebar-light .control-sidebar-heading,
        .control-sidebar-light .control-sidebar-subheading {
            color: #111;
        }
        .control-sidebar-light .control-sidebar-menu {
            margin-left: -14px;
        }
        .control-sidebar-light .control-sidebar-menu > li > a:hover {
            background: #f4f4f5;
        }
        .control-sidebar-light .control-sidebar-menu > li > a .menu-info > p {
            color: #5e5e5e;
        }
    </style>

    <!-- Custom styling plus plugins -->
    <!--<link href="<?php echo URL ?>/assets/build/css/custom.min.css" rel="stylesheet">-->
    <link href="<?php echo URL ?>/assets/build/css/custom.css" rel="stylesheet">
    <script src="<?php echo URL ?>/assets/vendors/jquery/dist/jquery.min.js"></script>
    <script src="<?php echo URL ?>/assets/js/Chart.min.js"></script>
        <script type="text/javascript">
            url = '<?php echo URL; ?>/';
        </script>
</head>

<body class="nav-md" onload="loading()">
<div id="load">
    <center>
        <svg class="lds-spinner" width="200px" height="200px" xmlns="http://www.w3.org/2000/svg"
             xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid"
             style="background: none;">
            <g transform="rotate(0 50 50)">
                <rect x="48" y="24" rx="9.6" ry="4.8" width="4" height="12" fill="#1ABB9C">
                    <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.9166666666666666s"
                             repeatCount="indefinite"></animate>
                </rect>
            </g>
            <g transform="rotate(30 50 50)">
                <rect x="48" y="24" rx="9.6" ry="4.8" width="4" height="12" fill="#1ABB9C">
                    <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.8333333333333334s"
                             repeatCount="indefinite"></animate>
                </rect>
            </g>
            <g transform="rotate(60 50 50)">
                <rect x="48" y="24" rx="9.6" ry="4.8" width="4" height="12" fill="#1ABB9C">
                    <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.75s"
                             repeatCount="indefinite"></animate>
                </rect>
            </g>
            <g transform="rotate(90 50 50)">
                <rect x="48" y="24" rx="9.6" ry="4.8" width="4" height="12" fill="#1ABB9C">
                    <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.6666666666666666s"
                             repeatCount="indefinite"></animate>
                </rect>
            </g>
            <g transform="rotate(120 50 50)">
                <rect x="48" y="24" rx="9.6" ry="4.8" width="4" height="12" fill="#1ABB9C">
                    <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.5833333333333334s"
                             repeatCount="indefinite"></animate>
                </rect>
            </g>
            <g transform="rotate(150 50 50)">
                <rect x="48" y="24" rx="9.6" ry="4.8" width="4" height="12" fill="#1ABB9C">
                    <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.5s"
                             repeatCount="indefinite"></animate>
                </rect>
            </g>
            <g transform="rotate(180 50 50)">
                <rect x="48" y="24" rx="9.6" ry="4.8" width="4" height="12" fill="#1ABB9C">
                    <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.4166666666666667s"
                             repeatCount="indefinite"></animate>
                </rect>
            </g>
            <g transform="rotate(210 50 50)">
                <rect x="48" y="24" rx="9.6" ry="4.8" width="4" height="12" fill="#1ABB9C">
                    <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.3333333333333333s"
                             repeatCount="indefinite"></animate>
                </rect>
            </g>
            <g transform="rotate(240 50 50)">
                <rect x="48" y="24" rx="9.6" ry="4.8" width="4" height="12" fill="#1ABB9C">
                    <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.25s"
                             repeatCount="indefinite"></animate>
                </rect>
            </g>
            <g transform="rotate(270 50 50)">
                <rect x="48" y="24" rx="9.6" ry="4.8" width="4" height="12" fill="#1ABB9C">
                    <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.16666666666666666s"
                             repeatCount="indefinite"></animate>
                </rect>
            </g>
            <g transform="rotate(300 50 50)">
                <rect x="48" y="24" rx="9.6" ry="4.8" width="4" height="12" fill="#1ABB9C">
                    <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.08333333333333333s"
                             repeatCount="indefinite"></animate>
                </rect>
            </g>
            <g transform="rotate(330 50 50)">
                <rect x="48" y="24" rx="9.6" ry="4.8" width="4" height="12" fill="#1ABB9C">
                    <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="0s"
                             repeatCount="indefinite"></animate>
                </rect>
            </g>
        </svg>
    </center>
</div>
<div class="container body">
    <div class="main_container">
        <div class="col-md-3 left_col">
            <div class="left_col scroll-view">

                <div class="navbar nav_title" style="border: 0;padding-left: 5px;padding-top: 3px">
                    <!-- <a href="index.html" class="site_title">
                         <span>SGA</span></a> -->
                    <img src="<?php echo URL ?>/assets/images/logoMenu.png" class="img-logo-menu-big">
                    <img src="<?php echo URL ?>/assets/images/logo70x70.png" class="img-logo-menu-small hide">
                </div>

               <div class="clearfix"></div>

                <!-- menu profile quick info -->
<!--                <div class="profile clearfix">-->
                    <!--<div class="profile_pic">
                <img src="<?php echo URL ?>/sga/assets/images/ngf.png" alt="..." class="img-circle profile_img">
              </div>

              <div class="profile_info" >
                 <h2><?php echo $_SESSION['empresaDesc']; ?></h2>

              </div> -->
<!--                </div>-->
                <!-- /menu profile quick info -->


<!--                <div class="profile user-info clearfix">-->
<!--                    <div class="profile_pic">-->
<!--                        <img src="--><?php //echo URL ?><!--/assets/images/usr.png" alt="..." class="img-circle profile_img">-->
<!--                    </div>-->
<!--                    <div class="profile_info">-->
<!--                        <span>Bem-Vindo:</span>-->
<!--                        <h2>--><?php //echo $_SESSION['nomeUsuario'] ?><!--</h2>-->
<!--                    </div>-->
<!--                </div>-->
                <!-- sidebar menu -->
                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                    <div class="menu_section">
                        <ul class="nav side-menu">
                            <li style="border-top: solid 1px #405060;"><a href="<?php echo URL ?>" onclick="loadingPagia()"><i class="fa fa-dashboard"></i>Dashboard<span
                                            class="fa fa-angle-right"></span></a>
                            </li>
                        </ul>
                    </div>

                    <?php
                    
                    $idUsrLogado = $_SESSION['idUsrLogado'];
                    $sql = "SELECT 
                            usr.idGrupo, 
                            usr.idUsuario,
                            grperm.idGrupo,
                            grperm.url,
                            menu.idMenu,
                            menu.descricao,
                            menu.url as menuUrl,
                            subCate.descricao as descricaoSubCategoria,
                            subCate.idSubCategoria as idSubCategoria,
                            subCate.icone as subIcone,
                            cate.idCategoria as idCategoria,
                            cate.descricao as descricaoCategoria,
                            cate.icone as iconeCategoria
                            FROM

                            z_sga_param_grupo_usuario as usr,
                            z_sga_param_grupo_permissao as grperm,
                            z_sga_param_menu as menu,
                            z_sga_param_sub_categoria as subCate,
                            z_sga_param_categoria as cate
                            where
                                usr.idGrupo = grperm.idGrupo AND
                                grperm.url = menu.idMenu AND
                                subCate.idSubCategoria = menu.idSubCategoria AND
                                cate.idCategoria = subCate.idCategoria AND
                                usr.idUsuario = '$idUsrLogado'
                                order BY descricaoCategoria,descricaoSubCategoria,descricao ";
                        
                    $sql = $this->db->query($sql);

                    $dados = array();
                    if ($sql->rowCount() > 0) {
                        $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
                    }
                    
                    $categoriaMenu = "";
                    $subCategoriaMenu = "";
                    $subCategoria = "";
                    ?>
   
                    <?php foreach ($dados as $key => $categoria): ?>
                        
                        <?php if ($categoriaMenu != $categoria['descricaoCategoria']): ?>
                            <?php $categoriaMenu = $categoria['descricaoCategoria']; ?>
                            <div class="menu_section">
                                <ul class="nav side-menu">
                                    <h3><i class="<?php echo $categoria['iconeCategoria'] ?>"></i><?php echo utf8_encode($categoriaMenu); ?></h3>
                        <?php endif; ?>

                                <?php 
                                if ($subCategoria != $categoria['descricaoSubCategoria']):
                                    $subCategoria = $categoria['descricaoSubCategoria'];
                                ?>

                                <li><a><i class="<?php echo $categoria['subIcone'] ?>"></i> <?php echo utf8_encode($subCategoria); ?> <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                <?php endif; ?> 
                                
                                        <li><a href="<?php echo URL . $categoria['menuUrl'] ?>" onclick="loadingPagia()"><?php echo utf8_encode($categoria['descricao']) ?></a></li>
                                <?php
                                $next = next($dados);
                                if (!isset($dados[$key+1]) || ($dados[$key+1]['descricaoSubCategoria'] != $subCategoria)): 
                                ?>
                                    </ul>
                                </li>
                                <?php endif; ?>

                        <?php 
                        if (!isset($dados[$key+1]) || ($categoriaMenu != $dados[$key+1]['descricaoCategoria'])): 
                        ?>
                                </ul>
                            </div>
                        <?php endif; ?>


                    <?php endforeach; ?>
                </div>

                <!-- /sidebar menu -->

                <!-- /menu footer buttons -->
                <div class="sidebar-footer">

                    <a data-toggle="tooltip" data-placement="top" title="Logout" href="<?php echo URL ?>/Login/sair">
                        <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                    </a>
                </div>
                <!-- /menu footer buttons -->
            </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">
            <div class="nav_menu">
                <nav>
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                    </div>
                    <ul class="nav navbar-nav navbar-right">
<!--                        <li>-->
<!--                            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>-->
<!--                        </li>-->
                        <li role="presentation" class="dropdown" style="/*! margin-right: -226px !important; */">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" style="padding: 7px;/*! border: none; */width: 80px;/*! float: right; *//*! margin-left: 0; */float: none;">
                                <span class="fa fa-chevron-down"></span>
                                <img src="<?php echo URL ?>/assets/images/usr.png" alt="..." class="img-circle image-profile" style="width: 63%;/*! float: right; */margin: 0 !important;padding: 0px;">
                            </a>
                            <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu" style="/*! margin-right: 226px; */">
                                <li>
                                    <span>Bem-Vindo: </span>
                                    <br><strong style="font-size: 14px"><?php echo $_SESSION['nomeUsuario'] ?></strong>
                                </li>
                            </ul>
                        </li>
                        <li role="presentation" class="dropdown">
                            <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown"
                               aria-expanded="false">
                                <?php echo $_SESSION['empresaDesc']; ?>
                            </a>
                            <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                                <li>
                                    <a>
                                        <form method="POST">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label>Empresa </label>
                                                    <select class="form-control select2" name="empresa" id="empresa">
                                                        <option value=""></option>
                                                        <?php foreach ($array as $value): ?>
                                                            <option value="<?php echo $value['idEmpresa'] ?>"><?php echo $value["razaoSocial"] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <button type="submit" name="ok"
                                                            class="btn btn-block btn-success btn-xs">Ok
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li role="presentation" class="dropdown">
                            <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-exclamation-triangle"></i>
                                <span class="badge bg-green quantAtiv"></span>
                            </a>
                            <ul id="menu1" class="dropdown-menu list-unstyled msg_list atividade" role="menu">
        
                            
                            
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
            <div class="">
                <div class="clearfix"></div>

                <div class="row">
                    <div class="col-md-12">
                        <div id="load" style="display:none">
                            <svg class="lds-spinner" width="200px" height="200px" xmlns="http://www.w3.org/2000/svg"
                                 xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 100 100"
                                 preserveAspectRatio="xMidYMid" style="background: none;">
                                <g transform="rotate(0 50 50)">
                                    <rect x="48" y="24" rx="9.6" ry="4.8" width="4" height="12" fill="#1ABB9C">
                                        <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s"
                                                 begin="-0.9166666666666666s" repeatCount="indefinite"></animate>
                                    </rect>
                                </g>
                                <g transform="rotate(30 50 50)">
                                    <rect x="48" y="24" rx="9.6" ry="4.8" width="4" height="12" fill="#1ABB9C">
                                        <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s"
                                                 begin="-0.8333333333333334s" repeatCount="indefinite"></animate>
                                    </rect>
                                </g>
                                <g transform="rotate(60 50 50)">
                                    <rect x="48" y="24" rx="9.6" ry="4.8" width="4" height="12" fill="#1ABB9C">
                                        <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s"
                                                 begin="-0.75s" repeatCount="indefinite"></animate>
                                    </rect>
                                </g>
                                <g transform="rotate(90 50 50)">
                                    <rect x="48" y="24" rx="9.6" ry="4.8" width="4" height="12" fill="#1ABB9C">
                                        <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s"
                                                 begin="-0.6666666666666666s" repeatCount="indefinite"></animate>
                                    </rect>
                                </g>
                                <g transform="rotate(120 50 50)">
                                    <rect x="48" y="24" rx="9.6" ry="4.8" width="4" height="12" fill="#1ABB9C">
                                        <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s"
                                                 begin="-0.5833333333333334s" repeatCount="indefinite"></animate>
                                    </rect>
                                </g>
                                <g transform="rotate(150 50 50)">
                                    <rect x="48" y="24" rx="9.6" ry="4.8" width="4" height="12" fill="#1ABB9C">
                                        <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s"
                                                 begin="-0.5s" repeatCount="indefinite"></animate>
                                    </rect>
                                </g>
                                <g transform="rotate(180 50 50)">
                                    <rect x="48" y="24" rx="9.6" ry="4.8" width="4" height="12" fill="#1ABB9C">
                                        <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s"
                                                 begin="-0.4166666666666667s" repeatCount="indefinite"></animate>
                                    </rect>
                                </g>
                                <g transform="rotate(210 50 50)">
                                    <rect x="48" y="24" rx="9.6" ry="4.8" width="4" height="12" fill="#1ABB9C">
                                        <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s"
                                                 begin="-0.3333333333333333s" repeatCount="indefinite"></animate>
                                    </rect>
                                </g>
                                <g transform="rotate(240 50 50)">
                                    <rect x="48" y="24" rx="9.6" ry="4.8" width="4" height="12" fill="#1ABB9C">
                                        <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s"
                                                 begin="-0.25s" repeatCount="indefinite"></animate>
                                    </rect>
                                </g>
                                <g transform="rotate(270 50 50)">
                                    <rect x="48" y="24" rx="9.6" ry="4.8" width="4" height="12" fill="#1ABB9C">
                                        <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s"
                                                 begin="-0.16666666666666666s" repeatCount="indefinite"></animate>
                                    </rect>
                                </g>
                                <g transform="rotate(300 50 50)">
                                    <rect x="48" y="24" rx="9.6" ry="4.8" width="4" height="12" fill="#1ABB9C">
                                        <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s"
                                                 begin="-0.08333333333333333s" repeatCount="indefinite"></animate>
                                    </rect>
                                </g>
                                <g transform="rotate(330 50 50)">
                                    <rect x="48" y="24" rx="9.6" ry="4.8" width="4" height="12" fill="#1ABB9C">
                                        <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="0s"
                                                 repeatCount="indefinite"></animate>
                                    </rect>
                                </g>
                            </svg>
                            <!--<img src='/sga/assets/images/load.gif'/>     -->

                        </div>
                        