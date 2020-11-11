<?php
/**
 * Created by Rodrigo Gomes do Nascimento.
 * User: a2
 * Date: 04/01/2019
 * Time: 12:39
 */

class Agendamento extends Model
{
    public function __construct(){
        parent::__construct();
    }

    /**
     * Busca os agendamentos que ainda não expiraram
     * @return array Agendamentos
     */
    public function buscaAgendamentos()
    {
        $sql = "SELECT * FROM z_sga_fluxo_agendamento_acesso WHERE dataInicio >= '".date('Y-d-m H:i:s')."'";

        try{
            $result = $this->db->query($sql);
            return array(
                'return'    => true,
                'result'    => $result->fetchAll()
            );
        }catch (Exception $e){
            return array(
                'return'    => false,
                'error'     => $e->getMessage()
            );
        }
    }
    
    /**
     * Busca os agendamentos que expiraram
     * @return array Agendamentos
     */
    public function buscaAgendamentosInativos()
    {
        $sql = "SELECT * FROM z_sga_fluxo_agendamento_acesso WHERE situacao = 1";

        try{
            $result = $this->db->query($sql);
            return array(
                'return'    => true,
                'result'    => $result->fetchAll()
            );
        }catch (Exception $e){
            return array(
                'return'    => false,
                'error'     => $e->getMessage()
            );
        }
    }

    /**
     * Busca as empresas
     * @return array Empresas
     */
    public function buscaEmpresas()
    {
        $sql = "SELECT idEmpresa, razaoSocial FROM z_sga_empresa";

        try{
            $result = $this->db->query($sql);
            return array(
                'return'    => true,
                'result'    => $result->fetchAll()
            );
        }catch (Exception $e){
            return array(
                'return'    => false,
                'error'     => $e->getMessage()
            );
        }
    }
    
    /**
     * Cria agendamentos na tabela z_sga_fluxo_agendamento_acesso
     * @param $data Array com a data e os usuarios a agendar
     * @return array
     */
    public function addAgendaRevisaoAcesso($data)
    {
        // Cria a query para o insert
        $sql = "
            INSERT INTO
                z_sga_fluxo_agendamento_acesso(
                    dataInicio,
                    dataFim,
                    situacao,
                    idSolicitante,                                        
                    idEmpresa,                                    
                    idUsuario                                     
                )VALUES ";

        for($i = 0; $i < count($data['idUsuario']); $i++):
            $expUser = explode('-',$data['idUsuario'][$i]);
            $dataInicio = trim(str_replace('/','-',$expUser[2]));
            $dataFim = str_replace('/','-',$expUser[3]);
            //$sql .= "('" . $data['data'] . "', 0," . $data['idSolicitante'] . "," . trim($expUser[1]).",'" . trim($expUser[0]) . (($i + 1 == count($data['idUsuario'])) ? "')" : "'),");
            $sql .= "('" . $dataInicio . "', '" . $dataFim . "', 0," . $data['idSolicitante'] . "," . trim($expUser[1]).",'" . trim($expUser[0]) . (($i + 1 == count($data['idUsuario'])) ? "')" : "'),");
        endfor;
        
        // Executa a query e retorna o resultado
        try{
            $this->db->query($sql);
            return array('return' => true);
        }catch (Exception $e){
            return array(
                'return'    => false,
                'error'     => $e->getMessage()
            );
        }
    }

