<script setup lang="ts">
import MasterLayout from '@/layouts/MasterLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, watch, computed, onMounted } from 'vue';
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
});

const parent_category_id = ref(null);
const subCategories = ref([]);

const categoryOptions = computed(() => {
    return props.categories.map((category) => ({
        ...category,
        displayName: `${category.title_en} (${category.title_bn})`,
    }));
});

const form = useForm({
    title_en: props.product?.title_en || '',
    title_bn: props.product?.title_bn || '',
    slug: props.product?.slug || '',
    description: props.product?.description || '',
    price: props.product?.price || 0,
    stock_quantity: props.product?.stock_quantity || 0,
    image: null as File | null,
    category_id: props.product?.category_id || null,
    unit_id: props.product?.unit_id || null,
    quantity: props.product?.quantity || 0,
    tags: props.product?.tags?.map((t: any) => t.id) || [],
});

form.transform(data => ({
    ...data,
    image: data.image || undefined,
}));

const submit = () => {
    if (props.product) {
        form.put(route('products.update', props.product.id));
    } else {
        form.post(route('products.store'));
    }
};

const fetchSubCategories = async (parentId) => {
    if (parentId) {
        try {
            const response = await axios.get(route('categories.subcategories', parentId));
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

// Watch for changes in name to auto-generate slug
watch(() => form.title_en, (newName) => {
    if (!props.product) { // Only auto-generate for new products
        form.slug = newName.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
    }
});
</script>

<template>
    <MasterLayout>
        <Head :title="form.title_en ? form.title_en : 'Create Product'" />
        <v-container>
            <v-row>
                <v-col cols="12">
                    <Link :href="route('products.index')" class="mb-4 d-inline-block">
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
                            <v-form @submit.prevent="submit">

                                <!-- Basic Information Section -->
                                <div class="form-section">
                                    <div class="form-section-title">
                                        <v-icon>mdi-information</v-icon>
                                        Basic Information
                                    </div>
                                    <v-row dense>
                                        <v-col cols="12" md="6">
                                            <VInputField
                                                v-model="form.title_en"
                                                label="Title (English)"
                                                :error-messages="form.errors.title_en"
                                                required
                                                density="compact"
                                                variant="outlined"
                                            />
                                        </v-col>
                                        <v-col cols="12" md="6">
                                            <VInputField
                                                v-model="form.title_bn"
                                                label="Title (Bengali)"
                                                :error-messages="form.errors.title_bn"
                                                density="compact"
                                                variant="outlined"
                                            />
                                        </v-col>
                                    </v-row>
                                    <v-row dense>
                                        <v-col cols="12">
                                            <VInputField
                                                v-model="form.slug"
                                                label="URL Slug"
                                                :error-messages="form.errors.slug"
                                                required
                                                density="compact"
                                                variant="outlined"
                                                hint="Auto-generated from English title"
                                            />
                                        </v-col>
                                    </v-row>
                                    <v-row dense>
                                        <v-col cols="12">
                                            <VInputField
                                                v-model="form.description"
                                                label="Description"
                                                :error-messages="form.errors.description"
                                                multiline
                                                density="compact"
                                                variant="outlined"
                                                rows="3"
                                            />
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
                                        <v-col cols="12" md="4">
                                            <VInputField
                                                v-model="form.price"
                                                label="Price"
                                                type="number"
                                                step="0.01"
                                                :error-messages="form.errors.price"
                                                required
                                                density="compact"
                                                variant="outlined"
                                                prepend-inner-icon="mdi-currency-bdt"
                                            />
                                        </v-col>
                                        <v-col cols="12" md="4">
                                            <VInputField
                                                v-model="form.stock_quantity"
                                                label="Stock Quantity"
                                                type="number"
                                                :error-messages="form.errors.stock_quantity"
                                                required
                                                density="compact"
                                                variant="outlined"
                                            />
                                        </v-col>
                                        <v-col cols="12" md="4">
                                            <VInputField
                                                v-model.number="form.quantity"
                                                label="Package Quantity"
                                                type="number"
                                                :error-messages="form.errors.quantity"
                                                required
                                                density="compact"
                                                variant="outlined"
                                            />
                                        </v-col>
                                    </v-row>
                                    <v-row dense>
                                        <v-col cols="12" md="6">
                                            <v-select
                                                v-model="form.unit_id"
                                                :items="units"
                                                item-title="name"
                                                item-value="id"
                                                label="Unit"
                                                variant="outlined"
                                                density="compact"
                                                :error-messages="form.errors.unit_id"
                                                prepend-inner-icon="mdi-weight"
                                            />
                                        </v-col>
                                    </v-row>
                                </div>

                                <!-- Media Section -->
                                <div class="form-section">
                                    <div class="form-section-title">
                                        <v-icon>mdi-image</v-icon>
                                        Product Image
                                    </div>
                                    <v-row dense>
                                        <v-col cols="12" md="8">
                                            <VFileInput
                                                v-model="form.image"
                                                title="Upload Product Image"
                                                variant="outlined"
                                                density="compact"
                                                :error-messages="form.errors.image"
                                                accept="image/*"
                                                prepend-icon=""
                                                prepend-inner-icon="mdi-camera"
                                            />
                                        </v-col>
                                        <v-col cols="12" md="4" v-if="product?.image_url">
                                            <div class="text-caption mb-2">Current Image</div>
                                            <v-img
                                                :src="product.image_url"
                                                height="100"
                                                max-width="150"
                                                class="rounded"
                                            />
                                        </v-col>
                                    </v-row>
                                </div>

                                <!-- Categorization Section -->
                                <div class="form-section">
                                    <div class="form-section-title">
                                        <v-icon>mdi-tag</v-icon>
                                        Categories & Tags
                                    </div>
                                    <v-row dense>
                                        <v-col cols="12" md="6">
                                            <v-select
                                                v-model="parent_category_id"
                                                :items="categoryOptions"
                                                item-title="displayName"
                                                item-value="id"
                                                label="Parent Category"
                                                variant="outlined"
                                                density="compact"
                                                :error-messages="form.errors.category_id"
                                                prepend-inner-icon="mdi-folder"
                                            />
                                        </v-col>
                                        <v-col cols="12" md="6" v-if="subCategories.length > 0">
                                            <v-select
                                                v-model="form.category_id"
                                                :items="subCategories"
                                                item-title="title_en"
                                                item-value="id"
                                                label="Sub Category"
                                                variant="outlined"
                                                density="compact"
                                                :error-messages="form.errors.category_id"
                                                prepend-inner-icon="mdi-folder-outline"
                                            />
                                        </v-col>
                                    </v-row>
                                    <v-row dense>
                                        <v-col cols="12">
                                            <v-select
                                                v-model="form.tags"
                                                :items="tags"
                                                item-title="name"
                                                item-value="id"
                                                label="Tags"
                                                multiple
                                                chips
                                                variant="outlined"
                                                density="compact"
                                                :error-messages="form.errors.tags"
                                                prepend-inner-icon="mdi-tag-multiple"
                                                closable-chips
                                            />
                                        </v-col>
                                    </v-row>
                                </div>

                                <!-- Form Actions -->
                                <div class="d-flex gap-3 mt-6">
                                    <VButton
                                        type="submit"
                                        :disabled="form.processing"
                                        :loading="form.processing"
                                        size="large"
                                        color="primary"
                                        class="px-8"
                                    >
                                        <v-icon left class="mr-2">
                                            {{ props.product ? 'mdi-content-save' : 'mdi-plus' }}
                                        </v-icon>
                                        {{ props.product ? 'Update Product' : 'Create Product' }}
                                    </VButton>

                                    <Link :href="route('products.index')">
                                        <VButton
                                            variant="outlined"
                                            size="large"
                                            class="px-6"
                                        >
                                            <v-icon left class="mr-2">mdi-close</v-icon>
                                            Cancel
                                        </VButton>
                                    </Link>
                                </div>
                            </v-form>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </MasterLayout>
</template>
