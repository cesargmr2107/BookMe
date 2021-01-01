<?php

include_once './VIEW/BaseView.php';

class CalendariosEditView extends BaseView{

    protected function body(){
    ?>
        <h1>Modificar información de calendario</h1>
        <form id="addForm" name="editForm" action="index.php" >
            <?php
                $this->includeHiddenField('ID_CALENDARIO', $this->data["ID_CALENDARIO"]);
                $this->includeTextField('Nombre', 'NOMBRE_CALENDARIO', $this->data["NOMBRE_CALENDARIO"]);
                $this->includeTextField('Descripción', 'DESCRIPCION_CALENDARIO', $this->data["DESCRIPCION_CALENDARIO"]);
                $this->includeDateField('Fecha de inicio', 'FECHA_INICIO_CALENDARIO', false, $this->data["FECHA_INICIO_CALENDARIO"]);
                $this->includeDateField('Fecha de fin', 'FECHA_FIN_CALENDARIO', false,  $this->data["FECHA_FIN_CALENDARIO"]);
                $this->includeTimeField('Hora de inicio', 'HORA_INICIO_CALENDARIO', $this->data["HORA_INICIO_CALENDARIO"]);
                $this->includeTimeField('Hora de fin', 'HORA_FIN_CALENDARIO', $this->data["HORA_FIN_CALENDARIO"]);
            ?>
            <span class="<?=$this->icons["EDIT"]?>" onclick="sendForm(document.editForm, 'CalendariosController', 'edit', true)"></span>
        </form>
    <?php
    }

}
?>