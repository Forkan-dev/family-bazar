<script setup lang="ts">
import { FormField } from '@/components/ui/form-field'
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select'
import { computed } from 'vue'

interface SelectOption {
  value: string | number
  label: string
  disabled?: boolean
}

interface Props {
    modelValue?: string | number | null
    label?: string
    errorMessages?: string | string[]
    placeholder?: string
    disabled?: boolean
    required?: boolean
    description?: string
    options?: SelectOption[]
    class?: string
}

const props = withDefaults(defineProps<Props>(), {
    disabled: false,
    required: false,
    options: () => [],
    placeholder: 'Select an option...'
})

const emit = defineEmits<{
    'update:modelValue': [value: string | number | null]
}>()

const internalValue = computed({
    get: () => props.modelValue?.toString() || '',
    set: (value) => {
        if (value === '') {
            emit('update:modelValue', null)
        } else {
            // Try to convert back to number if original was number
            const option = props.options.find(opt => opt.value.toString() === value)
            emit('update:modelValue', option ? option.value : value)
        }
    },
})
</script>

<template>
    <FormField
        :label="label"
        :required="required"
        :error="errorMessages"
        :description="description"
        :class="class"
    >
        <template #default="{ hasError }">
            <Select
                v-model="internalValue"
                :disabled="disabled"
            >
                <SelectTrigger
                    :class="{ 'border-destructive focus-visible:ring-destructive': hasError }"
                >
                    <SelectValue :placeholder="placeholder" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem
                        v-for="option in options"
                        :key="option.value"
                        :value="option.value.toString()"
                        :disabled="option.disabled"
                    >
                        {{ option.label }}
                    </SelectItem>
                </SelectContent>
            </Select>
        </template>
    </FormField>
</template>
