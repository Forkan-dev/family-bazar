<script setup>
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Badge } from '@/components/ui/badge'
import { FormSelect } from '@/components/ui/form-select'
import { Separator } from '@/components/ui/separator'
import MasterLayout from '@/layouts/MasterLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { computed, watch } from 'vue'
import { UserCheck, Users } from 'lucide-vue-next'

const route = (name: string, params?: any) => {
    return (window as any).route(name, params)
}

const props = defineProps({
    users: Array,
    roles: Array,
})

const form = useForm({
    user_id: null,
    roles: [],
})

const userOptions = computed(() =>
    props.users.map(user => ({
        value: user.id,
        label: user.name
    }))
)

const roleOptions = computed(() =>
    props.roles.map(role => ({
        value: role.name,
        label: role.name
    }))
)

const selectedUser = computed(() =>
    props.users.find(u => u.id === form.user_id)
)

const submit = () => {
    form.post(route('admin.roles.assign.store'))
}

watch(() => form.user_id, (newVal) => {
    if (newVal) {
        const user = props.users.find(u => u.id === newVal)
        if (user) {
            form.roles = user.roles.map(r => r.name)
        }
    } else {
        form.roles = []
    }
})
</script>

<template>
    <MasterLayout>

        <Head title="Assign Roles" />

        <div class="max-w-4xl mx-auto">
            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center">
                        <UserCheck class="h-5 w-5 mr-2" />
                        Assign Roles to User
                    </CardTitle>
                    <CardDescription>
                        Select a user and assign appropriate roles to manage their permissions
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- User Selection -->
                            <div class="space-y-2">
                                <FormSelect v-model="form.user_id" label="Select User" placeholder="Choose a user..."
                                    :options="userOptions" :error-messages="form.errors.user_id" required />
                            </div>

                            <!-- Role Selection -->
                            <div class="space-y-2">
                                <FormSelect v-model="form.roles" label="Select Roles" placeholder="Choose roles..."
                                    :options="roleOptions" :error-messages="form.errors.roles" multiple />
                            </div>
                        </div>

                        <!-- Current User Info -->
                        <div v-if="selectedUser" class="space-y-4">
                            <Separator />
                            <div class="p-4 bg-muted/50 rounded-lg">
                                <h4 class="font-medium mb-3 flex items-center">
                                    <Users class="h-4 w-4 mr-2" />
                                    Current User Information
                                </h4>
                                <div class="space-y-2">
                                    <p class="text-sm">
                                        <span class="font-medium">Name:</span> {{ selectedUser.name }}
                                    </p>
                                    <p class="text-sm">
                                        <span class="font-medium">Email:</span> {{ selectedUser.email }}
                                    </p>
                                    <div v-if="selectedUser.roles && selectedUser.roles.length > 0" class="space-y-2">
                                        <span class="text-sm font-medium">Current Roles:</span>
                                        <div class="flex flex-wrap gap-2">
                                            <Badge v-for="role in selectedUser.roles" :key="role.id"
                                                variant="secondary">
                                                {{ role.name }}
                                            </Badge>
                                        </div>
                                    </div>
                                    <div v-else class="text-sm text-muted-foreground">
                                        No roles assigned
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Selected Roles Preview -->
                        <div v-if="form.roles.length > 0" class="space-y-4">
                            <Separator />
                            <div class="p-4 bg-primary/5 border border-primary/20 rounded-lg">
                                <h4 class="font-medium mb-3">Selected Roles</h4>
                                <div class="flex flex-wrap gap-2">
                                    <Badge v-for="roleName in form.roles" :key="roleName"
                                        class="bg-primary/10 text-primary border-primary/20">
                                        {{ roleName }}
                                    </Badge>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex justify-end pt-4">
                            <Button type="submit" :disabled="form.processing || !form.user_id" class="min-w-32">
                                {{ form.processing ? 'Assigning...' : 'Assign Roles' }}
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </MasterLayout>
</template>
