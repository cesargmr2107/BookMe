<?php

include_once './VIEW/BaseView.php';

class CalendariosAddView extends BaseView{

    protected function body(){
        $this->includeTitle("i18n-newCalendar", "h1");
        ?>
            <form id="addForm" name="addForm" action="index.php" method="post" >
                <div>
                    <?php
                        $this->includeTextField("i18n-nombre", 'NOMBRE_CALENDARIO');
                        $this->includeTextArea("i18n-descripcion", 'DESCRIPCION_CALENDARIO');
                    ?>
                </div>
                <div>
                    <?php
                        $this->includeDateField("i18n-fecha_inicio", 'FECHA_INICIO_CALENDARIO', false);
                        $this->includeDateField("i18n-fecha_fin", 'FECHA_FIN_CALENDARIO', false);
                        $this->includeTimeField("i18n-hora_inicio", 'HORA_INICIO_CALENDARIO');
                        $this->includeTimeField("i18n-hora_fin", 'HORA_FIN_CALENDARIO');
                    ?>
                </div>
            </form>
            <span class="<?=$this->icons["ADD"]?>" onclick="sendForm(document.addForm, 'CalendariosController', 'add', checkCalendarAddForm())"></span>
        <?php
        $this->includeValidationModal();
    }

}
?>