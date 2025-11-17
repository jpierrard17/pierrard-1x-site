<script setup>
import Message from 'primevue/message';

defineProps({
    message: String,
});
</script>

<template>
    <Message v-show="message" severity="error">
        {{ message }}
    </Message>
</template>
