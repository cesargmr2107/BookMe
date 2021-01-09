<?php

include_once './VIEW/BaseView.php';

abstract class AuthenticationView extends BaseView{

    protected function render(){
        $this->jsFiles = array("./VIEW/js/md5.js");
        $this->header();
        $this->body();
        $this->includeLangSelection();
        $this->footer();
    }

}
?>