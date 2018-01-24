<?php

/**
 * Controller por defecto si no se usa el routes
 * 
 */

Load::lib('helpers/cssGenerate');
Load::lib('helpers/login');

class LoginController extends AppController
{

    public function index()
    {

    	$login = new LoginOm;   
        $this->template = $login->getViewLogin('index');
    }

    public function valida() 
    {
    	$login = new LoginOm;   
       	$this->template = $login->getViewLogin('index');
    	
      	if (Input::hasPost("login","password")){
            $login->user = Input::post("login");
            $login->password = Input::post("password");
            $authenticate = $login->authenticate('index');
            #printVar($authenticate);
            if($authenticate->code == "userIncorrect"){
            	Flash::error($authenticate->msm);  
            }else{
            	if($authenticate->code == "userInactiveAdm"){
	            	Flash::error($authenticate->msm);  
	            }else{
	            	if($authenticate->idFather == 0){
	            		Redirect::to("usuarios/index");
	            	}else{
	            		Redirect::to("categorias/index");
	            	}
	            	
	            }
            }
     
        }else{
        	Flash::error("Error: El email y password son necesarios");  
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
	public function closeSession() {
		$login = new LoginOm;   
        $login->closeSession();					
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