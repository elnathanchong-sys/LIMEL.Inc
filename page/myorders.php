<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <style>
        /* Consistent Background */
        body { 
            background-color: black; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
        }

        .order-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 0 20px;
        }

        /* White Container style */
        .order-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #eee;
        }

        .order-item {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 20px 0;
            border-bottom: 1px solid #eee;
        }
        .order-item:last-child { border-bottom: none; }

        .order-item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            background: #f9f9f9;
        }

        .order-info { flex: 1; }
        .order-name { font-weight: bold; font-size: 17px; color: #2d3436; }
        .order-meta { font-size: 14px; color: #636e72; margin-top: 4px; }
        .order-date { font-size: 13px; color: #b2bec3; margin-top: 4px; }

        .order-status {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        /* Status Colors */
        .status-processing { background: #fff3cd; color: #856404; }
        .status-shipped     { background: #cce5ff; color: #004085; }
        .status-delivered   { background: #d4edda; color: #155724; }
        .status-cancelled   { background: #f8d7da; color: #721c24; }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #667eea;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>

<main class="order-container">
    <a href="myaccount.php" class="back-link">← Back to Dashboard</a>
    
    <h1 style="color: #ffffff; margin-bottom: 20px;">My Orders</h1>

    <div class="order-card">
        <div class="order-item">
            <img src="https://via.placeholder.com/80" alt="product">
            
            <div class="order-info">
                <div class="order-name">Product Name Example</div>
                <div class="order-meta">
                    Qty: 1 &nbsp;|&nbsp; 
                    <strong>RM 45.00</strong>
                </div>
                <div class="order-date">
                    Ordered on: 12 Oct 2023
                </div>
            </div>

            <div class="order-status status-processing">
                Processing
            </div>
        </div>

        <div class="order-item">
            <img src="https://via.placeholder.com/80" alt="product">
            
            <div class="order-info">
                <div class="order-name">Product Name Example</div>
                <div class="order-meta">
                    Qty: 2 &nbsp;|&nbsp; 
                    <strong>RM 120.00</strong>
                </div>
                <div class="order-date">
                    Ordered on: 10 Oct 2023
                </div>
            </div>

            <div class="order-status status-delivered">
                Delivered
            </div>
        </div>
    </div>
</main>

</body>
</html>