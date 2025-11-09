<script setup lang="ts">
import MasterLayout from '@/layouts/MasterLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, watch, computed, onMounted, reactive } from 'vue';
import VInputField from '@/components/VInputField.vue';
import { Link } from '@inertiajs/vue3';
import { VButton } from '@/components/ui/button';
import VFileInput from '@/components/ui/VFileInput.vue';
import axios from 'axios';

const props = defineProps({
    product: Object,
    categories: Array,
    tags: Array,
    units: Array,
    brands: Array,
});

const documents = reactive(props.product?.documents || []);

const parent_category_id = ref(null);
const subCategories = ref([]);

const form = useForm({
    name_en: props.product?.name_en || '',
    name_bn: props.product?.name_bn || '',
    slug: props.product?.slug || '',
    description: props.product?.description || '',
    price: props.product?.price || 0,
    stock_quantity: props.product?.stock_quantity || 0,
    images: [] as File[],
    category_id: props.product?.category_id || null,
    unit_id: props.product?.unit_id || null,
    brand_id: props.product?.brand_id || null,
    quantity: props.product?.quantity || 0,
    tags: props.product?.tags || [],
});

form.transform(data => {
    const transformedData = {
        ...data,
        tags: data.tags.map(tag => {
            if (typeof tag === 'object' && tag !== null && tag.id) {
                return tag.id;
            }
            return tag;
        }),
        images: data.images.length > 0 ? data.images : undefined,
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
</script>

<template>
    <MasterLayout>

        <Head :title="form.name_en ? form.name_en : 'Create Product'" />
        <v-container>
            <v-row>
                <v-col cols="12">
                    <Link :href="route('product.products.index')" class="mb-4 d-inline-block">
                    <v-icon color="primary">mdi-arrow-left</v-icon>
                    </Link>
                    <v-card>
                        <v-card-title class="d-flex align-center">
                            <v-icon class="mr-3" color="primary">
                                {{ props.product ? 'mdi-pencil' : 'mdi-plus' }}
                            </v-icon>
                            {{ props.product ? 'Edit Product' : 'Create Product' }}
                        </v-card-title>
                        <v-divider></v-divider>
                        <v-card-text class="mt-5">
                            <v-row>
                                <!-- LEFT COLUMN (8/12) - Main Form Content -->
                                <v-col cols="12" md="8">

                                    <!-- Basic Information Section -->
                                    <div class="form-section">
                                        <div class="form-section-title">
                                            <v-icon>mdi-information</v-icon>
                                            Basic Information
                                        </div>
                                        <v-row dense>
                                            <v-col cols="12" sm="6">
                                                <VInputField v-model="form.name_en" label="Title (English)"
                                                    :error-messages="form.errors.name_en" required density="compact"
                                                    variant="outlined" />
                                            </v-col>
                                            <v-col cols="12" sm="6">
                                                <VInputField v-model="form.name_bn" label="Title (Bengali)"
                                                    :error-messages="form.errors.name_bn" density="compact"
                                                    variant="outlined" />
                                            </v-col>
                                        </v-row>
                                        <v-row dense>
                                            <v-col cols="12">
                                                <VInputField v-model="form.slug" label="URL Slug"
                                                    :error-messages="form.errors.slug" required density="compact"
                                                    variant="outlined" hint="Auto-generated from English title" />
                                            </v-col>
                                        </v-row>
                                        <v-row dense>
                                            <v-col cols="12">
                                                <VInputField v-model="form.description" label="Description"
                                                    :error-messages="form.errors.description" multiline
                                                    density="compact" variant="outlined" rows="3" />
                                            </v-col>
                                        </v-row>
                                    </div>

                                    <!-- Pricing & Inventory Section -->
                                    <div class="form-section">
                                        <div class="form-section-title">
                                            <v-icon>mdi-currency-usd</v-icon>
                                            Pricing & Inventory
                                        </div>
                                        <v-row dense>
                                            <v-col cols="12" sm="6">
                                                <VInputField v-model="form.price" label="Price" type="number"
                                                    step="0.01" :error-messages="form.errors.price" required
                                                    density="compact" variant="outlined"
                                                    prepend-inner-icon="mdi-currency-bdt" />
                                            </v-col>

                                            <v-col cols="12" sm="6">
                                                <VInputField v-model="form.stock_quantity" label="Stock Quantity"
                                                    type="number" :error-messages="form.errors.stock_quantity" required
                                                    density="compact" variant="outlined" />
                                            </v-col>
                                        </v-row>
                                        <v-row dense>
                                            <v-col cols="12" sm="4">
                                                <v-select v-model="form.unit_id" :items="units" item-title="displayName"
                                                    item-value="id" label="Unit" variant="outlined" density="compact"
                                                    :error-messages="form.errors.unit_id"
                                                    prepend-inner-icon="mdi-weight" />
                                            </v-col>

                                            <v-col cols="12" sm="4">
                                                <VInputField v-model.number="form.quantity" label="Quantity"
                                                    type="number" :error-messages="form.errors.quantity" required
                                                    density="compact" variant="outlined" />
                                            </v-col>

                                            <v-col cols="12" sm="4">
                                                <v-select v-model="form.brand_id" :items="brands" item-title="en_name"
                                                    item-value="id" label="Brand" variant="outlined" density="compact"
                                                    :error-messages="form.errors.brand_id"
                                                    prepend-inner-icon="mdi-tag" />
                                            </v-col>
                                        </v-row>
                                    </div>

                                    <!-- Media Section -->
                                    <div class="form-section">
                                        <div class="form-section-title">
                                            <v-icon>mdi-image</v-icon>
                                            Product Images
                                        </div>
                                        <v-row dense>
                                            <v-col cols="12">
                                                <VFileInput v-model="form.images" title="Upload Product Images"
                                                    variant="outlined" density="compact"
                                                    :error-messages="form.errors.images" accept="image/*"
                                                    prepend-icon="" prepend-inner-icon="mdi-camera" multiple />
                                            </v-col>
                                        </v-row>
                                        <v-row dense v-if="documents.length > 0">
                                            <v-col cols="12">
                                                <div class="text-caption mb-2">Current Images</div>
                                            </v-col>
                                            <v-col v-for="(image, index) in documents" :key="image.id" cols="6" sm="4"
                                                md="3">
                                                <v-card class="position-relative">
                                                    <v-img :src="image.url" height="100" class="rounded" />
                                                    <v-btn icon="mdi-close" size="x-small" color="red"
                                                        class="position-absolute" style="top: 4px; right: 4px;"
                                                        @click="removeImage(image.id, index)"></v-btn>
                                                </v-card>
                                            </v-col>
                                        </v-row>
                                    </div>

                                </v-col>

                                <!-- RIGHT COLUMN (4/12) - Sidebar Content -->
                                <v-col cols="12" md="4">

                                    <!-- Categorization Section -->
                                    <div class="form-section">
                                        <div class="form-section-title">
                                            <v-icon>mdi-tag</v-icon>
                                            Categories & Tags
                                        </div>
                                        <v-row dense>
                                            <v-col cols="12">
                                                <v-select v-model="parent_category_id" :items="categories"
                                                    item-title="displayName" item-value="id" label="Category"
                                                    variant="outlined" density="compact"
                                                    :error-messages="form.errors.category_id"
                                                    prepend-inner-icon="mdi-folder" />
                                            </v-col>
                                            <v-col cols="12" v-if="subCategories.length > 0">
                                                <v-select v-model="form.category_id" :items="subCategories"
                                                    item-title="displayName" item-value="id" label="Sub Category"
                                                    variant="outlined" density="compact"
                                                    :error-messages="form.errors.category_id"
                                                    prepend-inner-icon="mdi-folder-outline" />
                                            </v-col>
                                            <v-col cols="12">
                                                <v-combobox v-model="form.tags" :items="tags" item-title="name"
                                                    item-value="id" label="Tags" multiple chips variant="outlined"
                                                    density="compact" :error-messages="form.errors.tags"
                                                    prepend-inner-icon="mdi-tag-multiple" closable-chips />
                                            </v-col>
                                        </v-row>
                                    </div>

                                </v-col>
                            </v-row>
                        </v-card-text>

                        <!-- Form Actions - Card Footer -->
                        <v-divider></v-divider>
                        <v-card-actions class="pa-4">
                            <v-form @submit.prevent="submit">
                                <div class="d-flex gap-3">
                                    <VButton type="submit" :disabled="form.processing" :loading="form.processing">
                                        <v-icon left>
                                            {{ props.product ? 'mdi-content-save' : 'mdi-plus' }}
                                        </v-icon>
                                        {{ props.product ? 'Update Product' : 'Create Product' }}
                                    </VButton>

                                    <Link :href="route('product.products.index')">
                                    <VButton variant="outlined" size="large" class="px-6">
                                        <v-icon left class="mr-2">mdi-close</v-icon>
                                        Cancel
                                    </VButton>
                                    </Link>
                                </div>
                            </v-form>
                        </v-card-actions>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </MasterLayout>
</template>