    /**
     * Valida se já existe agendamentos para usuários e data selecionada
     * @param $data
     * @return array
     */
    public function validaAgendaRevisaoExistente($data)
    {
        //echo "<pre>";
        //print_r($data);
        $sql_in = '';
        // Se for string consulta apenas um usuário. Utilizado pela validação ajax.
        /*if(is_string($data['idUsuario'])):            
            $expUser = explode(' - ',$data['idUsuario']);
            $sql_in = " dataInicio >= '".$data['dataInicio']."' AND dataFim <= '".$data['dataFim']."' AND idUsuario IN('*',"."'".$expUser[0]."'".") AND idEmpresa = ".$data['idEmpresa'];
        else:
            
            //echo "<pre>";
            //print_r($data);
            // Se for uma array com mais de um usuário. Percorre o array e adiciona as clausulas na query
            for($i = 0; $i < count($data['idUsuario']); $i++):
                $expUser = explode(' - ',$data['idUsuario'][$i]);
                $dataInicio = str_replace('/','-',$expUser[2]);
                $dataFim = str_replace('/','-',$expUser[3]);
                if($i == 0):
                    //$sql_in .= " (data = '".$data['data']."' AND (idUsuario = '".$expUser[0]."' AND idEmpresa = ".$expUser[1].")) OR (data = '".$data['data']."' AND (idUsuario = '*' AND idEmpresa = ".$expUser[1]."))";
                    $sql_in .= " (dataInicio >= '".$dataInicio."' AND dataFim <= '".$dataFim."' AND (idUsuario = '".$expUser[0]."' AND idEmpresa = ".$expUser[1].")) OR (dataInicio >= '".$dataInicio."' AND dataFim <= '".$dataFim."' AND (idUsuario = '*' AND idEmpresa = ".$expUser[1]."))";
                else:
                    //$sql_in .= " OR (data = '".$data['data']."' AND (idUsuario = '".$expUser[0]."' AND idEmpresa = ".$expUser[1]."))  OR (data = '".$data['data']."'AND (idUsuario = '*' AND idEmpresa = ".$expUser[1]."))";
                    $sql_in .= " OR (dataInicio >= '".$dataInicio."' AND dataFim <= '".$dataFim."' AND (idUsuario = '".$expUser[0]."' AND idEmpresa = ".$expUser[1]."))  OR (dataInicio >= '".$dataInicio."' AND dataFim <= '".$dataFim."' AND (idUsuario = '*' AND idEmpresa = ".$expUser[1]."))";
                endif;
            endfor;
        endif;*/
        
        $sql = "
            SELECT 
                a.idAgendamento,
                a.dataInicio,
                a.dataFim,
                IF(a.idUsuario <> '*',(SELECT nome_usuario FROM z_sga_usuarios usr WHERE usr.z_sga_usuarios_id = a.idUsuario),'TODOS') AS usuario,    
                (SELECT nome_usuario FROM z_sga_usuarios usr WHERE usr.z_sga_usuarios_id = a.idSolicitante) AS solicitante,
                e.razaoSocial
            FROM 
                z_sga_fluxo_agendamento_acesso a
            LEFT JOIN
                z_sga_usuarios u
                ON a.idUSuario = u.z_sga_usuarios_id
            LEFT JOIN
                z_sga_empresa e
                ON a.idEmpresa = e.idEmpresa
            WHERE  
                (MONTH(a.dataInicio) >= MONTH('".$data['dataInicio']."') AND MONTH(a.dataFim) <= MONTH('".$data['dataFim']."')
                AND (a.dataInicio <= '".$data['dataFim']."' AND a.dataFim >= '".$data['dataInicio']."')
                OR (a.dataFim >= '".$data['dataFim']."' AND a.dataInicio <= '".$data['dataInicio']."')
                OR (a.dataInicio >= '".$data['dataInicio']."' AND (a.dataInicio <= '".$data['dataFim']."' AND a.dataFim >= '".$data['dataFim']."'))
                OR (a.dataInicio >= '".$data['dataInicio']."' AND a.dataFim <= '".$data['dataFim']."'))".
                ((isset($data['idUsuario']) && $data['idUsuario'] != '') ? " AND a.idUsuario IN('*',".$data['idUsuario'].")" : '' )." 
                AND a.idEmpresa = ".$data['idEmpresa']." 
                AND a.situacao = 0
            ORDER BY
                a.dataInicio";
        //echo "<pre>";
        //die($sql);

        try{
            $rs = $this->db->query($sql);
                        
            return array(
                'return' => true,
                'result' => $rs->rowCount(),
                'data'   => ($rs->rowCount() > 0) ? $rs->fetchAll(PDO::FETCH_ASSOC) : ''
            );                                    
        }catch (Exception $e){
            return array(
                'return'    => false,
                'error'     => $e->getMessage()
            );
        }
    }
    
