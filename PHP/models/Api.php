
<?php
class Api extends Model {

    public function __construct(){
        parent::__construct();
    }

    public function getEmpresa($idEmpresa){
        $sql = "SELECT * FROM z_sga_empresa where idLegEmpresa = '$idEmpresa'";

        $sql = $this->db->query($sql);
        $data = array();
        if($sql->rowCount()>0){
            $data = $sql->fetch();
        }
        return $data;
    }

    public function updateGestorUsuario($codUsuario, $codGestor){
        $sql = "UPDATE z_sga_usuarios set cod_gestor = '$codGestor' where cod_usuario = '$codUsuario' ";
        $sql = $this->db->query($sql);
        return $sql->rowCount();

    }

    public function login($usuario,$senha){
        $sql = "SELECT * FROM z_sga_param_login where login = '$usuario' AND senha = '$senha' ";
        $sql = $this->db->query($sql);

        if($sql->rowCount()>0){
            return true;
        }else{
            return false;
        }
    }


    /**
     * Retorna os funções
     * @return type
     */
    public function getFuncoes($json)
    {
        $sql = "
            SELECT 
                idFuncao AS cod_funcao,
                descricao
            FROM
                z_sga_manut_funcao
            WHERE
                idFuncao >= $json->inicial AND idFuncao <= $json->final 
            ORDER BY
                descricao ASC";

        try{
            $sql = $this->db->query($sql);

            if($sql->rowCount() > 0):
                $sql = $sql->fetchAll(PDO::FETCH_ASSOC);

                return array(
                    'return' => 'sucesso',
                    'msg'    => '',
                    'funcoes' => $sql
                );
            else:
                return array(
                    'return' => 'erro',
                    'funcoes' => [],
                    'msg'    => 'Nenhuma funcao encontrado'
                );
            endif;
        } catch (Exception $e) {
            return array(
                'return' => 'erro',
                'msg'    => $e->getMessage()
            );
        }
    }

    /**
     * Retorna os gestores
     * @return type
     */
    public function getGestores($json)
    {
        $sql = "
            SELECT DISTINCT
                #z_sga_usuarios_id AS idUsuario,
                cod_usuario,
                nome_usuario
            FROM
                z_sga_usuarios u            
            LEFT JOIN
                z_sga_gestor_usuario gu
                ON u.z_sga_usuarios_id = gu.idGestor
            LEFT JOIN
                z_sga_usuario_empresa e
                ON e.idUsuario = u.z_sga_usuarios_id
            WHERE
                u.gestor_usuario = 'S'	
                AND e.ativo = 1
                AND u.cod_usuario >= '$json->inicial' AND u.cod_usuario <= '$json->final'";
        try{
            $sql = $this->db->query($sql);

            if($sql->rowCount() > 0):
                $sql = $sql->fetchAll(PDO::FETCH_ASSOC);

                return array(
                    'return' => 'sucesso',
                    'gestores' => $sql,
                    'msg'    => ''
                );
            else:
                return array(
                    'return' => 'erro',
                    'gestores' => [],
                    'msg'    => 'Nenhum gestor encontrado'
                );
            endif;
        } catch (Exception $e) {
            return array(
                'return' => 'erro',
                'msg'    => $e->getMessage()
            );
        }
    }

    /**
     * Insere gestor a um usuario
     * @param type $json
     * @return type
     */
    public function PostGestores($json)
    {
        // Valida se existe usuario
        $sqlUser = "
            SELECT
                z_sga_usuarios_id AS idUsuario
            FROM
                z_sga_usuarios
            WHERE
                cod_usuario = '$json->cod_usuario'";

        try{

            $sqlUser = $this->db->query($sqlUser);

            if($sqlUser->rowCount() > 0):
                $sqlUser = $sqlUser->fetch(PDO::FETCH_ASSOC);

                // Valida se existe gestor e retorna seus dados caso sim
                $sqlGestor = "
                    SELECT
                       z_sga_usuarios_id AS idGestor
                    FROM
                        z_sga_usuarios
                    WHERE
                        cod_usuario = '$json->cod_gestor'";
                $sqlGestor = $this->db->query($sqlGestor);

                if($sqlGestor->rowCount() > 0):
                    $sqlGestor = $sqlGestor->fetch(PDO::FETCH_ASSOC);

                    try{
                        // Inicia Transaction
                        $this->db->beginTransaction();

                        // Relaciona gestor a usuario na tabela z_sga_usuarios
                        $sqlUpdateUsuarios = "
                            UPDATE
                                z_sga_usuarios
                            SET
                                cod_gestor = '" . $json->cod_gestor . "'
                            WHERE
                                z_sga_usuarios_id = " . $sqlUser['idUsuario'];
                        $this->db->query($sqlUpdateUsuarios);

                        // Tenta atualizar na tabela z_sga_gestor_usuario
                        $sqlUpdateGestor = "
                            UPDATE
                                z_sga_gestor_usuario
                            SET
                                idGestor = '" . $sqlGestor['idGestor'] . "',
                                idUsuario = '" . $sqlUser['idUsuario'] . "'
                            WHERE
                                idUsuario = " . $sqlUser['idUsuario'];
                        $sqlUpdateGestor = $this->db->query($sqlUpdateGestor);

                        // Não atualizou, faz a inserção
                        if($sqlUpdateGestor->rowCount() == 0):
                            $sqlInsertGestor = "
                                INSERT INTO
                                    z_sga_gestor_usuario(idGestor, idUsuario) 
                                VALUES(".$sqlGestor['idGestor'].",".$sqlUser['idUsuario'].")";
                            $this->db->query($sqlInsertGestor);
                        endif;

                        // Executa commit dos inserts e updates
                        $this->db->commit();

                        return array(
                            'return' => 'sucesso',
                            'msg'    => "Usuario $json->cod_gestor cadastrado como gestor do usuario $json->cod_usuario com sucesso"
                        );
                    } catch (Exception $e) {
                        // Desfaz os updates e inserts 
                        $this->db->rollback();

                        return array(
                            'return' => 'erro',
                            'msg'    => $e->getMessage()
                        );
                    }
                else:
                    return array(
                        'return' => 'erro',
                        'msg'    => 'Gestor nao encontrado'
                    );
                endif;
            else:
                return array(
                    'return' => 'erro',
                    'msg'    => 'Usuário ou gestor nao encontrado',
                );
            endif;

        } catch (Exception $e) {
            return array(
                'return' => 'erro',
                'msg'    => $e->getMessage()
            );
        }

    }

    /**
     * Elimina gestor de usuario
     * @param type $json
     * @return type
     */
    public function deleteGestores($json)
    {
        // Busca o id do usuario
        $sqlUser = "
            SELECT
                z_sga_usuarios_id AS idUsuario
            FROM
                z_sga_usuarios
            WHERE
                cod_usuario = '$json->cod_usuario'";

        try{
            $sqlUser = $this->db->query($sqlUser);

            if($sqlUser->rowCount() > 0):
                // Busca o id do gestor
                $sqlGestor = "
                    SELECT
                        z_sga_usuarios_id AS idGestor
                    FROM
                        z_sga_usuarios
                    WHERE
                        cod_usuario = '$json->cod_gestor'";
                $sqlGestor = $this->db->query($sqlGestor);

                if($sqlGestor->rowCount() > 0):
                    try{
                        // Inicia Transaction
                        $this->db->beginTransaction();

                        // Atualiza na tabela z_sga_usuarios
                        $sqlUpdate = "
                            UPDATE 
                                z_sga_usuarios
                            SET
                                cod_gestor = 'super'
                            WHERE
                                cod_usuario = '$json->cod_usuario'";
                        $this->db->query($sqlUpdate);

                        // Elimina da tabela z_sga_gestor_usuario
                        $sqlDelete = "
                            DELETE FROM
                                z_sga_gestor_usuario
                            WHERE
                                idGestor = ".$sqlGestor['idGestor']."
                                AND idUsuario = " . $sqlUser['idUsuario'];
                        $this->db->query($sqlDelete);

                        // Executa commit da querys executadas
                        $this->db->commit();

                        return array(
                            'return' => 'sucesso',
                            'msg'    => "Gestor $json->cod_gestor, eliminado com sucesso do usuario $json->cod_usuario"
                        );
                    } catch (Exception $e) {
                        // Desfaz updates e deletes
                        $this->db->rollback();

                        return array(
                            'return' => 'erro',
                            'msg'    => $e->getMessage()
                        );
                    }
                else:
                    return array(
                        'return' => 'erro',
                        'msg'    => 'Gestor nao encontrado'
                    );
                endif;
            else:
                return array(
                    'return' => 'erro',
                    'msg'    => 'Usuario nao encontrado'
                );
            endif;
        } catch (Exception $e) {
            return array(
                'return' => 'erro',
                'msg'    => $e->getMessage()
            );
        }

    }

