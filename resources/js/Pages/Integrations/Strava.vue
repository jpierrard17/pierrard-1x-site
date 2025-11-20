<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { defineProps, ref } from 'vue';
import axios from 'axios';

const props = defineProps<{
    stravaConnected: boolean;
}>();

const form = useForm({});
const syncing = ref(false);
const syncStats = ref(null);
const syncError = ref(null);

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
    } catch (error: any) {
        syncError.value = error.response?.data?.message || 'Failed to sync Strava data.';
        console.error('Error syncing Strava data:', error);
    } finally {
        syncing.value = false;
    }
};
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
                            <PrimaryButton @click="syncStravaData" :disabled="syncing">
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
