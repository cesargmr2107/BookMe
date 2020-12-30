<?php

include_once './VIEW/BaseView.php';

abstract class AuthenticationView extends BaseView{

    protected function render(){
        include_once './VIEW/components/header.php';
        $this->body();
        include_once './VIEW/components/footer.php';
    }

}
?>