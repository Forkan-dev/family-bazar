<script setup lang="ts">
import { FormContainer } from '@/components/ui/form-container'
import { FormSelect } from '@/components/ui/form-select'
import FileDropzone from '@/components/ui/FileDropzone.vue'
import MultiSelectInput from '@/components/ui/MultiSelectInput.vue'
import VInputField from '@/components/VInputField.vue'
import MasterLayout from '@/layouts/MasterLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { ref, watch, onMounted, reactive, computed } from 'vue'
import axios from 'axios'
import { Button } from '@/components/ui/button'
import { ArrowLeft, X } from 'lucide-vue-next'
import { Link } from '@inertiajs/vue3'

const route = (name: string, params?: any) => {
    return (window as any).route(name, params)
}

const props = defineProps<{
    product?: any
    categories: any[]
    tags: any[]
    units: any[]
    brands: any[]
}>()

const documents = reactive(props.product?.documents || []);
const availableTags = ref([...props.tags]);

const parent_category_id = ref(null);
const subCategories = ref([]);

const form = useForm({
    name_en: props.product?.name_en || '',
    name_bn: props.product?.name_bn || '',
    slug: props.product?.slug || '',
    description: props.product?.description || '',
    price: props.product?.price || 0,
    stock_quantity: props.product?.stock_quantity || 0,
    images: null as File | null,
    category_id: props.product?.category_id || null,
    unit_id: props.product?.unit_id || null,
    brand_id: props.product?.brand_id || null,
    quantity: props.product?.quantity || 0,
    tags: props.product?.tags || [],
});

form.transform((data: any) => {
    const transformedData: any = {
        ...data,
        tags: data.tags.map((tag: any) => {
            if (typeof tag === 'object' && tag !== null && tag.id) {
                return tag.id;
            }
            return tag;
        }),
        images: data.images ? data.images : undefined,
    };

    if (props.product) {
        transformedData._method = 'PUT';
    }

    return transformedData;
});

const submit = () => {
    if (props.product) {
        form.post(route('product.products.update', props.product.id));
    } else {
        form.post(route('product.products.store'));
    }
};

const fetchSubCategories = async (parentId) => {
    if (parentId) {
        try {
            const response = await axios.get(route('product.categories.subcategories', parentId));
            subCategories.value = response.data;
        } catch (error) {
            console.error('Error fetching subcategories:', error);
        }
    }
};

watch(parent_category_id, (newVal) => {
    form.category_id = null; // Reset sub-category when parent changes
    subCategories.value = [];
    fetchSubCategories(newVal);
});

// Set default parent category to 'Grocery' if it exists
onMounted(() => {
    const groceryCategory = props.categories.find(c => c.title_en === 'Grocery');
    if (groceryCategory) {
        parent_category_id.value = groceryCategory.id;
    }

    if (props.product?.category) {
        if (props.product.category.parent_id) {
            parent_category_id.value = props.product.category.parent_id;
            fetchSubCategories(props.product.category.parent_id).then(() => {
                form.category_id = props.product.category_id;
            });
        } else {
            parent_category_id.value = props.product.category_id;
            form.category_id = null;
        }
    }
});

const removeImage = async (id: number, index: number) => {
    if (!confirm('Are you sure you want to delete this image?')) {
        return;
    }

    try {
        await axios.delete(route('documents.destroy', id));
        documents.splice(index, 1);
    } catch (error) {
        console.error('Error deleting image:', error);
    }
};

// Watch for changes in name to auto-generate slug
watch(() => form.name_en, (newName) => {
    if (!props.product) { // Only auto-generate for new products
        form.slug = newName.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
    }
});

// Handle tag creation
const handleCreateTag = (tagName: string) => {
    // Create new tag with unique ID
    const newTagId = `new-${Date.now()}`;
    const newTag = { id: newTagId, name: tagName };

    // Add new tag to available options
    availableTags.value.push(newTag);

    // Add to form tags
    form.tags = [...form.tags, newTagId];
};// Computed options for selects
const categoryOptions = computed(() =>
    props.categories.map(category => ({
        value: category.id,
        label: category.displayName || `${category.title_en} (${category.title_bn})`
    }))
)

const subCategoryOptions = computed(() =>
    subCategories.value.map(category => ({
        value: category.id,
        label: category.displayName || `${category.title_en} (${category.title_bn})`
    }))
)

const unitOptions = computed(() =>
    props.units.map(unit => ({
        value: unit.id,
        label: unit.displayName || unit.name
    }))
)

const brandOptions = computed(() =>
    props.brands.map(brand => ({
        value: brand.id,
        label: brand.en_name
    }))
)

const tagOptions = computed(() =>
    availableTags.value.map(tag => ({
        value: tag.id,
        label: tag.name
    }))
)
</script>

