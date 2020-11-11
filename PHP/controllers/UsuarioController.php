<?php
class UsuarioController extends Controller {

    public function __construct() {
        parent::__construct();
         $login = new Login();
        
        if(!$login->isLogin()){
              header('Location: '.URL.'/login');   
        }else{
            if($login->validaTrocaSenha() == true){
                header('Location: '.URL.'/login/trocaSenha');
            }
        }
    }

    public function index() {
        $dados = array();

        $usr = new Usuario();
        $home = new Home();

        //$dados['usuarios'] = $usr->carregaUsuario($_SESSION['empresaid']);
        $dados['contUsuario'] = $home->contaUsuario($_SESSION['empresaid']);

        if(isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa']) ){
          $empresa = new Home();
          $empresaId = addslashes($_POST['empresa']);

          $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
          $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
          $_SESSION['empresaid'] = $empresaId;

            
            header('Location: '.URL);
        }
     
        
        $this->loadTemplate('usuario', $dados);
    }


  public function carregaDuplicado(){
     $dados = array();
     $d = new Usuario();

     
        if(isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa']) ){
          $empresa = new Home();
          $empresaId = addslashes($_POST['empresa']);

          $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
          $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
          $_SESSION['empresaid'] = $empresaId;

            
            header('Location: '.URL);
        }

     if(!empty($_POST['idUser'])) {
          
          $dados['duplicado'] = $d->carregaDuplicado($_POST['idUser'],$_SESSION['empresaid']);

          foreach($dados['duplicado'] as $valor){
            echo  '<tr>'.
                      '<td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">'.utf8_encode($valor['idLegGrupo']).'</font></font></td>'.
                      '<td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">'.utf8_encode($valor['cod_programa']). '</font></font></td>'.
                      '<td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">'.utf8_encode($valor['descricao_programa']). '</font></font></td>'.
                  '</tr>';
          } 
        }
  }

  public function dados_usuario($id){
      if(isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa']) ){
          $empresa = new Home();
          $empresaId = addslashes($_POST['empresa']);

          $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
          $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
          $_SESSION['empresaid'] = $empresaId;

            
            header('Location: '.URL);
      }

    $dados = array();
    $d = new Usuario();
    $dados['grupo'] = $d->carregaGruposUsuario($id,$_SESSION['empresaid']);
    $dados['grupo2'] = $d->carregaGruposUsuarioFoto($id,$_SESSION['empresaid']);


    $dados['totalAcesso'] = $d->countAcessosUsuario($id);
    $dados['totalAcesso2'] = $d->countAcessosUsuarioFoto($id);
    $dados['usuario'] = $d->usuarioSelecionado($id);

    if($dados['usuario'][4] != ''){
      $dados['dadosGestor'] = $d->dadosGestor($dados['usuario'][4]);
    }else{
      $dados['dadosGestor']  = '1';
    }
      $processo = new Processo();
      $dados['dataSnapshot'] = $processo->dataSnapshot();

    $this->loadTemplate('dados_usuario', $dados);
  }

  /**
     * método para criação de jquery datatable na tela de edição de grupos para tab usuários. Não utilizado ainda
     */
    public function ajaxDatatableProgUrs()
    {
        $dados = array();
        $data = array();
        $u = new Usuario();

        // usado pelo order by
        $fields = array(
            //1   => 'u.z_sga_usuarios_id',
            0   => 'g.idLegGrupo',
            1   => 'p.cod_programa',
            2   => 'p.cod_programa',
            3   => 'p.descricao_programa',
            4   => 'p.descricao_rotina'
        );
        
        // Variaveis usadas na paginação do Jquery DataTable
        $search = (isset($_POST["search"]["value"])) ? $_POST["search"]["value"] : '';
        $orderColumn = (isset($_POST['order']['0']['column'])) ? $fields[$_POST['order']['0']['column']] : '';
        $orderDir = (isset($_POST['order']['0']['dir'])) ? $_POST['order']['0']['dir'] : 'ASC';
        $limit = (isset($_POST["length"]) && $_POST["length"] != -1) ? $_POST['length'] : '10';
        $offset = (isset($_POST["length"]) && $_POST["length"] != -1) ? $_POST['start'] : '0';        

        // Traz apenas 10 usuarios utilizando paginação
        $dados = $u->carregaDatatableProgramasUsuario($search, $orderColumn, $orderDir, $offset, $limit, $_POST['id'], $_SESSION['empresaid']);

        // Traz todos os usuarios
        $total_all_records = $u->getCountTableProg($search, $fields, $_POST['id'], $_SESSION['empresaid']);

        // cria linhas customizadas no jquery datatable.
        if (count($dados) > 0):
            foreach ($dados as $key => $value):
                $sub_dados = array();                
                $sub_dados[] = '<td>'.$value['grupos'].'</td>';        
                $sub_dados[] = '<td>'.$value['cod_programa'].'</td>';
                $sub_dados[] = '<td>'.$value['descricao_programa'].'</td>';
                $sub_dados[] = '<td>'.(empty($value['ajuda_programa']) ? 'Não Cadastrado' : $value['ajuda_programa']).'</td>';
                $sub_dados[] = '<td>'.$value['descricao_rotina'].'</td>';
                $sub_dados[] = '<td>'.($value['especific'] == 'N' ? 'Não' : 'Sim').'</td>';
                $data[] = $sub_dados;
            endforeach;
        endif;

        $output = array(
            "draw" => intval(isset($_POST["draw"]) ? $_POST["draw"] : ''),
            "recordsTotal" => count($dados),
            "recordsFiltered" => $total_all_records,
            "data" => $data
        );
        echo json_encode($output);
    }

