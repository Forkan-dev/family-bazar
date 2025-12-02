<script setup lang="ts">
import { FormContainer } from '@/components/ui/form-container'
import { FormSelect } from '@/components/ui/form-select'
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs'
import FileDropzone from '@/components/ui/FileDropzone.vue'
import VInputField from '@/components/VInputField.vue'
import { useForm } from '@inertiajs/vue3'
import { onMounted, computed } from 'vue'

interface Banner {
    id?: number
    title?: string | { en?: string; bn?: string }
    sub_title?: string | { en?: string; bn?: string }
    description?: string | { en?: string; bn?: string }
    image?: string
    type_id?: number
    position?: string | number
    status?: 'active' | 'inactive'
    button_text_1?: string
    button_url_1?: string
    button_text_2?: string
    button_url_2?: string
}

interface Props {
    banner?: Banner
    types: Array<{ id: number; name: string }>
    action: 'create' | 'edit'
}

const props = withDefaults(defineProps<Props>(), {
    action: 'create'
})

const emit = defineEmits<{
    submit: [form: any]
    cancel: []
}>()

const route = (name: string, params?: any) => {
    return (window as any).route(name, params)
}

// Parse JSON data helper
const parseJsonField = (field: string | { en?: string; bn?: string } | undefined) => {
    if (!field) return { en: '', bn: '' }

    if (typeof field === 'string') {
        try {
            return JSON.parse(field)
        } catch {
            return { en: field, bn: '' }
        }
    }

    return field
}

const form = useForm({
    _method: props.action === 'edit' ? 'PUT' : undefined,
    title_en: '',
    title_bn: '',
    sub_title_en: '',
    sub_title_bn: '',
    description_en: '',
    description_bn: '',
    position: 'top',
    status: 'active' as 'active' | 'inactive',
    image: null as File | null,
    type_id: null as number | null,
    button_text_1: '',
    button_url_1: '',
    button_text_2: '',
    button_url_2: '',
})

// Initialize form data for edit mode
onMounted(() => {
    if (props.banner && props.action === 'edit') {
        const title = parseJsonField(props.banner.title)
        const subTitle = parseJsonField(props.banner.sub_title)
        const description = parseJsonField(props.banner.description)

        form.title_en = title.en || ''
        form.title_bn = title.bn || ''
        form.sub_title_en = subTitle.en || ''
        form.sub_title_bn = subTitle.bn || ''
        form.description_en = description.en || ''
        form.description_bn = description.bn || ''
        form.position = props.banner.position?.toString() || 'top'
        form.status = props.banner.status || 'active'
        form.type_id = props.banner.type_id || null
        form.button_text_1 = props.banner.button_text_1 || ''
        form.button_url_1 = props.banner.button_url_1 || ''
        form.button_text_2 = props.banner.button_text_2 || ''
        form.button_url_2 = props.banner.button_url_2 || ''
    }
})

const typeOptions = computed(() =>
    props.types.map(type => ({
        value: type.id,
        label: type.name_en
    }))
)

const positionOptions = [
    { value: 'top', label: 'Top' },
    { value: 'middle', label: 'Middle' },
    { value: 'bottom', label: 'Bottom' },
    { value: 'sidebar', label: 'Sidebar' }
]

const statusOptions = [
    { value: 'active', label: 'Active' },
    { value: 'inactive', label: 'Inactive' }
]

const formTitle = computed(() =>
    props.action === 'create' ? 'Create Banner' : 'Edit Banner'
)

const submitText = computed(() =>
    props.action === 'create' ? 'Create Banner' : 'Update Banner'
)

const backUrl = route('admin.banners.index')

const handleSubmit = () => {
    emit('submit', form)
}

const handleCancel = () => {
    emit('cancel')
}
</script>

<template>
    <FormContainer :title="formTitle" :back-url="backUrl" back-text="Back to Banners" :loading="form.processing"
        :submit-text="submitText" show-cancel max-width="4xl" @submit="handleSubmit" @cancel="handleCancel">

        <!-- Language Content Tabs -->
        <div class="col-span-full mb-4">
            <Tabs default-value="en" class="w-full">
                <TabsList class="grid w-full grid-cols-2">
                    <TabsTrigger value="en">English Content</TabsTrigger>
                    <TabsTrigger value="bn">Bengali Content</TabsTrigger>
                </TabsList>

                <TabsContent value="en" class="space-y-3 mt-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <VInputField v-model="form.title_en" label="Title EN" placeholder="Enter banner title"
                            :error-messages="form.errors.title_en" required />

                        <VInputField v-model="form.sub_title_en" label="Sub Title EN" placeholder="Enter sub title"
                            :error-messages="form.errors.sub_title_en" />
                    </div>

                    <VInputField v-model="form.description_en" label="Description EN" placeholder="Enter description"
                        :error-messages="form.errors.description_en" multiline rows="2" />
                </TabsContent>

                <TabsContent value="bn" class="space-y-3 mt-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <VInputField v-model="form.title_bn" label="Title BN" placeholder="Enter banner title"
                            :error-messages="form.errors.title_bn" />

                        <VInputField v-model="form.sub_title_bn" label="Sub Title BN" placeholder="Enter sub title"
                            :error-messages="form.errors.sub_title_bn" />
                    </div>

                    <VInputField v-model="form.description_bn" label="Description BN" placeholder="Enter description"
                        :error-messages="form.errors.description_bn" multiline rows="2" />
                </TabsContent>
            </Tabs>
        </div>

        <!-- Configuration Section -->
        <div class="col-span-full">
            <h3 class="text-lg font-medium mb-3">Banner Configuration</h3>
            <!-- add a divider -->
            <hr class="mb-4" />
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <FormSelect v-model="form.type_id" label="Banner Type" placeholder="Select banner type"
                    :options="typeOptions" :error-messages="form.errors.type_id" />

                <VInputField type="number" v-model="form.position" label="Position" placeholder="Select position"
                     :error-messages="form.errors.position" />

                <FormSelect v-model="form.status" label="Status" placeholder="Select status" :options="statusOptions"
                    :error-messages="form.errors.status" />
            </div>
        </div>

        <!-- Button Configuration -->
        <div class="col-span-full">
            <h3 class="text-lg font-medium mb-3">Button Configuration</h3>
            <hr class="mb-4" />
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <VInputField
                    v-model="form.button_text_1"
                    label="Button Text 1"
                    placeholder="Enter button text"
                    :error-messages="form.errors.button_text_1" />

                <VInputField
                    v-model="form.button_url_1"
                    label="Button URL 1"
                    placeholder="Enter button URL"
                    :error-messages="form.errors.button_url_1" />

                <VInputField
                    v-model="form.button_text_2"
                    label="Button Text 2"
                    placeholder="Enter second button text"
                    :error-messages="form.errors.button_text_2" />

                <VInputField
                    v-model="form.button_url_2"
                    label="Button URL 2"
                    placeholder="Enter second button URL"
                    :error-messages="form.errors.button_url_2" />
            </div>
        </div>

        <!-- Current Image Display (Edit Mode) -->
        <div v-if="action === 'edit' && banner?.image" class="col-span-full">
            <h3 class="text-lg font-medium mb-3">Current Image</h3>
            <img :src="banner.image" alt="Current banner" class="w-32 h-20 object-cover rounded-md border" />
        </div>

        <!-- Image Upload -->
        <div class="col-span-full">
            <FileDropzone v-model="form.image" :label="action === 'edit' ? 'Upload New Banner Image' : 'Banner Image'"
                accept="image/*" :error-messages="form.errors.image ? [form.errors.image] : []" />
        </div>
    </FormContainer>
</template>
