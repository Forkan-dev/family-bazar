<script setup lang="ts">
import { Label } from '@/components/ui/label'
import { cn } from '@/lib/utils'
import { computed } from 'vue'

interface Props {
    label?: string
    required?: boolean
    error?: string | string[]
    description?: string
    class?: string
}

const props = defineProps<Props>()

const errorMessage = computed(() => {
    if (Array.isArray(props.error)) {
        return props.error[0]
    }
    return props.error
})

const hasError = computed(() => Boolean(errorMessage.value))
</script>

<template>
    <div :class="cn('space-y-2', props.class)">
        <Label v-if="label" :class="{ 'text-destructive': hasError }">
            {{ label }}
            <span v-if="required" class="text-destructive ml-1">*</span>
        </Label>

        <div class="relative">
            <slot :has-error="hasError" />
        </div>

        <p v-if="description && !hasError" class="text-sm text-muted-foreground">
            {{ description }}
        </p>

        <div v-if="hasError" class="space-y-1">
            <p v-if="!Array.isArray(error)" class="text-sm text-destructive">
                {{ error }}
            </p>
            <p v-else v-for="err in error" :key="err" class="text-sm text-destructive">
                {{ err }}
            </p>
        </div>
    </div>
</template>