    /**
     * Deleta usuário e tudo que estiver associado a ele
     * @param type $json
     * @return type
     */
    public function deleteUsuarios($json)
    {
        /*RETORNA O ID DO USUÁRIO QUE SERÁ EXCLUIDO*/
        $sql = "SELECT * FROM z_sga_usuarios WHERE cod_usuario = '$json->cod_usuario'";
        try{
            $sql = $this->db->query($sql);

            if($sql->rowCount() > 0):
                $sql = $sql->fetch(PDO::FETCH_ASSOC);
                $idUsuario = $sql['z_sga_usuarios_id'];

                try{
                    // Inicia transaction
                    $this->db->beginTransaction();

                    // Exclui relacionamento com instância
                    $sqlDeletUsrEmp = "DELETE FROM z_sga_usuario_empresa WHERE idUsuario = ? AND idEmpresa = ?";
                    $stmtUserEmp = $this->db->prepare($sqlDeletUsrEmp);
                    $sqlDeletUsrEmp = $stmtUserEmp->execute(array(
                        $idUsuario,
                        $json->instancia
                    ));

                    /*APAGA USUARIO DAS TABELAS z_sga_usuarios,z_sga_usuario_empresa E z_sga_grupos*/
                    $sqlDeletUsr = "DELETE FROM z_sga_usuarios WHERE z_sga_usuarios_id = ?";
                    $stmtDeleteUser = $this->db->prepare($sqlDeletUsr);
                    $sqlDeletUsr = $stmtDeleteUser->execute(array($idUsuario));

                    // Exclui os grupos relacionados
                    $sqlDeletGP = "DELETE FROM z_sga_grupos WHERE idUsuario = ? AND idGrupo IN (SELECT g.idGrupo FROM z_sga_grupo AS g WHERE g.idGrupo = idGrupo AND g.idEmpresa = ?)";
                    $stmtDeleteGP = $this->db->prepare($sqlDeletGP);
                    $sqlDeletGP = $stmtDeleteGP->execute(array($idUsuario, $json->instancia));

                    // Executa as exclusões
                    $this->db->commit();
                } catch (Exception $e) {
                    // Defaz as exclusões anteriores
                    $this->db->rollBack();

                    return array(
                        'return' => 'erro',
                        'msg'    => $e->getMessage()
                    );
                }
            endif;
        } catch (Exception $e) {
            return array(
                'return'    => 'erro',
                'msg'       => $e->getMessage()
            );
        }

        $this->atualizaVMUsuarios();

        $fluxo = new Fluxo();
        $fluxo->gravaLogAuditoria(
            1,
            date('Y-m-d H:i:s'),
            date('Y-m-d H:i:s'),
            (isset($json->usrExecutor)) ? "'".$json->usrExecutor."'" : '',
            "'".$json->cod_usuario."'",
            'REMOVIDO',
            '',
            '',
            (isset($json->usrExecutor)) ? "'".$json->usrExecutor."'" : '',
            'a',
            0
        );

        return array(
            'return'    => 'sucesso',
            'msg'       => "Usuario ".$json->cod_usuario." apagado com sucesso"
        );

    }

    /**
     * Atualiza o complemento na tabela z_sga_usuarios
     * @param type $json
     * @return type
     */
    public function putUsuarios($json)
    {
        // Cria variavel com os campos que ira atualizar
        $camposAtualizar = json_decode($json, true);
        unset($camposAtualizar['instancia']);
        unset($camposAtualizar['cod_usuario']);
        unset($camposAtualizar['origem']);
        unset($camposAtualizar['sistema']);
        unset($camposAtualizar['token']);

        $solicitante = '';

        // json com todos os campos
        $json = json_decode($json);

        if(!isset($json->instancia)):
            return array(
                'return'    => 'erro',
                'msg'       => 'Instância não informada!',
            );
        endif;

        // Valida se foi atualizado a função do usuário
        if(isset($json->funcao)):
            // Valida se função existe
            $sqlFunc = $this->db->query("SELECT idFuncao AS cod_funcao, cod_funcao AS descricao FROM z_sga_manut_funcao");
            if($sqlFunc->rowCount() > 0):
                $sqlFunc = $sqlFunc->fetchAll(PDO::FETCH_ASSOC);
                $validaFuncao = false;
                foreach($sqlFunc as $val):
                    if($val['cod_funcao'] == $json->funcao):
                        $validaFuncao = true;
                        break;
                    endif;
                endforeach;

                if(!$validaFuncao):
                    return array(
                        'return'    => 'erro',
                        'msg'       => '',
                        'funcoes'   =>  $sqlFunc
                    );
                endif;
            endif;
        endif;

        // Busca na base se já existe um usuário com o mesmo código recebido no parâmetro
        $sqlGetusr = "SELECT * FROM z_sga_usuarios where cod_usuario = '".$json->cod_usuario."'";
        $sqlGetusr = $this->db->query($sqlGetusr);

        if($sqlGetusr->rowCount() > 0):
            $sqlGetusr = $sqlGetusr->fetch(PDO::FETCH_ASSOC);

            $idUsuario =  $sqlGetusr['z_sga_usuarios_id'];
            $sql = "
                SELECT 
                    * 
                FROM 
                    z_sga_usuario_empresa 
                WHERE 
                    idUsuario = $idUsuario AND 
                    idEmpresa = '".$json->instancia."'";
            $sql = $this->db->query($sql);

            // Valida se existe um registro com o mesmo cod_usuario a ser atualizado e para a instância
            if($sql->rowCount() == 0):
                // Insere na tabela usuario_empresa
                $sql = "
                    INSERT INTO
                        z_sga_usuario_empresa
                    SET 
                        idUsuario = $idUsuario,
                        idEmpresa = ".$json->instancia.", 
                        ativo = " . $json->ativo;

                $this->db->query($sql);
            endif;

            // Cria a query para atualização do complemento do usuário
            $sql = "
                UPDATE 
                    z_sga_usuarios
                SET ";

            $i = 0;
            foreach($camposAtualizar as $key => $val):
                $i++;
                if($i < count($camposAtualizar)):
                    if($key == 'funcao'):
                        $sql .= " $key = '".addslashes($val)."', ";
                        $sql .= " cod_funcao = '".addslashes($val)."', ";
                    endif;
                    $sql .= " $key = '".addslashes($val)."', ";
                else:
                    $sql .= " $key = '".addslashes($val)."' ";
                endif;
            endforeach;

            $sql .= " WHERE z_sga_usuarios_id = $idUsuario";
            $sqlErro = $sql;

            try{
                $sql = $this->db->query($sql);

                // Atualiza na tabela usuario_empresa
                $sql = "
                    UPDATE
                        z_sga_usuario_empresa
                    SET
                        ativo = " . $json->ativo."
                    WHERE
                        idUsuario = $idUsuario
                        AND idEmpresa = " . $json->instancia;

                $sql = $this->db->query($sql);

                // Valida se existe o parâmetro solicitante = S. Se sim valida se existe cadastro na tabela param_login. Se não, cria um cadastro.
                if(isset($json->solicitante) && $json->solicitante == 'S'):
                    // Valida se não existe um registro com o mesmo cod_usuario na tabela z_sga_param_login
                    $sql = "
                        SELECT 
                            * 
                        FROM 
                            z_sga_param_login 
                        WHERE 
                            login = '".$json->cod_usuario."'";

                    $sql = $this->db->query($sql);

                    if($sql->rowCount() == 0):
                        // Insere na tabela z_sga_param_login
                        $sql = "
                            INSERT INTO
                                z_sga_param_login
                            SET                         
                                login = '".$json->cod_usuario."',
                                nomeUsuario = '" . ((isset($json->nome_usuario)) ? $json->nome_usuario : '' )."',  
                                email = '" . ((isset($json->email)) ? $json->email : '' )."',
                                senha = md5('sga@1234!'),
                                validade = '".date('Y-m-d')."',
                                idTotovs = $idUsuario,
                                trocaSenha = 1";

                        $this->db->query($sql);
                    endif;
                endif;

                // Retorna com sucesso e mensagem
                return array(
                    'return'  => 'sucesso',
                    'msg'     => 'Usuário ' . $json->cod_usuario . ' atualizado com sucesso'
                );

            } catch (Exception $e) {
                return array(
                    'return'    => 'erro',
                    'msg'       => $e->getMessage()
                );
            }
        endif;

    }

    /**
     * Insere e associa usuário à instância(empresa), segundo parâmetros.
     * @param type $cod_usuario
     * @param type $nome_usuario
     * @param type $idEmpresa
     * @param type $ativo
     * @return string
     */
    public function postUsuarios($cod_usuario, $nome_usuario, $idEmpresa, $email, $ativo)
    {
        // Busca na base se já existe um usuário com o mesmo código recebido no parâmetro
        $sqlGetusr = "SELECT * FROM z_sga_usuarios where cod_usuario = '$cod_usuario'";
        $sqlGetusr = $this->db->query($sqlGetusr);

        if($sqlGetusr->rowCount() > 0){
            $sqlGetusr = $sqlGetusr->fetch(PDO::FETCH_ASSOC);

            $idUsuario =  $sqlGetusr['z_sga_usuarios_id'];
            $sqlGetUsrEmp = "SELECT * FROM z_sga_usuario_empresa where idUsuario = '$idUsuario' and idEmpresa = '$idEmpresa'";
            $sqlGetUsrEmp = $this->db->query($sqlGetUsrEmp);

            // Valida se já existe um registro com o mesmo cod_usuario a ser cadastrado e para a mesma instância($idEmpresa)
            if($sqlGetUsrEmp->rowCount() > 0){
                return array(
                    'return' => 'erro',
                    'msg'    => 'Usuario ja cadastrado'
                );
            }else{
                // Recupera o id do usuario e associa o mesmo ao ID da instância($idEmpresa)
                $idUsr = $sqlGetusr['z_sga_usuarios_id'];
                $sqlAddUsrEmp = "INSERT INTO z_sga_usuario_empresa SET idUsuario = '$idUsr',idEmpresa = '$idEmpresa', ativo = $ativo";
                try{
                    $sqlAddUsrEmp = $this->db->query($sqlAddUsrEmp);

                    // Retorna o ID criado ao associar o usuário a instância
                    return array(
                        'return'  => 'sucesso',
                        'msg'      => 'Usuario cadastrado com sucesso'
                    );
                } catch (Exception $e) {
                    return array(
                        'return'    => 'erro',
                        'msg'       => $e->getMessage()
                    );
                }
            }
        }else{
            // Insere o usuário na base.
            $sql = "
                INSERT INTO 
                    z_sga_usuarios 
                SET 
                    cod_usuario = '$cod_usuario',
                    nome_usuario = '$nome_usuario', 
					cod_funcao = 1,
					funcao = 1,  
                    email = '$email'";
            $sql = $this->db->query($sql);

            try{
                $retorno =  $this->db->lastInsertId();

                // Associa o usuário a instância($idEmpresa)
                if(!empty($retorno)){
                    $sqlAddUsrEmp = "
                        INSERT INTO 
                            z_sga_usuario_empresa 
                        SET 
                            idUsuario = '$retorno', 
                            idEmpresa = '$idEmpresa',                            
                            ativo = $ativo";

                    $sqlAddUsrEmp = $this->db->query($sqlAddUsrEmp);
                    // Retorna o ID criado ao associar o usuário a instância(empresa)
                    return array(
                        'return'  => 'sucesso',
                        'msg'     => 'Cadastrado com sucesso'
                    );
                }
            }catch (Exception $e){
                // Retorna tipo erro e a mensagem do mesmo
                return array(
                    'return'  => 'erro',
                    'msg'     => $e->getMessage()
                );
            }
        }
    }


