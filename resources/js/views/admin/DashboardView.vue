<template>
    <AdminView>
        <div
            class="flex flex-col w-full h-full items-center text-white justify-center md:p-6"
        >
            <div
                class="flex flex-col items-center w-full p-5 rounded-md bg-gray-800"
            >
                <h1 class="text-4xl font-bold mb-6 w-full">
                    Number of Visitors
                </h1>
                <div class="chart-container">
                    <Line
                        :key="chartData.labels"
                        :data="chartData"
                        :options="chartOptions"
                    />
                </div>
            </div>
        </div>
    </AdminView>
</template>

<script setup>
import AdminView from "@/views/admin/AdminView.vue";
import { ref, onMounted, watch } from "vue";
import apiClient from "@/services/apiClient";
import { Line } from "vue-chartjs";
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    LineElement,
    PointElement,
    CategoryScale,
    LinearScale,
} from "chart.js";

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    LineElement,
    PointElement,
    CategoryScale,
    LinearScale
);

const visitors = ref([]);

const fetchVisitors = async () => {
    try {
        const response = await apiClient.get("/visitors");
        visitors.value = response.data;
        console.log("Fetched visitors:", visitors.value); // Debugging statement
    } catch (error) {
        console.error("Error fetching visitors:", error);
    }
};

onMounted(() => {
    fetchVisitors();
});

const chartData = ref({
    labels: [],
    datasets: [
        {
            label: "Visitors",
            backgroundColor: "#42A5F5",
            borderColor: "#42A5F5",
            data: [],
            fill: false,
        },
    ],
});

const chartOptions = ref({
    responsive: true,
    maintainAspectRatio: false,
});

watch(visitors, (newVisitors) => {
    const visitorCounts = newVisitors.reduce((acc, visitor) => {
        const date = new Date(visitor.created_at).toLocaleDateString();
        if (!acc[date]) {
            acc[date] = 0;
        }
        acc[date]++;
        return acc;
    }, {});

    const labels = Object.keys(visitorCounts);
    const data = Object.values(visitorCounts);

    console.log("Processed labels:", labels); // Debugging statement
    console.log("Processed data:", data); // Debugging statement

    chartData.value.labels = labels;
    chartData.value.datasets[0].data = data;
});
</script>

<style scoped>
.chart-container {
    width: 100%;
    height: 400px;
}
</style>
