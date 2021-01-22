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

    private $passedTests = 0;
    private $totalTests = 0;

    protected function render(){
        $this->header();
        $this->body();
        $this->footer();
    }

    protected function body(){
        foreach (self::$models as $model) {
            $results = call_user_func_array([$this,"test$model"],[]);
            $this->showResults($model, $results);
        }
        ?>
            <div id='brief'>
                <h2>Resumen de los tests</h2>
                <ul>
                    <li><strong>Tests superados:</strong> <?=$this->passedTests?></li>
                    <li><strong>Tests fallidos:</strong> <?=$this->totalTests - $this->passedTests?></li>
                    <li><strong>Tests totales:</strong> <?=$this->totalTests?></li>
                    <li><strong>Porcentaje de superación:</strong> <?=round(100*($this->passedTests/$this->totalTests),2)?>%</li>
                </ul>
            </div>
        <?php
    }

    private function showResults($model, $results){
        echo "<div class='atribute-tests'>";
        echo "<h2>Test de entidad: $model</h2>";
        echo "<h3>Test de acción</h3>";
        echo "<h3>Test de atributos</h3>";
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
                            if ($test["expected"] === true) {
                                echo "<td class='expected'>true</td>";
                            } else {
                                echo "<td class='expected'>" . $test["expected"] . " - <span class='i18n-" . $test["expected"] . "'></span></td>";
                            }
                            if($test["obtained"] === true || $test["obtained"] === false){
                                echo "<td class='obtained'>true</td>";
                            }else{
                                echo "<td class='obtained'>";
                                    foreach ($test["obtained"] as $error) {
                                        echo "<div>$error - <span class='i18n-$error'></span></div>";
                                    }
                                echo "</td>";
                            }
                            if ($test["expected"] === $test["obtained"] || (is_array($test["obtained"]) && in_array($test["expected"], $test["obtained"]))) {
                                echo "<td class='test-result ok'>OK</td>";
                                $this->passedTests++;
                            } else {
                                echo "<td class='test-result not-ok'>NOT OK</td>";
                            }
                            $this->totalTests++;
                            echo "</tr>";
                        }
                    ?>
                </table>
            <?php
        }
        echo "</div>";
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

    private function testCalendariosModel(){
        
        $checks = [
            "ID_CALENDARIO" => [
                ["value" => "1", "expected" => true],
                ["value" => "cal1", "expected" => "AT101"],
            ],
            "NOMBRE_CALENDARIO" => [
                ["value" => "Calendario de verano", "expected" => true],
                ["value" => "Corto", "expected" => "AT111"],
                ["value" => "Un nombre demasiado largo para el calendario", "expected" => "AT111"],
                ["value" => "Calendario*_1", "expected" => "AT112"],
            ],
            "DESCRIPCION_CALENDARIO" => [
                ["value" => "Esta es una descripción correcta de calendario", "expected" => true],
                ["value" => "Esta es una descripción incorrecta de calendario porque es demasiado larga ya que supera el máximo de caracteres", "expected" => "AT121"],
                ["value" => "Esta / es + una * descripción 12 incorrecta & de % calendario", "expected" => "AT122"],
            ],
            "FECHA_INICIO_CALENDARIO" => [
                ["value" => "21/07/1999", "expected" => true],
                ["value" => "1999-07-21", "expected" => "AT131"],
                ["value" => "21-07-1999", "expected" => "AT131"],
                ["value" => "Esto no es una fecha", "expected" => "AT131"],
            ],
            "FECHA_FIN_CALENDARIO" => [
                ["value" => "21/07/1999", "expected" => true],
                ["value" => "1999-07-21", "expected" => "AT141"],
                ["value" => "21-07-1999", "expected" => "AT141"],
                ["value" => "Esto no es una fecha", "expected" => "AT141"],
            ],
            "HORA_INICIO_CALENDARIO" => [
                ["value" => "10:00:00", "expected" => true],
                ["value" => "11:30:00", "expected" => "AT151"],
                ["value" => "14:37", "expected" => "AT151"],
                ["value" => "Esto no es una hora", "expected" => "AT151"],
            ],
            "HORA_FIN_CALENDARIO" => [
                ["value" => "10:00:00", "expected" => true],
                ["value" => "11:30:00", "expected" => "AT161"],
                ["value" => "14:37", "expected" => "AT161"],
                ["value" => "Esto no es una hora", "expected" => "AT161"],
            ]
        ];

        return $this->doAtributesTest("CalendariosModel", $checks);
    }

    private function testRecursosModel(){
        
        $checks = [
            "ID_RECURSO" => [
                ["value" => "1", "expected" => true],
                ["value" => "rec1", "expected" => "AT201"],
            ],
            "NOMBRE_RECURSO" => [
                ["value" => "Recurso de la app", "expected" => true],
                ["value" => "Corto", "expected" => "AT211"],
                ["value" => "Un nombre demasiado largo para el recurso", "expected" => "AT211"],
                ["value" => "Recurso*_1", "expected" => "AT212"],
            ],
            "DESCRIPCION_RECURSO" => [
                ["value" => "Esta es una descripción correcta de recurso", "expected" => true],
                ["value" => "Esta es una descripción incorrecta de recurso porque es demasiado larga ya que supera el máximo de caracteres", "expected" => "AT221"],
                ["value" => "Esta / es + una * descripción 12 incorrecta & de % recurso", "expected" => "AT222"],
            ],
            "TARIFA_RECURSO" => [
                ["value" => "25", "expected" => true],
                ["value" => "10000", "expected" => "AT231"],
                ["value" => "-10000", "expected" => "AT231"],
                ["value" => "50.5", "expected" => "AT231"],
                ["value" => "Tarifa inválida", "expected" => "AT231"],
            ],
            "RANGO_TARIFA_RECURSO" => [
                ["value" => "HORA", "expected" => true],
                ["value" => "DIA", "expected" => true],
                ["value" => "SEMANA", "expected" => true],
                ["value" => "MES", "expected" => true],
                ["value" => "Rango inválido", "expected" => "AT241"],
                ["value" => "", "expected" => "AT241"],
                ["value" => "25", "expected" => "AT241"],
            ],
            "ID_CALENDARIO" => [
                ["value" => "1", "expected" => true],
                ["value" => "10000", "expected" => "AT251"],
                ["value" => "cal1", "expected" => "AT251"],
            ],
            "LOGIN_RESPONSABLE" => [
                ["value" => "resp1", "expected" => true],
                ["value" => "resp25", "expected" => "AT261"],
                ["value" => "pepito-grillo", "expected" => "AT261"],
            ],
            "BORRADO_LOGICO" => [
                ["value" => "SI", "expected" => true],
                ["value" => "NO", "expected" => true],
                ["value" => "TAL_VEZ", "expected" => "AT271"],
                ["value" => "", "expected" => "AT271"],
            ]
        ];

        return $this->doAtributesTest("RecursosModel", $checks);
    }
    
    private function testReservasModel(){

        $checks = [
            "ID_RESERVA" => [
                ["value" => "1", "expected" => true],
                ["value" => "res1", "expected" => "AT301"],
            ],
            "LOGIN_USUARIO" => [
                ["value" => "admin", "expected" => true],
                ["value" => "resp25", "expected" => "AT311"],
                ["value" => "pepito-grillo", "expected" => "AT311"],
            ],
            "ID_RECURSO" => [
                ["value" => "1", "expected" => true],
                ["value" => "10000", "expected" => "AT321"],
                ["value" => "rec1", "expected" => "AT321"],
            ],
            "FECHA_SOLICITUD_RESERVA" => [
                ["value" => "21/07/2022", "expected" => true],
                ["value" => "1999-07-21", "expected" => "AT331"],
                ["value" => "21-07-1999", "expected" => "AT331"],
                ["value" => "Esto no es una fecha", "expected" => "AT331"],
            ],
            "FECHA_RESPUESTA_RESERVA" => [
                ["value" => "21/07/2022", "expected" => true],
                ["value" => "1999-07-21", "expected" => "AT341"],
                ["value" => "21-07-1999", "expected" => "AT341"],
                ["value" => "Esto no es una fecha", "expected" => "AT341"],
            ],
            "MOTIVO_RECHAZO_RESERVA" => [
                ["value" => "Este es un motivo de rechazo correcto para la reserva", "expected" => true],
                ["value" => "Este es un motivo de rechazo incorrecto para la reserva porque es demasiado larga ya que supera el máximo de caracteres", "expected" => "AT351"],
                ["value" => "Este / es + un * motivo de rechazo 12 incorrecto & para la % reserva", "expected" => "AT352"],
            ],
            "ESTADO_RESERVA" => [
                ["value" => "PENDIENTE", "expected" => true],
                ["value" => "ACEPTADA", "expected" => true],
                ["value" => "RECHAZADA", "expected" => true],
                ["value" => "CANCELADA", "expected" => true],
                ["value" => "RECURSO_USADO", "expected" => true],
                ["value" => "RECURSO_NO_USADO", "expected" => true],
                ["value" => "ESTADO_INVALIDO", "expected" => "AT361"],
                ["value" => "14", "expected" => "AT361"],
            ],
            "COSTE_RESERVA" => [
                ["value" => "25.00", "expected" => true],
                ["value" => "25.50", "expected" => true],
                ["value" => "50", "expected" => "AT371"],
                ["value" => "Coste inválido", "expected" => "AT371"],
                ["value" => "-25.50", "expected" => "AT372"],
                ["value" => "10000.10", "expected" => "AT372"],
            ]
        ];

        return $this->doAtributesTest("ReservasModel", $checks);
    }
    
    private function testResponsablesModel(){
        
        $checks = [
            "LOGIN_RESPONSABLE" => [
                ["value" => "resp1", "expected" => true],
                ["value" => "pepito-grillo", "expected" => "AT401"],
            ],
            "DIRECCION_RESPONSABLE" => [
                ["value" => "Avenida Eduardo Fáñez Rodríguez 3ºA 32004", "expected" => true],
                ["value" => "Corta", "expected" => "AT411"],
                ["value" => "Calle Dirección exageradamente larga e inexistente Nº 26 555555A", "expected" => "AT411"],
                ["value" => "Dirección con % caracteres # extraños", "expected" => "AT412"],
            ],
            "TELEFONO_RESPONSABLE" => [
                ["value" => "666555444", "expected" => true],
                ["value" => "999888666", "expected" => true],
                ["value" => "12345", "expected" => "AT421"],
                ["value" => "123456789", "expected" => "AT421"],
                ["value" => "Teléfono inválido", "expected" => "AT421"],
            ],
        ];

        return $this->doAtributesTest("ResponsablesModel", $checks);
    }
    
    private function testSubreservasModel(){
        $checks = [
            "ID_RESERVA" => [
                ["value" => "1", "expected" => true],
                ["value" => "res1", "expected" => "AT501"],
            ],
            "FECHA_INICIO_SUBRESERVA" => [
                ["value" => "21/07/2021", "expected" => true],
                ["value" => "1999-07-21", "expected" => "AT521"],
                ["value" => "21-07-1999", "expected" => "AT521"],
                ["value" => "Esto no es una fecha", "expected" => "AT521"],
            ],
            "FECHA_FIN_SUBRESERVA" => [
                ["value" => "21/07/2021", "expected" => true],
                ["value" => "1999-07-21", "expected" => "AT531"],
                ["value" => "21-07-1999", "expected" => "AT531"],
                ["value" => "Esto no es una fecha", "expected" => "AT531"],
            ],
            "HORA_INICIO_SUBRESERVA" => [
                ["value" => "10:00:00", "expected" => true],
                ["value" => "11:30:00", "expected" => "AT541"],
                ["value" => "14:37", "expected" => "AT541"],
                ["value" => "Esto no es una hora", "expected" => "AT541"],
            ],
            "HORA_FIN_SUBRESERVA" => [
                ["value" => "10:00:00", "expected" => true],
                ["value" => "11:30:00", "expected" => "AT551"],
                ["value" => "14:37", "expected" => "AT551"],
                ["value" => "Esto no es una hora", "expected" => "AT551"],
            ],
            "COSTE_SUBRESERVA" => [
                ["value" => "25.00", "expected" => true],
                ["value" => "25.50", "expected" => true],
                ["value" => "50", "expected" => "AT561"],
                ["value" => "Coste inválido", "expected" => "AT561"],
                ["value" => "-25.50", "expected" => "AT562"],
                ["value" => "10000.10", "expected" => "AT562"],
            ]
        ];

        return $this->doAtributesTest("SubreservasModel", $checks);
    }

    private function testUsuariosModel(){
        $checks = [
            "LOGIN_USUARIO" => [
                ["value" => "cesarino", "expected" => true],
                ["value" => "login_demasiado_largo", "expected" => "AT601"],
                ["value" => "weird*login/", "expected" => "AT602"],
            ],
            "PASSWD_USUARIO" => [
                ["value" => "f9e2ede9ed5b31ffb5a9694ed3b02968", "expected" => true],
                ["value" => "", "expected" => "AT611"],
                ["value" => "(No_Es_Un_Hash)", "expected" => "AT611"],
            ],
            "NOMBRE_USUARIO" => [
                ["value" => "Pepito Grillo-Fáñez", "expected" => true],
                ["value" => "A", "expected" => "AT621"],
                ["value" => "Un Nombre Demasiado Largo Que De Ninguna Manera Nadie Tiene En El Mundo", "expected" => "AT621"],
                ["value" => "Pepito Grillo-Fáñez 25", "expected" => "AT622"],
            ],
            "EMAIL_USUARIO" => [
                ["value" => "cmrodriguez17@esei.uvigo.es", "expected" => true],
                ["value" => "cmrodriguez17", "expected" => "AT631"],
                ["value" => "esei.uvigo.es", "expected" => "AT631"],
                ["value" => "cmrodriguez17@esei", "expected" => "AT631"],
            ],
            "TIPO_USUARIO" => [
                ["value" => "NORMAL", "expected" => true],
                ["value" => "ADMINISTRADOR", "expected" => true],
                ["value" => "RESPONSABLE", "expected" => true],
                ["value" => "TIPO_INVALIDO", "expected" => "AT641"],
                ["value" => "", "expected" => "AT641"],
            ],
            "ES_ACTIVO" => [
                ["value" => "SI", "expected" => true],
                ["value" => "NO", "expected" => true],
                ["value" => "PUEDE_SER", "expected" => "AT651"],
            ]
        ];

        return $this->doAtributesTest("UsuariosModel", $checks);
    }

}

new Test();

?>