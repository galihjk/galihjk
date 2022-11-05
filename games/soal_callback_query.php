<?php
$callback_query_data = $update["callback_query"]['data'];
$chat_id = (string) $update["callback_query"]["message"]["chat"]["id"];
$from_id = $update["callback_query"]['from']['id'];
$message_id = $update["callback_query"]["message"]["message_id"];

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
        'text'=> "Anda telah memilih DOWNVOTE",
        'show_alert'=>true,
    ]);

    $explode = explode("__",str_replace("soal_downvote_","",$callback_query_data));
    $id_soal = $explode[0];
    $jenis_soal = $explode[1];

    $data_soal = loadData("soal/$jenis_soal/$id_soal");
    $my_vote = $data_soal['vote'][$from_id] ?? 0;
    if((string) $my_vote !== "-1"){
        $data_soal['vote'][$from_id] = -1;
        if((string) $my_vote === "1"){
            $data_soal['vtsc'] -= 2;
        }
        else{
            $data_soal['vtsc'] -= 1;
        }
        
        saveData("soal/$jenis_soal/$id_soal",$data_soal);

        updateSoalPost($id_soal,$jenis_soal,$data_soal);


    }
}
elseif(isDiawali($callback_query_data, "soal_unvote_")){
    KirimPerintah('answerCallbackQuery',[
        'callback_query_id' => $update["callback_query"]['id'],
        'text'=> "Anda telah memilih UNVOTE",
        'show_alert'=>true,
    ]);

    $explode = explode("__",str_replace("soal_unvote_","",$callback_query_data));
    $id_soal = $explode[0];
    $jenis_soal = $explode[1];

    $data_soal = loadData("soal/$jenis_soal/$id_soal");
    $my_vote = $data_soal['vote'][$from_id] ?? 0;
    if((string) $my_vote !== "0"){
        $data_soal['vote'][$from_id] = 0;
        if((string) $my_vote === "1"){
            $data_soal['vtsc'] -= 1;
        }
        else{
            $data_soal['vtsc'] += 1;
        }
        
        saveData("soal/$jenis_soal/$id_soal",$data_soal);

        updateSoalPost($id_soal,$jenis_soal,$data_soal);


    }
}
elseif(isDiawali($callback_query_data, "soal_upvote_")){
    KirimPerintah('answerCallbackQuery',[
        'callback_query_id' => $update["callback_query"]['id'],
        'text'=> "Anda telah memilih UPVOTE",
        'show_alert'=>true,
    ]);

    $explode = explode("__",str_replace("soal_upvote_","",$callback_query_data));
    $id_soal = $explode[0];
    $jenis_soal = $explode[1];

    $data_soal = loadData("soal/$jenis_soal/$id_soal");
    $my_vote = $data_soal['vote'][$from_id] ?? 0;
    if((string) $my_vote !== "1"){
        $data_soal['vote'][$from_id] = 1;
        if((string) $my_vote === "0"){
            $data_soal['vtsc'] += 1;
        }
        else{
            $data_soal['vtsc'] += 2;
        }
        
        saveData("soal/$jenis_soal/$id_soal",$data_soal);

        updateSoalPost($id_soal,$jenis_soal,$data_soal);


    }
}
elseif(isDiawali($callback_query_data, "soal_yesdelete_")){
    $explode = explode("__",str_replace("soal_yesdelete_","",$callback_query_data));
    $id_soal = $explode[0];
    $jenis_soal = $explode[1];

    $data_soal = loadData("soal/$jenis_soal/$id_soal");

    if(empty($data_soal['alasan'])){
        KirimPerintah('answerCallbackQuery',[
            'callback_query_id' => $update["callback_query"]['id'],
            'text'=> "Soal ini sudah tidak tersedia.",
            'show_alert'=>true,
        ]);
    }
    else{
        KirimPerintah('answerCallbackQuery',[
            'callback_query_id' => $update["callback_query"]['id'],
            'text'=> "Anda SETUJU untuk menghapusnya",
            'show_alert'=>true,
        ]);
        if(empty($data_soal['delsc'])) $data_soal['delsc'] = 0;
        $my_delete = $data_soal['delete'][$from_id] ?? 0;
        if((string) $my_delete !== "1"){
            $data_soal['delete'][$from_id] = 1;
            if((string) $my_delete === "-1"){
                $data_soal['delsc'] += 2;
            }
            else{
                $data_soal['delsc'] += 1;
            }
            saveData("soal/$jenis_soal/$id_soal",$data_soal);
        }
    }
}
elseif(isDiawali($callback_query_data, "soal_nodelete_")){
    $explode = explode("__",str_replace("soal_nodelete_","",$callback_query_data));
    $id_soal = $explode[0];
    $jenis_soal = $explode[1];

    $data_soal = loadData("soal/$jenis_soal/$id_soal");

    if(empty($data_soal['alasan'])){
        KirimPerintah('answerCallbackQuery',[
            'callback_query_id' => $update["callback_query"]['id'],
            'text'=> "Soal ini sudah tidak tersedia.",
            'show_alert'=>true,
        ]);
    }
    else{
        KirimPerintah('answerCallbackQuery',[
            'callback_query_id' => $update["callback_query"]['id'],
            'text'=> "Anda TIDAK SETUJU untuk menghapusnya",
            'show_alert'=>true,
        ]);
        if(empty($data_soal['delsc'])) $data_soal['delsc'] = 0;
        $my_delete = $data_soal['delete'][$from_id] ?? 0;
        if((string) $my_delete !== "-1"){
            $data_soal['delete'][$from_id] = -1;
            if((string) $my_delete === "1"){
                $data_soal['delsc'] -= 2;
            }
            else{
                $data_soal['delsc'] -= 1;
            }
            saveData("soal/$jenis_soal/$id_soal",$data_soal);
        }
    }
}
elseif(isDiawali($callback_query_data, "soal_yesedit_")){
    $explode = explode("__",str_replace("soal_yesedit_","",$callback_query_data));
    $id_soal = $explode[0];
    $jenis_soal = $explode[1];

    $data_soal = loadData("soal/$jenis_soal/$id_soal");

    if(empty($data_soal['edit'])){
        KirimPerintah('answerCallbackQuery',[
            'callback_query_id' => $update["callback_query"]['id'],
            'text'=> "Proses pengeditan sudah berakhir.",
            'show_alert'=>true,
        ]);
    }
    else{
        KirimPerintah('answerCallbackQuery',[
            'callback_query_id' => $update["callback_query"]['id'],
            'text'=> "Anda SETUJU atas pengeditan ini.",
            'show_alert'=>true,
        ]);
        if(empty($data_soal['editsc'])) $data_soal['editsc'] = 0;
        $my_edit = $data_soal['editvote'][$from_id] ?? 0;
        if((string) $my_edit !== "1"){
            $data_soal['editvote'][$from_id] = 1;
            if((string) $my_edit === "-1"){
                $data_soal['editsc'] += 2;
            }
            else{
                $data_soal['editsc'] += 1;
            }
            saveData("soal/$jenis_soal/$id_soal",$data_soal);
        }
    }
}
elseif(isDiawali($callback_query_data, "soal_noedit_")){
    $explode = explode("__",str_replace("soal_noedit_","",$callback_query_data));
    $id_soal = $explode[0];
    $jenis_soal = $explode[1];

    $data_soal = loadData("soal/$jenis_soal/$id_soal");

    if(empty($data_soal['edit'])){
        KirimPerintah('answerCallbackQuery',[
            'callback_query_id' => $update["callback_query"]['id'],
            'text'=> "Proses pengeditan sudah berakhir",
            'show_alert'=>true,
        ]);
    }
    else{
        KirimPerintah('answerCallbackQuery',[
            'callback_query_id' => $update["callback_query"]['id'],
            'text'=> "Anda TIDAK SETUJU atas pengeditan ini.",
            'show_alert'=>true,
        ]);
        if(empty($data_soal['editsc'])) $data_soal['editsc'] = 0;
        $my_edit = $data_soal['editvote'][$from_id] ?? 0;
        if((string) $my_edit !== "-1"){
            $data_soal['editvote'][$from_id] = -1;
            if((string) $my_edit === "1"){
                $data_soal['editsc'] -= 2;
            }
            else{
                $data_soal['editsc'] -= 1;
            }
            saveData("soal/$jenis_soal/$id_soal",$data_soal);
        }
    }
}
elseif(isDiawali($callback_query_data, "soal_jwbscshow_")){
    $explode = explode("__",str_replace("soal_jwbscshow_","",$callback_query_data));
    $jawaban_submit = $explode[0];
    $jawaban_skor = $explode[1];

    KirimPerintah('answerCallbackQuery',[
        'callback_query_id' => $update["callback_query"]['id'],
        'text'=> "Jawaban: '$jawaban_submit'. \n"
            ."Skor: " . $jawaban_skor,
        'show_alert'=>true,
    ]);
}
elseif(isDiawali($callback_query_data, "soal_jwbsc_")){
    $explode = explode("__",str_replace("soal_jwbsc_","",$callback_query_data));
    $id_soal = $explode[0];
    $jenis_soal = $explode[1];
    $plusminus = $explode[2];
    $jawaban_submit = $explode[3];

    $plusminustxt = ($plusminus == "+" ? "menambahkan" : "mengurangi");
    $data_soal = loadData("soal/$jenis_soal/$id_soal");
    $is_hapus = false;
    if(empty($data_soal['jawab'][$jawaban_submit])){
        KirimPerintah('answerCallbackQuery',[
            'callback_query_id' => $update["callback_query"]['id'],
            'text'=> "ERROR: Data sudah tidak ada.",
            'show_alert'=>true,
        ]);
    }
    else{
        $jawaban_submit_awal = $data_soal['jawab'][$jawaban_submit];
        if($plusminus == "+"){
            $data_soal['jawab'][$jawaban_submit] ++;
        }
        else{
            $data_soal['jawab'][$jawaban_submit] --;
            if($data_soal['jawab'][$jawaban_submit] < 0){
                unset($data_soal['jawab'][$jawaban_submit]);
                $is_hapus = true;
            }
        }
        if($is_hapus){
            $text_alert = "Berhasil menghapus jawaban '$jawaban_submit'.";
        }
        else{
            $text_alert = "Berhasil $plusminustxt skor untuk '$jawaban_submit'. \n" .
            $jawaban_submit_awal
            ." => ".($data_soal['jawab'][$jawaban_submit]);
        }
        KirimPerintah('answerCallbackQuery',[
            'callback_query_id' => $update["callback_query"]['id'],
            'text'=> $text_alert,
            'show_alert'=>true,
        ]);
        saveData("soal/$jenis_soal/$id_soal",$data_soal);
        soal_kirimEditorJawaban($chat_id, $jenis_soal, $id_soal, $message_id);
    }
}
elseif(isDiawali($callback_query_data, "soal_buatjwb_")){
    $explode = explode("__",str_replace("soal_buatjwb_","",$callback_query_data));
    $id_soal = $explode[0];
    $jenis_soal = $explode[1];
    $data_soal = loadData("soal/$jenis_soal/$id_soal");
    if(!empty($data_soal)){
        if(count($data_soal['jawab']) >= 15){
            KirimPerintah('answerCallbackQuery',[
                'callback_query_id' => $update["callback_query"]['id'],
                'text'=> "GAGAL: Mohon maaf, maksimal jumlah jawaban adalah 15.",
                'show_alert'=>true,
            ]);
        }
        else{
            KirimPerintah('answerCallbackQuery',[
                'callback_query_id' => $update["callback_query"]['id'],
                'text'=> "Tulis Jawabanmu!",
            ]);
            soal_kirimEditorJawaban($chat_id, $jenis_soal, $id_soal, "force_reply");
        }
    }
    else{
        KirimPerintah('answerCallbackQuery',[
            'callback_query_id' => $update["callback_query"]['id'],
            'text'=> "ERROR",
            'show_alert'=>true,
        ]);
    }
}
else{

}