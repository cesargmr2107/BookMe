<?php

class MessagesController {

	private static $cipherMethod = "aes-128-gcm";
	private static $cipherKey = '6v9y$B&E)H@MbQeThWmZq4t7w!z%C*F-JaNdRfUjXn2r5u8x/A?D(G+KbPeShVkY';
    
    function render(){

        // Get token, iv and tag
        $token = str_replace(" ", "+", $_GET["token"]);
		$iv = $_SESSION["iv"];
		$tag = $_SESSION["tag"];

        // Decrypt json and convert to assoc array which will be passed to view 
        $jsonString = openssl_decrypt($token, self::$cipherMethod, self::$cipherKey, $options = 0, $iv, $tag);
        $data = json_decode($jsonString, true);

        // Render view
        include_once './VIEW/MessageView.php';
        new MessageView($data);
    }
}
?>