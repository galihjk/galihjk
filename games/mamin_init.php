<?php

function mamin_kirim_soal($chat_id, $playdata, $soal_baru = true){
    global $emoji_square;
    global $emoji_square_check;

    $soal_no = $playdata['soal_no'];
    $players = $playdata['players'];
    $update_msgid = ($soal_baru ? false : $playdata['soal_msgid']);

    if($soal_baru){
        //ambil soal secara acak
        $soal_get = soal_get($playdata['soal_sudah']);
        if($soal_get == "habis!"){
            $habis = true;
        }
        //under.......

        //
        $data_soal = [
            'soal'=>"SOAL ". $soal_no
        ];
    }
    else{
        $data_soal = $playdata['data_soal'];
    }

    $text = "SOAL $soal_no/10\n\n";
    $text .= $data_soal['soal']."\n\n";

    $text .= ($playdata['soal_no'] <= 5 ? "Mode: Mayoritas 📈\n" : "Mode: Minoritas 📊\n");
    $text .= "Sisa Waktu: ".max(0, round($playdata['waktu_habis_sisa']))." detik\n";
    $text .= "\nYang lain boleh /join\n";
    foreach($players as $k=>$v){
        if(empty($v['jawab']) or $soal_baru){
            $text .= "<a href='tg://user?id=$k'>$emoji_square</a> ";
        }
        else{
            $text .= "<a href='tg://user?id=$k'>$emoji_square_check</a> ";
        }        
    }
    $reply_markup = inlineKeyBoard([
        ['JAWAB', 'switch_inline_query_current_chat'=>''],
    ]);
    if($update_msgid){
        $botresult = KirimPerintah('editMessageText',[
            'chat_id' => $chat_id,
            'text'=> $text,
            'parse_mode'=>'HTML',
            'message_id' => $update_msgid,
            'reply_markup' => $reply_markup,
            'disable_web_page_preview' => true,
        ]);
    }
    else{
        $botresult = KirimPerintah('sendMessage',[
            'chat_id' => $chat_id,
            'text'=> $text,
            'parse_mode'=>'HTML',
            'reply_markup' => $reply_markup,
            'disable_web_page_preview' => true,
        ]);
    }
    return [
        'data_soal'=>$data_soal,
        'botresult'=>$botresult,
    ];
}
