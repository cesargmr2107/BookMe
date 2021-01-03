<?php

include_once './VIEW/BaseView.php';

class CalendariosShowView extends BaseView{

    protected function body(){
        
        // DEBUG: Check data passed to view
        // echo '<pre>' . var_export($this->data, true) . '</pre>';
        
        $this->includeTitle("Detalles de calendario", "h1");
        echo "<div>";
            $this->includeShowInfo("ID", $this->data["ID_CALENDARIO"]);
            $this->includeShowInfo("Nombre", $this->data["NOMBRE_CALENDARIO"]);
            $this->includeShowInfo("Fecha de inicio", $this->data["FECHA_INICIO_CALENDARIO"]);
            $this->includeShowInfo("Fecha de fin", $this->data["FECHA_FIN_CALENDARIO"]);
            $this->includeShowInfo("Hora de inicio", $this->data["HORA_INICIO_CALENDARIO"]);
            $this->includeShowInfo("Hora de fin", $this->data["HORA_FIN_CALENDARIO"]);
        echo "</div>";
        echo "<div>";
            $this->includeShowInfo("Descripción", $this->data["DESCRIPCION_CALENDARIO"]);
            $this->includeShowList($this->data["resources"], "Recursos asociados", "Todavía no hay ningún recurso ascociado a este calendario.", "NOMBRE_RECURSO", "ID_RECURSO" );
        echo "</div>";
    }
}
?>