    /**
     * Retorna os dados do usuário com mesmo cod_usuario informado nos parâmetros
     * @param type $codUsuario
     * @return type
     */
    public function getUsuarios($codUsuario)
    {
        $sql = "
            SELECT 
                u.gestor_grupo,
                u.gestor_programa,
                u.gestor_usuario,
                u.si,
                u.solicitante,
                u.cpf,
                u.cod_gestor,
                u.funcao AS cod_funcao,
                f.cod_funcao AS funcao,
                u.email,
                u.nome_usuario,
                e.ativo
            FROM 
                z_sga_usuarios u
            LEFT JOIN
                z_sga_usuario_empresa e
                ON e.idUsuario = u.z_sga_usuarios_id
            LEFT JOIN
                z_sga_manut_funcao f
                ON u.cod_funcao = f.idFuncao
            WHERE 
                u.cod_usuario = '$codUsuario'";

        try{
            $sql = $this->db->query($sql);

            $data = array();

            if($sql->rowCount() > 0):
                $data = $sql->fetch(PDO::FETCH_ASSOC);

                return array(
                    'return'    => 'sucesso',
                    'msg'       => 'Dados recuperado com sucesso',
                    'dados'     => $data
                );
            else:
                return array(
                    'return'    => 'erro',
                    'msg'       => "Usuario $codUsuario nao encontrado"
                );
            endif;


        } catch (Exception $e) {
            return array(
                'return' => 'erro',
                'msg'    => $e->getMessage()
            );
        }
    }

    /**
     * Cria um novo Grupo com base nos parâmetros json
     * @param $nameGrupo
     * @param $descGrupo
     * @return array string msg com a mensagem de retorno e sucesso || erro || alerta.
     */
    public function postGrupos($json)
    {
        $sql = "
            SELECT 
                idLegGrupo 
            FROM
                z_sga_grupo
            WHERE
                idLegGrupo = '".$json->id_leg_grupo."'                 
                AND idEmpresa = '".$json->instancia."'";
        $sql = $this->db->query($sql);

        // Valida se já existe um grupo com dados iguais aos passado por json
        if($sql->rowCount() > 0):
            // Se sim retorna erro e mensagem informando a existencia do mesmo
            return array(
                'return' => 'erro',
                'msg'    => 'Grupo ja cadastro para essa instancia'
            );
        else:
            // Se não. Cadastra o grupo e retorna o resultado
            $sql = "
                INSERT INTO 
                    z_sga_grupo 
                SET 
                    idLegGrupo = '".addslashes($json->id_leg_grupo)."',
                    descAbrev = '".addslashes($json->desc_abrev)."',
                    idEmpresa = '".$json->instancia."'";

            try{
                $sql = $this->db->query($sql);

                return array(
                    'return' => 'sucesso',
                    'msg'    => 'Grupo cadastrado com sucesso'
                );
            }catch (Exception $e){
                return array(
                    'return' => false,
                    'error'  => $e->getMessage()
                );
            }
        endif;
    }

    /**
     * Atualiza um Grupo com base nos parâmetros json
     * @param $json
     * @return array string msg com a mensagem de retorno e sucesso || erro || alerta.
     */
    public function putGrupos($json)
    {
        $sql = "
            SELECT 
                idLegGrupo 
            FROM
                z_sga_grupo
            WHERE
                idLegGrupo = '".$json->id_leg_grupo."'                 
                AND idEmpresa = '".$json->instancia."'";
        $sql = $this->db->query($sql);

        // Valida se já existe um grupo com dados iguais aos passado por json
        if($sql->rowCount() > 0):
            // Se não. Cadastra o grupo e retorna o resultado
            $sql = "
                UPDATE 
                    z_sga_grupo 
                SET                     
                    descAbrev = '".addslashes($json->desc_abrev)."'
                WHERE
                    idLegGrupo = '".addslashes($json->id_leg_grupo)."'
                    AND idEmpresa = ".$json->instancia;

            try{
                $sql = $this->db->query($sql);

                return array(
                    'return' => 'sucesso',
                    'msg'    => 'Grupo atualizado com sucesso'
                );
            }catch (Exception $e){
                return array(
                    'return' => 'erro',
                    'error'  => $e->getMessage(),
					'msg'    => ''
                );
            }
        else:
            // Se não retorna erro e mensagem informando a inexistencia do mesmo
            return array(
                'return' => 'erro',
                'msg'    => 'Grupo nao encontrado para essa instancia'
            );

        endif;
    }

    /**
     * Retorna os dados do grupo com mesmo id_leg_Grupo informado nos parâmetros
     * @param type $json
     * @return type
     */
    public function getGrupos($json)
    {
        $sql = "
            SELECT 
                idGrupo    AS id_grupo,
                idLegGrupo AS id_leg_grupo,
                descAbrev  AS desc_abrev,
                instancia
            FROM 
                z_sga_grupo
            WHERE 
                idLegGrupo = '$json->id_leg_grupo'
                AND idEmpresa = ".$json->instancia;

        try{
            $sql = $this->db->query($sql);

            $data = array();

            if($sql->rowCount()>0):
                $data = $sql->fetchAll(PDO::FETCH_ASSOC);

                return array(
                    'return'    => 'sucesso',
                    'msg'       => 'Dados recuperado com sucesso',
                    'dados'     => $data
                );
            else:
                return array(
                    'return'    => 'erro',
                    'msg'       => 'Grupo nao encontrado'
                );
            endif;


        } catch (Exception $e) {
            return array(
                'return' => 'erro',
                'msg'    => $e->getMessage()
            );
        }
    }

    /**
     * Deleta grupo e tudo que estiver associado a ele
     * @param type $json
     * @return type
     */
    public function deleteGrupos($json)
    {
        /*RETORNA O ID DO GRUPO QUE SERÁ EXCLUIDO*/
        $sql = "
            SELECT
                idGrupo,
                idLegGrupo 
            FROM
                z_sga_grupo
            WHERE
                idLegGrupo = '$json->id_leg_grupo'                 
                AND idEmpresa = $json->instancia";
        $sql = $this->db->query($sql);

        // Valida se existe um grupo com dados iguais aos passado por json
        if($sql->rowCount() > 0):
            $grupo = $sql->fetch(PDO::FETCH_ASSOC);
            try{
                // Inicia transaction
                $this->db->beginTransaction();

                // Exclui da tabela z_sga_grupo_programa
                $sql = "
                    DELETE FROM
                        z_sga_grupo_programa
                    WHERE
                        idGrupo = ".$grupo['idGrupo'];
                $stmt = $this->db->query($sql);

                // Exclui da tabela z_sga_grupo
                $sqlGrp = "
                    DELETE FROM
                        z_sga_grupo
                    WHERE 
                        idLegGrupo = '".$json->id_leg_grupo."' 
                        AND idEmpresa = $json->instancia";
                $sqlGrp = $this->db->query($sqlGrp);

                // Se não houve erros. Executa as alterações
                $this->db->commit();
            } catch (Exception $e) {
                // Desfaz as exclusões anteriores
                $this->db->rollBack();

                return array(
                    'return'  => 'erro',
                    'msg'     => $e->getMessage()
                );
            }
        endif;

        $this->atualizaVMUsuarios();

        $fluxo = new Fluxo();
        $fluxo->gravaLogAuditoria(
            1,
            date('Y-m-d H:i:s'),
            date('Y-m-d H:i:s'),
            (isset($json->usrExecutor)) ? "'".$json->usrExecutor."'" : '',
            '',
            'REMOVIDO',
            $json->id_leg_grupo,
            '',
            (isset($json->usrExecutor)) ? "'".$json->usrExecutor."'" : '',
            'a',
            0
        );

        return array(
            'return'  => 'sucesso',
            'msg'     => 'Grupo removido '.$json->id_leg_grupo.' com sucesso'
        );
    }

