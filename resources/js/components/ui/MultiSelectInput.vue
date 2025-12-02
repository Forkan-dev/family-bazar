<script setup lang="ts">
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { cn } from '@/lib/utils'
import { Check, X, ChevronDown, Plus } from 'lucide-vue-next'
import { computed, ref, watch } from 'vue'

interface Option {
    value: any
    label: string
}

interface Props {
    modelValue?: any[]
    options: Option[]
    placeholder?: string
    label?: string
    errorMessages?: string[]
    required?: boolean
    allowCreate?: boolean
    createText?: string
    searchable?: boolean
    disabled?: boolean
    class?: string
}

const props = withDefaults(defineProps<Props>(), {
    placeholder: 'Select options...',
    allowCreate: true,
    createText: 'Create new',
    searchable: true,
    disabled: false
})

const emit = defineEmits<{
    'update:modelValue': [value: any[]]
    'create': [value: string]
}>()

const isOpen = ref(false)
const searchQuery = ref('')
const inputRef = ref<HTMLInputElement>()

const selectedValues = computed({
    get: () => props.modelValue || [],
    set: (value) => emit('update:modelValue', value)
})

const filteredOptions = computed(() => {
    if (!searchQuery.value) return props.options

    return props.options.filter(option =>
        option.label.toLowerCase().includes(searchQuery.value.toLowerCase())
    )
})

const selectedOptions = computed(() => {
    return props.options.filter(option =>
        selectedValues.value.some(val =>
            typeof val === 'object' ? val.value === option.value : val === option.value
        )
    )
})

const hasExactMatch = computed(() =>
    props.options.some(option =>
        option.label.toLowerCase() === searchQuery.value.toLowerCase()
    )
)

const canCreate = computed(() =>
    props.allowCreate &&
    searchQuery.value.trim() !== '' &&
    !hasExactMatch.value
)

const hasError = computed(() => props.errorMessages && props.errorMessages.length > 0)

const toggleOption = (option: Option) => {
    const isSelected = selectedValues.value.some(val =>
        typeof val === 'object' ? val.value === option.value : val === option.value
    )

    if (isSelected) {
        selectedValues.value = selectedValues.value.filter(val =>
            typeof val === 'object' ? val.value !== option.value : val !== option.value
        )
    } else {
        selectedValues.value = [...selectedValues.value, option.value]
    }

    searchQuery.value = ''
}

const removeOption = (option: Option) => {
    selectedValues.value = selectedValues.value.filter(val =>
        typeof val === 'object' ? val.value !== option.value : val !== option.value
    )
}

const createNewOption = () => {
    if (canCreate.value) {
        const newValue = searchQuery.value.trim()
        emit('create', newValue)
        searchQuery.value = ''
        closeDropdown()
    }
}

const openDropdown = () => {
    if (!props.disabled) {
        isOpen.value = true
        if (props.searchable) {
            setTimeout(() => inputRef.value?.focus(), 0)
        }
    }
}

const closeDropdown = () => {
    isOpen.value = false
    searchQuery.value = ''
}

// Close dropdown when clicking outside
const handleClickOutside = (event: Event) => {
    const target = event.target as Element
    if (!target.closest('.multiselect-container')) {
        closeDropdown()
    }
}

watch(isOpen, (open) => {
    if (open) {
        document.addEventListener('click', handleClickOutside)
    } else {
        document.removeEventListener('click', handleClickOutside)
    }
})
</script>

<template>
    <div class="multiselect-container relative" :class="props.class">
        <label v-if="label" class="text-sm font-medium mb-2 block">
            {{ label }}
            <span v-if="required" class="text-red-500">*</span>
        </label>

        <!-- Selected Values Display -->
        <div @click="openDropdown" :class="cn(
            'min-h-[40px] w-full border border-input bg-transparent dark:bg-input/30 px-3 py-2 text-sm cursor-pointer rounded-md',
            'flex flex-wrap gap-1 items-center',
            hasError ? 'border-red-500' : 'border-border focus:border-primary',
            disabled ? 'opacity-50 cursor-not-allowed' : 'hover:border-gray-400',
            isOpen ? 'border-primary ring-1 ring-primary' : ''
        )">
            <!-- Selected Tags -->
            <div v-if="selectedOptions.length > 0" class="flex flex-wrap gap-1">
                <div v-for="option in selectedOptions" :key="option.value"
                    class="inline-flex items-center gap-1 bg-primary/10 text-primary text-xs px-2 py-1 rounded">
                    {{ option.label }}
                    <button v-if="!disabled" type="button" @click.stop="removeOption(option)"
                        class="hover:bg-primary/20 rounded-full p-0.5">
                        <X class="h-3 w-3" />
                    </button>
                </div>
            </div>

            <!-- Placeholder -->
            <span v-if="selectedOptions.length === 0" class="text-muted-foreground">
                {{ placeholder }}
            </span>

            <!-- Chevron -->
            <ChevronDown :class="cn(
                'h-4 w-4 ml-auto transition-transform text-muted-foreground',
                isOpen ? 'rotate-180' : ''
            )" />
        </div>

        <!-- Dropdown -->
        <div v-if="isOpen"
            class="absolute z-50 mt-1 w-full left-0 right-0 bg-popover border border-border rounded-md shadow-lg max-h-60 overflow-auto">
            <!-- Search Input -->
            <div v-if="searchable" class="p-2 border-b">
                <Input ref="inputRef" v-model="searchQuery" placeholder="Search or type to create..." class="h-8" />
            </div>

            <!-- Options List -->
            <div class="py-1">
                <!-- Create New Option -->
                <button v-if="canCreate" type="button" @click="createNewOption"
                    class="w-full px-3 py-2 text-left text-sm hover:bg-accent flex items-center gap-2 text-primary">
                    <Plus class="h-4 w-4" />
                    {{ createText }} "{{ searchQuery }}"
                </button>

                <!-- Existing Options -->
                <button v-for="option in filteredOptions" :key="option.value" type="button"
                    @click="toggleOption(option)" :class="cn(
                        'w-full px-3 py-2 text-left text-sm hover:bg-accent flex items-center justify-between transition-colors',
                        selectedValues.some(val => typeof val === 'object' ? val.value === option.value : val === option.value)
                            ? 'bg-primary/5 text-primary font-medium' : ''
                    )">
                    <span>{{ option.label }}</span>
                    <Check v-if="selectedValues.some(val =>
                        typeof val === 'object' ? val.value === option.value : val === option.value
                    )" class="h-4 w-4 text-white font-bold stroke-2" />
                </button>

                <!-- No Options -->
                <div v-if="filteredOptions.length === 0 && !canCreate" class="px-3 py-2 text-sm text-muted-foreground">
                    No options found
                </div>
            </div>
        </div>

        <!-- Error Messages -->
        <div v-if="hasError" class="mt-1">
            <p v-for="error in errorMessages" :key="error" class="text-sm text-red-600">
                {{ error }}
            </p>
        </div>
    </div>
</template>
