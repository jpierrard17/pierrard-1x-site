<script setup>
import { computed } from 'vue';
import Dialog from 'primevue/dialog';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    maxWidth: {
        type: String,
        default: '2xl',
    },
    closeable: {
        type: Boolean,
        default: true,
    },
});

const emit = defineEmits(['close']);

const onHide = () => {
    emit('close');
};

const maxWidthClass = computed(() => {
    return {
        'sm': 'sm:max-w-sm',
        'md': 'sm:max-w-md',
        'lg': 'sm:max-w-lg',
        'xl': 'sm:max-w-xl',
        '2xl': 'sm:max-w-2xl',
    }[props.maxWidth];
});
</script>

<template>
    <Dialog
        :visible="props.show"
        :modal="true"
        :closable="props.closeable"
        @update:visible="onHide"
        :pt="{
            root: 'w-full',
            mask: 'fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity',
            content: `mb-6 bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:mx-auto ${maxWidthClass}`
        }"
    >
        <slot />
    </Dialog>
</template>
