<?php

include_once './VIEW/BaseView.php';

class CalendariosSearchView extends BaseView{

    function body(){
        echo "<table>";
        echo "<tr>";
        foreach($this->data["atributeNames"] as $atribute){
            echo "<th>" . $atribute . "</th>";
        }
        echo "<th>Opciones</th>";
        echo "</tr>";
        foreach($this->data["result"] as $row){
            echo "<tr>";
            foreach($row as $atribute){
                $id = $row["ID_CALENDARIO"];
                echo "<td>" . $atribute ."</td>";
            }
            ?>
            <td>
                <form name="goToShow<?= $id ?>" action="index.php" method="post">
                    <input type="hidden" name="ID_CALENDARIO" value="<?= $id ?>"/>
                    <span class="far fa-eye" onclick="sendForm(document.goToShow<?= $id ?>, 'CalendariosController', 'show', true)"></span>
                </form>
            </td> 
            <?php
            echo "</tr>";
        }
        echo '</table>';
    }
}
?>