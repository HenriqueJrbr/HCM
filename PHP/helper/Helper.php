<?php

class Helper{
    protected $db;

    public function __construct()
    {
        global $db;
        $this->db = $db;
    }

    /*public function jqueryDataTable($fields, $search, $orderColumn, $orderDir, $limit, $offset, $dados, $total_all_records, $urlEdit)
    {
        $dados = array();
        $data = array();
        $m = new manutencao();


        // usado pelo order by
        $fields = array(
            0 => 'u.cod_usuario',
            1 => 'u.nome_usuario',
            2 => 'm.descricao',
            3 => 'u.email',
            4 => 'u.gestor_usuario',
            5 => 'u.gestor_grupo',
        );

        // Variaveis usadas na paginação do Jquery DataTable
        $search = (isset($_POST["search"]["value"])) ? $_POST["search"]["value"] : '';
        $orderColumn = (isset($_POST['order']['0']['column'])) ? $fields[$_POST['order']['0']['column']] : '';
        $orderDir = (isset($_POST['order']['0']['dir'])) ? $_POST['order']['0']['dir'] : 'ASC';
        $limit = (isset($_POST["length"]) && $_POST["length"] != -1) ? $_POST['length'] : '10';
        $offset = (isset($_POST["length"]) && $_POST["length"] != -1) ? $_POST['start'] : '0';

        // Traz apenas 10 usuarios utilizando paginação
        $dados = $m->carregaDatatableUsuario($search, $orderColumn, $orderDir, $offset, $limit);

        // relacionamentos entre tabelas
        $join = " INNER JOIN
                    z_sga_usuarios AS u 
                    ON userEmp.idUsuario = u.z_sga_usuarios_id
              LEFT JOIN
                    z_sga_manut_funcao AS m 
                    ON u.cod_funcao = m.idFuncao ";


        // Traz todos os usuarios
        $total_all_records = $m->getCountTable("z_sga_usuario_empresa AS userEmp", $search, $fields, $join);

        // cria linhas customizadas no jquery datatable.
        if (count($dados) > 0):
            foreach ($dados as $key => $value):
                $sub_dados = array();
                $sub_dados[] = $value["cod_usuario"];
                $sub_dados[] = $value["nome_usuario"];
                $sub_dados[] = $value["descricao"];
                $sub_dados[] = $value["email"];
                $sub_dados[] = $value["gestor_usuario"];
                $sub_dados[] = $value["gestor_grupo"];
                $sub_dados[] = '<button type="button" class="btn btn-warning btn-xs" data-toggle="modal" onclick="location.href=\'' . URL . $urlEdit . $value['z_sga_usuarios_id'] . '\'">Editar</button>';
                $data[] = $sub_dados;
            endforeach;
        endif;

        $output = array(
            "draw" => intval(isset($_POST["draw"]) ? $_POST["draw"] : ''),
            "recordsTotal" => count($dados),
            "recordsFiltered" => $total_all_records,
            "data" => $data
        );
        echo json_encode($output);
    }*/

    /**
     * Mostra o alert com a mensagem de erro ou sucesso, se contem na
     */
    public function alertMessage()
    {
        $type = '';
        // Atribuo á variavel $type o tipo de mensagem a ser exibida
        if(isset($_SESSION['msg']['success']) && $_SESSION['msg']['success'] != ''):
            $type =  'success';
        elseif(isset($_SESSION['msg']['error']) && $_SESSION['msg']['error'] != ''):
            $type =  'error';
        endif;

        if(!empty($type)):
            $html = '<div class="alert alert-'.$type.' alert-dismissible">';
            $html .= '    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
            $html .=      $_SESSION['msg'][$type];
            $html .= '</div>';

            echo $html;

            // Exclui a variavel
            unset($_SESSION['msg'][$type]);
        endif;
    }

    /**
     * Cria variável SESSION['msg'] de erro ou sucesso, conforme parâmetro $type
     * @param $type
     * @param $message
     * @param $url
     */
    public function setAlert($type, $message, $url = '')
    {
        $_SESSION['msg']["$type"] = $message;

        if(!empty($url)):
            header('Location: '.URL.'/'.$url);
            //die('');
        endif;
    }


    /**
     * Cria o script de criação do Jquery DataTable
     * @param $idElement Id da table que recebe o Jquery DataTable
     * @param $url Caminho para o método do Controller a ser chamado
     * @param $method POST, GET
     */
    public function scriptDataTable($idElement, $url, $method, $serverside = 'true')
    {
        // Opções usadas no jquery datatable
        $options = '
            "oLanguage": {
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "Nenhum registro encontrado",
                //"sInfo": "Exibindo de _START_ ate _END_ de _TOTAL_ registros",
                "sInfo": "Página _PAGE_ de _PAGES_",
                "sInfoEmpty": "Nenhum registro para ser exibido",
                //"sInfoFiltered": "(Filtrado de _MAX_ registros no total)",
                "sInfoFiltered": "",
                "sSearch": "Pesquisar:",
                "oPaginate": {
                    "sFirst": "Primeiro",
                    "sLast": "Último",
                    "sNext": "Proximo",
                    "sPrevious": "Anterior"
                },
                "sLoadingRecords": "&nbsp;",
                "sProcessing": \'<div class="box-loading-datatable"><div class="spinner pull-left"><img src="'.URL.'/assets/images/loader.gif"></div><div class="pull-left" style="margin-top: -10px;">Carregando...</div></div>\'                
            },'.(($serverside == 'true') ? '               
                "serverSide": true,
                "order": [],' :
            '').'
        ';

