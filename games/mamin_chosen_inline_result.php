<?php
include_once("galihjk/games/mamin_init.php");

$explode = explode("|",$chosen_inline_result['result_id']);

$from_id = $chosen_inline_result['from']['id'];

$from_name = $chosen_inline_result['from']['first_name'];

if(count($explode) !== 3){
    return false;
}
$chat_id = $explode[1];
$jawaban = $explode[2];

if(empty($data_playing_chatters[$chat_id]['mamin']['players'][$from_id])){
    //new player
    setUserPlaying($from_id, $chat_id, "mamin");
    addUserPlayingTime($from_id, 30, true);
    $data_playing_chatters[$chat_id]['mamin']['players'][$from_id] = [
        'score'=>0,
        'jawab'=>$jawaban,
    ];
}
else{
    if (empty($data_playing_chatters[$chat_id]['mamin']['players'][$from_id]['jawab'])) addUserPlayingTime($from_id, 30, true);
    $data_playing_chatters[$chat_id]['mamin']['players'][$from_id]['jawab'] = $jawaban;
}

$ada_yang_belum = false;
foreach($data_playing_chatters[$chat_id]['mamin']['players'] as $k=>$v){
    if(empty($v['jawab'])){
        $ada_yang_belum = true;
        break;
    }
}
if(!$ada_yang_belum){
    $data_playing_chatters[$chat_id]['mamin']['waktu_habis_sisa'] = 0;
    $data['change_step'][] = ['mamin', $chat_id, 'cek_waktu_habis'];
}
KirimPerintah('sendMessage',[
    'chat_id' => $chat_id,
    'text'=> "Jawaban $from_name diterima $emoji_like"
        .($ada_yang_belum ? '' : "\n\nSemua pemain sudah menjawab $emoji_like$emoji_like"),
    'parse_mode'=>'HTML',
    'reply_to_message_id'=> $data_playing_chatters[$chat_id]['mamin']['soal_msgid'],
]);