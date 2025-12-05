<script setup lang="ts">
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Separator } from '@/components/ui/separator'
import VInputField from '@/components/VInputField.vue'
import MasterLayout from '@/layouts/MasterLayout.vue'
import { Head, useForm, Link } from '@inertiajs/vue3'
import { ArrowLeft, Shield } from 'lucide-vue-next'

const route = (name: string, params?: any) => {
    return (window as any).route(name, params)
}

const props = defineProps({
    permission: Object,
})

const form = useForm({
    name: props.permission?.name || '',
    guard_name: props.permission?.guard_name || 'web',
})

const submit = () => {
    if (props.permission) {
        form.put(route('admin.permissions.update', props.permission.id))
    } else {
        form.post(route('admin.permissions.store'))
    }
}
</script>

<template>
    <MasterLayout>

        <Head :title="permission ? `Edit ${permission.name}` : 'Create Permission'" />

        <!-- Header -->
        <div class="mb-8">
            <Link :href="route('admin.permissions.index')">
            <Button variant="ghost" size="sm">
                <ArrowLeft class="h-4 w-4 mr-2" />
                Back to Permissions
            </Button>
            </Link>
        </div>

        <!-- Form -->
        <div class="max-w-4xl mx-auto">
            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center">
                        <Shield class="h-5 w-5 mr-2" />
                        {{ permission ? 'Edit Permission' : 'Create Permission' }}
                    </CardTitle>
                    <CardDescription>
                        {{ permission ? 'Modify permission details' : 'Create a new system permission' }}
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <VInputField v-model="form.name" label="Permission Name" placeholder="e.g., user.create"
                                :error-messages="form.errors.name" required />
                            <VInputField v-model="form.guard_name" label="Guard Name" placeholder="web"
                                :error-messages="form.errors.guard_name" />
                        </div>

                        <Separator />

                        <!-- Form Actions -->
                        <div class="flex justify-end space-x-3">
                            <Link :href="route('admin.permissions.index')">
                            <Button type="button" variant="outline">
                                Cancel
                            </Button>
                            </Link>
                            <Button type="submit" :disabled="form.processing">
                                {{ form.processing
                                    ? (permission ? 'Updating...' : 'Creating...')
                                    : (permission ? 'Update Permission' : 'Create Permission')
                                }}
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </MasterLayout>
</template>
