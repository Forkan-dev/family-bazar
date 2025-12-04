<script setup lang="ts">
import { FormContainer } from '@/components/ui/form-container'
import FileDropzone from '@/components/ui/FileDropzone.vue'
import VInputField from '@/components/VInputField.vue'
import MasterLayout from '@/layouts/MasterLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { watch } from 'vue'

const route = (name: string) => {
    return (window as any).route(name)
}

const form = useForm({
    en_name: '',
    bn_name: '',
    slug: '',
    image: null as File | null,
});

const submit = () => {
    form.post(route('product.brands.store'));
};

// Watch for changes in name to auto-generate slug
watch(() => form.en_name, (newName) => {
    form.slug = newName.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
});

</script>

<template>
    <MasterLayout>

        <Head title="Create Brand" />

        <FormContainer title="Create Brand" :back-url="route('product.brands.index')" back-text="Back"
            :loading="form.processing" submit-text="Create Brand" show-cancel @submit="submit"
            @cancel="$inertia.visit(route('product.brands.index'))">
            <VInputField v-model="form.en_name" label="Brand Name" placeholder="Enter brand name"
                :error-messages="form.errors.en_name" required />

            <VInputField v-model="form.bn_name" label="Brand Name (Bengali)" placeholder="ব্র্যান্ডের নাম"
                :error-messages="form.errors.bn_name" />

            <VInputField v-model="form.slug" label="URL Slug" placeholder="brand-url-slug"
                :error-messages="form.errors.slug" required />

            <FileDropzone v-model="form.image" label="Brand Logo" accept="image/*"
                :error-messages="form.errors.image" />
        </FormContainer>
    </MasterLayout>
</template>
