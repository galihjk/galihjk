<?php

// if($command == "allowgroup" and $chat_id == $id_developer){
//     if(!empty($command_after)){
//         $data['allowed_groups'][$command_after] = true;
//         $result = executeQuery("update settings set value = '".serialize($data['allowed_groups'])."' where key='allowed_groups'", "DB_GJK");
//         KirimPerintah('sendMessage',[
//             'chat_id' => $chat_id,
//             'text'=> "DONE: ".print_r($result,1),
//             'parse_mode'=>'HTML',
//             'reply_to_message_id' => $message_id
//         ]);	
//     }
//     else{
//         KirimPerintah('sendMessage',[
//             'chat_id' => $chat_id,
//             'text'=> "result: ".print_r($data['allowed_groups'],1),
//             'parse_mode'=>'HTML',
//             'reply_to_message_id' => $message_id
//         ]);	
//     }
// }

// else
if(isDiawali($command,"user") and $chat_id == $id_developer){
    if(isDiawali($command,"user_")){
        KirimPerintah('sendMessage',[
            'chat_id' => $chat_id,
            'text'=> "result: \n(per ".date("Y-m-d H:i",$data['last_active_user_time']).")\n ".print_r($data['active_users'][str_replace("user_","",$command)],1),
            'parse_mode'=>'HTML',
            'reply_to_message_id' => $message_id
        ]);
    }
    else{
        $output = "users:\n(per ".date("Y-m-d H:i",$data['last_active_user_time']).")\n ";
        foreach($data['active_users'] as $key=>$val){
            $output .= "/user_$key " . $val['first_name'] . "\n";
        }
        KirimPerintah('sendMessage',[
            'chat_id' => $chat_id,
            'text'=> $output,
            'parse_mode'=>'HTML',
            'reply_to_message_id' => $message_id
        ]);
    }
}

elseif($command == "setjadwal" and $chat_id == $id_developer){
    KirimPerintah('editMessageText',[
        'chat_id' => '-1001635551800',
        'text'=> $command_after
        ,
        'parse_mode'=>'HTML',
        'message_id' => '4313',
    ]);
}

elseif($command == "forceplay" and $chat_id == $id_developer){
    $result = KirimPerintah('sendAnimation',[
        'chat_id' =>'-1001635551800',
        'animation' => 'CgACAgUAAxkBAALHa2N-3z1MSV2MenFwfOhCwXTOLQUNAALfBgACxrD5V8vgk_f6z8n4KwQ',
        'caption' => "Kuis Mayo Mino\n\nAyo Ikutan!!\nklik >>> /join\n\nPermainan dimulai oleh: System",
        'parse_mode'=>'HTML',
    ]);
    $startmsgid = $result['result']['message_id'];
    startPlayingGame('-1001635551800', "System", 'mamin', [
        'step'=>'starting',
        'starting_timeleft'=>90,
        'startmsgid'=>$startmsgid,
        'remind_join'=>0,
        'player_change'=>true,
        'players'=>[]
    ]);
    $data['change_step'][] = [
        'mamin',
        '-1001635551800',
        'starting_check',
        time()+5,
    ];
}

elseif($command == "tesjob" and $chat_id == $id_developer){
    create_job("KirimPerintah('sendMessage',[
        'chat_id' => '$chat_id',
        'text'=> 'JOB JALAN!!',
        'parse_mode'=>'HTML',
        'reply_to_message_id' => '$message_id'
    ]);",time()+20);
    KirimPerintah('sendMessage',[
        'chat_id' => $chat_id,
        'text'=> 'Tunggu 20 detik',
        'parse_mode'=>'HTML',
        'reply_to_message_id' => $message_id
    ]);
}

elseif($command == "soaldir" and $chat_id == $id_developer){
    $folder = "data/soal/survey/";
    $list = scandir($folder);
    $output = "nih:\n";
    foreach($list as $item){
        if(isDiakhiri($item,".json")){
            $output .= "- $item: ".(round((time() - filemtime($folder.$item))/60))."\n";
        }
    }
    KirimPerintah('sendMessage',[
        'chat_id' => $chat_id,
        'text'=> $output,
        'parse_mode'=>'HTML',
        'reply_to_message_id' => $message_id
    ]);
}

elseif($command == "tessoal39" and $chat_id == $id_developer){
    KirimPerintah('sendMessage',[
        'chat_id' => $chat_id,
        'text'=> 'nih'.print_r(soal_get('survey',['39']),1),
        'parse_mode'=>'HTML',
        'reply_to_message_id' => $message_id
    ]);
}
elseif($command == "tessoal" and $chat_id == $id_developer){
    KirimPerintah('sendMessage',[
        'chat_id' => $chat_id,
        'text'=> 'nih'.print_r(soal_get('survey'),1),
        'parse_mode'=>'HTML',
        'reply_to_message_id' => $message_id
    ]);
}
elseif($command == "tessoalget" and $from_id == $id_developer){
    //ambil soal secara acak
    $soal_sudah = getChatData($chat_id,'soal_sudah');
    $soal_get = soal_get('survey',array_keys($soal_sudah));
    if($soal_get == "habis!"){
        KirimPerintah('sendMessage',[
            'chat_id' => $chat_id,
            'text'=> "Soal sudah habis, soal lama bisa muncul lagi. \n\n(* yuk tambah soal di @galihjksoal)",
            'parse_mode'=>'HTML',
        ]);
        setChatData($chat_id,['soal_sudah' => []]);
        $soal_get = soal_get('survey');
    }
    if(empty($soal_get['soal'])){
        $data_soal = [
            'id'=>'ERROR',
            'soal'=>"ERROR $soal_no",
        ];
    }
    else{
        $data_soal = $soal_get;
    }
    soal_setSudah($chat_id, $data_soal['id'],'survey');
    KirimPerintah('sendMessage',[
        'chat_id' => $chat_id,
        'text'=> 'nih'.print_r($data_soal,1),
        'parse_mode'=>'HTML',
        'reply_to_message_id' => $message_id
    ]);
}

