<?php
require_once('../connect.php');
require_once('../../CLASSES/Notifications.php');

$class = new Notifications(
						$_POST['pk'], 
						Null,
						Null,
						Null,
						$_POST['read'],
						Null
					);
$data = $class->read_notifs();

header("HTTP/1.0 404 User Not Found");
if($data['status']){
	header("HTTP/1.0 200 OK");
}

header('Content-Type: application/json');
print(json_encode($data));
?>