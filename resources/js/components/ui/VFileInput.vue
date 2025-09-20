<script setup lang="ts">
import { computed } from 'vue';
const props = withDefaults(
    defineProps<{
        density?: 'default' | 'comfortable' | 'compact';
        modelValue?: any;
    }>(),
    {
        density: 'compact',
    }
);

const emit = defineEmits(['update:modelValue']);

const model = computed({
    get: () => props.modelValue,
    set: (value) => emit('update:modelValue', value),
});
</script>

<template>
    <v-file-upload :density="props.density" v-model="model" class="mb-3"></v-file-upload>
</template>
