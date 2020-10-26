<?php
	$access_token 	= "YOUR_ACCESS_TOKEN";
	
	$response 		= file_get_contents("php://input");
	$response 		= json_decode($response, true);
	
	if(isset($response['entry'][0]['messaging'][0]['sender']['id'])){
		$id = $response['entry'][0]['messaging'][0]['sender']['id'];
	}
	
	if(isset($response['entry'][0]['messaging'][0]['message']['text'])){
		$message = $response['entry'][0]['messaging'][0]['message']['text'];
	}
	
	if(isset($response['entry'][0]['messaging'][0]['message']['quick_reply']['payload'])){
		$payload = $response['entry'][0]['messaging'][0]['message']['quick_reply']['payload'];
	}
	else{
		$payload = "greeting";
	}
	
	if($payload == "greeting"){
		$menu_keyboard = [		
			[
				"content_type" => "text",
				"title" => "Today's Date?",
				"payload" => "date"
			],
            [
				"content_type" => "text",
				"title" => "Tell me a Joke",
				"payload" => "joke"
			],          
			[
				"content_type" => "text",
				"title" => "Creator?",
				"payload" => "creator"
			]
		];
		
		$data = array(				
			'recipient' => array(
			   'id' => $id		
			),
			'message' => array(
			   'text' => "Welcome to Business Page. How can I help you today?",
			   'quick_replies' => $menu_keyboard
			)
		);
		
		send_reply($data, $access_token);
	}
	else if($payload == "date"){
		$today 	= date("Y/m/d");
		
		$data 	= array(				
			'recipient' => array(
			   'id' => $id		
			),
			'message' => array(
			   'text' => "Today is: ".$today,
			)
		);
		
		send_reply($data, $access_token);
	}
	else if($payload == "joke"){
		$url 	= "https://official-joke-api.appspot.com/random_joke";
	
		$json 	= file_get_contents($url);
		$obj 	= json_decode($json);
		
		$data 	= array(				
			'recipient' => array(
			   'id' => $id		
			),
			'message' => array(
			   'text' => $obj->setup,
			)
		);
		
		send_reply($data, $access_token);
		
		sleep(2);
		
		$data 	= array(				
			'recipient' => array(
			   'id' => $id		
			),
			'message' => array(
			   'text' => $obj->punchline,
			)
		);
		
		send_reply($data, $access_token);
	}
	else if($payload == "creator"){
		$data 	= array(				
			'recipient' => array(
			   'id' => $id		
			),
			'message' => array(
			   'text' => "I was created by Narendra Nauth",
			)
		);
		
		send_reply($data, $access_token);
	}
	
	function send_reply($data, $access_token){
		$url = "https://graph.facebook.com/v2.7/me/messages?access_token=".$access_token; 
		$data_string = json_encode($data);

		$ch = curl_init($url);	
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		   'Content-Type: application/json',
		   'Content-Length: ' . strlen($data_string))
		);

		curl_exec($ch);
		curl_close($ch);
	}
?>
