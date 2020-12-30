<?php

include_once './VIEW/BaseView.php';

class CalendariosAddView extends BaseView{

    protected function body(){
    ?>
        <h1>Añadir nuevo calendario</h1>
        <form id="addForm" name="addForm" action="index.php" >
            <?php
                $this->includeTextField('Nombre', 'NOMBRE_CALENDARIO');
                $this->includeTextField('Descripción', 'DESCRIPCION_CALENDARIO');
                $this->includeDateField('Fecha de inicio', 'FECHA_INICIO_CALENDARIO');
                $this->includeDateField('Fecha de fin', 'FECHA_FIN_CALENDARIO');
                $this->includeTimeField('Hora de inicio', 'HORA_INICIO_CALENDARIO');
                $this->includeTimeField('Hora de fin', 'HORA_FIN_CALENDARIO');
            ?>
            <span class="<?=$this->icons["ADD"]?>" onclick="sendForm(document.addForm, 'CalendariosController', 'add', true)"></span>
        </form>
    <?php
    }

}
?>