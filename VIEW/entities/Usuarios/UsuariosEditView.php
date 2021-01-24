<?php

include_once './VIEW/BaseView.php';

class UsuariosEditView extends BaseView{

    protected $jsFiles = array ("./VIEW/webroot/js/md5.js"); 

    protected function body(){
        $this->includeTitle("i18n-editUser", "h1");
        ?>
            <form id="editForm" name="editForm" action="index.php" method="post">
                <div>
                    <?php
                        $this->includeReadOnlyField("i18n-login", 'LOGIN_USUARIO', $this->data["normal_info"]["LOGIN_USUARIO"]);
                        $this->includeReadOnlyField("i18n-tipo", 'TIPO_USUARIO', $this->data["normal_info"]["TIPO_USUARIO"]);
                    ?>
                </div>
                <div>
                    <?php
                        $this->includeTextField("i18n-nombre", 'NOMBRE_USUARIO', $this->data["normal_info"]["NOMBRE_USUARIO"]);
                        $this->includePasswordField("i18n-password", 'PASSWD_USUARIO');
                        $this->includeTextField("i18n-email", 'EMAIL_USUARIO', $this->data["normal_info"]["EMAIL_USUARIO"]);
                    ?>
                </div>
                <?php
                    if($this->data["normal_info"]["TIPO_USUARIO"] === 'RESPONSABLE'){
                        echo "<div>";
                            $this->includeTextField("i18n-address", 'DIRECCION_RESPONSABLE', $this->data["resp_info"]["DIRECCION_RESPONSABLE"]);
                            $this->includeTextField("i18n-phone", 'TELEFONO_RESPONSABLE', $this->data["resp_info"]["TELEFONO_RESPONSABLE"]);
                        echo "</div>";
                    }
                ?>
            </form>
            <span class="<?=$this->icons["EDIT"]?>" onclick="sendCredentialsForm(document.editForm, 'UsuariosController', 'edit', checkUsersEditForm())"></span>
        <?php
        $this->includeValidationModal();
    }

}
?>