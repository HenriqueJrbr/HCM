<?php
class ApiController extends Controller 
{
    protected $helper;
    
    public function __construct() {
        parent::__construct();
        $login = new Login();
        $this->helper = new helper();
    }

    public function index() {
        $data = array();
        
        //$this->loadTemplate('docApi', $data);
    }

    public function doc() {
        $data = array();
        $login = new Login();

        if (!$login->islogin()) {
            header('Location: ' . URL . '/Login');
        }
        
        $this->loadTemplate('docApi', $data);
    }
  
  
    public function  getEmpresa($idEmpresa,$token){
        $api = new Api();
        $jwt = new Jwt();
        if(isset($token) && !empty($token)){
            $jwtReturn = $jwt->validate($token);

            if($jwtReturn){
                //$idEmpresa = addslashes($_GET['idEmpresa']);
                $retorno =  $api->getEmpresa($idEmpresa);
                
                if(!empty($retorno)){
                    header("Content-Type: application/json");
                    echo json_encode($retorno);
                }else{
                    echo "Erro ao  Cadastrar";
                }

            }else{
                echo "Token expirado";
            }   
        }  
    }
       
    
    /**
     * Método responsáveis pelo funcoes
     * Segundo tipo de requisição. POST, GET e DELETE
     * @param type $token      
     */
    public function funcoes($token = "")
    {
        $api = new Api();
        $jwt = new Jwt();
        
        // Recupera o tipo de requisição. Seta para json o tipo de recebimento dos parâmetros
        $metodo = $_SERVER['REQUEST_METHOD'];
        header('Content-Type:application/json');
        $body = file_get_contents('php://input');
        $jsonBody = json_decode($body);       
        
        // Retorna os dados das funcoes
        if($metodo === 'GET'):            
            if($jwt->validate($jsonBody->token)):
                $retorno = $api->getFuncoes($jsonBody);                
                echo json_encode($this->helper->formataRetornoApi($retorno, $jsonBody));
            else:
                echo json_encode(array(
                    'retorno'   => 'erro',
                    'msg'       => 'Token expirado'
                ));
            endif;   
        endif;                      
    }
    
    /**
     * Método responsáveis pelo relacionamento de usuários e gestores
     * Segundo tipo de requisição. POST e DELETE
     * @param $token      
     */
    public function gestores($token = "")
    {
        $api = new Api();
        $jwt = new Jwt();
        
        // Recupera o tipo de requisição. Seta para json o tipo de recebimento dos parâmetros
        $metodo = $_SERVER['REQUEST_METHOD'];
        header('Content-Type:application/json');
        $body = file_get_contents('php://input');
        $jsonBody = json_decode($body);

        // Insere gestor a um usuário no sga com base nos dados recebidos via json
        if($metodo === 'POST'){            
            // Valida o token
            if($jwt->validate($jsonBody->token)){
                $retorno = $api->updateGestorUsuario($jsonBody->cod_usuario, $jsonBody->cod_gestor);
                echo json_encode($this->helper->formataRetornoApi($retorno, $jsonBody));
            }else{
                echo json_encode(array(
                    'retorno'   => 'erro',
                    'msg'       => "Token expirado"
                ));
            }   
        }       
        
        // Retorna os dados do gestores
        if($metodo === 'GET'):            
            if($jwt->validate($jsonBody->token)):
                $retorno = $api->getGestores($jsonBody);                
                echo json_encode($this->helper->formataRetornoApi($retorno, $jsonBody));
            else:
                echo json_encode(array(
                    'retorno'   => 'erro',
                    'msg'       => 'Token expirado'
                ));
            endif;   
        endif;
        
        // Exclui gestor de usuário da base.
        /*if($metodo === 'DELETE'):
            if($jwt->validate($jsonBody->token)):
                $retorno = $api->deleteGestorUsuarios($jsonBody);
                $resJson = array();
                
                // Valida retorno. E retorna json com as informações.
                echo json_encode($this->helper->formataRetornoApi($retorno, $jsonBody));
            else:
                echo json_encode(array(
                    'retorno'  => 'erro',
                    'msg'      => 'Token expirado'
                ));
            endif;
        endif;*/         
    }

