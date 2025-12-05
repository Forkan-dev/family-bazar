<script setup lang="ts">
import { Button } from '@/components/ui/button'
import SearchableSelect from '@/components/ui/SearchableSelect.vue'
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
    category?: {
        id: number;
        title_en: string;
        title_bn: string;
        slug: string;
        description_en: string;
        description_bn: string;
        icon: string;
        image: string;
        parent_id: number | null;
    };
}>();

const form = useForm({
    title_en: props.category?.title_en || '',
    title_bn: props.category?.title_bn || '',
    slug: props.category?.slug || '',
    description_en: props.category?.description_en || '',
    description_bn: props.category?.description_bn || '',
    icon: props.category?.icon || '',
    image: null as File | null,
    parent_id: props.category?.parent_id || null,
});

const categorySearchUrl = computed(() =>
    route('product.categories.search')
)

const excludeCategoryId = computed(() =>
    props.category?.id || null
)

const submit = () => {
    if (props.category) {
        form.put(route('product.categories.update', props.category.id));
    } else {
        form.post(route('product.categories.store'));
    }
};

// Watch for changes in name to auto-generate slug
watch(
    () => form.title_en,
    (newName) => {
        if (!props.category) { // Only auto-generate slug for new categories
            form.slug = newName
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-|-$/g, '');
        }
    },
);
</script>

<template>
    <MasterLayout>

        <Head :title="category ? `Edit ${category.title_en}` : 'Create Category'" />

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
                    <h1 class="text-2xl font-bold">
                        {{ category ? 'Edit Category' : 'Create Category' }}
                    </h1>
                </div>

                <form @submit.prevent="submit" class="p-6">
                    <div class="space-y-8">
                        <!-- Language Tabs -->
                        <Tabs default-value="en" class="space-y-6">
                            <TabsList class="grid w-full grid-cols-2 max-w-sm">
                                <TabsTrigger value="en">English</TabsTrigger>
                                <TabsTrigger value="bn">Bengali</TabsTrigger>
                            </TabsList>

                            <TabsContent value="en" class="space-y-4">
                                <VInputField v-model="form.title_en" label="Name EN" placeholder="Enter category title"
                                    :error-messages="form.errors.title_en" required />

                                <VInputField v-model="form.description_en" label="Description EN"
                                    placeholder="Describe this category..." :error-messages="form.errors.description_en"
                                    multiline rows="3" />
                            </TabsContent>

                            <TabsContent value="bn" class="space-y-4">
                                <VInputField v-model="form.title_bn" label="Name BN"
                                    placeholder="Enter category name in Bengali"
                                    :error-messages="form.errors.title_bn" />

                                <VInputField v-model="form.description_bn" label="Description BN"
                                    placeholder="Enter category description in Bengali"
                                    :error-messages="form.errors.description_bn" multiline rows="3" />
                            </TabsContent>
                        </Tabs>

                        <!-- Other Fields -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <VInputField v-model="form.slug" label="URL Slug" placeholder="category-slug"
                                :error-messages="form.errors.slug" required />

                            <SearchableSelect v-model="form.parent_id" label="Parent Category"
                                placeholder="Search and select parent category..." :search-url="categorySearchUrl"
                                :exclude-id="excludeCategoryId" :error-messages="form.errors.parent_id" />
                        </div>

                        <!-- Image Upload -->
                        <FileDropzone v-model="form.image" label="Category Image" :error-messages="form.errors.image"
                            accept="image/*" />

                        <!-- Current Image (when editing) -->
                        <div v-if="category?.image" class="space-y-2">
                            <label class="text-sm font-medium">Current Image</label>
                            <img :src="`/${category.image}`" :alt="category.title_en"
                                class="w-32 h-32 object-cover rounded-lg border" />
                        </div>

                        <!-- Form Actions -->
                        <div class="flex justify-end space-x-3 pt-6 border-t">
                            <Link :href="route('product.categories.index')">
                            <Button type="button" variant="outline">
                                Cancel
                            </Button>
                            </Link>
                            <Button type="submit" :disabled="form.processing">
                                {{ form.processing
                                    ? (category ? 'Updating...' : 'Creating...')
                                    : (category ? 'Update Category' : 'Create Category')
                                }}
                            </Button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </MasterLayout>
</template>
