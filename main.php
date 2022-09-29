<?php

/*
// cek bot jadul tiap 10 menit
	if(empty($data['cek_bot_jadul'])){
		$data['cek_bot_jadul'] = 60*10;
		$jadul_bot_tokens = [
			'433975275:AAGoWePh1yqpXioaIvSDe6V3ByTgcCSzPNY', //KOMUNIKATABOT
			'428147988:AAEapHVzWBBu9lyJGoqfsNkAXpluLgJy0H0', //Kata Berbintang
			'400964893:AAEvj2WoVAb2bjd9NvciaP6j5NUxRpiIpAY', //KUIS MAYOFAM
			'370923118:AAG0b6_p56weFktRU3B07DwDqybdW3F5dzY', //KUIS MINORITAS
			'328351128:AAG3BIDcqeGqYJ49Gc_MYQTHhDXvW-W8HGI', //KUIS MAYORITAS
		];
		foreach($jadul_bot_tokens as $tokenjadul){
			$jadul_done = [];
			$update_id  = 0;
			$updates = DapatkanUpdate($update_id, $tokenjadul);

			//skip jika 100 (max getupdate)
			while(count($updates) >= 100){
				echo "\n skip 100 $tokenjadul id=$update_id\n";
				foreach ($updates as $message){
					$update_id = 1+$message["update_id"];
				}
				$updates = DapatkanUpdate($update_id, $tokenjadul);
			}

			echo "$tokenjadul = " .count($updates). " \n";
			foreach ($updates as $message){
				if(!empty($message["message"]["chat"]["id"]) 
				and !in_array($message["message"]["chat"]["id"],$jadul_done)){
					$update_id = 1+$message["update_id"];
					$message_data = $message["message"];
					$chat_id = (string) $message_data["chat"]["id"];
					$jadul_done[] = $chat_id;
					KirimPerintah('sendMessage',[
						'chat_id' => $chat_id,
						'text'=> "https://t.me/galihjkdev/10835",
						'parse_mode'=>'HTML',
					],$tokenjadul);
					if(substr($chat_id,0,1) == "-"){
						KirimPerintah('leaveChat',[
							'chat_id' => $chat_id,
						],$tokenjadul);		
					}
				}
			}
			$updates = DapatkanUpdate($update_id, $tokenjadul);
		}
		echo "DONE: Proses bot jadul\n";
	}
	else{
		$data['cek_bot_jadul'] -= $jeda;
		if($data['cek_bot_jadul'] <= 0){
			unset($data['cek_bot_jadul']);
		}
	}
//=======================================================================
*/

$ada_yang_lagi_main = false;

//game playing loops
if(!empty($data['playing_chatters'])){
	foreach($data['playing_chatters'] as $chat_id=>$val_chatter){
		if(!empty($val_chatter['playing'])){
			$ada_yang_lagi_main = true;
			$game = $val_chatter['playing'];
			$playdata = $val_chatter[$game];
			
			include('games/'.$game.'_mainplay.php');
		}
	}
}

if (!$ada_yang_lagi_main) server_stop();

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
			$data['playing_chatters'][$val[1]][$val[0]]['step'] = $val[2];
			unset($data['change_step'][$key]);
		}
	}
}