<?php 

function adaYangSalah($chat_id, $message_id){
	KirimPerintah('sendMessage',[
		'chat_id' => $chat_id,
		'text'=> "GAGAL!\n Maaf, ada sesuatu yang salah nih..",
		'reply_to_message_id' => $message_id
	]);
}