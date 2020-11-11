<?php
header('Content-type: text/html; charset=utf-8'); 
/**
 * Created by Rodrigo Gomes do Nascimento.
 * User: a2
 * Date: 04/01/2019
 * Time: 12:07
 */

class CargaController extends Controller
{
    /**
     * Carrega view de sincronizacao de programas
     * Caso houver a variável $_FILES. Valida a planilha.
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
        $sincronizacao = new Sincronizacao();
        $dados['logs'] = $sincronizacao->carregaLogs();		
        $this->loadTemplate('carga', $dados);
    }

    public function validaIntegridadeArquivos()
    {
        $valid = true;
        $sincronizacao = new Sincronizacao();
        $inicio = date('Y-m-d H:i:s');
        
        // Valida integridade dos arquivos
        $arrFiles = [
            BASE_PATH.'/cargas/export_sga/programas.csv',
            BASE_PATH.'/cargas/export_sga/usuarios.csv',
            BASE_PATH.'/cargas/export_sga/'.$_SESSION['empresaid'].'/grupo.csv',
            BASE_PATH.'/cargas/export_sga/'.$_SESSION['empresaid'].'/grupos.csv',
            BASE_PATH.'/cargas/export_sga/'.$_SESSION['empresaid'].'/grupo_programa.csv',
            BASE_PATH.'/cargas/export_sga/'.$_SESSION['empresaid'].'/programa_empresa.csv',
            BASE_PATH.'/cargas/export_sga/'.$_SESSION['empresaid'].'/usuario_empresa.csv'
        ];
        
        $resProgramas['totNaoCadastrados'] 			= 0;
		$resProgramas['totEliminados'] 				= 0;
		$resProgEmpresa['totNaoCadastrados']		= 0;
		$resProgEmpresa['totEliminados']			= 0;
		$resUsuarios['totNaoCadastrados']			= 0;
		$resUsuarios['totEliminados']				= 0;  
		$resUsuarioEmpresa['totNaoCadastrados']		= 0;
		$resUsuarioEmpresa['totEliminados']			= 0;
		$resGrupos['totNaoCadastrados']				= 0;
		$resGrupos['totEliminados']					= 0;
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
					$resUsuarios,
					$resUsuarioEmpresa,					
					$resGrupos,				
					$gruposUsuarios,					
                    $resGruposProgramas,
                    $resFuncao,										
					$_SESSION['empresaid'],
					'', 
                    '',
                    '',
					$inicio, 
					date('Y-m-d H:i:s'), 
					'Erro na integridade dos arquivos'."\n".'Arquivos não encontrados.'
                );
                
                $this->helper->setAlert(
                    'error',
                    'Não encontrei o arquivo {$csvFile}',
                    'Carga/'
                );
                die;
            endif;

            // Valida se arquivo não está vazio
            if(count(file($val, FILE_SKIP_EMPTY_LINES)) - 1 == 0):
                $sincronizacao->gravaHistoricoSincronizacao(
                    $resProgramas,					
                    $resProgEmpresa,
                    $resUsuarios,					
                    $resUsuarioEmpresa,					
                    $resGrupos,				
                    $gruposUsuarios,					
                    $resGruposProgramas,
                    $resFuncao,										
                    $_SESSION['empresaid'], 
                    '', 
                    '', 
                    '', 
                    $inicio, 
                    date('Y-m-d H:i:s'), 
                    'Erro na integridade dos arquivos'
                );

                $this->helper->setAlert(
                    'error',
                    'Erro na integridade dos arquivos. Favor exportar a carga novamente!',
                    'carga/'
                );
                die;
            endif;   
        endforeach;   
    }

    public function iniciaCarga()
    {        
        //include BASE_PATH.'/views/template_carga_header.php';
        
        ini_set('display_errors',1);
        ini_set('display_startup_erros',1);
        error_reporting(E_ALL);

        setlocale(LC_TIME, 'pt_BR');
        date_default_timezone_set('America/Sao_Paulo');
        $sincronizacao = new Sincronizacao();
        $inicio = date('d/m/Y H:i:s');        
        $analise = $_POST['analise'];                
        $csvFile = '';        
        
        try{
            if(isset($_FILES['file']) && !empty($_FILES['file'])):
                $csvFile = $_FILES['file']['tmp_name'];
            elseif((isset($_POST['file']) && !empty($_POST['file']))):
                $csvFile = BASE_PATH.'/cargas/sincronizando/'.$_POST['file'];                
            endif;
            
            $zip = new ZipArchive;
            $zip->open($csvFile);
            try{
                if($zip->extractTo(BASE_PATH.'/cargas/') == false):
                    $this->helper->setAlert(
                        'error',
                        'Erro ao descompactar arquivo',
                        'Carga'
                    );
                endif;
            }catch(EXCEPTION $e){
                print_r($e);
                die('');
            }
            
            $zip->close();                          
            $this->validaIntegridadeArquivos();

            $nameFileBkp = "C_".str_replace(['-',' ',':'],['','_',''], date('Y-m-d H:i:s')).'.sql';
            set_time_limit(0);

          

            // Se extraiu com sucesso, executa método de carga de programas 
            //echo 'comecei programas<br>';                       
            $resProgramas = $this->programas($analise, $nameFileBkp);
            //print_r($resProgramas);                                            
            if($resProgramas['return'] == false):        
				//die('comecei programas<br>');
                $this->helper->setAlert(
                    'error',
                    $resProgramas['erro'],
                    'Carga/'
                );                                                                                        
            else:                
                // Se executou carga de programas com sucesso, executa método de carga de programa vs empresa                            
                //echo 'comecei programa vs empresa<br>';  
                $resProgEmpresa = $this->programasEmpresas($analise, $nameFileBkp);
                //print_r($resProgEmpresa);
                if($resProgEmpresa['return'] == false):                      
					//die('comecei programa vs empresa<br>');
                    $this->helper->setAlert(
                        'error',
                        $resProgEmpresa['error'],
                        'Carga/'
                    );                                                                            
                else:
                    // Se executou carga de programa vs empresa com sucesso, executa método de carga de usuários 
                    //echo 'comecei usuários<br>';                      
                    $resUsuarios = $this->usuarios($analise, $nameFileBkp);
                    //print_r($resUsuarios);
                    if($resUsuarios['return'] == false):
						//die('comecei usuários<br>');
                        $this->helper->setAlert(
                            'error',
                            $resUsuarios['erro'],
                            'Carga/'
                        );                          
                    else:
                        // Se executou carga de usuários com sucesso, executa método de carga de usuário vs empresa  
                        //echo 'comecei usuário vs empresa<br>';                                  
                        $resUsuarioEmpresa = $this->usuariosEmpresas($analise, $nameFileBkp);
                        //print_r($resUsuarioEmpresa);
                        if($resUsuarioEmpresa['return'] == false):                                         
							//die('comecei usuário vs empresa<br>');
                            $this->helper->setAlert(
                                'error',
                                $resUsuarioEmpresa['erro'],
                                'Carga/'
                            );                                                                
                        else:
                            // Se executou carga de usuário vs empresa com sucesso, executa método de carga de grupos      
                            //echo 'comecei grupos<br>';                                           
                            $resGrupos = $this->grupos($analise, $nameFileBkp);
                            //print_r($resGrupos);
                            if($resGrupos['return'] == false):
								//die('comecei grupos<br>');
                                $this->helper->setAlert(
                                    'error',
                                    $resGrupos['erro'],
                                    'Carga/'
                                );                                                                               
                            else:
                                // Se executou carga de grupo vs usuário com sucesso, executa método de carga de grupo vs programa       
                                //echo 'grupo vs programa<br>';                                           
                                $resGruposProgramas = $this->gruposProgramas($analise, $nameFileBkp);
                                //print_r($resGruposProgramas);
                                if($resGruposProgramas['return'] == false):  
                                    //die('grupo vs programa<br>');
                                    $this->helper->setAlert(
                                        'error',
                                        $resGruposProgramas['erro'],
                                        'Carga/'
                                    );                                                                                      
                                else:
                                    // Se executou carga de grupos com sucesso, executa método de carga de grupo vs usuário
                                    //echo 'grupo vs usuário<br>';                                      
                                    $gruposUsuarios = $this->gruposUsuarios($analise, $nameFileBkp);
                                    //print_r($gruposUsuarios);
                                    if($gruposUsuarios['return'] == false):  
                                        //die('grupo vs usuário<br>');
                                        $this->helper->setAlert(
                                            'error',
                                            $gruposUsuarios['erro'],
                                            'Carga/'
                                        );                                                                                                  
                                    endif;                                                
                                endif;
                            endif;
                        endif;
                    endif;
                endif;
            endif;                           
            //nclude BASE_PATH.'/views/template_carga_footer.php';
            $file = '';
            $podeSincronizar = false;
            if($analise == 1):
                if($resProgramas['totNaoCadastrados'] > 0 || $resProgEmpresa['totNaoCadastrados'] > 0 || $resUsuarios['totNaoCadastrados'] 
                    || $resUsuarioEmpresa['totNaoCadastrados'] || $resGrupos['totNaoCadastrados'] || $gruposUsuarios['totNaoCadastrados']
                    || $resGruposProgramas['totNaoCadastrados']
                    || $resProgramas['totEliminados'] > 0 || $resProgEmpresa['totEliminados'] > 0 || $resUsuarios['totEliminados'] 
                    || $resUsuarioEmpresa['totEliminados'] || $resGrupos['totEliminados'] || $gruposUsuarios['totEliminados']
                    || $resGruposProgramas['totEliminados'] 
                ):
                    //echo 'Copiando export para sincronizando'."<br>";
                    $file = 'C_'.date('dmY_His').'.zip';
                    move_uploaded_file($_FILES['file']['tmp_name'], BASE_PATH.'/cargas/sincronizando/'.$file); 
                    $podeSincronizar = true;
                endif;
                
                $dados = [
                    'programas'         => $resProgramas,
                    'programaEmpresa'   => $resProgEmpresa,
                    'usuarios'          => $resUsuarios,
                    'usuarioEmpresa'    => $resUsuarioEmpresa,
                    'grupos'            => $resGrupos,
                    'grupoUsuario'      => $gruposUsuarios,
                    'grupoPrograma'     => $resGruposProgramas,
                    ['totNaoCadastrados' => 0, 'totEliminados' => 0],
                    'inicio'            => $inicio,
                    'fim'               => date('d/m/Y H:i:s'),
                    'podeSincronizar'   => $podeSincronizar,
                    'file'              => $file
                ];
            else:
                $dados = '';               
            endif;

            //echo 'Passei por tudo'."<br>";
            
            // Remove a pasta export_sga                                      
            foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator(BASE_PATH.'/cargas/export_sga',FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST) as $files):
                $files->isFile() ? unlink($files->getPathname()) : rmdir($files->getPathname());                                                
            endforeach;
            rmdir(BASE_PATH.'/cargas/export_sga');
            
            if($analise == 1):
                $dados['logs'] = $sincronizacao->carregaLogs();
                //print_r($dados['logs']);
                //echo 'Fui quase';
                $this->loadTemplate('carga', $dados);
            else: 				
				
                $csvStore = $resProgramas['csvStore'].
                            $resProgEmpresa['csvStore'].
                            $resUsuarios['csvStore'].
                            $resUsuarioEmpresa['csvStore'].
                            $resGrupos['csvStore'].
                            $gruposUsuarios['csvStore'].
                            $resGruposProgramas['csvStore'];

                $fileDiff = BASE_PATH.'/cargas/sincronizados/'.str_replace('.zip','',$_POST['file']).'_diff.csv';
                file_put_contents($fileDiff, $csvStore);

                move_uploaded_file(BASE_PATH.'/cargas/sincronizando/'.$_POST['file'], BASE_PATH.'/cargas/sincronizados/'.$_POST['file']);
                unlink(BASE_PATH.'/cargas/sincronizando/'.$_POST['file']);

                $sincronizacao->gravaHistoricoSincronizacao(
                    $resProgramas,
                    $resProgEmpresa,
                    $resUsuarios,
                    $resUsuarioEmpresa,
                    $resGrupos,
                    $gruposUsuarios,
                    $resGruposProgramas,
                    ['totNaoCadastrados' => 0, 'totEliminados' => 0],
                    $_SESSION['empresaid'],                
                    $_POST['file'],                    
                    $nameFileBkp,
                    $fileDiff,
                    $inicio,
                    'Sincronizado'
                );
                $this->helper->setAlert(
                    'success',
                    'Carga importada com sucesso',
                    //'Carga/'
                );
                $this->db->query("CALL sp_sga_refresh_MTZappsProcesso()");
                $this->db->query("UPDATE z_sga_vm_usuarios_refresh SET atualiza = 1");
  
                die('success');
                $this->helper->setAlert(
                    'success',
                    'Carga importada com sucesso',
                    'Carga/'
                );
                //$this->loadTemplate('carga', $dados);            
            endif;
        }catch(EXCEPTION $e){
            die($e->getMessage());
        }                
    }

	/**
	* Carrega tela de sincronização de programas
	*/
    public function programas($analise, $nameFileBkp)
    {        
		$sincronizacao = new Sincronizacao();
		
		setlocale(LC_TIME, 'pt_BR');
        date_default_timezone_set('America/Sao_Paulo');		                                 

        $csvFile = BASE_PATH.'/cargas/export_sga/programas.csv';



        $file = fopen($csvFile, 'r');
        // Pula cabeçalho
        fgetcsv($file);
        
        // Percorre as linhas e inclui na query
        $totProgJaCad = 0;
        $totProgCad = 0;
        $diff = '';
        $totalDiff = 0;
        $arr_query_diff_sga = [];
        $resSinc['inicio'] = date('H:i:s');    
        $resSinc['return'] = true;
        $numLine = 1;
        $htmlTable = '';
        $csvStore = "PROGRAMAS IMPORTADOS;\n";
        $csvStore .= "z_sga_programas_id;cod_programa;descricao_programa;cod_modulo;descricao_modulo;especific;upc;ajuda_programa;codigo_rotina;descricao_rotina;registro_padrao;visualiza_menu;procedimento_pai;monitorado;\n";

        if($analise == 0):
            $sincronizacao->backup_tables('z_sga_programas', $nameFileBkp);
        endif;

        $diff = $this->db->query('SELECT * FROM z_sga_programas');

        while(!feof($file)):
            $line = fgetcsv($file, 0, ';');

            // Valida se não é cabeçalho
            if(isset($line[0]) && $line[0] != 'z_sga_programas_id'):
                // Valida se programa já existe na base
                $valid = $sincronizacao->validaCadastroProgramas($line[1]);

                // Se foi encontrado na base                
                if(isset($valid['return']) && $valid['return']):
                    $numLine++;
                    $totProgJaCad++;
                    array_push($arr_query_diff_sga, $valid['z_sga_programas_id']);
                    continue;
                endif;                

                // Se não foi encontrado o relacionamento na base
                if($analise == 0):
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
                    
                    $this->db->query($query_values);
                endif;
                
                // Se não foi encontrado na base                
                if($line[0] != 'Programa'):                    
                    if($analise == 1):
                        $htmlTable .= '	<tr style="background: #9fdbc4">';
                        $htmlTable .= '		<td>'.$numLine++.'</td>';
                        $htmlTable .= '		<td>'.$line[1].'</td>';
                        $htmlTable .= '		<td>'.$line[2].'</td>';
                        $htmlTable .= '		<td>Inserir</td>';						
                        $htmlTable .= '	</tr>';
                    endif;
                    $totProgCad++;                    
                endif;
            endif;
        endwhile;

        fclose($file);        

        $csvStore .= "\n\n";

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
                        if($analise == 1):
                            $htmlTable .= '	<tr style="background: #ecd1d1">';
                            $htmlTable .= '		<td>'.$val['z_sga_programas_id'].'</td>';
                            $htmlTable .= '		<td>'.$val['cod_programa'].'</td>';
                            $htmlTable .= '		<td>'.$val['descricao_programa'].'</td>';
                            $htmlTable .= '		<td>Remover</td>';
                            $htmlTable .= '	</tr>';
                        endif;

                        if($analise == 0):
                            $this->db->query('DELETE FROM z_sga_programas WHERE z_sga_programas_id = ' . $val['z_sga_programas_id']);
                            if(isset($val['cod_programa'])):
                                $csvStore .= $val['z_sga_programas_id'].";".$val['cod_programa'].";".$val['descricao_programa'].";".$val['cod_modulo'].";".$val['descricao_modulo'].";".$val['especific'].";".$val['upc'].";".$val['ajuda_programa'].";".$val['codigo_rotina'].";".$val['descricao_rotina'].";".$val['registro_padrao'].";".$val['visualiza_menu'].";".$val['procedimento_pai'].";".$val['monitorado'].";";
                                $csvStore .= "\n";
                            endif;
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
		

        return $resSinc;
    }   		
    
