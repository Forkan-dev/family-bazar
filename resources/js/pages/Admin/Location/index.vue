<script setup lang="ts">
import { VButton } from '@/components/ui/button';
import MasterLayout from '@/layouts/MasterLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

import { ref } from 'vue';

const props = defineProps({
    unions: Array,
});

const headers = [
    { title: 'Union Name', key: 'name_en' },
    { title: 'Upazila Name', key: 'upazila.name_en' },
    { title: 'District Name', key: 'upazila.district.name_en' },
    { title: 'Division Name', key: 'upazila.district.division.name_en' },
    { title: 'Actions', key: 'actions', sortable: false },
];

const search = ref('');

const deleteUnion = (id: number) => {
    if (confirm('Are you sure you want to delete this union?')) {
        router.delete(route('locations.destroy', id), {
            onSuccess: () => alert('Union deleted successfully.'),
            onError: (errors) => console.error(errors),
        });
    }
};
</script>

<template>
    <MasterLayout>
        <Head title="Locations" />

        <v-container>
            <v-row>
                <v-col cols="12">
                    <v-card>
                        <v-card-title
                            class="d-flex align-center justify-space-between"
                        >
                            Locations
                            <VButton>
                                <v-icon left>mdi-plus</v-icon>
                                <Link
                                    :href="route('locations.create')"
                                    class="mr-2"
                                >
                                    Add Union
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
                                :items="unions"
                                :search="search"
                                class="elevation-1 mt-4"
                                density="compact"
                            >
                                <!-- Actions Column -->
                                <template v-slot:item.actions="{ item }">
                                    <Link
                                        :href="
                                            route('locations.edit', item.id)
                                        "
                                    >
                                        <v-icon small class="me-2"
                                            >mdi-pencil</v-icon
                                        >
                                    </Link>
                                    <v-icon
                                        small
                                        @click="deleteUnion(item.id)"
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
