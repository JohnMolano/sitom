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
    public function getUser($page, $ppage=20)
    {
        return $this->paginate("page: $page", "per_page: $ppage", 'order: dataTime desc');
    }
	

}

?>