    /**
     * Retorna se já existe agendamentos para usuários e data maior que a selecionada
     * @param $data
     * @return array
     */
    public function validaAgendaPosterior($data)
    {
        //echo "<pre>";
        //print_r($data);
        /*$sql_in = '';
        // Se for string consulta apenas um usuário. Utilizado pela validação ajax.
        if(is_string($data['idUsuario'])):
            $expUser = explode(' - ',$data['idUsuario']);
            $sql_in = " dataInicio >= '".$data['dataInicio']."' AND idUsuario IN('*',"."'".$expUser[0]."'".") AND idEmpresa = ".$data['idEmpresa'];
        else:
            // Se for uma array com mais de um usuário. Percorre o array e adiciona as clausulas na query
            for($i = 0; $i < count($data['idUsuario']); $i++):
                $expUser = explode(' - ',$data['idUsuario'][$i]);
                $dataInicio = str_replace('/','-',$expUser[2]);
                $dataFim = str_replace('/','-',$expUser[3]);
                if($i == 0):
                    //$sql_in .= " (data = '".$data['data']."' AND (idUsuario = '".$expUser[0]."' AND idEmpresa = ".$expUser[1].")) OR (data = '".$data['data']."' AND (idUsuario = '*' AND idEmpresa = ".$expUser[1]."))";
                    $sql_in .= " (dataInicio >= '".$dataInicio."' AND (idUsuario = '".$expUser[0]."' AND idEmpresa = ".$expUser[1].")) OR (dataInicio >= '".$dataInicio."' AND (idUsuario = '*' AND idEmpresa = ".$expUser[1]."))";
                else:
                    //$sql_in .= " OR (data = '".$data['data']."' AND (idUsuario = '".$expUser[0]."' AND idEmpresa = ".$expUser[1]."))  OR (data = '".$data['data']."'AND (idUsuario = '*' AND idEmpresa = ".$expUser[1]."))";
                    $sql_in .= " OR (dataInicio >= '".$dataInicio."' AND (idUsuario = '".$expUser[0]."' AND idEmpresa = ".$expUser[1]."))  OR (dataInicio >= '".$dataInicio."' AND (idUsuario = '*' AND idEmpresa = ".$expUser[1]."))";
                endif;
            endfor;
        endif;*/
        
        $sql = "
            SELECT 
                a.idAgendamento,
                a.dataInicio,
                a.dataFim,
                (SELECT nome_usuario FROM z_sga_usuarios usr WHERE usr.z_sga_usuarios_id = a.idUsuario) AS usuario,
                (SELECT nome_usuario FROM z_sga_usuarios usr WHERE usr.z_sga_usuarios_id = a.idSolicitante) AS solicitante,
                e.razaoSocial
            FROM
                z_sga_fluxo_agendamento_acesso a
            LEFT JOIN
                z_sga_usuarios u
                ON a.idUSuario = u.z_sga_usuarios_id
            LEFT JOIN
                z_sga_empresa e
                ON a.idEmpresa = e.idEmpresa
            WHERE                                 
                a.dataInicio >= '".$data['dataInicio']."' 
                AND a.idUsuario IN('*','".$data['idUsuario']."')
                AND a.idEmpresa = ".$data['idEmpresa']." 
                AND a.situacao = 0";

        //die($sql);

        try{
            $rs = $this->db->query($sql);                                        
            return array(
                'return' => true,
                'result' => $rs->rowCount(),
                'data'   => ($rs->rowCount() > 0) ? $rs->fetchAll(PDO::FETCH_ASSOC) : ''
            );     
        }catch (Exception $e){
            return array(
                'return'    => false,
                'error'     => $e->getMessage()
            );
        }
    }
    
    
    /**
     * Retorna se já existe agendamentos para usuários e data maior que a selecionada
     * @param $data
     * @return array
     */
    public function validaRevisaoAberta($data)
    {                
        //echo "<pre>";
        //print_r($data);
        
        $sql = "
            SELECT 
                a.idAgendamento,
                a.dataInicio,
                a.dataFim,
                (SELECT nome_usuario FROM z_sga_usuarios usr WHERE usr.z_sga_usuarios_id = a.idUsuario) AS usuario,
                (SELECT nome_usuario FROM z_sga_usuarios usr WHERE usr.z_sga_usuarios_id = a.idSolicitante) AS solicitante,
                e.razaoSocial
            FROM 
                z_sga_fluxo_agendamento_acesso a
            RIGHT JOIN
                z_sga_fluxo_solicitacao s	
                ON a.idAgendamento = s.idAgendamento
            LEFT JOIN
                z_sga_usuarios u
                ON a.idUSuario = u.z_sga_usuarios_id
            LEFT JOIN
                z_sga_empresa e
                ON a.idEmpresa = e.idEmpresa
            WHERE 	
                (a.dataInicio <= '".$data['dataInicio']."' AND a.dataFim >= '".$data['dataFim']."')
                AND a.dataFim >= a.dataInicio".
                (isset($data['idUsuario']) && $data['idUsuario'] != '' ? " AND a.idUsuario IN('*','".$data['idUsuario']."')" : '')." 
                AND a.idEmpresa = ".str_replace('+', '',$data['idEmpresa'])." 
                AND s.status = 1";

        //die($sql);

        try{
            $rs = $this->db->query($sql);                                        
            return array(
                'return' => true,
                'result' => $rs->rowCount(),
                'data'   => ($rs->rowCount() > 0) ? $rs->fetchAll(PDO::FETCH_ASSOC) : ''
            );       
        }catch (Exception $e){
            return array(
                'return'    => false,
                'error'     => $e->getMessage()
            );
        }
    }