  public function ajaxCarregaProgUrs(){
    $dados = array();
    $id = addslashes($_POST['id']);
    $d = new Usuario();
    $dados['prog'] = $d->carregaProgramasUsuario($id,$_SESSION['empresaid']);

    $html = "";
    foreach ($dados['prog'] as  $value) {

      if(empty($value['ajuda_programa'])){
        $ajuda_programa = "Não Cadastrado";
      }else{
        $ajuda_programa = $value['ajuda_programa'];
      }
      $html .= '<tr>';
        $html .= '<td>'.$value['grupos'].'</td>';
        //$html .= '<td>'.$value['descAbrev'].'</td>';
        $html .= '<td>'.$value['cod_programa'].'</td>';
        $html .= '<td>'.$value['descricao_programa'].'</td>';
        $html .= '<td>'.$ajuda_programa.'</td>';
        $html .= '<td>'.$value['descricao_rotina'].'</td>';
        $html .= '<td>'.($value['especific'] == 'N' ? 'Não' : 'Sim').'</td>';
      $html .= '</tr>';
    }
    echo $html;
  }

  public function ajaxCarregaAbaPessoais()
    {        
        $dados = array();
        $data = array();
        $s = new Usuario();
        $id = addslashes($_POST['id']);


        // usado pelo order by
        $fields = array(
            0   => 'g.idLegGrupo',
            1   => 'p.cod_programa',
            2   => 'p.descricao_programa',
            3   => 'zslc.name',
            4   => 'zslc.sensitive',
            5   => 'zslc.sensitive'
        );

        // Variaveis usadas na paginação do Jquery DataTable
        $search = (isset($_POST["search"]["value"])) ? $_POST["search"]["value"] : '';
        $orderColumn = (isset($_POST['order']['0']['column'])) ? $fields[$_POST['order']['0']['column']] : '';
        $orderDir = (isset($_POST['order']['0']['dir'])) ? $_POST['order']['0']['dir'] : 'ASC';
        $limit = (isset($_POST["length"]) && $_POST["length"] != -1) ? $_POST['length'] : '10';
        $offset = (isset($_POST["length"]) && $_POST["length"] != -1) ? $_POST['start'] : '0';

        
        // Traz apenas 10 usuarios utilizando paginação
        $dados = $s->carregaAbaPessoaisUsuario($search, $orderColumn, $orderDir, $offset, $limit, $id, $_SESSION['empresaid']);

        // Traz todos os usuarios
        $total_all_records = $s->getCountTableAbaPessoais("z_sga_programas p", $search, array('DISTINCT zslc.name'), '', $_SESSION['empresaid'], $_POST['id']);

        // Cria linhas customizadas no jquery datatable.
        if (count($dados) > 0):
            foreach ($dados as $key => $value):
                $sub_dados = array();            
                $sub_dados[] = $value["grupo"];                
                $sub_dados[] = $value["cod_programa"];
                $sub_dados[] = $value["descricao_programa"];
                $sub_dados[] = $value["Nome"];
                $sub_dados[] = $value['Pessoal'];
                $sub_dados[] = $value['Sensivel'];
                $data[] = $sub_dados;
            endforeach;
        endif;

        $output = array(
            "draw" => intval(isset($_POST["draw"]) ? $_POST["draw"] : ''),
            "recordsTotal" => count($dados),
            "recordsFiltered" => $total_all_records,
            "data" => $data
        );
        echo json_encode($output);
    }

    public function ajaxCarregaAbaSensiveis()
    {        
        $dados = array();
        $data = array();
        $s = new Usuario();


        // usado pelo order by
        $fields = array(
            0   => 'g.idLegGrupo',
            1   => 'p.cod_programa',
            2   => 'p.descricao_programa',
            3   => 'zslc.name',
            4   => 'zslc.sensitive',
            5   => 'zslc.sensitive'
        );

        // Variaveis usadas na paginação do Jquery DataTable
        $search = (isset($_POST["search"]["value"])) ? $_POST["search"]["value"] : '';
        $orderColumn = (isset($_POST['order']['0']['column'])) ? $fields[$_POST['order']['0']['column']] : '';
        $orderDir = (isset($_POST['order']['0']['dir'])) ? $_POST['order']['0']['dir'] : 'ASC';
        $limit = (isset($_POST["length"]) && $_POST["length"] != -1) ? $_POST['length'] : '10';
        $offset = (isset($_POST["length"]) && $_POST["length"] != -1) ? $_POST['start'] : '0';

        
        // Traz apenas 10 usuarios utilizando paginação
        $dados = $s->carregaAbaSensiveisUsuario($search, $orderColumn, $orderDir, $offset, $limit, $_POST['id'], $_SESSION['empresaid']);

        // Traz todos os usuarios
        $total_all_records = $s->getCountTableAbaSensiveis("z_sga_programas p", $search, array('DISTINCT zslc.name'), '', $_SESSION['empresaid'], $_POST['id']);

        // Cria linhas customizadas no jquery datatable.
        if (count($dados) > 0):
            foreach ($dados as $key => $value):
                $sub_dados = array();            
                $sub_dados[] = $value["grupo"];                
                $sub_dados[] = $value["cod_programa"];
                $sub_dados[] = $value["descricao_programa"];
                $sub_dados[] = $value["Nome"];
                $sub_dados[] = $value['Pessoal'];
                $sub_dados[] = $value['Sensivel'];
                $data[] = $sub_dados;
            endforeach;
        endif;

        $output = array(
            "draw" => intval(isset($_POST["draw"]) ? $_POST["draw"] : ''),
            "recordsTotal" => count($dados),
            "recordsFiltered" => $total_all_records,
            "data" => $data
        );
        echo json_encode($output);
    }

