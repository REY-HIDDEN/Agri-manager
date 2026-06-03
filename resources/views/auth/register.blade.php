<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - Agriculture Product Management</title>
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
            padding: 20px;
        }

        .register-card {
            background: #fff;
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 480px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }

        .register-header {
            text-align: center;
            margin-bottom: 28px;
        }

        .register-header .icon {
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

        .register-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 4px;
        }

        .register-header p {
            color: #888;
            font-size: 0.9rem;
            margin: 0;
        }

        .form-floating {
            margin-bottom: 14px;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px 16px 12px 44px;
            border: 2px solid #e8e8e8;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 3px rgba(45, 106, 79, 0.1);
        }

        .form-control.is-invalid {
            border-color: #dc3545;
        }

        .btn-register {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            font-size: 1rem;
            color: #fff;
            width: 100%;
            transition: transform 0.2s, box-shadow 0.2s;
            margin-top: 6px;
        }

        .btn-register:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(45, 106, 79, 0.3);
            color: #fff;
        }

        .btn-register:disabled {
            opacity: 0.7;
            transform: none;
        }

        .form-footer {
            text-align: center;
            margin-top: 20px;
        }

        .form-footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: color 0.3s;
        }

        .form-footer a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .form-footer i {
            color: var(--primary-light);
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

        .input-icon-wrapper .input-icon.textarea-icon {
            top: 18px;
            transform: none;
        }

        .invalid-feedback {
            font-size: 0.78rem;
            margin-top: 2px;
        }

        .password-hint {
            font-size: 0.75rem;
            color: #999;
            margin-top: 2px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--primary-color);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 20px;
            transition: color 0.3s;
        }

        .back-link:hover {
            color: var(--primary-dark);
        }
    </style>
</head>
<body>
    <div class="register-card">
        <a href="{{ route('landing') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Home
        </a>

        <div class="register-header">
            <div class="icon">
                <i class="fas fa-seedling"></i>
            </div>
            <h1>Create Your Account</h1>
            <p>Join AgriManager to manage your farm products</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger alert-custom">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="input-icon-wrapper">
                <i class="fas fa-user input-icon"></i>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                       placeholder="Full Name" value="{{ old('name') }}" required autofocus>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="input-icon-wrapper">
                <i class="fas fa-at input-icon"></i>
                <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                       placeholder="Username" value="{{ old('username') }}" required>
                @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="input-icon-wrapper">
                <i class="fas fa-envelope input-icon"></i>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                       placeholder="Email Address" value="{{ old('email') }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="input-icon-wrapper">
                <i class="fas fa-lock input-icon"></i>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                       placeholder="Password" required minlength="6">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="password-hint"><i class="fas fa-info-circle me-1"></i> Minimum 6 characters</div>
            </div>

            <div class="input-icon-wrapper">
                <i class="fas fa-check-circle input-icon"></i>
                <input type="password" name="password_confirmation" class="form-control"
                       placeholder="Confirm Password" required>
            </div>

            <button type="submit" class="btn-register">
                <i class="fas fa-user-plus me-2"></i> Create Account
            </button>
        </form>

        <div class="form-footer">
            Already have an account?
            <a href="{{ route('login') }}">
                <i class="fas fa-sign-in-alt me-1"></i> Sign In
            </a>
        </div>
    </div>
</body>
</html>