    /**
     * Retorna os usuários filtrando pela variável string contida na $strSearch
     * @param $strSearch String de consulta
     * @return array
     */
    public function ajaxCarregaUsuario($data){
        $sql = "
            SELECT 
                u.nome_usuario     AS nome_usuario,
                u.cod_usuario      AS cod_usuario,
                userEmp.idUsuario  AS idUsuario,
                userEmp.idEmpresa,
                e.razaoSocial   
            FROM 
                z_sga_usuario_empresa AS userEmp,
                z_sga_usuarios AS u ,
                z_sga_manut_funcao AS m,
                z_sga_empresa e
            WHERE 
                userEmp.idEmpresa = '".$data['idEmpresa']."'
                AND userEmp.idEmpresa = e.idEmpresa  
                AND userEmp.idUsuario = u.z_sga_usuarios_id                
                AND u.cod_funcao = m.idFuncao 
                AND userEmp.ativo = 1
                AND u.nome_usuario LIKE '%".$data['idUsr']."%'";

        $sql = $this->db->query($sql);
        $data = array();

        if($sql->rowCount()>0){
            $data = $sql->fetchAll();
        }
        return $data;
    }

    /**
     * Busca os agendamentos data corrente a diante
     * @return array
     */
    public function ajaxDatatableAgendamento()
    {
        $dia = date('d') + 1;
        $sql = "
            SELECT
                a.idAgendamento,
                a.dataInicio,
                a.dataFim,
                idUsuario,
                u.nome_usuario,
                e.razaoSocial AS empresa
            FROM 
                z_sga_fluxo_agendamento_acesso a
            LEFT JOIN
                z_sga_usuarios u
                ON a.idUsuario = u.z_sga_usuarios_id
            LEFT JOIN
                z_sga_empresa e
                ON a.idEmpresa = e.idEmpresa
            WHERE
                dataInicio >= '".date("Y-m-$dia")."'                
            ORDER BY
                dataInicio ASC";

        try{
            $sql = $this->db->query($sql);
            return array(
                'return'    => true,
                'result'    => $sql->fetchAll()
            );
        }catch (Exception $e){
            return array(
                'result'    => false,
                'error'     => $e->getMessage()
            );
        }
    }
    
    /**
     * Busca os agendamentos expirados e inativos
     * @return array
     */
    public function ajaxDatatableAgendamentoInativo()
    {        
        $sql = "
            SELECT
                a.idAgendamento,
                a.dataInicio,
                a.dataFim,
                idUsuario,
                u.nome_usuario,
                e.razaoSocial AS empresa
            FROM 
                z_sga_fluxo_agendamento_acesso a
            LEFT JOIN
                z_sga_usuarios u
                ON a.idUsuario = u.z_sga_usuarios_id
            LEFT JOIN
                z_sga_empresa e
                ON a.idEmpresa = e.idEmpresa
            WHERE                
                a.situacao = 0
                AND dataInicio <= NOW()
            ORDER BY
                dataInicio ASC";

        try{
            $sql = $this->db->query($sql);
            return array(
                'return'    => true,
                'result'    => $sql->fetchAll()
            );
        }catch (Exception $e){
            return array(
                'result'    => false,
                'error'     => $e->getMessage()
            );
        }
    }
    
    /**
     * Busca os agendamentos executados
     * @return array
     */
    public function ajaxDatatableAgendamentoFinalizado()
    {        
        $sql = "
            SELECT
                a.idAgendamento,
                a.dataInicio,
                a.dataFim,
                idUsuario,
                u.nome_usuario,
                e.razaoSocial AS empresa
            FROM 
                z_sga_fluxo_agendamento_acesso a
            LEFT JOIN
                z_sga_usuarios u
                ON a.idUsuario = u.z_sga_usuarios_id
            LEFT JOIN
                z_sga_empresa e
                ON a.idEmpresa = e.idEmpresa
            WHERE                
                a.situacao = 1
                AND dataInicio <= NOW()
            ORDER BY
                dataInicio ASC";

        try{
            $sql = $this->db->query($sql);
            return array(
                'return'    => true,
                'result'    => $sql->fetchAll()
            );
        }catch (Exception $e){
            return array(
                'result'    => false,
                'error'     => $e->getMessage()
            );
        }
    }

    /**
     * Apaga agendamentos selecionados no dataTable
     * @param $ids
     * @return array
     */
    public function apagaAgendamentos($data)
    {
        $ids = implode(',', $data);
        $sql = "DELETE FROM z_sga_fluxo_agendamento_acesso WHERE idAgendamento IN($ids)";

        // Executa a query e retorna o resultado
        try{
            $sql = $this->db->query($sql);

            return array('return' => true);
        }catch(Exception $e){
            return array(
                'return' => false,
                'error'  => $e->getMessage()
            );
        }
    }
}