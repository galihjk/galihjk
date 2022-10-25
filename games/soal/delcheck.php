<?php
$data_soal = loadData("soal/$delcheck_jenis/$delcheck_id");
if(!empty($data_soal['soal'])){
    $delsc = $data_soal['delsc'] ?? 0;
    if($delsc > 0){
        $soal = $data_soal['soal'];
        KirimPerintah('sendMessage',[
            'chat_id' => $chat_id,
            'text'=> "Soal ini:\n===\n$soal\n===\ntelah disetujui untuk dihapus.",
            'parse_mode'=>'HTML',
        ]);
    }
    else{
        unset($data_soal['delsc']);
        unset($data_soal['delete']);
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
