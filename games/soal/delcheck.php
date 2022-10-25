<?php
$data_soal = loadData("soal/$delcheck_jenis/$delcheck_id");
KirimPerintah('sendMessage',[
    'chat_id' => $chat_id,
    'text'=> "DELETE: ".print_r($data_soal,true),
    'parse_mode'=>'HTML',
]);