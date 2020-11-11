<?php

class ProvisionamentoController extends Controller
{
    public function __construct(){
  	parent::__construct();
    }
    
    /**
     * Carrega a tela de listagem de provisionamento
     */
    public function index()
    {
        if (isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa'])) {
            $empresa = new Home();
            $empresaId = addslashes($_POST['empresa']);

            $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
            $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
            $_SESSION['empresaid'] = $empresaId;
            header('Location: ' . URL);
        }
        
        $data = array();        
        $this->loadTemplate('provisionamento', $data);
    }
    
    
    /**
     * Carrega a tela de cadastro de provisionamento
     */
    public function cadastro()
    {
        if (isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa'])) {
            $empresa = new Home();
            $empresaId = addslashes($_POST['empresa']);

            $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
            $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
            $_SESSION['empresaid'] = $empresaId;
            header('Location: ' . URL);
        }
        
        $data = array();
        $data['instancias'] = $this->carregaInstancias();
        $data['funcoes']    = $this->carregaFuncoes();
        
        $this->loadTemplate('provisionamento_cadastro', $data);
    }            
    
    /**
     * Carrega a tela de edição de provisionamento
     * @param type $instancia
     * @param type $funcao
     */
    public function edita($instancia, $funcao)
    {
        if (isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa'])) {
            $empresa = new Home();
            $empresaId = addslashes($_POST['empresa']);

            $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
            $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
            $_SESSION['empresaid'] = $empresaId;
            header('Location: ' . URL);            
        }
        
        $data = array();
        $data['instancias'] = $this->carregaInstancias($instancia);
        $data['funcoes']    = $this->carregaFuncoes($funcao);
        $data['grupos']     = $this->carregaGrupos($funcao, $instancia);
        
        $this->loadTemplate('provisionamento_edita', $data);
    }
    
    /**
     * Grava função grupo
     * @return type
     */
    public function gravaProvisionamento()
    {        
        if(empty($_POST)):
            $this->helper->setAlert(
                'error',
                'Favor selecionar ao menos um Grupo',
                '/Provisionamento/'
            );
        endif;
        $p = new Provisionamento();        
        $res = $p->gravaFuncaoGrupo($_POST);
        
        if($res['return'] == true):
            $this->helper->setAlert(
                'success',
                'Provisionamento cadastrado com sucesso!',
                '/Provisionamento/'
            );
        else:
            $this->helper->setAlert(
                'error',
                'Erro ao cadastrar provisionamento.\n'.$res['error'],
                '/Provisionamento/'
            );
        endif;
    }
    
    /**
     * Exclui Provisionamento
     * @return type
     */
    public function excluiProvisionamento()
    {
        if(empty($_POST)):
            $this->helper->setAlert(
                'error',
                'Favor selecionar ao menos um registro',
                '/Provisionamento/'
            );
        endif;
        $p = new Provisionamento();        
        $res = $p->excluiFuncaoGrupo($_POST['idFuncaoGrupo']);
        
        if($res['return'] == true):
            $this->helper->setAlert(
                'success',
                'Provisionamento excluído com sucesso!',
                '/Provisionamento/'
            );
        else:
            $this->helper->setAlert(
                'error',
                'Erro ao excluir provisionamento.\n'.$res['error'],
                '/Provisionamento/'
            );
        endif;
    }
    
    
    /**
     * Retorna as instancias
     * @instancia
     * @return type     
     */
    public function carregaInstancias($instancia = '')
    {
        $p = new Provisionamento();        
        return $p->carregaInstancias($instancia);                
    }
    
    /**
     * Retorna as funções
     * @funcao
     * @return type
     */
    public function carregaFuncoes($funcao = '')
    {
        $p = new Provisionamento();        
        return $p->carregaFuncoes($funcao);
    }

    
    
    /**
     * Retorna os grupos pelo id da instancia selecionada
     * @return type
     */
    public function carregaGrupos($funcao = '', $instancia = '')
    {           
        
        $p = new Provisionamento();
        return $grupos = $p->ajaxCarregaGrupos($funcao, $instancia);                
    }                   
    
    /**
     * Retorna os grupos pelo id da instancia selecionada
     * @return type
     */
    public function ajaxCarregaGrupos($funcao = '', $instancia = '')
    {   
        // Valida se foi selecionado uma instancia e uma função
        if(isset($_POST['empresa']) && $_POST['empresa'] != '' || isset($_POST['funcao']) && $_POST['funcao'] != ''):
            $instancia = $_POST['empresa'];
            $funcao    = $_POST['funcao'];        
        endif;
        
        $p = new Provisionamento();
        $grupos = $p->ajaxCarregaGrupos($funcao, $instancia);
        
        $optGrupos = "<option></option>";
        
        foreach($grupos as $val):
            $optGrupos .= '<option value="'.$val['idGrupo'].'">'.$val['descricao'].'</option>';
        endforeach;
        
        echo $optGrupos;
    }                   
    
    
    /**
     * Retorna os grupos pelo id da instancia selecionada
     * @return type
     */
    public function ajaxBuscaGrupos($grupo = '', $instancia = '', $eliminar = '')
    {   
        // Valida se foi selecionado uma instancia e uma função
        if(isset($_POST['empresa']) && $_POST['empresa'] != '' || isset($_POST['grupo']) && $_POST['grupo'] != ''):
            $instancia = $_POST['empresa'];
            $grupo     = $_POST['grupo'];
            $eliminar  = $_POST['eliminar'];
        endif;
        
        $p = new Provisionamento();
        $grupos = $p->ajaxBuscaGrupos($grupo, $instancia, $eliminar);
        
        $optGrupos = "<option></option>";
        
        foreach($grupos as $val):
            $optGrupos .= '<option value="'.$val['idGrupo'].'">'.$val['descricao'].'</option>';
        endforeach;
        
        echo $optGrupos;
    }         

