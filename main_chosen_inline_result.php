<?php

$chosen_inline_result = $update['chosen_inline_result'];

checkImpersonate($chosen_inline_result['from']);

$from_id = $chosen_inline_result['from']['id'];

if(!empty($data['playing_users'][$from_id]['playing']['chat_id'])){
    $chat_id = $data['playing_users'][$from_id]['playing']['chat_id'];
    if(!empty($data['playing_chatters'][$chat_id]['playing'])
    ){
        $game = $data['playing_chatters'][$chat_id]['playing'];
        if(!empty($data['playing_chatters'][$chat_id][$game]['step'])
        and $data['playing_chatters'][$chat_id][$game]['step'] == 'receive_inline'){
            include ("games/$game".'_chosen_inline_result.php');
        }
    }
}
