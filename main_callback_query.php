<?php 
//underconstruction
if($update["callback_query"]['data'] == "underconstruction"){
    KirimPerintah('answerCallbackQuery',[
        'callback_query_id' => $update["callback_query"]['id'],
        'text'=> "Underconstruction",
        'show_alert'=>true,
    ]);	
}

//NO-BUTTON CALLBACK QUERIES
elseif(isset($update["callback_query"]) and $update["callback_query"]['data'] == "~~"){
    $callback_query = $update["callback_query"];
    KirimPerintah('answerCallbackQuery',[
        'callback_query_id' => $callback_query['id'],
        'text'=> "This button does nothing!",
    ]);
}

//test
elseif(isset($update["callback_query"]) and $update["callback_query"]['data'] == "PilihDelapan"){
    KirimPerintah('answerCallbackQuery',[
        'callback_query_id' => $update["callback_query"]['id'],
        'text'=> "menu 'Delapan' telah dipilih",
    ]);	
}

//game
else{
    $explode = explode("_",$update["callback_query"]['data']);
    if(count($explode) > 1){
        $game = $explode[0];
        if(file_exists("galihjk/games/$game".'_callback_query.php')){
            include("galihjk/games/$game".'_callback_query.php');
        }
    }
    
}



/*
    //TTSS CALLBACK QUERY ==
    elseif(isset($update["callback_query"]) and 
    substr($update["callback_query"]['data'],0,strlen("tts_")) == "tts_"){
        include('galihjk/ttss_callback_query.php');
    }
*/