	/**
     * Carrega tela de sincronização de grupos x programas
     */
    public function programasEmpresas($analise, $nameFileBkp)
    {       
		$sincronizacao = new Sincronizacao();
		
		setlocale(LC_TIME, 'pt_BR');
        date_default_timezone_set('America/Sao_Paulo');		                    

        $csvFile = BASE_PATH.'/cargas/export_sga/'.$_SESSION['empresaid'].'/programa_empresa.csv';
        $file = fopen($csvFile, 'r');
        // Pula cabeçalho
        //fgetcsv($file);
        
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
        
        if($analise == 0):
            $sincronizacao->backup_tables('z_sga_programa_empresa', $nameFileBkp);
        endif;

        $diff =  $this->db->query('SELECT * FROM z_sga_programa_empresa WHERE idEmpresa = '.$_SESSION['empresaid']);

        while(!feof($file)):
            $line = fgetcsv($file, 0, ';');
            
            // Valida se não é cabeçalho
            if(isset($line[0]) && $line[0] != 'idGrupoPrograma'):

                // Valida se instancia da carga é igual a instancia da sessão do sistema
                if($line[2] != $_SESSION['empresaid']):                    
                    return [
                        'return' => false,
                        'error' => 'Instância da carga difere da instância, selecionada no sistema!'
                    ];
                    break;
                endif;

                // Valida se não é cabeçalho
                if(isset($line[0]) && $line[0] != 'idGrupoPrograma'):
					// Valida se relação já existe na base                
                    $valid = $sincronizacao->validaCadastroProgramasEmpresas($line[1], $_SESSION['empresaid'], $analise);
					
                    if($valid['return'] === 'error'):
                        return [
                            'return' => false,
                            'error'  => $valid['error']                            
                        ];
                        break;
                    // Se foi encontrado na base
                    elseif($valid['return']):                        
                        $totProgJaCad++;
                        array_push($arr_query_diff_sga, $valid['idGrupoPrograma']);
                        continue;
                    endif;

                    // Se não foi encontrado o relacionamento na base
                    if($analise == 0):
                        $csvStore .= implode(';', $line)."\n";
                        $query_values = "INSERT INTO
                            z_sga_programa_empresa (
                                `idPrograma`,
                                `idEmpresa`
                            ) VALUES(
                            '".$valid['idPrograma']."',
                            '".$_SESSION['empresaid']."')";
                            
                            $this->db->query($query_values);
                    endif;

                    // Se não foi encontrado na base
                    
                    if($line[0] != 'idGrupoPrograma'):
                        if($analise == 1):
                            $htmlTable .= '	<tr style="background: #9fdbc4">';
                            $htmlTable .= '		<td style="width: 8%">'.$numLine++.'</td>';
                            $htmlTable .= '		<td style="width: 20%">'.$line[1].'</td>';
                            $htmlTable .= '		<td style="width: 20%">'.$line[2].'</td>';                        
                            $htmlTable .= '		<td>Inserir</td>';
                            $htmlTable .= '	</tr>';                            
                        endif;
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
                        if($analise == 1):
                            $htmlTable .= '	<tr style="background: #ecd1d1">';
                            $htmlTable .= '		<td style="width: 8%">'.$val['idGrupoPrograma'].'</td>';
                            $htmlTable .= '		<td style="width: 20%">'.$val['idPrograma'].'</td>';
                            $htmlTable .= '		<td style="width: 20%">'.$val['idEmpresa'].'</td>';                       
                            $htmlTable .= '		<td>Remover</td>';
                            $htmlTable .= '	</tr>'; 
                        endif;

                        if($analise == 0):
                            $this->db->query('DELETE FROM z_sga_programa_empresa WHERE idGrupoPrograma = ' . $val['idGrupoPrograma']);
                            if(isset($val['cod_programa'])):
                                $csvStore .= $val['idGrupoPrograma'].";".$val['cod_programa'].";".$val['idEmpresa'].";";
                                $csvStore .= "\n";
                            endif;
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
    public function gruposProgramas($analise, $nameFileBkp)
    {    
		$sincronizacao = new Sincronizacao();
		
		setlocale(LC_TIME, 'pt_BR');
        date_default_timezone_set('America/Sao_Paulo');		                          
        
        $csvFile = getcwd().'/cargas/export_sga/'.$_SESSION['empresaid'].'/grupo_programa.csv';
        $file = fopen($csvFile, 'r');
        // Pula cabeçalho
        //fgetcsv($file);
        
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
        
        if($analise == 0):
            $sincronizacao->backup_tables('z_sga_grupo_programa', $nameFileBkp);
        endif;
        
        /***** Recupera os dados da base, percorre e valida se existe no array com os dados do csv. Se existir remove do array */
        $diff = $this->db->query("SELECT * FROM z_sga_grupo_programa WHERE idEmpresa = " . $_SESSION['empresaid']);

        while(!feof($file)):
            $line = fgetcsv($file, 0, ';');

            // Valida se não é cabeçalho
            if(isset($line[0]) && $line[0] != 'z_sga_grupo_programa_id'):
                //echo 'Grupo: ' .$line[1] . ' x Programa: ' . $line[4]."<br>"; 
                // Valida se programa já existe na base
                $valid = $sincronizacao->validaCadastroGruposProgramas($line[1], $line[4], $analise, $_SESSION['empresaid']);
                
                // Se houver erro
                if(isset($valid) && $valid['return'] === 'error'):                                        
                    return [
                        'return' => 'error',
                        'error'  => $valid['error']                        
                    ];
                    break;
				endif;
				
                // Se foi encontrado na base
                if(isset($valid) && $valid['return']):                    
                    $totProgJaCad++;
                    array_push($arr_query_diff_sga, $valid['z_sga_grupo_programa_id']);
                    continue;
                endif;

                // Se não foi encontrado o relacionamento na base
                if($analise == 0):
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
                            ".$_SESSION['empresaid']."
                        )";

                        $this->db->query($query_values);
                endif;

                // Se não foi encontrado relacionamento na base
                if($line[0] != 'Programa'):
                    if($analise == 1):
                        $htmlTable .= '	<tr style="background: #9fdbc4">';
                        $htmlTable .= '		<td style="width: 8%">'.$numLine++.'</td>';
                        $htmlTable .= '		<td style="width: 20%">'.$line[1].'</td>';
                        $htmlTable .= '		<td style="width: 20%">'.$line[2].'</td>';
                        $htmlTable .= '		<td>'.$line[4].'</td>';
                        $htmlTable .= '		<td>Inserir</td>';
                        $htmlTable .= '	</tr>';                        
                    endif;
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
                        if($analise == 1):
                            $htmlTable .= '	<tr style="background: #ecd1d1">';
                            $htmlTable .= '		<td style="width: 8%">'.$val['z_sga_grupo_programa_id'].'</td>';
                            $htmlTable .= '		<td style="width: 20%">'.$val['cod_grupo'].'</td>';
                            $htmlTable .= '		<td style="width: 20%">'.$val['nome_grupo'].'</td>';
                            $htmlTable .= '		<td>'.$val['cod_programa'].'</td>';
                            $htmlTable .= '		<td>Remover</td>';
                            $htmlTable .= '	</tr>';
                        endif;

                        if($analise == 0):
                            $this->db->query('DELETE FROM z_sga_grupo_programa WHERE z_sga_grupo_programa_id = ' . $val['z_sga_grupo_programa_id']);
                            if(isset($val['cod_grupo'])):
                                $csvStore .= $val['z_sga_grupo_programa_id'].";".$val['cod_grupo'].";".$val['nome_grupo'].";".$val['gestor'].";".$val['cod_programa'].";".$val['idGrupo'].";".$val['idPrograma'].";";
                                $csvStore .= "\n";
                            endif;
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
    public function gruposUsuarios($analise, $nameFileBkp)
    {        
		$sincronizacao = new Sincronizacao();
		
		setlocale(LC_TIME, 'pt_BR');
        date_default_timezone_set('America/Sao_Paulo');		                    

        $csvFile = BASE_PATH.'/cargas/export_sga/'.$_SESSION['empresaid'].'/grupos.csv';
        $file = fopen($csvFile, 'r');
        // Pula cabeçalho
        //fgetcsv($file);
        
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
        
        if($analise == 0):
            $sincronizacao->backup_tables('z_sga_grupos', $nameFileBkp);
        endif;

        $diff = $this->db->query('SELECT * FROM z_sga_grupos gs LEFT JOIN z_sga_grupo g ON g.idGrupo = gs.idGrupo WHERE gs.idEmpresa = '.$_SESSION['empresaid']);

        while(!feof($file)):
            $line = fgetcsv($file, 0, ';');

            // Valida se não é cabeçalho
            if(isset($line[0]) && $line[0] != 'z_sga_grupos_id'):
                // Valida se programa já existe na base                
                $valid = $sincronizacao->validaCadastroGruposUsuarios($line[1], $line[4], $analise);

                // Se não foi encontrado algum grupo ou usuário
                if($valid['return'] === 'error'):
                    return [
                        'return' => 'error',
                        'error'  => $valid['error']
                    ];
                    break;
				endif;
				
                // Se foi encontrado relacionamento na base                
				if($valid['return']):                    
                    $totProgJaCad++;
                    array_push($arr_query_diff_sga, $valid['z_sga_grupos_id']);
                    continue;
                endif;

                // Se não foi encontrado o relacionamento na base
                if($analise == 0):
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
                            ".$_SESSION['empresaid'].")";

                        $this->db->query($query_values);
                endif;

                // Se não foi encontrado na base
                
                if($line[0] != 'Programa'):
                    if($analise == 1):
                        $htmlTable .= '	<tr style="background: #9fdbc4">';
                        $htmlTable .= '		<td style="width: 8%">'.$numLine++.'</td>';
                        $htmlTable .= '		<td style="width: 20%">'.$line[1].'</td>';
                        $htmlTable .= '		<td style="width: 20%">'.$line[2].'</td>';
                        $htmlTable .= '		<td>'.$line[4].'</td>';
                        $htmlTable .= '		<td>Inserir</td>';
                        $htmlTable .= '	</tr>';
                    endif;
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
                        if($analise == 1):    
                            $htmlTable .= '	<tr style="background: #ecd1d1">';
                            $htmlTable .= '		<td style="width: 8%">'.$val['z_sga_grupos_id'].'</td>';
                            $htmlTable .= '		<td style="width: 20%">'.$val['cod_grupo'].'</td>';
                            $htmlTable .= '		<td style="width: 20%">'.$val['desc_grupo'].'</td>';
                            $htmlTable .= '		<td>'.$val['cod_usuario'].'</td>';
                            $htmlTable .= '		<td>Remover</td>';
                            $htmlTable .= '	</tr>';  
                        endif;
                        if($analise == 0):
                            $this->db->query('DELETE FROM z_sga_grupos WHERE z_sga_grupos_id = ' . $val['z_sga_grupos_id']);
                            if(isset($val['cod_grupo'])):
                                $csvStore .= $val['z_sga_grupos_id'].';'.$val['cod_grupo'].';'.$val['desc_grupo'].';'.$val['cod_usuario'].';'.$val['idGrupo'].';'.$val['idUsuario'].';';
                                $csvStore .= "\n";
                            endif;
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
    public function grupos($analise, $nameFileBkp)
    {    
		$sincronizacao = new Sincronizacao();
		
		setlocale(LC_TIME, 'pt_BR');
        date_default_timezone_set('America/Sao_Paulo');		                        
                
        $csvFile = BASE_PATH.'/cargas/export_sga/'.$_SESSION['empresaid'].'/grupo.csv';
        $file = fopen($csvFile, 'r');
        // Pula cabeçalho
        //fgetcsv($file);
        
        // Percorre as linhas e inclui na query
        $totProgJaCad = 0;
        $totProgCad = 0;
		$diff= '';
        $totalDiff = 0;
		$arr_query_diff_sga = [];
        $resSinc['inicio'] = date('H:i:s');
        $resSinc['return'] = true;
        $numLine = 1;
        $htmlTable = '';
        $csvStore = "GRUPOS IMPORTADOS;\n";
        $csvStore .= "idGrupo;idLegGrupo;descAbrev;descricao;idEmpresa;\n";

        if($analise == 0):
            $sincronizacao->backup_tables('z_sga_grupo', $nameFileBkp);
        endif;

        $diff = $this->db->query('SELECT * FROM z_sga_grupo WHERE idEmpresa = '.$_SESSION['empresaid']);

        while(!feof($file)):
            $line = fgetcsv($file, 0, ';');

            // Valida se não é cabeçalho
            if(isset($line[0]) && $line[0] != 'idGrupo'):
                // Valida se instancia da carga é igual a instancia da sessão do sistema
                if($line[4] != $_SESSION['empresaid']):                    
                    return [
                        'return' => 'error',
                        'error' => 'Instância da carga difere da instância, selecionada no sistema!'
                    ];
                    break;
                endif;
                
                // Valida se grupo já existe na base                
                $valid = $sincronizacao->validaCadastroGrupos($line[1]);

                // Se foi encontrado na base
                if(isset($valid['return']) && $valid['return']):                    
                    $totProgJaCad++;
                    array_push($arr_query_diff_sga, $valid['idGrupo']);
                    continue;
                endif;

                // Se não foi encontrado na base
                // Se não foi encontrado o relacionamento na base
                if($analise == 0):
                    $csvStore .= implode(';', $line)."\n";
                    $query_values = "INSERT INTO
                        z_sga_grupo (														
                            `idLegGrupo`,
                            `descAbrev`,							
                            `idEmpresa`
                        ) VALUES (
                        '".addslashes($line[1])."', 
                        '".addslashes($line[2])."',                        
                        '".addslashes($_SESSION['empresaid'])."')";

                        $this->db->query($query_values);
                endif;

                // Se não foi encontrado na base                
                if($line[0] != 'idGrupo'):
                    if($analise == 1):
                        $htmlTable .= '	<tr style="background: #9fdbc4">';
                        $htmlTable .= '		<td>'.$numLine++.'</td>';
                        $htmlTable .= '		<td>'.$line[1].'</td>';
                        $htmlTable .= '		<td>'.$line[2].'</td>';
                        $htmlTable .= '		<td>Inserir</td>';
                        $htmlTable .= '	</tr>';                        
                    endif;
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
                $csvStore .= "GRUPOS ELIMINADOS DO SGA;\n";
                $csvStore .= "idGrupo;idLegGrupo;descAbrev;descricao;idEmpresa;\n";	

                foreach($diff as $key => $val):
                    if(in_array($val['idGrupo'], $arr_query_diff_sga)):
                        unset($diff[$key]);
                    else:
                        if($analise == 1):    
                            $htmlTable .= '	<tr style="background: #ecd1d1">';
                            $htmlTable .= '		<td>'.$val['idGrupo'].'</td>';
                            $htmlTable .= '		<td>'.$val['idLegGrupo'].'</td>';
                            $htmlTable .= '		<td>'.$val['descAbrev'].'</td>';
                            $htmlTable .= '		<td>Remover</td>';
                            $htmlTable .= '	</tr>';  
                        endif;
                        if($analise == 0):
                            $this->db->query('DELETE FROM z_sga_grupo WHERE idGrupo = ' . $val['idGrupo']);
                            if(isset($val['idLegGrupo'])):
                                $csvStore .= $val['idGrupo'].';'.$val['idLegGrupo'].';'.$val['descAbrev'].';'.$val['descricao'].';'.$val['idEmpresa'].';';
                                $csvStore .= "\n";
                            endif;
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
	* Carrega tela de sincronização de usuários
	*/
    public function usuarios($analise, $nameFileBkp)
    {        
		$sincronizacao = new Sincronizacao();
		
		setlocale(LC_TIME, 'pt_BR');
        date_default_timezone_set('America/Sao_Paulo');		                       

        $csvFile = BASE_PATH.'/cargas/export_sga/usuarios.csv';                
        $file = fopen($csvFile, 'r');
        // Pula cabeçalho
        //fgetcsv($file);
        
        // Percorre as linhas e inclui na query
        $totProgJaCad = 0;
        $totProgCad = 0;
		$diff = '';
        $totalDiff = 0;
        $arr_query_diff_sga = [];
        $resSinc['inicio'] = date('H:i:s');
        $resSinc['return'] = true;
        $numLine = 1;
        $htmlTable = '';
        $csvStore = "USUÁRIOS IMPORTADOS;\n";
        $csvStore .= "z_sga_usuarios_id;cod_usuario;nome_usuario;CPF;cod_gestor;cod_funcao;funcao;email;solicitante;gestor_usuario;gestor_grupo;gestor_programa;si;idUsrFluig;ativo;idDepartamento;\n";

        if($analise == 0):
            $sincronizacao->backup_tables('z_sga_usuarios', $nameFileBkp);
        endif;

        $diff = $this->db->query('SELECT * FROM z_sga_usuarios');

        while(!feof($file)):
            $line = fgetcsv($file, 0, ';');

            // Valida se não é cabeçalho
            if(isset($line[0]) && $line[0] != 'z_sga_usuarios_id'):
                // Valida se grupo já existe na base
                $valid = $sincronizacao->validaCadastroUsuarios($line[1]);

                // Se foi encontrado relacionamento na base
                if(isset($valid['return']) && $valid['return']):                    
                    $totProgJaCad++;
                    array_push($arr_query_diff_sga, $valid['z_sga_usuarios_id']);
                    continue;
                endif;

                // Se não foi encontrado o relacionamento na base
                if($analise == 0):
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

                        $this->db->query($query_values);
                endif;
                
                // Se não foi encontrado na base
                if($line[0] != 'cod_usuario'):
                    if($analise == 1):
                        $htmlTable .= '	<tr style="background: #9fdbc4">';
                        $htmlTable .= '		<td>'.$numLine++.'</td>';
                        $htmlTable .= '		<td>'.$line[1].'</td>';
                        $htmlTable .= '		<td>'.$line[2].'</td>';
                        $htmlTable .= '		<td>Inserir</td>';
                        $htmlTable .= '	</tr>';                          
                    endif;
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
                $csvStore .= "USUÁRIOS ELIMINADOS DO SGA;\n";
                $csvStore .= "z_sga_usuarios_id;cod_usuario;nome_usuario;CPF;cod_gestor;cod_funcao;funcao;email;solicitante;gestor_usuario;gestor_grupo;gestor_programa;si;idUsrFluig;ativo;idDepartamento;\n";			

                foreach($diff as $key => $val):
                    if(in_array($val['z_sga_usuarios_id'], $arr_query_diff_sga)):
                        unset($diff[$key]);
                    else:
                        if($analise == 1):    
                            $htmlTable .= '	<tr style="background: #ecd1d1">';
                            $htmlTable .= '		<td>'.$val['z_sga_usuarios_id'].'</td>';
                            $htmlTable .= '		<td>'.$val['cod_usuario'].'</td>';
                            $htmlTable .= '		<td>'.$val['nome_usuario'].'</td>';
                            $htmlTable .= '		<td>Remover</td>';
                            $htmlTable .= '	</tr>'; 
                        endif;
                        if($analise == 0):
                            $this->db->query('DELETE FROM z_sga_usuarios WHERE z_sga_usuarios_id = ' . $val['z_sga_usuarios_id']);
                            if(isset($val['cod_usuario'])):
                                $csvStore .= $val['z_sga_usuarios_id'].';'.$val['cod_usuario'].';'.$val['nome_usuario'].';'.$val['CPF'].';'.$val['cod_gestor'].';';
                                $csvStore .= $val['cod_funcao'].';'.$val['funcao'].';'.$val['email'].';'.$val['solicitante'].';'.$val['gestor_usuario'].';';
                                $csvStore .= $val['gestor_grupo'].';'.$val['gestor_programa'].';'.$val['si'].';'.$val['idUsrFluig'].';'.$val['ativo'].';'.$val['idDepartamento'].';';
                                $csvStore .= "\n";
                            endif;
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
     * Carrega tela de sincronização de grupos x programas
     */
    public function usuariosEmpresas($analise, $nameFileBkp)
    {       
        $dados = array();
		$sincronizacao = new Sincronizacao();
		
		setlocale(LC_TIME, 'pt_BR');
        date_default_timezone_set('America/Sao_Paulo');
		                  
        //$csvFile = 'ser004b.csv';
        $csvFile = BASE_PATH.'/cargas/export_sga/'.$_SESSION['empresaid'].'/usuario_empresa.csv';        
        $file = fopen($csvFile, 'r');
        // Pula cabeçalho
        //fgetcsv($file);
        
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
        
        if($analise == 0):
            $sincronizacao->backup_tables('z_sga_usuario_empresa', $nameFileBkp);
        endif;

        $diff = $this->db->query('SELECT * FROM z_sga_usuario_empresa WHERE idEmpresa = ' . $_SESSION['empresaid']);

        while(!feof($file)):
            $line = fgetcsv($file, 0, ';');

            // Valida se não é cabeçalho
            if(isset($line[0]) && $line[0] != 'idUsrEMp'):
                // Valida se não é cabeçalho
                if(isset($line[0]) && $line[0] != 'idUsrEMp'):
                    // Valida se instancia da carga é igual a instancia da sessão do sistema
                    if($line[2] != $_SESSION['empresaid']):                        
                        return [
                            'return' => 'error',
                            'error' => 'Instância da carga difere da instância, selecionada no sistema!'
                        ];
                        break;
                    endif;

                    // Valida se relação já existe na base                                                
                    $valid = $sincronizacao->validaCadastroUsuariosEmpresas($line[1], $_SESSION['empresaid'], $analise);

                    if($valid['return'] === 'error'):                                                
                        return [
                            'return' => 'error',
                            'error' => $valid['error']                        
                        ];
                        break;
                    // Se foi encontrado relacionamento na base
                    elseif($valid['return']):                        
                        $totProgJaCad++;
                        array_push($arr_query_diff_sga, $valid['idUsrEMp']);
                        //array_push($arr_cod_usuario, [$valid['idUsrEMp'] => $line[1]]);
                        continue;
                    endif;

                    // Se não foi encontrado o relacionamento na base
                    if($analise == 0):
                        $csvStore .= implode(';', $line)."\n";
                        $query_values = "INSERT INTO
                            z_sga_usuario_empresa (							
                                `idUsuario`,
                                `idEmpresa`,
                                `ativo`				
                            ) VALUES (
                            '".$valid['idUsuario']."',
                            '".$_SESSION['empresaid']."',                                                 
                            '".$valid['ativo']."')";

                            $this->db->query($query_values);
                    endif;

                    // Se não foi encontrado na base                    
                    if($line[0] != 'idUsrEMp'):
                        if($analise == 1):
                            $htmlTable .= '	<tr style="background: #9fdbc4">';
                            $htmlTable .= '		<td style="width: 8%">'.$numLine++.'</td>';
                            $htmlTable .= '		<td style="width: 20%">'.$line[1].'</td>';
                            $htmlTable .= '		<td style="width: 20%">'.$line[2].'</td>';
                            $htmlTable .= '		<td>Inserir</td>';
                            $htmlTable .= '	</tr>';                            
                        endif;
                        $totProgCad++;
                    endif;
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
                        if($analise == 1):    
                            $htmlTable .= '	<tr style="background: #ecd1d1">';
                            $htmlTable .= '		<td>'.$val['idUsrEMp'].'</td>';
                            $htmlTable .= '		<td>'.$val['idUsuario'].'</td>';
                            $htmlTable .= '		<td>'.$val['idEmpresa'].'</td>';
                            $htmlTable .= '		<td>Remover</td>';
                            $htmlTable .= '	</tr>';
                        endif;
                        if($analise == 0):
                            $this->db->query('DELETE FROM z_sga_usuario_empresa WHERE idUsrEMp = ' . $val['idUsrEMp']);
                            if(isset($val['cod_usuario'])):
                                $csvStore .= $val['idUsrEMp'].';'.$val['cod_usuario'].';'.$val['idEmpresa'].';'.$val['idGestor'].';'.$val['ativo'].';';
                                $csvStore .= "\n";
                            endif;
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
	* Carrega tela de sincronização de funcoes
	*/
    public function funcao()
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
		$sincronizacao = new Sincronizacao();				
		
        if(isset($_FILES['file']) && !empty($_FILES['file']) || isset($_POST['csvFile'])):
            setlocale(LC_TIME, 'pt_BR');
            date_default_timezone_set('America/Sao_Paulo');		                                 

            $csvFile = (isset($_POST['csvFile']) && !empty($_POST['csvFile'])) ? BASE_PATH.'/cargas/sincronizando/'.$_POST['csvFile'] : $_FILES['file']['tmp_name'];
            $file = fopen($csvFile, 'r');
            // Pula cabeçalho
            //fgetcsv($file);
            
            // Percorre as linhas e inclui na query
            $nameFileBkp = "CF_".str_replace(['-',' ',':'],['','_',''], date('Y-m-d H:i:s')).'.sql';
            $analise = $_POST['analise'];
            $totalCad = 0;
            $totalNotCad = 0;
            $query = '';
            $fileCsv = '';
            $query_values = '';
            $query_diff_sga = '';
            $dados['inicio'] = date('Y-m-d H:i:s');                
            $numLine = 1;
            $htmlTable = '';
            $csvStore = "FUNÇÕES IMPORTADAS;\n";
            $csvStore .= "idFuncao;cod_funcao;descricao;\n";

            while(!feof($file)):
                $line = fgetcsv($file, 0, ';');

                // Valida se não é cabeçalho
                if(isset($line[0]) && $line[0] != 'idFuncao'):
                    // Usado para comparar registro a mais no SGA
                    //$query_diff_sga .= "'".utf8_encode(trim($line[1]))."',";
                
                    // Valida se programa já existe na base
                    $valid = $sincronizacao->validaCadastroFuncao($line[1]);

                    // Se foi encontrado na base                
                    if($valid['return']):
                        $numLine++;
                        $totalCad++;
                        continue;
                    endif;                

                    // Se não foi encontrado o relacionamento na base                
                    $csvStore .= implode(';', $line)."\n";
                    $query_values .= "(
                        '".addslashes(trim($line[1]))."', 
                        '".addslashes(trim($line[2]))."'),";                    

                    if($analise == 1):
                        // Se não foi encontrado na base                
                        if($line[0] != 'idFuncao'):                    
                            if($analise == 1):
                                $htmlTable .= '	<tr style="background: #9fdbc4">';
                                $htmlTable .= '		<td>'.$numLine++.'</td>';
                                $htmlTable .= '		<td>'.trim($line[1]).'</td>';
                                $htmlTable .= '		<td>'.trim($line[2]).'</td>';
                                $htmlTable .= '		<td>Inserir</td>';
                                $htmlTable .= '	</tr>';
                            endif;                                                
                        endif;                        
                    endif;
                    $totalNotCad++;
                endif;
            endwhile;

            fclose($file);        

            $csvStore .= "\n\n";
            
            // Cria a query para comparação de registros a mais no SGA
            //if(!empty($query_diff_sga)):
                //$query_diff_sga =  'SELECT * FROM z_sga_manut_funcao WHERE cod_funcao NOT IN('.substr(trim($query_diff_sga), 0, -1) . ')';                
                
                //if($analise == 1):
                    //$diff = $sincronizacao->comparaDiffSGA($query_diff_sga);
                    
                    /*if(count($diff) > 0):
                        foreach($diff as $val):
                            $htmlTable .= '	<tr style="background: #ecd1d1">';
                            $htmlTable .= '		<td>'.$val['idFuncao'].'</td>';
                            $htmlTable .= '		<td>'.$val['cod_funcao'].'</td>';
                            $htmlTable .= '		<td>'.$val['descricao'].'</td>';
                            $htmlTable .= '		<td>Remover</td>';
                            $htmlTable .= '	</tr>';
                        endforeach;
                    endif;*/
                //endif;
            //endif;
                        
            // Cria a query para sincronização
            //echo $query_values;            
            if(!empty($query_values)):
                $query .= "
                    INSERT INTO
                        z_sga_manut_funcao (														
                            `cod_funcao`,
                            `descricao`
                        ) VALUES 
                " . $query_values;
                $query = substr(trim($query), 0, -1);
                
                if($analise == 1):
                    $fileCsv = 'CF_'.date('dmY_His').'.csv';
                    move_uploaded_file($_FILES['file']['tmp_name'], BASE_PATH.'/cargas/sincronizando/'.$fileCsv);
                    $dados['podeSincronizar'] = true;
                endif;

                // Se for para sincronizar
                if($analise == 0):
                    $result = $sincronizacao->sincronizaDados($query, 'z_sga_manut_funcao', $nameFileBkp, $query_diff_sga);

                    if($result['return']):
                        // Grava o que foi eliminado no csv caso exista.
                        if(count($result['diffSga']) > 0):                
                            $csvStore .= "FUNÇÕES ELIMINADAS DO SGA;\n";
                            $csvStore .= "idFuncao;cod_funcao;descricao;\n";
                            foreach($result['diffSga'] as $diff):
                                foreach($diff as $val):
                                    $csvStore .= $val.";";
                                endforeach;
                                $csvStore .= "\n";
                            endforeach;                
                            $csvStore .= "\n\n";             
                        endif;

                        $fileDiff = BASE_PATH.'/cargas/sincronizados/'.str_replace('.zip','',$_POST['csvFile']).'_diff.csv';
                        file_put_contents($fileDiff, $csvStore);
                        
                        move_uploaded_file(BASE_PATH.'/cargas/sincronizando/'.$_POST['csvFile'], BASE_PATH.'/cargas/sincronizados/'.$_POST['csvFile']);
                        unlink(BASE_PATH.'/cargas/sincronizando/'.$_POST['csvFile']);

                        // Grava o log da carga
                        $sincronizacao->gravaHistoricoSincronizacao(
                            ['totNaoCadastrados' => 0, 'totEliminados' => 0],
                            ['totNaoCadastrados' => 0, 'totEliminados' => 0],
                            ['totNaoCadastrados' => 0, 'totEliminados' => 0],
                            ['totNaoCadastrados' => 0, 'totEliminados' => 0],
                            ['totNaoCadastrados' => 0, 'totEliminados' => 0],
                            ['totNaoCadastrados' => 0, 'totEliminados' => 0],
                            ['totNaoCadastrados' => 0, 'totEliminados' => 0],
                            ['totNaoCadastrados' => $totalNotCad, 'totEliminados' => 0],
                            $_SESSION['empresaid'],
                            $_POST['csvFile'],
                            $nameFileBkp,
                            $fileDiff,
                            $dados['inicio'],
                            'Sincronizado'
                        ); 
                        
                        //die('fiz');
                        $this->helper->setAlert(
                            'success',
                            'Carga efetuada com sucesso!',
                            'carga/funcao'
                        );
                    endif;                                                                     
                endif;                
            endif;			        		
            
            $dados['fim']    				= date('H:i:s');
            $dados['totCadastrados'] 		= $totalCad;
            $dados['totNaoCadastrados']	    = $totalNotCad;		
            $dados['htmlTable'] 			= $htmlTable;
            $dados['csvStore'] 			    = $csvStore;
            $dados['csvFile'] 			    = $fileCsv;            
            
            if(isset($result['diffSga'])):
                $dados['totEliminados'] 	= count($result['diffSga']);
            elseif(isset($diff)):
                $dados['totEliminados']	= count($diff);
            else:
                $dados['totEliminados']	= 0;
            endif;
        endif;
		
		$dados['logs'] = $sincronizacao->carregaLogs();
        $this->loadTemplate('carga_funcao', $dados);
    } 
    
    /**
	* Carrega tela de sincronização de gestor de usuários
	*/
    public function gestorUsuario()
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
		$sincronizacao = new Sincronizacao();				
		
        if(isset($_FILES['file']) && !empty($_FILES['file']) || isset($_POST['csvFile'])):
            setlocale(LC_TIME, 'pt_BR');
            date_default_timezone_set('America/Sao_Paulo');		                                 

            $csvFile = (isset($_POST['csvFile']) && !empty($_POST['csvFile'])) ? BASE_PATH.'/cargas/sincronizando/'.$_POST['csvFile'] : $_FILES['file']['tmp_name'];
            $file = fopen($csvFile, 'r');
            // Pula cabeçalho
            //fgetcsv($file);
            
            // Percorre as linhas e inclui na query
            $nameFileBkp = "CGU_".str_replace(['-',' ',':'],['','_',''], date('Y-m-d H:i:s')).'.sql';
            $analise = $_POST['analise'];
            $totalCad = 0;
            $totalNotCad = 0;
            $query = '';
            $fileCsv = '';
            $query_values = '';
            $query_diff_sga = '';
            $dados['inicio'] = date('Y-m-d H:i:s');                
            $numLine = 1;
            $htmlTable = '';
            $csvStore = "GESTOR USUÁRIO IMPORTADOS;\n";
            $csvStore .= "codUsuario;nomeUsuario;codGestor;nomeGestor;\n";

            // die($analise);

            while(!feof($file)):
                $line = fgetcsv($file, 0, ';');

                // Valida se programa já existe na base
                $valid = $sincronizacao->validaCadastroGestorUsuario($line[0], $line[2]);

                // Se ocorrer um erro
                if (isset($valid['error'])) {
                    $this->helper->setAlert(
                        'error',
                        $valid['message'].$valid['error'],
                        'Carga/gestorUsuario'
                    );
                }

                // Se o retorno for false
                if(!$valid['return']) {
                    $totalNotCad++;
                    continue;
                }

                // Se não foi encontrado o relacionamento na base                
                $csvStore .= implode(';', $line)."\n";
                $query_values .= "(
                    '".addslashes(trim($line[1]))."', 
                    '".addslashes(trim($line[2]))."'),";                    

                if($analise == 1):
                    // Se não foi encontrado na base                
                    if($line[0] != 'idFuncao'):                    
                        if($analise == 1):
                            $htmlTable .= '	<tr style="background: #9fdbc4">';
                            $htmlTable .= '		<td>'.$numLine++.'</td>';
                            $htmlTable .= '		<td>'.trim($line[1]).'</td>';
                            $htmlTable .= '		<td>'.trim($line[2]).'</td>';
                            $htmlTable .= '		<td>'.trim($line[3]).'</td>';
                            $htmlTable .= '	</tr>';
                        endif;                                                
                    endif;                        
                endif;
                $totalCad++;
            endwhile;

            // die();

            fclose($file);        

            $csvStore .= "\n\n";
            
            // Cria a query para sincronização 
            // die($analise); 
            if(!empty($query_values)):
                if($analise == 1):
                    $fileCsv = 'CG'.date('dmY_His').'.csv';
                    move_uploaded_file($_FILES['file']['tmp_name'], BASE_PATH.'/cargas/sincronizando/'.$fileCsv);
                    $dados['podeSincronizar'] = true;
                endif;

                // Se for para sincronizar

                // die($analise);
                if($analise == 0):
                    $result = $sincronizacao->sincronizaDados($query, 'z_sga_usuarios', $nameFileBkp, $query_diff_sga);

                    // die(print_r($result));

                    if($result['return']):
                        // Grava o que foi eliminado no csv caso exista.
                        if(count($result['diffSga']) > 0):                
                            $csvStore .= "FUNÇÕES ELIMINADAS DO SGA;\n";
                            $csvStore .= "idFuncao;cod_funcao;descricao;\n";
                            foreach($result['diffSga'] as $diff):
                                foreach($diff as $val):
                                    $csvStore .= $val.";";
                                endforeach;
                                $csvStore .= "\n";
                            endforeach;                
                            $csvStore .= "\n\n";             
                        endif;

                        $fileDiff = BASE_PATH.'/cargas/sincronizados/'.str_replace('.zip','',$_POST['csvFile']).'_diff.csv';
                        // die($filleDiff . 'aiosio');
                        file_put_contents($fileDiff, $csvStore);
                        
                        move_uploaded_file(BASE_PATH.'/cargas/sincronizando/'.$_POST['csvFile'], BASE_PATH.'/cargas/sincronizados/'.$_POST['csvFile']);
                        unlink(BASE_PATH.'/cargas/sincronizando/'.$_POST['csvFile']);

                        // Grava o log da carga
                        $sincronizacao->gravaHistoricoSincronizacaoGestores(
                            $_SESSION['idUsrTotvs'],
                            $totalCad,
                            $totalNotCad,
                            $_POST['csvFile'],
                            $nameFileBkp,
                            $dados['inicio']
                        );
                        //die('fiz');
                        $this->helper->setAlert(
                            'success',
                            'Carga efetuada com sucesso!',
                            'Carga/gestorUsuario'
                        );
                    endif;                                                                     
                endif;                
            endif;			        		
            
            $dados['fim']    				= date('H:i:s');
            $dados['totCadastrados'] 		= $totalCad;
            $dados['totNaoCadastrados']	    = $totalNotCad;		
            $dados['htmlTable'] 			= $htmlTable;
            $dados['csvStore'] 			    = $csvStore;
            $dados['csvFile'] 			    = $fileCsv;            
            
            if(isset($result['diffSga'])):
                $dados['totEliminados'] 	= count($result['diffSga']);
            elseif(isset($diff)):
                $dados['totEliminados']	= count($diff);
            else:
                $dados['totEliminados']	= 0;
            endif;
        endif;
		
		$dados['logs'] = $sincronizacao->carregaLogsGestores();
        $this->loadTemplate('carga_gestor_usuario', $dados);
    }

