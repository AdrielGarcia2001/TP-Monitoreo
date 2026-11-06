# 🚀 TP Monitor de Servidor con Laravel

> **Desarrollador:** Adriel  
> **Entorno de Pruebas:** Windows 10/11 | PHP 8.4 | Laravel 12/13  

---

## 📋 Tabla de Contenidos
1. [Descripción del Proyecto](#-descripción-del-proyecto)
2. [Módulo 1: Monitor de Servidor (REST & Polling)](#-módulo-1-monitor-de-servidor-rest--polling)
3. [Módulo 2: Tiempo Real con WebSockets (Reverb)](#-módulo-2-tiempo-real-con-websockets-reverb)
4. [Módulo 3: Seguridad y Auditoría de Accesos](#-módulo-3-seguridad-y-auditoría-de-accesos)
5. [Cuestionario Técnico y Decisiones de Arquitectura](#-cuestionario-técnico-y-decisiones-de-arquitectura)

---

## 🛠️ Descripción del Proyecto
Aplicación desarrollada en **Laravel** orientada a la supervisión de infraestructura de sistemas operativos Windows mediante la integración nativa de comandos **PowerShell/WMI** encapsulados en servicios desacoplados (`ServerMetrics`). El sistema expone las métricas recolectadas a través de tres canales unificados: vistas web interactivas Blade, una API REST JSON y canales de difusión en tiempo real impulsados por WebSockets.

---

## 📊 Módulo 1: Monitor de Servidor (REST & Polling)

El núcleo del sistema recolecta en tiempo de ejecución las variables críticas del host local:
* **Hardware & OS:** Nombre del equipo (`hostname`), versión de plataforma (`php_uname`) y versión de motor PHP.
* **Almacenamiento y Memoria:** Capacidad total, espacio libre y porcentajes de uso en disco y memoria RAM.
* **Rendimiento:** Porcentaje de carga de CPU y tiempo activo del sistema (*uptime*).

### Endpoints Principales
* `GET /servidor` — Interfaz visual estructurada en tarjetas adaptativas.
* `GET /api/metrics` — Endpoint JSON protegido opcionalmente por control de tráfico (`throttle:60,1`).

---

## ⚡ Módulo 2: Tiempo Real con WebSockets (Reverb)

Migración del modelo clásico de sondeo (*polling*) hacia una arquitectura basada en eventos reactivos mediante **Laravel Reverb** y **Laravel Echo**.

### Guía rápida de ejecución de procesos simultáneos
Para levantar el entorno completo en desarrollo local se requieren cuatro terminales independientes:

| Terminal | Proceso / Comando | Descripción operativa |
| :--- | :--- | :--- |
| **1** | `php artisan reverb:start --debug` | Servidor WebSocket oficial en el puerto 8080 |
| **2** | `php artisan schedule:work` | Scheduler encargado de disparar `metrics:broadcast` cada 5s |
| **3** | `npm run dev` | Compilador de assets y enlaces de integración con Vite |
| **4** | `php artisan serve` | Servidor HTTP principal de la aplicación Laravel |

* **Visualización de Gráficos:** Accediendo a `/graficos` se despliegan componentes dinámicos basados en **Chart.js** que registran una ventana deslizante de las últimas 30 mediciones de CPU, Memoria y Disco.

---

## 🔒 Módulo 3: Seguridad y Auditoría de Accesos

Módulo encargado de registrar, auditar y bloquear patrones de autenticación anómalos o maliciosos en la plataforma.

### Matriz de Validación de Pruebas de Seguridad
1. **Login Válido:** Registro exitoso (`success`) en la tabla `login_attempts` y redirección limpia al dashboard.
2. **Credenciales Erróneas:** Conteo incremental de fallos registrado bajo estado `failed`.
3. **Umbral de Alerta (3 fallos / 5 min):** Activación automática del indicador visual de advertencia (*badge*) en el panel de auditoría.
4. **Rate Limiting (5 fallos / 5 min):** Bloqueo temporal preventivo por 10 minutos a nivel de caché de la aplicación.
5. **Acceso Restringido:** Intercepción de peticiones en estado de bloqueo con respuesta estándar `HTTP 429 Too Many Requests`.

---

## 🧠 Cuestionario Técnico y Decisiones de Arquitectura

### 1. ¿Por qué la temperatura suele aparecer como "No disponible" en Windows?
Requiere privilegios elevados de Administrador para consultar los registros WMI de la clase `MSAcpi_ThermalZoneTemperature`. Si el pool de ejecución opera con permisos estándar o el hardware del equipo no expone dichos sensores térmicos ACPI, el sistema intercepta la excepción de forma segura retornando `null`.

### 2. ¿Por qué el script de PowerShell nunca debe incluir datos que vengan del usuario?
Concatenar entradas directas del usuario (parámetros HTTP o formularios) dentro de ejecuciones de nivel de sistema mediante `shell_exec()` expone a la aplicación a vulnerabilidades críticas de **Inyección de Comandos (RCE)**. Por esta razón, el sistema implementa comandos estáticos y listas blancas estrictas.

### 3. Qué riesgos implica exponer `/api/metrics` sin autenticación?
* **Information Disclosure:** Revela detalles internos de la arquitectura del servidor (versiones, rutas y capacidades de hardware).
* **Denegación de Servicio (DoS):** Cada petición HTTP obliga a instanciar un proceso hijo de PowerShell en segundo plano, saturando los recursos del sistema ante peticiones masivas.

### 4. Impacto del Polling vs. WebSockets en concurrencia
* **Polling (TP1):** 50 usuarios consultando cada 5 segundos generan un tráfico constante de 600 solicitudes HTTP por minuto, multiplicando la ejecución de procesos del sistema operativo.
* **WebSockets (TP2):** El servidor ejecuta una única lectura centralizada cada 5 segundos y distribuye eficientemente el evento unificado a todos los clientes mediante una conexión persistente abierta.

### 5. ¿Por qué se utiliza una lista blanca en el control de ejecución de aplicaciones (`DemoController`)?
Porque el cliente nunca envía comandos ejecutables; solo transmite un identificador seguro (clave) que el controlador traduce mediante una constante interna (`APPS`). Cualquier llave ajena a la lista genera un error estricto `404`, blindando al servidor frente a ejecuciones arbitrarias.

---
*Documentación técnica para la asignatura Programación IV - UTN FRRe.*
