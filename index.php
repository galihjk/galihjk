<?php

if(!empty($_GET['bot']) and $_GET['bot'] == "9fb4d360c8df1a9d3d829e17ac3275b4"){
    //bot
    include('bot.php');
    exit();
}

elseif(!empty($_GET['runserver']) and $_GET['runserver'] == "f9c19a9ebb552c48c83fd79636039705"){
	//server
	include('server.php');
	exit();
}



echo "Hello World!";