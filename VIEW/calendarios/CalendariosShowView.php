<?php

include_once './VIEW/BaseView.php';

class CalendariosShowView extends BaseView{

    protected function body(){
        
        // DEBUG: Check data passed to view
        // echo '<pre>' . var_export($this->data, true) . '</pre>';
        
        ?>
            <h1>Detalles de calendario</h1>
            
            <div>
                <p>
                    <strong>ID:</strong>
                    <span><?= $this->data["ID_CALENDARIO"]?></span>
                </p>
                <p>
                    <strong>Nombre</strong>
                    <span><?= $this->data["NOMBRE_CALENDARIO"]?></span>
                </p>
                <p>
                    <strong>Fecha de inicio:</strong>
                    <span><?= $this->data["FECHA_INICIO_CALENDARIO"]?></span>
                </p>
                <p>
                    <strong>Hora de inicio:</strong>
                    <span><?= $this->data["HORA_INICIO_CALENDARIO"]?></span>
                </p>
                <p>
                    <strong>Hora de fin:</strong>
                    <span><?= $this->data["HORA_FIN_CALENDARIO"]?></span>
                </p>
            </div>

            <div>
                <p>
                    <strong>Descripción:</strong>
                    <span><?= $this->data["DESCRIPCION_CALENDARIO"]?></span>
                </p>
                <p>
                    <strong>Recursos asciados:<strong>
                <?php
                    if(!count($this->data["resources"])){
                    ?>
                        <span>Todavía no hay ningún recurso ascociado a este calendario.</span>
                    </p>
                    <?php
                    }else{
                        echo '</p>';
                        echo '<ul>';
                        foreach ($this->data["resources"] as $resource) {
                            echo '<li>' . $resource["ID_RECURSO"] . ': ' . $resource["NOMBRE_RECURSO"] . '</li>';
                        }
                        echo '</ul>';
                    }
                ?>
            </div>

        <?php
    }
}
?>