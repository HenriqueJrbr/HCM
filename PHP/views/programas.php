<style type="text/css">
    #country-list{float:left;list-style:none;margin-top:5px;padding:0;width:540px; position: absolute; z-index:999;}
    #country-list li{padding: 10px; background: #f0f0f0; border-bottom: #bbb9b9 1px solid; border-radius: 5px;}
    #country-list li:hover{background:#ece3d2;cursor: pointer;}
    #search-box{padding: 10px;border: #a8d4b1 1px solid;border-radius:4px;}
</style>
<div class="col-md-12 col-sm-12 col-xs-12">
    <ol class="breadcrumb">
        <li><a href="<?php echo URL ?>/" onclick="loadingPagia()">Dashboard</a></li>                    
        <li class="active">Programas</li>
    </ol>
  </div>
<form method="POST">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Informações Programa</h2>   
        <div class="clearfix"></div>
      </div>
      <br>
      <div class="row">
          <div class="col-md-6">
            <label>Selecione o Programa</label>
            <input type="" class="form-control" id="programas" name="programas" autocomplete="off">
            <input type="" class="form-control hide" id="codProgramas" name="codProgramas" autocomplete="off">
            <div id="suggesstion-box" id="prog" >
              <ul id="country-list"></ul>
            </div>
          </div>
            <div class="col-md-2">
            <label>&nbsp;</label>
            <input type="submit"  class=" form-control btn btn-default source" value="Pesquisar" name="">
          </div>
      </div>
      <br>
      <div class="row">
          <div class="col-md-3">
            <label>Codigo do Programa: </label>

            <span><?php if(empty($descricaoProg)){echo "Não carregado";}else{echo $descricaoProg['cod_programa']; }?></span>
          </div>
          <div class="col-md-4">
            <label>Descrição: </label>
            <span><?php if(empty($descricaoProg)){echo "Não carregado";}else{echo $descricaoProg['descricao_programa']; }?></span>
          </div>
          <div class="col-md-3">
            <label>Observação: </label>
            <span><?php if(empty($descricaoProg)){echo "Não carregado";}else{ if($descricaoProg['ajuda_programa'] == ""){echo "Não Cadastrado";}else{ echo $descricaoProg['ajuda_programa'];} }?></span>
          </div>
          <div class="col-md-3">
            <label>Tipo da Tarefa: </label>
            <span><?php if(empty($descricaoProg)){echo "Não carregado";}else{echo $descricaoProg['descricao_rotina']; }?></span>
          </div>
      </div>
      <br>
      <div class="x_content">


        <div class="" role="tabpanel" data-example-id="togglable-tabs">
          <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
            <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Grupos <small class="badge"> <?php if(empty($grupoProg)){echo 0;}else{echo count($grupoProg);} ?></small></a>
            </li>
            <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Usuários <small class="badge"> <?php if(empty($usuario)){echo 0;}else{echo count($usuario);} ?></small></a>
            </li>
            <li role="presentation" class=""><a href="#tab_content3" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Campos Pessoais <small class="badge"> <?php if(empty($camposPessoal)){echo 0;}else{echo count($camposPessoal);} ?></small></a>
            </li>
            <li role="presentation" class=""><a href="#tab_content4" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Campos Sensiveis<small class="badge"> <?php if(empty($camposSensivel)){echo 0;}else{echo count($camposSensivel);} ?></small></a>
            </li>
            <li role="presentation" class=""><a href="#tab_content5" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Campos Anonizados<small class="badge"> <?php if(empty($camposAnonizado)){echo 0;}else{echo count($camposAnonizado);} ?></small></a>
            </li>
          </ul>
          <div id="myTabContent" class="tab-content">
            <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
              <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="x_panel">
                    <div class="x_title">
                      <h2>Grupos</h2>
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
                            <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 5px;" aria-sort="ascending" aria-label="First name: activate to sort column descending">ID Grupo</th>
                            <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 10px;" aria-label="Last name: activate to sort column ascending">Descrição</th>
                            <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 10px;" aria-label="Last name: activate to sort column ascending">Total Usuário</th>
                          </tr>
                        </thead>
                        <tbody> 
                     
                          <?php foreach ($grupoProg as  $value):?>
              
                            <tr>
                           
                            <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $value['idLegGrupo'] ?></font></font></td>
                            <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $value['descAbrev'] ?></font></font></td>
                            <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><small class="badge"><?php echo $value['totalUsuario'] ?></small></font></font></td>

              
                            </tr>
                          <?php endforeach; ?>

                         </tbody>
                      </table></div></div></div>
                      
                      
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
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
                    <div class="x_content">
                      <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer"><div class="row"><div class="col-sm-6"></div><div class="col-sm-6"></div></div><div class="row"><div class="col-sm-12"><table  class="tabela table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info" style="width: 100%;">
                        <thead>
                          <tr role="row">
                            <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 5px;" aria-sort="ascending" aria-label="First name: activate to sort column descending">ID Totvs</th>
                            <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 10px;" aria-label="Last name: activate to sort column ascending">Nome Usuário</th>
                            <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 10px;" aria-label="Last name: activate to sort column ascending">ID Grupo</th>
                            <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 10px;" aria-label="Last name: activate to sort column ascending">Descrição</th>
                            <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 10px;" aria-label="Last name: activate to sort column ascending">Função</th>
                            <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 10px;" aria-label="Last name: activate to sort column ascending">Situação do usuário</th>
                          </tr>
                        </thead>
                        <tbody> 
                     
                          <?php foreach ($usuario as  $value):?>
            
                            <tr  onclick="location.href='<?php echo URL ?>/Usuario/dados_usuario/<?php echo $value['idUsuario'] ?>';loadingPagia()">
                               
                                <td><?php echo $value['nome_usuario'] ?></td>
                                <td><?php echo $value['cod_usuario'] ?></td>
                                <td><?php echo $value['idLegGrupo'] ?></td>
                                <td><?php echo $value['descAbrev'] ?></td>
                                <td><?php echo $value['cod_funcao'] ?></td>  
                                <td class="tesxt-center"><span class="badge label-primary"><?php echo ($value['ativo'] == 1) ? 'Ativo' : 'Inativo'  ?></span></td>  

                            </tr>
                         <?php endforeach; ?>

                         </tbody>
                      </table></div></div></div>
                      
                      
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div role="tabpanel" class="tab-pane fade active in" id="tab_content3" aria-labelledby="home-tab">
              <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="x_panel">
                    <div class="x_title">
                      <h2>Dados Pessoais</h2>
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
                            <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 5px;" aria-sort="ascending" aria-label="First name: activate to sort column descending">Nome Campo</th>
                            <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 10px;" aria-label="Last name: activate to sort column ascending">Dado Sensivel</th>
                            <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 10px;" aria-label="Last name: activate to sort column ascending">Dado Anonizado</th>
                          </tr>
                        </thead>
                        <tbody> 
                     
                          <?php foreach ($camposPessoal as  $value):?>
              
                            <tr>
                           
                            <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $value['Nome'] ?></font></font></td>
                            <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $value['Sensivel'] ?></font></font></td>
                            <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $value['Anonizado'] ?></font></font></td>

                            </tr>
                          <?php endforeach; ?>

                         </tbody>
                      </table></div></div></div>
                      
                      
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div role="tabpanel" class="tab-pane fade active in" id="tab_content4" aria-labelledby="home-tab">
              <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="x_panel">
                    <div class="x_title">
                      <h2>Dados Sensiveis</h2>
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
                            <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 5px;" aria-sort="ascending" aria-label="First name: activate to sort column descending">Nome Campo</th>
                            <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 10px;" aria-label="Last name: activate to sort column ascending">Dado Pessoal</th>
                            <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 10px;" aria-label="Last name: activate to sort column ascending">Dado Anonizado</th>
                          </tr>
                        </thead>
                        <tbody> 
                     
                          <?php foreach ($camposSensivel as  $value):?>
              
                            <tr>
                           
                            <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $value['Nome'] ?></font></font></td>
                            <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $value['Pessoal'] ?></font></font></td>
                            <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $value['Anonizado'] ?></font></font></td>

                            </tr>
                          <?php endforeach; ?>

                         </tbody>
                      </table></div></div></div>
                      
                      
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div role="tabpanel" class="tab-pane fade active in" id="tab_content5" aria-labelledby="home-tab">
              <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="x_panel">
                    <div class="x_title">
                      <h2>Dados Anonizados</h2>
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
                            <th class="sorting_asc" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 5px;" aria-sort="ascending" aria-label="First name: activate to sort column descending">Nome Campo</th>
                            <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 10px;" aria-label="Last name: activate to sort column ascending">Dado Pessoal</th>
                            <th class="sorting" tabindex="0" aria-controls="datatable-responsive" rowspan="1" colspan="1" style="width: 10px;" aria-label="Last name: activate to sort column ascending">Dado Sensivel</th>
                          </tr>
                        </thead>
                        <tbody> 
                     
                          <?php foreach ($camposAnonizado as  $value):?>
              
                            <tr>
                           
                            <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $value['Nome'] ?></font></font></td>
                            <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $value['Pessoal'] ?></font></font></td>
                            <td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo $value['Sensivel'] ?></font></font></td>

                            </tr>
                          <?php endforeach; ?>

                         </tbody>
                      </table></div></div></div>
                      
                      
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</form>