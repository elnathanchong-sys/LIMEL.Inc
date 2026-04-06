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
            <input type="file" id="upload" accept="image/*" style="display:none;">
            <p style="cursor:pointer;">Change Profile Picture</p>
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
                <label for="email" class="label">Email:</label>
                <input type="email" id="email" class="value">
            </div>

            <div class="detail">
                <label for="password" class="label">Password:</label>
                <input type="password" id="password" class="value" placeholder="Enter new password">
            </div>

            <div class="detail">
                <label class="label">Confirm Password:</label>
                <input type="password" class="value" placeholder="Confirm password">
            </div>

            <div class="button-wrapper">
                <button class="customize-button" id="save-btn">Save Changes</button>
                <button type="button" class="customize-button" id="clear-btn" style="background: #ccc; color: #000;">Clear Data</button>
            </div>

            <p id="success-msg">Changes saved!</p>

        </div> 
    </div> 
</section>
</main>

<?php include '../footer.php'; ?>

<script>
    const uploadInput = document.getElementById('upload');
    const profileImg = document.getElementById('profile-img');
    const saveBtn = document.getElementById('save-btn');
    const clearBtn = document.getElementById('clear-btn');
    const successMsg = document.getElementById('success-msg');
    const pTrigger = document.querySelector('.profile-picture p');

    const usernameInput = document.getElementById('username');
    const phoneInput = document.getElementById('phone');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.querySelectorAll('input[type="password"]')[1];

    window.addEventListener('DOMContentLoaded', () => {
        const savedData = JSON.parse(localStorage.getItem('userProfile'));
        if (savedData) {
            usernameInput.value = savedData.username || '';
            phoneInput.value = savedData.phone || '';
            emailInput.value = savedData.email || '';
            if (savedData.profilePic) {
                profileImg.src = savedData.profilePic;
            }
        }
    });

    pTrigger.addEventListener('click', () => uploadInput.click());

    uploadInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                profileImg.src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });

    const clearData = () => {
        if(confirm("Are you sure you want to clear all data?")) {
            usernameInput.value = "";
            phoneInput.value = "";
            emailInput.value = "";
            passwordInput.value = "";
            confirmPasswordInput.value = "";
            profileImg.src = "images/default-profile.png";
            localStorage.removeItem('userProfile');
        }
    };

    if (clearBtn) {
        clearBtn.addEventListener('click', clearData);
    }

    saveBtn.addEventListener('click', () => {
        if (!usernameInput.value.trim() || !phoneInput.value.trim() || !emailInput.value.trim()) {
            alert("Please fill in all required fields.");
            return;
        }

        if (passwordInput.value !== confirmPasswordInput.value) {
            alert("Passwords do not match!");
            return;
        }

        const userData = {
            username: usernameInput.value,
            phone: phoneInput.value,
            email: emailInput.value,
            profilePic: profileImg.src 
        };

        localStorage.setItem('userProfile', JSON.stringify(userData));

        successMsg.classList.add('show');
        setTimeout(() => {
            successMsg.classList.remove('show');
        }, 3000);

        passwordInput.value = "";
        confirmPasswordInput.value = "";
    });
</script>

</body>
</html>