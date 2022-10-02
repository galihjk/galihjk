<?php

function getChatData($chat_id, $return = "all", $empty = []){
    $chat = loadData("chat/$chat_id");
    if(empty($chat)) return $empty;
    if($return == "all") return $chat;
    if(empty($chat[$return])) return  $empty;
    return $chat[$return];
}

function setChatData($chat_id, $data_set_chat, $update_last_active = true, $update_if_time_dif = 0){
	if($update_last_active){
		$data_set_chat['last_active'] = time();
	}
	$data_chat = getChatData($chat_id);
    $last_active = $data_chat['last_active'] ?? 0;
    if(abs(time() - $last_active) > $update_if_time_dif){
        foreach($data_set_chat as $k=>$v){
            $data_chat[$k]=$v;
        }
        saveData("chat/$chat_id",$data_chat);        
    }
	return $data_chat;
}