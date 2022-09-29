<?php

$message_data = $update["poll"];
$poll_id = $message_data["id"];
$jawabans = $message_data["options"];
$soal = $message_data["question"];

if ($config['bot_username'] == "galihjkbot"){
    include("special_galihjkbot_poll.php");
}