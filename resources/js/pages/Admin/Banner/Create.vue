<script setup lang="ts">
import { FormContainer } from '@/components/ui/form-container'
import { FormSelect } from '@/components/ui/form-select'
import FileDropzone from '@/components/ui/FileDropzone.vue'
import VInputField from '@/components/VInputField.vue'
import MasterLayout from '@/layouts/MasterLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'

const props = defineProps<{
    types: Array<{ id: number; name: string }>;
}>();

const form = useForm({
    title_en: '',
    title_bn: '',
    sub_title_en: '',
    sub_title_bn: '',
    description_en: '',
    description_bn: '',
    position: 'top',
    status: 'active',
    image: null as File | null,
    type_id: null,
    button_text_1: '',
    button_url_1: '',
    button_text_2: '',
    button_url_2: '',
});

const route = (name: string) => {
    return (window as any).route(name)
}

const submit = () => {
    form.post(route('admin.banners.store'));
};

const typeOptions = props.types.map(type => ({
    value: type.id,
    label: type.name
}))

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
</script>

<template>
    <MasterLayout>

        <Head title="Create Banner" />

        <FormContainer title="Create Banner" :back-url="route('admin.banners.index')" back-text="Back to Banners"
            :loading="form.processing" submit-text="Create Banner" show-cancel :grid-cols="2" max-width="4xl"
            @submit="submit" @cancel="$inertia.visit(route('admin.banners.index'))">
            <VInputField v-model="form.title_en" label="Title (English)" placeholder="Enter banner title in English"
                :error-messages="form.errors.title_en" required />

            <VInputField v-model="form.title_bn" label="Title (Bengali)" placeholder="ব্যানার শিরোনাম বাংলায়"
                :error-messages="form.errors.title_bn" />

            <VInputField v-model="form.sub_title_en" label="Sub Title (English)"
                placeholder="Enter sub title in English" :error-messages="form.errors.sub_title_en" />

            <VInputField v-model="form.sub_title_bn" label="Sub Title (Bengali)" placeholder="উপ শিরোনাম বাংলায়"
                :error-messages="form.errors.sub_title_bn" />

            <div class="md:col-span-2">
                <VInputField v-model="form.description_en" label="Description (English)"
                    placeholder="Enter description in English" :error-messages="form.errors.description_en" multiline
                    rows="3" />
            </div>

            <div class="md:col-span-2">
                <VInputField v-model="form.description_bn" label="Description (Bengali)"
                    placeholder="বিবরণ বাংলায় লিখুন" :error-messages="form.errors.description_bn" multiline rows="3" />
            </div>

            <FormSelect v-model="form.type_id" label="Banner Type" placeholder="Select banner type"
                :options="typeOptions" :error-messages="form.errors.type_id" />

            <FormSelect v-model="form.position" label="Position" placeholder="Select position"
                :options="positionOptions" :error-messages="form.errors.position" />

            <FormSelect v-model="form.status" label="Status" placeholder="Select status" :options="statusOptions"
                :error-messages="form.errors.status" />

            <VInputField v-model="form.button_text_1" label="Button Text 1" placeholder="Enter button text"
                :error-messages="form.errors.button_text_1" />

            <VInputField v-model="form.button_url_1" label="Button URL 1" placeholder="Enter button URL"
                :error-messages="form.errors.button_url_1" />

            <VInputField v-model="form.button_text_2" label="Button Text 2" placeholder="Enter second button text"
                :error-messages="form.errors.button_text_2" />

            <VInputField v-model="form.button_url_2" label="Button URL 2" placeholder="Enter second button URL"
                :error-messages="form.errors.button_url_2" />

            <div class="md:col-span-2">
                <FileDropzone v-model="form.image" label="Banner Image" accept="image/*"
                    :error-messages="form.errors.image" />
            </div>
        </FormContainer>
    </MasterLayout>
</template>
