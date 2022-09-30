<?php

function getUser($user_id){
	global $data;
	if(empty($data['active_users'][$user_id])){
		$user = loadData("user/$user_id");
		$data['active_users'][$user_id] = $user;
	}
	else{
		$user = $data['active_users'][$user_id];
	}
	return $user;
}

function setUser($user_id, $data_set_user, $update_last_active = true){
	global $data;
	if($update_last_active){
		$data_set_user['last_active'] = time();
	}
	$data_user = getUser($user_id);
	foreach($data_set_user as $k=>$v){
		$data_user[$k]=$v;
	}
	saveData("user/$user_id",$data_user);
	$data['active_users'][$user_id] = $data_user;
	return $data_user;
}

function mentionUser($from_id){
	$data_user = getUser($from_id);
	if(empty($data_user)){
		return "[-?-]";
	}
	else{
		if(empty($data_user['username'])){
			return "<a href='tg://user?id=$from_id'>".$data_user['first_name']."</a>";
		}
		else{
			return "@".$data_user['username'];
		}
	}
}

function namaLengkap($user_id){
	if(empty(getUser($user_id))){
		return "[-??-]";
	}
	else{
		$return = getUser($user_id)['first_name'];
		if(!empty(getUser($user_id)['last_name'])) $return .= " " .getUser($user_id)['last_name'];
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