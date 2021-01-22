<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include './VIEW/BaseView.php';

include './MODEL/UsuariosModel.php';
include './MODEL/CalendariosModel.php';
include './MODEL/ResponsablesModel.php';
include './MODEL/RecursosModel.php';
include './MODEL/ReservasModel.php';
include './MODEL/SubreservasModel.php';

class Test extends BaseView{

    protected $cssFiles = ["./VIEW/webroot/css/test.css"];

    private static $models = [
        "CalendariosModel",
        "RecursosModel",
        "ReservasModel",
        "ResponsablesModel",
        "SubreservasModel",
        "UsuariosModel"
    ];

    protected function render(){
        $this->header();
        $this->body();
        $this->footer();
    }

    protected function body(){
        echo "<h1>Test de modelos</h1>";
        foreach (self::$models as $model) {
            $results = call_user_func_array([$this,"test$model"],[]);
            $this->showResults($model, $results);
        }
    }

    private function showResults($model, $results){
        ?>
            <h3>Test de entidad: <?=$model?></h3>
            <h4>Test de atributos</h4>
        <?php
            foreach($results as $atribute => $tests){
                ?>
                    <table class="table">
                        <tr>
                            <th colspan="4"><?=$atribute?></th>
                        </tr>
                        <tr>
                            <th class="value">Valor</th>
                            <th class="expected">Esperado</th>
                            <th class="obtained">Obtenido</th> 
                            <th class="test-result">Resultado</th> 
                        </tr>
                        <?php
                            foreach($tests as $test){
                                echo "<tr>";
                                echo "<td class='value'>" . $test["value"] . "</td>";
                                if ($test["expected"] === "true") {
                                    echo "<td class='expected'>true</td>";
                                    echo "<td class='obtained'>" . $test["obtained"] . "</td>";
                                } else {
                                    echo "<td class='expected'>" . $test["expected"] . " - <span class='i18n-" . $test["expected"] . "'></span></td>";
                                    echo "<td class='obtained'>" . $test["obtained"] . " - <span class='i18n-" . $test["obtained"] . "'></span></td>";
                                }
                                if ($test["expected"] === $test["obtained"]) {
                                    echo "<td class='test-result ok'>OK</td>";
                                } else {
                                    echo "<td class='test-result not-ok'>NOT OK</td>";
                                }
                                echo "</tr>";
                            }
                        ?>
                    </table>
                <?php
            }
        ?>
            
        <?php
        echo "<h4>Test de acción</h4>";
    }

    private function testUsuariosModel(){

    }

    private function testCalendariosModel(){
        
        $checks = [
            "NOMBRE_CALENDARIO" => [
                ["value" => "Calendario de verano", "expected" => "true"],
                ["value" => "Corto", "expected" => "AT111"],
                ["value" => "Un nombre demasiado largo para el calendario", "expected" => "AT111"],
                ["value" => "Calendario*_1", "expected" => "AT112"],
            ]
        ];
            
        $calendar = new CalendariosModel();
        foreach ($checks as $atribute => $tests) {
            foreach ($tests as $index => $test) {
                $result = $calendar->doAtributeChecks($atribute, $test["value"]);
                $checks[$atribute][$index]["obtained"] = $result;
            }
        }

        echo "<pre>" . var_export($checks, true) . "</pre>";

        return $checks;
    }

    private function testResponsablesModel(){

    }

    private function testRecursosModel(){

    }

    private function testReservasModel(){

    }

    private function testSubreservasModel(){

    }


}

new Test();

?>