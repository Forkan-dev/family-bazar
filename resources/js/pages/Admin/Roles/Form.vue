<script setup lang="ts">
import MasterLayout from '@/layouts/MasterLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import VInputField from '@/components/VInputField.vue';
import { VButton } from '@/components/ui/button';
import { computed, ref } from 'vue';

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

const groupedPermissions = computed(() => {
    return props.permissions.reduce((acc, permission) => {
        const group = permission.name.split('.')[0];
        if (!acc[group]) {
            acc[group] = [];
        }
        acc[group].push(permission);
        return acc;
    }, {});
});

const numColumns = 3;
const columnPermissions = computed(() => {
    const groups = Object.keys(groupedPermissions.value);
    const columns = Array.from({ length: numColumns }, () => ({}));
    groups.forEach((group, index) => {
        const columnIndex = index % numColumns;
        columns[columnIndex][group] = groupedPermissions.value[group];
    });
    return columns;
});

const toggleGroup = (groupPermissions, event) => {
    const permissionIds = groupPermissions.map(p => p.id);
    if (event) {
        form.selectedPermissions = [...new Set([...form.selectedPermissions, ...permissionIds])];
    } else {
        form.selectedPermissions = form.selectedPermissions.filter(id => !permissionIds.includes(id));
    }
};

const isGroupSelected = (groupPermissions) => {
    const permissionIds = groupPermissions.map(p => p.id);
    const selectedCount = permissionIds.filter(id => form.selectedPermissions.includes(id)).length;
    if (selectedCount === 0) return false;
    if (selectedCount === permissionIds.length) return true;
    return 'indeterminate';
};

const collapsedGroups = ref([]);

const toggleCollapse = (group) => {
    if (collapsedGroups.value.includes(group)) {
        collapsedGroups.value = collapsedGroups.value.filter(g => g !== group);
    } else {
        collapsedGroups.value.push(group);
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
                                </v-row>

                                <v-divider class="my-5"></v-divider>
                                <h3 class="text-h6 mb-4">Permissions</h3>

                                <v-row>
                                    <v-col v-for="(column, colIndex) in columnPermissions" :key="colIndex" cols="12" md="4">
                                        <div v-for="(permissions, group) in column" :key="group" class="mb-4">
                                            <v-card elevation="1">
                                                <v-card-title class="d-flex align-center pa-2">
                                                    <span class="text-subtitle-1 text-capitalize flex-grow-1">{{ group }}</span>
                                                    <v-checkbox
                                                        :model-value="isGroupSelected(permissions)"
                                                        @update:modelValue="toggleGroup(permissions, $event)"
                                                        density="compact"
                                                        hide-details
                                                        class="mr-2"
                                                        :indeterminate="isGroupSelected(permissions) === 'indeterminate'"
                                                    ></v-checkbox>
                                                    <v-btn
                                                        icon
                                                        size="small"
                                                        variant="text"
                                                        @click="toggleCollapse(group)"
                                                    >
                                                        <v-icon>{{ collapsedGroups.includes(group) ? 'mdi-chevron-down' : 'mdi-chevron-up' }}</v-icon>
                                                    </v-btn>
                                                </v-card-title>
                                                <v-expand-transition>
                                                    <div v-show="!collapsedGroups.includes(group)">
                                                        <v-divider></v-divider>
                                                        <v-card-text class="pa-2">
                                                            <div v-for="permission in permissions" :key="permission.id">
                                                                <v-checkbox
                                                                    v-model="form.selectedPermissions"
                                                                    :label="permission.name.split('.')[1]"
                                                                    :value="permission.id"
                                                                    density="compact"
                                                                    hide-details
                                                                ></v-checkbox>
                                                            </div>
                                                        </v-card-text>
                                                    </div>
                                                </v-expand-transition>
                                            </v-card>
                                        </div>
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
