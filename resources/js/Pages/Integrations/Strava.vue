<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { defineProps, ref, onMounted, onUnmounted } from 'vue';
import axios from 'axios';
import Chart from 'primevue/chart';
import 'leaflet/dist/leaflet.css';
import L from 'leaflet';
import polyline from 'polyline-encoded';

const props = defineProps<{
    stravaConnected: boolean;
}>();

const form = useForm({});
const syncing = ref(false);
const syncStats = ref(null);
const syncError = ref(null);

// Chart data
const chartData = ref(null);
const loadingCharts = ref(false);

// Map data
const activities = ref([]);
const selectedActivity = ref(null);
const activityMap = ref(null);
const heatmapData = ref(null);
const heatmapMap = ref(null);

const connectStrava = () => {
    window.location.href = route('integrations.strava.connect');
};

const disconnectStrava = () => {
    form.post(route('integrations.strava.disconnect'));
};

const syncStravaData = async () => {
    syncing.value = true;
    syncError.value = null;
    syncStats.value = null;
    try {
        const response = await axios.post(route('integrations.strava.sync'));
        syncStats.value = response.data.stats;
        // Reload charts after sync
        await fetchChartData();
        await fetchActivitiesWithRoutes();
    } catch (error: any) {
        syncError.value = error.response?.data?.message || 'Failed to sync Strava data.';
        console.error('Error syncing Strava data:', error);
    } finally {
        syncing.value = false;
    }
};

const fetchChartData = async () => {
    loadingCharts.value = true;
    try {
        const response = await axios.get(route('integrations.strava.fetch-chart-data'));
        const data = response.data;

        chartData.value = {
            frequency: {
                labels: data.frequency.labels,
                datasets: [{
                    label: 'Activities per Month',
                    backgroundColor: '#FF6B35',
                    data: data.frequency.data
                }]
            },
            distance: {
                labels: data.distance.labels,
                datasets: [{
                    label: 'Distance (km)',
                    borderColor: '#4ECDC4',
                    backgroundColor: 'rgba(78, 205, 196, 0.1)',
                    data: data.distance.data,
                    fill: true,
                    tension: 0.4
                }]
            },
            elevation: {
                labels: data.elevation.labels,
                datasets: [{
                    label: 'Elevation Gain (ft)',
                    borderColor: '#95E1D3',
                    backgroundColor: 'rgba(149, 225, 211, 0.1)',
                    data: data.elevation.data,
                    fill: true,
                    tension: 0.4
                }]
            },
            breakdown: {
                labels: data.breakdown.labels,
                datasets: [{
                    data: data.breakdown.counts,
                    backgroundColor: ['#FF6B35', '#4ECDC4', '#95E1D3', '#F38181', '#AA96DA']
                }]
            },
            pace: {
                labels: data.pace.labels,
                datasets: [{
                    label: 'Pace (min/km)',
                    borderColor: '#F38181',
                    backgroundColor: 'rgba(243, 129, 129, 0.1)',
                    data: data.pace.data,
                    fill: true,
                    tension: 0.4
                }]
            }
        };
    } catch (error) {
        console.error('Error fetching chart data:', error);
    } finally {
        loadingCharts.value = false;
    }
};

const fetchActivitiesWithRoutes = async () => {
    try {
        const response = await axios.get(route('integrations.strava.fetch-activities-with-routes'), {
            params: { limit: 50 }
        });
        activities.value = response.data;
    } catch (error) {
        console.error('Error fetching activities:', error);
    }
};

const fetchHeatmapData = async () => {
    try {
        const response = await axios.get(route('integrations.strava.fetch-heatmap-data'), {
            params: { min_occurrences: 5 }
        });
        heatmapData.value = response.data;
        renderHeatmap();
    } catch (error) {
        console.error('Error fetching heatmap data:', error);
    }
};

const selectActivity = (activity: any) => {
    selectedActivity.value = activity;
    renderActivityMap();
};

const renderActivityMap = () => {
    if (!selectedActivity.value || !selectedActivity.value.polyline) return;

    // Destroy existing map
    if (activityMap.value) {
        activityMap.value.remove();
    }

    // Decode polyline
    const coordinates = polyline.decode(selectedActivity.value.polyline);

    // Create map
    const mapElement = document.getElementById('activity-map');
    if (!mapElement) return;

    activityMap.value = L.map('activity-map').setView(coordinates[0], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(activityMap.value);

    // Add route polyline
    const routeLine = L.polyline(coordinates, {
        color: '#FF6B35',
        weight: 3,
        opacity: 0.8
    }).addTo(activityMap.value);

    // Fit bounds to route
    activityMap.value.fitBounds(routeLine.getBounds());
};

const renderHeatmap = () => {
    if (!heatmapData.value || !heatmapData.value.polylines) return;

    // Destroy existing map
    if (heatmapMap.value) {
        heatmapMap.value.remove();
    }

    const mapElement = document.getElementById('heatmap');
    if (!mapElement) return;

    heatmapMap.value = L.map('heatmap').setView([40.7128, -74.0060], 12); // Default center

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(heatmapMap.value);

    // Decode and render all polylines
    const allCoordinates: any[] = [];
    heatmapData.value.polylines.forEach((encodedPolyline: string) => {
        const coordinates = polyline.decode(encodedPolyline);
        allCoordinates.push(...coordinates);
        
        L.polyline(coordinates, {
            color: '#FF6B35',
            weight: 2,
            opacity: 0.3
        }).addTo(heatmapMap.value);
    });

    // Center map on all routes
    if (allCoordinates.length > 0) {
        const bounds = L.latLngBounds(allCoordinates);
        heatmapMap.value.fitBounds(bounds);
    }
};

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
};

