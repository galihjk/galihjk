<?php
$message_data = $update["message"];

checkImpersonate($message_data["from"]);

$chat_id = (string) $message_data["chat"]["id"];

//when group iddle (not playing game) =======
if(isDiawali($chat_id,"-") and empty($data['playing_chatters'][$chat_id])){
    //check group chat leave time
    $chat_data = getChatData($chat_id);
    $chat_leavetime = $chat_data['leavetime'] ?? time();
    $chat_timeleft = $chat_leavetime - time();
    $chat_active = $chat_data['active'] ?? false;
    if(!$chat_active){
        if($chat_timeleft < 10 * 60){
            // bonus 10 menit untuk grup baru
            $leavetime = time() + 10*60;
            $leavetime = time() + 10;//test
        }
        else{
            $leavetime = $chat_leavetime;
        }
        KirimPerintah('sendMessage',[
            'chat_id' => $chat_id,
            'text'=> "<i>info:</i>\nSaya akan otomatis leave group dalam ".timeToSimpleText($leavetime - time()).", karena BOT ini sedang dalam pengembangan. \nYuk /play",
            'parse_mode'=>'HTML',
        ]);
        setChatData($chat_id,[
            'active'=>true,
            'leavetime'=>$leavetime, 
        ],false);
    }
    elseif($chat_timeleft <= 0){
        $admins = KirimPerintah('getChatAdministrators',[
            'chat_id' => $chat_id,
        ]);
        $text = "Wahai admin";
        foreach($admins['result'] as $item){
            $text .= "<a href='tg://user?id=".$item['user']['id']."'>.</a>";
        }
        $text .= "\nSudah waktu nya saya pamit undur diri.. Saya izin left yaa,, kalau mau main lagi, nanti tambahkan lagi aja saya ke grup ini, hehe.. Terima Kasiiih.. :D";
        setChatData($chat_id, [
            'active'=>false,
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

// update chat data every 10 minutes
$setChatData = $message_data["chat"];
setChatData($chat_id,$setChatData,true,10*60);

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