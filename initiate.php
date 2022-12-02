<?php
$id_developer = $config['id_developer'];
$botname = $config['bot_username'];
if(!isset($data['active_users'])) $data['active_users'] = [];
if(!isset($data['impersonate'])) $data['impersonate'] = [];
if(!isset($data['allowed_groups'])) $data['allowed_groups'] = [
    "-1001136500791" => 1,
    "-1001635551800" => 1,
    "-1001792092552" => 1,
];

if(!isset($data['last_serve_time'])) $data['last_serve_time'] = 0;

if(!isset($data['last_active_user_time'])) $data['last_active_user_time'] = time();
if(abs(time()-$data['last_active_user_time']) >= 10 * 60){
    //reset active users every 10 minutes
    $data['last_active_user_time'] = time();
    foreach(array_keys($data['active_users']) as $key_user){
        checkExpiredUnclaimeds($key_user);
    }
    checkExpiredUnclaimeds($from_id);
    $data['active_users'] = [];

    //sekalian update server status setiap 10 menit
    $avg="?";
    if(!empty($data['srvupdate']['lags'])){
        $avg = array_sum($data['srvupdate']['lags'])/count($data['srvupdate']['lags']);
    }
    KirimPerintah('editMessageText',[
        'chat_id' => '@galihjkdev',
        'text'=> "STATUS @galihjkbot: OK\n"
            ."lag=$avg\n"
            ."per ".date("Y-m-d H:i:s", strtotime('+7 hours'))."\n"
            ."<i>Server mungkin DOWN (mati/rusak) jika tidak ada update dalam 10 menit</i>\n"
        ,
        'parse_mode'=>'HTML',
        'message_id' => '10859',
    ]);
    $data['srvupdate'] = [
        'lags' => [],
    ];
}

//initiate for games
// $gamefiles = scandir('galihjk/games/');
// foreach($gamefiles as $file){
// 	if(isDiakhiri($file,'_init.php')){
// 		include("galihjk/games/$file");
// 	}
// }