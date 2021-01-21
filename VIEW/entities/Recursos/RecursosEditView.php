<?php

include_once './VIEW/BaseView.php';

class RecursosEditView extends BaseView{

    protected function body(){
        $this->includeTitle("i18n-editResource", "h1");
        ?>
            <form id="editForm" name="editForm" action="index.php" method="post" >
                <div>
                    <?php
                        $this->includeHiddenField('ID_RECURSO', $this->data["resource"]["ID_RECURSO"]);
                        $this->includeTextField("i18n-nombre", 'NOMBRE_RECURSO', $this->data["resource"]["NOMBRE_RECURSO"]);
                        $this->includeSelectField("i18n-calendar", 'ID_CALENDARIO', $this->data["calendars"], true, $this->data["resource"]["ID_CALENDARIO"]);
                        $this->includeSelectField("i18n-login_responsable", 'LOGIN_RESPONSABLE', $this->data["responsables"], true, $this->data["resource"]["LOGIN_RESPONSABLE"]);
                        $this->includeSelectField("i18n-rango_tarifa", 'RANGO_TARIFA_RECURSO', $this->data["priceRanges"], false, $this->data["resource"]["RANGO_TARIFA_RECURSO"]);
                    ?>
                </div>
                <div>
                    <?php
                        $this->includeTextField("i18n-tarifa", 'TARIFA_RECURSO', $this->data["resource"]["TARIFA_RECURSO"]);
                        $this->includeTextArea("i18n-descripcion", 'DESCRIPCION_RECURSO', $this->data["resource"]["DESCRIPCION_RECURSO"]);
                    ?>
                </div>
            </form>
            <span class="<?=$this->icons["EDIT"]?>" onclick="sendForm(document.editForm, 'RecursosController', 'edit', checkResourceEditForm())"></span>
        <?php
        $this->includeValidationModal();
    }

}
?>