  public function ajaxCarregaAbaAnonizados()
    {        
        $dados = array();
        $data = array();
        $s = new Usuario();


        // usado pelo order by
        $fields = array(
            0   => 'g.idLegGrupo',
            1   => 'p.cod_programa',
            2   => 'p.descricao_programa',
            3   => 'zslc.name',
            4   => 'zslc.sensitive',
            5   => 'zslc.sensitive'
        );

        // Variaveis usadas na paginação do Jquery DataTable
        $search = (isset($_POST["search"]["value"])) ? $_POST["search"]["value"] : '';
        $orderColumn = (isset($_POST['order']['0']['column'])) ? $fields[$_POST['order']['0']['column']] : '';
        $orderDir = (isset($_POST['order']['0']['dir'])) ? $_POST['order']['0']['dir'] : 'ASC';
        $limit = (isset($_POST["length"]) && $_POST["length"] != -1) ? $_POST['length'] : '10';
        $offset = (isset($_POST["length"]) && $_POST["length"] != -1) ? $_POST['start'] : '0';

        
        // Traz apenas 10 usuarios utilizando paginação
        $dados = $s->carregaAbaAnonizadosUsuario($search, $orderColumn, $orderDir, $offset, $limit, $_POST['id'], $_SESSION['empresaid']);

        // Traz todos os usuarios
        $total_all_records = $s->getCountTableAbaAnonizados("z_sga_programas p", $search, array('DISTINCT zslc.name'), '', $_SESSION['empresaid'], $_POST['id']);

        // Cria linhas customizadas no jquery datatable.
        if (count($dados) > 0):
            foreach ($dados as $key => $value):
                $sub_dados = array();            
                $sub_dados[] = $value["grupo"];                
                $sub_dados[] = $value["cod_programa"];
                $sub_dados[] = $value["descricao_programa"];
                $sub_dados[] = $value["Nome"];
                $sub_dados[] = $value['Pessoal'];
                $sub_dados[] = $value['Sensivel'];
                $data[] = $sub_dados;
            endforeach;
        endif;

        $output = array(
            "draw" => intval(isset($_POST["draw"]) ? $_POST["draw"] : ''),
            "recordsTotal" => count($dados),
            "recordsFiltered" => $total_all_records,
            "data" => $data
        );
        echo json_encode($output);
    }

    public function AjaxCarregaBadges(){
      $dados = array();
      $s = new Usuario();

      $dados['totalCamposPessoais'] = $s->getCountTableAbaPessoais("z_sga_programas p", '', array(0 => 'DISTINCT zslc.name'), '', $_SESSION['empresaid'], $_POST['id']);
      $dados['totalCamposSensiveis'] = $s->getCountTableAbaSensiveis("z_sga_programas p", '', array(0 => 'DISTINCT zslc.name'), '', $_SESSION['empresaid'], $_POST['id']);
      $dados['totalCamposAnonizados'] = $s->getCountTableAbaAnonizados("z_sga_programas p", '', array(0 => 'DISTINCT zslc.name'), '', $_SESSION['empresaid'], $_POST['id']);

      echo json_encode(array(
        'totalCamposPessoais' => $dados['totalCamposPessoais'],
        'totalCamposSensiveis' => $dados['totalCamposSensiveis'],
        'totalCamposAnonizados' => $dados['totalCamposAnonizados']

    ));
      
    }

  /**
     * método para criação de jquery datatable na tela de edição de grupos para tab usuários. Não utilizado ainda
     */
    public function ajaxDatatableProgUrsFoto()
    {
        $dados = array();
        $data = array();
        $u = new Usuario();

        // usado pelo order by
        $fields = array(
            //1   => 'u.z_sga_usuarios_id',
            0   => 'g.idLegGrupo',
            1   => 'p.cod_programa',
            2   => 'p.cod_programa',
            3   => 'p.descricao_programa',
            4   => 'p.descricao_rotina'
        );
        
        // Variaveis usadas na paginação do Jquery DataTable
        $search = (isset($_POST["search"]["value"])) ? $_POST["search"]["value"] : '';
        $orderColumn = (isset($_POST['order']['0']['column'])) ? $fields[$_POST['order']['0']['column']] : '';
        $orderDir = (isset($_POST['order']['0']['dir'])) ? $_POST['order']['0']['dir'] : 'ASC';
        $limit = (isset($_POST["length"]) && $_POST["length"] != -1) ? $_POST['length'] : '10';
        $offset = (isset($_POST["length"]) && $_POST["length"] != -1) ? $_POST['start'] : '0';        

        // Traz apenas 10 usuarios utilizando paginação
        $dados = $u->carregaDatatableProgramasUsuarioFoto($search, $orderColumn, $orderDir, $offset, $limit, $_POST['id'], $_SESSION['empresaid']);

        // Traz todos os usuarios
        $total_all_records = $u->getCountTableProgFoto($search, $fields, $_POST['id'], $_SESSION['empresaid']);

        // cria linhas customizadas no jquery datatable.
        if (count($dados) > 0):
            foreach ($dados as $key => $value):
                $sub_dados = array();                
                $sub_dados[] = '<td>'.$value['grupos'].'</td>';        
                $sub_dados[] = '<td>'.$value['cod_programa'].'</td>';
                $sub_dados[] = '<td>'.$value['descricao_programa'].'</td>';
                $sub_dados[] = '<td>'.(empty($value['ajuda_programa']) ? 'Não Cadastrado' : $value['ajuda_programa']).'</td>';
                $sub_dados[] = '<td>'.$value['descricao_rotina'].'</td>';
                $sub_dados[] = '<td>'.($value['especific'] == 'N' ? 'Não' : 'Sim').'</td>';
                $data[] = $sub_dados;
            endforeach;
        endif;

        $output = array(
            "draw" => intval(isset($_POST["draw"]) ? $_POST["draw"] : ''),
            "recordsTotal" => count($dados),
            "recordsFiltered" => $total_all_records,
            "data" => $data
        );
        echo json_encode($output);
    }

  public function ajaxCarregaProgUrsFoto(){
    $dados = array();
    $id = addslashes($_POST['id']);
    $d = new Usuario();
    $dados['prog'] = $d->carregaProgramasUsuarioFoto($id,$_SESSION['empresaid']);

    $html = "";
    foreach ($dados['prog'] as  $value) {

      if(empty($value['ajuda_programa'])){
        $ajuda_programa = "Não Cadastrado";
      }else{
        $ajuda_programa = $value['ajuda_programa'];
      }
      $html .= '<tr>';
        //$html .= '<td>'.$value['idLegGrupo'].'</td>';
        $html .= '<td>'.$value['grupos'].'</td>';
        $html .= '<td>'.$value['cod_programa'].'</td>';
        $html .= '<td>'.$value['descricao_programa'].'</td>';
        $html .= '<td>'.$ajuda_programa.'</td>';
        $html .= '<td>'.$value['descricao_rotina'].'</td>';
        $html .= '<td>'.($value['especific'] == 'N' ? 'Não' : 'Ssim').'</td>';
      $html .= '</tr>';
    }
    echo $html;
  }

