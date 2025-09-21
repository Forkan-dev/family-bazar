<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
    modelValue?: any;
    items?: any[];
    label?: string;
    itemTitle?: string;
    itemValue?: string;
    errorMessages?: any;
}>();

const emit = defineEmits(['update:modelValue']);

const model = computed({
    get: () => props.modelValue,
    set: (value) => emit('update:modelValue', value),
});
</script>

<template>
    <v-combobox
        v-model="model"
        :items="props.items"
        :label="props.label"
        :item-title="props.itemTitle"
        :item-value="props.itemValue"
        :error-messages="props.errorMessages"
        chips
        variant="outlined"
        density="compact"
    ></v-combobox>
</template>
