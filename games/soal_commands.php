<?php 
if($command == 'soal_tambah'){
    $reply_markup = inlineKeyBoard([
        ['survey', 'soal_survey_tambah'],
        ['?', 'soal_survey_info'],
        ['kuis*', 'underconstruction'],
        ['?', 'soal_kuis_info'],
        ['komkat*', 'underconstruction'],
        ['?*', 'soal_kuis_info'],
        ['huruf*', 'underconstruction'],
        ['?*', 'soal_kuis_info'],
    ],2);
    
    //kirim pesan dengan tombol
    KirimPerintah('sendMessage',[
        'chat_id' => $chat_id,
        'text'=> "Mau membuat soal yang jenis nya apa? (Klik tanda tanya untuk info)",
        'parse_mode'=>'HTML',
        'reply_markup' => $reply_markup,
    ]);
    
}