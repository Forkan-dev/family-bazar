<script setup lang="ts">
import DataTable from '@/components/ui/DataTable.vue'
import { Button } from '@/components/ui/button'
import { Badge } from '@/components/ui/badge'
import MasterLayout from '@/layouts/MasterLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { Edit, Trash2, Shield, Users, Plus } from 'lucide-vue-next'

const route = (name: string, params?: any) => {
    return (window as any).route(name, params)
}

const { roles } = defineProps<{
    roles: {
        data: any[]
        current_page: number
        per_page: number
        total: number
        last_page: number
        from: number
        to: number
    }
}>()

const columns = [
    {
        key: 'name',
        label: 'Role Name',
        sortable: true,
        searchable: true,
        class: 'font-medium',
        render: (item: any) => {
            return `<div class="flex items-center space-x-2">
                        <svg class="h-4 w-4 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                        <span>${item.name}</span>
                    </div>`
        }
    },
    {
        key: 'guard_name',
        label: 'Guard Name',
        render: (item: any) => `<span class="inline-flex items-center rounded-md border px-2.5 py-0.5 text-xs font-semibold">${item.guard_name}</span>`
    },
    {
        key: 'permissions_count',
        label: 'Permissions',
        render: (item: any) => {
            const count = item.permissions_count || 0
            return `${count} permission${count !== 1 ? 's' : ''}`
        }
    }
]

const actions = [
    {
        label: 'Edit',
        icon: Edit,
        variant: 'ghost' as const,
        action: (item: any) => {
            router.visit(route('admin.roles.edit', item.id))
        }
    },
    {
        label: 'Delete',
        icon: Trash2,
        variant: 'destructive' as const,
        action: (item: any) => {
            if (confirm('Are you sure you want to delete this role?')) {
                router.delete(route('admin.roles.destroy', item.id))
            }
        }
    }
]

const handleSearch = (query: string) => {
    router.get(route('admin.roles.index'), { search: query }, {
        preserveState: true,
        preserveScroll: true
    })
}

const handlePagination = (page: number) => {
    router.get(route('admin.roles.index'), { page }, {
        preserveState: true,
        preserveScroll: true
    })
}

const handleSort = (column: string, direction: 'asc' | 'desc') => {
    router.get(route('admin.roles.index'), {
        sort: column,
        direction
    }, {
        preserveState: true,
        preserveScroll: true
    })
}
</script>

<template>
    <MasterLayout>

        <Head title="Roles Management" />

        <div class="space-y-6">
            <!-- Header with additional actions -->
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold flex items-center">
                        <Shield class="h-8 w-8 mr-3" />
                        Roles Management
                    </h1>
                    <p class="text-muted-foreground mt-2">
                        Manage user roles and their permissions
                    </p>
                </div>
                <Link :href="route('admin.roles.assign.create')">
                <Button variant="outline">
                    <Users class="h-4 w-4 mr-2" />
                    Assign Roles
                </Button>
                </Link>
            </div>

            <!-- DataTable -->
            <DataTable title="Roles" description="Manage user roles and permissions" :columns="columns"
                :data="roles.data" :actions="actions" :create-url="route('admin.roles.create')" create-text="Add Role"
                :current-page="roles.current_page" :per-page="roles.per_page" :total="roles.total"
                @search="handleSearch" @paginate="handlePagination" @sort="handleSort" />
        </div>
    </MasterLayout>
</template>
