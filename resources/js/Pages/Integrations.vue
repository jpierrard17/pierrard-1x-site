<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
    <AppLayout title="Integrations">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Integrations
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                        <h1 class="mt-8 text-2xl font-medium text-gray-900">
                            Integrations Overview
                        </h1>

                        <p class="mt-6 text-gray-500 leading-relaxed">
                            This page will provide an overview of all available integrations.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>