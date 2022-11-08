<?php 

//helpers autoload
$scandir = scandir('galihjk/helpers/');
foreach($scandir as $file){
	if(substr($file,-4) == '.php'){
		include("galihjk/helpers/$file");
	}
}

$token = $config['bot_token'];
$apiURL = "https://api.telegram.org/bot$token";
$data = loadData("data");
$id_developer = $config['id_developer'];

include('galihjk/initiate.php');

if(!empty($data)){
	$last_serve_time = intval(loadData("last_serve_time") ?? time());
	if(abs($last_serve_time-time()) > 1){
		$jeda = time() - intval($last_serve_time);
		include('galihjk/main.php');
	}
	saveData("last_serve_time",time());
	$update = json_decode(file_get_contents("php://input"), TRUE);
	if(!empty($update)) {
		include('galihjk/main_update.php');
	}
	echo "<pre>";
	print_r($data);
	saveData("data", $data);
}

