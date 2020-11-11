<?php
class Model {

    protected $db;

    protected $select   = array();
    protected $from     = array();
    protected $join     = array();
    protected $where    = array();
    protected $where_or = array();
    protected $where_in = array();
    protected $where_not_in = array();
    protected $like = array();
    protected $group_by = '';
    protected $order_by = '';
    protected $limit    = array();

    public function __construct() {
        global $db;
        $this->db = $db;
        $this->reset_query();
    }

    /**
     * @param $fields
     */
    public function select($fields)
    {
        if(is_array($fields)):
            foreach($fields as $val):
                $this->select[] = trim($val);
            endforeach;
        else:
            $this->select[] = trim($fields);
        endif;

        return $this;
    }

    /**
     * Acrescenta a tabela na query
     * @param $table Nome da tabela a se consultar
     */
    public function from($table)
    {
        if(is_array($table)):
            foreach($table as $val):
                $this->from[] = trim($val);
            endforeach;
        else:
            $this->from[] = trim($table);
        endif;
        return $this;
    }

    /**
     * Acrescenta join na query
     * @param $table $table da tabela a se consultar
     */
    public function join($table, $cond, $type)
    {
        if(!empty($table) && !empty($cond)):
            $this->join[] = ((($type == '') ? ' JOIN ' : $type . ' JOIN ') . $table . ' ON ' . $cond);
        endif;

        return $this;
    }

    /**
     * Cria where na query
     * @param $where Array com campos e valores
     * @return $this
     */
    public function where($where)
    {
        foreach($where as $val):
            $this->where[] = ($val[0] . " " . $val[1] . " " . $val[2]);
        endforeach;

        return $this;
    }

    /**
     * Cria OR na query
     * @param $where_or Array com campos e valores
     * @return $this
     */
    public function where_or($where_or)
    {
        foreach($where_or as $val):
            $this->where_or[] = $val[0] . " " . $val[1] . " " . $val[2];
        endforeach;

        return $this;
    }

    /**
     * Cria IN na query
     * @param $key Nome do campo
     * @param $values Array com campos e valores
     * @return $this
     */
    public function where_in($key, $values)
    {
        if(!empty($key) && count($values) > 0):
            $this->where_in[] = ($key . ' IN(' .implode(',', $values). ')');
        endif;

        return $this;
    }

    /**
     * Cria NOT IN na query
     * @param $key Nome do campo
     * @param $values Array com campos e valores
     * @return $this
     */
    public function where_not_in($key, $values)
    {
        if(!empty($key) && count($values) > 0):
            $this->where_in[] = ($key . ' NOT IN(' .implode(',', $values). ')');
        endif;

        return $this;
    }

    /**
     * @param $field Nome do campo
     * @param $cond Condição
     * @param $side
     * @return $this
     */
    public function like($field, $cond, $side)
    {
        if(!empty($field) && !empty($field)):

            // Verifica em qual lado fica o escape %
            // Both = '%string%'
            // Left = '%string'
            // Right = 'string%'
            // None = ''
            switch ($side):
                case 'none':
                    $cond = "'{$cond}'";
                    break;
                case 'left':
                    $cond = "'%{$cond}'";
                    break;
                case 'right':
                    $cond = "'{$cond}%'";
                    break;
                case 'both':
                    $cond = "'%{$cond}%'";
                    break;
            endswitch;

            $this->like[] =  $field . ' LIKE ' . $cond;
        endif;

        return $this;
    }

    /**
     * Acrescenta Agrupamento na query
     * @param $fields Campos a se agrupar
     * @return $this
     */
    public function group_by($fields)
    {
        if(is_string($fields)):
            $this->group_by = $fields;
        else:
            for($i = 0; $i < count($fields); $i++){
                $this->group_by .= ($fields . (($i < count($fields)) ? ', ' : ''));
            }
        endif;

        return $this;
    }

    /**
     * Cria order by na query
     * @param $fields String ou Array contendo os campos a serem ordenados
     * @param $type
     * @return $this
     */
    public function order_by($fields, $type)
    {
        if(is_string($fields)):
            $this->order_by = $fields .  ' ' . $type;
        else:
            for($i = 0; $i < count($fields); $i++){
                $this->order_by .= ($fields . (($i < count($fields)) ? ', ' : ' ' . $type));
            }
        endif;

        return $this;
    }

    /**
     * Cria LIMIT na query
     * @param $offset
     * @param $limit
     * @return $this
     */
    public function limit($offset = 0, $limit)
    {
        // se $limit estiver vazio retorna false
        if(empty($limit)):
            return false;
        elseif(!empty($offset) && !empty($limit)):
            // se $offset e $limit não estiver vazio acrescenta os valores
            $this->limit .= ($offset .  ', ' . $limit);
        else:
            // se $offset estiver vazio acrescenta valor padrão para OFFSET e valor recebido por parametro para LIMIT
            $this->limit[] = ($offset . ', ' . $limit);
        endif;

        return $this;
    }

