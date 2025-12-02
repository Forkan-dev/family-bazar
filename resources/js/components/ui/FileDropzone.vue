<script setup lang="ts">
import { cn } from '@/lib/utils'
import { Upload, X, Image } from 'lucide-vue-next'
import { computed, ref } from 'vue'

interface Props {
    modelValue?: File | null
    accept?: string
    maxSize?: number // in MB
    label?: string
    errorMessages?: string[]
    required?: boolean
    class?: string
}

const props = withDefaults(defineProps<Props>(), {
    accept: 'image/*',
    maxSize: 2,
    label: 'Upload File'
})

const emit = defineEmits<{
    'update:modelValue': [file: File | null]
}>()

const isDragOver = ref(false)
const fileInput = ref<HTMLInputElement>()

const fileUrl = computed(() => {
    if (props.modelValue) {
        return URL.createObjectURL(props.modelValue)
    }
    return null
})

const hasError = computed(() => {
    return props.errorMessages && props.errorMessages.length > 0
})

const handleDrop = (event: DragEvent) => {
    event.preventDefault()
    isDragOver.value = false

    const files = event.dataTransfer?.files
    if (files && files.length > 0) {
        handleFileSelect(files[0])
    }
}

const handleFileSelect = (file: File) => {
    // Check file size
    if (file.size > props.maxSize * 1024 * 1024) {
        console.error(`File size exceeds ${props.maxSize}MB limit`)
        return
    }

    emit('update:modelValue', file)
}

const handleInputChange = (event: Event) => {
    const target = event.target as HTMLInputElement
    const file = target.files?.[0]
    if (file) {
        handleFileSelect(file)
    }
}

const removeFile = () => {
    emit('update:modelValue', null)
    if (fileInput.value) {
        fileInput.value.value = ''
    }
}

const openFileDialog = () => {
    fileInput.value?.click()
}
</script>

<template>
    <div>
        <label v-if="label" class="text-sm font-medium mb-2 block">
            {{ label }}
            <span v-if="required" class="text-red-500">*</span>
        </label>

        <div @drop="handleDrop" @dragover.prevent="isDragOver = true" @dragleave.prevent="isDragOver = false"
            @click="openFileDialog" :class="cn(
                'border border-dashed rounded-lg p-6 text-center cursor-pointer transition-colors bg-transparent dark:bg-input/30',
                isDragOver ? 'border-primary bg-primary/5' : 'border-input hover:border-gray-400',
                hasError ? 'border-destructive' : '',
                props.class
            )">
            <input ref="fileInput" type="file" :accept="accept" @change="handleInputChange" class="hidden" />

            <!-- File Preview -->
            <div v-if="fileUrl" class="space-y-4">
                <div class="relative inline-block">
                    <img :src="fileUrl" :alt="modelValue?.name"
                        class="max-h-32 max-w-32 object-cover rounded-lg mx-auto" />
                    <button type="button" @click.stop="removeFile"
                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                        <X class="h-3 w-3" />
                    </button>
                </div>
                <p class="text-sm text-gray-600">{{ modelValue?.name }}</p>
                <p class="text-xs text-gray-500">Click to change file</p>
            </div>

            <!-- Upload Prompt -->
            <div v-else class="space-y-4">
                <div class="mx-auto">
                    <Upload class="h-12 w-12 text-gray-400 mx-auto" />
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">Drop files here or click to upload</p>
                    <p class="text-xs text-gray-500">{{ accept }} up to {{ maxSize }}MB</p>
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
