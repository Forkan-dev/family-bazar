<script setup lang="ts">
import { Button } from '@/components/ui/button'
import { FormSelect } from '@/components/ui/form-select'
import FileDropzone from '@/components/ui/FileDropzone.vue'
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs'
import VInputField from '@/components/VInputField.vue'
import MasterLayout from '@/layouts/MasterLayout.vue'
import { Head, useForm, Link } from '@inertiajs/vue3'
import { computed, watch } from 'vue'
import { ArrowLeft } from 'lucide-vue-next'

const route = (name: string, params?: any) => {
    return (window as any).route(name, params)
}

const props = defineProps<{
    categories: Array<{ id: number; title_en: string; title_bn: string }>;
}>();

const form = useForm({
    title_en: '',
    title_bn: '',
    slug: '',
    description: '',
    description_bn: '',
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

        <Head title="Create Category" />

        <!-- Header -->
        <div class="mb-8">
            <Link :href="route('product.categories.index')">
            <Button variant="ghost" size="sm">
                <ArrowLeft class="h-4 w-4 mr-2" />
                Back to Categories
            </Button>
            </Link>
        </div>

        <!-- Form -->
        <div class="max-w-4xl mx-auto">
            <div class="bg-card border rounded-lg">
                <div class="p-6 border-b">
                    <h1 class="text-2xl font-bold">Create Category</h1>
                </div>

                <form @submit.prevent="submit" class="p-6">
                    <div class="space-y-8">
                        <!-- Language Tabs -->
                        <Tabs default-value="en" class="space-y-6">
                            <TabsList class="grid w-full grid-cols-2 max-w-sm">
                                <TabsTrigger value="en">English</TabsTrigger>
                                <TabsTrigger value="bn">বাংলা</TabsTrigger>
                            </TabsList>

                            <TabsContent value="en" class="space-y-4">
                                <VInputField v-model="form.title_en" label="Category Title"
                                    placeholder="Enter category title" :error-messages="form.errors.title_en"
                                    required />

                                <VInputField v-model="form.description" label="Description"
                                    placeholder="Describe this category..." :error-messages="form.errors.description"
                                    multiline rows="3" />
                            </TabsContent>

                            <TabsContent value="bn" class="space-y-4">
                                <VInputField v-model="form.title_bn" label="ক্যাটেগরির নাম"
                                    placeholder="ক্যাটেগরির নাম বাংলায় লিখুন" :error-messages="form.errors.title_bn" />

                                <VInputField v-model="form.description_bn" label="বর্ণনা"
                                    placeholder="এই ক্যাটেগরির বর্ণনা লিখুন..."
                                    :error-messages="form.errors.description_bn" multiline rows="3" />
                            </TabsContent>
                        </Tabs>

                        <!-- Other Fields -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <VInputField v-model="form.slug" label="URL Slug" placeholder="category-slug"
                                :error-messages="form.errors.slug" required />

                            <FormSelect v-model="form.parent_id" label="Parent Category"
                                placeholder="Select parent category" :options="categoryOptions"
                                :error-messages="form.errors.parent_id" />
                        </div>

                        <!-- Image Upload -->
                        <FileDropzone v-model="form.image" label="Category Image" :error-messages="form.errors.image"
                            accept="image/*" />

                        <!-- Form Actions -->
                        <div class="flex justify-end space-x-3 pt-6 border-t">
                            <Link :href="route('product.categories.index')">
                            <Button type="button" variant="outline">
                                Cancel
                            </Button>
                            </Link>
                            <Button type="submit" :disabled="form.processing">
                                {{ form.processing ? 'Creating...' : 'Create Category' }}
                            </Button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </MasterLayout>
</template>
