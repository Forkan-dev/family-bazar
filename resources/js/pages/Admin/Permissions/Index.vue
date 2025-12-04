<script setup lang="ts">
import DataTable from '@/components/ui/DataTable.vue'
import MasterLayout from '@/layouts/MasterLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { Edit, Trash2 } from 'lucide-vue-next'

const route = (name: string, params?: any) => {
    return (window as any).route(name, params)
}

const { permissions } = defineProps<{
    permissions: {
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
        label: 'Permission Name',
        sortable: true,
        searchable: true,
        class: 'font-medium',
    },
    {
        key: 'guard_name',
        label: 'Guard Name',
    }
]

const actions = [
    {
        label: 'Edit',
        icon: Edit,
        variant: 'ghost' as const,
        action: (item: any) => {
            router.visit(route('admin.permissions.edit', item.id))
        }
    },
    {
        label: 'Delete',
        icon: Trash2,
        variant: 'destructive' as const,
        action: (item: any) => {
            if (confirm('Are you sure you want to delete this permission?')) {
                router.delete(route('admin.permissions.destroy', item.id))
            }
        }
    }
]

const handleSearch = (query: string) => {
    router.get(route('admin.permissions.index'), { search: query }, {
        preserveState: true,
        preserveScroll: true
    })
}

const handlePagination = (page: number) => {
    router.get(route('admin.permissions.index'), { page }, {
        preserveState: true,
        preserveScroll: true
    })
}

const handleSort = (column: string, direction: 'asc' | 'desc') => {
    router.get(route('admin.permissions.index'), {
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

        <Head title="Permissions" />

        <div class="p-8">
            <DataTable title="Permissions" description="Manage system permissions" :columns="columns"
                :data="permissions.data" :actions="actions" :create-url="route('admin.permissions.create')"
                create-text="Add Permission" :current-page="permissions.current_page" :per-page="permissions.per_page"
                :total="permissions.total" @search="handleSearch" @paginate="handlePagination" @sort="handleSort" />
        </div>
    </MasterLayout>
</template>
