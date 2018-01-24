<?php

/**
 * Controller por defecto si no se usa el routes
 * 
 */

Load::lib('helpers/cssGenerate');
Load::lib('helpers/login');

class UsuariosController extends AppController
{
    /**
     * Obtiene una lista para paginar los usuarios
     */
    public function index($page=1)
    {
        $login = new LoginOm;   
        $login->validaSection();

      	$this->activo = 'Usuarios';

        $usuarios = new UserOm();
        $this->listUser = $usuarios->getUser($page);
    }

    /**
     * Crea un Registro
     */
    public function create()
    {
        /**
         * Se verifica si el usuario envio el form (submit) y si ademas 
         * dentro del array POST existe uno llamado "usuarios"
         * el cual aplica la autocarga de objeto para guardar los 
         * datos enviado por POST utilizando autocarga de objeto
         */

        $this->activo = 'Usuarios';

        if(Input::hasPost('user_om')){
            /**
             * se le pasa al modelo por constructor los datos del form y ActiveRecord recoge esos datos
             * y los asocia al campo correspondiente siempre y cuando se utilice la convención
             * model.campo
             */

            $usuarios = new UserOm(Input::post('user_om'));
            $usuarios->password   =  md5($_POST['password']);
            $usuarios->idFather   =  $_COOKIE["user_id"];
            //En caso que falle la operación de guardar
            if($usuarios->create()){
                Flash::valid('Operación exitosa');
                //Eliminamos el POST, si no queremos que se vean en el form
                Input::delete();
                return Redirect::to();            
            }else{
                Flash::error('Falló Operación');
            }
        }
    }

    /**
     * Edita un Registro
     *
     * @param int $id (requerido)
     */
    public function edit($id)
    {
        $this->activo = 'Usuarios';

        $usuarios = new UserOm();
 
        //se verifica si se ha enviado el formulario (submit)
        if(Input::hasPost('user_om')){
            if($usuarios->update(Input::post('user_om'))){
                 Flash::valid('Operación exitosa');
                //enrutando por defecto al index del controller
                return Redirect::to();
            } else {
                Flash::error('Falló Operación');
                $this->user_om = $usuarios->find_by_id((int)$id);
            }
        } else {
            //Aplicando la autocarga de objeto, para comenzar la edición
            $this->user_om = $usuarios->find_by_id((int)$id);
            #printVar($this->user_om); exit;
        }
    }
 
    /**
     * Eliminar un menu
     * 
     * @param int $id (requerido)
     */
    public function del()
    {
        $this->activo = 'Usuarios';

        $usuarios = new UserOm();
        $id = $_POST['user_om_id'];
        if ($usuarios->delete((int)$id)) {
                Flash::valid('Operación exitosa');
        }else{
                Flash::error('Falló Operación'); 
        }
 
        //enrutando por defecto al index del controller
        return Redirect::to();
    }


}

