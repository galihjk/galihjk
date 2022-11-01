<?php 
if($command == 'soal_tambah'){
    $reply_markup = inlineKeyBoard([
        ['+ SURVEY', 'soal_survey_tambah'],
        ['->> [ ? ]', 'soal_survey_info'],
        // ['kuis*', 'underconstruction'],
        // ['?', 'soal_kuis_info'],
        ['komkat*', 'underconstruction'],
        ['->> [ ? ]', 'soal_komkat_info'],
        // ['huruf*', 'underconstruction'],
        // ['?', 'soal_huruf_info'],
        // ['akrab*', 'underconstruction'],
        // ['?', 'soal_akrab_info'],
    ],2);
    
    //kirim pesan dengan tombol
    KirimPerintah('sendMessage',[
        'chat_id' => $chat_id,
        'text'=> "Mau membuat soal <b>jenis</b> apa? (Klik tanda tanya untuk info)",
        'parse_mode'=>'HTML',
        'reply_markup' => $reply_markup,
    ]);
    
}
elseif(isDiawali($command,'soal_edit_')){
    $explode = explode("__",str_replace('soal_edit_','',$command));
    $id_soal = $explode[0];
    $jenis_soal = $explode[1];
    $data_soal = loadData("soal/$jenis_soal/$id_soal");
    if(empty($data_soal['soal'])){
        KirimPerintah('sendMessage',[
            'chat_id' => $chat_id,
            'text'=> "Data sudah tidak tersedia saat ini.",
            'parse_mode'=>'HTML',
        ]);
    }
    elseif(isset($data_soal['editsc'])){
        KirimPerintah('sendMessage',[
            'chat_id' => $chat_id,
            'text'=> "Soal ini sedang dalam proses edit..",
            'parse_mode'=>'HTML',
        ]);
    }
    else{
        $text = "[SOAL]\n\nUntuk memudahkan proses edit, silakan <i>copy</i> dahulu soal yang sudah ada:\n\n<pre>"
        .$data_soal['soal']."</pre>\n\nSetelah itu, <b>balas</b> (<i>reply</i>) <b>pada pesan ini</b> dengan soal baru. Anda dapat melakukan"
        ." <i>paste</i> dan mengirimkannya setelah Anda edit. \n|ID:$jenis_soal|$id_soal";
        KirimPerintah('sendMessage',[
            'chat_id' => $chat_id,
            'text'=> $text,
            'parse_mode'=>'HTML',
            'reply_markup' => [
                'force_reply'=>true,
                'input_field_placeholder'=>'Soal Update',
                'selective'=>true,
            ],
        ]);
    }
}
elseif(isDiawali($command,'soal_hapus_')){
    $explode = explode("__",str_replace('soal_hapus_','',$command));
    $id_soal = $explode[0];
    $jenis_soal = $explode[1];
    $data_soal = loadData("soal/$jenis_soal/$id_soal");
    if(empty($data_soal['soal'])){
        KirimPerintah('sendMessage',[
            'chat_id' => $chat_id,
            'text'=> "Data sudah tidak tersedia saat ini.",
            'parse_mode'=>'HTML',
        ]);
    }
    elseif(isset($data_soal['delsc'])){
        KirimPerintah('sendMessage',[
            'chat_id' => $chat_id,
            'text'=> "Soal ini memang sedang dalam proses penghapusan..",
            'parse_mode'=>'HTML',
        ]);
    }
    else{
        $text = "[SOAL]\n\nKenapa kamu ingin menghapus soal ini?\n\n==========\n<i>".$data_soal['soal']."</i>\n|ID:$jenis_soal|$id_soal";
        KirimPerintah('sendMessage',[
            'chat_id' => $chat_id,
            'text'=> $text,
            'parse_mode'=>'HTML',
            'reply_markup' => [
                'force_reply'=>true,
                'input_field_placeholder'=>'Alasan Hapus',
                'selective'=>true,
            ],
        ]);
    }
}
elseif(isDiawali($command,'soal_jawaban_')){
    $explode = explode("__",str_replace('soal_jawaban_','',$command));
    $id_soal = $explode[0];
    $jenis_soal = $explode[1];
    $data_soal = loadData("soal/$jenis_soal/$id_soal");
    if(empty($data_soal)){
        KirimPerintah('sendMessage',[
            'chat_id' => $chat_id,
            'text'=> "Data ini sudah tidak tersedia",
            'parse_mode'=>'HTML',
        ]);
    }
    else{
        $output = "[SOAL]\n\nJawaban untuk:\n======\n";
        $output .= $data_soal['soal'];
        $output .= "\n======\n\nBalas pada pesan ini untuk menambahkan jawaban\n|ID:$jenis_soal|$id_soal";;
        if(empty($data_soal['jawab'])){
            $reply_markup = [
                'force_reply'=>true,
                'input_field_placeholder'=>'Tulis Jawaban',
                'selective'=>true,
            ];
        }
        else{
            arsort($data_soal['jawab']);
            $inlinekeyboard_arr = [
                ['⬇️TAMBAHKAN⬇️', '~~'],
                ['⬇️JAWABAN⬇️', '~~'],
                ['⬇️KURANGI⬇️', '~~'],
            ];
            foreach($data_soal['jawab'] as $k=>$v){
                $inlinekeyboard_arr[] = ["$v+1", 'soal_jwbsc_'.$id_soal.'__'.$jenis_soal.'__+'];
                $inlinekeyboard_arr[] = [$k, '~~'];
                $inlinekeyboard_arr[] = ["$v-1", 'soal_jwbsc_'.$id_soal.'__'.$jenis_soal.'__-'];
            }
            $reply_markup = inlineKeyBoard($inlinekeyboard_arr,3);
        }
        $id_soal = $explode[0];
        KirimPerintah('sendMessage',[
            'chat_id' => $chat_id,
            'text'=> $output,
            'parse_mode'=>'HTML',
            'reply_markup' => $reply_markup,
        ]);
    }
    
}