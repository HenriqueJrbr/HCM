<?php

class ProcessoController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Carrega a página de consulta de processos
     */
    public function index()
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

        $this->loadTemplate('processos_consultas', $dados);
    }

    /**
     * Carrega a página de consulta de processos
     */
    public function visualiza_processo($idProcesso)
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

        // Busca os dados do processo
        $p = new Processo();
        $dados['proc'] = $p->carregaProcessos($idProcesso);
        $dados['procSnapshot'] = $p->carregaProcessosSnapshot($idProcesso);
        $dados['grupos'] = $p->carregaGruposProcesso($idProcesso);
        $dados['usuarios'] = $p->carregaUsuariosProcesso($idProcesso);
        $dados['gruposSnapshot'] = $p->carregaGruposProcessoSnapshot($idProcesso);
        $dados['usuariosSnapshot'] = $p->carregaUsuariosProcessoSnapshot($idProcesso);
        $dados['dataSnapshot'] = $p->dataSnapshot();
        $this->loadTemplate('processos_visualiza', $dados);
    }

    /**
     * Carrega o DataTable da tela de listagem de processos
     */
    public function ajaxCarregaProcessos()
    {
        $data = array();
        $p = new Processo();
        $procs = $p->carregaProcessos('');

        foreach ($procs as $key => $value):
            $sub_dados = array();
            $sub_dados[] = $value["idProcesso"];
            $sub_dados[] = $value["area"];
            $sub_dados[] = $value["descricao"];
            $sub_dados[] = $value["descProcesso"];
            $sub_dados[] = ($value["numUsuarios"] > 0) ? $value["numUsuarios"] : 0;
            $sub_dados[] = ($value["numGrupos"] > 0) ? $value["numGrupos"] : 0;
            $sub_dados[] = ($value["numProgramas"] > 0) ? $value["numProgramas"] : 0;
            $sub_dados[] = ($value["numModulos"] > 0) ? $value["numModulos"] : 0;
            if($value["numModulos"] > 0 || $value["numProgramas"] > 0 ||  $value["numUsuarios"] > 0 || $value["numGrupos"] > 0):
                $sub_dados[] = '<button type="button" class="btn btn-info btn-xs" data-toggle="modal" onclick="location.href=\'' . URL . '/Processo/visualiza_processo/' . $value['idProcesso'] . '\'">Visualizar</button>';
            else:
                $sub_dados[] = '';
            endif;

            $data[] = $sub_dados;
        endforeach;
        $output = array(
            "data" => (count($data) > 0 ) ? $data : ''
        );
        echo json_encode($output);
    }

    /**
     * Carrega o DataTable da tela de programas de processos
     */
    public function ajaxCarregaProgramasProcesso($idProcesso)
    {
        $data = array();
        $p = new Processo();
        $procs = $p->carregaProgramasProcesso($idProcesso);

        foreach ($procs as $key => $value):
            $sub_dados = array();
            $sub_dados[] = $value["grupos"];
            $sub_dados[] = $value["cod_programa"];
            $sub_dados[] = $value["descricao_programa"];
            $sub_dados[] = $value["descricao_rotina"];
            /*$sub_dados[] = '<button type="button" class="btn btn-info btn-xs" data-toggle="modal" onclick="location.href=\'' . URL . '/processo/visualiza_processo/' . $value['idProcesso'] . '\'">Visualizar</button>';*/
            $data[] = $sub_dados;
        endforeach;
        $output = array(
            "data" => (count($data) > 0 ) ? $data : ''
        );
        echo json_encode($output);
    }

    /**
     * Carrega o DataTable de módulos de processos
     * @param $idProcesso
     */
    public function ajaxCarregaModulosProcesso($idProcesso)
    {
        // Busca os módulos do processo
        $data = array();
        $p = new Processo();
        $dados['modulos'] = $p->carregaModulosProcesso($idProcesso);

        foreach ($dados['modulos'] as $value):
            $sub_dados = array();
            $sub_dados[] = $value["cod_modulo"];
            $sub_dados[] = $value["des_sist_dtsul"];
            $sub_dados[] = $value["descricao_modulo"];
            $sub_dados[] = $value["descricao_rotina"];
            $sub_dados[] = $value["Programas"];
            $sub_dados[] = $value["numProgramas"];
            $sub_dados[] = $value["Grupos"];
            $data[] = $sub_dados;
        endforeach;

        $output = array(
            "data" => (count($data) > 0 ) ? $data : ''
        );
        echo json_encode($output);
    }


    /**************************************************************************************
    *                              SNAPSHOT DOS PROCESSOS                                 *
    **************************************************************************************/

    /**
     * Carrega o DataTable da tela de listagem de processos snapshot
     */
    public function ajaxCarregaProcessosSnapshot()
    {
        $data = array();
        $p = new Processo();
        $procs = $p->carregaProcessos('');

        foreach ($procs as $key => $value):
            $sub_dados = array();
            $sub_dados[] = $value["idProcesso"];
            $sub_dados[] = $value["area"];
            $sub_dados[] = $value["descricao"];
            $sub_dados[] = $value["descProcesso"];
            $sub_dados[] = ($value["numUsuarios"] > 0) ? $value["numUsuarios"] : 0;
            $sub_dados[] = ($value["numGrupos"] > 0) ? $value["numGrupos"] : 0;
            $sub_dados[] = ($value["numProgramas"] > 0) ? $value["numProgramas"] : 0;
            $sub_dados[] = ($value["numModulos"] > 0) ? $value["numModulos"] : 0;
            $sub_dados[] = '<button type="button" class="btn btn-info btn-xs" data-toggle="modal" onclick="location.href=\'' . URL . '/Processo/visualiza_processo/' . $value['idProcesso'] . '\'">Visualizar</button>';
            $data[] = $sub_dados;
        endforeach;
        $output = array(
            "data" => (count($data) > 0 ) ? $data : ''
        );
        echo json_encode($output);
    }

    /**
     * Carrega o DataTable da tela de programas de processos snapshot
     */
    public function ajaxCarregaProgramasProcessoSnapshot($idProcesso)
    {
        $data = array();
        $p = new Processo();
        $procs = $p->carregaProgramasProcesso($idProcesso);

        foreach ($procs as $key => $value):
            $sub_dados = array();
            $sub_dados[] = $value["grupos"];
            $sub_dados[] = $value["cod_programa"];
            $sub_dados[] = $value["descricao_programa"];
            $sub_dados[] = $value["descricao_rotina"];
            /*$sub_dados[] = '<button type="button" class="btn btn-info btn-xs" data-toggle="modal" onclick="location.href=\'' . URL . '/processo/visualiza_processo/' . $value['idProcesso'] . '\'">Visualizar</button>';*/
            $data[] = $sub_dados;
        endforeach;
        $output = array(
            "data" => (count($data) > 0 ) ? $data : ''
        );
        echo json_encode($output);
    }

    /**
     * Carrega o DataTable de módulos de processos snapshot
     * @param $idProcesso
     */
    public function ajaxCarregaModulosProcessoSnapshot($idProcesso)
    {
        // Busca os módulos do processo
        $data = array();
        $p = new Processo();
        $dados['modulos'] = $p->carregaModulosProcesso($idProcesso);

        foreach ($dados['modulos'] as $value):
            $sub_dados = array();
            $sub_dados[] = $value["cod_modulo"];
            $sub_dados[] = $value["des_sist_dtsul"];
            $sub_dados[] = $value["descricao_modulo"];
            $sub_dados[] = $value["descricao_rotina"];
            $sub_dados[] = $value["Programas"];
            $sub_dados[] = $value["numProgramas"];
            $sub_dados[] = $value["Grupos"];
            $data[] = $sub_dados;
        endforeach;

        $output = array(
            "data" => (count($data) > 0 ) ? $data : ''
        );
        echo json_encode($output);
    }
}