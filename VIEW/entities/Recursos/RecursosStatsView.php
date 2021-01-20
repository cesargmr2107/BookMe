<?php

include_once './VIEW/BaseView.php';

class RecursosStatsView extends BaseView{

    protected $jsFiles = array("./VIEW/webroot/libraries/chart.js-2.9.4/dist/Chart.js");

    protected function body(){
        
        // DEBUG: Check data passed to view
        // echo '<pre>' . var_export($this->data, true) . '</pre>';

        $this->includeTitle("i18n-resourceStats", "h1");
        $this->includeStatsForm();
        $this->includeStats();
        
    }

    protected function includeStatsForm(){
        ?>
            <form id="statsForm" name="statsForm" action="index.php" method="post">
                <?= $this->includeHiddenField("ID_RECURSO", $this->data["id"])?>
                <?= $this->includeDateField("i18n-fecha_inicio", "FECHA_INICIO_INFORME", false, $this->data["defaultStartDate"])?>
                <?= $this->includeDateField("i18n-fecha_fin", "FECHA_FIN_INFORME", false, $this->data["defaultEndDate"])?>
                <span class="<?=$this->icons["SEARCH"]?>" onclick="sendForm(document.statsForm, 'RecursosController', 'stats', checkResourceStatsForm())"></span>
            </form>
        <?php
        $this->includeValidationModal();
    }

    protected function includeStats(){
        ?>
            <div>
                <?= $this->includeTitle("i18n-resourceBookings", "h3") ?>
                <ul>
                    <li>
                        <strong class="i18n-nBookAccepted"></strong>
                        <span><?= $this->data["count"]["PENDIENTE"]?></span>
                    </li>
                    <li>
                        <strong class="i18n-nBookPending"></strong>
                        <span><?= $this->data["count"]["ACEPTADA"]?></span>
                    </li>
                    <li>
                        <strong class="i18n-nBookRejected"></strong>
                        <span><?= $this->data["count"]["RECHAZADA"]?></span>
                    </li>
                    <li>
                        <strong class="i18n-nBookCanceled"></strong>
                        <span><?= $this->data["count"]["CANCELADA"]?></span>
                    </li>
                    <li>
                        <strong class="i18n-nBookUsed"></strong>
                        <span><?= $this->data["count"]["RECURSO_USADO"]?></span>
                    </li>
                    <li>
                        <strong class="i18n-nBookNotUsed"></strong>
                        <span><?= $this->data["count"]["RECURSO_NO_USADO"]?></span>
                    </li>
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