<?php
/**
 * Created by Rodrigo Gomes do Nascimento.
 * User: a2
 * Date: 04/01/2019
 * Time: 12:07
 */

class AgendamentoController extends Controller
{
    protected $url = array();

    public function __construct()
    {
        parent::__construct();
        $this->url['ra'] = 'Agendamento/revisao_acesso';
    }

    public function index(){}

    /**
     * Carrega view de agendamento de revisão de acesso
     */
    public function revisao_acesso()
    {
        if (isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa'])) {
            $empresa = new Home();
            $empresaId = addslashes($_POST['empresa']);

            $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
            $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
            $_SESSION['empresaid'] = $empresaId;

            header('Location: ' . URL);
        }

        $dados = array();
        $a = new Agendamento();

        $dados['agendamentos'] = $a->buscaAgendamentos();
        $dados['agendInativos'] = $a->buscaAgendamentosInativos();
        $empresas = $a->buscaEmpresas();
        $dados['empresa'] = $empresas['result'];

        $this->loadTemplate('agendamento_revisao_acesso', $dados);
    }

    /**
     * Cria o agendamento de revisão de acesso
     */
    public function add_agenda_revisao_acesso()
    {
        if (isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa'])) {
            $empresa = new Home();
            $empresaId = addslashes($_POST['empresa']);

            $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
            $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
            $_SESSION['empresaid'] = $empresaId;

            header('Location: ' . URL);
        }

        $post = $_POST;
        
        //$this->helper->debug($post, true);
        
        // Valida se todos os campos foram preenchidos
        if(/*$post['dataInicio'] == '' || $post['horaInicio'] == '' || $post['dataFim'] == '' || */count($post) == 0):
            $this->helper->setAlert(
                'error',
                'Favor preencher todos os campos',
                "{$this->url['ra']}"
            );
        endif;

        $a = new Agendamento();
        $data = array(
            'dataInicio'    => $post['dataInicio'] . ' ' . $post['horaInicio'],            
            'dataFim'       => $post['dataFim'],
            'idSolicitante' => $_SESSION['idUsrTotvs'],
            'idUsuario'     => $post['usuarios']
        );

        // Valida se já existe agendamento para os dados selecionados
        /*$result = $a->validaAgendaRevisaoExistente($data);
        
        if($result['return'] == true && $result['result'] > 0):
            $this->helper->setAlert(
                'error',
                'Já existe agendamento para o(s) usuário(s) com a data selecionada!',
                "{$this->url['ra']}"
            );
        endif;*/

        // Executa o método que adiciona agendamento da model e redireciona para a página de agendamento
        $result = $a->addAgendaRevisaoAcesso($data);
        if($result['return'] == true):
            $this->helper->setAlert(
                'success',
                'Agendamento realizado com sucesso!',
                "{$this->url['ra']}"
            );
        else:
            $this->helper->setAlert(
                'error',
                'Erro ao realizar agendamento!'."\n".$result['error'],
                "{$this->url['ra']}"
            );
        endif;
    }

    /**
     * Método de busca de usuários autocomplete
     */
    public function ajaxCarregaUsuarios()
    {
        $dados = array();
        $a = new Agendamento();
        $data = array(
            'idUsr'   => $_POST['idUsr'],
            'idEmpresa' => $_POST['idEmpresa']
        );

        if (isset($_POST['idUsr']) && !empty($_POST['idUsr'])) {
            $dados['usr'] = $a->ajaxCarregaUsuario($data);
            foreach ($dados['usr'] as $value) {
                echo '<li onclick="carregaDadosUserAgenda(' . "'" . $value["nome_usuario"] . "'" . "," . "'" . $value["idUsuario"] . "', '" . $value['idEmpresa'] . " ','".$value['razaoSocial']."'" . ')">' . $value['nome_usuario'] . ' - ' . $value['cod_usuario'] . '</li>';
            }
        }
    }

