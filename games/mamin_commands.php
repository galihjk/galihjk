<?php
if(empty($command)){
	goto sudahi;
}

$subcommand = str_replace("mamin_","",$command);
$subcommand_group_only = [
	'play',
];

if(in_array($subcommand,$subcommand_group_only) and !isDiawali($chat_id,"-")){
	KirimPerintah('sendMessage',[
		'chat_id' => $chat_id,
		'text'=> "Ayo jalankan command ini di group mu! $emoji_like\n\n<i>atau kamu bisa main di @galihjkplay</i>",
		'parse_mode'=>'HTML',
		'reply_to_message_id' => $message_id
	]);
}
elseif($subcommand == "info"){
    KirimPerintah('sendMessage',[
		'chat_id' => $chat_id,
		'text'=> "<b>Kuis Mayo Mino</b>\n\n"
            ."Permainan terdiri dari 10 pertanyaan, 5 pertanyaan berupa mode MAYORITAS, yang 5 lagi MINORITAS.\n\n"
            ."<b>Mode Mayoritas</b>: Dapat skor jika ada pemain lain yang jawabannya sama dengan mu.\n"
            ."<b>Mode Minoritas</b>: Skor semakin kecil tiap ada pemain lain yang jawabannya sama dengan mu.\n",
		'parse_mode'=>'HTML',
	]);
}
elseif($subcommand == "play"){
    if(checkUserNotPlayingAnyGame($from_id, $chat_id, $message_id)){
        $result = KirimPerintah('sendAnimation',[
            'chat_id' => $chat_id,
            'animation' => 'CgACAgUAAxkBAAIEGWMeeR-w3CJUBUUjPl_2PWYy36MmAAJnBQACnE34VM0GSJKN9AcXKQQ',
            'caption' => "Kuis Mayo Mino\n\nAyo Ikutan!!\nklik >>> /join\n\nPermainan dimulai oleh: ". mentionUser($from_id),
            'parse_mode'=>'HTML',
        ]);
        $startmsgid = $result['result']['message_id'];
        startPlayingGame($chat_id, $from_id, 'mamin', [
            'step'=>'starting',
            'starting_timeleft'=>90,
            'startmsgid'=>$startmsgid,
            'remind_join'=>0,
            'player_change'=>true,
            'soal_sudah'=>[],
            'players'=>[
                $from_id => [
                    'score'=>0,
                ],
            ]
        ]);
        $data['change_step'][] = [
            'mamin',
            $chat_id,
            'starting_check',
            time()+5,
        ];
        server_start();
    }
}
elseif($subcommand == "extend" and !empty($data['playing_chatters'][$chat_id]['mamin']['step'])
and in_array($data['playing_chatters'][$chat_id]['mamin']['step'],["starting", "starting_check"])){
    if(empty($data['playing_chatters'][$chat_id]['mamin']['players'][$from_id])){
        KirimPerintah('sendMessage',[
            'chat_id' => $chat_id,
            'text'=> "Kamu belum ikutan, ayo /join !",
            'reply_to_message_id'=> $message_id,
        ]);
    }
    else{
        $data['playing_chatters'][$chat_id]['mamin']['starting_timeleft'] = 120;
        $data['playing_chatters'][$chat_id]['mamin']['remind_join'] = 0;
        KirimPerintah('sendMessage',[
            'chat_id' => $chat_id,
            'text'=> "Waktu tunggu telah diset menjadi 120 detik.",
        ]);
    }
    
}
elseif($subcommand == "force_start" and !empty($data['playing_chatters'][$chat_id]['mamin']['step'])
and in_array($data['playing_chatters'][$chat_id]['mamin']['step'],["starting", "starting_check"]) ){
    if(empty($data['playing_chatters'][$chat_id]['mamin']['players'][$from_id])){
        KirimPerintah('sendMessage',[
            'chat_id' => $chat_id,
            'text'=> "Kamu belum ikutan, ayo /join !",
            'reply_to_message_id'=> $message_id,
        ]);
    }
    else{
        if(count($data['playing_chatters'][$chat_id]['mamin']['players']) < 3){
            $data['playing_chatters'][$chat_id]['mamin']['starting_timeleft'] = 0;
        }
        else{
            if(empty($data['playing_chatters'][$chat_id]['force_start'])) $data['playing_chatters'][$chat_id]['force_start'] = [];
            if(!in_array($from_id, $data['playing_chatters'][$chat_id]['force_start'])){
                $data['playing_chatters'][$chat_id]['force_start'][] = $from_id;
                if(count($data['playing_chatters'][$chat_id]['force_start']) < 2){
                    KirimPerintah('sendMessage',[
                        'chat_id' => $chat_id,
                        'text'=> "$from_name ingin mulai sekarang, <b>perlu seorang lagi</b> nih yang jalanin command /force_start",
                        'parse_mode'=>'HTML',
                    ]);
                }
                else{
                    $data['playing_chatters'][$chat_id]['mamin']['starting_timeleft'] = 0;
                    $data['change_step'][] = ['mamin', $chat_id, 'starting_check'];
                    unset($data['playing_chatters'][$chat_id]['force_start']);
                }
            }
        }
    }
    
}
elseif($subcommand == "join"){
    if(checkUserNotPlayingAnyGame($from_id, $chat_id, $message_id)){
        if(!empty($data['playing_chatters'][$chat_id]['mamin']['step']) 
        // and in_array($data['playing_chatters'][$chat_id]['mamin']['step'],["starting", "starting_check"])
        ){
            setUserPlaying($from_id, $chat_id, "mamin");
            if(empty($data['playing_chatters'][$chat_id]['mamin']['flee'][$from_id])){
                $data['playing_chatters'][$chat_id]['mamin']['players'][$from_id] = [
                    'score'=>0,
                ];
            }
            else{
                $data['playing_chatters'][$chat_id]['mamin']['players'][$from_id] = $data['playing_chatters'][$chat_id]['mamin']['flee'][$from_id];
                unset($data['playing_chatters'][$chat_id]['mamin']['flee'][$from_id]);
            }
            $data['playing_chatters'][$chat_id]['mamin']['player_change'] = true;
            KirimPerintah('sendMessage',[
                'chat_id' => $chat_id,
                'text'=> "$emoji_like",
                'reply_to_message_id' => $message_id
            ]);
            /*
                if(!empty($data['playing_chatters'][$chat_id]['mamin']['soal_no'])){
                    $next_soal = $data['playing_chatters'][$chat_id]['mamin']['soal_no'] + 1;
                    if($next_soal <= 10){
                        setUserPlaying($from_id, $chat_id, "mamin");
                        KirimPerintah('sendMessage',[
                            'chat_id' => $chat_id,
                            'text'=> "$emoji_like",
                            'reply_to_message_id' => $message_id
                        ]);
                        $data['playing_chatters'][$chat_id]['mamin']['next_join_player'][$from_id] = true;
                    }
                    else{
                        KirimPerintah('sendMessage',[
                            'chat_id' => $chat_id,
                            'text'=> "Sebentar lagi selesai nih,, kalau sudah selesai, nanti kamu mulai main juga yaa.. \n/play - mulai main",
                            'reply_to_message_id' => $message_id
                        ]);
                    }
                }
            */
        }
        else{
            KirimPerintah('sendMessage',[
                'chat_id' => $chat_id,
                'text'=> "GAGAL!\n maaf, coba lagi nanti ya..",
                'reply_to_message_id' => $message_id
            ]);
        }
    }
    
}
elseif($subcommand == "flee"){
    if(!empty($data['playing_chatters'][$chat_id]['mamin']['step'])){
        setUserWinRate($from_id, false, false);
        $unsetuser = unsetUserPlaying($from_id);
        $data['playing_chatters'][$chat_id]['mamin']['flee'][$from_id] = $data['playing_chatters'][$chat_id]['mamin']['players'][$from_id];
        unset($data['playing_chatters'][$chat_id]['mamin']['players'][$from_id]);
        $data['playing_chatters'][$chat_id]['mamin']['player_change'] = true;
        KirimPerintah('sendMessage',[
            'chat_id' => $chat_id,
            'text'=> "$emoji_dislike" . print_r($unsetuser,true),
            'reply_to_message_id' => $message_id
        ]);
    }
    else{
        adaYangSalah($chat_id, $message_id);
    }
}
elseif($subcommand == "skip"){
    $data['playing_chatters'][$chat_id]['mamin']['soal_no'] = 9;
    KirimPerintah('sendMessage',[
        'chat_id' => $chat_id,
        'text'=> "$from_id $id_developer $emoji_dislike",
        'reply_to_message_id' => $message_id
    ]);
    
}

sudahi:
;