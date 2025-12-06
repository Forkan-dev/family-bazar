<script setup lang="ts">
import { ref, watch, computed, onMounted } from 'vue'
import { Button } from '@/components/ui/button'
import {
    Command,
    CommandEmpty,
    CommandGroup,
    CommandInput,
    CommandItem,
    CommandList,
} from '@/components/ui/command'
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
    initialOption?: Option | null
    initialOptions?: Option[]
}

const props = withDefaults(defineProps<Props>(), {
    placeholder: 'Search and select...',
    excludeId: null,
    errorMessages: undefined,
    label: '',
    required: false,
    initialOption: null,
    initialOptions: () => []
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
    const found = options.value.find(opt => opt.value == props.modelValue) // Using == for loose equality
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
        const fetchedOptions = response.data

        // If we have an initial option and it's not in the fetched results, keep it
        if (props.initialOption && props.modelValue) {
            const hasInitialOption = fetchedOptions.some((opt: Option) => opt.value == props.modelValue)
            if (!hasInitialOption) {
                options.value = [props.initialOption, ...fetchedOptions]
            } else {
                options.value = fetchedOptions
            }
        } else {
            options.value = fetchedOptions
        }
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
    // If there are initial options, use them
    if (props.initialOptions && props.initialOptions.length > 0) {
        options.value = props.initialOptions
    }
    // If there's an initial option (for edit), add it to the options list
    else if (props.initialOption) {
        options.value = [props.initialOption]
    }
    // Only fetch if no initial data provided
    else {
        searchOptions()
    }
})

const selectOption = (selectedValue: any) => {
    emit('update:modelValue', selectedValue)
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
                <Command :filter-function="() => 1">
                    <CommandInput :placeholder="`Search ${label?.toLowerCase() || 'options'}...`" :model-value="query"
                        @update:model-value="handleSearch" />
                    <CommandList>
                        <CommandEmpty v-if="!loading">
                            {{ options.length === 0 ? 'No options found.' : 'Start typing to search...' }}
                        </CommandEmpty>
                        <CommandEmpty v-else>
                            Searching...
                        </CommandEmpty>
                        <CommandGroup v-if="options.length > 0">
                            <CommandItem v-for="option in options" :key="option.value" :value="option.value"
                                @select="(ev) => selectOption(ev.detail.value)">
                                {{ option.label }}
                                <Check :class="cn(
                                    'ml-auto h-4 w-4',
                                    modelValue == option.value ? 'opacity-100' : 'opacity-0'
                                )" />
                            </CommandItem>
                        </CommandGroup>
                    </CommandList>
                </Command>
            </PopoverContent>
        </Popover>

        <div v-if="errorArray.length > 0" class="mt-1">
            <p v-for="error in errorArray" :key="error" class="text-sm text-red-600">
                {{ error }}
            </p>
        </div>
    </div>
</template>
