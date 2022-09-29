<?php

function getUser($user_id){
	return loadData("user/$user_id");
}

function setUser($user_id, $data, $update_last_active = true){
	if($update_last_active){
		$data['last_active'] = time();
	}
	$data_user = getUser($user_id);
	foreach($data as $k=>$v){
		$data_user[$k]=$v;
	}
	saveData("user/$user_id",$data_user);
	return $data_user;
}

function mentionUser($from_id){
	global $data;
	if(!isset($data['playing_users'][$from_id])){
	    $data_user = getUser($from_id);
	    if(!empty($data_user)){
	        $data['playing_users'][$from_id] = $data_user;
	    }
	}
	if(!isset($data['playing_users'][$from_id])){
		return "[-?-]";
	}
	else{
		if(empty($data['playing_users'][$from_id]['username'])){
			return "<a href='tg://user?id=$from_id'>".$data['playing_users'][$from_id]['first_name']."</a>";
		}
		else{
			return "@".$data['playing_users'][$from_id]['username'];
		}
	}
}

function namaLengkap($user_id){
	global $data;
	if(!isset($data['playing_users'][$user_id])){
		return "[-??-]";
	}
	else{
		$return = $data['playing_users'][$user_id]['first_name'];
		if(!empty($data['playing_users'][$user_id]['last_name'])) $return .= " " .$data['playing_users'][$user_id]['last_name'];
		return $return;
	}
}

function checkImpersonate(&$userdata){
	global $data;
	global $id_developer;
	if(!empty($data['impersonate']) and $userdata['id'] == $id_developer){
		$userdata = $data['impersonate'];
	}
}