    /*
     * Carrega tela de sincronização de gestor de programa
    */ 

    public function gestorPrograma()
    {
        if(isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa'])) {
            $empresa = new Home();
            $empresaId = addslashes($_POST['empresa']);

            $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
            $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
            $_SESSION['empresaid'] = $empresaId;


            header('Location: '.URL);
        }

        $dados = array();
        $sincronizacao = new Sincronizacao();               
        
        if(isset($_FILES['file']) && !empty($_FILES['file']) || isset($_POST['csvFile'])):
            setlocale(LC_TIME, 'pt_BR');
            date_default_timezone_set('America/Sao_Paulo');                                      

            $csvFile = (isset($_POST['csvFile']) && !empty($_POST['csvFile'])) ? BASE_PATH.'/cargas/sincronizando/'.$_POST['csvFile'] : $_FILES['file']['tmp_name'];
            $file = fopen($csvFile, 'r');
            // Pula cabeçalho
            //fgetcsv($file);
            
            // Percorre as linhas e inclui na query
            $nameFileBkp = "CGP_".str_replace(['-',' ',':'],['','_',''], date('Y-m-d H:i:s')).'.sql';
            $analise = $_POST['analise'];
            $totalCad = 0;
            $totalNotCad = 0;
            $query = '';
            $fileCsv = '';
            $query_values = '';
            $query_diff_sga = '';
            $dados['inicio'] = date('Y-m-d H:i:s');                
            $numLine = 1;
            $htmlTable = '';
            $csvStore = "GESTOR PROGRAMA IMPORTADOS;\n";
            $csvStore .= "codGestor;nomeGestor;codPrograma;descricaoPrograma\n";

            // die($analise);

            while(!feof($file)):
                $line = fgetcsv($file, 0, ';');

                // Valida se programa já existe na base
                $valid = $sincronizacao->validaCadastroGestorPrograma($line[0], $line[2]);

                // Se ocorrer um erro
                if (isset($valid['error'])) {
                    $this->helper->setAlert(
                        'error',
                        $valid['message'].$valid['error'],
                        'Carga/gestorPrograma'
                    );
                }

                // Se o retorno for false
                if(!$valid['return']) {
                    $totalNotCad++;
                    continue;
                }

                // Se não foi encontrado o relacionamento na base                
                $csvStore .= implode(';', $line)."\n";
                $query_values .= "(
                    '".addslashes(trim($line[1]))."', 
                    '".addslashes(trim($line[2]))."'),";                    

