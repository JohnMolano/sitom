<?php

	class Omolds extends ActiveRecord
{

	

	public function getTodas(){

        return $this->find();

    }

    
    /**
     * Retorna los datos para ser paginados
     *
     */
    public function getOmolds($page,$table=0, $ppage=50)
    {   

        #$result =  $this->paginate('NOT key_om="0_omolds" AND table_om="'.$table.'" AND user_id_om = "'.$_COOKIE["user_id"].'"', "page: $page", "per_page: $ppage", 'order: order_om, dateTime_om asc');
        $result =  $this->paginate('NOT key_om="0_omolds" AND table_om="'.$table.'" ', "page: $page", "per_page: $ppage", 'order: order_om, dateTime_om asc');
        #printVar(($result->items)); exit;

       for ($i = 0; $i <= count($result->items); $i++) {
            if(!empty($result->items[$i]->id)){
                if (!in_array($result->items[$i]->value_om,$array_result->{$result->items[$i]->key_om})){ 
                  $array_result->{$result->items[$i]->key_om}[$result->items[$i]->column_om] = $result->items[$i]->value_om;
                 
               }
                $array_result->{$result->items[$i]->key_om}['base']['id_om'] = $result->items[$i]->id;
               $array_result->{$result->items[$i]->key_om}['base']['user_id_om'] = $result->items[$i]->user_id_om;
               $array_result->{$result->items[$i]->key_om}['base']['key_om'] = $result->items[$i]->key_om;
               $array_result->{$result->items[$i]->key_om}['base']['group_om'] = $result->items[$i]->group_om;
               $array_result->{$result->items[$i]->key_om}['base']['table_om'] = $result->items[$i]->table_om;
               $array_result->{$result->items[$i]->key_om}['base']['column_om'] = $result->items[$i]->column_om;
               $array_result->{$result->items[$i]->key_om}['base']['value_om'] = $result->items[$i]->value_om;
               $array_result->{$result->items[$i]->key_om}['base']['order_om'] = $result->items[$i]->order_om;
            }
        }
        #printVar($array_result); exit;
        return $array_result;
    }

    /**
     * Retorna los dato para ser paginado
     *
     */
    public function getOmold($table=0,$key_om=0, $page=1,$ppage=50)
    {   
        if($key_om == "0_omolds"){
            $result =  $this->paginate('table_om="'.$table.'" AND key_om="'.$key_om.'" ', "page: $page", "per_page: $ppage", 'order:   order_om, dateTime_om asc');
        }else{
            #$result =  $this->paginate('table_om="'.$table.'" AND key_om="'.$key_om.'" AND user_id_om ="'.$_COOKIE["user_id"].'"', "page: $page", "per_page: $ppage", 'order:   order_om, dateTime_om asc');
            $result =  $this->paginate('table_om="'.$table.'" AND key_om="'.$key_om.'" ', "page: $page", "per_page: $ppage", 'order:   order_om, dateTime_om asc');
        }
        
        #printVar($result->items); exit;
        for ($i = 0; $i <= count($result->items); $i++) {
            #printVar($result->items[$i]);
            if(!empty($result->items[$i]->id)){
                if (!in_array($result->items[$i]->value_om,$array_result->{$result->items[$i]->key_om})){ 
                  $array_result->{$result->items[$i]->key_om}[$result->items[$i]->column_om] = $result->items[$i]->value_om;

                   $array_result->{$result->items[$i]->key_om}['base']['id_om'][] = $result->items[$i]->id;
                   $array_result->{$result->items[$i]->key_om}['base']['user_id_om'] = $result->items[$i]->user_id_om;
                   $array_result->{$result->items[$i]->key_om}['base']['key_om'] = $result->items[$i]->key_om;
                   $array_result->{$result->items[$i]->key_om}['base']['group_om'] = $result->items[$i]->group_om;
                   $array_result->{$result->items[$i]->key_om}['base']['table_om'] = $result->items[$i]->table_om;
                   $array_result->{$result->items[$i]->key_om}['base']['column_om'] = $result->items[$i]->column_om;
                   $array_result->{$result->items[$i]->key_om}['base']['value_om'] = $result->items[$i]->value_om;
                   $array_result->{$result->items[$i]->key_om}['base']['order_om'][] = $result->items[$i]->order_om;
                 
               }
               
               
            }
        }
       #printVar($array_result); exit;
        return $array_result;
    }

    /**
     * Retorna los dato de una tabla
     *
     */
    public function getOmoldTable($table=0, $page=1,$ppage=3000)
    {   
        #$result =  $this->paginate('table_om="'.$table.'" AND user_id_om ="'.$_COOKIE["user_id"].'"', "page: $page", "per_page: $ppage", 'order:  order_om, dateTime_om asc');
        $result =  $this->paginate('table_om="'.$table.'" ', "page: $page", "per_page: $ppage", 'order:  order_om, dateTime_om asc');
        //printVar($result); exit;
        for ($i = 0; $i <= count($result->items); $i++) {
            if(!empty($result->items[$i]->id)){
                if (!in_array($result->items[$i]->value_om,$array_result->{$result->items[$i]->key_om})){ 
                  $array_result->{$result->items[$i]->key_om}[$result->items[$i]->column_om] = $result->items[$i]->value_om;
                 
               }
               $array_result->{$result->items[$i]->key_om}['base']['id_om'] = $result->items[$i]->id;
               $array_result->{$result->items[$i]->key_om}['base']['user_id_om'] = $result->items[$i]->user_id_om;
               $array_result->{$result->items[$i]->key_om}['base']['key_om'] = $result->items[$i]->key_om;
               $array_result->{$result->items[$i]->key_om}['base']['group_om'] = $result->items[$i]->group_om;
               $array_result->{$result->items[$i]->key_om}['base']['table_om'] = $result->items[$i]->table_om;
               $array_result->{$result->items[$i]->key_om}['base']['column_om'] = $result->items[$i]->column_om;
               $array_result->{$result->items[$i]->key_om}['base']['value_om'] = $result->items[$i]->value_om;
               $array_result->{$result->items[$i]->key_om}['base']['order_om'] = $result->items[$i]->order_om;
               
            }
        }
       //printVar($array_result); exit;
        return $array_result;
    }

    /**
     * Retorna el total de registos de una tabla
     *
     */
    public function getOmoldsCount($table=0, $page=1, $ppage=5000000000)
    {   
        $config = Config::read('config', true);
        $database = Config::read('databases', true);
        #printVar($database{$config['application']['database']}['name']);
        $query = "SELECT `AUTO_INCREMENT`
                FROM  INFORMATION_SCHEMA.TABLES
                WHERE TABLE_SCHEMA = '".$database{$config['application']['database']}['name']."'
                AND   TABLE_NAME   = 'omolds'";

        $result =  $this->paginate_by_sql($query, "page: $page", "per_page: $ppage", 'order: order_om asc');
        #printVar($result->items[0]->AUTO_INCREMENT); exit;
        
       //printVar($array_result); exit;
        return $result->items[0]->AUTO_INCREMENT;
    }
	

}

?>