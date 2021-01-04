<?php

include_once './VIEW/BaseView.php';

class RecursosStatsView extends BaseView{

    protected $jsFiles = array("./VIEW/libraries/chart.js-2.9.4/dist/Chart.js");

    protected function body(){
        
        // DEBUG: Check data passed to view
        echo '<pre>' . var_export($this->data, true) . '</pre>';

        $this->includeTitle("Informe de recurso", "h1");
        $this->includeStatsForm();
        $this->includeStats();
        
    }

    protected function includeStatsForm(){
        ?>
            <form id="statsForm" name="statsForm" action="index.php" method="post">
                <?= $this->includeHiddenField("ID_RECURSO", $this->data["id"])?>
                <?= $this->includeDateField("Fecha de inicio", "FECHA_INICIO_INFORME", false, $this->data["defaultStartDate"])?>
                <?= $this->includeDateField("Fecha de fin", "FECHA_FIN_INFORME", false, $this->data["defaultEndDate"])?>
                <span class="<?=$this->icons["SEARCH"]?>" onclick="sendForm(document.statsForm, 'RecursosController', 'stats', true)"></span>
            </form>
        <?php
    }

    protected function includeStats(){
        ?>
            <div>
                <?= $this->includeTitle("Reservas del recurso", "h3") ?>
                <ul>
                    <li>Reservas pendientes: <?= $this->data["count"]["PENDIENTE"]?></li>
                    <li>Reservas aceptadas: <?= $this->data["count"]["ACEPTADA"]?></li>
                    <li>Reservas rechazada: <?= $this->data["count"]["RECHAZADA"]?></li>
                    <li>Reservas canceladas: <?= $this->data["count"]["CANCELADA"]?></li>
                    <li>Reservas con recurso usado: <?= $this->data["count"]["RECURSO_USADO"]?></li>
                    <li>Reservas con recurso no usado: <?= $this->data["count"]["RECURSO_NO_USADO"]?></li>
                </ul>
            </div>

            <div id="graph">
                <canvas id="statsChart"></canvas>
            </div>

            <script>
                
                var dataset = {
                    datasets: [{
                        data: [<?= $this->data["totalNonBookedHours"]?>, <?= $this->data["totalBookedHours"]?>],
                        backgroundColor: [
                            '#D9D9D9',
                            '#4B62BF'
                        ]
                    }],

                    // These labels appear in the legend and in the tooltips when hovering different arcs
                    labels: [
                        'Horas disponibles',
                        'Horas ocupadas'
                    ]
                }
                
                var ctx = document.getElementById('statsChart');
                var statsChart = new Chart(ctx, {
                    type: 'pie',
                    data: dataset,
                });

            </script>
        <?php
    }
}
?>