<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Monitoreo de Seguridad</title>
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
            align-items: center;
            margin-bottom: 24px;
        }

        .page-title {
            font-size: 22px;
            font-weight: 700;
            margin: 0;
            color: #0f172a;
        }

        /* Tarjetas de Indicadores */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.04);
            border: 1px solid #e2e8f0;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
        }

        .stat-card.success::before {
            background-color: #10b981;
        }

        .stat-card.failed::before {
            background-color: #f59e0b;
        }

        .stat-card.blocked::before {
            background-color: #ef4444;
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .stat-label {
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            color: #64748b;
            letter-spacing: 0.5px;
        }

        .stat-value {
            font-size: 36px;
            font-weight: 800;
            line-height: 1;
        }

        .stat-card.success .stat-value {
            color: #059669;
        }

        .stat-card.failed .stat-value {
            color: #d97706;
        }

        .stat-card.blocked .stat-value {
            color: #dc2626;
        }

        .stat-footer {
            font-size: 12px;
            color: #94a3b8;
            margin-top: 8px;
        }

        /* Tabla de IPs */
        .table-card {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.04);
            overflow: hidden;
        }

        .table-header {
            padding: 18px 24px;
            border-bottom: 1px solid #e2e8f0;
            background: #ffffff;
        }

        .table-title {
            font-size: 16px;
            font-weight: 700;
            margin: 0;
            color: #1e293b;
        }

        .table-subtitle {
            font-size: 13px;
            color: #64748b;
            margin-top: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th {
            background: #f8fafc;
            padding: 14px 24px;
            font-size: 12px;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #e2e8f0;
        }

        td {
            padding: 16px 24px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .ip-code {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            background: #f1f5f9;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 13px;
            color: #0f172a;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 700;
        }

        .badge-danger {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .empty-state {
            text-align: center;
            padding: 30px;
            color: #94a3b8;
        }
    </style>
</head>

<body>

    <!-- Barra Superior -->
    <nav class="navbar">
        <a href="#" class="navbar-brand">🛡️ Sistema de Gestión Operativa</a>
        <div class="nav-links">
            <a href="/servidor" class="nav-link">🖥️ Monitor del Servidor</a>
            <a href="/security-dashboard" class="nav-link active">🔒 Monitoreo de Seguridad</a>
            <a href="/evidencias/" class="nav-link">📸 Evidencias</a>
            <a href="/security-login" class="nav-link nav-link-login">🔑 Iniciar Sesión</a>
        </div>
    </nav>

    <div class="container">
        <!-- Encabezado de la Página -->
        <div class="page-header">
            <div>
                <h1 class="page-title">Panel de Seguridad y Auditoría</h1>
            </div>
        </div>

        <!-- Indicadores -->
        <div class="cards-grid">
            <div class="stat-card success">
                <div class="stat-header">
                    <span class="stat-label">Intento Exitoso</span>
                    <span>✅</span>
                </div>
                <div class="stat-value">{{ $stats['success'] ?? 0 }}</div>
                <div class="stat-footer">Últimas 24 horas</div>
            </div>

            <div class="stat-card failed">
                <div class="stat-header">
                    <span class="stat-label">Intentos Fallidos</span>
                    <span>⚠️</span>
                </div>
                <div class="stat-value">{{ $stats['failed'] ?? 0 }}</div>
                <div class="stat-footer">Últimas 24 horas</div>
            </div>

            <div class="stat-card blocked">
                <div class="stat-header">
                    <span class="stat-label">Accesos Bloqueados</span>
                    <span>🚫</span>
                </div>
                <div class="stat-value">{{ $stats['blocked'] ?? 0 }}</div>
                <div class="stat-footer">Últimas 24 horas</div>
            </div>
        </div>

        <!-- Tabla de IPs Sospechosas -->
        <div class="table-card">
            <div class="table-header">
                <h2 class="table-title">Detección de IPs Sospechosas</h2>
                <div class="table-subtitle">Direcciones IP con 3 o más intentos fallidos en los últimos 5 minutos</div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Dirección IP</th>
                        <th>Total Fallos Registrados</th>
                        <th>Estado de Seguridad</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suspiciousIps as $item)
                    <tr>
                        <td><span class="ip-code">{{ $item->ip_address }}</span></td>
                        <td><strong>{{ $item->total_fallos }}</strong> intentos</td>
                        <td>
                            <span class="badge badge-danger">
                                🚨 ALERTA SOSPECHOSA
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="empty-state">No se detectaron actividades sospechosas en los últimos 5 minutos.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>