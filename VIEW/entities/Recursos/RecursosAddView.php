<?php

include_once './VIEW/BaseView.php';

class RecursosAddView extends BaseView{

    protected function body(){
        $this->includeTitle("Añadir nuevo recurso", "h1");
        ?>
            <form id="addForm" name="addForm" action="index.php" method="post">
                <?php
                    $this->includeTextField('Nombre', 'NOMBRE_RECURSO');
                    $this->includeTextField('Descripción', 'DESCRIPCION_RECURSO');
                    $this->includeSelectField('Calendario de uso', 'ID_CALENDARIO', $this->data["calendars"], true);
                    $this->includeTextField('Tarifa', 'TARIFA_RECURSO');
                    $this->includeSelectField('Rango de tarifa', 'RANGO_TARIFA_RECURSO', $this->data["priceRanges"], false);
                    $this->includeSelectField('Responsable', 'LOGIN_RESPONSABLE', $this->data["responsables"], true);
                ?>
                <span class="<?=$this->icons["ADD"]?>" onclick="sendForm(document.addForm, 'RecursosController', 'add', true)"></span>
            </form>
        <?php
    }

}
?>