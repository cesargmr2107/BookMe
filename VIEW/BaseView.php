<?php

include_once './COMMON/utils.php';

abstract class BaseView{

    // FONTAWESOME ICONS
    protected $icons = array(
        "SHOW" => "far fa-eye",
        "ADD" => "far fa-plus-square",
        "EDIT" => "far fa-edit",
        "DELETE" => "far fa-trash-alt",
        "CANCEL" => "far fa-times-circle",
        "ACCEPT" => "far fa-check-circle",
        "LOGIN" => "fas fa-sign-in-alt",
        "LOGOUT" => "fas fa-sign-out-alt",
        "BACK" => "fas fa-arrow-left",
        "CALENDAR" => "far fa-calendar-alt",
        "CHART" => "far fa-chart-bar",
        "SEARCH" => "fas fa-search",
        "BOOKING" => "far fa-calendar-plus",
        "PROFILE" => "fas fa-user-alt",
        "CANCEL-BOOKING" => "far fa-calendar-times"
    );


    // JS FILES
    protected $jsFiles;

    // CSS FILES
    protected $cssFiles;

    protected $data;
    
    protected abstract function body();

    public function __construct($data = null){
        $this->data = $data;
        $this->render();
    }

    protected function render(){
        $this->header();
        if(array_key_exists("LOGIN_USUARIO", $_SESSION)){
            $this->includeNavigationBar();
        }
        echo "<div id='container'>";
        $this->body();
        echo "</div>";
        $this->footer();
    }

    protected function header(){
        ?>
            <html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es">
            
            <head>
        
                <!-- Required meta tags -->
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        
                <!-- Title -->
                <title>BookMe</title>

                <!-- Favicon -->
                <link rel="shortcut icon" href="./favicon.png">

                <!-- Bootstrap and Datetime pickers -->
                <link href="./VIEW/webroot/libraries/bootstrap/bootstrap.min.css" rel="stylesheet"/>
                <link href="./VIEW/webroot/libraries/bootstrap/bootstrap-datetimepicker.min.css" rel="stylesheet"/>
                <script src="./VIEW/webroot/libraries/bootstrap/jquery.min.js"></script>
                <script src="./VIEW/webroot/libraries/bootstrap/bootstrap.min.js"></script>
                <script src="./VIEW/webroot/libraries/bootstrap/moment-with-locales.js"></script>
                <script src="./VIEW/webroot/libraries/bootstrap/bootstrap-datetimepicker.min.js"></script>
                
                <!-- Font awesome icons -->
                <link href="./VIEW/webroot/libraries/fontawesome/font-awesome.min.css" rel="stylesheet"/>
                <script src="./VIEW/webroot/libraries/fontawesome/ae3641038e.js" crossorigin="anonymous"></script>

                <!-- My style --> 
                <link href="./VIEW/webroot/css/style.css" rel="stylesheet"/>

                <!-- My scripts: common, validation and locales -->
                <script type="text/javascript" src="./VIEW/webroot/js/common.js"></script> 
                <script type="text/javascript" src="./VIEW/webroot/js/validation.js"></script> 
                <script type="text/javascript" src="./VIEW/locales/i18n.js"></script> 
                <script type="text/javascript" src="./VIEW/locales/lang_es.js"></script> 
                <script type="text/javascript" src="./VIEW/locales/lang_en.js"></script> 
                <script type="text/javascript" src="./VIEW/locales/lang_gl.js"></script>
                <?php
                    if($this->jsFiles){
                        foreach ($this->jsFiles as $file) {
                            echo "<script type='text/javascript' src='$file'></script>";
                        }
                    }
                ?>

                <!-- Other CSS -->
                <?php
                    if($this->cssFiles){
                        foreach ($this->cssFiles as $file) {
                            echo "<link href='$file' rel='stylesheet'/>";
                        }
                    }
                ?>
        
            </head>

            <body onload="setLang()">
        
        <?php
    }

    protected function footer(){
        ?>
                </body>
            </html>
        <?php
    }

    protected function formatDate($date){
        $d = preg_split("/-/", $date);
        return $d[2] . "/" . $d[1] . "/" . $d[0];
    }

