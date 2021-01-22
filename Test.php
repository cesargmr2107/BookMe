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
        echo "<h2>Test de entidad: $model</h2>";
        echo "<h3>Test de acción</h3>";
        echo "<h3>Test de atributos</h3>";
        echo "<div id='atribute-tests'>";
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
                            } else {
                                echo "<td class='expected'>" . $test["expected"] . " - <span class='i18n-" . $test["expected"] . "'></span></td>";
                            }
                            if($test["obtained"] === "true"){
                                echo "<td class='obtained'>true</td>";
                            }else{
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
        echo "<div>";
    }

    private function doAtributesTest($modelFile, $checks){
        $model = new $modelFile();
        foreach ($checks as $atribute => $tests) {
            foreach ($tests as $index => $test) {
                $result = $model->doAtributeChecks($atribute, $test["value"]);
                $checks[$atribute][$index]["obtained"] = $result;
            }
        }
        return $checks;
    }

    private function testUsuariosModel(){

    }

    private function testCalendariosModel(){
        
        $checks = [
            "ID_CALENDARIO" => [
                ["value" => "1", "expected" => "true"],
                ["value" => "cal1", "expected" => "AT101"],
            ],
            "NOMBRE_CALENDARIO" => [
                ["value" => "Calendario de verano", "expected" => "true"],
                ["value" => "Corto", "expected" => "AT111"],
                ["value" => "Un nombre demasiado largo para el calendario", "expected" => "AT111"],
                ["value" => "Calendario*_1", "expected" => "AT112"],
            ],
            "DESCRIPCION_CALENDARIO" => [
                ["value" => "Esta es una descripción correcta de calendario", "expected" => "true"],
                ["value" => "Esta es una descripción incorrecta de calendario porque es demasiado larga ya que supera el máximo de caracteres", "expected" => "AT121"],
                ["value" => "Esta / es + una * descripción 12 incorrecta & de % calendario", "expected" => "AT122"],
            ],
            "FECHA_INICIO_CALENDARIO" => [
                ["value" => "21/07/1999", "expected" => "true"],
                ["value" => "1999-07-21", "expected" => "AT131"],
                ["value" => "21-07-1999", "expected" => "AT131"],
                ["value" => "Esto no es una fecha", "expected" => "AT131"],
            ],
            "FECHA_FIN_CALENDARIO" => [
                ["value" => "21/07/1999", "expected" => "true"],
                ["value" => "1999-07-21", "expected" => "AT141"],
                ["value" => "21-07-1999", "expected" => "AT141"],
                ["value" => "Esto no es una fecha", "expected" => "AT141"],
            ],
            "HORA_INICIO_CALENDARIO" => [
                ["value" => "10:00:00", "expected" => "true"],
                ["value" => "11:30:00", "expected" => "AT151"],
                ["value" => "14:37", "expected" => "AT151"],
                ["value" => "Esto no es una hora", "expected" => "AT151"],
            ],
            "HORA_FIN_CALENDARIO" => [
                ["value" => "10:00:00", "expected" => "true"],
                ["value" => "11:30:00", "expected" => "AT161"],
                ["value" => "14:37", "expected" => "AT161"],
                ["value" => "Esto no es una hora", "expected" => "AT161"],
            ]
        ];

        return $this->doAtributesTest("CalendariosModel", $checks);
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