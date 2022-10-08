<?php 
if($command == 'soal_tambah'){
    $reply_markup = inlineKeyBoard([
        ['survey', 'soal_survey_tambah'],
        ['-> [ ? ]', 'soal_survey_info'],
        // ['kuis*', 'underconstruction'],
        // ['?', 'soal_kuis_info'],
        ['komkat*', 'underconstruction'],
        ['-> [ ? ]', 'soal_komkat_info'],
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
    KirimPerintah('sendMessage',[
        'chat_id' => $chat_id,
        'text'=> "EDIT:\n".print_r(loadData("soal/survey/$id_soal"),true),
        'parse_mode'=>'HTML',
    ]);
}
elseif(isDiawali($command,'soal_hapus_')){
    $explode = explode("__",str_replace('soal_hapus_','',$command));
    $id_soal = $explode[0];
    KirimPerintah('sendMessage',[
        'chat_id' => $chat_id,
        'text'=> "HAPUS:\n".print_r(loadData("soal/survey/$id_soal"),true),
        'parse_mode'=>'HTML',
    ]);
}
elseif(isDiawali($command,'soal_jawaban_')){
    $explode = explode("__",str_replace('soal_jawaban_','',$command));
    $id_soal = $explode[0];
    KirimPerintah('sendMessage',[
        'chat_id' => $chat_id,
        'text'=> "JAWABAN:\n".print_r(loadData("soal/survey/$id_soal"),true),
        'parse_mode'=>'HTML',
    ]);
}