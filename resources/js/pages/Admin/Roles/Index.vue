<script setup lang="ts">
import MasterLayout from '@/layouts/MasterLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { VButton } from '@/components/ui/button';

const props = defineProps({
    roles: Array, // Assuming roles will be passed as a prop
});

const headers = [
    { title: 'Name', key: 'name' },
    { title: 'Guard Name', key: 'guard_name' },
    { title: 'Actions', key: 'actions', sortable: false },
];

const search = ref('');

const deleteRole = (id: number) => {
    if (confirm('Are you sure you want to delete this role?')) {
        // Inertia.delete(route('roles.destroy', id));
        console.log('Delete role with ID:', id);
    }
};
</script>

<template>
    <MasterLayout>
        <Head title="Roles" />

        <v-container>
            <v-row>
                <v-col cols="12">
                    <v-card>
                        <v-card-title class="d-flex align-center justify-space-between">
                            Roles
                            <Link :href="route('admin.roles.create')">
                                <VButton>
                                    <v-icon left>mdi-plus</v-icon>
                                    Add Role
                                </VButton>
                            </Link>
                        </v-card-title>

                        <v-card-text>
                            <v-text-field v-model="search" label="Search" prepend-inner-icon="mdi-magnify"
                                variant="outlined" hide-details single-line density="compact"></v-text-field>

                            <v-data-table :headers="headers" :items="roles" :search="search" class="elevation-1 mt-4"
                                density="compact">
                                <template v-slot:item.actions="{ item }">
                                    <Link :href="route('admin.roles.edit', item.id)">
                                        <v-icon small class="me-2">mdi-pencil</v-icon>
                                    </Link>
                                    <v-icon small @click="deleteRole(item.id)">mdi-delete</v-icon>
                                </template>
                            </v-data-table>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </MasterLayout>
</template>
