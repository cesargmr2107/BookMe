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
        "CALENDAR" => "far fa-calendar-alt"
    );


    // JS FILE
    protected $jsFiles;

    protected $data;
    
    protected abstract function body();

    public function __construct($data = null){
        $this->data = $data;
        $this->render();
    }

    protected function render(){
        $this->header();
        $this->includeButton("LOGOUT", "logoutButton", "post", "AuthenticationController", "logout");
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
        
                <!-- Bootstrap and Datetime pickers -->
                <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet"/>
                <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css" rel="stylesheet"/>
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
                <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.3/moment.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/js/bootstrap-datetimepicker.min.js"></script>
                
                <!-- Font awesome icons -->
                <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
                <script src="https://kit.fontawesome.com/ae3641038e.js" crossorigin="anonymous"></script>
        
                <!-- My scripts -->
                <script type="text/javascript" src="./VIEW/js/common.js"></script> 
                <?php
                    if($this->jsFiles){
                        foreach ($this->jsFiles as $file) {
                            echo "<script type='text/javascript' src='./VIEW/js/$file'></script>";
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

    protected function includeCrudTable($idAtribute, $nameAtribute, $controller){
        echo "<table>";
            // Headers
            echo "<tr>";
            foreach($this->data["atributeNames"] as $atribute){
                echo "<th>" . $atribute . "</th>";
            }
            echo "<th>Opciones</th>";
            echo "</tr>";

            // Rows
            foreach($this->data["result"] as $row){
                echo "<tr>";

                    // Atribute columns
                    foreach($row as $atribute){
                        $id = $row[$idAtribute];
                        echo "<td>" . $atribute ."</td>";
                    }

                    //Option Column
                    echo "<td>";
                        $this->includeButton("SHOW", "goToShow$id", "post", $controller, "show", array ($idAtribute => $id) );
                        if($_SESSION["TIPO_USUARIO"] === "ADMINISTRADOR"){
                            $name = $row[$nameAtribute];
                            $this->includeButton("EDIT", "editBt$id", "post", $controller, "editForm", array ($idAtribute => $id));
                            $this->includeDeleteButtonAndModal($idAtribute, $id, $name, $controller);
                        }
                    echo '</td>';

                echo "</tr>";
            }
        echo '</table>';
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
}
?>