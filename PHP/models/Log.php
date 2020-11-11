
<?php
class Log extends Model {

      public function __construct(){
      	parent::__construct();
      }

      public function carregaLog($dataInicio, $dataFim,$solicitante,$solicitanteFim,$aprovador,$aprovadorFim, $usuario, $usuarioFim, $grupo, $grupoFim, $programa, $programaFim){
            $sql = "
                SELECT 
                    *,                    
                    (
                        SELECT 
                            url
                        FROM
                            z_sga_fluxo_form fm
                        LEFT JOIN
                            z_sga_fluxo_movimentacao m
                            ON fm.idForm = m.form
                        WHERE
                            m.idSolicitacao = L.idSolicitacao
                        GROUP BY
                            m.idSolicitacao
                    ) as form
                FROM 
                    z_sga_log L 
                WHERE 
                    L.dataInicio        >= '$dataInicio 00:00:00'                     
                    and L.dataFim       <= '$dataFim 23:59:59'                     
                    and idProcesso      >= 0  
                    and idProcesso      <= 999
                    and solicitante     >= '$solicitante' 
                    and solicitante     <= '$solicitanteFim'
                    and aprovadorAcao   >= '$aprovador' 
                    and aprovadorAcao   <= '$aprovadorFim'
                    and usuario         >= '$usuario' 
                    and usuario         <= '$usuarioFim'
                    and grupo           >= '$grupo'
                    and grupo           <= '$grupoFim'
                    and programa        >= '$programa'
                    and programa        <= '$programaFim'
                ORDER BY L.dataInicio DESC";
            //echo "<pre>";
            //die($sql);
            
            $sql = $this->db->query($sql);
            $array = array();
            if($sql->rowCount()>0){
                  $array = $sql->fetchAll();
            }
            return $array;
      }
   
      public function carregaUsuario(){
        $sql = "SELECT distinct(solicitante) FROM z_sga_log ";
            $sql = $this->db->query($sql);
            $array = array();
            if($sql->rowCount()>0){
                  $array = $sql->fetchAll();
            }
            return $array; 
      }
      public function carregaAprovador(){
        $sql = "SELECT distinct(aprovadorAcao) FROM z_sga_log";
            $sql = $this->db->query($sql);
            $array = array();
            if($sql->rowCount()>0){
                  $array = $sql->fetchAll();
            }
            return $array; 
      }
      
}