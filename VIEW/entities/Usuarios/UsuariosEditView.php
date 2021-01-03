<?php

include_once './VIEW/BaseView.php';

class UsuariosEditView extends BaseView{

    protected $jsFiles = array ("md5.js"); 

    protected function body(){
        $this->includeTitle("Modificar información de usuario", "h1");
        ?>
            <form id="editForm" name="editForm" action="index.php" >
                <?php
                    $this->includeReadOnlyField('Login', 'LOGIN_USUARIO', $this->data["normal_info"]["LOGIN_USUARIO"]);
                    $this->includeReadOnlyField('Tipo', 'TIPO_USUARIO', $this->data["normal_info"]["TIPO_USUARIO"]);
                    $this->includeTextField('Nombre', 'NOMBRE_USUARIO', $this->data["normal_info"]["NOMBRE_USUARIO"]);
                    $this->includePasswordField('Contraseña', 'PASSWD_USUARIO');
                    $this->includeTextField('Correo electrónico', 'EMAIL_USUARIO', $this->data["normal_info"]["EMAIL_USUARIO"]);
                    if($this->data["normal_info"]["TIPO_USUARIO"] === 'RESPONSABLE'){
                        $this->includeTextField('Direccion', 'DIRECCION_RESPONSABLE', $this->data["resp_info"]["DIRECCION_RESPONSABLE"]);
                        $this->includeTextField('Teléfono', 'TELEFONO_RESPONSABLE', $this->data["resp_info"]["TELEFONO_RESPONSABLE"]);
                    }
                ?>
                <span class="<?=$this->icons["EDIT"]?>" onclick="sendCredentialsForm(document.editForm, 'UsuariosController', 'edit', true)"></span>
            </form>
        <?php
    }

}
?>