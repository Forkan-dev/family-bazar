<script setup lang="ts">
import { VButton } from '@/components/ui/button';
import MasterLayout from '@/layouts/MasterLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

import { ref } from 'vue';

const props = defineProps({
    brands: Array,
});

const headers = [
    { title: 'EN Name', key: 'en_name' },
    { title: 'BN Name', key: 'bn_name' },
    { title: 'Slug', key: 'slug' },
    { title: 'Image', key: 'image' },
    { title: 'Actions', key: 'actions', sortable: false },
];

const search = ref('');

const deleteBrand = (id: number) => {
    if (confirm('Are you sure you want to delete this brand?')) {
        router.delete(route('product.brands.destroy', id), {
            onSuccess: () => alert('Brand deleted successfully.'),
            onError: (errors) => console.error(errors),
        });
    }
};
</script>

<template>
    <MasterLayout>
        <Head title="Brands" />

        <v-container>
            <v-row>
                <v-col cols="12">
                    <v-card>
                        <v-card-title
                            class="d-flex align-center justify-space-between"
                        >
                            Brands
                            <VButton>
                                <v-icon left>mdi-plus</v-icon>
                                <Link
                                    :href="route('product.brands.create')"
                                    class="mr-2"
                                >
                                    Add Brand
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
                                :items="brands"
                                :search="search"
                                class="elevation-1 mt-4"
                                density="compact"
                            >
                                <template v-slot:item.image="{ item }">
                                    <v-img
                                        v-if="item.image_url"
                                        :src="item.image_url"
                                        alt="Brand Image"
                                        max-width="80"
                                        max-height="50"
                                        contain
                                        @error="item.image_url = null"
                                    ></v-img>
                                    <span v-else>NA</span>
                                </template>

                                <!-- Actions Column -->
                                <template v-slot:item.actions="{ item }">
                                    <Link :href="route('product.brands.edit', item.id)">
                                        <v-icon small class="me-2"
                                            >mdi-pencil</v-icon
                                        >
                                    </Link>
                                    <v-icon small @click="deleteBrand(item.id)"
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
