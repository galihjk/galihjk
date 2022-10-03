<?php 
if($command == 'tambah_soal'){
    $reply_markup = inlineKeyBoard([
        ['survey', 'soal_survey_tambah'],
        ['?', 'soal_survey_info'],
        ['kuis', 'soal_kuis_tambah'],
        ['?', 'soal_kuis_info'],
    ],2);
    
    //kirim pesan dengan tombol
    KirimPerintah('sendMessage',[
        'chat_id' => $chat_id,
        'text'=> "Mau membuat soal yang jenis nya apa? (Klik tanda tanya untuk info)",
        'parse_mode'=>'HTML',
        'reply_markup' => $reply_markup,
    ]);
    
    // KirimPerintah('sendMessage',[
    //     'chat_id' => $chat_id,
    //     'text'=> "Balas pesan ini untuk membuat soal",
    //     'parse_mode'=>'HTML',
    //     'reply_markup' => [
    //         'force_reply'=>true,
    //         'input_field_placeholder'=>'Tulis soal yang mau ditambahkan',
    //         'selective'=>true,
    //     ],
    // ]);
}
else{
    KirimPerintah('sendMessage',[
        'chat_id' => $chat_id,
        'text'=> "command: $command",
        'parse_mode'=>'HTML',
        'reply_markup' => $reply_markup,
    ]);
}