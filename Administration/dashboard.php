<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LIMEL Admin - Dashboard</title>
    <link rel="stylesheet" href="/app.css">
</head>

<body class="admin-body">
    
    <?php include 'sidebar.php'; ?>

    <main class="admin-content">

        <header class="admin-top-header">
            <h2>Dashboard</h2>

            <div class="header-user-info">
                <span class="logged-in-email">staff@limel.inc</span>
                <img src="/images?icon/user.png" width="20" alt="User" class="user-avatar">
            </div>

            
               <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
               <script type="text/javascript">
                    google.charts.load('current', {'packages':['corechart']});
                    google.charts.setOnLoadCallback(drawChart);

                    function drawChart() {
                        var data = google.visualization.arrayToDataTable([
                            ['Year', 'Sales', 'Expenses'],
                            ['2004',  1000,      400],
                            ['2005',  1170,      460],
                            ['2006',  660,       1120],
                            ['2007',  1030,      540]
                        ]);

                        var options = {
                            title: 'Company Performance',
                            curveType: 'function',
                            legend: { position: 'bottom' }
                        };

                        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

                        chart.draw(data, options);
                    }
                </script>
              
        </header>
        <body class="admin-body">
            <div id="curve_chart" style="width: 900px; height: 500px"></div>
        </body>
    </main>
</body>
</html> 