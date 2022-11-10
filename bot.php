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

	$update = json_decode(file_get_contents("php://input"), TRUE);
		
	if(!empty($update)) {
		$data_playing_chatters = loadData("data_playing_chatters");
		if(!empty($data_playing_chatters)){
			include('galihjk/main_update.php');
			if(empty($data_playing_chatters)){
				file_put_contents("ERROR data_playing_chatters NOT LOADED 2.txt","data_playing_chatters DATA NOT LOADED 2.txt");
			}
			else{
				saveData("data_playing_chatters",$data_playing_chatters);
			}
		}
		else{
			file_put_contents("ERROR data_playing_chatters NOT LOADED.txt","data_playing_chatters DATA NOT LOADED.txt");
		}
	}
	else{
		$data_last_serve_time = loadData("last_serve_time");
		if(empty($data_last_serve_time)){
			file_put_contents("ERROR data_last_serve_time NOT LOADED x.txt","data_playing_chatters DATA NOT LOADED.txt");
		}
		else{
			$last_serve_time = intval($data_last_serve_time);
			if(abs($last_serve_time-time()) > 1){
				$jeda = time() - intval($last_serve_time);
				$data_playing_chatters = loadData("data_playing_chatters");
				if(!empty($data_playing_chatters)){
					include('galihjk/main.php');
					if(empty($data_playing_chatters)){
						file_put_contents("ERROR data_playing_chatters NOT LOADED 4.txt","data_playing_chatters DATA NOT LOADED 4.txt");
					}
					else{
						saveData("data_playing_chatters",$data_playing_chatters);
					}
				}
				else{
					file_put_contents("ERROR data_playing_chatters NOT LOADED3.txt","data_playing_chatters DATA NOT LOADED3.txt");
				}
				
			}
			saveData("last_serve_time",time());
		}
	}
	echo "<pre>";
	print_r($data);
	saveData("data", $data);
}
else{
	file_put_contents("ERROR DATA NOT LOADED.txt","ERROR DATA NOT LOADED.txt");
}