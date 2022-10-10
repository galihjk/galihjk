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