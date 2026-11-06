<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Seguridad</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: system-ui, -apple-system, sans-serif;
        }

        body {
            background-color: #f4f6f9;
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            color: #2c3e50;
        }

        .navbar {
            background: #ffffff;
            border-bottom: 1px solid #e1e8ed;
            padding: 14px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 18px;
            color: #1e293b;
            text-decoration: none;
        }

        .nav-link {
            text-decoration: none;
            color: #64748b;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
        }

        .login-container {
            max-width: 400px;
            margin: 80px auto;
            width: 100%;
            padding: 0 20px;
        }

        .card {
            background: #ffffff;
            border-radius: 12px;
            padding: 30px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .card-title {
            font-size: 20px;
            font-weight: 700;
            margin-top: 0;
            margin-bottom: 20px;
            text-align: center;
            color: #0f172a;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 6px;
            color: #475569;
        }

        .form-input {
            width: 100%;
            padding: 10px 14px;
            border-radius: 6px;
            border: 1px solid #cbd5e1;
            font-size: 14px;
        }

        .form-input:focus {
            outline: none;
            border-color: #2563eb;
        }

        .btn-submit {
            width: 100%;
            background: #0f172a;
            color: #ffffff;
            padding: 12px;
            border: none;
            border-radius: 6px;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
        }

        .btn-submit:hover {
            background: #1e293b;
        }

        .alert {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            padding: 10px;
            border-radius: 6px;
            font-size: 13px;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>

    <nav class="navbar">
        <a href="#" class="navbar-brand">🛡️ Sistema de Gestión Operativa</a>
        <a href="/servidor" class="nav-link">🖥️ Monitor del Servidor</a>
    </nav>

    <div class="login-container">
        <div class="card">
            <h1 class="card-title">Acceso de Seguridad</h1>

            @if(session('error'))
            <div class="alert">{{ session('error') }}</div>
            @endif

            <form action="/security-login" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Identificador (Usuario / Email)</label>
                    <input type="text" name="identifier" class="form-input" required placeholder="Ej: admin">
                </div>

                <div class="form-group">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-input" required placeholder="••••••••">
                </div>

                <button type="submit" class="btn-submit">Ingresar</button>
            </form>
        </div>
    </div>

</body>

</html>