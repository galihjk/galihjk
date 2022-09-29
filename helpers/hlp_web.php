<?php
function getWebSrv(){
	return "https://galihjk.herokuapp.com";
}

function genLoginCode($userid, $expired = 600){
    $time = time()+$expired;
    return base64_encode("$time|$userid|".md5('gjkweb'.($time).$userid)); // 10 menit
}

function getAllRequest($filter = ['id'=>'number']){
    $r = $_GET;
    foreach ($_POST as $k=>$v){
        $r[$k] = $v;
    }
	if(!empty($filter)){
		foreach($r as $k=>$v){
			if(isset($filter[$k])){
				$filtertype = $filter[$k];
				if($filtertype == "number"){
					$r[$k] = preg_replace('/\D/', '', $v);
				}
			}
		}
	}
    return (object) $r;
}

function webapi($method, $param = [], $assoc = false){
	$srv = getenv('srv');
	$url = "https://$srv/?app=api_f5b9f04cd3cc0be9e8a2825703cbc5d6&act=$method";
	foreach($param as $k=>$v){
		$url .= "&$k=" . urlencode($v);
	}
	$content = file_get_contents($url);
	$content_json = json_decode($content, $assoc);
	if($content_json !== null){
		return [
			'url'=>$url,
			'content'=>$content_json,
			'is_json'=>true,
		];
	}
	else{
		return [
			'url'=>$url,
			'content'=>$content,
			'is_json'=>false,
		];
	}
}

function webapi_to_array($method, $param = []){
	$result = webapi($method, $param = [], true);
	if(!empty($result['is_json'])) return $result['content'];
	return false;
}

function addLoginUrl($chat_id, $timeout = 10, $prefix = ""){
	$webtime = time()+$timeout+1; // (tambah 1 detik buat delay user open web)
	$logincode =  base64_encode("$webtime|$chat_id|".md5('gjkweb'.($webtime).$chat_id));
	return $prefix."login=$logincode";
}