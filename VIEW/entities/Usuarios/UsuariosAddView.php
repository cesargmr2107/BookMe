<?php

include_once './VIEW/BaseView.php';

class UsuariosAddView extends BaseView{

    protected $jsFiles;

    protected function body(){
        $this->includeTitle("Añadir nuevo usuario", "h1");
        ?>
            <form id="addForm" name="addForm" action="index.php" >
                <?php
                    $this->includeTextField('Login', 'LOGIN_USUARIO');
                    $this->includeTextField('Nombre', 'NOMBRE_USUARIO');
                    $this->includeSelectField('Correo electrónico', 'ID_CALENDARIO', $this->data["calendars"], true);
                    $this->includeTextField('Tarifa', 'TARIFA_RECURSO');
                    $this->includeSelectField('Rango de tarifa', 'RANGO_TARIFA_RECURSO', $this->data["priceRanges"], false);
                    $this->includeSelectField('Responsable', 'LOGIN_RESPONSABLE', $this->data["responsables"], true);
                ?>
                <div style="display:none">

                </div>
                <span class="<?=$this->icons["ADD"]?>" onclick="sendForm(document.addForm, 'UsuariosController', 'add', true)"></span>
            </form>
        <?php
    }

}
?>