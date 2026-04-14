<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="/app.css">
</head>
<body class="login-body">
    <?php include '../header.php'; ?>

<main class="login-main">
    <div class="login-container">
        <!-- ===== LOGIN FORM ===== -->
        <div class="login-form active">
            <div class="login-header">
                <h1>Welcome Back</h1>
                <p>Sign in to your account</p>
            </div>

            <div class="success-message" id="successMessage"></div>
            <div class="error-message" id="errorMessage"></div>

            <form method="POST" action="/security/login-process.php">
            <div class="form-group">
                 <label for="loginEmail">Email Address</label>
                <input type="email" name="email" id="loginEmail" placeholder="Enter your email" required>
            </div>

                <div class="form-group">
                    <label for="loginPassword">Password</label>
                    <input type="password" name="password" id="loginPassword" placeholder="Enter your password" required>
                </div>

                <div class="remember-forgot">
                    <label>
                        <input type="checkbox" id="rememberMe"> Remember me
                    </label>
                    <a class="forgot-link" onclick="switchToForgotPassword(event)">Forgot Password?</a>
                </div>

                <button type="submit" class="login-btn">Sign In</button>
            </form>

            <div class="signup-link">
                Don't have an account? <a onclick="alert('Sign up feature coming soon!')">Create one</a>
            </div>
        </div>

        <!-- ===== FORGOT PASSWORD FORM ===== -->
        <div class="forgot-password-form">
            <a class="back-link" onclick="switchToLogin(event)">← Back to Login</a>

            <div class="login-header">
                <h1>Reset Password</h1>
                <p>We'll help you reset your password</p>
            </div>

            <div class="success-message" id="forgotPasswordSuccessMsg"></div>
            <div class="error-message" id="forgotPasswordErrorMsg"></div>

            <div id="forgotPasswordStep1">
                <p class="form-description">
                    Enter your email address and we'll send you a verification code to reset your password.
                </p>

                <form onsubmit="handleForgotPasswordEmail(event)">
                    <div class="form-group">
                        <label for="forgotEmail">Email Address</label>
                        <input type="email" id="forgotEmail" placeholder="Enter your email" required>
                    </div>

                    <button type="submit" class="login-btn">Send Verification Code</button>
                </form>
            </div>

            <div id="forgotPasswordStep2" style="display: none;">
                <p class="form-description">
                    Enter the verification code sent to your email.
                </p>

                <form onsubmit="handleVerifyCode(event)">
                    <div class="form-group">
                        <label>Verification Code</label>
                        <div class="verification-code-group">
                            <input type="text" class="code-input" maxlength="1" placeholder="0" required>
                            <input type="text" class="code-input" maxlength="1" placeholder="0" required>
                            <input type="text" class="code-input" maxlength="1" placeholder="0" required>
                            <input type="text" class="code-input" maxlength="1" placeholder="0" required>
                        </div>
                    </div>

                    <button type="submit" class="login-btn">Verify Code</button>
                </form>

                <div class="resend-code">
                    Didn't receive the code? 
                    <a class="resend-link" onclick="resendVerificationCode(event)">Resend</a>
                </div>
            </div>

            <div id="forgotPasswordStep3" style="display: none;">
                <p class="form-description">
                    Create a new password for your account.
                </p>

                <form onsubmit="handleResetPassword(event)">
                    <div class="form-group">
                        <label for="newPassword">New Password</label>
                        <input type="password" id="newPassword" placeholder="Enter new password" required>
                    </div>

                    <div class="form-group">
                        <label for="confirmPassword">Confirm Password</label>
                        <input type="password" id="confirmPassword" placeholder="Confirm your password" required>
                    </div>

                    <button type="submit" class="login-btn">Reset Password</button>
                </form>
            </div>
        </div>

        <!-- ===== FORGET ACCOUNT FORM ===== -->
        <div class="forget-account-form">
            <a class="back-link" onclick="switchToLogin(event)">← Back to Login</a>

            <div class="login-header">
                <h1>Account Recovery</h1>
                <p>Help us locate your account</p>
            </div>

            <div class="success-message" id="forgetAccountSuccessMsg"></div>
            <div class="error-message" id="forgetAccountErrorMsg"></div>

            <form onsubmit="handleForgetAccount(event)">
                <p class="form-description">
                    We'll help you find your account. Enter any information you remember.
                </p>

                <div class="form-group">
                    <label for="recoveryEmail">Email Address (if you remember)</label>
                    <input type="email" id="recoveryEmail" placeholder="Enter your email">
                </div>

                <div class="form-group">
                    <label for="recoveryPhone">Phone Number (if you remember)</label>
                    <input type="tel" id="recoveryPhone" placeholder="Enter your phone number">
                </div>

                <div class="form-group">
                    <label for="recoveryUsername">Username (if you remember)</label>
                    <input type="text" id="recoveryUsername" placeholder="Enter your username">
                </div>

                <button type="submit" class="login-btn">Search for Account</button>
            </form>

            <div class="signup-link">
                Can't find your account? <a onclick="alert('Contact support at support@example.com')">Contact Support</a>
            </div>
        </div>
    </div>
