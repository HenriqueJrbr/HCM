<?php
class Empresa extends Model {

      public function __construct(){
      	parent::__construct();
      }

    public function carregaEmpresa($idEmpresa = null)
    {          
        $where = (!empty($idEmpresa)) ? " WHERE idEmpresa = $idEmpresa" : '' ;
        $sql = "
            SELECT 
                *  
            FROM 
                z_sga_empresa
            $where" ;
        $sql = $this->db->query($sql);
        $array = array();
        if($sql->rowCount()>0){
              $array = $sql->fetchAll(PDO::FETCH_ASSOC);
        }
        return $array;
      }
      
    /**
     * Edita as empresas
     * @param type $idEmpresa
     * @param type $razaoSocial
     * @param type $cnpj
     * @return type
     */
      
    public function getIntegrationData($idEmpresa, $erp)
    {
        $sql = "
            SELECT
                JSON_EXTRACT(
                    integration_data, 		
                    \"$.$erp\"
                ) AS integrationData 
            FROM 
                z_sga_empresa 
            WHERE 
                idEmpresa = $idEmpresa";
        try{
            $rsIntegracao = $this->db->query($sql);

            if($rsIntegracao->rowCount() > 0):                
                $integrationData = $rsIntegracao->fetch(PDO::FETCH_ASSOC);
               
                return json_decode($integrationData['integrationData']);
                
            endif;
        } catch (Exception $e) {
            return $e->getMessage();
        }

    }
      
    public function editaEmpresa($idEmpresa, $razaoSocial, $cnpj, $integrationData, $nameLogo){
        $query = "
            UPDATE 
                z_sga_empresa 
            SET 
                razaoSocial      = '$razaoSocial',
                cnpj             = '$cnpj',
                integration_data = '$integrationData', 
                logo             = '$nameLogo' 
            WHERE idEmpresa = '$idEmpresa'";

        try{
            $this->db->query($query);

            return array('return' => true);
        } catch (Exception $e) {
            return array(
                'return' => true,
                'error'  => $e->getMessage()
            );
        }                                    
    }
      
      /**
       * Cadastra novas empresas
       * @param type $addIdTotvs
       * @param type $razaoSocial
       * @param type $cnpj
       * @return type
       */
      public function cadastraEmpresa($addIdTotvs, $razaoSocial, $cnpj, $json = '', $nameFile){
            $query = "
                INSERT INTO 
                    z_sga_empresa 
                SET 
                    idLegEmpresa = '$addIdTotvs',
                    razaoSocial = '$razaoSocial',
                    cnpj = '$cnpj',
                    integration_data = '$json',
                    logo             = '$nameFile'";
                   
            try{
                $this->db->query($query);
                
                return array('return' => true);
            } catch (Exception $e) {
                return array(
                    'return' => false,
                    'error'  => $e->getMessage()
                );
            }            
      }
      
    /**
     * Exclui empresa
     * @param type $idEmpresa
     * @return type
     */
    public function excluiEmpresa($idEmpresa)
    {
        $query = "
            DELETE FROM
                z_sga_empresa
            WHERE
                idEmpresa = $idEmpresa";
        
        try{
            $this->db->query($query);
            
            return array('return' => true);
        } catch (Exception $e) {
            return array(
                'return' => false,
                'error'  => $e->getMessage()
            );
        }
        
    }

    public function validaExclusaoDBGrupo($idEmpresa)
    {       
        $query = "
            SELECT 
                count(idEmpresa) as total
            FROM 
                z_sga_grupo 
            WHERE 
                idEmpresa = $idEmpresa";   
        
        $res = $this->db->query($query);

        // $sql2 = "SELECT count(idEmpresa) from z_sga_usuario_empresa where idEmpresa = '$idEmpresa'";
        // $sql2 = $this->db->query($sql2);

        if($res->rowCount() > 0){
            return $res->fetch(PDO::FETCH_ASSOC);
        }

        return array();
    }

    public function validaExclusaoDBEmpresa($idEmpresa)
    {                  
        $query = "
            SELECT 
                count(idEmpresa) as total
            FROM 
                z_sga_usuario_empresa 
            WHERE 
                idEmpresa = '$idEmpresa'";

        $res = $this->db->query($query);

        if($res->rowCount() > 0){
            return $res->fetch(PDO::FETCH_ASSOC);
        }

        return array();
    }
      
}