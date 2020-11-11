<div class="col-md-12 col-sm-12 col-xs-12">
  <ol class="breadcrumb">
    <li><a href="<?php echo URL ?>/ExposicaoRisco/gestor"><font style="vertical-align: inherit;" onclick="loadingPagia()"><font style="vertical-align: inherit;">Gestor</font></font></a></li>
    <li class="active"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $codUsuario['nome_usuario'] ?></font></font></li>
  </ol>
</div>
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Usuários</h2>
        <ul class="nav navbar-right panel_toolbox">
          <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
          </li>
        </ul>
        <div class="clearfix"></div>
      </div>
      <br>
      <div class="row">
        <div class="col-md-4">
            <label>Nome do Gestor:</label>
            <?php echo $codUsuario['nome_usuario'] ?>
        </div>
        <div class="col-md-4">
            <label>Código DataSul:</label>
            <?php echo $codUsuario['cod_usuario'] ?>
        </div>
         <div class="col-md-4">
            <label>Código Fluig:</label>
            <?php echo $codUsuario['idUsrFluig'] ?>
        </div>
      </div>
      <br>
      <div class="x_content">
        <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer"><div class="row"><div class="col-sm-6"></div><div class="col-sm-6"></div></div><div class="row"><div class="col-sm-12"><table  class="tabela table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info" style="width: 100%;">
          <thead>
            <tr role="row">
              <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 5px;" aria-sort="ascending" aria-label="First name: activate to sort column descending">Nome</th>
              <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 10px;" aria-label="Last name: activate to sort column ascending">ID DataSul</th>
              <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 10px;" aria-label="Last name: activate to sort column ascending">ID Fluig</th>
              <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 10px;" aria-label="Last name: activate to sort column ascending">Situação do usuário</th>
          </thead>
          <tbody> 
       
          <?php foreach ($usuarios as  $value):?>
              <tr onclick="location.href='<?php echo URL ?>/Usuario/dados_usuario/<?php echo $value['idUsuario'] ?>';loadingPagia()">
                <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $value['nome_usuario'] ?></font></font></td>
                <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $value['cod_usuario'] ?></font></font></td>
                  <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $value['idUsrFluig'] ?></font></font></td>
                  <td class="text-center"><span class="badge label-primary"><?php echo ($value['ativo'] == 1) ? 'Ativo' : 'Inativo' ?></span></td>
              </tr>
          <?php endforeach; ?>

           </tbody>
        </table></div></div></div>
        
        
      </div>
    </div>
  </div>
</div>

