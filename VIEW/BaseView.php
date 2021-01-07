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
        
                <!-- My scripts -->
                <script type="text/javascript" src="./VIEW/js/common.js"></script> 
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
            <body>
        
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
                    <?= $this->includeButton("LOGOUT", "logoutButton", "post", "AuthenticationController", "logout") ?>
                </div>
            </nav>
        <?php
    }

    private function includeNavBarSolicitudes(){
        
        $options = array(
            array("text" => "Mis solicitudes", "controller" => "ReservasController", "action" => "searchOwn"),
            array("text" => "Nueva solicitud", "controller" => "ReservasController", "action" => "addForm")
        );

        if($_SESSION["TIPO_USUARIO"] !== "NORMAL"){
            array_push(
                $options,
                array("text" => "Solicitudes pendientes", "controller" => "ReservasController", "action" => "searchPending"),
                array("text" => "Confirmación de uso", "controller" => "ReservasController", "action" => "confirm")
            );
            if($_SESSION["TIPO_USUARIO"] === "ADMINISTRADOR"){
                array_push(
                    $options,
                    array("text" => "Historial de solicitudes", "controller" => "ReservasController", "action" => "search")
                );
            }
        }

        $this->includeNavBarDropdown("Solicitudes", $options);
    }

    private function includeNavBarRecursos(){

        $options = array(
            array("text" => "Recursos de la aplicación", "controller" => "RecursosController", "action" => "search")
        );

        if($_SESSION["TIPO_USUARIO"] === "ADMINISTRADOR"){
            array_push(
                $options,
                array("text" => "Nuevo recurso", "controller" => "RecursosController", "action" => "addForm")
            );
        }

        $this->includeNavBarDropdown("Recursos", $options);
    }

    private function includeNavBarCalendarios(){

        $options = array(
            array("text" => "Calendarios de la aplicación", "controller" => "CalendariosController", "action" => "search"),
        );

        if($_SESSION["TIPO_USUARIO"] === "ADMINISTRADOR"){
            array_push(
                $options,
                array("text" => "Nuevo calendario", "controller" => "CalendariosController", "action" => "addForm")
            );
        }

        $this->includeNavBarDropdown("Calendarios", $options);
    }

    private function includeNavBarUsuarios(){
        if($_SESSION["TIPO_USUARIO"] === "ADMINISTRADOR"){
            $options = array(
                array("text" => "Usuarios de la aplicación", "controller" => "UsuariosController", "action" => "search"),
                array("text" => "Nueva solicitud", "controller" => "UsuariosController", "action" => "addForm")
            );
            $this->includeNavBarDropdown("Usuarios", $options);
        }
    }

    private function includeNavBarDropdown($title, $options){
        ?>
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown">
                    <span><?= $title?></span>
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <?php
                        foreach ($options as $option) {
                            echo "<li>";
                                $this->includeLink(
                                    $option["text"],
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

    protected function includeLink($text, $name, $method, $controller, $action){
        ?>
            <form name="<?=$name?>" action="index.php" method="<?=$method?>">
                <a onclick="sendForm(document.<?=$name?>, '<?=$controller?>', '<?=$action?>', true)"><?=$text?></a>
            </form>
        <?php
    }

    protected function includeTitle($title, $tag){
        $valid_title_tags = array("h1","h2","h3","h4","h5","h6");
        if(in_array($tag, $valid_title_tags)){
            echo "<$tag>$title</$tag>";
        }else{
            echo "<h1>$title</h1>";
        }
    }

    protected function includeShowInfo($title, $info){
        ?>
            <p>
                <strong><?= $title ?>:</strong>
                <span><?= $info ?></span>
            </p>
        <?php
    }

    protected function includeShowList($list, $title, $noneMsg, $name, $id = null){
        echo "<p><strong>$title: <strong>";
        if(!count($list)){
            echo "<span>$noneMsg</span></p>";
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

    protected function includeTextField($label, $atribute, $value = null){
        $valueTag = ($value !== null) ? "value='$value'" : '';
        ?><div class="form-group"><label for="<?=$atribute?>"><?=$label?></label><input type="text" name="<?=$atribute?>" <?=$valueTag?>/></div><?php
    }

    protected function includeReadOnlyField($label, $atribute, $value = null){
        $valueTag = ($value !== null) ? "value='$value'" : '';
        ?><div class="form-group"><label for="<?=$atribute?>"><?=$label?></label><input type="text" name="<?=$atribute?>" <?=$valueTag?> readonly="readonly"/></div><?php
    }

    protected function includePasswordField($label, $atribute, $value = null){
        $valueTag = ($value !== null) ? "value='$value'" : '';
        ?>
            <div class="form-group">
                <label for="<?=$atribute?>"><?=$label?></label> 
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

    protected function includeDateField($label, $atribute, $useMinDateAsCurrent, $value = 'dd/mm/yyyy'){
        if($value !== 'dd/mm/yyyy') {
            $d = DateTime::createFromFormat('Y-m-d', $value);
            $value = date_format($d,'d/m/Y');
        }
        $this->includeDatetimeField($label, $atribute, 'DD/MM/YYYY', $value, $useMinDateAsCurrent);
    }
    
    protected function includeTimeField($label, $atribute, $value = 'hh:mm'){
        $this->includeDatetimeField($label, $atribute, 'HH:00', $value);
    }

    private function includeDatetimeField($label, $atribute, $format, $value, $useMinDateAsCurrent = false){
        $icon = ($format === 'DD/MM/YYYY') ? 'fa fa-calendar' : 'fa fa-clock';
        $valueTag = ($value !== null) ? "value='$value'" : '';
        ?>
            <label for="<?=$atribute?>"><?=$label?></label>
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

    protected function includeSelectField($label, $atribute, $options, $assocOptions, $value = null){
        ?>
            <label for="<?=$atribute?>"><?=$label?></label>
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
                echo "<th>" . $atribute . "</th>";
            }
            echo "<th>Opciones</th>";
            echo "</tr>";

            // Rows
            foreach($this->data["result"]as $row){
                echo "<tr>";

                    // Atribute columns
                    foreach($row as $atribute){
                        $optionsData["row"] = $row;
                        echo "<td>" . $atribute ."</td>";
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