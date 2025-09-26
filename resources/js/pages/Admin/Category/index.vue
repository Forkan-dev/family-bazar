<script setup lang="ts">
import { VButton } from '@/components/ui/button';
import MasterLayout from '@/layouts/MasterLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

import { ref } from 'vue';

const props = defineProps({
    categories: Array, // Inertia থেকে আসবে
});

const headers = [
    { title: 'Title (ENGLISH)', key: 'title_en' },
    { title: 'Title (BANGLA)', key: 'title_bn' },
    { title: 'Slug', key: 'slug' },
    { title: 'SubCategory', key: 'subCategory' },
    { title: 'Image', key: 'image' },
    { title: 'Actions', key: 'actions', sortable: false },
];

const search = ref('');

const deleteCategory = (id: number) => {
    if (confirm('Are you sure you want to delete this category?')) {
        router.delete(route('product.categories.destroy', id), {
            onSuccess: () => alert('Category deleted successfully.'),
            onError: (errors) => console.error(errors),
        });
    }
};
</script>

<template>
    <MasterLayout>
        <Head title="Categories" />

        <v-container>
            <v-row>
                <v-col cols="12">
                    <v-card>
                        <v-card-title
                            class="d-flex align-center justify-space-between"
                        >
                            Categories
                            <VButton>
                                <v-icon left>mdi-plus</v-icon>
                                <Link
                                    :href="route('product.categories.create')"
                                    class="mr-2"
                                >
                                    Add Category
                                </Link>
                            </VButton>
                        </v-card-title>

                        <v-card-text>
                            <v-text-field
                                v-model="search"
                                label="Search"
                                prepend-inner-icon="mdi-magnify"
                                variant="outlined"
                                hide-details
                                single-line
                                density="compact"
                            ></v-text-field>

                            <v-data-table
                                :headers="headers"
                                :items="categories"
                                :search="search"
                                class="elevation-1 mt-4"
                                density="compact"
                            >
                                <!-- SubCategory Column -->
                                <template v-slot:item.subCategory="{ item }">
                                    <span v-if="item.parent">
                                        {{ item.parent.title_en }} ({{
                                            item.parent.title_bn
                                        }}) → {{ item.title_en }} ({{
                                            item.title_bn
                                        }})
                                    </span>
                                    <span v-else> </span>
                                </template>

                                <template v-slot:item.image="{ item }">
                                    <v-img
                                        v-if="item.image"
                                        :src="`/${item.image}`"
                                        alt="Category Image"
                                        max-width="80"
                                        max-height="50"
                                        contain
                                    ></v-img>
                                    <span v-else>NA</span>
                                </template>

                                <!-- Actions Column -->
                                <template v-slot:item.actions="{ item }">
                                    <Link
                                        :href="
                                            route('product.categories.edit', item.id)
                                        "
                                    >
                                        <v-icon small class="me-2"
                                            >mdi-pencil</v-icon
                                        >
                                    </Link>
                                    <v-icon
                                        small
                                        @click="deleteCategory(item.id)"
                                        >mdi-delete</v-icon
                                    >
                                </template>
                            </v-data-table>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </MasterLayout>
</template>