elseif($command == "tesdir" and $chat_id == $id_developer){
    $folder = "data/user/";
    $list = scandir($folder);
    $output = "nih:\n";
    foreach($list as $item){
        if(isDiakhiri($item,".json")){
            $output .= "- $item: ".(round((time() - filemtime($folder.$item))/60))."\n";
        }
    }
    KirimPerintah('sendMessage',[
        'chat_id' => $chat_id,
        'text'=> $output,
        'parse_mode'=>'HTML',
        'reply_to_message_id' => $message_id
    ]);
}

elseif($command == "p" and $chat_id == $id_developer){
    if(empty($command_after)){
        $data['impersonate'] = [];
    }
    else{
        $data['impersonate'] = [
            'id' => $command_after,
            'username' => "VirtualPlayer$command_after",
            'first_name' => "Virtual Player $command_after",
        ];
    }
    KirimPerintah('sendMessage',[
        'chat_id' => $chat_id,
        'text'=> "ok",
        'parse_mode'=>'HTML',
        'reply_to_message_id' => $message_id
    ]);
}

elseif($command == "data" and $chat_id == $id_developer){
    $text = "DATA\n";
    foreach($data as $k=>$v){
        $text .= "- $k: ";
        if(is_array($v)){
            $text .= "/data__$k";
        }
        else{
            $text .= $v;
        }
        $text .= "\n";
    }
    KirimPerintah('sendMessage',[
        'chat_id' => $chat_id,
        'text' => $text,
        'parse_mode'=>'HTML',
        'reply_to_message_id' => $message_id
    ]);
}

elseif(isDiawali($command,"data__") and $chat_id == $id_developer){
    $explode = explode("__",$command);
    $datakey = $explode[1];
    $datashow = [];
    $dataget = $data[$datakey];
    if(isset($explode[2])){
        $datashow = $data[$datakey][$explode[2]];
    }
    else{
        foreach($dataget as $key => $val){
            if(is_array($val)){
                $datashow[$key] = "/data__$datakey"."__$key";
            }
            else{
                $datashow[$key] = $val;
            }
        }
    }
    
    KirimPerintah('sendMessage',[
        'chat_id' => $chat_id,
        'text' => 'data: '.print_r($datashow,1),
        'parse_mode'=>'HTML',
        'reply_to_message_id' => $message_id
    ]);
}

elseif($command == "getsetting" and !empty($command_after) and $chat_id == $id_developer){
    KirimPerintah('sendMessage',[
        'chat_id' => $chat_id,
        'text' => $command_after.' data: '.print_r(loadData("setting/$command_after"),1),
        'parse_mode'=>'HTML',
        'reply_to_message_id' => $message_id
    ]);
}

elseif($command == "setsetting" and $chat_id == $id_developer){
    if(empty($command_after)){
        KirimPerintah('sendMessage',[
            'chat_id' => $chat_id,
            'text' => 'setsetting key=>value',
            'parse_mode'=>'HTML',
            'reply_to_message_id' => $message_id
        ]);
    }
    else{
        $explode = explode("=>",$command_after);
        if(count($explode) == 2){
            saveData("setting/".$explode[0], $explode[1]);
            KirimPerintah('sendMessage',[
                'chat_id' => $chat_id,
                'text' => 'OK',
                'parse_mode'=>'HTML',
                'reply_to_message_id' => $message_id
            ]);
        }
        else{
            KirimPerintah('sendMessage',[
                'chat_id' => $chat_id,
                'text' => 'setsetting key=>value',
                'parse_mode'=>'HTML',
                'reply_to_message_id' => $message_id
            ]);
        }
    }
}

elseif($command == "startsrv" and $chat_id == $id_developer){
    server_start();
    KirimPerintah('sendMessage',[
        'chat_id' => $chat_id,
        'text' => 'OK',
        'parse_mode'=>'HTML',
        'reply_to_message_id' => $message_id
    ]);
}

// elseif($command == "lstprnth" and $chat_id == $id_developer){
//     KirimPerintah('sendMessage',[
//         'chat_id' => $chat_id,
//         'text' => "lstprnth: ".print_r([
//             'last_perintah_bot'=>loadData("last_perintah_bot",0),
//             'currenttime'=>time(),
//         ],1),
//         'parse_mode'=>'HTML',
//         'reply_to_message_id' => $message_id
//     ]);
// }

elseif($command == "stopsrv" and $chat_id == $id_developer){
    server_stop();
    KirimPerintah('sendMessage',[
        'chat_id' => $chat_id,
        'text' => 'OK',
        'parse_mode'=>'HTML',
        'reply_to_message_id' => $message_id
    ]);
}