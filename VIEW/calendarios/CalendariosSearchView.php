<?php

include_once './VIEW/BaseView.php';

class CalendariosSearchView extends BaseView{

    protected function body(){

        $this->includeButton("ADD", "goToAddForm", "post", "CalendariosController", "addForm");

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
                        $this->includeButton("SHOW", "goToShow$id", "post", "CalendariosController", "show", array ("ID_CALENDARIO" => $id) );
                        if($_SESSION["TIPO_USUARIO"] === "ADMINISTRADOR"){
                            $name = $row["NOMBRE_CALENDARIO"];
                            $this->includeDeleteModal($id, $name, "CalendariosController");
                        }
                    echo '</td>';

                echo "</tr>";
            }
        echo '</table>';
    }

}
?>