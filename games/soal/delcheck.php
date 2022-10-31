<?php
$data_soal = loadData("soal/$delcheck_jenis/$delcheck_id");
$channel_username = "@galihjksoal";
if(!empty($data_soal['soal'])){
    $delsc = $data_soal['delsc'] ?? 0;
    if($delsc > 0){
        $soal = $data_soal['soal'];
        KirimPerintah('sendMessage',[
            'chat_id' => $chat_id,
            'text'=> "Soal ini:\n===\n$soal\n===\ntelah disetujui untuk dihapus.",
            'parse_mode'=>'HTML',
        ]);
        $deletechannel = "@soal_hilang_galihjk";
        KirimPerintah('sendMessage',[
            'chat_id' => $deletechannel,
            'text'=> "$delcheck_jenis DELETED ".print_r($data_soal,true),
            'parse_mode'=>'HTML',
        ]);
        // KirimPerintah('forwardMessage',[
        //     'chat_id' => $deletechannel,
        //     'from_chat_id' => $channel_username,
        //     'disable_notification' => true,
        //     'protect_content' => true,
        //     'message_id' => $delcheck_id,
        // ]);
        $deleteMsg = KirimPerintah('deleteMessage',[
            'chat_id' => "@galihjksoal",
            'message_id' => $delcheck_id,
        ]);
        if(empty($deleteMsg['ok'])){
            KirimPerintah('editMessageText',[
                'chat_id' => "@galihjksoal",
                'text'=> "This message has been #deleted",
                'parse_mode'=>'HTML',
                'message_id' => $delcheck_id,
            ]);
        }
        deleteData("soal/$delcheck_jenis/$delcheck_id");
    }
    else{
        unset($data_soal['delsc']);
        unset($data_soal['delete']);
        unset($data_soal['alasan']);
        saveData("soal/$delcheck_jenis/$delcheck_id",$data_soal);
        KirimPerintah('sendMessage',[
            'chat_id' => $chat_id,
            'text'=> "<a href='t.me/galihjksoal/$delcheck_id'>Soal ini</a> TIDAK disetujui untuk dihapus.",
            'parse_mode'=>'HTML',
        ]);
    }
    KirimPerintah('deleteMessage',[
        'chat_id' => "@galihjksoal",
        'message_id' => $delcheck_msgid,
    ]);
}
