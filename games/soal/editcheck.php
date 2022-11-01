<?php
$data_soal = loadData("soal/$editcheck_jenis/$editcheck_id");
$channel_username = "@galihjksoal";
if(!empty($data_soal['soal'])){
    $editsc = $data_soal['editsc'] ?? 0;
    if($editsc > 0){
        $soal = $data_soal['soal'];
        KirimPerintah('sendMessage',[
            'chat_id' => $chat_id,
            'text'=> "<a href='t.me/galihjksoal/$editcheck_id'>Soal ini</a>\n===\n$soal\n===\ntelah disetujui untuk diedit.",
            'parse_mode'=>'HTML',
        ]);
        $editetechannel = "@soal_hilang_galihjk";
        KirimPerintah('sendMessage',[
            'chat_id' => $editetechannel,
            'text'=> "$editcheck_jenis $editcheck_id edited ".print_r($data_soal,true),
            'parse_mode'=>'HTML',
        ]);
        $data_soal['soal'] = $data_soal['edit'];
        $creator = "";
        $editor = [$data_soal['editby']=>0];
        foreach($data_soal['vote'] as $k=>$v){
            if($creator == ""){
                $creator = [$k=>$v];
                unset($data_soal['vote'][$k]);
            }
            if($k == $data_soal['editby']){
                $editor = [$k=>$v];
                unset($data_soal['vote'][$k]);
            }
        }
        $data_soal['vote'] = $creator + $editor + $data_soal['vote'];
        unset($data_soal['editby']);
        unset($data_soal['editsc']);
        unset($data_soal['edit']);
        unset($data_soal['editvote']);
        saveData("soal/$editcheck_jenis/$editcheck_id",$data_soal);
        updateSoalPost($editcheck_id,$editcheck_jenis,$data_soal);
    }
    else{
        unset($data_soal['editby']);
        unset($data_soal['editsc']);
        unset($data_soal['edit']);
        unset($data_soal['editvote']);
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
