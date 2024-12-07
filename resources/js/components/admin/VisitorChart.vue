<template>
    <div class="chart-container">
        <BarChart :chart-data="chartData" />
    </div>
</template>

<script setup>
import { ref, watch, defineComponent } from "vue";
import { Bar } from "vue-chartjs";
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
} from "chart.js";

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale
);

const props = defineProps({
    visitors: {
        type: Array,
        required: true,
    },
});

const chartData = ref({
    labels: [],
    datasets: [
        {
            label: "Visitors",
            backgroundColor: "#42A5F5",
            data: [],
        },
    ],
});

watch(props.visitors, (newVisitors) => {
    const labels = newVisitors.map((visitor) =>
        new Date(visitor.created_at).toLocaleDateString()
    );
    const data = newVisitors.map((visitor) => 1); // Each entry represents one visitor

    chartData.value.labels = labels;
    chartData.value.datasets[0].data = data;
});

const BarChart = defineComponent({
    extends: Bar,
    props: ["chartData"],
    mounted() {
        this.renderChart(this.chartData, {
            responsive: true,
            maintainAspectRatio: false,
        });
    },
});
</script>

<style scoped>
.chart-container {
    width: 100%;
    height: 400px;
}
</style>
