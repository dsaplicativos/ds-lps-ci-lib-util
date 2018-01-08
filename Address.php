<?php
if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
include_once APPPATH . 'libraries/util/ORMObject.php';


class Address extends ORMObject{

    function __construct(){
        parent::__construct('endereco');
    }

    /**
     * Retorna o endereço de um usuário (clínica ou pessoa)
     * 
     * @param int user_id: o id do usuário
     * @param int type: o tipo de pessoa (física ou jurídica)
     * @return associative array: dados do endereço
     */
    public function getByUserId($user_id, $type = 0, $use_array = false){
        return $this->dao->getWhere(
            array(
                'user_id' => $user_id, 
                'person_type' => $type
            ),
            $use_array
        );
    }

    public static function getTableFields(){
        return array(
            'user_id' => array(
                'type' => 'INT',
                'constraint' => '9'
            ),
            'person_type' => array( // pessoa física: 0, pessoa jurídica: 1
                'type' => 'TINYINT',
                'constraint' => '1'
            ),
            'cep' => array(
                'type' => 'VARCHAR',
                'constraint' => '10'
            ),
            'logradouro' => array(
                'type' => 'VARCHAR',
                'constraint' => '100'
            ),
            'numero' => array(
                'type' => 'INT',
                'constraint' => '6'
            ),
            'complemento' => array(
                'type' => 'VARCHAR',
                'constraint' => '100'
            ),
            'bairro' => array(
                'type' => 'VARCHAR',
                'constraint' => '100'
            ),
            'cidade' => array(
                'type' => 'VARCHAR',
                'constraint' => '100'
            ),
            'estado' => array(
                'type' => 'VARCHAR',
                'constraint' => '100'
            )
        );
    }

    public function getObjectData(){}

}