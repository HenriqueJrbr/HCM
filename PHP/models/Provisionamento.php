<?php

class Provisionamento extends Model
{
    public function __construct(){
  	parent::__construct();
    }
    
    /**
     * Retorna as instancias
     * @return type
     */
    public function carregaInstancias($instancia)
    {
        $sql = "
            SELECT
                idEmpresa,
                razaoSocial
            FROM
                z_sga_empresa";
        
        if($instancia != ''):
            $sql .= " WHERE idEmpresa = $instancia";
        endif;
        
        $sql = $this->db->query($sql);
        
        if($sql->rowCount() > 0):
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        else:
            return array();
        endif;
    }
    
    /**
     * Retorna as instancias
     * @return type
     */
    public function carregaFuncoes($funcao)
    {
        $sql = "
            SELECT
                idFuncao,
                descricao
            FROM
                z_sga_manut_funcao
            ";
        
        
        if($funcao != ''):
            $sql .= " WHERE idFuncao = $funcao ";
        else:
            //$sql .= " WHERE idFuncao NOT IN(SELECT idFuncao FROM z_sga_prov_funcao_grupo) ";
        endif;        

        $sql .= " ORDER BY descricao ";
        
        $sql = $this->db->query($sql);
        
        if($sql->rowCount() > 0):
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        else:
            return array();
        endif;
    }
    
    
    /**
     * Retorna os grupos pela instancia selecionada
     * @return type
     */
    public function ajaxCarregaGrupos($funcao, $instancia)
    {
        $sql = "
            SELECT
                idGrupo,
                CONCAT(idLegGrupo, ' - ', descAbrev) descricao
            FROM
                z_sga_grupo
            WHERE
                idEmpresa = $instancia
                AND idGrupo NOT IN(SELECT idGrupo FROM z_sga_prov_funcao_grupo WHERE idFuncao = $funcao)";
        
        $sql = $this->db->query($sql);
        
        if($sql->rowCount() > 0):
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        else:
            return array();
        endif;
    } 
    
    /**
     * Retorna os grupos pela instancia selecionada
     * @return type
     */
    public function ajaxBuscaGrupos($grupo, $instancia, $eliminar)
    {
        $sql = "
            SELECT
                idGrupo,
                CONCAT(idLegGrupo, ' - ', descAbrev) descricao
            FROM
                z_sga_grupo
            WHERE
                idEmpresa = $instancia
                AND (idLegGrupo LIKE '$grupo%' OR descAbrev LIKE '$grupo%')";

        if(!empty($eliminar)):
            $sql .= " AND idGrupo NOT IN (".implode(',', $eliminar).")";
        endif;
        
        
        $sql = $this->db->query($sql);
        
        if($sql->rowCount() > 0):
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        else:
            return array();
        endif;
    }
    
    /**
     * Retorna os grupos pela instancia selecionada
     * @return type
     */
    public function ajaxCarregaProvisionamentosDatatable($search, $orderColumn, $orderDir, $offset, $limit, $post)
    {
        $sqlFields = '';
        $where = '';
        $groupBy = '';
        if((isset($post['empresa']) && $post['empresa'] != '') && (isset($post['funcao']) && $post['funcao'] != '')):
            $sqlFields = ",CONCAT(g.idLegGrupo, ' - ', g.descAbrev) AS grupo ";
            $where .= "
                WHERE
                    fg.idEmpresa = ". $post['empresa'] . "
                    AND fg.idFuncao = " . $post['funcao'];
        else:
            $sqlFields .= ",GROUP_CONCAT(DISTINCT concat_ws(' - ', g.idLegGrupo) ORDER BY g.idLegGrupo SEPARATOR ' | ') AS grupo";
            $sqlFields .= ",fg.idFuncao AS idFuncao";
            $sqlFields .= ",fg.idEmpresa AS idEmpresa ";
            $sqlFields .= ",e.razaoSocial AS empresa";
            $groupBy = " GROUP BY fg.idFuncao, fg.idEmpresa";
        endif;
        
        $sql = "
            SELECT
                fg.idFuncaoGrupo,                
                f.descricao AS funcao
                $sqlFields
            FROM
                z_sga_prov_funcao_grupo fg
            LEFT JOIN                
                z_sga_grupo g
                ON fg.idGrupo = g.idGrupo
            LEFT JOIN
                z_sga_manut_funcao f
                ON fg.idFuncao = f.idFuncao
            LEFT JOIN
                z_sga_empresa e
                ON fg.idEmpresa = e.idEmpresa
            $where";
        
         // Se a variavel $search não estiver vazia o where no select
        if($search != ''):
            $sql .= (($where == '') ? " WHERE " : " AND ") . " g.idLegGrupo LIKE '%$search%'";
            $sql .= " OR g.descAbrev LIKE '%$search%'";
            $sql .= " OR f.descricao LIKE '%$search%'";            
        endif;
                
        $sql .= $groupBy;
        
        // Se a variavel $order não estiver vazia ordena o retorno
        if($orderColumn != ''):
            $sql .= " ORDER BY $orderColumn $orderDir";
        endif;

        // Se as variaveis $offset e $limit não estiverem vazias limita o retorno
        if($offset != '' && $limit != ''):
            $sql .= " LIMIT $offset, $limit";
        endif;                    
        
        $sql = $this->db->query($sql);
        
        if($sql->rowCount() > 0):
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        else:
            return array();
        endif;
    }
    
