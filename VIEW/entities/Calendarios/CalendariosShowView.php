<?php

include_once './VIEW/BaseView.php';

class CalendariosShowView extends BaseView{

    protected function body(){
        
        // DEBUG: Check data passed to view
        // echo '<pre>' . var_export($this->data, true) . '</pre>';
        
        $this->includeTitle("i18n-calendarInfo", "h1");
        echo "<div id='calendar-details'>";
            echo "<div>";
                $this->includeShowInfo("i18n-id", $this->data["ID_CALENDARIO"]);
                $this->includeShowInfo("i18n-nombre", $this->data["NOMBRE_CALENDARIO"]);
                $this->includeShowDate("i18n-fecha_inicio", $this->data["FECHA_INICIO_CALENDARIO"]);
                $this->includeShowDate("i18n-fecha_fin", $this->data["FECHA_FIN_CALENDARIO"]);
                $this->includeShowInfo("i18n-hora_inicio", $this->data["HORA_INICIO_CALENDARIO"]);
                $this->includeShowInfo("i18n-hora_fin", $this->data["HORA_FIN_CALENDARIO"]);
            echo "</div>";
            echo "<div>";
                $this->includeShowInfo("i18n-descripcion", $this->data["DESCRIPCION_CALENDARIO"]);
                $this->includeShowList($this->data["resources"], "i18n-assocResources", "i18n-nonAssocResources", "NOMBRE_RECURSO", "ID_RECURSO" );
            echo "</div>";
        echo "</div>";

        if(isAdminUser()){
            // Links
            $this->includeTitle("i18n-options", "h3");
            echo "<div class='show-options'>";
                $controller = "CalendariosController";
                $idAtribute = "ID_CALENDARIO";
                $id = $this->data["ID_CALENDARIO"];
                $this->includeButton("EDIT", "editBt", "post", $controller, "editForm", array ($idAtribute => $id));
                $this->includeDeleteButtonAndModal($idAtribute, $id, $this->data["NOMBRE_CALENDARIO"], $controller);
            echo "</div>";
        }
    }
}
?>