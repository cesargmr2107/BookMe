<?php

include_once './VIEW/BaseView.php';

class ReservasConfirmView extends BaseView{

    protected function body(){
        
        // DEBUG: Check data passed to view
        // echo '<pre>' . var_export($this->data, true) . '</pre>';
        
        $this->includeTitle("i18n-confirmBooking" , "h1");
        
        if(empty($this->data)){
            $this->includeTitle("i18n-bookingAllConfirmed", "h4");
        } else {
            foreach($this->data as $booking){
                $id = $booking["ID_RESERVA"];
                $date = $this->formatDate($booking["FECHA_SOLICITUD_RESERVA"]);
                $name = $booking["NOMBRE_RECURSO"];
                $user = $booking["LOGIN_USUARIO"];
                echo "<div class='confirm-use'>";
                    echo "<strong><span class='i18n-requestedOn'></span>$date</strong>";
                    echo "<div>$name, $user</div>";
                    echo "<div id='options'>";
                        $this->includePositiveConfirmation($id,$date,$name,$user);
                        $this->includeNegativeConfirmation($id,$date,$name,$user);
                    echo "</div>";
                echo "</div>";
            }
        }
        
    }

    protected function includePositiveConfirmation($id, $date, $name, $user){
        ?>
        <!-- Button -->
        <span class="<?= $this->icons["ACCEPT"]?> accept" data-toggle="modal" href="#usedModal<?=$id?>"></span>

        <!-- Modal -->
        <div class="modal" id="usedModal<?=$id?>">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                
                    <!-- Modal Header  -->
                    <div class="modal-header">
                        <h4 class="modal-title">
                            <span class="i18n-positiveConfirm"></span>
                            <strong><?=$user?></strong>
                            <span class="i18n-of"></span>
                            <strong><?=$name?></strong>
                            <span class="i18n-for"></span>
                            <strong><?=$date?></strong>?
                        </h4>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <span class="<?= $this->icons["CANCEL"] ?>" data-dismiss="modal"></span>
                        <?php
                            $data = array("ID_RESERVA" => $id);
                            $this->includeButton("ACCEPT", "usedModal$id","post", "ReservasController", "confirmUse", $data)
                        ?>
                    </div>

                </div>
            </div>
        </div>
    <?php
    }

    protected function includeNegativeConfirmation($id, $date, $name, $user){
        ?>
        <!-- Button -->
        <span class="<?= $this->icons["CANCEL"]?> cancel" data-toggle="modal" href="#notUsedModal<?=$id?>"></span>

        <!-- Modal -->
        <div class="modal" id="notUsedModal<?=$id?>">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                
                    <!-- Modal Header  -->
                    <div class="modal-header">
                        <h4 class="modal-title">
                            <span class="i18n-negativeConfirm"></span>
                            <strong><?=$user?></strong>
                            <span class="i18n-of"></span>
                            <strong><?=$name?></strong>
                            <span class="i18n-for"></span>
                            <strong><?=$date?></strong>?
                        </h4>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <span class="<?= $this->icons["CANCEL"] ?>" data-dismiss="modal"></span>
                        <?php
                            $data = array("ID_RESERVA" => $id);
                            $this->includeButton("ACCEPT", "notUsedModal$id","post", "ReservasController", "confirmNoUse", $data)
                        ?>
                    </div>

                </div>
            </div>
        </div>
    <?php
    }

}
?>