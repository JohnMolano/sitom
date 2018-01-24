<?php
	class Helpers extends ActiveRecord
{
	
	public function getTodos(){
        return $this->find();
    }
	
}
?>