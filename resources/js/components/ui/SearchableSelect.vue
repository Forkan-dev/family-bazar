<script setup lang="ts">
import { ref, watch, computed, onMounted } from 'vue'
import { Button } from '@/components/ui/button'
// Removed Command imports - using custom implementation for better control
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover'
import { Check, ChevronsUpDown } from 'lucide-vue-next'
import { cn } from '@/lib/utils'
import axios from 'axios'

interface Option {
    value: any
    label: string
}

interface Props {
    modelValue?: any
    placeholder?: string
    searchUrl: string
    excludeId?: number | null
    errorMessages?: string | string[]
    label?: string
    required?: boolean
}

const props = withDefaults(defineProps<Props>(), {
    placeholder: 'Search and select...',
    excludeId: null,
    errorMessages: undefined,
    label: '',
    required: false
})

const emit = defineEmits<{
    'update:modelValue': [value: any]
}>()

const query = ref('')
const options = ref<Option[]>([])
const loading = ref(false)
const open = ref(false)

const selectedLabel = computed(() => {
    if (!props.modelValue) return ''
    const found = options.value.find(opt => opt.value === props.modelValue)
    return found?.label || ''
})

const searchOptions = async (searchQuery: string = '') => {
    loading.value = true
    try {
        const params: any = { q: searchQuery }
        if (props.excludeId) {
            params.exclude = props.excludeId
        }

        const response = await axios.get(props.searchUrl, { params })
        options.value = response.data
    } catch (error) {
        console.error('Error fetching options:', error)
        options.value = []
    } finally {
        loading.value = false
    }
}

// Debounced search
let searchTimeout: NodeJS.Timeout
const handleSearch = (searchQuery: string) => {
    query.value = searchQuery

    // Clear previous timeout
    clearTimeout(searchTimeout)

    // Debounce the actual search
    searchTimeout = setTimeout(() => {
        searchOptions(searchQuery)
    }, 300)
}

// Initial search
onMounted(() => {
    searchOptions()
})

const handleSelection = (optionValue: any) => {
    emit('update:modelValue', optionValue)
    open.value = false
}

const errorArray = computed(() => {
    if (!props.errorMessages) return []
    return Array.isArray(props.errorMessages) ? props.errorMessages : [props.errorMessages]
})

// Watch for modelValue changes to update display
watch(() => props.modelValue, (newValue) => {
    // If there's a value but we don't have options or the selected option is not in the list
    if (newValue && !options.value.find(opt => opt.value === newValue)) {
        searchOptions()
    }
}, { immediate: true })
</script>

<template>
    <div class="w-full">
        <label v-if="label" class="block text-sm font-medium mb-2">
            {{ label }}
            <span v-if="required" class="text-red-500 ml-1">*</span>
        </label>

        <Popover v-model:open="open">
            <PopoverTrigger as-child>
                <Button variant="outline" role="combobox" :aria-expanded="open" :class="cn(
                    'w-full justify-between',
                    !selectedLabel && 'text-muted-foreground',
                    errorArray.length > 0 && 'border-red-500'
                )">
                    {{ selectedLabel || placeholder }}
                    <ChevronsUpDown class="ml-2 h-4 w-4 shrink-0 opacity-50" />
                </Button>
            </PopoverTrigger>
            <PopoverContent class="w-[--radix-popover-trigger-width] p-0" align="start">
                <div class="flex flex-col">
                    <!-- Search Input -->
                    <div class="flex items-center border-b px-3" cmdk-input-wrapper="">
                        <input :value="query" @input="handleSearch($event.target.value)"
                            :placeholder="`Search ${label?.toLowerCase() || 'options'}...`"
                            class="flex h-10 w-full rounded-md bg-transparent py-3 text-sm outline-none placeholder:text-muted-foreground disabled:cursor-not-allowed disabled:opacity-50" />
                    </div>

                    <!-- Results -->
                    <div class="max-h-[300px] overflow-y-auto">
                        <div v-if="loading" class="px-2 py-6 text-center text-sm text-muted-foreground">
                            Searching...
                        </div>

                        <div v-else-if="options.length === 0"
                            class="px-2 py-6 text-center text-sm text-muted-foreground">
                            No options found.
                        </div>

                        <div v-else class="p-1">
                            <div v-for="option in options" :key="option.value || 'null'"
                                @click="handleSelection(option.value)"
                                class="relative flex cursor-default select-none items-center rounded-sm px-2 py-1.5 text-sm outline-none hover:bg-accent hover:text-accent-foreground cursor-pointer">
                                <Check :class="cn(
                                    'mr-2 h-4 w-4',
                                    modelValue === option.value ? 'opacity-100' : 'opacity-0'
                                )" />
                                {{ option.label }}
                            </div>
                        </div>
                    </div>
                </div>
            </PopoverContent>
        </Popover>

        <div v-if="errorArray.length > 0" class="mt-1">
            <p v-for="error in errorArray" :key="error" class="text-sm text-red-600">
                {{ error }}
            </p>
        </div>
    </div>
</template>
