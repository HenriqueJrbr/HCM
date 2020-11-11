
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Grupos Totvs</h2>
        <ul class="nav navbar-right panel_toolbox">
          <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
          </li>
        </ul>
        <div class="clearfix"></div>
      </div>
      
      <div class="x_content">
        <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer"><div class="row"><div class="col-sm-6"></div><div class="col-sm-6"></div></div><div class="row"><div class="col-sm-12"><table  class="tabela table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info" style="width: 100%;">
          <thead>
            <tr role="row">
              <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 5px;" aria-sort="ascending" aria-label="First name: activate to sort column descending">Legenda</th>
              <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 10px;" aria-label="Last name: activate to sort column ascending">Acesso</th>
              <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 10px;" aria-label="Last name: activate to sort column ascending">Descrição</th>
              <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 10px;" aria-label="Position: activate to sort column ascending">Risco</th>
          </thead>
          <tbody> 
       
          <?php foreach ($risco as  $value):?>
        

                      <?php if($value['APPRISCO'] == "alto"): ?>

                            <tr >
                                <td><font style="vertical-align: inherit;text-transform: uppercase;"><font style="vertical-align: inherit;"><?php echo utf8_decode($value['legenda'])?></font></font></td>
                                <td><font style="vertical-align: inherit; text-transform: uppercase;"><font style="vertical-align: inherit;"><?php echo utf8_decode($value['Acessos'])?></font></font></td>
                                <td><font style="vertical-align: inherit; text-transform: uppercase;"><font style="vertical-align: inherit;"><?php echo $value['ds_conflito'] ?></font></font></td>
                                <td style="background-color: red; color:#FFFFFF; text-align: center;text-transform: uppercase; padding-top: 25px;"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo "Alto" ?></font></font></td>
                              </tr>

                          <?php endif; ?>
                            <?php if($value['APPRISCO'] == "medio"): ?>

                            <tr >
                             <td><font style="vertical-align: inherit;text-transform: uppercase;"><font style="vertical-align: inherit;"><?php echo utf8_decode($value['legenda'])?></font></font></td>
                                <td><font style="vertical-align: inherit; text-transform: uppercase;"><font style="vertical-align: inherit;"><?php echo utf8_decode($value['Acessos'])?></font></font></td>
                                <td><font style="vertical-align: inherit; text-transform: uppercase;"><font style="vertical-align: inherit;"><?php echo $value['ds_conflito'] ?></font></font></td>
                                <td style="background-color: #FB9200; color:#000000; text-align: center;text-transform: uppercase; padding-top: 25px;"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo "Medio" ?></font></font></td>
                              </tr>

                          <?php endif; ?>
                            <?php if($value['APPRISCO'] == "baixo"): ?>

                            <tr >
                                <td><font style="vertical-align: inherit;text-transform: uppercase;"><font style="vertical-align: inherit;"><?php echo utf8_decode($value['legenda'])?></font></font></td>
                                <td><font style="vertical-align: inherit; text-transform: uppercase;"><font style="vertical-align: inherit;"><?php echo utf8_decode($value['Acessos'])?></font></font></td>
                                <td><font style="vertical-align: inherit; text-transform: uppercase;"><font style="vertical-align: inherit;"><?php echo $value['ds_conflito'] ?></font></font></td>
                                <td style="background-color: #FABE6A; color:#000000; text-align: center;text-transform: uppercase; padding-top: 25px;"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo "Baixo" ?></font></font></td>
                              </tr>

                          <?php endif; ?>
                  <?php endforeach; ?>

           </tbody>
        </table></div></div></div>
        
        
      </div>
    </div>
  </div>
</div>

