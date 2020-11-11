<div class="col-md-12 col-sm-12 col-xs-12">
    <ol class="breadcrumb">
        <li><a href="<?php echo URL ?>/" onclick="loadingPagia()">Dashboard</a></li>            
        <li>Movimentação de Acesso</li>            
    </ol>
</div>
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Movimentação Acesso<small></small></h2>
        <ul class="nav navbar-right panel_toolbox">
          <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
          </li>
        </ul>
        <div class="clearfix"></div>
      </div>
      <?php if(isset($_SESSION['validaDataLog'])):?>
       <div class="alert alert-warning alert-dismissible fade in" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
          </button>
          <strong>Alerta!</strong> <?php echo $_SESSION['validaDataLog'];  ?>
        </div>
      <?php unset($_SESSION['validaDataLog']); endif;?>


    <div class="panel-group" id="accordion">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">
        Filtro</a>
      </h4>
    </div>
    <div id="collapse1" class="panel-collapse collapse ">
      <div class="panel-body">
            <form method="POST">
      <div class="row">
        <div class="col-md-2">
          <label>Data de Inicio</label>
          <input type="date" name="dataInicio" id="dataInicio" class="form-control" value="<?php echo (isset($_SESSION['dataInicio'])) ? $_SESSION['dataInicio'] : date('Y-m-d', strtotime('-15 days')); ?>" >
        </div>
        <div class="col-md-1">            
          <label><br></label>
          <input type="button"  class="btn form-control btn-danger btn-xs" value="<< >>" onclick="copiaDataInicio()" >
        </div>
        <div class="col-md-2">
          
          <label>Data de Fim</label>
          <input type="date" name="dataFim" id="dataFim" class="form-control" value="<?php echo (isset($_SESSION['dataFim'])) ? $_SESSION['dataFim'] : date('Y-m-d'); ?>">
        </div>
      </div>      
       <div class="row">
        <div class="col-md-2">
          <label>Solicitante</label>
          <input type="text" name="solicitante" id="solicitante" class="form-control" <?php if(isset($_SESSION['solicitante'])):?> value="<?php echo $_SESSION['solicitante'] ?>"  <?php else: ?> value=""<?php endif; ?>>
        </div>
        <div class="col-md-1">
          <label><br></label>
          <input type="button"  class="btn form-control btn-danger btn-xs" value="<< >>" onclick="copiaSolicitante()">
        </div>
        <div class="col-md-2">
          <label><br></label>
          <input type="text" name="solicitanteFim" id="solicitanteFim" class="form-control"  <?php if(isset($_SESSION['solicitanteFim'])):?> value="<?php echo $_SESSION['solicitanteFim'] ?>"  <?php else: ?> value="ZZZZZZZZZZZZZZZ"<?php endif; ?>>
        </div>
      </div>
      <div class="row">
        <div class="col-md-2">
          <label>Aprovador</label>
          <input type="text" name="aprovador" id="aprovador" class="form-control"  <?php if(isset($_SESSION['aprovador'])):?> value="<?php echo $_SESSION['aprovador'] ?>"  <?php else: ?> value=""<?php endif; ?>>
        </div>
        <div class="col-md-1">
          <label><br></label>
          <input type="button"  class="btn form-control btn-danger btn-xs" value="<< >>" onclick="copiaAprovador()">
        </div>
        <div class="col-md-2">
          <label><br></label>
          <input type="text" name="aprovadorFim" id="aprovadorFim" class="form-control" <?php if(isset($_SESSION['aprovadorFim'])):?> value="<?php echo $_SESSION['aprovadorFim'] ?>"  <?php else: ?> value="ZZZZZZZZZZZZZZZ"<?php endif; ?>>
        </div>
      </div>
                
      <div class="row">
        <div class="col-md-2">
          <label>Usuário</label>
          <input type="text" name="usuario" id="usuario" class="form-control"  <?php if(isset($_SESSION['usuario'])):?> value="<?php echo $_SESSION['usuario'] ?>"  <?php else: ?> value=""<?php endif; ?>>
        </div>
        <div class="col-md-1">
          <label><br></label>
          <input type="button"  class="btn form-control btn-danger btn-xs" value="<< >>" onclick="copiaUsuario()">
        </div>
        <div class="col-md-2">
          <label><br></label>
          <input type="text" name="usuarioFim" id="usuarioFim" class="form-control" <?php if(isset($_SESSION['usuarioFim'])):?> value="<?php echo $_SESSION['usuarioFim'] ?>"  <?php else: ?> value="ZZZZZZZZZZZZZZZ"<?php endif; ?>>
        </div>
      </div>

        <div class="row">
            <div class="col-md-2">
                <label>Grupo</label>
                <input type="text" name="grupo" id="grupo" class="form-control"  <?php if(isset($_SESSION['grupo'])):?> value="<?php echo $_SESSION['grupo'] ?>"  <?php else: ?> value=""<?php endif; ?>>
            </div>
            <div class="col-md-1">
                <label><br></label>
                <input type="button"  class="btn form-control btn-danger btn-xs" value="<< >>" onclick="copiaGrupo()">
            </div>
            <div class="col-md-2">
                <label><br></label>
                <input type="text" name="grupoFim" id="grupoFim" class="form-control" <?php if(isset($_SESSION['grupoFim'])):?> value="<?php echo $_SESSION['grupoFim'] ?>"  <?php else: ?> value="ZZZZZZZZZZZZZZZ"<?php endif; ?>>
            </div>
        </div>

        <div class="row">
            <div class="col-md-2">
                <label>Programa</label>
                <input type="text" name="programa" id="programa" class="form-control"  <?php if(isset($_SESSION['programa'])):?> value="<?php echo $_SESSION['programa'] ?>"  <?php else: ?> value=""<?php endif; ?>>
            </div>
            <div class="col-md-1">
                <label><br></label>
                <input type="button"  class="btn form-control btn-danger btn-xs" value="<< >>" onclick="copiaPrograma()">
            </div>
            <div class="col-md-2">
                <label><br></label>
                <input type="text" name="programaFim" id="programaFim" class="form-control" <?php if(isset($_SESSION['programaFim'])):?> value="<?php echo $_SESSION['programaFim'] ?>"  <?php else: ?> value="ZZZZZZZZZZZZZZZ"<?php endif; ?>>
            </div>
        </div>

      <br>
      <div class="row">
        <div class="col-md-2">
          <input type="submit" name="pesquisar" value="Filtrar" class="form-control btn btn-success">
        </div>
      </div>
      <br>
    </form>
      </div>
    </div>
  </div>
