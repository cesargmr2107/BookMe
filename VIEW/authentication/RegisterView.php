<?php

include_once './VIEW/authentication/AuthenticationView.php';

class RegisterView extends AuthenticationView{

    protected function body(){
        $this->includeTitle("Bienvenido a <span>BookMe</span>", "h1");
        ?>
            <form name="registerForm" action="index.php" method="post">
                <?php
                $this->includeTextField("i18n-login","LOGIN_USUARIO");
                $this->includeTextField("i18n-name","NOMBRE_USUARIO");
                $this->includeTextField("i18n-email","EMAIL_USUARIO");
                $this->includePasswordField("i18n-password","PASSWD_USUARIO");
                ?>
                <span class="<?=$this->icons["LOGIN"]?>" onclick="sendCredentialsForm(document.registerForm, 'AuthenticationController', 'register', true)"></span>
            </form>
        <?php
        $this->includeLink("i18n-goToLogin", "goToLogin", "post", "AuthenticationController", "loginForm");
    }
}
?>