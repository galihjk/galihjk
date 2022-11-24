<?php
if(empty($_GET['runserver']) or $_GET['runserver'] !== "f9c19a9ebb552c48c83fd79636039705"){
    die("wrong access");
}

//helpers autoload
$scandir = scandir('galihjk/helpers/');
foreach($scandir as $file){
	if(substr($file,-4) == '.php'){
		include("galihjk/helpers/$file");
	}
}

$token = $config['bot_token'];
$id_developer = $config['id_developer'];

sleep(rand(1,2));
$srvstatus = loadData("srvstatus");
if(!empty($srvstatus['run_code'])){
	$run_code = $srvstatus['run_code'];
	if($_GET['code'] == $run_code){
		$srvstatus = loadData("srvstatus");
		$srvstatus['time'] = time();
		saveData("srvstatus",$srvstatus);
		file_get_contents("https://galihjk.my.id/?bot=9fb4d360c8df1a9d3d829e17ac3275b4&serve=1");
		get_without_wait("https://galihjk.my.id/?runserver=f9c19a9ebb552c48c83fd79636039705&code=$run_code");
	}
	else{
		KirimPerintah('sendMessage',[
			'chat_id' => '227024160',
			'text' => 'Server '.$_GET['code'].' has been terminated. Changed to: '.$run_code,
		]);
	}
}
else{
	$botresult = KirimPerintah('editMessageText',[
        'chat_id' => '@galihjkdev',
        'text'=> "STATUS @galihjkbot: OFF🔴\Stopped: ".date("Y-m-d H:i:s") 
			."\n<a href='https://galihjk.my.id/?web_run_action=srv_start'>START</a>" ,
        'parse_mode'=>'HTML',
        'message_id' => '10859',
    ]);
	// KirimPerintah('sendMessage',[
	// 	'chat_id' => '227024160',
	// 	'text' => 'Server stopped.',
	// ]);
}