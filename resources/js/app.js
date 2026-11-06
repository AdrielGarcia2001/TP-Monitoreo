import './echo';
import Chart from 'chart.js/auto';

const metricsChannel = window.Echo.channel('server.metrics');
const formatValue = (value) => value ?? 'No disponible';
const updateText = (id, value) => {
    const element = document.getElementById(id);

    if (element) {
        element.textContent = formatValue(value);
    }
};

const connection = window.Echo.connector.pusher.connection;
const connectionStatus = document.getElementById('estado');

if (connectionStatus) {
    const updateConnectionStatus = (state) => {
        const connected = state === 'connected';

        connectionStatus.textContent = connected ? 'Conectado' : 'Sin conexión';
        connectionStatus.className = connected ? 'status on' : 'status off';
    };

    updateConnectionStatus(connection.state);
    connection.bind('state_change', ({ current }) => updateConnectionStatus(current));
}

metricsChannel.listen('MetricsUpdated', ({ data }) => {
    updateText('at', data.at);
    updateText('disk', data.disk?.used_pct);
    updateText('memory', data.memory?.used_pct);
    updateText('cpu', data.cpu_pct);
    updateText('temp', data.cpu_temp_c);
    updateText('uptime', data.uptime_s);
});

const activity = document.getElementById('actividad');

if (activity) {
    window.Echo.channel('server.demo').listen('AppOpened', ({ app, ip, at }) => {
        const item = document.createElement('li');
        item.textContent = `${at} · ${ip} abrió ${app}`;
        activity.prepend(item);
    });
}

const demoStatus = document.getElementById('demo-status');

if (demoStatus) {
    document.querySelectorAll('[data-app]').forEach((button) => {
        button.addEventListener('click', async () => {
            button.disabled = true;
            demoStatus.textContent = 'Solicitando apertura...';

            try {
                const response = await fetch(`/demo/abrir/${button.dataset.app}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        Accept: 'application/json',
                    },
                });

                if (!response.ok) {
                    const message = response.status === 429
                        ? 'Límite alcanzado. Espera un minuto antes de volver a probar.'
                        : response.status === 403
                            ? 'La demo está desactivada o no está disponible en este sistema.'
                            : `No se pudo abrir el programa (HTTP ${response.status}).`;

                    throw new Error(message);
                }

                demoStatus.textContent = 'Solicitud enviada. La actividad aparecerá en las sesiones conectadas.';
            } catch (error) {
                demoStatus.textContent = error.message;
                console.error('Error al abrir el programa de demostración:', error);
            } finally {
                button.disabled = false;
            }
        });
    });
}

const charts = {};
const maxPoints = 30;

document.querySelectorAll('canvas[data-grafico]').forEach((canvas) => {
    const { grafico: field, tipo, color } = canvas.dataset;

    charts[field] = tipo === 'medidor'
        ? new Chart(canvas, {
            type: 'doughnut',
            data: {
                labels: ['Usado', 'Libre'],
                datasets: [{
                    data: [0, 100],
                    backgroundColor: [color, '#e5e7eb'],
                    borderWidth: 0,
                }],
            },
            options: {
                aspectRatio: 2,
                rotation: -90,
                circumference: 180,
                cutout: '70%',
                plugins: { legend: { display: false } },
            },
        })
        : new Chart(canvas, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    data: [],
                    borderColor: color,
                    backgroundColor: `${color}22`,
                    fill: true,
                    tension: 0.3,
                    pointRadius: 0,
                }],
            },
            options: {
                aspectRatio: 2,
                animation: false,
                scales: {
                    x: { display: false },
                    y: { min: 0, max: 100 },
                },
                plugins: { legend: { display: false } },
            },
        });
});

function addReading(field, time, value) {
    updateText(`valor-${field}`, value);

    const chart = charts[field];

    if (!chart || value == null) {
        return;
    }

    if (chart.config.type === 'doughnut') {
        chart.data.datasets[0].data = [value, 100 - value];
    } else {
        chart.data.labels.push(time);
        chart.data.datasets[0].data.push(value);

        if (chart.data.labels.length > maxPoints) {
            chart.data.labels.shift();
            chart.data.datasets[0].data.shift();
        }
    }

    chart.update();
}

if (Object.keys(charts).length > 0) {
    metricsChannel.listen('MetricsUpdated', ({ data }) => {
        const time = new Date(data.at).toLocaleTimeString();

        updateText('at', time);
        addReading('cpu', time, data.cpu_pct);
        addReading('memory', time, data.memory?.used_pct);
        addReading('disk', time, data.disk?.used_pct);
    });
}

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import './echo';
