<?php

include_once './VIEW/BaseView.php';

class ReservasPendientesSearchView extends BaseView{

    protected function body(){
        
        $this->includeTitle("Recursos con solicitudes pendientes", "h1");
        
        // DEBUG: Check data passed to view
        // echo '<pre>' . var_export($this->data, true) . '</pre>';

        if(empty($this->data)){
            $this->includeTitle("No hay recursos que tengan solicitudes pendientes", "h4");
        }else{
            foreach($this->data as $resource){
                ?> 
                    <form name="goToManage<?=$resource["ID_RECURSO"]?>" action="index.php" method="post">
                        <?= $this->includeHiddenField("ID_RECURSO", $resource["ID_RECURSO"])?>
                        <div onclick="sendForm(document.goToManage<?=$resource['ID_RECURSO']?>, 'ReservasController', 'managePendingForm', true)">
                            <strong><?=$resource["NOMBRE_RECURSO"]?></strong>
                            <p>
                                <span><?=$resource["COUNT"]?></span>
                                <span> solicitudes pendientes</span>
                            </p>
                        </div>
                    </form>
                <?php
            }
        }
    }
}
?>