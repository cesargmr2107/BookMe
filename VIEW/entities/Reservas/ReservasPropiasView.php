<?php

include_once './VIEW/BaseView.php';

class ReservasPropiasView extends BaseView{

    protected function body(){
        
        $this->includeTitle("i18n-myBookings", "h1");
        
        $this->includeButton("BOOKING", "goToAddForm", "post", "ReservasController", "addForm");

        $titleCodes = array(
            "PENDIENTE" => "i18n-pending",
            "ACEPTADA" => "i18n-accepted",
            "RECHAZADA" => "i18n-rejected",
            "CANCELADA" => "i18n-canceled",
            "RECURSO_USADO" => "i18n-used",
            "RECURSO_NO_USADO" => "i18n-unused"
        );
        
        foreach($this->data as $category => $bookings){
            $this->includeTitle($titleCodes[$category], "h3");
            if(count($bookings)){
                foreach ($bookings as $booking) {
                    $this->includeBookingInfo($booking);
                }
            }else{
                $this->includeTitle("i18n-noBookings", "h6");
            }
        }
    }

    protected function includeBookingInfo($booking){
        ?>
            <div>
                <strong><?=$booking["NOMBRE_RECURSO"]?></strong>
                <?= $this->includeButton("SHOW", "goToShow" . $booking["ID_RESERVA"], "post", "ReservasController", "show", array( "ID_RESERVA" => $booking["ID_RESERVA"] )) ?>
                <p><?= $this->formatDate($booking["FECHA_SOLICITUD_RESERVA"]) ?></p>
            </div>
        <?php
    }
}
?>