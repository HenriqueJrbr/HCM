<?php

class EmpresaController extends Controller {

    public function __construct() {
        parent:: __construct();
        $login = new Login();

        if (!$login->isLogin()) {
            header('Location: ' . URL . '/Login');
        } else {
            if ($login->validaTrocaSenha() == true) {
                header('Location: ' . URL . '/Login/trocaSenha');
            }
        }
    }

    public function index() {
        $dados = array();

        $home = new Home();

        if (isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa'])) {
            $empresa = new Home();
            $empresaId = addslashes($_POST['empresa']);

            $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
            $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
            $_SESSION['empresaid'] = $empresaId;

            header('Location: ' . URL);
        }

        $empresa = new Empresa();
        $dados['dadosEmpresa'] = $empresa->carregaEmpresa();
        $this->loadTemplate('empresa', $dados);
    }
    
    /**
     * Edita as empresas
     */
    public function editaEmpresa()
    {
        if (isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa'])) {
            $empresa = new Home();
            $empresaId = addslashes($_POST['empresa']);

            $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
            $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
            $_SESSION['empresaid'] = $empresaId;

            header('Location: ' . URL);
        }                        
        
        $empresa = new Empresa();

        /*
            idEmpresa = Ajax
            razaoEmpresa = Input
            cnpj = Input
            logo = Input de Arquivo
        */

        // Validação do idEmpresa, razaoSocial e cnpj
        if (isset($_POST['idEmpresa']) && !empty($_POST['idEmpresa']) && isset($_POST['razaoSocial']) && !empty($_POST['razaoSocial']) && isset($_POST['cnpj']) && !empty($_POST['cnpj'])) {
            // Variaveis
            $nameLogo = '';
            $idEmpresa = addslashes($_POST['idEmpresa']);
            $razaoSocial = addslashes($_POST['razaoSocial']);
            $cnpj = addslashes($_POST['cnpj']); 
            
            $execBO = json_encode(['execBO' => $_POST['execBO']]);
            $execBO = addslashes($execBO);

            // Verifica se tem logo
            if(isset($_FILES["logo"]["tmp_name"]) && !empty($_FILES["logo"]["tmp_name"])):
                // Faz o upload do logo caso tenha sido escolhido algum
                $resLogo = $this->uploadLogo();
                if($resLogo['return']):
                    $nameLogo = $resLogo['nameFile'];
                else:
                    $this->helper->setAlert(
                        'error',
                        'Erro ao atualizar logo da empresa. ' . $resLogo['error'],
                        'Empresa'
                    );
                endif;
            endif;
            // Fim da validação de nome                       
            
            $res = $empresa->editaEmpresa($idEmpresa, $razaoSocial, $cnpj, $execBO, $nameLogo);

            if($res['return'] === true):
                $this->helper->setAlert(
                    'success',
                    'Empresa atualizada com sucesso!',
                    'Empresa'
                );
            else:
                $this->helper->setAlert(
                    'success',
                    'Erro ao atualizar empresa!',
                    'Empresa'
                );
            endif;
        }
    }
    
    /**
     * Cadastra novas empresas
     */
    public function cadastraEmpresa()
    {        
        if (isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa'])) {
            $empresa = new Home();
            $empresaId = addslashes($_POST['empresa']);

            $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
            $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
            $_SESSION['empresaid'] = $empresaId;

            header('Location: ' . URL);
        }
        
        $empresa = new Empresa();
        $nameLogo = '';
        
        if (isset($_POST['AddIdTotvs']) && !empty($_POST['AddIdTotvs']) && (isset($_POST['AddRazaoSocial']) && !empty($_POST['AddRazaoSocial'])) && (isset($_POST['AddCnpj']) && !empty($_POST['AddCnpj']))):
            if(isset($_FILES["logo"]["tmp_name"])):
                // Faz o upload do logo caso tenha sido escolhido algum
                $resLogo = $this->uploadLogo();
                if($resLogo['return']):
                    $nameLogo = $resLogo['nameFile'];
                else:
                    $this->helper->setAlert(
                        'error',
                        'Erro ao cadastrar logo da empresa. ' . $resLogo['error'],
                        'Empresa'
                    );
                endif;
            endif;

            $addIdTotvs = addslashes($_POST['AddIdTotvs']);
            $razaoSocial = addslashes($_POST['AddRazaoSocial']);
            $cnpj = addslashes($_POST['AddCnpj']);                        
            
            $res = $empresa->cadastraEmpresa($addIdTotvs, $razaoSocial, $cnpj, json_encode(['execBO' => $_POST['execBO']]), $nameLogo);

            if($res['return'] == true):
                $this->helper->setAlert(
                    'success',
                    'Empresa cadastrada com sucesso',
                    'Empresa'
                );
            else:
                $this->helper->setAlert(
                    'error',
                    "Erro ao cadastrar empresa. <br>".$res['error'],
                    'Empresa'
                );
            endif;                
        else:
            $this->helper->setAlert(
                'error',
                'Favor preencher todos os campos!!!',
                'Empresa'
            );        
        endif;    
    }

