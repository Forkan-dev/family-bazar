<script setup lang="ts">
import MasterLayout from '@/layouts/MasterLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import VInputField from '@/components/VInputField.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    product: Object,
    categories: Array,
    tags: Array,
});

const form = useForm({
    name: props.product?.name || '',
    slug: props.product?.slug || '',
    description: props.product?.description || '',
    price: props.product?.price || 0,
    stock: props.product?.stock || 0,
    image_url: props.product?.image_url || '',
    categories: props.product?.categories.map((c: any) => c.id) || [],
    tags: props.product?.tags.map((t: any) => t.id) || [],
});

const submit = () => {
    if (props.product) {
        form.put(route('products.update', props.product.id));
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
                                        <VInputField v-model="form.image_url" label="Image URL"
                                            :error-messages="form.errors.image_url"></VInputField>
                                    </v-col>
                                </v-row>

                                <v-row dense>
                                    <v-col cols="12" md="6">
                                        <v-select v-model="form.categories" :items="categories" item-title="name"
                                            item-value="id" label="Categories" multiple chips variant="outlined"
                                            density="compact" :error-messages="form.errors.categories"></v-select>
                                    </v-col>
                                    <v-col cols="12" md="6">
                                        <v-select v-model="form.tags" :items="tags" item-title="name"
                                            item-value="id" label="Tags" multiple chips variant="outlined"
                                            density="compact" :error-messages="form.errors.tags"></v-select>
                                    </v-col>
                                </v-row>

                                <v-btn type="submit" color="primary" :disabled="form.processing">
                                    {{ props.product ? 'Update' : 'Create' }}
                                </v-btn>
                            </v-form>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </MasterLayout>
</template>