</main>

    <script>
function switchToForgotPassword(event) {
    event.preventDefault();
    document.querySelector('.login-form').classList.remove('active');
    document.querySelector('.forget-account-form').classList.remove('active');
    document.querySelector('.forgot-password-form').classList.add('active');
    resetForgotPasswordForm();
}

function switchToLogin(event) {
    event.preventDefault();
    document.querySelector('.login-form').classList.add('active');
    document.querySelector('.forgot-password-form').classList.remove('active');
    document.querySelector('.forget-account-form').classList.remove('active');
    clearAllMessages();
}

function switchToForgetAccount() {
    document.querySelector('.login-form').classList.remove('active');
    document.querySelector('.forgot-password-form').classList.remove('active');
    document.querySelector('.forget-account-form').classList.add('active');
}

function handleLoginSubmit(event) {
    event.preventDefault();
    
    const email = document.getElementById('loginEmail').value;
    const password = document.getElementById('loginPassword').value;
    const rememberMe = document.getElementById('rememberMe').checked;

    if (!email || !password) {
        showErrorMessage('Please fill in all fields.', 'errorMessage');
        return;
    }

    if (!email.includes('@')) {
        showErrorMessage('Please enter a valid email address.', 'errorMessage');
        return;
    }

    if (password.length < 6) {
        showErrorMessage('Password must be at least 6 characters.', 'errorMessage');
        return;
    }

    showSuccessMessage('Login successful! Redirecting...', 'successMessage');
    
    if (rememberMe) {
        localStorage.setItem('userEmail', email);
    }

    setTimeout(() => {
        window.location.href = '../page/account.php';
    }, 1500);
}

function handleForgotPasswordEmail(event) {
    event.preventDefault();
    
    const email = document.getElementById('forgotEmail').value;
    const errorMsg = document.getElementById('forgotPasswordErrorMsg');

    if (!email.includes('@')) {
        errorMsg.textContent = 'Please enter a valid email address.';
        errorMsg.classList.add('show');
        return;
    }

    showSuccessMessage('Verification code sent to ' + email, 'forgotPasswordSuccessMsg');
    
    setTimeout(() => {
        document.getElementById('forgotPasswordStep1').style.display = 'none';
        document.getElementById('forgotPasswordStep2').style.display = 'block';
        document.getElementById('forgotPasswordSuccessMsg').classList.remove('show');
    }, 1000);
}

function handleVerifyCode(event) {
    event.preventDefault();
    
    const codeInputs = document.querySelectorAll('.code-input');
    const code = Array.from(codeInputs).map(input => input.value).join('');

    if (code.length !== 4) {
        showErrorMessage('Please enter a valid 4-digit code.', 'forgotPasswordErrorMsg');
        return;
    }

    showSuccessMessage('Code verified! Now set your new password.', 'forgotPasswordSuccessMsg');
    
    setTimeout(() => {
        document.getElementById('forgotPasswordStep2').style.display = 'none';
        document.getElementById('forgotPasswordStep3').style.display = 'block';
        document.getElementById('forgotPasswordSuccessMsg').classList.remove('show');
    }, 1000);
}

