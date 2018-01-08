<?php
if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
include_once APPPATH . 'libraries/util/ORMObject.php';


class Phone extends ORMObject{

    function __construct(){
        parent::__construct('telefone');
    }

    /**
     * Retorna o telefone de um usuário (clínica ou pessoa)
     * 
     * @param int user_id: o id do usuário
     * @param int type: o tipo de pessoa (física ou jurídica)
     * @return associative array: ddd e numero do telefone
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
            'ddd' => array(
                'type' => 'TINYINT',
                'constraint' => '3'
            ),
            'numero' => array(
                'type' =>'VARCHAR',
                'constraint' => '16'
            )
        );
    }

    public static function unique(){
        return 'user_id, person_type, ddd, numero';
    }

    public function getObjectData(){}
}