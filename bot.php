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



if(!empty($data)){
	include('galihjk/initiate.php');
	/*
		$updates = DapatkanUpdate($update_id, $tokenjadul);
		//skip jika 100 (max getupdate)
		while(count($updates) >= 100){
			echo "\n skip 100 $tokenjadul id=$update_id\n";
			foreach ($updates as $message){
				$update_id = 1+$message["update_id"];
			}
			$updates = DapatkanUpdate($update_id, $tokenjadul);
		}
	*/

	$last_serve_time = intval(loadData("last_serve_time") ?? time());
	if(abs($last_serve_time-time()) > 1){
		$jeda = time() - intval($last_serve_time);
		include('galihjk/main.php');
	}
	saveData("last_serve_time",time());

	$update = json_decode(file_get_contents("php://input"), TRUE);
	
	if(!empty($update)) {
		$data_playing_chatters = loadData("data_playing_chatters");
		include('galihjk/main_update.php');
		if(!empty($data_playing_chatters)){
			saveData("data_playing_chatters",$data_playing_chatters);
		}
	}
	
	echo "<pre>";
	print_r($data);
	saveData("data", $data);
}
else{
	file_put_contents("ERROR DATA NOT LOADED.txt","ERROR DATA NOT LOADED.txt");
}