<?php
class Manutencao extends Model{
    public function __construct(){
        parent::__construct();
    }

    /*
      Está consulta é para pegar o codigo totvs do usuário que está sendo deletado do grupo;
    */
    public function carregaCodUsrGrupos($id){
        $sql = "SELECT * FROM z_sga_grupos where z_sga_grupos_id = '$id'";
        $sql = $this->db->query($sql);

        $dados = array();
        if($sql->rowCount()>0){
            $dados = $sql->fetch();
        }

        return $dados;
    }

    public function carregaFuncao(){
        $sql  = "SELECT manut.idFuncao,manut.cod_funcao,manut.descricao, (select count(funcao) from z_sga_usuarios where cod_funcao = manut.idFuncao) as total
            FROM 
            z_sga_manut_funcao as manut";

        $sql = $this->db->query($sql);

        $dados = array();
        if($sql->rowCount()>0){
            $dados = $sql->fetchAll();
        }

        return $dados;

    }

    public function cadastrarFuncao($funcao,$descricao){
        $sql = "INSERT INTO z_sga_manut_funcao SET cod_funcao = '$funcao', descricao = '$descricao'";
        $this->db->query($sql);
        return $this->db->lastInsertId();
    }

    public function alterarCadastro($id,$funcao,$descricao){
        $sql = "UPDATE  z_sga_manut_funcao SET cod_funcao = '$funcao', descricao = '$descricao' where idFuncao = '$id' ";

        $this->db->query($sql);
    }

    public function excluirFuncao($id){
        $sql = "DELETE FROM z_sga_manut_funcao where idFuncao = '$id' ";
        $this->db->query($sql);

    }
        
    public function carregaUsuario(){
        $idEmpresa = $_SESSION['empresaid'];
        $sql  = "SELECT
                  u.z_sga_usuarios_id,
                  u.cod_usuario,
                  u.nome_usuario,
                  u.cpf,
                  u.cod_gestor,
                  m.descricao,
                  IF(u.email = '','Não Cadastrado',u.email) as email,
                  CASE u.solicitante      
                       WHEN '' THEN 'Não'      
                       WHEN 'S' THEN 'Sim'          
                    END AS solicitante,
                  
                   CASE u.gestor_usuario      
                       WHEN '' THEN 'Não'      
                       WHEN 'S' THEN 'Sim'          
                    END AS gestor_usuario,
                      
                    CASE u.gestor_grupo      
                       WHEN '' THEN 'Não'      
                       WHEN 'S' THEN 'Sim'          
                    END AS gestor_grupo,
                      
                    CASE u.gestor_programa      
                       WHEN '' THEN 'Não'      
                       WHEN 'S' THEN 'Sim'          
                    END AS gestor_programa,
                      
                    CASE u.si      
                       WHEN '' THEN 'Não'      
                       WHEN 'S' THEN 'Sim'          
                    END AS si
                      
                    FROM z_sga_usuario_empresa AS userEmp
                    INNER JOIN
                        z_sga_usuarios AS u ON userEmp.idUsuario = u.z_sga_usuarios_id
                    LEFT JOIN
                        z_sga_manut_funcao AS m ON u.funcao = m.idFuncao
                    where userEmp.idEmpresa = '$idEmpresa' GROUP BY u.z_sga_usuarios_id";

        $sql = $this->db->query($sql);

        $dados = array();
        if($sql->rowCount()>0){
            $dados = $sql->fetchAll();
        }

        return $dados;

    }

    public function carregaUsuarioedi($id){
        $sql  = "
            SELECT 
                u.z_sga_usuarios_id AS idusu,
                u.cod_usuario,
                u.nome_usuario,
                u.cod_funcao AS codfuncao,
                (SELECT 
                        descricao
                    FROM
                        z_sga_manut_funcao
                    WHERE
                        idFuncao = u.cod_funcao) AS funcao,
                u.email,
                u.gestor_usuario,
                u.gestor_grupo,                
                u.gestor_programa
            FROM
                z_sga_usuarios AS u
            WHERE
                u.z_sga_usuarios_id = $id";
        //echo "<pre>";
        
        //die($sql);

        $sql = $this->db->query($sql);

        $dados = array();
        if($sql->rowCount()>0){
            $dados = $sql->fetchAll();
        }

        return $dados;

    }

    /**
     * @param $search String com o filtro a se buscar
     * @param $orderColumn Int com o índice da coluna a se ordenar na consulta
     * @param $orderDir String ASC ou DESC
     * @param $offset Int numero com salto da consulta
     * @param $limit Total de registros as se buscar por pagina
     * @return array Retorna um array com os registros encontrados
     */
    public function carregaDatatableUsuario($search, $orderColumn, $orderDir, $offset, $limit){
        $sql  = "SELECT DISTINCT
                  u.z_sga_usuarios_id,
                  u.cod_usuario,
                  u.nome_usuario,
                  u.cpf,
                  u.cod_gestor,
                  m.descricao,
                  IF(u.email = '','Não Cadastrado',u.email) as email,
                  IF(userEmp.ativo = 1, 'Ativo','Inativo') as ativo,
                  CASE u.solicitante      
                       WHEN '' THEN 'Não'      
                       WHEN 'S' THEN 'Sim'          
                  END AS solicitante,
                  
                  CASE u.gestor_usuario      
                       WHEN '' THEN 'Não'      
                       WHEN 'S' THEN 'Sim'          
                  END AS gestor_usuario,
                      
                  CASE u.gestor_grupo      
                       WHEN '' THEN 'Não'      
                       WHEN 'S' THEN 'Sim'          
                  END AS gestor_grupo,
                      
                  CASE u.gestor_programa      
                       WHEN '' THEN 'Não'      
                       WHEN 'S' THEN 'Sim'          
                  END AS gestor_programa,
                      
                  CASE u.si      
                       WHEN '' THEN 'Não'      
                       WHEN 'S' THEN 'Sim'          
                  END AS si
                      
                  FROM 
                      z_sga_usuario_empresa AS userEmp 
                  LEFT JOIN
                        z_sga_usuarios AS u 
                        ON userEmp.idUsuario = u.z_sga_usuarios_id
                  LEFT JOIN
                        z_sga_manut_funcao AS m 
                        ON u.cod_funcao = m.idFuncao ";

        // Se a variavel $search não estiver vazia limita cria o where no select
        if($search != ''):
            $sql .= " WHERE (u.cod_usuario LIKE '%$search%'";			
            $sql .= " OR u.nome_usuario LIKE '%$search%'";
            $sql .= " OR u.cpf LIKE '%$search%'";
            $sql .= " OR u.cod_gestor LIKE '%$search%'";
            $sql .= " OR m.descricao LIKE '%$search%'";
            $sql .= " OR u.email LIKE '%$search%') ";
			$sql .= " AND userEmp.idEmpresa = " . $_SESSION['empresaid'];
        endif;

        // Se a variavel $order não estiver vazia ordena o retorno
        if($orderColumn != ''):
            $sql .= " ORDER BY $orderColumn $orderDir";
        endif;

        // Se as variaveis $offset e $limit não estiverem vazias limita o retorno
        if($offset != '' && $limit != ''):
            $sql .= " LIMIT $offset, $limit";
        endif;
		
        $sql = $this->db->query($sql);

        $dados = array();
        if($sql->rowCount()>0){
            $dados = $sql->fetchAll();
        }

        return $dados;
    }

