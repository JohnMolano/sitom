<?php

/**
 * Controller por defecto si no se usa el routes
 * 
 */

Load::lib('helpers/cssGenerate');

class HelpersController extends AppController
{

    public function index()
    {
      	$this->mensaje = 'Esto es un helper';
    }

    public function cssGenerate($rand=0)
    {
    	
    	$css = new CssGenerate;	
    	$css->header = true;
    	//$css->printVar = true;
    	$css->key_om = '2_user';
    	$css->cssFiles = array(
    		"cssVichOM" => "cssOmoldsCloud.css"
		);
    	$css->group_om = 'login';

      	echo $css->salida();
      	exit;
    }

    public function admCss()
    {
        $this->CssModulo = cargaCSS();
        $css = new CssGenerate; 
       // $css->printVar = true;
        $css->key_om = '2_user';
        $css->group_om = 'omolds';

        $this->colores = $css->coloresDb();
        
    }

    public function admCssSave()
    {
      //printVar($_POST['id_om']); exit;
       if(isset($_POST['id_om']) && isset($_POST['value_om'])){
        
            $css = new CssGenerate; 
            $css->user_id_om    =  2;
            $css->id_om    =  $_POST['id_om'];
            $css->value_om =  $_POST['value_om'];
            $res = $css->coloresDbSave();
        
        }else{
            $res = 3;
        }  
        echo $res;  
        exit;
    }

    public function vistaGenerador()
    {
        $this->mensaje = 'Esto es un helper';
    }

}


function cargaCSS(){
    $css = '<link rel="stylesheet" href="'.APP_PAGE_OMOLDS.'css/helpers/cssGeneratorHelper.css">';   
    return $css;
}


function cargaJsBody(){
    $js = ' <script src="'.APP_PAGE_OMOLDS.'javascript/bootstrap4/jquery.min.js"></script>';   
    return $js;
}

function cargaJsHead(){
    $js = ' <script src="'.APP_PAGE_OMOLDS.'javascript/bootstrap4/jquery.min.js"></script>';   
    return $js;
}

