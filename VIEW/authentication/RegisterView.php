<?php

include_once './VIEW/authentication/AuthenticationView.php';

class RegisterView extends AuthenticationView{

    protected function body(){
        $this->includeTitle("Bienvenido a <span>BookMe</span>", "h1");
        ?>
            <form name="registerForm" action="index.php" method="post">
                <?php
                $this->includeTextField("Login","LOGIN_USUARIO");
                $this->includeTextField("Nombre","NOMBRE_USUARIO");
                $this->includeTextField("Correo electrónico","EMAIL_USUARIO");
                $this->includePasswordField("Contraseña","PASSWD_USUARIO");
                ?>
                <span class="<?=$this->icons["LOGIN"]?>" onclick="sendCredentialsForm(document.registerForm, 'AuthenticationController', 'register', true)"></span>
            </form>
        <?php
        $this->includeLink("Iniciar sesión", "goToLogin", "post", "AuthenticationController", "loginForm");
    }
}
?>