<script setup lang="ts">
import { Button } from '@/components/ui/button'
import { cn } from '@/lib/utils'
import { Link } from '@inertiajs/vue3'
import { ArrowLeft } from 'lucide-vue-next'
import { computed } from 'vue'

interface Props {
    title?: string
    description?: string
    loading?: boolean
    submitText?: string
    cancelText?: string
    showCancel?: boolean
    gridCols?: number
    maxWidth?: string
    class?: string
    backUrl?: string
    backText?: string
}

const props = withDefaults(defineProps<Props>(), {
    submitText: 'Save',
    cancelText: 'Cancel',
    showCancel: false,
    gridCols: 1,
    maxWidth: '2xl',
    backText: 'Back'
})

const emit = defineEmits<{
    submit: []
    cancel: []
}>()

const gridClass = computed(() => {
    const cols: Record<number, string> = {
        1: 'grid-cols-1',
        2: 'grid-cols-1 md:grid-cols-2',
        3: 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3',
        4: 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4'
    }
    return cols[props.gridCols] || 'grid-cols-1'
})

const maxWidthClass = computed(() => {
    const widths: Record<string, string> = {
        'sm': 'max-w-sm',
        'md': 'max-w-md',
        'lg': 'max-w-lg',
        'xl': 'max-w-xl',
        '2xl': 'max-w-2xl',
        '3xl': 'max-w-3xl',
        '4xl': 'max-w-4xl',
        '5xl': 'max-w-5xl',
        '6xl': 'max-w-6xl',
        '7xl': 'max-w-7xl',
        'full': 'max-w-full'
    }
    return widths[props.maxWidth] || 'max-w-2xl'
})
</script>

<template>
    <!-- Header -->
    <div class="mb-8" v-if="backUrl">
        <Link :href="backUrl">
        <Button variant="ghost" size="sm">
            <ArrowLeft class="h-4 w-4 mr-2" />
            {{ backText }}
        </Button>
        </Link>
    </div>

    <!-- Form -->
    <div :class="cn(maxWidthClass, 'mx-auto', props.class)">
        <div class="bg-card border rounded-lg">
            <div v-if="title || description" class="p-6 border-b">
                <h1 v-if="title" class="text-2xl font-bold">
                    {{ title }}
                </h1>
                <p v-if="description" class="text-sm text-muted-foreground mt-1">
                    {{ description }}
                </p>
            </div>

            <div class="p-6">
                <form @submit.prevent="emit('submit')" class="space-y-6">
                    <div :class="cn('space-y-6', gridClass === 'grid-cols-1' ? '' : 'grid gap-6 ' + gridClass)">
                        <slot />
                    </div>

                    <div class="flex justify-end space-x-3 pt-6 border-t">
                        <Button v-if="showCancel" type="button" variant="outline" @click="emit('cancel')">
                            {{ cancelText }}
                        </Button>
                        <Button type="submit" :disabled="loading">
                            {{ loading ? 'Saving...' : submitText }}
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
