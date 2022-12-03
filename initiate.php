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
            ."per ".date("Y-m-d H:i:s")."\n"
            ."<i>Server mungkin DOWN (mati/rusak) jika tidak ada update dalam 10 menit</i>\n"
        ,
        'parse_mode'=>'HTML',
        'message_id' => '10859',
    ]);
    $data['srvupdate'] = [
        'lags' => [],
    ];

    //tes main MAMIN setiap jam 4 sampai 5 sore
    if(empty($data['playing_chatters']['-1001635551800'])
        and(
            date("H") == "16" or
            date("H") == "06" or
            date("H") == "13"
        )
    ){
        $result = KirimPerintah('sendAnimation',[
            'chat_id' =>'-1001635551800',
            'animation' => 'CgACAgUAAxkBAALHa2N-3z1MSV2MenFwfOhCwXTOLQUNAALfBgACxrD5V8vgk_f6z8n4KwQ',
            'caption' => "Kuis Mayo Mino\n\nAyo Ikutan!!\nklik >>> /join\n\nPermainan dimulai oleh: System",
            'parse_mode'=>'HTML',
        ]);
        $startmsgid = $result['result']['message_id'];
        startPlayingGame('-1001635551800', "System", 'mamin', [
            'step'=>'starting',
            'starting_timeleft'=>90,
            'startmsgid'=>$startmsgid,
            'remind_join'=>0,
            'player_change'=>true,
            'players'=>[]
        ]);
        $data['change_step'][] = [
            'mamin',
            '-1001635551800',
            'starting_check',
            time()+5,
        ];
    }

}

//initiate for games
// $gamefiles = scandir('galihjk/games/');
// foreach($gamefiles as $file){
// 	if(isDiakhiri($file,'_init.php')){
// 		include("galihjk/games/$file");
// 	}
// }