</div>


    <?php 
      unset($_SESSION['dataInicio']); 
      unset($_SESSION['dataFimInicio']);
      unset($_SESSION['dataInicioFim']);
      unset($_SESSION['dataFim']); 
      unset($_SESSION['solicitante']);
      unset($_SESSION['solicitanteFim']);
      unset($_SESSION['aprovador']); 
      unset($_SESSION['aprovadorFim']);
    ?>
    
      
      <div class="x_content">
        <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer"><div class="row"><div class="col-sm-6"></div><div class="col-sm-6"></div></div><div class="row"><div class="col-sm-12"><table  class="tabela table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info" style="width: 100%;">
          <thead>
            <tr role="row">
<!--           <th style="width: 8%"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">ID Processo</font></font></th>-->
<!--              <th style="width: 10%"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Data Inicio</font></font></th>-->
              <th style="width: 10%">Data Fim</th>
              <th style="width: 12%">Solicitante</th>
              <th style="width: 12%">Usuário</th>
              <th style="width: 10%">Ação</th>
              <th style="width: 8%">Tipo</th>
              <th style="width: 20%">Grupo</th>
              <th style="width: 8%">Programa</th>
              <th style="width: 10%">Solicitação</th>
              <th style="width: 10%">Aprovador / Operador</th>
          </thead>
          <tbody> 
       
             <?php foreach ($dadoslog as  $value):?>
                    <tr>
                        <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo date('d/m/Y H:i:s', strtotime($value['dataFim'])); ?></font></font></td>
                        <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $value['solicitante'] ?></font></font></td>
                        <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $value['usuario'] ?></font></font></td>
                        <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo strtoupper($value['acao']); ?></font></font></td>
                        <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">
                            <?php
                                if($value['tipoMovimentacao'] == 'm'):
                                    echo 'Manutenção';
                                elseif($value['tipoMovimentacao'] == 'f'):
                                    echo 'Fluxo';
                                else:
                                    echo 'Api';
                                endif;

                            ?></font></font>
                        </td>
                        <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $value['grupo']?></font></font></td>
                        <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $value['programa']?></font></font></td>
                        <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo ($value['idSolicitacao'] == 0 ? 'Não' : '<a class="btn btn-warning btn-xs" href="'.URL.'/fluxo/callRegras/'.$value['form'].'/'.$value['idSolicitacao'].'/0/0/0/0" target="_blank">Vizualizar</a>' )?></font></font></td>
                        <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $value['aprovadorAcao'] ?></font></font></td>  
                    </tr>
                <?php endforeach; ?>


           </tbody>
        </table></div></div></div>
        
        
      </div>
    </div>
  </div>
</div>

