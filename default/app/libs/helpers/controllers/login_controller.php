<?php

/**
 * Controller por defecto si no se usa el routes
 * 
 */

class LoginController extends AppController
{

    public function index()
    {
		$this->FONDO_APP = 'vistaClienteFondo';
    }

    public function valida()
    {
    	$this->FONDO_APP = 'vistaClienteFondo';
       if (Input::hasPost("login","password")){
            $pwd = md5(Input::post("password"));
            $usuario=Input::post("login");
 			

 			switch (Input::post("tipo")) {
			    case 'cliente':
			        $auth = new Auth("model", "class: VichomCliente", "email: $usuario", "clave: $pwd");
            
		            if ($auth->authenticate()) {
		            	$this->activo = Auth::get('activo');
		            	if($this->activo == 'N'){
		            		Flash::error("Su usuario se encuentra inactivo");
		            		Auth::destroy_identity();
		            	}else{
		            		if($this->activo == 'I'){
								Flash::error("Su usuario se encuentra inactivo");
								Flash::valid("Hemos enviado nuevamente un correo para activar tu cuenta");
								//printVar(Auth::get('email'), Auth::get('id')); exit;
								Load::lib('PHPMailer-master/PHPMailerAutoload');
								emailValidaUsu(Auth::get('email'), Auth::get('id'));
								Auth::destroy_identity();
							}else{
								//usuario ingresa
								/* expira en una hora */
								//printVar($pwd); exit;
								setcookie("token", $pwd, time()+3600, "/", "omolds.com");	
								setcookie("users_act", md5(Auth::get('email')), time()+3600, "/", "omolds.com");					
								Redirect::to("cliente/index");
							}
		            	}
		                
		            } else {
		                Flash::error("Por favor valide los datos de acceso");

		            }
			        break;
			    case 'cordinador':
			        $auth = new Auth("model", "class: VichomCordinador", "email: $usuario", "clave: $pwd");
            
		            if ($auth->authenticate()) {
		            	$this->activo = Auth::get('activo');
		            	if($this->activo == 'N'){
		            		Flash::error("Su usuario se encuentra inactivo");
		            		Auth::destroy_identity();
		            	}else{
		            		if($this->activo == 'I'){
								Flash::error("Su usuario se encuentra inactivo");
								Flash::valid("Hemos enviado nuevamente un correo para activar tu cuenta");
								//printVar(Auth::get('email'), Auth::get('id')); exit;
								Load::lib('PHPMailer-master/PHPMailerAutoload');
								emailValidaUsu(Auth::get('email'), Auth::get('id'));
								Auth::destroy_identity();
							}else{
								//usuario ingresa
								/* expira en una hora */
								//printVar($pwd); exit;
								setcookie("token", $pwd, time()+3600, "/", "omolds.com");	
								setcookie("users_act", md5(Auth::get('email')), time()+3600, "/", "omolds.com");					
								Redirect::to("cordinador/index");
							}
		            	}
		                
		            } else {
		                Flash::error("Por favor valide los datos de acceso");

		            }
			        break;
			    case 'agente':
			        $auth = new Auth("model", "class: vichomAgente", "email: $usuario", "clave: $pwd");
            
		            if ($auth->authenticate()) {
		            	$this->activo = Auth::get('activo');
		            	if($this->activo == 'N'){
		            		Flash::error("Su usuario se encuentra inactivo");
		            		Auth::destroy_identity();
		            	}else{
		            		if($this->activo == 'I'){
								Flash::error("Su usuario se encuentra inactivo");
								Flash::valid("Hemos enviado nuevamente un correo para activar tu cuenta");
								//printVar(Auth::get('email'), Auth::get('id')); exit;
								Load::lib('PHPMailer-master/PHPMailerAutoload');
								emailValidaUsu(Auth::get('email'), Auth::get('id'));
								Auth::destroy_identity();
							}else{
								//usuario ingresa
								/* expira en una hora */
								//printVar($pwd); exit;
								setcookie("token", $pwd, time()+3600, "/", "omolds.com");	
								setcookie("users_act", md5(Auth::get('email')), time()+3600, "/", "omolds.com");					
								Redirect::to("agente/index");
							}
		            	}
		                
		            } else {
		                Flash::error("Por favor valide los datos de acceso");

		            }
			        break;
			}


            
        }
    }
    
    
     public function recuperaClaveEmail()
    {
    	$this->FONDO_APP = 'vistaClienteFondo';
       if (Input::hasPost("emailClave")){
            $emailUsu=Input::post("emailClave");
 	
            $Usuario = new Usuario();	
            $validaUser  = $Usuario->find_first("email = '".$emailUsu."'");
            if(isset($validaUser->id)){
            	$claveValida = md5($validaUser->email).'-'.$validaUser->id;
            	//printVar($claveValida); exit;
            	Load::lib('PHPMailer-master/PHPMailerAutoload');
				//printVar(emailRecuperaClave($validaUser->email, $claveValida)); exit;
            	if(emailRecuperaClave($validaUser->email, $claveValida)){
            		$this->tipoMsm = 'success';
					$this->mensaje .= '<strong>Ok</strong> le hemos enviado un E-mail a '.$emailUsu ;
            	}else{
            		$this->tipoMsm = 'danger';
					$this->mensaje = '<strong>Error</strong> no se pudo enviar el E-mail por favor intenta m&aacute;s tarde';
            	}	
            }else{
            	$this->tipoMsm = 'danger';
				$this->mensaje = '<strong>Error</strong> el usuario no existe';
            }
            
        }
    }
    
