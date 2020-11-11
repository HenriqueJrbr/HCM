<?php
//http://developer:8080/wsexecbo/WebServiceExecBO?wsdl

/**
 * Código dos retornos
 * E_001 = Relacionamento já existente
 */

class ChannelAdvisorAuth { 
    public $DeveloperKey; 
    public $Password; 

    public function __construct($key, $pass){ 
        $this->DeveloperKey = $key; 
        $this->Password = $pass; 
    } 
} 

class ExecBO{        
	public function rodaExecBo($programa, $procedure, $data)
        {                     
            set_time_limit(0);
            //print_r($procedure);
		if($procedure == 'piGrupoUsuario'):
			/*********************************************************************************************************
			* COMEÇO JSON PARA PROCEDURE piGrupoUsuario							         *
			*********************************************************************************************************/
			$JsonParam = ' [ {"name":"ttGrupoUsuario",
				"type":"input",
				"dataType":"temptable",
				"value":{"name":"ttGrupoUsuario",
						"fields":[  {"name":"cod_usuario","label":"cod_usuario","type":"character"},
									{"name":"cod_grp_usuar","label":"cod_grp_usuar","type":"character"},
									{"name":"acao","label":"acao","type":"character"}],
						"records":[{"cod_usuario":"' . $data['codUsuario'] . '","cod_grp_usuar":"' . $data['idLegGrupo'] . '","acao":"'.$data['tipo'].'"}]
					   }
				},
				{"dataType":"character","name":"retorno","value":"","type":"output"} ]';
			/*********************************************************************************************************
			* FIM JSON PARA PROCEDURE piGrupoUsuario							         *
			*********************************************************************************************************/
                elseif($procedure == 'piGrupoPrograma'):
                    // Remove coluna idPrograma
                    if(isset($data[0]['idPrograma'])):
                        foreach($data as $keyData => $value):                                
                            unset($data[$keyData]['idPrograma']);
                        endforeach;
                    endif;
                    
                    
			/*********************************************************************************************************
			* COMEÇO JSON PARA PROCEDURE piGrupoPrograma							         *
			*********************************************************************************************************/
			$JsonParam = ' [ {"name":"ttGrupoPrograma",
				"type":"input",
				"dataType":"temptable",
				"value":{"name":"ttGrupoPrograma",
						"fields":[  {"name":"cod_prog_dtsul","label":"cod_usuario","type":"character"},
									{"name":"cod_grp_usuar","label":"cod_grp_usuar","type":"character"},
									{"name":"acao","label":"acao","type":"character"}],
						"records":'.json_encode($data).'
					   }
				},
				{"dataType":"character","name":"retorno","value":"","type":"output"} ]';                        
                        /*********************************************************************************************************
			* FIM JSON PARA PROCEDURE piGrupoPrograma							         *
			*********************************************************************************************************/
		elseif($procedure == 'piBloqueiaUsuario'):
			/*********************************************************************************************************
			* COMEÇO JSON PARA PROCEDURE piBloqueiaUsuario 						                 *
			*********************************************************************************************************/
			$JsonParam = '[
                    {"dataType":"character","name":"c_usuario","value":"'.$data['codUsuario'].'","type":"input"},
                    {"dataType":"character","name":"retorno","value":"","type":"output"}
                ]';
		endif;
                
                echo "<pre>"; 
                //echo $JsonParam; 
                //die();
		
                $devKey      = "";
		$password    = "";
		$accountId   = "";
		$token = '';

                // Retorna os dados de configuração do execBO da base
                $idEmpresa = $_SESSION['empresaid'];
                $empresa = new Empresa();
                $integrationData = $empresa->getIntegrationData($idEmpresa, 'execBO');                
                                                
                try{                                       
                    if(isset($integrationData->integra) && $integrationData->integra == 1):
                        // Create the SoapClient instance
                        $url        = $integrationData->url;
                        $client     = new SoapClient($url, array("trace" => 1, "exception" => 0));
                        
                        // Create the header
                        $auth       = new ChannelAdvisorAuth($integrationData->devKey, $integrationData->password);
                        $header     = new SoapHeader("http://www.example.com/webservices/", "APICredentials", $auth, false);


                        $client->__setLocation($url);
                        $client->__getFunctions();
                        

                        /*pegando o Token*/
                        //$result = $client->userLogin(array('arg0'=> "super"));
                        $result = $client->userLogin(array('arg0'=> $integrationData->userLogin));
                        
                        $token = $result->return;
                        //echo "Auth: <br>";
                        //print_r($auth);
                        //echo "<br>";
                        //echo "header: <br>";
                        //print_r($header);
                        //echo "<br>";
                        //echo "client: <br>";
                        //print_r($client);                        
                        //echo "<br>";
                        //echo "token: <br>";
                        //print_r($token);
                        //echo "<br>";
                        //print_r($JsonParam);
                        
                        $result = $client->callProcedureWithToken(
                            array(
                                'arg0'=>$token,
                                'arg1'=>$programa,
                                'arg2'=>$procedure,
                                'arg3'=>$JsonParam
                            )
                        );                                                
                        $data = json_decode($result->return);
                        //echo "<pre>";                                                 
                        $exp = explode(';', $data[0]->value);
                        //$exp = array_pop($exp);
                        
                        $success = [];
                        $error = [];
                        
                        // Valida o retorno e devolve array de sucesso e erro
                        foreach($exp as $value):                            
                            $res = explode(' - ', (trim(str_replace(array('OK | ', 'NOK | '), '', $value))));                            
                            if((isset($res[3]) && (in_array($res[3], array('OK', 'jß existe', 'já existe')))) || (isset($res[2]) && (in_array($res[2], array('OK', 'jß existe', 'já existe'))))):
                                array_push($success, $res[1]);
                            elseif(isset($res[0]) && !empty($res[0])):
                                array_push($error, [$res[0] => $res[2]]);
                            endif;
                        endforeach;
                        return array(
                            'success' => $success,
                            'error'   => $error
                        );                                                
                        
                        //die('parou');
                        
                        if(trim($exp[0]) == 'OK'):
                            return array('return' => 'OK');
                        endif;
                        
                        if(trim($exp[0]) == 'NOK' && ((isset(explode(' - ', trim($exp[1]))[2]) && explode(' - ', trim($exp[1]))[2] == 'jß existe') || (isset(explode(' - ', trim($exp[1]))[2]) && explode(' - ', trim($exp[1]))[2] == 'já existe'))):
                            return array('return' => 'ja existe');                            
                        endif;
                        
                        if(trim($exp[0]) == 'NOK' && ((isset(explode(' - ', trim($exp[1]))[2]) && explode(' - ', trim($exp[1]))[2] == 'nÒo encontrado') || (isset($exp[1]) && trim($exp[1]) == 'Usußrio nÒo encontrado'))):
                            return array('return' => 'nao encontrado');                            
                        endif;
                        
                        if(trim($exp[0]) == 'NOK'):                           
                            return array('error'  => $exp[1]);
                        endif;
                        
                    endif;
                } catch (Exception $e) {                    
                    return array(                        
                        'return' => false,
                        'error'  => $e->getMessage()
                    );
                }
                
                
	}
        
        /**
         * Integra com Totvs e devolve o resultado
         * @param type $programa
         * @param type $procedure
         * @param type $JsonParam
         * @return type
         */
        public function rodaExecBoIntegra($programa, $procedure, $JsonParam)
        {                     
            echo "<pre>";
            set_time_limit(0);            
            $token = '';

            // Retorna os dados de configuração do execBO da base
            $idEmpresa = $_SESSION['empresaid'];
            $empresa = new Empresa();
            $integrationData = $empresa->getIntegrationData($idEmpresa, 'execBO');
            var_dump($integrationData);
            try{
                if(isset($integrationData->integra) && $integrationData->integra === "true"):
                    
                    // Create the SoapClient instance
                    $url        = trim($integrationData->url);
                    //$url        = trim("http://papaiz-ne.dts-teste.totvscloud.com.br/wsexecbo/WebServiceExecBO?wsdl");
                    $client     = new SoapClient($url, array(
                        "trace" => true, 
                        "exception" => false,
                        'soap_version'=>SOAP_1_2, 
                        'cache_wsdl' => WSDL_CACHE_NONE 
                    ));
                    
                    // Create the header
                    $auth       = new ChannelAdvisorAuth($integrationData->devKey, $integrationData->password);
                    $header     = new SoapHeader("http://www.example.com/webservices/", "APICredentials", $auth, false);
                    
                    $client->__setLocation($url);
                    $client->__getFunctions();
                
                    var_dump($client->__getFunctions());
                   
                    /*pegando o Token*/
                    $result = $client->userLogin(array('arg0'=> $integrationData->userLogin));
                    var_dump($result);
                    $token = $result->return;
                    // return $client->callProcedureWithToken(
                        var_dump($client->callProcedureWithToken(
                        array(
                            'arg0'=>$token,
                            'arg1'=>$programa,
                            'arg2'=>$procedure,
                            'arg3'=>$JsonParam
                        ))
                    );
                    die;
                endif;
            } catch (Exception $e){
                die($e->getMessage());
                return array(
                    'return' => false,
                    'error'  => $e->getMessage()
                );
            }
	}
        
        /**         
         * Método responsável pela integração de Grupo - Usuário
         * @param type $acao ESC ou INC
         * @param type $data Array com os dados para integração
         * @return type
         */
        public function execboGrupoUsuario($acao, $data)
        {
            // Remove coluna idPrograma
            if(isset($data[0]['idUsuario'])):
                foreach($data as $keyData => $value):
                    unset($data[$keyData]['idUsuario']);
                    unset($data[$keyData]['descAbrev']);
                    unset($data[$keyData]['idGrupo']);
                endforeach;
            endif;
           
            /*********************************************************************************************************
            * JSON PARA PROCEDURE piGrupoUsuario							         *
            *********************************************************************************************************/
            $JsonParam = '[
                {
                    "name":"ttGrupoUsuario",
                    "type":"input",
                    "dataType":"temptable",
                    "value":{
                        "name":"ttGrupoUsuario",
                        "fields":[
                            {"name":"cod_usuario","label":"cod_usuario","type":"character"},
                            {"name":"cod_grp_usuar","label":"cod_grp_usuar","type":"character"},
                            {"name":"acao","label":"acao","type":"character"}
                        ],
                        "records":'.json_encode($data).'                        
                    }
                },
                {"dataType":"character","name":"retorno","value":"","type":"output"}
            ]';                                    
            echo "<pre>";
            echo $JsonParam;
            $return = $this->rodaExecBoIntegra('sga/esp/essga005b.p', 'piGrupoUsuario', $JsonParam);
            echo "<pre>";
            var_dump($return);
            die('parei');
            // Valida se foi executado com sucesso, caso não, retorna a mensagem de erro
            if(is_array($return) && isset($return['error'])):
                return array(
                    'return'    => false,
                    'error'     => $return['error']
                );
            endif;
            
            $result = json_decode($return->return, true);
            $exp = json_decode($result[0]['value']);
            $success = [];
            $error = [];

            //var_dump($result);
            //var_dump($exp->retorno->msg);
            //die;
            /* Prepara o retorno dependendo da ação
             * Se for INC, considera os retornos OK, já existe e jß existe como sucesso
             * Se for ESC, considera os retornos OK, nÒo encontrado como sucesso
             */
            if($acao == 'INC'):
                // Valida o retorno e devolve array de sucesso e erro
                foreach($exp->retorno->msg as $value):                    
                    if($value->Result == 'true'):
                        array_push($success, $value->usuario);
                    else:
                        array_push($error, [$value->usuario => $value->info]);
                    endif;
                endforeach;
            elseif($acao == 'ESC'):
                //echo "<pre>";                                
                foreach($exp->retorno->msg as $value):                                
                    if($value->Result == 'true'):
                        array_push($success, $value->usuario);
                    else:
                        array_push($error, [$value->usuario => $value->info]);
                    endif;
                endforeach;                
            endif;
            
            //echo "success <br>"; 
            //print_r($success);
            //echo "error <br>";
            //print_r($error);
            //die('ESC');
            return array(
                'success' => $success,
                'error'   => $error
            );
        }
        
        /**
         * Método responsável pela integração de Grupo - Programa
         * @param type $acao ESC ou INC
         * @param type $data Array com os dados para integração
         * @return type
         */
        public function execboGrupoPrograma($acao, $data)
        {
            // Remove coluna idPrograma
            if(isset($data[0]['idPrograma'])):
                foreach($data as $keyData => $value):
                    unset($data[$keyData]['idPrograma']);
                endforeach;
            endif;

            /*********************************************************************************************************
            * JSON PARA PROCEDURE piGrupoPrograma							         *
            *********************************************************************************************************/
            $JsonParam = ' 
                [
                    {
                        "name":"ttGrupoPrograma",
                        "type":"input",
                        "dataType":"temptable",
                        "value":{
                            "name":"ttGrupoPrograma",
                            "fields":[
                                {"name":"cod_prog_dtsul","label":"cod_usuario","type":"character"},
                                {"name":"cod_grp_usuar","label":"cod_grp_usuar","type":"character"},
                                {"name":"acao","label":"acao","type":"character"}
                            ],
                            "records":'.json_encode($data).'
                        }
                    },
                    {"dataType":"character","name":"retorno","value":"","type":"output"}
                ]';                                    

                //echo "<pre>";
                //die($JsonParam);
            $return = $this->rodaExecBoIntegra('sga/esp/essga005b.p', 'piGrupoPrograma', $JsonParam);
            
            // Valida se foi executado com sucesso, caso não, retorna a mensagem de erro
            if(is_array($return) && isset($return['error'])):
                return array(
                    'return'    => false,
                    'error'     => $return['error']
                );
            endif;
            echo "<pre>";
            $result = json_decode($return->return, true);
            $exp = json_decode($result[0]['value']);
            $success = [];
            $error = [];
            //print_r($exp->retorno->msg);
            //die;
            /* Prepara o retorno dependendo da ação
             * Se for INC, considera os retornos OK, já existe e jß existe como sucesso
             * Se for ESC, considera os retornos OK, nÒo encontrado como sucesso
             */
            if($acao == 'INC'):
                // Valida o retorno e devolve array de sucesso e erro
                foreach($exp->retorno->msg as $value):                    
                    if($value->Result):
                        array_push($success, $value->programa);
                    else:
                        array_push($error, [$value->programa => $value->info]);
                    endif;
                endforeach;
            elseif($acao == 'ESC'):
                foreach($exp->retorno->msg as $value):                    
                    if($value->Result):
                        array_push($success, $value->programa);
                    else:
                        array_push($error, [$value->programa => $value->info]);
                    endif;
                endforeach;                
            endif;
            
            return array(
                'success' => $success,
                'error'   => $error
            );
        }


    /**
     * Método responsável pela integração de Grupo
     * @param type $acao ESC ou INC
     * @param type $data Array com os dados para integração
     * @return type
     */
    public function execboGrupo($acao, $data)
    {
        // Remove coluna idPrograma
        /*********************************************************************************************************
         * JSON PARA PROCEDURE piGrupo							                                                 *
         *********************************************************************************************************/
        $JsonParam = '[
                {
                    "name":"ttGrupo",
                    "type":"input",
                    "dataType":"temptable",
                    "value":{
                        "name":"ttGrupo",
                        "fields":[
                            {"name":"id_leg_grupo","label":"id_leg_grupo","type":"character"},
                            {"name":"desc_abrev","label":"desc_abrev","type":"character"},
                            {"name":"acao","label":"acao","type":"character"},
                            {"name":"copyUsr","label":"copyUsr","type":"character"},
                            {"name":"copyProgs","label":"copyProgs","type":"character"}                            
                        ],
                        "records":['.json_encode($data).']                        
                    }
                },
                {"dataType":"character","name":"retorno","value":"","type":"output"}
            ]';

        //echo "<pre>";
        //print_r($JsonParam);
        //die;

        // exemplo dos dados a enviar
        //[{"id_leg_grupo":"ab0606","cod_grp_usuar":"sop","acao":"INC"}]

        $return = $this->rodaExecBoIntegra('sga/esp/essga005b.p', 'piGrupo', $JsonParam);

        // Valida se foi executado com sucesso, caso não, retorna a mensagem de erro
        if(is_array($return) && isset($return['error'])):
            return array(
                'return'    => false,
                'error'     => $return['error']
            );
        endif;

        $result = json_decode($return->return, true);
        $exp = json_decode($result[0]['value']);
        $success = [];
        $error = [];


        //echo "<pre>";
        var_dump($return);die;
        //var_dump($exp);
        //print_r(gettype($exp));

        /* Prepara o retorno dependendo da ação
         * Se for INC, considera os retornos OK, já existe e jß existe como sucesso
         * Se for ESC, considera os retornos OK, nÒo encontrado como sucesso
         */
        if($acao == 'INC'):
            // Valida o retorno e devolve array de sucesso e erro
            foreach($exp->retorno->msg as $value):
                if($value->Result == 'true'):
                    array_push($success, [$value->grupo => $value->info]);
                else:
                    array_push($error, [$value->grupo => $value->info]);
                endif;
            endforeach;
        elseif($acao == 'ESC'):
            foreach($exp->retorno->msg as $value):
                if($value->Result == 'true'):
                    array_push($success, [$value->grupo => $value->info]);
                else:
                    array_push($error, [$value->grupo => $value->info]);
                endif;
            endforeach;
        endif;

        //echo "success <br>";
        //print_r($success);
        //echo "error <br>";
        //print_r($error);
        //die('ESC');
        return array(
            'success' => $success,
            'error'   => $error
        );
    }
}


