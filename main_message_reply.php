<?php 
$reply_to_message = $message_data['reply_to_message'];
$reply_to_message_text = $reply_to_message['text'] ?? '';
if(isDiawali($reply_to_message_text,"[")){
    $game = str_replace("[","",explode("]",$reply_to_message_text)[0]) ;
    include ("galihjk/$game".'_reply');
}