    public function recuperaClave($clave = 0)
    {
    	$this->FONDO_APP = 'vistaClienteFondo';
    	if(isset($clave)){
    		//printVar($clave); exit;
    		 $Usuario = new Usuario();	
    		$dataClave = explode("-", $clave);
			$validaUser  = $Usuario->find_first("id = '".$dataClave[1]."'");
		
		if(isset($validaUser->id)){
			if (md5($validaUser->email) === $dataClave[0]) {
				 if (Input::hasPost("claveNueva")){
					//usuario actualiza clave
					$claveNueva=Input::post("claveNueva");
						
					$Usuario = new Usuario();
					$Usuario->clave = md5($claveNueva);
					$Usuario->id = $validaUser->id;
					if($Usuario->update()){
						Flash::valid("La clave fue actualizada con &eacute;xito <a href='".APP_PAGE."login'>Volver a login</a>");
					}else{
						Flash::error("Error: al actualizar la clave favor int&eacute;ntelo mas tarde");  
					} 
				}else{
					//usuario puede cambiar clave
					$this->clave= $clave;
				}
			}else{
				Redirect::to("login/index/#Error1");
			}
	    	}else{
	    		Redirect::to("login/index/#Error2");
	    	}	
    	}
    	
    }
    
    
    
    /*funcion de para cerrar Seccion del  usuario*/
	public function cerrarSeccion() {
		Auth::destroy_identity();
		setcookie("token", $_COOKIE["token"], time()-3600, "/", "omolds.com");	
		setcookie("users_act", $_COOKIE["usersAct"], time()-3600, "/", "omolds.com");					
		Redirect::to("/login");
		
	}

}


function emailValidaUsu($emailUsu, $idUsuario){
	//Create a new PHPMailer instance
	$mail = new PHPMailer;
	//Set who the message is to be sent from
	$mail->setFrom('management@omolds.com', 'Omolds');
	//Set an alternative reply-to address
	//$mail->addReplyTo('management@omolds.com', 'Omolds');
	
	//Set who the message is to be sent to
	$mail->addAddress($emailUsu, $nombreUsu);
	//Set the subject line
	$mail->Subject = 'Activar Cuenta Omolds';
	//Read an HTML message body from an external file, convert referenced images to embedded,
	//convert HTML into a basic plain-text alternative body
	$postdata = http_build_query(
	    array(
	        'nombreUsu' => $emailUsu,
	        'clave' => md5($emailUsu),
	        'id' => $idUsuario, 
	        'dominio' => APP_PAGE
	    )
	);
	//printVar($postdata); exit;
	$opts = array('http' =>
	    array(
	        'method'  => 'POST',
	        'header'  => 'Content-type: application/x-www-form-urlencoded',
	        'content' => $postdata
	    )
	);
	
	$context  = stream_context_create($opts);
	//$rutaArchivo = '/home/omolds/public_html/page/default/public/';
	$mail->msgHTML(file_get_contents(APP_PAGE.'emails/omolds/activarCuenta.php', false, $context), dirname(__FILE__));
	//Replace the plain text body with one created manually
	$mail->AltBody = 'This is a plain-text message body';
	//Attach an image file
	//$mail->addAttachment('images/phpmailer_mini.png');
	
	$mail->CharSet = 'UTF-8';
	//send the message, check for errors
	$enviaEmail = $mail->send();
	if (!$enviaEmail) {
		Flash::error("Mailer Error: " . $mail->ErrorInfo);  
	} else {
		Flash::valid("Mensaje enviado");  	
	}
	//exit;
}


function emailRecuperaClave($emailUsu, $clave){
	//Create a new PHPMailer instance
	$mail = new PHPMailer;
	//Set who the message is to be sent from
	$mail->setFrom('management@omolds.com', 'Omolds');
	//Set an alternative reply-to address
	//$mail->addReplyTo('management@omolds.com', 'Omolds');
	
	//Set who the message is to be sent to
	$mail->addAddress($emailUsu);
	//Set the subject line
	$mail->Subject = 'Recupera clave Omolds';
	//Read an HTML message body from an external file, convert referenced images to embedded,
	//convert HTML into a basic plain-text alternative body
	$postdata = http_build_query(
	    array(
	        'nombreUsu' => $emailUsu,
	        'clave' => $clave,
	        'dominio' => APP_PAGE
	    )
	);
	//printVar($postdata); exit;
	$opts = array('http' =>
	    array(
	        'method'  => 'POST',
	        'header'  => 'Content-type: application/x-www-form-urlencoded',
	        'content' => $postdata
	    )
	);
	
	$context  = stream_context_create($opts);
	//$rutaArchivo = '/home/omolds/public_html/page/default/public/';
	$mail->msgHTML(file_get_contents(APP_PAGE.'emails/omolds/recuperarClave.php', false, $context), dirname(__FILE__));
	
	//Replace the plain text body with one created manually
	$mail->AltBody = 'This is a plain-text message body';
	//Attach an image file
	//$mail->addAttachment('images/phpmailer_mini.png');
	
	$mail->CharSet = 'UTF-8';
	//send the message, check for errors
	$enviaEmail = $mail->send();
	if (!$enviaEmail) {
		return false;
	} else {
		return true; 	
	}
}