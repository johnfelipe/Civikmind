<?php

if($data_ini == $data_fin) {
	$datas = "LIKE '".$data_ini."%'";
}

$data1 = $data_ini;
$data2 = $data_fin;

$unix_data1 = strtotime($data1);
$unix_data2 = strtotime($data2);

$interval = ($unix_data2 - $unix_data1) / 86400;


if($interval >= "31") {

	$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";

	//chamados mensais
	 $querym = "
	SELECT DISTINCT DATE_FORMAT(glpi_tickets.date, '%b-%Y') as day_l,  COUNT(glpi_tickets.id) as nb, DATE_FORMAT(glpi_tickets.date, '%y-%m') as day
	FROM glpi_tickets_users, glpi_tickets
	WHERE glpi_tickets.is_deleted = '0'
	AND glpi_tickets.date ".$datas."
	AND glpi_tickets_users.users_id = ".$id_tec."
	AND glpi_tickets_users.type = 2
	AND glpi_tickets_users.tickets_id = glpi_tickets.id
	GROUP BY day
	ORDER BY day ";
}

else {

	$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";

	//chamados mensais
	 $querym = "
	SELECT DISTINCT DATE_FORMAT(glpi_tickets.date, '%b-%d') as day_l,  COUNT(glpi_tickets.id) as nb, DATE_FORMAT(glpi_tickets.date, '%Y-%m-%d') as day
	FROM glpi_tickets_users, glpi_tickets
	WHERE glpi_tickets.is_deleted = '0'
	AND glpi_tickets.date ".$datas."
	AND glpi_tickets_users.users_id = ".$id_tec."
	AND glpi_tickets_users.type = 2
	AND glpi_tickets_users.tickets_id = glpi_tickets.id
	GROUP BY day
	ORDER BY day ";
}

$resultm = $DB->query($querym) or die('errot');

$contador = $DB->numrows($resultm);

$arr_grfm = array();
while ($row_result = $DB->fetch_assoc($resultm)){
	$v_row_result = $row_result['day_l'];
	$arr_grfm[$v_row_result] = $row_result['nb'];
}

$grfm = array_keys($arr_grfm) ;
$quantm = array_values($arr_grfm) ;

$grfm3 = json_encode($grfm);

$quantm2 = implode(',',$quantm);


$status = "('5','6')"	;

if($interval >= "31") {

	$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";

	// fechados mensais
	$queryf = "
	SELECT DISTINCT DATE_FORMAT(glpi_tickets.date, '%b-%Y') as day_l,  COUNT(glpi_tickets.id) as nb, DATE_FORMAT(glpi_tickets.date, '%y-%m') as day
	FROM glpi_tickets_users, glpi_tickets
	WHERE glpi_tickets.is_deleted = '0'
	AND glpi_tickets.date ".$datas."
	AND glpi_tickets_users.users_id = ".$id_tec."
	AND glpi_tickets_users.type = 2
	AND glpi_tickets_users.tickets_id = glpi_tickets.id
	AND glpi_tickets.status IN ".$status."
	GROUP BY day
	ORDER BY day ";
 }

else {
	$datas = "BETWEEN '".$data_ini." 00:00:00' AND '".$data_fin." 23:59:59'";

	// fechados mensais
	$queryf = "
	SELECT DISTINCT DATE_FORMAT(glpi_tickets.date, '%b-%d') as day_l,  COUNT(glpi_tickets.id) as nb, DATE_FORMAT(glpi_tickets.date, '%Y-%m-%d') as day
	FROM glpi_tickets_users, glpi_tickets
	WHERE glpi_tickets.is_deleted = '0'
	AND glpi_tickets.date ".$datas."
	AND glpi_tickets_users.users_id = ".$id_tec."
	AND glpi_tickets_users.type = 2
	AND glpi_tickets_users.tickets_id = glpi_tickets.id
	AND glpi_tickets.status IN ".$status."
	GROUP BY day
	ORDER BY day ";
}

$resultf = $DB->query($queryf) or die('errof');

$arr_grff = array();
while ($row_result = $DB->fetch_assoc($resultf)){
	$v_row_result = $row_result['day_l'];
	$arr_grff[$v_row_result] = $row_result['nb'];
}

$grff = array_keys($arr_grff) ;
$quantf = array_values($arr_grff) ;

$quantf2 = implode(',',$quantf);


echo "
<script type='text/javascript'>
$(function ()
{

        $('#graf_linhas').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: '".__('Tickets','dashboard')."'
            },
            legend: {
                layout: 'horizontal',
                align: 'center',
                verticalAlign: 'bottom',
                x: 0,
                y: 0,
                //floating: true,
                borderWidth: 0,
								adjustChartSize: true
                //backgroundColor: '#FFFFFF'
            },
            xAxis: {
                categories: $grfm3,
						  labels: {
                    rotation: -55,
                    align: 'right'
                }

            },
            yAxis: {
                title: {
                    text: ''
                }
            },
            tooltip: {
                shared: true
            },
            credits: {
                enabled: false
            },
            plotOptions: {
                column: {
                    fillOpacity: 0.5,
                    borderWidth: 1,
                	  borderColor: 'white',
                	  shadow:true,
                    dataLabels: {
	                 	enabled: true
	                 },
                },
            },
          series: [{
                name: '".__('Opened','dashboard')."',
                data: [$quantm2] },
                {
                name: '".__('Closed','dashboard')."',
                data: [$quantf2]
            }]
        });
    });
  </script>
";

		?>
