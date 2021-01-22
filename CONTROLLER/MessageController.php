<?php

include_once './COMMON/utils.php';

class MessagesController {
    
    function render(){

        $jsonString = false;

        // If possible, decrypt
        if( array_key_exists("token", $_GET) ){
            $jsonString = decrypt($_GET["token"]);
        }
            
        if($jsonString === false){
            $data = array(
                "result" => array("code" => "AC003"),
                "link" => "index.php"
            );
        } else {
            $data = json_decode($jsonString, true);
        }

        // Render view
        include_once './VIEW/MessageView.php';
        new MessageView($data);
    }
}
?>