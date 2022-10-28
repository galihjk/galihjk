<?php
$data_soal = loadData("soal/$editcheck_jenis/$editcheck_id");
$channel_username = "@galihjksoal";
if(!empty($data_soal['soal'])){
    $editsc = $data_soal['editsc'] ?? 0;
    if($editsc > 0){
        $soal = $data_soal['soal'];
        KirimPerintah('sendMessage',[
            'chat_id' => $chat_id,
            'text'=> "Soal ini:\n===\n$soal\n===\ntelah disetujui untuk diedit.",
            'parse_mode'=>'HTML',
        ]);
        $editetechannel = "@soal_hilang_galihjk";
        KirimPerintah('sendMessage',[
            'chat_id' => $editetechannel,
            'text'=> "$editcheck_jenis edited ".print_r($data_soal,true),
            'parse_mode'=>'HTML',
        ]);
        // KirimPerintah('forwardMessage',[
        //     'chat_id' => $editetechannel,
        //     'from_chat_id' => $channel_username,
        //     'disable_notification' => true,
        //     'protect_content' => true,
        //     'message_id' => $editcheck_id,
        // ]);
        // editeteData("soal/$editcheck_jenis/$editcheck_id");
        // $editeteMsg = KirimPerintah('deleteMessage',[
        //     'chat_id' => "@galihjksoal",
        //     'message_id' => $editcheck_id,
        // ]);
        // if(empty($deleteMsg[['ok']])){
            KirimPerintah('sendMessage',[
                'chat_id' => "@galihjksoal",
                'text'=> "SOAL INI PERLU DIEDIT JADI ".$data_soal['edit'],
                'reply_to_message_id' =>$editcheck_id,
                'parse_mode'=>'HTML',
            ]);
        // }
    }
    else{
        unset($data_soal['editsc']);
        unset($data_soal['edit']);
        saveData("soal/$editcheck_jenis/$editcheck_id",$data_soal);
        KirimPerintah('sendMessage',[
            'chat_id' => $chat_id,
            'text'=> "<a href='t.me/galihjksoal/$editcheck_id'>Soal ini</a> TIDAK disetujui untuk diedit.",
            'parse_mode'=>'HTML',
        ]);
    }
    KirimPerintah('deleteMessage',[
        'chat_id' => "@galihjksoal",
        'message_id' => $editcheck_msgid,
    ]);
}
