<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LIMEL</title>
    <link rel="stylesheet" href="/app.css">
</head>

<body>

<?php include '../header.php'; ?>

<main>
<section class="account-page">
    <div class="account-container">

        <div class="profile-picture">
            <img src="images/default-profile.png" alt="Profile Picture" id="profile-img">
            <input type="file" id="upload" accept="image/*">
            <p>Change Profile Picture</p>
        </div>

        <div class="account-details">

            <div class="detail">
                <label for="username" class="label">Username:</label>
                <input type="text" id="username" class="value">
            </div>

            <div class="detail">
                <label for="phone" class="label">Phone:</label>
                <input type="tel" id="phone" class="value">
            </div>

            <div class="detail">
                <label>Email:</label>
                <input type="email">
            </div>

            <div class="detail">
                <label>Password:</label>
                <input type="password" placeholder="Enter new password">
            </div>

            <div class="detail">
                <label>Confirm Password:</label>
                <input type="password" placeholder="Confirm password">
            </div>

            <div class="button-wrapper">
                <button class="customize-button" id="save-btn">Save Changes</button>
            </div>

            <p id="success-msg">Changes saved!</p>


        </div> 

    </div> 
</section>
</main>

<?php include '../footer.php'; ?>

</body>
</html>