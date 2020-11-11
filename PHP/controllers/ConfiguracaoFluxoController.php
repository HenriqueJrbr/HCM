<?php
/**
 * Created by Rodrigo Gomes do Nascimento.
 * User: a2
 * Date: 04/01/2019
 * Time: 12:07
 */

class ConfiguracaoFluxoController extends Controller
{
    /**
     * Carrega view de agendamento de revisão de acesso
     */
    public function index(){
     
    }


    public function substituto(){
        $dados = array();
        $conf = new ConfiguracaoFluxo();

        if(isset($_POST['salvar']) && !empty($_POST['salvar'])){
            if(isset($_POST['usrSerSubstituido']) && !empty($_POST['usrSerSubstituido']) && isset($_POST['usrSubstituido']) && !empty($_POST['usrSubstituido'])  && isset($_POST['dataInicio']) && !empty($_POST['dataInicio']) && isset($_POST['dataFim']) && !empty($_POST['dataFim']) ){

                $usrSerSubstituido = addslashes($_POST['usrSerSubstituido']);
                $usrSubstituido = addslashes($_POST['usrSubstituido']);
                $dataInicio = addslashes($_POST['dataInicio']);
                $dataFim = addslashes($_POST['dataFim']);
                $obs = addslashes($_POST['obs']);

                $retorno = $conf->criaUsrSubistituto($usrSerSubstituido,$usrSubstituido,$dataInicio,$dataFim,$obs);
                if(!empty($retorno)){

                    if($dataInicio == date("Y-m-d")):
                        $retornoAtualiza =  $conf->atualizaSubMovimento($usrSubstituido,$usrSerSubstituido);
                    endif;
                   if($retornoAtualiza > 0){
                        $this->helper->setAlert(
                            'success',
                            'Usuário substituto criado com sucesso!',
                            'ConfiguracaoFluxo/substituto'
                        );
                   }else{
                        $this->helper->setAlert(
                            'success',
                            'Usuário Agendamento criado com sucesso!',
                            'ConfiguracaoFluxo/substituto'
                        );
                   }
                }
            }
        }

        if(isset($_POST['subCancelar']) && !empty($_POST['subCancelar'])){
            if(isset($_POST['cancelar']) && !empty($_POST['cancelar'])){

                foreach ($_POST['cancelar'] as $value) {
                   $retorno = $conf->atualizaStatus($value);
                }

                $this->helper->setAlert(
                    'success',
                    'Cancelamento efetuado com sucesso!',
                    'ConfiguracaoFluxo/substituto'
                );
            }
        }
        $dados['getUsrSerSubst'] = $conf->getUsrSerSubst();
        $dados['getUsrSubst'] = $conf->getUsrSubst();
        $this->loadTemplate('substituto', $dados);
    }

    public function ajaxCarregaTabSub(){
        $dados = array();
        $conf = new ConfiguracaoFluxo();
        $data = array();
        $dados['sub'] = $conf->ajaxCarregaTabSub();

          foreach ($dados['sub'] as $key => $value):
                    $sub_dados = array();
                    $sub_dados[] = $value["serSub"];
                    $sub_dados[] = $value["substituto"];
                    $sub_dados[] = $value["dataInicio"]." à ".$value["dataInicio"];
                    $idSub = $value["idSub"];
                    $sub_dados[] = "<label><input type= 'checkbox' name='cancelar[]' value='".$idSub."'></label>";
                    $data[] = $sub_dados;
            endforeach;
        $output = array(
                "data" => (count($data) > 0) ? $data : ''
            );
        echo json_encode($output);

    }

    /**
     * Carrega a tela de configuração de fluxo de Atividade
     */
    public function fluxoAtividade()
    {
        if(isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa']) ){
            $empresa = new Home();
            $empresaId = addslashes($_POST['empresa']);

            $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
            $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
            $_SESSION['empresaid'] = $empresaId;


            header('Location: '.URL);
        }

        $dados = array();

        $conf = new ConfiguracaoFluxo();
        $dados['fluxos'] = $conf->buscaFluxos();

        $this->loadTemplate('configuracao_fluxo_atividade', $dados);
    }

    /**
     * Carrega a tela de configuração de fluxo de Atividade
     */
    public function buscaAtividade()
    {
        $dados = array();

        $post = $_POST;

        if(empty($post)):
            echo json_encode(array(
                'return'    => false,
                'error'     => 'Parametros incompletos'
            ));
            die;
        endif;

        $conf = new ConfiguracaoFluxo();
        $atividade = $conf->buscaAtividade($post['idAtividade'], $post['idFluxo']);

        if($atividade['return']):
            echo json_encode(array(
                'return'     => true,
                'dados'      => $atividade['dados'][0],
                'atividades' => $atividade['atividades']
            ));
        else:
            echo json_encode(array(
                'return'    => false,
                'error'     => $atividade['error']
            ));
        endif;
        die();

    }

