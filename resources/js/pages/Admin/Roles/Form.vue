<script setup lang="ts">
import MasterLayout from '@/layouts/MasterLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import VInputField from '@/components/VInputField.vue';
import { VButton } from '@/components/ui/button';

const props = defineProps({
    role: Object, // Assuming a role object will be passed for editing
    permissions: Array, // Assuming a list of all permissions will be passed
});

const form = useForm({
    name: props.role?.name || '',
    guard_name: props.role?.guard_name || 'web', // Default guard name
    selectedPermissions: props.role?.permissions.map(p => p.id) || [], // Array of permission IDs
});

const submit = () => {
    if (props.role) {
        form.put(route('admin.roles.update', props.role.id));
    } else {
        form.post(route('admin.roles.store'));
    }
};
</script>

<template>
    <MasterLayout>
        <Head :title="form.name ? form.name : 'Create Role'" />
        <v-container>
            <v-row>
                <v-col cols="12">
                    <Link :href="route('admin.roles.index')" class="mb-4 d-inline-block">
                        <v-icon color="primary">mdi-arrow-left</v-icon>
                    </Link>
                    <v-card>
                        <v-card-title class="d-flex align-center">
                            <v-icon class="mr-3" color="primary">
                                {{ props.role ? 'mdi-pencil' : 'mdi-plus' }}
                            </v-icon>
                            {{ props.role ? 'Edit Role' : 'Create Role' }}
                        </v-card-title>
                        <v-divider></v-divider>
                        <v-card-text class="mt-5">
                            <v-form @submit.prevent="submit">
                                <v-row dense>
                                    <v-col cols="12" sm="6">
                                        <VInputField v-model="form.name" label="Role Name"
                                            :error-messages="form.errors.name" required density="compact"
                                            variant="outlined" />
                                    </v-col>
                                    <v-col cols="12" sm="6">
                                        <VInputField v-model="form.guard_name" label="Guard Name"
                                            :error-messages="form.errors.guard_name" density="compact"
                                            variant="outlined" />
                                    </v-col>
                                    <v-col cols="12">
                                        <v-select
                                            v-model="form.selectedPermissions"
                                            :items="permissions"
                                            item-title="name"
                                            item-value="id"
                                            label="Permissions"
                                            multiple
                                            chips
                                            variant="outlined"
                                            density="compact"
                                            :error-messages="form.errors.selectedPermissions"
                                            prepend-inner-icon="mdi-security"
                                            closable-chips
                                        ></v-select>
                                    </v-col>
                                </v-row>
                                <v-card-actions class="pa-4">
                                    <div class="d-flex gap-3">
                                        <VButton type="submit" :disabled="form.processing" :loading="form.processing">
                                            <v-icon left>
                                                {{ props.role ? 'mdi-content-save' : 'mdi-plus' }}
                                            </v-icon>
                                            {{ props.role ? 'Update Role' : 'Create Role' }}
                                        </VButton>

                                        <Link :href="route('admin.roles.index')">
                                            <VButton variant="outlined" size="large" class="px-6">
                                                <v-icon left class="mr-2">mdi-close</v-icon>
                                                Cancel
                                            </VButton>
                                        </Link>
                                    </div>
                                </v-card-actions>
                            </v-form>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </MasterLayout>
</template>
