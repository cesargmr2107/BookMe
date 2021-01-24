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
        // Links
        $this->includeTitle("i18n-options", "h3");
        echo "<div class='show-options'>";
            $controller = "UsuariosController";
            $idAtribute = "LOGIN_USUARIO";
            $id = $this->data["normal_info"]["LOGIN_USUARIO"];
            $this->includeButton("EDIT", "editBt", "post", $controller, "editForm", array ($idAtribute => $id));
            if(isAdminUser() && $_SESSION["LOGIN_USUARIO"]!=$this->data["normal_info"]["LOGIN_USUARIO"]){
                $this->includeDeleteButtonAndModal($idAtribute, $id, $this->data["normal_info"]["NOMBRE_USUARIO"], $controller);
            } else if(!isAdminUser() && !isRespUser()){
                $this->includeDeleteOwnProfileModal();
            }
        echo "</div>";
    }

    public function includeDeleteOwnProfileModal(){
        ?>
            <!-- Delete button -->
            <span class="<?= $this->icons["DELETE"]?>" data-toggle="modal" href="#deleteModal"></span>

            <!-- Delete modal -->
            <div class="modal" id="deleteModal">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                    
                        <!-- Modal Header  -->
                        <div class="modal-header">
                            <h4 class="modal-title">
                                <span class="i18n-deleteOwnConfirmation"></span>
                            </h4>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body options">
                            <?= $this->includeButton("ACCEPT", "deleteForm", "post", "UsuariosController", "delete", array("LOGIN_USUARIO" => $_SESSION["LOGIN_USUARIO"])) ?>
                            <span class="<?= $this->icons["CANCEL"] ?>" data-dismiss="modal"></span>
                        </div>

                    </div>
                </div>
            </div>
        <?php
    }
}
?>