    public function getCountGrupos($funcao)
    {
        $sql = "
            SELECT
                count(idFuncaoGrupo) AS total
            FROM
                z_sga_prov_funcao_grupo
            WHERE
                idFuncao = $funcao";

        $sql = $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);

        return $sql['total'];
    }
    
    /**
     * Responsável por retornar o total de registros encontrados na tabela    
     * @param $search String contendo o filtro     
     * @param $post Array com empresa e função a filtrar
     */
    public function getCountTableProvisionamento($search, $post)
    {
        $dados = array();
        
        $where = '';
        $groupBy = '';
        if((isset($post['empresa']) && $post['empresa'] != '') && (isset($post['funcao']) && $post['funcao'] != '')):
            $where .= "
                WHERE
                    fg.idEmpresa = ". $post['empresa'] . "
                    AND fg.idFuncao = " . $post['funcao'];
        else:
            $groupBy = " GROUP BY fg.idFuncao, fg.idEmpresa";
        endif;
        
        $sql = "
            SELECT
                count(fg.idFuncaoGrupo) AS total
            FROM
                z_sga_prov_funcao_grupo fg
            LEFT JOIN                
                z_sga_grupo g
                ON fg.idGrupo = g.idGrupo
            LEFT JOIN
                z_sga_manut_funcao f
                ON fg.idFuncao = f.idFuncao
            LEFT JOIN
                z_sga_empresa e
                ON fg.idEmpresa = e.idEmpresa
            $where ";
        
         // Se a variavel $search não estiver vazia o where no select
        if($search != ''):
            $sql .= (($where == '') ? " WHERE " : " AND ") . " AND g.idLegGrupo LIKE '%$search%'";
            $sql .= " OR g.descAbrev LIKE '%$search%'";
            $sql .= " OR f.descricao LIKE '%$search%'";            
        endif;
        
        $sql .= $groupBy;
        
        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0):
            $dados = $sql->fetch(PDO::FETCH_ASSOC);
        else:
            return [];
        endif;

        //return $dados['total'];
    }
    
    
    /**
     * Adiciona provisionamentos
     * @param type $data
     * @return type
     */
    public function gravaFuncaoGrupo($data)
    {
        $sql = "INSERT INTO z_sga_prov_funcao_grupo(idFuncao, idGrupo, idEmpresa) VALUES ";
        
        $i = 1;
        foreach($data['grupos'] as $val):            
            $sql .= "(".$data['funcao'].",".$val.",".$data['empresa'].")";
            $sql .= ($i < count($data['grupos'])) ? ', ' : '';
            $i++;
        endforeach;
        
        try{
            $this->db->query($sql);
            
            return array('return' => true);
        } catch (Exception $e) {
            return array(
                'return' => false,
                'erro'   => $e->getMessage()
            );
        }
    }
    
    /**
     * excluir provisionamentos
     * @param type $data
     * @return type
     */
    public function excluiFuncaoGrupo($ids)
    {
        $sql = "
            DELETE FROM 
                z_sga_prov_funcao_grupo 
            WHERE 
                idFuncaoGrupo IN(".implode(',', $ids).")";
        
        try{
            $this->db->query($sql);
            
            return array('return' => true);
        } catch (Exception $e) {
            return array(
                'return' => false,
                'erro'   => $e->getMessage()
            );
        }
    }
}