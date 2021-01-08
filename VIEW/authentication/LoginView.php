<?php

include_once './VIEW/authentication/AuthenticationView.php';

class LoginView extends AuthenticationView{

    protected function body(){
        $this->includeTitle("<span>Bienvenido a </span><span>BookMe</span>", "h1");
        ?>
            <form name="loginForm" action="index.php" method="post">
                <?php
                $this->includeTextField("Login","LOGIN_USUARIO");
                $this->includePasswordField("Contraseña","PASSWD_USUARIO");
                ?>
                <span class="<?=$this->icons["LOGIN"]?>" onclick="sendCredentialsForm(document.loginForm, 'AuthenticationController', 'login', true)"></span>
            </form>
        <?php
        $this->includeLink("Crear cuenta", "goToRegister", "post", "AuthenticationController", "registerForm");
    }
}
?>