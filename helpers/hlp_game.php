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
	global $data;
	global $emoji_thinking;
	if(!empty($data['playing_users'][$user_id]['playing'])){
		if($data['playing_users'][$user_id]['playing']['chat_id'] == $chat_id){
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
	global $data;

	$data['playing_users'][$user_id]['playing'] = [
		'bot'=> $config['bot_username'],
		'chat_id'=>$chat_id,
		'game'=>$game,
		'time'=>0
	];
}

function addUserPlayingTime($user_id, $seconds, $active){
	global $data;
	if(!empty($data['playing_users'][$user_id]['playing'])){
		$data['playing_users'][$user_id]['playing']['playtime'] += $seconds;
		if($active) $data['playing_users'][$user_id]['playing']['activetime'] += $seconds;
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
	global $data;
	if(empty($playercount)){
		$win_ratio = 0;
	}
	else{
		$win_ratio = ($playercount - ($rank-1))/$playercount;
	}
	$data['playing_users'][$user_id]['playing']['win_ratio'] = $win_ratio;
	return $win_ratio;
}

function unsetUserPlaying($user_id, $calculate = true){
	global $data;
	if($calculate){
		if(empty($data['playing_users'][$user_id]['unclaimeds'])){
			$data['playing_users'][$user_id]['unclaimeds'] = [];
		}
		$calculate_result = calculatePlayingPoint(
			$data['playing_users'][$user_id]['playing']['activetime'],
			$data['playing_users'][$user_id]['playing']['playtime'],
			$data['playing_users'][$user_id]['playing']['win_ratio']
		);
// 		$data['playing_users'][$user_id]['unclaimeds']
// 		[$data['playing_users'][$user_id]['playing']['game']]
// 		[$data['playing_users'][$user_id]['playing']['chat_id']]
// 		[md5(date('YmdHis').rand(0,999))] = [
// 			$calculate_result,
// 			time()+24*60*60,
// 		];
	}
	$return = [
		'calculate_result'=>$calculate_result,
		'user'=>$data['playing_users'][$user_id],
	];
	unset($data['playing_users'][$user_id]);
	setUser(
		$user_id,
		[
			'playing'=>'',
			'test'=>'wkwk',
			'unclaimeds'=>json_encode($data['playing_users'][$user_id]['unclaimeds']),
		]
	);
	return $return;
}
