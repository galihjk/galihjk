<?php
if($command == "start" and $command_after === ""){
    KirimPerintah('sendMessage',[
        'chat_id' => $chat_id,
        'text'=> "/play - mulai bermain"
            ."\n/info - info",
        'parse_mode'=>'HTML',
        'reply_to_message_id' => $message_id
    ]);	
    // "<b>UNDERCONSTRUCTION!!! </b> (sedang dikembangkan)\n"
    //         ."-play_komkat \n<b>KOMUNIKATA</b> \n"
    //         ."-play_kabin \n<b>KATA BERBINTANG</b> \n"
    //         ."-play_fam \n<b>KUIS MAYORITAS FAMILY</b> \n"
    //         ."/mamin_play \n<b>KUIS MAYORITAS MINORITAS</b>\n"
    //         ."-play_asbung \n<b>ASAL NYAMBUNG</b> \n"
    //         ."/ttss_play \n<b>TTS SURVEY</b> \n"
    //         ."\n/info"
}

elseif($command == "play"){
    KirimPerintah('sendMessage',[
        'chat_id' => $chat_id,
        'text'=> "/mamin_play \n<b>KUIS MAYO MINO</b>"
            ."\n\n/info",
        'parse_mode'=>'HTML',
        'reply_to_message_id' => $message_id
    ]);	
    // "<b>UNDERCONSTRUCTION!!! </b> (sedang dikembangkan)\n"
    //         ."-play_komkat \n<b>KOMUNIKATA</b> \n"
    //         ."-play_kabin \n<b>KATA BERBINTANG</b> \n"
    //         ."-play_fam \n<b>KUIS MAYORITAS FAMILY</b> \n"
    //         ."/mamin_play \n<b>KUIS MAYORITAS MINORITAS</b>\n"
    //         ."-play_asbung \n<b>ASAL NYAMBUNG</b> \n"
    //         ."/ttss_play \n<b>TTS SURVEY</b> \n"
    //         ."\n/info"
}

elseif($command == "info"){
    KirimPerintah('sendMessage',[
        'chat_id' => $chat_id,
        'text'=> "<b>UNDERCONSTRUCTION!!!",
        'parse_mode'=>'HTML',
        'reply_to_message_id' => $message_id
    ]);	
    // KirimPerintah('sendMessage',[
        // 'chat_id' => $chat_id,
        // 'text'=> "<b>UNDERCONSTRUCTION!!! </b> (sedang dikembangkan)\n
            // -info_komkat \n<b>KOMUNIKATA</b> \n"
            // ."-info_kabin \n<b>KATA BERBINTANG</b> \n"
            // ."-info_fam \n<b>KUIS MAYORITAS FAMILY</b> \n"
            // ."/mamin_info \n<b>KUIS MAYORITAS MINORITAS</b>\n"
            // ."-info_asbung \n<b>ASAL NYAMBUNG</b> \n"
            // ."-info_ttss \n<b>TTS SURVEY</b> \n",
        // 'parse_mode'=>'HTML',
        // 'reply_to_message_id' => $message_id
    // ]);	
}

elseif($command == "stop"){
    $lanjut = true;
    if(substr($chat_id,0,1) == "-"){
        if($from_id != $id_developer){
            $lanjut = false;
        }
    }
    else{
        
    }
    if($lanjut){
        KirimPerintah('sendMessage',[
            'chat_id' => $chat_id,
            'text'=> "OK, permainan dihentikan.",
            'parse_mode'=>'HTML',
            'reply_to_message_id' => $message_id
        ]);	
        if($data_playing_chatters[$chat_id]['playing'] == "ttss"){
            KirimPerintah('deleteMessage',[
                'chat_id' => $chat_id,
                'message_id'=> $data_playing_chatters[$chat_id]['ttss']['board_msgid'],
            ]);
            unset($data_playing_chatters[$chat_id]['ttss']);
        }
        // $data_playing_chatters[$chat_id]['playing'] = false;	
        // clear memory and stop playing
        $data_playing_chatters[$chat_id] = [
            'playing' => false,
        ];
    }
}

elseif($command == "ping"){
    KirimPerintah('sendMessage',[
        'chat_id' => $chat_id,
        'text'=>  "PONG! ($timelag s)",
        'parse_mode'=>'HTML',
        'reply_to_message_id' => $message_id
    ]);
}

elseif($command == "debug" and isset($message_data['reply_to_message'])){
    KirimPerintah('sendMessage',[
        'chat_id' => $chat_id,
        'text'=> print_r($message_data['reply_to_message'],1),
        'parse_mode'=>'HTML',
        'reply_markup' => $reply_markup,
    ]);
}

elseif($command == "menu"){		
    
    //set variabel jumlah kolom, jika tidak diisi maka otomatis ambil dari parameter width terbesar pada tombol. default width = 1
    $jml_kolom = 4;
    
    //definisikan reply markup
    $reply_markup = inlineKeyBoard([
        'underconstruction',
        ['Satu', 'underconstruction'],
        ['Dua', 'callback_data'=>'underconstruction'],
        ['text'=>'Tiga', 'callback_data'=>'underconstruction'],
        ['text'=>'4. (restart bot)', 'url'=>'http://t.me/galihjkbot?start=1','width'=>2],
        ['5. (mention bot)','width'=>2,'switch_inline_query_current_chat'=>'test'],
        ['Enam (blog)','https://gjberkarya.blogspot.com',2],
        ['text'=>'Tujuh (galihjkdev)','url'=>'t.me/galihjkdev',1],
        ['Delapan', 'PilihDelapan'],			
        ['9 (select and mention bot)','switch_inline_query'=>'test','width'=>4],
        ['text'=>'Sepuluh (telegram bot api)','url'=>'core.telegram.org/bots/api'],
    ],$jml_kolom);
    
    //kirim pesan dengan tombol
    KirimPerintah('sendMessage',[
        'chat_id' => $chat_id,
        'text'=> "contoh menu",
        'parse_mode'=>'HTML',
        'reply_markup' => $reply_markup,
    ]);
}

