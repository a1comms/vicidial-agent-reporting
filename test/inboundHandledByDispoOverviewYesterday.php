<?php

require "../config.php";

$camp = (@$_GET['camp'] != "" ? $_GET['camp'] : 'CS');

$data = new Campaign($camp);

$yday_start = mktime(0,0,0) - (24*3600);
$yday_end = mktime(23,59,59) - (24*3600);

$data->setTimePeriod($yday_start, $yday_end);

echo "<h1>Data For Campaign: " . $camp . "</h1>\n";
?>
<table border="1">
    <tr>
        <th>Dispo Name</th>
        <th>Total Calls</th>
        <th>Percentage of Total</th>
        <th>Average Talk Time</th>
        <th>Average Hold Time</th>
        <th>Average Dispo Time</th>
        <th>Average Dead Time</th>
        <th>Average Handle Time</th>
        <th>Average Wrap Time</th>
    </tr>
    <?php
    $queues = $data->byInbound()->queues;
    if(($key = array_search("AGENTDIRECT", $queues)) !== false) {
        unset($queues[$key]);
    }
    $total = $data->byInbound()->byQueue($queues)->getTotalAnswered();
    foreach ($data->byInbound()->byQueue($queues)->getTotalByDispo() as $d => $c){
        echo "<tr>\n";
            echo "<td>" . $data->fetchData()->getDispoName($d) . "</td>\n";
            echo "<td>" . $c . "</td>\n";
            echo "<td>" . round( ( $c / $total ) * 100 ) . "</td>\n";
            echo "<td>" . gmdate("H:i:s", (int)$data->byInbound()->fetchCallTimes()->byDispo($d)->getAVGTalkTime()) . "</td>\n";
            echo "<td>" . gmdate("H:i:s", (int)$data->byInbound()->fetchCallTimes()->byDispo($d)->getAVGHoldTime()) . "</td>\n";
            echo "<td>" . gmdate("H:i:s", (int)$data->byInbound()->fetchCallTimes()->byDispo($d)->getAVGDispoTime()) . "</td>\n";
            echo "<td>" . gmdate("H:i:s", (int)$data->byInbound()->fetchCallTimes()->byDispo($d)->getAVGDeadTime()) . "</td>\n";
            echo "<td>" . gmdate("H:i:s", (int)$data->byInbound()->fetchCallTimes()->byDispo($d)->getAVGHandleTime()) . "</td>\n";
            echo "<td>" . gmdate("H:i:s", (int)$data->byInbound()->fetchCallTimes()->byDispo($d)->getAVGWrapTime()) . "</td>\n";
        echo "</tr>\n";
    }
        echo "<tr>\n";
            echo "<td><b>Total</b></td>\n";
            echo "<td>" . $total . "</td>\n";
            echo "<td>100%</td>\n";
            echo "<td>" . gmdate("H:i:s", (int)$data->byInbound()->fetchCallTimes()->getAVGTalkTime()) . "</td>\n";
            echo "<td>" . gmdate("H:i:s", (int)$data->byInbound()->fetchCallTimes()->getAVGHoldTime()) . "</td>\n";
            echo "<td>" . gmdate("H:i:s", (int)$data->byInbound()->fetchCallTimes()->getAVGDispoTime()) . "</td>\n";
            echo "<td>" . gmdate("H:i:s", (int)$data->byInbound()->fetchCallTimes()->getAVGDeadTime()) . "</td>\n";
            echo "<td>" . gmdate("H:i:s", (int)$data->byInbound()->fetchCallTimes()->getAVGHandleTime()) . "</td>\n";
            echo "<td>" . gmdate("H:i:s", (int)$data->byInbound()->fetchCallTimes()->getAVGWrapTime()) . "</td>\n";
        echo "</tr>\n";
?>
</table>
