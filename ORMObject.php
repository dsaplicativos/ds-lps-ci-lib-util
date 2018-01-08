<?php
include_once 'Dao.php';

abstract class ORMObject{
	
	protected $dao;

	
	/**
	 * @param String $table o nome da tabela � qual 
	 * os objetos desta classe ser�o associados
	 */
	function __construct($table = '') {
		$this->dao = new Dao($table);
	}
	
	/**
	 * Define os valores de todos os atributos para um objeto da classe atual.
	 * @param array $data: array cujos indices tem os mesmos nomes dos atributos da classe
	 */
	function setObjectData($data){
		$k = array_keys($data);
		$v = array_values($data);
		for($i = 0; $i < count($k); $i++){
			$this->{$k[$i]} = $v[$i];
		}
	}

	
	/**
	 * Insere ou atualiza um registro na tabela table
	 * recebida como argumento no construtor das classes
	 * que herdam de ORMObject.
	 */
	function write($return_object = false) {
		$data = $this->getObjectData();
		return $this->dao->write($data, $return_object);
	}
	
	
	function delete($id, $table = null){
	    return $this->dao->delete($id, $table);
	}
	
	
	/**
	 * Prepara os dados necess�rios para a atualiza��o
	 * dos objetos desta classe no banco de dados.
	 */
	abstract function getObjectData();
}