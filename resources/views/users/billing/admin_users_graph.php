<?php
$downloadData = array();
foreach($download as $key => $entry){
    $data=($entry->acctoutputoctets)/1073741824;
    $time=date('M,d',strtotime($entry->acctstarttime));
    $downloadData[$key]['label']=$time;
    $downloadData[$key]['y']=$data;
} 
$uploadData = array();
foreach($upload as $key2 => $entry2){
    $data2=($entry2->acctinputoctets)/1073741824;
    $time2=date('M,d',strtotime($entry2->acctstarttime));
    $uploadData[$key2]['label']=$time2;
    $uploadData[$key2]['y']=$data2;
} 
?>
<script>
    window.onload = function () {
        var chart = new CanvasJS.Chart("chartContainer", {
            title: {
                text: "Daily 'Internet Data' Usage"
            },
            theme: "light2",
            animationEnabled: true,
            toolTip:{
                shared: true,
                reversed: true
            },
            axisY: {
                title: "Data in GB",
                suffix: " GB"
            },
            legend: {
                cursor: "pointer",
                itemclick: toggleDataSeries
            },
            data: [
            {
                type: "stackedColumn",
                name: "Download",
                showInLegend: true,
                yValueFormatString: "#,##0 GB",
                dataPoints: <?php echo json_encode($downloadData, JSON_NUMERIC_CHECK); ?>
            },{
                type: "stackedColumn",
                name: "Upload",
                showInLegend: true,
                yValueFormatString: "#,##0 GB",
                dataPoints: <?php echo json_encode($uploadData, JSON_NUMERIC_CHECK); ?>
            }
            ]
        });
        chart.render();
        function toggleDataSeries(e) {
            if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                e.dataSeries.visible = false;
            } else {
                e.dataSeries.visible = true;
            }
            e.chart.render();
        }
    }
</script>
<div id="chartContainer" style="height: 370px; width: 100%;"></div>