function handleResetPassword(event) {
    event.preventDefault();
    
    const newPassword = document.getElementById('newPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    const errorMsg = document.getElementById('forgotPasswordErrorMsg');

    if (newPassword !== confirmPassword) {
        errorMsg.textContent = 'Passwords do not match.';
        errorMsg.classList.add('show');
        return;
    }

    if (newPassword.length < 6) {
        errorMsg.textContent = 'Password must be at least 6 characters.';
        errorMsg.classList.add('show');
        return;
    }

    showSuccessMessage('Password reset successfully! Redirecting to login...', 'forgotPasswordSuccessMsg');
    
    setTimeout(() => {
        switchToLogin(event);
        document.getElementById('forgotPasswordStep3').style.display = 'none';
        document.getElementById('forgotPasswordStep1').style.display = 'block';
    }, 1500);
}

function handleForgetAccount(event) {
    event.preventDefault();
    
    const email = document.getElementById('recoveryEmail').value;
    const phone = document.getElementById('recoveryPhone').value;
    const username = document.getElementById('recoveryUsername').value;
    const errorMsg = document.getElementById('forgetAccountErrorMsg');

    if (!email && !phone && !username) {
        errorMsg.textContent = 'Please enter at least one piece of information.';
        errorMsg.classList.add('show');
        return;
    }

    showSuccessMessage('Account found! We\'ve sent recovery instructions to your email.', 'forgetAccountSuccessMsg');
    
    setTimeout(() => {
        switchToLogin(event);
    }, 2000);
}

function showSuccessMessage(message, elementId) {
    const element = document.getElementById(elementId);
    element.textContent = '✓ ' + message;
    element.classList.add('show');
}

function showErrorMessage(message, elementId) {
    const element = document.getElementById(elementId);
    element.textContent = '✗ ' + message;
    element.classList.add('show');
}

function clearAllMessages() {
    document.getElementById('successMessage').classList.remove('show');
    document.getElementById('errorMessage').classList.remove('show');
    document.getElementById('forgotPasswordSuccessMsg').classList.remove('show');
    document.getElementById('forgotPasswordErrorMsg').classList.remove('show');
    document.getElementById('forgetAccountSuccessMsg').classList.remove('show');
    document.getElementById('forgetAccountErrorMsg').classList.remove('show');
}

function resetForgotPasswordForm() {
    document.getElementById('forgotPasswordStep1').style.display = 'block';
    document.getElementById('forgotPasswordStep2').style.display = 'none';
    document.getElementById('forgotPasswordStep3').style.display = 'none';
    document.getElementById('forgotEmail').value = '';
    document.querySelectorAll('.code-input').forEach(input => input.value = '');
    document.getElementById('newPassword').value = '';
    document.getElementById('confirmPassword').value = '';
    clearAllMessages();
}

function resendVerificationCode(event) {
    event.preventDefault();
    const link = event.target;
    
    if (link.classList.contains('disabled')) return;

    showSuccessMessage('Verification code resent!', 'forgotPasswordSuccessMsg');
    
    link.classList.add('disabled');
    link.textContent = 'Resending...';
    
    setTimeout(() => {
        link.classList.remove('disabled');
        link.textContent = 'Resend';
        document.getElementById('forgotPasswordSuccessMsg').classList.remove('show');
    }, 3000);
}

window.addEventListener('DOMContentLoaded', () => {
    const savedEmail = localStorage.getItem('userEmail');
    
    if (savedEmail) {
        document.getElementById('loginEmail').value = savedEmail;
        document.getElementById('rememberMe').checked = true;
    }

    const codeInputs = document.querySelectorAll('.code-input');
    codeInputs.forEach((input, index) => {
        input.addEventListener('input', (e) => {
            if (e.target.value && index < codeInputs.length - 1) {
                codeInputs[index + 1].focus();
            }
        });

        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !input.value && index > 0) {
                codeInputs[index - 1].focus();
            }
        });
    });

    document.getElementById('recoveryPhone').addEventListener('input', (e) => {
        e.target.value = e.target.value.replace(/\D/g, '');
    });
});
    </script>
    <?php include '../footer.php'; ?>
</body>
</html>