  public function ajaxCarregaProgDuplicadoUrs(){
    $dados = array();
    $id = addslashes($_POST['id']);
    $d = new Usuario();
    $dados['progDuplicado'] = $d->carregaProgramasDuplicado($id,$_SESSION['empresaid']);

    $html = "";
    foreach ($dados['progDuplicado'] as  $value) {
      $html .= '<tr>';
        $html .= '<td>'.$value['cod_programa'].'</td>';        
        $html .= '<td>'.$value['descricao_programa'].'</td>';
        $html .= '<td>'.$value['descricao_rotina'].'</td>';
        $html .= '<td>'.($value['especific'] == 'N' ? 'Não' : 'Sim').'</td>';
        $html .= '<td>'.$value['grupos'].'</td>';
      $html .= '</tr>';
    }
    echo $html;
  }
  public function ajaxCarregaProgDuplicadoUrsFoto(){
    $dados = array();
    $id = addslashes($_POST['id']);
    $d = new Usuario();
    $dados['progDuplicado'] = $d->carregaProgramasDuplicadoFoto($id,$_SESSION['empresaid']);

    $html = "";
    foreach ($dados['progDuplicado'] as  $value) {
      $html .= '<tr>';
        $html .= '<td>'.$value['cod_programa'].'</td>';
        $html .= '<td>'.$value['descricao_programa'].'</td>';
        $html .= '<td>'.$value['descricao_rotina'].'</td>';
        $html .= '<td>'.($value['especific'] == 'N' ? 'Não' : 'Sim').'</td>';
        $html .= '<td>'.$value['grupos'].'</td>';
      $html .= '</tr>';
    }
    echo $html;
  }


  public function ajaxMatrizDeRisco(){
    $dados = array();
    $id = addslashes($_POST['id']);
    $d = new Usuario();
    $dados['conflitos'] = $d->carregaConflitos($id,$conta="");

    $area = "";
    $risco = "";
    $idCollapseArea = 10000;
    $next = array();
    $idTabela = 0;

    $html = "";

    $html .= '<div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                  <div class="row">
                    <div class="col-sm-6"></div>
                    <div class="col-sm-6"></div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="panel-group" id="accordion">';

        foreach ($dados['conflitos'] as $value) {
        

            if($area != $value['descArea']){
                $area = $value['descArea'];
             $html .='<div class="panel panel-default">
                          <div class="panel-heading">
                            <h4 class="panel-title">
                              <a data-toggle="collapse" data-parent="#accordion" href="#collapse'.$idCollapseArea.'"> 
                              Área '.$value["descArea"].'</a>
                            </h4>
                          </div>
                          <div id="collapse'.$idCollapseArea.'" class="panel-collapse collapse">
                            <div class="panel-body">';
            }
            if($risco != $value['codRisco']){
              $risco = $value['codRisco'];
              $idTabela = $idTabela + 1;
              $html .=  "<h5><strong>".$value["codRisco"]."</strong> <span class=\"badge\" style=\"background-color:".(($value['mitigado'] == 'Mitigado') ? '#26B99A' : '#d9534f' ).'" >'.$value['mitigado'] . "</span> - ".$value["descRisco"]."</h5><br>";

              $html .= '<table class="tabela table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="DataTables_Table_0_info" style="width: 100%;" id="DataTables_Table_<?php echo $idTabela?>">
                          <thead>
                          <tr role="row"><td><strong>Composiçao do Risco</strong></td></tr>
                          <tr role="row">
                            <th>Grau de Risco</th>
                            <th>Processo Referencia</th>
                            <th>Programas do processo</th>
                            <th>Processos vinculados</th>
                            <th>Programas do processo</th>
                          </tr>
                         </thead>
                         <tbody>';

               }
                $html .='<tr>';
                $html .= '<td bgcolor="'.$value["bgcolor"].'"><font color="'.$value["fgcolor"].'">'.$value["grau"].'</font></td>';
                $html .= '<td>'.$value["processoPri"].'</td>';
                $html .= '<td>'.$value["progspPri"].'</td>';
                $html .= '<td>'.$value["processoSec"].'</td>';
                $html .=  '<td>'.$value["progspSec"].'</td>'; 
                $html .='</tr>';

              $next = next($dados['conflitos']);
              if($risco != $next['codRisco']){
                $html .='</tbody></table>';
              }

               
              if($area != $next['descArea']){
                $html .='</div></div></div>';
                $idCollapseArea = $idCollapseArea+1;
              }
        }

        $html .='</div></div></div></div>';

        echo $html;
  }

