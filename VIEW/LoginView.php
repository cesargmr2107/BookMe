<?php

class LoginView{

    function __construct(){
        $this->render();
    }

    function render(){
    ?>
        <html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es">
            
            <head>
                <!-- Required meta tags -->
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

                <!-- Bootstrap CSS -->
                <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

                <!-- Font awesome icons -->
                <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
                <script src="https://kit.fontawesome.com/ae3641038e.js" crossorigin="anonymous"></script>

                <!-- My scripts -->
                <script type="text/javascript" src="./VIEW/js/common.js"></script> 

            </head>

            <body>
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
                    <!-- Hidden fields -->
                    <input type="hidden" name="controller" value="LoginController"/>
                    <input type="hidden" name="action" value="login"/>
                    <!-- Login button -->
                    <span class="fas fa-sign-in-alt" onclick="sendForm(document.loginForm, true)"></span>
                </form>
            </body>

        </html>
    <?php
    }
}
?>