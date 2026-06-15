<script setup>
import { computed } from 'vue';
import Chart from 'primevue/chart';

/**
 * Thin wrapper around PrimeVue's <Chart>. The backend (StatsService) emits
 * Chart.js-ready configs ({ type, title, labels, datasets, format }), so this
 * component stays dumb: it just maps that payload into Chart's data/options.
 */
const props = defineProps({
    config: { type: Object, required: true },
});

const isCurrency = computed(() => props.config.format === 'currency');
const isCircular = computed(() => ['doughnut', 'pie'].includes(props.config.type));

const fmtCurrency = (v) =>
    new Intl.NumberFormat(undefined, { style: 'currency', currency: 'USD', maximumFractionDigits: 0 }).format(v ?? 0);

const data = computed(() => ({
    labels: props.config.labels ?? [],
    datasets: props.config.datasets ?? [],
}));

const options = computed(() => {
    const base = {
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: isCircular.value,
                position: 'bottom',
                labels: { usePointStyle: true, boxWidth: 8 },
            },
            tooltip: {
                callbacks: isCurrency.value
                    ? { label: (ctx) => `${ctx.dataset.label}: ${fmtCurrency(ctx.parsed.y ?? ctx.parsed)}` }
                    : {},
            },
        },
    };

    if (isCircular.value) {
        return { ...base, cutout: '62%' };
    }

    return {
        ...base,
        scales: {
            x: { grid: { display: false }, ticks: { maxRotation: 0, autoSkip: true, maxTicksLimit: 8 } },
            y: {
                beginAtZero: true,
                ticks: isCurrency.value ? { callback: (v) => fmtCurrency(v) } : { precision: 0 },
            },
        },
    };
});
</script>

<template>
    <div class="surface-card surface-card-pad stat-chart">
        <h3 class="card-title">{{ config.title }}</h3>
        <div class="stat-chart-canvas">
            <Chart :type="config.type" :data="data" :options="options" />
        </div>
    </div>
</template>

<style scoped>
.stat-chart-canvas {
    position: relative;
    height: 260px;
}
</style>
