<?php

include_once './VIEW/BaseView.php';

class UsuariosAddView extends BaseView{

    protected $jsFiles = array ("./VIEW/js/md5.js"); 

    protected function body(){
        $this->includeTitle("Añadir nuevo usuario", "h1");
        ?>
            <form id="addForm" name="addForm" action="index.php" >
                <?php
                    $this->includeTextField('Login', 'LOGIN_USUARIO');
                    $this->includeTextField('Nombre', 'NOMBRE_USUARIO');
                    $this->includePasswordField('Contraseña', 'PASSWD_USUARIO');
                    $this->includeTextField('Correo electrónico', 'EMAIL_USUARIO');
                    $this->includeSelectField('Tipo de usuario', 'TIPO_USUARIO', $this->data["userTypes"], false);
                ?>
                <div id="respAtributes"></div>
                <script>
                    $("#TIPO_USUARIO").change(function () {
                        var type = $(this).val();
                        if(type == "RESPONSABLE"){
                            $("#respAtributes").append('<?= $this->includeTextField('Dirección', 'DIRECCION_RESPONSABLE')?>');
                            $("#respAtributes").append('<?= $this->includeTextField('Teléfono', 'TELEFONO_RESPONSABLE')?>');
                        }else{
                            document.getElementById("respAtributes").innerHTML = '';
                        }
                    });          
                </script>
                <span class="<?=$this->icons["ADD"]?>" onclick="sendCredentialsForm(document.addForm, 'UsuariosController', 'add', true)"></span>
            </form>
        <?php
    }

}
?>