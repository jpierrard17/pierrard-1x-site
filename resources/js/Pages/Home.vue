<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue';
import Welcome from '@/Components/Welcome.vue';
</script>

<template>
    <GuestLayout title="Home">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Home
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <Welcome />
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