elseif($command == "claim"){
    checkExpiredUnclaimeds($from_id);
    $user_data = getUser($from_id);
    if(empty($user_data)){
        KirimPerintah('sendMessage',[
            'chat_id' => $chat_id,
            'text'=> "Gagal",
            'reply_to_message_id' => $message_id,
        ]);
        goto skip_to_end;
    }
    $calimdata = empty($user_data['unclaimeds']) ? [] : $user_data['unclaimeds'];
    $text = "Poin yg dapat diklaim: \n\n";
    $ada = false;
    foreach($calimdata as $gametype=>$claimchat){
        foreach($claimchat as $claim_chat_id=>$claimvals){
            foreach($claimvals as $claimcode=>$claimval){
                $ada = true;
                $expired_in = $claimval[1] - time();
                $expired_text = timeToSimpleText($expired_in); 
                $text .= "- ".$claimval[0]." ($gametype / " ;
                $text .= getChatData($claim_chat_id,'title','') ;
                $text .= ") \n	&gt;&gt; <a href='https://galihjk.my.id/?web_run_action=claim&code=$from_id|$gametype|$claim_chat_id|$claimcode'>[AMBIL]</a> &lt;&lt;\n";
                $text .= "<i>Kadaluarsa dalam $expired_text</i>\n\n";
            }
        }
    }
    if($ada){
        $text .= "Kalau muncul produk yang kamu mau, masukin keranjang yaa.. :D";
    }
    else{
        $text .= "TIDAK ADA\n\nAyo mainkan dulu game nya /play";
    }
    
    $result = KirimPerintah('sendMessage',[
        'chat_id' => $chat_id,
        'text'=> $text,
        'parse_mode'=>'HTML',
        'reply_to_message_id' => $message_id,
        'disable_web_page_preview' => true,
    ]);
}

elseif($command == "point"){
    $user_data = getUser($from_id);
    if(empty($user_data)){
        $text = "Mohon maaf, tidak dapat diproses.";
    }
    else{
        $point = 0;
        if(!empty($user_data['point'])){
            $point = $user_data['point'];
        }
        $text = "POINT ".$user_data['first_name'].": \n<b>$point</b>";
    }
    // ini nanti dipake:
    // if(!empty($user_data['w_point'])){
    //     $text .= "\n\nPerolehan poin minggu ini:\n";
    //     $total_w_point = 0;
    //     foreach($user_data['w_point'] as $gametype=>$vals){
    //         $text .= "- $gametype:\n";
    //         foreach($vals as $val_chatid=>$val){
    //             $text .= " -- ".getChatData($val_chatid,'title','').": $val\n";
    //             $total_w_point += $val;
    //         }
    //     }
    //     $text .= "TOTAL: $total_w_point";
    // }
    $result = KirimPerintah('sendMessage',[
        'chat_id' => $chat_id,
        'text'=> $text,
        'parse_mode'=>'HTML',
        'reply_to_message_id' => $message_id
    ]);
}
//=============================================================

//don't play other game!
if(!empty($data_playing_chatters[$chat_id]['playing']) and substr($command,-5) == "_play"){
    if(substr($chat_id,0,1) == "-"){
        $balasan = "Kamu sedang bermain ".strtoupper($data_playing_chatters[$chat_id]['playing']).". Kalau ingin berhenti, admin perlu jalankan command /stop (harus admin grup)";
    }
    else{
        $balasan = "Kamu sedang bermain ".strtoupper($data_playing_chatters[$chat_id]['playing']).". Kalau ingin berhenti, kamu perlu jalankan command /stop";
    }
    KirimPerintah('sendMessage',[
        'chat_id' => $chat_id,
        'text'=> $balasan,
        'parse_mode'=>'HTML',
        'reply_to_message_id' => $message_id
    ]);
}
else{

    if($command == "join"){
        if(empty($data_playing_chatters[$chat_id]['playing'])){
            KirimPerintah('sendMessage',[
                'chat_id' => $chat_id,
                'text'=>  "Mau join apa? Kita gak lagi main apa-apa nih.. Yuk /play dulu!",
                'parse_mode'=>'HTML',
                'reply_to_message_id' => $message_id
            ]);
        }
        else{
            $command = $data_playing_chatters[$chat_id]['playing'] . "_$command";
        }
    }
    elseif(in_array($command,["flee", "extend", "force_start"])  and !empty($data_playing_chatters[$chat_id]['playing'])){
        $command = $data_playing_chatters[$chat_id]['playing'] . "_$command";
    }
    
    $gamefiles = scandir('galihjk/games/');
    foreach($gamefiles as $file){
        if(isDiakhiri($file,'_commands.php')){
            $game = str_replace('_commands.php','',$file);
            if(isDiawali($command,$game)) include("galihjk/games/$game".'_commands.php');
        }
    }
    /*
        // MAMIN COMMANDS ===
        if(substr($command,0,strlen("mamin")) == "mamin"){
            include('galihjk/games/mamin_commands.php');
        }

        // RPG COMMANDS ===
        elseif(substr($command,0,strlen("rpg")) == "rpg"){
            include('galihjk/games/rpg_commands.php');
        }

        //TTSS COMMANDS ===
        elseif(substr($command,0,strlen("ttss")) == "ttss"){
            include('galihjk/games/ttss_commands.php');
        }
    */
}

skip_to_end:
;