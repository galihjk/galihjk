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
        'text'=> "[SOAL]\n\nBalas pesan ini untuk membuat soal survey. \n".mentionUser($from_id),
        'parse_mode'=>'HTML',
        'reply_markup' => [
            'force_reply'=>true,
            'input_field_placeholder'=>'Tulis soalmu...',
            'selective'=>true,
        ],
    ]);
}
elseif($callback_query_data == "soal_survey_info"){
    KirimPerintah('answerCallbackQuery',[
        'callback_query_id' => $update["callback_query"]['id'],
        'text'=> $emoji_like,
    ]);
    $text = "<b>SOAL SURVEY</b>\n";
    $text .= "<i>Dipakai untuk game yang berkaitan dengan survey, seperti kuis mayoritas minoritas.</i>\n\n";
    $text .= "CONTOH:\n";
    $text .= "- Apa yang <b>biasanya</b> orang lakukan.....\n";
    $text .= "- Benda apa yang <b>biasanya</b>.....\n";
    $text .= "- Sebutkan nama ........ yang <b>biasa</b> orang ketahui!\n";
    $text .= "- Sebutkan macam ........ <b>menurut kebanyakan</b> orang!\n";
    $text .= "- Apa ....... <b>favorit</b> orang?\n\n/soal_tambah";
    KirimPerintah('sendMessage',[
        'chat_id' => $chat_id,
        'text'=> $text,
        'parse_mode'=>'HTML',
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
    $text = "<b>KOMUNIKATA</b>\n";
    $text .= "Permianan menebak 5 hal (terdiri dari satu atau dua kata) yang berkaitan dengan sebuah kata yang ada di soal.\n\n";
    $text .= "CONTOH:\n";
    $text .= "- SOAL: ULAR\n";
    $text .= " -- JAWABAN 1: BISA\n";
    $text .= " -- JAWABAN 2: MELATA\n";
    $text .= " -- JAWABAN 3: PANJANG\n";
    $text .= " -- JAWABAN 4: DESIS\n";
    $text .= " -- JAWABAN 5: REPTIL\n\n/soal_tambah";
    KirimPerintah('sendMessage',[
        'chat_id' => $chat_id,
        'text'=> $text,
        'parse_mode'=>'HTML',
    ]);
}
elseif($callback_query_data == "soal_huruf_info"){
    KirimPerintah('answerCallbackQuery',[
        'callback_query_id' => $update["callback_query"]['id'],
        'text'=> $emoji_like,
    ]);	
}
elseif(isDiawali($callback_query_data, "soal_downvote_")){
    $id_soal = str_replace("soal_downvote_","",$callback_query_data);
    KirimPerintah('answerCallbackQuery',[
        'callback_query_id' => $update["callback_query"]['id'],
        'text'=> "DownVote: $id_soal\noleh:$from_id",
        'show_alert'=>true,
    ]);	
}
elseif(isDiawali($callback_query_data, "soal_unvote_")){
    $id_soal = str_replace("soal_downvote_","",$callback_query_data);
    KirimPerintah('answerCallbackQuery',[
        'callback_query_id' => $update["callback_query"]['id'],
        'text'=> "UN: $id_soal\noleh:$from_id",
        'show_alert'=>true,
    ]);	
}
elseif(isDiawali($callback_query_data, "soal_upvote_")){
    $id_soal = str_replace("soal_downvote_","",$callback_query_data);
    KirimPerintah('answerCallbackQuery',[
        'callback_query_id' => $update["callback_query"]['id'],
        'text'=> "UP: $id_soal\noleh:$from_id",
        'show_alert'=>true,
    ]);	
}
else{

}