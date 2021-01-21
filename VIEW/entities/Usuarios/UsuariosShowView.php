<?php

include_once './VIEW/BaseView.php';

class UsuariosShowView extends BaseView{

    protected function body(){
        
        // DEBUG: Check data passed to view
        // echo '<pre>' . var_export($this->data, true) . '</pre>';
        
        $this->includeTitle("i18n-userProfile", "h1");

        echo "<div id='user-info-container'>";
            $this->includeShowInfo("i18n-login", $this->data["normal_info"]["LOGIN_USUARIO"]);
            $this->includeShowInfo("i18n-nombre", $this->data["normal_info"]["NOMBRE_USUARIO"]);
            $this->includeShowInfo("i18n-email", $this->data["normal_info"]["EMAIL_USUARIO"]);
            $this->includeShowInfo("i18n-tipo", $this->data["normal_info"]["TIPO_USUARIO"]);
            $this->includeShowInfo("i18n-active", $this->data["normal_info"]["ES_ACTIVO"]);
            if($this->data["normal_info"]["TIPO_USUARIO"] === "RESPONSABLE"){
                $this->includeShowInfo("i18n-address", $this->data["resp_info"]["DIRECCION_RESPONSABLE"]);
                $this->includeShowInfo("i18n-phone", $this->data["resp_info"]["TELEFONO_RESPONSABLE"]);
                $this->includeShowList( $this->data["resp_info"]["resources"],
                                        "i18n-respResources",
                                        "i18n-noRespResources",
                                        "NOMBRE_RECURSO",
                                        "ID_RECURSO" );
            }
        echo "</div>";
    }
}
?>