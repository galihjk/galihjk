<?php 
/*
	function soal_hash($str){
		$str = strtoupper(preg_replace("/[^a-zA-Z0-9]/", " ", $str));
		$explode = explode(" ",$str);
		$unique = array_unique($explode);
		$tidak_usah = [
			'SEBUTKAN','YG','BIASA','BIASANYA','MACAM','NAMA','ADA','DI','APA','APAKAH','OLEH','SIAPA','SESUATU','DG','HURUF'
		];
		foreach($unique as $k=>$v){
			if(empty($v)){
				unset($unique[$k]);
			}
			else{
				$v = strtoupper(sinonim($v));
				if(in_array($v,$tidak_usah)){
					unset($unique[$k]);
				}
				else{
					$unique[$k] = $v;
				}
			}
		}
		sort($unique);
		return implode("",$unique);
	}
*/

function updateSoalPost($id_soal, $jenis_soal, $data_soal){
    global $emoji_dislike;
    global $emoji_please;
    global $emoji_like;
    global $emoji_pencil;
    global $emoji_cross;
    global $emoji_chat;
    global $config;

    $channel_username = "@galihjksoal";
    $vtsc = $data_soal['vtsc'];
    $soal = $data_soal['soal'];
    $kontributor_names = [];
    foreach(array_keys($data_soal['vote']) as $user_id){
        $kontributor_names[] = getUser($user_id)['first_name'] ?? "?";
		if(count($kontributor_names)>=10) break;
    }
    
    $text = "[SOAL ".strtoupper($jenis_soal)."]\n<i>".implode(", ",$kontributor_names)."</i>\n==============\n\n$soal\n\nVoteScore: $vtsc\n ";

    KirimPerintah('editMessageText',[
        'chat_id' => $channel_username,
        'message_id'=>$id_soal,
        'text'=> $text,
        'parse_mode'=>'HTML',
        'reply_markup' => inlineKeyBoard([
            ["$emoji_dislike DOWN (-1)","soal_downvote_$id_soal"."__$jenis_soal"],
            ["$emoji_please unvote (0)","soal_unvote_$id_soal"."__$jenis_soal"],
            ["$emoji_like UP (+1)","soal_upvote_$id_soal"."__$jenis_soal"],
            ["$emoji_pencil Edit","https://t.me/".$config['bot_username']."?start=cmd_soal_edit_$id_soal"."__$jenis_soal"],
            ["$emoji_cross Hapus","https://t.me/".$config['bot_username']."?start=cmd_soal_hapus_$id_soal"."__$jenis_soal"],
            ["$emoji_chat Jawaban","https://t.me/".$config['bot_username']."?start=cmd_soal_jawaban_$id_soal"."__$jenis_soal"],
        ],3),
    ]);
}

function userContributeSoal($user_id){
	$userdata =  getUser($user_id);
	$point = $userdata['point'] ?? 0;
	$last = $userdata['lstktrb'] ?? 0;
	if(abs(time() - $last) > 1*60*60){ 
		// 2 point in 1 hours
		$point_add = 2;
		$point += $point_add;
		setUser($user_id, ['point' => $point, 'lstktrb' => time()]);
		$url = "https://galihjk.my.id/?web_run_action=delayed_perintah&method=sendMessage&delay=5&param_data=".urlencode(json_encode([
			'chat_id' => $user_id,
			'text'=> "Kamu mendapatkan $point_add /point",
			'parse_mode'=>'HTML',
		]));
		get_without_wait($url);
		// KirimPerintah('sendMessage',[
		// 	'chat_id' => $user_id,
		// 	'text'=> "Kamu mendapatkan $point_add /point $url",
		// 	'parse_mode'=>'HTML',
		// ]);
	}
}

function soal_kirimEditorJawaban($chat_id, $jenis_soal, $id_soal, $edit_id = ""){
	$data_soal = loadData("soal/$jenis_soal/$id_soal");
	if(!$data_soal) return false;
	$output = "[SOAL]\n\nJawaban untuk:\n======\n";
	$output .= $data_soal['soal'];
	$output .= "\n======\n\nBalas pada pesan ini untuk menambahkan jawaban\n|ID:$jenis_soal|$id_soal";;
	if(
		(!($edit_id != "force_reply" and !empty($edit_id)))
		and
		(empty($data_soal['jawab']) or $edit_id == "force_reply")
	){
		$reply_markup = [
			'force_reply'=>true,
			'input_field_placeholder'=>'Tulis Jawaban',
			'selective'=>true,
		];
	}
	else{
		arsort($data_soal['jawab']);
		$inlinekeyboard_arr = [
			['⬇️Kurangi⬇️', '~~'],
			['⬇️Jawaban⬇️', '~~'],
			['⬇️Tambah⬇️', '~~'],
		];
		foreach($data_soal['jawab'] as $k=>$v){
			$vplus = $v+1;
			$vminus = $v-1;
			if($vminus < 0){
				$inlinekeyboard_arr[] = ["➖ (Hapus)", 'soal_jwbsc_'.$id_soal.'__'.$jenis_soal.'__-__'.$k];
			}
			else{
				$inlinekeyboard_arr[] = ["➖ $v => $vminus", 'soal_jwbsc_'.$id_soal.'__'.$jenis_soal.'__-__'.$k];
			}
			$inlinekeyboard_arr[] = [$k, 'soal_jwbscshow_'.$k.'__'.$v];
			$inlinekeyboard_arr[] = ["➕ $v => $vplus", 'soal_jwbsc_'.$id_soal.'__'.$jenis_soal.'__+__'.$k];
		}
		$inlinekeyboard_arr[] = ["@galihjksoal", 'http://t.me/galihjksoal/'.$id_soal];
		$inlinekeyboard_arr[] = ["✨ Buat Jawaban", 'soal_buatjwb_'.$id_soal.'__'.$jenis_soal];
		$reply_markup = inlineKeyBoard($inlinekeyboard_arr,3);
	}
	if(empty($edit_id) or $edit_id == "force_reply"){
		KirimPerintah('sendMessage',[
			'chat_id' => $chat_id,
			'text'=> $output,
			'parse_mode'=>'HTML',
			'reply_markup' => $reply_markup,
		]);
	}
	else{
		KirimPerintah('editMessageText',[
            'message_id' => $edit_id,
			'chat_id' => $chat_id,
			'text'=> $output,
			'parse_mode'=>'HTML',
			'reply_markup' => $reply_markup,
		]);
	}
}

function soal_get($jenis_soal, $except = []){
    $folder = "data/soal/$jenis_soal/";
    $list = scandir($folder);
	shuffle($list);
	$data_soal_got = [];
	$id_soal = "";
	foreach($list as $k=>$file){
		if(!isDiakhiri($file,".json")
			or in_array(str_replace(".json", "",$file),$except)
		){
			continue;
		}
		else{
			$id_soal = str_replace(".json", "",$file);
			$data_soal = loadData("soal/$jenis_soal/$id_soal");
			if(empty($data_soal['vtsc'])){
				continue;
			}
			elseif($data_soal['vtsc'] < 2){
				continue;
			}
			else{
				$data_soal_got = $data_soal;
				break;
			}
		}
	}
	if(empty($data_soal_got)){
		return "habis!";
	}
	$data_soal_got['id'] = $id_soal;
	return $data_soal_got;
}

function soal_setSudah($chat_id, $id_soal, $type){
	$soal_sudah = getChatData($chat_id,'soal_sudah');
	$soal_sudah[$id_soal] = $type;
	setChatData($chat_id,['soal_sudah' => $soal_sudah], false, -1);
}