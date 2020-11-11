<?php
/**
 * Created by Rodrigo Gomes do Nascimento.
 * User: a2
 * Date: 04/01/2019
 * Time: 12:07
 */

class SincronizacaoController extends Controller
{
    /**
     * Carrega view de sincronizacao de programas
     * Caso houver a variável $_FILES. Valida a planilha.
     */
    public function index(){
    }

	/**
	* Carrega tela de sincronização de programas
	*/
    public function programas()
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
		
		setlocale(LC_TIME, 'pt_BR');
        date_default_timezone_set('America/Sao_Paulo');
		
        if(isset($_FILES['file']) && !empty($_FILES['file'])):            
            $query = '';
            $query_values = '';
            $inicio = date('H:i:s');

            //$csvFile = 'ser004b.csv';
            $csvFile = $_FILES['file']['tmp_name'];
            $file = fopen($csvFile, 'r');
			// Pula cabeçalho
			fgetcsv($file);
			
            // Percorre as linhas e inclui na query
            $totProgJaCad = 0;
            $totProgCad = 0;
            $numLine = 1;
            $htmlTable = '';

            while(!feof($file)):
                $line = fgetcsv($file, 0, ';');

                // Valida se não é cabeçalho
                if(isset($line[0]) && $line[0] != 'Programa'):
                    // Valida se programa já existe na base
                    $line = explode(',', $line[0]);

                    if($line[1] == '' || $line[2] == '' || $line[3] == '' || $line[4] == '' || $line[5] == '' || $line[9] == '' || $line[10] == '' || $line[11] == '' || $line[12] == ''):
                        unset($_SESSION['query_sincroniza_dados']);
                        $this->helper->setAlert(
                            'error',
                            'Erro na disposição dos dados no arquivo. <br>Verifique as informações e tente novamente, por favor!',
                            'sincronizacao/programas'
                        );
                        break;
                    endif;

                    $valid = $sincronizacao->validaCadastroProgramas($line[1]);

                    // Se foi encontrado na base
                    if($valid['return']):
                        $htmlTable .= '	<tr style="background: #d4ecd1">';
                        $htmlTable .= '		<td style="width: 8%">'.$numLine++.'</td>';
                        $htmlTable .= '		<td style="width: 20%">'.$line[1].'</td>';
                        $htmlTable .= '		<td>'.$line[2].'</td>';
                        $htmlTable .= '		<td>Sim</td>';
                        $totProgJaCad++;
                        continue;
                    endif;

