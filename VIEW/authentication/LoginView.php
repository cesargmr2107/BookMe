<?php

include_once './VIEW/authentication/AuthenticationView.php';

class LoginView extends AuthenticationView{

    protected function body(){
        ?>
            <h1>Bienvenido a <span>BookMe</span></h1>

            <form name="loginForm" action="index.php" method="post">
                <!-- Login field -->
                <div class="form-group">
                    <label class='i18n-login-user-label' for="LOGIN_USUARIO">Login</label> 
                    <input type='text' name='LOGIN_USUARIO'/>
                </div>
                <!-- Password field -->
                <div class="form-group">
                    <label class='i18n-login-user-label' for="PASSWD_USUARIO">Contraseña</label>
                    <input type='password' name='PASSWD_USUARIO'/>
                </div>
                <!-- Login button -->
                <span class="fas fa-sign-in-alt" onclick="sendCredentialsForm(document.loginForm, 'LoginController', 'login', true)"></span>
            </form>

            <form name="goToRegister" action="index.php" method="post">
                <a onclick="sendForm(document.goToRegister, 'RegisterController', 'registerForm', true)">Crear cuenta</a>
            </form>
        <?php
    }
}
?>