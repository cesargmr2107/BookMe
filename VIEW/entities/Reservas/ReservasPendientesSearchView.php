<?php

include_once './VIEW/BaseView.php';

class ReservasPendientesSearchView extends BaseView{

    protected function body(){
        
        $this->includeTitle("Recursos con solicitudes pendientes", "h1");
        
        // DEBUG: Check data passed to view
        // echo '<pre>' . var_export($this->data, true) . '</pre>';

        foreach($this->data as $resource){
            echo "<div>";
                echo "<strong>" . $resource["NOMBRE_RECURSO"] . "</strong>";
                echo "<p>";
                    echo "<span>" . $resource["COUNT"] . "</span>";
                    echo "<span> solicitudes pendientes</span>";
                echo "</p>";
            echo "</div>";
        }
    }
}
?>