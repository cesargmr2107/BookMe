<?php

include_once './VIEW/authentication/AuthenticationView.php';

class LoginView extends AuthenticationView{

    protected function body(){
        $this->includeValidationModal();
        ?>
            <h1>
                <span class='i18n-welcome'></span>
                <span>BookMe</span>
            </h1>
            <form name="loginForm" action="index.php" method="post">
                <?php
                $this->includeTextField("i18n-login","LOGIN_USUARIO");
                $this->includePasswordField("i18n-password","PASSWD_USUARIO");
                ?>
                <span class="<?=$this->icons["LOGIN"]?>" onclick="sendCredentialsForm(document.loginForm, 'AuthenticationController', 'login', checkLoginForm())"></span>
            </form>
        <?php
        $this->includeLink("i18n-goToRegister", "goToRegister", "post", "AuthenticationController", "registerForm");
    }
}
?>