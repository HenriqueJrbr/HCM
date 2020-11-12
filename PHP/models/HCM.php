<?php
    class HCM extends Model{
        
        public function __construct(){
            parent::__construct();
        }
        
        

        public function carregaEmpresas(){
            $query = "SELECT idEmpresa,razaoSocial from z_sga_empresa";
            $empresas = $this->db->query($query);
            
            if($empresas->rowCount()>0):
                return $empresas->fetchAll(PDO::FETCH_ASSOC);
            else:
                return [];
            endif;
        }

        public function carregaCargoBaseModel(){
            $query = "SELECT 
            idCargoBase, descCargoBase
        FROM
            z_sga_cargo_base";
            $instacias = $this->db->query($query);
            
            if($instacias->rowCount()>0):
                return $instacias->fetchAll(PDO::FETCH_ASSOC);
            else:
                return [];
            endif;
        }
        
        public function carregaDepartamentoModel(){
            $query = "SELECT 
            idDepartamentohcm, descDepartamentoHCM
        FROM
            z_sga_departamento_hcm";
            $instacias = $this->db->query($query);
            
            if($instacias->rowCount()>0):
                return $instacias->fetchAll(PDO::FETCH_ASSOC);
            else:
                return [];
            endif;
        }

        public function carregaCentroCustoModel(){
            $query = "SELECT 
            idCentroCusto, descCentroCusto
        FROM
            z_sga_centro_custo";
            $instacias = $this->db->query($query);
            
            if($instacias->rowCount()>0):
                return $instacias->fetchAll(PDO::FETCH_ASSOC);
            else:
                return [];
            endif;
        }

        public function carregaUnLotacaoModel(){
            $query = "SELECT 
            idUnidadeLotacao, desc_unidade_lotacao
        FROM
             z_sga_unidade_lotacao";
            $instacias = $this->db->query($query);
            
            if($instacias->rowCount()>0):
                return $instacias->fetchAll(PDO::FETCH_ASSOC);
            else:
                return [];
            endif;
        }

        public function carregaGruposModel(){
            $query = "SELECT 
            idGrupo, descAbrev
        FROM
             z_sga_grupo";
            $instacias = $this->db->query($query);
            
            if($instacias->rowCount()>0):
                return $instacias->fetchAll(PDO::FETCH_ASSOC);
            else:
                return [];
            endif;
        }

        public function carregaNvlHierModel(){
            $query = "SELECT 
            idNivelHierarquico, descNivelHierarquico
        FROM
              z_sga_nivel_hierarquico";
            $instacias = $this->db->query($query);
            
            if($instacias->rowCount()>0):
                return $instacias->fetchAll(PDO::FETCH_ASSOC);
            else:
                return [];
            endif;
        }

        public function carregaFuncaoModel(){
            $query = "SELECT 
            idFuncao, descricao
        FROM
             z_sga_manut_funcao";
            $instacias = $this->db->query($query);
            
            if($instacias->rowCount()>0):
                return $instacias->fetchAll(PDO::FETCH_ASSOC);
            else:
                return [];
            endif;
        }

        public function carregaEstabelecimentos($empresa){
            if($empresa=="todas")
            {
                $query = "SELECT 
                idEstabelecimento, descEstabelecimento
            FROM
                z_sga_Estabelecimento";
                   $instacias = $this->db->query($query);
            
                   if($instacias->rowCount()>0):
                       return $instacias->fetchAll(PDO::FETCH_ASSOC);
                   else:
                       return [];
                   endif;
            }
            else
            {
            $query = "SELECT 
            idEstabelecimento, descEstabelecimento
        FROM
            z_sga_Estabelecimento
        WHERE
            idEstabelecimento NOT IN (SELECT 
                    idEstabelecimento
                FROM
                    z_sga_estabelecimento_empresa
                WHERE
                    idEmpresa = $empresa)";
            $instacias = $this->db->query($query);
            
            if($instacias->rowCount()>0):
                return $instacias->fetchAll(PDO::FETCH_ASSOC);
            else:
                return [];
            endif;
        }
    }

        public function carregaEstabelecimentosVinculados($empresa){
            $query = "SELECT 
            zse.idEstabelecimento, descEstabelecimento
        FROM
            z_sga_Estabelecimento zse
                INNER JOIN
            z_sga_estabelecimento_empresa zsee ON zse.idEstabelecimento = zsee.idEstabelecimento
        WHERE
            zsee.idEmpresa = $empresa";
            $instacias = $this->db->query($query);
            
            if($instacias->rowCount()>0):
                return $instacias->fetchAll(PDO::FETCH_ASSOC);
            else:
                return [];
            endif;
        }
        public function gravarEstabxEmpresa($dados){
            if(!isset($dados['idOriginais'])):
                $dados['idOriginais']=array();
            endif;
            if(!isset($dados['idSelecionados'])):
                $dados['idSelecionados']=array();
            endif;
            $inclusao=array_diff($dados['idSelecionados'],$dados['idOriginais']);
            $exclusao=array_diff($dados['idOriginais'],$dados['idSelecionados']);
            $empresa=$dados['idEmpresa'];

            try{
                if(count($inclusao)>0):
                    $query_Insert = $this->db->prepare(
                        "INSERT INTO
                            z_sga_estabelecimento_empresa (idEmpresa,idEstabelecimento)
                        VALUES
                            (:idEmpresa , :idEstabelecimento )");

                    foreach($inclusao as $key => $value):
                        $query_Insert->bindValue(':idEmpresa',$empresa);
                        $query_Insert->bindValue(':idEstabelecimento',$value);
                        $query_Insert->execute();
                    endforeach;
                endif;
                
                if(count($exclusao)>0):
                    $query_Delete = $this->db->prepare(
                        "DELETE FROM
                            z_sga_estabelecimento_empresa
                        WHERE
                            idEmpresa = :idEmpresa
                        AND
                            idEstabelecimento = :idEstabelecimento");

                    foreach($exclusao as $key => $value):
                        $query_Delete->bindValue(':idEmpresa',$empresa);
                        $query_Delete->bindValue(':idEstabelecimento',$value);
                        $query_Delete->execute();
                    endforeach;
                endif;
                
                return array('return' => true);
            } catch (Exception $e) {
                return array(
                    'return' => false,
                    'erro'   => $e->getMessage()
                );
            }
        }



        public function gravarRegraModel($dados){
        
            try{
            
                    $query_Insert = $this->db->prepare(
                        "INSERT INTO
                            z_sga_regra_admissao (idEmpresa,idEstabelecimento, idDepartamentoHCM, idUnidadeLotacao, idCentroCusto, idCargoBase, idNivelHierarquico, idFuncao)
                        VALUES
                            (:idEmpresa,:idEstabelecimento, :idDepartamentoHCM, :idUnidadeLotacao, :idCentroCusto, :idCargoBase, :idNivelHierarquico, :idFuncao )");

                   
                        $query_Insert->bindValue(':idEmpresa',$dados['idEmpresa']);
                        $query_Insert->bindValue(':idEstabelecimento',$dados['idEstabelecimento']);
                        $query_Insert->bindValue(':idDepartamentoHCM',$dados['idDepartamentoHCM']);
                        $query_Insert->bindValue(':idUnidadeLotacao',$dados['idUnidadeLotacao']);
                        $query_Insert->bindValue(':idCentroCusto',$dados['idCentroCusto']);
                        $query_Insert->bindValue(':idCargoBase',$dados['idCargoBase']);
                        $query_Insert->bindValue(':idNivelHierarquico',$dados['idNivelHierarquico']);
                        $query_Insert->bindValue(':idFuncao',$dados['idFuncao']);
                        $query_Insert->execute();
                        $ultimoid = $this->db ->lastInsertId();
                        $this -> gravarGrupoModel($dados['idGrupo'],$ultimoid);
                
                return array('return' => true);
            } catch (Exception $e) {
                return array(
                    'return' => false,
                    'erro'   => $e->getMessage()
                );
            }
        }  
        // acaba aqui

        public function gravarRegra2Model($dados){
          $emp = $dados['idEmpresa'];
            try{
            
                    $query_Insert = $this->db->prepare(
                        "INSERT INTO
                            z_sga_regra_admissao (idEmpresa,idEstabelecimento, idDepartamentoHCM, idUnidadeLotacao, idCentroCusto, idCargoBase, idNivelHierarquico, idFuncao)
                        VALUES
                            (:idEmpresa,:idEstabelecimento, :idDepartamentoHCM, :idUnidadeLotacao, :idCentroCusto, :idCargoBase, :idNivelHierarquico, :idFuncao )");

                        foreach($emp as $key => $value):
                            $query_Insert->bindValue(':idEmpresa',$value);
                            $query_Insert->bindValue(':idEstabelecimento',$dados['idEstabelecimento']);
                            $query_Insert->bindValue(':idDepartamentoHCM',$dados['idDepartamentoHCM']);
                            $query_Insert->bindValue(':idUnidadeLotacao',$dados['idUnidadeLotacao']);
                            $query_Insert->bindValue(':idCentroCusto',$dados['idCentroCusto']);
                            $query_Insert->bindValue(':idCargoBase',$dados['idCargoBase']);
                            $query_Insert->bindValue(':idNivelHierarquico',$dados['idNivelHierarquico']);
                            $query_Insert->bindValue(':idFuncao',$dados['idFuncao']);
                            $query_Insert->execute();
                            $ultimoid = $this->db ->lastInsertId();
                            $this -> gravarGrupoModel($dados['idGrupo'],$ultimoid);
                        endforeach;

                        
                       
                
                return array('return' => true);
            } catch (Exception $e) {
                return array(
                    'return' => false,
                    'erro'   => $e->getMessage()
                );
            }
        }  

        public function gravarGrupoModel($arrayId,$id){
         
            try{
            
                    $query_Insert = $this->db->prepare(
                        "INSERT INTO
                            z_sga_grupos_regra_admissao (idGrupo,idRegraAdmissao)
                        VALUES
                            (:idGrupo,:idRegraAdmissao)");

                        foreach($arrayId as $key => $value):
                        $query_Insert->bindValue(':idGrupo',$value);
                        $query_Insert->bindValue(':idRegraAdmissao',$id);
                        $query_Insert->execute();
                        endforeach;
                
                return array('return' => true);
            } catch (Exception $e) {
                return array(
                    'return' => false,
                    'erro'   => $e->getMessage()
                );
            }
        }  
    }
?>