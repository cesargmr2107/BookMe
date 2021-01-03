<?php

include_once './VIEW/BaseView.php';

class UsuariosShowView extends BaseView{

    protected function body(){
        
        // DEBUG: Check data passed to view
        // echo '<pre>' . var_export($this->data, true) . '</pre>';
        
        $this->includeTitle("Perfil de usuario", "h1");

        echo "<div>";
            $this->includeShowInfo("Login", $this->data["normal_info"]["LOGIN_USUARIO"]);
            $this->includeShowInfo("Nombre", $this->data["normal_info"]["NOMBRE_USUARIO"]);
            $this->includeShowInfo("Correo electrónico", $this->data["normal_info"]["EMAIL_USUARIO"]);
            $this->includeShowInfo("Tipo de usuario", $this->data["normal_info"]["TIPO_USUARIO"]);
            $this->includeShowInfo("Activo", $this->data["normal_info"]["ES_ACTIVO"]);
            if($this->data["normal_info"]["TIPO_USUARIO"] === "RESPONSABLE"){
                $this->includeShowInfo("Dirección", $this->data["resp_info"]["DIRECCION_RESPONSABLE"]);
                $this->includeShowInfo("Teléfono", $this->data["resp_info"]["TELEFONO_RESPONSABLE"]);
                $this->includeShowList( $this->data["resp_info"]["resources"],
                                        "Recursos bajo su responsabilidad",
                                        "Todavía no es responsable de ningún recurso.",
                                        "NOMBRE_RECURSO",
                                        "ID_RECURSO" );
            }
        echo "</div>";
    }
}
?>