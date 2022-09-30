<?php

if($command == "allowgroup" and $chat_id == $id_developer){
    if(!empty($command_after)){
        $data['allowed_groups'][$command_after] = true;
        $result = executeQuery("update settings set value = '".serialize($data['allowed_groups'])."' where key='allowed_groups'", "DB_GJK");
        KirimPerintah('sendMessage',[
            'chat_id' => $chat_id,
            'text'=> "DONE: ".print_r($result,1),
            'parse_mode'=>'HTML',
            'reply_to_message_id' => $message_id
        ]);	
    }
    else{
        KirimPerintah('sendMessage',[
            'chat_id' => $chat_id,
            'text'=> "result: ".print_r($data['allowed_groups'],1),
            'parse_mode'=>'HTML',
            'reply_to_message_id' => $message_id
        ]);	
    }
}

elseif(isDiawali($command,"user") and $chat_id == $id_developer){
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

elseif($command == "tesdir" and $chat_id == $id_developer){
    KirimPerintah('sendMessage',[
        'chat_id' => $chat_id,
        'text'=> "games: ".print_r(scandir("galihjk/games/"),1),
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
    KirimPerintah('sendMessage',[
        'chat_id' => $chat_id,
        'text' => 'data: '.print_r(loadData("data"),1),
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

elseif($command == "stopsrv" and $chat_id == $id_developer){
    server_stop();
    KirimPerintah('sendMessage',[
        'chat_id' => $chat_id,
        'text' => 'OK',
        'parse_mode'=>'HTML',
        'reply_to_message_id' => $message_id
    ]);
}