  public function ajaxMatrizDeRiscoFoto(){
    $dados = array();
    $id = addslashes($_POST['id']);
    $d = new Usuario();
    $dados['conflitos'] = $d->carregaConflitosFoto($id);

    $area = "";
    $risco = "";
    $idCollapseArea = 10000;
    $next = array();
    $idTabela = 0;

    $html = "";

    $html .= '<div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                  <div class="row">
                    <div class="col-sm-6"></div>
                    <div class="col-sm-6"></div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="panel-group" id="accordion">';

        foreach ($dados['conflitos'] as $value) {
        

            if($area != $value['descArea']){
                $area = $value['descArea'];
             $html .='<div class="panel panel-default">
                          <div class="panel-heading">
                            <h4 class="panel-title">
                              <a data-toggle="collapse" data-parent="#accordion" href="#collapse'.$idCollapseArea.'">
                              Área '.$value["descArea"].'</a>
                            </h4>
                          </div>
                          <div id="collapse'.$idCollapseArea.'" class="panel-collapse collapse">
                            <div class="panel-body">';
            }
            if($risco != $value['codRisco']){
              $risco = $value['codRisco'];
              $idTabela = $idTabela + 1;
              $html .=  "<h5><strong>".$value["codRisco"]."</strong> <span class=\"badge\" style=\"background-color:".(($value['mitigado'] == 'Mitigado') ? '#26B99A' : '#d9534f' ).'" >'.$value['mitigado'] . "</span> - ".$value["descRisco"]."</h5><br>";

              $html .= '<table class="tabela table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="DataTables_Table_0_info" style="width: 100%;" id="DataTables_Table_<?php echo $idTabela?>">
                          <thead>
                          <tr role="row"><td><strong>Composiçao do Risco</strong></td></tr>
                          <tr role="row">
                            <th>Grau de Risco</th>
                            <th>Processo Referencia</th>
                            <th>Programas do processo</th>
                            <th>Processos vinculados</th>
                            <th>Programas do processo</th>
                          </tr>
                         </thead>
                         <tbody>';

               }
                $html .='<tr>';
                $html .= '<td bgcolor="'.$value["bgcolor"].'"><font color="'.$value["fgcolor"].'">'.$value["grau"].'</font></td>';
                $html .= '<td>'.$value["processoPri"].'</td>';
                $html .= '<td>'.$value["progspPri"].'</td>';
                $html .= '<td>'.$value["processoSec"].'</td>';
                $html .=  '<td>'.$value["progspSec"].'</td>'; 
                $html .='</tr>';

              $next = next($dados['conflitos']);
              if($risco != $next['codRisco']){
                $html .='</tbody></table>';
              }

               
              if($area != $next['descArea']){
                $html .='</div></div></div>';
                $idCollapseArea = $idCollapseArea+1;
              }
        }

        $html .='</div></div></div></div>';

        echo $html;
  }

  public function ajaxMatrizProcesDeRisco(){
    $dados = array();
    $id = addslashes($_POST['id']);
    $d = new Usuario();
    $dados['conflitoProcesso'] = $d->carregaConflitosProcesso($id);

    $area = "";
    $risco = "";
    $idCollapseArea = 20000;
    $next = array();
    $idTabela = 0;

    $html = "";

    $html .= '<div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                  <div class="row">
                    <div class="col-sm-6"></div>
                    <div class="col-sm-6"></div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="panel-group" id="accordion">';

        foreach ($dados['conflitoProcesso'] as $value) {
        

            if($area != $value['GrupoProcesso']){
                $area = $value['GrupoProcesso'];
             $html .='<div class="panel panel-default">
                          <div class="panel-heading">
                            <h4 class="panel-title">
                              <a data-toggle="collapse" data-parent="#accordion" href="#collapse'.$idCollapseArea.'">
                              '.$value["GrupoProcesso"].'</a>
                            </h4>
                          </div>
                          <div id="collapse'.$idCollapseArea.'" class="panel-collapse collapse">
                            <div class="panel-body">';
            }
            if($risco != $value['descProcesso']){
              $risco = $value['descProcesso'];
              $idTabela = $idTabela + 1;
              $html .=  "<h5 style='color:#FF8000'><strong>".$value["descProcesso"]."</strong></h5><br>";

              $html .= '<table class="tabelaMatrizProcesso table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="DataTables_Table_0_info" style="width: 100%;" id="DataTables_Table_<?php echo $idTabela?>">
                          <thead>
                         
                          <tr role="row">
                            <th>Programa</th>
                            <th>Descrição</th>
                            <th>Observação</th>
                            <th>Grupos</th>
                          </tr>
                         </thead>
                         <tbody>';

               }

                if(empty($value["ajuda_programa"])){
                  $ajuda_programa = "Não Cadastrado";
                }else{
                  $ajuda_programa = $value["ajuda_programa"];
                }
                $html .='<tr>';
                $html .= '<td>'.$value["cod_programa"].'</td>';
                $html .= '<td>'.$value["descricao_programa"].'</td>';
                $html .= '<td>'.$ajuda_programa.'</td>';
                $html .= '<td>'.$value["Grupos"].'</td>';
                $html .='</tr>';

              $next = next($dados['conflitoProcesso']);
              if($risco != $next['descProcesso']){
                $html .='</tbody></table>';
              }

               
              if($area != $next['GrupoProcesso']){
                $html .='</div></div></div>';
                $idCollapseArea = $idCollapseArea+1;
              }
        }

        $html .='</div></div></div></div>';

        echo $html;
  }

    public function ajaxMatrizProcesDeRiscoFoto(){
    $dados = array();
    $id = addslashes($_POST['id']);
    $d = new Usuario();
    $dados['conflitoProcesso'] = $d->carregaConflitosProcessoFoto($id);

    $area = "";
    $risco = "";
    $idCollapseArea = 20000;
    $next = array();
    $idTabela = 0;

    $html = "";

    $html .= '<div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                  <div class="row">
                    <div class="col-sm-6"></div>
                    <div class="col-sm-6"></div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="panel-group" id="accordion">';

        foreach ($dados['conflitoProcesso'] as $value) {
        

            if($area != $value['GrupoProcesso']){
                $area = $value['GrupoProcesso'];
             $html .='<div class="panel panel-default">
                          <div class="panel-heading">
                            <h4 class="panel-title">
                              <a data-toggle="collapse" data-parent="#accordion" href="#collapse'.$idCollapseArea.'">
                               '.$value["GrupoProcesso"].'</a>
                            </h4>
                          </div>
                          <div id="collapse'.$idCollapseArea.'" class="panel-collapse collapse">
                            <div class="panel-body">';
            }
            if($risco != $value['descProcesso']){
              $risco = $value['descProcesso'];
              $idTabela = $idTabela + 1;
              $html .=  "<h5 style='color:#FF8000'><strong>".$value["descProcesso"]."</strong></h5><br>";

              $html .= '<table class="tabelaMatrizProcesso table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="DataTables_Table_0_info" style="width: 100%;" id="DataTables_Table_<?php echo $idTabela?>">
                          <thead>
                         
                          <tr role="row">
                            <th>Programa</th>
                            <th>Descrição</th>
                            <th>Observação</th>
                            <th>Grupos</th>
                          </tr>
                         </thead>
                         <tbody>';

               }

                if(empty($value["ajuda_programa"])){
                  $ajuda_programa = "Não Cadastrado";
                }else{
                  $ajuda_programa = $value["ajuda_programa"];
                }
                $html .='<tr>';
                $html .= '<td>'.$value["cod_programa"].'</td>';
                $html .= '<td>'.$value["descricao_programa"].'</td>';
                $html .= '<td>'.$ajuda_programa.'</td>';
                $html .= '<td>'.$value["Grupos"].'</td>';
                $html .='</tr>';

              $next = next($dados['conflitoProcesso']);
              if($risco != $next['descProcesso']){
                $html .='</tbody></table>';
              }

               
              if($area != $next['GrupoProcesso']){
                $html .='</div></div></div>';
                $idCollapseArea = $idCollapseArea+1;
              }
        }

        $html .='</div></div></div></div>';

        echo $html;
  }


