<?php
header('Content-Type: text/html; charset=utf-8');
set_time_limit(0);
define('BASE_PATH_SINCRONIZACAO', str_replace('/script/sincronizacao.php', '', $_SERVER['SCRIPT_FILENAME']));

require BASE_PATH_SINCRONIZACAO.'/config.php';

require BASE_PATH_SINCRONIZACAO.'/core/Model.php';
require BASE_PATH_SINCRONIZACAO.'/models/Sincronizacao.php';      
global $config;

setlocale(LC_TIME, 'pt_BR');
date_default_timezone_set('America/Sao_Paulo');

try{
    $db = new PDO("mysql:dbname=".$config['dbname'].";host=".$config['host'], $config['dbuser'], $config['dbpass']);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    echo"Erro na Conexao ".$e->getMessage();
}

$instancias = '';

// Busca os dados de integração da instancia informada
$sql = "
    SELECT
        idEmpresa,
        idLegEmpresa,
        JSON_EXTRACT(
            integration_data, 		
            \"$.execBO\"
        ) AS integrationData 
    FROM 
        z_sga_empresa 
    #WHERE 
        #idEmpresa = 1";
try{
    $rsIntegracao = $db->query($sql);

    if($rsIntegracao->rowCount() > 0):
        $rsIntegration = $rsIntegracao->fetchAll(PDO::FETCH_ASSOC);
        $instancias = $rsIntegration;
    endif;
} catch (Exception $e) {
    die($e->getMessage());
}

foreach($instancias as $integrationData):
    echo '********************* <strong>Instância: '.$integrationData['idEmpresa'].'</strong> **********************';
    echo "<br>";
    execboSincronizacao($db,$integrationData);
    // $file= 'S_12102020_000112.zip';
    // $carga = new CargaController();
    // $inicio = date('Y-m-d H:i:s');
    // $carga->iniciaCarga(0,$file, 2, $inicio, $db);
endforeach;

class ChannelAdvisorAuth {
    public $DeveloperKey;
    public $Password;

    public function __construct($key, $pass){
        $this->DeveloperKey = $key;
        $this->Password = $pass;
    }
}


/**
 * Integra com Totvs e devolve o resultado
 * @param type $programa
 * @param type $procedure
 * @param type $JsonParam
 * @return type
 */
function rodaExecBoIntegra($programa, $procedure, $JsonParam, $db, $integrationData)
{
    set_time_limit(0);
    $token = '';

    // Retorna os dados de configuração do execBO da base
    $idEmpresa = 1;
  
   //$integrationData=json_decode($integrationData['integrationData']);

    // Consome webservice
    try{        
        // Create the SoapClient instance
        $url        = $integrationData->url;
        $client     = new SoapClient($url, array("trace" => 1, "exception" => 0));
        
        $client->__setLocation($url);
        //, array(
          //  'trace' => true, 
            //"exception" => 1,
            //'keep_alive' => false,
            //'connection_timeout' => 5000,
            //'cache_wsdl' => WSDL_CACHE_NONE,
            //'compression'   => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE            
        //));

        // Create the header
        $auth       = new ChannelAdvisorAuth($integrationData->devKey, $integrationData->password);
        $header     = new SoapHeader("http://www.example.com/webservices/", "APICredentials", $auth, false);

        $client->__getFunctions();

        /*pegando o Token*/
        $result = $client->userLogin(array('arg0'=> $integrationData->userLogin));

        $token = $result->return;
        return $client->callProcedureWithToken(
            array(
                'arg0'=>$token,
                'arg1'=>$programa,
                'arg2'=>$procedure,
                'arg3'=>$JsonParam
            )
        );        
    } catch (Exception $e){
        return array(
            'return' => false,
            'error'  => $e->getMessage()
        );
    }
}

/**
 * Método responsável pela integração de Grupo
 * @param type $acao ESC ou INC
 * @param type $data Array com os dados para integração
 * @return type
 */
function execboSincronizacao($db, $integrationData)
{ 
    //setlocale(LC_TIME, 'pt_BR');
    //date_default_timezone_set('America/Sao_Paulo');

    $instancia = $integrationData['idEmpresa'];
    $nomeEmpresa = $integrationData['idLegEmpresa'];
    $integrationData = json_decode($integrationData['integrationData']);
    $inicio = date('Y-m-d H:i:s');

    echo "<pre>"; 
    echo '********************* <strong>BAIXANDO export_sga.zip</strong> **********************';
    echo "<br>";
    /*********************************************************************************************************
     * JSON PARA PROCEDURE piSincroniza							                                                 *
     *********************************************************************************************************/
    $JsonParam = '[
        {"dataType":"longchar","name":"retorno","value":"","type":"output"},
        {"dataType":"character","name":"localEntrega","value":"'.addslashes($integrationData->localEntrega).'","type":"input"}
    ]';
    /*$JsonParam = '[
        {"dataType":"longchar","name":"retorno","value":"","type":"output"}       
    ]';*/  
    
    // Executa o execBO
    $return = rodaExecBoIntegra('sga/esp/essga005b.p', 'piSincroniza', $JsonParam, $db, $integrationData);
	//print_r($return);die;
    // Valida se foi executado com sucesso, caso não, retorna a mensagem de erro
    
    if(is_array($return) && isset($return['error'])):
        die($return['error']);        
    endif;

    $result = json_decode($return->return, true);
    $exp = json_decode($result[0]['value']);
    $success = [];
    $error = [];    

    // print_r($return);die;
    echo 'Dados retornados com sucesso!';
    echo "<br><br>";
    
    
    // Descompacta o zip para o diretorio
    $file = 'S_'.date('dmY_His').'.zip';
    file_put_contents(BASE_PATH_SINCRONIZACAO.'/cargas/sincronizando/'.$file, base64_decode($exp->arquivo));
	//$file = 'export_sga_erro_ativo_brook.zip';
    if(!is_file(BASE_PATH_SINCRONIZACAO.'/cargas/sincronizando/'.$file)):
        die('Erro ao baixar ou descompactar arquivo');
    endif;                     
   
    $carga = new CargaController();
    $carga->iniciaCarga(0,$file, 2, $inicio, $db);
	//$carga->iniciaCarga(0, $file, 2, $inicio, $db);
}

class CargaController
{    
    public $programas = [
        'table'     => 'z_sga_programas',
        'fields'    => [
            '1' => 'cod_programa',
            '2' => 'descricao_programa',
            '3' => 'cod_modulo',
            '4' => 'descricao_modulo',
            '5' => 'especific',
            '6' => 'upc',
            '7' => 'ajuda_programa',
            '8' => 'codigo_rotina',
            '9' => 'descricao_rotina',
            '10' => 'registro_padrao',
            '11' => 'visualiza_menu',
            '12' => 'procedimento_pai'            
        ]
    ];
    
