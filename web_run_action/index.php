<?php 
if(empty($include_action)){
    die("wrong access");
}

$config = include("galihjk/config.php");

//helpers autoload
$scandir = scandir('galihjk/helpers/');
foreach($scandir as $file){
	if(substr($file,-4) == '.php'){
		include("galihjk/helpers/$file");
	}
}

include($include_action);