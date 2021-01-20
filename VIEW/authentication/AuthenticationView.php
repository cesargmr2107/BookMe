<?php

include_once './VIEW/BaseView.php';

abstract class AuthenticationView extends BaseView{

    protected function render(){
        $this->jsFiles = array("./VIEW/webroot/js/md5.js");
        $this->header();
        $this->includeBackgroundVideo("background-video", "./VIEW/webroot/res/background-video.mp4", "./VIEW/webroot/res/background-video.jpg");
        echo "<div id='auth-container'>";
        $this->body();
        $this->includeLangSelection();
        echo "</div>";
        $this->footer();
    }

}
?>