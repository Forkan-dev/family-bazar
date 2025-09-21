<script setup lang="ts">
import MasterLayout from '@/layouts/MasterLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';
import VInputField from '@/components/VInputField.vue';
import { Link } from '@inertiajs/vue3';
import { VButton } from '@/components/ui/button';
import VFileInput from '@/components/ui/VFileInput.vue';
import singleSearchSelect from '@/components/ui/singleSearchSelect.vue';
import multiSearchTagSelect from '@/components/ui/multiSearchTagSelect.vue';

const props = defineProps({
    product: Object,
    categories: Array,
    tags: Array,
});

const categoryOptions = computed(() => {
    return props.categories.map((category) => ({
        ...category,
        displayName: `${category.title_en} (${category.title_bn})`,
    }));
});

const form = useForm({
    name: props.product?.name || '',
    slug: props.product?.slug || '',
    description: props.product?.description || '',
    price: props.product?.price || 0,
    stock: props.product?.stock || 0,
    image: null as File | null,
    category_id: props.product?.category_id || null,
    tags: props.product?.tags.map((t: any) => t.id) || [],
});

form.transform(data => ({
    ...data,
    image: data.image || undefined,
}));

const submit = () => {
    if (props.product) {
        form.post(route('products.update', props.product.id), {
            _method: 'put',
        });
    } else {
        form.post(route('products.store'));
    }
};

// Watch for changes in name to auto-generate slug
watch(() => form.name, (newName) => {
    if (!props.product) { // Only auto-generate for new products
        form.slug = newName.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
    }
});
</script>

<template>
    <MasterLayout>

        <Head :title="form.name ? form.name : 'Create Product'" />

        <v-container>
            <v-row>
                <v-col cols="12">
                    <Link :href="route('products.index')" class="mb-4 d-inline-block">
                    <v-icon color="primary">mdi-arrow-left</v-icon>
                    </Link>
                    <v-card>
                        <v-card-title class="d-flex align-center">
                            {{ props.product ? 'Edit Product' : 'Create Product' }}
                        </v-card-title>
                        <v-divider></v-divider>
                        <v-card-text>
                            <v-form @submit.prevent="submit">
                                <v-row dense>
                                    <v-col cols="12" md="6">
                                        <VInputField v-model="form.name" label="Product Name"
                                            :error-messages="form.errors.name" required></VInputField>
                                    </v-col>
                                    <v-col cols="12" md="6">
                                        <VInputField v-model="form.slug" label="Slug"
                                            :error-messages="form.errors.slug" required></VInputField>
                                    </v-col>
                                </v-row>

                                <v-row dense>
                                    <v-col cols="12">
                                        <VInputField v-model="form.description" label="Description"
                                            :error-messages="form.errors.description" multiline></VInputField>
                                    </v-col>
                                </v-row>

                                <v-row dense>
                                    <v-col cols="12" md="6">
                                        <VInputField v-model="form.price" label="Price" type="number" step="0.01"
                                            :error-messages="form.errors.price" required></VInputField>
                                    </v-col>
                                    <v-col cols="12" md="6">
                                        <VInputField v-model="form.stock" label="Stock" type="number"
                                            :error-messages="form.errors.stock" required></VInputField>
                                    </v-col>
                                </v-row>

                                <v-row dense>
                                    <v-col cols="12">
                                        <VFileInput
                                            v-model="form.image"
                                            title="Product Image"
                                            variant="outlined"
                                            :error-messages="form.errors.image"
                                            accept="image/*"
                                        ></VFileInput>
                                        <v-img
                                            v-if="product?.image_url"
                                            :src="product.image_url"
                                            height="100"
                                            class="mt-2"
                                        ></v-img>
                                    </v-col>
                                </v-row>

                                <v-row dense>
                                    <v-col cols="12" md="6">
                                        <v-select v-model="form.category_id" :items="categoryOptions" item-title="displayName"
                                            item-value="id" label="Category" variant="outlined"
                                            density="compact" :error-messages="form.errors.category_id"></v-select>
                                    </v-col>
                                    <v-col cols="12" md="6">
                                        <v-select v-model="form.tags" :items="tags" item-title="name"
                                            item-value="id" label="Tags" multiple chips variant="outlined"
                                            density="compact" :error-messages="form.errors.tags"></v-select>
                                    </v-col>
                                </v-row>

                                <VButton type="submit" :disabled="form.processing" size="default">
                                    {{ props.product ? 'Update' : 'Create' }}
                                </VButton>
                            </v-form>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </MasterLayout>
</template>