    /**
     * Retorna os usuarios aba usuarios na tela de manutenção de grupos
     *
     * @param $search String com o filtro a se buscar
     * @param $orderColumn Int com o índice da coluna a se ordenar na consulta
     * @param $orderDir String ASC ou DESC
     * @param $offset Int numero com salto da consulta
     * @param $limit Total de registros as se buscar por pagina
     * @param $idGrupo Id do grupo a ser filtrado
     * @return array Retorna um array com os registros encontrados
     */
    public function carregaDatatableGrupoUsuario($search, $orderColumn, $orderDir, $offset, $limit, $idGrupo){
        $where = '';
        $sql = "
            SELECT 
                g.z_sga_grupos_id, 
                u.z_sga_usuarios_id, 
                u.nome_usuario,
                u.cod_usuario,
                u.idUsrFluig,
                u.cod_gestor,
                fun.cod_funcao 
            from 
                z_sga_grupos as g,
                z_sga_usuarios as u,
                z_sga_usuario_empresa as emp,
                z_sga_manut_funcao as fun
            WHERE 
                emp.idUsuario = u.z_sga_usuarios_id
                AND fun.idFuncao = u.cod_funcao
                AND emp.idEmpresa = '".$_SESSION['empresaid']."'
                AND g.idUsuario = u.z_sga_usuarios_id 
                AND idGrupo = '$idGrupo'";

        $sql .= " WHERE g.idEmpresa = '".$_SESSION['empresaid']."'";

        // Se a variavel $search não estiver vazia limita cria o where no select
        if($search != ''):
            $sql .= " AND g.z_sga_grupos_id  LIKE '%$search%'";
            $sql .= " OR u.z_sga_usuarios_id  LIKE '%$search%'";
            $sql .= " OR u.nome_usuario LIKE '%$search%'";
            $sql .= " OR u.cod_usuario LIKE '%$search%'";
            $sql .= " OR u.idUsrFluig LIKE '%$search%'";
            $sql .= " OR u.cod_gestor LIKE '%$search%'";
            $sql .= " OR fun.cod_funcao LIKE '%$search%'";
        endif;

        // Se a variavel $order não estiver vazia ordena o retorno
        if($orderColumn != ''):
            $sql .= " ORDER BY $orderColumn $orderDir";
        endif;

        // Se as variaveis $offset e $limit não estiverem vazias limita o retorno
        if($offset != '' && $limit != ''):
            $sql .= " LIMIT $offset, $limit";
        endif;

        $sql = $this->db->query($sql);

        $dados = array();
        if($sql->rowCount()>0){
            $dados = $sql->fetchAll();
        }

        return $dados;
    }

    /**
     * @param $search String com o filtro a se buscar
     * @param $orderColumn Int com o índice da coluna a se ordenar na consulta
     * @param $orderDir String ASC ou DESC
     * @param $offset Int numero com salto da consulta
     * @param $limit Total de registros as se buscar por pagina
     * @return array Retorna um array com os registros encontrados
     */
    public function carregaDatatablePrograma($search, $orderColumn, $orderDir, $offset, $limit){
        $sql  = "
            SELECT                           
                prog.z_sga_programas_id 	AS id,     
                prog.cod_programa 			AS codigo,
                prog.descricao_programa 	AS descricao,
                IF(prog.especific = 'N', 'Não', 'Sim') AS especifico,
                prog.ajuda_programa 		AS ajuda,
                prog.codigo_rotina			As codigo_rotina
            FROM 
                z_sga_programas prog ";

        // Se a variavel $search não estiver vazia limita cria o where no select
        if($search != ''):
            $sql .= " WHERE prog.cod_programa LIKE '%$search%'";
            $sql .= " OR prog.descricao_programa LIKE '%$search%'";
            $sql .= " OR prog.especific LIKE '%$search%'";
            $sql .= " OR prog.ajuda_programa LIKE '%$search%'";
            $sql .= " OR prog.codigo_rotina LIKE '%$search%'";
        endif;

        // Se a variavel $order não estiver vazia ordena o retorno
        if($orderColumn != ''):
            $sql .= " ORDER BY $orderColumn $orderDir";
        endif;

        // Se as variaveis $offset e $limit não estiverem vazias limita o retorno
        if($offset != '' && $limit != ''):
            $sql .= " LIMIT $offset, $limit";
        endif;

        $sql = $this->db->query($sql);

        $dados = array();
        if($sql->rowCount()>0){
            $dados = $sql->fetchAll();
        }

        return $dados;
    }

    /**
     * Retorna os grupos filtrando pelo id da empresa
     * @param $idEmpresa
     * @return array
     */
    public function carregaDatatableGrupo($search, $orderColumn, $orderDir, $offset, $limit){
        $sql = "SELECT 
                    g.*,
                    (SELECT COUNT(gp.cod_programa) FROM z_sga_grupo_programa AS gp WHERE gp.idGrupo = g.idGrupo) AS totalProg,
                    (SELECT COUNT(gs.idUsuario) FROM z_sga_grupos as gs WHERE gs.idGrupo = g.idGrupo) AS totalUsuario
                FROM 
                    z_sga_grupo AS g ";

        $sql .= " WHERE g.idEmpresa = '".$_SESSION['empresaid']."'";

        // Se a variavel $search não estiver vazia o where no select
        if($search != ''):
            $sql .= " AND g.idLegGrupo LIKE '%$search%'";
            $sql .= " OR g.descAbrev LIKE '%$search%'";
        endif;

        // Se a variavel $order não estiver vazia ordena o retorno
        if($orderColumn != ''):
            $sql .= " ORDER BY $orderColumn $orderDir";
        endif;

        // Se as variaveis $offset e $limit não estiverem vazias limita o retorno
        if($offset != '' && $limit != ''):
            $sql .= " LIMIT $offset, $limit";
        endif;
        $sql = $this->db->query($sql);
        $array = array();
        if($sql->rowCount()>0){
            $array = $sql->fetchAll();
        }
        return $array;
    }

    /**
     * Responsável por retornar o total de registros encontrados na tabela
     * @param $table String contendo nome da tabela a se consultar
     * @param $search String contendo o filtro
     * @param $fields Array com campos a filtrar
     * @param $join String com a relação entre tabelas
     * @param $idEmpresa Int contendo o id da empresa a incluir no filtro
     * @return mixed Retorna o total de registros encontrados
     */
    public function getCountTable($table, $search, $fields, $join, $idEmpresa = ''){
        $dados = array();
        $where = '';
        $sql = "SELECT count(DISTINCT $fields[0]) AS total FROM $table ";

        $sql .= $join;

        // Se a variavel $search não estiver vazia limita cria o where no select
        if($search != '' && count($fields) > 0):
            $i = 0;
            foreach($fields as $val):
                $where .= (($i == 0) ? ' WHERE ' : ' OR ') . "$val LIKE '%$search%'";
                $i++;
            endforeach;
        endif;

        $sql .= $where;

        // Se a variavel $search não estiver vazia limita cria o where no select
        if($idEmpresa != '' && $idEmpresa != null):
            $sql .= (($where == '') ? ' WHERE ' : ' AND ') . " emp.idEmpresa = '".$_SESSION['empresaid']."'";
        endif;

        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0):
            $dados = $sql->fetchAll();
        endif;

