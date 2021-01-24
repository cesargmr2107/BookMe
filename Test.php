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
        "SubreservasModel",
        "UsuariosModel",
        "ResponsablesModel"
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
                    <li><strong>Tests totales:</strong> <?=$this->totalTests?></li>
                    <ul>
                        <li><strong>Tests superados:</strong> <?=$this->passedTests?></li>
                        <li><strong>Tests fallidos:</strong> <?=$this->totalTests - $this->passedTests?></li>
                    </ul>
                    <li><strong>Porcentaje de superación:</strong> <?=round(100*($this->passedTests/$this->totalTests),2)?>%</li>
                </ul>
            </div>
        <?php
    }

    private function showResults($model, $results){
        echo "<div class='tests-container'>";
        echo "<h2>Test de entidad: $model</h2>";
        if(array_key_exists("actionResults", $results)){
            $this->showActionResults($results["actionResults"]);
        }
        if(array_key_exists("atributeResults", $results)){
            $this->showAtributeResults($results["atributeResults"]);
        }
        echo "</div>";
    }

    private function showActionResults($results){
        //echo "<pre>" . var_export($results, true) . "</pre>";
        echo "<h3>Test de acción</h3>";
        foreach($results as $action => $tests){
            ?>
                <table class="table action-tests">
                    <tr>
                        <th colspan="4"><?=$action?></th>
                    </tr>
                    <tr>
                        <th class="atributes">Atributos</th>
                        <th class="expected">Esperado</th>
                        <th class="obtained">Obtenido</th> 
                        <th class="test-result">Resultado</th> 
                    </tr>
                    <?php
                        foreach($tests as $test){
                            ?>
                                <tr>
                                    <td class="atributes">
                                        <pre><?=var_export($test["atributes"])?></pre>
                                    </td>
                                    <td class="expected">
                                        <strong><?=$test["expected"]?> : </strong>
                                        <span class="i18n-<?=$test["expected"]?>"></span>
                                    </td>
                                    <td class="obtained">
                                        <strong><?=$test["obtained"]["code"]?> : </strong>
                                        <span class="i18n-<?=$test["obtained"]["code"]?>"></span>
                                        <?php
                                            if(array_key_exists("atributeErrors", $test["obtained"])){
                                                echo "<ul>";
                                                echo "<p>Errores de atributo:</p>";
                                                foreach ($test["obtained"]["atributeErrors"] as $atribute => $checks) {
                                                    foreach ($checks as $check => $error){
                                                        echo "<li><strong>$error : </strong><span class='i18n-$error'></span></li>";
                                                    }
                                                }
                                                echo "</ul>";
                                            }
                                        ?>
                                    </td>
                                    <?php
                                        if ($test["expected"] === $test["obtained"]["code"]){
                                            echo "<td class='test-result ok'>OK</td>";
                                            $this->passedTests++;
                                        } else {
                                            echo "<td class='test-result not-ok'>NOT OK</td>";
                                        }
                                        $this->totalTests++;
                                    ?>
                                </tr>
                            <?php
                        }
                    ?>
                </table>
            <?php
        }
    }

    private function showAtributeResults($results){
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

    private function doActionsTest($modelFile, $actions){
        $model = new $modelFile();
        foreach ($actions as $action => $tests) {
            foreach ($tests as $index => $test) {
                $model->setAtributes($test["atributes"]);
                if($modelFile === "ReservasModel" && $action === "ADD"){
                    $model->setInfoSubreservas(json_encode($test["atributes"]["infoSubreservas"], true));
                }
                $result = $model->$action();
                $actions[$action][$index]["obtained"] = $result;
                $model->clear();
            }
        }
        return $actions;
    }

    private function testCalendariosModel(){

        $result = [];

        // Action tests

        $actions =  [
            "ADD" => [
                [
                    "atributes" => [
                        "NOMBRE_CALENDARIO" => "Calendario del curso",
                        "DESCRIPCION_CALENDARIO" => "Este es el calendario para el curso académico",
                        "FECHA_INICIO_CALENDARIO" => "21/09/2020",
                        "FECHA_FIN_CALENDARIO" => "21/06/2021",
                        "HORA_INICIO_CALENDARIO" => "08:00:00", 
                        "HORA_FIN_CALENDARIO" => "22:00:00"
                    ],
                    "expected" => "AC111"
                ],
                [
                    "atributes" => [
                        "NOMBRE_CALENDARIO" => "",
                        "DESCRIPCION_CALENDARIO" => "",
                        "FECHA_INICIO_CALENDARIO" => "",
                        "FECHA_FIN_CALENDARIO" => "",
                        "HORA_INICIO_CALENDARIO" => "", 
                        "HORA_FIN_CALENDARIO" => ""
                    ],
                    "expected" => "AC011"
                ],
            ],
            "EDIT" => [
                [
                    "atributes" => [
                        "ID_CALENDARIO" => "1",
                        "FECHA_INICIO_CALENDARIO" => "21/09/2020",
                        "FECHA_FIN_CALENDARIO" => "21/06/2021"
                    ],
                    "expected" => "AC112"
                ],
                [
                    "atributes" => [
                        "ID_CALENDARIO" => "1000",
                        "FECHA_INICIO_CALENDARIO" => "21/09/2020",
                        "FECHA_FIN_CALENDARIO" => "21/06/2021"
                    ],
                    "expected" => "AC012"
                ],
            ],
            "DELETE" => [
                [
                    "atributes" => [
                        "ID_CALENDARIO" => "5"
                    ],
                    "expected" => "AC113"
                ],
                [
                    "atributes" => [
                        "ID_CALENDARIO" => "1"
                    ],
                    "expected" => "AC013"
                ],
            ]
        ];

        $result["actionResults"] = $this->doActionsTest("CalendariosModel", $actions);

        // Atribute tests
        
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
            ],
            "BORRADO_LOGICO" => [
                ["value" => "SI", "expected" => true],
                ["value" => "NO", "expected" => true],
                ["value" => "TAL_VEZ", "expected" => "AT171"],
                ["value" => "", "expected" => "AT171"],
            ]
        ];

        $result["atributeResults"] = $this->doAtributesTest("CalendariosModel", $checks);

        return $result;
    }

    private function testRecursosModel(){
        
        $result = [];

        // Action tests

        $actions =  [
            "ADD" => [
                [
                    "atributes" => [
                        "NOMBRE_RECURSO" => "Nuevo recurso",
                        "DESCRIPCION_RECURSO" => "Este es un nuevo recurso",
                        "TARIFA_RECURSO" => "10",
                        "RANGO_TARIFA_RECURSO" => "SEMANA",
                        "ID_CALENDARIO" => "1",
                        "LOGIN_RESPONSABLE" => "resp1"
                    ],
                    "expected" => "AC121"
                ],
                [
                    "atributes" => [
                        "NOMBRE_RECURSO" => "",
                        "DESCRIPCION_RECURSO" => "",
                        "TARIFA_RECURSO" => "",
                        "RANGO_TARIFA_RECURSO" => "",
                        "ID_CALENDARIO" => "",
                        "LOGIN_RESPONSABLE" => ""
                    ],
                    "expected" => "AC021"
                ],
            ],
            "EDIT" => [
                [
                    "atributes" => [
                        "ID_RECURSO" => "1",
                        "TARIFA_RECURSO" => "100"
                    ],
                    "expected" => "AC122"
                ],
                [
                    "atributes" => [
                        "ID_RECURSO" => "1000",
                        "TARIFA_RECURSO" => "100"
                    ],
                    "expected" => "AC022"
                ],
            ],
            "DELETE" => [
                [
                    "atributes" => [
                        "ID_RECURSO" => "7"
                    ],
                    "expected" => "AC123"
                ],
                [
                    "atributes" => [
                        "ID_RECURSO" => "1"
                    ],
                    "expected" => "AC023"
                ],
            ]
        ];

        $result["actionResults"] = $this->doActionsTest("RecursosModel", $actions);

        // Atribute tests

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
                ["value" => "10000.0", "expected" => "AT231"],
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

        $result["atributeResults"] = $this->doAtributesTest("RecursosModel", $checks);

        return $result;
    }
    
    private function testReservasModel(){

        $result = [];

        // Action tests

        $actions =  [
            "ADD" => [
                [
                    "atributes" => [
                        "LOGIN_USUARIO" => "cesarino",
                        "ID_RECURSO" => "1",
                        "FECHA_SOLICITUD_RESERVA" => "20/01/2021",
                        "infoSubreservas" => [ 
                            "subreservas" => [
                                "subreserva-0" => [
                                    "FECHA_INICIO_SUBRESERVA" => "28/01/2021",
                                    "FECHA_FIN_SUBRESERVA" => "31/01/2021",
                                    "HORA_INICIO_SUBRESERVA" => "17:00:00",
                                    "HORA_FIN_SUBRESERVA" => "18:00:00"
                                ],
                                "subreserva-1" => [
                                    "FECHA_INICIO_SUBRESERVA" => "10/02/2021",
                                    "FECHA_FIN_SUBRESERVA" => "23/02/2021",
                                    "HORA_INICIO_SUBRESERVA" => "17:00:00",
                                    "HORA_FIN_SUBRESERVA" => "18:00:00"
                                ]
                            ]
                        ]
                    ],
                    "expected" => "AC131"
                ],
                [
                    "atributes" => [
                        "LOGIN_USUARIO" => "emmolina15",
                        "ID_RECURSO" => "1",
                        "FECHA_SOLICITUD_RESERVA" => "20/01/2021",
                        "COSTE_RESERVA" => "5",
                        "infoSubreservas" => [ 
                            "subreservas" => [
                                "subreserva-0" => [
                                    "FECHA_INICIO_SUBRESERVA" => "28/01/2021",
                                    "FECHA_FIN_SUBRESERVA" => "31/01/2021",
                                    "HORA_INICIO_SUBRESERVA" => "17:00:00",
                                    "HORA_FIN_SUBRESERVA" => "18:00:00"
                                ],
                                "subreserva-1" => [
                                    "FECHA_INICIO_SUBRESERVA" => "28/01/2021",
                                    "FECHA_FIN_SUBRESERVA" => "31/01/2021",
                                    "HORA_INICIO_SUBRESERVA" => "17:00:00",
                                    "HORA_FIN_SUBRESERVA" => "18:00:00"
                                ]
                            ]
                        ]
                    ],
                    "expected" => "AC031"
                ],
                [
                    "atributes" => [
                        "LOGIN_USUARIO" => "cesarino",
                        "ID_RECURSO" => "1",
                        "FECHA_SOLICITUD_RESERVA" => "20/01/2021",
                        "COSTE_RESERVA" => "5",
                        "infoSubreservas" => [ 
                            "subreservas" => [
                                "subreserva-0" => [
                                    "FECHA_INICIO_SUBRESERVA" => "28/01/2021",
                                    "FECHA_FIN_SUBRESERVA" => "31/01/2021",
                                    "HORA_INICIO_SUBRESERVA" => "17:00:00",
                                    "HORA_FIN_SUBRESERVA" => "18:00:00"
                                ],
                                "subreserva-1" => [
                                    "FECHA_INICIO_SUBRESERVA" => "28/01/2021",
                                    "FECHA_FIN_SUBRESERVA" => "31/01/2021",
                                    "HORA_INICIO_SUBRESERVA" => "17:00:00",
                                    "HORA_FIN_SUBRESERVA" => "18:00:00"
                                ]
                            ]
                        ]
                    ],
                    "expected" => "AC031"
                ],
                [
                    "atributes" => [
                        "LOGIN_USUARIO" => "emmolina15",
                        "ID_RECURSO" => "1",
                        "FECHA_SOLICITUD_RESERVA" => "20/01/2021",
                        "COSTE_RESERVA" => "5",
                        "infoSubreservas" => [ 
                            "subreservas" => [ ]
                        ]
                    ],
                    "expected" => "AC031"
                ]
            ],
            "EDIT" => [
                [
                    "atributes" => [
                        "ID_RESERVA" => "1",
                        "ESTADO_RESERVA" => "RECURSO_USADO"
                    ],
                    "expected" => "AC132"
                ],
                [
                    "atributes" => [
                        "ID_RESERVA" => "10000",
                        "ESTADO_RESERVA" => "RECURSO_USADO"
                    ],
                    "expected" => "AC032"
                ]
            ]
        ];

        $result["actionResults"] = $this->doActionsTest("ReservasModel", $actions);

        // Atribute tests

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

        $result["atributeResults"] = $this->doAtributesTest("ReservasModel", $checks);

        return $result;
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

        $result["atributeResults"] = $this->doAtributesTest("SubreservasModel", $checks);

        return $result;
    }

    private function testUsuariosModel(){

        $result = [];

        // Action tests

        $actions =  [
            "ADD" => [
                [
                    "atributes" => [
                        "LOGIN_USUARIO" => "newuser",
                        "PASSWD_USUARIO" => "0354d89c28ec399c00d3cb2d094cf093",
                        "NOMBRE_USUARIO" => "New User",
                        "EMAIL_USUARIO" => "newuser@mail.com",
                        "TIPO_USUARIO" => "NORMAL",
                        "ES_ACTIVO" => "SI"
                    ],
                    "expected" => "AC161"
                ],
                [
                    "atributes" => [
                        "LOGIN_USUARIO" => "",
                        "PASSWD_USUARIO" => "",
                        "NOMBRE_USUARIO" => "",
                        "EMAIL_USUARIO" => "",
                        "TIPO_USUARIO" => "",
                        "ES_ACTIVO" => ""
                    ],
                    "expected" => "AC061"
                ]
            ],
            "EDIT" => [
                [
                    "atributes" => [
                        "LOGIN_USUARIO" => "newuser",
                        "EMAIL_USUARIO" => "newemail@mail.com"
                    ],
                    "expected" => "AC162"
                ],
                [
                    "atributes" => [
                        "LOGIN_USUARIO" => "unknown_user",
                        "EMAIL_USUARIO" => "newemail@mail.com",
                    ],
                    "expected" => "AC062"
                ]
            ]
        ];

        $result["actionResults"] = $this->doActionsTest("UsuariosModel", $actions);

        // Atribute tests

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

        $result["atributeResults"] = $this->doAtributesTest("UsuariosModel", $checks);

        return $result;
    }

    private function testResponsablesModel(){
        
        $result = [];

        // Action tests

        $actions =  [
            "ADD" => [
                [
                    "atributes" => [
                        "LOGIN_RESPONSABLE" => "resp3",
                        "DIRECCION_RESPONSABLE" => "Avenida Castelao 15 3A 36209 Vigo",
                        "TELEFONO_RESPONSABLE" => "666555111"
                    ],
                    "expected" => "AC141"
                ],
                [
                    "atributes" => [
                        "LOGIN_RESPONSABLE" => "resp3",
                        "DIRECCION_RESPONSABLE" => "Avenida Castelao 15 3A 36209 Vigo",
                        "TELEFONO_RESPONSABLE" => "666555111"
                    ],
                    "expected" => "AC041"
                ],
                [
                    "atributes" => [
                        "LOGIN_RESPONSABLE" => "",
                        "DIRECCION_RESPONSABLE" => "Avenida Castelao 15 3A 36209 Vigo",
                        "TELEFONO_RESPONSABLE" => "666555111"
                    ],
                    "expected" => "AC041"
                ],
            ],
            "EDIT" => [
                [
                    "atributes" => [
                        "LOGIN_RESPONSABLE" => "resp3",
                        "DIRECCION_RESPONSABLE" => "Avenida Otero Pedrayo 16 5A 32004 Ourense",
                        "TELEFONO_RESPONSABLE" => "999888777"
                    ],
                    "expected" => "AC142"
                ],
                [
                    "atributes" => [
                        "LOGIN_RESPONSABLE" => "",
                        "DIRECCION_RESPONSABLE" => "",
                        "TELEFONO_RESPONSABLE" => ""
                    ],
                    "expected" => "AC042"
                ]
            ]
        ];

        $result["actionResults"] = $this->doActionsTest("ResponsablesModel", $actions);

        // Atribute tests

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

        $result["atributeResults"] = $this->doAtributesTest("ResponsablesModel", $checks);

        return $result;
    }
}

// Restore DB
$mysql = new mysqli('localhost', 'pma', 'iu');
$DBScript = file_get_contents("./installBD.sql");
if($mysql->multi_query($DBScript)) {
    do{
        $mysql->next_result();
    }while($mysql->more_results());
}
$mysql->close();

new Test();

?>