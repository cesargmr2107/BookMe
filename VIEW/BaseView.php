<?php

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
        "BOOKING" => "far fa-calendar-plus"
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
        $this->includeNavigationBar();
        $this->body();
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
                <link href="./VIEW/libraries/bootstrap/bootstrap.min.css" rel="stylesheet"/>
                <link href="./VIEW/libraries/bootstrap/bootstrap-datetimepicker.min.css" rel="stylesheet"/>
                <script src="./VIEW/libraries/bootstrap/jquery.min.js"></script>
                <script src="./VIEW/libraries/bootstrap/bootstrap.min.js"></script>
                <script src="./VIEW/libraries/bootstrap/moment.min.js"></script>
                <script src="./VIEW/libraries/bootstrap/bootstrap-datetimepicker.min.js"></script>
                
                <!-- Font awesome icons -->
                <link href="./VIEW/libraries/fontawesome/font-awesome.min.css" rel="stylesheet"/>
                <script src="./VIEW/libraries/fontawesome/ae3641038e.js" crossorigin="anonymous"></script>
        
                <!-- My scripts: common and locales -->
                <script type="text/javascript" src="./VIEW/js/common.js"></script> 
                <script type="text/javascript" src="./VIEW/locales/i18n.js"></script> 
                <script type="text/javascript" src="./VIEW/locales/lang_es.js"></script> 
                <script type="text/javascript" src="./VIEW/locales/lang_en.js"></script> 
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
            <nav class="navbar navbar-inverse">
                <div class="container-fluid">
                    <div class="navbar-header">
                    <a class="navbar-brand" href="#">BookMe</a>
                    </div>
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
                        $this->includeButton("LOGOUT", "logoutButton", "post", "AuthenticationController", "logout");
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

        if($_SESSION["TIPO_USUARIO"] !== "NORMAL"){
            array_push(
                $options,
                array("textCode" => "i18n-pendingBookings", "controller" => "ReservasController", "action" => "searchPending"),
                array("textCode" => "i18n-confirmBooking", "controller" => "ReservasController", "action" => "confirm")
            );
            if($_SESSION["TIPO_USUARIO"] === "ADMINISTRADOR"){
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
            array("textCode" => "i18n-resourcesSearch", "controller" => "RecursosController", "action" => "search")
        );

        if($_SESSION["TIPO_USUARIO"] === "ADMINISTRADOR"){
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

        if($_SESSION["TIPO_USUARIO"] === "ADMINISTRADOR"){
            array_push(
                $options,
                array("textCode" => "i18n-newCalendar", "controller" => "CalendariosController", "action" => "addForm")
            );
        }

        $this->includeNavBarDropdown("i18n-navbar-calendars", $options);
    }

    private function includeNavBarUsuarios(){
        if($_SESSION["TIPO_USUARIO"] === "ADMINISTRADOR"){
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

    private function includeLangSelection(){
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

    protected function includeShowInfo($titleCode, $info){
        ?>
            <p><strong class="<?= $titleCode ?>"></strong><span>: <?= $info ?></span></p>
        <?php
    }

    protected function includeShowDate($titleCode, $date){
        $this->includeShowInfo($titleCode, $this->formatDate($date));
    }

    protected function includeShowList($list, $titleCode, $noneMsgCode, $name, $id = null){
        echo "<p><strong class='$titleCode'></strong>";
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
        echo "<input type='hidden' name='$atribute' value='$value'/>";
    }

    protected function includeTextField($labelCode, $atribute, $value = null){
        $valueTag = ($value !== null) ? "value='$value'" : '';
        ?><div class="form-group"><label class="<?=$labelCode?>" for="<?=$atribute?>"></label><input type="text" name="<?=$atribute?>" <?=$valueTag?>/></div><?php
    }

    protected function includeReadOnlyField($labelCode, $atribute, $value = null){
        $valueTag = ($value !== null) ? "value='$value'" : '';
        ?><div class="form-group"><label class="<?=$labelCode?>" for="<?=$atribute?>"></label><input type="text" name="<?=$atribute?>" <?=$valueTag?> readonly="readonly"/></div><?php
    }

    protected function includePasswordField($labelCode, $atribute, $value = null){
        $valueTag = ($value !== null) ? "value='$value'" : '';
        ?>
            <div class="form-group">
                <label class="<?=$labelCode?>" for="<?=$atribute?>"></label> 
                <input type="password" name="<?=$atribute?>" <?=$valueTag?>/>
            </div>
        <?php
    }

    protected function includeNumberField($label, $atribute, $value = null){
        $valueTag = ($value !== null) ? "value='$value'" : '';
        ?>
            <div class="form-group">
                <label for="<?=$atribute?>"><?=$label?></label> 
                <input type="number" name="<?=$atribute?>" <?=$valueTag?>/>
            </div>
        <?php
    }

    protected function includeDateField($labelCode, $atribute, $useMinDateAsCurrent, $value = 'dd/mm/yyyy'){
        if($value !== 'dd/mm/yyyy') {
            $d = DateTime::createFromFormat('Y-m-d', $value);
            $value = date_format($d,'d/m/Y');
        }
        $this->includeDatetimeField($labelCode, $atribute, 'DD/MM/YYYY', $value, $useMinDateAsCurrent);
    }
    
    protected function includeTimeField($labelCode, $atribute, $value = 'hh:mm'){
        $this->includeDatetimeField($labelCode, $atribute, 'HH:00', $value);
    }

    private function includeDatetimeField($labelCode, $atribute, $format, $value, $useMinDateAsCurrent = false){
        $icon = ($format === 'DD/MM/YYYY') ? 'fa fa-calendar' : 'fa fa-clock';
        $valueTag = ($value !== null) ? "value='$value'" : '';
        ?>
            <label class="<?=$labelCode?>" for="<?=$atribute?>"></label>
            <div class='input-group date' id='<?=$atribute?>'>
                <input type='text' class="form-control" name="<?=$atribute?>" <?=$valueTag?> readonly />
                <span class="input-group-addon">
                    <span class="<?= $icon ?>"></span>
                </span>
            </div>
            <script type="text/javascript">
                $(function () {
                    $('#<?=$atribute?>').datetimepicker(
                        {
                            <?php
                                if($format === 'DD/MM/YYYY' && $useMinDateAsCurrent === true)
                                echo 'minDate: new Date(),';
                            ?>
                            format: '<?=$format?>',
                            ignoreReadonly: true
                        }
                    );
                });
            </script>
        <?php
    }

    protected function includeSelectField($labelCode, $atribute, $options, $assocOptions, $value = null){
        ?>
            <label class="<?=$labelCode?>" for="<?=$atribute?>"></label>
            <select name="<?=$atribute?>" id="<?=$atribute?>" class="custom-select">
                <?php
                if($assocOptions){
                    if($value === null){
                        echo "<option disabled='disabled' selected>Opciones</option>";
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
        <?php
    }

    protected function includeCrudTable($optionsData){
        echo "<table>";
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

    protected function includeOptions($optionsData){

        // Get data
        $idAtribute = $optionsData["idAtribute"];
        $id = $optionsData["row"][$idAtribute];
        $nameAtribute = $optionsData["nameAtribute"];
        $name = $optionsData["row"][$nameAtribute];
        $controller = $optionsData["controller"];

        echo "<td>";
            $this->includeButton("SHOW", "goToShow$id", "post", $controller, "show", array ($idAtribute => $id) );
            if($_SESSION["TIPO_USUARIO"] === "ADMINISTRADOR"){
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
                            <h4 class="modal-title">¿Estás seguro de que quieres borrar '<?= $name?>'?</h4>
                        </div>
    
                        <!-- Modal body -->
                        <div class="modal-body">
                            <span class="<?= $this->icons["CANCEL"] ?>" data-dismiss="modal"></span>
                            <?= $this->includeButton("ACCEPT", "deleteForm$id", "post", $controller, "delete", array($atribute => $id)) ?>
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
                                "startRecur: '" . $event["FECHA_INICIO_SUBRESERVA"] . "'," .
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
}
?>