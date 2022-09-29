<?php 

$config = include("config.php");

//helpers autoload
$scandir = scandir('helpers/');
foreach($scandir as $file){
	if(substr($file,-4) == '.php'){
		include("helpers/$file");
	}
}

$token = $config['bot_token'];
$apiURL = "https://api.telegram.org/bot$token";
$data = loadData("data");
$id_developer = $config['id_developer'];

include('initiate.php');

$last_serve_time = intval(loadData("last_serve_time") ?? 0);
if($last_serve_time !== time()){
    $jeda = time() - intval($last_serve_time);
    saveData("last_serve_time",time());
    include('main.php');
}

$update = json_decode(file_get_contents("php://input"), TRUE);
if(!empty($update)) {
	//================================================================
	//poll:==========================
	if(isset($update["poll"])){
		include("main_poll.php");
	}

	//callback query:==========================
	elseif(isset($update["callback_query"])){
		include("main_callback_query.php");
	}

	//inline_query:==========================
	elseif(isset($update['inline_query'])){
		include("main_inline_query.php");
	}

	//chosen_inline_result:==========================
	elseif(isset($update['chosen_inline_result'])){
		include("main_chosen_inline_result.php");
	}

	//channel_post:==========================
	elseif(isset($update['channel_post'])){

	}
	//edited_channel_post:==========================
	elseif(isset($update['edited_channel_post'])){
		
	}

	//my_chat_member (start / stop bot):==========================
	elseif(isset($update['my_chat_member'])){
		include("main_my_chat_member.php");
	}

	//message (basic chat message):==========================
	elseif(isset($update['message'])){
		include("main_message.php");
	}

	//unhandled report:==========================
	else{
		KirimPerintah('sendMessage',[
			'chat_id' => $id_developer,
			'text'=> "ERROR: not handled:\n\n" . print_r($update,1),
			'parse_mode'=>'HTML',
		]);
	}
	//================================================================

    /*
    	if(!empty($update['message'])){
    		$message = $update["message"];
    		if(time() - $message['date'] <= 5 * 60){
    			if(!empty($message["chat"])){
    				$chat = $message["chat"];
    				if(!empty($chat["id"])){
    					$chat_id = $chat["id"];
    
    					saveData("chat/$chat_id",$chat);
    
        				// 	if(!empty($message["text"])){
        				// 		$message_text = $message["text"];
        				// 		$responses_text = scandir('responses_text');
        				// 		foreach($responses_text as $rt){
        				// 			if(substr($rt,-4) == '.php'){
        				// 				include("responses_text/$rt");
        				// 			}
        				// 		}
        				// 	}
        				KirimPerintah('sendMessage',[
                			'chat_id' => $id_developer,
                			'text'=> "UNDERCONST:\n\n" . print_r($update,1),
                			'parse_mode'=>'HTML',
                		]);
    
    				}
    				else{
    					print_r($chat);
    					echo "\nmessage - chat - id is empty\n";
    				}
    			}
    			else{
    				print_r($message);
    				echo "\nmessage - chat is empty\n";
    			}			
    		}
    		else{
    			//jika pesan lebih dari lima menit yang lalu
    			print_r($message);
    			echo "\nmessage is too old ( > 5 minutes) \n";
    		}
    
    	}
    	else{
    		print_r($update);
    		echo "\nmessage is empty\n";
    	}
	*/
}
echo "<pre>";
print_r($data);

saveData("data", $data);
