<?php 
function server_stop(){
    $srvstatus['run_code'] = false;
    saveData("srvstatus",$srvstatus);
}

function server_start($check_already_running=false, $drop_pending = true){
    global $token;
    global $id_developer;

    if($drop_pending){
        $update_id = 0;
        $updates = DapatkanUpdate($update_id, $token);
        $maxloop = 1000;
        while(count($updates) >= 50){
            $maxloop --;
            $update_id = 1+end($updates)["update_id"];
            $updates = DapatkanUpdate($update_id, $token);
            if($maxloop < 1) break;
        }
    }

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
    // KirimPerintah('sendMessage',[
    //     'chat_id' => $id_developer,
    //     'text' => 'Server Started: '.$run_code . " \nSTOP: https://galihjk.my.id/?web_run_action=srv_stop",
    //     'disable_web_page_preview' => true,
	// ]);
    $botresult = KirimPerintah('editMessageText',[
        'chat_id' => '@galihjkdev',
        'text'=> "STATUS @galihjkbot: ON🟢\nStarted at: ".date("Y-m-d H:i:s"),
        'parse_mode'=>'HTML',
        'message_id' => '10859',
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

function create_job($php, $time){
    file_put_contents("galihjk/jobs/$time.php", "<?php $php");
}