    protected function includeNavigationBar(){
        ?>
            <nav class="navbar">
                <div class="navbar-header">
                    <a class="navbar-brand" href="index.php">BookMe</a>
                </div>
                <div id="navbar-container">
                    <ul class="nav navbar-nav">
                        <?php
                        // Navegación de solicitudes
                        $this->includeNavBarSolicitudes();
                        
                        // Navegación de recursos
                        $this->includeNavBarRecursos();
                        
                        // Navegación de calendarios
                        $this->includeNavBarCalendarios();
                        
                        // Navegación de usuarios
                        $this->includeNavBarUsuarios();
                        ?>
                    </ul>
                    <?php
                        $this->includeLangSelection();
                        echo "<div id='nav-bar-icons'>";
                            $this->includeButton("PROFILE", "profileButton", "post", "UsuariosController", "show", ["LOGIN_USUARIO" => $_SESSION["LOGIN_USUARIO"]]);
                            $this->includeButton("LOGOUT", "logoutButton", "post", "AuthenticationController", "logout");
                        echo "</div>";
                    ?>
                </div>
            </nav>
        <?php
    }

    private function includeNavBarSolicitudes(){
        
        $options = array(
            array("textCode" => "i18n-myBookings", "controller" => "ReservasController", "action" => "searchOwn"),
            array("textCode" => "i18n-newBooking", "controller" => "ReservasController", "action" => "addForm")
        );

        if(!isNormalUser()){
            array_push(
                $options,
                array("textCode" => "i18n-pendingBookings", "controller" => "ReservasController", "action" => "searchPending"),
                array("textCode" => "i18n-confirmBooking", "controller" => "ReservasController", "action" => "confirm")
            );
            if(isAdminUser()){
                array_push(
                    $options,
                    array("textCode" => "i18n-bookingHistory", "controller" => "ReservasController", "action" => "search")
                );
            }
        }

        $this->includeNavBarDropdown("i18n-navbar-bookings", $options);
    }

    private function includeNavBarRecursos(){

        $options = array(
            array("textCode" => "i18n-resourcesSearch", "controller" => "RecursosController", "action" => "search"),
            array("textCode" => "i18n-resourcesGlobal", "controller" => "RecursosController", "action" => "global")
        );

        if(isAdminUser()){
            array_push(
                $options,
                array("textCode" => "i18n-newResource", "controller" => "RecursosController", "action" => "addForm")
            );
        }

        $this->includeNavBarDropdown("i18n-navbar-resources", $options);
    }

    private function includeNavBarCalendarios(){

        $options = array(
            array("textCode" => "i18n-calendarsSearch", "controller" => "CalendariosController", "action" => "search"),
        );

        if(isAdminUser()){
            array_push(
                $options,
                array("textCode" => "i18n-newCalendar", "controller" => "CalendariosController", "action" => "addForm")
            );
        }

        $this->includeNavBarDropdown("i18n-navbar-calendars", $options);
    }

    private function includeNavBarUsuarios(){
        if(isAdminUser()){
            $options = array(
                array("textCode" => "i18n-usersSearch", "controller" => "UsuariosController", "action" => "search"),
                array("textCode" => "i18n-newUser", "controller" => "UsuariosController", "action" => "addForm")
            );
            $this->includeNavBarDropdown("i18n-navbar-users", $options);
        }
    }

    private function includeNavBarDropdown($titleCode, $options){
        ?>
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown">
                    <span class="<?= $titleCode?>"></span>
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <?php
                        foreach ($options as $option) {
                            echo "<li>";
                                $this->includeLink(
                                    $option["textCode"],
                                    "goTo" . $option["controller"] . $option["action"],
                                    "post",
                                    $option["controller"],
                                    $option["action"]
                                );
                            echo "</li>";
                        }
                    ?>
                </ul>
            </li>
        <?php
    }

    protected function includeLangSelection(){
        $langs = array ("ES", "GL", "EN");
        $toShow = '';
        foreach ($langs as $lang) {
            $toShow = $toShow . "<a onclick=\"setLang('$lang')\">$lang</a> | ";
        }
        echo "<div>" . substr($toShow,0,-3) ."</div>";
    }

    protected function includeButton($icon, $button_id, $method, $controller, $action, $object_data = null){
        echo "<form id='$button_id' name='$button_id' action='index.php' method='$method'>";
            if(is_array($object_data)){
                foreach ($object_data as $atributeName => $value) {
                    echo "<input type='hidden' name='$atributeName' value='$value'/>"; 
                }
            }
            $icon = $this->icons[$icon];
            echo "<span class='$icon' onclick='sendForm(document.$button_id, \"$controller\", \"$action\", true)'></span>";
        echo '</form>';
    }

    protected function includeLink($textCode, $name, $method, $controller, $action){
        ?>
            <form name="<?=$name?>" action="index.php" method="<?=$method?>">
                <a class="<?=$textCode?>" onclick="sendForm(document.<?=$name?>, '<?=$controller?>', '<?=$action?>', true)"></a>
            </form>
        <?php
    }

