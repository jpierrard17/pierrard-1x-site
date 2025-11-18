<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm, Head } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import { defineProps } from 'vue';

const props = defineProps<{
    hevyConnected: boolean;
}>();

const form = useForm({
    api_key: '',
});

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

                    <div v-if="props.hevyConnected">
                        <p class="text-lg text-gray-700 mb-4">
                            Hevy is currently connected to your account.
                        </p>
                        <PrimaryButton @click="disconnectHevy">
                            Disconnect Hevy
                        </PrimaryButton>
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