<script setup lang="ts">
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Checkbox } from '@/components/ui/checkbox'
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible'
import { Separator } from '@/components/ui/separator'
import VInputField from '@/components/VInputField.vue'
import MasterLayout from '@/layouts/MasterLayout.vue'
import { Head, useForm, Link } from '@inertiajs/vue3'
import { computed, ref } from 'vue'
import { ArrowLeft, Shield, ChevronRight } from 'lucide-vue-next'

const route = (name: string, params?: any) => {
    return (window as any).route(name, params)
}

const props = defineProps({
    role: Object,
    permissions: Array,
    selectedPermissions: Array,
});

const form = useForm({
    name: props.role?.name || '',
    guard_name: props.role?.guard_name || 'web',
    selectedPermissions: props.selectedPermissions || [],
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

const collapsedGroups = ref<string[]>([]);

const toggleCollapse = (group: string) => {
    const index = collapsedGroups.value.indexOf(group);
    if (index > -1) {
        collapsedGroups.value.splice(index, 1);
    } else {
        collapsedGroups.value.push(group);
    }
};

// Toggle single permission
const togglePermission = (permissionId: number, checked: boolean) => {
    if (checked) {
        if (!form.selectedPermissions.includes(permissionId)) {
            form.selectedPermissions.push(permissionId);
        }
    } else {
        form.selectedPermissions = form.selectedPermissions.filter(id => id !== permissionId);
    }
};

// Toggle all permissions in a group
const toggleGroupPermissions = (groupPermissions: any[], checked: boolean) => {
    console.log('toggleGroupPermissions called with checked:', checked);
    const permissionIds = groupPermissions.map(p => p.id);
    console.log('Permission IDs to toggle:', permissionIds);

    if (checked) {
        // Add all permissions - create new array to trigger reactivity
        const newPermissions = [...form.selectedPermissions];
        permissionIds.forEach(id => {
            if (!newPermissions.includes(id)) {
                newPermissions.push(id);
            }
        });
        form.selectedPermissions = newPermissions;
    } else {
        // Remove all permissions
        form.selectedPermissions = form.selectedPermissions.filter(id => !permissionIds.includes(id));
    }

    console.log('After toggle, selectedPermissions:', form.selectedPermissions);
};

// Check if all permissions in group are selected
const isGroupFullySelected = (groupPermissions: any[]) => {
    const permissionIds = groupPermissions.map(p => p.id);
    return permissionIds.length > 0 && permissionIds.every(id => form.selectedPermissions.includes(id));
};

// Check if some (but not all) permissions in group are selected
const isGroupPartiallySelected = (groupPermissions: any[]) => {
    const permissionIds = groupPermissions.map(p => p.id);
    const selectedCount = permissionIds.filter(id => form.selectedPermissions.includes(id)).length;
    return selectedCount > 0 && selectedCount < permissionIds.length;
};
</script>

<template>
    <MasterLayout>
        <Head :title="role ? `Edit ${role.name}` : 'Create Role'" />

        <div class="mb-8">
            <Link :href="route('admin.roles.index')">
                <Button variant="ghost" size="sm">
                    <ArrowLeft class="h-4 w-4 mr-2" />
                    Back to Roles
                </Button>
            </Link>
        </div>

        <div class="max-w-7xl mx-auto">
            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center">
                        <Shield class="h-5 w-5 mr-2" />
                        {{ role ? 'Edit Role' : 'Create Role' }}
                    </CardTitle>
                    <CardDescription>
                        {{ role ? 'Modify role permissions and settings' : 'Create a new role with specific permissions' }}
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit" class="space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <VInputField
                                v-model="form.name"
                                label="Role Name"
                                placeholder="Enter role name"
                                :error-messages="form.errors.name"
                                required
                            />
                            <VInputField
                                v-model="form.guard_name"
                                label="Guard Name"
                                placeholder="web"
                                :error-messages="form.errors.guard_name"
                            />
                        </div>

                        <Separator />

                        <div>
                            <h3 class="text-lg font-semibold mb-6 flex items-center">
                                <Shield class="h-5 w-5 mr-2" />
                                Permissions
                            </h3>

                            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                                <div v-for="(permissions, group) in groupedPermissions" :key="group">
                                    <Card class="h-fit">
                                        <Collapsible :open="!collapsedGroups.includes(group)">
                                            <CardHeader class="pb-3">
                                                <div class="flex items-center justify-between">
                                                    <div
                                                        class="flex-1 cursor-pointer hover:bg-muted/50 transition-colors rounded p-2 -m-2"
                                                        @click="toggleCollapse(group)"
                                                    >
                                                        <CardTitle class="text-base capitalize flex items-center">
                                                            {{ group }}
                                                            <span class="ml-2 text-xs bg-muted px-2 py-1 rounded">
                                                                {{ permissions.length }}
                                                            </span>
                                                        </CardTitle>
                                                    </div>
                                                    <div class="flex items-center space-x-2">
                                                        <Checkbox
                                                            :model-value="isGroupFullySelected(permissions)"
                                                            @update:model-value="(checked) => {
                                                                console.log('Parent checkbox clicked:', checked);
                                                                toggleGroupPermissions(permissions, checked);
                                                            }"
                                                            class="cursor-pointer"
                                                        />
                                                        <ChevronRight
                                                            :class="['h-4 w-4 transition-transform cursor-pointer', collapsedGroups.includes(group) ? '' : 'rotate-90']"
                                                            @click="toggleCollapse(group)"
                                                        />
                                                    </div>
                                                </div>
                                            </CardHeader>
                                            <CollapsibleContent>
                                                <CardContent class="pt-0">
                                                    <Separator class="mb-4" />
                                                    <div class="space-y-3">
                                                        <div
                                                            v-for="permission in permissions"
                                                            :key="permission.id"
                                                            class="flex items-center space-x-2"
                                                        >
                                                            <Checkbox
                                                                :id="`permission-${permission.id}`"
                                                                :model-value="form.selectedPermissions.includes(permission.id)"
                                                                @update:model-value="(checked) => togglePermission(permission.id, checked)"
                                                            />
                                                            <label
                                                                :for="`permission-${permission.id}`"
                                                                class="text-sm font-medium leading-none cursor-pointer capitalize"
                                                            >
                                                                {{ permission.name.split('.')[1]?.replace('_', ' ') || permission.name }}
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

                        <div class="flex justify-end space-x-3 pt-6">
                            <Link :href="route('admin.roles.index')">
                                <Button type="button" variant="outline">
                                    Cancel
                                </Button>
                            </Link>
                            <Button type="submit" :disabled="form.processing">
                                {{ form.processing ? (role ? 'Updating...' : 'Creating...') : (role ? 'Update Role' : 'Create Role') }}
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </MasterLayout>
</template>
