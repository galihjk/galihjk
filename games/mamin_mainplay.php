<?php
echo "<pre style=color:red>";
print_r($playdata);
echo "</pre>";
if($playdata['step'] == 'starting'){
    if($playdata['starting_timeleft'] <= 0){
        $data['change_step'][] = ['mamin', $chat_id, 'starting_check'];
    }
    else{
        $data['playing_chatters'][$chat_id]['mamin']['starting_timeleft'] -= $jeda;
        if(
            ($data['playing_chatters'][$chat_id]['mamin']['starting_timeleft'] <= 55
            and $data['playing_chatters'][$chat_id]['mamin']['remind_join'] == 0)
            or
            ($data['playing_chatters'][$chat_id]['mamin']['starting_timeleft'] <= 40
            and $data['playing_chatters'][$chat_id]['mamin']['remind_join'] == 1)
            or
            ($data['playing_chatters'][$chat_id]['mamin']['starting_timeleft'] <= 25
            and $data['playing_chatters'][$chat_id]['mamin']['remind_join'] == 2)
            or
            ($data['playing_chatters'][$chat_id]['mamin']['starting_timeleft'] <= 10
            and $data['playing_chatters'][$chat_id]['mamin']['remind_join'] == 3)
        ){
            $data['playing_chatters'][$chat_id]['mamin']['remind_join'] += 1;
            KirimPerintah('sendMessage',[
                'chat_id' => $chat_id,
                'text'=> "Permainan akan dimulai ".ceil($playdata['starting_timeleft'])." detik lagi\n"
                    ."/join - ikutan\n"
                    ."/extend - perpanjang jadi 120\n"
                    ."/force_start - mulai sekarang!"
                ,
                'parse_mode'=>'HTML',
            ]);
        }
    }
}
elseif($playdata['step'] == 'starting_check'){
    $data['playing_chatters'][$chat_id]['mamin']['player_change'] = false;
    $text = "Pemain:\n";
    foreach($playdata['players'] as $k=>$v){
        $text .= "- ".namaLengkap($k)."\n";
    }
    $jml_pemain = count($playdata['players']);
    $syarat_terpenuhi = false;
    $text .= "\nSyarat:";
    $text .= "\nJml Pemain Minimal: 3";
    if($jml_pemain >= 3){
        $syarat_terpenuhi = true;
        $text .= "$emoji_check ($jml_pemain)";
    }
    else{
        $text .= "$emoji_cross ($jml_pemain)";
    }
    if($playdata['starting_timeleft'] <= 0){
        $data['change_step'][] = ['mamin', $chat_id, 'waiting'];
        if($syarat_terpenuhi){
            $text .= "\n\nWaktu tunggu habis";
            $data['change_step'][] = ['mamin', $chat_id, 'starting_start', time()+5,];
        }
        else{
            $text .= "\n\n<b>SYARAT TIDAK TERPENUHI!</b>";
            $data['change_step'][] = ['mamin', $chat_id, 'game_end', time()+3];
            $data['delayedPerintah'][] = ['sendMessage',
                [
                    'chat_id' => $chat_id,
                    'text'=> "Karena syarat tidak terpenuhi, permainan akan dibatalkan. Ayo ajak temanmu!",
                    'parse_mode'=>'HTML',
                ], time()+1
            ];
        }
        KirimPerintah('sendMessage',[
            'chat_id' => $chat_id,
            'text'=> $text,
            'parse_mode'=>'HTML',
            'reply_to_message_id'=> $playdata['startmsgid'],
        ]);
    }
    else{
        if($playdata['player_change']){
            $text .= "\n\nAYO IKUTAN! --> /join";
            $text .= "\nsisa waktu: ".ceil($playdata['starting_timeleft'])." detik";
            $text .= "\n/extend - perpanjang jadi 120";
            $text .= "\n/force_start - mulai sekarang!";
            $last_check = KirimPerintah('sendMessage',[
                'chat_id' => $chat_id,
                'text'=> $text,
                'parse_mode'=>'HTML',
                'reply_to_message_id'=> $playdata['startmsgid'],
            ]);
            if(!empty($playdata['last_check_msgid'])){
                KirimPerintah('deleteMessage',[
                    'chat_id' => $chat_id,
                    'message_id'=> $playdata['last_check_msgid'],
                ]);
            }
            $last_msgid = $last_check['result']['message_id'];
            $data['playing_chatters'][$chat_id]['mamin']['last_check_msgid'] = $last_msgid;
        }
        $data['change_step'][] = ['mamin', $chat_id, 'starting'];
        if($playdata['starting_timeleft'] > 10){
            $data['change_step'][] = ['mamin', $chat_id, 'starting_check', time()+10,];
        }
    }
}
elseif($playdata['step'] == 'starting_start'){
    $data['change_step'][] = ['mamin', $chat_id, 'waiting'];
    $text = "Permainan akan segera dimulai,,\n\nBERSIAPLAH";
    foreach($playdata['players'] as $k=>$v){
        $text .= "<a href='tg://user?id=$k'>!</a>";
    }
    $text .= "\n\nSoal 1-5: Mode Mayoritas 📈";
    $text .= "\nSoal 6-10: Mode Minoritas 📊";
    $text .= "\nWaktu Maks: 30 detik";
    KirimPerintah('sendMessage',[
        'chat_id' => $chat_id,
        'text'=> $text,
        'parse_mode'=>'HTML',
    ]);
    $data['delayedPerintah'][] = ['sendMessage',
        [
            'chat_id' => $chat_id,
            'text'=> "== MODE MAYORITAS 📈 ==\n"
                ."Semakin banyak yang sama, semakin besar skor nya!\n"
                ."*<i>skor = jml pemain lain yg jawabannya sama dikali 10</i>\n"
                ."*<i>bonus skor +5 jika jawabannya valid</i>",
            'parse_mode'=>'HTML',
        ], time()+5
    ];
    $data['playing_chatters'][$chat_id]['mamin']['soal_no'] = 1;
    $data['change_step'][] = ['mamin', $chat_id, 'kirim_soal', time()+10];
}
elseif($playdata['step'] == 'kirim_soal'){
    //check player count
    $playercount = count($playdata['players']);
    if($playercount < 3){
        KirimPerintah('sendMessage',[
            'chat_id' => $chat_id,
            'text'=> "Permainan dihentikan karena jumlah pemain kurang dari tiga",
            'parse_mode'=>'HTML',
            'reply_to_message_id'=> $playdata['soal_msgid'],
        ]);
        $data['change_step'][] = ['mamin', $chat_id, 'game_end'];
    }
    else{
        $data['change_step'][] = ['mamin', $chat_id, 'receive_inline'];
        $data['playing_chatters'][$chat_id]['mamin']['waktu_habis_sisa'] = 30;
        $data['playing_chatters'][$chat_id]['mamin']['waktu_check_sisa'] = rand(25,35);
        $data['playing_chatters'][$chat_id]['mamin']['soal_msgid'] = "";
    
        foreach($data['playing_chatters'][$chat_id]['mamin']['players'] as $player_id=>$player_data){
            $data['playing_chatters'][$chat_id]['mamin']['players'][$player_id]['jawab'] = "";
        }
    
        $mamin_kirim_soal = mamin_kirim_soal($chat_id,$data['playing_chatters'][$chat_id]['mamin']);
        $data['playing_chatters'][$chat_id]['mamin']['soal_msgid'] = $mamin_kirim_soal['botresult']['result']['message_id'];
        // $data['playing_chatters'][$chat_id]['mamin']['soal_sudah'][] = $mamin_kirim_soal['data_soal']['id'];
        soal_setSudah($chat_id, $mamin_kirim_soal['data_soal']['id'],'survey');
        $data['playing_chatters'][$chat_id]['mamin']['data_soal'] = $mamin_kirim_soal['data_soal'];
    }
}
elseif($playdata['step'] == 'receive_inline'){
    if($playdata['waktu_habis_sisa'] > 0){
        $data['playing_chatters'][$chat_id]['mamin']['waktu_habis_sisa'] -= $jeda;
        if($playdata['waktu_habis_sisa']<= $playdata['waktu_check_sisa']
        and $playdata['waktu_check_sisa'] >= 10){
            $data['playing_chatters'][$chat_id]['mamin']['waktu_check_sisa'] -= rand(15,30);
            $data['change_step'][] = ['mamin', $chat_id, 'cek_waktu_habis'];
        }
    }
    else{
        $data['change_step'][] = ['mamin', $chat_id, 'cek_waktu_habis'];
    }
}
elseif($playdata['step'] == 'cek_waktu_habis'){
    mamin_kirim_soal($chat_id,$playdata,false);
    if($playdata['waktu_habis_sisa'] > 0){
        $data['playing_chatters'][$chat_id]['mamin']['step'] = "receive_inline";
        $text = "Sisa waktu: ". ceil($playdata['waktu_habis_sisa']) . " detik\n";
        foreach($playdata['players'] as $k=>$v){
            if(empty($v['jawab'])){
                $text .= "<a href='tg://user?id=$k'>$emoji_square</a>";
            }
            else{
                $text .= $emoji_square_check;
            }
        }
        $last_check = KirimPerintah('sendMessage',[
            'chat_id' => $chat_id,
            'text'=> $text,
            'parse_mode'=>'HTML',
            'reply_to_message_id'=> $playdata['soal_msgid']
        ]);
    }
    else{
        $data['change_step'][] = ['mamin', $chat_id, 'check_jawaban'];
        $text = "Waktu Habis!\n";
        $tidak_jawab = [];
        foreach($playdata['players'] as $k=>$v){
            if(empty($v['jawab'])){
                $tidak_jawab[] = mentionUser($k);
            }
        }
        if(!empty($tidak_jawab)){
            $text .= implode(", ",$tidak_jawab) . " tidak menjawab.\n";
            $text .= "<i>*Kalau tidak mau ikut main, gunakan command </i><pre>/flee</pre>";
            KirimPerintah('sendMessage',[
                'chat_id' => $chat_id,
                'text'=> $text,
                'parse_mode'=>'HTML',
            ]);
        }
        
    }
}
elseif($playdata['step'] == 'check_jawaban'){
    $data['change_step'][] = ['mamin', $chat_id, 'waiting'];

    $jawabans = [];
    $tidak_jawab = [];
    foreach($playdata['players'] as $player_id=>$player_data){
        if(!empty($player_data['jawab'])){
            $jawabans[$player_id] = $player_data['jawab'];
        }
        else{
            $tidak_jawab[] = $player_id;
        }
    }
    $tidak_valid = [];
    $array_count_values = array_count_values($jawabans);
    arsort($array_count_values);
    $jawaban_valid = json_decode($playdata['data_soal']['jawaban'],true);
    $text = "JAWABAN PEMAIN:\n\n";
    if($playdata['soal_no'] <= 5){
        //mayo score
        foreach($array_count_values as $jawaban=>$skorcnt){
            $skornya = ($skorcnt-1)*10;
            $skor_mayo = "(+$skornya)";
            $skor_valid = "";
            if(isset($jawaban_valid[$jawaban])){
                $skor_valid = "(+5)";
                $skornya += 5;
            }
            $text .= "- <b>$jawaban</b> $skor_mayo $skor_valid:\n";
            foreach($jawabans as $player_id=>$jawaban_pemain){
                if($jawaban_pemain == $jawaban){
                    $text .= " -- " . mentionUser($player_id) . "\n";
                    $data['playing_chatters'][$chat_id]['mamin']['players'][$player_id]['score'] += $skornya;
                }
            }
            $text .= "\n";
        }        
    }
    else{
        //mino score
        asort($array_count_values);
        $total_player = count($playdata['players']);
        foreach($array_count_values as $jawaban=>$skorcnt){
            if($skorcnt == 1 and !isset($jawaban_valid[$jawaban])){
                foreach($jawabans as $player_id=>$jawaban_pemain){
                    if($jawaban_pemain == $jawaban){
                        $tidak_valid[$player_id] = $jawaban;
                    }
                }
            }
            else{
                $skornya = round(10*$total_player/$skorcnt)-5;
                $text .= "- <b>$jawaban</b> (+$skornya):\n";
                foreach($jawabans as $player_id=>$jawaban_pemain){
                    if($jawaban_pemain == $jawaban){
                        $text .= " -- " . mentionUser($player_id) . "\n";
                        $data['playing_chatters'][$chat_id]['mamin']['players'][$player_id]['score'] += $skornya;
                    }
                }
                $text .= "\n";
            }
        }
        if(!empty($tidak_valid)){
            $text .= "- <b>*TIDAK VALID</b> (+0):\n";
            foreach($tidak_valid as $player_id=>$jawaban_pemain){
                $text .= " -- " . mentionUser($player_id) . " : $jawaban_pemain\n";
            }
            $text .= "<i>*Untuk menambah jawaban valid, buka link soal lalu pilih tombol jawab.</i>\n\n";
        }
    }
    
    if(!empty($tidak_jawab)){
        $text .= "- <b>Tidak Menjawab</b> (-5):\n";
        foreach($tidak_jawab as $player_id_tidak_jawab){
            $text .= " -- " . mentionUser($player_id_tidak_jawab) . "\n";
            $data['playing_chatters'][$chat_id]['mamin']['players'][$player_id_tidak_jawab]['score'] -= 5;
        }
        $text .= "\n";
    }

    $data['playing_chatters'][$chat_id]['mamin']['soal_no'] ++;

    if(!empty($data['playing_chatters'][$chat_id]['mamin']['next_join_player'])){
        $next_join_player = array_keys($data['playing_chatters'][$chat_id]['mamin']['next_join_player']);
        $mention_new_join = [];
        foreach($next_join_player as $v){
            if(empty($data['playing_chatters'][$chat_id]['mamin']['players'][$v])){
                $data['playing_chatters'][$chat_id]['mamin']['players'][$v] = ['score'=>0];
            }
            $mention_new_join[] = mentionUser($v);
        }
        $text .= "Ada pemain yang bergabung nih: " . implode(", ", $mention_new_join) . "\n";
        unset($data['playing_chatters'][$chat_id]['mamin']['next_join_player']);
    }

    KirimPerintah('sendMessage',[
        'chat_id' => $chat_id,
        'text'=> $text,
        'parse_mode'=>'HTML',
        'reply_to_message_id'=> $playdata['soal_msgid'],
    ]);

    if($playdata['soal_no'] >= 10){
        $data['change_step'][] = ['mamin', $chat_id, 'game_end', time()+5];
    }
    elseif($playdata['soal_no'] == 5){
        $data['delayedPerintah'][] = ['sendMessage',
            [
                'chat_id' => $chat_id,
                'text'=> "== MODE MINORITAS 📊 ==\n"
                    ."Semakin beda jawabanmu, semakin besar skor nya!\n"
                    ."*<i>Jika jawaban valid, skor = jml pemain dibagi jml jawabanmu dan yg sama dgn mu, dikali 10, kemudian dikurangi 5.\n"
                    ."Jika tidak valid, skor nya nol.</i>",
                'parse_mode'=>'HTML',
            ], time()+4
        ];
        $data['change_step'][] = ['mamin', $chat_id, 'kirim_soal', time()+8];
    }
    else{
        $data['change_step'][] = ['mamin', $chat_id, 'kirim_soal', time()+5];
    }

    
}
elseif($playdata['step'] == 'game_end'){
    $text = "Permainan Berakhir!\n";
    $scores = [];
    foreach($playdata['players'] as $k=>$v){
        $scores[$k] = (empty($v['score']) ? 0 : $v['score']);
    }
    arsort($scores);
    $rank = 1;
    $skorsblmny = "";
    foreach($scores as $k=>$v){
        if($v == $skorsblmny){
            $rank--;
        }
        $text .= $rank . ". " . mentionUser($k) . " : $v\n";
        setUserWinRate($k, $rank, count($playdata['players']));
        $points[$k] = unsetUserPlaying($k);
        $rank++;
        $skorsblmny = $v;
    }
    KirimPerintah('sendMessage',[
        'chat_id' => $chat_id,
        'text'=> $text,
        'parse_mode'=>'HTML',
    ]);
    $ada_poin_klaim = false;
    $text = "Poin yang dapat diklaim:\n";
    foreach ($points as $k=>$v){
        if(!empty($v['calculate_result'])){
            $ada_poin_klaim = true;
            $text .= mentionUser($k) . " : ".$v['calculate_result']."\n";
        }
    }
    if($ada_poin_klaim){
        $text .= "\nKlik untuk ambil --> <a href='t.me/".$config['bot_username']."?start=cmd_claim'>[CLAIM]</a>\n<i>*kadaluarsa dalam 24 jam</i>";
        $text .= "\n\n/mamin_play - main lagi";
        $text .= "\n/play - main yang lain";
        $data['delayedPerintah'][] = ['sendMessage',
            [
                'chat_id' => $chat_id,
                'text'=> $text,
                'parse_mode'=>'HTML',
                'disable_web_page_preview' => true,
            ], time()+2
        ];        
    }
    stopPlayingGame($chat_id);
}