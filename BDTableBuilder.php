<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BDTableBuilder{
    private $db;
    private $dbforge;
    private $table_name;

    /**
     * Inclui as colunas padrão na lista de colunas de uma tabela.
     * 
     * @param associative array: colunas específicas de uma tabela.
     */
    function __construct($table_name, $db = null){
        $this->table_name = $table_name;

        $ci = & get_instance();
        if($db != null) $ci->db->db_select(SERVERDBPREFIX . $db);
        $this->dbforge = $ci->load->dbforge($ci->db, true);
        $this->db = $ci->db;
    }

    /**
     * Inclui as colunas padrão à lista de colunas de uma tabela.
     * 
     * @param associative array: colunas específicas de uma tabela.
     */
    public function commonFields($cols){
        $u = array('id' => array(
            'type' => 'INT',
            'constraint' => 9,
            'unsigned' => TRUE,
            'auto_increment' => TRUE
        ));

        $v = array('deleted' => array(
            'type' => 'TINYINT',
            'constraint' => 1,
            'unsigned' => TRUE,
            'default' => 0
        ));

        return array_merge($u, $cols, $v);
    }

    
    /**
     * Organiza os dados necessários para a criação de uma tabela
     * 
     * @param string table_name: o nome da tabela a ser criada
     * @param associative array: lista de campos da tabela junto com seus atributos
     * @param boolean update: indica se a coluna last_modified deve ser atualizada 
     * quando algum valor de um registro sofrer alteração
     */
    public function setTableData($fields, $update = false){
        $fields = $this->commonFields($fields);
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', true);

        $cpl = $update ? ' ON UPDATE CURRENT_TIMESTAMP' : ''; 
        $this->dbforge->add_field("last_modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP $cpl");
    }

    /**
     * Cria a tabela especificada no método setTableData
     */
    public function createTable(){
        if(! $this->tableExists($this->table_name))
        $this->dbforge->create_table($this->table_name);
        else $this->truncateTable($this->table_name);
    }

    /**
     * Verifica se uma tabela existe
     * 
     * @param string table_name: o nome da tabela
     */
    private function tableExists($table_name){
        $this->db->db_debug = false;
        $this->db->query("SELECT * FROM $table_name");
        $this->db->db_debug = true;
        return $this->db->error()['code'] == 0;
    }

    /**
     * Define uma restrição unique para uma tabela do bd
     * 
     * @param string fields: lista de colunas da tabela separadas por vírgula
     */
    public function unique($fields){
        $v = explode(',', $fields);
        $v = array_map('trim', $v);
        $s = implode('_', $v);
        $this->db->query("ALTER TABLE $this->table_name ADD UNIQUE $s ($fields)");
    }

    /**
     * Elimina todos os dados de uma tabela
     */
    public function truncateTable($table_name){
        $this->db->query("TRUNCATE $table_name");
    }
}