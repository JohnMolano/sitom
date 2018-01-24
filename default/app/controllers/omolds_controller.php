<?php

/**
 * Controller por defecto si no se usa el routes
 * 
 */

Load::lib('helpers/cssGenerate');
Load::lib('helpers/login');

class OmoldsController extends AppController
{

    public function index($page=1)
    {
        $login = new LoginOm;   
        $login->validaSection();

      	$this->activo = ucfirst(RENDER_URL['controller']);

       
      	$omolds = new Omolds();
        $this->listData = $omolds->getOmolds($page,RENDER_URL['controller']);
        /*printVar($this->listData->column_om); 
        printVar($this->listData->value_om); exit;*/


    }

    public function categorias($categoria, $idCategoria, $page=1)
    {
        $login = new LoginOm;   
        $login->validaSection();

        $this->activo = ucfirst(RENDER_URL['controller']);
        $this->categoria = $categoria;
        $this->idCategoria = $idCategoria;
        $omolds = new Omolds();
        $this->listData = $omolds->getOmolds($page,RENDER_URL['controller']."_".$categoria);
        /*printVar($this->listData->column_om); 
        printVar($this->listData->value_om); exit;*/


    }

    /**
     * Crea un Registro
     */
    public function create($categoria, $idCategoria)
    {
        /**
         * Se verifica si el usuario envio el form (submit) y si ademas 
         * dentro del array POST existe uno llamado "usuarios"
         * el cual aplica la autocarga de objeto para guardar los 
         * datos enviado por POST utilizando autocarga de objeto
         */

        $this->activo = ucfirst(RENDER_URL['controller']);
      
        $omolds = new Omolds();
        $this->categoria = $categoria;
        $this->idCategoria = $idCategoria;
        //se verifica si se ha enviado el formulario (submit)
        if (Input::hasPost("value_om","column_om")){
            
        	$guarda = false;		
        	$count = $omolds->getOmoldsCount(RENDER_URL['controller']."_".$categoria);
           // printVar($count); exit();
     		for ($i = 0; $i < count($_POST['column_om']); $i++) {
	     		   //printVar($_POST['column_om'][$i],'column_om' );
	     		   //printVar($_POST['value_om'][$i],'value_om' );	
	     		   //printVar($_POST['id_om'][$i],'id_om' );	exit;
	     		   $omolds = new Omolds();
	     		   $omolds->user_id_om = $_COOKIE["user_id"];
	     		   $omolds->key_om = $count.'_omolds';
	     		   $omolds->group_om = $_POST['group_om'][$i];
	     		   $omolds->value_om = ($_POST['value_om'][$i]);
	     		   $omolds->column_om = ($_POST['column_om'][$i]);
	     		   $omolds->order_om = ($_POST['order_om'][$i]);
	     		   $omolds->table_om = RENDER_URL['controller']."_".$categoria;
	     		   if($omolds->save()){
	     		   		$guarda = true;
	     		   }
			   
			}
			if($guarda){
     			Flash::valid('Operación exitosa');
            	//enrutando por defecto al index del controller
            	return Redirect::to("omolds/categorias/".$categoria."/".$idCategoria);
			}else {
          	  Flash::error('Falló Operación');
          	   $this->listOmolds = $omolds->getOmold(RENDER_URL['controller'],'0_omolds');
       		}

        } else {
            //Aplicando la autocarga de objeto, para comenzar la edición
             $this->listOmolds = $omolds->getOmold(RENDER_URL['controller'],'0_omolds');
            //printVar($this->omolds); exit;
        }
    }

    /**
     * Edita un Registro
     *
     * @param int $id (requerido)
     */

    public function edit($id, $categoria, $idCategoria)
    {
        $this->activo = ucfirst(RENDER_URL['controller']);
        $this->categoria = $categoria;
        $this->idCategoria = $idCategoria; 
        $omolds = new Omolds();
        //se verifica si se ha enviado el formulario (submit)
        if (Input::hasPost("value_om","column_om")){
            
            $update = false;    
            
            for ($i = 0; $i < count($_POST['column_om']); $i++) {
                   //printVar($_POST['column_om'][$i],'column_om' );
                   //printVar($_POST['value_om'][$i],'value_om' );  
                   //printVar($_POST['id_om'][$i],'id_om' );    exit;
                    
                   $omolds = new Omolds();
                   
                
                   $omolds->table_om = RENDER_URL['controller']."_".$categoria;
                   if(empty($_POST['id_om'][$i])){
                    ///printVar($_POST['value_om'][$i]); exit;
                       $omolds->user_id_om = $_COOKIE["user_id"];
                       $omolds->key_om = $_POST['key_om'][$i-1];
                       $omolds->group_om = $_POST['group_om'][$i-1];
                       $omolds->value_om = ($_POST['value_om'][$i]);  
                       $omolds->column_om = ($_POST['column_om'][$i]);
                       $omolds->order_om = $i+1;
                        if($omolds->save()){
                            $update = true;
                            #Flash::valid($omolds->id.' save exitosa');
                        }
                   }else{
                        $omolds->user_id_om = $_COOKIE["user_id"];
                        $omolds->key_om = $_POST['key_om'][$i];
                        $omolds->group_om = $_POST['group_om'][$i];
                        $omolds->value_om = ($_POST['value_om'][$i]);  
                        $omolds->id = (int)$_POST['id_om'][$i];
                        if($omolds->update()){
                             #Flash::valid($omolds->id.' update exitosa');
                            $update = true;
                        }
                   }
                   
               
            }
            if($update){
                Flash::valid('Operación exitosa');
                //enrutando por defecto al index del controller
                return Redirect::to("omolds/categorias/".$categoria."/".$idCategoria);
            }else {
              Flash::error('Falló Operación');
              $this->listOmolds = $omolds->getOmold(RENDER_URL['controller'],'0_omolds');
              $this->id = $id;
              $this->listData = $omolds->getOmold(RENDER_URL['controller']."_".$categoria, $id);
            }

        } else {
            //Aplicando la autocarga de objeto, para comenzar la edición
            $this->listOmolds = $omolds->getOmold(RENDER_URL['controller'],'0_omolds');
            $this->id = $id;
            $this->listData = $omolds->getOmold(RENDER_URL['controller']."_".$categoria, $id);
            //printVar($this->omolds); exit;
        }
    }

 
    /**
     * Eliminar un menu
     * 
     * @param int $id (requerido)
     */
    public function del($categoria, $idCategoria)
    {
        $this->activo = ucfirst(RENDER_URL['controller']);

        $omolds = new Omolds();
        $id = $_POST['key_om_id'];
        if ($omolds->delete_all("key_om = '".$id."'")) {
                Flash::valid('Operación exitosa');
        }else{
                Flash::error('Falló Operación'); 
        }
 
        //enrutando por defecto al index del controller
        return Redirect::to("omolds/categorias/".$categoria."/".$idCategoria);
    }


}


