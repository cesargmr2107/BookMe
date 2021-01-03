<?php

include_once './VIEW/BaseView.php';

class RecursosEditView extends BaseView{

    protected function body(){
        $this->includeTitle("Modificar información de recurso", "h1");
        ?>
            <form id="editForm" name="editForm" action="index.php" >
                <?php
                    $this->includeHiddenField('ID_RECURSO', $this->data["resource"]["ID_RECURSO"]);
                    $this->includeTextField('Nombre', 'NOMBRE_RECURSO', $this->data["resource"]["NOMBRE_RECURSO"]);
                    $this->includeTextField('Descripción', 'DESCRIPCION_RECURSO', $this->data["resource"]["DESCRIPCION_RECURSO"]);
                    $this->includeSelectField('Calendario de uso', 'ID_CALENDARIO', $this->data["calendars"], true, $this->data["resource"]["ID_CALENDARIO"]);
                    $this->includeTextField('Tarifa', 'TARIFA_RECURSO', $this->data["resource"]["TARIFA_RECURSO"]);
                    $this->includeSelectField('Rango de tarifa', 'RANGO_TARIFA_RECURSO', $this->data["priceRanges"], false, $this->data["resource"]["RANGO_TARIFA_RECURSO"]);
                    $this->includeSelectField('Responsable', 'LOGIN_RESPONSABLE', $this->data["responsables"], true, $this->data["resource"]["LOGIN_RESPONSABLE"]);
                ?>
                <span class="<?=$this->icons["EDIT"]?>" onclick="sendForm(document.editForm, 'RecursosController', 'edit', true)"></span>
            </form>
        <?php
    }

}
?>