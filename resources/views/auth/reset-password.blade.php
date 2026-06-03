<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Agriculture Product Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2d6a4f;
            --primary-dark: #1b4332;
            --primary-light: #52b788;
        }

        body {
            background: linear-gradient(135deg, #1b4332 0%, #2d6a4f 50%, #40916c 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }

        .reset-card {
            background: #fff;
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }

        .reset-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .reset-header .icon {
            width: 72px;
            height: 72px;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            font-size: 2rem;
            color: #fff;
        }

        .reset-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 4px;
        }

        .reset-header p {
            color: #888;
            font-size: 0.9rem;
            margin: 0;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px 16px;
            border: 2px solid #e8e8e8;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 3px rgba(45, 106, 79, 0.1);
        }

        .btn-reset {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            font-size: 1rem;
            color: #fff;
            width: 100%;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-reset:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(45, 106, 79, 0.3);
            color: #fff;
        }

        .btn-reset:disabled {
            opacity: 0.7;
            transform: none;
        }

        .alert-custom {
            border-radius: 10px;
            border: none;
            font-size: 0.85rem;
            padding: 12px 16px;
        }

        .input-icon-wrapper {
            position: relative;
        }

        .input-icon-wrapper .form-control {
            padding-left: 44px;
        }

        .input-icon-wrapper .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
            z-index: 5;
            transition: color 0.3s;
        }

        .form-label {
            color: #555;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .form-footer {
            text-align: center;
            margin-top: 20px;
            color: #aaa;
            font-size: 0.8rem;
        }

        .form-footer i {
            color: var(--primary-light);
        }

        .step-indicator {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-bottom: 24px;
        }

        .step-indicator .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #e0e0e0;
        }

        .step-indicator .dot.active {
            background: var(--primary-color);
            width: 24px;
            border-radius: 4px;
        }

        .password-strength {
            height: 4px;
            border-radius: 2px;
            background: #e0e0e0;
            margin-top: 8px;
            overflow: hidden;
            transition: all 0.3s;
        }

        .password-strength .bar {
            height: 100%;
            width: 0%;
            border-radius: 2px;
            transition: width 0.3s, background 0.3s;
        }

        .password-strength .bar.weak {
            width: 33%;
            background: #dc3545;
        }

        .password-strength .bar.medium {
            width: 66%;
            background: #ffc107;
        }

        .password-strength .bar.strong {
            width: 100%;
            background: var(--primary-light);
        }
    </style>
</head>
<body>
    <div class="reset-card">
        <div class="reset-header">
            <div class="icon">
                <i class="fas fa-lock-open"></i>
            </div>
            <h1>Reset Password</h1>
            <p>Choose a new password for your account.</p>
        </div>

        <!-- Step indicator -->
        <div class="step-indicator">
            <div class="dot"></div>
            <div class="dot active"></div>
        </div>

        @if($errors->any())
            <div class="alert alert-danger alert-custom">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <div class="input-icon-wrapper">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" name="email" id="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $email) }}" readonly
                           style="background: #f8f9fa; cursor: not-allowed;">
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">New Password</label>
                <div class="input-icon-wrapper">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" name="password" id="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Min. 6 characters" required minlength="6" autofocus>
                </div>
                <div class="password-strength">
                    <div class="bar" id="strength-bar"></div>
                </div>
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                <div class="input-icon-wrapper">
                    <i class="fas fa-check-circle input-icon"></i>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="form-control"
                           placeholder="Re-enter your new password" required minlength="6">
                </div>
            </div>

            <button type="submit" class="btn-reset" id="reset-btn">
                <i class="fas fa-save me-2"></i> Reset Password
            </button>
        </form>

        <div class="mt-4 pt-3 border-top text-center">
            <a href="{{ route('login') }}" class="text-decoration-none" style="color: var(--primary-color); font-weight: 600; font-size: 0.85rem;">
                <i class="fas fa-arrow-left me-1"></i> Back to Sign In
            </a>
        </div>

        <div class="form-footer">
            <i class="fas fa-seedling me-1"></i> Agriculture Product Management System
        </div>
    </div>

    <script>
        // Live password strength indicator
        document.getElementById('password').addEventListener('input', function() {
            var val = this.value;
            var bar = document.getElementById('strength-bar');
            bar.className = 'bar';

            if (val.length === 0) {
                bar.style.width = '0%';
                return;
            }

            var hasUpper = /[A-Z]/.test(val);
            var hasLower = /[a-z]/.test(val);
            var hasNumber = /[0-9]/.test(val);
            var hasSpecial = /[^A-Za-z0-9]/.test(val);
            var score = (val.length >= 6 ? 1 : 0) + (hasUpper ? 1 : 0) + (hasLower ? 1 : 0) + (hasNumber ? 1 : 0) + (hasSpecial ? 1 : 0);

            if (score <= 2) {
                bar.classList.add('weak');
            } else if (score <= 3) {
                bar.classList.add('medium');
            } else {
                bar.classList.add('strong');
            }
        });

        // Disable button on submit
        document.querySelector('form').addEventListener('submit', function() {
            var btn = document.getElementById('reset-btn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Resetting...';
        });
    </script>
</body>
</html>