  public function ajaxAcessoModulos(){
    $dados = array();
    $id = addslashes($_POST['id']);
    $d = new Usuario();
    $dados['acessoModulo'] = $d->carregaAcessoModulo($id);

    $sistema = "";
    $modulo = "";
    $colAreaRisco = 7000;
    $idCollapseArea = 60000;
    $idCollapseModulo = 100;
    $descModulo = "";
    $next = array();
    $idTabela = 0;

    $html = "";

    $html .= '<div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                  <div class="row">
                    <div class="col-sm-6"></div>
                    <div class="col-sm-6"></div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="panel-group" id="accordion">';

        foreach ($dados['acessoModulo'] as $value) {
        

            if($sistema != $value['des_sist_dtsul']){
                $sistema = $value['des_sist_dtsul'];
                
                $html .='<div class="panel panel-default">
                          <div class="panel-heading">
                            <h4 class="panel-title">
                              <a data-toggle="collapse" data-parent="#accordion" href="#collapse'.$idCollapseArea.'">
                              '.$value["des_sist_dtsul"].'</a>
                            </h4>
                          </div>
                          <div id="collapse'.$idCollapseArea.'" class="panel-collapse collapse in">
                            <div class="panel-body">';

            }

            if($descModulo != $value['descricao_modulo']){
                $descModulo = $value['descricao_modulo'];

              $html .= '<div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                  <div class="row">
                    <div class="col-sm-6"></div>
                    <div class="col-sm-6"></div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="panel-group" id="accordion2">';

                
                $html .='<div class="panel panel-default">
                          <div class="panel-heading">
                            <h4 class="panel-title">
                              <a data-toggle="collapse" data-parent="#accordion2" href="#collapse'.$idCollapseModulo.'">
                                  '.$value["descricao_modulo"].'</a>
                            </h4>
                          </div>
                          <div id="collapse'.$idCollapseModulo.'" class="panel-collapse collapse">
                            <div class="panel-body">';
            }

            if($modulo != $value['descricao_modulo']){
              $modulo = $value['descricao_modulo'];
              $idTabela = $idTabela + 1;
              $html .=  "<h5><strong>".$value["descricao_modulo"]."</strong></h5><br>";

              $html .= '<table class="tabelaMatrizProcesso table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="DataTables_Table_0_info" style="width: 100%;" id="DataTables_Table_<?php echo $idTabela?>">
                          <thead>
                         
                          <tr role="row">
                            <th>Rotina</th>
                            <th>Grupo</th>
                            <th>Quant.Programas</th>
                          </tr>
                         </thead>
                         <tbody>';

               }

                $prog = $value['Programas'];
                $descRotina = $value['descricao_rotina'];
                $html .='<tr onclick='."'".'abrirModal("'.$prog.'","'.$descRotina.'")'."'".'>';
                $html .= '<td>'.$value["descricao_rotina"].'</td>';
                $html .= '<td>'.$value["Grupos"].'</td>';
                $html .= '<td>'.$value["numProgramas"].'</td>';
                $html .='</tr>';

              $next = next($dados['acessoModulo']);
              if($modulo != $next['descricao_modulo']){
                $html .='</tbody></table>';
              }
              if($descModulo != $next['descricao_modulo']){
                $html .='</div></div></div>';
                 $html .='</div></div></div></div>';
                $idCollapseModulo = $idCollapseModulo+1;
              }

               
              if($sistema != $next['des_sist_dtsul']){
                $html .='</div></div></div>';
                $colAreaRisco = $colAreaRisco + 1;
                $idCollapseArea = $idCollapseArea+1;
              }
        }

        $html .='</div></div></div></div>';

        echo $html;
  }

