<script setup lang="ts">
import MasterLayout from '@/layouts/MasterLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { VButton } from '@/components/ui/button';

const props = defineProps({
    products: Array,
});

const headers = [
    { title: 'Name', key: 'name' },
    { title: 'Slug', key: 'slug' },
    { title: 'Price', key: 'price' },
    { title: 'Stock', key: 'stock' },
    { title: 'Categories', key: 'categories' },
    { title: 'Tags', key: 'tags' },
    { title: 'Actions', key: 'actions', sortable: false },
];

const search = ref('');

const deleteProduct = (id: number) => {
    if (confirm('Are you sure you want to delete this product?')) {
        // Inertia.delete(route('products.destroy', id));
        console.log('Delete product with ID:', id);
    }
};
</script>

<template>
    <MasterLayout>

        <Head title="Products" />

        <v-container>
            <v-row>
                <v-col cols="12">
                    <v-card>
                        <v-card-title class="d-flex align-center justify-space-between">
                            Products
                            <Link :href="route('product.products.create')">
                            <VButton>
                                <v-icon left>mdi-plus</v-icon>
                                Add Product
                            </VButton>
                            </Link>
                        </v-card-title>

                        <v-card-text>
                            <v-text-field v-model="search" label="Search" prepend-inner-icon="mdi-magnify"
                                variant="outlined" hide-details single-line density="compact"></v-text-field>

                            <v-data-table :headers="headers" :items="products" :search="search" class="elevation-1 mt-4"
                                density="compact">
                                <template v-slot:item.categories="{ item }">
                                    <v-chip v-for="category in item.categories" :key="category.id" class="ma-1"
                                        color="primary" size="small">
                                        {{ category.name }}
                                    </v-chip>
                                </template>
                                <template v-slot:item.tags="{ item }">
                                    <v-chip v-for="tag in item.tags" :key="tag.id" class="ma-1" color="secondary"
                                        size="small">
                                        {{ tag.name }}
                                    </v-chip>
                                </template>
                                <template v-slot:item.actions="{ item }">
                                    <Link :href="route('product.products.edit', item.id)">
                                    <v-icon small class="me-2">mdi-pencil</v-icon>
                                    </Link>
                                    <v-icon small @click="deleteProduct(item.id)">mdi-delete</v-icon>
                                </template>
                            </v-data-table>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </MasterLayout>
</template>
