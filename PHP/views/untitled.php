<div class="" role="tabpanel" data-example-id="togglable-tabs">
        <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
          <li role="presentation" class="active"><a href="#tab_content1" id="profile-tab1" role="tab" data-toggle="tab" aria-expanded="true">Situação Atual</a>
          </li>
          <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Situação Anterior</a>
          </li>
        
        </ul>
        <div id="myTabContent" class="tab-content">
          <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="profile-tab1"><!--Inicio Tabela 1 -->
            <br>
              <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer"><div class="row"><div class="col-sm-6"></div><div class="col-sm-6"></div></div><div class="row"><div class="col-sm-12">
              <table  class="tabela table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info" style="width: 100%;" id="tableGrupoUsuario">
              <thead>
                <tr>
                <th>Id Grupo</th>
                <th>Descrição</th>
                <th>Gestor</th>
                <th>Quant. Programa</th>
                <th>Quant. Usuários</th>
                </tr>
              </thead>
              <tbody> 
           
               <?php foreach($grupo as $valor):?>
                  <tr>
                    <td><?php echo utf8_decode($valor['idLegGrupo'])?></td>
                    <td><?php echo $valor['descAbrev']?></td>
                    <td><?php echo $valor['nomeGestor']?></td>
                    <td><span class="badge label-primary"><?php echo $valor['totalPro']?></span></td>
                    <td><span class="badge label-primary"><?php echo $valor['totalUsuario']?></span></td>
                  </tr>
                <?php endforeach;?>  

               </tbody>
            </table></div></div></div>
          </div><!--Fim Tabela 1 -->
          <div role="tabpanel" class="tab-pane fade " id="tab_content2" aria-labelledby="profile-tab2"><!--Inicio Tabela 2 -->
              <div class="loadProgUsr">
               <center><img src="<?php echo URL ?>/assets/images/loader.gif"></center>
              </div>
              <br>
              <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer"><div class="row"><div class="col-sm-6"></div><div class="col-sm-6"></div></div><div class="row"><div class="col-sm-12"><table  class="tabelaProg table table-striped hover table-striped dt-responsive nowrap  no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info" style="width: 100%;" id="tableProgUsuario">
              <thead>
                <tr role="row">
                  <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 71px;" aria-sort="ascending" aria-label="First name: activate to sort column descending">Id Grupo</th>
                  <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 70px;" aria-label="Last name: activate to sort column ascending">Descrição</th>
                  <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 70px;" aria-label="Last name: activate to sort column ascending">Programa</th>
                  <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 70px;" aria-label="Last name: activate to sort column ascending">Descrição</th>
                   <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 70px;" aria-label="Last name: activate to sort column ascending">Observacao</th>
                  <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 70px;" aria-label="Last name: activate to sort column ascending">Rotina</th>
                </tr>
              </thead>
              <tbody id="carregaProgUsr"> 
           
           

               </tbody>
            </table></div></div></div>
          </div><!--FIM Tabela 2 -->
          <div role="tabpanel" class="tab-pane fade " id="tab_content3" aria-labelledby="profile-tab3"><!--Inicio Tabela 3 -->
             <div class="loadProgDuplicadoUsr">
               <center><img src="<?php echo URL ?>/assets/images/loader.gif"></center>
              </div>
                <br>
              <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer"><div class="row"><div class="col-sm-6"></div><div class="col-sm-6"></div></div><div class="row"><div class="col-sm-12">
                <table  class="tabelaProgDuplicado table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info" style="width: 100%;" id="taleProgDuplicado" >
                <thead>
                  <tr role="row">
                    <th>Programa</th>
                    <th>Descrição</th>
                    <th>Grupos</th>
                  </tr>
                </thead>
                <tbody id="carregaProDuplicadogUsr"> 
             
                

                 </tbody>
              </table></div></div></div>
          </div><!--Inicio Tabela 3-->
          <div role="tabpanel" class="tab-pane fade " id="tab_content4" aria-labelledby="profile-tab4"><!--Inicio Tabela 4 -->
             <div class="loadMatriz">
               <center><img src="<?php echo URL ?>/assets/images/loader.gif"></center>
              </div>
              <input type="text" name="controlaMatriz" id="controlaMatriz" class="hide">
              <br>
              <div id="carregaMatiz"></div>
              
              
          </div><!--Fim Tabela 1 -->

          <div role="tabpanel" class="tab-pane fade " id="tab_content5" aria-labelledby="profile-tab5"><!--Inicio Tabela 5 -->
             <div class="loadMatrizProcesso">
               <center><img src="<?php echo URL ?>/assets/images/loader.gif"></center>
              </div>
              <input type="text" name="controlaProcessoMatriz" id="controlaProcessoMatriz" class="hide">
               <br>
               <div id="carregaProcessoMatiz"></div>

            
          </div><!--Fim Tabela 5 -->

          <div role="tabpanel" class="tab-pane fade " id="tab_content6" aria-labelledby="profile-tab6"><!--Inicio Tabela 6 -->
               <br>
               <div class="loadModulo">
               <center><img src="<?php echo URL ?>/assets/images/loader.gif"></center>
              </div>
                <input type="text" name="controlaModulo" id="controlaModulo" class="hide">
                <div id="carregaModulo"></div>  

          </div><!--Fim Tabela 6 -->
        </div>
      </div>