<?php

include_once './VIEW/BaseView.php';

class CalendariosSearchView extends BaseView{

    protected function body(){

        ?>
            <form name="goToAddForm" action="index.php" method="post">
                <span class="fas fa-plus-square" onclick="sendForm(document.goToAddForm, 'CalendariosController', 'add', true)"></span>
            </form>
        <?php

        // CRUD TABLE
        echo "<table>";

            // Headers
            echo "<tr>";
            foreach($this->data["atributeNames"] as $atribute){
                echo "<th>" . $atribute . "</th>";
            }
            echo "<th>Opciones</th>";
            echo "</tr>";

            // Rows
            foreach($this->data["result"] as $row){
                echo "<tr>";

                    // Atribute columns
                    foreach($row as $atribute){
                        $id = $row["ID_CALENDARIO"];
                        echo "<td>" . $atribute ."</td>";
                    }

                    //Option Column
                    echo "<td>";
                        $this->includeButton("far fa-eye", "goToShow$id", "post", "CalendariosController", "show", array ("ID_CALENDARIO" => $id) );
                        if($_SESSION["TIPO_USUARIO"] === "ADMINISTRADOR"){
                            $name = $row["NOMBRE_CALENDARIO"];
                            $this->includeDeleteModal($id, $name);
                        }
                    echo '</td>';

                echo "</tr>";
            }
        echo '</table>';
    }

    private function includeDeleteModal($id, $name){
    ?>
        <!-- Delete button -->
        <span class="far fa-trash-alt" data-toggle="modal" href="#deleteModal<?= $id ?>"></span>

        <!-- Delete modal -->
        <div class="modal" id="deleteModal<?= $id ?>">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                
                    <!-- Modal Header  -->
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">¿Estás seguro de que quieres borrar '<?= $name?>'?</h4>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <span class="far fa-times-circle" data-dismiss="modal"></span>
                        <?= $this->includeButton("far fa-check-circle", "deleteForm$id", "post", "CalendariosController", "delete" ) ?>
                    </div>

                </div>
            </div>
        </div>
    <?php
    }
}
?>