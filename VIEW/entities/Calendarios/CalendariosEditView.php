<?php

include_once './VIEW/BaseView.php';

class CalendariosEditView extends BaseView{

    protected function body(){
        $this->includeTitle("i18n-editCalendar", "h1");
        ?>
            <form id="editForm" name="editForm" action="index.php" method="post" >
                <?php
                    $this->includeHiddenField('ID_CALENDARIO', $this->data["ID_CALENDARIO"]);
                    $this->includeTextField("i18n-nombre", 'NOMBRE_CALENDARIO', $this->data["NOMBRE_CALENDARIO"]);
                    $this->includeTextField("i18n-descripcion", 'DESCRIPCION_CALENDARIO', $this->data["DESCRIPCION_CALENDARIO"]);
                    $this->includeDateField("i18n-fecha_inicio", 'FECHA_INICIO_CALENDARIO', false, $this->data["FECHA_INICIO_CALENDARIO"]);
                    $this->includeDateField("i18n-fecha_fin", 'FECHA_FIN_CALENDARIO', false,  $this->data["FECHA_FIN_CALENDARIO"]);
                    $this->includeTimeField("i18n-hora_inicio", 'HORA_INICIO_CALENDARIO', $this->data["HORA_INICIO_CALENDARIO"]);
                    $this->includeTimeField("i18n-hora_fin", 'HORA_FIN_CALENDARIO', $this->data["HORA_FIN_CALENDARIO"]);
                ?>
                <span class="<?=$this->icons["EDIT"]?>" onclick="sendForm(document.editForm, 'CalendariosController', 'edit', true)"></span>
            </form>
        <?php
    }

}
?>