<?php

/**
 * Controller por defecto si no se usa el routes
 * 
 */

Load::lib('helpers/cssGenerate');
Load::lib('helpers/login');

class IndexController extends AppController
{

    public function index()
    {
        $login = new LoginOm;   
        $login->validaSection();

      	$this->mensaje = 'hola mundo';
    }


    public function bpmn()
    {
        $this->mensaje = 'hola mundo';exit;
    }

    public function cssGenerate($rand=0)
    {
    	
    	$css = new CssGenerate;	
    	$css->header = true;
    	//$css->printVar = true;
    	$css->key_om = '1_omolds';
    	$css->cssFiles = array(
    		"cssVichOM" => "cssVichOM.css",
    		"cssVichomBtns" => "cssVichomBtns.css"
		);
    	$css->group_om = 'vichom';

      	echo $css->salida();
      	exit;
    }

    public function vistaGenerador()
    {
        $this->mensaje = 'Esto es un helper';
    }

}

