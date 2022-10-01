<?php

function getChatData($chat_id, $return = "all", $empty = []){
    $chat = loadData("chat/$chat_id");
    if(empty($chat)) return $empty;
    if($return == "all") return $chat;
    if(empty($chat[$return])) return  $empty;
    return $chat[$return];
}