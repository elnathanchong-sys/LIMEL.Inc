<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LIMEL - Contact Us</title>
    <link rel="stylesheet" href="/app.css">
</head>
    
<body>
    <?php include '../header.php'; ?>

    <main class="contact-main-container">
        
        <div class="contact-header">
            <h1>Contact Us</h1>
            <p>We'd love to hear from you. Reach out to us below.</p>
        </div>

        <div class="contact-wrapper">
            
            <div class="contact-info-panel">
                <h3>Contact Information</h3>
                
                <div class="con-info">
                    <strong>Business Name</strong>
                    <span>LIMEL</span>
                </div>
                <div class="con-info">
                    <strong>Email</strong>
                    <span>limelinc@gmail.com</span>
                </div>
                <div class="con-info">
                    <strong>Phone</strong>
                    <span>+60 12 345 6722</span>
                </div>
                <div class="con-info">
                    <strong>Business Hours</strong>
                    <span>Greenwich Mean Time +8 - Monday to Saturday, 10A.M. - 6P.M.</span>
                </div>
                <div class="con-info">
                    <strong>Response Time</strong>
                    <span>Within 24 hours</span>
                </div>
            </div>

            <div class="contact-form-panel">
                <h3>Send us a Message</h3>
                
                <form action="#" method="POST" class="limel-form" id="contactForm">
                    <div class="input-row">
                        <input type="text" placeholder="Your Name" required>
                        <input type="email" placeholder="Your Email" required>
                    </div>
                    <input type="text" placeholder="Order Number (Optional)">
                    <textarea rows="5" placeholder="How can we help you?" required></textarea>
                    <button type="submit" class="submit-message-btn">Send Message</button>
                </form>
            </div>

        </div>
    </main>

    <?php include '../footer.php'; ?>
</body>
</html>