    public function uploadLogo()
    {
        $target_dir = "arquivos/";
        $nameFile = basename($_FILES["logo"]["name"]);
        $target_file = $target_dir . basename($_FILES["logo"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        // Check if image file is a actual image or fake image
        if(isset($_POST["salvarEdit"])) {
            $check = getimagesize($_FILES["logo"]["tmp_name"]);
            if($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                return [
                    'return' => false,
                    'error'  => 'Arquivo não é uma imagem.'
                ];
                $uploadOk = 0;
            }
        }
        // Check if file already exists
        /*if (file_exists($target_file)) {
            return [
                'return' => false,
                'error'  => "Desculpe, seu arquivo já existe."
            ];
            $uploadOk = 0;
        }*/
        // Check file size
        if ($_FILES["logo"]["size"] > 500000) {
            return [
                'return' => false,
                'error'  => 'Desculpe, seu arquivo é muito grande'
            ];

            $uploadOk = 0;
        }
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            return [
                'return' => false,
                'error'  => 'Desculpe, sómente arquivos com a extensão JPG, JPEG e PNG são permitidos.'
            ];
            
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            return [
                'return' => false,
                'error'  => 'Desculpe, seu arquivo não foi transferido.'
            ];
            
        // if everything is ok, try to upload file
        } else {
            try{
                move_uploaded_file($_FILES["logo"]["tmp_name"], $target_file);

                return [
                    'return'    => true,
                    'nameFile'  => $nameFile
                ];
            }catch(EXCEPTION $e){
                return [
                    'return' => false,
                    'error'  => $e->getMessage()
                ];
            }
        }
    }

    /**
     * Exclui empresa pelo Id
     * @param type $idEmpresa
     */
    public function excluiEmpresa($idEmpresa) {
        $dados = array();

        if (!empty($idEmpresa)){

            $empresa = new Empresa();

            $validaEmpGrupos = $empresa->validaExclusaoDBGrupo($idEmpresa);
            $validaEmpUsers = $empresa->validaExclusaoDBEmpresa($idEmpresa);

            if ($validaEmpGrupos['total'] > 0 || $validaEmpUsers['total'] > 0):
                $this->helper->setAlert(
                    'error',
                    'Empresa não pode ser excluída, pois possui relacionamentos!',
                    'Empresa'
                );                  
            else:
                $res = $empresa->excluiEmpresa($idEmpresa);
                                
                if($res['return'] == true):
                    $this->helper->setAlert(
                        'success',
                        'Empresa excluída com sucesso!',
                        'Empresa'
                    );                               
                else:
                    $this->helper->setAlert(
                        'error',
                        'Erro ao excluir empresa!<br>' + $res['error'],
                        'Empresa'
                    );                    
                endif;
                
            endif;
        }
    }

    public function ajaxBuscaIntegrationData()
    {
        $post = $_POST;
        if(!isset($post['idEmpresa']) || empty($post['idEmpresa'])):
            echo json_encode([
                'return' => false,
                'error'  => 'Instância não informada'
            ]);
        endif;

        $empresa = new Empresa();

        $dadosEmp = $empresa->carregaEmpresa($post['idEmpresa']);

        if(count($dadosEmp) > 0):
            $data = json_decode($dadosEmp[0]['integration_data']);
            
            echo json_encode([
                'return' => true,
                'dados'  => $data->execBO
            ]);
        else:
            echo json_encode([
                'return' => false,                
            ]);
        endif;
    }

}
