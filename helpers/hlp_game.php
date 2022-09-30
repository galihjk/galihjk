<?php 
function getGameType($type, $prop = "all"){
	$gametype = [
		'test'=>[
			'name'=>'Test saja',
		],
		'rnt_survival'=>[
			'name'=>'RNT Survival',
		],
	];
	$gametype = $gametype[$type];
	if($prop != "all"){
		if (empty($gametype[$prop])) return false;
		$gametype = $gametype[$prop];
	}
	return $gametype;
}

function checkUserNotPlayingAnyGame($user_id, $chat_id, $message_id){
	global $emoji_thinking;
	if(!empty(getUser($user_id)['playing'])){
		if(getUser($user_id)['playing']['chat_id'] == $chat_id){
			KirimPerintah('sendMessage',[
				'chat_id' => $chat_id,
				'text'=> "GAGAL!\nKayaknya kamu sudah join deh..$emoji_thinking",
				'reply_to_message_id'=> $message_id,
			]);
		}
		else{
			KirimPerintah('sendMessage',[
				'chat_id' => $chat_id,
				'text'=> "GAGAL!\nKamu saat ini sedang memainkan game lain (tidak bisa main lebih dari 1 game sekaligus).",
				'reply_to_message_id'=> $message_id,
			]);
		}
		return false;
	}
	else{
		return true;
	}
}

function startPlayingGame($chat_id, $from_id, $game, $datagame){
	global $config;
	global $data;

	$data['playing_chatters'][$chat_id]['playing'] = $game;
	$data['playing_chatters'][$chat_id]['bot'] = $config['bot_username'];
	$data['playing_chatters'][$chat_id][$game] = $datagame;
	setUserPlaying($from_id, $chat_id, $game);
}

function setUserPlaying($user_id, $chat_id, $game){
	global $config;

	setUser($user_id, ['playing'=>[
		'bot'=> $config['bot_username'],
		'chat_id'=>$chat_id,
		'game'=>$game,
		'time'=>0
	]]);

}

function addUserPlayingTime($user_id, $seconds, $active){
	if(!empty(getUser($user_id)['playing'])){
		$playing = getUser($user_id)['playing'];
		$playing['playtime'] += $seconds;
		if($active) $playing['activetime'] += $seconds;
		setUser($user_id,['playing'=>$playing]);
	}
}

function calculatePlayingPoint($activetime, $playtime, $win_ratio){
	$playtime_score = floor(0.1 * $playtime);
	$activetime_score = floor(0.075 * $activetime);
	$win_ratio_score = floor(0.75 * $win_ratio + 2 * $win_ratio * 0.1 * $playtime );
	$score = floor(0.4 * ($playtime_score + $activetime_score + $win_ratio_score));
	return $score;
}

function setUserWinRate($user_id, $rank, $playercount){
	if(empty($playercount)){
		$win_ratio = 0;
	}
	else{
		$win_ratio = ($playercount - ($rank-1))/$playercount;
	}
	$playing = getUser($user_id)['playing'];
	$playing['win_ratio'] = $win_ratio;
	setUser($user_id,['playing'=>$playing]);
	return $win_ratio;
}

function unsetUserPlaying($user_id, $calculate = true){
	$calculate_result = false;
	$set_user = [
		'playing'=>'',
	];
	if($calculate){
		$unclaimeds = [];
		if(!empty(getUser($user_id)['unclaimeds'])){
			$unclaimeds = getUser($user_id)['unclaimeds'];
		}
		if(!empty(getUser($user_id)['playing'])){
			$playing = getUser($user_id)['playing'];
			$calculate_result = calculatePlayingPoint(
				$playing['activetime'],
				$playing['playtime'],
				$playing['win_ratio']
			);
			$playing_game = $playing['game'];
			$playing_chat_id = $playing['chat_id'];
			$random = md5(date('YmdHis').rand(0,999));
			// KirimPerintah('sendMessage',[
			// 	'chat_id' => '227024160',
			// 	'text'=> "NIH: " . print_r([$playing_game,$playing_chat_id, $random], true),
			// 	'reply_to_message_id'=> $message_id,
			// ]);
			if(empty($unclaimeds[$playing_game])){
				$unclaimeds[$playing_game] = [];
			}
			if(empty($unclaimeds[$playing_game][$playing_chat_id])){
				$unclaimeds[$playing_game][$playing_chat_id] = [];
			}
			$unclaimeds[$playing_game][$playing_chat_id][$random] = [
				$calculate_result,
				time()+24*60*60, //kadaluarsa dalam 24 jam
			];
		}
		$set_user['unclaimeds']=$unclaimeds;
	}
	$return = [
		'calculate_result'=>$calculate_result,
		'set_user'=>$set_user,
	];
	setUser($user_id, $set_user);
	return $return;
}
