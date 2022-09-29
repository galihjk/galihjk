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

//games
else{
    $gamefiles = scandir('games/');
    foreach($gamefiles as $file){
        if(isDiakhiri($file,'_callback_query.php')){
            $game = str_replace('_callback_query.php','',$file);
            if(isDiawali($update["callback_query"]['data'],$game."_"))
                include("games/$game".'_callback_query.php')
            ;
        }
    }
}



/*
    //TTSS CALLBACK QUERY ==
    elseif(isset($update["callback_query"]) and 
    substr($update["callback_query"]['data'],0,strlen("tts_")) == "tts_"){
        include('ttss_callback_query.php');
    }
*/


