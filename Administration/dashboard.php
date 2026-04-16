<?php
require_once '../base.php'; 

$query = $_db->query("SELECT monthName, sales, expenses FROM monthlysales");
$results = $query->fetchAll();

$chart_data = [
    ['Month', 'Sales', 'Expenses']
];

foreach ($results as $row) {
    $chart_data[] = [
        $row->monthName,
        (int)$row->sales,
        (int)$row->expenses
    ];
}

$json_chart_data = json_encode($chart_data);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LIMEL Admin - Dashboard</title>
    <link rel="stylesheet" href="/app.css">

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable(<?php echo $json_chart_data; ?>);
            var options = {
                title: 'Monthly Sales and Expenses',
                curveType: 'function',
                backgroundColor: 'transparent', 
                titleTextStyle: { color: '#ffffff', fontSize: 20, fontName: 'Montserrat' },
                legend: { position: 'bottom', textStyle: { color: '#cccccc', fontName: 'Roboto' } },
                hAxis: { 
                    textStyle: { color: '#cccccc', fontName: 'Roboto' },
                    gridlines: { color: 'transparent' } 
                },
                vAxis: { 
                    textStyle: { color: '#cccccc', fontName: 'Roboto' },
                    gridlines: { color: '#333333' },
                    baselineColor: '#555555' 
                },
                
                colors: ['#667eea', '#ff7675'], 
                chartArea: { width: '85%', height: '70%' } 
            };

            var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
            chart.draw(data, options);
        }
    </script>
</head>

<body class="admin-body">
    
    <?php include 'sidebar.php'; ?>

    <main class="admin-content">

        <header class="admin-top-header">
            <h2>Dashboard</h2>
            <div class="header-user-info">
                <span class="logged-in-email">staff@limel.inc</span>
                <img src="/images/icon/user.png" width="20" alt="User" class="user-avatar">
            </div>
        </header>
        
        <div class="admin-panel">
            <div id="curve_chart" style="width: 100%; height: 450px;"></div>
        </div>

    </main>
</body>
</html>