    /**
     * Associa usuario a um grupo com base no json
     * @param type $json
     * @return type
     */
    public function postGruposUsuarios($json)
    {
        $idGrupo = '';
        $idUsuario = '';

        /*********************************************************************************
        * VALIDA SE GRUPO EXISTE
        *********************************************************************************/
        $sqlGrupo = "
            SELECT 
                * 
            FROM 
                z_sga_grupo 
            WHERE 
                idLegGrupo = '".$json->id_leg_grupo."' 
                AND idEmpresa = " . $json->instancia;
        $sqlGrupo = $this->db->query($sqlGrupo);

        if($sqlGrupo->rowCount() == 0):
            /*********************************************************************************
            * CADASTRA GRUPO CASO NÃO EXISTA
            *********************************************************************************/
            $sql = "
                INSERT INTO 
                    z_sga_grupo 
                SET 
                    idLegGrupo = '".addslashes($json->id_leg_grupo)."',
                    descAbrev = '".(isset($json->desc_abrev) ? addslashes($json->desc_abrev) : '')."',
                    idEmpresa = '".$json->instancia."'";
            try{
                $sql = $this->db->query($sql);
                $idGrupo = $this->db->lastInsertId();
            }catch (Exception $e){
                return array(
                    'return' => false,
                    'error'  => $e->getMessage()
                );
            }
        else:
            $sqlGrupo   = $sqlGrupo->fetch(PDO::FETCH_ASSOC);
            $idGrupo = $sqlGrupo['idGrupo'];
        endif;

        /*********************************************************************************
        * VALIDA SE USUÁRIO EXISTE
        *********************************************************************************/
        $sqlUsr = "SELECT z_sga_usuarios_id FROM z_sga_usuarios WHERE cod_usuario = '".$json->cod_usuario."'";
        $sqlUsr = $this->db->query($sqlUsr);
        $sqlUsrRes = $sqlUsr->fetch(PDO::FETCH_ASSOC);
        $idUsuario = $sqlUsrRes['z_sga_usuarios_id'];

        if($sqlUsr->rowCount() == 0):
            /*********************************************************************************
            * CADASTRA USUÁRIO CASO NÃO EXISTA
            *********************************************************************************/
            // Cria variavel com os campos que ira atualizar
            $sql = "
                INSERT INTO 
                    z_sga_usuarios
                SET                     
                    cod_usuario     = '".$json->cod_usuario."',
                    nome_usuario    = '".$json->nome_usuario."',
                    CPF             = '',
                    cod_gestor      = '',
                    cod_funcao      = '1',
                    funcao          = '1',
                    email           = '".$json->email."',
                    solicitante     = 'S',
                    gestor_usuario  = 'N',
                    gestor_grupo    = 'N',
                    gestor_programa = 'N',
                    si              = 'N',
                    idUsrFluig      = '".$json->idUsrFluig."',
                    ativo           = '".(isset($json->ativo) && !empty($json->ativo) ? $json->ativo : 1)."',
                    idDepartamento  = '1'";
            try{
                $this->db->query($sql);
                $idUsuario =  $this->db->lastInsertId();

                /*********************************************************************************
                * RELACIONA USUÁRIO PARA A INSTÂNCIA INFORMADA
                *********************************************************************************/
                if(!empty($idUsuario)){
                    $sqlAddUsrEmp = "
                        INSERT INTO 
                            z_sga_usuario_empresa 
                        SET 
                            idUsuario = '$idUsuario', 
                            idEmpresa = '$json->instancia',
                            ativo = ".(isset($json->ativo) && !empty($json->ativo) ? $json->ativo : 1);

                    $this->db->query($sqlAddUsrEmp);
                }
            }catch (Exception $e){
                // Retorna tipo erro e a mensagem do mesmo
                return array(
                    'return'  => 'erro',
                    'msg'     => $e->getMessage()
                );
            }
        else:
            /*********************************************************************************
             * SE USUÁRIO EXISTE
             *********************************************************************************/
            // Valida se usuário está relacionado a alguma instância
            $sqlUsrEmp = "
            SELECT 
                * 
            FROM 
                z_sga_usuarios u
            RIGHT JOIN
                z_sga_usuario_empresa ue
                ON u.z_sga_usuarios_id = ue.idUsuario
            WHERE
                u.cod_usuario = '".$json->cod_usuario."'
                AND ue.idEmpresa = " . $json->instancia;

            $sqlUsrEmp = $this->db->query($sqlUsrEmp);

            if($sqlUsrEmp->rowCount() == 0):
                $sqlAddUsrEmp = "
                    INSERT INTO 
                        z_sga_usuario_empresa 
                    SET 
                        idUsuario = '$idUsuario', 
                        idEmpresa = '$json->instancia',
                        ativo = ".$json->ativo;

            $this->db->query($sqlAddUsrEmp);
            endif;
        endif;

        // Valida se já existe o usuario atribuido para o grupo
        $sql = "SELECT cod_usuario FROM z_sga_grupos WHERE cod_usuario = '".$json->cod_usuario."' AND cod_grupo = '".$json->id_leg_grupo."'";
        $sql = $this->db->query($sql);

        if($sql->rowCount() == 0):
            $sql = "
                INSERT INTO
                    z_sga_grupos 
                SET
                    cod_grupo = '".$json->id_leg_grupo."',
                    desc_grupo = '".(isset($json->desc_abrev) ? addslashes($json->desc_abrev) : '')."',
                    gestor = 'super',
                    cod_usuario = '".$json->cod_usuario."',
                    idGrupo =  ".$idGrupo.", 
                    idUsuario = " . $idUsuario;
            try{
                $sql = $this->db->query($sql);
            }catch (Exception $e){
                return array(
                    'return' => 'erro',
                    'msg'  => $e->getMessage()
                );
            }
        endif;

        $this->atualizaVMUsuarios();

        $fluxo = new Fluxo();
        $fluxo->gravaLogAuditoria(
            1,
            date('Y-m-d H:i:s'),
            date('Y-m-d H:i:s'),
            (isset($json->usrExecutor)) ? "'".$json->usrExecutor."'" : '',
            "'".$json->cod_usuario."'",
            'ADICIONADO',
            $json->id_leg_grupo .' - '. (isset($json->desc_abrev) ? addslashes($json->desc_abrev) : ''),
            '',
            (isset($json->usrExecutor)) ? "'".$json->usrExecutor."'" : '',
            'a',
            0
        );

        return array(
            'return' => 'sucesso',
            'msg'    => 'Usuario atribuido ao grupo com sucesso'
        );
    }

