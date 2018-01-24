<?php

	class UserOm extends ActiveRecord
{

	

	public function getTodas(){

        return $this->find();

    }

    
    /**
     * Retorna los menu para ser paginados
     *
     */
    public function getUser($page, $ppage=50)
    {
        return $this->paginate('NOT idFather="0" AND idFather ='.$_COOKIE["user_id"], "page: $page", "per_page: $ppage", 'order: dateTime desc');
    }
	

}

?>