<?php

include_once './VIEW/authentication/AuthenticationView.php';

class RegisterView extends AuthenticationView{

    protected function body(){
        ?>
            <h1>Bienvenido a <span>BookMe</span></h1>

            <form name="registerForm" action="index.php" method="post">
                <!-- Login field -->
                <div class="form-group">
                    <label class='i18n-login-user-label' for="LOGIN_USUARIO">Login</label> 
                    <input type='text' name='LOGIN_USUARIO'/>
                </div>
                <!-- Email field -->
                <div class="form-group">
                    <label class='i18n-login-user-label' for="EMAIL_USUARIO">Correo electrónico</label> 
                    <input type='text' name='EMAIL_USUARIO'/>
                </div>
                <!-- Password field -->
                <div class="form-group">
                    <label class='i18n-login-user-label' for="PASSWD_USUARIO">Contraseña</label>
                    <input type='password' name='PASSWD_USUARIO'/>
                </div>
                <!-- Login button -->
                <span class="<?=$this->icons["LOGIN"]?>" onclick="sendCredentialsForm(document.registerForm, 'RegisterController', 'register', true)"></span>
            </form>

            <form name="goToLogin" action="index.php" method="post">
                <a onclick="sendForm(document.goToLogin, 'LoginController', 'loginForm', true)">Iniciar sesión</a>
            </form>
        <?php
    }
}
?>