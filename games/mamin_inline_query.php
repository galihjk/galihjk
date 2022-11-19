<?php 
include_once("galihjk/games/mamin_init.php");

$jawaban = cleanWord(
    substr($update['inline_query']['query'],0,20)
);// substr(preg_replace('/[^A-Z0-9\-]/', '-', strtoupper($update['inline_query']['query'])),0,20);
if(empty($jawaban)){
    $results[] = [
        'type'=>'article',
        'id'=>count($results)+1 . "|$chat_id|$jawaban",
        'title'=>"Tulis Jawabanmu!",
        'description'=>"Setelah ditulis, jawabanmu akan muncul di sini",
        'thumb_url'=>'https://d338t8kmirgyke.cloudfront.net/icons/icon_pngs/000/001/508/original/edit-text.png',
        'input_message_content'=>[
            'message_text'=>'Ayo jawab!',
        ]
    ];
}
else{
    $results[] = [
        'type'=>'article',
        'id'=>count($results)+1 . "|$chat_id|$jawaban",
        'title'=>"JAWAB: $jawaban",
        'description'=>"Pilih ini untuk menjawab dengan \"$jawaban\"",
        'input_message_content'=>[
            'message_text'=>'Saya sudah menjawab',
        ],
        'thumb_url'=>'https://icon2.cleanpng.com/20180715/eqi/kisspng-speech-balloon-computer-icons-hablante-sprecherzie-icon-conversation-5b4b58176c9a10.6350522215316644074448.jpg',
    ];
}


// print_r($results);