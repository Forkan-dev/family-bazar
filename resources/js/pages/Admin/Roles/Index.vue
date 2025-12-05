<script setup lang="ts">
import DataTable from '@/components/ui/DataTable.vue'
import MasterLayout from '@/layouts/MasterLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { Edit, Trash2 } from 'lucide-vue-next'

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
        class: 'font-medium'
    },
    {
        key: 'guard_name',
        label: 'Guard Name',
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

        <Head title="Roles" />

        <div>
            <DataTable title="Roles" description="Manage user roles and permissions" :columns="columns"
                :data="roles.data" :actions="actions" :create-url="route('admin.roles.create')" create-text="Add Role"
                :current-page="roles.current_page" :per-page="roles.per_page" :total="roles.total"
                @search="handleSearch" @paginate="handlePagination" @sort="handleSort" />
        </div>
    </MasterLayout>
</template>