        // Cria o jquery datatable.
        echo '
            <script type="text/javascript" language="javascript">
                $(document).ready(function () {
                    $("#'.$idElement.'").dataTable({
                        "processing": true, 
                        '.$options.'                            
                        "ajax": {
                            url: url + "'.$url.'",
                            type: "'.$method.'"
                        },                                                
                    });                               
                });
            </script>
        ';
    }


    public function scriptDataTable2($idElement, $url, $method, $serverside = 'true',$tipo)
    {
        // Opções usadas no jquery datatable
        $options = '
            "oLanguage": {
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "Nenhum registro encontrado",
                //"sInfo": "Exibindo de _START_ ate _END_ de _TOTAL_ registros",
                "sInfo": "Página _PAGE_ de _PAGES_",
                "sInfoEmpty": "Nenhum registro para ser exibido",
                //"sInfoFiltered": "(Filtrado de _MAX_ registros no total)",
                "sInfoFiltered": "",
                "sSearch": "Pesquisar:",
                "oPaginate": {
                    "sFirst": "Primeiro",
                    "sLast": "Último",
                    "sNext": "Proximo",
                    "sPrevious": "Anterior"
                },
                "sLoadingRecords": "&nbsp;",
                "sProcessing": \'<div class="box-loading-datatable"><div class="spinner pull-left"><img src="'.URL.'/assets/images/loader.gif"></div><div class="pull-left" style="margin-top: -10px;">Carregando...</div></div>\'                
            },'.(($serverside == 'true') ? '               
                "serverSide": true,
                "order": [],' :
            '').'
        ';

        // Cria o jquery datatable.
        echo '
            <script type="text/javascript" language="javascript">
                $(document).ready(function () {
                    $("#'.$idElement.'").dataTable({
                        "processing": true, 
                        '.$options.'                            
                        "ajax": {
                            url: url + "'.$url.'/'.$tipo.'",
                            type: "'.$method.'"
                        },                                                
                    });                               
                });
            </script>
        ';
    }

    /**
     * imprime as variáveis contidas no parametro $data
     * @param $data
     * @param bool $die
     */
    public function debug($data, $die = false)
    {
        echo "<pre>";
        if(is_string($data)):
            echo 'String: ' . $data;
        else:
            print_r($data);
        endif;

        echo "</pre>";

        if($die):
            die();
        endif;
    }

    /**
     * Cria o datatable para a tab de grupos de log de fluxo
     * @param $idSolicitacao
     */
    public function tabLogGrupos($idSolicitacao)
    {
        $sql = "
            SELECT
                fl.idGrupo,
                g.idLegGrupo,
                g.descAbrev,
                u.nome_usuario
            FROM
                z_sga_fluxo_log fl
            LEFT JOIN
                z_sga_grupo g
                ON fl.idGrupo = g.idGrupo
            LEFT JOIN
                z_sga_grupos gs
                ON fl.idGrupo = gs.z_sga_grupos_id
            LEFT JOIN
                z_sga_usuarios u
                ON gs.gestor = u.cod_usuario
            WHERE
                fl.idSolicitacao = $idSolicitacao
        ";
        $sql = $this->db->query($sql);

        if($sql->rowCount() > 0):
            $grupos = $sql->fetchAll(PDO::FETCH_ASSOC);

            $dataTable = '
                <div id="datatable-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                    <div class="row">
                        <div class="col-sm-6"></div>
                        <div class="col-sm-6"></div>
                    </div>
                    <div class="row"><div class="col-sm-12">
                    <table class="tabela table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info" style="width: 100%;" id="tableGrupoUsuario2">
                        <thead>
                            <tr>
                                <th>Id Grupo</th>
                                <th>Descrição</th>
                                <th>Gestor</th>                                                             
                            </tr>
                        </thead>
                        <tbody>
            ';

            // cria linhas customizadas no jquery datatable.
            foreach ($grupos as $key => $value):
                $dataTable .= '<tr>';
                $dataTable .= '    <td>'.utf8_decode($value['idLegGrupo']).'</td>';
                $dataTable .= '    <td>'.$value['descAbrev'].'</td>';
                $dataTable .= '    <td>'.$value['nomeGestor'].'</td>';
                //$dataTable .= '    <td><span class="badge label-primary">'.$value['totalPro'].'</span></td>';
                //$dataTable .= '    <td><span class="badge label-primary">'.$value['totalUsuario'].'</span></td>';
                $dataTable .= '</tr>';
            endforeach;
            
            $dataTable .= '
                        </tbody>
                    </table>
                </div>
            ';

            echo $dataTable;
        endif;
    }

    /**
     * Cria o datatable para a tab de grupos de log de fluxo
     * @param $idSolicitacao
     */
    public function tabLogProgramas($idSolicitacao)
    {

        $dataTable = '
            <div id="datatable-responsive_wrapper " class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                <div class="row">
                    <div class="col-sm-6"></div>
                    <div class="col-sm-6"></div>
                </div>
                <div class="row"><div class="col-sm-12">
                <table class="table table-striped hover table-striped dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" role="grid" aria-describedby="datatable-responsive_info" style="width: 100%;" id="tableProgLog">
                    <thead>
                        <tr>
                            <th>Id Grupo</th>
                            <th>Desc. Grupo</th>
                            <th>Programa</th>                                                             
                            <th>Descrição</th>
                            <th>Observação</th>
                            <th>Rotina</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        ';

        echo $dataTable;

        $this->scriptDataTable('tableProgLog', 'fluxo/ajaxDatatableProgramasLog/'.$idSolicitacao,'POST');
    }

    /**
     * Formata o retorno com base no parâmetro $retorno
     * @param type $retorno
     * @param type $json
     * @return string
     */
    public function formataRetornoApi($retorno, $json)
    {   
        //print_r($retorno);                    
        $this->gravaLog('api'); 
        //die;
        $resJson = array();
        
        // Valida retorno. E retorna json com as informações.
        switch($retorno['return']):
            case 'sucesso':
                
                $resJson = array(
                    'retorno'     => 'sucesso',
                    'msg'         => trim($retorno['msg']),
                    'codigo_erro' => ''
                );

                // Se existir o indice token envia no json
                if(isset($retorno['token'])):
                    $resJson['token'] = trim($retorno['token']);
                endif;

                // Se existir o indice funcoes envia no json
                if(isset($retorno['funcoes'])):
                    $resJson['funcoes'] = $retorno['funcoes'];
                endif;

                // Se existir o indice gestores envia no json
                if(isset($retorno['gestores'])):
                    $resJson['gestores'] = $retorno['gestores'];
                endif;

                // Se existir o indice dados envia no json
                if((isset($retorno['dados']) && count($retorno['dados']) > 0)):
                    foreach($retorno['dados'] as $key => $val):
                        $resJson[strtolower($key)] = $val;
                    endforeach;
                endif;
                
                break;
            case 'erro':
                $resJson = array(
                    'retorno' => '17006',
                    'msg'     => $retorno['msg']
                );
                break;
            case 'alerta':
                $resJson = array(
                    'retorno' => 'alerta',
                    'msg'     => $retorno['id']
                );
                break;
        endswitch;
        
        return $resJson;
    }
    
    /**
     * Grava a mensagem de log no arquivo de log especificado
     * @param string $arquivo
     * @param type $data
     */
    public function gravaLog($pasta)
    {
        $backTrace = debug_backtrace();
        
        //echo "<pre>";        
        
        $log = "-----------------------------------------------------------------------------------------------------------------------------------------------------\n";
        $log .= "Data: " . date('d/m/Y H:i:s') . "\n";
        $log .= "File: " . $backTrace[1]['file'] . "\n";
        $log .= "Line: " . $backTrace[1]['line'] . "\n";
        $log .= "API: " . $backTrace[2]['function'] . "\n";
        $log .= "Class: " . $backTrace[2]['class'] . "\n";
        $log .= "Return: " . isset($backTrace[1]['args'][0]['msg']) ? (is_array($backTrace[1]['args'][0]['msg']) ? str_replace(array('{', '}','[',']'), array("{\n\t", "\n}",'',''), str_replace(',', ",\n\t", json_encode($backTrace[1]['args'][0]['msg']))) : $backTrace[1]['args'][0]['msg']) : '';
        $log .= isset($backTrace[1]['args'][0]['funcoes']) ? (is_array($backTrace[1]['args'][0]['funcoes']) ? str_replace(array('{', '}','[',']'), array("{\n\t", "\n}",'',''), str_replace(',', ",\n\t", json_encode($backTrace[1]['args'][0]['funcoes']))) : $backTrace[1]['args'][0]['funcoes']) : '';
        $log .= "\n";
        $log .= "Json: " . str_replace(array('{', '}'), array("{\n\t", "\n}"), str_replace(',', ",\n\t", json_encode($backTrace[1]['args'][1]))) . "\n";
        $log .= "-----------------------------------------------------------------------------------------------------------------------------------------------------\n";
        $log .= "\n\n";
        //print_r($backTrace);
        // Variável $fp armazena a conexão com o arquivo e coloca o ponteiro no começo do arquivo                
        
        $dir = LOG_PATH . '/' . $pasta . '/' . (string)date('Y-m-d');

        if(!is_dir($dir)):
            mkdir($dir);
        endif;
        
        $fp = fopen($dir . '/' . 'log_'.(string)date('Y-m-d').'.txt', "a+");
        //$fp = fopen($arquivo, "a+");
        // Escreve no arquivo aberto
        fwrite($fp, $log);

        // Fecha o arquivo
        fclose($fp);               
    }
    
}