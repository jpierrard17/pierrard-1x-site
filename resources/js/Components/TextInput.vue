<script setup>
import { onMounted, ref } from 'vue';
import InputText from 'primevue/inputtext';

const props = defineProps({
    modelValue: String,
    autofocus: Boolean,
});

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (props.autofocus) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <InputText
        ref="input"
        :value="props.modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        :autofocus="props.autofocus"
    />
</template>
