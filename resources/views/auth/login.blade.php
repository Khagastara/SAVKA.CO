<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - UMKM Manager</title>
    <style>
        :root {
            --peach: #FFCDB2;
            --mocha-cream: #E8CEBF;
            --sage-green: #B7C4A4;
            --warm-white: #F8F4F0;
            --charcoal-gray: #3A3A3A;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, var(--warm-white) 0%, var(--mocha-cream) 100%);
            color: var(--charcoal-gray);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: var(--warm-white);
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 1000px;
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            animation: fadeIn 0.5s ease-out;
        }

        .login-left {
            background: linear-gradient(135deg, var(--peach), var(--sage-green));
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
        }

        .brand h1 {
            font-size: 2.5rem;
            color: var(--charcoal-gray);
        }

        .brand p {
            color: var(--charcoal-gray);
            opacity: 0.8;
        }

        .login-right {
            padding: 60px 50px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: var(--charcoal-gray);
        }

        .form-group input {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid var(--mocha-cream);
            border-radius: 12px;
            background: var(--warm-white);
        }

        .btn-login {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, var(--sage-green), var(--peach));
            border: none;
            border-radius: 12px;
            font-weight: 600;
            color: var(--charcoal-gray);
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(183, 196, 164, 0.4);
        }

        .error-message {
            background: #ffebee;
            color: #c62828;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.9rem;
            border-left: 4px solid #c62828;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-left">
            <div class="brand">
                <h1>SAVKA.CO Management</h1>
                <p>Kelola bisnis Anda dengan efisien. Sistem untuk Owner, Produksi, dan Distribusi.</p>
            </div>
        </div>

        <div class="login-right">
            <h2>Login</h2>

            @if(session('error'))
                <div class="error-message">{{ session('error') }}</div>
            @endif

            <form action="{{ route('login') }}" method="POST" autocomplete="off">
                @csrf
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" placeholder="Masukkan username" required>
                </div>

                <div class="form-group" autocomplete="off">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Masukkan password" required>
                </div>

                <button type="submit" class="btn-login">Masuk</button>
            </form>
        </div>
    </div>
</body>
</html>
