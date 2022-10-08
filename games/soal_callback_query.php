<?php
$callback_query_data = $update["callback_query"]['data'];
$chat_id = (string) $update["callback_query"]["message"]["chat"]["id"];
$from_id = $update["callback_query"]['from']['id'];

if($callback_query_data == "soal_survey_tambah"){
    KirimPerintah('answerCallbackQuery',[
        'callback_query_id' => $update["callback_query"]['id'],
        'text'=> "$emoji_like",
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
    KirimPerintah('answerCallbackQuery',[
        'callback_query_id' => $update["callback_query"]['id'],
        'text'=> "DownVote: $id_soal\noleh:$from_id",
        'show_alert'=>true,
    ]);

    $explode = explode("__",str_replace("soal_downvote_","",$callback_query_data));
    $id_soal = $explode[0];
    $data_soal = loadData("soal/survey/$id_soal");
    $my_vote = $data_soal['vote'][$from_id] ?? 0;
    if((string) $my_vote !== "-1"){
        $data_soal['vote'][$from_id] = -1;
        $data_soal['vtsc'] -= 1;
        saveData("soal/survey/$id_soal",$data_soal);

        $vtsc = $data_soal['vtsc'];
        $jenis_soal = 'survey';
        $soal = $data_soal['soal'];
        $channel_username = "@galihjksoal";
        KirimPerintah('editMessageText',[
            'chat_id' => $channel_username,
            'message_id'=>$id_soal,
            'text'=> "[SOAL ".strtoupper($jenis_soal)."]\n\n$soal\n\nVoteScore: $vtsc\n<i>Kontributor:</i> ...",
            'parse_mode'=>'HTML',
            'reply_markup' => inlineKeyBoard([
                ["$emoji_dislike DOWNvote (-1)","soal_downvote_$id_soal"."__$jenis_soal"],
                ["$emoji_please unvote (0)","soal_unvote_$id_soal"."__$jenis_soal"],
                ["$emoji_like UPvote (+1)","soal_upvote_$id_soal"."__$jenis_soal"],
                ["$emoji_pencil Edit","https://t.me/".$config['bot_username']."?start=cmd_soal_edit_$id_soal"."__$jenis_soal"],
                ["$emoji_cross Hapus","https://t.me/".$config['bot_username']."?start=cmd_soal_hapus_$id_soal"."__$jenis_soal"],
                ["$emoji_chat Jawaban","https://t.me/".$config['bot_username']."?start=cmd_soal_jawaban_$id_soal"."__$jenis_soal"],
            ],3),
        ]);
    }
    // [
    //     'soal'=>$soal,
    //     'vote'=>[
    //         [$from_id => 1],
    //     ],
    //     'vtsc'=>1,
    //     'jawab'=>[],
    // ];
    // saveData("soal/survey/$id_soal",$data_soal);
}
elseif(isDiawali($callback_query_data, "soal_unvote_")){
    $explode = explode("__",str_replace("soal_downvote_","",$callback_query_data));
    $id_soal = $explode[0];
    KirimPerintah('answerCallbackQuery',[
        'callback_query_id' => $update["callback_query"]['id'],
        'text'=> "UN: $id_soal\noleh:$from_id",
        'show_alert'=>true,
    ]);	
}
elseif(isDiawali($callback_query_data, "soal_upvote_")){
    $explode = explode("__",str_replace("soal_upvote_","",$callback_query_data));
    $id_soal = $explode[0];
    KirimPerintah('answerCallbackQuery',[
        'callback_query_id' => $update["callback_query"]['id'],
        'text'=> "UP: $id_soal\noleh:$from_id",
        'show_alert'=>true,
    ]);	
}
else{

}