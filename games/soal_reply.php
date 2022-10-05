<?php
if(isDiawali($reply_to_message_text,"[SOAL]\n\nBalas pesan ini untuk membuat soal survey. ")){
    $soal = $message_text;
    $channel_username = "@galihjksoal";
    $channel_post = KirimPerintah('sendMessage',[
        'chat_id' => $channel_username,
        'text'=> "Loading...",
        'parse_mode'=>'HTML',
    ]);
    $id_soal = $channel_post['result']['message_id'];
    $data['delayedPerintah'][] = ['editMessageText',
        [
            'chat_id' => $channel_username,
            'message_id'=>$id_soal,
            'text'=> "[SOAL SURVEY]\n\n$soal\n\n<i>Kontributor:</i> $first_name",
            'parse_mode'=>'HTML',
            'reply_markup' => inlineKeyBoard([
                ["ID SOAL: $id_soal","underconstruction"]
            ],2),
        ], time()+10
    ];
    $data_soal = [
        'soal'=>$soal,
        'vote'=>[
            [$from_id => 1],
        ],
        'vtsc'=>1,
        'jawab'=>[],
    ];
    saveData("soal/survey/$id_soal",$data_soal);

    KirimPerintah('sendMessage',[
        'chat_id' => $chat_id,
        'text'=> "Berhasil. Lihat soalmu di: https://t.me/galihjksoal/$id_soal",
        'parse_mode'=>'HTML',
    ]);
}