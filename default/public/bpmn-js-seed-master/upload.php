<?php 
//upload.php
define('APP_PATH', dirname(__FILE__) . "/");
//echo APP_PATH; exit();


$upload_folder = APP_PATH."bpmnUp";

$nombre_archivo = $_FILES['bpmn']['name'];

$tipo_archivo = $_FILES['bpmn']['type'];

$tamano_archivo = $_FILES['bpmn']['size'];

$tmp_archivo = $_FILES['bpmn']['tmp_name'];

$archivador = $upload_folder . '/' . $nombre_archivo;
//echo '<pre>'; print_r($_FILES); echo '</pre>'; exit();
if (!move_uploaded_file($tmp_archivo, $archivador)) {

	$return = Array("ok" => FALSE, "msg" => "Ocurrio un error al subir el archivo. No pudo guardarse.", "status" => "error");

}else{
	$return = Array('ok'=>TRUE, "archivo"=>$nombre_archivo);
}

echo json_encode($return);
?>