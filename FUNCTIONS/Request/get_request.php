<?php
require_once('../connect.php');
require_once('../../CLASSES/Request.php');

$class = new Request(
						$_POST['pk'],
	                    NULL,
	                    NULL,
	                    NULL
					);

$data = $class->get_request($_POST);



header("HTTP/1.0 404 User Not Found");
if($data['status']){
	header("HTTP/1.0 200 OK");
}

header('Content-Type: application/json');
print(json_encode($data));
?>