    public function reset_query()
    {
        unset($this->select);
        unset($this->from);
        unset($this->join);
        unset($this->where);
        unset($this->where_or);
        unset($this->where_in);
        unset($this->where_not_in);
        unset($this->like);
        unset($this->order_by);
        unset($this->group_by);
        unset($this->limit);
    }

    /**
     * Executa a query analisando cada variavel e acrescentando as partes na query
     * @return array com os registros encontrados
     */
    public function get()
    {

        $sql = "SELECT ";

        // Acrescenta os campos na query
        if(count($this->select) > 0):
            $sql .= implode(', ', $this->select);
        else:
            $sql = '*';
        endif;

        // Acrescenta a tabela na consulta
        if(count($this->from) > 0):
            if(count($this->from) > 1):
                $sql .= ' FROM ' . implode(', ', $this->from);
            else:
                $sql .= ' FROM ' . $this->from[0];
            endif;
        endif;

        // Acrescenta JOINS na query
        if(isset($this->join) && count($this->join) > 0):
            foreach($this->join as $val):
                $sql .= $val . " ";
            endforeach;
        endif;

        // Acrescenta a clausula WHERE na query
        if(isset($this->where) && count($this->where) > 0):
            $sql .= ' WHERE ' . implode(' AND ', $this->where);
        endif;

        // Acrescenta a clausula OR na query
        if(isset($this->where_or) && count($this->where_or) > 0):
            if(count($this->where) == 0):
                $sql .= ' WHERE ' . implode(' OR ', $this->where_or);
            else:
                $sql .= ' AND ' . implode(' OR ', $this->where_or);
            endif;
        endif;

        // Acrescenta a clausula IN na query
        if(isset($this->where_in) && count($this->where_in) > 0):
            if(count($this->where) == 0 && count($this->where_or) == 0):
                $sql .= ' WHERE ' . implode(' AND ', $this->where_in);
            else:
                $sql .= ' AND ' . implode(' AND ', $this->where_in);
            endif;
        endif;

        // Acrescenta a clausula NOT IN na query
        if(isset($this->where_not_in) && count($this->where_not_in) > 0):
            if(count($this->where) == 0 && count($this->where_or) == 0 && count($this->where_in) == 0):
                $sql .= ' WHERE ' . implode(' AND ', $this->where_not_in);
            else:
                $sql .= ' AND ' . implode(' AND ', $this->where_not_in);
            endif;
        endif;

        // Acrescenta a clausula LIKE na query
        if(isset($this->like) && count($this->like) > 0):
            if((!isset($this->where_not_in) || count($this->where) == 0) && (!isset($this->where_or) || count($this->where_or) == 0) && (!isset($this->where_in) || count($this->where_in) == 0) && (!isset($this->where_not_in) || count($this->where_not_in) == 0)):
                $sql .= ' WHERE ' . implode(' AND ', $this->like);
            else:
                $sql .= ' AND ' . implode(' AND ', $this->like);
            endif;
        endif;

        // Acrescenta o GROUP BY na query
        if(!empty($this->group_by)):
            $sql .= ' GROUP BY ' . $this->group_by;
        endif;

        // Acrescenta a ORDER BY na query
        if(!empty($this->order_by)):
            $sql .= ' ORDER BY ' . $this->order_by;
        endif;

        // Acrescenta LIMIT na query
        if(isset($this->limit) && count($this->limit) > 0):
            $sql .= ' LIMIT ' . $this->limit[0];
        endif;

        // Executa a query. Caso ok retorna os registros encontrados.
        // Caso ocorra algum erro na query. Mata aplicação e mostra o erro na tela
        $this->reset_query();
        //die($sql);

        try{
            return $this->db->query($sql);
        }catch (Exception $e){
            die($e->getMessage());
        }
    }

    // function gravar($texto){
    //     //Variável arquivo armazena o nome e extensão do arquivo.
    //     $arquivo = './arquivos/log.txt';
        
    //     //Variável $fp armazena a conexão com o arquivo e o tipo de ação.
    //     $fp = fopen($arquivo, "a+");

    //     $textoEscrito = '************************************ '.date('d - m - Y').' ****************************************'.PHP_EOL;
    //     $textoEscrito.=$texto.PHP_EOL;
    
    //     //Escreve no arquivo aberto.
    //     fwrite($fp, $textoEscrito);
        
    //     //Fecha o arquivo.
    //     fclose($fp);
    // }

}
?>
