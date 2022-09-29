<?php
$message_data = $update["message"];

checkImpersonate($message_data["from"]);

$chat_id = (string) $message_data["chat"]["id"];

saveData("chat/$chat_id",$message_data["chat"]);

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
    if(empty($data['playing_users'][$from_id])){
        saveData("user/$from_id", $message_data["from"]);
        $data['playing_users'][$from_id] = $message_data["from"];
    }
    $data['playing_users'][$from_id]['last_active'] = time();
}

//developer commands 
include("developer_commands.php");

//main commands
include("main_commands.php");

//message updates for active games====
//TTSS MESSAGE UPDATE
// if(!empty($data['playing_chatters'][$chat_id]['playing']) and $data['playing_chatters'][$chat_id]['playing'] == "ttss"
// ){
//     include('ttss_msg_update.php');
// }
//====================================