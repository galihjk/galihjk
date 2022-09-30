<?php 
function server_stop(){
    $srvstatus['run_code'] = false;
    saveData("srvstatus",$srvstatus);
}

function server_start($check_already_running=false){
    global $token;
    global $id_developer;

    if($check_already_running){
        $srvstatus = loadData("srvstatus");
        if(!empty($srvstatus['run_code'])){
            if(!empty($srvstatus['time'])){
                if(abs(time() - $srvstatus['time']) <= 7){
                    //jika sudah aktif dalam 7 detik yang lalu, tidak perlu start ulang
                    return false;
                }
            }
        }
    }
    
    $run_code = md5(date("YmdHis").rand(0,99));
    $srvstatus['run_code'] = $run_code;
    bot_execute($token,'sendMessage',[
        'chat_id' => $id_developer,
        'text' => 'Server Started: '.$run_code,
    ]);
    saveData("srvstatus",$srvstatus);
    get_without_wait("https://galihjk.my.id/?runserver=f9c19a9ebb552c48c83fd79636039705&code=$run_code");
}

function get_without_wait($url)
{
	$ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_TIMEOUT, 1);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}