    public function validaIntegridadeArquivos($instancia)
    {
        $valid = true;
        $sincronizacao = new Sincronizacao();
        $inicio = date('Y-m-d H:i:s');

        // Valida integridade dos arquivos
        $arrFiles = [
            BASE_PATH_SINCRONIZACAO.'/cargas/export_sga/programas.csv',
            BASE_PATH_SINCRONIZACAO.'/cargas/export_sga/usuarios.csv',
            BASE_PATH_SINCRONIZACAO.'/cargas/export_sga/'.$instancia.'/grupo.csv',
            BASE_PATH_SINCRONIZACAO.'/cargas/export_sga/'.$instancia.'/grupos.csv',
            BASE_PATH_SINCRONIZACAO.'/cargas/export_sga/'.$instancia.'/grupo_programa.csv',
            BASE_PATH_SINCRONIZACAO.'/cargas/export_sga/'.$instancia.'/programa_empresa.csv',
            BASE_PATH_SINCRONIZACAO.'/cargas/export_sga/'.$instancia.'/usuario_empresa.csv'
        ];

        $resProgramas['totNaoCadastrados'] 			= 0;
        $resProgramas['totEliminados'] 				= 0;
        $resProgramas['totAtualizados']				= 0;
		$resProgEmpresa['totNaoCadastrados']		= 0;
		$resProgEmpresa['totEliminados']			= 0;
		$resUsuarios['totNaoCadastrados']			= 0;
        $resUsuarios['totEliminados']				= 0; 
        $resUsuarios['totAtualizados']              = 0;
		$resUsuarioEmpresa['totNaoCadastrados']		= 0;
		$resUsuarioEmpresa['totEliminados']			= 0;
		$resGrupos['totNaoCadastrados']				= 0;
        $resGrupos['totEliminados']					= 0;
        $resGrupos['totAtualizados']				= 0;
		$gruposUsuarios['totNaoCadastrados']		= 0;	
		$gruposUsuarios['totEliminados']			= 0; 
		$resGruposProgramas['totNaoCadastrados']	= 0;
        $resGruposProgramas['totEliminados']		= 0;
        $resFuncao['totNaoCadastrados']	            = 0;
		$resFuncao['totEliminados']		            = 0;
		
        foreach($arrFiles as $val):
            // Valida se existe o arquivo
            if(!is_file($val)):
				$sincronizacao->gravaHistoricoSincronizacao(
					$resProgramas,					
					$resProgEmpresa,
					$resProgEmpresa,
					$resUsuarios,					
					$resUsuarioEmpresa,					
					$resGrupos,				
					$gruposUsuarios,					
                    $resGruposProgramas,
                    $resFuncao,											
					$instancia, 
					'', 
					'', 
					'', 
					$inicio, 
					date('Y-m-d H:i:s'), 
					'Erro na integridade dos arquivos'."\n".'Arquivos não encontrados.'
				);                
                
                echo json_encode([
                    'return'    => 'error',
                    'error'     => 'Não encontrei o arquivo {$csvFile}'                   
                ]);
                die;
            endif;

            // Valida se arquivo não está vazio
            if(count(file($val, FILE_SKIP_EMPTY_LINES)) - 1 == 0):
                $sincronizacao->gravaHistoricoSincronizacao($resProgramas,					
					$resProgEmpresa,
					$resProgEmpresa,
					$resUsuarios,					
					$resUsuarioEmpresa,					
					$resGrupos,				
					$gruposUsuarios,					
					$resGruposProgramas,										
					$instancia, 
					'', 
					'', 
					'', 
					$inicio, 
					date('Y-m-d H:i:s'), 
                    'Erro na integridade dos arquivos'
                );

                echo json_encode([
                    'return'    => 'error',
                    'error'     => 'Erro na integridade dos arquivos. Favor exportar a carga novamente!',                    
                ]);
                die;
            endif;   
        endforeach;   
    }

