<?php
$id_developer = $config['id_developer'];
$botname = $config['bot_username'];
if(!isset($data['playing_users'])) $data['playing_users'] = [];
if(!isset($data['impersonate'])) $data['impersonate'] = [];
if(!isset($data['allowed_groups'])) $data['allowed_groups'] = [
    "-1001136500791" => 1,
    "-1001635551800" => 1,
    "-1001792092552" => 1,
];
if(!isset($data['playing_chatters'])) $data['playing_chatters'] = [];
if(!isset($data['last_serve_time'])) $data['last_serve_time'] = 0;

//initiate for games
$gamefiles = scandir('galihjk/games/');
foreach($gamefiles as $file){
	if(isDiakhiri($file,'_init.php')){
		include("galihjk/games/$file");
	}
}