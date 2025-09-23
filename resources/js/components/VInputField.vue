<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps({
    modelValue: [String, Number],
    label: String,
    type: { type: String, default: 'text' },
    errorMessages: [String, Array],
    multiline: { type: Boolean, default: false },
    // Pass through any other props to v-text-field or v-textarea
    // eslint-disable-next-line vue/no-unused-properties
    otherProps: Object,
});

const emit = defineEmits(['update:modelValue']);

const internalValue = computed({
    get: () => props.modelValue,
    set: (value) => emit('update:modelValue', value),
});
</script>

<template>
    <v-textarea
        v-if="multiline"
        v-model="internalValue"
        :label="label"
        :error-messages="errorMessages"
        variant="outlined"
        density="compact"
        class="mb-1"
        v-bind="otherProps"
    ></v-textarea>
    <v-text-field
        v-else
        v-model="internalValue"
        :label="label"
        :type="type"
        :error-messages="errorMessages"
        variant="outlined"
        density="compact"
        class="mb-1"
        v-bind="otherProps"
    ></v-text-field>
</template>
