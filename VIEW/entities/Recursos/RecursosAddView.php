<?php

include_once './VIEW/BaseView.php';

class RecursosAddView extends BaseView{

    protected function body(){
        $this->includeTitle("i18n-newResource", "h1");
        ?>
            <form id="addForm" name="addForm" action="index.php" method="post">
                <div>
                    <?php
                        $this->includeTextField("i18n-nombre", 'NOMBRE_RECURSO');
                        $this->includeSelectField("i18n-calendar", 'ID_CALENDARIO', $this->data["calendars"], true);
                        $this->includeSelectField("i18n-rango_tarifa", 'RANGO_TARIFA_RECURSO', $this->data["priceRanges"], false);
                        $this->includeSelectField("i18n-login_responsable", 'LOGIN_RESPONSABLE', $this->data["responsables"], true);
                    ?>
                </div>
                <div>
                    <?php
                        $this->includeTextField("i18n-tarifa", 'TARIFA_RECURSO');
                        $this->includeTextArea("i18n-descripcion", 'DESCRIPCION_RECURSO');
                    ?>
                </div>
            </form>
            <span class="<?=$this->icons["ADD"]?>" onclick="sendForm(document.addForm, 'RecursosController', 'add', checkResourceAddForm())"></span>
        <?php
        $this->includeValidationModal();
    }

}
?>