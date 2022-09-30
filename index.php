<?php

if(!empty($_GET['bot']) and $_GET['bot'] == "9fb4d360c8df1a9d3d829e17ac3275b4"){
    //bot
    include('galihjk/bot.php');
    exit();
}

elseif(!empty($_GET['runserver']) and $_GET['runserver'] == "f9c19a9ebb552c48c83fd79636039705"){
	//server
	include('galihjk/server.php');
	exit();
}

elseif(!empty($_GET['web_run_action']) and file_exists("galihjk/web_run_action/".$_GET['web_run_action'].".php")){
	//web_run_action
    $include_action = "galihjk/web_run_action/".$_GET['web_run_action'].".php";
	include("galihjk/web_run_action/index.php");
	exit();
}

echo "Hello World!";