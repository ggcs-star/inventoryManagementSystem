<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification | ShopNow</title>
    <style>
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
        
        .verification-features {
            margin-top: 40px;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
        }
        
        .verification-item {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .verification-icon {
            background: rgba(255, 255, 255, 0.1);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }
        
        .verify-section {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: white;
        }
        
        .verify-container {
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
        
        .verify-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .verify-header h2 {
            font-size: 28px;
            color: #1e293b;
            margin-bottom: 8px;
        }
        
        .verify-header p {
            color: #64748b;
            font-size: 15px;
            line-height: 1.6;
        }
        
        .error-container {
            background: #fef2f2;
            border: 1px solid #fee2e2;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 24px;
            text-align: center;
        }
        
        .error-message {
            color: #dc2626;
            font-size: 14px;
            font-weight: 500;
        }
        
        .success-container {
            background: #dcfce7;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 24px;
            text-align: center;
        }
        
        .success-message {
            color: #16a34a;
            font-size: 14px;
            font-weight: 500;
        }
        
        .otp-input-group {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            justify-content: center;
        }
        
        .otp-input {
            width: 50px;
            height: 50px;
            text-align: center;
            font-size: 20px;
            font-weight: 600;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            background: #f8fafc;
            transition: all 0.3s;
        }
        
        .otp-input:focus {
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
        }
        
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.2);
        }
        
        .resend-section {
            text-align: center;
            margin-top: 30px;
            padding-top: 25px;
            border-top: 1px solid #e2e8f0;
        }
        
        .resend-text {
            color: #64748b;
            font-size: 15px;
            margin-bottom: 15px;
        }
        
        .resend-btn {
            display: inline-block;
            padding: 14px 32px;
            background: white;
            border: 2px solid #2563eb;
            border-radius: 10px;
            color: #2563eb;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            font-size: 15px;
        }
        
        .resend-btn:hover {
            background: #2563eb;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.2);
        }
        
        .timer {
            color: #dc2626;
            font-weight: 500;
            margin-left: 5px;
        }
        
        @media (max-width: 1024px) {
            .container {
                flex-direction: column;
            }
            
            .image-section {
                padding: 40px;
                text-align: center;
            }
            
            .verification-features {
                justify-content: center;
            }
        }
        
        @media (max-width: 768px) {
            .image-section h1 {
                font-size: 32px;
            }
            
            .verification-features {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .otp-input {
                width: 45px;
                height: 45px;
                font-size: 18px;
            }
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const otpInputs = document.querySelectorAll('.otp-input');
            
            otpInputs.forEach((input, index) => {
                input.addEventListener('input', function(e) {
                    if (this.value.length === 1 && index < otpInputs.length - 1) {
                        otpInputs[index + 1].focus();
                    }
                    
                    if (this.value.length > 1) {
                        this.value = this.value.charAt(0);
                    }
                });
                
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && this.value.length === 0 && index > 0) {
                        otpInputs[index - 1].focus();
                    }
                });
                
                input.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const pastedData = e.clipboardData.getData('text');
                    const digits = pastedData.replace(/\D/g, '').split('');
                    
                    digits.forEach((digit, idx) => {
                        if (idx < otpInputs.length) {
                            otpInputs[idx].value = digit;
                        }
                    });
                    
                    if (digits.length >= otpInputs.length) {
                        otpInputs[otpInputs.length - 1].focus();
                    } else if (digits.length > 0) {
                        otpInputs[digits.length].focus();
                    }
                });
            });
        });
        
        function startTimer(duration, display) {
            let timer = duration, minutes, seconds;
            const interval = setInterval(function () {
                minutes = parseInt(timer / 60, 10);
                seconds = parseInt(timer % 60, 10);

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                display.textContent = minutes + ":" + seconds;

                if (--timer < 0) {
                    clearInterval(interval);
                    document.getElementById('resendBtn').disabled = false;
                    display.textContent = "00:00";
                }
            }, 1000);
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="image-section">
            <h1>Email Verification</h1>
            <p>We've sent a 6-digit verification code to your email. Please enter it below to verify your account.</p>
            
            <div class="verification-features">
                <div class="verification-item">
                    <div class="verification-icon">✓</div>
                    <div>
                        <h4>Account Security</h4>
                        <p style="font-size: 14px; opacity: 0.8;">Protect your account</p>
                    </div>
                </div>
                <div class="verification-item">
                    <div class="verification-icon">📧</div>
                    <div>
                        <h4>Email Confirmation</h4>
                        <p style="font-size: 14px; opacity: 0.8;">Verify email ownership</p>
                    </div>
                </div>
                <div class="verification-item">
                    <div class="verification-icon">⚡</div>
                    <div>
                        <h4>Quick Process</h4>
                        <p style="font-size: 14px; opacity: 0.8;">Complete in seconds</p>
                    </div>
                </div>
                <div class="verification-item">
                    <div class="verification-icon">🔒</div>
                    <div>
                        <h4>Secure Access</h4>
                        <p style="font-size: 14px; opacity: 0.8;">One-time use only</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="verify-section">
            <div class="verify-container">
                <div class="logo">SHOP<span>NOW</span></div>
                
                <div class="verify-header">
                    <h2>Enter Verification Code</h2>
                    <p>Check your email for the 6-digit OTP and enter it below</p>
                </div>
                
                @if ($errors->any())
                    <div class="error-container">
                        <p class="error-message">{{ $errors->first() }}</p>
                    </div>
                @endif
                
                @if (session('status') === 'otp-sent')
                    <div class="success-container">
                        <p class="success-message">✓ OTP sent successfully to your email</p>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('otp.verify') }}" id="verifyForm">
                    @csrf
                    
                    <div class="otp-input-group">
                        <input type="text" name="otp1" class="otp-input" maxlength="1" required autofocus>
                        <input type="text" name="otp2" class="otp-input" maxlength="1" required>
                        <input type="text" name="otp3" class="otp-input" maxlength="1" required>
                        <input type="text" name="otp4" class="otp-input" maxlength="1" required>
                        <input type="text" name="otp5" class="otp-input" maxlength="1" required>
                        <input type="text" name="otp6" class="otp-input" maxlength="1" required>
                    </div>
                    
                    <input type="hidden" name="otp" id="fullOtp">
                    
                    <button type="submit" class="submit-btn" onclick="combineOtp()">Verify OTP</button>
                </form>
                
                <div class="resend-section">
                    <div class="resend-text">
                        Didn't receive the code? 
                        <span id="timer" class="timer">02:00</span>
                    </div>
                    <form method="POST" action="{{ route('otp.resend') }}">
                        @csrf
                        <button type="submit" class="resend-btn" id="resendBtn" disabled>Resend OTP</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function combineOtp() {
            const otp1 = document.querySelector('input[name="otp1"]').value || '';
            const otp2 = document.querySelector('input[name="otp2"]').value || '';
            const otp3 = document.querySelector('input[name="otp3"]').value || '';
            const otp4 = document.querySelector('input[name="otp4"]').value || '';
            const otp5 = document.querySelector('input[name="otp5"]').value || '';
            const otp6 = document.querySelector('input[name="otp6"]').value || '';
            
            const fullOtp = otp1 + otp2 + otp3 + otp4 + otp5 + otp6;
            document.getElementById('fullOtp').value = fullOtp;
        }
        
        window.onload = function () {
            const twoMinutes = 120;
            const display = document.querySelector('#timer');
            const resendBtn = document.getElementById('resendBtn');
            
            startTimer(twoMinutes, display);
            
            setTimeout(function() {
                resendBtn.disabled = false;
            }, twoMinutes * 1000);
        };
    </script>
</body>
</html>