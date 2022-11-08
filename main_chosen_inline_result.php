<?php

$chosen_inline_result = $update['chosen_inline_result'];

checkImpersonate($chosen_inline_result['from']);

$from_id = $chosen_inline_result['from']['id'];

if(!empty(getUser($from_id)['playing']['chat_id'])){
    $chat_id = getUser($from_id)['playing']['chat_id'];
    if(!empty($data_playing_chatters[$chat_id]['playing'])
    ){
        $game = $data_playing_chatters[$chat_id]['playing'];
        if(!empty($data_playing_chatters[$chat_id][$game]['step'])
        and $data_playing_chatters[$chat_id][$game]['step'] == 'receive_inline'){
            include ("galihjk/games/$game".'_chosen_inline_result.php');
        }
    }
}
