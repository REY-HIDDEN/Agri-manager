<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Agriculture Product Management</title>
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
            line-height: 1.5;
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

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--primary-color);
            font-weight: 600;
            font-size: 0.85rem;
            text-decoration: none;
            transition: color 0.2s;
        }

        .back-link:hover {
            color: var(--primary-dark);
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
    </style>
</head>
<body>
    <div class="reset-card">
        <div class="reset-header">
            <div class="icon">
                <i class="fas fa-key"></i>
            </div>
            <h1>Forgot Password?</h1>
            <p>Enter your registered email address and we'll send you a password reset link.</p>
        </div>

        <!-- Step indicator -->
        <div class="step-indicator">
            <div class="dot active"></div>
            <div class="dot"></div>
        </div>

        @if(session('status'))
            <div class="alert alert-success alert-custom">
                <i class="fas fa-check-circle me-2"></i> {{ session('status') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-custom">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="form-label" style="color: #555; font-weight: 600; font-size: 0.85rem;">Email Address</label>
                <div class="input-icon-wrapper">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" name="email" id="email"
                           class="form-control @error('email') is-invalid @enderror"
                           placeholder="you@example.com" value="{{ old('email') }}" required autofocus>
                </div>
            </div>

            <button type="submit" class="btn-reset" id="send-btn">
                <i class="fas fa-paper-plane me-2"></i> Send Reset Link
            </button>
        </form>

        <div class="mt-4 pt-3 border-top text-center">
            <a href="{{ route('login') }}" class="back-link">
                <i class="fas fa-arrow-left"></i> Back to Sign In
            </a>
        </div>

        <div class="form-footer">
            <i class="fas fa-seedling me-1"></i> Agriculture Product Management System
        </div>
    </div>

    <script>
        // Disable button after submit to prevent double-clicks
        document.querySelector('form').addEventListener('submit', function() {
            var btn = document.getElementById('send-btn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Sending...';
        });
    </script>
</body>
</html>