    /**
     * Método responsáveis por Inserir, atualizar e deletar usuários no sga.
     * Segundo tipo de requisição. POST, PUT ou DELETE
     * @param type $token 
     * @param type $cod_usuario
     */
    public function usuarios($token = "", $cod_usuario = ""){
        $api = new Api();
        $jwt = new Jwt();
        
        // Recupera o tipo de requisição. Seta para json o tipo de recebimento dos parâmetros
        $metodo = $_SERVER['REQUEST_METHOD'];
        header('Content-Type:application/json');
        $body = file_get_contents('php://input');
        $jsonBody = json_decode($body);

        // Cria novo usuário no sga com base nos dados recebidos via json
        if($metodo === 'POST'){            
            // Valida o token
            if($jwt->validate($jsonBody->token)){
                $retorno = $api->postUsuarios($jsonBody->cod_usuario, $jsonBody->nome_usuario, $jsonBody->instancia, '', $jsonBody->ativo);
                echo json_encode($this->helper->formataRetornoApi($retorno, $jsonBody));
            }else{
                echo json_encode(array(
                    'retorno'   => 'erro',
                    'msg'       => "Token expirado"
                ));
            }   
        }

        // Retorna os dados do usuário com base nos dados recebidos via json
        if($metodo === 'GET'):            
            if($jwt->validate($jsonBody->token)):
                $retorno = $api->getUsuarios($jsonBody->cod_usuario);                
                echo json_encode($this->helper->formataRetornoApi($retorno, $jsonBody));
            else:
                echo json_encode(array(
                    'retorno'   => 'erro',
                    'msg'       => "Token expirado"
                ));
            endif;   
        endif;

        // Atualiza usuário com base nos dados recebidos via json
        if($metodo === 'PUT'){
            if($jwt->validate($jsonBody->token)):
                $retorno = $api->putUsuarios($body);
                $resJson = array();
                
                // Valida retorno. E retorna json com as informações.
                echo json_encode($this->helper->formataRetornoApi($retorno, $jsonBody));
            else:
                echo json_encode(array(
                    'retorno'  => 'erro',
                    'msg'      => 'Token expirado'
                ));
            endif;
        }
        
        // Exclui um usuário da base e tudo que estiver associado a ele.
        if($metodo === 'DELETE'):
            if($jwt->validate($jsonBody->token)):
                $retorno = $api->deleteUsuarios($jsonBody);
                $resJson = array();
                
                // Valida retorno. E retorna json com as informações.
                echo json_encode($this->helper->formataRetornoApi($retorno, $jsonBody));
            else:
                echo json_encode(array(
                    'retorno'  => 'erro',
                    'msg'      => 'Token expirado'
                ));
            endif;
        endif;         
    }       
        
    /**
     * Método responsáveis por Inserir, atualizar e deletar grupos no sga.
     * Segundo tipo de requisição. POST, PUT ou DELETE
     * @param type $token 
     * @param type $id_leg_grupo
     */
    public function grupos($token = "", $id_leg_grupo = ""){
        $api = new Api();
        $jwt = new Jwt();
        
        // Recupera o tipo de requisição. Seta para json o tipo de recebimento dos parâmetros
        $metodo = $_SERVER['REQUEST_METHOD'];
        header('Content-Type:application/json');
        $body = file_get_contents('php://input');
        $jsonBody = json_decode($body);

        // Cria novo grupo no sga com base nos dados recebidos via json
        if($metodo === 'POST'){            
            // Valida o token
            if($jwt->validate($jsonBody->token)){
                $retorno = $api->postGrupos($jsonBody);
                echo json_encode($this->helper->formataRetornoApi($retorno, $jsonBody));
            }else{
                echo json_encode(array(
                    'retorno'   => 'erro',
                    'msg'       => "Token expirado"
                ));
            }   
        }

        // Retorna os dados do grupo com base nos dados recebidos via json
        if($metodo === 'GET'):            
            if($jwt->validate($jsonBody->token)):
                $retorno =  $api->getGrupos($jsonBody);                
                echo json_encode($this->helper->formataRetornoApi($retorno, $jsonBody));
            else:
                echo json_encode(array(
                    'retorno'   => 'erro',
                    'msg'       => "Token expirado"
                ));
            endif;   
        endif;

        // Atualiza grupo com base nos dados recebidos via json
        if($metodo === 'PUT'){
            if($jwt->validate($jsonBody->token)):
                $retorno = $api->putGrupos($jsonBody);
                $resJson = array();
                
                // Valida retorno. E retorna json com as informações.
                echo json_encode($this->helper->formataRetornoApi($retorno, $jsonBody));
            else:
                echo json_encode(array(
                    'retorno'  => 'erro',
                    'msg'      => 'Token expirado'
                ));
            endif;
        }
        
        // Exclui um usuário da base e tudo que estiver associado a ele.
        if($metodo === 'DELETE'):
            if($jwt->validate($jsonBody->token)):
                $retorno = $api->deleteGrupos($jsonBody);
                $resJson = array();
                
                // Valida retorno. E retorna json com as informações.
                echo json_encode($this->helper->formataRetornoApi($retorno, $jsonBody));
            else:
                echo json_encode(array(
                    'retorno'  => 'erro',
                    'msg'      => 'Token expirado'
                ));
            endif;
        endif;         
    }
    