    protected function includeTitle($titleCode, $tag){
        $valid_title_tags = array("h1","h2","h3","h4","h5","h6");
        if(in_array($tag, $valid_title_tags)){
            echo "<$tag class='$titleCode'></$tag>";
        }else{
            echo "<h1 class='$titleCode'></h1>";
        }
    }

    protected function includeShowInfo($titleCode, $info, $id = null){
        $id = ($id != null) ? "id='$id'" : "";
        echo "<p><strong class='$titleCode'></strong><span>: </span><span $id>$info</span>";
    }

    protected function includeShowDate($titleCode, $date){
        $this->includeShowInfo($titleCode, $this->formatDate($date));
    }

    protected function includeShowList($list, $titleCode, $noneMsgCode, $name, $id = null){
        echo "<p><strong class='$titleCode'></strong>: ";
        if(!count($list)){
            echo "<span class='$noneMsgCode'></span></p>";
        }else{
            echo '</p>';
            echo '<ul>';
            foreach ($list as $element) {
                if($id === null){
                    echo "<li>$element[$name]</li>";
                }else{
                    echo "<li>$element[$id]: $element[$name]</li>";
                }
            }
            echo '</ul>';
        }
    }

    protected function includeHiddenField($atribute, $value){
        echo "<input type='hidden' id='$atribute' name='$atribute' value='$value'/>";
    }

    protected function includeTextField($labelCode, $atribute, $value = null){
        $valueTag = ($value !== null) ? "value='$value'" : '';
        ?><div class="form-group"><label class="<?=$labelCode?>" for="<?=$atribute?>"></label><input type="text" id="<?=$atribute?>" name="<?=$atribute?>" <?=$valueTag?>/></div><?php
    }

    protected function includeTextArea($labelCode, $atribute, $value = null){
        ?>
            <div class="form-group">
                <label class="<?=$labelCode?>" for="<?=$atribute?>"></label>
                <textarea id="<?=$atribute?>" name="<?=$atribute?>" rows="4" cols="25" maxlength="100"><?=$value?></textarea>
            </div>
        <?php
    }

    protected function includeReadOnlyField($labelCode, $atribute, $value = null){
        $valueTag = ($value !== null) ? "value='$value'" : '';
        ?><div class="form-group"><label class="<?=$labelCode?>" for="<?=$atribute?>"></label><input type="text" id="<?=$atribute?>" name="<?=$atribute?>" <?=$valueTag?> readonly="readonly"/></div><?php
    }

    protected function includePasswordField($labelCode, $atribute, $value = null){
        $valueTag = ($value !== null) ? "value='$value'" : '';
        ?>
            <div class="form-group">
                <label class="<?=$labelCode?>" for="<?=$atribute?>"></label> 
                <input type="password" id="<?=$atribute?>" name="<?=$atribute?>" <?=$valueTag?>/>
            </div>
        <?php
    }

    protected function includeNumberField($label, $atribute, $value = null){
        $valueTag = ($value !== null) ? "value='$value'" : '';
        ?>
            <div class="form-group">
                <label for="<?=$atribute?>"><?=$label?></label> 
                <input type="number" id="<?=$atribute?>" name="<?=$atribute?>" <?=$valueTag?>/>
            </div>
        <?php
    }

    protected function includeDateField($labelCode, $atribute, $minDate, $maxDate, $value = 'dd/mm/yyyy'){
        if($value !== 'dd/mm/yyyy') {
            $d = DateTime::createFromFormat('Y-m-d', $value);
            $value = date_format($d,'d/m/Y');
        }
        $this->includeDatetimeField($labelCode, $atribute, 'DD/MM/YYYY', $minDate, $maxDate, $value );
    }
    
    protected function includeTimeField($labelCode, $atribute, $minHour, $maxHour, $value = 'hh:mm:ss'){
        $this->includeDatetimeField($labelCode, $atribute, 'HH:00:00', $minHour, $maxHour, $value);
    }

