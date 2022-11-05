<?php
function isDiawali($string, $diawali, $caseSensitive = true){
	if(!$caseSensitive){
		$string = strtolower($string);
		$diawali = strtolower($diawali);
	}
	if(substr($string,0,strlen($diawali)) === $diawali){
		return true;
	}
	else{
		return false;
	}
}

function isDiakhiri($string, $diakhiri, $caseSensitive = true){
	if(!$caseSensitive){
		$string = strtolower($string);
		$diakhiri = strtolower($diakhiri);
	}
	if(substr($string,-strlen($diakhiri)) === $diakhiri){
		return true;
	}
	else{
		return false;
	}
}

function sinonim($str){
	$sinonim['yang'] = 'yg';
	$sinonim['untuk'] = 'utk';
	$sinonim['lakilaki'] = 'pria';
	$sinonim['cowo'] = 'pria';
	$sinonim['cowok'] = 'pria';
	$sinonim['perempuan'] = 'wanita';
	$sinonim['cewe'] = 'wanita';
	$sinonim['cewek'] = 'wanita';
	$sinonim['dengan'] = 'dg';
	$sinonim['binatang'] = 'hewan';
	$strcek = strtolower(preg_replace("/[^a-zA-Z0-9]/", "", $str));
	if(isset($sinonim[$strcek])){
		return $sinonim[$strcek];
	}
	else{
		return $str;
	}
}

function html_double_quote($str){
	return str_replace('"',"&#34;",$str);
}

function escapeHtmlOpenTag($str){
	return str_replace("<","&lt;",$str);
}

function strToDB($str){
	return str_replace("'","''",$str);
}

function timeToSimpleText($time){
	if($time < 60) return "$time detik";
	return ($time < 60*60 ? round($time/60) . " menit" : round($time/(60*60)) . " jam");
}

function cleanWord($text, $replace = " ", $upper=true){
	$text = preg_replace('/[^A-Z0-9\-]/', $replace, $text);
	if($upper) $text = strtoupper($text);
	return $text;
}