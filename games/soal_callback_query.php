<?php
$callback_query_data = $update["callback_query"]['data'];
if($callback_query_data == "soal_survey_tambah"){
    KirimPerintah('answerCallbackQuery',[
        'callback_query_id' => $update["callback_query"]['id'],
        'text'=> "soal_survey_tambah dipilih",
    ]);	
}
elseif($callback_query_data == "soal_survey_info"){
    KirimPerintah('answerCallbackQuery',[
        'callback_query_id' => $update["callback_query"]['id'],
        'text'=> "soal_survey_info dipilih",
    ]);	
}
elseif($callback_query_data == "soal_kuis_tambah"){
    KirimPerintah('answerCallbackQuery',[
        'callback_query_id' => $update["callback_query"]['id'],
        'text'=> "soal_kuis_tambah dipilih",
    ]);	
}
elseif($callback_query_data == "soal_kuis_info"){
    KirimPerintah('answerCallbackQuery',[
        'callback_query_id' => $update["callback_query"]['id'],
        'text'=> "soal_kuis_info dipilih",
    ]);	
}
else{

}