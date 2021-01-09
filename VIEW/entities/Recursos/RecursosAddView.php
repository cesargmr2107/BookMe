<?php

include_once './VIEW/BaseView.php';

class RecursosAddView extends BaseView{

    protected function body(){
        $this->includeTitle("i18n-newResource", "h1");
        ?>
            <form id="addForm" name="addForm" action="index.php" method="post">
                <?php
                    $this->includeTextField("i18n-nombre", 'NOMBRE_RECURSO');
                    $this->includeTextField("i18n-descripcion", 'DESCRIPCION_RECURSO');
                    $this->includeSelectField("i18n-calendar", 'ID_CALENDARIO', $this->data["calendars"], true);
                    $this->includeTextField("i18n-tarifa", 'TARIFA_RECURSO');
                    $this->includeSelectField("i18n-rango_tarifa", 'RANGO_TARIFA_RECURSO', $this->data["priceRanges"], false);
                    $this->includeSelectField("i18n-login_responsable", 'LOGIN_RESPONSABLE', $this->data["responsables"], true);
                ?>
                <span class="<?=$this->icons["ADD"]?>" onclick="sendForm(document.addForm, 'RecursosController', 'add', checkResourceAddForm())"></span>
            </form>
        <?php
        $this->includeValidationModal();
    }

}
?>