                if($analise == 1):
                    // Se não foi encontrado na base                
                    if($line[0] != 'idFuncao'):                    
                        if($analise == 1):
                            $htmlTable .= ' <tr style="background: #9fdbc4">';
                            $htmlTable .= '     <td>'.$numLine++.'</td>';
                            $htmlTable .= '     <td>'.trim($line[1]).'</td>';
                            $htmlTable .= '     <td>'.trim($line[2]).'</td>';
                            $htmlTable .= '     <td>'.trim($line[3]).'</td>';
                            $htmlTable .= ' </tr>';
                        endif;                                                
                    endif;                        
                endif;
                $totalCad++;
            endwhile;

            // die();

            fclose($file);        

            $csvStore .= "\n\n";
            
            // Cria a query para sincronização 
            // die($analise); 
            if(!empty($query_values)):
                if($analise == 1):
                    $fileCsv = 'CGP'.date('dmY_His').'.csv';
                    move_uploaded_file($_FILES['file']['tmp_name'], BASE_PATH.'/cargas/sincronizando/'.$fileCsv);
                    $dados['podeSincronizar'] = true;
                endif;

                // Se for para sincronizar

                // die($analise);
                if($analise == 0):
                    $result = $sincronizacao->sincronizaDados($query, 'z_sga_gest_mpr_dtsul', $nameFileBkp, $query_diff_sga);

