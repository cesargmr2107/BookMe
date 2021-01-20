<?php

include_once './VIEW/authentication/AuthenticationView.php';

class RegisterView extends AuthenticationView{

    protected function body(){
        $this->includeValidationModal();
        ?>
            <h1 id="welcome-title">
                <span class='i18n-welcome'></span>
                <span>BookMe</span>
            </h1>
            <form name="registerForm" action="index.php" method="post">
                <h3 class="i18n-goToRegister"></h3>
                <?php
                $this->includeTextField("i18n-login","LOGIN_USUARIO");
                $this->includeTextField("i18n-nombre","NOMBRE_USUARIO");
                $this->includeTextField("i18n-email","EMAIL_USUARIO");
                $this->includePasswordField("i18n-password","PASSWD_USUARIO");
                ?>
                <span class="<?=$this->icons["LOGIN"]?>" onclick="sendCredentialsForm(document.registerForm, 'AuthenticationController', 'register', checkRegisterForm())"></span>
            </form>
        <?php
        $this->includeLink("i18n-goToLogin", "goToLogin", "post", "AuthenticationController", "loginForm");
    }
}
?>