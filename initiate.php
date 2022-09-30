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
if(!isset($data['playing_chatters'])) $data['playing_chatters'] = [];
if(!isset($data['last_serve_time'])) $data['last_serve_time'] = 0;

if(!isset($data['last_active_user_time'])) $data['last_active_user_time'] = time();
if(abs(time()-$data['last_active_user_time']) >= 10 * 60){
    //reset active users every 10 minutes
    $data['last_active_user_time'] = time();
    $data['active_users'] = [];
}

//initiate for games
$gamefiles = scandir('galihjk/games/');
foreach($gamefiles as $file){
	if(isDiakhiri($file,'_init.php')){
		include("galihjk/games/$file");
	}
}