<template>
    <MasterLayout>

        <Head :title="props.product ? `Edit ${form.name_en}` : 'Create Product'" />

        <!-- Header -->
        <div class="mb-8">
            <Link :href="route('product.products.index')">
            <Button variant="ghost" size="sm">
                <ArrowLeft class="h-4 w-4 mr-2" />
                Back to Products
            </Button>
            </Link>
        </div>

        <!-- Form Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Form -->
            <div class="lg:col-span-2">
                <div class="bg-card border rounded-lg">
                    <div class="p-6 border-b">
                        <h1 class="text-2xl font-bold">{{ props.product ? 'Edit Product' : 'Create Product' }}</h1>
                    </div>

                    <div class="p-6">
                        <form @submit.prevent="submit" class="space-y-6">
                            <VInputField v-model="form.name_en" label="Product Name" placeholder="Enter product name"
                                :error-messages="form.errors.name_en" required />

                            <VInputField v-model="form.name_bn" label="Product Name (Bengali)"
                                placeholder="পণ্যের নাম বাংলায়" :error-messages="form.errors.name_bn" />

                            <VInputField v-model="form.slug" label="URL Slug" placeholder="product-url-slug"
                                :error-messages="form.errors.slug" required />

                            <VInputField v-model="form.description" label="Description"
                                placeholder="Enter product description..." :error-messages="form.errors.description"
                                multiline rows="3" />

                            <div class="grid grid-cols-2 gap-4">
                                <VInputField v-model="form.price" label="Price (৳)" type="number" step="0.01"
                                    placeholder="0.00" :error-messages="form.errors.price" required />

                                <VInputField v-model="form.stock_quantity" label="Stock Quantity" type="number"
                                    placeholder="0" :error-messages="form.errors.stock_quantity" required />
                            </div>

                            <VInputField v-model="form.quantity" label="Package Quantity" type="number" placeholder="1"
                                :error-messages="form.errors.quantity" required />

                            <FileDropzone v-model="form.images" label="Product Images" accept="image/*"
                                :error-messages="form.errors.images" />

                            <!-- Current Images -->
                            <div v-if="documents.length > 0" class="md:col-span-3">
                                <label class="text-sm font-medium mb-2 block">Current Images</label>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <div v-for="(image, index) in documents" :key="image.id" class="relative group">
                                        <img :src="image.url" :alt="`Product image ${index + 1}`"
                                            class="w-full h-24 object-cover rounded-lg border" />
                                        <Button variant="destructive" size="sm"
                                            class="absolute -top-2 -right-2 h-6 w-6 p-0 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"
                                            @click="removeImage(image.id, index)">
                                            <X class="h-3 w-3" />
                                        </Button>
                                    </div>
                                </div>
                            </div>
                            <!-- Current Images -->
                            <div v-if="documents.length > 0">
                                <label class="text-sm font-medium mb-2 block">Current Images</label>
                                <div class="grid grid-cols-3 gap-3">
                                    <div v-for="(image, index) in documents" :key="image.id" class="relative group">
                                        <img :src="image.url" :alt="`Product image ${index + 1}`"
                                            class="w-full h-20 object-cover rounded border" />
                                        <Button variant="destructive" size="sm"
                                            class="absolute -top-1 -right-1 h-5 w-5 p-0 rounded-full opacity-0 group-hover:opacity-100"
                                            @click="removeImage(image.id, index)">
                                            <X class="h-3 w-3" />
                                        </Button>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end space-x-3 pt-6 border-t">
                                <Link :href="route('product.products.index')">
                                <Button type="button" variant="outline">
                                    Cancel
                                </Button>
                                </Link>
                                <Button type="submit" :disabled="form.processing">
                                    {{ form.processing ? (props.product ? 'Updating...' : 'Creating...') :
                                        (props.product ? 'Update Product' : 'Create Product') }}
                                </Button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Categories -->
                <div class="bg-card border rounded-lg p-4">
                    <h3 class="font-semibold mb-4">Categories</h3>
                    <div class="space-y-4">
                        <FormSelect v-model="parent_category_id" label="Main Category" placeholder="Select category"
                            :options="categoryOptions" :error-messages="form.errors.category_id" />

                        <FormSelect v-if="subCategories.length > 0" v-model="form.category_id" label="Sub Category"
                            placeholder="Select sub category" :options="subCategoryOptions"
                            :error-messages="form.errors.category_id" />
                    </div>
                </div>

                <!-- Attributes -->
                <div class="bg-card border rounded-lg p-4">
                    <h3 class="font-semibold mb-4">Attributes</h3>
                    <div class="space-y-4">
                        <FormSelect v-model="form.unit_id" label="Unit" placeholder="Select unit" :options="unitOptions"
                            :error-messages="form.errors.unit_id" />

                        <FormSelect v-model="form.brand_id" label="Brand" placeholder="Select brand"
                            :options="brandOptions" :error-messages="form.errors.brand_id" />
                    </div>
                </div>

                <!-- Tags -->
                <div class="bg-card border rounded-lg p-4">
                    <h3 class="font-semibold mb-4">Tags</h3>
                    <MultiSelectInput v-model="form.tags" :options="tagOptions" label="Product Tags"
                        placeholder="Select or create tags..." :allow-create="true" create-text="Create tag"
                        :error-messages="form.errors.tags" @create="handleCreateTag" />
                </div>
            </div>
        </div>
    </MasterLayout>
</template>
