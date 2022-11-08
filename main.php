<?php

$ada_yang_lagi_main = false;

//jobs
$scandir = scandir('galihjk/jobs/');
foreach($scandir as $file){
	if(substr($file,-4) == '.php'){
		if(time() >= substr($file,0,strlen($file)-4)){
			include("galihjk/jobs/$file");
			unlink("galihjk/jobs/$file");
		}
	}
}
//====

//game playing loops
$data_playing_chatters = loadData("data_playing_chatters");
if(!empty($data_playing_chatters)){
	foreach($data_playing_chatters as $chat_id=>$val_chatter){
		if(!empty($val_chatter['playing'])){
			$ada_yang_lagi_main = true;
			$game = $val_chatter['playing'];
			$playdata = $val_chatter[$game];
			
			include('galihjk/games/'.$game.'_mainplay.php');
		}
	}
}

if (!$ada_yang_lagi_main){
	// KirimPerintah('sendMessage',[
	// 	'chat_id' => $id_developer,
	// 	'text'=> "Tidak ada yang main, server akan tidur.\n".print_r($data,1),
	// 	'parse_mode'=>'HTML',
	// ]);
	file_put_contents("TidakAdaYangMain.txt",print_r($data,1));
	server_stop();
}

//delayedPerintah
if(!empty($data['delayedPerintah'])){
	foreach($data['delayedPerintah'] as $key=>$val){
		if(time() >= $val[2]){
			KirimPerintah($val[0],$val[1]);
			unset($data['delayedPerintah'][$key]);
		}		
	}
}

//change game step
// 0 'game', 1 $chat_id, 2 'step', 3 time()+xx
if(!empty($data['change_step'])){
	foreach($data['change_step'] as $key=>$val){
		if(!isset($val[3]) or time() >= $val[3]){
			if(!empty($data_playing_chatters[$val[1]]['playing']) and $data_playing_chatters[$val[1]]['playing'] == $val[0]){
				$data_playing_chatters[$val[1]][$val[0]]['step'] = $val[2];
			}
			else{
				file_put_contents("gjlog/error_change_step".date("YmdHis").".txt",print_r([$data_playing_chatters,$val],1));
			}
			unset($data['change_step'][$key]);
		}
	}
}

if(!empty($data_playing_chatters)){
	saveData("data_playing_chatters",$data_playing_chatters);
}