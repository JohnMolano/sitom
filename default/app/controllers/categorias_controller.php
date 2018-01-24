<?php

/**
 * Controller por defecto si no se usa el routes
 * 
 */

Load::lib('helpers/cssGenerate');
Load::lib('helpers/login');

class CategoriasController extends AppController
{
    
    public function index($page=1)
    {
        $login = new LoginOm;   
        $login->validaSection();

      	$this->activo = ucfirst(RENDER_URL['controller']);

      	$omolds = new Omolds();
        $this->keyOmolds = '0_omolds';
        $this->listOmolds = $omolds->getOmold(RENDER_URL['controller'],$this->keyOmolds);
        $this->listData = $omolds->getOmolds($page,RENDER_URL['controller']);
        


    }

    /**
     * Crea un Registro
     */
    public function create()
    {
        $login = new LoginOm;   
        $login->validaSection();
        /**
         * Se verifica si el usuario envio el form (submit) y si ademas 
         * dentro del array POST existe uno llamado "usuarios"
         * el cual aplica la autocarga de objeto para guardar los 
         * datos enviado por POST utilizando autocarga de objeto
         */

        //$this->activo = ucfirst(RENDER_URL['controller']);
        $this->activo = ucfirst(RENDER_URL['controller']);
      
        $omolds = new Omolds();

        //se verifica si se ha enviado el formulario (submit)
        if (Input::hasPost("value_om","column_om")){
            
        	$guarda = false;		
        	$count = $omolds->getOmoldsCount(RENDER_URL['controller']);
            
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
	     		   $omolds->table_om = RENDER_URL['controller'];
	     		   if($omolds->save()){
	     		   		$guarda = true;
	     		   }
			   
			}
			if($guarda){
     			Flash::valid('Operación exitosa');
            	//enrutando por defecto al index del controller
            	return Redirect::to();
			}else {
          	  Flash::error('Falló Operación');
          	   $this->listOmolds = $omolds->getOmold(RENDER_URL['controller'],'0_omolds');
       		}

        } else {
            //Aplicando la autocarga de objeto, para comenzar la edición
            $this->listOmolds = $omolds->getOmold(RENDER_URL['controller'],'0_omolds');

        }
    }

    /**
     * Edita un Registro
     *
     * @param int $id (requerido)
     */
    public function edit($id)
    {
       $login = new LoginOm;   
       $login->validaSection();
         
       $this->activo = ucfirst(RENDER_URL['controller']);

        $omolds = new Omolds();
        //se verifica si se ha enviado el formulario (submit)
        if (Input::hasPost("value_om","column_om")){
            
        	$update = false;	
            
     		for ($i = 0; $i < count($_POST['column_om']); $i++) {
	     		   //printVar($_POST['column_om'][$i],'column_om' );
	     		   //printVar($_POST['value_om'][$i],'value_om' );	
	     		   //printVar($_POST['id_om'][$i],'id_om' );	exit;
                    
	     		   $omolds = new Omolds();
	     		   
	     		
	     		   $omolds->table_om = RENDER_URL['controller'];
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
            	return Redirect::to();
			}else {
          	  Flash::error('Falló Operación');
              $this->listOmolds = $omolds->getOmold(RENDER_URL['controller'],'0_omolds');
              $this->id = $id;
          	  $this->listData = $omolds->getOmold(RENDER_URL['controller'], $id);
       		}

        } else {
            //Aplicando la autocarga de objeto, para comenzar la edición
            $this->listOmolds = $omolds->getOmold(RENDER_URL['controller'],'0_omolds');
            $this->id = $id;
            $this->listData = $omolds->getOmold(RENDER_URL['controller'], $id);
            #printVar($this->listData); exit;
        }
    }
 
    /**
     * Eliminar un menu
     * 
     * @param int $id (requerido)
     */
    public function del()
    {
        $login = new LoginOm;   
        $login->validaSection();

        $this->activo = ucfirst(RENDER_URL['controller']);

        $omolds = new Omolds();
        $id = $_POST['key_om_id'];
        if ($omolds->delete_all("key_om = '".$id."'")) {
                Flash::valid('Operación exitosa');
        }else{
                Flash::error('Falló Operación'); 
        }
 
        //enrutando por defecto al index del controller
        return Redirect::to();
    }


}