    /**
     * Remove usuario do grupo informado
     * @param type $json
     * @return type
     */
    public function deleteGruposUsuarios($json)
    {

        $idGrupo = '';
        $idUsuario = '';

        /*********************************************************************************
         * VALIDA SE GRUPO EXISTE
         *********************************************************************************/
        $sqlGrupo = "
            SELECT 
                * 
            FROM 
                z_sga_grupo 
            WHERE 
                idLegGrupo = '".$json->id_leg_grupo."' 
                AND idEmpresa = " . $json->instancia;
        $sqlGrupo = $this->db->query($sqlGrupo);

        if($sqlGrupo->rowCount() == 0):
            /*********************************************************************************
             * CADASTRA GRUPO CASO NÃO EXISTA
             *********************************************************************************/
            $sql = "
                INSERT INTO 
                    z_sga_grupo 
                SET 
                    idLegGrupo = '".addslashes($json->id_leg_grupo)."',
                    descAbrev = '".(isset($json->desc_abrev) ? addslashes($json->desc_abrev) : '')."',
                    idEmpresa = '".$json->instancia."'";
            try{
                $sql = $this->db->query($sql);
                $idGrupo = $this->db->lastInsertId();
            }catch (Exception $e){
                return array(
                    'return' => false,
                    'error'  => $e->getMessage()
                );
            }
        else:
            $sqlGrupo   = $sqlGrupo->fetch(PDO::FETCH_ASSOC);
            $idGrupo = $sqlGrupo['idGrupo'];
        endif;

        /*********************************************************************************
         * VALIDA SE USUÁRIO EXISTE
         *********************************************************************************/
        $sqlUsr = "SELECT z_sga_usuarios_id FROM z_sga_usuarios WHERE cod_usuario = '".$json->cod_usuario."'";
        $sqlUsr = $this->db->query($sqlUsr);
        $sqlUsrRes = $sqlUsr->fetch(PDO::FETCH_ASSOC);
        $idUsuario = $sqlUsrRes['z_sga_usuarios_id'];

        if($sqlUsr->rowCount() == 0):
            /*********************************************************************************
             * CADASTRA USUÁRIO CASO NÃO EXISTA
             *********************************************************************************/
            // Cria variavel com os campos que ira atualizar
            $sql = "
                INSERT INTO 
                    z_sga_usuarios
                SET                     
                    cod_usuario     = '".$json->cod_usuario."',
                    nome_usuario    = '".$json->nome_usuario."',
                    CPF             = '',
                    cod_gestor      = '',
                    cod_funcao      = '1',
                    funcao          = '1',
                    email           = '".$json->email."',
                    solicitante     = 'S',
                    gestor_usuario  = 'N',
                    gestor_grupo    = 'N',
                    gestor_programa = 'N',
                    si              = 'N',
                    idUsrFluig      = '".$json->idUsrFluig."',
                    ativo           = '".(isset($json->ativo) && !empty($json->ativo) ? $json->ativo : 1)."',
                    idDepartamento  = '1'";
            try{
                $this->db->query($sql);
                $idUsuario =  $this->db->lastInsertId();

                /*********************************************************************************
                * RELACIONA USUÁRIO PARA A INSTÂNCIA INFORMADA
                *********************************************************************************/
                if(!empty($idUsuario)){
                    $sqlAddUsrEmp = "
                        INSERT INTO 
                            z_sga_usuario_empresa 
                        SET 
                            idUsuario = '$idUsuario', 
                            idEmpresa = '$json->instancia',
                            ativo = ".(isset($json->ativo) && !empty($json->ativo) ? $json->ativo : 1);

                    $this->db->query($sqlAddUsrEmp);
                }
            }catch (Exception $e){
                // Retorna tipo erro e a mensagem do mesmo
                return array(
                    'return'  => 'erro',
                    'msg'     => $e->getMessage()
                );
            }
        else:
            /*********************************************************************************
             * SE USUÁRIO EXISTE
             *********************************************************************************/
            // Valida se usuário está relacionado a alguma instância
            $sqlUsrEmp = "
            SELECT 
                * 
            FROM 
                z_sga_usuarios u
            RIGHT JOIN
                z_sga_usuario_empresa ue
                ON u.z_sga_usuarios_id = ue.idUsuario
            WHERE
                u.cod_usuario = '".$json->cod_usuario."'
                AND ue.idEmpresa = " . $json->instancia;

            $sqlUsrEmp = $this->db->query($sqlUsrEmp);

            if($sqlUsrEmp->rowCount() == 0):
                $sqlAddUsrEmp = "
                    INSERT INTO 
                        z_sga_usuario_empresa 
                    SET 
                        idUsuario = '$idUsuario', 
                        idEmpresa = '$json->instancia',
                        ativo = ".$json->ativo;

                $this->db->query($sqlAddUsrEmp);
            endif;
        endif;

        // Valida se usuário está relacionado ao grupo
        $sqlUserGrupo = "
            SELECT 
                z_sga_grupos_id 
            FROM 
                z_sga_grupos            
            WHERE
                idGrupo = $idGrupo
                AND idUsuario = $idUsuario";

        $sqlUserGrupo = $this->db->query($sqlUserGrupo);

        if($sqlUserGrupo->rowCount() >= 1):
            $sql = "
                DELETE FROM
                    z_sga_grupos
                WHERE 
                    idUsuario = $idUsuario
                    AND idGrupo = $idGrupo";
            try{
                $this->db->query($sql);
            } catch (Exception $e) {
                return array(
                    'return' => 'erro',
                    'msg'    => $e->getMessage()
                );
            }
        endif;

        $this->atualizaVMUsuarios();

        $fluxo = new Fluxo();
        $fluxo->gravaLogAuditoria(
            1,
            date('Y-m-d H:i:s'),
            date('Y-m-d H:i:s'),
            (isset($json->usrExecutor)) ? "'".$json->usrExecutor."'" : '',
            "'".$json->cod_usuario."'",
            'REMOVIDO',
            $json->id_leg_grupo .' - '. (isset($json->desc_abrev) ? addslashes($json->desc_abrev) : ''),
            '',
            (isset($json->usrExecutor)) ? "'".$json->usrExecutor."'" : '',
            'a',
            0
        );

        return array(
            'return' => 'sucesso',
            'msg'    => 'Usuario removido do grupo com sucesso'
        );
    }

    /**
     * Deleta programas e tudo que estiver associado a ele
     * @param type $json
     * @return type
     */
    public function deleteProgramas($json)
    {
        /*RETORNA O ID DO PROGRAMA QUE SERÁ EXCLUIDO*/
        $sqlProg = "
            SELECT 
                * 
            FROM 
                z_sga_programas 
            WHERE 
                cod_programa = '$json->cod_programa' 
                AND descricao_programa = '".$json->descricao_programa."'";
        $sqlProg = $this->db->query($sqlProg);

        if($sqlProg->rowCount() > 0):
            $sqlProg = $sqlProg->fetch(PDO::FETCH_ASSOC);
            $idPrograma = $sqlProg['z_sga_programas_id'];

            try{
                // Inicia transaction
                $this->db->beginTransaction();

                // Deleta a instancia
                $sqlDelEmp = "
                    DELETE FROM
                        z_sga_programa_empresa
                    WHERE
                        idPrograma = $idPrograma";
                $this->db->query($sqlDelEmp);

                // Deleta dos grupos associados
                $sqlDelGrupo = "
                    DELETE FROM
                        z_sga_grupo_programa
                    WHERE
                        idPrograma = $idPrograma";
                $this->db->query($sqlDelGrupo);

                // Deleta da tabela z_sga_apc_mtz_apps_processo
                $sqlDelProcess = "
                    DELETE FROM
                        z_sga_apc_mtz_apps_processo
                    WHERE
                        codPrograma = '" . $json->cod_programa . "'";
                $this->db->query($sqlDelProcess);

                // Deleta o programa
                $sqlDelProg = "
                    DELETE FROM
                        z_sga_programas
                    WHERE
                        z_sga_programas_id = $idPrograma";
                $this->db->query($sqlDelProg);

                // Executa o commit confirmando a exclusão
                $this->db->commit();
            } catch (Exception $e) {
                // Desfaz querys executadas
                $this->db->rollback();

                return array(
                    'return' => 'erro',
                    'msg'    => $e->getMessage()
                );
            }
        endif;

        $this->atualizaVMUsuarios();

        $fluxo = new Fluxo();
        $fluxo->gravaLogAuditoria(
            1,
            date('Y-m-d H:i:s'),
            date('Y-m-d H:i:s'),
            (isset($json->usrExecutor)) ? "'".$json->usrExecutor."'" : '',
            '',
            'REMOVIDO',
            '',
            "'".$json->cod_programa."'",
            (isset($json->usrExecutor)) ? "'".$json->usrExecutor."'" : '',
            'a',
            0
        );
        return array(
            'return' => 'sucesso',
            'msg'    => 'Programa '.$json->cod_programa.' removido com sucesso'
        );
    }

    /**
     * Atualiza o complemento na tabela z_sga_programas
     * @param type $json
     * @return type
     */
    public function putProgramas($json)
    {
        // Cria variavel com os campos que ira atualizar
        $camposAtualizar = json_decode($json, true);

        // Cria variavel com os campos que ira inserir
        $camposInserir = json_decode($json, true);

        unset($camposAtualizar['instancia']);
        unset($camposAtualizar['cod_programa']);
        unset($camposAtualizar['origem']);
        unset($camposAtualizar['sistema']);
        unset($camposAtualizar['token']);

        // json com todos os campos
        $json = json_decode($json);

        // Busca na base se existe um programa com o mesmo código
        $sqlGetProg = "
            SELECT 
                z_sga_programas_id 
            FROM 
                z_sga_programas 
            WHERE 
                cod_programa = '".$json->cod_programa."'";
        $sqlGetProg = $this->db->query($sqlGetProg);

        if($sqlGetProg->rowCount() > 0):
            $sqlGetProg = $sqlGetProg->fetch(PDO::FETCH_ASSOC);

            $idPrograma =  $sqlGetProg['z_sga_programas_id'];
            $sql = "
                SELECT 
                    * 
                FROM 
                    z_sga_programa_empresa 
                WHERE 
                    idPrograma = $idPrograma AND 
                    idEmpresa = '".$json->instancia."'";
            $sql = $this->db->query($sql);

            // Valida se existe um registro com o mesmo cod_programa a ser atualizado e para a instância
            if($sql->rowCount() == 0):
                // Associa o programa a instância(empresa)
                $sqlAddUsrEmp = "INSERT INTO z_sga_programa_empresa SET idPrograma = $idPrograma, idEmpresa = " . $json->instancia;
                $this->db->query($sqlAddUsrEmp);
            endif;

            // Cria a query para atualização do complemento do programa
            $sql = "
                UPDATE 
                    z_sga_programas
                SET ";

            $i = 0;
            foreach($camposAtualizar as $key => $val):
                $i++;
                if($i < count($camposAtualizar)):
                    //$sql .= ($val != '') ? " $key = '".addslashes($val)."', " : '';
                    $sql .= " $key = '".addslashes($val)."', ";
                elseif($i >= count($camposAtualizar)):
                    //$sql .= ($val != '') ? " $key = '".addslashes($val)."' " : '';
                    $sql .= " $key = '".addslashes($val)."' ";
                endif;
            endforeach;

            $sql .= " WHERE z_sga_programas_id = $idPrograma";
            try{
                $sql = $this->db->query($sql);

                // Retorna com sucesso e mensagem
                return array(
                    'return'  => 'sucesso',
                    'msg'     => 'Programa ' . $json->cod_programa . ' atualizado com sucesso'
                );
            } catch (Exception $e) {
                return array(
                    'return'    => 'erro',
                    'msg'       => $e->getMessage()
                );
            }
        else:
            unset($camposInserir['instancia']);
            unset($camposInserir['origem']);
            unset($camposInserir['sistema']);
            unset($camposInserir['token']);

            // Insere o programa na base.
            $sql = "
                INSERT INTO 
                    z_sga_programas 
                SET ";

            $i = 0;
            foreach($camposInserir as $key => $val):
                $i++;
                if($i < count($camposInserir)):
                    $sql .= " $key = '$val', ";
                else:
                    $sql .= " $key = '$val' ";
                endif;

            endforeach;

            try {
                $sql = $this->db->query($sql);

                $retorno = $this->db->lastInsertId();

                // Associa o programa a instância(empresa)
                if (!empty($retorno)) {
                    $sqlAddUsrEmp = "INSERT INTO z_sga_programa_empresa SET idPrograma = '$retorno',idEmpresa = " . $json->instancia;


                    $sqlAddUsrEmp = $this->db->query($sqlAddUsrEmp);
                    // Retorna o ID criado ao associar o usuário a instância(empresa)
                    return array(
                        'return' => 'sucesso',
                        'msg' => 'Programa ' . $json->cod_programa . ' atualizado com sucesso'
                    );
                }
            } catch (Exception $e) {
                // Retorna tipo erro e a mensagem do mesmo
                return array(
                    'return' => 'erro',
                    'msg' => $e->getMessage()
                );
            }
        endif;

    }