    /**
     * Valida via ajax Agendamento existente para usuário e data selecionada
     */
    public function ajaxValidaAgendaExistente()
    {
        $post = $_POST;
        //echo "<pre>";
        //print_r($post);
        //die;
        
        // Valida se todos os campos foram preenchidos
        if($post['dataInicio'] == '' || $post['dataFim'] && count($post) == 0):
            $this->helper->setAlert(
                'error',
                'Favor preencher todos os campos',
                "{$this->url['ra']}"
            );
        endif;

        $agendamento = new Agendamento();
        $data = array(
            'dataInicio' => $post['dataInicio'],
            'dataFim'    => $post['dataFim'],
            'idUsuario'  => $post['usuarios'],
            'idEmpresa'  => preg_replace("/[^0-9]/", "", $post['idEmpresa'])
        );
        
        // Valida se já existe revisão aberta com as datas selecionadas
        $result = $agendamento->validaRevisaoAberta($data);
        if($result['return'] == true):            
            if($result['result'] > 0):
                $html = "";
                foreach($result['data'] as $val):
                    $html .= "<tr role=\"row\">                                                             
                                <td>".$val['usuario']."</td>
                                <td>".implode('/', array_reverse(explode('-', $val['dataInicio'])))."</td>
                                <td>".implode('/', array_reverse(explode('-', $val['dataFim'])))."</td>
                                <td>".$val['solicitante']."</td>
                                <td>".$val['razaoSocial']."</td>
                            </tr>";
                endforeach;
                $html .= "</table>";
                
                echo json_encode(array(
                    'result' => 'revAberta',
                    'data'   => $html
                ));              
                die('');
            endif;            
        else:
            echo json_encode(array(
                'result' => 'erro'
            ));              
            die(''); 
        endif;
 
        // Valida se já existe agendamento para os dados selecionados
        $result = $agendamento->validaAgendaRevisaoExistente($data);        
        if($result['return'] == true):                        
            if($result['result'] > 0):
                $html = "";
                foreach($result['data'] as $val):
                    $html .= "<tr role=\"row\">                                                             
                                <td><input type=\"hidden\" name=\"idAgenda[]\" value=\"".$val['idAgendamento']."\">".$val['usuario']."</td>
                                <td>".implode('/', array_reverse(explode('-', $val['dataInicio'])))."</td>
                                <td>".implode('/', array_reverse(explode('-', $val['dataFim'])))."</td>
                                <td>".$val['solicitante']."</td>
                                <td>".$val['razaoSocial']."</td>
                            </tr>";
                endforeach;
                $html .= "</table>";
                
                echo json_encode(array(
                    'result' => 'existente',
                    'data'   => $html
                ));
                die('');                
            endif;            
        else:            
            echo json_encode(array(
                'result' => 'erro'
            ));              
            die(''); 
        endif;
        
        // Valida se já existe agendamento com data maior para os dados selecionados
        $result = $agendamento->validaAgendaPosterior($data);
        if($result['return'] == true):            
            if($result['result'] > 0):
                $html = "";
                foreach($result['data'] as $val):
                    $html .= "<tr role=\"row\">                                                             
                                <td>".$val['usuario']."</td>
                                <td>".implode('/', array_reverse(explode('-', $val['dataInicio'])))."</td>
                                <td>".implode('/', array_reverse(explode('-', $val['dataFim'])))."</td>
                                <td>".$val['solicitante']."</td>
                                <td>".$val['razaoSocial']."</td>
                            </tr>";
                endforeach;
                $html .= "</table>";
                echo json_encode(array(
                    'result' => 'posterior',
                    'data'   => $html
                ));              
                die('');
            endif;            
        else:
            echo json_encode(array(
                'result' => 'erro'
            ));              
            die('');            
        endif;
                
        
        
        echo json_encode(array(
            'result' => 0
        ));
    }