                    // Se não foi encontrado o relacionamento na base
                    $query_values .= "(
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
                        '".addslashes($line[12])."'),";

                    // Se não foi encontrado na base
                    if($line[0] != 'Programa'):
                        $htmlTable .= '	<tr style="background: #ecd1d1">';
                        $htmlTable .= '		<td>'.$numLine++.'</td>';
                        $htmlTable .= '		<td>'.$line[1].'</td>';
                        $htmlTable .= '		<td>'.$line[2].'</td>';
                        $htmlTable .= '		<td>Não</td>';
                        $htmlTable .= '	</tr>';

                        $totProgCad++;
                    endif;
                endif;
            endwhile;

            fclose($file);

            // Cria a query para sincronização
            if(!empty($query_values)):
                $query .= "
					INSERT INTO
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
						) VALUES 
				" . $query_values;
                $query = substr(trim($query), 0, -1);

                $_SESSION['query_sincroniza_dados'] = $query;
                $dados['podeSincronizar'] = true;
                //$this->helper->debug($query, true);
            endif;
			//echo "<pre>";
			//die($query);
            $dados['inicio'] = $inicio;
            $dados['fim']    = date('H:i:s');
            $dados['totCadastrados'] = $totProgJaCad;
            $dados['totNaoCadastrados'] = $totProgCad;
            $dados['htmlTable'] = $htmlTable;
        endif;
		
		$dados['logs'] = $sincronizacao->carregaLogs('z_sga_programas');		
        $this->loadTemplate('sincronizacao_programas', $dados);
    }   
	
	/**
	* Executa sincronização, cria dump da tabela z_sga_programas e grava log.
	*/
    public function sincronizaProgramas()
    {
		$sincronizacao = new Sincronizacao();
		
		// Inicia transaction
		$this->db->beginTransaction();
		
		// Realiza o backup da tabela z_sga_programas
		$resBackup = $sincronizacao->backup_tables($tables = 'z_sga_programas');
		if($resBackup['return']):			
			$resSinc = $sincronizacao->sincronizaDados();			
			if($resSinc['return']):
				// Grava log da sincronização
				$resLog = $sincronizacao->gravaHistoricoSincronizacao($resBackup['backup'], 'z_sga_programas', $resSinc['rowCount']);
				if($resLog['return']):
				
					// Elimina variável com a query executada.
					unset($_SESSION['query_sincroniza_dados']);									
					
					// Executa commit
					$this->db->commit();
					
					$this->helper->setAlert(
						'success',
						'Sincronia efetuada com sucesso!',
						'sincronizacao/programas'
					);
				else:
					// remove arquivo de backup
					unlink('dumps/'.$resBackup['backup']);
					
					// Executa rollback
					$this->db->rollback();
					
					$this->helper->setAlert(
						'error',
						"Erro ao gravar log de sincronização! <br>". $resLog['error'] . "<br>Favor tentar novamente.",
						'sincronizacao/programas'
					);									
				endif;
			else:			
				// remove arquivo de backup
				unlink('dumps/'.$resBackup['backup']);								
				
				// Executa rollback
				$this->db->rollback();
				
				$this->helper->setAlert(
					'error',
					"Erro ao realizar sincronização! <br>". $resSinc['error'] . "<br>Favor tentar novamente.",
					'sincronizacao/programas'
				);											
			endif;					
		else:
			$this->helper->setAlert(
				'error',
				"Erro ao realizar dump da tabela! \n". $$resBackup['error'],
				'sincronizacao/programas'
			);
		endif;		       
    }
    
	/**
     * Carrega tela de sincronização de grupos x programas
     */
    public function programasEmpresas()
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
		
		setlocale(LC_TIME, 'pt_BR');
        date_default_timezone_set('America/Sao_Paulo');
		
        if(isset($_FILES['file']) && !empty($_FILES['file'])):            
            $query = '';
            $query_values = '';
            $inicio = date('H:i:s');

            //$csvFile = 'ser004b.csv';
            $csvFile = $_FILES['file']['tmp_name'];
            $file = fopen($csvFile, 'r');
			// Pula cabeçalho
			fgetcsv($file);
			
            // Percorre as linhas e inclui na query
            $totProgJaCad = 0;
            $totProgCad = 0;
            $numLine = 0;
            $htmlTable = '';
			
            while(!feof($file)):
                $line = fgetcsv($file, 0, ';');

                // Valida se não é cabeçalho
                if(isset($line[0]) && $line[0] != 'idGrupoPrograma'):
                    // Valida se relação já existe na base
                    $line = explode(',', $line[0]);

                    if($line[1] == ''):
                        unset($_SESSION['query_sincroniza_dados']);
                        $this->helper->setAlert(
                            'error',
                            'Erro na disposição dos dados no arquivo. <br>Verifique as informações e tente novamente, por favor!',
                            'sincronizacao/programasEmpresas'
                        );
                        break;
                    endif;

                    $valid = $sincronizacao->validaCadastroProgramasEmpresas($line[1], $_SESSION['empresaid']);

                    if($valid['return'] === 'error'):
                        unset($_SESSION['query_sincroniza_dados']);
                        $dados['podeSincronizar'] = false;
                        $this->helper->setAlert(
                            'error',
                            $valid['error'],
                            'sincronizacao/programasEmpresas'
                        );
                        break;
                    // Se foi encontrado na base
                    elseif($valid['return']):
                        $htmlTable .= '	<tr style="background: #d4ecd1">';
                        $htmlTable .= '		<td style="width: 8%">'.$numLine++.'</td>';
                        $htmlTable .= '		<td style="width: 20%">'.$line[1].'</td>';
						$htmlTable .= '		<td style="width: 20%">'.$line[2].'</td>';                        
                        $htmlTable .= '		<td>Sim</td>';
                        $totProgJaCad++;
                        continue;
                    endif;

                    // Se não foi encontrado o relacionamento na base
                    $query_values .= "(
                        '".$valid['idPrograma']."',                                                 
                        '".$_SESSION['empresaid']."'),";

                    // Se não foi encontrado na base
                    if($line[0] != 'idGrupoPrograma'):
                        $htmlTable .= '	<tr style="background: #ecd1d1">';
                        $htmlTable .= '		<td style="width: 8%">'.$numLine++.'</td>';
                        $htmlTable .= '		<td style="width: 20%">'.$line[1].'</td>';
						$htmlTable .= '		<td style="width: 20%">'.$line[2].'</td>';                        
                        $htmlTable .= '		<td>Não</td>';
                        $htmlTable .= '	</tr>';

                        $totProgCad++;
                    endif;
                endif;
            endwhile;

            fclose($file);

            // Cria a query para sincronização
            if(!empty($query_values)):
                $query .= "
					INSERT INTO
						z_sga_programa_empresa (
							`idPrograma`,
							`idEmpresa`
						) VALUES 
				" . $query_values;
                $query = substr(trim($query), 0, -1);

                $_SESSION['query_sincroniza_dados'] = $query;
                $dados['podeSincronizar'] = true;
				//$this->helper->debug($query, true);
            endif;
			
            $dados['inicio'] = $inicio;
            $dados['fim']    = date('H:i:s');
            $dados['totCadastrados'] = $totProgJaCad;
            $dados['totNaoCadastrados'] = $totProgCad;
            $dados['htmlTable'] = $htmlTable;
        endif;
        
        $dados['logs'] = $sincronizacao->carregaLogs('z_sga_programa_empresa');
        $this->loadTemplate('sincronizacao_programas_empresas', $dados);
    }

	/**
	* Executa sincronização, cria dump da tabela z_sga_programa_empresa e grava log.
	*/
    public function sincronizaProgramasEmpresas()
    {
		$sincronizacao = new Sincronizacao();
		
		// Inicia transaction
		$this->db->beginTransaction();
		
		// Realiza o backup da tabela z_sga_programas
		$resBackup = $sincronizacao->backup_tables($tables = 'z_sga_grupo_programa');
		if($resBackup['return']):			
			$resSinc = $sincronizacao->sincronizaDados();			
			if($resSinc['return']):
				// Grava log da sincronização
				$resLog = $sincronizacao->gravaHistoricoSincronizacao($resBackup['backup'], 'z_sga_programa_empresa', $resSinc['rowCount']);
				if($resLog['return']):
				
					// Elimina variável com a query executada.
					unset($_SESSION['query_sincroniza_dados']);									
					
					// Executa commit
					$this->db->commit();
					
					$this->helper->setAlert(
						'success',
						'Sincronia efetuada com sucesso!',
						'sincronizacao/programasEmpresas'
					);
				else:
					// remove arquivo de backup
					unlink('dumps/'.$resBackup['backup']);
					
					// Executa rollback
					$this->db->rollback();
					
					$this->helper->setAlert(
						'error',
						"Erro ao gravar log de sincronização! <br>". $resLog['error'] . "<br>Favor tentar novamente.",
						'sincronizacao/programasEmpresas'
					);
				endif;
			else:			
				// remove arquivo de backup
				unlink('dumps/'.$resBackup['backup']);								
				
				// Executa rollback
				$this->db->rollback();
				
				$this->helper->setAlert(
					'error',
					"Erro ao realizar sincronização! <br>". $resSinc['error'] . "<br>Favor tentar novamente.",
					'sincronizacao/programasEmpresas'
				);											
			endif;					
		else:
			$this->helper->setAlert(
				'error',
				"Erro ao realizar dump da tabela! \n". $$resBackup['error'],
				'sincronizacao/programasEmpresas'
			);
		endif;		       
    }
	
	/**
     * Carrega tela de sincronização de grupos x programas
     */
    public function gruposProgramas()
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
		
		setlocale(LC_TIME, 'pt_BR');
        date_default_timezone_set('America/Sao_Paulo');
		
        if(isset($_FILES['file']) && !empty($_FILES['file'])):            
            $query = '';
            $query_values = '';
            $inicio = date('H:i:s');

            //$csvFile = 'ser004b.csv';
            $csvFile = $_FILES['file']['tmp_name'];
            $file = fopen($csvFile, 'r');
			// Pula cabeçalho
			fgetcsv($file);
			
            // Percorre as linhas e inclui na query
            $totProgJaCad = 0;
            $totProgCad = 0;
            $numLine = 0;
            $htmlTable = '';
			
            while(!feof($file)):
                $line = fgetcsv($file, 0, ';');

                // Valida se não é cabeçalho
                if(isset($line[0]) && $line[0] != 'Programa'):
                    // Valida se programa já existe na base
                    $line = explode(',', $line[0]);

                    if($line[1] == '' || $line[4] == ''):
                        unset($_SESSION['query_sincroniza_dados']);
                        $this->helper->setAlert(
                            'error',
                            'Erro na disposição dos dados no arquivo. <br>Verifique as informações e tente novamente, por favor!',
                            'sincronizacao/gruposProgramas'
                        );
                        break;
                    endif;

                    $valid = $sincronizacao->validaCadastroGruposProgramas($line[1], $line[4]);

                    // Se não foi encontrado algum grupo ou programa
                    if($valid['return'] === 'error'):
                        unset($_SESSION['query_sincroniza_dados']);
                        $dados['podeSincronizar'] = false;
                        $this->helper->setAlert(
                            'error',
                            $valid['error'],
                            'sincronizacao/gruposProgramas'
                        );
                        break;
                    // Se foi encontrado na base
                    elseif($valid['return']):
                        $htmlTable .= '	<tr style="background: #d4ecd1">';
                        $htmlTable .= '		<td style="width: 8%">'.$numLine++.'</td>';
                        $htmlTable .= '		<td style="width: 20%">'.$line[1].'</td>';
						$htmlTable .= '		<td style="width: 20%">'.$line[3].'</td>';
                        $htmlTable .= '		<td>'.$line[4].'</td>';
                        $htmlTable .= '		<td>Sim</td>';
                        $totProgJaCad++;
                        continue;
                    endif;

                    // Se não foi encontrado o relacionamento na base
                    $query_values .= "(
                        '".addslashes($valid['codGrupo'])."', 
                        '".addslashes($valid['nomeGrupo'])."',
                        '".addslashes($valid['gestor'])."',						    
                        '".addslashes($valid['codPrograma'])."',
                        '".addslashes($valid['idGrupo'])."',
                        '".addslashes($valid['idPrograma'])."'),";

                    // Se não foi encontrado relacionamento na base
                    if($line[0] != 'Programa'):
                        $htmlTable .= '	<tr style="background: #ecd1d1">';
                        $htmlTable .= '		<td style="width: 8%">'.$numLine++.'</td>';
                        $htmlTable .= '		<td style="width: 20%">'.$line[1].'</td>';
						$htmlTable .= '		<td style="width: 20%">'.$line[2].'</td>';
                        $htmlTable .= '		<td>'.$line[4].'</td>';
                        $htmlTable .= '		<td>Não</td>';
                        $htmlTable .= '	</tr>';

                        $totProgCad++;
                    endif;
                endif;
            endwhile;

            fclose($file);

            // Cria a query para sincronização
            if(!empty($query_values)):
                $query .= "
					INSERT INTO
						z_sga_grupo_programa (							
							`cod_grupo`,
							`nome_grupo`,
							`gestor`,
							`cod_programa`,
							`idGrupo`,
							`idPrograma`
						) VALUES						    
				" . $query_values;
                $query = substr(trim($query), 0, -1);
                //$this->helper->debug($query, true);
                $_SESSION['query_sincroniza_dados'] = $query;
                $dados['podeSincronizar'] = true;
            endif;
			
            $dados['inicio'] = $inicio;
            $dados['fim']    = date('H:i:s');
            $dados['totCadastrados'] = $totProgJaCad;
            $dados['totNaoCadastrados'] = $totProgCad;
            $dados['htmlTable'] = $htmlTable;
        endif;
        
        $dados['logs'] = $sincronizacao->carregaLogs('z_sga_grupo_programa');
        $this->loadTemplate('sincronizacao_grupos_programas', $dados);
    }

	/**
	* Executa sincronização, cria dump da tabela z_sga_grupo_programa e grava log.
	*/
    public function sincronizaGruposProgramas()
    {
		$sincronizacao = new Sincronizacao();
		
		// Inicia transaction
		$this->db->beginTransaction();
		
		// Realiza o backup da tabela z_sga_programas
		$resBackup = $sincronizacao->backup_tables($tables = 'z_sga_grupo_programa');
		if($resBackup['return']):			
			$resSinc = $sincronizacao->sincronizaDados();			
			if($resSinc['return']):
				// Grava log da sincronização
				$resLog = $sincronizacao->gravaHistoricoSincronizacao($resBackup['backup'], 'z_sga_grupo_programa', $resSinc['rowCount']);
				if($resLog['return']):
				
					// Elimina variável com a query executada.
					unset($_SESSION['query_sincroniza_dados']);									
					
					// Executa commit
					$this->db->commit();
					
					$this->helper->setAlert(
						'success',
						'Sincronia efetuada com sucesso!',
						'sincronizacao/gruposProgramas'
					);
				else:
					// remove arquivo de backup
					unlink('dumps/'.$resBackup['backup']);
					
					// Executa rollback
					$this->db->rollback();
					
					$this->helper->setAlert(
						'error',
						"Erro ao gravar log de sincronização! <br>". $resLog['error'] . "<br>Favor tentar novamente.",
						'sincronizacao/gruposProgramas'
					);
				endif;
			else:			
				// remove arquivo de backup
				unlink('dumps/'.$resBackup['backup']);								
				
				// Executa rollback
				$this->db->rollback();
				
				$this->helper->setAlert(
					'error',
					"Erro ao realizar sincronização! <br>". $resSinc['error'] . "<br>Favor tentar novamente.",
					'sincronizacao/gruposProgramas'
				);											
			endif;					
		else:
			$this->helper->setAlert(
				'error',
				"Erro ao realizar dump da tabela! \n". $$resBackup['error'],
				'sincronizacao/gruposProgramas'
			);
		endif;		       
    }

	/**
     * Carrega tela de sincronização de grupos x usuários
     */
    public function gruposUsuarios()
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
		
		setlocale(LC_TIME, 'pt_BR');
        date_default_timezone_set('America/Sao_Paulo');
		
        if(isset($_FILES['file']) && !empty($_FILES['file'])):            
            $query = '';
            $query_values = '';
            $inicio = date('H:i:s');

            //$csvFile = 'ser004b.csv';
            $csvFile = $_FILES['file']['tmp_name'];
            $file = fopen($csvFile, 'r');
			// Pula cabeçalho
			fgetcsv($file);
			
            // Percorre as linhas e inclui na query
            $totProgJaCad = 0;
            $totProgCad = 0;
            $numLine = 0;
            $htmlTable = '';
			
            while(!feof($file)):
                $line = fgetcsv($file, 0, ';');

                // Valida se não é cabeçalho
                if(isset($line[0]) && $line[0] != 'Programa'):
                    // Valida se programa já existe na base
                    $line = explode(',', $line[0]);

                    if($line[1] == '' || $line[4] == ''):
                        $htmlTable = '';
                        $this->helper->setAlert(
                            'error',
                            'Erro na disposição dos dados no arquivo. <br>Verifique as informações e tente novamente, por favor!'
                        );
                        break;
                    endif;

                    $valid = $sincronizacao->validaCadastroGruposUsuarios($line[1], $line[4]);

                    // Se não foi encontrado algum grupo ou usuário
                    if($valid['return'] === 'error'):
                        unset($_SESSION['query_sincroniza_dados']);
                        $this->helper->setAlert(
                            'error',
                            $valid['error'],
                            'sincronizacao/gruposUsuarios'
                        );
                        break;
                    // Se foi encontrado relacionamento na base
                    elseif($valid['return']):
                        $htmlTable .= '	<tr style="background: #d4ecd1">';
                        $htmlTable .= '		<td style="width: 8%">'.$numLine++.'</td>';
                        $htmlTable .= '		<td style="width: 20%">'.$line[1].'</td>';
						$htmlTable .= '		<td style="width: 20%">'.$line[2].'</td>';
                        $htmlTable .= '		<td>'.$line[4].'</td>';
                        $htmlTable .= '		<td>Sim</td>';
                        $totProgJaCad++;
                        continue;
                    endif;

                    // Se não foi encontrado o relacionamento na base
                    $query_values .= "(
                        '".addslashes($valid['codGrupo'])."', 
                        '".addslashes($valid['nomeGrupo'])."',
                        '".addslashes($valid['gestor'])."',						    
                        '".addslashes($valid['codUsuario'])."',
                        '".addslashes($valid['idGrupo'])."',
                        '".addslashes($valid['idUsuario'])."'),";

                    // Se não foi encontrado na base
                    if($line[0] != 'Programa'):
                        $htmlTable .= '	<tr style="background: #ecd1d1">';
                        $htmlTable .= '		<td style="width: 8%">'.$numLine++.'</td>';
                        $htmlTable .= '		<td style="width: 20%">'.$line[1].'</td>';
						$htmlTable .= '		<td style="width: 20%">'.$line[2].'</td>';
                        $htmlTable .= '		<td>'.$line[4].'</td>';
                        $htmlTable .= '		<td>Não</td>';
                        $htmlTable .= '	</tr>';

                        $totProgCad++;
                    endif;
                endif;
            endwhile;

            fclose($file);

            // Cria a query para sincronização
            if(!empty($query_values)):
                $query .= "
					INSERT INTO
						z_sga_grupos (							
							`cod_grupo`,
							`desc_grupo`,
							`gestor`,
							`cod_usuario`,
							`idGrupo`,
							`idUsuario`
						) VALUES 
				" . $query_values;
                $query = substr(trim($query), 0, -1);

                $_SESSION['query_sincroniza_dados'] = $query;
                $dados['podeSincronizar'] = true;
                //$this->helper->debug($query, true);
            endif;
			
            $dados['inicio'] = $inicio;
            $dados['fim']    = date('H:i:s');
            $dados['totCadastrados'] = $totProgJaCad;
            $dados['totNaoCadastrados'] = $totProgCad;
            $dados['htmlTable'] = $htmlTable;
        endif;
        
        $dados['logs'] = $sincronizacao->carregaLogs('z_sga_grupos');
        $this->loadTemplate('sincronizacao_grupos_usuarios', $dados);
    }

	/**
	* Executa sincronização, cria dump da tabela z_sga_grupos e grava log.
	*/
    public function sincronizaGruposUsuarios()
    {
		$sincronizacao = new Sincronizacao();
		
		// Inicia transaction
		$this->db->beginTransaction();
		
		// Realiza o backup da tabela z_sga_programas
		$resBackup = $sincronizacao->backup_tables($tables = 'z_sga_grupos');
		if($resBackup['return']):			
			$resSinc = $sincronizacao->sincronizaDados();			
			if($resSinc['return']):
				// Grava log da sincronização
				$resLog = $sincronizacao->gravaHistoricoSincronizacao($resBackup['backup'], 'z_sga_grupos', $resSinc['rowCount']);
				if($resLog['return']):
				
					// Elimina variável com a query executada.
					unset($_SESSION['query_sincroniza_dados']);									
					
					// Executa commit
					$this->db->commit();
					
					$this->helper->setAlert(
						'success',
						'Sincronia efetuada com sucesso!',
						'sincronizacao/gruposUsuarios'
					);
				else:
					// remove arquivo de backup
					unlink('dumps/'.$resBackup['backup']);
					
					// Executa rollback
					$this->db->rollback();
					
					$this->helper->setAlert(
						'error',
						"Erro ao gravar log de sincronização! <br>". $resLog['error'] . "<br>Favor tentar novamente.",
						'sincronizacao/gruposUsuarios'
					);
				endif;
			else:			
				// remove arquivo de backup
				unlink('dumps/'.$resBackup['backup']);								
				
				// Executa rollback
				$this->db->rollback();
				
				$this->helper->setAlert(
					'error',
					"Erro ao realizar sincronização! <br>". $resSinc['error'] . "<br>Favor tentar novamente.",
					'sincronizacao/gruposUsuarios'
				);											
			endif;					
		else:
			$this->helper->setAlert(
				'error',
				"Erro ao realizar dump da tabela! \n". $$resBackup['error'],
				'sincronizacao/gruposUsuarios'
			);
		endif;		       
    }

	/**
	* Carrega tela de sincronização de grupos
	*/
    public function grupos()
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
		
		setlocale(LC_TIME, 'pt_BR');
        date_default_timezone_set('America/Sao_Paulo');
		
        if(isset($_FILES['file']) && !empty($_FILES['file'])):            
            $query = '';
            $query_values = '';
            $inicio = date('H:i:s');

            //$csvFile = 'ser004b.csv';
            $csvFile = $_FILES['file']['tmp_name'];
            $file = fopen($csvFile, 'r');
			// Pula cabeçalho
			fgetcsv($file);
			
            // Percorre as linhas e inclui na query
            $totProgJaCad = 0;
            $totProgCad = 0;
            $numLine = 1;
            $htmlTable = '';

            while(!feof($file)):
                $line = fgetcsv($file, 0, ';');

                // Valida se não é cabeçalho
                if(isset($line[0]) && $line[0] != 'idGrupo'):
                    // Valida se grupo já existe na base
                    $line = explode(',', $line[0]);

                    if($line[1] == ''):
                        unset($_SESSION['query_sincroniza_dados']);
                        $this->helper->setAlert(
                            'error',
                            'Erro na disposição dos dados no arquivo. <br>Verifique as informações e tente novamente, por favor!',
                            'sincronizacao/grupos'
                        );
                        break;
                    endif;

                    $valid = $sincronizacao->validaCadastroGrupos($line[1]);

                    // Se foi encontrado na base
                    if($valid['return']):
                        $htmlTable .= '	<tr style="background: #d4ecd1">';
                        $htmlTable .= '		<td style="width: 8%">'.$numLine++.'</td>';
                        $htmlTable .= '		<td style="width: 20%">'.$line[1].'</td>';
                        $htmlTable .= '		<td>'.$line[2].'</td>';
                        $htmlTable .= '		<td>Sim</td>';
                        $totProgJaCad++;
                        continue;
                    endif;

                    // Se não foi encontrado na base
                    // Se não foi encontrado o relacionamento na base
                    $query_values .= "(
                        '".addslashes($line[1])."', 
                        '".addslashes($line[2])."',                        
                        '".addslashes($_SESSION['empresaid'])."'),";

                    // Se não foi encontrado na base
                    if($line[0] != 'idGrupo'):
                        $htmlTable .= '	<tr style="background: #ecd1d1">';
                        $htmlTable .= '		<td>'.$numLine++.'</td>';
                        $htmlTable .= '		<td>'.$line[1].'</td>';
                        $htmlTable .= '		<td>'.$line[2].'</td>';
                        $htmlTable .= '		<td>Não</td>';
                        $htmlTable .= '	</tr>';

                        $totProgCad++;
                    endif;
                endif;
            endwhile;

            fclose($file);

            // Cria a query para sincronização
            if(!empty($query_values)):
                $query .= "
					INSERT INTO
						z_sga_grupo (														
							`idLegGrupo`,
							`descAbrev`,							
							`idEmpresa`
						) VALUES 
				" . $query_values;
                $query = substr(trim($query), 0, -1);

                $_SESSION['query_sincroniza_dados'] = $query;
                $dados['podeSincronizar'] = true;
                //$this->helper->debug($query, true);
            endif;
			//echo "<pre>";
			//die($query);
            $dados['inicio'] = $inicio;
            $dados['fim']    = date('H:i:s');
            $dados['totCadastrados'] = $totProgJaCad;
            $dados['totNaoCadastrados'] = $totProgCad;
            $dados['htmlTable'] = $htmlTable;
        endif;
		
		$dados['logs'] = $sincronizacao->carregaLogs('z_sga_grupo');		
        $this->loadTemplate('sincronizacao_grupos', $dados);
    }

	/**
	* Executa sincronização, cria dump da tabela z_sga_grupo e grava log.
	*/
    public function sincronizaGrupos()
    {
		$sincronizacao = new Sincronizacao();
		
		// Inicia transaction
		$this->db->beginTransaction();
		
		// Realiza o backup da tabela z_sga_programas
		$resBackup = $sincronizacao->backup_tables($tables = 'z_sga_grupo');
		if($resBackup['return']):			
			$resSinc = $sincronizacao->sincronizaDados();			
			if($resSinc['return']):
				// Grava log da sincronização
				$resLog = $sincronizacao->gravaHistoricoSincronizacao($resBackup['backup'], 'z_sga_grupo', $resSinc['rowCount']);
				if($resLog['return']):
				
					// Elimina variável com a query executada.
					unset($_SESSION['query_sincroniza_dados']);									
					
					// Executa commit
					$this->db->commit();
					
					$this->helper->setAlert(
						'success',
						'Sincronia efetuada com sucesso!',
						'sincronizacao/grupos'
					);
				else:
					// remove arquivo de backup
					unlink('dumps/'.$resBackup['backup']);
					
					// Executa rollback
					$this->db->rollback();
					
					$this->helper->setAlert(
						'error',
						"Erro ao gravar log de sincronização! <br>". $resLog['error'] . "<br>Favor tentar novamente.",
						'sincronizacao/grupos'
					);									
				endif;
			else:			
				// remove arquivo de backup
				unlink('dumps/'.$resBackup['backup']);								
				
				// Executa rollback
				$this->db->rollback();
				
				$this->helper->setAlert(
					'error',
					"Erro ao realizar sincronização! <br>". $resSinc['error'] . "<br>Favor tentar novamente.",
					'sincronizacao/grupos'
				);											
			endif;					
		else:
			$this->helper->setAlert(
				'error',
				"Erro ao realizar dump da tabela! \n". $$resBackup['error'],
				'sincronizacao/grupos'
			);
		endif;		       
    }

	/**
	* Carrega tela de sincronização de usuários
	*/
    public function usuarios()
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
		
		setlocale(LC_TIME, 'pt_BR');
        date_default_timezone_set('America/Sao_Paulo');
		
        if(isset($_FILES['file']) && !empty($_FILES['file'])):            
            $query = '';
            $query_values = '';
            $inicio = date('H:i:s');

            //$csvFile = 'ser004b.csv';
            $csvFile = $_FILES['file']['tmp_name'];
            $file = fopen($csvFile, 'r');
			// Pula cabeçalho
			fgetcsv($file);
			
            // Percorre as linhas e inclui na query
            $totProgJaCad = 0;
            $totProgCad = 0;
            $numLine = 1;
            $htmlTable = '';

            while(!feof($file)):
                $line = fgetcsv($file, 0, ';');

			   // Valida se não é cabeçalho
                if(isset($line[0]) && $line[0] != 'z_sga_usuarios_id'):
					// Valida se grupo já existe na base
                    $line = explode(',', $line[0]);

                    if($line[1] == '' || $line[2] == '' || $line[4] == '' || $line[4] == '' || $line[5] == '' || $line[6] == '' || $line[7] == '' ||
                        $line[8] == '' || $line[9] == '' || $line[10] == '' || $line[11] == '' || $line[12] == '' || $line[13] == '' || $line[14] == ''):
                        unset($_SESSION['query_sincroniza_dados']);
                        $this->helper->setAlert(
                            'error',
                            'Erro na disposição dos dados no arquivo. <br>Verifique as informações e tente novamente, por favor!',
                            'sincronizacao/usuarios'
                        );
                        break;
                    endif;

					$valid = $sincronizacao->validaCadastroUsuarios($line[1]);

					// Se foi encontrado relacionamento na base
                    if($valid['return']):
						$htmlTable .= '	<tr style="background: #d4ecd1">';
						$htmlTable .= '		<td style="width: 8%">'.$numLine++.'</td>';
						$htmlTable .= '		<td style="width: 20%">'.$line[1].'</td>';
						$htmlTable .= '		<td>'.$line[2].'</td>';
						$htmlTable .= '		<td>Sim</td>';
						$totProgJaCad++;
						continue;
					endif;

                    // Se não foi encontrado o relacionamento na base
                    $query_values .= "(
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
                        '".addslashes($line[14])."'),";
					
					// Se não foi encontrado na base
                    if($line[0] != 'cod_usuario'):
						$htmlTable .= '	<tr style="background: #ecd1d1">';
						$htmlTable .= '		<td>'.$numLine++.'</td>';
						$htmlTable .= '		<td>'.$line[1].'</td>';
						$htmlTable .= '		<td>'.$line[2].'</td>';
						$htmlTable .= '		<td>Não</td>';
						$htmlTable .= '	</tr>';
					endif;
				
					$totProgCad++;       
				endif;	
            endwhile;
			
            fclose($file);
			
            // Cria a query para sincronização
            if(!empty($query_values)):
                $query .= "
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
						) VALUES 
				" . $query_values;
                $query = substr(trim($query), 0, -1);

                $_SESSION['query_sincroniza_dados'] = $query;
                $dados['podeSincronizar'] = true;
                //$this->helper->debug($query, true);
            endif;
			//echo "<pre>";
			//die($query);
            $dados['inicio'] = $inicio;
            $dados['fim']    = date('H:i:s');
            $dados['totCadastrados'] = $totProgJaCad;
            $dados['totNaoCadastrados'] = $totProgCad;
            $dados['htmlTable'] = $htmlTable;
        endif;
		
		$dados['logs'] = $sincronizacao->carregaLogs('z_sga_usuarios');	
        $this->loadTemplate('sincronizacao_usuarios', $dados);
    }

	/**
	* Executa sincronização, cria dump da tabela z_sga_usuarios e grava log.
	*/
    public function sincronizaUsuarios()
    {
		$sincronizacao = new Sincronizacao();
		
		// Inicia transaction
		$this->db->beginTransaction();
		
		// Realiza o backup da tabela z_sga_usuarios
		$resBackup = $sincronizacao->backup_tables($tables = 'z_sga_grupo');
		if($resBackup['return']):			
			$resSinc = $sincronizacao->sincronizaDados();			
			if($resSinc['return']):
				// Grava log da sincronização
				$resLog = $sincronizacao->gravaHistoricoSincronizacao($resBackup['backup'], 'z_sga_usuarios', $resSinc['rowCount']);
				if($resLog['return']):
				
					// Elimina variável com a query executada.
					unset($_SESSION['query_sincroniza_dados']);									
					
					// Executa commit
					$this->db->commit();
					
					$this->helper->setAlert(
						'success',
						'Sincronia efetuada com sucesso!',
						'sincronizacao/usuarios'
					);
				else:
					// remove arquivo de backup
					unlink('dumps/'.$resBackup['backup']);
					
					// Executa rollback
					$this->db->rollback();
					
					$this->helper->setAlert(
						'error',
						"Erro ao gravar log de sincronização! <br>". $resLog['error'] . "<br>Favor tentar novamente.",
						'sincronizacao/usuarios'
					);									
				endif;
			else:			
				// remove arquivo de backup
				unlink('dumps/'.$resBackup['backup']);								
				
				// Executa rollback
				$this->db->rollback();
				
				$this->helper->setAlert(
					'error',
					"Erro ao realizar sincronização! <br>". $resSinc['error'] . "<br>Favor tentar novamente.",
					'sincronizacao/usuarios'
				);											
			endif;					
		else:
			$this->helper->setAlert(
				'error',
				"Erro ao realizar dump da tabela! \n". $$resBackup['error'],
				'sincronizacao/usuarios'
			);
		endif;		       
    }

	/**
     * Carrega tela de sincronização de grupos x programas
     */
    public function usuariosEmpresas()
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
		
		setlocale(LC_TIME, 'pt_BR');
        date_default_timezone_set('America/Sao_Paulo');
		
        if(isset($_FILES['file']) && !empty($_FILES['file'])):            
            $query = '';
            $query_values = '';
            $inicio = date('H:i:s');

            //$csvFile = 'ser004b.csv';
            $csvFile = $_FILES['file']['tmp_name'];
            $file = fopen($csvFile, 'r');
			// Pula cabeçalho
			fgetcsv($file);
			
            // Percorre as linhas e inclui na query
            $totProgJaCad = 0;
            $totProgCad = 0;
            $numLine = 0;
            $htmlTable = '';
			
            while(!feof($file)):
                $line = fgetcsv($file, 0, ';');

                // Valida se não é cabeçalho
                if(isset($line[0]) && $line[0] != 'idUsrEMp'):
                    // Valida se relação já existe na base
                    $line = explode(',', $line[0]);

                    if($line[1] == ''):
                        unset($_SESSION['query_sincroniza_dados']);
                        $this->helper->setAlert(
                            'error',
                            'Erro na disposição dos dados no arquivo. <br>Verifique as informações e tente novamente, por favor!',
                            'sincronizacao/usuariosEmpresas'
                        );
                        break;
                    endif;

                    $valid = $sincronizacao->validaCadastroUsuariosEmpresas($line[1], $_SESSION['empresaid']);

                    if($valid['return'] === 'error'):
                        unset($_SESSION['query_sincroniza_dados']);
                        $dados['podeSincronizar'] = false;
                        $this->helper->setAlert(
                            'error',
                            $valid['error'],
                            'sincronizacao/usuariosEmpresas'
                        );
                        break;
                    // Se foi encontrado relacionamento na base
                    elseif($valid['return']):
                        $htmlTable .= '	<tr style="background: #d4ecd1">';
                        $htmlTable .= '		<td style="width: 8%">'.$numLine++.'</td>';
                        $htmlTable .= '		<td style="width: 20%">'.$line[1].'</td>';
						$htmlTable .= '		<td style="width: 20%">'.$line[2].'</td>';
                        $htmlTable .= '		<td>Sim</td>';
                        $totProgJaCad++;
                        continue;
                    endif;

                    // Se não foi encontrado o relacionamento na base
                    $query_values .= "(
                        '".$valid['idUsuario']."',
                        '".$_SESSION['empresaid']."',                                                 
                        '".$valid['ativo']."'),";

                    // Se não foi encontrado na base
                    if($line[0] != 'idUsrEMp'):
                        $htmlTable .= '	<tr style="background: #ecd1d1">';
                        $htmlTable .= '		<td style="width: 8%">'.$numLine++.'</td>';
                        $htmlTable .= '		<td style="width: 20%">'.$line[1].'</td>';
						$htmlTable .= '		<td style="width: 20%">'.$line[2].'</td>';
                        $htmlTable .= '		<td>Não</td>';
                        $htmlTable .= '	</tr>';

                        $totProgCad++;
                    endif;
                endif;
            endwhile;

            fclose($file);

            // Cria a query para sincronização
            if(!empty($query_values)):
                $query .= "
					INSERT INTO
						z_sga_usuario_empresa (							
							`idUsuario`,
							`idEmpresa`,
                            `ativo`				
						) VALUES 
				" . $query_values;
                $query = substr(trim($query), 0, -1);

                $_SESSION['query_sincroniza_dados'] = $query;
                $dados['podeSincronizar'] = true;
				//$this->helper->debug($query, true);
            endif;
			
            $dados['inicio'] = $inicio;
            $dados['fim']    = date('H:i:s');
            $dados['totCadastrados'] = $totProgJaCad;
            $dados['totNaoCadastrados'] = $totProgCad;
            $dados['htmlTable'] = $htmlTable;
        endif;
        
        $dados['logs'] = $sincronizacao->carregaLogs('z_sga_usuario_empresa');
        $this->loadTemplate('sincronizacao_usuarios_empresas', $dados);
    }

	/**
	* Executa sincronização, cria dump da tabela z_sga_programas e grava log.
	*/
    public function sincronizaUsuariosEmpresas()
    {
		$sincronizacao = new Sincronizacao();
		
		// Inicia transaction
		$this->db->beginTransaction();
		
		// Realiza o backup da tabela z_sga_programas
		$resBackup = $sincronizacao->backup_tables($tables = 'z_sga_usuario_empresa');
		if($resBackup['return']):			
			$resSinc = $sincronizacao->sincronizaDados();			
			if($resSinc['return']):
				// Grava log da sincronização
				$resLog = $sincronizacao->gravaHistoricoSincronizacao($resBackup['backup'], 'z_sga_usuario_empresa', $resSinc['rowCount']);
				if($resLog['return']):
				
					// Elimina variável com a query executada.
					unset($_SESSION['query_sincroniza_dados']);									
					
					// Executa commit
					$this->db->commit();
					
					$this->helper->setAlert(
						'success',
						'Sincronia efetuada com sucesso!',
						'sincronizacao/usuariosEmpresas'
					);
				else:
					// remove arquivo de backup
					unlink('dumps/'.$resBackup['backup']);
					
					// Executa rollback
					$this->db->rollback();
					
					$this->helper->setAlert(
						'error',
						"Erro ao gravar log de sincronização! <br>". $resLog['error'] . "<br>Favor tentar novamente.",
						'sincronizacao/usuariosEmpresas'
					);
				endif;
			else:			
				// remove arquivo de backup
				unlink('dumps/'.$resBackup['backup']);								
				
				// Executa rollback
				$this->db->rollback();
				
				$this->helper->setAlert(
					'error',
					"Erro ao realizar sincronização! <br>". $resSinc['error'] . "<br>Favor tentar novamente.",
					'sincronizacao/usuariosEmpresas'
				);											
			endif;					
		else:
			$this->helper->setAlert(
				'error',
				"Erro ao realizar dump da tabela! \n". $$resBackup['error'],
				'sincronizacao/usuariosEmpresas'
			);
		endif;		       
    }

}