    /**
     * Insere e associa programa à instância(empresa), segundo parâmetros.
     * @param type $json
     * @return string
     */
    public function postProgramas($json)
    {
        // Cria variavel com os campos que ira inserir
        $camposInserir = json_decode($json, true);
        unset($camposInserir['instancia']);
        unset($camposInserir['origem']);
        unset($camposInserir['sistema']);
        unset($camposInserir['token']);

        // json com todos os campos
        $json = json_decode($json);

        // Busca na base se já existe um programa com o mesmo código e descricao recebido no parâmetro
        $sqlGetProg = "
            SELECT 
                * 
            FROM 
                z_sga_programas 
            WHERE 
                cod_programa = '$json->cod_programa' 
                AND descricao_programa = '".$json->descricao_programa."'
                AND cod_programa = procedimento_pai /* Apresenta apenas programas principais */";
        $sqlGetProg = $this->db->query($sqlGetProg);

        if($sqlGetProg->rowCount() > 0){
            $sqlGetProg = $sqlGetProg->fetch(PDO::FETCH_ASSOC);

            $idPrograma =  $sqlGetProg['z_sga_programas_id'];
            $sqlGetProgEmp = "SELECT * FROM z_sga_programa_empresa where idPrograma = $idPrograma and idEmpresa = " . $json->instancia;
            $sqlGetProgEmp = $this->db->query($sqlGetProgEmp);

            // Valida se já existe um registro com o mesmo programa a ser cadastrado e para a mesma instância(empresa)
            if($sqlGetProgEmp->rowCount() > 0){
                return array(
                    'return' => 'erro',
                    'msg'    => 'Programa ja cadastrado'
                );
            }else{
                // Recupera o id do programa e associa o mesmo ao ID da instância(empresa)
                $idProg = $sqlGetProg['z_sga_programas_id'];
                $sqlAddProgEmp = "INSERT INTO z_sga_programa_empresa SET idPrograma = $idProg, idEmpresa = " . $json->instancia;
                try{
                    $sqlAddProgEmp = $this->db->query($sqlAddProgEmp);

                    // Retorna o mensagem de sucesso ao associar o programa a instância
                    return array(
                        'return'  => 'sucesso',
                        'msg'      => 'Programa ja cadastrado. Apenas associado a instancia informada'
                    );
                } catch (Exception $e) {
                    return array(
                        'return'    => 'erro',
                        'msg'       => $e->getMessage()
                    );
                }
            }
        }else{
            // Insere o programa na base.
            $sql = "
                INSERT INTO 
                    z_sga_programas 
                SET ";

            $i = 0;
            foreach($camposInserir as $key => $val):
                $i++;
                if($i < count($camposInserir)):
                    $sql .= " $key = '$val', ";
                else:
                    $sql .= " $key = '$val' ";
                endif;

            endforeach;
            $sql = $this->db->query($sql);

            try{
                $retorno =  $this->db->lastInsertId();

                // Associa o programa a instância(empresa)
                if(!empty($retorno)){
                    $sqlAddUsrEmp = "INSERT INTO z_sga_programa_empresa SET idPrograma = '$retorno',idEmpresa = ". $json->instancia;

                    try{
                        $sqlAddUsrEmp = $this->db->query($sqlAddUsrEmp);
                        // Retorna o ID criado ao associar o usuário a instância(empresa)
                        return array(
                            'return'  => 'sucesso',
                            'msg'     => 'Programa '.$json->cod_programa.' cadastrado com sucesso'
                        );
                    } catch (Exception $e){
                        // Retorna tipo erro e a mensagem do mesmo
                        return array(
                            'return'  => 'erro',
                            'msg'     => $e->getMessage()
                        );
                    }
                }
            }catch (Exception $e){
                // Retorna tipo erro e a mensagem do mesmo
                return array(
                    'return'  => 'erro',
                    'msg'     => $e->getMessage()
                );
            }
        }
    }

    /**
     * Retorna os dados do programa com mesmo cod_programa e descricao_programa informado no json
     * @param type $json
     * @return type
     */
    public function getProgramas($json)
    {
        $sql = "
            SELECT 
                *
            FROM 
                z_sga_programas 
            WHERE 
                cod_programa = '$json->cod_programa' 
                AND descricao_programa = '$json->descricao_programa'";

        try{
            $sql = $this->db->query($sql);

            if($sql->rowCount() > 0):
                return array(
                    'return'    => 'sucesso',
                    'msg'       => 'Dados recuperado com sucesso',
                    'dados'     => $sql->fetch(PDO::FETCH_ASSOC)
                );
            else:
                return array(
                    'return' => 'erro',
                    'msg'    => "Programa $json->cod_programa nao encontrado"
                );
            endif;
        } catch (Exception $e) {
            return array(
                'return' => 'erro',
                'msg'    => $e->getMessage()
            );
        }
    }

    /**
     * Insere log de uso dos programas do TOTVS.
     * @param type $json
     * @return string
     */
    public function postProgramasLog($json)
    {
        // Busca o id do usuário no SGA        
        $sqlUser = "
            SELECT 
                u.z_sga_usuarios_id AS idUsuario
            FROM
                z_sga_usuarios u
            LEFT JOIN
                z_sga_usuario_empresa e
                ON e.idUsuario = u.z_sga_usuarios_id
            WHERE
                e.idEmpresa = $json->instancia
                AND u.cod_usuario = '$json->cod_usuario'";

        $sqlUser = $this->db->query($sqlUser);

        // Valida se o usuário existe e retorna erro, caso não exista.
        if($sqlUser->rowCount() == 0):
            return array(
                'return'    => 'erro',
                'msg'       => 'Usuário não encontrado para a instancia informada'
            );
        endif;

        // busca o id do programa no SGA
        $sqlProg = "
            SELECT 
                p.z_sga_programas_id AS idProg
            FROM
                z_sga_programas p
            LEFT JOIN
                z_sga_programa_empresa e
                ON e.idPrograma = p.z_sga_programas_id
            WHERE
                e.idEmpresa = $json->instancia
                AND p.cod_programa = '$json->cod_programa'";

        $sqlProg = $this->db->query($sqlProg);

        // Valida se o programa existe e retorna erro, caso não exista.
        if($sqlProg->rowCount() == 0):
            return array(
                'return'    => 'erro',
                'msg'       => 'Programa não encontrado para a instancia informada'
            );
        endif;

        // Retorna o array com as informações de programas e usuários
        $sqlProg = $sqlProg->fetch(PDO::FETCH_ASSOC);
        $sqluser = $sqlUser->fetch(PDO::FETCH_ASSOC);

        // Cria a query de inserção
        $sqlAddProgLog = "
            INSERT INTO 
                z_sga_programas_log_uso
            SET 
                idPrograma  =  ".$sqlProg['idProg'].",
                idUsuario   = ".$sqlUser['idUsuario'].",
                dataUso     = ".date('Y-m-d').",
                tempoUso    = ".$json->tempoUso.",
                idEmpresa   = " . $json->instancia;
        try{
            $this->db->query($sqlAddProgLog);

            // Insere log de uso de programa.
            return array(
                'return'  => 'sucesso',
                'msg'     => 'Log cadastrado com sucesso.'
            );
        } catch (Exception $e) {
            return array(
                'return'    => 'erro',
                'msg'       => $e->getMessage()
            );
        }

    }


