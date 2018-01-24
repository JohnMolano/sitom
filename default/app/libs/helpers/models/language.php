<?php
	class Language extends ActiveRecord
{
	
	public function getTodos(){
        return $this->find();
    }
	
}
?>