<script setup lang="ts">
import { cn } from '@/lib/utils'
import { Upload, X, Images } from 'lucide-vue-next'
import { computed, ref } from 'vue'

interface Props {
    modelValue?: File[]
    accept?: string
    maxSize?: number // in MB per file
    maxFiles?: number
    label?: string
    errorMessages?: string[]
    required?: boolean
    class?: string
}

const props = withDefaults(defineProps<Props>(), {
    accept: 'image/*',
    maxSize: 2,
    maxFiles: 10,
    label: 'Upload Images',
    modelValue: () => []
})

const emit = defineEmits<{
    'update:modelValue': [files: File[]]
}>()

const isDragOver = ref(false)
const fileInput = ref<HTMLInputElement>()

const selectedFiles = computed(() => props.modelValue || [])

const hasError = computed(() => {
    return props.errorMessages && props.errorMessages.length > 0
})

const handleDrop = (event: DragEvent) => {
    event.preventDefault()
    isDragOver.value = false

    const files = event.dataTransfer?.files
    if (files) {
        handleFilesSelect(Array.from(files))
    }
}

const handleFilesSelect = (newFiles: File[]) => {
    const validFiles: File[] = []

    for (const file of newFiles) {
        // Check file size
        if (file.size > props.maxSize * 1024 * 1024) {
            console.error(`File ${file.name} exceeds ${props.maxSize}MB limit`)
            continue
        }

        // Check file type
        if (!file.type.startsWith('image/')) {
            console.error(`File ${file.name} is not an image`)
            continue
        }

        validFiles.push(file)
    }

    // Combine with existing files, respecting maxFiles limit
    const allFiles = [...selectedFiles.value, ...validFiles]
    const limitedFiles = allFiles.slice(0, props.maxFiles)

    emit('update:modelValue', limitedFiles)
}

const handleInputChange = (event: Event) => {
    const target = event.target as HTMLInputElement
    const files = target.files
    if (files) {
        handleFilesSelect(Array.from(files))
    }
}

const removeFile = (index: number) => {
    const newFiles = selectedFiles.value.filter((_, i) => i !== index)
    emit('update:modelValue', newFiles)

    // Reset input
    if (fileInput.value) {
        fileInput.value.value = ''
    }
}

const openFileDialog = () => {
    fileInput.value?.click()
}

const getFileUrl = (file: File) => {
    return URL.createObjectURL(file)
}
</script>

<template>
    <div>
        <label v-if="label" class="text-sm font-medium mb-2 block">
            {{ label }}
            <span v-if="required" class="text-red-500">*</span>
        </label>

        <!-- Selected Images Preview -->
        <div v-if="selectedFiles.length > 0" class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
            <div v-for="(file, index) in selectedFiles" :key="`file-${index}`" class="relative group">
                <img :src="getFileUrl(file)" :alt="file.name" class="w-full h-24 object-cover rounded-lg border" />
                <button type="button" @click.stop="removeFile(index)"
                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 opacity-0 group-hover:opacity-100 transition-opacity">
                    <X class="h-3 w-3" />
                </button>
                <div class="absolute bottom-0 left-0 right-0 bg-black/50 text-white text-xs p-1 rounded-b-lg truncate">
                    {{ file.name }}
                </div>
            </div>
        </div>

        <!-- Upload Area -->
        <div @drop="handleDrop" @dragover.prevent="isDragOver = true" @dragleave.prevent="isDragOver = false"
            @click="openFileDialog" :class="cn(
                'border border-dashed rounded-lg p-6 text-center cursor-pointer transition-colors bg-transparent dark:bg-input/30',
                isDragOver ? 'border-primary bg-primary/5' : 'border-input hover:border-gray-400',
                hasError ? 'border-destructive' : '',
                props.class
            )">
            <input ref="fileInput" type="file" :accept="accept" @change="handleInputChange" :multiple="maxFiles > 1"
                class="hidden" />

            <div class="space-y-4">
                <div class="mx-auto">
                    <Images class="h-12 w-12 text-gray-400 mx-auto" />
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">Drop images here or click to upload</p>
                    <p class="text-xs text-gray-500">
                        {{ accept }} up to {{ maxSize }}MB each, max {{ maxFiles }} files
                    </p>
                    <p v-if="selectedFiles.length > 0" class="text-xs text-primary mt-1">
                        {{ selectedFiles.length }}/{{ maxFiles }} files selected
                    </p>
                </div>
            </div>
        </div>

        <!-- Error Messages -->
        <div v-if="hasError" class="mt-2 space-y-1">
            <p v-for="error in errorMessages" :key="error" class="text-sm text-red-600">
                {{ error }}
            </p>
        </div>
    </div>
</template>