    /**
     * Associa programa a um grupo com base no json
     * @param type $json
     * @return type
     */
    public function postGruposProgramas($json)
    {
        $idGrupo = '';
        $idPrograma = '';
        /********************************************************************************
        * VALIDAÇÃO DE GRUPO. SE NÃO EXISTIR O GRUPO. REALIZA O CADASTRO.
        ********************************************************************************/
        $sqlGrupo = "
            SELECT 
                * 
            FROM 
                z_sga_grupo 
            WHERE 
                idLegGrupo = '".$json->id_leg_grupo."' 
                AND idEmpresa = " . $json->instancia;
        $sqlGrupo = $this->db->query($sqlGrupo);

        if($sqlGrupo->rowCount() == 0):
            // Se não existir o grupo. Faz o cadastro.
            $sql = "
                INSERT INTO 
                    z_sga_grupo 
                SET 
                    idLegGrupo = '".addslashes($json->id_leg_grupo)."',
                    descAbrev = '".addslashes($json->desc_abrev)."',
                    idEmpresa = '".$json->instancia."'";

            try{
                $sql = $this->db->query($sql);
                $idGrupo = $this->db->lastInsertId();
            }catch (Exception $e){
                return array(
                    'return' => false,
                    'error'  => $e->getMessage()
                );
            }
        else:
            $sqlGrupoRs = $sqlGrupo->fetch(PDO::FETCH_ASSOC);
            $idGrupo = $sqlGrupoRs['idGrupo'];
        endif;

        /********************************************************************************
        * VALIDAÇÃO DE PROGRAMA. SE NÃO EXISTIR O PROGRAMA. REALIZA O CADASTRO.
        ********************************************************************************/
        $sqlProg = "
            SELECT 
                *
            FROM 
                z_sga_programas 
            WHERE 
                cod_programa = '".$json->cod_programa."'
                AND descricao_programa = '$json->descricao_programa'
                /*AND cod_programa = procedimento_pai  Apresenta apenas programas principais */";
        $sqlProg = $this->db->query($sqlProg);

        if($sqlProg->rowCount() == 0):
            // Se não existir o programa. Cadastra e devolve o id.
            // Insere o programa na base.
            $sql = "
                INSERT INTO 
                    z_sga_programas (
                        cod_programa,
                        descricao_programa,
                        cod_modulo,
                        especific,
                        upc,
                        codigo_rotina,
                        descricao_rotina,
                        registro_padrao,
                        visualiza_menu,
                        procedimento_pai
                    )
                VALUES(                                                                                                 
                    '".$json->cod_programa."',
                    '".$json->descricao_programa."',
                    '".$json->cod_modulo."',
                    '".$json->especific."',
                    '".$json->upc."',
                    '".$json->codigo_rotina."',
                    '".$json->descricao_rotina."',
                    '".$json->registro_padrao."',
                    '".$json->visualiza_menu."',
                    '".$json->procedimento_pai."')";
            try {
                $sql = $this->db->query($sql);
                $idPrograma = $this->db->lastInsertId();

                // Associa o programa a instância(empresa)
                if (!empty($idPrograma)) {
                    $sqlAddProgEmp = "INSERT INTO z_sga_programa_empresa SET idPrograma = $idPrograma, idEmpresa = " . $json->instancia;
                    $sqlAddProgEmp = $this->db->query($sqlAddProgEmp);
                }
            }catch (EXCEPTION $e) {
                return array(
                    'return'      => 'erro'  ,
                    'msg'         => $e->getMessage(),
                    'codigo_erro' => '3010'
                );
            }
        else:
            $sqlProg = $sqlProg->fetch(PDO::FETCH_ASSOC);
            $idPrograma = $sqlProg['z_sga_programas_id'];

            // Valida se programa está relacionado a alguma instância
            $sqlProgEmp = "
                SELECT 
                    * 
                FROM 
                    z_sga_programa_empresa                    
                WHERE                        
                    idPrograma = $idPrograma";

            $sqlProgEmp = $this->db->query($sqlProgEmp);

            if($sqlProgEmp->rowCount() == 0):
                $sqlAddProgEmp = "INSERT INTO z_sga_programa_empresa SET idPrograma = $idPrograma, idEmpresa = " . $json->instancia;
                $sqlAddProgEmp = $this->db->query($sqlAddProgEmp);
            endif;
        endif;

        // Valida se já existe programa atribuido para o grupo
        $sqlGrpProg = "
            SELECT 
                * 
            FROM 
                z_sga_grupo_programa                        
            WHERE                            
                idGrupo = $idGrupo
                AND idPrograma = $idPrograma";
        $sqlGrpProg = $this->db->query($sqlGrpProg);

        if($sqlGrpProg->rowCount() == 0):
            // Associa programa ao grupo
            $sql = "
            INSERT INTO
                z_sga_grupo_programa 
            SET
                cod_grupo    = '".$json->id_leg_grupo."',
                nome_grupo   = '".$json->desc_abrev."',
                gestor       = '',
                cod_programa = '".$json->cod_programa."',
                idGrupo      =  ".$idGrupo.",
                idPrograma   = " . $idPrograma;
            try{
                $sql = $this->db->query($sql);
            }catch (Exception $e){
                return array(
                    'return' => 'erro',
                    'msg'  => $e->getMessage()
                );
            }
        endif;

        $this->atualizaVMUsuarios();

        $fluxo = new Fluxo();
        $fluxo->gravaLogAuditoria(
            1,
            date('Y-m-d H:i:s'),
            date('Y-m-d H:i:s'),
            (isset($json->usrExecutor)) ? "'".$json->usrExecutor."'" : '',
            '',
            'ADICIONADO',
            $idGrupo .' - '. $json->desc_abrev,
            "'".$json->cod_programa."'",
            (isset($json->usrExecutor)) ? "'".$json->usrExecutor."'" : '',
            'a',
            0
        );

        return array(
            'return'      => 'sucesso',
            'msg'         => "Programa $json->cod_programa atribuido ao grupo $json->id_leg_grupo com sucesso",
            'codigo_erro' => '3020'
        );
    }

    /**
     * Remove programa do grupo informado
     * @param type $json
     * @return type
     */
    public function deleteGruposProgramas($json)
    {
        $idGrupo = '';
        $idPrograma = '';
        /********************************************************************************
         * VALIDAÇÃO DE GRUPO. SE NÃO EXISTIR O GRUPO. REALIZA O CADASTRO.
         ********************************************************************************/
        $sqlGrupo = "
            SELECT 
                * 
            FROM 
                z_sga_grupo 
            WHERE 
                idLegGrupo = '".$json->id_leg_grupo."' 
                AND idEmpresa = " . $json->instancia;
        $sqlGrupo = $this->db->query($sqlGrupo);

        if($sqlGrupo->rowCount() == 0):
            // Se não existir o grupo. Faz o cadastro.
            $sql = "
                INSERT INTO 
                    z_sga_grupo 
                SET 
                    idLegGrupo = '".addslashes($json->id_leg_grupo)."',
                    descAbrev = '".addslashes($json->desc_abrev)."',
                    idEmpresa = '".$json->instancia."'";

            try{
                $sql = $this->db->query($sql);
                $idGrupo = $this->db->lastInsertId();
            }catch (Exception $e){
                return array(
                    'return' => false,
                    'error'  => $e->getMessage()
                );
            }
        else:
            $sqlGrupoRs = $sqlGrupo->fetch(PDO::FETCH_ASSOC);
            $idGrupo = $sqlGrupoRs['idGrupo'];
        endif;

        /********************************************************************************
         * VALIDAÇÃO DE PROGRAMA. SE NÃO EXISTIR O PROGRAMA. REALIZA O CADASTRO.
         ********************************************************************************/
        $sqlProg = "
            SELECT 
                *
            FROM 
                z_sga_programas 
            WHERE 
                cod_programa = '".$json->cod_programa."'
                AND descricao_programa = '$json->descricao_programa'
                /*AND cod_programa = procedimento_pai  Apresenta apenas programas principais */";
        $sqlProg = $this->db->query($sqlProg);

        if($sqlProg->rowCount() == 0):
            // Se não existir o programa. Cadastra e devolve o id.
            // Insere o programa na base.
            $sql = "
                INSERT INTO 
                    z_sga_programas (
                        cod_programa,
                        descricao_programa,
                        cod_modulo,
                        especific,
                        upc,
                        codigo_rotina,
                        descricao_rotina,
                        registro_padrao,
                        visualiza_menu,
                        procedimento_pai
                    )
                VALUES(                                                                                                 
                    '".$json->cod_programa."',
                    '".$json->descricao_programa."',
                    '".$json->cod_modulo."',
                    '".$json->especific."',
                    '".$json->upc."',
                    '".$json->codigo_rotina."',
                    '".$json->descricao_rotina."',
                    '".$json->registro_padrao."',
                    '".$json->visualiza_menu."',
                    '".$json->procedimento_pai."')";
            try {
                $sql = $this->db->query($sql);
                $idPrograma = $this->db->lastInsertId();

                // Associa o programa a instância(empresa)
                if (!empty($idPrograma)) {
                    $sqlAddProgEmp = "INSERT INTO z_sga_programa_empresa SET idPrograma = $idPrograma, idEmpresa = " . $json->instancia;
                    $sqlAddProgEmp = $this->db->query($sqlAddProgEmp);
                }
            }catch (EXCEPTION $e) {
                return array(
                    'return'      => 'erro'  ,
                    'msg'         => $e->getMessage(),
                    'codigo_erro' => '3010'
                );
            }
        else:
            $sqlProg = $sqlProg->fetch(PDO::FETCH_ASSOC);
            $idPrograma = $sqlProg['z_sga_programas_id'];

            // Valida se programa está relacionado a alguma instância
            $sqlProgEmp = "
                SELECT 
                    * 
                FROM 
                    z_sga_programa_empresa                    
                WHERE                        
                    idPrograma = $idPrograma";

            $sqlProgEmp = $this->db->query($sqlProgEmp);

            if($sqlProgEmp->rowCount() == 0):
                $sqlAddProgEmp = "INSERT INTO z_sga_programa_empresa SET idPrograma = $idPrograma, idEmpresa = " . $json->instancia;
                $sqlAddProgEmp = $this->db->query($sqlAddProgEmp);
            endif;
        endif;

        // Valida se existe programa atribuido para o grupo
        $sqlGrpProg = "
            SELECT 
                * 
            FROM 
                z_sga_grupo_programa                        
            WHERE                            
                idGrupo = $idGrupo
                AND idPrograma = $idPrograma";
        $sqlGrpProg = $this->db->query($sqlGrpProg);

        if($sqlGrpProg->rowCount() > 0):
            $sql = "
                DELETE FROM
                    z_sga_grupo_programa 
                WHERE
                    idGrupo = $idGrupo
                    AND idPrograma = $idPrograma";
            try{
                $sql = $this->db->query($sql);
            }catch (Exception $e){
                return array(
                    'return' => 'erro',
                    'msg'  => $e->getMessage()
                );
            }
        endif;

        $this->atualizaVMUsuarios();
        $fluxo = new Fluxo();
        $fluxo->gravaLogAuditoria(
            1,
            date('Y-m-d H:i:s'),
            date('Y-m-d H:i:s'),
            (isset($json->usrExecutor)) ? "'".$json->usrExecutor."'" : '',
            '',
            'REMOVIDO',
            $json->id_leg_grupo .' - '. $json->desc_abrev,
            "'".$json->cod_programa."'",
            (isset($json->usrExecutor)) ? "'".$json->usrExecutor."'" : '',
            'a',
            0
        );

        return array(
            'return' => 'sucesso',
            'msg'    => "Programa $json->cod_programa removido do grupo $json->id_leg_grupo com sucesso"
        );
    }

