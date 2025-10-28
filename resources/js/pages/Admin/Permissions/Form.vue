<script setup lang="ts">
import MasterLayout from '@/layouts/MasterLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import VInputField from '@/components/VInputField.vue';
import { VButton } from '@/components/ui/button';

const props = defineProps({
    permission: Object, // Assuming a permission object will be passed for editing
});

const form = useForm({
    name: props.permission?.name || '',
    guard_name: props.permission?.guard_name || 'web', // Default guard name
});

const submit = () => {
    if (props.permission) {
        form.put(route('admin.permissions.update', props.permission.id));
    } else {
        form.post(route('admin.permissions.store'));
    }
};
</script>

<template>
    <MasterLayout>
        <Head :title="form.name ? form.name : 'Create Permission'" />
        <v-container>
            <v-row>
                <v-col cols="12">
                    <Link :href="route('admin.permissions.index')" class="mb-4 d-inline-block">
                        <v-icon color="primary">mdi-arrow-left</v-icon>
                    </Link>
                    <v-card>
                        <v-card-title class="d-flex align-center">
                            <v-icon class="mr-3" color="primary">
                                {{ props.permission ? 'mdi-pencil' : 'mdi-plus' }}
                            </v-icon>
                            {{ props.permission ? 'Edit Permission' : 'Create Permission' }}
                        </v-card-title>
                        <v-divider></v-divider>
                        <v-card-text class="mt-5">
                            <v-form @submit.prevent="submit">
                                <v-row dense>
                                    <v-col cols="12" sm="6">
                                        <VInputField v-model="form.name" label="Permission Name"
                                            :error-messages="form.errors.name" required density="compact"
                                            variant="outlined" />
                                    </v-col>
                                    <v-col cols="12" sm="6">
                                        <VInputField v-model="form.guard_name" label="Guard Name"
                                            :error-messages="form.errors.guard_name" density="compact"
                                            variant="outlined" />
                                    </v-col>
                                </v-row>
                                <v-card-actions class="pa-4">
                                    <div class="d-flex gap-3">
                                        <VButton type="submit" :disabled="form.processing" :loading="form.processing">
                                            <v-icon left>
                                                {{ props.permission ? 'mdi-content-save' : 'mdi-plus' }}
                                            </v-icon>
                                            {{ props.permission ? 'Update Permission' : 'Create Permission' }}
                                        </VButton>

                                        <Link :href="route('admin.permissions.index')">
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
