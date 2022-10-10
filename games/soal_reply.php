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
    KirimPerintah('editMessageText',
    [
        'chat_id' => $channel_username,
        'message_id'=>$id_soal,
        'text'=> "[SOAL ".strtoupper($jenis_soal)."]\n\n$soal\n\nVoteScore: 1\n<i>Kontributor:</i> $first_name",
        'parse_mode'=>'HTML',
        'reply_markup' => inlineKeyBoard([
            ["$emoji_dislike DOWN (-1)","soal_downvote_$id_soal"."__$jenis_soal"],
            ["$emoji_please unvote (0)","soal_unvote_$id_soal"."__$jenis_soal"],
            ["$emoji_like UP (+1)","soal_upvote_$id_soal"."__$jenis_soal"],
            ["$emoji_pencil Edit","https://t.me/".$config['bot_username']."?start=cmd_soal_edit_$id_soal"."__$jenis_soal"],
            ["$emoji_cross Hapus","https://t.me/".$config['bot_username']."?start=cmd_soal_hapus_$id_soal"."__$jenis_soal"],
            ["$emoji_chat Jawaban","https://t.me/".$config['bot_username']."?start=cmd_soal_jawaban_$id_soal"."__$jenis_soal"],
        ],3),
    ]);
    $data_soal = [
        'soal'=>$soal,
        'vote'=>[
            [$from_id => 1],
        ],
        'vtsc'=>1,
        'jawab'=>[],
        'ktrb'=>[$from_id],
    ];
    saveData("soal/$jenis_soal/$id_soal",$data_soal);

    KirimPerintah('sendMessage',[
        'chat_id' => $chat_id,
        'text'=> "Berhasil. Lihat soalmu di: https://t.me/galihjksoal/$id_soal",
        'parse_mode'=>'HTML',
    ]);
}