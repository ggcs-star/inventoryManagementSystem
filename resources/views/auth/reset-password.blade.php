<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | ShopNow</title>
    <style>
        /* Password toggle styles - Reset password page ke liye bhi */
.password-wrapper {
    position: relative;
}

.password-toggle {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
    color: #64748b;
    padding: 0;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.eye-icon {
    fill: #64748b;
    transition: fill 0.3s;
    width: 20px;
    height: 20px;
}

.password-toggle:hover .eye-icon {
    fill: #2563eb;
}
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }
        
        body {
            background: #f8fafc;
            min-height: 100vh;
            display: flex;
        }
        
        .container {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }
        
        .image-section {
            flex: 1;
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
                        url('https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80');
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px;
            color: white;
        }
        
        .image-section h1 {
            font-size: 42px;
            margin-bottom: 20px;
            font-weight: 700;
            line-height: 1.2;
        }
        
        .image-section p {
            font-size: 18px;
            opacity: 0.9;
            line-height: 1.6;
            max-width: 500px;
        }
        
        .security-features {
            margin-top: 40px;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
        }
        
        .security-item {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .security-icon {
            background: rgba(255, 255, 255, 0.1);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }
        
        .reset-section {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: white;
        }
        
        .reset-container {
            width: 100%;
            max-width: 420px;
        }
        
        .logo {
            font-size: 32px;
            font-weight: 800;
            color: #2563eb;
            margin-bottom: 10px;
            text-align: center;
        }
        
        .logo span {
            color: #7c3aed;
        }
        
        .reset-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .reset-header h2 {
            font-size: 28px;
            color: #1e293b;
            margin-bottom: 8px;
        }
        
        .reset-header p {
            color: #64748b;
            font-size: 15px;
            line-height: 1.6;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group input {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s;
            background: #f8fafc;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #2563eb;
            background: white;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        
        .submit-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
        }
        
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.2);
        }
        
        .login-section {
            text-align: center;
            margin-top: 30px;
            padding-top: 25px;
            border-top: 1px solid #e2e8f0;
        }
        
        .login-text {
            color: #64748b;
            font-size: 15px;
            margin-bottom: 15px;
        }
        
        .login-btn {
            display: inline-block;
            padding: 14px 32px;
            background: white;
            border: 2px solid #2563eb;
            border-radius: 10px;
            color: #2563eb;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .login-btn:hover {
            background: #2563eb;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.2);
        }
        
        .back-section {
            margin-bottom: 30px;
        }
        .success-container {
    background: #ecfdf5;
    border: 1px solid #a7f3d0;
    color: #065f46;
    border-radius: 8px;
    padding: 14px 16px;
    margin-bottom: 24px;
    font-size: 14px;
    font-weight: 500;
}

.error-container {
    background: #fef2f2;
    border: 1px solid #fee2e2;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 24px;
}

.error-container li {
    color: #dc2626;
    font-size: 14px;
}

        
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #64748b;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .back-link:hover {
            color: #2563eb;
        }
        
        .password-requirements {
            background: #f0f9ff;
            border: 1px solid #e0f2fe;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .requirements-title {
            color: #0369a1;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .requirements-list {
            list-style: none;
            font-size: 13px;
            color: #64748b;
        }
        
        .requirements-list li {
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        @media (max-width: 1024px) {
            .container {
                flex-direction: column;
            }
            
            .image-section {
                padding: 40px;
                text-align: center;
            }
            
            .security-features {
                justify-content: center;
            }
        }
        
        @media (max-width: 768px) {
            .image-section h1 {
                font-size: 32px;
            }
            
            .security-features {
                grid-template-columns: 1fr;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="image-section">
            <h1>Set New Password</h1>
            <p>Create a strong, new password for your account to ensure maximum security and protection.</p>
            
            <div class="security-features">
                <div class="security-item">
                    <div class="security-icon">🔐</div>
                    <div>
                        <h4>Strong Encryption</h4>
                        <p style="font-size: 14px; opacity: 0.8;">Military-grade password protection</p>
                    </div>
                </div>
                <div class="security-item">
                    <div class="security-icon">⚡</div>
                    <div>
                        <h4>Instant Update</h4>
                        <p style="font-size: 14px; opacity: 0.8;">Password changed immediately</p>
                    </div>
                </div>
                <div class="security-item">
                    <div class="security-icon">🛡️</div>
                    <div>
                        <h4>Account Safety</h4>
                        <p style="font-size: 14px; opacity: 0.8;">Protect your personal information</p>
                    </div>
                </div>
                <div class="security-item">
                    <div class="security-icon">📱</div>
                    <div>
                        <h4>Cross-Device Sync</h4>
                        <p style="font-size: 14px; opacity: 0.8;">Updated across all your devices</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="reset-section">
            <div class="reset-container">
                <div class="logo">SHOP<span>NOW</span></div>
                
                <div class="back-section">
                    <a href="{{ url('/otp.notice') }}" class="back-link">
                        ← Back to Verify OTP
                    </a>
                </div>
                
                <div class="reset-header">
                    <h2>Reset Password</h2>
                    <p>Enter your OTP and create a new secure password for your account.</p>
                </div>
                
                <div class="password-requirements">
                    <div class="requirements-title">Password Requirements:</div>
                    <ul class="requirements-list">
                        <li>✓ Minimum 8 characters</li>
                        <li>✓ At least one uppercase letter</li>
                        <li>✓ At least one number</li>
                        <li>✓ At least one special character</li>
                    </ul>
                </div>
                
                <form method="POST" action="/reset-password">
                    @csrf
                    
                 
                    
                   <div class="form-group">
    <input type="text" name="otp" placeholder="Enter OTP" required>
</div>

<div class="form-group">
    <div class="password-wrapper">
        <input type="password" name="password" id="newPassword" placeholder="New Password" required>
        <button type="button" class="password-toggle" onclick="togglePassword('newPassword')">
            <svg class="eye-icon" viewBox="0 0 24 24" width="20" height="20">
                <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
            </svg>
        </button>
    </div>
</div>

<div class="form-group">
    <div class="password-wrapper">
        <input type="password" name="password_confirmation" id="confirmPassword" placeholder="Confirm New Password" required>
        <button type="button" class="password-toggle" onclick="togglePassword('confirmPassword')">
            <svg class="eye-icon" viewBox="0 0 24 24" width="20" height="20">
                <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
            </svg>
        </button>
    </div>
</div>
                    
                    <button type="submit" class="submit-btn">Reset Password</button>
                </form>
                
                <div class="login-section">
                    <div class="login-text">Remembered your password?</div>
                    <a href="{{ url('/login') }}" class="login-btn">Sign In Instead</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<script>
function togglePassword(fieldId) {
    const passwordInput = document.getElementById(fieldId);
    const toggleButton = passwordInput.nextElementSibling;
    const eyeIcon = toggleButton.querySelector('.eye-icon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        // Hide icon (eye with slash)
        eyeIcon.innerHTML = `<path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" style="opacity:0.5"/>
                             <path d="M2 2l20 20M9.88 9.88a3 3 0 1 0 4.24 4.24M17.66 17.66A10.71 10.71 0 0 1 12 20.5c-5 0-9.27-3.11-11-7.5a10.84 10.84 0 0 1 3.16-4.84"/>`;
    } else {
        passwordInput.type = 'password';
        // Show icon (normal eye)
        eyeIcon.innerHTML = `<path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>`;
    }
}
</script>