<?php

include_once './VIEW/BaseView.php';

class UsuariosAddView extends BaseView{

    protected $jsFiles = array ("./VIEW/webroot/js/md5.js"); 

    protected function body(){
        $this->includeTitle("i18n-newUser", "h1");
        ?>
            <form id="addForm" name="addForm" action="index.php" method="post">
                <div>
                    <?php
                        $this->includeTextField("i18n-login", 'LOGIN_USUARIO');
                        $this->includePasswordField("i18n-password", 'PASSWD_USUARIO');
                        $this->includeTextField("i18n-nombre", 'NOMBRE_USUARIO');
                        $this->includeTextField("i18n-email", 'EMAIL_USUARIO');
                    ?>
                </div>
                <div>
                    <?php
                        $this->includeSelectField("i18n-tipo", 'TIPO_USUARIO', $this->data["userTypes"], false);
                    ?>
                    <div id="respAtributes"></div>
                </div>
                <script>
                    $("#TIPO_USUARIO").change(function () {
                        var type = $(this).val();
                        if(type == "RESPONSABLE"){
                            $("#respAtributes").append('<?= $this->includeTextField("i18n-address", 'DIRECCION_RESPONSABLE')?>');
                            $("#respAtributes").append('<?= $this->includeTextField("i18n-phone", 'TELEFONO_RESPONSABLE')?>');
                        }else{
                            document.getElementById("respAtributes").innerHTML = '';
                        }
                        setLang();
                    });          
                </script>
            </form>
            <span class="<?=$this->icons["ADD"]?>" onclick="sendCredentialsForm(document.addForm, 'UsuariosController', 'add', checkUsersAddForm())"></span>
        <?php
        $this->includeValidationModal();
    }

}
?>