

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="/app.css">
</head>
<body class="account-management-system">
    <?php include '../header.php'; ?>
    <div class="user-status-bar">
        Logged in as: <span id="navUsername">Guest</span>
    </div>

    <div class="container">
        <div class="header">
            <h1>Account Settings</h1>
        </div>

        <div class="card">
            <div class="success-message" id="successMessage">✓ Profile updated successfully</div>
            <div class="error-message" id="errorMessage"></div>

            <div class="profile-section">
                <div class="upload-area">
                    <div class="profile-picture-container" id="profilePictureContainer">
                        <img id="imagePreview" src="" alt="Profile" style="display: none;">
                        <span id="imgPlaceholder">USER</span>
                    </div>

                    <div class="drag-drop-area" id="dragDropArea">
                        <div class="drag-drop-text">Drag & Drop or Click to Upload</div>
                        <div class="drag-drop-hint">PNG, JPG up to 5MB</div>
                    </div>
                    <input type="file" id="fileInput" accept="image/*" style="display: none;">
                </div>

                <div class="form-section">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" placeholder="Enter username">
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" placeholder="example@gmail.com">
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" placeholder="Numbers only (e.g. 0123456789)">
                    </div>
                </div>

                <div class="button-group">
                    <button class="btn btn-primary" id="saveBtn">Save Changes</button>
                    <button class="btn btn-secondary" id="clearBtn">Clear All</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
    const phoneInput = document.getElementById('phone');
    const dragDropArea = document.getElementById('dragDropArea');
    const fileInput = document.getElementById('fileInput');
    const imagePreview = document.getElementById('imagePreview');
    const imgPlaceholder = document.getElementById('imgPlaceholder');
    const saveBtn = document.getElementById('saveBtn');
    const clearBtn = document.getElementById('clearBtn');
    const navUsername = document.getElementById('navUsername');
    
    const successMsg = document.getElementById('successMessage');
    const errorMsg = document.getElementById('errorMessage');

   
    phoneInput.addEventListener('input', (e) => {
        
        e.target.value = e.target.value.replace(/\D/g, '');
    });

   
    const handleFileUpload = (file) => {
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (e) => {
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
                imgPlaceholder.style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    };

    dragDropArea.addEventListener('click', () => fileInput.click());

    fileInput.addEventListener('change', (e) => {
        handleFileUpload(e.target.files[0]);
    });

   
    dragDropArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        dragDropArea.style.borderColor = '#764ba2';
    });

    dragDropArea.addEventListener('dragleave', () => {
        dragDropArea.style.borderColor = '#667eea';
    });

    dragDropArea.addEventListener('drop', (e) => {
        e.preventDefault();
        dragDropArea.style.borderColor = '#667eea';
        const file = e.dataTransfer.files[0];
        handleFileUpload(file);
    });

   
    saveBtn.addEventListener('click', () => {
        const userValue = document.getElementById('username').value;
        const emailValue = document.getElementById('email').value;
        const phoneValue = phoneInput.value;

        if (userValue && emailValue && phoneValue) {
            
            navUsername.textContent = userValue;
            
         
            errorMsg.classList.remove('show');
            successMsg.classList.add('show');
            
            setTimeout(() => successMsg.classList.remove('show'), 3000);
        } else {
            errorMsg.textContent = "Please fill in all fields.";
            errorMsg.classList.add('show');
        }
    });

    
    clearBtn.addEventListener('click', () => {
      
        document.getElementById('username').value = '';
        document.getElementById('email').value = '';
        phoneInput.value = '';
        
    
        imagePreview.src = '';
        imagePreview.style.display = 'none';
        imgPlaceholder.style.display = 'block';
        fileInput.value = '';

        
        successMsg.classList.remove('show');
        errorMsg.classList.remove('show');
    });
});
</script>
<?php include '../footer.php'; ?>
</body>
</html>