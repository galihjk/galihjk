<?php
if(isDiawali($reply_to_message_text,"[SOAL]\n\nBalas pesan ini untuk membuat soal survey. ")){
    KirimPerintah('sendMessage',[
        'chat_id' => $chat_id,
        'text'=> "[SOAL]\n\nBalas pesan ini untuk membuat soal survey. \n$message_text\n".mentionUser($from_id),
        'parse_mode'=>'HTML',
        'reply_markup' => [
            'force_reply'=>true,
            'input_field_placeholder'=>'Tulis soal yang mau ditambahkan',
            'selective'=>true,
        ],
    ]);
}