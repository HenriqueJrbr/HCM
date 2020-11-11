
<div class="x_content">
      <div class="" role="tabpanel" data-example-id="togglable-tabs">
        <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
          <li role="presentation" class="active"><a href="#tab_content1" id="profile-tab1" role="tab" data-toggle="tab" aria-expanded="true">Usuários Gestor <span class="badge"><?php //echo count($grupo) ?></a>
          </li>
          <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">Grupos Gestor <span class="badge"><?php //echo count($programas) ?></span></a>
          </li>
         
        </ul>
        <div id="myTabContent" class="tab-content">
          <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="profile-tab1"><!--Inicio Tabela 1 -->
            <br>
              <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer"><div class="row"><div class="col-sm-6"></div><div class="col-sm-6"></div></div><div class="row"><div class="col-sm-12"><</div></div></div>
          </div><!--Fim Tabela 1 -->

          <div role="tabpanel" class="tab-pane fade active in" id="tab_content2" aria-labelledby="profile-tab2"><!--Inicio Tabela 2 -->
            <br>
              <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer"><div class="row"><div class="col-sm-6"></div><div class="col-sm-6"></div></div><div class="row"><div class="col-sm-12"><</div></div></div>
          </div><!--Fim Tabela 2 -->
          
        </div>
      </div>
    </div>