  public function ajaxAcessoModulosFoto(){
    $dados = array();
    $id = addslashes($_POST['id']);
    $d = new Usuario();
    $dados['acessoModulo'] = $d->carregaAcessoModuloFoto($id);

    $sistema = "";
    $modulo = "";
    $colAreaRisco = 7000;
    $idCollapseArea = 60000;
    $idCollapseModulo = 100;
    $descModulo = "";
    $next = array();
    $idTabela = 0;

    $html = "";

    $html .= '<div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                  <div class="row">
                    <div class="col-sm-6"></div>
                    <div class="col-sm-6"></div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="panel-group" id="accordion">';

        foreach ($dados['acessoModulo'] as $value) {
        

            if($sistema != $value['des_sist_dtsul']){
                $sistema = $value['des_sist_dtsul'];
                
                $html .='<div class="panel panel-default">
                          <div class="panel-heading">
                            <h4 class="panel-title">
                              <a data-toggle="collapse" data-parent="#accordion" href="#collapse'.$idCollapseArea.'">
                              '.$value["des_sist_dtsul"].'</a>
                            </h4>
                          </div>
                          <div id="collapse'.$idCollapseArea.'" class="panel-collapse collapse in">
                            <div class="panel-body">';

            }

            if($descModulo != $value['descricao_modulo']){
                $descModulo = $value['descricao_modulo'];

              $html .= '<div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                  <div class="row">
                    <div class="col-sm-6"></div>
                    <div class="col-sm-6"></div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="panel-group" id="accordion2">';

                
                $html .='<div class="panel panel-default">
                          <div class="panel-heading">
                            <h4 class="panel-title">
                              <a data-toggle="collapse" data-parent="#accordion2" href="#collapse'.$idCollapseModulo.'">
                                  '.$value["descricao_modulo"].'</a>
                            </h4>
                          </div>
                          <div id="collapse'.$idCollapseModulo.'" class="panel-collapse collapse">
                            <div class="panel-body">';
            }

            if($modulo != $value['descricao_modulo']){
              $modulo = $value['descricao_modulo'];
              $idTabela = $idTabela + 1;
              $html .=  "<h5><strong>".$value["descricao_modulo"]."</strong></h5><br>";

              $html .= '<table class="tabelaMatrizProcesso table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="DataTables_Table_0_info" style="width: 100%;" id="DataTables_Table_<?php echo $idTabela?>">
                          <thead>
                         
                          <tr role="row">
                            <th>Rotina</th>
                            <th>Grupo</th>
                            <th>Quant.Programas</th>
                          </tr>
                         </thead>
                         <tbody>';

               }

                $prog = $value['Programas'];
                $descRotina = $value['descricao_rotina'];
                $html .='<tr onclick='."'".'abrirModal("'.$prog.'","'.$descRotina.'")'."'".'>';
                $html .= '<td>'.$value["descricao_rotina"].'</td>';
                $html .= '<td>'.$value["Grupos"].'</td>';
                $html .= '<td>'.$value["numProgramas"].'</td>';
                $html .='</tr>';

              $next = next($dados['acessoModulo']);
              if($modulo != $next['descricao_modulo']){
                $html .='</tbody></table>';
              }
              if($descModulo != $next['descricao_modulo']){
                $html .='</div></div></div>';
                 $html .='</div></div></div></div>';
                $idCollapseModulo = $idCollapseModulo+1;
              }

               
              if($sistema != $next['des_sist_dtsul']){
                $html .='</div></div></div>';
                $colAreaRisco = $colAreaRisco + 1;
                $idCollapseArea = $idCollapseArea+1;
              }
        }

        $html .='</div></div></div></div>';

        echo $html;
  }

    /**
     * método para criação de jquery datatable na tela de usuários
     */
    public function ajaxDatatableUsuarios()
    {
        $dados = array();
        $data = array();
        $u = new Usuario();


        // usado pelo order by
        $fields = array(
            0 => 'idUsuario',
            1 => 'nome_usuario',
            2 => 'cod_usuario',
            3 => 'gestor',
            4 => 'cod_funcao',
            5 => 'nroInstancias',
            6 => 'nroRiscos'
        );

        // Variaveis usadas na paginação do Jquery DataTable
        $search = (isset($_POST["search"]["value"])) ? $_POST["search"]["value"] : '';
        $orderColumn = (isset($_POST['order']['0']['column'])) ? $fields[$_POST['order']['0']['column']] : '';
        $orderDir = (isset($_POST['order']['0']['dir'])) ? $_POST['order']['0']['dir'] : 'ASC';
        $limit = (isset($_POST["length"]) && $_POST["length"] != -1) ? $_POST['length'] : '10';
        $offset = (isset($_POST["length"]) && $_POST["length"] != -1) ? $_POST['start'] : '0';

        // Traz apenas 10 usuarios utilizando paginação
        $dados = $u->carregaDatatableUsuario($search, $orderColumn, $orderDir, $offset, $limit);

        // relacionamentos entre tabelas
        $join = " 
            INNER JOIN
                z_sga_usuarios AS u ON userEmp.idUsuario = u.z_sga_usuarios_id
            LEFT JOIN
                z_sga_manut_funcao AS m ON u.cod_funcao = m.idFuncao
            LEFT JOIN
                v_sga_mtz_resumo_matriz_usuario AS rru 
                ON rru.idUsuario = userEmp.idUsuario AND rru.idEmpresa = userEmp.idEmpresa ";

        $fieldsToCount = array(
            //0 => 'userEmp.idUsuario',
            0 => 'u.z_sga_usuarios_id',
            1 => 'u.nome_usuario',
            2 => 'u.cod_usuario',
            3 => 'u.funcao',
            4 => 'u.cod_funcao'
        );

        // Traz todos os usuarios
        $total_all_records = $u->getCountTable("z_sga_usuario_empresa AS userEmp", $search, $fieldsToCount, $join, $_SESSION['empresaid']);

        // cria linhas customizadas no jquery datatable.
        if (count($dados) > 0):
            foreach ($dados as $key => $value):
                $sub_dados = array();
                $sub_dados[] = $value["idUsuario"];
                $sub_dados[] = $value["nome_usuario"];
                $sub_dados[] = $value["cod_usuario"];
                $sub_dados[] = (empty($value['gestor'])) ? "Usuario sem gestor" : $value['gestor'];
                $sub_dados[] = $value["cod_funcao"];
                $sub_dados[] = '<center><small class="badge">'.$value['nroInstancias'].'</small></center>';
                $sub_dados[] = '<center><small class="badge">'.$value['nroRiscos'].'</small></center>';
                $sub_dados[] = '<button type="button" class="btn btn-success btn-xs" data-toggle="modal" onclick="location.href=\'' . URL . '/Usuario/dados_usuario/'.$value['idUsuario'].'\'">Visualizar</button>';
                $data[] = $sub_dados;
            endforeach;
        endif;

        $output = array(
            "draw" => intval(isset($_POST["draw"]) ? $_POST["draw"] : ''),
            "recordsTotal" => count($dados),
            "recordsFiltered" => $total_all_records,
            "data" => $data
        );
        echo json_encode($output);
    }

