<?php

/**
 * Controller por defecto si no se usa el routes
 * 
 */

Load::lib('helpers/cssGenerate');
Load::lib('helpers/login');

class BpmnController extends AppController
{

    public function index($id=0, $controller, $categoria)
    {
        $login = new LoginOm;   
        $login->validaSection();
        $dataSection = $login->dataSection();
        //printVar($dataSection); exit();

        $css = '<link rel="stylesheet" href="'.APP_PAGE_OMOLDS.'bpmn-js-seed-master/bower_components/bpmn-js/dist/assets/diagram-js.css">'; 
        $css .= '<link rel="stylesheet" href="'.APP_PAGE_OMOLDS.'bpmn-js-seed-master/bower_components/bpmn-js/dist/assets/bpmn-font/css/bpmn-embedded.css">'; 
        $this->CssModulo = $css;
        

        $js = ' <script src="'.APP_PAGE_OMOLDS.'bpmn-js-seed-master/bower_components/bpmn-js/dist/bpmn-modeler.js?30"></script>'; 
        $js .= ' <script src="'.APP_PAGE_OMOLDS.'bpmn-js-seed-master/jquery-3.2.1.min.js"></script>'; 
        $js .= ' <script src="'.APP_PAGE_OMOLDS.'bpmn-js-seed-master/modeler.js?32"></script>'; 
        $this->JsModuloBody = $js;

        $omolds = new Omolds();
        $this->listData = $omolds->getOmold($controller."_".$categoria,$id);
        $this->xml = minify($this->listData->{$id}['xml']);
        $this->id_xml = $this->listData->{$id}['base']['id_om'][3];
        #printVar($this->listData->{$id}['xml']); exit();
        $cadena_de_texto = $this->xml;
        $cadena_buscada   = 'definitions xmlns=';
        $posicion_coincidencia = strpos($cadena_de_texto, $cadena_buscada);
        #printVar(($cadena_de_texto));
        //se puede hacer la comparacion con 'false' o 'true' y los comparadores '===' o '!=='
        if ($posicion_coincidencia !== FALSE) {
            $this->xmlCorecto = 'true';
            #printVar("Si hay xml");
        }else{
           $this->xmlCorecto = 'false';
           #printVar("No hay xml");
        }
     
        $this->id = $id;
        $this->key_bpmn = $dataSection->user_id.'_'.$this->id_xml;
        #printVar( $this->key_bpmn); exit;
        
    }

    public function edit($id)
    {
       $this->activo = ucfirst(RENDER_URL['controller'].'s');

        $omolds = new Omolds();
        //se verifica si se ha enviado el formulario (submit)
        if (Input::hasPost("value_om","column_om")){
            
            $update = false;        
            //printVar($_POST['column_om'][$i],'column_om' );
            //printVar($_POST['value_om'][$i],'value_om' );  
            //printVar($_POST['id_om'][$i],'id_om' );    exit;
            $omolds = new Omolds();
            $omolds->id = (int)$_POST['id_om'];
            $omolds->user_id_om = $_COOKIE["user_id"];
            $omolds->key_om = $_POST['key_om'];
            $omolds->group_om = $_POST['group_om'];
            $omolds->value_om = $_POST['value_om'];
            $omolds->table_om = $_POST['table_om'];;
            if($omolds->update()){
                $update = true;
            }
               
            
            if($update){
                Flash::valid('Operación exitosa '.$omolds->id);
                //enrutando por defecto al index del controller
                //return Redirect::to();
            }else {
              Flash::error('Falló Operación');
              $this->listData = $omolds->getOmold(RENDER_URL['controller'], $id);
            }

        } else {
            //Aplicando la autocarga de objeto, para comenzar la edición
            $this->listData = $omolds->getOmold(RENDER_URL['controller'], $id);
            //printVar($this->omolds); exit;
        }
        exit();
    }



}

function minify($buffer){
    // Remove comments
    $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
    // Remove space after colons
    $buffer = str_replace(': ', ':', $buffer);
    // Remove whitespace
    $buffer = str_replace(array("\r\n", "\r", "\n", "\t",'  ', '    ', '    '), '', $buffer);

    return ($buffer);
}



