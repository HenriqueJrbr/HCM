<?php

class ConfiguracaoFluxo extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Retorna os usuários que não são substitutos.
     * Usuários substitutos não podem ser substituídos.
     * @return array
     */
    public function getUsrSerSubst()
    {
        $sql = "
            SELECT 
                *
            FROM
                z_sga_usuarios
            WHERE
                z_sga_usuarios_id NOT IN(SELECT idUsrSub FROM z_sga_fluxo_substituto)";

        $sql = $this->db->query($sql);

        $array = array();
        if ($sql->rowCount() > 0) {
            $array = $sql->fetchAll();
        }
        return $array;
    }

    /**
     * Retorna os usuário substitutos.
     * @return array
     */
    public function getUsrSubst()
    {
        $sql = "Select * from z_sga_usuarios";
        $sql = $this->db->query($sql);

        $array = array();
        if ($sql->rowCount() > 0) {
            $array = $sql->fetchAll();
        }
        return $array;
    }

    public function criaUsrSubistituto($idUsrSerSub, $idUsrSub, $dataInicio, $dataFim, $obs)
    {
        $sql = "INSERT INTO z_sga_fluxo_substituto SET idUsrSerSub = '$idUsrSerSub',idUsrSub='$idUsrSub',dataInicio = '$dataInicio',dataFim = '$dataFim',obs='$obs'";
        $sql = $this->db->query($sql);
        return $this->db->lastInsertId();
    }

    public function ajaxCarregaTabSub()
    {
        $sql = "SELECT 
              sub.*,
              (SELECT nome_usuario FROM z_sga_usuarios where z_sga_usuarios_id = sub.idUsrSerSub) as serSub,
              (SELECT nome_usuario FROM z_sga_usuarios where z_sga_usuarios_id = sub.idUsrSub) as substituto  
              FROM 
              z_sga_fluxo_substituto as sub where status = '1'";

        $sql = $this->db->query($sql);

        $array = array();
        if ($sql->rowCount() > 0) {
            $array = $sql->fetchAll();
        }
        return $array;
    }

    public function atualizaSubMovimento($idSubistituto, $idResponsavel)
    {
        $sql = "UPDATE z_sga_fluxo_movimentacao SET idResponsavel = '$idSubistituto' where idResponsavel = '$idResponsavel' AND status = 1";


        try{
            $this->db->query($sql);
            return true;
        }catch (Exception $e){
            return false;
        }
        return $sql->rowCount();
    }


    public function atualizaStatus($value)
    {
        $sql = "SELECT * FROM z_sga_fluxo_substituto where idSub = '$value'";

        $sql = $this->db->query($sql);
        if ($sql->rowCount() > 0) {
            $sql = $sql->fetch();
            //echo $sql['idUsrSerSub'];

            $retorno = $this->atualizaSubMovimento($sql['idUsrSerSub'], $sql['idUsrSub']);
            if ($retorno) {
                $sql2 = "UPDATE z_sga_fluxo_substituto SET status = '0' where idSub = '$value'";
                $sql2 = $this->db->query($sql2);
                return $sql2->rowCount();
            }
        }
    }

    /**
     * Busca os atividades de fluxo do sistema
     */
    public function buscaFluxos()
    {
        $sql = "
            SELECT
                *
            FROM
                z_sga_fluxo
        ";

        $sql = $this->db->query($sql);

        $dados = array();
        if ($sql->rowCount() > 0):
            $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
        endif;

        return $dados;
    }

    /**
     * Busca os atividades dos fluxos do sistema
     */
    public function buscaAtividade($idAtividade, $idFluxo)
    {
        $sqlAtividade = "
            SELECT 
                `id`,
                `idAtividade`,
                `descricao`,
                `idFluxo`,
                `ativo`,
                `proximaAtiv`,
                `diasAtraso`,
                `diasNotifica`,
                `ativo`
            FROM 
                `z_sga_fluxo_atividade`
            WHERE
                id = $idAtividade
                AND idFluxo = $idFluxo 
        ";

        try {
            $sqlAtividade = $this->db->query($sqlAtividade);
            $dados = array();
            if ($sqlAtividade->rowCount() > 0):
                $sqlAtividades = "
                    SELECT 
                        `id`,                        
                        `descricao`                                                                        
                    FROM 
                        `z_sga_fluxo_atividade`
                    WHERE                        
                        idFluxo = $idFluxo";
                $sqlAtividades = $this->db->query($sqlAtividades);
                if ($sqlAtividades->rowCount() > 0):
                    return array(
                        'return' => true,
                        'dados' => $sqlAtividade->fetchAll(PDO::FETCH_ASSOC),
                        'atividades' => $sqlAtividades->fetchAll(PDO::FETCH_ASSOC),
                    );
                else:
                    return array(
                        'return' => false,
                        'error' => 'Nenhuma atividade encontrado!'
                    );
                endif;
            else:
                return array(
                    'return' => false,
                    'error' => 'Nenhuma atividade encontrado!'
                );
            endif;
        } catch (Exception $e) {
            return array(
                'return' => false,
                'error' => $e->getMessage()
            );
        }


        return $dados;
    }

    /**
     * Atualiza atividade de fluxo
     * @param $data
     * @return array
     */
    public function editarAtividade($data)
    {
        $sql = "
            UPDATE
                z_sga_fluxo_atividade
            SET
                descricao    = '" . $data['descricao'] . "',
                proximaAtiv  = " . $data['proximaAtiv'] . ",
                diasAtraso   = " . $data['diasAtraso'] . ",
                diasNotifica = " . $data['diasNotifica'] . ",
                ativo        = " . $data['ativo'] . "
            WHERE
                id = " . $data['idAtividade'] . " 
                AND idFluxo = " . $data['idFluxo'] . "
        ";
        
        try {
            $sql = $this->db->query($sql);

            return array('return' => true);
        } catch (Exception $e) {
            return array(
                'return' => false,
                'error' => $e->getMessage()
            );
        }
    }

    /**
     * Atualiza configurações de fluxo
     * @param $data
     * @return array
     */
    public function atualizaConfigFluxo($data)
    {
        $idFluxo = $data['idFluxo'];
        unset($data['idFluxo']);

        $sql = "
            UPDATE
                z_sga_fluxo
            SET
                parametros   = '" . json_encode($data) . "'                
            WHERE                 
                idFluxo = $idFluxo";

        try {
            $sql = $this->db->query($sql);

            return array('return' => true);
        } catch (Exception $e) {
            return array(
                'return' => false,
                'error' => $e->getMessage()
            );
        }
    }


    /**
     * Busca configurações de fluxo
     * @param $data
     * @return array
     */
    public function buscaConfigFluxo($data)
    {
        $idFluxo = $data['idFluxo'];

        $sql = "
            SELECT
                parametros
            FROM
                z_sga_fluxo                            
            WHERE                 
                idFluxo = ". $data['idFluxo'];

        try {
            $sql = $this->db->query($sql);
            $sql = $sql->fetch(PDO::FETCH_ASSOC);
            return array(
                'return' => true,
                'data'   => $sql['parametros']
            );
        } catch (Exception $e) {
            return array(
                'return' => false,
                'error' => $e->getMessage()
            );
        }
    }

    /**
     * Busca os atividades dos fluxos do sistema
     */
    public function ajaxCarregaAtividades($idFluxo)
    {
        $sql = "
            SELECT 
                `id`,
                `idAtividade`,
                `descricao`,
                `idFluxo`,
                `ativo`,
                #`proximaAtiv`,
                (SELECT descricao FROM z_sga_fluxo_atividade fa WHERE fa.id = a.proximaAtiv) AS `proximaAtiv`,
                `diasAtraso`,
                `diasNotifica`,
                `ativo`
            FROM 
                `z_sga_fluxo_atividade` a
            WHERE
                idFluxo = $idFluxo 
        ";        
        
        $sql = $this->db->query($sql);

        $dados = array();
        if ($sql->rowCount() > 0):
            $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
        endif;

        return $dados;
    }


}