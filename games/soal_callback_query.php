<?php
$callback_query_data = $update["callback_query"]['data'];
$chat_id = (string) $update["callback_query"]["message"]["chat"]["id"];
$from_id = $update["callback_query"]['from']['id'];

if($callback_query_data == "soal_survey_tambah"){
    KirimPerintah('answerCallbackQuery',[
        'callback_query_id' => $update["callback_query"]['id'],
        'text'=> "soal_survey_tambah $emoji_like",
    ]);
    KirimPerintah('sendMessage',[
        'chat_id' => $chat_id,
        'text'=> "Balas pesan ini untuk membuat soal survey. \n".mentionUser($from_id),
        'parse_mode'=>'HTML',
        'reply_markup' => [
            'force_reply'=>true,
            'input_field_placeholder'=>'Tulis soal yang mau ditambahkan',
            'selective'=>true,
        ],
    ]);
}
elseif($callback_query_data == "soal_survey_info"){
    KirimPerintah('answerCallbackQuery',[
        'callback_query_id' => $update["callback_query"]['id'],
        'text'=> $emoji_like,
    ]);	
}
elseif($callback_query_data == "soal_kuis_info"){
    KirimPerintah('answerCallbackQuery',[
        'callback_query_id' => $update["callback_query"]['id'],
        'text'=> $emoji_like,
    ]);	
}
elseif($callback_query_data == "soal_komkat_info"){
    KirimPerintah('answerCallbackQuery',[
        'callback_query_id' => $update["callback_query"]['id'],
        'text'=> $emoji_like,
    ]);	
}
elseif($callback_query_data == "soal_huruf_info"){
    KirimPerintah('answerCallbackQuery',[
        'callback_query_id' => $update["callback_query"]['id'],
        'text'=> $emoji_like,
    ]);	
}
else{

}