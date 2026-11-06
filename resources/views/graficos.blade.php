<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gráficos en tiempo real</title>
    @vite('resources/js/app.js')
    <style>
        * {
            box-sizing: border-box;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        body {
            margin: 0;
            color: #2c3e50;
            background: #f4f6f9;
        }

        .navbar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 14px 40px;
            border-bottom: 1px solid #e1e8ed;
            background: #fff;
        }

        .navbar a {
            color: #1e293b;
            font-weight: 650;
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
        }

        main {
            width: min(1100px, calc(100% - 32px));
            margin: 30px auto;
        }

        .page-header {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 24px;
        }

        .page-header h1 { margin: 0 0 8px; color: #0f172a; }
        .page-header p { margin: 0; color: #64748b; }

        .status {
            padding: 4px 10px;
            border-radius: 9999px;
            font-weight: 700;
        }

        .status.on { color: #15803d; background: #dcfce7; }
        .status.off { color: #b91c1c; background: #fee2e2; }

        .chart-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(min(100%, 320px), 1fr));
            gap: 20px;
        }

        .chart-card {
            min-width: 0;
            padding: 20px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, .04);
        }

        .chart-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 12px;
        }

        .chart-header h2 { margin: 0; font-size: 1rem; }
        .chart-value { color: #0f172a; font-size: 1.4rem; font-weight: 800; }
        canvas { max-height: 240px; }

        @media (max-width: 700px) {
            .navbar { padding: 14px 20px; }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="/servidor">🛡️ Sistema de Gestión Operativa</a>
        <div class="nav-links">
            <a href="/servidor">🖥️ Monitor del Servidor</a>
            <a href="/security-dashboard">🔒 Monitoreo de Seguridad</a>
            <a href="/evidencias/">📸 Evidencias</a>
        </div>
    </nav>

    <main>
        <header class="page-header">
            <div>
                <h1>Gráficos en tiempo real</h1>
                <p><a href="/servidor">← Volver al monitor</a> · Última lectura: <span id="at">Esperando datos...</span></p>
            </div>
            <div>WebSocket: <span id="estado" class="status off">Conectando...</span></div>
        </header>

        <section class="chart-grid" aria-label="Métricas del servidor en vivo">
            <x-grafico-metrica titulo="Uso de CPU (%)" campo="cpu" color="#1d5fa8" />
            <x-grafico-metrica titulo="Memoria usada (%)" campo="memory" color="#0f766e" />
            <x-grafico-metrica titulo="Disco usado (%)" campo="disk" color="#c2410c" tipo="medidor" />
        </section>
    </main>
</body>
</html>