    /*
    Esta função carrega e monta o datatable da pagina de configurações -> agendamento -> revisão de acesso
    Usuario http://162.144.118.90:84/sga_v2/agendamento/revisao_acesso
    */
    public function ajaxDatatableAgendamento(){
        $data = array();
        $a = new Agendamento();
        $result = $a->ajaxDatatableAgendamento();

        if($result['return']):
            foreach ($result['result'] as $key => $value):
                $sub_dados = array();
                $sub_dados[] = '<td><input type="checkbox" name="idAgenda[]" class="checkAgenda" value="'.$value['idAgendamento'].'"></td>';
                $sub_dados[] = date('d/m/Y H:i:s', strtotime($value["dataInicio"]));
                $sub_dados[] = date('d/m/Y', strtotime($value["dataFim"]));
                $sub_dados[] =  ($value['idUsuario'] != '*') ? $value["nome_usuario"] : 'TODOS';
                $sub_dados[] =  $value['empresa'];
                $data[] = $sub_dados;
            endforeach;
            $output = array(
                "data" => (count($data) > 0 ) ? $data : ''
            );
            echo json_encode($output);
        else:
            echo json_encode($result['error']);
        endif;
    }
    
    /*
    Esta função carrega e monta o datatable da pagina de configurações -> agendamento -> revisão de acesso
    Usuario http://162.144.118.90:84/sga_v2/agendamento/revisao_acesso
    */
    public function ajaxDatatableAgendamentoInativo(){
        $data = array();
        $a = new Agendamento();
        $result = $a->ajaxDatatableAgendamentoInativo();
        
        if($result['return']):
            foreach ($result['result'] as $key => $value):
                $sub_dados = array();                
                $sub_dados[] = date('d/m/Y H:i:s', strtotime($value["dataInicio"]));
                $sub_dados[] = date('d/m/Y', strtotime($value["dataFim"]));
                $sub_dados[] =  ($value['idUsuario'] != '*') ? $value["nome_usuario"] : 'TODOS';
                $sub_dados[] =  $value['empresa'];
                $data[] = $sub_dados;
            endforeach;
            $output = array(
                "data" => (count($data) > 0 ) ? $data : ''
            );
            echo json_encode($output);
        else:
            echo json_encode($result['error']);
        endif;
    }
    
    /*
    Esta função carrega e monta o datatable da pagina de configurações -> agendamento -> revisão de acesso
    Usuario http://162.144.118.90:84/sga_v2/agendamento/revisao_acesso
    */
    public function ajaxDatatableAgendamentoFinalizado(){
        $data = array();
        $a = new Agendamento();
        $result = $a->ajaxDatatableAgendamentoFinalizado();
        
        if($result['return']):
            foreach ($result['result'] as $key => $value):
                $sub_dados = array();                
                $sub_dados[] = date('d/m/Y H:i:s', strtotime($value["dataInicio"]));
                $sub_dados[] = date('d/m/Y', strtotime($value["dataFim"]));
                $sub_dados[] =  ($value['idUsuario'] != '*') ? $value["nome_usuario"] : 'TODOS';
                $sub_dados[] =  $value['empresa'];
                $data[] = $sub_dados;
            endforeach;
            $output = array(
                "data" => (count($data) > 0 ) ? $data : ''
            );
            echo json_encode($output);
        else:
            echo json_encode($result['error']);
        endif;
    }

    /**
     * Apaga agendamentos selecionado no dataTable
     */
    public function apagaAgendamentos()
    {
        if (isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa'])) {
            $empresa = new Home();
            $empresaId = addslashes($_POST['empresa']);

            $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
            $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
            $_SESSION['empresaid'] = $empresaId;

            header('Location: ' . URL);
        }

        // Valida se foi selecionado ao menos um registro
        // Caso não, redireciona para página de agendamento -> revisao_acesso, Com mensagem de erro.
        if(count($_POST['idAgenda']) == 0):
            $this->helper->setAlert(
                'error',
                'Nenhum agendamento selecionado. Favor selecionar ao menos um agendamento!',
                "{$this->url['ra']}"
            );
        endif;

        $a = new Agendamento();
        $idAgendamentos = $_POST['idAgenda'];

        // Executa o metódo e retorna o resultado
        $result = $a->apagaAgendamentos($idAgendamentos);
        if($result['return']):
            $this->helper->setAlert(
                'success',
                'Agendamento(s) apagado(s) com sucesso!',
                "{$this->url['ra']}"
            );
        else:
            $this->helper->setAlert(
                'error',
                'Erro ao apagar Agendamento(s)!.\n'.$result['error'],
                "{$this->url['ra']}"
            );
        endif;
    }

}
