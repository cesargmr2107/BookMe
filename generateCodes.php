<?php

foreach (glob("./MODEL/*.php") as $filename)
{
    include_once $filename;
}

$actions = array("ADD", "EDIT", "DEL");

$models = array(
    "CalendariosModel",
    "RecursosModel",
    "ReservasModel",
    "ResponsablesModel",
    "SubreservasModel",
    "UsuariosModel"
);

echo "<h1>General action codes</h1>";
echo "<ul>";
echo "<li>Can't access DB: AC000</li>"; 
echo "<li>Bad query to DB: AC001</li>";
echo "<li>Bad credentials: AC002</li>";
echo "<li>Invalid token: AC003</li>";
echo "</ul>";

foreach($models as $i => $model){
    $modelCode = $i + 1;
    echo "<h1>Codes for $model</h1>";
    echo "<h3>Action codes: </h3>";
    foreach ($actions as $j => $action) {
        $actionCode = $j + 1;
        echo "<ul>";
        echo "<li>$model ($modelCode) - $action ($actionCode) - OK : <strong>AC1$modelCode$actionCode</strong></li>"; 
        echo "<li>$model ($modelCode) - $action ($actionCode) - ERR : <strong>AC0$modelCode$actionCode</strong></li>";
        echo "</ul>";
    }
    echo "<h3>Atribute codes: </h3>";
    foreach ($model::$atributeNames as $atributeIndex => $atribute) {
        $entity = new $model();
        if(array_key_exists($atribute, $entity->checks)){
            $checkIndex = 1;
            echo "<ul>";
            foreach ($entity->checks[$atribute] as $check => $atributes) {
                echo "<li>$atribute ($atributeIndex) - $check ($checkIndex): <strong>AT$modelCode$atributeIndex$checkIndex</strong></li>";
                $checkIndex++;
            }
            echo "</ul>";
        }
    }
}

?>