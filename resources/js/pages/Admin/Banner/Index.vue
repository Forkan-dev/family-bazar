<script setup lang="ts">
import { VButton } from '@/components/ui/button';
import MasterLayout from '@/layouts/MasterLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

import { ref } from 'vue';

const props = defineProps({
    banners: Array, // Inertia from controller
});

const headers = [
    { title: 'Title', key: 'title' },
    { title: 'Description', key: 'description' },
    { title: 'Image', key: 'image' },
    { title: 'Type', key: 'type' },
    { title: 'Actions', key: 'actions', sortable: false },
];

const search = ref('');

const deleteBanner = (id: number) => {
    if (confirm('Are you sure you want to delete this banner?')) {
        router.delete(route('admin.banners.destroy', id), {
            onSuccess: () => alert('Banner deleted successfully.'),
            onError: (errors) => console.error(errors),
        });
    }
};
</script>

<template>
    <MasterLayout>
        <Head title="Banners" />

        <v-container>
            <v-row>
                <v-col cols="12">
                    <v-card>
                        <v-card-title
                            class="d-flex align-center justify-space-between"
                        >
                            Banners
                            <VButton>
                                <v-icon left>mdi-plus</v-icon>
                                <Link
                                    :href="route('admin.banners.create')"
                                    class="mr-2"
                                >
                                    Add Banner
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
                                :items="banners"
                                :search="search"
                                class="elevation-1 mt-4"
                                density="compact"
                            >
                                <template v-slot:item.type="{ item }">
                                    <span v-if="item.type">
                                        {{ item.type.name }}
                                    </span>
                                    <span v-else>N/A</span>
                                </template>

                                <template v-slot:item.image="{ item }">
                                    <v-img
                                        v-if="item.image"
                                        :src="`/${item.image}`"
                                        alt="Banner Image"
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
                                            route('admin.banners.edit', item.id)
                                        "
                                    >
                                        <v-icon small class="me-2"
                                            >mdi-pencil</v-icon
                                        >
                                    </Link>
                                    <v-icon
                                        small
                                        @click="deleteBanner(item.id)"
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