    private function includeDatetimeField($labelCode, $atribute, $format, $min, $max, $value){
        $icon = ($format === 'DD/MM/YYYY') ? 'fa fa-calendar' : 'fa fa-clock';
        $valueTag = ($value !== 'hh:mm:ss' && $value !== 'dd/mm/yyyy') ? "value='$value'" : "placeholder='$value'";
        ?>
            <div class="form-group">
                <label class="<?=$labelCode?>" for="<?=$atribute?>"></label>
                <div class='input-group date' id='<?=$atribute?>'>
                    <input type='text' class="form-control" name="<?=$atribute?>" <?=$valueTag?> readonly />
                    <span class="input-group-addon">
                        <span class="<?= $icon ?>"></span>
                    </span>
                </div>
                <script type="text/javascript">
                    $(function () {
                        var dp = $('#<?=$atribute?>').datetimepicker(
                            {
                                <?php
                                    if($min !== null && $max !== null){
                                        if($format === 'DD/MM/YYYY'){
                                            echo "minDate: new Date('$min'),";
                                            $d = strtotime("$max + 1 day");
                                            $max = date("Y-m-d", $d);
                                            echo "maxDate: new Date('$max'),";
                                        } else {
                                            echo "minDate: moment({hour:$min}),";
                                            $max++;
                                            echo "maxDate: moment({hour:$max}),";
                                        }
                                    }
                                ?>
                                locale: moment.locale('es'),
                                format: '<?=$format?>',
                                ignoreReadonly: true
                            }
                        );
                        pickers.push(dp);
                    });
                </script>
            </div>
        <?php
    }

    protected function includeSelectField($labelCode, $atribute, $options, $assocOptions, $value = null){
        ?>
            <div class="form-group">
                <label class="<?=$labelCode?>" for="<?=$atribute?>"></label>
                <select name="<?=$atribute?>" id="<?=$atribute?>" class="custom-select">
                    <?php
                    if($assocOptions){
                        if($value === null){
                            echo "<option class='i18n-options' disabled='disabled' selected></option>";
                        }
                        foreach ($options as $id => $text) {
                            if($value != $id){
                                echo "<option value='$id'>$text</option>";
                            }else {
                                echo "<option value='$id' selected='selected'>$text</option>";
                            }                      
                        }
                    }else{
                        foreach ($options as $text) {
                            if($value != $text){
                                echo "<option value='$text'>$text</option>";
                            }else{
                                echo "<option value='$text' selected='selected'>$text</option>";
                            }                      
                        }
                    }
                    ?>
                </select>
            </div>
            <?php
    }
    
    protected function includeCrudTable($optionsData){

        if(empty($this->data["result"])){
            echo "<h3 class='i18n-noResults'></h3>";
        } else {
            echo "<table class='table crud-table'>";
            // Headers
            echo "<tr>";
            foreach($this->data["atributeNames"] as $atribute){
                echo "<th class='i18n-$atribute'></th>";
            }
                echo "<th class='i18n-options'></th>";
                echo "</tr>";

                // Rows
                foreach($this->data["result"]as $row){
                    echo "<tr>";

                        // Atribute columns
                        foreach($row as $atribute => $value){
                            $optionsData["row"] = $row;
                            if (strpos($atribute, 'FECHA') !== false){ // Parse date to format
                                $d = DateTime::createFromFormat('Y-m-d', $value);
                                $value = date_format($d,'d/m/Y');
                            }
                            echo "<td>" . $value ."</td>";
                        }

                        $this->includeOptions($optionsData);

                    echo "</tr>";
                }
            echo '</table>';
        }

    }

    protected function includeOptions($optionsData){

        // Get data
        $idAtribute = $optionsData["idAtribute"];
        $id = $optionsData["row"][$idAtribute];
        $nameAtribute = $optionsData["nameAtribute"];
        $name = $optionsData["row"][$nameAtribute];
        $controller = $optionsData["controller"];

        echo "<td id='row-options'>";
            $this->includeButton("SHOW", "goToShow$id", "post", $controller, "show", array ($idAtribute => $id) );
            if(isAdminUser()){
                $this->includeButton("EDIT", "editBt$id", "post", $controller, "editForm", array ($idAtribute => $id));
                $this->includeDeleteButtonAndModal($idAtribute, $id, $name, $controller);
            }
        echo '</td>';
    }

    protected function includeDeleteButtonAndModal($atribute, $id, $name, $controller){
        ?>
            <!-- Delete button -->
            <span class="<?= $this->icons["DELETE"]?>" data-toggle="modal" href="#deleteModal<?= $id ?>"></span>
    
            <!-- Delete modal -->
            <div class="modal" id="deleteModal<?= $id ?>">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                    
                        <!-- Modal Header  -->
                        <div class="modal-header">
                            <h4 class="modal-title">
                                <span class="i18n-deleteConfirmation"></span>
                                <strong>'<?=$name?>'</strong>?
                            </h4>
                        </div>
    
                        <!-- Modal body -->
                        <div class="modal-body options">
                            <?= $this->includeButton("ACCEPT", "deleteForm$id", "post", $controller, "delete", array($atribute => $id)) ?>
                            <span class="<?= $this->icons["CANCEL"] ?>" data-dismiss="modal"></span>
                        </div>
    
                    </div>
                </div>
            </div>
        <?php
    }