    /**
     * Método responsáveis pelo relacionamento de usuários a grupos
     * Segundo tipo de requisição. POST, PUT ou DELETE
     * @param type $token 
     * @param type $cod_usuario
     */
    public function gruposusuarios($token = "", $id_leg_grupo = ""){
        $api = new Api();
        $jwt = new Jwt();
        
        // Recupera o tipo de requisição. Seta para json o tipo de recebimento dos parâmetros
        $metodo = $_SERVER['REQUEST_METHOD'];
        header('Content-Type:application/json');
        $body = file_get_contents('php://input');
        $jsonBody = json_decode($body);

        // Cria novo grupo no sga com base nos dados recebidos via json
        if($metodo === 'POST'){            
            // Valida o token
            if($jwt->validate($jsonBody->token)){
                $retorno = $api->postGruposUsuarios($jsonBody);
                echo json_encode($this->helper->formataRetornoApi($retorno, $jsonBody));              
            }else{
                echo json_encode(array(
                    'retorno'   => 'erro',
                    'msg'       => "Token expirado"
                ));
            }   
        }       
        
        // Exclui um usuário da base e tudo que estiver associado a ele.
        if($metodo === 'DELETE'):
            if($jwt->validate($jsonBody->token)):
                $retorno = $api->deleteGruposUsuarios($jsonBody);
                
                // Valida retorno. E retorna json com as informações.
                echo json_encode($this->helper->formataRetornoApi($retorno, $jsonBody));                    
            else:
                echo json_encode(array(
                    'retorno'  => 'erro',
                    'msg'      => 'Token expirado'
                ));
            endif;
        endif;         
    }
    
    /**
     * Método responsáveis por Inserir, atualizar e deletar programas no sga.
     * Segundo tipo de requisição. POST, PUT ou DELETE
     * @param type $token 
     * @param type $cod_programa
     */
    public function programas($token = "", $cod_programa = ""){
        $api = new Api();
        $jwt = new Jwt();
        
        // Recupera o tipo de requisição. Seta para json o tipo de recebimento dos parâmetros
        $metodo = $_SERVER['REQUEST_METHOD'];
        header('Content-Type:application/json');
        $body = file_get_contents('php://input');
        $jsonBody = json_decode($body);

        // Cria novo programa no sga com base nos dados recebidos via json
        if($metodo === 'POST'){            
            // Valida o token
            if($jwt->validate($jsonBody->token)){
                $retorno = $api->postProgramas($body);
                echo json_encode($this->helper->formataRetornoApi($retorno, $jsonBody));
            }else{
                echo json_encode(array(
                    'retorno'   => 'erro',
                    'msg'       => "Token expirado"
                ));
            }   
        }

        // Retorna os dados do programa com base nos dados recebidos via json
        if($metodo === 'GET'):            
            if($jwt->validate($jsonBody->token)):
                $retorno = $api->getProgramas($jsonBody);                
                echo json_encode($this->helper->formataRetornoApi($retorno, $jsonBody));
            else:
                echo json_encode(array(
                    'retorno'   => 'erro',
                    'msg'       => "Token expirado"
                ));
            endif;   
        endif;

        // Atualiza programas com base nos dados recebidos via json
        if($metodo === 'PUT'){
            if($jwt->validate($jsonBody->token)):
                $retorno = $api->putProgramas($body);
                $resJson = array();
                
                // Valida retorno. E retorna json com as informações.
                echo json_encode($this->helper->formataRetornoApi($retorno, $jsonBody));
            else:
                echo json_encode(array(
                    'retorno'  => 'erro',
                    'msg'      => 'Token expirado'
                ));
            endif;
        }
        
        // Exclui um programa da base e tudo que estiver associado a ele.
        if($metodo === 'DELETE'):
            if($jwt->validate($jsonBody->token)):                
                $retorno = $api->deleteProgramas($jsonBody);
                // Valida retorno. E retorna json com as informações.
                echo json_encode($this->helper->formataRetornoApi($retorno, $jsonBody));
            else:
                echo json_encode(array(
                    'retorno'  => 'erro',
                    'msg'      => 'Token expirado'
                ));
            endif;
        endif;
    } 
    