    public function iniciaCarga($analise, $file, $instancia, $inicio, $db)
    {               
        try{        
            setlocale(LC_TIME, 'pt_BR');
            date_default_timezone_set('America/Sao_Paulo');
            
            $sincronizacao = new Sincronizacao();
            $inicio = date('Y-m-d H:i:s');                    
            $csvFile = '';       

            $zip = new ZipArchive;
			
            $zip->open(BASE_PATH_SINCRONIZACAO.'/cargas/sincronizando/'.$file);
			
            if($zip->extractTo(BASE_PATH_SINCRONIZACAO.'/cargas/') == false):
                echo json_encode([
                    'return' => 'error',
                    'error' =>'Erro ao descompactar arquivo'
                ]);
            endif;            
            $zip->close();                          
            
            $this->validaIntegridadeArquivos($instancia);

            $nameFileBkp = "S_".$instancia."_".str_replace(['-',' ',':'],['','_',''], date('Y-m-d H:i:s')).'.sql';
            
            // Se extraiu com sucesso, executa método de carga de programas
            $resProgramas = $this->programas($analise, $instancia, $nameFileBkp, $db);                                         
            if($resProgramas['return'] == false):                
                echo json_encode([
                    'return' => 'error',
                    'error'  => $resProgramas['erro']
                ]);                                                                                        
            else:
                // Se executou carga de programas com sucesso, executa método de carga de programa vs empresa                                                      
                $resProgEmpresa = $this->programasEmpresas($analise, $instancia, $nameFileBkp, $db);                                
                if($resProgEmpresa['return'] == false):                      
                    echo json_encode([
                        'return' => 'error',
                        'error' => $resProgEmpresa['error']                    
                    ]);                                                                            
                else:
                    // Se executou carga de programa vs empresa com sucesso, executa método de carga de usuários                                       
                    $resUsuarios = $this->usuarios($analise, $instancia, $nameFileBkp, $db);                                        
                    if($resUsuarios['return'] == false):                                              
                        echo json_encode([
                            'return' => 'error',
                            'error' => $resUsuarios['erro']                           
                        ]);                          
                    else:
                        // Se executou carga de usuários com sucesso, executa método de carga de usuário vs empresa                                                 
                        $resUsuarioEmpresa = $this->usuariosEmpresas($analise, $instancia, $nameFileBkp, $db);
						//print_r($resUsuarioEmpresa);die;
                        if($resUsuarioEmpresa['return'] == false):                                         
                            echo json_encode([
                                'return' => 'error',
                                'error' => $resUsuarioEmpresa['erro']
                            ]);                                                                
                        else:
                            // Se executou carga de usuário vs empresa com sucesso, executa método de carga de grupos                                                                                                     
                            $resGrupos = $this->grupos($analise, $instancia, $nameFileBkp, $db);                            
                            if($resGrupos['return'] == false):                                      
                                echo json_encode([
                                    'return' => 'error',
                                    'error' => $resGrupos['erro']
                                ]);                                                                               
                            else:
                                // Se executou carga de grupos com sucesso, executa método de carga de grupo vs usuário                                                                                                           
                                $gruposUsuarios = $this->gruposUsuarios($analise, $instancia, $nameFileBkp, $db);                                
                                if($gruposUsuarios['return'] == false):                                             
                                    echo json_encode([
                                        'return' => 'error',
                                        'error' => $gruposUsuarios['erro']
                                    ]);                                                                                   
                                else:
                                    // Se executou carga de grupo vs usuário com sucesso, executa método de carga de grupo vs programa                                                                                                                      
                                    $resGruposProgramas = $this->gruposProgramas($analise, $instancia, $nameFileBkp, $db);                                    
                                    if($resGruposProgramas['return'] == false):                                          
                                        echo json_encode([
                                            'return' => 'error',
                                            'error' => $resGruposProgramas['erro']
                                        ]);                                                                                                    
                                    endif;                                                

                                    // Remove a pasta export_sga                                      
                                    foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator(BASE_PATH_SINCRONIZACAO.'/cargas/export_sga',FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST) as $files):
                                        $files->isFile() ? unlink($files->getPathname()) : rmdir($files->getPathname());                                                
                                    endforeach;
                                    rmdir(BASE_PATH_SINCRONIZACAO.'/cargas/export_sga');                                                 
                                                    
                                    $csvStore = $resProgramas['csvStore'].
									$resProgEmpresa['csvStore'].
									$resUsuarios['csvStore'].
									$resUsuarioEmpresa['csvStore'].
									$resGrupos['csvStore'].
									$gruposUsuarios['csvStore'].
									$resGruposProgramas['csvStore'];
                                    $fileDiff = BASE_PATH_SINCRONIZACAO.'/cargas/sincronizados/'.str_replace('.zip','', $file).'_diff.csv';
                                    file_put_contents($fileDiff,"\xEF\xBB\xBF".$csvStore);
                                    
                                    move_uploaded_file(BASE_PATH_SINCRONIZACAO.'/cargas/sincronizando/'.$file, BASE_PATH_SINCRONIZACAO.'/cargas/sincronizados/'.$file);
                                    unlink(BASE_PATH_SINCRONIZACAO.'/cargas/sincronizando/'.$file);

                                    $sincronizacao->gravaHistoricoSincronizacao(
                                        $resProgramas,
                                        $resProgEmpresa,
                                        $resUsuarios,
                                        $resUsuarioEmpresa,
                                        $resGrupos,
                                        $gruposUsuarios,
                                        $resGruposProgramas,
                                        ['totNaoCadastrados' => 0, 'totEliminados' => 0],
                                        $instancia,                
                                        $file,                    
                                        $nameFileBkp,
                                        $fileDiff,
                                        $inicio,
                                        'Sincronizado'
                                    );              

                                    $db->query("UPDATE z_sga_vm_usuarios_refresh SET atualiza = 1");
                                    $db->query("CALL sp_sga_refresh_MTZappsProcesso");
                                    $db->query("CALL sp_sga_refresh_vm_usuarios_processos_riscos");

                                    echo json_encode([
                                        'return' => 'success',
                                        'msg' => 'Carga importada com sucesso'                    
                                    ]);
                                    //die;
                                endif;
                            endif;
                        endif;
                   endif;
               endif;
            endif;                                                                        
            return true;
                                     
        }catch(EXCEPTION $e){
            echo json_encode([
                'msg' => $e->getMessage()
            ]);
        }                
    }

	/**
	* Carrega tela de sincronização de programas
	*/
    public function programas($analise, $instancia, $nameFileBkp, $db)
    {        
        echo 'Passando por Programas';
		$sincronizacao = new Sincronizacao();
		
		setlocale(LC_TIME, 'pt_BR');
        date_default_timezone_set('America/Sao_Paulo');		                                 

        $csvFile = BASE_PATH_SINCRONIZACAO.'/cargas/export_sga/programas.csv';



        $file = fopen($csvFile, 'r');
        // Pula cabeçalho
        fgets($file);
        
        // Percorre as linhas e inclui na query
        $totProgJaCad = 0;
        $totProgCad = 0;
        $diff = '';
        $totalDiff = 0;
        $totalProgAtt = 0;
        $arr_query_diff_sga = [];
        $arr_update_sga = [];
        $arr_update_diff_sga = [];
        $arr_codigos_atualizaveis = [];
        $resSinc['inicio'] = date('H:i:s');    
        $resSinc['return'] = true;
        $numLine = 1;
        $htmlTable = '';
        $csvStore = "PROGRAMAS IMPORTADOS;\n";
        $csvStore .= "z_sga_programas_id;cod_programa;descricao_programa;cod_modulo;descricao_modulo;especific;upc;ajuda_programa;codigo_rotina;descricao_rotina;registro_padrao;visualiza_menu;procedimento_pai;monitorado;\n";

        $sincronizacao->backup_tables('z_sga_programas', $nameFileBkp);
        
        $diff = $db->query('SELECT * FROM z_sga_programas');

        while(!feof($file)):
            $line = str_getcsv(fgets($file),';');
                if($line[0]!=NULL):
                // Valida se programa já existe na base
                $valid = $sincronizacao->validaCadastroProgramas($line[1],$instancia);
                // Se foi encontrado na base                
                if(isset($valid["return"]) && $valid["return"]==true):
                    $numLine++;
                    $totProgJaCad++;
                    array_push($arr_query_diff_sga, $valid['z_sga_programas_id']);
                    if ($valid['descricao_programa']!=$line[2] || $valid['cod_modulo']!=$line[3] || $valid['descricao_modulo']!=$line[4]
                        || $valid['especific']!=$line[5] || $valid['upc']!=$line[6] || $valid['ajuda_programa']!=$line[7]  || $valid['codigo_rotina']!=$line[8]
                        || $valid['descricao_rotina']!=$line[9] || $valid['registro_padrao']!=$line[10] || $valid['visualiza_menu']!=$line[11] || $valid['procedimento_pai']!=$line[12]):
                        array_push($arr_codigos_atualizaveis, $valid['z_sga_programas_id']);
                        $arr_update_sga[$valid['z_sga_programas_id']] = array('cod_programa'=>$valid['cod_programa'],'descricao_programa'=>$valid['descricao_programa'],'cod_modulo'=>$valid['cod_modulo'],'descricao_modulo'=>$valid['descricao_modulo'],'especific'=>$valid['especific'],'upc'=>$valid['upc'],'ajuda_programa'=>$valid['ajuda_programa'],'codigo_rotina'=>$valid['codigo_rotina'],'descricao_rotina'=>$valid['descricao_rotina'],'registro_padrao'=>$valid['registro_padrao'],'visualiza_menu'=>$valid['visualiza_menu'],'procedimento_pai'=>$valid['procedimento_pai']);
                        $arr_update_diff_sga[$valid['z_sga_programas_id']] = array('cod_programa'=>$line[1],'descricao_programa'=>$line[2],'cod_modulo'=>$line[3],'descricao_modulo'=>$line[4],'especific'=>$line[5],'upc'=>$line[6],'ajuda_programa'=>$line[7],'codigo_rotina'=>$line[8],'descricao_rotina'=>$line[9],'registro_padrao'=>$line[10],'visualiza_menu'=>$line[11],'procedimento_pai'=>$line[12]);
                    endif; 

                    continue;
                endif;                

                // Se não foi encontrado o relacionamento na base
                $csvStore .= implode(';', $line)."\n";

				$query_values = "INSERT INTO
					z_sga_programas (														
						`cod_programa`,
						`descricao_programa`,
						`cod_modulo`,
						`descricao_modulo`,
						`especific`,
						`upc`,
						`ajuda_programa`,
						`codigo_rotina`,
						`descricao_rotina`,
						`registro_padrao`,
						`visualiza_menu`,
						`procedimento_pai`
					) VALUES (
						'".addslashes($line[1])."', 
						'".addslashes($line[2])."',
						'".addslashes($line[3])."',						    
						'".addslashes($line[4])."',
						'".addslashes($line[5])."',
						'".addslashes($line[6])."',
						'".addslashes($line[7])."',
						'".addslashes($line[8])."',
						'".addslashes($line[9])."',
						'".addslashes($line[10])."',
						'".addslashes($line[11])."',
						'".addslashes($line[12])."'
                )";
				try{
                    $db->query($query_values);
					//print_r($db);
				}catch(EXCEPTION $e){
					echo $e->getMessage();
                }
            endif;
        endwhile;

        fclose($file);        

        $csvStore .= "\n\n";
        //Realiza as Atualizações Para Ajustar a Base de Acordo com a Planilha.
        if(count($arr_codigos_atualizaveis) > 0):
            $update = $db->prepare(
            "UPDATE 
                z_sga_programas 
            SET
                descricao_programa=:descricao_programa,
                cod_modulo=:cod_modulo,
                descricao_modulo=:descricao_modulo,
                especific=:especific,
                upc=:upc,
                ajuda_programa=:ajuda_programa,
                codigo_rotina=:codigo_rotina,
                descricao_rotina=:descricao_rotina,
                registro_padrao=:registro_padrao,
                visualiza_menu=:visualiza_menu,
                procedimento_pai=:procedimento_pai
            WHERE
                z_sga_programas_id=:z_sga_programas_id");

            $csvStore .= "PROGRAMAS ATUALIZADOS NO SGA;\n";
            $csvStore .= "tipo_valores;z_sga_programas_id;cod_programa;descricao_programa;cod_modulo;descricao_modulo;especific;upc;ajuda_programa;codigo_rotina;descricao_rotina;registro_padrao;visualiza_menu;procedimento_pai;monitorado;\n";
                foreach($arr_codigos_atualizaveis as $key => $val):
                    $csvStore .= "Valores Antigos;".$val.";".$arr_update_sga[$val]['cod_programa'].";".$arr_update_sga[$val]['descricao_programa'].";".$arr_update_sga[$val]['cod_modulo'].";".$arr_update_sga[$val]['descricao_modulo'].";".$arr_update_sga[$val]['especific'].";".$arr_update_sga[$val]['upc'].";".$arr_update_sga[$val]['ajuda_programa'].";".$arr_update_sga[$val]['codigo_rotina'].";".$arr_update_sga[$val]['descricao_rotina'].";".$arr_update_sga[$val]['registro_padrao'].";".$arr_update_sga[$val]['visualiza_menu'].";".$arr_update_sga[$val]['procedimento_pai'].";;";
                    $csvStore .="\n";
                    $csvStore .= "Valores Atualizados;".$val.";".$arr_update_diff_sga[$val]['cod_programa'].";".$arr_update_diff_sga[$val]['descricao_programa'].";".$arr_update_diff_sga[$val]['cod_modulo'].";".$arr_update_diff_sga[$val]['descricao_modulo'].";".$arr_update_diff_sga[$val]['especific'].";".$arr_update_diff_sga[$val]['upc'].";".$arr_update_diff_sga[$val]['ajuda_programa'].";".$arr_update_diff_sga[$val]['codigo_rotina'].";".$arr_update_diff_sga[$val]['descricao_rotina'].";".$arr_update_diff_sga[$val]['registro_padrao'].";".$arr_update_diff_sga[$val]['visualiza_menu'].";".$arr_update_diff_sga[$val]['procedimento_pai'].";;";
                    $csvStore .="\n\n";

                    $update->execute(
                        array(
                            ':descricao_programa'=>$arr_update_diff_sga[$val]['descricao_programa'],
                            ':cod_modulo'=>$arr_update_diff_sga[$val]['cod_modulo'],
                            ':descricao_modulo'=>$arr_update_diff_sga[$val]['descricao_modulo'],
                            ':especific'=>$arr_update_diff_sga[$val]['especific'],
                            ':upc'=>$arr_update_diff_sga[$val]['upc'],
                            ':ajuda_programa'=>$arr_update_diff_sga[$val]['ajuda_programa'],
                            ':codigo_rotina'=>$arr_update_diff_sga[$val]['codigo_rotina'],
                            ':descricao_rotina'=>$arr_update_diff_sga[$val]['descricao_rotina'],
                            ':registro_padrao'=>$arr_update_diff_sga[$val]['registro_padrao'],
                            ':visualiza_menu'=>$arr_update_diff_sga[$val]['visualiza_menu'],
                            ':procedimento_pai'=>$arr_update_diff_sga[$val]['procedimento_pai'],
                            ':z_sga_programas_id'=>$val
                        ));
                        $totalProgAtt++;

                endforeach;
            endif;

        // Elimina registros a mais no SGA e coloca os dados no csv
        if(count($arr_query_diff_sga) > 0):
            if($diff->rowCount() > 0):
                $diff = $diff->fetchAll(PDO::FETCH_ASSOC);
                $csvStore .= "PROGRAMAS ELIMINADOS DO SGA;\n";
                $csvStore .= "z_sga_programas_id;cod_programa;descricao_programa;cod_modulo;descricao_modulo;especific;upc;ajuda_programa;codigo_rotina;descricao_rotina;registro_padrao;visualiza_menu;procedimento_pai;monitorado;\n";

                foreach($diff as $key => $val):
                    if(in_array($val['z_sga_programas_id'], $arr_query_diff_sga)):
                        unset($diff[$key]);
                    else:
						$db->query('DELETE FROM z_sga_programas WHERE z_sga_programas_id = ' . $val['z_sga_programas_id']);
						if(isset($val['cod_programa'])):
							$csvStore .= $val['z_sga_programas_id'].";".$val['cod_programa'].";".$val['descricao_programa'].";".$val['cod_modulo'].";".$val['descricao_modulo'].";".$val['especific'].";".$val['upc'].";".$val['ajuda_programa'].";".$val['codigo_rotina'].";".$val['descricao_rotina'].";".$val['registro_padrao'].";".$val['visualiza_menu'].";".$val['procedimento_pai'].";".$val['monitorado'].";";
							$csvStore .= "\n";
						endif;
                        
                        $totalDiff += 1;
                    endif;
                endforeach;   
                
                $csvStore .= "\n\n";
			endif;
        endif;

        $resSinc['fim']    				= date('H:i:s');
        $resSinc['totCadastrados'] 		= $totProgJaCad;
        $resSinc['totNaoCadastrados']	= $totProgCad;		
        $resSinc['htmlTable'] 			= $htmlTable;
        $resSinc['csvStore'] 			= $csvStore;
        $resSinc['totEliminados'] 	    = $totalDiff;
        $resSinc['totAtualizados']       = $totalProgAtt;
		

        return $resSinc;
    }   		
    
	/**
     * Carrega tela de sincronização de grupos x programas
     */
    public function programasEmpresas($analise, $instancia, $nameFileBkp, $db)
    {       
        echo 'Passando por Programas Empresa';
		$sincronizacao = new Sincronizacao();
		
		setlocale(LC_TIME, 'pt_BR');
        date_default_timezone_set('America/Sao_Paulo');		                    

        $csvFile = BASE_PATH_SINCRONIZACAO.'/cargas/export_sga/'.$instancia.'/programa_empresa.csv';
        $file = fopen($csvFile, 'r');
        // Pula cabeçalho
        fgets($file);
        
        // Percorre as linhas e inclui na query
        $totProgJaCad = 0;
        $totProgCad = 0;
        $diff = '';
        $totalDiff = 0;
		$arr_query_diff_sga = [];
        $resSinc['inicio'] = date('H:i:s');        
        $resSinc['return'] = true;
        $numLine = 0;
        $htmlTable = '';
        $csvStore = 'PROGRAMA x EMPRESA IMPORTADOS;'."\n";
        $csvStore .= 'idGrupoPrograma;codPrograma;idEmpresa;'."\n";
        
        $sincronizacao->backup_tables('z_sga_programa_empresa', $nameFileBkp);

        $diff =  $db->query('SELECT * FROM z_sga_programa_empresa WHERE idEmpresa = '.$instancia);

        while(!feof($file)):
            $line = str_getcsv(fgets($file),';');
            if($line[0]!=NULL):
                // Valida se instancia da carga é igual a instancia da sessão do sistema
                if($line[2] != $instancia):                    
                    return [
                        'return' => false,
                        'error' => 'Instância da carga difere da instância, selecionada no sistema!'
                    ];
                    break;
                endif;

                // Valida se não é cabeçalho
                if(isset($line[0]) && $line[0] != 'idGrupoPrograma'):
                    // Valida se relação já existe na base           
                    $valid = $sincronizacao->validaCadastroProgramasEmpresas($line[1], $instancia, $analise);
					
                    if(isset($valid['return']) && $valid['return'] === 'error'):
                        return [
                            'return' => false,
                            'error'  => $valid['error']                          
                        ];
                        break;
                    // Se foi encontrado na base
                    elseif(isset($valid['return']) && $valid["return"]):                        
                        $totProgJaCad++;
                        array_push($arr_query_diff_sga, $valid['idGrupoPrograma']);
                        continue;
                    endif;

                    // Se não foi encontrado o relacionamento na base
					$csvStore .= implode(';', $line)."\n";
					$query_values = "INSERT INTO
						z_sga_programa_empresa (
							`idPrograma`,
							`idEmpresa`
						) VALUES(
						'".$valid['idPrograma']."',
						'".$instancia."')";
					$db->query($query_values);

                    // Se não foi encontrado na base
                    if($line[0] != 'idGrupoPrograma'):
                        $totProgCad++;
                    endif;
                endif;
            endif;
        endwhile;
      
        fclose($file);

        $csvStore .= "\n\n";
		
		// Elimina registros a mais no SGA e coloca os dados no csv
        if(count($arr_query_diff_sga) > 0):
            if($diff->rowCount() > 0):
                $diff = $diff->fetchAll(PDO::FETCH_ASSOC);
                $csvStore .= 'PROGRAMA x EMPRESA ELIMINADOS;'."\n";
                $csvStore .= 'idGrupoPrograma;codPrograma;idEmpresa;'."\n";

                foreach($diff as $key => $val):
                    if(in_array($val['idGrupoPrograma'], $arr_query_diff_sga)):
                        unset($diff[$key]);
                    else:                 
						$db->query('DELETE FROM z_sga_programa_empresa WHERE idGrupoPrograma = ' . $val['idGrupoPrograma']);
						if(isset($val['cod_programa'])):
							$csvStore .= $val['idGrupoPrograma'].";".$val['cod_programa'].";".$val['idEmpresa'].";";
							$csvStore .= "\n";
						endif;
                        
                        $totalDiff += 1;
                    endif;
                endforeach;
                $csvStore .= "\n\n";
			endif;
        endif;

        $resSinc['fim']                 = date('H:i:s');
        $resSinc['totCadastrados']      = $totProgJaCad;
        $resSinc['totNaoCadastrados']   = $totProgCad;
        $resSinc['htmlTable']           = $htmlTable;
        $resSinc['csvStore']            = $csvStore;
		$resSinc['totEliminados']       = $totalDiff;
		
        return $resSinc;
    }	
	
	/**
     * Carrega tela de sincronização de grupos x programas
     */
    public function gruposProgramas($analise, $instancia, $nameFileBkp, $db)
    {    
        echo 'Passando por gruposProgramas';
		$sincronizacao = new Sincronizacao();
		
		setlocale(LC_TIME, 'pt_BR');
        date_default_timezone_set('America/Sao_Paulo');		                          
        
        $csvFile = BASE_PATH_SINCRONIZACAO.'/cargas/export_sga/'.$instancia.'/grupo_programa.csv';
        $file = fopen($csvFile, 'r');
        // Pula cabeçalho
        fgets($file);
        
        // Percorre as linhas e inclui na query
        $totProgJaCad = 0;
        $totProgCad = 0;
        $diff = '';
        $totalDiff = 0;
        $arr_query_diff_sga = [];
        $resSinc['inicio'] = date('H:i:s');
        $resSinc['return'] = true;
        $numLine = 0;
        $i = 0;
        $htmlTable = '';
        $csvStore = "GRUPO X PROGRAMA IMPORTADOS;\n";
        $csvStore .= "z_sga_grupo_programa_id;cod_grupo;nome_grupo;gestor;cod_programa;idGrupo;idPrograma;\n";
        
        $sincronizacao->backup_tables('z_sga_grupo_programa', $nameFileBkp);
        
        /***** Recupera os dados da base, percorre e valida se existe no array com os dados do csv. Se existir remove do array */
        $diff = $db->query("SELECT * FROM z_sga_grupo_programa WHERE idEmpresa = " . $instancia);

        while(!feof($file)):
            $line = str_getcsv(fgets($file),';');
            if($line[0]!=NULL):
                //echo 'Grupo: ' .$line[1] . ' x Programa: ' . $line[4]."<br>"; 
                // Valida se programa já existe na base
                $valid = $sincronizacao->validaCadastroGruposProgramas($line[1], $line[4], $analise, $instancia);
                
                // Se houver erro
                if(isset($valid['return']) && $valid['return'] === 'error'):                                        
                    return [
                        'return' => 'error',
                        'error'  => $valid['error']                        
                    ];
                    break;
				endif;
				
                // Se foi encontrado na base
                if(isset($valid['return']) && $valid["return"]):                    
                    $totProgJaCad++;
                    array_push($arr_query_diff_sga, $valid['z_sga_grupo_programa_id']);
                    continue;
                endif;

                // Se não foi encontrado o relacionamento na base
				$csvStore .= implode(';', $line)."\n";
				$query_values = "INSERT INTO
					z_sga_grupo_programa (							
						`cod_grupo`,
						`nome_grupo`,
						`gestor`,
						`cod_programa`,
						`idGrupo`,
						`idPrograma`,
						`idEmpresa`
					) VALUES(
						'".addslashes($valid['codGrupo'])."', 
						'".addslashes($valid['nomeGrupo'])."',
						'".addslashes($valid['gestor'])."',						    
						'".addslashes($valid['codPrograma'])."',
						'".addslashes($valid['idGrupo'])."',
						'".addslashes($valid['idPrograma'])."',
						".$instancia."
                    )";
                    

                    $db->query($query_values);
               

                // Se não foi encontrado relacionamento na base
                if($line[0] != 'Programa'):
                    $totProgCad++;
                endif;
            endif;
            $numLine++;
        endwhile;

        fclose($file);

        $csvStore .= "\n\n";
		
		// Cria a query para comparação de registros a mais no SGA
        if(count($arr_query_diff_sga) > 0):
            if($diff->rowCount() > 0):
                $diff = $diff->fetchAll(PDO::FETCH_ASSOC);
                $csvStore .= "GRUPO X PROGRAMA ELIMINADOS DO SGA;\n";
                $csvStore .= "z_sga_grupo_programa_id;cod_grupo;nome_grupo;gestor;cod_programa;idGrupo;idPrograma;\n";
                foreach($diff as $key => $val):
                    if(in_array($val['z_sga_grupo_programa_id'], $arr_query_diff_sga)):
                        unset($diff[$key]);
                    else:
						$db->query('DELETE FROM z_sga_grupo_programa WHERE z_sga_grupo_programa_id = ' . $val['z_sga_grupo_programa_id']);
						if(isset($val['cod_grupo'])):
							$csvStore .= $val['z_sga_grupo_programa_id'].";".$val['cod_grupo'].";".$val['nome_grupo'].";".$val['gestor'].";".$val['cod_programa'].";".$val['idGrupo'].";".$val['idPrograma'].";";
							$csvStore .= "\n";
						endif;
                        
                        $totalDiff += 1;
                    endif;
                endforeach; 

                $csvStore .= "\n\n";
			endif;
        endif;
        
        $resSinc['fim']                 = date('H:i:s');
        $resSinc['totCadastrados']      = $totProgJaCad;
        $resSinc['totNaoCadastrados']   = $totProgCad;        
        $resSinc['htmlTable']           = $htmlTable;    
        $resSinc['csvStore']            = $csvStore; 
		$resSinc['totEliminados'] 	    = $totalDiff;

        return $resSinc;
    }

	/**
     * Carrega tela de sincronização de grupos x usuários
     */
    public function gruposUsuarios($analise, $instancia, $nameFileBkp, $db)
    {        
        echo 'Passando por gruposUsuarios';
		$sincronizacao = new Sincronizacao();
		
		setlocale(LC_TIME, 'pt_BR');
        date_default_timezone_set('America/Sao_Paulo');		                    

        $csvFile = BASE_PATH_SINCRONIZACAO.'/cargas/export_sga/'.$instancia.'/grupos.csv';
        $file = fopen($csvFile, 'r');
        // Pula cabeçalho
        fgets($file);
        
        // Percorre as linhas e inclui na query
        $totProgJaCad = 0;
        $totProgCad = 0;
		$diff = '';
        $totalDiff = 0;
        $arr_query_diff_sga = [];
        $resSinc['inicio'] = date('H:i:s');          
        $resSinc['return'] = true; 
        $numLine = 0;
        $htmlTable = '';
        $csvStore = "GRUPO X USUÁRIO IMPORTADOS;\n";
        $csvStore .= "z_sga_grupos_id;cod_grupo;desc_grupo;gestor;cod_usuario;idGrupo;idUsuario;\n";
        
        $sincronizacao->backup_tables('z_sga_grupos', $nameFileBkp);

        $diff = $db->query('SELECT * FROM z_sga_grupos gs LEFT JOIN z_sga_grupo g ON g.idGrupo = gs.idGrupo WHERE gs.idEmpresa = '.$instancia);

        while(!feof($file)):
            $line = str_getcsv(fgets($file),';');
            if($line[0]!=NULL):
                // Valida se programa já existe na base                
                $valid = $sincronizacao->validaCadastroGruposUsuarios($line[1], $line[4], $analise, $instancia);

                // Se não foi encontrado algum grupo ou usuário
                if(isset($valid['return']) && $valid['return'] === 'error'):
                    return [
                        'return' => 'error',
                        'error'  => $valid['error']
                    ];
                    break;
				endif;
				
                // Se foi encontrado relacionamento na base                
				if(isset($valid['return']) && $valid["return"]):                    
                    $totProgJaCad++;
                    array_push($arr_query_diff_sga, $valid['z_sga_grupos_id']);
                    continue;
                endif;

                // Se não foi encontrado o relacionamento na base
				$csvStore .= implode(';', $line)."\n";
				$query_values = "INSERT INTO
					z_sga_grupos (`cod_grupo`,`desc_grupo`,`gestor`,`cod_usuario`,`idGrupo`,`idUsuario`,`idEmpresa`) 
					VALUES (
						'".addslashes($valid['codGrupo'])."', 
						'".addslashes($valid['nomeGrupo'])."', 
						'".addslashes($valid['gestor'])."', 
						'".addslashes($valid['codUsuario'])."', 
						'".addslashes($valid['idGrupo'])."', 
						'".addslashes($valid['idUsuario'])."', 
						".$instancia.")";

					$db->query($query_values);

                // Se não foi encontrado na base
                
                if($line[0] != 'Programa'):
                    $totProgCad++;
                endif;
            endif;
        endwhile;

        fclose($file);

        $csvStore .= "\n\n";

		// Elimina registros a mais do SGA e coloca no csv.
        if(count($arr_query_diff_sga) > 0):
            if($diff->rowCount() > 0):
                $diff = $diff->fetchAll(PDO::FETCH_ASSOC);
                $csvStore .= "GRUPO X USUÁRIO ELIMINADOS DO SGA;\n";
                $csvStore .= "z_sga_grupos_id;cod_grupo;desc_grupo;gestor;cod_usuario;idGrupo;idUsuario;\n";

                foreach($diff as $key => $val):
                    if(in_array($val['z_sga_grupos_id'], $arr_query_diff_sga)):
                        unset($diff[$key]);
                    else:
						$db->query('DELETE FROM z_sga_grupos WHERE z_sga_grupos_id = ' . $val['z_sga_grupos_id']);
						if(isset($val['cod_grupo'])):
							$csvStore .= $val['z_sga_grupos_id'].';'.$val['cod_grupo'].';'.$val['desc_grupo'].';'.$val['cod_usuario'].';'.$val['idGrupo'].';'.$val['idUsuario'].';';
							$csvStore .= "\n";
						endif;
                        
                        $totalDiff  += 1;
                    endif;
                endforeach;

                $csvStore .= "\n\n";
			endif;
        endif;
        
        $resSinc['fim']                 = date('H:i:s');
        $resSinc['totCadastrados']      = $totProgJaCad;
        $resSinc['totNaoCadastrados']   = $totProgCad;
        $resSinc['htmlTable']           = $htmlTable;
        $resSinc['csvStore']            = $csvStore; 
        $resSinc['totEliminados'] 	    = $totalDiff;
        
        return $resSinc;
    }

	/**
	* Carrega tela de sincronização de grupos
	*/
    public function grupos($analise, $instancia, $nameFileBkp, $db)
    {    
        echo 'Passando por grupos';
		$sincronizacao = new Sincronizacao();
		
		setlocale(LC_TIME, 'pt_BR');
        date_default_timezone_set('America/Sao_Paulo');		                        
                
        $csvFile = BASE_PATH_SINCRONIZACAO.'/cargas/export_sga/'.$instancia.'/grupo.csv';
        $file = fopen($csvFile, 'r');
        // Pula cabeçalho
        fgets($file);
        
        // Percorre as linhas e inclui na query
        $totProgJaCad = 0;
        $totProgCad = 0;
		$diff= '';
        $totalDiff = 0;
        $totalGrupoAtt = 0;
        $arr_query_diff_sga = [];
        $arr_update_sga = [];
        $arr_update_diff_sga = [];
        $arr_codigos_atualizaveis = [];
        $resSinc['inicio'] = date('H:i:s');
        $resSinc['return'] = true;
        $numLine = 1;
        $htmlTable = '';
        $csvStore = "GRUPOS IMPORTADOS;\n";
        $csvStore .= "idGrupo;idLegGrupo;descAbrev;descricao;idEmpresa;\n";

        $sincronizacao->backup_tables('z_sga_grupo', $nameFileBkp);

        $diff = $db->query('SELECT * FROM z_sga_grupo WHERE idEmpresa = '.$instancia);

        while(!feof($file)):
            $line = str_getcsv(fgets($file),';');
            if($line[0]!=NULL):
                // Valida se instancia da carga é igual a instancia da sessão do sistema
                if($line[4] != $instancia):                    
                    return [
                        'return' => 'error',
                        'error' => 'Instância da carga difere da instância, selecionada no sistema!'
                    ];
                    break;
                endif;
                
                // Valida se grupo já existe na base                
                $valid = $sincronizacao->validaCadastroGrupos($line[1],$instancia);

                // Se foi encontrado na base
                if(isset($valid['return']) && $valid['return']):                    
                    $totProgJaCad++;
                    array_push($arr_query_diff_sga, $valid['idGrupo']);
                    if($valid['descAbrev']!=$line[2]):
                        array_push($arr_codigos_atualizaveis,$valid['idGrupo']);
                        $arr_update_sga[$valid['idGrupo']] = array('idLegGrupo'=>$valid['idLegGrupo'],'descAbrev'=>$valid['descAbrev']);
                        $arr_update_diff_sga[$valid['idGrupo']] = array('idLegGrupo'=>$line[1],'descAbrev'=>$line[2]);                        ;
                    endif;                    
                    continue;
                endif;

                // Se não foi encontrado na base
				$csvStore .= implode(';', $line)."\n";
				$query_values = "INSERT INTO
					z_sga_grupo (														
						`idLegGrupo`,
						`descAbrev`,							
						`idEmpresa`
					) VALUES (
					'".addslashes($line[1])."', 
					'".addslashes($line[2])."',                        
					'".addslashes($instancia)."')";

				$db->query($query_values);

                // Se não foi encontrado na base                
                if($line[0] != 'idGrupo'):
                    $totProgCad++;
                endif;
            endif;
        endwhile;

        fclose($file);
		
        $csvStore .= "\n\n";
        
        if(count($arr_codigos_atualizaveis) > 0):
            $update=$db->prepare(
                "UPDATE
                    z_sga_grupo
                SET 
                    descAbrev=:descAbrev
                WHERE
                    idGrupo=:idGrupo");

            $csvStore.="GRUPOS ATUALIZADOS NO SGA;\n";
            $csvStore.="tipo_valores;idGrupo;idLegGrupo;descAbrev;\n";
            foreach($arr_codigos_atualizaveis as $key => $val):
                $csvStore.="Valores Antigos;".$val.";".$arr_update_sga[$val]['idLegGrupo'].";".$arr_update_sga[$val]['descAbrev'].";";
                $csvStore.="\n";
                $csvStore.="Valores Atualizados;".$val.";".$arr_update_diff_sga[$val]['idLegGrupo'].";".$arr_update_diff_sga[$val]['descAbrev'].";";
                $csvStore.="\n\n";

                $update->execute(
                    array(
                        ':idGrupo' => $val,
                        ':descAbrev' => $arr_update_diff_sga[$val]['descAbrev']
                    )
                );
                $totalGrupoAtt++;
            endforeach;
        endif;


        // Elimina registros a mais do SGA e coloca no csv.
        if(count($arr_query_diff_sga) > 0):
            if($diff->rowCount() > 0):
                $diff = $diff->fetchAll(PDO::FETCH_ASSOC);
                $csvStore .= "GRUPOS ELIMINADOS DO SGA;\n";
                $csvStore .= "idGrupo;idLegGrupo;descAbrev;descricao;idEmpresa;\n";	

                foreach($diff as $key => $val):
                    if(in_array($val['idGrupo'], $arr_query_diff_sga)):
                        unset($diff[$key]);
                    else:     
						$db->query('DELETE FROM z_sga_grupo WHERE idGrupo = ' . $val['idGrupo']);
						if(isset($val['idLegGrupo'])):
							$csvStore .= $val['idGrupo'].';'.$val['idLegGrupo'].';'.$val['descAbrev'].';'.$val['descricao'].';'.$val['idEmpresa'].';';
							$csvStore .= "\n";
						endif;
                       
                        $totalDiff  += 1;
                    endif;
                endforeach;

                $csvStore .= "\n\n";
			endif;
        endif;
                     
        $resSinc['fim']                 = date('H:i:s');
        $resSinc['totCadastrados']      = $totProgJaCad;
        $resSinc['totNaoCadastrados']   = $totProgCad;
        $resSinc['htmlTable']           = $htmlTable;
        $resSinc['csvStore']            = $csvStore;
        $resSinc['totEliminados'] 	    = $totalDiff;
        $resSinc['totAtualizados']      = $totalGrupoAtt;

        return $resSinc;
    }

	/**
	* Carrega tela de sincronização de usuários
	*/
    public function usuarios($analise, $instancia, $nameFileBkp, $db)
    {        
        echo 'Passando por Usuarios';
		$sincronizacao = new Sincronizacao();
		
		setlocale(LC_TIME, 'pt_BR');
        date_default_timezone_set('America/Sao_Paulo');		                       

        $csvFile = BASE_PATH_SINCRONIZACAO.'/cargas/export_sga/usuarios.csv';                
        $file = fopen($csvFile, 'r');
        // Pula cabeçalho
        fgets($file);
        
        // Percorre as linhas e inclui na query
        $totProgJaCad = 0;
        $totProgCad = 0;
		$diff = '';
        $totalDiff = 0;
        $totalUsuariosAtt = 0;
        $arr_query_diff_sga = [];
        $arr_update_sga = [];
        $arr_update_diff_sga = [];
        $arr_codigos_atualizaveis = [];
        $resSinc['inicio'] = date('H:i:s');
        $resSinc['return'] = true;
        $numLine = 1;
        $htmlTable = '';
        $csvStore = "USUÁRIOS IMPORTADOS;\n";
        $csvStore .= "z_sga_usuarios_id;cod_usuario;nome_usuario;CPF;cod_gestor;cod_funcao;funcao;email;solicitante;gestor_usuario;gestor_grupo;gestor_programa;si;idUsrFluig;ativo;idDepartamento;\n";

        $sincronizacao->backup_tables('z_sga_usuarios', $nameFileBkp);

        $diff = $db->query('SELECT * FROM z_sga_usuarios');

        while(!feof($file)):
            $line = str_getcsv(fgets($file),';');
            if($line[0]!=NULL):
                // Valida se grupo já existe na base
                $valid = $sincronizacao->validaCadastroUsuarios($line[1],$instancia);

                // Se foi encontrado relacionamento na base
                if(isset($valid['return']) && $valid['return']):                    
                    $totProgJaCad++;
                    array_push($arr_query_diff_sga, $valid['z_sga_usuarios_id']);
                    if($valid['nome_usuario']!=$line[2] || $valid['cpf']!=$line[3] || $valid['email']!=$line[7] || $valid['ativo']!=$line[14]):
                        array_push($arr_codigos_atualizaveis,$valid['z_sga_usuarios_id']);
                        $arr_update_sga[$valid['z_sga_usuarios_id']] = array('cod_usuario'=>$valid['cod_usuario'],'nome_usuario'=>$valid['nome_usuario'],'cpf'=>$valid['cpf'],'email'=>$valid['email'],'ativo'=>$valid['ativo']);
                        $arr_update_diff_sga[$valid['z_sga_usuarios_id']] = array('cod_usuario'=>$line[1],'nome_usuario'=>$line[2],'cpf'=>$line[3],'email'=>$line[7],'ativo'=>$line[14]);
                    endif;
                    
                    continue;
                endif;

                // Se não foi encontrado o relacionamento na base
				$csvStore .= implode(';', $line)."\n";
				$query_values = "
					INSERT INTO
						z_sga_usuarios (														
							`cod_usuario`,
							`nome_usuario`,
							`CPF`,
							`cod_gestor`,
							`cod_funcao`,
							`funcao`,
							`email`,
							`solicitante`,
							`gestor_usuario`,
							`gestor_grupo`,
							`gestor_programa`,
							`si`,
							`idUsrFluig`,
							`ativo`							
						) VALUES(
							'".addslashes($line[1])."', 
							'".addslashes($line[2])."',
							'".addslashes($line[3])."',
							'".addslashes($line[4])."',	
							'".addslashes($line[5])."',	
							'".addslashes($line[6])."',	
							'".addslashes($line[7])."',	
							'".addslashes($line[8])."',	
							'".addslashes($line[9])."',	
							'".addslashes($line[10])."',	
							'".addslashes($line[11])."',							    
							'".addslashes($line[12])."',
							'".addslashes($line[13])."',                        
							'".addslashes($line[14])."'
						)";

					$db->query($query_values);
                
                // Se não foi encontrado na base
                if($line[0] != 'cod_usuario'):
                    $totProgCad++;
                endif;                     
            endif;	
        endwhile;
        
        fclose($file);
        
        $csvStore .= "\n\n";

        if(count($arr_codigos_atualizaveis) > 0):
            $update=$db->prepare(
                "UPDATE
                    z_sga_usuarios
                SET
                    nome_usuario=:nome_usuario,
                    cpf=:cpf,
                    email=:email,
                    ativo=:ativo
                WHERE
                    z_sga_usuarios_id=:z_sga_usuarios_id");
            $csvStore.="USUARIOS ATUALIZADOS PELO SGA;\n";
            $csvStore.="tipo_valores;z_sga_usuarios_id;cod_usuario;nome_usuario;CPF;email;ativo;\n";
            foreach($arr_codigos_atualizaveis as $key => $val):
                $csvStore.="Valores Antigos;".$val.";".$arr_update_sga[$val]['cod_usuario'].";".$arr_update_sga[$val]['nome_usuario'].";".$arr_update_sga[$val]['cpf'].";".$arr_update_sga[$val]['email'].";".$arr_update_sga[$val]['ativo'].";";
                $csvStore.="\n";
                $csvStore.="Valores Atualizados;".$val.";".$arr_update_diff_sga[$val]['cod_usuario'].";".$arr_update_diff_sga[$val]['nome_usuario'].";".$arr_update_diff_sga[$val]['cpf'].";".$arr_update_diff_sga[$val]['email'].";".$arr_update_diff_sga[$val]['ativo'].";";
                $csvStore.="\n\n";
                $update->execute(
                    array(
                        ':nome_usuario'=>$arr_update_diff_sga[$val]['nome_usuario'],
                        ':cpf'=>$arr_update_diff_sga[$val]['cpf'],
                        ':email'=>$arr_update_diff_sga[$val]['email'],
                        ':ativo'=>$arr_update_diff_sga[$val]['ativo'],
                        ':z_sga_usuarios_id'=>$val
                ));
                $totalUsuariosAtt++;
            endforeach;
        endif;
        // Elimina registros a mais do SGA e coloca no csv.
        if(count($arr_query_diff_sga) > 0):
            if($diff->rowCount() > 0):
                $diff = $diff->fetchAll(PDO::FETCH_ASSOC);
                $csvStore .= "USUÁRIOS ELIMINADOS DO SGA;\n";
                $csvStore .= "z_sga_usuarios_id;cod_usuario;nome_usuario;CPF;cod_gestor;cod_funcao;funcao;email;solicitante;gestor_usuario;gestor_grupo;gestor_programa;si;idUsrFluig;ativo;idDepartamento;\n";			

                foreach($diff as $key => $val):
                    if(in_array($val['z_sga_usuarios_id'], $arr_query_diff_sga)):
                        unset($diff[$key]);
                    else:
						$db->query('DELETE FROM z_sga_usuarios WHERE z_sga_usuarios_id = ' . $val['z_sga_usuarios_id']);
						if(isset($val['cod_usuario'])):
							$csvStore .= $val['z_sga_usuarios_id'].';'.$val['cod_usuario'].';'.$val['nome_usuario'].';'.$val['CPF'].';'.$val['cod_gestor'].';';
							$csvStore .= $val['cod_funcao'].';'.$val['funcao'].';'.$val['email'].';'.$val['solicitante'].';'.$val['gestor_usuario'].';';
							$csvStore .= $val['gestor_grupo'].';'.$val['gestor_programa'].';'.$val['si'].';'.$val['idUsrFluig'].';'.$val['ativo'].';'.$val['idDepartamento'].';';
							$csvStore .= "\n";
                        endif;
                        $totalDiff  += 1;
                    endif;
                endforeach;

                $csvStore .= "\n\n";
			endif;
        endif;   
        
        $resSinc['fim']                 = date('H:i:s');
        $resSinc['totCadastrados']      = $totProgJaCad;
        $resSinc['totNaoCadastrados']   = $totProgCad;
        $resSinc['htmlTable']           = $htmlTable;    
        $resSinc['csvStore']            = $csvStore;
        $resSinc['totEliminados'] 	    = $totalDiff;
        $resSinc['totAtualizados']      = $totalUsuariosAtt;
        
        return $resSinc;
    }

	/**
     * Carrega tela de sincronização de grupos x programas
     */
    public function usuariosEmpresas($analise, $instancia, $nameFileBkp, $db)
    {       
        echo 'Passando por usuariosEmpresas';
        $dados = array();
		$sincronizacao = new Sincronizacao();
		
		setlocale(LC_TIME, 'pt_BR');
        date_default_timezone_set('America/Sao_Paulo');
		                  
        //$csvFile = 'ser004b.csv';
        $csvFile = BASE_PATH_SINCRONIZACAO.'/cargas/export_sga/'.$instancia.'/usuario_empresa.csv';        
        $file = fopen($csvFile, 'r');
        // Pula cabeçalho
        fgets($file);
        
        // Percorre as linhas e inclui na query
        $totProgJaCad = 0;
        $totProgCad = 0;
		$diff = '';
        $totalDiff = 0;
        $arr_query_diff_sga = [];
        //$arr_cod_usuario = [];
        $resSinc['inicio'] = date('H:i:s');
        $resSinc['return'] = true;
        $numLine = 0;
        $htmlTable = '';
        $csvStore = "USUÁRIO x EMPRESA IMPORTADOS;\n";
        $csvStore .= "idUsrEMp;cod_usuario;idEmpresa;idGestor;\n";
        
        $sincronizacao->backup_tables('z_sga_usuario_empresa', $nameFileBkp);

        $diff = $db->query('SELECT * FROM z_sga_usuario_empresa WHERE idEmpresa = ' . $instancia);

        while(!feof($file)):
            $line = str_getcsv(fgets($file),';');
            if($line[0]!=NULL):
                    // Valida se instancia da carga é igual a instancia da sessão do sistema
                    if($line[2] != $instancia):                        
                        return [
                            'return' => 'error',
                            'error' => 'Instância da carga difere da instância, selecionada no sistema!'
                        ];
                        break;
                    endif;

                    // Valida se relação já existe na base                                                
                    $valid = $sincronizacao->validaCadastroUsuariosEmpresas($line[1], $instancia, $analise);

                    if(isset($valid['return']) && $valid['return'] === 'error'):                                                
                        return [
                            'return' => 'error',
                            'error' => $valid['error']                        
                        ];
                        break;
                    // Se foi encontrado relacionamento na base
                    elseif(isset($valid['return']) && $valid['return']):                        
                        $totProgJaCad++;
                        array_push($arr_query_diff_sga, $valid['idUsrEMp']);
                        //array_push($arr_cod_usuario, [$valid['idUsrEMp'] => $line[1]]);
                        continue;
                    endif;

                    // Se não foi encontrado o relacionamento na base
					$csvStore .= implode(';', $line)."\n";
					$query_values = "INSERT INTO
						z_sga_usuario_empresa (							
							`idUsuario`,
							`idEmpresa`,
							`ativo`				
						) VALUES (
						'".$valid['idUsuario']."',
						'".$instancia."',                                                 
						'".$valid['ativo']."')";

					$db->query($query_values);                    

                    // Se não foi encontrado na base                    
                    if($line[0] != 'idUsrEMp'):
                        $totProgCad++;
                    endif;
                endif;
        endwhile;

        fclose($file);
		
		$csvStore .= "\n\n";

        // Elimina registros a mais do SGA e coloca no csv.
        if(count($arr_query_diff_sga) > 0):
            if($diff->rowCount() > 0):
                $diff = $diff->fetchAll(PDO::FETCH_ASSOC);
                $csvStore .= "USUÁRIO x EMPRESA ELIMINADOS DO SGA;\n";
                $csvStore .= "idUsrEMp;cod_usuario;idEmpresa;idGestor;ativo;\n";

                foreach($diff as $key => $val):
                    if(in_array($val['idUsrEMp'], $arr_query_diff_sga)):
                        unset($diff[$key]);
                    else:
						$db->query('DELETE FROM z_sga_usuario_empresa WHERE idUsrEMp = ' . $val['idUsrEMp']);
						if(isset($val['cod_usuario'])):
							$csvStore .= $val['idUsrEMp'].';'.$val['cod_usuario'].';'.$instancia.';'.$val['idGestor'].';'.$val['ativo'].';';
							$csvStore .= "\n";
						endif;
                       
                        $totalDiff  += 1;
                    endif;
                endforeach;

                $csvStore .= "\n\n";
			endif;
        endif;
        
        $resSinc['fim']                 = date('H:i:s');
        $resSinc['totCadastrados']      = $totProgJaCad;
        $resSinc['totNaoCadastrados']   = $totProgCad;
        $resSinc['htmlTable']           = $htmlTable;
        $resSinc['csvStore']            = $csvStore; 		
        $resSinc['totEliminados'] 	    = $totalDiff;
		//print_r($resSinc);
        return $resSinc;
    }   
}