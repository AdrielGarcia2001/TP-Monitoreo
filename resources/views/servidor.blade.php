<!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Monitor del servidor</title>
        @vite('resources/js/app.js')
        <style>
            * {
                box-sizing: border-box;
                font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            }

            body {
                background-color: #f4f6f9;
                margin: 0;
                padding: 0;
                color: #2c3e50;
            }

            /* Barra de navegación superior */
            .navbar {
                background: #ffffff;
                border-bottom: 1px solid #e1e8ed;
                padding: 14px 40px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            }

            .navbar-brand {
                font-weight: 700;
                font-size: 18px;
                color: #1e293b;
                text-decoration: none;
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .nav-links {
                display: flex;
                gap: 12px;
                align-items: center;
            }

            .nav-link {
                text-decoration: none;
                color: #64748b;
                font-weight: 600;
                padding: 8px 16px;
                border-radius: 6px;
                font-size: 14px;
                transition: all 0.2s;
            }

            .nav-link:hover {
                background: #f1f5f9;
                color: #0f172a;
            }

            .nav-link.active {
                background: #e2e8f0;
                color: #0f172a;
            }

            .nav-link-login {
                background: #0f172a;
                color: #ffffff !important;
            }

            .nav-link-login:hover {
                background: #1e293b !important;
            }

            /* Contenido Principal */
            .container {
                max-width: 1100px;
                margin: 30px auto;
                padding: 0 20px;
            }

            .page-header {
                display: flex;
                justify-content: space-between;
                align-items: flex-end;
                margin-bottom: 24px;
                border-bottom: 1px solid #e2e8f0;
                padding-bottom: 16px;
            }

            .page-title {
                font-size: 22px;
                font-weight: 700;
                margin: 0;
                color: #0f172a;
            }

            .timestamp {
                font-size: 13px;
                color: #64748b;
                font-weight: 500;
            }

            .timestamp code {
                background: #e2e8f0;
                padding: 3px 6px;
                border-radius: 4px;
                color: #0f172a;
                font-family: ui-monospace, monospace;
            }

            .status {
                display: inline-block;
                padding: 4px 10px;
                border-radius: 9999px;
                font-weight: 700;
            }

            .status.on {
                color: #15803d;
                background: #dcfce7;
            }

            .status.off {
                color: #b91c1c;
                background: #fee2e2;
            }

            /* Grilla de Tarjetas */
            .grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
                gap: 20px;
            }

            .card {
                background: #ffffff;
                border-radius: 12px;
                padding: 20px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.04);
                border: 1px solid #e2e8f0;
                position: relative;
                overflow: hidden;
            }

            .card::before {
                content: "";
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 4px;
                background-color: #0284c7;
            }

            .card.hardware::before {
                background-color: #7c3aed;
            }

            .card.warning::before {
                background-color: #f59e0b;
            }

            .label-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 10px;
            }

            .label {
                font-size: 12px;
                font-weight: 700;
                text-transform: uppercase;
                color: #64748b;
                letter-spacing: 0.5px;
            }

            .value {
                font-size: 26px;
                font-weight: 800;
                color: #0f172a;
                line-height: 1.2;
                word-break: break-word;
            }

            .card-footer {
                font-size: 12px;
                color: #94a3b8;
                margin-top: 8px;
            }

            .actions {
                display: flex;
                flex-wrap: wrap;
                gap: 12px;
                margin: 24px 0;
            }

            .demo-button {
                padding: 10px 16px;
                border: 0;
                border-radius: 8px;
                color: #fff;
                background: #1d5fa8;
                font: inherit;
                font-weight: 650;
                cursor: pointer;
            }

            .demo-button:hover {
                background: #12305a;
            }

            .demo-button:disabled {
                cursor: wait;
                opacity: .7;
            }

            .activity-card {
                margin-top: 24px;
                padding: 20px;
                border: 1px solid #e2e8f0;
                border-radius: 12px;
                background: #fff;
            }

            #actividad {
                min-height: 24px;
                margin-bottom: 0;
            }

            #demo-status {
                color: #475569;
            }

            @media (max-width: 760px) {
                .navbar {
                    flex-wrap: wrap;
                    gap: 12px;
                    padding: 14px 20px;
                }

                .nav-links {
                    flex-wrap: wrap;
                }

                .page-header {
                    align-items: flex-start;
                    flex-direction: column;
                    gap: 12px;
                }
            }
        </style>
    </head>

    <body>

        <!-- Barra Superior -->
        <nav class="navbar">
            <a href="#" class="navbar-brand">🛡️ Sistema de Gestión Operativa</a>
            <div class="nav-links">
                <a href="/servidor" class="nav-link active">🖥️ Monitor del Servidor</a>
                <a href="/graficos" class="nav-link">📈 Gráficos en tiempo real</a>
                <a href="/security-dashboard" class="nav-link">🔒 Monitoreo de Seguridad</a>
                <a href="/evidencias/" class="nav-link">📸 Evidencias</a>
                <a href="/security-login" class="nav-link nav-link-login">🔑 Iniciar Sesión</a>
            </div>
        </nav>

        <div class="container">
            <!-- Encabezado -->
            <div class="page-header">
                <div>
                    <h1 class="page-title">Monitor del servidor</h1>
                    <p><a href="/graficos">Ver gráficos en tiempo real →</a></p>
                </div>
                <div class="timestamp">
                    WebSocket: <span id="estado" class="status off">Conectando...</span>
                    <br>
                    ⏱️ Última lectura: <code id="at">{{ $data['at'] }}</code>
                </div>
            </div>

            <!-- Métrica de Servidor -->
            <div class="grid">
                <div class="card">
                    <div class="label-header">
                        <span class="label">Nombre del equipo</span>
                        <span>💻</span>
                    </div>
                    <div class="value">{{ $data['hostname'] }}</div>
                    <div class="card-footer">Host local</div>
                </div>

                <div class="card">
                    <div class="label-header">
                        <span class="label">Sistema operativo</span>
                        <span>🪟</span>
                    </div>
                    <div class="value" style="font-size: 20px;">{{ $data['os'] }}</div>
                    <div class="card-footer">Plataforma del sistema</div>
                </div>

                <div class="card">
                    <div class="label-header">
                        <span class="label">Versión de PHP</span>
                        <span>🐘</span>
                    </div>
                    <div class="value">{{ $data['php'] }}</div>
                    <div class="card-footer">Entorno de ejecución</div>
                </div>

                <div class="card hardware">
                    <div class="label-header">
                        <span class="label">Disco usado (%)</span>
                        <span>💾</span>
                    </div>
                    <div class="value" id="disk">{{ $data['disk']['used_pct'] }}</div>
                    <div class="card-footer">Almacenamiento principal</div>
                </div>

                <div class="card hardware">
                    <div class="label-header">
                        <span class="label">Memoria usada (%)</span>
                        <span>🧠</span>
                    </div>
                    <div class="value" id="memory">{{ $data['memory']['used_pct'] ?? 'No disponible' }}</div>
                    <div class="card-footer">Uso de RAM</div>
                </div>

                <div class="card hardware">
                    <div class="label-header">
                        <span class="label">Uso de CPU (%)</span>
                        <span>⚡</span>
                    </div>
                    <div class="value" id="cpu">{{ $data['cpu_pct'] ?? 'No disponible' }}</div>
                    <div class="card-footer">Carga de procesamiento</div>
                </div>

                <div class="card warning">
                    <div class="label-header">
                        <span class="label">Temperatura CPU (°C)</span>
                        <span>🌡️</span>
                    </div>
                    <div class="value" id="temp" style="font-size: 20px; color: #d97706;">{{ $data['cpu_temp_c'] ?? 'No disponible' }}</div>
                    <div class="card-footer">Sensor térmico</div>
                </div>

                <div class="card">
                    <div class="label-header">
                        <span class="label">Encendido hace (segundos)</span>
                        <span>⏱️</span>
                    </div>
                    <div class="value" id="uptime" style="font-size: 22px;">{{ $data['uptime_s'] ?? 'No disponible' }}</div>
                    <div class="card-footer">Segundos en línea</div>
                </div>
            </div>

            @if (config('app.demo_apps'))
                <div class="actions">
                    <button class="demo-button" data-app="calculadora" type="button">Abrir calculadora</button>
                    <button class="demo-button" data-app="bloc" type="button">Abrir Bloc de notas</button>
                    <button class="demo-button" data-app="paint" type="button">Abrir Paint</button>
                </div>
                <p id="demo-status" role="status" aria-live="polite"></p>
            @endif

            <section class="activity-card">
                <h2>Actividad en vivo</h2>
                <ul id="actividad"></ul>
            </section>
        </div>
    </body>

    </html>