<?php
$message_data = $update["message"];

checkImpersonate($message_data["from"]);

$chat_id = (string) $message_data["chat"]["id"];

//when group iddle (not playing game) =======
if(isDiawali($chat_id,"-") and empty($data['playing_chatters'][$chat_id])){
    // check chat data every 10 minutes
    $setChatData = $message_data["chat"];
    $setChatData['active'] = true;
    setChatData($chat_id,$setChatData,true,10*60);

    //check group chat idle time
    $chat_data = getChatData($chat_id);
    $chat_active = $chat_data['active'] ?? false;
    if(!$chat_active){
        setChatData($chat_id,[
            'active'=>true,
            'idle_notif'=>false,
            'idle_leavetime'=>time(),
        ]);
    }
    $chat_last_play = $chat_data['last_play'] ?? 0;
    $chat_idle_leavetime = $chat_data['idle_leavetime'] ?? time();
    $chat_idle_timeleft = $chat_idle_leavetime - time();
    $chat_idle_notif = getChatData($chat_id,'idle_notif',false);
    $since_last_play = time() - $chat_last_play;
    $bonus_time = max(0,(5*60)-$since_last_play); //bonus time up to 5 minutes after last play

    // KirimPerintah('sendMessage',[
    //     'chat_id' => $config['id_developer'],
    //     'text'=> "nih".print_r([
    //         $chat_last_play,
    //         $chat_idle_leavetime,
    //         $chat_idle_timeleft,
    //         $chat_idle_notif,
    //         $since_last_play,
    //         $bonus_time,
    //         time(),
    //     ],true),
    //     'parse_mode'=>'HTML',
    // ]);

    if(!$chat_idle_notif){
        $add_idle_time_left = 30;
        $leave_timeleft = $chat_idle_timeleft + $bonus_time + $add_idle_time_left;
        setChatData($chat_id,[
            'idle_notif'=>true,
            'idle_leavetime'=>time() + $leave_timeleft,
        ]);
        KirimPerintah('sendMessage',[
            'chat_id' => $chat_id,
            'text'=> "<i>info:</i>\nSaya akan otomatis leave group dalam ".timeToSimpleText($leave_timeleft)." jika tidak ada yang main, karena BOT ini sedang dalam pengembangan. \nYuk /play",
            'parse_mode'=>'HTML',
        ]);
    }
    elseif($chat_idle_timeleft + $bonus_time <= 0){
        $admins = KirimPerintah('getChatAdministrators',[
            'chat_id' => $chat_id,
        ]);
        $text = "Wahai admin";
        foreach($admins['result'] as $item){
            $text .= "<a href='tg://user?id=".$item['user']['id']."'>.</a>";
        }
        $text .= print_r($admins['result'],true);
        $text .= "\nSaya izin left yaa,, kalau mau main lagi, nanti tambahkan lagi aja saya ke grup ini, hehe.. Terima Kasiiih.. :D";
        setChatData($chat_id, [
            'active'=>false,
            'idle_notif'=>false,
        ], false);
        KirimPerintah('sendMessage',[
            'chat_id' => $chat_id,
            'text'=> $text,
            'parse_mode'=>'HTML',
        ]);
        KirimPerintah('leaveChat',[
            'chat_id' => $chat_id,
        ]);
        goto skip_to_end;
    }
}


$message_id = $message_data["message_id"];
// KirimPerintah('sendMessage',[
//     'chat_id' => $chat_id,
//     'text'=> "UNDER CONSTRUCTION\n\n" ,
//     'parse_mode'=>'HTML',
//     'reply_to_message_id' => $message_id
// ]);

$from_id = $message_data["from"]["id"];
$from_username = $message_data["from"]["username"];
$first_name = $message_data["from"]["first_name"];
if(isset($message_data["from"]["last_name"])){
    $last_name = " " . $message_data["from"]["last_name"];
}else{
    $last_name = "";
}
$from_name = $first_name . $last_name;
$from_name = htmlspecialchars($from_name);

$text = $message_data["text"];

$command = "";
$command_after = "";

if(substr($text,0,1) == "/"){
    $command = substr(strtolower(trim(explode(" ",explode("@$botname",$text)[0])[0])),1);
    $command_after = trim(str_ireplace("/$command","",str_ireplace("/$command@$botname","",$text)));
}

if($command == "start" and !isDiawali($chat_id,"-") and !empty($command_after) and isDiawali($command_after,"cmd_")){
    $command = substr($command_after,4);
}

/*if(
//$command != "weblogin" and 
$from_id != $id_developer){
    //underconst.....
    KirimPerintah('sendMessage',[
        'chat_id' => $chat_id,
        'text'=> "UNDER CONSTRUCTION\n\n Mohon maaf, saat ini bot dan web galihjk tidak bisa dipakai,, entah sampai kapan :(",
        'parse_mode'=>'HTML',
        'reply_to_message_id' => $message_id
    ]);
    continue;
}*/

//=======================================
//check user
if(!empty($from_id)){
    if(empty(getUser($from_id))){
        setUser($from_id, $message_data["from"]);
    }
}

//developer commands 
include("galihjk/developer_commands.php");

//main commands
include("galihjk/main_commands.php");

//message updates for active games====
//TTSS MESSAGE UPDATE
// if(!empty($data['playing_chatters'][$chat_id]['playing']) and $data['playing_chatters'][$chat_id]['playing'] == "ttss"
// ){
//     include('galihjk/ttss_msg_update.php');
// }
//====================================

skip_to_end:

;