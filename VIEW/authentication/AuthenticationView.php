<?php

include_once './VIEW/BaseView.php';

abstract class AuthenticationView extends BaseView{

    protected function render(){
        $this->jsFiles = array("md5.js");
        $this->header();
        $this->body();
        $this->footer();
    }

}
?>