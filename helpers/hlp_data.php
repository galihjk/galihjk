<?php 

function saveData($name, $data){
    $filename="data/$name.json";
	return file_put_contents($filename, json_encode($data)); 
}

function loadData($name){
    $filename="data/$name.json";
	if(file_exists($filename)){
		$filedata = file_get_contents($filename);
		$data = json_decode($filedata,true);
		if($data === false){
			$data = [];
		}
	}
	else{
		$data = [];
	}
	return $data;
}
