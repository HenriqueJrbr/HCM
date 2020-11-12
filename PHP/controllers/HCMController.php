<?php

class HCMController extends Controller
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

        
        $empresa = new Home();
        $hcm = new HCM();
        $dados['empresas'] = $hcm ->carregaEmpresas();
    $this->loadTemplate('hcm_empresaxestabelecimento', $dados);
    }

    public function cadastroRegra()
    {
        if(isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa']) ){
            $empresa = new Home();
            $empresaId = addslashes($_POST['empresa']);

            $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
            $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
            $_SESSION['empresaid'] = $empresaId;


            header('Location: '.URL);
        }
        $empresa = new Home();
        $hcm = new HCM();
        $dados['empresas'] = $hcm ->carregaEmpresas();
        $dados['empresas'] = $hcm ->carregaEmpresas();
        $dados['empresas'] = $hcm ->carregaEmpresas();
        $dados['empresas'] = $hcm ->carregaEmpresas();
        $dados['empresas'] = $hcm ->carregaEmpresas();
        $dados['empresas'] = $hcm ->carregaEmpresas();

        $this->loadTemplate('hcm_cadastrarRegra', $dados);
    }

    public function cadastroInstancias()
    {
        if(isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa']) ){
            $empresa = new Home();
            $empresaId = addslashes($_POST['empresa']);
            $hcm = new HCM();
            $dados['empresas'] = $hcm ->carregaEmpresas();

            $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
            $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
            $_SESSION['empresaid'] = $empresaId;


            header('Location: '.URL);
        }

            $empresa = new Home();
            $hcm = new HCM();
            // $dados['instancias'] = $hcm->carregaInstancias();
            $dados['empresas'] = $hcm ->carregaEmpresas();
        $this->loadTemplate('hcm_empresaxestabelecimento', $dados);
    }

    public function ajaxEstabelecimentos(){
        $post=$_POST;
        $empresa = $post['idEmpresa'];
        $hcm=new HCM();
        $dados = $hcm-> carregaEstabelecimentos($empresa);
        $opt="";
        $opt="<option value=''></option><option id='0' value='todas'>Todos</option>";
        foreach($dados as $estabelecimentos){
            $opt .= '<option id="'.$estabelecimentos['idEstabelecimento'].'" value="'.$estabelecimentos['idEstabelecimento'].'">'.$estabelecimentos['descEstabelecimento'].'</option>';
            //$opt[] = ['id'=>$estabelecimentos['idEstabelecimento'],'desc'=>$estabelecimentos['descEstabelecimento']];
        }
        echo $opt;
    }

    public function ajaxEstabelecimentos2(){
        $post=$_POST;
        $empresa = $post['idEmpresa'];
        $hcm=new HCM();
        $dados = $hcm-> carregaEstabelecimentos2($empresa);
        $opt="";
        $opt="<option value=''></option><option id='0' value='todas'>Todos</option>";
        foreach($dados as $estabelecimentos){
            $opt .= '<option id="'.$estabelecimentos['idEstabelecimento'].'" value="'.$estabelecimentos['idEstabelecimento'].'">'.$estabelecimentos['descEstabelecimento'].'</option>';
            //$opt[] = ['id'=>$estabelecimentos['idEstabelecimento'],'desc'=>$estabelecimentos['descEstabelecimento']];
        }
        echo $opt;
    }
    public function ajaxCargoBase(){
        $hcm=new HCM();
        $dados = $hcm-> carregaCargoBaseModel();
        $opt="";
       $opt="<option value=''></option><option id='0' value='todas'>Todos</option>";
        foreach($dados as $estabelecimentos){
            $opt .= '<option id="'.$estabelecimentos['idCargoBase'].'" value="'.$estabelecimentos['idCargoBase'].'">'.$estabelecimentos['descCargoBase'].'</option>';
            //$opt[] = ['id'=>$estabelecimentos['idEstabelecimento'],'desc'=>$estabelecimentos['descEstabelecimento']];
        }
        echo $opt;
    }

    public function ajaxGrupos(){
        $hcm=new HCM();
        $dados = $hcm-> carregaGruposModel();
        $opt="";
       $opt="<option value=''></option><option id='0' value='todas'>Todos</option>";
        foreach($dados as $estabelecimentos){
            $opt .= '<option id="'.$estabelecimentos['idGrupo'].'" value="'.$estabelecimentos['idGrupo'].'">'.$estabelecimentos['descAbrev'].'</option>';
            //$opt[] = ['id'=>$estabelecimentos['idEstabelecimento'],'desc'=>$estabelecimentos['descEstabelecimento']];
        }
        echo $opt;
    }

    public function ajaxDepartamento(){
        $hcm=new HCM();
        $dados = $hcm-> carregaDepartamentoModel();
        $opt="";
       $opt="<option value=''></option><option id='0' value='todas'>Todos</option>";
        foreach($dados as $estabelecimentos){
            $opt .= '<option id="'.$estabelecimentos['idDepartamentohcm'].'" value="'.$estabelecimentos['idDepartamentohcm'].'">'.$estabelecimentos['descDepartamentoHCM'].'</option>';
            //$opt[] = ['id'=>$estabelecimentos['idEstabelecimento'],'desc'=>$estabelecimentos['descEstabelecimento']];
        }
        echo $opt;
    }

    public function ajaxCentroCusto(){
        $hcm=new HCM();
        $dados = $hcm-> carregaCentroCustoModel();
        $opt="";
       $opt="<option value=''></option><option id='0' value='todas'>Todos</option>";
        foreach($dados as $estabelecimentos){
            $opt .= '<option id="'.$estabelecimentos['idCentroCusto'].'" value="'.$estabelecimentos['idCentroCusto'].'">'.$estabelecimentos['descCentroCusto'].'</option>';
            //$opt[] = ['id'=>$estabelecimentos['idEstabelecimento'],'desc'=>$estabelecimentos['descEstabelecimento']];
        }
        echo $opt;
    }

    public function ajaxUnLotacao(){
        $hcm=new HCM();
        $dados = $hcm-> carregaUnLotacaoModel();
        $opt="";
       $opt="<option value=''></option><option id='0' value='todas'>Todos</option>";
        foreach($dados as $estabelecimentos){
            $opt .= '<option id="'.$estabelecimentos['idUnidadeLotacao'].'" value="'.$estabelecimentos['idUnidadeLotacao'].'">'.$estabelecimentos['desc_unidade_lotacao'].'</option>';
            //$opt[] = ['id'=>$estabelecimentos['idEstabelecimento'],'desc'=>$estabelecimentos['descEstabelecimento']];
        }
        echo $opt;
    }

    public function ajaxCarregaFuncao(){
        $hcm=new HCM();
        $dados = $hcm-> carregaFuncaoModel();
        $opt="";
       $opt="<option value=''></option><option id='0' value='todas'>Todos</option>";
        foreach($dados as $estabelecimentos){
            $opt .= '<option id="'.$estabelecimentos['idFuncao'].'" value="'.$estabelecimentos['idFuncao'].'">'.$estabelecimentos['descricao'].'</option>';
            //$opt[] = ['id'=>$estabelecimentos['idEstabelecimento'],'desc'=>$estabelecimentos['descEstabelecimento']];
        }
        echo $opt;
    }

    public function ajaxNvlHier(){
        $hcm=new HCM();
        $dados = $hcm-> carregaNvlHierModel();
        $opt="";
       $opt="<option value=''></option><option id='0' value='todas'>Todos</option>";
        foreach($dados as $estabelecimentos){
            $opt .= '<option id="'.$estabelecimentos['idNivelHierarquico'].'" value="'.$estabelecimentos['idNivelHierarquico'].'">'.$estabelecimentos['descNivelHierarquico'].'</option>';
            //$opt[] = ['id'=>$estabelecimentos['idEstabelecimento'],'desc'=>$estabelecimentos['descEstabelecimento']];
        }
        echo $opt;
    }

    public function ajaxEstabelecimentosVinculados(){
        $post=$_POST;
        $empresa = $post['idEmpresa'];
        $hcm=new HCM();
        $dados = $hcm-> carregaEstabelecimentosVinculados($empresa);
        $opt="";
       $opt="<option value=''></option><option id='0' value='todas'>Todos</option>";
        $ids = array();
        $retorno= array();
        foreach($dados as $estabelecimentos){
             $opt .= '<option id="'.$estabelecimentos['idEstabelecimento'].'" value="'.$estabelecimentos['descEstabelecimento'].'">'.$estabelecimentos['descEstabelecimento'].'</option>';
            //$opt[] = ['id'=>$estabelecimentos['idEstabelecimento'],'desc'=>$estabelecimentos['descEstabelecimento']];
            $ids[]=$estabelecimentos['idEstabelecimento'];
        }
        $retorno[]=$opt;
        $retorno[]=$ids;
        echo json_encode(array($retorno));
    }

    public function gravarEstabexEmpresa(){
        $hcm = new HCM();        
        $res = $hcm->gravarEstabxEmpresa($_POST);

        if($res['return'] == true):
            $this->helper->setAlert(
                'success',
                'Alterações realizadas com sucesso!',
                '/HCM/'
            );
        else:
            $this->helper->setAlert(
                'error',
                'Erro ao realizar alterações.\n'.$res['error'],
                '/HCM/'
            );
        endif;
    }

    public function ajaxgravarRegra(){
        $hcm = new HCM();        
        $res = $hcm->gravarRegraModel($_POST);

        if($res['return'] == true):
            $this->helper->setAlert(
                'success',
                'Infomações salvas com sucesso!',
                '/HCM/'
            );
        else:
            $this->helper->setAlert(
                'error',
                'Erro ao salvar.\n'.$res['error'],
                '/HCM/'
            );
        endif;
    }

    public function ajaxgravarRegra2(){
        $hcm = new HCM();        
        $res = $hcm->gravarRegraModel($_POST);

        if($res['return'] == true):
            $this->helper->setAlert(
                'success',
                'Infomações salvas com sucesso!',
                '/HCM/'
            );
        else:
            $this->helper->setAlert(
                'error',
                'Erro ao salvar.\n'.$res['error'],
                '/HCM/'
            );
        endif;
    }

    public function ajaxgravarGrupo(){
        $hcm = new HCM();        
        $res = $hcm->gravarGrupoModel($_POST);

        if($res['return'] == true):
            $this->helper->setAlert(
                'success',
                'Infomações salvas com sucesso!',
                '/HCM/'
            );
        else:
            $this->helper->setAlert(
                'error',
                'Erro ao salvar.\n'.$res['error'],
                '/HCM/'
            );
        endif;
    }
}
?>