                    // die(print_r($result));

                    if($result['return']):
                        // Grava o que foi eliminado no csv caso exista.
                        if(count($result['diffSga']) > 0):                
                            $csvStore .= "FUNÇÕES ELIMINADAS DO SGA;\n";
                            $csvStore .= "idFuncao;cod_funcao;descricao;\n";
                            foreach($result['diffSga'] as $diff):
                                foreach($diff as $val):
                                    $csvStore .= $val.";";
                                endforeach;
                                $csvStore .= "\n";
                            endforeach;                
                            $csvStore .= "\n\n";             
                        endif;

                        $fileDiff = BASE_PATH.'/cargas/sincronizados/'.str_replace('.zip','',$_POST['csvFile']).'_diff.csv';
                        // die($filleDiff . 'aiosio');
                        file_put_contents($fileDiff, $csvStore);
                        
                        move_uploaded_file(BASE_PATH.'/cargas/sincronizando/'.$_POST['csvFile'], BASE_PATH.'/cargas/sincronizados/'.$_POST['csvFile']);
                        unlink(BASE_PATH.'/cargas/sincronizando/'.$_POST['csvFile']);

                        // Grava o log da carga
                        $sincronizacao->gravaHistoricoSincronizacaoGestores(
                            $_SESSION['idUsrTotvs'],
                            $totalCad,
                            $totalNotCad,
                            $_POST['csvFile'],
                            $nameFileBkp,
                            $dados['inicio']
                        );
                        //die('fiz');
                        $this->helper->setAlert(
                            'success',
                            'Carga efetuada com sucesso!',
                            'Carga/gestorUsuario'
                        );
                    endif;                                                                     
                endif;                
            endif;                          
            