    protected function includeCancelModal(){
        ?>
            <!-- Cancel button -->
            <span class="<?= $this->icons["CANCEL-BOOKING"]?>" data-toggle="modal" href="#cancelModal"></span>

            <!-- Cancel modal -->
            <div class="modal" id="cancelModal">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                    
                        <!-- Modal Header  -->
                        <div class="modal-header">
                            <h4 class="modal-title">
                                <span class="i18n-cancelConfirmation"></span>
                            </h4>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body options">
                            <?= $this->includeButton("ACCEPT", "cancelForm", "post", "ReservasController", "cancel", array("ID_RESERVA" => $this->data["ID_RESERVA"])) ?>
                            <span class="<?= $this->icons["CANCEL"] ?>" data-dismiss="modal"></span>
                        </div>

                    </div>
                </div>
            </div>
        <?php
    }

    protected function includeCalendar($events, $showTitle){
        
        // Include HTML
        echo "<div id='calendar'></div>";

        // Format events for JS if passed as array
        if(is_string($events)) {
            $event_string = $events;
        } else {
            $event_string = "";
            foreach($events as $event){
                $title = ($showTitle === true) ? "title: '" . $event["NOMBRE_RECURSO"] . "'," : '';
                $event_string = $event_string .
                                "{" .
                                $title .
                                "startRecur: new Date ('" . $event["FECHA_INICIO_SUBRESERVA"] . "')," .
                                "endRecur: new Date ('" . $event["FECHA_FIN_SUBRESERVA"] . "')," .
                                "startTime: '" . $event["HORA_INICIO_SUBRESERVA"] . "'," .
                                "endTime: '" . $event["HORA_FIN_SUBRESERVA"] . "'," .
                                "color: '#D9D9D9'," .
                                "textColor: 'black'" .
                                "},";
            }
            if (strpos($event_string, '{') !== false){
                $event_string = substr($event_string,0,-1);
            }
        }
        
        // Include JS
        ?>
            <script>
                var resource_events = [<?= $event_string ?>];
                document.addEventListener('DOMContentLoaded', function() {
                    createCalendar(resource_events);
                });
            </script>
        <?php
    }


    protected function includeValidationModal(){
        ?>
            <div class="modal" id="validationModal">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                    
                        <!-- Modal Header  -->
                        <div class="modal-header">
                            <h4 class="i18n-formError modal-title"></h4>
                            <span class="<?= $this->icons["CANCEL"] ?> close" onclick="closeModal()"></span>
                        </div>
    
                        <!-- Modal body -->
                        <div class="modal-body">
                            <ul id="errorMsgs"></ul>
                        </div>
    
                    </div>
                </div>
            </div>
        <?php
    }

    protected function includeBackgroundVideo($id, $srcVideo, $srcPoster){
        ?>
            <video playsinline autoplay muted loop poster="<?=$srcPoster?>" id="<?=$id?>">
                <source src="<?=$srcVideo?>" type="video/mp4"/>        
            </video>
        <?php
    }

    protected function includeSearchBar($atribute, $placeholderCode, $controller){
        ?>
            <form name="searchForm" method="post" action="index.php">
                <input id="search-input" class="<?=$placeholderCode?>" type="text" name="<?=$atribute?>" />
                <span class="<?=$this->icons["SEARCH"]?>" onclick="sendForm(document.searchForm, '<?=$controller?>', 'search', true)"></span>
            </form>
        <?php
    }

    protected function includeFilters($default){
        ?>
            <div id="filters">
                <?php
                    foreach($this->data["atributeNames"] as $index => $code){
                        $atr = $this->data["atributesForSearch"][$index];
                        echo "<div>";
                        if($default === $code){
                            echo "<input type='radio' name='filter' value='$atr' checked/>";
                        } else {
                            echo "<input type='radio' name='filter' value='$atr'/>";
                        }
                        echo "<label class='i18n-$code' for='$code'></label>";
                        echo "</div>";
                    }
                ?>
                <script>
                    $('input[type=radio][name=filter]').change(function() {
                        var searchInput = document.getElementById("search-input");
                        searchInput.name = this.value;
                        searchInput.className = `i18n-searchBy${this.value}`;
                        searchInput.placeholder = translations[`i18n-searchBy${this.value}`];
                    });
                </script>
            </div>
        <?php
    }
}
?>