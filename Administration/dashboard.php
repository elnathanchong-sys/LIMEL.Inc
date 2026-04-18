<?php
include '../base.php'; 

$rev_query = $_db->query("SELECT SUM(totalPrice) as total_rev FROM orders WHERE orderStatus != 'cancelled'");
$total_revenue = $rev_query->fetch()->total_rev ?? 0;

$pending_query = $_db->query("SELECT COUNT(*) as count FROM orders WHERE orderStatus = 'Processing'");
$pending_orders = $pending_query->fetch()->count ?? 0;

$prod_query = $_db->query("SELECT COUNT(*) as count FROM products");
$total_products = $prod_query->fetch()->count ?? 0;

$users_query = $_db->query("SELECT COUNT(*) as count FROM users");
$total_members = $users_query->fetch()->count ?? 0;

$line_query = $_db->query("SELECT monthName, sales, expenses FROM monthlysales");
$line_results = $line_query->fetchAll();

$chart_data = [['Month', 'Sales', 'Expenses']];
foreach ($line_results as $row) {
    $chart_data[] = [ $row->monthName, (int)$row->sales, (int)$row->expenses ];
}
$json_chart_data = json_encode($chart_data);

$pie_query = $_db->query("
    SELECT c.categoryName, SUM(o.orderHistoryQuantity) as total_sold
    FROM orders o
    JOIN products p ON o.productID = p.productID
    JOIN category c ON p.categoryID = c.categoryID
    GROUP BY c.categoryName
");
$pie_results = $pie_query->fetchAll();

$pie_data = [['Category', 'Total Sold']];
foreach ($pie_results as $row) {
    $pie_data[] = [ $row->categoryName, (int)$row->total_sold ];
}
$json_pie_data = json_encode($pie_data);

$wf_query = $_db->query("SELECT SUM(sales) as total_sales, SUM(expenses) as total_exp FROM monthlysales");
$wf_data = $wf_query->fetch();
$rev = (int)($wf_data->total_sales ?? 0);
$total_exp = (int)($wf_data->total_exp ?? 0);
$parts = $total_exp * 0.60;
$ship = $total_exp * 0.20;
$mkt = $total_exp * 0.20; 
$net = $rev - $total_exp;

$step1 = $rev;                   
$step2 = $step1 - $parts;        
$step3 = $step2 - $ship;         
$step4 = $step3 - $mkt;          

$waterfall_data = [
    ['Revenue',     0,      0,      $rev,   $rev],
    ['Parts Cost',  $step2, $step1, $step2, $step1],
    ['Shipping',    $step3, $step2, $step3, $step2],
    ['Marketing',   $step4, $step3, $step4, $step3],
    ['Net Profit',  0,      0,      $net,   $net]
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
    
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
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
                <h3 class="summary-value">RM <?= number_format($total_revenue, 2) ?></h3>
            </div>
            
            <div class="summary-card green">
                <span class="summary-title">Orders Pending</span>
                <h3 class="summary-value"><?= number_format($pending_orders) ?></h3>
            </div>
            
            <div class="summary-card orange">
                <span class="summary-title">Total Products</span>
                <h3 class="summary-value"><?= number_format($total_products) ?></h3>
            </div>
            
            <div class="summary-card red">
                <span class="summary-title">Total Members</span>
                <h3 class="summary-value"><?= number_format($total_members) ?></h3>
            </div>
        </div>

        <div class="dashboard-row">
            <div class="admin-panel">
                <div id="curve_chart" class="chart-container"></div>
            </div>
            <div class="admin-panel">
                <div id="pie_chart" class="chart-container"></div>
            </div>
        </div>

        <div class="dashboard-row-full">
            <div class="admin-panel">
                <div id="waterfall_chart" class="chart-container"></div>
            </div>
        </div>

    </main>

    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawAllCharts);

        function drawAllCharts() {
            // Line Chart
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

            // Pie Chart
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

            // Waterfall Chart
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
</body>
</html>