    /**
     * Método responsáveis por Inserir, atualizar e deletar programas no sga.
     * Segundo tipo de requisição. POST, PUT ou DELETE
     * @param type $token 
     * @param type $cod_programa
     */
    public function programaslog($token = "", $cod_programa = ""){
        $api = new Api();
        $jwt = new Jwt();
        
        // Recupera o tipo de requisição. Seta para json o tipo de recebimento dos parâmetros
        $metodo = $_SERVER['REQUEST_METHOD'];
        header('Content-Type:application/json');
        $body = file_get_contents('php://input');
        $jsonBody = json_decode($body);

        // Cria novo programa no sga com base nos dados recebidos via json
        if($metodo === 'POST'){            
            // Valida o token
            if($jwt->validate($jsonBody->token)){
                $retorno = $api->postProgramasLog($body);
                echo json_encode($this->helper->formataRetornoApi($retorno, $jsonBody));
            }else{
                echo json_encode(array(
                    'retorno'   => 'erro',
                    'msg'       => "Token expirado"
                ));
            }   
        }                
    } 
    
    
    /**
     * Método responsáveis pelo relacionamento de programs a grupos
     * Segundo tipo de requisição. POST e DELETE
     * @param type $token      
     */
    public function gruposprogramas($token = ""){
        $api = new Api();
        $jwt = new Jwt();
        
        // Recupera o tipo de requisição. Seta para json o tipo de recebimento dos parâmetros
        $metodo = $_SERVER['REQUEST_METHOD'];
        header('Content-Type:application/json');
        $body = file_get_contents('php://input');
        $jsonBody = json_decode($body);

        // Insere um programa a um grupo
        if($metodo === 'POST'){            
            // Valida o token
            if($jwt->validate($jsonBody->token)){
                $retorno = $api->postGruposProgramas($jsonBody);
                echo json_encode($this->helper->formataRetornoApi($retorno, $jsonBody));
            }else{
                echo json_encode(array(
                    'retorno'   => 'erro',
                    'msg'       => "Token expirado"
                ));
            }   
        }       
        
        // Remove um programa de um grupo
        if($metodo === 'DELETE'):
            if($jwt->validate($jsonBody->token)):
                $retorno = $api->deleteGruposProgramas($jsonBody);
				
                // Valida retorno. E retorna json com as informações.
                echo json_encode($this->helper->formataRetornoApi($retorno, $jsonBody));
            else:
                echo json_encode(array(
                    'retorno'  => 'erro',
                    'msg'      => 'Token expirado'
                ));
            endif;
        endif;         
    }
    
