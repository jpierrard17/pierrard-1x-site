<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm, Head } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import { defineProps, ref, onMounted } from 'vue';
import axios from 'axios';
import Chart from 'primevue/chart';

const props = defineProps<{
    hevyConnected: boolean;
}>();

const form = useForm({
    api_key: '',
});

const hevyData = ref(null);
const chartData = ref(null);
const loading = ref(false);
const fetchError = ref(null);

const submitApiKey = () => {
    form.post(route('integrations.hevy.store-api-key'), {
        onSuccess: () => {
            form.reset('api_key');
        },
    });
};

const disconnectHevy = () => {
    form.post(route('integrations.hevy.disconnect'));
};

const fetchHevyData = async () => {
    loading.value = true;
    fetchError.value = null;
    try {
        const response = await axios.get(route('integrations.hevy.fetch-data'));
        hevyData.value = response.data;
    } catch (error: any) {
        fetchError.value = error.response?.data?.message || 'Failed to fetch Hevy data.';
        console.error('Error fetching Hevy data:', error);
    } finally {
        loading.value = false;
    }
};

const fetchChartData = async () => {
    try {
        const response = await axios.get(route('integrations.hevy.fetch-chart-data'));
        const data = response.data;

        chartData.value = {
            frequency: {
                labels: data.frequency.labels,
                datasets: [
                    {
                        label: 'Workouts per Month',
                        backgroundColor: '#42A5F5',
                        data: data.frequency.data
                    }
                ]
            },
            volume: {
                labels: data.volume.labels,
                datasets: [
                    {
                        label: 'Volume (kg)',
                        borderColor: '#66BB6A',
                        data: data.volume.data,
                        fill: false,
                        tension: 0.4
                    }
                ]
            }
        };
    } catch (error) {
        console.error('Error fetching chart data:', error);
    }
};

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
};

onMounted(() => {
    if (props.hevyConnected) {
        fetchChartData();
    }
});
</script>

<template>
    <AppLayout title="Hevy Integration">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Hevy Integration
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 lg:p-8">
                    <Head title="Hevy Integration" />

                    <h1 class="text-2xl font-medium text-gray-900 mb-6">
                        Manage Hevy Integration
                    </h1>

                    <div v-if="$page.props.flash && $page.props.flash.success" class="mb-4 font-medium text-sm text-green-600">
                        {{ $page.props.flash.success }}
                    </div>
                    <div v-if="$page.props.flash && $page.props.flash.error" class="mb-4 font-medium text-sm text-red-600">
                        {{ $page.props.flash.error }}
                    </div>

                    <div v-if="props.hevyConnected">
                        <p class="text-lg text-gray-700 mb-4">
                            Hevy is currently connected to your account.
                        </p>
                        <div class="flex items-center space-x-4 mb-6">
                            <PrimaryButton @click="disconnectHevy">
                                Disconnect Hevy
                            </PrimaryButton>
                            <PrimaryButton @click="fetchHevyData" :disabled="loading">
                                <span v-if="loading">Fetching Data...</span>
                                <span v-else>Fetch Raw Data</span>
                            </PrimaryButton>
                        </div>

                        <div v-if="fetchError" class="mb-4 font-medium text-sm text-red-600">
                            {{ fetchError }}
                        </div>

                        <div v-if="chartData" class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div class="bg-gray-50 p-4 rounded-lg shadow">
                                <h3 class="text-lg font-semibold mb-4">Workout Frequency</h3>
                                <div class="h-64">
                                    <Chart type="bar" :data="chartData.frequency" :options="chartOptions" />
                                </div>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg shadow">
                                <h3 class="text-lg font-semibold mb-4">Volume Progress</h3>
                                <div class="h-64">
                                    <Chart type="line" :data="chartData.volume" :options="chartOptions" />
                                </div>
                            </div>
                        </div>

                        <div v-if="hevyData">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Fetched Hevy Data:</h3>
                            <pre class="bg-gray-100 p-4 rounded-md text-sm overflow-x-auto">{{ JSON.stringify(hevyData, null, 2) }}</pre>
                        </div>
                    </div>
                    <div v-else>
                        <p class="text-lg text-gray-700 mb-4">
                            Connect your Hevy account by entering your API key below.
                        </p>
                        <form @submit.prevent="submitApiKey" class="max-w-md">
                            <div class="mb-4">
                                <InputLabel for="api_key" value="Hevy API Key" />
                                <TextInput
                                    id="api_key"
                                    v-model="form.api_key"
                                    type="text"
                                    class="mt-1 block w-full"
                                    required
                                    autofocus
                                />
                                <InputError :message="form.errors.api_key" class="mt-2" />
                            </div>
                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Connect Hevy
                            </PrimaryButton>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>