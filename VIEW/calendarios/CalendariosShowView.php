<?php

include_once './VIEW/BaseView.php';

class CalendariosShowView extends BaseView{

    function body(){
        echo '<pre>' . var_export($this->data, true) . '</pre>';
    }
}
?>