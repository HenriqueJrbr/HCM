<?php

class Sincronizacao extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

	/**
	* Valida se programa já está cadastrado
	*/
    public function validaCadastroProgramas($codPrograma,$empresa='')
    {                      
        if($empresa=='' && isset($_SESSION['empresaid'])){
            $empresa=$_SESSION['empresaid'];
        }

        $sql = "
        SELECT 
            z_sga_programas_id,
            cod_programa,
            descricao_programa,
            cod_modulo,
            descricao_modulo,
            especific,
            upc,
            ajuda_programa,
            codigo_rotina,
            descricao_rotina,
            registro_padrao,
            visualiza_menu,
            procedimento_pai
        FROM 
            z_sga_programas zsp
        INNER JOIN
            z_sga_programa_empresa zspe
        ON 
            zspe.idPrograma = zsp.z_sga_programas_id
        WHERE 
            cod_programa = '".$codPrograma."' 
            AND zspe.idEmpresa =".$empresa;   
				//die;
        try{
            $sql = $this->db->query($sql);

            if($sql->rowCount() > 0):

                $sql = $sql->fetch(PDO::FETCH_ASSOC);
                
                return array(
                    'return' => true,
                    'z_sga_programas_id' => $sql['z_sga_programas_id'],
                    'cod_programa' => $sql['cod_programa'],
                    'descricao_programa' => $sql['descricao_programa'],
                    'cod_modulo' => $sql['cod_modulo'],
                    'descricao_modulo' => $sql['descricao_modulo'],
                    'especific' => $sql['especific'],
                    'upc' => $sql['upc'],
                    'ajuda_programa' => $sql['ajuda_programa'],
                    'codigo_rotina' => $sql['codigo_rotina'],
                    'descricao_rotina' => $sql['descricao_rotina'],
                    'registro_padrao' => $sql['registro_padrao'],
                    'visualiza_menu' => $sql['visualiza_menu'],
                    'procedimento_pai' => $sql['procedimento_pai']         
                );
            endif;
        }catch (EXCEPTION $e){
            return array(
                'return' => false,
                'error'  => $e->getMessage()
            );
        }
    }
	
	/**
	* Valida se empresa já está relacinado à programa
	*/
    public function validaCadastroProgramasEmpresas($codPrograma, $idEmpresa, $analise)
    {
        $sql = "
            SELECT
                z_sga_programas_id as idPrograma
            FROM
                z_sga_programas
            WHERE
                cod_programa = '".
				$codPrograma."'"
                ;
        try{
            $sql = $this->db->query($sql);

            if($sql->rowCount() > 0):
                $prog = $sql->fetch(PDO::FETCH_ASSOC);

                $sql = "
                    SELECT
                        idGrupoPrograma,
                        idPrograma
                    FROM 
                        z_sga_programa_empresa
                    WHERE 
                        idPrograma = ".$prog['idPrograma']."
                        AND idEmpresa = $idEmpresa";
               
                $sql = $this->db->query($sql);
                
                if($sql->rowCount()> 0):
                    $sql = $sql->fetch(PDO::FETCH_ASSOC);

                    return array('return' => true, 'idGrupoPrograma' => $sql['idGrupoPrograma']);
                else:
                    return array(
                        'return' => false,
                        'idPrograma' => $prog['idPrograma']
                    );
                endif;
            else:
                if($analise == 1):
                    return array(
                        'return' => false,
                        'error'  => $codPrograma
                    );
                else:
                    return array(
                        'return' => 'error',
                        'error'  => 'Alguns registros não foram encontrados. Favor sincronizar os programas.'
                    );
                endif;
                
            endif;
        }catch (EXCEPTION $e){
            return array(
                'return' => false,
                'error'  => $e->getMessage()
            );
        }

    }
	
	/**
	* Valida se grupo já está relacinado à programa
	*/
    public function validaCadastroGruposProgramas($codGrupo, $codPrograma, $analise, $instancia)
    {
        if($instancia=='' && isset($_SESSION['empresaid'])){
            $instancia=$_SESSION['empresaid'];
        }
        
        $sql = "
            SELECT
                z_sga_grupo_programa_id,
				cod_grupo,
                cod_programa,
                nome_grupo
            FROM 
                z_sga_grupo_programa
            WHERE 
				cod_grupo = '".$codGrupo."'
                AND cod_programa = '".$codPrograma."'
                AND idEmpresa = " . $instancia;

        try{
            $sql = $this->db->query($sql);
            if($sql->rowCount() > 0):
                $sql = $sql->fetch(PDO::FETCH_ASSOC);

                return array(
                    'return' => true,
                    'z_sga_grupo_programa_id' => $sql['z_sga_grupo_programa_id']
                );
            else:
                $sql = "
                    SELECT 
                        idGrupo,
                        idLegGrupo,
                        descAbrev,
                        '' AS gestor
                    FROM 
                        z_sga_grupo	 
                    WHERE 
                        idLegGrupo = '".$codGrupo."'
                        And idempresa='".$instancia."'";
                $sql = $this->db->query($sql);

                if($sql->rowCount() == 0):
                    if($analise == 0):
						return ['return' => true];
                        //return array(
                        //    'return' => 'error',
                        //    'error'  => 'Alguns registros não foram encontrados. Favor sincronizar os programas e grupos novamente.'
                        //);
                    endif;
                    
                endif;

                $grupo = $sql->fetch(PDO::FETCH_ASSOC);

                $sql = "
                    SELECT 
                        z_sga_programas_id AS idPrograma, 
                        cod_programa 
                    FROM 
                        z_sga_programas 
                    WHERE cod_programa = '".$codPrograma."'";

                $sql = $this->db->query($sql);

                if($sql->rowCount() == 0):
                    if($analise == 0):
						return ['return' => true];
                        //return array(
                        //    'return' => 'error',
                        //    'error'  => 'Alguns registros não foram encontrados. Favor sincronizar os <strong>programas</strong> novamente.'
                        //);
                    endif;
                endif;

                $prog = $sql->fetch(PDO::FETCH_ASSOC);
                if($prog instanceof PDOStatement && $grupo instanceof PDOStatement):
                    return array(
                        'return'        => false,
                        'idGrupo'       => $grupo['idGrupo'],
                        'codGrupo'      => $grupo['idLegGrupo'],
                        'nomeGrupo'     => $grupo['descAbrev'],
                        'gestor'        => $grupo['gestor'],
                        'idPrograma'    => $prog['idPrograma'],
                        'codPrograma'  => $prog['cod_programa']
                    );
                endif;
            endif;
        }catch (EXCEPTION $e){
            return array(
                'return' => false,
                'error'  => $e->getMessage()
            );
        }

    }
	
	/**
	* Valida se grupo já está relacinado à usuário
	*/
    public function validaCadastroGruposUsuarios($codGrupo, $codUsuario, $analise, $instancia='')
    {
        if($instancia=='' && isset($_SESSION['empresaid'])){
            $instancia=$_SESSION['empresaid'];
        }

        $sql = "
            SELECT
                z_sga_grupos_id,
                cod_grupo
            FROM 
                z_sga_grupos
            WHERE 
                cod_grupo = '".$codGrupo."'
                AND cod_usuario = '".$codUsuario."'
                AND idEmpresa=".$instancia;
        try{
            $sql = $this->db->query($sql);

            if($sql->rowCount() > 0):
                $sql = $sql->fetch(PDO::FETCH_ASSOC);

                return array(
                    'return' => true,
                    'z_sga_grupos_id' => $sql['z_sga_grupos_id']
                );
            else:
                $sql = "
                    SELECT 
                        idGrupo,
                        idLegGrupo,
                        descAbrev,
                        '' AS 'gestor'		
                    FROM 
                        z_sga_grupo	 
                    WHERE 
                        idLegGrupo = '".$codGrupo."'
                        and idempresa =".$instancia;
			//echo $sql."<br>";
                $sql = $this->db->query($sql);

                if($sql->rowCount() == 0):
                    if($analise == 0):
                        return array(
                            'return' => 'error',
                            'error'  => 'Alguns registros não foram encontrados. Favor sincronizar os usuários e grupos novamente.'
                        );
                    else:
                        return ['return' => false];
                    endif;
                endif;

                $grupo = $sql->fetch(PDO::FETCH_ASSOC);

                $sql = "
                    SELECT 
                        z_sga_usuarios_id AS idUsuario, 
                        cod_usuario 
                    FROM 
                        z_sga_usuarios 
                    WHERE cod_usuario = '".$codUsuario."'";
				//echo $sql."<br>";
                $sql = $this->db->query($sql);

                if($sql->rowCount() == 0):
                    if($analise == 0):
                        return array(
                            'return' => 'error',
                            'error'  => 'Alguns registros não foram encontrados. Favor sincronizar os <strong>usuários</strong> e <strong>grupos</strong> novamente.'
                        );
                    else:
                        return ['return' => false];
                    endif;
                endif;

                $prog = $sql->fetch(PDO::FETCH_ASSOC);
                return array(
                    'return'        => false,
                    'idGrupo'       => $grupo['idGrupo'],
                    'codGrupo'      => $grupo['idLegGrupo'],
                    'nomeGrupo'     => $grupo['descAbrev'],
                    'gestor'        => $grupo['gestor'],
                    'idUsuario'    => $prog['idUsuario'],
                    'codUsuario'  => $prog['cod_usuario']
                );
            endif;
        }catch (EXCEPTION $e){
            return array(
                'return' => false,
                'error'  => $e->getMessage()
            );
        }

    }

	/**
	* Valida se grupo já está cadastrado
	*/
    public function validaCadastroGrupos($idLegGrupo,$instancia='')
    {
        if($instancia=='' && isset($_SESSION['empresaid'])){
            $instancia=$_SESSION['empresaid'];
        }
        $sql = "
            SELECT 
                idGrupo, 
                idLegGrupo,
                descAbrev
            FROM 
                z_sga_grupo
            WHERE 
                idLegGrupo = '".$idLegGrupo."'
                AND idEmpresa='".$instancia."'";
        try{
            $sql = $this->db->query($sql);

            if($sql->rowCount() > 0):
                $sql = $sql->fetch(PDO::FETCH_ASSOC);

                return array(
                    'return' => true,
                    'idGrupo' => $sql['idGrupo'],
                    'idLegGrupo'=> $sql['idLegGrupo'],
                    'descAbrev'=> $sql['descAbrev']
                );
            endif;
        }catch (EXCEPTION $e){
            return array(
                'return' => false,
                'error'  => $e->getMessage()
            );
        }
    }
	
	/**
	* Valida se usuário já está cadastrado
	*/
    public function validaCadastroUsuarios($codUsuario,$instancia='')
    {
        if($instancia=='' && isset($_SESSION['empresaid'])){
            $instancia=$_SESSION['empresaid'];
        }

        $sql = "
        SELECT 
            z_sga_usuarios_id,
            cod_usuario,
            nome_usuario,
            cpf,
            email,
            zsu.ativo
        FROM 
            z_sga_usuarios zsu
        INNER JOIN
            z_sga_usuario_empresa zsgu
        ON
            zsgu.idUsuario = zsu.z_sga_usuarios_id
        WHERE 
            zsu.cod_usuario = '".$codUsuario."'
            AND zsgu.idEmpresa =".$instancia;
        try{
            $sql = $this->db->query($sql);

            if($sql->rowCount() > 0):
                $sql = $sql->fetch(PDO::FETCH_ASSOC);

                return array(
                    'return' => true,
                    'z_sga_usuarios_id' => $sql['z_sga_usuarios_id'],
                    'cod_usuario' => $sql['cod_usuario'],
                    'nome_usuario' => $sql['nome_usuario'],
                    'cpf' => $sql['cpf'],
                    'email' => $sql['email'],
                    'ativo' => $sql['ativo']
                );
            endif;
        }catch (EXCEPTION $e){
            return array(
                'return' => false,
                'error'  => $e->getMessage()
            );
        }
    }
	
	/**
	* Valida se usuário já está cadastrado para instancia informada
	*/
    public function validaCadastroUsuariosEmpresas($codUsuario, $idEmpresa, $analise)
    {
        $sql = "
            SELECT 
                z_sga_usuarios_id AS idUsuario,
                ativo
            FROM 
                z_sga_usuarios
            WHERE 
                cod_usuario = '".$codUsuario."'";
        try{
            $sql = $this->db->query($sql);

            if($sql->rowCount() > 0):
                $usr = $sql->fetch(PDO::FETCH_ASSOC);

                $sql = "
                    SELECT 
                        idUsrEMp,
                        idUsuario
                    FROM 
                        z_sga_usuario_empresa
                    WHERE 
                        idUsuario = ".$usr['idUsuario']."
                        AND idEmpresa = $idEmpresa";

                $sql = $this->db->query($sql);
                if($sql->rowCount() > 0):
                    $sql = $sql->fetch(PDO::FETCH_ASSOC);
                    return array('return' => true, 'idUsrEMp' => $sql['idUsrEMp']);
                else:
                    return array(
                        'return' => false,
                        'idUsuario' => $usr['idUsuario'],
                        'codUsuario' => $codUsuario,
                        'ativo'     => $usr['ativo']
                    );
                endif;
            else:
                if($analise == 0):
                    return array(
                        'return' => 'error',
                        'error'  => 'Alguns registros não foram encontrados. Favor sincronizar os programas.'
                    );
                else:
                    return ['return' => false];
                endif;
            endif;
        }catch (EXCEPTION $e){
            return array(
                'return' => false,
                'error'  => $e->getMessage()
            );
        }
    }

    /**
	* Valida se função já está cadastrada
	*/
    public function validaCadastroFuncao($codFuncao)
    {                      
        $sql = "
            SELECT 
                cod_funcao 
            FROM 
                z_sga_manut_funcao
            WHERE 
                cod_funcao = '".$codFuncao."'";      
				//echo $sql."<br>";
				//die;
        try{
            $sql = $this->db->query($sql);

            if($sql->rowCount() > 0):
                return array(
                    'return' => true
                );
            endif;
        }catch (EXCEPTION $e){
            return array(
                'return' => false,
                'error'  => $e->getMessage()
            );
        }
    }

    /**
	* retorna dadados da tabela z_sga_grupo_programa
	*/
    public function getGruposProgramas($codGrupo, $codPrograma)
    {
        $sql = "
            SELECT
                gp.z_sga_grupo_programa_id,
                g.descAbrev
            FROM 
                z_sga_grupo_programa gp
            LEFT JOIN
                z_sga_grupo g
                ON g.idGrupo = gp.idGrupo
            WHERE 
				(gp.cod_grupo = '".$codGrupo."'
                AND gp.cod_programa = '".$codPrograma."')
                AND g.idEmpresa = " . $_SESSION['empresaid'];
               // echo $sql."<br>";
        try{
            $sql = $this->db->query($sql);

            if($sql->rowCount() > 0):
                return [
                    'return'    => true,
                    'dados'     => $sql->fetch(PDO::FETCH_ASSOC)
                    //'dados'     => $sql->fetchAll(PDO::FETCH_ASSOC)
                ];
            else:
                return [
                    'return' => true,
                    'dados'  => []
                ];
            endif;
        }catch(EXCEPTION $e){
            return [];
        }
    }

    /**
	* retorna dadados da tabela z_sga_grupos
	*/
    public function getGruposUsuarios($codGrupo, $codUsuario)
    {
        $sql = "
            SELECT
                z_sga_grupos_id AS idGruposId
            FROM 
                z_sga_grupos gs
            LEFT JOIN
                z_sga_grupo g
                ON g.idGrupo = gs.idGrupo
            WHERE 
				(cod_grupo = '".$codGrupo."'
                AND cod_usuario = '".$codUsuario."')
                ANd g.idEmpresa = " . $_SESSION['empresaid'];
        try{
            $sql = $this->db->query($sql);

            if($sql->rowCount() > 0):
                return [
                    'return'    => true,
                    'dados'     => $sql->fetchAll(PDO::FETCH_ASSOC)
                ];
            else:
                return [
                    'return' => true,
                    'dados'  => []
                ];
            endif;
        }catch(EXCEPTION $e){
            return [];
        }
    }

	/**
	* Retorna os registros que estão a mais no SGA
	* @param $query
	*/
	public function comparaDiffSGA($query)
	{
        //echo $query."<br><br>";
		$res = $this->db->query($query);
		//echo ($res->rowCount());
		if($res->rowCount() > 0):
			return $res->fetchAll(PDO::FETCH_ASSOC);
		else:
			return [];
		endif;
	}

	/**
	* Realiza sincronizacao. Utiliza query criada e atribuida em sessão.
	*/

    public function sincronizaDados($query, $table, $nameFileBkp, $query_diff_sga)
    {
        $bkp = false;
        $diffSga = [];
        
		try{
            // Faz backup da tabela
            if(!empty($query)):                
                $bkp = $this->backup_tables($table, $nameFileBkp);

                if($bkp):       
                    if(!empty($quer)):         
                        $res = $this->db->query($query);
                    endif;
                else:
                    return array(
                        'return'    => false,
                        'error'     => 'Erro ao fazer backup'
                    );            
                endif;
            endif; 

            
                        
            // Executa comparação de registros a mais no SGA
            if(!empty($query_diff_sga)):                
                //echo($query_diff_sga)."<br>";


                if($table == 'z_sga_grupos' || $table == 'z_sga_grupo_programa'):
                    if($bkp == false):
                        $bkp = $this->backup_tables($table, $nameFileBkp);
                    endif;

                    if($bkp):                                                
                        try{
                            $res = $this->db->query(str_replace('SELECT *', 'DELETE ', $query_diff_sga));
                            //print_r($res);
                        }catch(EXCEPTION $e){
                            //print_r($e);
                            die($e->getMessage());
                        }
                        
                    else:
                        return array(
                            'return'    => false,
                            'error'     => 'Erro ao fazer backup'
                        );
                    endif;                
                endif;

                $diffSga = $this->db->query($query_diff_sga);
                if($diffSga->rowCount() > 0):
                    // Faz backup se ainda não fez
                    if($bkp == false):
                        $bkp = $this->backup_tables($table, $nameFileBkp);
                    endif;

                    if($bkp):
                        $diffSga = $diffSga->fetchAll(PDO::FETCH_ASSOC);
                        //echo(str_replace('SELECT *', 'DELETE ', $query_diff_sga))."<br>";
                        try{
                            $res = $this->db->query(str_replace('SELECT *', 'DELETE ', $query_diff_sga));
                            //print_r($res);
                        }catch(EXCEPTION $e){
                            print_r($e);
                            die($e->getMessage());
                        }
                        
                    else:
                        return array(
                            'return'    => false,
                            'error'     => 'Erro ao fazer backup'
                        );
                    endif;
                else:
                    $diffSga = [];
                endif;
            endif;

            return array(
                'return'        => true,
                //'rowCount'      => $res->rowCount(),
                'diffSga'       => $diffSga
            );            			
		}catch(EXCEPTION $e){
            die($e->getMessage());
			return array(
				'return' => false,
				'error'	 => "Erro de sincronização! <br>". $e->getMessage() . "<br>Favor tentar novamente."
			);
		}		        
    }   


	/**
	* backup the db OR just a table 
	*/
	public function backup_tables($tables = '*', $nameFileBkp)
	{				
		try{
			$return = '';
			//get all of the tables
			if($tables == '*'):		
				$tables = array();
				$result = $this->db->query('SHOW TABLES')->fetchAll(PDO::FETCH_ASSOC);
				
				foreach($result as $val):			
					$tables[] = $val;
				endforeach;
			else:		
				$tables = is_array($tables) ? $tables : explode(',',$tables);
			endif;
            
            //save file
			$nome_backup = $nameFileBkp;

			//cycle through
			foreach($tables as $table):	
				$result = $this->db->query('SELECT * FROM ' . $table);
				$num_fields = $result->columnCount();
				$result = $result->fetchAll(PDO::FETCH_ASSOC);							
				
				$return .= 'DROP TABLE '.$table.';';
				$create_table = $this->db->query('SHOW CREATE TABLE '.$table)->fetch(PDO::FETCH_ASSOC);
				$return .= "\n\n".$create_table['Create Table'].";\n\n";																											
				$return.= 'INSERT INTO '.$table.' VALUES ';				
				foreach($result as $res):
					$return .= '(';
					foreach($res as $val):
						$return .= "'".addslashes($val)."', ";						
					endforeach;					
					$return = substr(trim($return), 0, -1);
					$return.= "),\n";				
				endforeach;				
				$return = substr(trim($return), 0, -1);
                $return .= ";\n\n\n";
                //echo $return."<br>";
			endforeach;
			
			//echo "<pre>";
			//print_r($return);
			//die;
			
			setlocale(LC_TIME, 'pt_BR');
			date_default_timezone_set('America/Sao_Paulo');
									
			$handle = fopen(BASE_PATH_SINCRONIZACAO.'/dumps/'.$nome_backup, 'a+');
			fwrite($handle, $return);
			fclose($handle);
		
			return array(
				'return'	=> true				
			);
		}catch(EXCEPTION $e){
			return array(
				'return' => false,
				'error'	 => $e->getMessage()
			);
		}
	}

	/**
     * Grava log das sincronizações
     * @param $data
     * @return array
     */
	public function gravaHistoricoSincronizacao($programas, $progEmpresa, $usuarios, $usuarioEmpresa, $grupos, $gruposUsuarios, $gruposProgramas, $funcao, $instancia, $anexo, $backupFile, $fileDiff, $inicio, $status)
    {		

		setlocale(LC_TIME, 'pt_BR');
        date_default_timezone_set('America/Sao_Paulo');
        $idUsuario = '';
        
        if(!isset($_SESSION['idUsrTotvs'])):
            $sql = $this->db->query("SELECT z_sga_usuarios_id AS idUsuario FROM z_sga_usuarios WHERE cod_usuario = 'super'");
            //echo $sql."<br>";
            if($sql->rowCount() > 0):
                $sql = $sql->fetch(PDO::FETCH_ASSOC);
                $idUsuario = $sql['idUsuario'];
            endif;
        else:
            $idUsuario = $_SESSION['idUsrTotvs'];
        endif;
        
		try{
			$sql = "
				INSERT INTO
					z_sga_sincronizacao(
						`idUsuario`,
                        `progs`,
                        `progsDel`,
                        `progsAtt`,
                        `progEmp`,
                        `progEmpDel`,
                        `users`,
                        `usersDel`,
                        `usersAtt`,
                        `userEmp`,
                        `userEmpDel`,
                        `grupos`,
                        `gruposDel`,
                        `gruposAtt`,
                        `grupoUser`,
                        `grupoUserDel`,
                        `grupoProg`,
                        `grupoProgDel`,
                        `funcao`,
                        `funcaoDel`,
                        `instancia`,
                        `anexo`,
                        `dump`,
                        `csvResult`,
                        `inicio`,
                        `fim`,
                        `data`,
                        `status`
					) VALUES(
						$idUsuario,
                        {$programas['totNaoCadastrados']},
                        {$programas['totEliminados']},
                        {$programas['totAtualizados']},
                        {$progEmpresa['totNaoCadastrados']},
                        {$progEmpresa['totEliminados']},
                        {$usuarios['totNaoCadastrados']},
                        {$usuarios['totEliminados']},  
                        {$usuarios['totAtualizados']},
                        {$usuarioEmpresa['totNaoCadastrados']},
                        {$usuarioEmpresa['totEliminados']},
                        {$grupos['totNaoCadastrados']},
                        {$grupos['totEliminados']},
                        {$grupos['totAtualizados']},
                        {$gruposUsuarios['totNaoCadastrados']},
                        {$gruposUsuarios['totEliminados']}, 
                        {$gruposProgramas['totNaoCadastrados']},
                        {$gruposProgramas['totEliminados']},                     
                        {$funcao['totNaoCadastrados']},
                        {$funcao['totEliminados']},   
                        {$instancia},
                        '{$anexo}',
                        '{$backupFile}',
                        '{$fileDiff}',
                        '{$inicio}',
                        '".date('Y-m-d H:i:s')."',
                        '".date('Y-m-d H:i:s')."',
                        '{$status}'
					)
			";
            //echo "<pre>";
            //echo $sql;
            //die;
			$this->db->query($sql);
            
			return array('return' => true);
		}catch(EXCEPTION $e){			
			return array(
				'return' => false,
				'error'	 => $e->getMessage()
			);
		}		       
    }

    /**
     * Busca os atividades dos fluxos do sistema
     */
    public function carregaLogs()
    {
        $sql = "
            SELECT                 
                l.*,
                u.nome_usuario                
            FROM 
                z_sga_sincronizacao l
			INNER JOIN
				z_sga_usuarios u
				ON l.idUsuario = u.z_sga_usuarios_id			
        ";        
        
        $sql = $this->db->query($sql);

        $dados = array();
        if ($sql->rowCount() > 0):
            $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
        endif;

        return $dados;
    }


}