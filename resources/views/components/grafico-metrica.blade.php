@props([
    'titulo',
    'campo',
    'color' => '#1d5fa8',
    'tipo' => 'linea',
])

<article class="chart-card">
    <div class="chart-header">
        <h2>{{ $titulo }}</h2>
        <span class="chart-value" id="valor-{{ $campo }}">--</span>
    </div>
    <canvas
        data-grafico="{{ $campo }}"
        data-tipo="{{ $tipo }}"
        data-color="{{ $color }}"
        role="img"
        aria-label="{{ $titulo }}"
    ></canvas>
</article>