    /**
     * Deleta módulo e tudo que estiver associado a ele
     * @param type $json
     * @return type
     */
    public function deleteModulos($json)
    {
        /*RETORNA O ID DO MODULO QUE SERÁ EXCLUIDO*/
        $sqlMod = "
            SELECT 
                * 
            FROM 
                z_sga_modul_dtsul
            WHERE 
                cod_modul_dtsul = '$json->cod_modul_dtsul'";
        $sqlMod = $this->db->query($sqlMod);

        if($sqlMod->rowCount() > 0):
            $sqlMod = $sqlMod->fetch(PDO::FETCH_ASSOC);
            $idModulo = $sqlMod['idMdlDtsul'];

            /* RETORNA A QUANTIDADE DE PROGRAMAS QUE O MODULO ESTÁ RELACIONADO */
            $sqlGetProg = "SELECT COUNT(*) AS total FROM z_sga_programas WHERE cod_modulo = '$json->cod_modul_dtsul'";
            $sqlGetProg = $this->db->query($sqlGetProg)->fetch(PDO::FETCH_ASSOC);

            // Se tiver mais de um retorna erro informando relacionamentos
            if($sqlGetProg['total'] > 1):
                return array(
                    'return' => 'erro',
                    'msg'    => "Nao foi possivel remover. O modulo $json->cod_modul_dtsul possui relacionamentos com ".$sqlGetProg['total']." programas."
                );
            else:
                try{
                    // Inicia transaction
                    $this->db->beginTransaction();

                    // Deleta a instancia
                    $sqlDelMod = "
                        DELETE FROM
                            z_sga_modul_dtsul
                        WHERE
                            idMdlDtsul = $idModulo";
                    $this->db->query($sqlDelMod);

                    // Executa o commit confirmando a exclusão
                    $this->db->commit();

                    return array(
                        'return' => 'sucesso',
                        'msg'    => "Modulo $json->cod_modul_dtsul removido com sucesso."
                    );
                } catch (Exception $e) {
                    // Desfaz querys executadas
                    $this->db->rollback();

                    return array(
                        'return' => 'erro',
                        'msg'    => $e->getMessage()
                    );
                }
            endif;
        else:
            return array(
                'return' => 'sucesso',
                'msg'    => "Modulo $json->cod_modul_dtsul removido com sucesso."
            );
        endif;
    }

    /**
     * Atualiza o complemento na tabela z_sga_modul_dtsul
     * @param type $json
     * @return type
     */
    public function putModulos($json)
    {
        // Cria variavel com os campos que ira atualizar
        $camposAtualizar = json_decode($json, true);
        unset($camposAtualizar['instancia']);
        unset($camposAtualizar['cod_modul_dtsul']);
        unset($camposAtualizar['origem']);
        unset($camposAtualizar['sistema']);
        unset($camposAtualizar['token']);

        // json com todos os campos
        $json = json_decode($json);

        // Busca na base se existe um modulo com o mesmo código
        $sqlGetMod = "
            SELECT 
                idMdlDtsul 
            FROM 
                z_sga_modul_dtsul 
            WHERE 
                cod_modul_dtsul = '".$json->cod_modul_dtsul."'";
        $sqlGetMod = $this->db->query($sqlGetMod);

        if($sqlGetMod->rowCount() > 0):
            $sqlGetMod = $sqlGetMod->fetch(PDO::FETCH_ASSOC);

            $idModulo =  $sqlGetMod['idMdlDtsul'];
            try{
                // Inicia transaction
                $this->db->beginTransaction();

                // Cria a query para atualização do complemento do programa
                $sql = "
                    UPDATE 
                        z_sga_modul_dtsul
                    SET 
                        cod_modul_dtsul = '$json->cod_modul_dtsul',
                        des_mudul_dtsul = '$json->des_modul_dtsul',
                        cod_sist_dtsul  = '$json->cod_sist_dtsul'";

                $sql .= " WHERE idMdlDtsul = $idModulo";
                $sql = $this->db->query($sql);

                // Atualiza a descricao nos programas relacionados 
                $sql = "
                    UPDATE 
                        z_sga_programas
                    SET 
                        descricao_modulo = '$json->des_mudul_dtsul' 
                    WHERE 
                        cod_modulo = '$json->cod_modul_dtsul'";
                $this->db->query($sql);

                // Executa commit das queries executadas
                $this->db->commit();

                // Retorna com sucesso e mensagem
                return array(
                    'return'  => 'sucesso',
                    'msg'     => 'Modulo ' . $json->cod_modul_dtsul . ' atualizado com sucesso'
                );
            } catch (Exception $e) {
                // Desfaz as queries executadas
                $this->db->rollback();

                return array(
                    'return'    => 'erro',
                    'msg'       => $e->getMessage()
                );
            }
        else:
            return array(
                'return'  => 'erro',
                'msg'     => 'Modulo ' . $json->cod_modul_dtsul . ' nao encontrado'
            );
        endif;

    }

    /**
     * Insere e associa modulos à instância(empresa), segundo parâmetros.
     * @param type $json
     * @return string
     */
    public function postModulos($json)
    {
        // Cria variavel com os campos que ira inserir
        $camposInserir = json_decode($json, true);
        unset($camposInserir['instancia']);
        unset($camposInserir['origem']);
        unset($camposInserir['sistema']);
        unset($camposInserir['token']);

        // json com todos os campos
        $json = json_decode($json);

        // Busca na base se existe um modulo com o mesmo código
        $sqlGetMod = "
            SELECT 
                idMdlDtsul 
            FROM 
                z_sga_modul_dtsul 
            WHERE 
                cod_modul_dtsul = '".$json->cod_modul_dtsul."'";
        $sqlGetMod = $this->db->query($sqlGetMod);

        if($sqlGetMod->rowCount() > 0):
            // Retorna o mensagem de erro de modulo existente
            return array(
                'return'  => 'sucesso',
                'msg'     => "Modulo $json->cod_modul_dtsul cadastrado com sucesso"
            );
        else:
            // Insere o modulo na base.
            $sql = "
                INSERT INTO 
                    z_sga_modul_dtsul 
                SET 
                    cod_modul_dtsul = '$json->cod_modul_dtsul',
                    des_mudul_dtsul = '$json->des_modul_dtsul',
                    cod_sist_dtsul  = '$json->cod_sist_dtsul'";
            try{
                $sql = $this->db->query($sql);
                return array(
                    'return'  => 'sucesso',
                    'msg'     => 'Modulo '.$json->cod_modul_dtsul.' cadastrado com sucesso'
                );
            } catch (Exception $e){
                // Retorna tipo erro e a mensagem do mesmo
                return array(
                    'return'  => 'erro',
                    'msg'     => $e->getMessage()
                );
            }
        endif;
    }

    /**
     * Retorna os dados do módulo com mesmo cod_modul_dtsul informado nos parâmetros
     * @param type $json
     * @return type
     */
    public function getModulos($json)
    {
        $sql = "
            SELECT 
                cod_modul_dtsul,
                des_mudul_dtsul AS des_modul_dtsul,
                cod_sist_dtsul
            FROM 
                z_sga_modul_dtsul 
            WHERE 
                cod_modul_dtsul = '$json->cod_modul_dtsul'";

        try{
            $sql = $this->db->query($sql);

            $data = array();

            if($sql->rowCount() > 0):
                $data = $sql->fetch(PDO::FETCH_ASSOC);

                return array(
                    'return'    => 'sucesso',
                    'msg'       => 'Dados recuperado com sucesso',
                    'dados'     => $data
                );
            else:
                return array(
                    'return' => 'erro',
                    'msg'    => "Modulo $json->cod_modul_dtsul nao encontrado"
                );
            endif;


        } catch (Exception $e) {
            return array(
                'return' => 'erro',
                'msg'    => $e->getMessage()
            );
        }
    }

    public function validalogin($usuario,$senha){
        $senha = md5($senha);
        $sql = "SELECT * FROM z_sga_param_login where login = '$usuario' AND senha = '$senha' ";
        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function token(){
        $iniciar = curl_init('http://appsga.com.br/dev/Api/token');
        curl_setopt($iniciar, CURLOPT_RETURNTRANSFER, true);
        $dados = array(
            "usuario"=>'super',
            "senha"=>"ngf123",
            "ambiente"=> "DEV",
            'origem' => 'ERP',
            'sisetma' => 'DATASUL',
        );
        curl_setopt($iniciar, CURLOPT_POST, true);
        curl_setopt($iniciar, CURLOPT_POSTFIELDS, $dados);
        $token = curl_exec($iniciar);
        curl_close($iniciar);
        
        return $token;
    }

    public function getConfigGlobal()
    {
        $sql = "SELECT * FROM z_sga_param_global";

        return $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
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

}