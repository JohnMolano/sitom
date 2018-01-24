<?php

/*incluimos models de la base de datos de Omoldsers*/
date_default_timezone_set('America/Bogota'); 
class LoginOm
{
    /**
     * Si es true muestra un print_r de la consulta en bd $this->printVar = true.
     * @var string
     */
    var $printVar;

    /**
     * $this->user = el mail del usuario para hacer login
     * @var string
     */
    var $user;

    /**
     * $this->password = la clave del usuario encriptada
     * @var string
     */
    var $password;

     /**
     * $return = array de respuesta
     * @var array
     */

    //var $return = new stdClass();


    function __construct() {
        $this->printVar = false;
        $this->user = false;
        $this->password = false;
    }

    /*Validamos al usuario si esta autorizado en la base de datos*/
    public function authenticate()
    {
    	$return = new stdClass();
    	if ($this->user && $this->password){
		        $password 	 =  md5($this->password);
		        $user =  $this->user;
 				#printVar($password);
			    $auth = new Auth("model", "class: userOm", "email: $user", "password: $password");
            
	            if ($auth->authenticate()) {
	            	$this->activo = Auth::get('active');
	            	if($this->activo == 'N'){
	            		$return->msm = "Su usuario se encuentra inactivo por administración";
	            		$return->code = 'userInactiveAdm';
	            		Auth::destroy_identity();
	            	}else{
	            		if($this->activo == 'I'){
	            			$return->msm = "Su usuario se encuentra inactivo por valiación de cuenta";
	            			$return->code = 'userInactive';
							//printVar(Auth::get('email'), Auth::get('id')); exit;
							/*Load::lib('PHPMailer-master/PHPMailerAutoload');
							emailValidaUsu(Auth::get('email'), Auth::get('id'));
							Auth::destroy_identity();*/
						}else{
							//usuario ingresa
							/* expira en 12 horas */
							//printVar($pwd); exit;
							
							//setcookie("token", $pwd, time()+3600, "/", "omolds.com");	
							setcookie("token", $password, time()+43200, "/", $_SERVER['SERVER_NAME']);	
							setcookie("users_act", md5(Auth::get('email')), time()+43200, "/", $_SERVER['SERVER_NAME']);
							setcookie("user_id", Auth::get('id'), time()+43200, "/", $_SERVER['SERVER_NAME']);
							setcookie("user_idFather", Auth::get('idFather'), time()+43200, "/", $_SERVER['SERVER_NAME']);
							//printVar($_COOKIE); exit;
							$return->msm = "Usuario ingresa su cuenta expira en unas horas";
	            			$return->code = 'userActive';					
	            			$return->idFather = Auth::get('idFather');					
						}
	            	}
	                
	            } else {
	            	$return->msm = "Por favor valide los datos de acceso";
	            	$return->code = 'userIncorrect';
	            }
			   
			}

		/*** ver coloresDb print_r base de datos ***/
        if($this->printVar){
        	printVar($return); exit();
        }

        return $return;
            
    }

       


    /*funcion para validar session del usuario*/
	public function validaSection() {
		$return = new stdClass();
		#printVar($_COOKIE); exit;
		if($_COOKIE['token'] == NULL){
    		echo "<script> window.location.href = '".APP_PAGE_OMOLDS."login/index'; </script>"; exit();
    		$return->section = false;
    	}else{
    		$return->section = true;
    	}			
		return $return;
	}

	/*funcion para retornar datos de session del usuario*/
	public function dataSection() {
		$return = new stdClass();
		#printVar($_COOKIE); exit;
		if($_COOKIE['token'] == NULL){
    		echo "<script> window.location.href = '".APP_PAGE_OMOLDS."login/index'; </script>"; exit();
    		$return->section = false;
    	}else{
    		$return->section = true;
    		$return->user_id = $_COOKIE["user_id"];
    		$return->user_idFather = $_COOKIE["user_idFather"];
    	}			
		return $return;
	}

	public function getViewLogin($template)
    {
    	$Language = new Language();
		//printVar($this->language); exit;
        $file = dirname(__FILE__).'/views/login/'.$template.'.phtml';
        #printVar(file_exists($file));exit;
        //Si no existe el view y es scaffold
 
        if (file_exists($file)) {
        	if(isset($_COOKIE['idioma'])){
				$idioma = $_COOKIE['idioma'];
			}else{
				$idioma = detectarIdioma();
			}
			#$idioma = "en";
        	$idiomaTranslate  = $Language->find('key_om = "1_omolds" AND group_om = "login" AND language = "'.$idioma.'"');
        	#printVar($idioma); exit;

			$buffer = file_get_contents($file); 
			//cambiar idioma 
			$i = 0;
			
			$buffer = str_replace("<?=APP_PAGE_OMOLDS;?>", APP_PAGE_OMOLDS, $buffer);
			if(count($idiomaTranslate) >= 1){
				foreach($idiomaTranslate as $textos): 
					$buffer = (str_replace("{$textos->column_om}", "{$textos->value_om}", $buffer));
					#printVar($textos->value_om,$idiomaDefaul[$i]->value_om ); exit;
					$i++;
				endforeach;	
			}
			


        }else{
        	$buffer = "NO hay html";
        }
        return $buffer;
    }


    /*funcion de para cerrar Seccion del  usuario*/
	public function closeSession() {
		$return = new stdClass();
		Auth::destroy_identity();
		setcookie("token", $_COOKIE["token"], time()-43200, "/", $_SERVER['SERVER_NAME']);	
		setcookie("user_id", $_COOKIE["user_id"], time()-43200, "/", $_SERVER['SERVER_NAME']);	
		setcookie("user_idFather", $_COOKIE["user_idFather"], time()-43200, "/", $_SERVER['SERVER_NAME']);	
		setcookie("users_act", $_COOKIE["usersAct"], time()-43200, "/", $_SERVER['SERVER_NAME']);	
		$return->msm = "Seccion cerrada";
	    $return->code = 'userCloseSection';	
	    echo "<script> window.location.href = '".APP_PAGE_OMOLDS."login/index'; </script>"; exit();			
		return $return;
	}


	
	
}
?>