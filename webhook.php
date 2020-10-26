<?php
    $verify_token   = "chat_bot_token";

    $mode           = $_GET['hub_mode'];
    $token          = $_GET['hub_verify_token'];
    $challenge      = $_GET['hub_challenge'];

    if($mode && $token){
		if($mode === 'subscribe' && $token === $verify_token){
			echo $challenge;
			exit;
		}
    }
?>