    /*
    Esta função carrega e monta o datatable da pagina de grupos Usuario http://162.144.118.90:84/sga_v2/carregaDadosGrupo
  */
    public function ajaxCarregaDatatableUsr(){
        $data = array();
        $u = new Usuario();
        $tipo = $_POST['tipo'];
        $dados['carregaUsrGrupo'] = $u->carregaUsuario($_SESSION['empresaid'],$tipo);
       
     
        foreach ($dados['carregaUsrGrupo'] as $key => $value):
            $sub_dados = array();
            $sub_dados[] = $value["idUsuario"];
            $sub_dados[] = $value["nome_usuario"];
            $sub_dados[] = $value["cod_usuario"];
            $sub_dados[] = (empty($value['gestor'])) ? "Usuario sem gestor" : $value['gestor'];
            $sub_dados[] = $value["cod_funcao"];
            $sub_dados[] = '<center><small class="badge">'.$value['nroInstancias'].'</small></center>';
            $sub_dados[] = '<center><small class="badge">'.$value['nroRiscos'].'</small></center>';
            $sub_dados[] = '<center><small class="badge">'.(($value['ativo'] == 1) ? 'Ativo' : 'Inativo').'</small></center>';
            $sub_dados[] = '<button type="button" class="btn btn-success btn-xs" data-toggle="modal" onclick="location.href=\'' . URL . '/Usuario/dados_usuario/'.$value['idUsuario'].'\'">Visualizar</button>';
            $data[] = $sub_dados;
        endforeach;
        $output = array(
            "data" => (count($data) > 0 ) ? $data : ''
        );
        echo json_encode($dados['carregaUsrGrupo']);
    }

  public function usuarsioAcessos()
  {
    $data = array();

    if(isset($_POST['ok']) && !empty($_POST['ok']) && isset($_POST['empresa']) && !empty(['empresa'])){
      $empresa = new Home();
      $empresaId = addslashes($_POST['empresa']);

      $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
      $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
      $_SESSION['empresaid'] = $empresaId;      
      header('Location: '.URL);
    }

    $this->loadTemplate('usuariosAcessos', $data);
  }

  public function carregaMenus()
  {
    $u = new Usuario();
    $dados = $u->carregaMenu($_POST['i']);

    echo json_encode($dados);
  }

  public function ajaxCarregaAcessos()
  {
    $u = new Usuario();
    $usr = $u->procuraUsuariosAcessos($_POST['idMenu']);

    echo json_encode(array('permitidos' => $usr[0], 'negados' => $usr[1], 'todos' => $usr[2]));
  }

  public function editarAcesso()
  {
    $usuarios = new Usuario();
    $perfisPermitidos = array();

    if (isset($_POST['perfisPermitidos'])) {
      $perfisPermitidos = $_POST['perfisPermitidos'];
    }

    die(print_r($perfisPermitidos));

    if (isset($_POST['idMenu'])) {
      $res = $usuarios->salvaEditaAcesso($perfisPermitidos, $_POST['idMenu']);

      // Verifica se tem erro e redireciona
      if (isset($res['error'])) {
        $this->helper->setAlert(
          'error',
          $res['message'] . $res['error'],
          '/Usuario/usuarioAcessos/'
        );
      }
      else {        
        $this->helper->setAlert(
          'success',
          $res['message'],
          '/Usuario/usuarioAcessos/'
        );
      }
    }
  }

  public function perfis()
  {
    if(isset($_POST['ok']) && !empty($_POST['ok']) && isset($_POST['empresa']) && !empty(['empresa'])) {
      $empresa = new Home();
      $empresaId = addslashes($_POST['empresa']);

      $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
      $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
      $_SESSION['empresaid'] = $empresaId;      
      header('Location: ' . URL);
    }

    // Carrega a view
    $this->loadTemplate('perfis', $data);
  }

  public function ajaxCarregaPerfis()
  {
    $usuarios = new Usuario();

    $data = $usuarios->carregaPerfis();

    echo json_encode($data);
  }

  public function editarPerfil()
  {
    // Variaveis
    $usuario = new Usuario();
    $name = '';
    $grupo = '';
    $res = array();

    // Verifica se existem os dados necessários
    if (!isset($_POST['name']) || !isset($_POST['idPerfil'])) {
      // Retorna para o view
      $this->helper->setAlert(
        'error',
        'Nome e/ou id do grupo não foram definidos',
        '/Usuario/perfis/'
      );
    } 
    else {
      // Define as variaveis caso exista
      $name = $_POST['name'];
      $grupo = $_POST['idPerfil'];

      // Chama o modal
      //die(print_r($_POST['permitidos']));
      $res = $usuario->editaPerfil($grupo, $name, $_POST['permitidos']);

      // Verifica se houve erros e volta a view
      if (isset($res['error'])) {
        $this->helper->setAlert(
          'error',
          $res['message'] . $res['error'],
          '/Usuario/perfis/'
        );
      }
      else {
        $this->helper->setAlert(
          'success',
          $res['message'],
          'Usuario/perfis/'
        );
      }
    }
  }

  public function addPerfil()
  {
    // Variaveis
    $usuario = new Usuario();
    $name = $_POST['name'];
    $permitidos = array();
    
    // Define as variaveis caso existam
    if (isset($_POST['permitidos'])) {
      $permitidos = $_POST['permitidos'];
    }
    // Retorna pra view caso não exista campo
    else {
      $this->helper->setAlert(
        'error',
        'Nenhum nome foi definido.',
        '/Usuario/perfis/'
      );
    }

    // Chama o modal
    $res = $usuario->addPerfil($name, $permitidos);

    // Verifica se houve erros e retorna pra view
    if (isset($res['error'])) {
      $this->helper->setAlert(
        'error',
        $res['message'] . $res['error'],
        'Usuario/perfis/'
      );
    }
    else {
      $this->helper->setAlert(
        'success',
        $res['message'],
        'Usuario/perfis/'
      );
    }
  }

  public function excluirPerfil()
  {
    // Variaveis
    $usuario = new Usuario();
    $idPerfil = '';

    // Define as variaveis caso existam os campos
    if (isset($_POST['idPerfil'])) {
      $idPerfil = $_POST['idPerfil'];
    }
    // Volta pra view caso não existam os campos necessários
    else {
      $this->helper->setAlert(
        'error',
        'Nenhum grupo foi passado para remover.',
        '/Usuario/perfis/'
      );
    }

    // Chama o model
    $res = $usuario->excluirPerfil($idPerfil);

    // Verifica se houve erros e retorna pra view
    if (isset($res['error'])) {
      $this->helper->setAlert(
        'error',
        $res['message'] . $res['error'],
        '/Usuario/perfis/'
      );
    }
    else {
      $this->helper->setAlert(
        'success',
        $res['message'],
        '/Usuario/perfis/'
      );
    }
  }
}