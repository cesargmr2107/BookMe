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

    protected $data;
    
    protected abstract function body();

    public function __construct($data = null){
        $this->data = $data;
        $this->render();
    }

    protected function render(){
        include_once './VIEW/components/header.php';
        $this->includeButton("LOGOUT", "logoutButton", "post", "UsuariosController", "logout");
        $this->body();
        include_once './VIEW/components/footer.php';
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

    protected function includeHiddenField($atribute, $value){
        echo "<input type='hidden' name='$atribute' value='$value'/>";
    }

    protected function includeTextField($label, $atribute, $value = null){
        $valueTag = ($value !== null) ? "value='$value'" : '';
        ?>
            <div class="form-group">
                <label for="<?=$atribute?>"><?=$label?></label> 
                <input type="text" name="<?=$atribute?>" <?=$valueTag?>/>
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


    protected function includeDeleteModal($id, $name, $controller){
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
                            <?= $this->includeButton("ACCEPT", "deleteForm$id", "post", $controller, "delete" ) ?>
                        </div>
    
                    </div>
                </div>
            </div>
        <?php
        }
}
?>