<?php
require_once '../base.php'; 

$query = $_db->query("SELECT monthName, sales, expenses FROM monthlysales");
$results = $query->fetchAll();

$chart_data = [
    ['Month', 'Sales', 'Expenses']
];
foreach ($results as $row) {
    $chart_data[] = [ $row->monthName, (int)$row->sales, (int)$row->expenses ];
}
$json_chart_data = json_encode($chart_data);

$pie_data = [
    ['Category', 'Total Sold'],
    ['Keyboards', 450],
    ['Mice',      280],
    ['Switches',  150],
    ['Keycaps',   120]
];
$json_pie_data = json_encode($pie_data);

$waterfall_data = [
    ['Revenue',     0,      0,      100000, 100000],
    ['Parts Cost',  60000,  100000, 60000,  100000],
    ['Shipping',    50000,  60000,  50000,  60000],
    ['Marketing',   35000,  50000,  35000,  50000],
    ['Net Profit',  0,      0,      35000,  35000] 
];
$json_waterfall_data = json_encode($waterfall_data);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LIMEL Admin - Dashboard</title>
    <link rel="stylesheet" href="/app.css">

    <style>
        .dashboard-row {
            display: grid;
            grid-template-columns: 2fr 1fr; 
            gap: 20px;
            margin-bottom: 20px;
        }

        .dashboard-row-full {
            width: 100%;
            margin-bottom: 20px;
        }
    </style>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawAllCharts);

        function drawAllCharts() {
            var lineData = google.visualization.arrayToDataTable(<?php echo $json_chart_data; ?>);
            var lineOptions = {
                title: 'Monthly Sales vs Expenses',
                curveType: 'function',
                backgroundColor: 'transparent', 
                titleTextStyle: { color: '#ffffff', fontSize: 18, fontName: 'Montserrat' },
                legend: { position: 'bottom', textStyle: { color: '#cccccc', fontName: 'Roboto' } },
                hAxis: { textStyle: { color: '#cccccc', fontName: 'Roboto' }, gridlines: { color: 'transparent' } },
                vAxis: { textStyle: { color: '#cccccc', fontName: 'Roboto' }, gridlines: { color: '#333333' }, baselineColor: '#555555' },
                colors: ['#667eea', '#ff7675'], 
                chartArea: { width: '85%', height: '70%' } 
            };
            new google.visualization.LineChart(document.getElementById('curve_chart')).draw(lineData, lineOptions);

            var pieData = google.visualization.arrayToDataTable(<?php echo $json_pie_data; ?>);
            var pieOptions = {
                title: 'Sales by Category',
                backgroundColor: 'transparent',
                pieHole: 0.4, 
                titleTextStyle: { color: '#ffffff', fontSize: 18, fontName: 'Montserrat' },
                legend: { position: 'bottom', textStyle: { color: '#cccccc', fontName: 'Roboto' } },
                colors: ['#667eea', '#ff7675', '#2dce89', '#f5365c'], 
                chartArea: { width: '90%', height: '70%' },
                pieSliceBorderColor: '#1A1A1A' 
            };
            new google.visualization.PieChart(document.getElementById('pie_chart')).draw(pieData, pieOptions);

            var waterfallData = google.visualization.arrayToDataTable(<?php echo $json_waterfall_data; ?>, true);
            var waterfallOptions = {
                title: 'Profit Breakdown (Current Month)',
                legend: 'none',
                backgroundColor: 'transparent',
                bar: { groupWidth: '90%' }, 
                candlestick: {
                    fallingColor: { strokeWidth: 0, fill: '#ff7675' },
                    risingColor: { strokeWidth: 0, fill: '#2dce89' } 
                },
                titleTextStyle: { color: '#ffffff', fontSize: 18, fontName: 'Montserrat' },
                hAxis: { textStyle: { color: '#cccccc', fontName: 'Roboto' } },
                vAxis: { textStyle: { color: '#cccccc', fontName: 'Roboto' }, gridlines: { color: '#333333' }, baselineColor: '#555555' },
                chartArea: { width: '85%', height: '70%' }
            };
            new google.visualization.CandlestickChart(document.getElementById('waterfall_chart')).draw(waterfallData, waterfallOptions);
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
        
        <div class="summary-grid">
            <div class="summary-card">
                <span class="summary-title">Total Revenue</span>
                <h3 class="summary-value">RM 145,000</h3>
            </div>
            
            <div class="summary-card green">
                <span class="summary-title">Orders Pending</span>
                <h3 class="summary-value">24</h3>
            </div>
            
            <div class="summary-card orange">
                <span class="summary-title">Total Products</span>
                <h3 class="summary-value">156</h3>
            </div>
            
            <div class="summary-card red">
                <span class="summary-title">Total Members</span>
                <h3 class="summary-value">1,204</h3>
            </div>
        </div>

        <div class="dashboard-row">
            <div class="admin-panel">
                <div id="curve_chart" style="width: 100%; height: 400px;"></div>
            </div>
            <div class="admin-panel">
                <div id="pie_chart" style="width: 100%; height: 400px;"></div>
            </div>
        </div>

        <div class="dashboard-row-full">
            <div class="admin-panel">
                <div id="waterfall_chart" style="width: 100%; height: 400px;"></div>
            </div>
        </div>

    
    </main>
</body>
</html>