            $dados['fim']                   = date('H:i:s');
            $dados['totCadastrados']        = $totalCad;
            $dados['totNaoCadastrados']     = $totalNotCad;     
            $dados['htmlTable']             = $htmlTable;
            $dados['csvStore']              = $csvStore;
            $dados['csvFile']               = $fileCsv;            
            
            if(isset($result['diffSga'])):
                $dados['totEliminados']     = count($result['diffSga']);
            elseif(isset($diff)):
                $dados['totEliminados'] = count($diff);
            else:
                $dados['totEliminados'] = 0;
            endif;
        endif;
        
        $dados['logs'] = $sincronizacao->carregaLogsGestores();
        $this->loadTemplate('carga_gestor_programa', $dados);
    }

    /*
     * Carrega tela de sincronização de gestor de rotina
    */ 
    public function gestorRotina()
    {
        if(isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa'])) {
            $empresa = new Home();
            $empresaId = addslashes($_POST['empresa']);

            $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
            $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
            $_SESSION['empresaid'] = $empresaId;


            header('Location: '.URL);
        }

        $dados = array();
        $sincronizacao = new Sincronizacao();               
        
        if(isset($_FILES['file']) && !empty($_FILES['file']) || isset($_POST['csvFile'])):
            setlocale(LC_TIME, 'pt_BR');
            date_default_timezone_set('America/Sao_Paulo');                                      

            $csvFile = (isset($_POST['csvFile']) && !empty($_POST['csvFile'])) ? BASE_PATH.'/cargas/sincronizando/'.$_POST['csvFile'] : $_FILES['file']['tmp_name'];
            $file = fopen($csvFile, 'r');
            // Pula cabeçalho
            //fgetcsv($file);
            
            // Percorre as linhas e inclui na query
            $nameFileBkp = "CGR_".str_replace(['-',' ',':'],['','_',''], date('Y-m-d H:i:s')).'.sql';
            $analise = $_POST['analise'];
            $totalCad = 0;
            $totalNotCad = 0;
            $query = '';
            $fileCsv = '';
            $query_values = '';
            $query_diff_sga = '';
            $dados['inicio'] = date('Y-m-d H:i:s');                
            $numLine = 1;
            $htmlTable = '';
            $csvStore = "GESTOR ROTINA IMPORTADOS;\n";
            $csvStore .= "codGestor;nomeGestor;codRotina;descricaoRotina\n";

            // die($analise);

            while(!feof($file)):
                $line = fgetcsv($file, 0, ';');

                // Valida se programa já existe na base
                $valid = $sincronizacao->validaCadastroGestorRotina($line[0], $line[2]);

                // Se ocorrer um erro
                if (isset($valid['error'])) {
                    $this->helper->setAlert(
                        'error',
                        $valid['message'].$valid['error'],
                        'Carga/gestorRotina'
                    );
                }

                // Se o retorno for false
                if(!$valid['return']) {
                    $totalNotCad++;
                    continue;
                }

                // Se não foi encontrado o relacionamento na base                
                $csvStore .= implode(';', $line)."\n";
                $query_values .= "(
                    '".addslashes(trim($line[1]))."', 
                    '".addslashes(trim($line[2]))."'),";                    

                if($analise == 1):
                    // Se não foi encontrado na base                
                    if($line[0] != 'idFuncao'):                    
                        if($analise == 1):
                            $htmlTable .= ' <tr style="background: #9fdbc4">';
                            $htmlTable .= '     <td>'.$numLine++.'</td>';
                            $htmlTable .= '     <td>'.trim($line[1]).'</td>';
                            $htmlTable .= '     <td>'.trim($line[2]).'</td>';
                            $htmlTable .= '     <td>'.trim($line[3]).'</td>';
                            $htmlTable .= ' </tr>';
                        endif;                                                
                    endif;                        
                endif;
                $totalCad++;
            endwhile;

            // die();

            fclose($file);        

            $csvStore .= "\n\n";
            
            // Cria a query para sincronização 
            // die($analise); 
            if(!empty($query_values)):
                if($analise == 1):
                    $fileCsv = 'CGR'.date('dmY_His').'.csv';
                    move_uploaded_file($_FILES['file']['tmp_name'], BASE_PATH.'/cargas/sincronizando/'.$fileCsv);
                    $dados['podeSincronizar'] = true;
                endif;

                // Se for para sincronizar

                // die($analise);
                if($analise == 0):
                    $result = $sincronizacao->sincronizaDados($query, 'z_sga_gest_mpr_dtsul', $nameFileBkp, $query_diff_sga);

                    // die(print_r($result));

                    if($result['return']):
                        // Grava o que foi eliminado no csv caso exista.
                        if(count($result['diffSga']) > 0):                
                            $csvStore .= "FUNÇÕES ELIMINADAS DO SGA;\n";
                            $csvStore .= "idFuncao;cod_funcao;descricao;\n";
                            foreach($result['diffSga'] as $diff):
                                foreach($diff as $val):
                                    $csvStore .= $val.";";
                                endforeach;
                                $csvStore .= "\n";
                            endforeach;                
                            $csvStore .= "\n\n";             
                        endif;

                        $fileDiff = BASE_PATH.'/cargas/sincronizados/'.str_replace('.zip','',$_POST['csvFile']).'_diff.csv';
                        // die($filleDiff . 'aiosio');
                        file_put_contents($fileDiff, $csvStore);
                        
                        move_uploaded_file(BASE_PATH.'/cargas/sincronizando/'.$_POST['csvFile'], BASE_PATH.'/cargas/sincronizados/'.$_POST['csvFile']);
                        unlink(BASE_PATH.'/cargas/sincronizando/'.$_POST['csvFile']);

                        // Grava o log da carga
                        $sincronizacao->gravaHistoricoSincronizacaoGestores(
                            $_SESSION['idUsrTotvs'],
                            $totalCad,
                            $totalNotCad,
                            $_POST['csvFile'],
                            $nameFileBkp,
                            $dados['inicio']
                        );
                        //die('fiz');
                        $this->helper->setAlert(
                            'success',
                            'Carga efetuada com sucesso!',
                            'Carga/gestorRotina'
                        );
                    endif;                                                                     
                endif;                
            endif;                          
            
            $dados['fim']                   = date('H:i:s');
            $dados['totCadastrados']        = $totalCad;
            $dados['totNaoCadastrados']     = $totalNotCad;     
            $dados['htmlTable']             = $htmlTable;
            $dados['csvStore']              = $csvStore;
            $dados['csvFile']               = $fileCsv;            
            
            if(isset($result['diffSga'])):
                $dados['totEliminados']     = count($result['diffSga']);
            elseif(isset($diff)):
                $dados['totEliminados'] = count($diff);
            else:
                $dados['totEliminados'] = 0;
            endif;
        endif;
        
        $dados['logs'] = $sincronizacao->carregaLogsGestores();
        $this->loadTemplate('carga_gestor_rotina', $dados);
    }

    /*
     * Carrega tela de sincronização de gestor de modulo
    */ 
    public function gestorModulo()
    {
        if(isset($_POST['ok']) && !empty(['ok']) && isset($_POST['empresa']) && !empty(['empresa'])) {
            $empresa = new Home();
            $empresaId = addslashes($_POST['empresa']);

            $dados['descEmpresa'] = $empresa->carregaDescEmpresa($empresaId);
            $_SESSION['empresaDesc'] = $dados['descEmpresa'][0];
            $_SESSION['empresaid'] = $empresaId;


            header('Location: '.URL);
        }

        $dados = array();
        $sincronizacao = new Sincronizacao();               
        
        if(isset($_FILES['file']) && !empty($_FILES['file']) || isset($_POST['csvFile'])):
            setlocale(LC_TIME, 'pt_BR');
            date_default_timezone_set('America/Sao_Paulo');                                      

            $csvFile = (isset($_POST['csvFile']) && !empty($_POST['csvFile'])) ? BASE_PATH.'/cargas/sincronizando/'.$_POST['csvFile'] : $_FILES['file']['tmp_name'];
            $file = fopen($csvFile, 'r');
            // Pula cabeçalho
            //fgetcsv($file);
            
            // Percorre as linhas e inclui na query
            $nameFileBkp = "CGM_".str_replace(['-',' ',':'],['','_',''], date('Y-m-d H:i:s')).'.sql';
            $analise = $_POST['analise'];
            $totalCad = 0;
            $totalNotCad = 0;
            $query = '';
            $fileCsv = '';
            $query_values = '';
            $query_diff_sga = '';
            $dados['inicio'] = date('Y-m-d H:i:s');                
            $numLine = 1;
            $htmlTable = '';
            $csvStore = "GESTOR MODULO IMPORTADOS;\n";
            $csvStore .= "codGestor;nomeGestor;codMdulo;descricaoMdulo\n";

            // die($analise);

            while(!feof($file)):
                $line = fgetcsv($file, 0, ';');

                // Valida se programa já existe na base
                $valid = $sincronizacao->validaCadastroGestorModulo($line[0], $line[2]);

                // Se ocorrer um erro
                if (isset($valid['error'])) {
                    $this->helper->setAlert(
                        'error',
                        $valid['message'].$valid['error'],
                        'Carga/gestorRotina'
                    );
                }

                // Se o retorno for false
                if(!$valid['return']) {
                    $totalNotCad++;
                    continue;
                }

                // Se não foi encontrado o relacionamento na base                
                $csvStore .= implode(';', $line)."\n";
                $query_values .= "(
                    '".addslashes(trim($line[1]))."', 
                    '".addslashes(trim($line[2]))."'),";                    

                if($analise == 1):
                    // Se não foi encontrado na base                
                    if($line[0] != 'idFuncao'):                    
                        if($analise == 1):
                            $htmlTable .= ' <tr style="background: #9fdbc4">';
                            $htmlTable .= '     <td>'.$numLine++.'</td>';
                            $htmlTable .= '     <td>'.trim($line[1]).'</td>';
                            $htmlTable .= '     <td>'.trim($line[2]).'</td>';
                            $htmlTable .= '     <td>'.trim($line[3]).'</td>';
                            $htmlTable .= ' </tr>';
                        endif;                                                
                    endif;                        
                endif;
                $totalCad++;
            endwhile;

            // die();

            fclose($file);        

            $csvStore .= "\n\n";
            
            // Cria a query para sincronização 
            // die($analise); 
            if(!empty($query_values)):
                if($analise == 1):
                    $fileCsv = 'CGM'.date('dmY_His').'.csv';
                    move_uploaded_file($_FILES['file']['tmp_name'], BASE_PATH.'/cargas/sincronizando/'.$fileCsv);
                    $dados['podeSincronizar'] = true;
                endif;

                // Se for para sincronizar

                // die($analise);
                if($analise == 0):
                    $result = $sincronizacao->sincronizaDados($query, 'z_sga_gest_mpr_dtsul', $nameFileBkp, $query_diff_sga);

                    // die(print_r($result));

                    if($result['return']):
                        // Grava o que foi eliminado no csv caso exista.
                        if(count($result['diffSga']) > 0):                
                            $csvStore .= "FUNÇÕES ELIMINADAS DO SGA;\n";
                            $csvStore .= "idFuncao;cod_funcao;descricao;\n";
                            foreach($result['diffSga'] as $diff):
                                foreach($diff as $val):
                                    $csvStore .= $val.";";
                                endforeach;
                                $csvStore .= "\n";
                            endforeach;                
                            $csvStore .= "\n\n";             
                        endif;

                        $fileDiff = BASE_PATH.'/cargas/sincronizados/'.str_replace('.zip','',$_POST['csvFile']).'_diff.csv';
                        // die($filleDiff . 'aiosio');
                        file_put_contents($fileDiff, $csvStore);
                        
                        move_uploaded_file(BASE_PATH.'/cargas/sincronizando/'.$_POST['csvFile'], BASE_PATH.'/cargas/sincronizados/'.$_POST['csvFile']);
                        unlink(BASE_PATH.'/cargas/sincronizando/'.$_POST['csvFile']);

                        // Grava o log da carga
                        $sincronizacao->gravaHistoricoSincronizacaoGestores(
                            $_SESSION['idUsrTotvs'],
                            $totalCad,
                            $totalNotCad,
                            $_POST['csvFile'],
                            $nameFileBkp,
                            $dados['inicio']
                        );
                        //die('fiz');
                        $this->helper->setAlert(
                            'success',
                            'Carga efetuada com sucesso!',
                            'Carga/gestorRotina'
                        );
                    endif;                                                                     
                endif;                
            endif;                          
            
            $dados['fim']                   = date('H:i:s');
            $dados['totCadastrados']        = $totalCad;
            $dados['totNaoCadastrados']     = $totalNotCad;     
            $dados['htmlTable']             = $htmlTable;
            $dados['csvStore']              = $csvStore;
            $dados['csvFile']               = $fileCsv;            
            
            if(isset($result['diffSga'])):
                $dados['totEliminados']     = count($result['diffSga']);
            elseif(isset($diff)):
                $dados['totEliminados'] = count($diff);
            else:
                $dados['totEliminados'] = 0;
            endif;
        endif;
        
        $dados['logs'] = $sincronizacao->carregaLogsGestores();
        $this->loadTemplate('carga_gestor_modulo', $dados);
    }
}
