<script setup lang="ts">
import { FormContainer } from '@/components/ui/form-container'
import { FormSelect } from '@/components/ui/form-select'
import FileDropzone from '@/components/ui/FileDropzone.vue'
import VInputField from '@/components/VInputField.vue'
import MasterLayout from '@/layouts/MasterLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { computed, watch } from 'vue'

const props = defineProps<{
    categories: Array<{ id: number; title_en: string; title_bn: string }>;
}>();

const form = useForm({
    title_en: '',
    title_bn: '',
    slug: '',
    description: '',
    icon: '',
    image: null as File | null,
    parent_id: null as number | null,
});

const categoryOptions = computed(() => [
    { value: null, label: 'No Parent (Root Category)' },
    ...props.categories.map(category => ({
        value: category.id,
        label: `${category.title_en} (${category.title_bn})`
    }))
])

const submit = () => {
    form.post(route('product.categories.store'));
};

// Watch for changes in name to auto-generate slug
watch(
    () => form.title_en,
    (newName) => {
        form.slug = newName
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-|-$/g, '');
    },
);
</script>

<template>
    <MasterLayout>

        <Head title="Add Category" />



        <FormContainer title="Create Category" :back-url="route('product.categories.index')"
            back-text="Back to Categories" :loading="form.processing" submit-text="Create Category" show-cancel
            :grid-cols="2" max-width="3xl" @submit="submit" @cancel="$inertia.visit(route('product.categories.index'))">
            <VInputField v-model="form.title_en" label="Category Title (English)"
                placeholder="Enter category title in English" :error-messages="form.errors.title_en" required
                description="Main category name in English" />

            <VInputField v-model="form.title_bn" label="Category Title (Bengali)"
                placeholder="ক্যাটেগরির নাম বাংলায় লিখুন" :error-messages="form.errors.title_bn"
                description="Category name in Bengali (optional)" />

            <VInputField v-model="form.slug" label="URL Slug" placeholder="category-url-slug"
                :error-messages="form.errors.slug" required
                description="Auto-generated from English title, used in URLs" />

            <FormSelect v-model="form.parent_id" label="Parent Category" placeholder="Select parent category"
                :options="categoryOptions" :error-messages="form.errors.parent_id"
                description="Choose a parent category or leave empty for root category" />

            <VInputField v-model="form.description" label="Description" placeholder="Describe this category..."
                :error-messages="form.errors.description" multiline description="Optional description for this category"
                class="md:col-span-2" />

            <div class="md:col-span-2">
                <FileDropzone v-model="form.image" label="Category Image" :error-messages="form.errors.image"
                    accept="image/*" />
            </div>
        </FormContainer>
    </MasterLayout>
</template>
