<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Agriculture Product Management</title>
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

        .login-card {
            background: #fff;
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }

        .login-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .login-header .icon {
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

        .login-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 4px;
        }

        .login-header p {
            color: #888;
            font-size: 0.9rem;
            margin: 0;
        }

        /* Role Toggle */
        .role-toggle {
            display: flex;
            background: #f0f2f5;
            border-radius: 12px;
            padding: 4px;
            margin-bottom: 24px;
            gap: 4px;
        }

        .role-toggle .role-btn {
            flex: 1;
            padding: 10px 16px;
            border: none;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            background: transparent;
            color: #888;
        }

        .role-toggle .role-btn:hover {
            color: #555;
        }

        .role-toggle .role-btn.active {
            background: #fff;
            color: var(--primary-dark);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .role-toggle .role-btn i {
            margin-right: 6px;
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

        .btn-login {
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

        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(45, 106, 79, 0.3);
            color: #fff;
        }

        .btn-login:disabled {
            opacity: 0.7;
            transform: none;
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

        .alert-custom {
            border-radius: 10px;
            border: none;
            font-size: 0.85rem;
            padding: 12px 16px;
        }

        .input-icon-wrapper {
            position: relative;
            transition: opacity 0.3s ease, transform 0.3s ease;
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
            transition: all 0.3s ease;
        }

        .input-icon-wrapper .login-hint {
            display: block;
            font-size: 0.75rem;
            color: #aaa;
            margin-top: 4px;
            margin-left: 4px;
        }

        .field-transition {
            animation: fadeSlideIn 0.3s ease;
        }

        @keyframes fadeSlideIn {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <div class="icon">
                <i class="fas fa-seedling"></i>
            </div>
            <h1>Agriculture Product Management</h1>
            <p>Sign in to your account</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger alert-custom">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Role Toggle -->
            <div class="role-toggle" role="group" aria-label="Login role">
                <button type="button" class="role-btn active" data-role="admin" onclick="setRole('admin')">
                    <i class="fas fa-shield-alt"></i> Admin
                </button>
                <button type="button" class="role-btn" data-role="sales_officer" onclick="setRole('sales_officer')">
                    <i class="fas fa-user-tie"></i> Sales Officer
                </button>
            </div>

            <input type="hidden" name="role" id="role-input" value="admin">

            <!-- Dynamic Login Field -->
            <div class="input-icon-wrapper" id="login-field-wrapper">
                <i class="fas fa-user input-icon" id="login-icon"></i>
                <input type="text" name="login" id="login-input"
                       class="form-control @error('login') is-invalid @enderror"
                       placeholder="Username" value="{{ old('login') }}" required autofocus>
                <span class="login-hint" id="login-hint">Enter your admin username</span>
            </div>

            <div class="input-icon-wrapper">                            <i class="fas fa-lock input-icon"></i>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Password" required>
                        </div>

                        <div class="text-end mb-3">
                            <a href="{{ route('password.request') }}" style="color: var(--primary-color); font-size: 0.82rem; font-weight: 500; text-decoration: none; transition: color 0.2s;">
                                <i class="fas fa-question-circle me-1"></i> Forgot Password?
                            </a>
                        </div>

                        <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt me-2"></i> Sign In
            </button>
        </form>

        <div class="mt-4 pt-3 border-top text-center">
            <span style="color: #888; font-size: 0.85rem;">Don't have an account?</span>
            <a href="{{ route('register') }}" style="color: var(--primary-color); font-weight: 600; font-size: 0.85rem; text-decoration: none; margin-left: 4px;">
                Create Account <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="form-footer">
            <i class="fas fa-seedling me-1"></i> Agriculture Product Management System
        </div>
    </div>

    <script>
        function setRole(role) {
            // Update hidden input
            document.getElementById('role-input').value = role;

            // Update toggle buttons
            document.querySelectorAll('.role-btn').forEach(function(btn) {
                btn.classList.toggle('active', btn.dataset.role === role);
            });

            // Get field elements
            var loginInput = document.getElementById('login-input');
            var loginIcon = document.getElementById('login-icon');
            var loginHint = document.getElementById('login-hint');
            var wrapper = document.getElementById('login-field-wrapper');

            // Animate transition
            wrapper.style.opacity = '0.5';
            wrapper.style.transform = 'translateY(-4px)';

            setTimeout(function() {
                if (role === 'admin') {
                    loginInput.placeholder = 'Username';
                    loginInput.type = 'text';
                    loginIcon.className = 'fas fa-user input-icon';
                    loginHint.textContent = 'Enter your admin username';
                    loginInput.autocomplete = 'username';
                } else {
                    loginInput.placeholder = 'Email address';
                    loginInput.type = 'email';
                    loginIcon.className = 'fas fa-envelope input-icon';
                    loginHint.textContent = 'Enter your registered email address';
                    loginInput.autocomplete = 'email';
                }

                wrapper.style.opacity = '1';
                wrapper.style.transform = 'translateY(0)';
            }, 150);

            // Focus the input
            setTimeout(function() {
                loginInput.focus();
            }, 200);
        }

        // Preserve selected role on page reload (if validation failed)
        document.addEventListener('DOMContentLoaded', function() {
            var oldRole = '{{ old('role') }}';
            if (oldRole && oldRole !== 'admin') {
                setRole(oldRole);
            }
        });
    </script>
</body>
</html>
