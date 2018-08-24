<?php


header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET, POST');
header('Content-Type: text/html; charset=utf-8');

include_once 'controller.php';

$method = $_SERVER['REQUEST_METHOD'];
$uri = explode('/',$_SERVER['REQUEST_URI']);

foreach ($uri as $i => $val) {
  if (empty($val)) unset($uri[$i]);
}

if (in_array($method,['GET','POST'])){
    $controller = new controller();
    $controller->init_controller($method,$uri);
}else die(json_encode(["error"=>"405 Method Not Allowed"]));
?>
