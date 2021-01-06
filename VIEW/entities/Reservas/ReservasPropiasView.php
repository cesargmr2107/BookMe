<?php

include_once './VIEW/BaseView.php';

class ReservasPropiasView extends BaseView{

    protected function body(){
        
        $this->includeTitle("Mis solicitudes de reserva", "h1");
        
        $this->includeButton("BOOKING", "goToAddForm", "post", "ReservasController", "addForm");

        $titles = array(
            "PENDIENTE" => "Pendientes",
            "ACEPTADA" => "Aceptadas",
            "RECHAZADA" => "Rechazadas",
            "CANCELADA" => "Canceladas",
            "RECURSO_USADO" => "Con recurso usado",
            "RECURSO_NO_USADO" => "Con recurso no usado"
        );
        
        foreach($this->data as $category => $bookings){
            $this->includeTitle($titles[$category], "h3");
            if(count($bookings)){
                foreach ($bookings as $booking) {
                    $this->includeBookingInfo($booking);
                }
            }else{
                $this->includeTitle("No hay reservas", "h6");
            }
        }
    }

    protected function includeBookingInfo($booking){
        ?>
            <div>
                <strong><?= $booking["NOMBRE_RECURSO"] ?></strong>
                <?= $this->includeButton("SHOW", "goToShow" . $booking["ID_RESERVA"], "post", "ReservasController", "show", array( "ID_RESERVA" => $booking["ID_RESERVA"] )) ?>
                <p><?= $booking["FECHA_SOLICITUD_RESERVA"] ?></p>
            </div>
        <?php
    }
}
?>