    /**
     * Grava as informações da tela de configuração de atividade de fluxos
     */
    public function editarAtividade()
    {
        if(isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa']) ){
            $empresa = new Home();
            $empresaId = addslashes($_POST['empresa']);

            $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
            $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
            $_SESSION['empresaid'] = $empresaId;


            header('Location: '.URL);
        }
        $post = $_POST;

        if(empty($post)):
            $this->helper->setAlert(
                'error',
                'Erro ao atualizar a atividade. Por favor, tente novamente!',
                '/configuracaoFluxo/fluxoAtividade'
            );
            die();
        endif;

        $conf = new ConfiguracaoFluxo();
        $result = $conf->editarAtividade($post);

        if($result['return']):
            $this->helper->setAlert(
                'success',
                'Atividade atualizada com sucesso!',
                '/ConfiguracaoFluxo/fluxoAtividade'
            );
        else:
            $this->helper->setAlert(
                'error',
                'Erro ao atualizar a atividade!'. "\n".$result['error'],
                '/ConfiguracaoFluxo/fluxoAtividade'
            );
        endif;
    }

    /**
     * Grava as informações da tela de configuração de fluxos
     */
    public function atualizaConfigFluxo()
    {
        if(isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa']) ){
            $empresa = new Home();
            $empresaId = addslashes($_POST['empresa']);

            $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
            $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
            $_SESSION['empresaid'] = $empresaId;


            header('Location: '.URL);
        }
        $post = $_POST;

        if(empty($post)):
            echo json_encode([
                'type' => false,
                'msg' => 'Favor informar os dados das configurações!'
            ]);
            die();
        endif;

        $conf = new ConfiguracaoFluxo();
        $result = $conf->atualizaConfigFluxo($post);

        if($result['return']):
            echo json_encode([
                'type' => 'success',
                'msg' => 'Fluxo atualizado com sucesso!'
            ]);
            die();
        else:
            echo json_encode([
                'type' => 'error',
                'msg' => 'Erro ao atualizar fluxo!'. "\n".$result['error'],
            ]);
            die();
        endif;
    }



    /**
     * Busca as informações da tela de configuração de fluxos
     */
    public function buscaConfigFluxo()
    {
        $post = $_POST;

        if(empty($post)):
            echo json_encode([
                'type' => false,
                'msg' => 'Favor informar o fluxo!'
            ]);
            die();
        endif;

        $conf = new ConfiguracaoFluxo();
        $result = $conf->buscaConfigFluxo($post);

        if($result['return']):
            echo json_encode($result['data']);
            die();
        else:
            echo json_encode([
                'type' => 'error',
                'msg' => 'Erro ao buscar as configurações do fluxo!'. "\n".$result['error'],
            ]);
            die();
        endif;
    }


    /**
     * Carrega o datatable de atividades na tela de configuração de fluxo de atividade
     * @param $idFluxo
     */
    public function ajaxCarregaAtividades(){
        
        if(!isset($_POST['idFluxo']) || empty($_POST['idFluxo'])):
            $output = array(
                "draw" => intval(isset($_POST["draw"]) ? $_POST["draw"] : ''),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => ''
            );
            echo json_encode($output);
            die('');
        endif;
        
        $dados = array();        
        $conf = new ConfiguracaoFluxo();                
        $idFluxo = $_POST['idFluxo'];

        $atividades = $conf->ajaxCarregaAtividades($idFluxo);

        foreach ($atividades as $key => $value):
            $sub_dados = array();
            $sub_dados[] = $value["idAtividade"];
            $sub_dados[] = $value["descricao"];
            $sub_dados[] = $value["proximaAtiv"];
            $sub_dados[] = ($value["diasAtraso"] == '') ? 0 : $value["diasAtraso"];
            $sub_dados[] = ($value["diasNotifica"] == '') ? 0 : $value["diasNotifica"];
            $sub_dados[] = ($value["ativo"] == 0) ? '<span class="badge label-primary">Inativo</span>' : '<span class="badge label-primary">Ativo</span>';            
            $sub_dados[] = '<button type="button" class="btn btn-warning btn-xs" onclick="carregaModalAtividade('.$value['idAtividade'].', '.$idFluxo.')">Editar</button>';
            $dados[] = $sub_dados;
        endforeach;
        
        $output = array(            
            "data" => (count($dados) > 0) ? $dados : ''
        );
        echo json_encode($output);

    }



}
