<?php

include_once './VIEW/BaseView.php';

class ReservasShowView extends BaseView{

    protected function body(){
        
        // DEBUG: Check data passed to view
        // echo '<pre>' . var_export($this->data, true) . '</pre>';
        
        $this->includeTitle("Detalles de reserva", "h1");

        if(array_key_exists("user", $this->data)){
            echo '<div>';
                $this->includeTitle("Solicitante", "h4");
                $this->includeShowInfo("Login", $this->data["user"]["LOGIN_USUARIO"]);
                $this->includeShowInfo("Nombre", $this->data["user"]["NOMBRE_USUARIO"]);
                $this->includeShowInfo("Correo electrónico", $this->data["user"]["EMAIL_USUARIO"]);
            echo '</div>';           
        }

        echo '<div>';
            $this->includeTitle("Solicitud", "h4");
            $this->includeShowInfo("Fecha de solicitud", $this->data["FECHA_SOLICITUD_RESERVA"]);
            $this->includeShowInfo("Estado", $this->data["ESTADO_RESERVA"]);
            $msgResponseDate = ($this->data["FECHA_RESPUESTA_RESERVA"] === null) ?
                                "Todavía no hay respuesta" : $this->data["FECHA_RESPUESTA_RESERVA"];
            $this->includeShowInfo("Fecha de respuesta", $msgResponseDate);
            if($this->data["MOTIVO_RECHAZO_RESERVA"] !== null){
                $this->includeShowInfo("Motivo de rechazo", $this->data["MOTIVO_RECHAZO_RESERVA"]);
            }
        echo '</div>';

        echo '<div>';
            $this->includeTitle("Recurso", "h4");
            $this->includeShowInfo("ID", $this->data["resource"]["ID_RECURSO"]);
            $this->includeShowInfo("Nombre", $this->data["resource"]["NOMBRE_RECURSO"]);
            $this->includeShowInfo("Responsable", $this->data["resource"]["LOGIN_RESPONSABLE"]);
        echo '</div>';

        foreach ($this->data["subreservas"] as $subreserva) {
            ?>
                <div>
                    <p><?=$subreserva["FECHA_INICIO_SUBRESERVA"]?> - <?=$subreserva["FECHA_FIN_SUBRESERVA"]?></p>
                    <p><?=$subreserva["HORA_INICIO_SUBRESERVA"]?> - <?=$subreserva["HORA_FIN_SUBRESERVA"]?></p>
                    <p><?=$subreserva["COSTE_SUBRESERVA"]?>€</p>
                </div>
            <?php
        }

        $this->includeTitle("Coste total de la reserva: " . $this->data["COSTE_RESERVA"], "h4");
        
    }
}
?>