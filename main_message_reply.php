<?php 
$reply_to_message = $message_data['reply_to_message'];
$reply_to_message_text = $reply_to_message['text'] ?? '';
if(isDiawali($reply_to_message_text,"[")){
    $game = strtolower(str_replace("[","",explode("]",$reply_to_message_text)[0])) ;
    include ("galihjk/games/$game".'_reply.php');
}