<?php

/*incluimos models de la base de datos de Omoldsers*/
require 'models/helpers.php';
require 'models/language.php';

class CssGenerate
{
	/**
     * Si la salida es .css $this->header = true.
     * @var string
     */
    var $header;

    /**
     * Si es true muestra un print_r de la consulta en bd $this->printVar = true.
     * @var string
     */
    var $printVar;

    /**
     * $this->key = la llave asociada puede ser id del omolds o id de usuario que 
     va usar los estilos.
     * @var string
     */
    var $key_om;

    /**
     * $this->group = sirve para agrupar estilos por omolds.
     * @var string
     */
    var $group_om;

    /**
     * $this->cssFile = Agregue sus archivos CSS a esta matriz.
     $cssFiles = array(
		  "modales.css",
		  "botones.css"
		);
     * @var string
     */
    var $cssFiles;


    function __construct() {
        $this->header = false;
        $this->printVar = false;
        $this->key_om = false;
        $this->id_om = false;
        $this->value_om = false;
        $this->group_om = false;
        $this->cssFiles = array();
    }

    public function coloresDb()
    {
    	$Helpers = new Helpers();

    	/*** consultar por key_om ***/
        if($this->key_om){
        	/*** consultar por group_om ***/
	        if($this->group_om){
	        	$dataCss  = $Helpers->find('group_om = "'.$this->group_om.'" AND key_om = "'.$this->key_om.'"');
	        }else{
	        	$dataCss  = $Helpers->find('key_om = "'.$this->key_om.'"');
	        }
        }else{
        	/*** consultar por group_om ***/
	        if($this->group_om){
	        	$dataCss  = $Helpers->find('group_om = "'.$this->group_om.'"');
	        }	
        }

        /*** ver coloresDb print_r base de datos ***/
        if($this->printVar){
        	printVar($dataCss); exit();
        }
        if(empty($dataCss[0]->id)){
        	$dataCss  = $Helpers->find('key_om = "1_omolds" AND group_om = "'.$this->group_om.'"');
        }
        return $dataCss;
    }

    public function coloresDbSave()
    {
       
       if($this->id_om && $this->id_om){
            $Helpers = new Helpers();
            $Helpers->id = $this->id_om;
            $Helpers->key_om = $this->key_om;
            $Helpers->value_om = $this->value_om;
            if($Helpers->update()){
                $res = 0;
            }else{
                $res = 1;
            } 
                
        
        }else{
            $res = 3;
        }  
        return $res;  
    }

	public function salida()
    {
    	$Helpers = new Helpers();

    	/*** consultar por key_om ***/
        if($this->key_om){
        	/*** consultar por group_om ***/
	        if($this->group_om){
	        	$dataCss  = $Helpers->find('group_om = "'.$this->group_om.'" AND key_om = "'.$this->key_om.'"');
	        }else{
	        	$dataCss  = $Helpers->find('key_om = "'.$this->key_om.'"');
	        }
        }else{
        	/*** consultar por group_om ***/
	        if($this->group_om){
	        	$dataCss  = $Helpers->find('group_om = "'.$this->group_om.'"');
	        }	
        }
       


		
		

		/*** ver salida print_r base de datos ***/
        if($this->printVar){
        	printVar($dataCss); exit();
        }

        /*** set the content type header ***/
  

		$colorDefaul  = $Helpers->find('key_om = "1_omolds" AND group_om = "'.$this->group_om.'"');


		if($this->header){
    
			/**
			 * Idealmente, no necesitarías cambiar ningún código más allá de este punto..
			 */
			$buffer = "";
			 //printVar(dirname(__FILE__)); exit;
			foreach ($this->cssFiles as $cssFile) {
			  $buffer .= file_get_contents(dirname(__FILE__).'/../../../public/css/'.$cssFile);
			}
			
			// Remove comments
			$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
			// Remove space after colons
			$buffer = str_replace(': ', ':', $buffer);
			// Remove whitespace
			$buffer = str_replace(array("\r\n", "\r", "\n", "\t",'  ', '    ', '    '), '', $buffer);

			//cambiar colores
			$i = 0;
			foreach($colorDefaul as $color):
				$buffer = str_replace(strtolower($color->value_om), strtolower($dataCss[$i]->value_om) , $buffer);
				$buffer = str_replace(strtoupper($color->value_om), strtoupper($dataCss[$i]->value_om) , $buffer);
				$i++;
			endforeach;	

			// Enable GZip encoding.
			ob_start("ob_gzhandler");
			// Enable caching
			header('Cache-Control: public');
			// Expire in one day
			header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 86400) . ' GMT');
			// Set the correct MIME type, because Apache won't set it for us
			header("Content-type: text/css");
			// Write everything out
			return ($buffer);

        }else{
        	return $css;
        }

        
    }

	
}
?>