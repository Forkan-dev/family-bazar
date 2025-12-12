<script setup lang="ts">
import { FormField } from '@/components/ui/form-field'
import { Input } from '@/components/ui/input'
import { Textarea } from '@/components/ui/textarea'
import { computed } from 'vue'

interface Props {
    modelValue?: string | number
    label?: string
    type?: string
    errorMessages?: string | string[]
    multiline?: boolean
    placeholder?: string
    disabled?: boolean
    required?: boolean
    description?: string
    class?: string
}

const props = withDefaults(defineProps<Props>(), {
    type: 'text',
    multiline: false,
    disabled: false,
    required: false,
})

defineOptions({
    inheritAttrs: false
})

const emit = defineEmits<{
    'update:modelValue': [value: string | number]
}>()

const internalValue = computed({
    get: () => props.modelValue,
    set: (value) => emit('update:modelValue', value || ''),
})
</script>

<template>
    <FormField :label="label" :required="required" :error="errorMessages" :description="description" :class="class">
        <template #default="{ hasError }">
            <Textarea v-if="multiline" v-model="internalValue" :placeholder="placeholder" :disabled="disabled"
                :class="{ 'border-destructive focus-visible:ring-destructive': hasError }" rows="4" v-bind="$attrs" />

            <Input v-else v-model="internalValue" :type="type" :placeholder="placeholder" :disabled="disabled"
                :class="{ 'border-destructive focus-visible:ring-destructive': hasError }" v-bind="$attrs" />
        </template>
    </FormField>
</template>