    /**
     * Método responsáveis por Inserir, atualizar e deletar módulos no sga.
     * Segundo tipo de requisição. POST, PUT ou DELETE
     * @param type $token      
     */
    public function modulos($token = ""){
        $api = new Api();
        $jwt = new Jwt();
        
        // Recupera o tipo de requisição. Seta para json o tipo de recebimento dos parâmetros
        $metodo = $_SERVER['REQUEST_METHOD'];
        header('Content-Type:application/json');
        $body = file_get_contents('php://input');
        $jsonBody = json_decode($body);

        // Cria novo modulo no sga com base nos dados recebidos via json
        if($metodo === 'POST'){            
            // Valida o token
            if($jwt->validate($jsonBody->token)){
                $retorno = $api->postModulos($body);
                echo json_encode($this->helper->formataRetornoApi($retorno, $jsonBody));
            }else{
                echo json_encode(array(
                    'retorno'   => 'erro',
                    'msg'       => "Token expirado"
                ));
            }   
        }

        // Retorna os dados do modulo com base nos dados recebidos via json
        if($metodo === 'GET'):            
            if($jwt->validate($jsonBody->token)):
                $retorno = $api->getModulos($jsonBody);                
                echo json_encode($this->helper->formataRetornoApi($retorno, $jsonBody));
            else:
                echo json_encode(array(
                    'retorno'   => 'erro',
                    'msg'       => "Token expirado"
                ));
            endif;   
        endif;

        // Atualiza modulo com base nos dados recebidos via json
        if($metodo === 'PUT'){
            if($jwt->validate($jsonBody->token)):
                $retorno = $api->putModulos($body);
                $resJson = array();
                
                // Valida retorno. E retorna json com as informações.
                echo json_encode($this->helper->formataRetornoApi($retorno, $jsonBody));
            else:
                echo json_encode(array(
                    'retorno'  => 'erro',
                    'msg'      => 'Token expirado'
                ));
            endif;
        }
        
        // Exclui um modulo da base e tudo que estiver associado a ele.
        if($metodo === 'DELETE'):
            if($jwt->validate($jsonBody->token)):                
                $retorno = $api->deleteModulos($jsonBody);
                // Valida retorno. E retorna json com as informações.
                echo json_encode($this->helper->formataRetornoApi($retorno, $jsonBody));
            else:
                echo json_encode(array(
                    'retorno'  => 'erro',
                    'msg'      => 'Token expirado'
                ));
            endif;
        endif;
    }
    
    public function token(){
        $jwt = new Jwt();
        $api = new Api();

        $metodo = $_SERVER['REQUEST_METHOD'];
        header('Content-Type:application/json');
        $body = file_get_contents('php://input');                
        
        if($metodo === 'POST'){
            $jsonBody = json_decode($body);
            
            // Recupera os dados de configuração do sga
            $config = $api->getConfigGlobal();
            
            // Valida o ambiente
            if($config['ambiente'] == $jsonBody->ambiente):
                // Grava log de requisição
                //$this->helper->formataRetornoApi('', $jsonBody);

                if($api->validalogin($jsonBody->usuario, $jsonBody->senha)){ 
                    echo (json_encode($this->helper->formataRetornoApi(
                        array(
                            'return' => 'sucesso',
                            'msg'    => 'Token gerado com sucesso',
                            'token' => $jwt->create()
                        ), 
                        $jsonBody
                    )));
                }else{
                    echo json_encode($this->helper->formataRetornoApi(array(
                        'return' => 'erro',
                        'msg' => "Usuário ou senha invalido"                    
                    ), $jsonBody));
                }
            else:
                echo json_encode($this->helper->formataRetornoApi(array(
                    'return' => 'erro',
                    'msg'     => 'Ambiente informado TOTVS: '.$jsonBody->ambiente.'. Ambiente SGA: '.$config['ambiente'] 
                ), $jsonBody));
            endif;
        }
    }

    public function envia(){
        $api = new Api();
        $token = $api->token();

        $iniciar = curl_init('http://localhost/hm/Api/token');
        curl_setopt($iniciar, CURLOPT_RETURNTRANSFER, true);
        $dados = array(
            "usuario"=>'helpersga',
            "senha"=>"ngf123",
            'origem' => 'ERP',
            'sisetma' => 'DATASUL',
            'ambiente' => 'DEV'
              
        );
        curl_setopt($iniciar, CURLOPT_HTTPGET, true);
        curl_setopt($iniciar, CURLOPT_POSTFIELDS, json_encode($dados));
        $retornoToken = curl_exec($iniciar);
        curl_close($iniciar);
        print_r($retornoToken);
        
        return $retornoToken;

    }

