<?php


class Dao {
	private $db;
	private $table;
	private $cols = '*';


	function __construct($table = '') {
		$ci = & get_instance();
		$this->db = $ci->db;
		$this->table = $table;
		$ci->load->helper('string');
	}


	public function query($sql){
		$query = $this->db->query($sql); 
		return $query->result();
	}


	/**
	 * Carrega da tabela $table o objeto que possui os atributos
	 * e valores passados pela lista args.
	 *
	 * @param array args
	 * @param int $deleted
	 */
	public function getWhere($args, $use_array = false, $deleted = 0, $table = null){
		$cols = $this->getCols();
		$this->db->select($cols);
		$args['deleted'] = $deleted;
		$table = $table ? $table : $this->table;
		$rs = $this->db->get_where($table, $args);
		return $use_array ? $rs->result_array() : $rs->result();
	}


	/**
	 * Carrega um registro do banco de dados
	 */
	public function getById($id, $table = null, $return_object = false) {
		$cols = $this->getCols();
		$this->db->select($cols);
		$table = $table ? $table : $this->table;
		$query = $this->db->get_where($table, array('id' => $id));
		
		if($return_object)
			return $query->custom_row_object(0, $table);
		return $query->row();
	}


	/**
	 * Carrega todos os registros ativos de uma tabela
	 */
	public function getAll($table = null) {
		$cols = $this->getCols();
		$this->db->select($cols);
		$table = $table ? $table : $this->table;
		return $this->db->get_where($table, array('deleted' => 0))->result();
	}


	/**
	 * Envia as informacoes de um objeto para o banco de dados
	 * @param boolean $return_object
	 * @param string $alt_table nome de uma tabela
	 * @return unknown
	 */
	public function write($data, $return_object = FALSE, $table = NULL) {
		$table = $table ? $table : $this->table;
		$last_id = $this->insert_or_update($data, $table);
		if($return_object){
			$query = $this->db->get_where($table, array('id' => $last_id));
			return $query->result();
		} else return $last_id;
	}


	/**
	 * Deleta, temporariamente, um registro.
	 * @param int $id
	 * @param String $table
	 * @return boolean
	 */
	public function delete($id, $table = null){
		$table = $table ? $table : $this->table;
		$sql = "UPDATE $table SET deleted = 1 WHERE id = $id ";
		return $this->db->query($sql);
	}


	/**
	 * Insere data em table; caso data ja exista em table, atualiza  
	 * os valores das colunas nas quais houver alteracoes
	 *
	 * @param String $table - o nome da tabela a ser atualizada
	 * @param Array $data - os dados a serem inseridos ou atualizados na tabela table
	 * @return int: id do registro inserido
	 */
	public function insert_or_update($data, $table = null) {
		$table = $table ? $table : $this->table;
		$updt = plic_array($data);
		$sql = $this->db->insert_string($table, $data) .
		' ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id), '
		.urldecode(http_build_query($updt, '', ', '));
		$this->db->query($sql);
		return $this->db->insert_id();
	}


	/**
	 * Define quais colunas serao retornadas em uma consulta.
	 * @param string $cols: os nomes das colunas separadas por vírgulas.
	 */
	public function setCols($cols){
		$this->cols = $cols;
	}


	private function getCols(){
		return $this->cols;
	}

	/**
	 * Retorna a última string sql executada.
	 */
	public function lastQuery(){
		return $this->db->last_query();
	}
}