    /**
     * Retorna os grupos já adicionados pelo id da instancia e função selecionada
     * @return type
     */
    public function ajaxDatatableProvisionamento()
    {                     
        /*if($_POST['empresa'] == '' && $_POST['funcao'] == ''):
            $output = array(
                "draw" => intval(isset($_POST["draw"]) ? $_POST["draw"] : ''),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => ''
            );
            echo json_encode($output);
            die('');
        endif;*/
        
        $data = array();
        $p = new Provisionamento();
        $fields = array(
            0 =>'idFuncaoGrupo',
            1 => 'funcao',
            2 => 'grupo'
        );
        
        // Variaveis usadas na paginação do Jquery DataTable
        $search = (isset($_POST["search"]["value"])) ? $_POST["search"]["value"] : '';
        $orderColumn = (isset($_POST['order']['0']['column'])) ? $fields[$_POST['order']['0']['column']] : '';
        $orderDir = (isset($_POST['order']['0']['dir'])) ? $_POST['order']['0']['dir'] : 'ASC';
        $limit = (isset($_POST["length"]) && $_POST["length"] != -1) ? $_POST['length'] : '10';
        $offset = (isset($_POST["length"]) && $_POST["length"] != -1) ? $_POST['start'] : '0';
                
        // Traz provisionamento obedecendo o limite escolhido pelo usuario
        $dados = $p->ajaxCarregaProvisionamentosDatatable($search, $orderColumn, $orderDir, $offset, $limit, $_POST);
                               
        // Traz o total de todos os provisionamentos filtrados pela instância e função
        $total_all_records = $p->getCountTableProvisionamento($search, $_POST);
        
        foreach ($dados as $value):
            $sub_dados = array();
            if(isset($_POST['empresa']) && isset($_POST['funcao'])):
                $sub_dados[] = '<td><input type="checkbox" name="idFuncaoGrupo[]" class="checkFuncaoGrupo" value="'.$value['idFuncaoGrupo'].'"></td>';
            endif;
            
            if(isset($value['empresa'])):
                $sub_dados[] = $value["empresa"];
            endif;
            
            $sub_dados[] = $value["funcao"];
            $sub_dados[] = $value["grupo"];
            
            if(!isset($_POST['empresa']) && !isset($_POST['funcao'])):
                $sub_dados[] = '<button type="button" class="btn btn-info btn-xs" onclick="location.href=\'' . URL . '/Provisionamento/edita/' . $value['idEmpresa'] . '/'.$value['idFuncao'].'\'">Visualizar</button>';
            endif;
            
            $data[] = $sub_dados;
        endforeach;

        $output = array(
            "draw" => intval(isset($_POST["draw"]) ? $_POST["draw"] : ''),
            "recordsTotal" => count($dados),
            "recordsFiltered" => $total_all_records,
            "data" => $data
        );
        echo json_encode($output);               
    }

    /**
     * Carrega matriz de risco por ids de grupos
     */
    public function ajaxMatrizDeRisco()
    {
        $dados = array();
        $s = new SolicitacaoAcesso();
        $p = new Provisionamento();
        $dados['conflitos'] = $s->fluxoMatrizRisco($_POST['grupos']);
        $totalRiscos = $s->fluxoMatrizCountRisco($_POST['grupos']);
        $dados['totalGrupo'] = $p->getCountGrupos($_POST['funcao']);
        $dados['totalProgByGrupo'] = $s->getCountTableAbaProgs("z_sga_programas p", '', array(0 => 'DISTINCT p.z_sga_programas_id'), '', $_SESSION['empresaid'], $_POST['grupos']);

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
                              Area '.$value["descArea"].'</a>
                            </h4>
                          </div>
                          <div id="collapse'.$idCollapseArea.'" class="panel-collapse collapse">
                            <div class="panel-body">';
            }
            if($risco != $value['codRisco']){
                $risco = $value['codRisco'];
                $idTabela = $idTabela + 1;
                $html .=  "<h5><strong>".$value["codRisco"]."</strong> - ".$value["descRisco"]."</h5><br>";

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

        //echo $html;
        echo json_encode(array(
            'html'              => $html,
            'totalRiscos'       => $totalRiscos,
            'totalProgByGrupo'  => $dados['totalProgByGrupo'],
            'totalGrupo'        => $dados['totalGrupo'],
        ));
    }
}

