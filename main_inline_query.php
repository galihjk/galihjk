<?php
checkImpersonate($update['inline_query']['from']);

$inline_from_id = $update['inline_query']['from']['id'];


$results = [];

if(!empty($data['playing_users'][$inline_from_id]['playing']['chat_id'])){
    $chat_id = $data['playing_users'][$inline_from_id]['playing']['chat_id'];
    $checkfile = "games/".$data['playing_chatters'][$chat_id]['playing'].'_inline_query.php';
    if(!empty($data['playing_chatters'][$chat_id]['playing'])){
        if(file_exists($checkfile)) include ($checkfile);
    }
}
else{
    $results[] = [
        'type'=>'article',
        'id'=>count($results)+1,
        'title'=>"Kamu belum join game apapun!",
        'description'=>"Kalau sudah join, coba tulis sesuatu!",
        'thumb_url'=>'http://cdn.onlinewebfonts.com/svg/img_293158.png',
        'input_message_content'=>[
            'message_text'=>'Klik --> /join@'. $config['bot_username'],
        ]
    ];
    // if(empty($update['inline_query']['query'])){
    //     $results[] = [
    //         'type'=>'article',
    //         'id'=>count($results)+1,
    //         'title'=>"Ayo Tulis Sesuatu!",
    //         'description'=>"Tulis sesuatu setelah @".$config['bot_username']." , nanti muncul pilihan",
    //         'thumb_url'=>'https://d338t8kmirgyke.cloudfront.net/icons/icon_pngs/000/001/508/original/edit-text.png',
    //         'input_message_content'=>[
    //             'message_text'=>"Main yuk!\n"
    //                 .'Ayo /join@'. $config['bot_username'],
    //         ]
    //     ];
    // }
    // else{
    //     $results[] = [
    //         'type'=>'article',
    //         'id'=>count($results)+1,
    //         'title'=>"Main ".$update['inline_query']['query']." yuk!",
    //         'description'=>"Pilih ini untuk mengeajak teman bermain ".$update['inline_query']['query'],
    //         'thumb_url'=>'http://cdn.onlinewebfonts.com/svg/img_293158.png',
    //         'input_message_content'=>[
    //             'message_text'=>'Main '.$update['inline_query']['query']." yuk!\n"
    //                 .'Ayo /join@'. $config['bot_username'],
    //         ]
    //     ];
    // }
}
KirimPerintah('answerInlineQuery',[
    'inline_query_id'=>$update['inline_query']['id'],
    'results'=>json_encode($results),
    'cache_time'=>1
]);