    public function post(){
        $iniciar2 = curl_init('http://localhost/hm/Api/gruposusuarios');
        curl_setopt($iniciar2, CURLOPT_RETURNTRANSFER, true);
        $token = json_decode($this->envia());        
        $token = trim($token->token);
        
        $dados2 = array(
            "token"=>$token,              
            "instancia"=>"2",
            'sistema'   => 'DATASUL',
            'origem'    => 'ERP',            
            //'cod_usuario'=> 'r_teste',
            //'email'=> 'teste@a2solutions.com.br',
			"cod_usuario"=>"sgatst2",
			"id_leg_grupo"=>"sup",
            "desc_abrev"=>"sup",
			"nome_usuario"=>"Sga teste 2",
			"email"=>"",
			"idusrfluig"=>"sgatst2",
			"ativo"=>"1",
			"usrExecutor"=>"super"
        );

        curl_setopt($iniciar2, CURLOPT_HTTPGET, true);
        curl_setopt($iniciar2, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($iniciar2, CURLOPT_POSTFIELDS, json_encode($dados2));
        $retorno = curl_exec($iniciar2);
        curl_close($iniciar2);
        echo $retorno;
    }
    
    public function delete(){
        $iniciar2 = curl_init('http://localhost/hm/Api/gruposusuarios');
        curl_setopt($iniciar2, CURLOPT_RETURNTRANSFER, true);        
        $token = json_decode($this->envia());        
        //print_r($token);
        $token = trim($token->token);
        
        $dados2 = array(
            "token"=>$token,              
            "instancia"=>"2",
            'sistema'   => 'DATASUL',
            'origem'    => 'ERP',            
            "cod_usuario"=>"sgatst",
			"id_leg_grupo"=>"001",
			"nome_usuario"=>"Sga teste",
			"email"=>"",
			"idusrfluig"=>"sgatst",
			"ativo"=>"1",
			"ambiente" => "DEV"
        );

        curl_setopt($iniciar2, CURLOPT_HTTPGET, true);
        curl_setopt($iniciar2, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($iniciar2, CURLOPT_POSTFIELDS, json_encode($dados2));
        $retorno = curl_exec($iniciar2);
        curl_close($iniciar2);
        echo $retorno;
    }
    
    public function put(){
        $iniciar2 = curl_init('http://dev.appsga.com.br/Api/usuarios');
        curl_setopt($iniciar2, CURLOPT_RETURNTRANSFER, true);        
        $token = json_decode($this->envia());        
        $token = trim($token->token);
                
        $dados2 = array(
            "token" => $token,            
            "instancia" => "1",                                          
            'sistema'   => 'DATASUL',
            'origem'    => 'ERP',            
            'cod_usuario'=> 'r_teste10',
            'nome_usuario' => 'Rodrigo Teste 10000',
            'cpf'=> '1234567890',
            'cod_gestor'=> 'r_nascimento',
            'cod_funcao'=> '1',
            'funcao'=> '1',
            'solicitante'=> 'S',
            'gestor_usuario'=> 'S',
            'gestor_grupo'=> 'N',
            'gestor_programa'=> 'N',
            'si'=> 'N',
            'idusrfluig'=> 'r_teste10000',
            'instancia'=> '1',
            'ativo'=> '1',
            'idDepartamento' => '2'
        );

        curl_setopt($iniciar2, CURLOPT_HTTPGET, true);
        curl_setopt($iniciar2, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($iniciar2, CURLOPT_POSTFIELDS, json_encode($dados2));
        $retorno = curl_exec($iniciar2);
        curl_close($iniciar2);
        echo $retorno;
    }
    
    
       public function get(){
        $iniciar2 = curl_init('http://brook.appsga.com.br/hm/Api/funcoes');
        curl_setopt($iniciar2, CURLOPT_RETURNTRANSFER, true);        
      

         $iniciar3 = curl_init('http://brook.appsga.com.br/hm/Api/token');
            curl_setopt($iniciar3, CURLOPT_RETURNTRANSFER, true);

            $dados3 = array(
            "usuario" => 'helpersga',            
            "senha" => "ngf123",                                          
            'ambiente'   => 'DEV',

        );

        curl_setopt($iniciar3, CURLOPT_HTTPGET, true);
        curl_setopt($iniciar3, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($iniciar3, CURLOPT_POSTFIELDS, json_encode($dados3));
        $token = curl_exec($iniciar3);
        curl_close($iniciar3);
        $t = json_decode($token);
       
        //print_r($t);
        

        $dados2 = array(
            "token" => trim($t->token),            
            "instancia" => "2",                                          
            'sistema'   => 'DATASUL',
            'origem'    => 'ERP',
            'inicial'   => '0',
            'final'     => '100'
        );

        curl_setopt($iniciar2, CURLOPT_HTTPGET, true);
        curl_setopt($iniciar2, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($iniciar2, CURLOPT_POSTFIELDS, json_encode($dados2));
        $retorno = curl_exec($iniciar2);
        curl_close($iniciar2);
       
        echo $retorno."<br>";
      
    }





  

}