onMounted(() => {
    if (props.stravaConnected) {
        fetchChartData();
        fetchActivitiesWithRoutes();
        fetchHeatmapData();
    }
});

onUnmounted(() => {
    if (activityMap.value) {
        activityMap.value.remove();
    }
    if (heatmapMap.value) {
        heatmapMap.value.remove();
    }
});
</script>

<template>
    <AppLayout title="Strava Integration">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Strava Integration
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 lg:p-8">
                    <Head title="Strava Integration" />

                    <h1 class="text-2xl font-medium text-gray-900 mb-6">
                        Manage Strava Integration
                    </h1>

                    <div v-if="$page.props.flash && $page.props.flash.success" class="mb-4 font-medium text-sm text-green-600">
                        {{ $page.props.flash.success }}
                    </div>
                    <div v-if="$page.props.flash && $page.props.flash.error" class="mb-4 font-medium text-sm text-red-600">
                        {{ $page.props.flash.error }}
                    </div>

                    <div v-if="props.stravaConnected">
                        <p class="text-lg text-gray-700 mb-4">
                            Strava is currently connected to your account.
                        </p>
                        <div class="flex items-center space-x-4 mb-6">
                            <PrimaryButton @click="disconnectStrava">
                                Disconnect Strava
                            </PrimaryButton>
                            <PrimaryButton @click="syncStravaData" :disabled="syncing" type="button">
                                <span v-if="syncing">Syncing...</span>
                                <span v-else>Sync Now</span>
                            </PrimaryButton>
                        </div>

                        <div v-if="syncStats" class="mb-4 font-medium text-sm text-blue-600">
                            Sync Complete: Added {{ syncStats.added }} activities, Skipped {{ syncStats.skipped }} existing activities.
                        </div>

                        <div v-if="syncError" class="mb-4 font-medium text-sm text-red-600">
                            {{ syncError }}
                        </div>

                        <!-- Charts Section -->
                        <div v-if="chartData" class="mb-8">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Activity Statistics</h2>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div class="bg-gray-50 p-4 rounded-lg shadow">
                                    <h3 class="text-lg font-semibold mb-4">Activity Frequency</h3>
                                    <div class="h-64">
                                        <Chart type="bar" :data="chartData.frequency" :options="chartOptions" />
                                    </div>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg shadow">
                                    <h3 class="text-lg font-semibold mb-4">Distance Progress</h3>
                                    <div class="h-64">
                                        <Chart type="line" :data="chartData.distance" :options="chartOptions" />
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                                <div class="bg-gray-50 p-4 rounded-lg shadow">
                                    <h3 class="text-lg font-semibold mb-4">Elevation Gain</h3>
                                    <div class="h-64">
                                        <Chart type="line" :data="chartData.elevation" :options="chartOptions" />
                                    </div>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg shadow">
                                    <h3 class="text-lg font-semibold mb-4">Activity Types</h3>
                                    <div class="h-64">
                                        <Chart type="doughnut" :data="chartData.breakdown" :options="chartOptions" />
                                    </div>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg shadow">
                                    <h3 class="text-lg font-semibold mb-4">Running Pace</h3>
                                    <div class="h-64">
                                        <Chart type="line" :data="chartData.pace" :options="chartOptions" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Heatmap Section -->
                        <div class="mb-8">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Route Heatmap (≥5 times)</h2>
                            <div id="heatmap" class="h-96 rounded-lg shadow"></div>
                        </div>

                        <!-- Recent Activities with Routes -->
                        <div class="mb-8">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Recent Activities</h2>
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div class="bg-gray-50 p-4 rounded-lg shadow max-h-96 overflow-y-auto">
                                    <div v-for="activity in activities" :key="activity.id" 
                                         @click="selectActivity(activity)"
                                         class="p-3 mb-2 bg-white rounded cursor-pointer hover:bg-blue-50 transition"
                                         :class="{ 'bg-blue-100': selectedActivity?.id === activity.id }">
                                        <div class="font-semibold">{{ activity.name }}</div>
                                        <div class="text-sm text-gray-600">
                                            {{ activity.type }} • {{ activity.distance }} km • {{ activity.date }}
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg shadow">
                                    <div v-if="selectedActivity">
                                        <h3 class="font-semibold mb-2">{{ selectedActivity.name }}</h3>
                                        <div id="activity-map" class="h-80 rounded-lg"></div>
                                    </div>
                                    <div v-else class="h-80 flex items-center justify-center text-gray-500">
                                        Select an activity to view its route
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else>
                        <p class="text-lg text-gray-700 mb-4">
                            Connect your Strava account to sync your activities.
                        </p>
                        <PrimaryButton @click="connectStrava">
                            Connect with Strava
                        </PrimaryButton>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