        return $dados[0]['total'];
    }


    /**
     * Responsável por retornar o total de registros encontrados na tabela
     * @param $table String contendo nome da tabela a se consultar
     * @param $search String contendo o filtro
     * @param $fields Array com campos a filtrar
     * @param $join String com a relação entre tabelas
     * @param $idEmpresa Int contendo o id da empresa a incluir no filtro
     * @return mixed Retorna o total de registros encontrados
     */
    public function getCountTableGrupos($table, $search, $fields, $join, $idEmpresa = ''){
        $dados = array();
        $where = '';
        $sql = "SELECT count(DISTINCT $fields[0]) AS total FROM $table ";

        $sql .= $join;

        // Se a variavel $search não estiver vazia limita cria o where no select
        if($search != '' && count($fields) > 0):
            $i = 0;
            foreach($fields as $val):
                $where .= (($i == 0) ? ' WHERE ' : ' OR ') . "$val LIKE '%$search%'";
                $i++;
            endforeach;
        endif;

        $sql .= $where;

        // Se a variavel $search não estiver vazia limita cria o where no select
        if($idEmpresa != '' && $idEmpresa != null):
            $sql .= (($where == '') ? ' WHERE ' : ' AND ') . " idEmpresa = '".$_SESSION['empresaid']."'";
        endif;

        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0):
            $dados = $sql->fetchAll();
        endif;

        return $dados[0]['total'];
    }

    /**
     * @param $id
     * @return mixed Retorna um programa pelo id
     */
    public function programaById($id){
        $sql = "
            SELECT                           
                prog.z_sga_programas_id AS id,     
                prog.cod_programa AS codigo,
                prog.descricao_programa AS descricao,
                IF(prog.especific = 'N', 'Não', 'Sim') AS especifico,
                prog.ajuda_programa AS ajuda,
                prog.codigo_rotina As codigo_rotina
            FROM 
                z_sga_programas prog 
            WHERE
                prog.z_sga_programas_id IN(".(is_array($id) ? implode(',', $id) : $id). ")";
        
        //echo "<pre>";
        //die($sql);
        $sql = $this->db->query($sql);

        $dados = array();
        if($sql->rowCount()>0){
            $dados = $sql->fetchAll();
        }

        return $dados[0];
    }

    /**
     * Atualiza um programa por id
     * @param $data
     * @return array|bool Retorna true no indice 'return' caso sucesso. E false com codigo do erro no indice 'error'
     */
    public function salvarPrograma($data){
        $sql = "
            UPDATE
                z_sga_programas
            SET
                ajuda_programa = '{$data['ajuda']}'
            WHERE
                z_sga_programas_id = {$data['id']}
        ";

        try{
            $this->db->query($sql);
            return array('return' => true);
        }catch (Exception $e){
            return array(
                'return' => false,
                'error'  => $e->getMessage()
            );
        }

        return false;
    }

    /**
     * Atualiza um usuário por id.
     * @param $data
     * @return array|bool Retorna true no indice 'return' caso sucesso. E false com codigo do erro no indice 'error'
     */
    public function salvarUsuario($data){
        $sql = "
            UPDATE
                z_sga_usuarios
            SET                    
                cod_usuario    = '{$data['codusu']}',
                nome_usuario   = '{$data['nomeusu']}',
                cod_funcao     = '{$data['funcaousu']}',
                funcao         = '{$data['funcaousu']}',
                email          = '{$data['emailusu']}',
                gestor_usuario = '{$data['gestorusu']}',
                gestor_grupo   = '{$data['gestorgrupo']}',
                gestor_programa   = '{$data['gestorprograma']}'
            WHERE
                z_sga_usuarios_id = {$data['idusu']}
        ";

        try{
            $this->db->query($sql);
            return array('return' => true);
        }catch (Exception $e){
            return array(
                'return' => false,
                'error'  => $e->getMessage()
            );
        }

        return false;
    }

    /**
     * @param $idGestor
     * @param $grupos Pode conter apenas o ID do grupo ou um array com varios ids.
     * @return array|bool
     */
    public function apagaGrupoGestor($idGestor, $grupos, $cod_usuario){
        $sql = "DELETE FROM z_sga_gestor_grupo WHERE idGestor = $idGestor ";
        $sqlUpdate = "UPDATE z_sga_grupos SET gestor = 'super' WHERE gestor = '".$cod_usuario."'";

        // Verifica se grupos é tipo array. Se sim e grupos for maior que 0.
        // Percorre o array incluindo cada id na clausula WHERE
        if(is_array($grupos)):
            if(count($grupos) == 1):
                $sql .= " AND idGrupo = '{$grupos[0]}'";
                $sqlUpdate .= " AND cod_grupo = '{$grupos[0]}'";
            elseif(count($grupos) > 1):
                $sql .= " And idGrupo IN('" . implode("','", $grupos)."')";
                $sqlUpdate .= " And cod_grupo IN('" . implode("','", $grupos)."')";
            endif;
        elseif(!is_array($grupos) && $grupos != ''):
            $sql .= " AND idGrupo = '$grupos'";
            $sqlUpdate .= " AND cod_grupo = '$grupos'";
        endif;

        try{
            $this->db->query($sql);
            $this->db->query($sqlUpdate);
            return array('return' => 'true');
        }catch (Exception $e){
            return array(
                'return' => 'false',
                'error'  => $e->getMessage()
            );
        }

        return false;
    }

    /**
     * Insere gestor para grupo na tabela gestor_grupo
     * @param $data
     * @return array|bool
     */
    public function insereGrupoGestor($data){
        // retorno os ids que ja possuem gestores
        $grpAtribuidos = $this->buscaIdGruposAtribuidos($data['grupos']);

        // Caso retorne algum id. Percorre os mesmos e retira do array de inserção
        if(count($grpAtribuidos) > 0):
            // atualizo os usuarios que ja possuem gestores
            foreach ($grpAtribuidos as $val):
                $result = $this->updateGrupoGestor($data['idusu'], $val, $data['codusu']);
                if($result['return'] == 'false'):
                    /*return array(
                        'return'    => 'false',
                        'msg'       => $result['msg']
                    );*/
                endif;
            endforeach;

            // Retiro os ids dos usuarios que possuem gestor do array de usuarios
            foreach($data['grupos'] as $key => $val):
                if(in_array($data['grupos'][$key], $grpAtribuidos)):
                    unset($data['grupos'][$key]);
                endif;
            endforeach;
        endif;

        $sql = "INSERT INTO z_sga_gestor_grupo(idGestor,idGrupo) VALUES ";

        $i = 0;
        $grupos = '';
        foreach($data['grupos'] as $val):
            $grupos .= "'".$val."'";
            $grupos .= (($i + 1) < count($data['grupos'])) ? ',' : '';
            $sql .= "(".$data['idusu']. ", '" . $val."')";
            $sql .= (($i + 1) < count($data['grupos'])) ? ',' : '';
            $i++;
        endforeach;

        try{
            $this->db->query($sql);

            // Atualiza gestor do grupo na tabela z_sga_grupos
            $sql = "SELECT * FROM z_sga_grupos WHERE cod_grupo IN($grupos)";
            
            $sql = $this->db->query($sql);

            if($sql  && $sql->rowCount() > 0):
                // Atualiza tabela z_sga_grupos
                $sql = "UPDATE z_sga_grupos SET gestor = '".$data['codusu']."' WHERE cod_grupo IN($grupos) ";

                try{
                    $this->db->query($sql);
                    return array('return' => true);
                }catch (Exception $e){
                    return array(
                        'return'    => false,
                        'error'       => $e
                    );
                }

                return array('return' => 'true');
            endif;

        }catch (Exception $e){
            return array(
                'return' => 'false',
                'error'  => $e->getMessage()
            );
        }

        return false;
    }

    /**
     * Atualiza gestores dos grupos na tabela z_sga_gestor_grupo
     * @param $data
     * @return array True ou False. Caso false retorna a mensagem do erro
     */
    public function updateGrupoGestor($idGestor, $idGrupo, $codUsuario){
        $sql = "UPDATE z_sga_gestor_grupo SET idGestor = $idGestor WHERE idGrupo = '{$idGrupo}'";

        try{
            $this->db->query($sql);
        }catch (Exception $e){
            return array(
                'return'    => false,
                'msg'       => $e
            );
        }
    }

    /**
     * @param $idGestor
     * @param $usuarios Pode conter apenas o ID do usuario ou um array com varios ids.
     * @return array|bool
     */
    public function apagaUsuarioGestor($idGestor, $usuarios){
        $sql = "DELETE FROM z_sga_gestor_usuario WHERE idGestor = $idGestor ";

        // Verifica se usuarios é tipo array. Se sim e usuarios for maior que 0.
        // Percorre o array incluindo cada id na clausula WHERE
        if(is_array($usuarios)):
            if(count($usuarios) == 1):
                $sql .= " AND idUsuario = {$usuarios[0]}";
            elseif(count($usuarios) > 1):
                $sql .= " And idUsuario IN(" . implode(',', $usuarios).")";
            endif;
        elseif(!is_array($usuarios) && $usuarios != ''):
            $sql .= " AND idUsuario = $usuarios";
        endif;

        try{
            $this->db->query($sql);
            return array('return' => 'true');
        }catch (Exception $e){
            return array(
                'return' => 'false',
                'error'  => $e->getMessage()
            );
        }

        return false;
    }

    /**
     * Insere gestor para usuário na tabela gestor_usuario
     * @param $data
     * @return array|bool
     */
    public function insereUsuarioGestor($data){
        try{
            // retorno os ids que ja possuem gestores
            $usrAtribuidos = $this->buscaIdUsuariosAtribuidos($data['usuarios']);

            // Caso retorne algum id. Percorre os mesmos e retira do array de inserção
            if(count($usrAtribuidos) > 0):
                // atualizo os usuarios que ja possuem gestores
                foreach ($usrAtribuidos as $val):
                    $result = $this->updateUsuarioGestor($data['idusu'], $val, $data['codusu']);
                    if($result['return'] == 'false'):
                        /*return array(
                            'return'    => 'false',
                            'msg'       => $result['msg']
                        );*/
                    endif;
                endforeach;

                // Retiro os ids dos usuarios que possuem gestor do array de usuarios
                foreach($data['usuarios'] as $key => $val):
                    if(in_array($data['usuarios'][$key], $usrAtribuidos)):
                        unset($data['usuarios'][$key]);
                    endif;
                endforeach;
            endif;

            if(count($data['usuarios']) > 0):
                // Monta a query para inserção de gestor em cada usuarios selecionado
                $sql = "INSERT INTO z_sga_gestor_usuario(idGestor,idUsuario) VALUES ";
                $i = 0;
                foreach($data['usuarios'] as $val):
                    $sql .= "(".$data['idusu']. "," . $val.")";
                    $sql .= (($i + 1) < count($data['usuarios'])) ? ',' : '';
                    $i++;
                    
                    // Atualiza na tabelz z_sga_usuarios
                    $SqlUpt = "UPDATE z_sga_usuarios SET cod_gestor = '".$data['codusu']."' WHERE z_sga_usuarios_id = " . $val;                                        
                    
                    $this->db->query($SqlUpt);
                endforeach;   
                
                // insere os usuários que ainda não foram atribuidos
                $this->db->query($sql);                
                
            endif;

        
           
            return array('return' => 'true');
        }catch (Exception $e){
            return array(
                'return' => 'false',
                'error'  => $e->getMessage()
            );
        }

        return false;
    }

    /**
     * Atualiza gestores dos usuarios na tabela z_sga_gestor_usuario
     * @param $data
     * @return array True ou False. Caso false retorna a mensagem do erro
     */
    public function updateUsuarioGestor($idGestor, $idUsuario, $codGestor){
        $sql = "UPDATE z_sga_gestor_usuario SET idGestor = $idGestor WHERE idUsuario = $idUsuario";

        try{
            $this->db->query($sql);
            
            $sql = "
                UPDATE 
                    z_sga_usuarios 
                SET 
                    cod_gestor = '".$codGestor."' 
                WHERE z_sga_usuarios_id = $idUsuario";
            
                $this->db->query($sql);
            return array('return' => 'true');                        
        }catch (Exception $e){
            return array(
                'return'    => 'false',
                'msg'       => $e
            );
        }
    }


    /**
     * Insere gestor para modulo, rotina e programa na tabela z_sga_gest_mpr_dtsul
     * @param $data
     * @return array|bool
     */
    public function insereModuloGestor($data){
        foreach($data['modulos'] as $val):
            $exp = explode('-', $val);

            // Apaga os gestores anteriores
            //$sql = "DELETE FROM z_sga_gest_mpr_dtsul WHERE idUsuario = ".$exp[].";
            $sql = "
                INSERT INTO 
                    z_sga_gest_mpr_dtsul(
                        idUsuario, 
                        codMdlDtsul, 
                        codRotinaDtsul, 
                        codProgDtsul) 
                    VALUES (
                        ".$exp[0].", 
                        '".$exp[1]."', 
                        '".$exp[2]."', 
                        '".$exp[3]."'
                    )";
				
            try{
                $this->db->query($sql);
            }catch (Exception $e){
                //die($e->getMessage());
                return array(
                    'return' => 'false',
                    'error'  => $e->getMessage()
                );
            }
        endforeach;                
        return array('return' => 'true');
    }


    /**
     * Insere gestor para modulo, rotina e programa na tabela z_sga_gest_mpr_dtsul
     * @param $data
     * @return array|bool
     */
    public function apagaModuloGestor($idUsuario){

        // Apaga os modulos do ID passado por parametro
        $sql = "DELETE FROM z_sga_gest_mpr_dtsul WHERE idUsuario = $idUsuario";

        try{
            $this->db->query($sql);
            return array('return' => true);
        }catch (Exception $e){
            return array(
                'return' => 'false',
                'error'  => $e->getMessage()
            );
        }

        return false;
    }


    /**
     * Retorna os ids dos usuarios que ja possuem gestor
     * @param $usuarios ids de usuarios a consultar
     * @return array
     */
    public function buscaIdUsuariosAtribuidos($usuarios){
        // retorno os ids que ja possuem gestores
        $idsUsuarios = '';
        $data = array();

        // Verifica se $usuarios é tipo array. Se sim e $usuarios for maior que 0.
        // Percorre o array incluindo cada id na clausula WHERE
        if(is_array($usuarios)):
            if(count($usuarios) == 1):
                $idsUsuarios = $usuarios[0];
            elseif(count($usuarios) > 1):
                $idsUsuarios = implode(',', $usuarios);
            endif;
        elseif(!is_array($usuarios) && $usuarios != ''):
            $idsUsuarios = $usuarios;
        endif;
        $sql = "
            SELECT 
                u.nome_usuario     AS nome_usuario,                    
                u.cod_usuario      AS cod_usuario,
                userEmp.idUsuario  AS idUsuario           
            FROM 
                z_sga_usuario_empresa AS userEmp,
                z_sga_usuarios AS u ,
                z_sga_manut_funcao AS m
            WHERE 
                userEmp.idEmpresa = '".$_SESSION['empresaid']."'  
                AND userEmp.idUsuario = u.z_sga_usuarios_id
                AND userEmp.idUsuario IN(SELECT idUsuario FROM z_sga_gestor_usuario where idUsuario IN($idsUsuarios))
                AND u.funcao = m.idFuncao";

        $sql = $this->db->query($sql);

        if($sql->rowCount()>0):
            $data = $sql->fetchAll(PDO::FETCH_COLUMN, 2);
        endif;

        return $data;
    }

    /**
     * Retorna os usuários por id de gestor
     * @param $id
     * @return array
     */
    public function usuariosGestor($id){
        $sql = "
            SELECT 
                u.nome_usuario     AS nome_usuario,                    
                u.cod_usuario      AS cod_usuario,
                userEmp.idUsuario  AS idUsuario,
                userEmp.ativo
            FROM 
                z_sga_usuario_empresa AS userEmp,
                z_sga_usuarios AS u ,
                z_sga_manut_funcao AS m
            WHERE 
                userEmp.idEmpresa = '".$_SESSION['empresaid']."'  
                AND userEmp.idUsuario = u.z_sga_usuarios_id
                AND userEmp.idUsuario IN(SELECT idUsuario FROM z_sga_gestor_usuario where idGestor = $id)
                AND u.cod_funcao = m.idFuncao";

        $sql = $this->db->query($sql);
        $data = array();

        if($sql->rowCount()>0){
            $data = $sql->fetchAll();
        }
        return $data;
    }

    /**
     * Retorna os ids dos grupos que ja possuem gestor
     * @param $grupos ids de grupos a consultar
     * @return array
     */
    public function buscaIdGruposAtribuidos($grupos){
        // retorno os ids que ja possuem gestores
        $idsGrupos = '';
        $data = array();

        // Verifica se $grupos é tipo array. Se sim e $grupos for maior que 0.
        // Percorre o array incluindo cada id na clausula WHERE
        if(is_array($grupos)):
            if(count($grupos) == 1):
                $idsGrupos = "'$grupos[0]'";
            elseif(count($grupos) > 1):
                $idsGrupos = "'".implode("','", $grupos)."'";
            endif;
        elseif(!is_array($grupos) && $grupos != ''):
            $idsGrupos = "'$grupos'";
        endif;

        $sql = "
            SELECT 
                g.idLegGrupo AS idLegGrupo                    
            FROM 
                z_sga_grupo g
            WHERE                     
                g.idLegGrupo IN(SELECT idGrupo FROM z_sga_gestor_grupo WHERE idGrupo IN($idsGrupos)) 
                AND idEmpresa = '".$_SESSION['empresaid']."' 
            GROUP BY
                g.idLegGrupo";
        //die($sql);
        $sql = $this->db->query($sql);

        if($sql->rowCount()>0):
            $data = $sql->fetchAll(PDO::FETCH_COLUMN, 0);
        endif;

        return $data;
    }

    /**
     * Retorna os ids dos grupos que ja possuem gestor
     * @param $grupos ids de grupos a consultar
     * @return array
     */
    public function buscaIdGrupos($grupos){
        // retorno os ids que ja possuem gestores
        $idsGrupos = '';
        $data = array();

        // Verifica se $grupos é tipo array. Se sim e $grupos for maior que 0.
        // Percorre o array incluindo cada id na clausula WHERE
        if(is_array($grupos)):
            if(count($grupos) == 1):
                $idsGrupos = "'$grupos[0]'";
            elseif(count($grupos) > 1):
                $idsGrupos = "'".implode("','", $grupos)."'";
            endif;
        elseif(!is_array($grupos) && $grupos != ''):
            $idsGrupos = "'$grupos'";
        endif;

        $sql = "
            SELECT 
                g.z_sga_grupos_id                    
            FROM 
                z_sga_grupos g
            WHERE                     
                cod_grupo IN($idsGrupos) 
                AND idEmpresa = '".$_SESSION['empresaid']."' 
            GROUP BY
                g.idLegGrupo";
        //die($sql);
        $sql = $this->db->query($sql);

        if($sql->rowCount()>0):
            $data = $sql->fetchAll(PDO::FETCH_COLUMN, 0);
        endif;

        return $data;
    }


    /**
     * Retorna os grupos por id de gestor
     * @param $id
     * @return array
     */
    public function gruposGestor($id){
        $sql = "
            SELECT 
                grp.idLegGrupo AS idLegGrupo,
                grp.descAbrev  AS descAbrev
            FROM 
                z_sga_grupo As grp
            LEFT JOIN
                z_sga_gestor_grupo AS gst
                ON grp.idLegGrupo = gst.idGrupo
            LEFT JOIN
                z_sga_usuario_empresa AS userEmp
                ON gst.idGestor = userEmp.idUsuario             
            WHERE
                userEmp.idEmpresa = '".$_SESSION['empresaid']."'
                AND gst.idGestor = '".$id."'
            GROUP BY 
                grp.idLegGrupo";

        $sql = $this->db->query($sql);
        $data = array();

        if($sql->rowCount()>0){
            $data = $sql->fetchAll();
        }
        return $data;
    }

    /**
     * Retorna o gestor filtrando pelo id do usuario. Busca usuarios ou grupos com gestor atribuido.
     * @param $id
     * @param $tipo
     * @return array
     */
    public function ajaxBuscaGestor($id, $tipo){
        $sql = "
            SELECT 
                u.nome_usuario     AS nome_usuario,
                u.cod_usuario      AS cod_usuario,
                userEmp.idUsuario  AS idUsuario    
            FROM
                z_sga_usuarios u
            LEFT JOIN	
                z_sga_usuario_empresa AS userEmp
                ON u.z_sga_usuarios_id = userEmp.idUsuario
            WHERE 
                userEmp.idEmpresa = '".$_SESSION['empresaid']."'";
        if($tipo == 'usuario'):
            $sql .= " AND userEmp.idUsuario IN(SELECT idGestor FROM z_sga_gestor_usuario WHERE idUsuario IN($id))";
        elseif($tipo == 'grupo'):
            $sql .= " AND userEmp.idUsuario IN(SELECT idGestor FROM z_sga_gestor_grupo WHERE idGrupo IN('$id'))";
        endif;


        $sql = $this->db->query($sql);
        $data = array();

        if($sql->rowCount()>0){
            $data = $sql->fetchAll();
        }
        return $data;
    }

    /**
     * Retorna os usuários filtrando pela variável string contida na $strSearch
     * @param $strSearch String de consulta
     * @param $idGestor id usado para eliminar os usuários ja atruibuídos ao usuário
     * @return array
     */
    public function ajaxCarregaUsr($strSearch, $idGestor){
        $sql = "
            SELECT 
                u.nome_usuario     AS nome_usuario,
                u.cod_usuario      AS cod_usuario,
                userEmp.idUsuario  AS idUsuario           
            FROM 
                z_sga_usuario_empresa AS userEmp,
                z_sga_usuarios AS u ,
                z_sga_manut_funcao AS m
            WHERE 
                userEmp.idEmpresa = '".$_SESSION['empresaid']."'  
                AND userEmp.idUsuario = u.z_sga_usuarios_id
                AND userEmp.idUsuario NOT IN(SELECT idUsuario FROM z_sga_gestor_usuario WHERE idGestor = $idGestor)  
                AND u.cod_funcao = m.idFuncao 
                AND u.nome_usuario LIKE '%".$strSearch."%'";

        $sql = $this->db->query($sql);
        $data = array();

        if($sql->rowCount()>0){
            $data = $sql->fetchAll();
        }
        return $data;
    }

    /**
     * Retorna os grupos filtrando pela variável $strSearch
     * @param $strSearch
     * @param $idGestor
     * @return array
     */
    public function ajaxCarregaGrp($strSearch, $idGestor){
        $sql = "
            SELECT 
                g.idLegGrupo AS idLegGrupo,
                g.descAbrev  AS descAbrev
            FROM 
                z_sga_grupo g
            WHERE
                (idLegGrupo like '%{$strSearch}%' OR descAbrev LIKE '%{$strSearch}%')
                AND g.idLegGrupo NOT IN(SELECT idGrupo FROM z_sga_gestor_grupo WHERE idGestor = $idGestor) 
                AND idEmpresa = '".$_SESSION['empresaid']."' 
            GROUP BY
                g.idLegGrupo";

        $sql = $this->db->query($sql);
        $data = array();

        if($sql->rowCount()>0){
            $data = $sql->fetchAll();
        }
        return $data;
    }

    /**
     * Cria um novo Grupo
     * @param $nameGrupo
     * @param $descGrupo
     * @return array True ou False. Caso false retorna a mensagem do erro ocorrido
     */
    public function addGrupo($nameGrupo,$descGrupo){
        $idEmpresa = $_SESSION['empresaid'];
        $sql = "INSERT INTO z_sga_grupo SET idLegGrupo = '$nameGrupo', descAbrev = '$descGrupo',idEmpresa = '$idEmpresa'";

        try{
            $sql = $this->db->query($sql);

            return array('return' => $this->db->lastInsertId());
        }catch (Exception $e){
            return array(
                'return' => false,
                'error'  => $e->getMessage()
            );
        }
    }

    public function clonaPrograma($idGrupo,$idGrupoClone){
      $sql = "INSERT  INTO z_sga_grupo_programa  SELECT null,cod_grupo,nome_grupo,gestor,cod_programa,$idGrupo,idPrograma FROM z_sga_grupo_programa where idGrupo = $idGrupoClone";
      try{
            $sql = $this->db->query($sql);
            return array('return' =>true);
        }catch (Exception $e){
            return array(
                'return' => false,
                'error'  => $e->getMessage()
            );
        }
    }
    public function clonaUsuario($idGrupo,$idGrupoClone){
      $sql = "
        INSERT INTO 
            z_sga_grupos  
        SELECT 
            null,
            cod_grupo,
            desc_grupo,
            gestor,
            cod_usuario,
            $idGrupo,
            idUsuario 
        FROM
            z_sga_grupos 
        where 
            idGrupo = $idGrupoClone";
      try{
            $sql = $this->db->query($sql);
            return array('return' =>true);
        }catch (Exception $e){
            return array(
                'return' => false,
                'error'  => $e->getMessage()
            );
        }
    }

    public function carregaUsrGrupo($idGrupo)
    {
        $idEmpresa = $_SESSION['empresaid'];
        $sql = "
            SELECT 
                g.z_sga_grupos_id,    
                u.z_sga_usuarios_id, 
                u.nome_usuario,
                u.cod_usuario,
                u.idUsrFluig,
                u.cod_gestor,
                fun.cod_funcao, 
                emp.ativo 
            FROM
                z_sga_grupos as g
            LEFT JOIN
                z_sga_usuarios u
                ON g.idUsuario = u.z_sga_usuarios_id
            LEFT JOIN
                z_sga_usuario_empresa as emp
                ON emp.idUsuario = g.idUsuario
            LEFT JOIN
                z_sga_manut_funcao as fun
                ON u.cod_funcao = fun.idFuncao
            WHERE
                emp.idEmpresa = $idEmpresa	
                AND g.idGrupo = $idGrupo
        ";
        $sql = $this->db->query($sql);
        $array = array();
        if($sql->rowCount()>0){
            $array = $sql->fetchAll();
        }
        return $array;
    }

    public function totalUserManutencao($idGrupo)
    {
        $sql = "
            select * from z_sga_grupos where idGrupo = $idGrupo
        ";

        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0):
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        else:
            return [];
        endif;
    }

    public function carregaDadosGrupo($idGrupo){
        $idEmpresa = $_SESSION['empresaid'];
        $sql = "
            SELECT 
                g.z_sga_grupos_id, 
                u.z_sga_usuarios_id, 
                u.nome_usuario,
                u.cod_usuario,
                u.idUsrFluig,
                u.cod_gestor,
                fun.cod_funcao,                 
                emp.ativo from 
                z_sga_grupos as g,
                z_sga_usuarios as u,
                z_sga_usuario_empresa as emp,
                z_sga_manut_funcao as fun
            where emp.idUsuario = u.z_sga_usuarios_id
                and fun.idFuncao = u.cod_funcao
                and emp.idEmpresa = '$idEmpresa'
                and g.idUsuario = u.z_sga_usuarios_id 
                and idGrupo = '$idGrupo'";
        $sql = $this->db->query($sql);
        $array = array();
        if($sql->rowCount()>0){
            $array = $sql->fetchAll();
        }
        return $array;
    }


    public function carregaGestorGrupo($idGrupo){
        $sql = "SELECT distinct(g.idGrupo), g.cod_grupo,g.desc_grupo,g.gestor, u.nome_usuario 
                        FROM z_sga_grupos as g,
                        z_sga_usuarios as u 
                        where idGrupo = '$idGrupo' and u.cod_usuario = g.gestor";
        $sql = $this->db->query($sql);
        $array = array();
        if($sql->rowCount()>0){
            $array = $sql->fetch();
        }
        return $array;
    }

    public function carregaDescGrupo($idGrupo){
        $sql = "SELECT * FROM z_sga_grupo where idGrupo = '$idGrupo'";
        $sql = $this->db->query($sql);
        $array = array();
        if($sql->rowCount()>0){
            $array = $sql->fetch();
        }
        return $array;
    }


    public function carregaDadosGrupoProg($idGrupo){        
        $sql = "
            SELECT 
                prog.z_sga_grupo_programa_id,
                p.z_sga_programas_id,
                prog.idGrupo, 
                p.descricao_programa, 
                p.cod_programa, 
                IF(p.ajuda_programa = '','Não Cadstrado', p.ajuda_programa ) AS ajuda,
                p.descricao_rotina, 
                p.especific 
            FROM 
                z_sga_grupo_programa AS prog 
            JOIN 
                z_sga_programas AS p 
                ON p.z_sga_programas_id = prog.idPrograma 
            WHERE 
                prog.idGrupo = '$idGrupo'";
        //echo "<pre>";
        //die($sql);
        $sql = $this->db->query($sql);
        $array = array();
        if($sql->rowCount()>0){
            $array = $sql->fetchAll();
        }
        return $array;
    }

    /**
     * Valida se já existe um grupo com nome igual a $nameGrupo. se não existir busca grupos com nomes parecidos
     * @param $nameGrupo
     * @param $descAbrev
     * @return array Existe e os dados do grupo existente, caso ja exista um grupo com mesmo nome.
     * True para nomes parecidos. False para erro na execução do método
     */
    public function validaGrupoExistente($nameGrupo, $descAbrev)
    {
        // Valida se já existe
        $sql = "
            SELECT 
                idLegGrupo,
                descAbrev
            FROM 
                z_sga_grupo
            WHERE
                idEmpresa = 4
                AND idLegGrupo = '$nameGrupo'";

            try{
                $sql = $this->db->query($sql);

                // Se existir retorna os dados do grupo
                if($sql->rowCount() > 0):
                    return array(
                        'return'    => 'existe',
                        'data'      => $sql->fetchAll()
                    );
                endif;
            }catch (Exception $e){
                return array(
                    'return'    => false,
                    'error'     => $e->getMessage()
                );
            }

        // Busca grupos com dados parecidos
        $sql = "
            SELECT 
                idLegGrupo,
                descAbrev
            FROM 
                z_sga_grupo
            WHERE
                idEmpresa = 4
                AND idLegGrupo LIKE '$nameGrupo%'
                OR descAbrev LIKE '$nameGrupo%'
                OR idLegGrupo LIKE '$descAbrev'
                OR descAbrev LIKE '$descAbrev%'
        ";

        try{
            $sql = $this->db->query($sql);

            if($sql->rowCount() > 0):
                return array(
                    'return'    => true,
                    'data'      => $sql->fetchAll()
                );
            else:
                return array('return' => 'salvar');
            endif;

        }catch (Exception $e){
            return array(
                'return'    => false,
                'error'     => $e->getMessage()
            );
        }
    }

    /**
     * Retorna lista de programas filtrando pela string contida na variavel $idProg
     * @param $idProg
     * @return array
     */
    public function carregaProgramas($idProg){
        $idEmpresa = $_SESSION['empresaid'];
        $sql = "
            SELECT 
                * 
            FROM 
                z_sga_programas as prog
            WHERE 
                cod_programa LIKE '".$idProg."%' 
                OR descricao_programa LIKE '".$idProg."%' 
            LIMIT 10" ;

        $sql = $this->db->query($sql);

        $array = array();
        if($sql->rowCount()>0){
            $array = $sql->fetchAll();
        }
        return $array;
    }


    /**
     * @param $grupos Pode conter apenas o ID do grupo ou um array com varios ids.
     * @return array|bool
     */
    public function apagaProgramaGrupo($idProgramas){

        $sql = "DELETE FROM z_sga_grupo_programa ";

        // Verifica se $idProgramas é tipo array. Se sim e $idProgramas for maior que 0.
        // Percorre o array incluindo cada id na clausula WHERE
        if(is_array($idProgramas)):
            if(count($idProgramas) == 1):
                $sql .= " WHERE z_sga_grupo_programa_id = '{$idProgramas[0]}'";
            elseif(count($idProgramas) > 1):
                $sql .= " WHERE z_sga_grupo_programa_id IN('" . implode("','", $idProgramas)."')";
            endif;
        elseif(!is_array($idProgramas) && $idProgramas != ''):
            $sql .= " WHERE z_sga_grupo_programa_id = '$idProgramas'";
        endif;

        try{
            $this->db->query($sql);
            $this->atualizaVMUsuarios();
            return array('return' => 'true');
        }catch (Exception $e){
            return array(
                'return' => 'false',
                'error'  => $e->getMessage()
            );
        }

        return false;
    }
        

    /**
     * @param $grupos Pode conter apenas o ID do usuario ou um array com varios ids.
     * @return array|bool
     */
    public function apagaUsuarioGrupo($idGrupo, $usuarios)
    {                
        $sql = "
            DELETE FROM
                z_sga_grupos             
            WHERE
                cod_usuario IN('".(is_array($usuarios) ? implode("', '", $usuarios) : $usuarios)."')
                AND idGrupo = $idGrupo";    
                
        try{
            $sql = $this->db->query($sql);
            $this->atualizaVMUsuarios();
            return array('return' => true);
        }catch (Exception $e){
            return array(
                'return' => false,
                'error'  => $e->getMessage()
            );
        }                       
    }
    
    /**
     * Apaga relacionamento de usuário a grupo
     * @param $idGrupo
     * @param $idUsuario
     * @return array|bool
     */
    public function apagaUsuarioGrupoByGrupo($idGrupo, $idUsuario){
        $sql = "
            DELETE FROM 
                z_sga_grupos 
            WHERE
                idGrupo = $idGrupo
                AND idUsuario = $idUsuario";
                    
        //echo $sql."<br>";
        //die($sql);
        try{
            $this->db->query($sql);
            $this->atualizaVMUsuarios();
            return array('return' => 'true');
        }catch (Exception $e){
            return array(
                'return' => 'false',
                'error'  => $e->getMessage()
            );
        }

        return false;
    }

    public function addUsuarioGrupoExecbo($idGrupo, $usuarios)
    {       
        $sqlGrupo = "SELECT * FROM z_sga_grupo where idGrupo = '$idGrupo'";
        $sqlGrupo = $this->db->query($sqlGrupo);
        
        if($sqlGrupo->rowCount()>0){
            $sqlGrupo = $sqlGrupo->fetch();

            $idLegGrupo = $sqlGrupo['idLegGrupo'];
            $descAbrev = $sqlGrupo['descAbrev'];            

            try{
                $sql = "
                    INSERT INTO 
                    	z_sga_grupos (cod_grupo, desc_grupo, gestor, cod_usuario, idGrupo, idUsuario, idEmpresa) 
                    SELECT
                        '$idLegGrupo' 	AS cod_grupo,
                        '$descAbrev'	AS desc_grupo,
                        'super'         AS gestor,
                        cod_usuario,
                        '$idGrupo'	AS idGrupo,
                        z_sga_usuarios_id AS idUsuario,
                        '".$_SESSION['empresaid']."' AS idEmpresa
                    FROM
                        z_sga_usuarios
                    WHERE
                        cod_usuario IN('".(is_array($usuarios) ? implode("', '", $usuarios) : $usuarios)."')
                    GROUP BY
                        z_sga_usuarios_id";
              
                $sql = $this->db->query($sql);
                $this->atualizaVMUsuarios();

                return array('return' => true);
            }catch (Exception $e){
                return array(
                    'return' => false,
                    'error'  => $e->getMessage()
                );
            }
        }                        
    }

    /**
     * Atualiza tabela z_sga_vm_usuarios_refresh
     */
    public function atualizaVMUsuarios()
    {
        $sql = "            
            UPDATE
                z_sga_vm_usuarios_refresh
            SET                            
                atualiza = 1";

        $this->db->query($sql);

    }
    
    public function addUsuarioGrupo($idGrupo,$idUsr){
        $sqlGrupo = "SELECT * FROM z_sga_grupo where idGrupo = '$idGrupo'";
        $sqlGrupo = $this->db->query($sqlGrupo);
        $array = array();
        if($sqlGrupo->rowCount()>0){
            $sqlGrupo = $sqlGrupo->fetch();

            $idLegGrupo = $sqlGrupo['idLegGrupo'];
            $descAbrev = $sqlGrupo['descAbrev'];
            $idGrupo  = $sqlGrupo['idGrupo'];

            $sqlUsr = "SELECT * FROM z_sga_usuarios where z_sga_usuarios_id = '$idUsr'";
            $sqlUsr = $this->db->query($sqlUsr);
            if($sqlUsr->rowCount()>0){
                $sqlUsr = $sqlUsr->fetch();

                $cod_usuario = $sqlUsr['cod_usuario'];
                $idUsr = $sqlUsr['z_sga_usuarios_id'];
                $gestor = "super";

                // Valida se já existe usuario atribuido para o grupo
                $sql = "SELECT cod_usuario FROM z_sga_grupos WHERE idUsuario = '$idUsr' AND idGrupo = $idGrupo ";
                try{
                    $sql = $this->db->query($sql);
                    
                    if($sql->rowCount() > 0):
                        return array(
                            'return'    => false,
                            'error'     => 'Usuário ja atribuído para esse grupo'
                        );
                    endif;
                }catch (Exception $e){
                    return array(
                        'return'    => false,
                        'error'     => $e->getMessage()
                    );
                }

                $sql = "INSERT INTO z_sga_grupos SET cod_grupo = '$idLegGrupo', desc_grupo = '$descAbrev',gestor = '$gestor',cod_usuario = '$cod_usuario',idGrupo =  '$idGrupo',idUsuario = '$idUsr'";
                try{
                    $sql = $this->db->query($sql);
                    $sql = $this->atualizaVMUsuarios();
                    return array('return' => true);
                }catch (Exception $e){
                    return array(
                        'return' => false,
                        'error'  => $e->getMessage()
                    );
                }
            }
        }
    }

    /**
     * Inativa usuario no sistema
     * @param $idUsuario
     * @return array
     */
    public function inativaUsuario($idUsuario)
    {
        $sql = "
            UPDATE 
                z_sga_usuarios
            SET
                ativo = 0
            WHERE
                z_sga_usuarios_id = $idUsuario
        ";

        try{
            $this->db->query($sql);
            
            $sql = "
                UPDATE 
                    z_sga_usuario_empresa
                SET
                    ativo = 0
                WHERE
                    idUsuario = $idUsuario";
            
            $this->db->query($sql);
            
            return array('return' => true);
        }catch (Exception $e){
            return array(
                'return'    => false,
                'error'     => $e->getMessage()
            );
        }

    }

    public function addProgramaGrupo($idGrupo, $idProg){
        $sqlGrupo = "SELECT * FROM z_sga_grupo where idGrupo = $idGrupo";        
        $sqlGrupo = $this->db->query($sqlGrupo);

        $array = array();
        if($sqlGrupo->rowCount()>0){
            $sqlGrupo = $sqlGrupo->fetch();
            $idLegGrupo = $sqlGrupo['idLegGrupo'];
            $idGrupo = $sqlGrupo['idGrupo'];                                                                    
                                    
            try{
                $sql = "
                    INSERT INTO 
                        z_sga_grupo_programa (cod_grupo, nome_grupo, gestor, cod_programa, idGrupo, idPrograma, idEmpresa) 
                    SELECT                        
                        '$idLegGrupo' AS cod_grupo, #cod_grupo
                        '$idLegGrupo' AS nome_grupo, #nome_grupo                        
                        gestor AS gestor,
                        cod_programa AS cod_programa,	
                        '$idGrupo' AS idGrupo, #idGrupo                        
                        idPrograma AS idPrograma,
                        '".$_SESSION['empresaid']."' AS idEmpresa
                    FROM
                        z_sga_grupo_programa
                    WHERE 
                        cod_programa IN('".implode("', '", $idProg)."')
                    GROUP BY
                        idPrograma";
                
                $sql = $this->db->query($sql);
                $this->atualizaVMUsuarios();
                return array('return' => true);
            }catch (Exception $e){
                return array(
                    'return' => false,
                    'error'  => $e->getMessage()
                );
            }
        }       
    }
    
    public function apagaProgramaGrupoExcebo($idGrupo, $idProg){
        $sqlGrupo = "SELECT * FROM z_sga_grupo where idGrupo = '$idGrupo'";
        $sqlGrupo = $this->db->query($sqlGrupo);

        $array = array();
        if($sqlGrupo->rowCount()>0){
            $sqlGrupo = $sqlGrupo->fetch();
            $idLegGrupo = $sqlGrupo['idLegGrupo'];
            $idGrupo = $sqlGrupo['idGrupo'];                                                                    
                                    
            try{
                $sql = "
                    DELETE FROM
                        z_sga_grupo_programa
                    WHERE
                        idGrupo = $idGrupo
                        AND cod_programa IN('".(is_array($idProg) ? implode("', '", $idProg) : $idProg)."')";
                
                $sql = $this->db->query($sql);
                $this->atualizaVMUsuarios();
                return array('return' => true);
            }catch (Exception $e){
                return array(
                    'return' => false,
                    'error'  => $e->getMessage()
                );
            }
        }       
    }

    /**
     * Carrega rotinas de modulo seleciona
     * @return array
     */
    public function ajaxCarregaModuloProgByRotina($idRotina){
        $idEmpresa = $_SESSION['empresaid'];

        $sql = "
            SELECT DISTINCT
                m.cod_modul_dtsul AS id,
                m.des_mudul_dtsul AS descModulo
            FROM 
                z_sga_modul_dtsul m
            LEFT JOIN
                z_sga_programas p
                ON m.cod_modul_dtsul = p.cod_modulo
            LEFT JOIN
                z_sga_programa_empresa pe
                ON p.z_sga_programas_id = pe.idPrograma
            WHERE
                p.codigo_rotina = $idRotina
                AND pe.idEmpresa = $idEmpresa
            ORDER BY
                m.des_mudul_dtsul";

        $sql = $this->db->query($sql);

        $array = array();
        if($sql->rowCount()>0){
            $array = $sql->fetchAll(PDO::FETCH_ASSOC);
        }
        return $array;
    }

    /**
     * Carrega rotinas de modulo
     * @return array
     */
    public function ajaxCarregaRotinaProgByModulo($idModulo){
        $idEmpresa = $_SESSION['empresaid'];

        $sql = "
            SELECT
                p.codigo_rotina AS id,
                p.descricao_rotina AS descRotina
            FROM
                z_sga_programas p
            RIGHT JOIN
                z_sga_programa_empresa pe
                ON p.z_sga_programas_id = pe.idPrograma
            WHERE
                p.cod_modulo = '$idModulo'
                AND pe.idEmpresa = $idEmpresa
            GROUP BY
                p.codigo_rotina            
            ORDER BY
                p.descricao_rotina";

        $sql = $this->db->query($sql);

        $array = array();
        if($sql->rowCount() > 0){
            $array = $sql->fetchAll(PDO::FETCH_ASSOC);
        }
        return $array;
    }

    /**
     * Carrega modulos
     * @return array
     */
    public function ajaxCarregaModulos($search){
        $idEmpresa = $_SESSION['empresaid'];

        $sql = "
            SELECT DISTINCT
                m.cod_modul_dtsul AS id,
                m.des_mudul_dtsul As text               
            FROM 
                z_sga_modul_dtsul m
            LEFT JOIN
                z_sga_programas p
                ON m.cod_modul_dtsul = p.cod_modulo
            RIGHT JOIN
                z_sga_programa_empresa pe
                ON p.z_sga_programas_id = pe.idPrograma
            WHERE
                m.cod_modul_dtsul LIKE '$search%'
                OR m.des_mudul_dtsul LIKE '$search%'
                OR m.cod_sist_dtsul LIKE '$search%'
                AND pe.idEmpresa = $idEmpresa";

        $sql = $this->db->query($sql);

        $array = array();
        if($sql->rowCount()>0){
            $array = $sql->fetchAll(PDO::FETCH_ASSOC);
        }
        return $array;
    }

    /**
     * Carrega modulos
     * @return array
     */
    public function ajaxCarregaProgramas($search){
        $idEmpresa = $_SESSION['empresaid'];

        $sql = "
            SELECT DISTINCT
                p.cod_programa AS id,
                p.descricao_programa AS text
            FROM 
                z_sga_programas p
            RIGHT JOIN
                z_sga_programa_empresa pe
                ON p.z_sga_programas_id = pe.idPrograma
            WHERE
                p.cod_programa LIKE '$search%'
                OR p.descricao_programa LIKE '$search%'
                AND pe.idEmpresa = $idEmpresa";

        $sql = $this->db->query($sql);

        $array = array();
        if($sql->rowCount()>0){
            $array = $sql->fetchAll(PDO::FETCH_ASSOC);
        }
        return $array;
    }

    /**
     * Busca as rotinas filtrando pela string digitada no campo
     * @param $search
     * @return array
     */
    public function ajaxCarregaRotinas($search)
    {
        $idEmpresa = $_SESSION['empresaid'];

        $sql = "
            SELECT
                p.codigo_rotina AS id,
                p.descricao_rotina AS descRotina
            FROM
                z_sga_programas p
            RIGHT JOIN
                z_sga_programa_empresa pe
                ON p.z_sga_programas_id = pe.idPrograma
            WHERE              
                pe.idEmpresa = $idEmpresa
                AND p.descricao_rotina LIKE '$search%'
            GROUP BY
                p.codigo_rotina
        ";

        $sql = $this->db->query($sql);

        $array = array();
        if($sql->rowCount()>0){
            $array = $sql->fetchAll(PDO::FETCH_ASSOC);
        }
        return $array;
    }

    /**
     * Carrega rotinas dos modulos
     * @param $idModulo
     * @return array
     */
    public function carregaModuloRotinaByProg($codProg){
        $idEmpresa = $_SESSION['empresaid'];
        $sql = "
            SELECT 
                m.cod_modul_dtsul,                
                m.des_mudul_dtsul,
                p.codigo_rotina,
                p.descricao_rotina
            FROM 
                z_sga_modul_dtsul m
            LEFT JOIN
                z_sga_programas p
                ON m.cod_modul_dtsul = p.cod_modulo
            RIGHT JOIN
                z_sga_programa_empresa pe
                ON p.z_sga_programas_id = pe.idPrograma
            WHERE
                p.cod_programa = '".$codProg."'
                AND pe.idEmpresa = $idEmpresa";

        $sql = $this->db->query($sql);

        $array = array();
        if($sql->rowCount()>0){
            $array = $sql->fetch(PDO::FETCH_ASSOC);
        }
        return $array;
    }

    /**
     * Busca por gestor de modulo, rotina e programa
     * @param $codModulo
     * @param $codRotina
     * @param $codPrograma
     * @param $idUsuario
     * @return bool
     */
    public function ajaxValidaGestorPrograma($codModulo, $codRotina, $codPrograma)
    {
        $idEmpresa = $_SESSION['empresaid'];
        $sql = "
            SELECT 
                u.nome_usuario,
                u.z_sga_usuarios_id AS idUsuario
            FROM 
                z_sga_gest_mpr_dtsul mpr
            LEFT JOIN
                z_sga_usuarios u
                ON mpr.idUsuario = u.z_sga_usuarios_id
            LEFT JOIN
                z_sga_usuario_empresa ue
                ON ue.idUsuario = mpr.idUsuario
            WHERE                            
                codMdlDtsul = '$codModulo'
                AND codProgDtsul = '$codPrograma'
                AND codRotinaDtsul = '$codRotina'
                AND ue.idEmpresa = $idEmpresa";

        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0):
            return $sql->fetch(PDO::FETCH_ASSOC);
        else:
            return array();
        endif;
    }

    /**
     * Valida se existe revisão em aberto para usuario. Segundo o parametro id 
     * @param type $id
     * @return type
     */
    public function usuarioPossuiRevisao($id)
    {
        $sql = "
            SELECT                 
		COUNT(*) AS total
            FROM 
                z_sga_fluxo_solicitacao s
            LEFT JOIN
                z_sga_fluxo_agendamento_acesso a
                ON a.idAgendamento = s.idAgendamento	
            WHERE 
                a.idUsuario = $id
                AND s.status = 1";
        
        return $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Retorna os módulos, rotinas e programas do gestor pelo id
     * @param type $id
     * @return type
     */
    public function mprGestor($id)
    {
        $sql = "
            SELECT DISTINCT
                mpr.idGestMPR id, 
                IF(mpr.codMdlDtsul <> '*', (SELECT CONCAT(cod_modul_dtsul, ' - ',des_mudul_dtsul) FROM z_sga_modul_dtsul m WHERE m.cod_modul_dtsul = mpr.codMdlDtsul LIMIT 1), 'TODOS' ) AS codMod,
                IF(mpr.codProgDtsul <> '*', (SELECT CONCAT(cod_programa, ' - ',descricao_programa) FROM z_sga_programas p WHERE p.cod_programa = mpr.codProgDtsul LIMIT 1), 'TODOS' ) AS codProg,
                IF(mpr.codRotinaDtsul <> '*', (SELECT CONCAT(codigo_rotina, ' - ',descricao_rotina) FROM z_sga_programas p WHERE p.codigo_rotina = mpr.codRotinaDtsul LIMIT 1), 'TODOS' ) AS codRot
            FROM 
                z_sga_gest_mpr_dtsul mpr
            LEFT JOIN
                z_sga_usuario_empresa ue
                ON ue.idUsuario = mpr.idUsuario
            WHERE
                mpr.idUsuario = $id";
        $sql = $this->db->query($sql);
        
        if($sql->rowCount() > 0):
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        else:
            return array();
        endif;        
    }

    /**
     * Remove gestor de módulo, rotina e programa
     * @param $idMod
     * @return array|bool
     */
    public function apagaMPRGestor($idMod = [], $idUsuario = ''){
        if(!empty($idMod) && $idUsuario == ''):
            $sql = "DELETE FROM z_sga_gest_mpr_dtsul WHERE idGestMPR IN(".implode(',', $idMod).")";
        elseif($idMod == '' && $idUsuario != ''):
            $sql = "DELETE FROM z_sga_gest_mpr_dtsul WHERE idUsuario = $idUsuario";
        endif;
        
        
        try{
            $this->db->query($sql);
            return array('return' => 'true');
        }catch (Exception $e){
            return array(
                'return' => 'false',
                'error'  => $e->getMessage()
            );
        }

        return false;
    }
    
    /**
     * Valida se já existe solicitação ou revisão em aberto para usuário informado
     * @param type $idUsuario
     * @return boolean
     */
    public function existeSolicitacaoAberta($idUsuario)
    {
        $sql = "
            SELECT 
                d.idSolicitacao	
            FROM 
                z_sga_fluxo_documento d
            LEFT JOIN
                z_sga_fluxo_solicitacao s
                ON s.idSolicitacao = d.idSolicitacao
            WHERE 
                #d.idSolicitacao = 1
                s.status = 1
                AND JSON_EXTRACT(d.documento, \"$.idusuario\") = '".$idUsuario."'";
        
        $resSql = $this->db->query($sql);
        
        if($resSql->rowCount() > 0):
            return true;
        else:
            return false;
        endif;
    }
    
    /**
     * Valida se já existe solicitação ou revisão em aberto para grupo informado
     * @param type $idGrupo
     * @return boolean
     */
    public function existeSolicitacaoAbertaGrupo($idGrupo)
    {
        $sql = "
            SELECT 
                GROUP_CONCAT(s.idSolicitacao) AS ids,
                JSON_SEARCH(
                    documento, 
                    'one',
                    \"$idGrupo\", 
                    NULL, 
                    '$.grupos[*].idGrupo'
                ) AS path
            FROM 
                z_sga_fluxo_documento d
            LEFT JOIN
                z_sga_fluxo_solicitacao s
                ON s.idSolicitacao = d.idSolicitacao
            WHERE
                s.status = 1";
        
        $resSql = $this->db->query($sql);
        
        if($resSql->rowCount() > 0):
            $dados = $resSql->fetch(PDO::FETCH_ASSOC);

            foreach($dados as $key => $val):
                if($key == 'path' && $val != ''):
                    return array(
                        'return' => true,
                        'dados'  => $val['idSolicitacao']
                    );
                endif;
            endforeach;

            return array('return' => false);
        else:
            return false;
        endif;
    }
    
    /**
     * Retorna os dados do usuario
     * @param type $idUsuario
     * @return boolean
     */
    public function dadosUsuario($idUsuario)
    {
        $sql = "
            SELECT 
                u.cod_usuario,
                u.nome_usuario
            FROM 
                z_sga_usuarios u
            LEFT JOIN
                z_sga_usuario_empresa e
                ON e.idUsuario = u.z_sga_usuarios_id
            WHERE 
                e.idEmpresa = ".$_SESSION['empresaid']."
                AND u.z_sga_usuarios_id = $idUsuario";
        
        $resSql = $this->db->query($sql);
        
        if($resSql->rowCount() > 0):
            return $resSql->fetch(PDO::FETCH_ASSOC);
        else:
            return false;
        endif;
    }
    
}