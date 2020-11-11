<style type="text/css">
    #country-list{float:left;list-style:none;margin-top:2px;padding:0;width:540px; position: absolute; z-index:999;}
    #country-list li{padding: 10px; background: #f0f0f0; border-bottom: #bbb9b9 1px solid; border-radius: 5px;}
    #country-list li:hover{background:#ece3d2;cursor: pointer;}
    #search-box{padding: 10px;border: #a8d4b1 1px solid;border-radius:4px;}

    #country-list2{float:left;list-style:none;margin-top:2px;padding:0;width:540px; position: absolute; z-index:999;}
    #country-list2 li{padding: 10px; background: #f0f0f0; border-bottom: #bbb9b9 1px solid; border-radius: 5px;}
    #country-list2 li:hover{background:#ece3d2;cursor: pointer;}
    #search-box2{padding: 10px;border: #a8d4b1 1px solid;border-radius:4px;}

    #country-list3{float:left;list-style:none;margin-top:2px;padding:0;width:540px; position: absolute; z-index:999;}
    #country-list3 li{padding: 10px; background: #f0f0f0; border-bottom: #bbb9b9 1px solid; border-radius: 5px;}
    #country-list3 li:hover{background:#ece3d2;cursor: pointer;}
    #search-box3{padding: 10px;border: #a8d4b1 1px solid;border-radius:4px;}
</style>


<div class="col-md-12 col-sm-12 col-xs-12">
    <ol class="breadcrumb">
        <li><a href="<?php echo URL ?>/" onclick="loadingPagia()">Dashboard</a></li>
        <li><a href="<?php echo URL ?>/Manutencao/programa"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Manutenção</font></font></a></li>
        <li><a href="<?php echo URL ?>/Manutencao/programa"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Programa</font></font></a></li>
        <li class="active"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Atualização</font></font></li>
    </ol>
</div>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Detalhes do Programa</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <br>
                <form id="demo-form2" action="<?= URL ?>/Manutencao/salvarPrograma" method="post" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
                    <input type="hidden" name="id" value="<?= $programa['id']; ?>">
                    <?php if(isset($_SESSION['msg']['error'])): ?>
                        <div class="alert alert-danger alert-dismissible">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <?= $_SESSION['msg']['error']; ?>
                            <?php unset($_SESSION['msg']['error']); ?>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-4">
                            <label  for="first-name">Cod. Programa</label>
                            <input type="text" id="codPrograma" name="codPrograma" required="required" class="form-control" value="<?php echo $programa['codigo']; ?> " readonly="readonly">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label  for="first-name">Descrição</label>
                            <textarea class="form-control" id="descricaoPrograma" name="descricaoPrograma" rows="5" readonly="readonly"><?php echo $programa['descricao']; ?></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label  for="first-name">Específico</label>
                            <input type="text" id="especifico" name="especifico" required="required" class="form-control" value="<?php echo $programa['especifico']; ?> " readonly="readonly">
                        </div>
                        <!--<div class="col-md-4">
                            <label  for="first-name">Código da Rotina</label>
                            <input type="text" id="codRotina" name="codRotina" required="required" class="form-control" value="<?php /*echo $programa['codigo_rotina']; */?> " readonly="readonly">
                            <input type="text" id="idCodRotina" name="idCodRotina" required="required" class="form-control hide" value="<?php /*echo $programa['idGrauRisco']; */?> ">
                        </div>-->
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label  for="first-name">Ajuda</label>
                            <textarea class="form-control" id="ajudaPrograma" name="ajudaPrograma" rows="5"><?php echo $programa['ajuda']; ?></textarea>
                        </div>
                    </div>
                    <div class="clearfix">&nbsp;</div>

                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-success pull-right">Salvar</button>
                            <button type="button" class="btn btn-danger pull-right" onclick="javascript:history.back(-1)">Voltar</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>