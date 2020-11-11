<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <link rel="shortcut icon" type="image/x-icon" href="/assets/images/favicon.ico">
    <link rel="stylesheet" href="/assets/css/style.css">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Sistema de Gestão de Acessos </title>
    <!-- Bootstrap -->
    <link href="<?php echo URL ?>/assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?php echo URL ?>/assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?php echo URL ?>/assets/vendors/font-awesome/css/all.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="<?php echo URL ?>/assets/vendors/nprogress/nprogress.css" rel="stylesheet">

    <link href="<?php echo URL ?>/assets/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

    <link href="<?php echo URL ?>/assets/vendors/mjolnic-bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css"
          rel="stylesheet">
    <!-- bootstrap-wysiwyg -->
    <link href="<?php echo URL ?>/assets/vendors/google-code-prettify/bin/prettify.min.css" rel="stylesheet">
    <link href="<?php echo URL ?>/assets/build/css/select2.min.css" rel="stylesheet"/>

    <style type="text/css">
        .favoritado {
            color: #d7c95d !important;
        }

        .favoritoAdicionado::after {
            left: 17px !important;
        }

        .favoritoAdicionado {
            padding-left: 27px !important
        }

        .iconeFavorito {
            font-size: 18px;
            margin-top: 8px;
            float: left;
        }

        .naoFavoritado {
            color: #72869b;
        }

        .catFavoritos {
            width: 104%;
            color: rgba(255, 255, 255, .75);
            padding-left: 15px;
            letter-spacing: .5px;
            font-size: 12.5px;
            margin-bottom: 0;
            margin-top: 0;
            padding-right: 20px !important;
            text-shadow: 1px 1px #000;
            background: #213244;
            padding: 7px 12px 7px 12px;
            cursor: default;
        }

        .catFavoritos:hover {
            background: #213244 !important;
        }

        .subCat {
            font-size: 11px;
            /*color: #fff*/
        }

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

        .favorite-icon:hover {
            background: #35495d;
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

                <!-- Home -->
                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                    <div class="menu_section">
                        <ul class="nav side-menu">
                            <li style="border-top: solid 1px #405060;">
                                <a href="<?php echo URL ?>" onclick="loadingPagia()">
                                    <i class="fa fa-dashboard"></i>
                                    Home - KPI's
                                    <span class="fa fa-angle-right"></span>
                                </a>
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
                            menu.idMenu as idMenu,
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
                                group by descricaoCategoria, descricaoSubCategoria, menu.descricao
                                order BY cate.idCategoria, subCate.idSubCategoria/*,descricaoSubCategoria,descricao*/";
                        // echo "<pre>";
                        // die($sql);
                    $sql = $this->db->query($sql);

                    $dados = array();
                    if ($sql->rowCount() > 0) {
                        $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
                    }
                    
                    $categoriaMenu = "";
                    $subCategoriaMenu = "";
                    $subCategoria = "";

                    $idUsuario = intval($_SESSION['idUsrTotvs']);

                    // Select favoritos
                    $sql2 = "SELECT 
                            usr.idGrupo, 
                            usr.idUsuario,
                            grperm.idGrupo,
                            grperm.url,
                            menu.idMenu as idMenu,
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
                            z_sga_param_categoria as cate,
                            z_sga_param_menu_favorito as menuF
                            where
                            usr.idGrupo = grperm.idGrupo AND
                            grperm.url = menu.idMenu AND
                            subCate.idSubCategoria = menu.idSubCategoria AND
                            cate.idCategoria = subCate.idCategoria AND
                            menu.idMenu IN ((SELECT idMenu FROM z_sga_param_menu_favorito WHERE idUsuario = $idUsuario))
                            group by descricaoCategoria, descricaoSubCategoria, menu.descricao, idSubCategoria
                            order BY descricaoCategoria, descricaoSubCategoria, subCate.idSubCategoria/*,descricaoSubCategoria,descricao*/";

                    $sql2 = $this->db->query($sql2);

                    $dadosFavoritos = array();

                    if(isset($sql2)){
                        if ($sql2->rowCount() > 0) {
                            $dadosFavoritos = $sql2->fetchAll(PDO::FETCH_ASSOC);
                        }
                    }

                    $categoriaMenuFavoritos = "";
                    $subCategoriaMenuFavoritos = "";
                    $subCategoriaFavoritos = "";
                    $favoritado = array();
                    $prevCat = '';
                    ?>
   
                    <!-- Menu Favoritos -->
                        <div class="menu_section">
                            <ul class="nav side-menu">
                                <h3>
                                    <i class="fa fa-star-o"></i>
                                    Favoritos
                                </h3>

                                <li>
                                    <a>
                                        <i class="fa fa-star"></i> 
                                        Favoritos 
                                        <span class="fa fa-chevron-down"></span>
                                    </a>

                                    
                                    <ul class="nav child_menu favoritos" id="menuDeFavoritados">
                                        <?php foreach ($dadosFavoritos as $key => $categoria): ?>
                                        
                                            <!-- <?php $prevCat = $dadosFavoritos[$key-1]['idCategoria'] ?: '';?> -->
                                            
                                            <!-- Não faz nada -->
                                            <?php if($prevCat == $categoria['idCategoria']): ?>
                                            <!-- Mostra a categoria msm assim -->
                                            <?php else: ?>
                                                <li class="mt-1 row catFavoritos" 
                                                    id="<?php str_replace([' ', ','], '' , $categoria['descricaoCategoria'])?>">
                                                    <?php echo $categoria['descricaoCategoria']?>
                                                </li>  
                                            <?php endif?>
 
                                            <?php array_push($favoritado, $categoria['idMenu']) ?>
                                            <li id="favoritado<?php echo $categoria['idMenu'] ?>" class="mt-1 row" style="">
                                                <i class="fa fa-star favoritado iconeFavorito" id="favoritado<?php echo $categoria['idMenu'] ?>"></i>

                                                <a href="<?php echo URL . $categoria['menuUrl'] ?>" onclick="loadingPagia()" style="padding-right: 0 !important; width: 80%; margin-left: 15px">
                                                    <?php echo $categoria['descricao'] ?>
                                                </a>
                                            </li>
                                        
                                            <?php
                                            $next = next($dadosFavoritos);
                                            if (!isset($dadosFavoritos[$key+1]) || ($dadosFavoritos[$key+1]['idSubCategoria'] != $subCategoriaFavoritos)): 
                                            ?> 
                                            <?php endif; ?>
                                        <?php endforeach ?>
                                </li>
                            </ul>
                        </div>

                    <!-- Foreach Geral -->
                    <?php foreach ($dados as $key => $categoria): ?>
                        <?php if ($categoriaMenu != $categoria['descricaoCategoria']): ?>
                            <?php $categoriaMenu = $categoria['descricaoCategoria']; ?>
                            <div class="menu_section">
                                <ul class="nav side-menu">
                                    <h3 id="h">
                                        <i class="<?php echo $categoria['iconeCategoria'] ?>"></i>
                                        <?php echo $categoriaMenu; ?>
                                    </h3>
                        <?php endif; ?>

                                <?php 
                                /*if ($subCategoria != $categoria['descricaoSubCategoria']):
                                    $subCategoria = $categoria['descricaoSubCategoria'];*/
                                    if ($subCategoria != $categoria['idSubCategoria']):
                                        $subCategoria = $categoria['idSubCategoria'];
                                ?>

                                <li><a><i class="<?php echo $categoria['subIcone'] ?>"></i> <?php echo $categoria['descricaoSubCategoria']; ?> <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                <?php endif; ?> 
                                    <!-- Menu -->
                                    <li id="menuFavorito<?php echo $categoria['idMenu'] ?>" class="row">
                                        <!-- Não é favorito -->
                                        <?php if (!in_array($categoria['idMenu'], $favoritado)): ?>
                                            <i class="fa fa-star-o iconeFavorito" id="<?php echo $categoria['idMenu'] ?>"></i>
                                        <!-- é favorito -->
                                        <?php else: ?>
                                            <i class="fa fa-star iconeFavorito favoritado" id="<?php echo $categoria['idMenu'] ?>"></i>
                                        <?php endif; ?>
                                        <!-- Link -->
                                        <a href="<?php echo URL . $categoria['menuUrl'] ?>" onclick="loadingPagia()" style="width: 80%; margin-left: 15px">
                                            <?php echo $categoria['descricao'] ?>
                                        </a>
                                    </li>

                                <?php
                                $next = next($dados);
                                if (!isset($dados[$key+1]) || ($dados[$key+1]['idSubCategoria'] != $subCategoria)): 
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

                    <div class="menu_section">
                        <ul class="nav side-menu">
                            <li style="border-top: solid 1px #405060;"><a href="<?php echo URL ?>/Login/sair"><i class="fa fa-sign-out"></i>Sair<spanclass="fa fa-angle-right"></span></a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- /sidebar menu -->

                <!-- /menu footer buttons -->
                <!--<div class="sidebar-footer">

                    <a data-toggle="tooltip" data-placement="top" title="Logout" href="<?php echo URL ?>/Login/sair">
                        <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                    </a>
                </div>-->
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
                        <?php
                        $this->loadViewInTemplate($viewName, $viewData);
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- /page content -->

        <!-- footer content -->
        <footer>
            <div class="pull-right">
                <!--SGA - 2018 V.2.01-->
                Sistema de Gestão de Acessos (SGA) &copy; 2019 <a href="http://www.bitistech.com.br" target="blanck"> www.bitistech.com.br</a> | versão: <strong> <?php include("versao")?> </strong>
            </div>
            <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark" style="display: none">
            <!-- Create the tabs -->
            <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
                <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
                <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <!-- Home tab content -->
                <div class="tab-pane" id="control-sidebar-home-tab">
                    <h3 class="control-sidebar-heading">Recent Activity</h3>
                    <ul class="control-sidebar-menu">
                        <li>
                            <a href="javascript:void(0)">
                                <i class="menu-icon fa fa-birthday-cake bg-red"></i>

                                <div class="menu-info">
                                    <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                                    <p>Will be 23 on April 24th</p>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <i class="menu-icon fa fa-user bg-yellow"></i>

                                <div class="menu-info">
                                    <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>

                                    <p>New phone +1(800)555-1234</p>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

                                <div class="menu-info">
                                    <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>

                                    <p>nora@example.com</p>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <i class="menu-icon fa fa-file-code-o bg-green"></i>

                                <div class="menu-info">
                                    <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>

                                    <p>Execution time 5 seconds</p>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <!-- /.control-sidebar-menu -->

                    <h3 class="control-sidebar-heading">Tasks Progress</h3>
                    <ul class="control-sidebar-menu">
                        <li>
                            <a href="javascript:void(0)">
                                <h4 class="control-sidebar-subheading">
                                    Custom Template Design
                                    <span class="label label-danger pull-right">70%</span>
                                </h4>

                                <div class="progress progress-xxs">
                                    <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <h4 class="control-sidebar-subheading">
                                    Update Resume
                                    <span class="label label-success pull-right">95%</span>
                                </h4>

                                <div class="progress progress-xxs">
                                    <div class="progress-bar progress-bar-success" style="width: 95%"></div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <h4 class="control-sidebar-subheading">
                                    Laravel Integration
                                    <span class="label label-warning pull-right">50%</span>
                                </h4>

                                <div class="progress progress-xxs">
                                    <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <h4 class="control-sidebar-subheading">
                                    Back End Framework
                                    <span class="label label-primary pull-right">68%</span>
                                </h4>

                                <div class="progress progress-xxs">
                                    <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <!-- /.control-sidebar-menu -->

                </div>
                <!-- /.tab-pane -->
                <!-- Stats tab content -->
                <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
                <!-- /.tab-pane -->
                <!-- Settings tab content -->
                <div class="tab-pane" id="control-sidebar-settings-tab">
                    <form method="post">
                        <h3 class="control-sidebar-heading">General Settings</h3>

                        <div class="form-group">
                            <label class="control-sidebar-subheading">
                                Report panel usage
                                <input type="checkbox" class="pull-right" checked>
                            </label>

                            <p>
                                Some information about this general settings option
                            </p>
                        </div>
                        <!-- /.form-group -->

                        <div class="form-group">
                            <label class="control-sidebar-subheading">
                                Allow mail redirect
                                <input type="checkbox" class="pull-right" checked>
                            </label>

                            <p>
                                Other sets of options are available
                            </p>
                        </div>
                        <!-- /.form-group -->

                        <div class="form-group">
                            <label class="control-sidebar-subheading">
                                Expose author name in posts
                                <input type="checkbox" class="pull-right" checked>
                            </label>

                            <p>
                                Allow the user to show his name in blog posts
                            </p>
                        </div>
                        <!-- /.form-group -->

                        <h3 class="control-sidebar-heading">Chat Settings</h3>

                        <div class="form-group">
                            <label class="control-sidebar-subheading">
                                Show me as online
                                <input type="checkbox" class="pull-right" checked>
                            </label>
                        </div>
                        <!-- /.form-group -->

                        <div class="form-group">
                            <label class="control-sidebar-subheading">
                                Turn off notifications
                                <input type="checkbox" class="pull-right">
                            </label>
                        </div>
                        <!-- /.form-group -->

                        <div class="form-group">
                            <label class="control-sidebar-subheading">
                                Delete chat history
                                <a href="javascript:void(0)" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
                            </label>
                        </div>
                        <!-- /.form-group -->
                    </form>
                </div>
                <!-- /.tab-pane -->
            </div>
        </aside>
        <!-- /.control-sidebar -->
        <!-- Add the sidebar's background. This div must be placed
             immediately after the control sidebar -->
        <div class="control-sidebar-bg"></div>
    </div>
</div>

<!-- compose -->
<div class="compose col-md-6 col-xs-12">
    <div class="compose-header">
        New Message
        <button type="button" class="close compose-close">
            <span>×</span>
        </button>
    </div>

    <div class="compose-body">
        <div id="alerts"></div>

        <div class="btn-toolbar editor" data-role="editor-toolbar" data-target="#editor">
            <div class="btn-group">
                <a class="btn dropdown-toggle" data-toggle="dropdown" title="Font"><i class="fa fa-font"></i><b
                            class="caret"></b></a>
                <ul class="dropdown-menu">
                </ul>
            </div>

            <div class="btn-group">
                <a class="btn dropdown-toggle" data-toggle="dropdown" title="Font Size"><i
                            class="fa fa-text-height"></i>&nbsp;<b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li>
                        <a data-edit="fontSize 5">
                            <p style="font-size:17px">Huge</p>
                        </a>
                    </li>
                    <li>
                        <a data-edit="fontSize 3">
                            <p style="font-size:14px">Normal</p>
                        </a>
                    </li>
                    <li>
                        <a data-edit="fontSize 1">
                            <p style="font-size:11px">Small</p>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="btn-group">
                <a class="btn" data-edit="bold" title="Bold (Ctrl/Cmd+B)"><i class="fa fa-bold"></i></a>
                <a class="btn" data-edit="italic" title="Italic (Ctrl/Cmd+I)"><i class="fa fa-italic"></i></a>
                <a class="btn" data-edit="strikethrough" title="Strikethrough"><i class="fa fa-strikethrough"></i></a>
                <a class="btn" data-edit="underline" title="Underline (Ctrl/Cmd+U)"><i class="fa fa-underline"></i></a>
            </div>

            <div class="btn-group">
                <a class="btn" data-edit="insertunorderedlist" title="Bullet list"><i class="fa fa-list-ul"></i></a>
                <a class="btn" data-edit="insertorderedlist" title="Number list"><i class="fa fa-list-ol"></i></a>
                <a class="btn" data-edit="outdent" title="Reduce indent (Shift+Tab)"><i class="fa fa-dedent"></i></a>
                <a class="btn" data-edit="indent" title="Indent (Tab)"><i class="fa fa-indent"></i></a>
            </div>

            <div class="btn-group">
                <a class="btn" data-edit="justifyleft" title="Align Left (Ctrl/Cmd+L)"><i class="fa fa-align-left"></i></a>
                <a class="btn" data-edit="justifycenter" title="Center (Ctrl/Cmd+E)"><i class="fa fa-align-center"></i></a>
                <a class="btn" data-edit="justifyright" title="Align Right (Ctrl/Cmd+R)"><i
                            class="fa fa-align-right"></i></a>
                <a class="btn" data-edit="justifyfull" title="Justify (Ctrl/Cmd+J)"><i class="fa fa-align-justify"></i></a>
            </div>

            <div class="btn-group">
                <a class="btn dropdown-toggle" data-toggle="dropdown" title="Hyperlink"><i class="fa fa-link"></i></a>
                <div class="dropdown-menu input-append">
                    <input class="span2" placeholder="URL" type="text" data-edit="createLink"/>
                    <button class="btn" type="button">Add</button>
                </div>
                <a class="btn" data-edit="unlink" title="Remove Hyperlink"><i class="fa fa-cut"></i></a>
            </div>

            <div class="btn-group">
                <a class="btn" title="Insert picture (or just drag & drop)" id="pictureBtn"><i
                            class="fa fa-picture-o"></i></a>
                <input type="file" data-role="magic-overlay" data-target="#pictureBtn" data-edit="insertImage"/>
            </div>

            <div class="btn-group">
                <a class="btn" data-edit="undo" title="Undo (Ctrl/Cmd+Z)"><i class="fa fa-undo"></i></a>
                <a class="btn" data-edit="redo" title="Redo (Ctrl/Cmd+Y)"><i class="fa fa-repeat"></i></a>
            </div>
        </div>

        <div id="editor" class="editor-wrapper"></div>
    </div>

    <div class="compose-footer">
        <button id="send" class="btn btn-sm btn-success" type="button">Send</button>
    </div>
</div>


    <script src="<?php echo URL ?>/assets/vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="<?php echo URL ?>/assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="<?php echo URL ?>/assets/vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="<?php echo URL ?>/assets/vendors/nprogress/nprogress.js"></script>
    <!-- Chart.js -->
    <script src="<?php echo URL ?>/assets/vendors/Chart.js/dist/Chart.min.js"></script>
    <!-- gauge.js -->
    <script src="<?php echo URL ?>/assets/vendors/gauge.js/dist/gauge.min.js"></script>
    <!-- bootstrap-progressbar -->
    <script src="<?php echo URL ?>/assets/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    <!-- iCheck -->
    <script src="<?php echo URL ?>/assets/vendors/iCheck/icheck.min.js"></script>
    <!-- Skycons -->
    <script src="<?php echo URL ?>/assets/vendors/skycons/skycons.js"></script>
    <!-- Flot -->
    <script src="<?php echo URL ?>/assets/vendors/Flot/jquery.flot.js"></script>
    <script src="<?php echo URL ?>/assets/vendors/Flot/jquery.flot.pie.js"></script>
    <script src="<?php echo URL ?>/assets/vendors/Flot/jquery.flot.time.js"></script>
    <script src="<?php echo URL ?>/assets/vendors/Flot/jquery.flot.stack.js"></script>
    <script src="<?php echo URL ?>/assets/vendors/Flot/jquery.flot.resize.js"></script>
    <!-- Flot plugins -->
    <script src="<?php echo URL ?>/assets/vendors/flot.orderbars/js/jquery.flot.orderBars.js"></script>
    <script src="<?php echo URL ?>/assets/vendors/flot-spline/js/jquery.flot.spline.min.js"></script>
    <script src="<?php echo URL ?>/assets/vendors/flot.curvedlines/curvedLines.js"></script>
    <!-- DateJS -->
    <script src="<?php echo URL ?>/assets/vendors/DateJS/build/date.js"></script>
    <!-- JQVMap -->
    <script src="<?php echo URL ?>/assets/vendors/jqvmap/dist/jquery.vmap.js"></script>
    <script src="<?php echo URL ?>/assets/vendors/jqvmap/dist/maps/jquery.vmap.world.js"></script>
    <script src="<?php echo URL ?>/assets/vendors/jqvmap/examples/js/jquery.vmap.sampledata.js"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="<?php echo URL ?>/assets/vendors/moment/min/moment.min.js"></script>
    <script src="<?php echo URL ?>/assets/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>


    <script src="<?php echo URL ?>/assets/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo URL ?>/assets/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="<?php echo URL ?>/assets/vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="<?php echo URL ?>/assets/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
    <script src="<?php echo URL ?>/assets/vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="<?php echo URL ?>/assets/vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="<?php echo URL ?>/assets/vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="<?php echo URL ?>/assets/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
    <script src="<?php echo URL ?>/assets/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
    <script src="<?php echo URL ?>/assets/vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="<?php echo URL ?>/assets/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
    <script src="<?php echo URL ?>/assets/vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
    <script src="<?php echo URL ?>/assets/vendors/jszip/dist/jszip.min.js"></script>
    <script src="<?php echo URL ?>/assets/vendors/pdfmake/build/pdfmake.min.js"></script>
    <script src="<?php echo URL ?>/assets/vendors/pdfmake/build/vfs_fonts.js"></script>
    <script src="<?php echo URL ?>/assets/vendors/mjolnic-bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
    <script src="<?php echo URL ?>/assets/build/js/select2.min.js"></script>
    <script src="<?php echo URL ?>/assets/vendors/jquery-form/jquery.form.min.js"></script>
    <!-- Custom Theme Scripts -->
    <script src="<?php echo URL ?>/assets/build/js/custom.min.js"></script>
    <script src="<?php echo URL ?>/assets/js/script.js"></script>



    <script>
        $(document).ready(function(){
            
            setInterval(function(){  

                $.ajax({
                    type: "POST",
                    url: url+"Home/ajaxNotificacao",
                    data:'id='+"",
                    beforeSend: function(){           
                    },
                    success: function(data){
                        var dados = JSON.parse(data);
                        $(".quantAtiv").html(dados.length);
                     
                        
                        var html = "";
                        for (var i=0;i<dados.length; i++){
                            //console.log(dados[i].descricao);
                            html+="<li>";
                                html+="<a href='"+url+"Fluxo/callRegras/"+dados[i].url+"/"+dados[i].idSolicitacao+"/"+dados[i].idAtividade+"/0/"+dados[i].idMovimentacao+"/"+dados[i].idSolicitante+"'";
                                    
                                    html+="<span>";
                                        html+="<span><strong>Olá!</strong> "+dados[i].nome_usuario+"</span>";
                                    html+="</span>";
                                   
                                    html+="<p>";      
                                        html+="Você tem esta tarefa pendente.<br>";
                                        html+="Descrição: <strong>"+dados[i].descricao+"</strong><br>";
                                        html+="Numero da Solicitação: <strong>"+dados[i].idSolicitacao+"</strong>";
                                    html+="</p>";
                                  
                                html+="</a>";
                            html+="</li>";                             
                        }
                        $(".atividade").html(html);  
                    }
                });

            }, 3000);
            
            $(document).on('click', '.iconeFavorito', function(event){
                var id = $(this).attr('id');
                var nId = '';

                if (id.length == 11) {
                    nId = id[10];
                } else if (id.length == 12) {
                    nId = id[10] + id[11];
                } else {
                    nId = id;
                }

                id = nId;

                $('#load').css('display', 'block');
               
                $.post(
                    url + 'Home/favoritar',
                    {idMenu:id, idUsuario:<?php echo $_SESSION['idUsrTotvs']?>},

                    function(data, textStatus, xhr) {
                        $('#load').css('display', 'none');
                        
                        var isFavorite = $.parseJSON(data).isFavorite;

                        // Favoritou
                        if (isFavorite == 1) {
                            // Remove a estrela  e troca a cor do icone na listagem normal
                            $('#' + id).removeClass('fa-star-o').addClass('fa-star');
                            $('#' + id).removeClass('naoFavoritado').addClass('favoritado');

                            // Pega a categoria
                            cat = $('#' + id).parent().parent().parent().parent().children('#h').text().replace(/[\n]/g, '');
                            catID = cat.replace(/[, ]/g, '');

                            console.log(catID);

                            // Verifica se existe no menu de favoritados
                            if ($('#menuDeFavoritados').children('#' + catID).length > 0) {
                                // Adiciona aos favoritados
                                var html = '<li class="favoritoAdicionado" id="favoritado'+ id +'">';
                                    html += $('#menuFavorito' + id).html();
                                html += '</li>';

                                $(html).insertAfter('#' + catID);
                            } else {
                                console.log(catID);
                                // Prepara o html
                                var html = '<li class="mt-1 row catFavoritos" id="' + catID + '">';
                                    html += cat;
                                    html += '</li>';
                                    html += '<li class="favoritoAdicionado" id="favoritado' + id + '">';
                                    html += $('#menuFavorito' + id).html();
                                html += '</li>';

                                // Adiciona aos favoritados
                                $('#menuDeFavoritados').append(html);
                            }

                            // Muda o id
                            $('#menuDeFavoritados > li').children('#' + id).prop('id', 'favoritado' + id);
                        } 
                        // Desfavoritou
                        else {
                            $('#' + id).removeClass('fa-star').addClass('fa-star-o');

                            $('#' + id).removeClass('favoritado');
                            $('#' + id).addClass('naoFavoritado');


                            var cat = $('#favoritado' + id).prev().hasClass('catFavoritos');
                            var haveMenuAbove = $('#favoritado' + id).next().children().first().hasClass('iconeFavorito');

                            if (cat == true && haveMenuAbove == false) {
                                $('#favoritado' + id).prev().remove();
                                $('#menuDeFavoritados').children('#favoritado' + id).remove();
                            } else {
                                $('#menuDeFavoritados').children('#favoritado' + id).remove();
                            }

                        } 
                    }
                );
            });
            // .done(function() {
            //     console.log("success");
            // })
            // .fail(function() {
            //     console.log("error");
            // })
            // .always(function() {
            //     console.log("complete");
            // });
            
            /* Act on the event */
        });
            
        // Alterna entre logos quando botão de menu é clicado
        $('#menu_toggle').on('click', function () {
            $('.navbar').find('.img-logo-menu-big').toggleClass('hide')
            $('.navbar').find('.img-logo-menu-small').toggleClass('hide');
        });




        /* ControlSidebar()
         * ===============
         * Toggles the state of the control sidebar
         *
         * @Usage: $('#control-sidebar-trigger').controlSidebar(options)
         *         or add [data-toggle="control-sidebar"] to the trigger
         *         Pass any option as data-option="value"
         */
        +function ($) {
            'use strict';

            //var DataKey = 'lte.controlsidebar';
            var DataKey = 'controlsidebar';

            var Default = {
                controlsidebarSlide: true
            };

            var Selector = {
                sidebar: '.control-sidebar',
                data   : '[data-toggle="control-sidebar"]',
                open   : '.control-sidebar-open',
                bg     : '.control-sidebar-bg',
                wrapper: '.wrapper',
                content: '.content-wrapper',
                boxed  : '.layout-boxed'
            };

            var ClassName = {
                open: 'control-sidebar-open',
                transition: 'control-sidebar-hold-transition',
                fixed: 'fixed'
            };

            var Event = {
                collapsed: 'collapsed.controlsidebar',
                expanded : 'expanded.controlsidebar'
            };

            // ControlSidebar Class Definition
            // ===============================
            var ControlSidebar = function (element, options) {
                this.element         = element;
                this.options         = options;
                this.hasBindedResize = false;

                this.init();
            };

            ControlSidebar.prototype.init = function () {
                // Add click listener if the element hasn't been
                // initialized using the data API
                if (!$(this.element).is(Selector.data)) {
                    $(this).on('click', this.toggle);
                }

                this.fix();
                $(window).resize(function () {
                    this.fix();
                }.bind(this));
            };

            ControlSidebar.prototype.toggle = function (event) {
                if (event) event.preventDefault();

                this.fix();

                if (!$(Selector.sidebar).is(Selector.open) && !$('body').is(Selector.open)) {
                    this.expand();
                } else {
                    this.collapse();
                }
            };

            ControlSidebar.prototype.expand = function () {
                $(Selector.sidebar).show();
                if (!this.options.controlsidebarSlide) {
                    $('body').addClass(ClassName.transition).addClass(ClassName.open).delay(50).queue(function(){
                        $('body').removeClass(ClassName.transition);
                        $(this).dequeue()
                    })
                } else {
                    $(Selector.sidebar).addClass(ClassName.open);
                }


                $(this.element).trigger($.Event(Event.expanded));
            };

            ControlSidebar.prototype.collapse = function () {
                if (!this.options.controlsidebarSlide) {
                    $('body').addClass(ClassName.transition).removeClass(ClassName.open).delay(50).queue(function(){
                        $('body').removeClass(ClassName.transition);
                        $(this).dequeue()
                    })
                } else {
                    $(Selector.sidebar).removeClass(ClassName.open);
                }
                $(Selector.sidebar).fadeOut();
                $(this.element).trigger($.Event(Event.collapsed));
            };

            ControlSidebar.prototype.fix = function () {
                if ($('body').is(Selector.boxed)) {
                    this._fixForBoxed($(Selector.bg));
                }
            };

            // Private

            ControlSidebar.prototype._fixForBoxed = function (bg) {
                bg.css({
                    position: 'absolute',
                    height  : $(Selector.wrapper).height()
                });
            };

            // Plugin Definition
            // =================
            function Plugin(option) {
                return this.each(function () {
                    var $this = $(this);
                    var data  = $this.data(DataKey);

                    if (!data) {
                        var options = $.extend({}, Default, $this.data(), typeof option == 'object' && option);
                        $this.data(DataKey, (data = new ControlSidebar($this, options)));
                    }

                    if (typeof option == 'string') data.toggle();
                });
            }

            var old = $.fn.controlSidebar;

            $.fn.controlSidebar             = Plugin;
            $.fn.controlSidebar.Constructor = ControlSidebar;

            // No Conflict Mode
            // ================
            $.fn.controlSidebar.noConflict = function () {
                $.fn.controlSidebar = old;
                return this;
            };

            // ControlSidebar Data API
            // =======================
            $(document).on('click', Selector.data, function (event) {
                if (event) event.preventDefault();
                Plugin.call($(this), 'toggle');
            });
        }(jQuery);

        $(function(){
            $('[data-toggle="control-sidebar"]').controlSidebar()
            var $controlSidebar = $('[data-toggle="control-sidebar"]').data('controlsidebar')
            $(window).on('load', function() {
                // Reinitialize variables on load
                $controlSidebar = $('[data-toggle="control-sidebar"]').data('.controlsidebar');
            })
        });

        function favorite(id, isFavorite) {
            $.ajax({
                url: url + 'Home/favoritar',
                type: 'POST',
                dataType: 'json',
                data: {
                    favoritar: isFavorite,
                    idMenu: id
                },

                success: function (resp, status) {
                    // Remove uma classe e substitui por outra
                    if ($('#' + id).hasClass('fa-star-o')) {
                        $('#' + id).remove('fa-star-o');
                        $('#' + id).addClass('fa-star');
                    } else {
                        $('#' + id).remove('fa-star');
                        $('#' + id).addClass('fa-star-o');
                    }

                    var content = $('#menu' + id).html();

                    $('.favoritos').append(content);

                }
            }).fail(function (resp, status) {
                

                console.log(resp);
            })
        }
    </script>





</body>
</html>