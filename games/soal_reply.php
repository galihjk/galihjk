<?php
if(isDiawali($reply_to_message_text,"[SOAL]\n\nBalas pesan ini untuk membuat soal survey. ")){
    $jenis_soal = 'survey';
    $soal = $message_text;
    $channel_username = "@galihjksoal";
    $channel_post = KirimPerintah('sendMessage',[
        'chat_id' => $channel_username,
        'text'=> "Loading...",
        'parse_mode'=>'HTML',
    ]);
    $id_soal = $channel_post['result']['message_id'];
    $data_soal = [
        'soal'=>$soal,
        'vote'=>[
            $from_id => 1,
        ],
        'vtsc'=>1,
        'jawab'=>[],
        'ktrb'=>[(string) $from_id],
    ];
    updateSoalPost($id_soal,$jenis_soal,$data_soal);
    saveData("soal/$jenis_soal/$id_soal",$data_soal);
    userContributeSoal($from_id);

    KirimPerintah('sendMessage',[
        'chat_id' => $chat_id,
        'text'=> "Berhasil. Lihat soalmu di: https://t.me/galihjksoal/$id_soal",
        'parse_mode'=>'HTML',
        'reply_markup' => [
            'force_reply'=>false,
        ],
    ]);
}