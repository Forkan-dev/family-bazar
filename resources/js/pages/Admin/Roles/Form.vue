<script setup lang="ts">
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Checkbox } from '@/components/ui/checkbox'
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible'
import { Separator } from '@/components/ui/separator'
import VInputField from '@/components/VInputField.vue'
import MasterLayout from '@/layouts/MasterLayout.vue'
import { Head, useForm, Link } from '@inertiajs/vue3'
import { computed, ref, nextTick } from 'vue'
import { ArrowLeft, ChevronDown, ChevronRight, Shield } from 'lucide-vue-next'

const route = (name: string, params?: any) => {
    return (window as any).route(name, params)
}

const props = defineProps({
    role: Object, // Assuming a role object will be passed for editing
    permissions: Array, // Assuming a list of all permissions will be passed
});

const form = useForm({
    name: props.role?.name || '',
    guard_name: props.role?.guard_name || 'web', // Default guard name
    selectedPermissions: props.role?.permissions?.map(p => p.id) || [], // Array of permission IDs
});

// Force re-render key for checkboxes
const checkboxKey = ref(0);

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

const toggleGroup = (groupPermissions, checked) => {
    const permissionIds = groupPermissions.map(p => p.id);
    console.log('Toggle group called:', { checked, permissionIds });

    if (checked) {
        // Add all group permissions
        permissionIds.forEach(id => {
            if (!form.selectedPermissions.includes(id)) {
                form.selectedPermissions.push(id);
            }
        });
    } else {
        // Remove all group permissions
        permissionIds.forEach(id => {
            const index = form.selectedPermissions.indexOf(id);
            if (index > -1) {
                form.selectedPermissions.splice(index, 1);
            }
        });
    }

    console.log('Updated permissions:', form.selectedPermissions);
    checkboxKey.value++; // Force re-render
}; const isGroupSelected = (groupPermissions) => {
    const permissionIds = groupPermissions.map(p => p.id);
    const selectedCount = permissionIds.filter(id => form.selectedPermissions.includes(id)).length;
    if (selectedCount === 0) return false;
    if (selectedCount === permissionIds.length) return true;
    return 'indeterminate';
};

const getGroupCheckboxProps = (groupPermissions) => {
    const selectionState = isGroupSelected(groupPermissions);
    return {
        checked: selectionState === true,
        indeterminate: selectionState === 'indeterminate'
    };
};

const collapsedGroups = ref([]);

const isGroupOpen = (group) => {
    return !collapsedGroups.value.includes(group);
};

const toggleCollapse = (group) => {
    if (collapsedGroups.value.includes(group)) {
        collapsedGroups.value = collapsedGroups.value.filter(g => g !== group);
    } else {
        collapsedGroups.value.push(group);
    }
};

const togglePermission = (permissionId, checked) => {
    console.log('Toggle permission called:', { permissionId, checked });

    if (checked) {
        if (!form.selectedPermissions.includes(permissionId)) {
            form.selectedPermissions.push(permissionId);
        }
    } else {
        const index = form.selectedPermissions.indexOf(permissionId);
        if (index > -1) {
            form.selectedPermissions.splice(index, 1);
        }
    }

    console.log('Updated permissions:', form.selectedPermissions);
    checkboxKey.value++; // Force re-render
};</script>

<template>
    <MasterLayout>

        <Head :title="role ? `Edit ${role.name}` : 'Create Role'" />

        <!-- Header -->
        <div class="mb-8">
            <Link :href="route('admin.roles.index')">
            <Button variant="ghost" size="sm">
                <ArrowLeft class="h-4 w-4 mr-2" />
                Back to Roles
            </Button>
            </Link>
        </div>

        <!-- Form -->
        <div class="max-w-7xl mx-auto">
            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center">
                        <Shield class="h-5 w-5 mr-2" />
                        {{ role ? 'Edit Role' : 'Create Role' }}
                    </CardTitle>
                    <CardDescription>
                        {{ role ? 'Modify role permissions and settings' : 'Create a new role with specific permissions'
                        }}
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit" class="space-y-8">
                        <!-- Basic Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <VInputField v-model="form.name" label="Role Name" placeholder="Enter role name"
                                :error-messages="form.errors.name" required />
                            <VInputField v-model="form.guard_name" label="Guard Name" placeholder="web"
                                :error-messages="form.errors.guard_name" />
                        </div>

                        <Separator />

                        <!-- Permissions Section -->
                        <div>
                            <h3 class="text-lg font-semibold mb-6 flex items-center">
                                <Shield class="h-5 w-5 mr-2" />
                                Permissions
                            </h3>

                            <!-- Permission Grid -->
                            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                                <div v-for="(permissions, group) in groupedPermissions" :key="group">
                                    <Card class="h-fit">
                                        <Collapsible :open="isGroupOpen(group)">
                                            <CardHeader class="pb-3">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex-1 cursor-pointer hover:bg-muted/50 transition-colors rounded p-2 -m-2"
                                                        @click="toggleCollapse(group)">
                                                        <CardTitle class="text-base capitalize flex items-center">
                                                            {{ group }}
                                                            <span class="ml-2 text-xs bg-muted px-2 py-1 rounded">
                                                                {{ permissions.length }}
                                                            </span>
                                                        </CardTitle>
                                                    </div>
                                                    <div class="flex items-center space-x-2">
                                                        <div @click.stop="() => { }">
                                                            <Checkbox :key="`group-${group}-${checkboxKey}`"
                                                                :checked="isGroupSelected(permissions) === true"
                                                                @update:checked="(checked) => { console.log('Group checkbox update:checked fired', checked); toggleGroup(permissions, checked); }"
                                                                @click.stop="() => { }"
                                                                class="data-[state=checked]:bg-primary cursor-pointer" />
                                                        </div>
                                                        <ChevronRight :class="{
                                                            'h-4 w-4 transition-transform cursor-pointer': true,
                                                            'rotate-90': isGroupOpen(group)
                                                        }" @click="toggleCollapse(group)" />
                                                    </div>
                                                </div>
                                            </CardHeader>
                                            <CollapsibleContent>
                                                <CardContent class="pt-0">
                                                    <Separator class="mb-4" />
                                                    <div class="space-y-3">
                                                        <div v-for="permission in permissions" :key="permission.id"
                                                            class="flex items-center space-x-2">
                                                            <Checkbox :id="`permission-${permission.id}`"
                                                                :key="`${permission.id}-${checkboxKey}`"
                                                                :checked="form.selectedPermissions.includes(permission.id)"
                                                                @update:checked="(checked) => togglePermission(permission.id, checked)" />
                                                            <label :for="`permission-${permission.id}`"
                                                                class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 capitalize cursor-pointer">
                                                                {{ permission.name.split('.')[1]?.replace('_', ' ') ||
                                                                    permission.name }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </CardContent>
                                            </CollapsibleContent>
                                        </Collapsible>
                                    </Card>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex justify-end space-x-3 pt-6">
                            <Link :href="route('admin.roles.index')">
                            <Button type="button" variant="outline">
                                Cancel
                            </Button>
                            </Link>
                            <Button type="submit" :disabled="form.processing">
                                {{ form.processing
                                    ? (role ? 'Updating...' : 'Creating...')
                                    : (role ? 'Update Role' : 'Create Role')
                                }}
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </MasterLayout>
</template>
